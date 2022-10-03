<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Support\Facades\Auth;
use Src\Core\Enums\ConstGuards;
use Src\Http\Requests\BaseRequest;

/**
 * @property int $order_id
 * @property int|null $driver_id
 * @property string $time
 * @property bool $now
 */
class ChangePreorderDataRequest extends BaseRequest
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
            'order_id' => ['required', 'exists:orders,order_id'],
            'driver_id' => ['nullable', 'exists:drivers,driver_id'],
            'time' => ['required', 'string'],
            'now' => ['required', 'bool'],
        ];
    }

    /**
     * @return array
     */
    public function errorMessages(): array
    {
        return [
            //
        ];
    }
}
