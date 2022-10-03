<?php

declare(strict_types=1);

namespace Repository\Traits;

trait Migratable
{
    /**
     * Get the write alias.
     *
     * @return string
     */
    public function getWriteAlias()
    {
        return $this->getName().'_write';
    }
}
