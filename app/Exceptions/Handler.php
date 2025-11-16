<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Custom render untuk throttle login.
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof TooManyRequestsHttpException) {
            return response()->json([
                'message' => 'Terlalu banyak percobaan login. Coba lagi nanti.',
                'retry_after' => $exception->getHeaders()['Retry-After'] ?? 60
            ], 429);
        }

        return parent::render($request, $exception);
    }
}
