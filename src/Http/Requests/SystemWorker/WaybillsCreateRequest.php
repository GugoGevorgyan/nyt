<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Factory as ValidationFactory;
use Src\Core\Enums\ConstGuards;
use Src\Http\Requests\BaseRequest;
use Src\Repositories\Driver\DriverContract;

/**
 * Class WaybillsCreateRequest
 * @property mixed driver_id
 * @property mixed days
 * @property mixed checked
 * @package Src\Http\Requests\SystemWorker
 */
class WaybillsCreateRequest extends BaseRequest
{
    /**
     * WaybillsCreateRequest constructor.
     * @param  DriverContract  $driverContract
     * @param  ValidationFactory  $validationFactory
     */
    public function __construct(protected DriverContract $driverContract, ValidationFactory $validationFactory)
    {
        $validationFactory->extend('driver_max_waybill_days', function () { return true; });

        parent::__construct();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::guard(ConstGuards::SYSTEM_WORKERS_WEB()->getValue())->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'driver_id' => ['required', 'integer', 'exists:drivers,driver_id', 'driver_max_waybill_days'],
            'days' => ['required', 'integer', 'min:1', 'max:20'],
            'checked' => ['boolean']
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function errorMessages(): array
    {
        return [];
    }
}
