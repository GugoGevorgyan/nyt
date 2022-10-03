<?php

declare(strict_types=1);

namespace Src\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class GetConversationRequest
 * @package Src\Http\Requests
 */
class GetConversationRequest extends FormRequest
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
            //
        ];
    }
}
