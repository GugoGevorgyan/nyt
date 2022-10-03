<?php

declare(strict_types=1);

namespace Src\Http\Requests\Client;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Src\Support\Rules\Client\CheckClientCancelOrderFeedbackTimeOut;

/**
 * Class AddFeedbackAbortedOrderRequest
 * @property mixed aborted_order_id
 * @property mixed feedback
 * @package Src\Http\Requests\ClientMessage
 */
class AddFeedbackAbortedOrderRequest extends FormRequest
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
        $request = $this->request->all();

        if (($request['feedback']['assessment'] ?? false) && isset($request['completed_order_id']) && $request['completed_order_id']) {
            return [
                'completed_order_id' => ['required', 'exists:completed_orders,completed_order_id'],
                'feedback' => ['required', 'array'],
                'feedback.option_id' => ['nullable', 'exists:order_feedback_options,option'],
                'feedback.text' => ['nullable'],
                'feedback.assessment' => ['required', 'required', 'digits_between:1,1', 'numeric', 'min:1', 'max:5'],
            ];
        }

        return [
            'aborted_order_id' => ['required', 'exists:canceled_orders,canceled_order_id', new CheckClientCancelOrderFeedbackTimeOut()],
            'feedback' => ['required', 'array'],
            'feedback.option_id' => '',
            'feedback.text' => '',
        ];
    }
}
