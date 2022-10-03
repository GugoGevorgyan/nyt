<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;
use Src\Models\Role\Role;

/**
 * Class CreateTransactionRequest
 * @property mixed type
 * @property mixed side
 * @property mixed input
 * @property mixed sum
 * @property mixed comment
 * @package Src\Http\Requests\SystemWorker
 */
class CreateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return \Auth::check() && (user()->is_admin || user()->hasRole(Role::ACCOUNTANT_API) || user()->hasRole(Role::ACCOUNTANT_WEB));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'input' => ['required', 'boolean'],
            'side' => ['required', 'exists:drivers,driver_id'],
            'type' => ['required'],
            'sum' => ['required'],
            'comment' => ['nullable'],
        ];
    }
}
