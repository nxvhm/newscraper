<?php

return [
  /**
   * The model responsible for handling db stuff
   */
  'model' => Nxvhm\Newscraper\Models\Article::class,
  /**
   * Namespaces for strategies, outside the pkg
   */
  'strategy_namespace' => [],

  /**
   * Custom eloquent model for saving the strategy classes, if no class provided
   * Nxvhm\Newscraper\Models\Site will be used
   */
  'site_model' => null,

  /**
   * Pass class and method which will handle article save
   * If null, default save method from Newscraper\Strategies\Strategy
   * will be used
   * Example: MyNamespace\MySaver::class
   */
  'custom_save' => null,
];
