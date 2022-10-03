<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StartSessionRequest
 * @property mixed nickname
 * @property mixed password
 * @property mixed token
 * @package Src\Http\Requests\SystemWorker
 */
class StartSessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'nickname' => ['required', 'exists:system_workers,nickname'],
            'password' => ['required'],
            'token' => ['required', 'exists:worker_sessions,token'],
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'nickname.required' => 'fwefwe',
            'nickname.exists' => 'fweffwe',
            'password.required' => 'fewfew',
        ];
    }
}
