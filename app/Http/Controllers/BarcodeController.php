<?php

namespace App\Http\Controllers;
use App\Product;
use App\Models\Producttype;
use App\ProductTranslation;
use App\ProductStock;
use App\Models\Sequence;
use Illuminate\Support\Facades\DB;
use \InvPDF;

use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    public function barcode()
	{
    $proarr="";
	    return view('backend.product.products.barcode', compact('proarr'));
	}
    public function store(Request $request)
	{

      $proarrData = isset($request->proarrkey) ? $request->proarrkey: "";
      if($proarrData != ""){
        $peoStckkeys = array_keys($proarrData);
        $proarr = Product::whereIn('id',$peoStckkeys)->get();
      }else{
      $proarr = array();
      }
      $ispdfprint = isset($request->ispdfprint) ? $request->ispdfprint: "";
      if($ispdfprint == 1){
        $pdffilename = 'barcodeproduct_'.date('y_m_d').'.pdf';
        $pdf = \App::make('dompdf.wrapper');
        $pdf = InvPDF::loadView('backend.product.products.barcodepdf',compact('proarr'))->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->stream($pdffilename);
        return view('backend.product.products.barcodepdf', compact('proarr'));
      }else{
        return view('backend.product.products.barcode', compact('proarr'));
      }

	}
    public function storeproid(Request $request,$id)
	{
        $proarr =array();
        $proarr[] = Product::findOrFail($id);
	    return view('backend.product.products.barcode', compact('proarr'));
	}
}
