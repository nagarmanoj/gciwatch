<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FilteredColumns;
use Auth;

class FilteredcolumnsController extends Controller
{
  public function productFilterAjax(Request $request)
  {
      $reqMenu =$request->menu;
      $reqDefaultOption =isset($request->default_option) ? $request->default_option : "";
      $menuOpt = "";
      if(!empty($request->columnArr)){
        $menuOpt = serialize($request->columnArr);
      }

      $current_user = Auth::user();
      $user_id = $current_user->id;
      $menuData = FilteredColumns::where('menu',$reqMenu)->where('user_id',$user_id)->get()->count();
      if($menuData > 0){
        $filteredProduct = FilteredColumns::where('menu',$reqMenu)->where('user_id',$user_id)->firstOrFail();
      }else{
        $filteredProduct = new FilteredColumns;
        $filteredProduct->menu = $request->menu;

    }
    $filteredProduct->menu_option = $menuOpt;
    if($reqDefaultOption == 1){
        $filteredProduct->default_option = $menuOpt;
    }

    $filteredProduct->user_id = $user_id;
    $filteredProduct->save();
     return response()->json(['success' => true]);
  }

}
