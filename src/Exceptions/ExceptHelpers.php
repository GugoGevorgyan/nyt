<?php
declare(strict_types=1);


namespace Src\Exceptions;


/**
 * Class Helpers
 * @package Src\Exceptions
 */
class ExceptHelpers
{
    /**
     *
     */
    public const DANGER = 'danger';
    /**
     *
     */
    public const WARNING = 'warning';
    /**
     *
     */
    public const INFO = 'info';
    /**
     *
     */
    public const SUCCESS = 'SUCCESS';

    /**
     * @return string
     */
    public function danger(): string
    {
        return self::DANGER;
    }

    /**
     * @return string
     */
    public function warning(): string
    {
        return self::WARNING;
    }

    /**
     * @return string
     */
    public function info(): string
    {
        return self::INFO;
    }

    /**
     * @return string
     */
    public function success(): string
    {
        return self::SUCCESS;
    }
}
