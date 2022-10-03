<?php

declare(strict_types=1);

namespace Src\Http\Controllers\SystemWorker;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use JsonException;
use Src\Http\Controllers\Controller;
use Src\Services\OrderFeedback\OrderFeedbackService;

/**
 * Class FeedbackController
 * @package Src\Http\Controllers\SystemWorker
 */
class FeedbackController extends Controller
{

    /**
     * @var OrderFeedbackService
     */
    protected OrderFeedbackService $feedbackService;

    /**
     * OrderComplaintController constructor.
     * @param  OrderFeedbackService  $feedbackService
     */
    public function __construct(OrderFeedbackService $feedbackService)
    {
        $this->feedbackService = $feedbackService;
    }

    /**
     * @return Application|Factory|View
     * @throws JsonException
     */
    public function index()
    {
        return view('system-worker.feedback.index', [
            'orderStatuses' => json_encode($this->feedbackService->getOrderStatuses(), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            'types' => json_encode($this->feedbackService->getTypes(), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            'writers' => json_encode($this->feedbackService->getWriters(), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)
        ]);
    }

    /**
     * @param  Request  $request
     * @return Application|ResponseFactory|Response
     */
    public function paginate(Request $request)
    {
        return response($this->feedbackService->complaintFranchisePaginate($request));
    }
}
