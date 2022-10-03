<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Src\Models\Role\Role;

/**
 * Class UpdateParkRequest
 * @package Src\Http\Requests\SystemWorker
 */
class UpdateParkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (
                (
                    Auth::check() &&
                    Auth::user()->is_admin
                ) ||
                Auth::user()->hasRole(Role::HEAD_PERSONAL_DEPARTMENT_WEB)
            ) ||
            Auth::user()->hasRole(Role::WORKER_PERSONAL_DEPARTMENT_WEB);
    }

    /**
     * @return string[]
     */
    public function rules()
    {

        $id = $this->route('park_id');

        return [
            'name' => 'required|max:100|min:3|'.
                Rule::unique('parks', 'name')
                    ->where('franchise_id', FRANCHISE_ID)
                    ->ignore($id, 'park_id')
            ,
            'city_id' => 'required|integer',
            'manager_id' => 'required|integer|exists:system_workers,system_worker_id',
            'address' => 'required|max:100|min:3',
            'entity_id' => 'required|exists:legal_entities,legal_entity_id'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name required',
            'city_id.required' => 'City required',
            'address.required' => 'Address data',
        ];
    }
}
