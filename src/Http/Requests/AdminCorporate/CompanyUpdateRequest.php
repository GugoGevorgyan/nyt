<?php

declare(strict_types=1);

namespace Src\Http\Requests\AdminCorporate;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
use Src\Models\Corporate\Company;

/**
 * Class CompanyUpdateRequest
 * @package Src\Http\Requests\AdminCorporate
 */
class CompanyUpdateRequest extends FormRequest
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
        return [
            'name' => ['required'],
            'company_id' => ['required'],
            'details' => ['required'],
            'franchise_id' => ['required'],
            'entity_id' => ['required'],
            'email' => ['required'],
            'code' => ['unique:' . Company::class . ',code,' . user()->company_id],
            'order_canceled_timeout' => ['required'],
            'period' => ['required'],
            'limit' => ['required'],
        ];
    }
}
