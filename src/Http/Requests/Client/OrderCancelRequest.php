<?php

declare(strict_types=1);

namespace Src\Http\Requests\Client;

use Auth;
use Src\Core\Enums\ConstGuards;
use Src\Http\Requests\BaseRequest;

/**
 * Class OrderCancelRequest
 * @package Src\Http\Requests\ClientMessage
 */
class OrderCancelRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::guard(ConstGuards::CLIENTS_WEB()->getValue())->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function errorMessages(): array
    {
        return [];
    }
}
