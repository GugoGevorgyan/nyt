<?php

declare(strict_types=1);

namespace Src\Support\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class ExistsRedis
 * @package Src\Support\Rules
 */
class ExistsRedis implements Rule
{
    /**
     * @var string
     */
    protected string $message = 'The validation error message.';

    /**
     * Create a new rule instance.
     *
     * @param $db_key_name
     * @param $db_key_value
     */
    public function __construct(protected $db_key_name, protected $db_key_value)
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     * @noinspection MultipleReturnStatementsInspection
     */
    public function passes($attribute, $value): bool
    {
        $result = redis()->hmget($this->db_key_name, [$this->db_key_value]);

        if (!$result) {
            $this->message = (string)trans('validation.custom.rules.Your confirmation key has expired');
            return false;
        }

        if ((int)$result[0] !== (int)$value) {
            $this->message = (string)trans('validation.custom.rules.Invalid confirmation code');
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }
}
