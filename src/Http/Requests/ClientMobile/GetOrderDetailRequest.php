<?php

declare(strict_types=1);

namespace Src\Http\Requests\ClientMobile;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class GetOrderDetailRequest
 * @package Src\Http\Requests\ClientMobile
 */
class GetOrderDetailRequest extends FormRequest
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
            'order_id' => ['required', 'exists:orders,order_id']
        ];
    }

    /**
     * Get all of the input and files for the request.
     *
     * @param  array|mixed|null  $keys
     * @return array
     */
    public function all($keys = null): array
    {
        return array_replace_recursive(parent::all(), $this->route()->parameters());
    }
}
