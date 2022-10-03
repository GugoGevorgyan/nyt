<?php

declare(strict_types=1);

namespace Src\Http\Requests\Driver;

use Illuminate\Support\Facades\Auth;
use Src\Core\Enums\ConstGuards;
use Src\Http\Requests\BaseRequest;

/**
 *
 * @property mixed $online
 */
class ChangeOnlineRequest extends BaseRequest
{
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
            'online' => ['required', 'boolean']
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
