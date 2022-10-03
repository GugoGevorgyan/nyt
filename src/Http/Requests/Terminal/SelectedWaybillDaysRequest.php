<?php

declare(strict_types=1);

namespace Src\Http\Requests\Terminal;

use Illuminate\Support\Facades\Auth;
use Src\Core\Enums\ConstGuards;
use Src\Http\Requests\BaseRequest;

/**
 * Class SelectedWaybillDaysRequest
 * @property mixed days
 * @package Src\Http\Requests\Terminal
 */
class SelectedWaybillDaysRequest extends BaseRequest
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
           'days' => ['required', 'gte:1', 'lte:7']
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
        $this->merge(['days' => (int)$this->days]);
    }
}
