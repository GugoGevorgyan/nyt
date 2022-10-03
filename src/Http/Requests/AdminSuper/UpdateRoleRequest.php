<?php

declare(strict_types=1);

namespace Src\Http\Requests\AdminSuper;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 *
 */
class UpdateRoleRequest extends FormRequest
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
            'module_id' => 'required|integer|exists:modules,module_id',
            'name' => 'required|string|max:255',
            'alias' => 'required|string|max:255',
            'guard_name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ];
    }
}
