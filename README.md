# News Scraper Package for laravel

## Installation

1. Download package and paste the package in your laravel app.

2. Update composer to autoload it. For example:
```
"autoload": {
    "psr-4": { "Nxvhm\\Newscraper" : "app/Library/Nxvhm/Newscraper" }
}
```
3. Run ``composer dump-autoload``

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

## ToDO

* Define site strategies from a config file
* --Allow strategy class lookup in a configurable namespaces not only in a single one-- 
* Implement more strategies
* Implement mechanism for Flexible Time/Date scraping 
