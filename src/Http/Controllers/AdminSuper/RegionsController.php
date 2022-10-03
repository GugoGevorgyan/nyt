<?php

declare(strict_types=1);

namespace Src\Http\Controllers\AdminSuper;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JsonException;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\AdminSuper\CreateRegionsRequest;
use Src\Http\Requests\AdminSuper\DestroyMultipleRegionsRequest;
use Src\Http\Requests\AdminSuper\GetRegionsRequest;
use Src\Http\Requests\AdminSuper\UpdateRegionsRequest;
use Src\Http\Resources\Models\CityResources;
use Src\Http\Resources\BaseResource;
use Src\Http\Resources\PaginateResource;
use Src\Services\Region\RegionServiceContract;

/**
 * Class RegionsController
 * @package Src\Http\Controllers\AdminSuper
 */
class RegionsController extends Controller
{
    /**
     * @var RegionServiceContract
     */
    protected RegionServiceContract $regionService;

    /**
     * RegionsController constructor.
     * @param  RegionServiceContract  $regionService
     */
    public function __construct(RegionServiceContract $regionService)
    {
        $this->regionService = $regionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('admin-super.regions', ['countries' => $this->regionService->getAllCountries()]);
    }

    /**
     * @param  GetRegionsRequest  $request
     * @return ResponseFactory|Response
     * @throws JsonException
     */
    public function paginate(GetRegionsRequest $request)
    {
        $roles = $this->regionService->getRegionPaginate($request->validated());

        return response(json_encode($roles, JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateRegionsRequest  $request
     * @return Response
     */
    public function store(CreateRegionsRequest $request): Response
    {
        $region = $this->regionService->createRegion($request);

        if (!$region) {
            return response('Something went wrong', 400);
        }

        return response('Region Created');
    }

    /**
     * @param  UpdateRegionsRequest  $request
     * @param $region
     * @return ResponseFactory|Response
     */
    public function update(UpdateRegionsRequest $request, $region)
    {
        $update = $this->regionService->updateRegion($request, $region);
        if (!$update) {
            return response('Something went wrong', 400);
        }

        return response('Region Updated');
    }

    /**
     * @param $region
     * @return ResponseFactory|Response
     */
    public function destroy($region)
    {
        $delete = $this->regionService->deleteRegion($region);

        if (!$delete) {
            return response('Something went wrong', 400);
        }

        return response('Region Deleted');
    }

    /**
     * @param  DestroyMultipleRegionsRequest  $request
     * @return ResponseFactory|Response
     */
    public function destroyMultiple(DestroyMultipleRegionsRequest $request)
    {
        $deletes = $this->regionService->destroyMultiple($request);

        if (!$deletes) {
            return response('Something went wrong', 400);
        }

        return response('Regions Deleted');
    }

    /**
     * @return Application|Factory|View
     */
    public function cities(): View|Factory|Application
    {
        $countries = $this->regionService->getAllCountries();

        return \view('admin-super.cities', ['countries' => $countries]);
    }

    /**
     * @param  Request  $request
     * @return BaseResource|PaginateResource
     */
    public function citiesPager(Request $request): PaginateResource|BaseResource
    {
        $cities = $this->regionService->citiesPager($request->all());

        return (new PaginateResource($cities))->collectionClass(CityResources::class);
    }
}
