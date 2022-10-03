<?php

namespace Src\Http\Controllers\SystemWorker;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Src\Http\Controllers\Controller;
use Src\Repositories\SystemWorker\SystemWorkerContract;
use Src\Services\Complaint\ComplaintServiceContract;
use Src\Services\Worker\WorkerServiceContract;

class ProfileController extends Controller
{
    /**
     * @var WorkerServiceContract
     */
    protected WorkerServiceContract $systemWorkerServiceContract;

    /**
     * @var ComplaintServiceContract
     */
    protected ComplaintServiceContract $complaintServiceContract;

    /**
     * ProfileController constructor.
     * @param WorkerServiceContract $systemWorkerServiceContract
     * @param ComplaintServiceContract $complaintServiceContract
     */
    public function __construct(
        WorkerServiceContract $systemWorkerServiceContract,
        ComplaintServiceContract $complaintServiceContract
    )
    {
        $this->systemWorkerServiceContract = $systemWorkerServiceContract;
        $this->complaintServiceContract = $complaintServiceContract;
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $user = $this->systemWorkerServiceContract->getProfileWorker(USER_ID);
        return view('system-worker.profile.index', ['user' => $user]);
    }

    /**
     * @param  Request  $request
     * @return Application|ResponseFactory|Response
     */
    public function update(Request $request)
    {
        $user = $this->systemWorkerServiceContract->updateProfile($request);

        return $user
            ? response(['message' => trans('messages.profile_info_updated'), 'user' => $user])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param $worker_id
     * @return Application|Factory|View
     */
    public function viewProfile($worker_id)
    {
        $user = $this->systemWorkerServiceContract->getProfileWorker($worker_id);
        if (!$user){
            return view('errors.404');
        }
        return view('system-worker.profile.index', ['user' => $user]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function complaints(Request $request)
    {
        return $this->complaintServiceContract->paginate($request, USER_ID);
    }
}
