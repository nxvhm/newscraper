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

  public function getListOfLinks() {
    foreach ($this->strategy->getPagesToCrawl() as $page) {
      if ($this->command) {
        $this->command->info('Fetch Links for '.$page);
      }
    }
  }
}
