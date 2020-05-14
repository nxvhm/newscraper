<?php

namespace Nxvhm\Newscraper\Strategies;

use Nxvhm\Newscraper\Contracts\NewsScraperInterface;

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
    '/topics/regions/africa.html'
  ];

  public $bodyTextQueries = [
    '.article-content__text'
  ];

  public function getSiteName(): string {
    return $this->name;
  }

  public function getSiteUrl(): string {
    return $this->url;
  }

  public function getNewsLinks() {

  }

  public function stripInvalidLinks() {

  }

  public function extractDataFromLink() {

  }

}
