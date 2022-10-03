<?php

declare(strict_types=1);

namespace Src\Http\Requests\Terminal;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AddedCasheRequest
 * @property mixed type
 * @property mixed cash
 * @package Src\Http\Requests\Terminal
 */
class AddedCasheRequest extends FormRequest
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
            'type' => ['required'],
            'cash' => ['required']
        ];
    }
}
