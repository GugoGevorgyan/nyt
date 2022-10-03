<?php

namespace Src\Exceptions\Support;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;

/**
 *
 */
class PropertyNotExists extends Exception
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
