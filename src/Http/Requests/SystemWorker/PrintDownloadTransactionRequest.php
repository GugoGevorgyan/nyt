<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;
use Src\Models\Role\Role;

/**
 * Class PrintDownloadTransactionRequest
 * @property mixed side
 * @property mixed date_start
 * @property mixed date_end
 * @package Src\Http\Requests\SystemWorker
 */
class PrintDownloadTransactionRequest extends FormRequest
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
            'side' => ['required'],
            'date_start' => ['nullable', 'date'],
            'date_end' => ['nullable', 'date'],
        ];
    }
}
