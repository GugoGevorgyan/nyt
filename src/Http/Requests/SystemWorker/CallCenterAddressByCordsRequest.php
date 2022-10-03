<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Support\Facades\Auth;
use Src\Http\Requests\BaseRequest;

/**
 *
 * @property mixed $address
 */
class CallCenterAddressByCordsRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check(); /*Auth::user()->hasRole(Role::CALL_CENTER_OPERATOR_WEB); @todo*/
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'address' => ['required', 'string']
        ];
    }

    public function errorMessages(): array
    {
        return [];
    }

    /**
     *
     */
    public function prepareForValidation(): void
    {
        $this->merge(['address' => $this->address]);
    }
}
