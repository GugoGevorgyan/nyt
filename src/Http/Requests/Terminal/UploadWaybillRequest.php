<?php

declare(strict_types=1);

namespace Src\Http\Requests\Terminal;

use Illuminate\Support\Facades\Auth;
use Src\Core\Enums\ConstGuards;
use Src\Http\Requests\BaseRequest;

/**
 * Class UploadWaybillRequest
 * @property mixed transaction_id
 * @property mixed waybill
 * @package Src\Http\Requests\Terminal
 */
class UploadWaybillRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::guard(ConstGuards::DRIVERS_API()->getValue())->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'transaction_id' => ['required', 'exists:franchise_transactions,franchise_transaction_id'],
            'waybill' => ['required', 'file', 'max:1024', 'mimes:xlsx,xls'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    public function prepareForValidation(): void
    {
        $this->merge(['waybill' => $this->waybill]);
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
