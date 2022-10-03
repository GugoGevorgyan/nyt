<?php

namespace Src\Http\Requests\AdminSuper;

use Illuminate\Foundation\Http\FormRequest;

class UpdateModuleRequest extends FormRequest
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
        $id = $this->route('module');
        return [
            'name' => 'required|string|max:255|unique:modules,name,'.$id.',module_id',
            'description' => 'required|string',
            'default' => 'required|boolean',
            'alias' => 'required|string|max:255',
        ];
    }
}
