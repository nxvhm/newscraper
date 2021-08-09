<?php

namespace Nxvhm\Newscraper\Strategies;

use Nxvhm\Newscraper\Contracts\CrawlingStrategyContract;
use Symfony\Component\DomCrawler\Crawler;

class Aljazeera extends Strategy implements CrawlingStrategyContract
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
    'title'       => 'h1',
    'description' => '.article__subhead',
    'date'        => '.date-simple',
    'author'      => '.article-heading-author-name',
    'category'    => '.topics',
    'text'        => '.wysiwyg--all-content',
  ];

  public $onlyFirstResult = [
    'date'
  ];

  /**
   * Filter  which are not pointing to an article.
   *
   * @param   String  $url Raw extracted url from href attr
   * @return  Mixed  $url String containing formated and Validated link, or null otherwise
   */
  public function validateAndFormatUrl(string $url) {
    return $this->urlValidationClosure($urlParts = 7)($url);
  }

}
