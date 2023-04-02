<?php

namespace Nxvhm\Newscraper\Commands;
use Illuminate\Console\Command;
use Nxvhm\Newscraper\Newscraper;
use Nxvhm\Newscraper\Factory;
use Nxvhm\Newscraper\Exceptions\InvalidResponseException;
use Log;

class ScrapeUrl extends Command
{
    /**
     * @var string
     */
    protected $signature = 'scrape:url {url} {--timeout=1} {--save=1} {--rescrape}';

    protected $description = 'Scrape article from given url';

    public function handle() {
        $url = $this->argument('url');

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $this->error(sprintf("Invalid url provider: %s", $url));
            return false;
        }

        try {

            $strategy = Factory::getStrategyFromUrl($url);

        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return;
        }

        $scraper = new Newscraper($strategy);
        $data = $scraper->articleFromLink($url);
        if (config('newscraper.custom_save')) {

          $saveClass = Factory::getArticleSaverClass();

          $msg = call_user_func_array(
            [$saveClass, 'saveArticle'],
            [$article, $scraper->strategy]
          );

        } else {
          $msg = $scraper->strategy->saveData($article);
        }


    }

}
