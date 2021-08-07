<?php

namespace Nxvhm\Newscraper;
use Nxvhm\Newscraper\Contracts\ArticleSaver;

class Factory {

  public static $namespaces = [
    'Nxvhm\\Newscraper\\Strategies\\'
  ];

  /**
   * Get an array with namespaces in which we have scraping strategies
   *
   * @return void
   */
  public static function getStrategiesNamespaces() {

    $configNamespace = config('newscraper.strategy_namespace', false);

    return $configNamespace
      ? array_merge(self::$namespaces, $configNamespace)
      : self::$namespaces;
  }

  /**
   * Try to find a strategy class and return an instance of it
   *
   * @param string $site
   * @return void
   */
  public static function getScrapingStrategy($site) {
    foreach(self::getStrategiesNamespaces() as $namespace) {
      if (class_exists($namespace.$site)) {
        $className = $namespace.$site;
        return new $className;
      }
    }
  }

  /**
   * Get a list of the available crawling strategies classes
   *
   * @return array
   */
  public static function getStrategiesClasses(): array {
    $res = [];

    # Get namespaces for strategies
    $namespaces = self::getStrategiesNamespaces();

    # Get composer autoload so we can get a list of all classes in our application
    $composer = require base_path() . '/vendor/autoload.php';

    if (!$composer) {
      throw new \Exception("Composer autoloader cannot be included");
    }

    # List of available classes
    $classes  = array_keys($composer->getClassMap());

    # Iterate through str.namespaces and get all classes available
    foreach($namespaces as $namespace) {

      $classesFromNamespace = array_filter($classes, function($someClass) use($namespace) {
        return strstr($someClass, $namespace);
      });

      $res = array_merge($res, $classesFromNamespace);
    }

    return $res;

  }
  /**
   * Check if custom save class is implementing the ArticleSaver
   * contract and if true return it
   * @throws Exception
   * @return String $saverClass Class
   */
  public static function getArticleSaverClass(): string
  {
    $saverClass = config('newscraper.custom_save');

    $ref = new \ReflectionClass($saverClass);

    if (!$ref->implementsInterface(ArticleSaver::class)) {
      throw new \Exception("Custom class $saverClass is not implementing the article saver contract");
    }

    return $saverClass;
  }

}
