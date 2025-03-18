<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        RateLimiter::for('api', function ($request) {
            return Limit::perMinute(60);
        });

        VerifyCsrfToken::except([
            'api/*',
        ]);

        Builder::macro('filter', function (Request $request, ?string $filterClass = null) {
            /** @var string $nameSpace */
            $nameSpace = config('queryFilter.namespace');
            /** @var string $suffix */
            $suffix = config('queryFilter.suffix');

            $filterClass = $filterClass ?? $nameSpace.class_basename($this->getModel()).$suffix;

            if (class_exists($filterClass)) {
                $filter = new $filterClass($request);

                /** @phpstan-ignore-next-line  */
                return $filter->apply($this);
            }

            return $this;
        });
    }
}
