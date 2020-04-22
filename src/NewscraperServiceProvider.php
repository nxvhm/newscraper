<?php

namespace Nxvhm\Newscraper;

use Illuminate\Support\ServiceProvider;

class NewscraperServiceProvider extends ServiceProvider
{

    public $packageCommands = [
        \Nxvhm\Newscraper\Commands\Scraper::class
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
      if ($this->app->runningInConsole()) {
        $this->commands($this->packageCommands);
      }

        # php artisan vendor:publish --tag=config
        $this->publishes([
            __DIR__ . '/config/scraper.php' => \config_path('newscraper.php')
        ], 'config');
    }
}
