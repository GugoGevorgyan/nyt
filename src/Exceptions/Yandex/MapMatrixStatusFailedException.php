<?php

declare(strict_types=1);


namespace Src\Exceptions\Yandex;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class MapMatrixStatusFailedException
 * @package Src\Exceptions\Yandex
 */
class MapMatrixStatusFailedException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'Map Api Matrix Status Failed';

    /**
     * @var int
     */
    protected $code = 400;

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
        return response()->json(['error' => $this->getMessage()]);
    }
}
