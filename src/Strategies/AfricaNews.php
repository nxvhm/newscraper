<?php

namespace Nxvhm\Newscraper\Strategies;

use Nxvhm\Newscraper\Contracts\CrawlingStrategyContract;
use Symfony\Component\DomCrawler\Crawler;

class AfricaNews extends Strategy implements CrawlingStrategyContract
{

  public $name = "AfricaNews";

	/**
	 * Site Url
   *
	 * @var string
	 */
  public $url = "https://www.africanews.com";

  /**
   * Relative path to pages from which we fetch links
   *
   * @var array
   */
  public $pagesToCrawl = [
    '/news/',
    '/business/',
    '/science-technology/',
    '/country/kenya/',
    '/country/tunisia/',
    '/country/algeria/',
    '/country/democratic-republic-of-congo/'


  ];

  /**
   * CSS Query Selectors for article contetns
   * @var Array
   */
  public $contentSelectors = [
   'title' => 'h1.article__title',
   'description' => '.article-content__text p:nth-child(1)',
   'date' => '.article__meta > time',
   'author' => '.article__author',
   'category' => '',
   'text' => '.article-content__text',
   'image' => '.article-wrapper .article__image > img'
  ];


  /**
   * Filter  which are not pointing to an article.
   *
   * @param   String  $url Raw extracted url from href attr
   * @return  Mixed  $url String containing formated and Validated link, or null otherwise
   */
  public function validateAndFormatUrl(string $url) {
    return $this->urlValidationClosure($urlParts = 6)($url);
  }

}
