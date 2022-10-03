<?php

declare(strict_types=1);

namespace Src\Http\Requests\App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Src\Http\Requests\BaseRequest;
use Src\Repositories\Preorder\PreorderContract;

/**
 *
 * @property int $order_id
 * @property string $date
 */
class ChangePreOrderDateRequest extends BaseRequest
{
    /**
     * @param  PreorderContract  $preorderContract
     */
    public function __construct(protected PreorderContract $preorderContract)
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
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'order_id' => ['required'],
            'date' => ['required', 'date_format:Y-m-d H:i', 'after:'.now()],
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
        $this->merge(['order_id' => $this->order_id, 'date' => $this->date]);
    }

    /**
     * @param  Validator  $validator
     */
    public function moreValidation(Validator $validator): void
    {
        $validator->after(function () use ($validator) {
            $preorder = $this->preorderContract
                ->where('order_id', '=', $this->order_id)
                ->where('time', '>', f_now())
                ->findFirst(['order_id', 'time', 'active', 'create_time']);

            if (!$preorder) {
                $validator->errors()->add('date', 'this order is timeout');
            }
        });
    }
}
