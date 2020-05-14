<?php

namespace Nxvhm\Newscraper\Strategies;

abstract class Strategy {
  /**
   * Get the name of the site
   *
   * @return  String
   */
  abstract function getSiteName();
  /**
   * Get full site url
   *
   * @return  String
   */
  abstract function getSiteUrl();

  /**
   * Get array with absolute urls specifiyng website pages
   * from which we will gather article links
   *
   * @return  Array
   */
  public function getPagesToCrawl(): array {
    return array_map(function($page) {
      return $this->getSiteUrl().$page;
    }, $this->pagesToCrawl);
  }


}
