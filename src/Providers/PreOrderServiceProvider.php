<?php

namespace Webkul\PreOrder\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Webkul\PreOrder\Shipping;
use Illuminate\Foundation\AliasLoader;
use Webkul\Shipping\Facades\Shipping as ShippingFacade;
use Webkul\Checkout\Facades\Cart;

class PreOrderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->app->register(EventServiceProvider::class);

        $this->app->register(ModuleServiceProvider::class);

        $this->loadRoutesFrom(__DIR__ . '/../Http/admin-routes.php');

        $this->loadRoutesFrom(__DIR__ . '/../Http/front-routes.php');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'preorder');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'preorder');

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('vendor/webkul/preorder/assets'),
        ], 'public');


        $this->publishes([
            __DIR__ . '/../Resources/views/shop/default/products/add-buttons.blade.php' => resource_path('themes/default/views/products/add-buttons.blade.php'),

            __DIR__ . '/../Resources/views/shop/default/products/view/product-add.blade.php' => resource_path('themes/default/views/products/view/product-add.blade.php'),

            __DIR__ . '/../Resources/views/shop/default/customers/account/orders' => resource_path('themes/default/views/customers/account/orders'),
        ]);


        $this->publishes([
            __DIR__ . '/../Resources/views/shop/velocity/products' => resource_path('themes/velocity/views/products'),

            __DIR__ . '/../Resources/views/shop/velocity/customers/account/orders/view.blade.php' => resource_path('themes/velocity/views/customers/account/orders/view.blade.php'),

            __DIR__ . '/../Resources/views/shop/velocity/customers/account/orders' => resource_path('themes/velocity/views/customers/account/orders'),
        ]);


        $this->publishes([
            __DIR__ . '/../Resources/views/admin/sales/orders' => resource_path('views/vendor/admin/sales/orders'),

            __DIR__ . '/../Resources/views/admin/catalog/products/edit.blade.php' => resource_path('views/vendor/admin/catalog/products/edit.blade.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../Http/Controllers/Shop/ShopController.php' => __DIR__ .'/../../../Velocity/src/Http/Controllers/Shop/ShopController.php',
        ]);

        $this->publishes([
            __DIR__ . '/../Type/AbstractType.php' => __DIR__ .'/../../../Product/src/Type/AbstractType.php',
        ]);

        $this->publishes([
            __DIR__ . '/../Type/Configurable.php' => __DIR__ .'/../../../Product/src/Type/Configurable.php',
        ]);

        $this->publishes([
            __DIR__ . '/../Helpers/ConfigurableOption.php' => __DIR__ .'/../../../Product/src/Helpers/ConfigurableOption.php',
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

        $this->registerFacades();
    }

    /**
     * Register Bouncer as a singleton.
     *
     * @return void
     */
    protected function registerFacades()
    {
        $loader = AliasLoader::getInstance();

        $loader->alias('shipping', ShippingFacade::class);

        $this->app->singleton('shipping', function () {
            return new Shipping();
        });

        $loader->alias('cart', Cart::class);

        $this->app->singleton('cart', function () {
            return new Cart();
        });

        $this->app->bind('cart', 'Webkul\PreOrder\Cart');
    }

    /**
     * Register package config.
     *
     * @return void
     */
    public function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );
    }
}