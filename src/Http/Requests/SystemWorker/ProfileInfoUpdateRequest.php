<?php

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;

class ProfileInfoUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $data = $this->request->all();

        return  [
            'name' => 'required|min:3|max:99',
            'surname' => 'required|min:3|max:99',
            'patronymic' => 'required|min:3|max:99',
            'nickname' => 'required|unique:system_workers,nickname,'.USER_ID.',system_worker_id|min:3|max:99',
            'email' => 'nullable|unique:system_workers,email,'.USER_ID.',system_worker_id|min:3|max:99',
            'phone' => 'nullable|max:18|min:9',
            'description' => 'nullable|string|max:999',
            'photo_file' => 'nullable|mimes:jpeg,jpg,png|max:512',

            'password' => $data['change_password']? 'required|min:6|max:100': '',
        ];
    }
}
