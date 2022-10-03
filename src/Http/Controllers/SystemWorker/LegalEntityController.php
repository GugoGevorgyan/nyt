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
use Src\Http\Requests\SystemWorker\LegalEntityBankCreateRequest;
use Src\Http\Requests\SystemWorker\LegalEntityBankUpdateRequest;
use Src\Http\Requests\SystemWorker\LegalEntityCreateRequest;
use Src\Http\Requests\SystemWorker\LegalEntityUpdateRequest;
use Src\Services\LegalEntity\LegalEntityServiceContract;
use Src\Services\Region\RegionServiceContract;

/**
 * Class LegalEntityController
 * @package Src\Http\Controllers\SystemWorker
 */
class LegalEntityController extends Controller
{
    /**
     * @var LegalEntityServiceContract
     */
    protected LegalEntityServiceContract $legalEntityService;
    /**
     * @var LegalEntityServiceContract
     */
    protected LegalEntityServiceContract $legalEntityServiceContract;
    /**
     * @var RegionServiceContract
     */
    protected RegionServiceContract $regionService;

    /**
     * LegalEntityController constructor.
     * @param  LegalEntityServiceContract  $legalEntityService
     * @param  LegalEntityServiceContract  $legalEntityTypeServiceContract
     * @param  RegionServiceContract  $regionService
     */
    public function __construct(
        LegalEntityServiceContract $legalEntityService,
        LegalEntityServiceContract $legalEntityTypeServiceContract,
        RegionServiceContract $regionService
    ) {
        $this->legalEntityService = $legalEntityService;
        $this->legalEntityServiceContract = $legalEntityTypeServiceContract;
        $this->regionService = $regionService;
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        return view('system-worker.legal-entity.index', ['types' => $this->legalEntityServiceContract->getAllEntityTypes()]);
    }

    /**
     * @param  Request  $request
     * @return ResponseFactory|Response
     */
    public function paginate(Request $request)
    {
        return response($this->legalEntityService->franchiseEntitiesPaginate($request->all()));
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        $countries = $this->regionService->getAllCountries();
        $entityTypes = $this->legalEntityServiceContract->getAllEntityTypes();
        return view('system-worker.legal-entity.create', ['countries' => $countries, 'entityTypes' => $entityTypes]);
    }

    /**
     * @param $entity_id
     * @return Application|Factory|View
     */
    public function edit($entity_id)
    {
        $entity = $this->legalEntityService->franchiseGetEntity($entity_id);
        $countries = $this->regionService->getAllCountries();
        $entityTypes = $this->legalEntityServiceContract->getAllEntityTypes();

        if (!$entity) {
            return \view('errors.404');
        }

        return view('system-worker.legal-entity.edit', compact('entity', 'countries', 'entityTypes'));
    }

    /**
     * @param  LegalEntityCreateRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function store(LegalEntityCreateRequest $request)
    {
        return ($this->legalEntityService->franchiseCreateEntity($request->all()))
            ? response(['message' => trans('messages.legal_entity_created')])
            : response(['message' => trans('messages.something_went_wrong')], 400);
    }

    /**
     * @param  LegalEntityUpdateRequest  $request
     * @param $entity_id
     * @return Application|ResponseFactory|Response
     */
    public function update(LegalEntityUpdateRequest $request, $entity_id)
    {
        return ($this->legalEntityService->franchiseUpdateEntity($request->all(), $entity_id))
            ? response(['message' => trans('messages.legal_entity_updated')])
            : response(['message' => trans('messages.something_went_wrong')], 400);
    }

    /**
     * @param $entity_id
     * @return Application|ResponseFactory|Response
     */
    public function destroy($entity_id)
    {
        return ($this->legalEntityService->franchiseDestroyEntity($entity_id))
            ? response(['message' => trans('messages.legal_entity_deleted')])
            : response(['message' => trans('messages.something_went_wrong')], 400);
    }

    /**
     * @param  LegalEntityBankCreateRequest  $request
     * @return Application|ResponseFactory|Response
     */
    public function storeBank(LegalEntityBankCreateRequest $request)
    {
        $bank = $this->legalEntityService->entityBankCreate($request->all());
        return ($bank)
            ? response(['message' => trans('messages.legal_entity_bank_created'), 'bank' => $bank])
            : response(['message' => trans('messages.something_went_wrong')], 400);
    }

    /**
     * @param  LegalEntityBankUpdateRequest  $request
     * @param $entity_bank_id
     * @return Application|ResponseFactory|Response
     */
    public function updateBank(LegalEntityBankUpdateRequest $request, $entity_bank_id)
    {
        $bank = $this->legalEntityService->entityBankUpdate($request->all(), $entity_bank_id);
        return ($bank)
            ? response(['message' => trans('messages.legal_entity_bank_updated'), 'bank' => $bank])
            : response(['message' => trans('messages.something_went_wrong')], 400);
    }

    /**
     * @param $entity_bank_id
     * @return Application|ResponseFactory|Response
     */
    public function destroyBank($entity_bank_id)
    {
        return ($this->legalEntityService->entityBankDestroy($entity_bank_id))
            ? response(['message' => trans('messages.legal_entity_bank_deleted')])
            : response(['message' => trans('messages.something_went_wrong')], 400);
    }
}
