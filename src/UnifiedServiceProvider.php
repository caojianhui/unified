<?php


namespace Unified;



use Illuminate\Support\ServiceProvider;
use Unified\Middleware\UnifiedMiddleware;
use Unified\Providers\EventServiceProvider;

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
//        $this->loadMigrationsFrom(__DIR__ . '/migrations');
//
//        $this->publishes([
//            __DIR__ . '/config/unified.php' => config_path('unified.php')
//        ], 'config');
//        $path = realpath(__DIR__.'/../../config/unified.php');
        $path = __DIR__ . '/config/unified.php';


        $this->publishes([$path => config_path('jwt.php')], 'config');
        $this->mergeConfigFrom($path, 'jwt');

        $this->aliasMiddleware();
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
