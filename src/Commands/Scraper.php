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
    protected $signature = 'scrape:news {site : The site to be scraped} {--timeout=1} {--save=1}';

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
      $save    = $this->option('save');

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

          if (!isset($article['date']) || empty($article['date'])) {
            $this->error("No date scraped, cannot save without date");
            continue;
          }

          if (!$save) {
            sleep($timeout);
            continue;
          }

          $msg = $scraper->strategy->saveData($article);

          if ($msg->has('error')) {
            $this->error($msg->first('error'));
          }

          if ($msg->has('success') && $msg->has('id')) {
            $this->info("Article saved with id ".$msg->first('id'));
          }

          if ($msg->has('exists')) {
            $this->info("Article already exists in db");
          }

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
