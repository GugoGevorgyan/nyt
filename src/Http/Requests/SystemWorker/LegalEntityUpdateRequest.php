<?php

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;

class LegalEntityUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; /*@todo*/
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $entity_id = $this->route('entity_id');
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
            'tax_inn' => 'nullable|min:3|max:100|unique:legal_entities,tax_inn,'.$entity_id.',legal_entity_id',
            'tax_kpp' => 'nullable|min:3|max:100unique:legal_entities,tax_kpp,'.$entity_id.',legal_entity_id',
            'tax_psrn' => 'nullable|min:3|max:100|unique:legal_entities,tax_psrn,'.$entity_id.',legal_entity_id',
            'tax_psrn_serial' => 'nullable|min:3|max:100|unique:legal_entities,tax_psrn_serial,'.$entity_id.',legal_entity_id',
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
            'registration_certificate_date' => 'nullable|date_format:Y-m-d'
        ];
    }
}
