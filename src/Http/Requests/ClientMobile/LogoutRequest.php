<?php

declare(strict_types=1);

namespace Src\Http\Requests\ClientMobile;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LogoutRequest
 * @package Src\Http\Requests\ClientMobile
 */
class LogoutRequest extends FormRequest
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
    public function rules()
    {
        return [
            //
        ];
    }
}
