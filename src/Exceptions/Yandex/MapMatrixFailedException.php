<?php

declare(strict_types=1);


namespace Src\Exceptions\Yandex;


/**
 * Class MapMatrixFailedException
 * @package Src\Exceptions\Yandex
 */
class MapMatrixFailedException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'Map Api Matrix Error';

    /**
     * @var int
     */
    protected $code = 400;
}
