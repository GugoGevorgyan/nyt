<?php

declare(strict_types=1);

namespace Src\Http\Requests\Driver;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Src\Core\Enums\ConstGuards;
use Src\Http\Requests\BaseRequest;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;

/**
 * @property mixed $hash
 * @property mixed $order_id
 */
class DriverrInPlaceRequest extends BaseRequest
{
    /**
     * Create a new rule instance.
     */
    public function __construct(protected OrderShippedDriverContract $shippedContract)
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
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'exists:orders,order_id'],
            'hash' => ['required', 'exists:order_shipped_drivers,in_place_hash']
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
        $this->merge(['hash' => $this->hash, 'order_id' => (int)($this->order_id ?? 0)]);
    }

    /**
     * @param  Validator  $validator
     */
    public function moreValidation(Validator $validator): void
    {
        $validator->after(function () use ($validator) {
            $data = $this->shippedContract
                ->where('in_place_hash', '=', $this->hash)
                ->where('driver_id', '=', get_user_id())
                ->whereHas('order', fn(Builder $q_on_way) => $q_on_way->where('order_id', '=', $this->order_id))
                ->exists();

            if (!$data) {
                $validator->errors()->add('payload', 'empty request body');
            }
        });
    }
}
