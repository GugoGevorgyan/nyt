<?php
declare(strict_types=1);

namespace Src\Exceptions\Passport;

use Exception;
use Throwable;

/**
 * Class NotGuardException
 * @package Src\Exceptions\Passport
 */
class NotGuardException extends Exception
{
    protected $message;

    /**
     * Construct the exception. Note: The message is NOT binary safe.
     * @link https://php.net/manual/en/exception.construct.php
     * @param  string  $message  [optional] The Exception message to throw.
     * @param  int  $code  [optional] The Exception code.
     * @param  Throwable  $previous  [optional] The previous throwable used for the exception chaining.
     * @since 5.1.0
     */
    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        $this->message = $message;

        parent::__construct();
    }

    public function messages(): string
    {
        return $this->message;
    }
}
