<?php

namespace Edbizarro\Slacker\Iugu\Providers;

use Edbizarro\Slacker\Iugu\Contracts\IuguServiceContract;
use Edbizarro\Slacker\Iugu\Services\IuguService;
use Illuminate\Support\ServiceProvider;
use Spatie\SlashCommand\SlashCommandServiceProvider;

/**
 * Class SlackerIuguServiceProvider.
 */
class SlackerIuguServiceProvider extends SlashCommandServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        parent::boot();

        if ($this->isLumen()) {
            $this->app->configure('slash-command-iugu-handler');
        }

        if ($this->isLumen() === false) {
            $this->publishes([
                __DIR__.'/../../config/slash-command-iugu-handler.php' => config_path('slash-command-iugu-handler.php'),
            ], 'config');
        }

        $this->mergeConfigFrom(__DIR__.'/../../config/slash-command-iugu-handler.php', 'slash-command-iugu-handler');

        if (is_array(config('laravel-slack-slash-command.handlers'))) {
            config(['laravel-slack-slash-command.handlers' => array_merge(
                config('slash-command-iugu-handler.handlers'),
                config('laravel-slack-slash-command.handlers')
            )]);
        }
    }

    public function register()
    {
        parent::register();

        $this->app->bind(IuguServiceContract::class, function ($app) {
            return new IuguService;
        });

        $this->app->singleton('iugu-service', function ($app) {
            return new IuguService;
        });
    }

    public function provides()
    {
        return [IuguServiceContract::class];
    }

    /**
     * @return bool
     */
    private function isLumen()
    {
        return true === str_contains($this->app->version(), 'Lumen');
    }
}
