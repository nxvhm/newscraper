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
   * Filter urls which are not pointing to an article.
   *
   * @param   String  $url Raw extracted url from href attr
   * @return  Mixed  $url String containing formated and Validated link, or null otherwise
   */
  public function validateAndFormatUrl($url) {

    if (null == $url) {
      return $url;
    }

    # This site works with relative links, so concat with host
    if ($url[0] == '/') {
      $url = $this->getSiteUrl().$url;
    }

    $parts = array_filter(explode('/', $url));
    if (!$parts || !is_countable($parts) || count($parts) < 6) {
      $url = null;
    }

    if (!filter_var($url, FILTER_VALIDATE_URL)) {
     $url = null;
    }

    return $url;
  }



}
