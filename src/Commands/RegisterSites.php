<?php

namespace Nxvhm\Newscraper\Commands;

use Illuminate\Console\Command;
use Nxvhm\Newscraper\Factory;
use Nxvhm\Newscraper\Contracts\CrawlingStrategyContract;
use Nxvhm\Newscraper\Models\Site;
use Log;

class RegisterSites extends Command {
  /**
   * @var string
   */
  protected $signature = 'newscraper:register-sites';
  /**
   * @var string
   */
  protected $description = 'Iterate through all crawling strategies/configs and register the websites in database, in order for them to receive unique id in the system';

  public function handle() {

    $classes = Factory::getStrategiesClasses();

    foreach($classes as $className) {
      $ref = new \ReflectionClass($className);

      if ($ref->isAbstract()) {
        continue;
      }

      if (!$ref->implementsInterface(CrawlingStrategyContract::class)) {
        continue;
      }

      $props = $ref->getDefaultProperties();

      foreach(['name', 'url'] as $requiredProp) {

        if (!isset($props[$requiredProp]) || empty($props[$requiredProp])) {
          $this->error("Site $requiredProp is not defined for class: $className");
          continue;
        }
      }

      $siteName = $props['name'];

      $record = Site::where('name', $props['name'])->first();
      $record = $record ?? Site::create(['name' => $siteName, 'url' => $props['url']]);

      $this->info("$siteName is registered with id $record->id");

    }


  }

}
