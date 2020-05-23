<?php

namespace Nxvhm\Newscraper\Contracts;
use Symfony\Component\DomCrawler\Crawler;

interface NewsScraperInterface
{

  /**
   * Implement logic to decide what is valid/invalid link
   *
   * @param   Array  $links
   *
   * @return  Array
   */
  public function stripInvalidLinks(array $urls): array;

  /**
   * Implement logic to extract all of the required article data from a web page
   *
   * @param   String  $link  Valid url
   *
   * @return  Array
   */
  public function getArticleData(Crawler $crawler): array;

  /**
   * Return array containing dom selectors
   *
   * @param Void
   *
   * @return Array
   */
  public function getContentSelectors(): array;

}
