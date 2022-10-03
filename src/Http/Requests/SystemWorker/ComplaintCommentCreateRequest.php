<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ComplaintCommentCreateRequest
 * @package Src\Http\Requests\SystemWorker
 */
class ComplaintCommentCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'complaint_id' => ['required', 'exists:complaints,complaint_id'],
            'text' => ['max:2500'],
            'files' => ['nullable', 'array'],
            'files.*' => ['nullable', 'mimes:jpeg,jpg,png']
        ];
    }
}
