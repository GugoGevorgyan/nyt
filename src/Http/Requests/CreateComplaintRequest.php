<?php

declare(strict_types=1);

namespace Src\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateComplaintRequest
 * @package Src\Http\Requests
 */
class CreateComplaintRequest extends FormRequest
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
            'workers' => 'required|array',
            'complaint' => 'required|min:10',
            'order' => 'nullable',
        ];
    }
}
