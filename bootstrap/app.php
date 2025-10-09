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
        $middleware->alias([
            "admin" => \App\Http\Middleware\IsAdmin::class,
            "disable" => \App\Http\Middleware\Disable::class,
            "enable" => \App\Http\Middleware\Enable::class,
            "auth" => \App\Http\Middleware\Authenticate::class,
            "guest" => \App\Http\Middleware\RedirectIfAuthenticated::class,
            "secretkey" => \App\Http\Middleware\SecretKey::class,
        ]);
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
                    'status' => 400,
                    'message' => 'Validation Error',
                    'data' => $exception->errors(),
                ], 400);
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

        $exceptions->renderable(function (Exception $exception, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Internal Server Error! | ' . $exception->getMessage(),
                    'data' => null,
                ], 500);
            }
        });
    })->withSchedule(function (Schedule $schedule) {
        $schedule->command('sanctum:prune-expired  --hours=24')->daily();
    })->create();
