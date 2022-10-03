<?php

declare(strict_types=1);

namespace Src\Http\Requests\SystemWorker;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Src\Models\Driver\DriverSubtype;
use Src\Models\Role\Role;

/**
 * Class CreateDriverFromCandidateRequest
 * @package Src\Http\Requests\SystemWorker
 */
class CreateDriverFromCandidateRequest extends FormRequest
{
    /**@return bool
     * @todo
     * Determine if the user is authorized to make this request.
     *
     */
    public function authorize(): bool
    {
        return Auth::check() && (Auth::user()->is_admin || Auth::user()->hasRole(Role::WORKER_PERSONAL_DEPARTMENT_WEB));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $subType = $this->subtype_id ? DriverSubtype::findOrFail($this->subtype_id) : null;
        $driver_id = isset($this->request->all()['driver_id']) && $this->request->all()['driver_id'];

        $rules = [
            'candidate_id' => 'required|exists:driver_candidates,driver_candidate_id',
            'graphic_id' => 'required|exists:driver_graphics,driver_graphic_id',
            'type_id' => 'required|exists:driver_types,driver_type_id',
            'subtype_id' => 'required|exists:driver_subtypes,driver_subtype_id',
            'free_days_price' => 'required|numeric',
            'busy_days_price' => 'required|numeric',
            'entity_id' => $subType && in_array($subType->value, [
                'tenant_individual_entrepreneur',
                'aggregator_individual_entrepreneur',
                'will_tell_individual_entrepreneur',
                'corporate_individual_entrepreneur'
            ]) ? 'required|exists:legal_entities,legal_entity_id' : '',

            'expiration_day' => 'required|date|after:today',
            'work_start_day' => 'required|date|after_or_equal:today|before_or_equal:expiration_day'
        ];

        if (!$driver_id) {
            $rules['nickname'] = 'required|min:3|max:16|unique:drivers,nickname';
            $rules['phone'] = 'required|min:6|max:32|unique:drivers,phone';
            $rules['password'] = 'required|min:3|max:16';
        }

        return $rules;
    }
}
