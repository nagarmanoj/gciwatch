<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteOptions extends Model
{
    // protected $with = ['site_options_translations'];

    // public function getTranslation($field = '', $lang = false){
    //     $lang = $lang == false ? App::getLocale() : $lang;
    //     $site_options_translation = $this->site_options_translations->where('lang', $lang)->first();
    //     return $site_options_translation != null ? $site_options_translation->$field : $this->$field;
    // }
  
    // public function site_options_translations(){
    //   return $this->hasMany(BrandTranslation::class);
    // }

}
