<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Support\Facades\Auth;
use Src\Core\Enums\ConstGuards;
use Src\Http\Requests\BaseRequest;

/**
 *
 */
class ForeignBoardAttachRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && get_guard() === ConstGuards::SYSTEM_WORKERS_WEB()->getValue();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'exists:orders,order_id'],
        ];
    }

    public function errorMessages(): array
    {
        return [
        ];
    }
}
