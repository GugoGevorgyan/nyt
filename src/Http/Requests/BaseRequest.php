<?php

declare(strict_types=1);

namespace Src\Http\Requests;

use Arr;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Src\Exceptions\Lexcept;
use Validator;

/**
 * Class BookkeepingCompanyPaginateRequest
 * @package Src\Http\Requests\SystemWorker
 * @method moreValidation(\Illuminate\Validation\Validator $validator)
 */
abstract class BaseRequest extends FormRequest
{
    /**
     * @return \Illuminate\Validation\Validator
     */
    public function validator(): \Illuminate\Validation\Validator
    {
        $v = Validator::make($this->input(), $this->rules(), $this->messages(), $this->attributes());

        if (method_exists(static::class, 'moreValidation')) {
            static::moreValidation($v);
        }

        return $v;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    abstract public function rules(): array;

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    final public function messages(): array
    {
        return $this->errorMessages();
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    abstract public function errorMessages(): array;

    /**
     * Handle a passed validation attempt.
     *
     * @throws Exception
     */
    protected function passedValidation(): void
    {
        parent::passedValidation();

        if (!config('app.strict') || app()->isProduction()) {
            return;
        }

        $all_with_dots = Arr::dot($this->all());
        $validated_with_dots = Arr::dot($this->validated());

        $not_validated_fields = array_keys(array_diff_key($all_with_dots, $validated_with_dots));

        if (!empty($not_validated_fields)) {
            throw new Lexcept('All request fields must be validated. Please validate these fields: '.implode(', ', $not_validated_fields), 423);
        }

        $rules_with_dots = Arr::dot($this->rules());

        $empty_rules = array_filter($rules_with_dots, static fn($rule) => empty($rule));

        if (!empty($empty_rules)) {
            throw new Lexcept('All request rules must be non-empty. Empty rules: '.implode(', ', array_keys($empty_rules)), 423);
        }
    }
}
