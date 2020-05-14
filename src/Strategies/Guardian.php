<?php

namespace Nxvhm\Newscraper\Strategies;

use Nxvhm\Newscraper\Contracts\NewsScraperInterface;

class Guardian extends Strategy implements NewsScraperInterface
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
    '/international',
    '/uk-news',
    '/global-development'
  ];

  public $bodyTextQueries = [
    // '.article-content__text'
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
