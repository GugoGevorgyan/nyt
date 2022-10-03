<?php

declare(strict_types=1);

namespace Src\Http\Requests\Driver;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Validator;
use JetBrains\PhpStorm\ArrayShape;
use Src\Core\Enums\ConstGuards;
use Src\Http\Requests\BaseRequest;
use Src\Models\Driver\DriverStatus;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;

/**
 * Class OrderOnWayRequest
 * @property mixed $hash
 * @property mixed response
 * @property mixed $selected_route
 * @property mixed $order_id
 * @property mixed $accept
 * @package Src\Http\Requests\Driver
 */
class OrderOnWayRequest extends BaseRequest
{
    /**
     * @param  OrderShippedDriverContract  $shippedContract
     * @param  OrderContract  $orderContract
     */
    public function __construct(protected OrderShippedDriverContract $shippedContract, protected OrderContract $orderContract)
    {
        parent::__construct();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::guard(ConstGuards::DRIVERS_API()->getValue())->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape([
        'order_id' => 'string[]',
        'hash' => 'string[]',
        'selected_route' => 'string[]',
        'accept' => 'string[]'
    ])]
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'exists:orders,order_id'],
            'hash' => ['required', 'exists:order_shipped_drivers,on_way_hash'],
            'selected_route' => ['nullable', 'exists:order_on_way_roads,order_on_way_road_id'],
            'accept' => ['nullable', 'bool']
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
        $this->merge([
            'order_id' => (int)$this->order_id,
            'hash' => $this->hash,
            'selected_route' => $this->selected_route ? (int)$this->selected_route : null,
            'accept' => (boolean)($this->accept ?? false)
        ]);

        parent::prepareForValidation();
    }

    /**
     * @param  Validator  $validator
     */
    public function moreValidation(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (!$this->selected_route && $this->orderContract->has('preorder')->find($this->order_id, ['order_id'])) {
                $shipped = $this->shippedContract
                    ->where('on_way_hash', '=', $this->hash)
                    ->where('driver_id', '=', get_user_id())
                    ->exists();

                if (!$shipped) {
                    $validator->errors()->add('client_id', 'Hacked client data');
                    return false;
                }

                return true;
            }

            $data = $this->shippedContract
                ->where('on_way_hash', '=', $this->hash)
                ->where('driver_id', '=', get_user_id())
                ->whereHas(
                    'on_way_roads',
                    fn(Builder $q_on_way) => $q_on_way->where('order_on_way_road_id', '=', $this->selected_route)
                )
                ->whereHas(
                    'driver',
                    fn(Builder $q_driver) => $q_driver->where('current_status_id', '=', DriverStatus::getStatusId(DriverStatus::DRIVER_ON_ACCEPT))
                )
                ->whereHas(
                    'order',
                    fn(Builder $q_on_way) => $q_on_way->where('order_id', '=', $this->order_id)
                )
                ->exists();

            if (!$data) {
                $validator->errors()->add('client_id', 'Hacked client data');
                return false;
            }

            return true;
        });
    }
}
