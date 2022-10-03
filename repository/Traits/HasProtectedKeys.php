<?php

declare(strict_types=1);

namespace Repository\Traits;

use function in_array;

trait HasProtectedKeys
{
    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        if (in_array($key, $this->protectedKeys, true)) {
            return $this;
        }

        return parent::set($key, $value);
    }
}
