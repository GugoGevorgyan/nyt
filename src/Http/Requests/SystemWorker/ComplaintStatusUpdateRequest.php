<?php

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;

class ComplaintStatusUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'complaint_id' => 'required|exists:complaints,complaint_id',
            'status_id' => 'required|exists:complaint_statuses,complaint_status_id'
        ];
    }
}
