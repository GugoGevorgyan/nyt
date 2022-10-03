<?php

declare(strict_types=1);

namespace Src\Http\Controllers\SystemWorker;

use Collective\Annotations\Routing\Annotations\Annotations\Get;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\SystemWorker\BookkeepingCompanyOrdersPaginateRequest;
use Src\Http\Requests\SystemWorker\BookkeepingPaginateRequest;
use Src\Http\Requests\SystemWorker\CreateTransactionRequest;
use Src\Http\Requests\SystemWorker\PrintDownloadTransactionRequest;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\PaginateResource;
use Src\Http\Resources\Worker\Bookkeeping\BookkeepingData;
use Src\Http\Resources\Worker\Bookkeeping\BookkeepingDriversResource;
use Src\Http\Resources\Worker\Bookkeeping\CompanyOrdersPaginateResource;
use Src\Http\Resources\Worker\Bookkeeping\CompanyOrdersReportResource;
use Src\Http\Resources\Worker\Bookkeeping\TransactionDetailsResource;
use Src\Services\Driver\DriverServiceContract;
use Src\Services\Worker\WorkerServiceContract;
use Src\ServicesCrud\Driver\DriverCrudContract;

/**
 * Class AccountingDepartmentController
 * @package Src\Http\Controllers\SystemWorker
 */
class BookkeepingController extends Controller
{
    /**
     * AccountingDepartmentController constructor.
     * @param  DriverCrudContract  $driverCrudContract
     * @param  WorkerServiceContract  $workerService
     * @param  DriverServiceContract  $driverService
     */
    public function __construct(
        protected DriverCrudContract $driverCrudContract,
        protected WorkerServiceContract $workerService,
        protected DriverServiceContract $driverService
    ) {
    }

    /**
     * @return Application|Factory|View
     */
    public function index(): Factory|View|Application
    {
        return view('system-worker.bookkeeping.index', $this->workerService->getBookkeepingProps());
    }

    /**
     * @return Application|Factory|View
     */
    public function redirectCompanies(): Factory|View|Application
    {
        return view('system-worker.bookkeeping.companies.index', $this->workerService->getBookkeepingProps());
    }

    /**
     * @return Application|Factory|View
     */
    public function redirectDrivers(): Factory|View|Application
    {
        return view('system-worker.bookkeeping.drivers.index');
    }

    /**
     * @param  BookkeepingPaginateRequest  $request
     * @return PaginateResource
     */
    public function bookkeepingPaginate(BookkeepingPaginateRequest $request): PaginateResource
    {
        $data = $this->workerService->bookkeepingPaginate($request);

        return (new PaginateResource($data))->collectionClass(BookkeepingData::class);
    }

    /**
     * @param  int  $transaction_id
     * @return TransactionDetailsResource
     */
    public function bookkeepingDetails(int $transaction_id): TransactionDetailsResource
    {
        $data = $this->workerService->bookkeepingTransactionInfo($transaction_id);

        return new TransactionDetailsResource($data);
    }

    /**
     * @param  CreateTransactionRequest  $request
     * @return Response
     */
    public function addTransaction(CreateTransactionRequest $request): Response
    {
        $result = $this->workerService->workerCreateTransaction(
            $request->type,
            $request->side,
            $request->sum,
            $request->input,
            $request->comment
        );

        return $result['success'] ? response($result, 200) : response($result, 500);
    }

    /**
     * @param  PrintDownloadTransactionRequest  $request
     * @throws Exception
     */
    public function printTransaction(PrintDownloadTransactionRequest $request)
    {
        $writer = $this->workerService->createPrintTransaction($request->side, $request->date_start, $request->date_end);

        return $writer->save('php://output');
    }

    /**
     * @param  BookkeepingCompanyOrdersPaginateRequest  $request
     * @return BaseResource|PaginateResource
     */
    public function companyOrdersPaginate(BookkeepingCompanyOrdersPaginateRequest $request): PaginateResource|BaseResource
    {
        $resource = $this->workerService->bookkeepingCompanyOrders($request->validated());

        return (new PaginateResource($resource))->collectionClass(CompanyOrdersPaginateResource::class);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return PaginateResource|BaseResource
     */
    public function companyOrdersReportPaginate(\Illuminate\Http\Request $request): PaginateResource|BaseResource
    {
        $resource = $this->workerService->bookkeepingCompanyOrdersReport($request->all());

        return (new PaginateResource($resource))->collectionClass(CompanyOrdersReportResource::class);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function companyOrdersReportDownload(\Illuminate\Http\Request $request)
    {
        $writer = $this->workerService->bookkeepingCompanyOrdersReportDownload($request->all());

        return $writer->save('php://output');
    }

    /**
     * @Get("bookkeeping/company-orders/download")
     * @param  BookkeepingCompanyOrdersPaginateRequest  $request
     * @return BaseResource|PaginateResource
     */
    public function companyOrdersDownload(BookkeepingCompanyOrdersPaginateRequest $request): PaginateResource|BaseResource
    {
        $writer = $this->workerService->bookkeepingCompanyOrdersDownload($request->validated());

        return $writer->save('php://output');
    }

    /**
     * @param $driver_id
     * @return Response|Application|ResponseFactory
     */
    public function getDriverDebt($driver_id): Response|Application|ResponseFactory
    {
        $debt = $this->driverService->getDebt($driver_id);

        if (!$debt) {
            return response(['message' => 'error'], 400);
        }

        return response(['message' => 'ok', '_payload' => ['debt' => $debt->debt]]);
    }

    public function getDrivers(\Illuminate\Http\Request $request)
    {
        $resource = $this->workerService->getDriversOfParks($request->all());

        return (new PaginateResource($resource))->collectionClass(BookkeepingDriversResource::class);
    }
}
