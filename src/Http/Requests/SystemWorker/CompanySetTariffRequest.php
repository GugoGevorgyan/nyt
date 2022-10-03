<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Auth;
use Src\Http\Requests\BaseRequest;

/**
 * Class CompanySetTariffRequest
 * @property array tariff_id
 * @property int company_id
 * @package Src\Http\Requests\SystemWorker
 */
class CompanySetTariffRequest extends BaseRequest
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
            'company_id' => 'required|exists:companies,company_id',
            'tariff_ids' => 'required|array',
            'tariff_ids.*' => 'required|exists:tariffs,tariff_id',
        ];
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
