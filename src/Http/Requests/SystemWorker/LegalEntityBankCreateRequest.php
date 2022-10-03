<?php

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;

class LegalEntityBankCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:100|min:3',
            'entity_id' => 'required|exists:legal_entities,legal_entity_id',
            'country_id' => 'required|exists:countries,country_id',
            'region_id' => 'required|exists:regions,region_id',
            'city_id' => 'required|exists:cities,city_id',
            'bank_account_number' => 'required|max:100|min:3',
            'correspondent_account_number' => 'required|max:100|min:3',
            'bank_identification_account' => 'required|max:100|min:3',
        ];
    }
}
