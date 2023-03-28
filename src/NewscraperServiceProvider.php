<?php

namespace Nxvhm\Newscraper;

use Illuminate\Support\ServiceProvider;

class NewscraperServiceProvider extends ServiceProvider
{

    public $packageCommands = [
        \Nxvhm\Newscraper\Commands\Scraper::class,
        \Nxvhm\Newscraper\Commands\RegisterSites::class,
        \Nxvhm\Newscraper\Commands\CreateStrategy::class,
        \Nxvhm\Newscraper\Commands\ScrapeUrl::class
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
          __DIR__ . '/../config/newscraper.php' => \config_path('newscraper.php')
      ], 'config');

      # php artisan vendor:publish --provider="Nxvhm\Newscraper\NewscraperServiceProvider" --tag=migrations
      $this->publishes([
        __DIR__.'/../database/migrations/create_newscraper_tables.php.stub' =>
          \database_path('migrations/'.date('Y_m_d_His').'_create_newscraper_tables.php')
      ], 'migrations');
    }
}
