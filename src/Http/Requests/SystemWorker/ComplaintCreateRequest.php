<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ComplaintCreateRequest
 * @package Src\Http\Requests\SystemWorker
 */
class ComplaintCreateRequest extends FormRequest
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
            'subject' => ['required', 'min:3', 'max:100'],
            'complaint' => ['required', 'min:3', 'max:2500'],
            'recipient_id' => ['required', 'exists:system_workers,system_worker_id'],
            'order_id' => ['nullable', 'exists:orders,order_id'],
            'files' => ['required', 'array'],
            'files.*' => ['nullable', 'mimes:jpeg,jpg,png']
        ];
    }
}
