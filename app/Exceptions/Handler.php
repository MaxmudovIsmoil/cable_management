<?php

namespace App\Exceptions;

use http\Env\Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Exception;


class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof NotFoundException) {
            return response()->error($e->getMessage(), $e->getCode());
        }

        if ($e instanceof UnauthorizedException) {
            return response()->error($e->getMessage(), $e->getCode());
        }

        return parent::render($request, $e);
    }
}
