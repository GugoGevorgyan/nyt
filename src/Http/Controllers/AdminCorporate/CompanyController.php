<?php

declare(strict_types=1);

namespace Src\Http\Controllers\AdminCorporate;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\AdminCorporate\CompanyUpdateRequest;
use Src\Services\Company\CompanyServiceContract;

/**
 * Class CompanyController
 * @package Src\Http\Controllers\AdminCorporate
 */
class CompanyController extends Controller
{
    /**
     * @var CompanyServiceContract
     */
    protected CompanyServiceContract $companyService;

    /**
     * CorporateClientController constructor.
     * @param CompanyServiceContract $companyServiceContract
     */
    public function __construct(CompanyServiceContract $companyServiceContract)
    {
        $this->companyService = $companyServiceContract;
    }


    /**
     * @return ResponseFactory|Response
     */
    public function getCompany()
    {
        $company = $this->companyService->getCompany();

        if ($company) {
            return response($company);
        }

        return response('Company not found', 400);
    }


    /**
     * @param CompanyUpdateRequest $request
     * @param $id
     * @return ResponseFactory|Response
     */
    public function updateCompany(CompanyUpdateRequest $request, $id)
    {
        $company = $this->companyService->adminUpdateCompany($id, $request->all());

        if (!empty($company)) {
            return response(['message' => 'Updated']);
        }

        return response('Company don\'t updated', 400);
    }

    public function getCompanyPhoneMask($company_id)
    {
        return $this->companyService->getPhoneMask($company_id);
    }
}
