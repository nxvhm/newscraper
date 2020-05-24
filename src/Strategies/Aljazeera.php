<?php

namespace Nxvhm\Newscraper\Strategies;

use Nxvhm\Newscraper\Contracts\NewsScraperInterface;
use Symfony\Component\DomCrawler\Crawler;

class Aljazeera extends Strategy implements NewsScraperInterface
{

  public $name = "Aljazeera News";

	/**
	 * Site Url
   *
	 * @var string
	 */
  public $url = "https://www.aljazeera.com";

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
   * CSS Query Selectors for article contetns
   * @var Array
   */
  public $contentSelectors = [
    'title'       => 'h1.post-title',
    'description' => '.article-heading-des',
    'text'        => '.article-p-wrapper > p',
    'date'        => 'time',
    'author'      => '.article-heading-author-name',
    'category'    => '#article-body-topics',
  ];

  /**
   * Filter all urls which are not pointing to an article
   *
   * @param   Array  $links Raw extracted hrefs
   * @return  Array  $links Filtered links pointing to an article
   */
  public function stripInvalidLinks(array $urls): array {
    foreach ($urls as $key => $url) {

      # This site works with relative links, so concat with host
      if ($url[0] == '/') {
        $url = $this->getSiteUrl().$url;
        # Save concatenated url back in array
        $urls[$key] = $url;
      }

      $parts = array_filter(explode('/', $url));

      if (!$parts || !is_countable($parts) || count($parts) < 6) {
        unset($urls[$key]);
      }

      if (!filter_var($url, FILTER_VALIDATE_URL)) {
        unset($urls[$key]);
      }

    }

    return array_unique($urls);
  }

  public function getContentSelectors(): array {
    return $this->contentSelectors;
  }



}
