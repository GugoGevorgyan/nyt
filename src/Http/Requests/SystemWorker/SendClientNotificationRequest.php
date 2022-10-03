<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Src\Core\Enums\ConstGuards;
use Src\Http\Requests\BaseRequest;
use Src\Repositories\Client\ClientContract;

/**
 * @property mixed $text
 * @property mixed $clients
 */
class SendClientNotificationRequest extends BaseRequest
{
    /**
     * @param  clientContract  $clientContract
     */
    public function __construct(protected ClientContract $clientContract)
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
            'text' => ['required', 'max:500', 'string'],
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
            $checked_clients = $this->clientContract
                ->whereIn('client_id', $this->clients)
                ->findAll(['client_id']);

            if ($checked_clients->count() !== count($this->clients)) {
                $validator->errors()->add('client_id', 'Hacked client data');
            }
        });
    }
}
