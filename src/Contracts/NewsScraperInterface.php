<?php

namespace Nxvhm\Newscraper\Contracts;

interface NewsScraperInterface
{

  public function getNewsLinks();

  public function stripInvalidLinks();

  public function extractDataFromLink();

}
