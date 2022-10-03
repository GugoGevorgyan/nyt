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
use Src\Http\Requests\SystemWorker\CompanySetTariffRequest;
use Src\Http\Requests\SystemWorker\CreateCompanyRequest;
use Src\Http\Requests\SystemWorker\DeleteCompanyRequest;
use Src\Http\Requests\SystemWorker\UpdateCompanyRequest;
use Src\Services\Company\CompanyServiceContract;
use Src\Services\Tariff\TariffServiceContract;

/**
 * Class CompanyController
 * @package Src\Http\Controllers\SystemWorker
 */
class CompanyController extends Controller
{
    /**
     * @var CompanyServiceContract
     */
    protected CompanyServiceContract $companyService;
    /**
     * @var TariffServiceContract
     */
    protected TariffServiceContract $tariffService;

    /**
     * WorkerController constructor.
     * @param  CompanyServiceContract  $companyService
     * @param  TariffServiceContract  $tariffService
     */
    public function __construct(CompanyServiceContract $companyService, TariffServiceContract $tariffService)
    {
        $this->companyService = $companyService;
        $this->tariffService = $tariffService;
    }

    /**
     * @return Factory|View
     */
    public function showIndex()
    {
        return view('system-worker.company.index');
    }

    /**
     * @param  Request  $request
     * @return Application|ResponseFactory|Response
     */
    public function companyPaginate(Request $request)
    {
        return response($this->companyService->franchiseCompaniesPaginate($request->all()));
    }

    /**
     * @return Application|Factory|View
     */
    public function companyCreate()
    {
        $result = $this->companyService->getCurrentPhoneMask(user()->franchise_id);

        return view('system-worker.company.create',
            [
                'phoneMask' => json_encode($result->country['phone_mask'])
            ]
        );
    }

    /**
     * @param $company_id
     * @return Application|Factory|View
     */
    public function companyEdit($company_id)
    {
        $company = $this->companyService->getFranchiseCompany($company_id);

        if (!$company) {
            return view('errors.404');
        }

        return view('system-worker.company.edit', ['company' => $company]);
    }

    /**
     * @param  UpdateCompanyRequest  $request
     * @param  int  $company_id
     * @return Application|ResponseFactory|Response
     */
    public function companyUpdate(UpdateCompanyRequest $request, int $company_id)
    {
        return $this->companyService->updateCompanyFranchise($request->validated(), $company_id)
            ? response(['message' => trans('messages.company_updated')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param  CreateCompanyRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function companyStore(CreateCompanyRequest $request)
    {
        return $this->companyService->createCompanyFranchise($request)
            ? response(['message' => trans('messages.company_created')])
            : response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @return mixed
     */
    public function getTariffs()
    {
        $tariffs = $this->tariffService->getTariffsForCompanies();

        if (!$tariffs) {
            return response(['message' => 'Invalid tariff data'],400);
        }

        return $tariffs;
    }

    /**
     * @param  CompanySetTariffRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function setTariff(CompanySetTariffRequest $request)
    {
        $tariff_ids = $this->companyService->companyAttachTariff($request->company_id, $request->tariff_ids);

        return $tariff_ids ?
            response(['message' => trans('messages.company_tariffs_updated'), 'tariff_ids' => $tariff_ids], 200) :
            response(['message' => trans('messages.something_went_wrong')], 500);
    }

    /**
     * @param $company_id
     * @return Application|ResponseFactory|Response
     */
    public function deleteCompany(DeleteCompanyRequest $request)
    {
        return $this->companyService->deleteFranchiseCompany($request->company_id) ?
            response(['message' => trans('messages.company_deleted')], 200) :
            response(['message' => trans('messages.something_went_wrong')], 500);
    }

}
