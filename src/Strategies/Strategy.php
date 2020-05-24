<?php

namespace Nxvhm\Newscraper\Strategies;
use Symfony\Component\DomCrawler\Crawler;

abstract class Strategy {
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

  public function getArticleData(Crawler $crawler): array {

    # Initialize Empty array representing article data
    $data = array_fill_keys(array_keys($this->getContentSelectors()), "");

    foreach ($this->getContentSelectors() as $contentType => $selector) {

      if ($crawler->filter($selector)->count()) {

        $crawler->filter($selector)->each(function($node) use($contentType, &$data) {

          if ($contentType == 'date') {

            $dateStr = strtotime($node->attr('datetime'));

            $data[$contentType] = $dateStr ? date('Y-m-d', $dateStr) : $node->text();

          } else {
            $data[$contentType] .= $node->text();
          }
        });
      }
    }

    return $data;
  }


  public function getSiteName(): string {
    return $this->name;
  }

  public function getSiteUrl(): string {
    return $this->url;
  }


}
