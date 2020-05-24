<?php

namespace Nxvhm\Newscraper\Strategies;

use Nxvhm\Newscraper\Contracts\NewsScraperInterface;
use Symfony\Component\DomCrawler\Crawler;

class NewYorkTimes extends Strategy implements NewsScraperInterface
{

  public $name = "NewYork Times";

	/**
	 * Site Url
   *
	 * @var string
	 */
  public $url = "https://www.nytimes.com";

  /**
   * Relative path to pages from which we fetch links
   *
   * @var array
   */
  public $pagesToCrawl = [
    '/section/world',
    '/section/us',
    '/section/science',
    '/section/health',
    '/section/nyregion',

  ];

  /**
   * CSS Query Selectors for article contetns
   * @var Array
   */
  public $contentSelectors = [
    'title'       => 'h1',
    'description' => '#article-summary',
    'date'        => 'time',
    'author'      => 'p[itemprop="author"]',
    'text'        => 'section[name="articleBody"]',
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
