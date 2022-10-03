<?php

namespace Src\Http\Requests\AdminSuper;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Src\Models\Tariff\Tariff;

class CopyTariffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('tariff_id');
        $tariff = Tariff::with('tariff_type')->findOrFail($id);

        $rules = [
            'name' => ['required', 'string', 'unique:tariffs,name'],
            'car_class_id' => ['required', 'numeric', 'exists:cars_class,car_class_id'],
            'minimal_price' => ['required', 'numeric'],
        ];

        return array_merge($rules, $this->typeFields($tariff->tariff_type));
    }

    protected function typeFields($type)
    {
        switch ($type->type) {
            case 1:
                return ['price_min' => ['required', 'numeric']];
            case 2:
                return ['price_km' => ['required', 'numeric']];
            case 3:
                return ['price_min' => ['required', 'numeric'], 'price_km' => ['required', 'numeric']];
            default:
                return [];
        }
    }
}
