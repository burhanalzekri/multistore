<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Sentry\Laravel\Integration;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 🚪 توجيه الضيوف إلى صفحة الدخول
        $middleware->redirectGuestsTo('/login');
        $middleware->redirectUsersTo('/dashboard');

        $middleware->trustProxies(at: "*");
        $middleware->web(append: [
            \App\Http\Middleware\ResolveTenant::class,
            \App\Http\Middleware\CheckMaintenance::class,
        ]);
        $middleware->alias([
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'super_admin' => \App\Http\Middleware\RequireSuperAdmin::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'webhooks/*',
            'api/track',
            'api/*',
            'api/track',
            'api/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        Integration::handles($exceptions);
    })->create();
