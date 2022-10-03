<?php

namespace Src\Http\Requests\SystemWorker;

use Src\Http\Requests\BaseRequest;

/**
 * Class UpdateCompanyRequest
 * @package Src\Http\Requests\SystemWorker
 */
class UpdateCompanyRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return \Auth::check(); /*@todo*/
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $company_id = $this->route('company_id');
        $data = $this->request->all();

        $rules = [
            '_method' => 'string',
            'company.company_id' => 'nullable',
            'company.name' => 'required|min:3|max:32',
            'company.entity_id' => 'required|exists:legal_entities,legal_entity_id',
            'company.address' => 'nullable|min:3|max:120',
            'company.email' => 'nullable|email|min:3|max:32',
            'company.phone' => 'nullable|min:3|max:120',
            'company.additional_phones' => 'sometimes|array',
            'company.additional_phones.*' => 'sometimes|unique:company_phones,number,' . $company_id . ',company_id|unique:clients,phone',
            'company.order_canceled_timeout' => 'nullable|integer',
            'company.period' => 'nullable|integer',
            'company.frequency' => 'nullable|integer',
            'company.code' => 'required|unique:companies,code,' . $company_id . ',company_id',
            'company.contract_start' => 'nullable|date',
            'company.contract_end' => 'nullable|date',
            'company.contract_scan_file' => 'sometimes|image|mimes:jpeg,jpg,png',
            'company.limit' => 'nullable|integer',
            'company.details' => 'nullable',
            'company.admin_added' => 'nullable',
        ];

        if ($data['company']['admin_added']) {
            $rules['adminCorporate'] = [
                'name' => ['nullable', 'min:3', 'max:32'],
                'surname' => ['nullable', 'min:3', 'max:32'],
                'patronymic' => ['nullable', 'min:3', 'max:32'],
                'phone' => ['nullable', 'min:6', 'max:32'],
                'email' => ['required', 'email', 'unique:admin_corporates,email'],
                'password' => ['required', 'min:6', 'max:32']
            ];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function errorMessages(): array
    {
        return [];
    }
}
