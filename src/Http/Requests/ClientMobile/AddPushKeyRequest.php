<?php

declare(strict_types=1);

namespace Src\Http\Requests\ClientMobile;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed push_uid
 */
class AddPushKeyRequest extends FormRequest
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
            'push_uid' => ['required', 'unique:fcm_clients,key']
        ];
    }
}
