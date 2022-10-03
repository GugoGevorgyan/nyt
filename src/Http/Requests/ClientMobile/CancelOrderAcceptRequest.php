<?php

declare(strict_types=1);

namespace Src\Http\Requests\ClientMobile;

use Illuminate\Support\Facades\Auth;
use Src\Core\Enums\ConstGuards;
use Src\Http\Requests\BaseRequest;

/**
 *
 * @property mixed $accept
 */
class CancelOrderAcceptRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::guard(ConstGuards::CLIENTS_API()->getValue())->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'accept' => ['required', 'bool']
        ];
    }

    /**
     * @return array
     */
    public function errorMessages(): array
    {
        return [];
    }
}
