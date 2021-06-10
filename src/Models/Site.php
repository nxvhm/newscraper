<?php

namespace Nxvhm\Newscraper\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model {

  public $table = 'newscraper_sites';

  public $guarded = [];

  public $timestamps = false;

  public function articles() {
    return $this->hasMany(Article::class);
  }

}
