<?php

declare(strict_types=1);


namespace Src\Services\Complaint;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use ServiceEntity\BaseService;
use Src\Models\Complaint\Complaint;
use Src\Repositories\Complaint\ComplaintContract;
use Src\Repositories\ComplaintComment\ComplaintCommentContract;
use Src\Repositories\ComplaintCommentFile\ComplaintCommentFileContract;
use Src\Repositories\ComplaintFile\ComplaintFileContract;
use Src\Repositories\ComplaintStatus\ComplaintStatusContract;

/**
 * Class ComplaintService
 * @package Src\Services\Complaint
 */
class ComplaintService extends BaseService implements ComplaintServiceContract
{
    /**
     * ComplaintService constructor.
     * @param  ComplaintContract  $complaintContract
     * @param  ComplaintStatusContract  $complaintStatusContract
     * @param  ComplaintCommentContract  $complaintCommentContract
     * @param  ComplaintFileContract  $complaintFileContract
     * @param  ComplaintCommentFileContract  $complaintCommentFileContract
     */
    public function __construct(
        protected ComplaintContract $complaintContract,
        protected ComplaintStatusContract $complaintStatusContract,
        protected ComplaintCommentContract $complaintCommentContract,
        protected ComplaintFileContract $complaintFileContract,
        protected ComplaintCommentFileContract $complaintCommentFileContract
    ) {
    }

    /**
     * @return Collection
     */
    public function getStatuses(): Collection
    {
        return $this->complaintStatusContract->findAll();
    }

    /**
     * @inheritDoc
     */
    public function paginate($request, $worker_id = null): LengthAwarePaginator
    {
        $per_page = $request['per_page'] && is_numeric($request['per_page']) ? $request['per_page'] : 25;
        $page = $request['page'] && is_numeric($request['page']) ? $request['page'] : 1;
        $search = ($request['search'] && 'null' !== $request['search']) ? $request['search'] : '';
        $status = $request['status'] && 'null' !== $request['status'] && is_numeric($request['status']) ? $request['status'] : null;

        $req = $this->complaintContract->where('franchise_id', '=', FRANCHISE_ID);

        if ($worker_id) {
            $req->where('recipient_id', '=', $worker_id)
                ->orWhere('writer_id', '=', $worker_id);
        }

        if ($search) {
            $searchWords = explode(' ', $search);
            $req->where(
                function ($q) use ($search, $searchWords) {
                    return $q->whereHas(
                        'writer',
                        function ($q) use ($search, $searchWords) {
                            $q->where('name', 'LIKE', '%'.$search.'%')
                                ->orWhere('surname', 'LIKE', '%'.$search.'%')
                                ->orWhere('patronymic', 'LIKE', '%'.$search.'%');

                            foreach ($searchWords as $word) {
                                $q->orWhere('name', 'LIKE', '%'.$word.'%')
                                    ->orWhere('surname', 'LIKE', '%'.$word.'%')
                                    ->orWhere('patronymic', 'LIKE', '%'.$word.'%');
                            }
                        }
                    )
                        ->orWhereHas(
                            'recipient',
                            function ($q) use ($search, $searchWords) {
                                $q->where('name', 'LIKE', '%'.$search.'%')
                                    ->orWhere('surname', 'LIKE', '%'.$search.'%')
                                    ->orWhere('patronymic', 'LIKE', '%'.$search.'%');

                                foreach ($searchWords as $word) {
                                    $q->orWhere('name', 'LIKE', '%'.$word.'%')
                                        ->orWhere('surname', 'LIKE', '%'.$word.'%')
                                        ->orWhere('patronymic', 'LIKE', '%'.$word.'%');
                                }
                            }
                        );
                }
            )
                ->orWhere('complaint', '=', $search)
                ->orWhere('subject', '=', $search);
        }
        if ($status) {
            $req = $req->where('status_id', $status);
        }

        return $req
            ->with([
                'writer',
                'recipient',
                'status',
                'files',
                'order' => fn($q) => $q->with($this->complaintOrderRelations())
            ])
            ->orderBy('complaint_id', 'desc')
            ->paginate($per_page, ['*'], 'page', $page);
    }

