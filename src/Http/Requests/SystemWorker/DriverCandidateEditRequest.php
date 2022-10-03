<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Src\Models\Role\Role;
use Src\Repositories\DriverCandidate\DriverCandidateContract;

/**
 * Class DriverCandidateEditRequest
 * @property mixed image
 * @property mixed info
 * @property mixed candidate
 * @package Src\Http\Requests
 */
class DriverCandidateEditRequest extends FormRequest
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
    public function rules(): array
    {
        $id = $this->route('candidate_id');
        $candidate = app(DriverCandidateContract::class)->withTrashed()->findOrFail($id);

        return [
            'candidate.phone' => ['required', 'max:20', 'min:9'],
            'candidate.learn_status_id' => ['required', 'exists:learn_statuses,learn_status_id'],
            'candidate.learn_start' => ['nullable', 'date', 'before_or_equal:candidate.learn_end'],
            'candidate.learn_end' => ['nullable', 'date', 'after_or_equal:candidate.learn_start'],
            'candidate.tutor_id' => ['required', 'exists:system_workers,system_worker_id'],

            'info.name' => ['required', 'min:3', 'max:16'],
            'info.surname' => ['required', 'min:3', 'max:16'],
            'info.patronymic' => ['required', 'min:3', 'max:16'],
            'info.email' => ['nullable', 'email'],
            'info.birthday' => ['required', 'date_format:Y-m-d', 'before:-18 years'],
            'info.license_type_ids' => ['required', 'array'],
            'info.license_type_ids.*' => ['required', 'exists:driver_license_types,driver_license_type_id'],
            'info.license_code' => ['string', 'min:6', 'max:16'],
            'info.experience' => ['required', 'numeric', 'max:99'],
            'info.passport_serial' => ['required', 'max:16', 'min:2'],
            'info.photo_file' => ['nullable', 'mimes:jpg,png,jpeg'],
            'info.passport_scan' => ['nullable'],
            'info.license_scan_file' => ['nullable', 'mimes:jpg,png,jpeg'],
            'info.license_qr_code_file' => ['nullable', 'sometimes', 'mimes:jpg,png,jpeg'],
            'info.passport_number' => ['required', 'max:16', 'min:6'],
            'info.passport_issued_by' => ['required'],
            'info.passport_when_issued' => ['required','date'],
            'info.citizen' => ['required'],
            'info.address' => ['required'],
            'info.id_kis_art' => ['nullable']
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [];
    }
}
