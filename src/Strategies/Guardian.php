<?php

namespace Nxvhm\Newscraper\Strategies;

use Nxvhm\Newscraper\Contracts\CrawlingStrategyContract;
use Symfony\Component\DomCrawler\Crawler;

class Guardian extends Strategy implements CrawlingStrategyContract
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
    '/world',
    '/uk-news',
    '/lifestyle',
  ];


  /**
   * CSS Query Selectors for article contetns
   * @var Array
   */
  public $contentSelectors = [
    'title'       => 'title',
    'description' => '.css-1rm7u2e p, .css-xmt4aq p, .content__main-column p, .dcr-mj1r7n p',
    'date'        => 'time, .css-hn0k3p, .dcr-hn0k3p',
    'author'      => 'p.byline',
    'text'        => '.article-body-viewer-selector',
  ];

  public $onlyFirstResult = [
    'description'
  ];

  public function validateAndFormatUrl($url) {
    return $this->urlValidationClosure($urlParts = 8)($url);
  }

}
