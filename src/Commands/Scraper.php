<?php

namespace Nxvhm\Newscraper\Commands;
use Illuminate\Console\Command;
use Nxvhm\Newscraper\Newscraper;
use Nxvhm\Newscraper\Factory;
use Nxvhm\Newscraper\Exceptions\InvalidResponseException;
use Log;

class Scraper extends Command
{
    /**
     * @var string
     */
    protected $signature = 'scrape:news {site : The site to be scraped} {--timeout=1}';

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
      $site    = $this->argument('site');
      $timeout = $this->option('timeout');

      if ($timeout < 1) {
        throw new \Exception("Timeouts less then 1 are not acceptable");
      }

      if (!$site) {
        return $this->error("No site specified");
      }

      # Create scraper with desired strategy
      $scraper = Newscraper::init($site, $this);

      foreach($scraper->strategy->getPagesToCrawl() as $pageUrl) {
        $this->info('Fetching Links from '.$pageUrl.' ...');
      }

      $links = $scraper->getListOfLinks();

      $this->info(count($links). ' raw links extracted from pages');

      $links = $scraper->strategy->stripInvalidLinks($links);

      $this->info(count($links). ' links after filter');
      foreach($links as $url) {
        try {

          $this->line("Processing $url");

          try {

            $article = $scraper->articleFromLink($url);

          } catch(InvalidResponseException $e) {

            $this->error($e->getMessage());
            continue;
          }

          if (empty($article)) {
            $this->info("No data scrapped for $url");

          } else {
            $this->info(sprintf("Title %s \nDescription: %s \nDate: %s \n",
              $article['title'],
              $article['description'],
              $article['date'])
            );
          }

          // $scraper->strategy->saveData($article);

          # Timeout between requests
          sleep($timeout);

        } catch (\Exception $e) {
          Log::error($e);
          $this->error("Error processing $url");
          $this->error($e->getMessage());
        }

      }


    }
}
