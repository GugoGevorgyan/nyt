<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Support\Facades\Hash;
use Src\Http\Requests\BaseRequest;

/**
 * @property array $driver
 */
class UpdateDriverInfoRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $driver_id = $this->route('driver_id');
        $driver_info_id = $this->route('driver_info_id');

        return [
            'driver_info.name' => 'required|string',
            'driver_info.surname' => 'required|string',
            'driver_info.patronymic' => 'string',
            'driver_info.citizen' => 'required|string',
            'driver_info.birthday' => 'required',
            'driver_info.address' => 'required',
            'driver_info.email' => 'required|unique:drivers_info,email,'.$driver_info_id.',driver_info_id|email',
            'driver.mean_assessment' => 'required|max:5',
            'driver.rating' => 'required|max:999',
            'driver.phone' => 'required|unique:drivers,phone,'.$driver_id.',driver_id',
            'driver.password' => "required_if:driver.password,!=,''",
            'driver.nickname' => 'required',
        ];
    }

    /**
     * @return array
     */
    public function errorMessages(): array
    {
        return [
            //
        ];
    }
}
