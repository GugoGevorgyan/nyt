<?php

declare(strict_types=1);

namespace Src\Support\Collections;

use Closure;
use PHPStan\BetterReflection\Reflection\Exception\PropertyDoesNotExist;

/**
 * Get collection data where exists else Exception
 * take or shit
 */
class Item
{
    /**
     * @return Closure
     */
    public function __invoke(): Closure
    {
        return function (string $name) {
            $worth = 'local' === config('app.env') && config('app.strict');
            $has = $this->has($name);

            if ($worth && !$has) {
                throw new PropertyDoesNotExist($name." doesn't exist in collection", 500);
            }

            return $this->get($name);
        };
    }
}
