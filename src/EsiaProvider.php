<?php

namespace Chongwu\Esia;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class EsiaProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');

        $routeConfig = [
            'namespace' => 'Chongwu\Esia\Controllers',
            'middleware' => 'web'
        ];

        $this->getRouter()->group($routeConfig, function (Router $router) {
           $router->get('esia/login', [
               'uses' => 'AuthController@redirectToEsia',
               'as' => 'esia.login'
           ]);

           $router->get('esia/callback', [
               'uses' => 'AuthController@handleEsiaCallback',
               'as' => 'esia.callback'
           ]);

           $router->post('esia/logout', [
               'uses' => 'AuthController@esiaLogout',
               'as' => 'esia.logout'
           ]);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\SocialiteProviders\Manager\ServiceProvider::class);
        $loader = AliasLoader::getInstance();
        $loader->alias('Socialite', \Laravel\Socialite\Facades\Socialite::class);
    }

    /**
     * Get the active router.
     *
     * @return Router
     */
    protected function getRouter()
    {
        return $this->app['router'];
    }
}
