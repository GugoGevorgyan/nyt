<?php

declare(strict_types=1);

namespace Src\Support\Rules\Cords;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class ValidLatitude
 * @package Src\Support\Rules
 */
class ValidLatitude implements Rule
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
     * @param $lat
     * @return bool|int
     * @copyright '/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/'
     */
    public function passes($attribute, $lat): bool|int
    {
        $latitude = (string)$lat;
        return preg_match('/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,15})?))$/', $latitude);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Latitude non valid.';
    }
}
