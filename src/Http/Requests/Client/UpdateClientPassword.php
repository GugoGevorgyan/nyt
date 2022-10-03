<?php

declare(strict_types=1);

namespace Src\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * @property mixed $confirmPassword
 * @property mixed $newPassword
 * @property mixed $client_id
 * @property mixed $currentPassword
 */
class UpdateClientPassword extends FormRequest
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
            'currentPassword' => ['required'],
            'newPassword' => ['required'],
            'confirmPassword' => ['same:newPassword'],
        ];
    }

    /**
     * @return array
     */
    public function errorMessages(): array
    {
        return [
            'currentPassword.required' => 'Current Password is required',
            'newPassword.required' => 'New Password is required',
            'confirmPassword.required' => 'Confirm Password is required',
            'confirmPassword.same' => 'Please make sure your password match',
        ];
    }
}
