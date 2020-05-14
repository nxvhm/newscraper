<?php

namespace Nxvhm\Newscraper\Commands;
use Illuminate\Console\Command;
use Nxvhm\Newscraper\Scraper as NewsScrapper;
use Nxvhm\Newscraper\Factory;

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

      if (!$site) {
        return $this->error("No site specified");
      }
      # Get instance of scraping strategy
      $strategy = Factory::getScrapingStrategy($site);

      if (!$strategy) {
        throw new Exception("Strategy class not found for $site");
      }

      # Create scraper with desired strategy
      $scraper = new NewsScrapper($strategy, $this);

      $scraper->getListOfLinks();

    }
}
