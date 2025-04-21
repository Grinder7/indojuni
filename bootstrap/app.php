<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: 'api',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (HttpException $exception, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => $exception->getStatusCode(),
                    'message' => $exception->getMessage(),
                    'data' => null,
                ], $exception->getStatusCode());
            }
        });

        $exceptions->renderable(function (ValidationException $exception, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => $exception->status,
                    'message' => 'Validation Error! |' . $exception->getMessage(),
                    'data' => $exception->errors(),
                ], 422);
            }
        });

        $exceptions->renderable(function (AuthenticationException $exception, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized! | ' . $exception->getMessage(),
                    'data' => null,
                ], 401);
            }
        });

        // $this->renderable(function (NotFoundHttpException $exception, $request) {
        //     if ($request->is('api/*')) {
        //         return response()->json([
        //             'status' => 404,
        //             'message' => 'Not Found!',
        //             'data' => null,
        //         ], 404);
        //     }
        // });

        // $this->renderable(function (ValidationException $exception, $request) {
        //     if ($request->is('api/*')) {
        //         return response()->json([
        //             'status' => 400,
        //             'message' => 'Bad Request!',
        //             'data' => $exception->errors(),
        //         ], 400);
        //     }
        // });

        // $this->renderable(function (AuthenticationException $exception, $request) {
        //     if ($request->is('api/*')) {
        //         return response()->json([
        //             'status' => 401,
        //             'message' => 'Unauthorized!',
        //             'data' => null,
        //         ], 401);
        //     }
        // });

        // $this->renderable(function (MethodNotAllowedHttpException $exception, $request) {
        //     if ($request->is('api/*')) {
        //         return response()->json([
        //             'status' => 405,
        //             'message' => 'Method Not Allowed!',
        //             'data' => null,
        //         ], 405);
        //     }
        // });
    })->withSchedule(function (Schedule $schedule) {
        $schedule->command('sanctum:prune-expired  --hours=24')->daily();
    })->create();
