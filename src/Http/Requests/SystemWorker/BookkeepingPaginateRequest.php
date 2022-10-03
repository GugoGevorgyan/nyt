<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class BookkeepingPaginateRequest
 * @package Src\Http\Requests\SystemWorker
 */
class BookkeepingPaginateRequest extends FormRequest
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
            'page' => [''],
            'per_page' => [''],
            'search' => [''],
            'payment_types' => [''],
            'trans_types' => [''],
            'parks' => [''],
            'driver' => [''],
            'sort_desc' => [''],
            'sort_by' => [''],
            'date_start' => [''],
            'date_end' => [''],
            'payed' => [''],
        ];
    }
}
