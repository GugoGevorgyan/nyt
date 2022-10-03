<?php

declare(strict_types=1);

namespace Src\Exceptions\Order;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class TimerJobException
 * @package Src\Exceptions\Order
 */
class TimerJobException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
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
        return response()->json(['error' => $this->getMessage()], $this->getCode());
    }
}
