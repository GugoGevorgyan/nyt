<?php

declare(strict_types=1);

namespace Src\Http\Requests\Terminal;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AuthRequest
 * @property int id
 * @property string secret
 * @property string terminal_name
 * @package Src\Http\Requests\Terminal
 */
class AuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'terminal_name' => ['required', 'exists:terminals,name'],
            'password' => ['required'],
        ];
    }
}
