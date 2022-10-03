<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LegalEntityCreateRequest
 * @package Src\Http\Requests\SystemWorker
 */
class LegalEntityCreateRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => 'required|min:3|max:100',
            'type_id' => 'nullable|exists:legal_entity_types,entity_type_id',
            'country_id' => 'required|exists:countries,country_id',
            'region_id' => 'required|exists:regions,region_id',
            'city_id' => 'required|exists:cities,city_id',
            'zip_code' => 'nullable|min:3|max:8',
            'address' => 'nullable|min:3|max:100',
            'phone' => 'nullable|min:3|max:32',
            'email' => 'nullable|email|min:3|max:32',
            'tax_inn' => 'nullable|min:3|max:100|unique:legal_entities,tax_inn',
            'tax_kpp' => 'nullable|min:3|max:100|unique:legal_entities,tax_kpp',
            'tax_psrn' => 'nullable|min:3|max:100|unique:legal_entities,tax_psrn',
            'tax_psrn_serial' => 'nullable|min:3|max:100|unique:legal_entities,tax_psrn_serial',
            'tax_psrn_issued_by' => 'nullable|min:3|max:100',
            'tax_psrn_date' => 'nullable|date_format:Y-m-d',
            'director_name' => 'nullable|min:3|max:100',
            'director_surname' => 'nullable|min:3|max:100',
            'director_patronymic' => 'nullable|min:3|max:100',
            'aucneb' => '',
            'arceo' => '',
            'arcfo' => '',
            'arclf' => '',
            'registration_certificate_number' => '',
            'registration_certificate_date' => 'nullable|date_format:Y-m-d',

            'new_banks' => 'nullable|array',
            'new_banks.*' => 'nullable|array',
            'new_banks.*.name' => 'nullable|max:100|min:3',
            'new_banks.*.country_id' => 'nullable|exists:countries,country_id',
            'new_banks.*.region_id' => 'nullable|exists:regions,region_id',
            'new_banks.*.city_id' => 'nullable|exists:cities,city_id',
            'new_banks.*.bank_account_number' => 'nullable|max:100|min:3',
            'new_banks.*.correspondent_account_number' => 'nullable|max:100|min:3',
            'new_banks.*.bank_identification_account' => 'nullable|max:100|min:3',
        ];
    }
}
