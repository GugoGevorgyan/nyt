<?php

declare(strict_types=1);

namespace Src\Http\Requests\ClientMobile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Src\Support\Rules\Client\CheckClientCancelOrderFeedbackTimeOut;

/**
 * Class AddFeedbackAbortedOrderRequest
 * @package Src\Http\Requests\ClientMobile
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

        if (null !== $request['feedback']['assessment'] && isset($request['completed_order_id']) && $request['completed_order_id']) {
            return [
                'completed_order_id' => ['required', 'exists:completed_orders,completed_order_id'],
                'feedback' => ['required', 'array'],
                'feedback.option_id' => ['nullable', 'exists:order_feedback_options,option'],
                'feedback.text' => ['nullable'],
                'feedback.assessment' => ['required', 'required', 'digits_between:1,1', 'numeric', 'min:1', 'max:5'],
                'favorite' => ['nullable', 'boolean'],
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
