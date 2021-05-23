<?php

namespace Nxvhm\Newscraper;
use Goutte\Client;
use Illuminate\Console\Command;
use Nxvhm\Newscraper\Strategies\Strategy;
use Nxvhm\Newscraper\Contracts\NewsScraperContract;
use Nxvhm\Newscraper\Exceptions\InvalidResponseException;

class Newscraper implements NewsScraperContract {
  /**
   * Crawling strategy
   *
   * @var Nxvhm\Newscraper\Strategies\Strategy;
   */
  public $strategy;

  public $command;

  public function __construct(Strategy $strategy) {

    $this->strategy = $strategy;

    $this->httpClient = new Client();

  }

  public static function init($siteName) {
    # Get instance of scraping strategy
    $strategy = Factory::getScrapingStrategy($siteName);

    if (!$strategy) {
      throw new \Exception("Strategy class not found for $siteName");
    }

    return new self($strategy);
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

      $crawler = $this->httpClient->request('GET', $page);

      $anchors = $crawler->filter('a');

      if (!$anchors->count()) continue;

      $anchors->each(function($a) use (&$links) {
        array_push($links, $a->attr('href'));
      });
    }

    return $links;
    // return $this->strategy->stripInvalidLinks($links);
  }

  public function articleFromLink(string $url): array {
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
      return [];
    }

    $crawler = $this->httpClient->request('GET', $url);

    if ($this->httpClient->getResponse()->getStatusCode() !== 200) {
      throw new InvalidResponseException("Response Status Code is not 200, continue..");
    }

    return  array_merge(
      $this->strategy->getArticleData($crawler),
      ['url' => $url]
    );

  }

  public static function getConfig($path = null):array {
    return $path ? config('newscraper.'.$path, []) : config('newscraper');
  }
}
