<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Src\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Auth;
use Src\Models\Corporate\Company;

/**
 * Class BookkeepingCompanyPaginateRequest
 * @package Src\Http\Requests\SystemWorker
 */
class BookkeepingCompanyOrdersPaginateRequest extends BaseRequest
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
        $company = app(Company::class);

        return [
            'page' => 'required',
            'per_page' => 'required',
            'company' => 'required|integer|exists:' . $company->getTable() . ',' . $company->getKeyName(),
            'date_start' => 'required|date_format:Y-m-d',
            'date_end' => 'required|date_format:Y-m-d'
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData(): array
    {
        $this->merge(
            [
                'page' => (int)$this['page'],
                'per_page' => (int)$this['per_page'],
                'company' => (int)$this['company'],
                'date_start' => $this['date_start'] ?? null,
                'date_end' => $this['date_end'] ?? null,
            ]
        );

        return $this->all();
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
