<?php

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;

class WorkerDeleteMultipleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; /*@todo*/
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'worker_ids' => 'required|array',
            'worker_ids.*' => 'required|exists:system_workers,system_worker_id',
            'password' => 'required|min:5|max:32'
        ];
    }
}
