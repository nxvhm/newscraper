<?php

namespace Nxvhm\Newscraper\Strategies;

use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\MessageBag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Nxvhm\Newscraper\Models\Site;

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
  public function stripInvalidLinks($urls): array {
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

      if (!$selector) continue;

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
  /**
   * The default url validation function
   *
   * @param integer $partsCount The number of elements you get when you explode the url with "/" delimiter
   * @return void
   */
  public function urlValidationClosure($partsCount = 6) {
    return function($url) use ($partsCount) {
      if (null == $url) {
        return $url;
      }

      if ($url[0] == '/') {
        $url = $this->getSiteUrl().$url;
      }

      $parts = explode('/', $url);

      if (!$parts || !is_countable($parts) || count($parts) < $partsCount) {
        $url = null;
      }

      if (!filter_var($url, FILTER_VALIDATE_URL)) {
        $url = null;
      }

      return $url;
    };
  }
  /**
   * Retrieve the db record corresponsing to the Strategy
   * and return its model
   *
   * @return Illuminate\Database\Eloquent\Model
   */
  public function getSiteModel(): Model {
    return Site::where('name', $this->name)->first();
  }

  /**
   * Save article data in db
   *
   * @param array $article
   * @throws Exception
   * @return Illuminate\Support\MessageBag
   */
  public function saveData(array $article): MessageBag {
    $articleModel = config('newscraper.model', false);

    if (!$articleModel || !\class_exists($articleModel)) {
      throw new \Exception("$articleModel not found");
    }

    $site = $this->getSiteModel();

    if (!$site) {
      throw new \Exception("Site model not found. Cant save article");
    }

    $msg = new MessageBag();

    $record = $articleModel::where('url', $article['url'])->first();

    if ($record) {
      $msg->add('exists', true);
      return $msg;
    }

    try {
      $article['site_id'] = $site->id;
      $record = $articleModel::create($article);
      $msg->add('success', true);
      $msg->add('id', $record->id);

    } catch(\Exception $e) {

      Log::error($e);
      $msg->add('error', $e->getMessage());

    }


    return $msg;

  }




}
