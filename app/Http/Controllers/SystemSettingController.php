<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class SystemSettingController extends Controller
{
    public function syatemSettingSave(Request $request){
        
        $model = DB::table('system_sales')->insert(['SaleTax'=>$request->system_saleTax]);
        $request->session()->flash('message','data inserted successfully');
        
        
        return redirect()->route('system_setting');
    }
    
    public function showsaleTax(){
        $model = DB::table('system_sales')->select('SaleTax')->orderBy('id', 'DESC')->first();
        
        return view('backend.system.system_setting',compact('model'));
    }
}

