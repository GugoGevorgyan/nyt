<?php

namespace Src\Http\Requests\AdminSuper;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Src\Models\SystemUsers\SystemWorker;

class UpdateFranchiseAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('system_worker_id');

        return [
            'name' => 'required|min:3|max:36',
            'surname' => 'nullable|min:3|max:36',
            'patronymic' => 'nullable|min:3|max:36',
            'nickname' => 'required|min:3|max:36|unique:system_workers,nickname,'.$id.',system_worker_id',
            'email' => 'nullable|email|min:3|max:36',
            'phone' => 'nullable|min:3|max:36',
            'change_password' => 'required|boolean',
            'password' => 'required_if:change_password,==,1|min:0|max:36',
        ];
    }
}
