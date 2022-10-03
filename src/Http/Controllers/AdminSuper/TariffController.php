<?php

declare(strict_types=1);

namespace Src\Http\Controllers\AdminSuper;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use JsonException;
use Src\Core\Enums\ConstTariffRound;
use Src\Http\Controllers\Controller;
use Src\Http\Requests\AdminSuper\CopyTariffRequest;
use Src\Http\Requests\AdminSuper\CreateTariffsRequest;
use Src\Http\Requests\AdminSuper\UpdateTariffsRequest;
use Src\Services\Car\CarServiceContract;
use Src\Services\Payment\PaymentServiceContract;
use Src\Services\Tariff\TariffServiceContract;
use Src\ServicesCrud\Tariff\TariffCrudContract;

/**
 * Class TariffController
 * @package Src\Http\Controllers\SystemWorker
 */
class TariffController extends Controller
{
    /**
     * @var TariffCrudContract
     */
    protected TariffCrudContract $tariffCrud;
    /**
     * @var CarServiceContract
     */
    protected CarServiceContract $carService;
    /**
     * @var PaymentServiceContract
     */
    protected PaymentServiceContract $paymentTypeServiceContract;
    /**
     * @var TariffServiceContract
     */
    protected TariffServiceContract $tariffService;

    /**
     * TariffController constructor.
     * @param  TariffCrudContract  $tariffCrud
     * @param  CarServiceContract  $carServiceContract
     * @param  PaymentServiceContract  $paymentTypeServiceContract
     * @param  TariffServiceContract  $tariffServiceContract
     */
    public function __construct(
        TariffCrudContract $tariffCrud,
        CarServiceContract $carServiceContract,
        PaymentServiceContract $paymentTypeServiceContract,
        TariffServiceContract $tariffServiceContract
    ) {
        $this->tariffCrud = $tariffCrud;
        $this->carService = $carServiceContract;
        $this->paymentTypeServiceContract = $paymentTypeServiceContract;
        $this->tariffService = $tariffServiceContract;
    }

    /**
     * @return Factory|View
     */
    public function index()
    {
        return view('admin-super.tariff.index');
    }

    /**
     * @return Factory|View
     * @throws JsonException
     */
    public function create()
    {
        return view(
            'admin-super.tariff.create',
            [
                'rounds' => json_encode(array_values(ConstTariffRound::getAll()), JSON_THROW_ON_ERROR),
                'carClasses' => $this->carService->getCarClasses(),
                'tariffTypes' => $this->tariffService->getTariffTypes(),
                'paymentTypes' => $this->paymentTypeServiceContract->getPaymentTypes()
            ]
        );
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function paginate(Request $request)
    {
        return $this->tariffService->adminPaginate($request);
    }

    /**
     * @param  CreateTariffsRequest  $request
     * @return JsonResponse
     */
    public function store(CreateTariffsRequest $request): JsonResponse
    {
        $createTariff = $this->tariffCrud->createTariff($request->post());

        if (!$createTariff) {
            return jsponse(['message' => 'Oops! Something went wrong.'], 500);
        }

        return jsponse(['message' => 'Tariff successfully created']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $tariff_id
     * @return Factory|View
     * @throws JsonException
     */
    public function edit($tariff_id)
    {
        return view(
            'admin-super.tariff.edit',
            [
                'rounds' => json_encode(array_values(ConstTariffRound::getAll()), JSON_THROW_ON_ERROR),
                'tariff' => $this->tariffCrud->getTariffById($tariff_id),
                'carClasses' => $this->carService->getCarClasses(),
                'tariffTypes' => $this->tariffService->getTariffTypes(),
                'paymentTypes' => $this->paymentTypeServiceContract->getPaymentTypes()
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateTariffsRequest  $request
     * @param $tariff_id
     * @return JsonResponse
     */
    public function update(UpdateTariffsRequest $request, $tariff_id): JsonResponse
    {
        $update = $this->tariffCrud->updateTariff($tariff_id, $request->all());

        if ($update) {
            return jsponse(['message' => 'Tariff successfully updated']);
        }

        return jsponse(['message' => 'Oops! Something went wrong.'], 500);
    }

    /**
     * @param  CopyTariffRequest  $request
     * @param $tariff_id
     * @return ResponseFactory|Response
     */
    public function copy(CopyTariffRequest $request, $tariff_id)
    {
        $copy = $this->tariffCrud->copyTariff($request->all(), $tariff_id);

        if ($copy) {
            return response(['message' => 'Tariff successfully copied']);
        }

        return response(['message' => 'Oops! Something went wrong.'], 500);
    }

    /**
     * @param $tariff_id
     * @return ResponseFactory|Response
     */
    public function destroy($tariff_id)
    {
        $delete = $this->tariffCrud->deleteTariff($tariff_id);

        if (!$delete) {
            return response(['message' => 'Oops! Something went wrong.'], 500);
        }

        return response(['message' => 'Tariff successfully deleted']);
    }
}
