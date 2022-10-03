<?php

namespace Src\Http\Requests\AdminSuper;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRegionsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $except = $this->route('region');

        return [
            'country_id' => 'required|integer|exists:countries,country_id',
            'iso_2' => "required|string|max:10|unique:regions,iso_2,$except,region_id",
            'name' => 'required|string|max:255',
        ];
    }
}
