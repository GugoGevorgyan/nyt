<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Src\Models\Role\Role;

/**
 * Class CallCenterGetCallsRequest
 * @package Src\Http\Requests\SystemWorker
 */
class CallCenterGetCallsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() /*&& user()->hasRole(Role::CALL_CENTER_OPERATOR_WEB)*/;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'subPhone' => ['required', 'integer', 'exists:franchise_sub_phones,number']
        ];
    }
}
