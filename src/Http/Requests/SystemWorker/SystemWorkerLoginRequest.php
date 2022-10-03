<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SystemWorkerLoginRequest
 * @property mixed nickname
 * @property mixed password
 * @package Src\Http\Requests\SystemWorker
 */
class SystemWorkerLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'nickname' => ['required', 'exists:system_workers,nickname'],
            'password' => ['required']
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'nickname.required' => 'Login required',
            'nickname.exists' => 'Invalid data',
            'password.required' => 'Invalid data',
        ];
    }
}
