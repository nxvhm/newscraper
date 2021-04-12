<?php

namespace Nxvhm\Newscraper\Strategies;
use Symfony\Component\DomCrawler\Crawler;

abstract class Strategy {

  /**
   * Holds name of selectors, which may return more then one result,
   * but only the first should be considered
   *
   * @var array
   */
  public $onlyFirstResult = [];

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


  public function getSiteName(): string {
    return $this->name;
  }

  public function getSiteUrl(): string {
    return $this->url;
  }

  /**
   * Return array containing dom selectors
   *
   * @param Void
   *
   * @return Array
   */
  public function getContentSelectors(): array {
    return $this->contentSelectors;
  }

  /**
   * Filter all urls which are not pointing to an article
   *
   * @param   Array  $urls Raw extracted hrefs
   * @return  Array  Filtered links pointing to an article
   */
  public function stripInvalidLinks($urls) {
    return array_unique(array_filter(
      array_map([$this, 'validateAndFormatUrl'], $urls)
    ));
  }

  /**
   * Scrape data through the define content selectors
   * @param  Crawler $crawler Instance containing page markup
   * @return Array
   */
  public function getArticleData(Crawler $crawler): array {

    # Initialize Empty array representing article data
    $data = array_fill_keys(array_keys($this->getContentSelectors()), "");

    foreach ($this->getContentSelectors() as $contentType => $selector) {

      # If method is implemented for parsing the specific content type,
      # then execute that method and continue with next contentType
      $methodName = 'parse'.ucfirst($contentType);
      if (method_exists($this, $methodName)) {
        $data[$contentType] = call_user_func_array([$this, $methodName], [$crawler]);
        continue;
      }

      # Execute css selector for a given contentType
      if ($crawler->filter($selector)->count()) {

        if (!in_array($contentType, $this->onlyFirstResult)) {
          # Iterate over the nodes from the query selector and get the content from all of them
          $crawler->filter($selector)->each(function($node) use($contentType, &$data) {

            if ($contentType == 'date') {

              $dateStr = $node->attr('datetime')
                ? strtotime(substr($node->attr('datetime'), 0, 10))
                : strtotime($node->text());

              $data[$contentType] = $dateStr ? date('Y-m-d', $dateStr) : $node->text();

            } else {
              $data[$contentType] .= $node->text();
            }
          });

        } else {

          # Get only the first node from the query selector results
          $node = $crawler->filter($selector)->first();
          $data[$contentType] = $node->text();
        }

      }
    }

    return $data;
  }




}
