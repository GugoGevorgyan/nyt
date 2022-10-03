<?php
declare(strict_types=1);


namespace Src\Support\Facades;

use Illuminate\Support\Facades\Facade;
use RuntimeException;

/**
 * Class HandlerFacade
 * @package Src\Facades
 */
class HandlerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return 'HandlerFacade';
    }
}
