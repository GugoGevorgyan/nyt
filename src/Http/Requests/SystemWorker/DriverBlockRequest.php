<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Support\Facades\Auth;
use Src\Http\Requests\BaseRequest;

/**
 *
 * @property mixed $id
 * @property mixed $minute
 */
class DriverBlockRequest extends BaseRequest
{
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
            'id' => ['required', 'exists:drivers,driver_id'],
            'minute' => ['required'],
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
