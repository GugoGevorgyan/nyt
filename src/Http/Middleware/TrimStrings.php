<?php

namespace Src\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

class TrimStrings extends Middleware
{
    /**
     * The names of the attributes that should not be trimmed.
     *
     * @var array
     */
    protected $except = [
        'password',
        'password_confirmation',
    ];

    /**
     * Transform the given value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value): mixed
    {
        if ('true' === $value || 'TRUE' === $value) {
            return true;
        }

        if ('false' === $value || 'FALSE' === $value) {
            return false;
        }

        return $value;
    }
}
