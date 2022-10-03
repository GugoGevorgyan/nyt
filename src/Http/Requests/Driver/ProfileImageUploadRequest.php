<?php

declare(strict_types=1);

namespace Src\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ProfileImageUploadRequest
 * @package Src\Http\Requests\Driver
 */
class ProfileImageUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'photo' => ['required', 'mimes:jpeg,jpg,png', 'max:2048']
        ];
    }
}
