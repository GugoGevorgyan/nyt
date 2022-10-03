<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Support\Facades\Auth;
use Src\Http\Requests\BaseRequest;

/**
 * Class OrderReCalcEditRequest
 * @property mixed order_id
 * @property mixed duration
 * @property mixed distance
 * @property mixed cross
 * @property mixed cost
 * @package Src\Http\Requests\SystemWorker
 */
class OrderReCalcEditRequest extends BaseRequest
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
            'order_id' => ['required', 'exists:orders,order_id', 'exists:completed_orders,order_id'],
            'distance' => ['nullable'],
            'duration' => ['nullable'],
            'options' => ['nullable'],
            'cross' => ['bool'],
            'cost' => ['nullable']
        ];
    }

    /**
     * @return array
     */
    public function errorMessages(): array
    {
        return [
            'order_id.exists:completed_orders' => '',
        ];
    }

    public function validationData(): array
    {
        $this->request->add(['order_id' => (int)$this->order_id]);
        return $this->request->all();
    }
}
