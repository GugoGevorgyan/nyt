<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Src\Core\Enums\ConstGuards;
use Src\Http\Requests\BaseRequest;
use Src\Repositories\Driver\DriverContract;

/**
 * @property mixed $text
 * @property mixed $clients
 */
class SendDriverNotificationRequest extends BaseRequest
{
    /**
     * @param  DriverContract  $driverContract
     */
    public function __construct(protected DriverContract $driverContract)
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
    public function rules(): array
    {
        return [
            'title' => ['required', 'max:50', 'string'],
            'text' => ['required', 'max:300', 'string'],
            'clients' => ['required', 'array']
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
     * @param  Validator  $validator
     */
    public function moreValidation(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $driver_check = true;

            $checked_drivers = $this->driverContract
                ->whereIn('driver_id', $this->clients)
                ->findAll(['driver_id', 'current_franchise_id'])
                ->map(function ($item) use (&$driver_check) {
                    if (FRANCHISE_ID !== $item->current_franchise_id) {
                        $driver_check = false;
                    }

                    return $item;
                });

            if (!$driver_check || $checked_drivers->count() !== count($this->clients)) {
                $validator->errors()->add('driver_id', 'Hacked driver data');
            }
        });
    }
}
