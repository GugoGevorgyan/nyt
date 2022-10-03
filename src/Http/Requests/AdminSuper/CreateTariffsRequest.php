<?php

declare(strict_types=1);

namespace Src\Http\Requests\AdminSuper;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Src\Models\Tariff\TariffPriceType;

/**
 * Class CreateSystemWorker
 * @package Src\Http\Requests\SystemWorker
 */
class CreateTariffsRequest extends FormRequest
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
        $request = $this->request->all();
        $type = TariffPriceType::findOrFail($request['tariff_type_id']);

        $rules = [
            'name' => ['required', 'string', 'unique:tariffs,name'],
            'car_class_id' => ['required', 'numeric', 'exists:cars_class,car_class_id'],
            'tariff_type_id' => ['required', 'numeric', 'exists:tariff_price_types,tariff_type_id'],
            'payment_type_id' => ['required', 'numeric', 'exists:payment_types,payment_type_id'],
            'paid_parking_client' => ['required', 'numeric'],
            'tool_roads_client' => ['required', 'numeric'],
            'is_default' => ['required', 'numeric'],
            'status' => ['required', 'numeric'],
            'free_wait_minutes' => ['required', 'numeric', 'max:180'],
            'paid_wait_minute' => ['required', 'numeric'],
            'minimal_price' => ['required', 'numeric'],
            'rounding_price' => ['required', 'numeric'],
            'date_from' => ['required', 'date_format:Y-m-d'],
            'date_to' => ['required', 'date_format:Y-m-d'],
            'option' => ['required', 'in:fictional_region,regions_cities,destination,fix_time,rent']
        ];

        $location = [
            'country_id' => ['required', 'exists:countries,country_id'],
            'city_ids' => ['nullable', 'array'],
            'city_ids.*' => ['nullable', 'exists:cities,city_id']
        ];

        $regions_cities = $this->defaultFields($request, $type, 'regions_cities');

        $destination = [
            'destination.price' => ['required', 'numeric'],
            'destination.destination_from_id' => ['required', 'numeric', 'exists:areas,area_id'],
            'destination.destination_to_id' => ['required', 'numeric', 'exists:areas,area_id'],
            'destination.free_wait_stop_minutes' => ['required', 'numeric'],
            'destination.paid_wait_stop_minute' => ['required', 'numeric']
        ];

        $rent = [
            'rent.hours' => ['required', 'numeric'],
            'rent.area_id' => ['required', 'numeric'],
        ];

        switch ($request['option']) {
            case 'regions_cities':
                $rules = array_merge($rules, $location, $regions_cities);
                break;
            case 'destination':
                $rules = array_merge($rules, $destination);
                break;
            case 'rent':
                $rules = array_merge($rules, $location, $rent);
                break;
        }

        return $rules;
    }

    /**
     * @param $request
     * @param $type
     * @param $option
     * @param  string  $pref
     * @return array
     */
    public function defaultFields($request, $type, $option, string $pref = ''): array
    {
        return [
            $pref.$option => ['required', 'array'],
            $pref.$option.'.price_km' => ($type->type === 2 || $type->type === 3) ? ['required', 'numeric'] : '',
            $pref.$option.'.price_min' => ($type->type === 1 || $type->type === 3) ? ['required', 'numeric'] : '',
            $pref.$option.'.sitting_fee' => ['required', 'numeric'],
            $pref.$option.'.sit_price_km' =>
                (2 === $type->type || 3 === $type->type) &&
                $request[$option] &&
                '1' === $request[$option]['sitting_fee'] &&
                !$request[$option]['sit_fix_price'] ?
                    ['required', 'numeric'] : '',
            $pref.$option.'.sitting_fee' =>
                (2 === $type->type || 3 === $type->type) &&
                $request[$option] &&
                '1' === $request[$option]['sitting_fee'] ?
                    ['required', 'numeric'] : '',
            $pref.$option.'.free_wait_stop_minutes' => ['required', 'numeric', 'max:180'],
            $pref.$option.'.paid_wait_stop_minute' => ['required', 'numeric'],
            $pref.$option.'.enable_speed_wait' => ['required', 'numeric'],
            $pref.$option.'.speed_wait_limit' =>
                $request[$option] &&
                '1' === $request[$option]['enable_speed_wait'] ?
                    ['required', 'numeric', 'max:200'] : '',
            $pref.$option.'.speed_wait_price_minute' =>
                $request[$option] &&
                '1' === $request[$option]['enable_speed_wait'] ?
                    ['required', 'numeric'] : '',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [];
    }
}
