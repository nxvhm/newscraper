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
   * Get array with relative urls specifiyng website pages
   * from which we will gather article links
   *
   * @return  Array
   */
  abstract function getPagesToCrawl();

}
