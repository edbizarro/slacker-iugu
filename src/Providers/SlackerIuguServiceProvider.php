<?php

namespace Edbizarro\Slacker\Iugu\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class SlackerIuguServiceProvider.
 */
class SlackerIuguServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->isLumen()) {
            $this->app->configure('slash-command-iugu-handler');
        }

        if ($this->isLumen() === false) {
            $this->publishes([
                __DIR__.'/../../config/slash-command-iugu-handler.php' => config_path('slash-command-iugu-handler.php'),
            ], 'config');
        }

        $this->mergeConfigFrom(__DIR__.'/../../config/slash-command-iugu-handler.php', 'slash-command-iugu-handler');
    }

    /**
     * @return bool
     */
    private function isLumen()
    {
        return true === str_contains($this->app->version(), 'Lumen');
    }
}
