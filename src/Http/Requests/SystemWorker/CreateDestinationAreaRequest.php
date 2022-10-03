<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/16/2019
 * Time: 3:41 PM
 */

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;

class CreateDestinationAreaRequest extends FormRequest
{

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [];
    }
}
