<?php


namespace Unified;



use Illuminate\Support\ServiceProvider;
use Unified\Providers\EventServiceProvider;

class UnifiedServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');

        $this->publishes([
            __DIR__ . '/config/unified.php' => config_path('unified.php')
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        $this->app->bind('unified', function($app) {
            return new UnifiedManager();
        });
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides() {
        return ['unified'];
    }
}
