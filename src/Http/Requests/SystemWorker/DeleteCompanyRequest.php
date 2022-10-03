<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Src\Http\Requests\BaseRequest;
use Src\Models\Corporate\Company;

/**
 * Class CreateCompanyRequest
 * @package Src\Http\Requests\SystemWorker
 */
class DeleteCompanyRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; /*Auth::user()->hasRole(Role::ADMIN_FRANCHISE_WEB) @todo;*/
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $company = app(Company::class);

        return [
            'company_id' => 'required|integer|exists:' . $company->getTable() . ',' . $company->getKeyName()
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(){
        $this->merge([
            'company_id' => $this->route('company_id')
        ]);
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function errorMessages(): array{
        return [];
    }
}
