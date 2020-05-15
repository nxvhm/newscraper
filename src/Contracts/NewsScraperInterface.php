<?php

namespace Nxvhm\Newscraper\Contracts;

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
   * Implement logic to extract all of the required data from a web page
   *
   * @param   String  $link  Valid url
   *
   * @return  Array
   */
  public function extractDataFromLink(string $url): array;

}
