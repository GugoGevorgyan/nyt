<?php

declare(strict_types=1);

namespace Src\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;
use Src\Models\Driver\DriverStatus;
use Src\Support\Rules\Driver\DriverStatusCheckRule;

/**
 * Class OrderRejectRequest
 * @property mixed order_id
 * @property mixed option
 * @property mixed text
 * @package Src\Http\Requests\Driver
 */
class OrderRejectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return \Auth::check();
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
            'option' => ['required', 'exists:order_feedback_options,option'],
            'text' => ['nullable', 'string'],
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        $this->request->replace(['order_id' => (int)$this->order_id, 'option' => (int)$this->option, 'text' => $this->text]);

        return $this->request->all();
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'order_id.required' => 'order_id required field',
            'order_id.exists' => 'order_id das not exists invalid',
        ];
    }
}
