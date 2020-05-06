<?php

namespace Nxvhm\Newscraper;

class Factory {

  public static $namespaces = [
    '\\Nxvhm\\Newscraper\\Strategies\\'
  ];

  public static function getStrategiesNamespaces() {

    $configNamespace = config('newscraper.strategy_namespace', false);

    return $configNamespace
      ? array_merge(self::$namespaces, $configNamespace)
      : self::$namespaces;
  }

  public static function getScrapingStrategy($site) {
    foreach(self::getStrategiesNamespaces() as $namespace) {
      if (class_exists($namespace.$site)) {
        $className = $namespace.$site;
        return new $className;
      }
    }
  }

}
