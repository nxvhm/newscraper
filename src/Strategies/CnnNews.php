<?php

namespace Nxvhm\Newscraper\Strategies;

use Nxvhm\Newscraper\Contracts\CrawlingStrategyContract;
use Symfony\Component\DomCrawler\Crawler;

class CnnNews extends Strategy implements CrawlingStrategyContract
{

  public $name = "CNN";

	/**
	 * Site Url
   *
	 * @var string
	 */
  public $url = "https://edition.cnn.com";

  /**
   * Relative path to pages from which we fetch links
   *
   * @var array
   */
  public $pagesToCrawl = [
    '/world/europe',
    '/world/asia',
    '/world/middle-east',
    '/world/africa'
  ];

  /**
   * CSS Query Selectors for article contetns
   * @var Array
   */
  public $contentSelectors = [
   'title' => 'h1#maincontent',
   'description' => '.article__content p.paragraph',
   'date' => '.timestamp',
   'author' => '.byline__names',
   'category' => '',
   'text' => '.article__content',
   'image' => '.article__main picture img'
  ];

  public $onlyFirstResult = [
    'date',
    'description',
    'image'
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

  public function parseDate(Crawler $crawler) {
    $host = parse_url($crawler->getUri());
    try {
      [, $year, $month, $date] = explode('/', $host['path']);
      $date = date("Y-m-d", strtotime(implode("-", [$year, $month, $date])));
    } catch (\Exception $e) {
      return '1970-01-01';
    }
    return $date;
  }

}
