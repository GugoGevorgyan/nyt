<?php

namespace Src\Http\Requests\AdminSuper;

use Illuminate\Foundation\Http\FormRequest;

class CreateFranchiseAdminRequest extends FormRequest
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
        return [
            'franchise_id' => 'required|exists:franchisee,franchise_id',
            'name' => 'required|min:3|max:36',
            'surname' => 'nullable|min:3|max:36',
            'patronymic' => 'nullable|min:3|max:36',
            'nickname' => 'nullable|min:3|max:36|unique:system_workers,nickname',
            'email' => 'nullable|email|min:3|max:36',
            'phone' => 'nullable|min:3|max:36',
            'password' => 'required|min:3|max:36',
        ];
    }
}
