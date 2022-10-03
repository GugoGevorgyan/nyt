<?php

declare(strict_types=1);

namespace Src\Http\Requests\AdminCorporate;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string phone
 */
class CheckClientExistsRequest extends FormRequest
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
            //
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [];
    }


    /**
     * @return array
     */
    public function validationData(): array
    {
        return $this->all();
    }
}
