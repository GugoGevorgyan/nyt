<?php

declare(strict_types=1);

namespace Src\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class Lexcept
 * @package Src\Exceptions
 */
class Lexcept extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report(): void
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function render($request): JsonResponse
    {
        return response()->json(['message' => $this->getMessage() ?? 'Server Error'], $this->getCode() ?? 500);
    }
}
