<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;
use Src\Support\Rules\Driver\DriverHasCurrentAnnulledWaybillRule;

/**
 * Class WaybillsRestoreRequest
 * @package Src\Http\Requests\SystemWorker
 */
class WaybillsRestoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'driver_id' => ['required', 'exists:drivers,driver_id', new DriverHasCurrentAnnulledWaybillRule]
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        $this->merge([
            'driver_id' => (int)$this->driver_id
        ]);

        return $this->all();
    }
}
