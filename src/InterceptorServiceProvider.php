<?php

namespace Jundayw\LaravelInterceptor;

use Illuminate\Support\ServiceProvider;
use Jundayw\LaravelInterceptor\Contracts\Filter;

class InterceptorServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (!app()->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../config/interceptor.php', 'interceptor');
        }

        $this->app->bind(Filter::class, config('interceptor.driver'));
        $this->app->bind(\Jundayw\LaravelInterceptor\Contracts\Interceptor::class, Interceptor::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (app()->runningInConsole()) {
            $this->registerMigrations();

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
                __DIR__ . '/../database/seeders' => database_path('seeders'),
            ], 'interceptor-migrations');

            $this->publishes([
                __DIR__ . '/../config/interceptor.php' => config_path('interceptor.php'),
            ], 'interceptor-config');
        }
    }

    /**
     * Register migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        if (config('interceptor.migration')) {
            return $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }

}
