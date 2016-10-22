# Slacker Iugu - Slack Slash command Iugu handler

Based and inspired by [spatie/laravel-slack-slash-command](https://github.com/spatie/laravel-slack-slash-command)

## Usage

Follow this steps to use this package on your Laravel installation

### 1. Require it on composer

```bash
composer require edbizarro/slacker-iugu
```

### 2. Load service provider

You need to update your `config/app.php` configuration file to register our service provider, adding this line on `providers` array:

```php
Edbizarro\Slacker\Iugu\Providers\SlackerIuguServiceProvider::class
```
## Configuration

You need to publish the package configuration file. To do this, run `php artisan vendor:publish` on terminal.
This will publish a `slacker-iugu-handler.php` file on your configuration folder like this:

```php
<?php

return [
    /*
     * The token provided by iugu.
     */
    'token' => env('IUGU_API_TOKEN'),
    'handlers' => [
        Edbizarro\Slacker\Iugu\Handlers\IuguCustomerHandler::class,
        Edbizarro\Slacker\Iugu\Handlers\IuguSubscriptionHandler::class,
    ],
];
```

Note that this file uses environment variables, it's a good practice put your secret keys on your `.env` file adding this lines on it:


```
IUGU_API_TOKEN="YOUR_KEY"
```

And you're good to go.
