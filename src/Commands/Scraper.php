<?php

namespace Nxvhm\Newscraper\Commands;
use Illuminate\Console\Command;

class Scraper extends Command
{
    /**
     * @var string
     */
    protected $signature = 'scrape:news {site : The site to be scraped}';

    protected $client;

    /**
     * @var string
     */
    protected $description = 'Scrape news from a site';

    public function __construct()
    {
      parent::__construct();

    }

    public function handle()
    {
      $site = $this->argument('site');
      var_dump($site);
    }
}
