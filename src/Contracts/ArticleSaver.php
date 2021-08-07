<?php

namespace Nxvhm\Newscraper\Contracts;
use Nxvhm\Newscraper\Strategies\Strategy;
use Illuminate\Support\MessageBag;

interface ArticleSaver
{
  public static function saveArticle(array $article, Strategy $strategy): MessageBag;
}
