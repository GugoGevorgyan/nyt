<?php

declare(strict_types=1);

namespace Src\Core\Contracts;

/**
 *
 */
interface Realizing
{
    /**
     * @param ...$params
     * @return mixed
     */
    public function runner(...$params): void;
}
