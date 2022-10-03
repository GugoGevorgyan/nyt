<?php
declare(strict_types=1);

namespace ServiceEntity\Exceptions;

use Exception;

/**
 * Class ServiceException
 * @package ServiceEntityStory\Exceptions
 */
class ServiceException extends Exception
{
    /**
     * The exception description.
     *
     * @var string
     */
    protected $message = 'Could not determine what you are trying to do. Sorry! Check your migration name.';
}
