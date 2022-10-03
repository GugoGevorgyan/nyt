<?php

declare(strict_types=1);

namespace Src\Http\Requests\AdminSuper;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Src\Models\Franchise\Module;

/**
 * Class UpdateFranchiseRequest
 * @package Src\Http\Requests\AdminSuper
 */
class UpdateFranchiseRequest extends FormRequest
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
        $id = $this->route('franchise_id');
        $callCenterModule = Module::where('name', '=', 'call_center')->first();
        $data = $this->request->all();

        $rules = [
            'name' => 'required|min:3|max:36|unique:franchisee,name,'.$id.',franchise_id',
            'phone' => 'required|min:3|max:36',
            'email' => 'nullable|email|min:3|max:36',
            'text' => 'nullable|max:250',
            'address' => 'nullable|min:3',
            'zip_code' => 'nullable|min:3|max:36',
            'country_id' => 'required|exists:countries,country_id',
            'regions_cities' => 'required|array',
            'regions_cities.*' => 'required|array',
            'entity_id' => 'required|exists:legal_entities,legal_entity_id',
            'module_roles' => 'required|array',
            'module_roles.*' => 'required|array',
            'file' => 'nullable|max:10000|mimes:jpeg,jpg,png',

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
