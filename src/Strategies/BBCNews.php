<?php

namespace Nxvhm\Newscraper\Strategies;

use Nxvhm\Newscraper\Contracts\CrawlingStrategyContract;
use Symfony\Component\DomCrawler\Crawler;

class BBCNews extends Strategy implements CrawlingStrategyContract
{

  public $name = "BBCNews";

	/**
	 * Site Url
   *
	 * @var string
	 */
  public $url = "https://www.bbc.com";

  /**
   * Relative path to pages from which we fetch links
   *
   * @var array
   */
  public $pagesToCrawl = [
    '/news',
    '/news/world',
    '/news/uk',
    '/news/business',
    '/news/science_and_environment',
    '/news/world/africa',
    '/news/world/australia',
    '/news/world/latin_america',

  ];

  /**
   * CSS Query Selectors for article contetns
   * @var Array
   */
  public $contentSelectors = [
    'title' => 'h1#main-heading',
    'description' => '.ssrcss-11r1m41-RichTextComponentWrapper.ep2nwvo0',
    'date' => 'time, author-unit__container > span',
    'author' => '',
    'category' => '',
    'text' => '.ssrcss-pv1rh6-ArticleWrapper.e1nh2i2l6',
    'image' => 'article picture img'
   ];

  public $onlyFirstResult = [
    'date',
    'description'
  ];

  /**
   * Filter  which are not pointing to an article.
   *
   * @param   String  $url Raw extracted url from href attr
   * @return  Mixed  $url String containing formated and Validated link, or null otherwise
   */
  public function validateAndFormatUrl(string $url) {
    return $this->urlValidationClosure($urlParts = 4)($url);
  }

  public function stripInvalidLinks(array $links): array {
    $filtered = [];
    foreach ($links as $key => $link) {

      if (!strlen($link) || strlen($link) < 5) {
        continue;
      }

      $link = $link[0] == '/' ? $this->url.$link : $link;

      if (strpos($link, $this->url) === false) {
        continue;
      }

      $parts = explode('/', $link);

      $last = end($parts);

      if (count(explode('-', $last)) < 2) {
        continue;
      }

      array_push($filtered, $link);
    }

    return $filtered;
  }

}
