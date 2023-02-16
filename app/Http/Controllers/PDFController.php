<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Memo;
use App\MemoDetail;
use App\Product;
use Illuminate\Support\Facades\DB;
use \InvPDF;
use App\ProductStock;

use App\Productcondition;
use App\Country;
use App\Models\Producttype;


class PDFController extends Controller
{
  function viewinvoice(Request $request, $id,$status)
  {
    $memo = Memo::findOrFail($id);
    $memo =DB::table('memos')
             ->join('retail_resellers', 'retail_resellers.id', '=', 'memos.customer_name')
             ->select('memos.*','retail_resellers.company','retail_resellers.customer_name','retail_resellers.billing_address','retail_resellers.billing_city',
             'retail_resellers.text_billing_state','retail_resellers.billing_zipcode','retail_resellers.phone','retail_resellers.shipping_address','retail_resellers.city',
             'retail_resellers.shipping_city','retail_resellers.text_shipping_state','retail_resellers.shipping_zipcode','retail_resellers.customer_group',
             'retail_resellers.office_address','retail_resellers.zipcode','retail_resellers.email','retail_resellers.drop_state','retail_resellers.office_phone_number')
             ->where('memos.id',$id)
             ->first();
             
    $memo_details_data = DB::table('memo_details')
                        ->join('products', 'products.id', '=', 'memo_details.product_id')
                        ->select('memo_details.id','memo_details.item_status','memo_details.row_total','memo_details.product_qty','products.stock_id',
                        'products.model','products.description','memo_details.product_price')
                        ->where('memo_details.memo_id',$id)
                        ->where('memo_details.item_status',$status)
                        ->get();
                       

    $url = 'https://gcijewel.com/public/uploads/all/vYaq660ap6T3vRppb83IikSpRb6hKf5xvKr1G6Wg.jpg';
    $image = file_get_contents($url);
    $imgbase64 = "";
    if ($image !== false){
        $imgbase64 = 'data:image/jpg;base64,'.base64_encode($image);
    }
    return view("backend.memo.invoice.viewinvoice",compact('memo','memo_details_data','imgbase64'));
  }
  function generatePDF(Request $request, $id,$status)
  {
    // $memo =DB::table('memos')
    //          ->join('retail_resellers', 'retail_resellers.id', '=', 'memos.customer_name')
    //          ->join('memo_details','memo_details.memo_id','=','memos.id')
    //          ->select('memos.*','retail_resellers.company','retail_resellers.customer_name','retail_resellers.billing_address','retail_resellers.billing_city',
    //          'retail_resellers.text_billing_state','retail_resellers.billing_zipcode','retail_resellers.phone','retail_resellers.shipping_address','retail_resellers.city',
    //          'retail_resellers.shipping_city','retail_resellers.text_shipping_state','retail_resellers.shipping_zipcode','memo_details.id as memodetailid','memo_details.product_id',
    //          'retail_resellers.customer_group','retail_resellers.office_address','retail_resellers.zipcode','retail_resellers.email','retail_resellers.drop_state','retail_resellers.office_phone_number')
    //          ->where('memos.id',$id)
    //          ->first();
              $memo =DB::table('memos')
                     ->join('retail_resellers', 'retail_resellers.id', '=', 'memos.customer_name')
                     ->join('memo_details','memo_details.memo_id','=','memos.id')
                     ->select('memos.*','retail_resellers.company','retail_resellers.customer_name','retail_resellers.billing_address','retail_resellers.billing_city',
                     'retail_resellers.text_billing_state','retail_resellers.billing_zipcode','retail_resellers.phone','retail_resellers.shipping_address','retail_resellers.city',
                     'retail_resellers.shipping_city','retail_resellers.text_shipping_state','retail_resellers.shipping_zipcode','memo_details.id as memodetailid','memo_details.product_id',
                     'retail_resellers.customer_group','retail_resellers.office_address','retail_resellers.zipcode','retail_resellers.email','retail_resellers.drop_state','retail_resellers.office_phone_number')
                     ->where('memos.id',$id)
                    //  ->where('memos.memo_status',$status)
                     ->first();
                     
    
    // $product= Product::where('id','=',$memo->product_id)->first();
    //  $ProductStockID = ProductStock::where('product_id','=',$memo->product_id)->first()->id;
     
    // $productTypeId= $product->product_type_id;
    //  $productconditionid = $product->productcondition_id;
    
    
     $product= Product::where('id','=',$memo->product_id)->first();
    //   dd($product);
     $ProductStockID = ProductStock::where('product_id','=',$memo->product_id)->first()->id;
    // dd($ProductStockID);
    $productTypeId= $product->product_type_id;
    // dd($productTypeId);
     $productconditionid = $product->productcondition_id;
    // dd($productconditionid);
    
     
    // $memo_details_data = DB::table('memo_details')
    //                     ->join('products', 'products.id', '=', 'memo_details.product_id')
    //                     ->join('product_types','product_types.id','=','products.product_type_id')
    //                     ->join('product_stocks','products.id','=','product_stocks.product_id')
    //                     ->LeftJoin('productconditions','productconditions.id','products.productcondition_id')
    //                     ->select('memo_details.id','memo_details.item_status','memo_details.row_total','memo_details.product_price',
    //                     'memo_details.product_qty','products.stock_id','products.product_type_id','products.model',
    //                     'products.productcondition_id','products.custom_6','products.paper_cart','products.custom_4',
    //                     'products.custom_3','products.metal','products.custom_5','products.weight','product_types.listing_type','product_stocks.sku','productconditions.name')
    //                     ->where('memo_details.memo_id',$id)
    //                     ->where('memo_details.item_status',$status)
    //                     ->where('products.product_type_id', $productTypeId)
    //                     ->where('product_stocks.id',$ProductStockID)
    //                     ->where('productconditions.id',$productconditionid)
    //                     ->get();
  $memo_details_data = DB::table('memo_details')
                        ->join('products', 'products.id', '=', 'memo_details.product_id')
                        ->join('product_types','product_types.id','=','products.product_type_id')
                        ->join('product_stocks','products.id','=','product_stocks.product_id')
                        ->leftJoin('productconditions','productconditions.id','products.productcondition_id')
                        ->select('memo_details.id','memo_details.item_status','memo_details.row_total',
                        'memo_details.product_qty','products.stock_id','products.product_type_id','products.model',
                        'products.productcondition_id','products.custom_6','products.paper_cart','products.custom_4',
                        'products.custom_3','products.metal','products.custom_5','products.weight','product_types.listing_type','product_stocks.sku','productconditions.name','memo_details.product_price')
                        ->where('memo_details.memo_id',$id)
                        ->get();
    
    $url = 'https://gcijewel.com/public/uploads/all/vYaq660ap6T3vRppb83IikSpRb6hKf5xvKr1G6Wg.jpg';
    $image = file_get_contents($url);
    $imgbase64 = "";
    if ($image !== false){
        $imgbase64 = 'data:image/jpg;base64,'.base64_encode($image);
    }
    $pdf = \App::make('dompdf.wrapper');
    $pdf = InvPDF::loadView('backend.memo.invoice.viewinvoice',compact('memo','memo_details_data','imgbase64'))->setOptions(['defaultFont' => 'sans-serif']);
    return $pdf->stream('invoice.pdf');
  }
}
