<?php

declare(strict_types=1);

namespace App;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use NotificationChannels\Fcm\Exceptions\CouldNotSendNotification;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class Handler
 * @package Src\Exceptions
 */
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  Throwable  $exception
     * @return void
     * @throws Exception|Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param  Throwable  $exception
     * @return Response
     * @throws Throwable
     * @noinspection MultipleReturnStatementsInspection
     */
    public function render($request, Throwable $exception): Response
    {
        if ($exception instanceof ThrottleRequestsException && 'GET' !== $request->method()) {
            return response(['message' => $exception->getMessage()], 429);
        }

        if ($exception instanceof CouldNotSendNotification) {
            return response(['message' => $exception->getMessage()], 422);
        }

        if (trans('messages.pusher_error') === $exception->getMessage()) {
            return response(['message' => 'TCP Connection error'], 502);
        }

        return parent::render($request, $exception);
    }
}
