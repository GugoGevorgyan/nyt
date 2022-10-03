<?php

declare(strict_types=1);

namespace Src\Http\Requests\AdminSuper;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateAreaRequest
 * @package Src\Http\Requests\AdminSuper
 */
class CreateAreaRequest extends FormRequest
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
    public function rules()
    {
        return [
            'title' => ['required', 'unique:areas,title'],
            'area' => ['required', 'array'],
            'description' => []
        ];
    }
}
