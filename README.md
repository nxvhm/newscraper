# News Scraper Package for laravel

## Installation

1. Download package and paste the package in your laravel app.

2. Add the package folder path  in composer's repositories section as local. For example:
```

    "repositories": {
        "local": {
            "type": "path",
            "url": "app/Library/nxvhm/newscraper"
        }
    },

```
3. Run ``composer require "nxvhm\newscraper @dev"``. This will symlink the package to the vendor/ folder and install its dependencies and treat it as regular composer pkg.
At this point the package should be auto-discoverable from laravel.

4. Publish config and migration:
```
php artisan vendor:publish --provider="Nxvhm\Newscraper\NewscraperServiceProvider"
```
5. Run the migration
```
php artisan migrate
```


## Usage
Register strategies in database in order for them to have unique id
```
php artisan newscraper:register-sites
```

Start from artisan with the following cmd:               
```
  php artisan scrape:news {StrategyName}
```
Where strategy name is an existing strategy class.                         

To create scraping strategy class via CLI:
```
php artisan newscraper:create-strategy
```
After creating it, you should update the autoload maps and register it in db:
```
composer dump-autoload
php artisan newscraper:register-sites
```

### Custom article db save logic

For customized logic on saving article to db provide the responsible class in the config file in ``custom_save``. The class should implement the ``Nxvhm\Newscraper\Contracts\ArticleSaver`` contract. Example:

```
<?php

namespace App;
use Nxvhm\Newscraper\Strategies\Strategy;
use Nxvhm\Newscraper\Contracts\ArticleSaver as ArticleSaverInterface;
use Illuminate\Support\MessageBag;

class ArticleSaver implements ArticleSaverInterface
{
  public static function saveArticle(array $article, Strategy $strategy): MessageBag
  {
    # Custom logic goes here
    dd($article, $strategy->name);
  }
}
```

## ToDO

* Define site strategies from a config file
* --Allow strategy class lookup in a configurable namespaces not only in a single one-- 
* Implement more strategies
* Implement mechanism for Flexible Time/Date scraping 
