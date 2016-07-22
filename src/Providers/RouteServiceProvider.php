<?php

namespace TypiCMS\Modules\Products\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use TypiCMS\Modules\Core\Facades\TypiCMS;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'TypiCMS\Modules\Products\Http\Controllers';

    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function (Router $router) {

            /*
             * Front office routes
             */
            if ($page = TypiCMS::getPageLinkedToModule('products')) {
                $options = $page->private ? ['middleware' => 'auth'] : [];
                foreach (config('translatable.locales') as $lang) {
                    if ($page->translate($lang)->status && $uri = $page->uri($lang)) {
                        $router->get($uri, $options + ['as' => $lang.'.products', 'uses' => 'PublicController@index']);
                        $router->get($uri.'/{slug}', $options + ['as' => $lang.'.products.slug', 'uses' => 'PublicController@show']);
                    }
                }
            }

            /*
             * Admin routes
             */
            $router->get('admin/products', 'AdminController@index')->name('admin::index-products');
            $router->get('admin/products/create', 'AdminController@create')->name('admin::create-product');
            $router->get('admin/products/{product}/edit', 'AdminController@edit')->name('admin::edit-product');
            $router->post('admin/products', 'AdminController@store')->name('admin::store-product');
            $router->put('admin/products/{product}', 'AdminController@update')->name('admin::update-product');

            /*
             * API routes
             */
            $router->get('api/products', 'ApiController@index')->name('api::index-products');
            $router->put('api/products/{product}', 'ApiController@update')->name('api::update-product');
            $router->delete('api/products/{product}', 'ApiController@destroy')->name('api::destroy-product');
        });
    }
}
