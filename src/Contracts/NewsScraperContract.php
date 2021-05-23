<?php

namespace Nxvhm\Newscraper\Contracts;

interface NewsScraperContract
{
  /**
   * Set the website name, which will be scraped
   * Search for strategy class in implementation, if found then instanciate
   * the newscraping class with the provided strategy
   *
   * @param string $siteName
   * @return void
   */
  public static function init(string $siteName);

  /**
   * Get list of available news urls
   *
   * @return array
   */
  public function getListOfLinks(): array;

  /**
   * Scrape article data from url
   *
   * @return array
   */
  public function articleFromLink(string $url): array;

}
