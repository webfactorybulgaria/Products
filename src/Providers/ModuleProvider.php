<?php

namespace TypiCMS\Modules\Products\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use TypiCMS\Modules\Core\Facades\TypiCMS;
use TypiCMS\Modules\Core\Observers\FileObserver;
use TypiCMS\Modules\Core\Observers\SlugObserver;
use TypiCMS\Modules\Core\Services\Cache\LaravelCache;
use TypiCMS\Modules\Products\Models\Product;
use TypiCMS\Modules\Products\Models\ProductTranslation;
use TypiCMS\Modules\Products\Repositories\CacheDecorator;
use TypiCMS\Modules\Products\Repositories\EloquentProduct;
use TypiCMS\Modules\Attributes\Observers\AttributeGroupObserver;

class ModuleProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'typicms.products'
        );

        $modules = $this->app['config']['typicms']['modules'];
        $this->app['config']->set('typicms.modules', array_merge(['products' => ['linkable_to_page']], $modules));

        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'products');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'products');

        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/products'),
        ], 'views');
        $this->publishes([
            __DIR__.'/../database' => base_path('database'),
        ], 'migrations');

        AliasLoader::getInstance()->alias(
            'Products',
            'TypiCMS\Modules\Products\Facades\Facade'
        );

        // Observers
        ProductTranslation::observe(new SlugObserver());
        Product::observe(new FileObserver());
        Product::observe(new AttributeGroupObserver());
    }

    public function register()
    {
        $app = $this->app;

        /*
         * Register route service provider
         */
        $app->register('TypiCMS\Modules\Products\Providers\RouteServiceProvider');

        /*
         * Sidebar view composer
         */
        $app->view->composer('core::admin._sidebar', 'TypiCMS\Modules\Products\Composers\SidebarViewComposer');

        /*
         * Add the page in the view.
         */
        $app->view->composer('products::public.*', function ($view) {
            $view->page = TypiCMS::getPageLinkedToModule('products');
        });

        $app->bind('TypiCMS\Modules\Products\Repositories\ProductInterface', function (Application $app) {
            $repository = new EloquentProduct(new Product());
            if (!config('typicms.cache')) {
                return $repository;
            }
            $laravelCache = new LaravelCache($app['cache'], ['products', 'attributes'], 10);

            return new CacheDecorator($repository, $laravelCache);
        });
    }
}
