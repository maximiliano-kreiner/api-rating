<?php

use App\Http\Middleware\JwtMiddleware;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        // $middleware->alias([
        //     'jwt' => JwtMiddleware::class
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // Manejar errores 404 (rutas no encontradas)
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            // if ($request->expectsJson()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Recurso no encontrado',
                'data'    => [],
                'code'    => 999,
            ], 404);
            // }
        });

        // Manejar errores de base de datos (SQL)
        $exceptions->render(function (QueryException $e, Request $request) {
            // if ($request->expectsJson()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Error en la base de datos',
                'data'    => [],
                'code'    => 999,
                'error' => app()->environment('local') ? $e->getMessage() : 'Error interno',
            ], 500);
            // }
        });

        // Manejar errores genéricos (cualquier otra excepción no controlada)
        $exceptions->render(function (Throwable $e, Request $request) {
            // if ($request->expectsJson()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Error inesperado',
                'data'    => [],
                'error' => app()->environment('local') ? $e->getMessage() : 'Error interno',
            ], 500);
            // }
        });
    })
    ->create();
