# Slacker Iugu - Slack Slash command Iugu handler
[![Packagist](https://img.shields.io/packagist/v/edbizarro/slacker-iugu.svg)](https://packagist.org/packages/edbizarro/slacker-iugu) 
[![Build Status](https://travis-ci.org/edbizarro/slacker-iugu.svg?branch=master)](https://travis-ci.org/edbizarro/slacker-iugu)
[![StyleCI](https://styleci.io/repos/71378457/shield?branch=master)](https://styleci.io/repos/71378457)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/7f1a680b4bd848ef8a3b8bcf90039b2e)](https://www.codacy.com/app/Zendev/slacker-iugu?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=edbizarro/slacker-iugu&amp;utm_campaign=Badge_Grade)

Based and inspired by [spatie/laravel-slack-slash-command](https://github.com/spatie/laravel-slack-slash-command)

First of all [setup a Slash command](https://docs.spatie.be/laravel-slack-slash-command/v1/installation-and-setup) and install [spatie/laravel-slack-slash-command](https://github.com/spatie/laravel-slack-slash-command) package into a Laravel app.

## Usage

Follow this steps to use this package in your Laravel installation

### 1. Require it on composer

```bash
composer require edbizarro/slacker-iugu
```

### 2. Load service provider

You need to update your `config/app.php` configuration file to register our service provider, adding this line on `providers` array:

```php
// config/app.php
'providers' => [
    ...
    Edbizarro\Slacker\Iugu\Providers\SlackerIuguServiceProvider::class,
];
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

Note that this file uses environment variables, it's a good practice put your secret keys in your `.env` file adding this lines on it:


```
IUGU_API_TOKEN="YOUR_KEY"
```

And you're good to go.
