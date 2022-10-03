<?php

declare(strict_types=1);

namespace Src\Exceptions\Yandex;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class GeocodeException
 * @package Src\Exceptions\Yandex
 */
class GeocodeException extends Exception
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
        return response()->json(['error' => $this->getMessage()], 500);
    }
}
