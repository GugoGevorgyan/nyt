<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Src\Core\Enums\ConstGuards;
use Src\Http\Requests\BaseRequest;
use Src\Models\Driver\DriverStatus;
use Src\Repositories\Driver\DriverContract;

/**
 *
 * @property mixed $order_id
 * @property mixed $driver_id
 */
class DriverUnpinOrderRequest extends BaseRequest
{
    protected DriverContract $driverContract;

    /**
     * @var int|null
     */
    private ?int $orderId = null;

    public function __construct()
    {
        $this->driverContract = app(DriverContract::class);

        parent::__construct();
    }

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
            'driver_id' => ['required', 'exists:drivers,driver_id'],
            'order_id' => ['required', 'exists:orders,order_id']
        ];
    }

    /**
     * @return array
     */
    public function errorMessages(): array
    {
        return [];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    public function prepareForValidation(): void
    {
        $driver = $this->driverContract
            ->with(['current_order' => fn($query) => $query->select(['orders.order_id'])])
            ->where('current_status_id', '=', DriverStatus::DRIVER_ON_ACCEPT)
            ->orWhere('current_status_id', '=', DriverStatus::DRIVER_ON_WAY)
            ->find($this->driver_id, ['driver_id', 'current_status_id']);

        if (!$driver || !$driver->current_order) {
            return;
        }

        $this->orderId = $driver->current_order->order_id;

        $this->merge(['order_id' => $driver->current_order->order_id, 'driver_id' => $this->driver_id]);
    }

    /**
     * @param  Validator  $validator
     */
    public function moreValidation(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if (!$this->orderId) {
                $validator->errors()->add('order_id', 'driver bla bla');
            }
        });
    }
}
