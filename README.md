<p align="center">
<a href="https://packagist.org/packages/tabatii/localmail"><img src="https://img.shields.io/packagist/dt/tabatii/localmail" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/tabatii/localmail"><img src="https://img.shields.io/packagist/v/tabatii/localmail" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/tabatii/localmail"><img src="https://img.shields.io/packagist/l/tabatii/localmail" alt="License"></a>
</p>


## About LocalMail

LocalMail is a laravel database mailer for local development.


## Installation

You can install the package via composer:
```
composer require tabatii/localmail
```

Next, you should run the package migrations:
```
php artisan migrate
```

Also, add the package mailer to your `mail.php` configuration file:
```
'mailers' => [

    // Other mailers

    'localmail' => [
        'transport' => 'localmail',
    ],

],
```

Finally, change the `MAIL_MAILER` environment variable to `localmail` in your `.env` file:
```
MAIL_MAILER=localmail
```

Optionally, you can publish the package configuration file using:
```
php artisan vendor:publish --tag="localmail-config"
```


# Usage

You can visit the LocalMail dashboard to preview every email you sent.\
The LocalMail dashboard is available at the `/localmail` route named `localmail.dashboard`.\
You can customize this route path in the LocalMail configuration file by changing the `routes.prefix` key.
```
'routes' => [
    'prefix' => 'localmail',
],
```

You can also protect the LocalMail dashboard by adding a middleware to the `routes.middleware` key.\
Keep in mind that the `web` middleware is required for the LocalMail dashboard to work properly.
```
'routes' => [
    'middleware' => ['web', 'auth:admin'],
],
```
