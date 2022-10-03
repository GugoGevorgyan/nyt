<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class WaybillsPaginateRequest
 * @property mixed page
 * @property mixed search
 * @property mixed per_page
 * @property mixed filter
 * @package Src\Http\Requests\SystemWorker
 */
class WaybillsPaginateRequest extends FormRequest
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
            'page' => ['required'],
            'per_page' => ['required'],

            'filter.search' => [''],
            'filter.drivers' => [''],
            'filter.parks' => [''],
            'filter.date_start' => [''],
            'filter.date_end' => [''],
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        $this->replace(
            [
                'page' => (int)$this->page,
                'per_page' => (int)$this->per_page,
                'filter' => [
                    'search' => (string)$this->search,
                    'drivers' => $this->drivers,
                    'parks' => $this->parks,
                    'date_start' => $this->date_start,
                    'date_end' => $this->date_end,
                ]
            ]
        );

        return $this->all();
    }
}
