<?php

namespace Nxvhm\Newscraper\Strategies;

use Nxvhm\Newscraper\Contracts\CrawlingStrategyContract;
use Symfony\Component\DomCrawler\Crawler;

class BalkanInsight extends Strategy implements CrawlingStrategyContract
{

  public $name = "Balkan Insight";

	/**
	 * Site Url
   *
	 * @var string
	 */
  public $url = "https://balkaninsight.com";

  /**
   * Relative path to pages from which we fetch links
   *
   * @var array
   */
  public $pagesToCrawl = [
    '/albania-home',
    '/bulgaria-home',
    '/romania-home',
    '/croatia-home',
    '/greece-home',
    '/kosovo-home',
    '/macedonia-home',
    '/turkey-home',


  ];

  /**
   * CSS Query Selectors for article contetns
   * @var Array
   */
  public $contentSelectors = [
    'title' => 'h1 > span.headline',
    'description' => '.btArticleExcerpt',
    'date' => 'span.btArticleDate',
    'author' => 'a.author',
    'category' => '.btSubTitle',
    'text' => '.btArticleBody',
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
