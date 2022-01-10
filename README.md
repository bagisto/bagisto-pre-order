# Introduction

Bagisto Pre-order add-on allows the customer to pre-order products which are not yet available at the online store. With the help of Bagisto Pre-order add-on, the customer or the guest user can place orders for out of stock products. The admin can set the whether the customer will pay a full or partial amount of the product.

It packs in lots of demanding features that allows your business to scale in no time:

- Buyer can pre-order only out of stock products.
- Works with Simple and Configurable products.
- Set a custom message to display on the preorder product page.
- Admin can set preorder status and availability date.
- Using this module customer can pay full or partial payment.
- Customers can receive an email notification when a product is in-stock.
- Guest users can also use the pre-order feature and place the orders.
- The admin can display Free Preorder Note to customers.
- Multi-Lingual support/All language working including RTL.

## Requirements:

- **Bagisto**: v1.3.3

## Installation with composer:
- Run the following command
```
composer require bagisto/bagisto-pre-order
```

- Goto config/concord.php file and add following line under 'modules'
```php
\Webkul\PreOrder\Providers\ModuleServiceProvider::class
```

- Run these commands below to complete the setup
```
composer dump-autoload
```

```
php artisan migrate
php artisan route:cache
php artisan config:cache
php artisan db:seed --class=Webkul\\PreOrder\\Database\\Seeders\\DatabaseSeeder
php artisan vendor:publish
```
-> Press 0 and then press enter to publish all assets and configurations.

## Installation without composer:

- Unzip the respective extension zip and then merge "packages" folder into project root directory.
- Goto config/app.php file and add following line under 'providers'

```
Webkul\PreOrder\Providers\PreOrderServiceProvider::class
```

- Goto composer.json file and add following line under 'psr-4'

```
"Webkul\\PreOrder\\": "packages/Webkul/PreOrder/src"
```

- Run these commands below to complete the setup

```
composer dump-autoload
```

```
php artisan optimize
```

```
php artisan migrate
```

```
php artisan route:cache
```

```
php artisan db:seed --class=Webkul\\PreOrder\\Database\\Seeders\\DatabaseSeeder
```

```
php artisan vendor:publish

-> Press 0 and then press enter to publish all assets and configurations.
```

> That's it, now just execute the project on your specified domain.
