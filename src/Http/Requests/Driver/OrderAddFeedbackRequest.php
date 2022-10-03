<?php

declare(strict_types=1);

namespace Src\Http\Requests\Driver;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class OrderAddFeedbackRequest
 * @property mixed assessment
 * @property mixed option
 * @property mixed text
 * @property mixed completed_order_id
 * @property mixed slip
 * @package Src\Http\Requests\Driver
 */
class OrderAddFeedbackRequest extends FormRequest
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
            'completed_order_id' => ['required', 'exists:completed_orders,completed_order_id'],
            'assessment' => $this->assessment ? ['numeric', 'digits_between:1,1', 'min:1', 'max:5'] : ['nullable'],
            'option' => ['nullable', 'exists:order_feedback_options,option'],
            'text' => ['nullable'],
            'slip' => ['nullable']
        ];
    }
}
