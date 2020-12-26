<?php

namespace Nxvhm\Newscraper;
use Goutte\Client;
use Illuminate\Console\Command;
use Nxvhm\Newscraper\Strategies\Strategy;

class Scraper {
  /**
   * Crawling strategy
   *
   * @var Nxvhm\Newscraper\Strategies\Strategy;
   */
  public $strategy;

  public $command;

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
    # Iterate through the pages we want to crawl and scrape all hrefs
    foreach ($this->strategy->getPagesToCrawl() as $page) {

      if ($this->command) {
        $this->command->info('Fetch Links from '.$page);
      }

      $crawler = $this->httpClient->request('GET', $page);

      $anchors = $crawler->filter('a');

      if (!$anchors->count()) continue;

      $anchors->each(function($a) use (&$links) {
        array_push($links, $a->attr('href'));
      });
    }

    // $this->output(count($links). ' raw links extracted from pages');
    return $links;
    // return $this->strategy->stripInvalidLinks($links);
  }

  public function articleFromLink(string $url): array {
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
      return [];
    }

    $this->output("Processing $url", 'line');

    $crawler = $this->httpClient->request('GET', $url);

    if ($this->httpClient->getResponse()->getStatusCode() !== 200) {
      $this->output("Response Status Code is not 200, continue..", 'error');
      return [];
    }

    return  $this->strategy->getArticleData($crawler);

  }

  public function output(string $msg, $type = 'info'): void {
    $this->command && $this->command instanceof Command
      ? call_user_func([$this->command, $type], $msg)
      : print("\n $msg");
  }
}
