<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Support\Facades\Auth;
use Src\Http\Requests\BaseRequest;

/**
 *
 */
class GetClientsRequest extends BaseRequest
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
            'page' => ['required'],
            'per_page' => ['required'],
            'search' => ['nullable'],
            'sort_by' => ['nullable'],
            'sort_desc' => ['nullable'],
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
