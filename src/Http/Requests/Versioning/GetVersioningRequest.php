<?php

declare(strict_types=1);

namespace Src\Http\Requests\Versioning;

use Src\Http\Requests\BaseRequest;

/**
 *
 * @property mixed $key
 */
class GetVersioningRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'key' => [
                'required',
                'exists:versioning,auth_key'
            ]
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
     * @return array
     */
    public function validationData(): array
    {
        $this->replace(['key' => $this->header('authDevice')]);
        return $this->all();
    }
}
