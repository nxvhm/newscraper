<?php

namespace Nxvhm\Newscraper\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model {

  public $table = 'newscraper_articles';

  public $guarded = [];

  public function site() {
    return $this->belongsTo(Site::class);
  }
}
