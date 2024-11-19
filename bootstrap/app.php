<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

function errorResponse($message, $code)
{
    return response()->json([
        'status' => 'Error',
        'message' => $message,
        'data' => null,
    ], $code);
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (\Illuminate\Validation\ValidationException $e, $request) {
            if ($request->is('api/*')) {
                // return new ErrorResponse(
                //     errors: $e->validator->errors()->all(),
                //     error_code: Code::VALIDATION,
                //     message: __('validation error')
                // );
                // dd('aaa');
                return errorResponse($e->validator->errors()->all(), 401);
            }
        });

        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $e, $request) {
            if ($request->is('api/*')) {

                return errorResponse(['إتأكد من الميثود يا بييييه'], 401);
            }
        });
    })->create();
