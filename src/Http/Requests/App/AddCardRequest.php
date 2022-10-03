<?php

declare(strict_types=1);

namespace Src\Http\Requests\App;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class AddCardRequest
 * @package Src\Http\Requests\App
 */
class AddCardRequest extends FormRequest
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
            'cc_number' => 'required|integer|digits_between:16,19',
            'cc_expiration' => 'required|date_format:y/m|after:today',
            'cc_cvc' => 'required|integer|digits:3'
        ];
    }

    /**
     * @return array
     */
    public function validationData(): array{
        $this->merge([
            'cc_number' => str_replace('-', '', $this->cc_number),
            'cc_cvc' => (int)$this->cc_cvc
        ]);

        return $this->all();
    }
}
