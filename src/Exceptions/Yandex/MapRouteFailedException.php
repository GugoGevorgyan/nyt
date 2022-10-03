<?php

declare(strict_types=1);


namespace Src\Exceptions\Yandex;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class MapRouteFailedException
 * @package Src\Exceptions\Yandex
 */
class MapRouteFailedException extends \Exception
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
    public function render($request)
    {
        return response()->json(['error' => $this->getMessage()], 500);
    }
}
