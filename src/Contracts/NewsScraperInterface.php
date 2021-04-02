<?php

namespace Nxvhm\Newscraper\Contracts;
use Symfony\Component\DomCrawler\Crawler;

interface NewsScraperInterface
{

  /**
   * Implement logic to decide what is valid/invalid url
   * Currently used Used as array_map callback
   *
   * @param   String  $url
   *
   * @return  String
   */
  public function validateAndFormatUrl(string $url);

  /**
   * Implement logic to extract all of the required article data from a web page
   *
   * @param   String  $link  Valid url
   *
   * @return  Array
   */
  public function getArticleData(Crawler $crawler): array;
}