    /**
     * @return array
     */
    protected function complaintOrderRelations(): array
    {
        return [
            'carClass',
            'meet',
            'car_options',
            'passenger',
            'paymentType',
            'customer',
            'completed' => fn($q) => $q->with(['driver.driver_info', 'car.park']),
            'canceled' => fn($q) => $q->with(['driver.driver_info', 'car.park']),
            'current_shipped' => fn($q) => $q->with(['driver.driver_info', 'driver.car.park', 'status']),
            'client' => fn($q) => $q->withCount([
                'orders as completed_orders_count' => fn($q) => $q->whereHas('completed'),
                'orders as canceled_orders_count' => fn($q) => $q->whereHas('canceled'),
            ])
        ];
    }

    /**
     * @param $request
     * @param $complaint_id
     * @return LengthAwarePaginator
     */
    public function complaintComments($request, $complaint_id): LengthAwarePaginator
    {
        $per_page = 10;
        $page = isset($request['page']) && $request['page'] && is_numeric($request['page']) ? $request['page'] : 1;

        return $this->complaintCommentContract
            ->whereHas('complaint', fn($q) => $q->where('complaint_id', '=', $complaint_id)->where('franchise_id', '=', FRANCHISE_ID))
            ->with(['files', 'worker'])
            ->orderBy('complaint_comment_id', 'desc')
            ->paginate($per_page, ['*'], 'page', $page);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function commentCreate($request): ?object
    {
        $comment = $this->complaintCommentContract->beginTransaction(function () use ($request) {
            $this->complaintCommentContract->forgetCache();
            $comment = $this->complaintCommentContract->create([
                'complaint_id' => $request->complaint_id,
                'text' => $request->text,
                'worker_id' => user()->{user()->getKeyName()}
            ]);

            if (!$comment) {
                return null;
            }

            if ($request->hasFile('files')) {
                foreach ($request->only('files')['files'] as $file) {
                    if (!$this->createCommentFile($comment, $file)) {
                        return null;
                    }
                }
            }

            return $comment;
        });

        return $comment ? $comment->load(['files', 'worker']) : null;
    }

    /**
     * @param $comment
     * @param $file
     * @return object|null
     */
    protected function createCommentFile($comment, $file): ?object
    {
        $uploadedName = $this->saveComplaintFile($file);

        if (!$uploadedName) {
            return null;
        }

        return $this->complaintCommentFileContract->create(['complaint_comment_id' => $comment->complaint_comment_id, 'file' => $uploadedName]);
    }

    /**
     * @param $file
     * @return false|string
     */
    protected function saveComplaintFile($file)
    {
        try {
            $path = storage_path('public'.DS.'complaints'.DS);
            $uploaded = $this->fileUpload($file, $path);

            return $uploaded ? '/storage/complaints/'.$uploaded : false;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param $request
     * @return mixed
     */
    public function updateStatus($request)
    {
        $complaint = $this->complaintContract
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->find($request->complaint_id);

        return $complaint && $complaint->update(['status_id' => $request->status_id]) ? $complaint->load('status')->status : false;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function complaintCreate($request): bool
    {
        return $this->complaintContract->beginTransaction(function () use ($request) {
            $this->complaintContract->forgetCache();

            $complaint = $this->complaintContract->create([
                'subject' => $request->subject,
                'complaint' => $request->complaint,
                'recipient_id' => $request->recipient_id,
                'writer_id' => USER_ID,
                'order_id' => $request->order_id,
                'franchise_id' => FRANCHISE_ID,
                'status_id' => Complaint::COMPLAINT_STATUS_NEW,
            ]);

            if ($request->hasFile('files')) {
                foreach ($request->only('files')['files'] as $file) {
                    if (!$this->createComplaintFile($complaint, $file)) {
                        return false;
                    }
                }
            }

            return true;
        });
    }

    /**
     * @param $complaint
     * @param $file
     * @return object|null
     */
    protected function createComplaintFile($complaint, $file): ?object
    {
        $uploadedName = $this->saveComplaintFile($file);

        if (!$uploadedName) {
            return null;
        }

        $this->complaintFileContract->forgetCache();

        return $this->complaintFileContract->create(['complaint_id' => $complaint->complaint_id, 'file' => $uploadedName]);
    }
}
