<?php
declare(strict_types=1);

namespace Src\Exceptions\Role;

use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * Class GuardDoesNotMatch
 * @package Src\Exceptions\WorkerRole
 */
class GuardDoesNotMatch extends InvalidArgumentException
{
    /**
     * @param  string  $givenGuard
     * @param  Collection  $expectedGuards
     * @return GuardDoesNotMatch
     */
    public static function create(string $givenGuard, Collection $expectedGuards)
    {
        return new static("The given role or permission should use guard `{$expectedGuards->implode(', ')}` instead of `{$givenGuard}`.");
    }
}
