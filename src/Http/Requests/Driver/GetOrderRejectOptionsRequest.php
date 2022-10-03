<?php

declare(strict_types=1);

namespace Src\Http\Requests\Driver;

use Auth;
use Src\Http\Requests\BaseRequest;
use Src\Models\Driver\DriverStatus;
use Src\Support\Rules\Driver\DriverStatusCheckRule;

/**
 * Class GetOrderRejectOptionsRequest
 * @property mixed order_id
 * @package Src\Http\Requests\Driver
 */
class GetOrderRejectOptionsRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'order_id' => [
                'required',
                'exists:orders,order_id',
                new DriverStatusCheckRule([
                    DriverStatus::getStatusId(DriverStatus::DRIVER_ON_ACCEPT),
                    DriverStatus::getStatusId(DriverStatus::DRIVER_ON_WAY),
                    DriverStatus::getStatusId(DriverStatus::DRIVER_IN_PLACE),
                ])
            ],
        ];
    }

    public function errorMessages(): array
    {
        return [];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return void
     */
    public function prepareForValidation(): void
    {
        $this->merge(['order_id' => (int)$this->order_id]);
    }
}
