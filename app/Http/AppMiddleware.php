<?php

namespace App\Http;

use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

/**
 * Analogue for Kernel.php on older laravel versions
 */
class AppMiddleware
{
    /**
     * @param Middleware $middleware
     * @return void
     */
    public function __invoke(Middleware $middleware): void
    {
        $middleware->appendToGroup('api', [
            EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            SubstituteBindings::class,
        ]);
    }
}
