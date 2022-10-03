<?php

declare(strict_types=1);

namespace Src\Http\Requests\Webhooks;

use Illuminate\Foundation\Http\FormRequest;
use Src\Support\Rules\AuthApi\SignVerifiedRule;

/**
 * Class AcquiringRequest
 * @package Src\Http\Requests\Webhooks
 */
class AcquiringRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => 'required|string',
            'status' => 'required|string',
            'signature' => ['required', 'string', new SignVerifiedRule]
        ];
    }
}
