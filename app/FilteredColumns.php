<?php

namespace App;
use Auth;

use Illuminate\Database\Eloquent\Model;

class FilteredColumns extends Model
{
  public static function get_table_model($id)
  {
    $current_user = Auth::user();
    $user_id = $current_user->id;
    $menuData = FilteredColumns::where('menu',$id)
    ->where('user_id',$user_id)
    ->get();
    $columnAr ="";
    foreach ($menuData as $menus) {
      $column = $menus->menu;
      $menuOption = $menus->menu_option;
     $columnAr = unserialize($menuOption);
    }
    return $columnAr;
  }
  public static function default_table_model($id)
  {
    $current_user = Auth::user();
    $user_id = $current_user->id;
    $menuData = FilteredColumns::where('menu',$id)
    ->where('user_id',$user_id)
    ->first();

    $default_option_str = isset($menuData->default_option) ? $menuData->default_option : "";
    if($default_option_str != ""){
      $defaultOptArr = unserialize($default_option_str);
      if(!empty($defaultOptArr)){
        $default_option_str = json_encode($defaultOptArr);
      }
    }
    return $default_option_str;
  }
}
