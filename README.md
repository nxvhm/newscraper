# News Scraper Package for laravel

## Installation

1. Download package and paste the package in your laravel app.

2. Update composer to autoload it. For example:
```
"autoload": {
    "psr-4": { "Nxvhm\\Newscraper" : "app/Library/Nxvhm/Newscraper" }
}
```
2.1 Run ``composer dump-autoload``
3. Publish config and migration:
```
php artisan vendor:publish --provider="Nxvhm\Newscraper\NewscraperServiceProvider"
```
4. Run the migration
```
php artisan migrate
```


## Usage
Start from artisan with the following cmd:               
```
  php artisan scrape:news {StrategyName}
```
Where strategy name is an existing strategy class. 

## ToDO

* Define site strategies from a config file
* Allow strategy class lookup in a configurable namespaces not only in a single one 
* Implement more strategies
* Implement mechanism for Flexible Time/Date scraping 
