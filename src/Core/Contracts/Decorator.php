<?php

declare(strict_types=1);

namespace Src\Core\Contracts;

/**
 *
 */
interface Decorator
{
    /**
     * @param  Realizing  $app
     * @return static
     */
    public static function decorate(Realizing $app): self;

    /**
     * @param  Realizing  $app
     * @return $this
     */
    public function setApp(Realizing $app): static;

    /**
     * @return void
     */
    public function annul(): void;
}
