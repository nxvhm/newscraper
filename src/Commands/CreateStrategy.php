<?php

namespace Nxvhm\Newscraper\Commands;

use Illuminate\Console\Command;
use Nxvhm\Newscraper\Factory;
use Nxvhm\Newscraper\Contracts\CrawlingStrategyContract;
use Nxvhm\Newscraper\Models\Site;

class CreateStrategy extends Command {
  /**
   * @var string
   */
  protected $signature = 'newscraper:create-strategy';
  /**
   * @var string
   */
  protected $description = 'Create crawling strategy';

  public function handle() {
    $stubFilePath = __DIR__ . "/../../stubs/StrategyStub.stub";

    if (!file_exists($stubFilePath)) {
      $this->error($stubFilePath . " not found on the filesystem. Cannot continue");
    }

    $name = null;
    $url = null;
    $className = null;

    while(!$name) {
      $name = $this->ask("What's the website name, you want to scrape?");
    }

    while (!$className) {
      $className = $this->ask("What will be the class name of the strategy ?");
    }


    while(!$url) {
      $url  = $this->ask("Enter fully qualified valid http/s address");

      if (!filter_var($url, FILTER_VALIDATE_URL)) {
        $url = null;
        $this->line("Please provider valid url");
      } else if (substr($url, -1) == '/') {
        $url = substr($url, 0, -1);
      }
    }

    $pages = [];

    array_push($pages, str_replace($url, '', $this->ask("Enter pages urls you want to crawl")));

    $extraPages = true;

    while ($extraPages) {
      $extraPages = $this->confirm("Add more pages to crawl ?", true);

      if ($extraPages) {
        $page = $this->ask("Enter pages urls you want to crawl");

        if (!filter_var($page, FILTER_VALIDATE_URL) || strpos($page, $url) === false) {
          $this->line("Please enter valid url from $url");
        } else {
          array_push($pages, str_replace($url, '', $page));
        }
      }
    }

    $pages = array_unique($pages);

    $selectorsToFill = [
      'title',
      'description',
      'date',
      'author',
      'category',
      'text'
    ];

    $selectors = [];

    foreach ($selectorsToFill as $key => $selectorName) {
      $value = $this->ask("Enter dom query selector for $selectorName");
      $selectors[$selectorName] = $value;
    }

    // dd($name, $url, $pages, $selectors);
    $stub = file_get_contents($stubFilePath);

    $stub = str_replace('{{ name }}', '"'.$name . '"', $stub);
    $stub = str_replace('{{ className }}', $className, $stub);
    $stub = str_replace('{{ url }}', '"'.$url.'"', $stub);


    # Generate pagesToCrawl array contents
    $pagesStr = "";
    foreach ($pages as $page) {
      $pagesStr .= "'".$page."',\n    ";
    }

    $stub = str_replace('{{ pagesToCrawl }}', $pagesStr, $stub);

    # Generate $contentSelector array contents
    $selectorsStr = "";
    foreach ($selectors as $name => $selector) {
      $selectorsStr .= "'".$name."' => '".$selector."',\n   ";
    }

    $stub = str_replace('{{ contentSelectors }}', $selectorsStr, $stub);

    # Save strategy
    $outputFilename = $className . '.php';
    $path = __DIR__.'/../Strategies/'.$outputFilename;
    file_put_contents($path, $stub);

    $this->info("$className strategy generated under: $path");

    // dd($stub);
  }

}
