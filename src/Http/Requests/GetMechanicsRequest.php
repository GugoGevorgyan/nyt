<?php

declare(strict_types=1);

namespace Src\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Src\Models\Role\Role;

/**
 * Class GetMechanicsRequest
 * @property mixed page
 * @property mixed per_page
 * @property mixed park
 * @property mixed search
 * @package Src\Http\Requests
 */
class GetMechanicsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return
            ((
                Auth::check() &&
                    Auth::user()->is_admin
            ) ||
                Auth::user()->hasRole(Role::HEAD_PERSONAL_DEPARTMENT_WEB)) ||
            Auth::user()->hasRole(Role::WORKER_PERSONAL_DEPARTMENT_WEB) ||
            Auth::user()->hasRole(Role::TUTOR_WEB);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            $this->request->get('page') => '',
            $this->request->get('per-page') => '',
            $this->request->get('park') => '',
            'search' => '',
        ];
    }
}
