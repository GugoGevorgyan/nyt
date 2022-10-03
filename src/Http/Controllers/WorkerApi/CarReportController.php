<?php

declare(strict_types=1);

namespace Src\Http\Controllers\WorkerApi;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\CarReport\ChangeWorkerInfoRequest;
use Src\Http\Requests\CarReport\ReportCreateRequest;
use Src\Http\Resources\Worker\Mechanic\ReportQuestionsResource;
use Src\Repositories\CarReportQuestion\CarReportQuestionContract;
use Src\Services\Car\CarServiceContract;
use Src\Services\Worker\WorkerServiceContract;

/**
 * Class CarReportController
 * @package Src\Http\Controllers\Mechanic
 */
class CarReportController extends Controller
{
    /**
     * @var CarServiceContract
     */
    protected CarServiceContract $carService;
    /**
     * @var CarReportQuestionContract
     */
    protected CarReportQuestionContract $carQuestionContract;
    /**
     * @var WorkerServiceContract
     */
    protected WorkerServiceContract $workerService;

    /**
     * CarReportController constructor.
     * @param  CarServiceContract  $carReportService
     * @param  CarReportQuestionContract  $carReportQuestionContract
     * @param  WorkerServiceContract  $systemWorkerService
     */
    public function __construct(
        CarServiceContract $carReportService,
        CarReportQuestionContract $carReportQuestionContract,
        WorkerServiceContract $systemWorkerService
    ) {
        $this->carService = $carReportService;
        $this->carQuestionContract = $carReportQuestionContract;
        $this->workerService = $systemWorkerService;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function getQuestions(): AnonymousResourceCollection
    {
        $questions = $this->carQuestionContract->findAll();

        return ReportQuestionsResource::collection($questions);
    }

    /**
     * @param  ReportCreateRequest  $request
     * @return ResponseFactory|Response
     */
    public function report(ReportCreateRequest $request)
    {
        $result = $this->workerService->report($request->waybill_number, $request->data, $request->question, $request->file('images'));

        if (!$result) {
            return response(['message' => 'non printed', 'status' => 'FAIL'], 400);
        }

        return response(['message' => 'print', 'status' => 'OK']);
    }

    /**
     * @param  ChangeWorkerInfoRequest  $request
     * @return ResponseFactory|Response
     */
    public function workerInfo(ChangeWorkerInfoRequest $request)
    {
        $result = $this->carService->updateInfo($request->validated());

        if ($result) {
            return response('Your information updated successful', 200);
        }

        return response('Something went wrong', 400);
    }
}
