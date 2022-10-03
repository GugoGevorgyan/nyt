<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Src\Core\Enums\ConstGuards;

/**
 *
 * @property mixed $per_page
 * @property mixed $page
 * @property mixed $search
 * @property mixed $date_start
 * @property mixed $date_end
 * @property mixed $sort_by
 * @property mixed $sort_desc
 */
class PenaltyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::guard(ConstGuards::SYSTEM_WORKERS_WEB()->getValue())->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'per_page' => ['required'],
            'page' => ['required'],
            'search' => [],
            'date_start' => [],
            'date_end' => [],
            'sort_by' => [],
            'sort_desc' => [],
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
     * Prepare the data for validation.
     *
     * @return void
     */
    public function prepareForValidation()
    {
        $this->merge([
            'per_page' => $this->per_page,
            'page' => $this->page,
            'search' => $this->search,
            'sort_by' => $this->sort_by,
            'sort_desc' => $this->sort_desc,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end
        ]);
    }
}
