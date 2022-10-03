<?php

declare(strict_types=1);

namespace Src\Http\Requests\ClientMobile;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class GetFavoriteAddressRequest
 * @property mixed favorite
 * @package Src\Http\Requests\ClientMobile
 */
class GetFavoriteAddressRequest extends FormRequest
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
            'favorite' => ['boolean', 'required']
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        $this->request->add(['favorite' => (boolean)$this->favorite]);

        return $this->request->all();
    }
}
