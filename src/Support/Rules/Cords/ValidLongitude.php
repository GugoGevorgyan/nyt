<?php

declare(strict_types=1);


namespace Src\Support\Rules\Cords;


use Illuminate\Contracts\Validation\Rule;

/**
 * Class ValidLongitude
 * @package Src\Support\Rules\Cords
 */
class ValidLongitude implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param $long
     * @return bool|int
     * @copyright '/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/'
     */
    public function passes($attribute, $long): bool|int
    {
        if (!$long) {
            return true;
        }

        $longitude = (string)$long;
        return preg_match('/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,15})?))$/', $longitude);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Longitude non valid.';
    }
}
