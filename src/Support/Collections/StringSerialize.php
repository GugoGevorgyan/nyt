<?php

declare(strict_types=1);


namespace Src\Support\Collections;


use Closure;

/**
 * Class StringSerialize
 * @package Src\Support\Collections
 */
class StringSerialize
{
    /**
     * @return Closure
     */
    public function __invoke(): Closure
    {
        return function (string $name) {
            return json_decode($this->get($name), true, 512, JSON_THROW_ON_ERROR);
        };
    }
}
