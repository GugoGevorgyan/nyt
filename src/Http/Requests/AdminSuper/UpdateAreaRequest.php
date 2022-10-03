<?php

namespace Src\Http\Requests\AdminSuper;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 *
 */
class UpdateAreaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape([
        'title' => 'string[]',
        'area' => 'string[]',
        'description' => 'array'
    ])]
    public function rules(): array
    {
        $id = $this->route('area_id');
        return [
            'title' => ['required', 'unique:areas,title,'.$id.',area_id'],
            'area' => ['required', 'array'],
            'description' => ['nullable']
        ];
    }
}
