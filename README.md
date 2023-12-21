# SUPERBAN

This package adds the ability to ban a client completely for a period of time in a laravel application. This is achieved by utilizing Laravel’s built-in rate-limiting features. The user can be banned using their Ip address, UserId, and Email address.

## Installation

Open the `composer.json` file in your laravel application and include the following array somewhere in the object:

```bash
...

"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/eazybright/superban"
    }
]

...
```

Run the command below to install this package via composer:

```bash
composer require eazybright/superban
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="Eazybright\SuperBan\SuperBanServiceProvider" --tag="config"
```
The config file will be exported into the `/config` directory with the name `superban.php`. This file contains an array of default configuration values.

Let's register the middleware into our application. There are many ways to achieve this in the `app/Http/Kernel.php` file:

1. You can update the `$middlewareAliases` array to use the middleware for specific routes.

```php
protected $middlewareAliases = [
    'auth' => \App\Http\Middleware\Authenticate::class,
    ...
    'superban' => \Eazybright\SuperBan\Http\Middleware\SuperBanMiddleware::class,
    ...
];
```
With this in place, you can use this middleware in your routes as:
```php
Route::middleware(['superban:100,5,10'])->group(function () {
    ...
});
```
The middleware parameter can be explained in the table below:
| Options       | Detail                                                             | Example   |  Data Type |
| ------------- | -------------------------------------------------------------------| --------------- |  --------- |
| max_attempts   | Specifies the number of requests allowed                          | 100               |   Integer  |
| decay_rate | Amount of minutes for the period of time the number of requests can happen    | 2               |   Integer  |
| ban_duration   | Amount minutes for which the user will be banned for    | 10              |   Integer  |

2. Or update the `$middlewareGroups` array, you can either place it in the `web` or `api` object depending on your usage.
```php
protected $middlewareGroups = [
    'web' => [
        ...
        \Eazybright\SuperBan\Http\Middleware\SuperBanMiddleware::class
    ],

    'api' => [
        ...
        \Eazybright\SuperBan\Http\Middleware\SuperBanMiddleware::class
    ],
];
```
If you are using this approach, the middleware parameter can be configured in `config\superban.php` file.

## Cache Configuration
Your default application cache is being used by the rate limiter as defined by the `default` key within your application's `cache` configuration file. You can configure different cache drivers such as redis, database, memcached, etc. You can learn more on how to configure your cache driver here - [https://laravel.com/docs/10.x/cache](https://laravel.com/docs/10.x/cache).
To use a different cache driver, you may specify which cache driver the rate limiter should use by defining a `limiter` key within your application's `cache` configuration file:
```php
// config/cache.php

'default' => env('CACHE_DRIVER', 'file'),

...

'limiter' => 'redis', // add this line to use a seperate cache driver with the rate limiter
```

## Ban Parameter
This package has the ability to ban request by user id, IP address and email. You can update the `rate_limit_by` key in the `superban.php` file to specify how you want to ban user. Available options are `ip`, `user_id`, `email`.

## Usage
In order to ban user for 24 hours if there are over 200 requests to a particular route within a space of 2 minutes, you can do the following:

```php
Route::middleware(['superban:200,2,1440'])->group(function () {
    Route::post('/thisroute', function () {
       // ...
   });
 
   Route::post('anotherroute', function () {
       // ...
   });
});
```

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
