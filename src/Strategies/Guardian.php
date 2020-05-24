<?php

namespace Nxvhm\Newscraper\Strategies;

use Nxvhm\Newscraper\Contracts\NewsScraperInterface;

class Guardian extends Strategy implements NewsScraperInterface
{
  public $name = "The Guardian";
	/**
	 * Site Url
   *
	 * @var string
	 */
  public $url = "https://www.theguardian.com";

  /**
   * Relative path to pages from which we fetch links
   *
   * @var array
   */
  public $pagesToCrawl = [
    '/topics/regions/middleeast.html',
    '/topics/regions/africa.html',
    '/topics/regions/asia.html',
    '/topics/regions/asia-pacific.html'
  ];

  /**
   * Filter all urls which are not pointing to an article
   *
   * @param   Array  $links Raw extracted hrefs
   * @return  Array  $links Filtered links pointing to an article
   */
  public function stripInvalidLinks(array $links): array {
    foreach ($links as $key => $link) {
      $parts = explode('/', $link);

      if (!$parts || !is_countable($parts) || count($parts) < 8) {
        unset($links[$key]);
      }

      if (!filter_var($link, FILTER_VALIDATE_URL)) {
        unset($links[$key]);
      }

    }

    return array_unique($links);
  }

  public function extractDataFromLink(string $link): array {

  }
}
