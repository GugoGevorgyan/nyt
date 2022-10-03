<?php

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;
use Src\Models\Order\CompletedOrder;

class UpdateFeedbackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'feedback_status' => 'in:'.implode(
                ",",
                [
                        CompletedOrder::FEEDBACK_STATUS_NEW,
                        CompletedOrder::FEEDBACK_STATUS_PROCESSING,
                        CompletedOrder::FEEDBACK_STATUS_DONE
                    ]
            )
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'feedback_status.in' => 'Wrong feedback status',
        ];
    }
}
