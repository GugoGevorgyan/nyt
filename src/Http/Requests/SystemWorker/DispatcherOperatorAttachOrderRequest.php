<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Src\Core\Enums\ConstGuards;

/**
 *
 */
class DispatcherOperatorAttachOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::guard(ConstGuards::SYSTEM_WORKERS_WEB()->getValue())->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'operator_id' => ['required', 'exists:worker_operators,worker_operator_id'],
            'order_id' => ['required', 'exists:orders,order_id'],
        ];
    }
}
