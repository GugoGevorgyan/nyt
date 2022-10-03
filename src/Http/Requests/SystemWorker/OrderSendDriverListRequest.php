<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Src\Core\Enums\ConstGuards;
use Src\Http\Requests\BaseRequest;
use Src\Repositories\Driver\DriverContract;

/**
 * @property array $drivers
 * @property int $order_id
 * @property mixed $type
 * @property mixed $radius
 */
class OrderSendDriverListRequest extends BaseRequest
{
    /**
     * @param  DriverContract  $driverContract
     */
    public function __construct(protected DriverContract $driverContract)
    {
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
            'order_id' => ['required', 'exists:orders,order_id'],
            'drivers' => ['array', 'required'],
            'radius' => ['nullable'],
            'type' => ['nullable'],
        ];
    }

    /**
     * @return array
     */
    public function errorMessages(): array
    {
        return [
            //
        ];
    }

    /**
     * Class BookkeepingCompanyPaginateRequest
     * @package Src\Http\Requests\SystemWorker
     * @method moreValidation(\Illuminate\Validation\Validator $validator)
     */
    public function moreValidation(Validator $validator): Validator
    {
        return $validator->after(function (Validator $validator) {
            $drivers = array_chunk($this->drivers, 10);
            $result = true;

            foreach ($drivers as $driver) {
                if (!$this->driverContract->where('driver_id', '=', $driver)->exists()) {
                    $result = false;
                    break;
                }
            }

            if (!$result) {
                $validator->errors()->add('drivers', 'invalid driver');
                return false;
            }

            return true;
        });
    }
}
