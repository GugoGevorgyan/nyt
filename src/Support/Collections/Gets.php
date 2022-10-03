<?php

declare(strict_types=1);


namespace Src\Support\Collections;


use Closure;
use Illuminate\Support\Collection;

/**
 * Class Gets
 * @package Src\Support\Collections
 * @method Collection gets(string $name)
 */
class Gets
{
    /**
     * @return Closure
     */
    public function __invoke(): Closure
    {
        return function (string $name) {
            $name_key = explode('.', $name)[0];
            $properties = substr($name, strpos($name, '.') + 1);
            $collections = $this;

            if (!$collections->has($name_key)) {
                return collect();
            }

            return $collections->get($name_key)[$properties];
        };
    }
}
