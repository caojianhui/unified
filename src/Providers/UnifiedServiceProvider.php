<?php


namespace Unified\Login\Providers;



use Illuminate\Support\ServiceProvider;
use Unified\Login\Middleware\UnifiedMiddleware;
use Unified\Login\Providers\EventServiceProvider;
use Unified\Login\UnifiedManager;

class UnifiedServiceProvider extends ServiceProvider
{
    protected $middlewareAliases = [
        'unified' => UnifiedMiddleware::class,
    ];
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {

        $path = realpath(__DIR__.'/../../config/unified.php');

        $this->publishes([$path => config_path('unified.php')], 'config');
        $this->mergeConfigFrom($path, 'unified');

        $this->aliasMiddleware();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {

        $this->app->singleton('unified.login.provider.unified', function ($app) {
            return $this->getConfigInstance('providers.unified');
        });
        $this->app->singleton('unified.login.manager', function($app) {
            return new UnifiedManager();
        });
        $this->app->register(EventServiceProvider::class);
    }

    protected function getConfigInstance($key)
    {
        $instance = $this->config($key);

        if (is_string($instance)) {
            return $this->app->make($instance);
        }

        return $instance;
    }


    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides() {
        return ['unified'];
    }

    /**
     * Alias the middleware.
     *
     * @return void
     */
    protected function aliasMiddleware()
    {
        $router = $this->app['router'];

        $method = method_exists($router, 'aliasMiddleware') ? 'aliasMiddleware' : 'middleware';

        foreach ($this->middlewareAliases as $alias => $middleware) {
            $router->$method($alias, $middleware);
        }
    }
}
