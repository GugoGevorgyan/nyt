<?php

declare(strict_types=1);

namespace Src\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class OrderLateRequest
 * @property mixed minute
 * @package Src\Http\Requests\Driver
 */
class OrderLateRequest extends FormRequest
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
            'minute' => ['numeric']
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        $this->replace(['minute' => (int)$this->minute]);
        return $this->all();
    }
}
