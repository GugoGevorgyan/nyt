<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use JetBrains\PhpStorm\ArrayShape;
use Src\Core\Enums\ConstGuards;
use Src\Http\Requests\BaseRequest;
use Src\Repositories\Order\OrderContract;

/**
 *
 */
class CallCenterOrderCommentCreateRequest extends BaseRequest
{
    /**
     * @param  OrderContract  $orderContract
     */
    public function __construct(protected OrderContract $orderContract)
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
        return Auth::check() && Auth::guard(ConstGuards::SYSTEM_WORKERS_WEB()->getValue())->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape([
        'order_id' => 'string',
        'text' => 'string',
        'for_driver' => 'bool',
    ])]
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'exists:orders,order_id'],
            'text' => ['required', 'min:1', 'max:2500'],
            'for_driver' => ['required', 'bool'],
        ];
    }

    public function errorMessages(): array
    {
        return [];
    }

    /**
     * @param  Validator  $validator
     */
    public function moreValidation(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if ($this->for_driver) {
                $order = $this->orderContract
                    ->with(['driver' => fn($query) => $query->select(['drivers.driver_id'])])
                    ->find($this->order_id, ['order_id']);

                if (!$order->driver) {
                    $validator->errors()->add('order_id', 'order has not a driver');
                }
            }
        });
    }
}
