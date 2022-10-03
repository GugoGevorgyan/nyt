<?php

declare(strict_types=1);

namespace Src\Http\Requests\Driver;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property mixed driver_id
 * @property mixed franchise_id
 */
class UpdateFranchiseRequest extends FormRequest
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
        return [
            'franchise_id' => ['required', Rule::in($this->subscribedFranchisee())],
        ];
    }

    /**
     * @return array
     */
    private function subscribedFranchisee()
    {
        return auth()->user()->franchisee->pluck('franchise_id');
    }

    /**
     * @return array|string[]
     */
    public function messages(): array
    {
        return [
            'franchise_id.required' => 'franchise_id required',
            'franchise_id.in' => 'Driver did not subscribed for this franchise',
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData(): array
    {
        $this->request->replace(['franchise_id' => (int)$this->franchise_id]);
        return $this->all();
    }
}
