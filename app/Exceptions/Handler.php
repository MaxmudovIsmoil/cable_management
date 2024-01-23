<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;


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
            return response()->fail($e->getMessage(), $e->getCode());
        }

        if ($e instanceof UnauthorizedException) {
            return response()->fail($e->getMessage(), $e->getCode());
        }


        return parent::render($request, $e);
    }


    public function register(): void
    {
        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->fail('Record not found.', 404);
            }
        });
    }
}
