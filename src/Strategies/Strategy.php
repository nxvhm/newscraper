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

    $pages = array_map(function($page) {
      $url =  $this->getSiteUrl().$page;
      return filter_var($url, FILTER_VALIDATE_URL) ? $url : null;
    }, $this->pagesToCrawl);

    return array_filter($pages, 'strlen');
  }


}
