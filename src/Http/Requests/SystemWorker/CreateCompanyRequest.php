<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Support\Facades\Auth;
use Src\Core\Enums\ConstGuards;
use Src\Http\Requests\BaseRequest;

/**
 * Class CreateCompanyRequest
 * @package Src\Http\Requests\SystemWorker
 */
class CreateCompanyRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::user() && Auth::guard(ConstGuards::SYSTEM_WORKERS_WEB()->getValue())->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $data = $this->all();

        $rules = [
            'company.company_id' => 'nullable',
            'company.name' => 'required|min:3|max:32',
            'company.address' => 'nullable|min:3|max:120',
            'company.entity_id' => 'required|exists:legal_entities,legal_entity_id',
            'company.phone' => 'nullable|min:6|max:32',
            'company.additional_phones' => 'sometimes|array',
            'company.additional_phones.*' => 'sometimes|unique:company_phones,number|unique:clients,phone',
            'company.email' => 'nullable|email|min:3|max:32',
            'company.order_canceled_timeout' => 'nullable|integer',
            'company.period' => 'nullable|integer',
            'company.frequency' => 'nullable|integer',
            'company.code' => 'required|unique:companies,code',
            'company.contract_start' => 'nullable|date',
            'company.contract_end' => 'nullable|date',
            'company.contract_scan_file' => 'nullable|image|mimes:jpeg,jpg,png',
            'company.limit' => 'nullable|integer',
            'company.details' => 'nullable|max:1500',
            'company.admin_added' => 'nullable',
            'company.pickerOptions' => 'required',
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
