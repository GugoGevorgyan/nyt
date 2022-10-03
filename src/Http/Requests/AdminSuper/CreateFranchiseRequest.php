<?php

declare(strict_types=1);

namespace Src\Http\Requests\AdminSuper;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Src\Models\Franchise\Module;

/**
 * Class CreateFranchiseRequest
 * @package Src\Http\Requests
 */
class CreateFranchiseRequest extends FormRequest
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
        $callCenterModule = Module::where('name', '=', 'call_center')->first();
        $data = $this->request->all();
        $rules = [
            'name' => ['required', 'min:3', 'max:36', 'unique:franchisee,name'],
            'phone' => ['required', 'min:3', 'max:36'],
            'email' => 'nullable|email|min:3|max:36',
            'text' => 'nullable|max:250',
            'address' => 'nullable|max:100',
            'zip_code' => 'nullable|max:36',
            'country_id' => 'required|exists:countries,country_id',
            'regions_cities' => 'required|array',
            'regions_cities.*' => 'required|array',
            'entity_id' => 'required|exists:legal_entities,legal_entity_id',
            'module_roles' => 'required|array',
            'module_roles.*' => 'required|array',
            'file' => 'nullable|mimes:jpeg,jpg,png',

            'new_admins' => 'required|array',
            'new_admins.*' => 'required|array',
            'new_admins.*.name' => 'required|min:3|max:36',
            'new_admins.*.surname' => 'nullable|min:3|max:36',
            'new_admins.*.patronymic' => 'nullable|min:3|max:36',
            'new_admins.*.nickname' => 'required|min:3|max:36|unique:system_workers,nickname',
            'new_admins.*.email' => 'nullable|email|min:3|max:36',
            'new_admins.*.phone' => 'nullable|min:3|max:36',
            'new_admins.*.password' => 'required|min:3|max:36',

            'option' => 'required|array|size:4',
            'option.*.default_assessment' => 'required|numeric|min:1|max:5',
            'option.*.default_rating' => 'required|integer|min:100|max:999',
            'option.*.waybill_max_days' => 'required|integer|min:1|max:20'
        ];

        if ($callCenterModule && isset($data['module_roles'][$callCenterModule->module_id])) {
            $rules['call_center_phones'] = 'required|array';
            $rules['call_center_phones.*'] = 'required|array';
            $rules['call_center_phones.*.number'] = 'required|min:3|max:36';
            $rules['call_center_phones.*.sub_phones'] = 'required|array';
            $rules['call_center_phones.*.sub_phones.*'] = 'required|array';
            $rules['call_center_phones.*.sub_phones.*.number'] = 'required|min:3|max:5';
            $rules['call_center_phones.*.sub_phones.*.password'] = 'required|min:8|max:36';
        }

        return $rules;
    }
}
