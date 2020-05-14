<?php

namespace Nxvhm\Newscraper;
use Goutte\Client;
use Illuminate\Console\Command;
use Nxvhm\Newscraper\Strategies\Strategy;

class Scraper {
  /**
   * [$links description]
   *
   * @var Array
   */
  protected $links;
  /**
   * Crawling strategy
   *
   * @var Nxvhm\Newscraper\Strategies\Strategy;
   */
  protected $strategy;

  protected $command;

  public function __construct(Strategy $strategy, $cmd = null) {

    $this->strategy = $strategy;

    $this->httpClient = new Client();

    if (!is_null($cmd) && $cmd instanceof Command) {
      $this->command = $cmd;
    }

  }

  /**
   * Get all of the links for the selected pages to scrape
   *
   * @return  Array
   */
  public function getListOfLinks(): array {
    $links = [];

    foreach ($this->strategy->getPagesToCrawl() as $page) {

      if ($this->command) {
        $this->command->info('Fetch Links for '.$page);
      }

      $crawler = $this->httpClient->request('GET', $page);

      $anchors = $crawler->filter('a');

      if ($this->command) {
        $this->command->line($anchors->count(). ' links fetched');
      }

      if (!$anchors->count()) continue;

      $anchors->each(function($a) use (&$links) {
        array_push($links, $a->attr('href'));
      });

    }

    return $links;
  }
}
