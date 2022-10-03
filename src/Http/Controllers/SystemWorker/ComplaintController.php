<?php

declare(strict_types=1);

namespace Src\Http\Controllers\SystemWorker;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\SystemWorker\ComplaintCommentCreateRequest;
use Src\Http\Requests\SystemWorker\ComplaintCreateRequest;
use Src\Http\Requests\SystemWorker\ComplaintStatusUpdateRequest;
use Src\Repositories\SystemWorker\SystemWorkerContract;
use Src\Services\Complaint\ComplaintServiceContract;

/**
 * Class ComplaintController
 * @package Src\Http\Controllers\SystemWorker
 */
class ComplaintController extends Controller
{
    /**
     * @var ComplaintServiceContract
     */
    protected ComplaintServiceContract $complaintService;

    /**
     * @var SystemWorkerContract
     */
    protected SystemWorkerContract $systemWorkerContract;

    /**
     * ComplaintController constructor.
     * @param  ComplaintServiceContract  $complaintService
     * @param  SystemWorkerContract  $systemWorkerContract
     */
    public function __construct(
        ComplaintServiceContract $complaintService,
        SystemWorkerContract $systemWorkerContract
    ) {
        $this->complaintService = $complaintService;
        $this->systemWorkerContract = $systemWorkerContract;
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $statuses = $this->complaintService->getStatuses();
        return view('system-worker.complaint.index', ['statuses' => $statuses]);
    }

    /**
     * @param  Request  $request
     * @return Application|ResponseFactory|Response
     */
    public function paginate(Request $request)
    {
        return response($this->complaintService->paginate($request));
    }

    /**
     * @param  Request  $request
     * @param $complaint_id
     * @return Application|ResponseFactory|Response
     */
    public function comments(Request $request, $complaint_id)
    {
        return response($this->complaintService->complaintComments($request, $complaint_id));
    }

    /**
     * @param  ComplaintCommentCreateRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function commentCreate(ComplaintCommentCreateRequest $request)
    {
        $comment = $this->complaintService->commentCreate($request);

        return $comment
            ? response(['comment' => $comment])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  ComplaintStatusUpdateRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function statusUpdate(ComplaintStatusUpdateRequest $request)
    {
        $status = $this->complaintService->updateStatus($request);

        return $status ?
            response(['status' => $status], 200) :
            response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function getWorkers()
    {
        return response($this->systemWorkerContract
            ->where('system_worker_id', '<>', USER_ID)
            ->where('franchise_id', '=', FRANCHISE_ID)
            ->findAll());
    }

    /**
     * @param  ComplaintCreateRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function store(ComplaintCreateRequest $request): Response|Application|ResponseFactory
    {
        return $this->complaintService->complaintCreate($request)
            ? response(['message' => trans('messages.complaint_created')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }
}
