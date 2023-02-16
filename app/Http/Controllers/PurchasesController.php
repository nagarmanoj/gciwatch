<?php

namespace App\Http\Controllers;

use App\Productcondition;

use App\ProductTranslation;

use App\ReturnProd;

use App\Transfer;

use App\TransferItem;

use App\ProductStock;

use App\Models\Sequence;

use App\Models\Warehouse;

use App\Category;

use App\Activitylog;

use App\SiteOptions;

use App\FlashDealProduct;

use Illuminate\Http\Request;

use App\Models\Purchases;

use App\Brand;

use App\Product;

use App\Memo;

use App\MemoDetail;

use App\Upload;

use App\PurchasesExport;

use Excel;

use App\Models\Producttype;

use App\Tag;

use \InvPDF;

use App\ProductTax;

use App\AttributeValue;

use App\Cart;

use Illuminate\Support\Facades\DB;

use App\FilteredColumns;

class PurchasesController extends Controller

{

   // Warehouse Start

    public function purchases(Request $request)

    {

      $sort_search =null;

        $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;

        if($request->input('pagination_qty')!=NULL){

            $pagination_qty =  $request->input('pagination_qty');

        }

        $PurchasesProduct=Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop','category','productcondition','productType')

        ->leftJoin('warehouse','warehouse.id','=','products.warehouse_id')

        ->leftJoin('users','users.id','=','products.supplier_id')

        ->leftJoin('product_types','product_types.id','=','products.product_type_id')

        ->leftJoin('brands','brands.id','=','products.brand_id')

        ->leftJoin('product_stocks','products.id','=','product_stocks.product_id')

        ->leftJoin('site_options','site_options.option_value','=','products.model')

        ->leftJoin('productconditions','productconditions.id','=','products.productcondition_id')

        ->leftJoin('categories','categories.id','=','products.category_id')

        ->orderBy('products.id', 'DESC')

        ->groupBy('products.id')

        ->select('products.*','warehouse.name as warehouseName','users.name as supplienName','site_options.low_stock','memo_details.product_id','memo_details.item_status','product_types.listing_type','brands.name as brandName','product_stocks.sku','product_stocks.qty','productconditions.name as productconditions_name')

          ->leftJoin('memo_details','memo_details.product_id','=','products.id');

        //   ->leftJoin('memos', 'memos.id', '=', 'memo_details.memo_id');
        $startrangedate=$request->startrangedate;
        $endrangedate=$request->endrangedate;
        // dd($startrangedate);
        $startdate=  date('y-m-d', strtotime($startrangedate));
        $endate=  date('y-m-d', strtotime($endrangedate));
        // dd($startrangedate.' 00:00:00');
      if ($request->startrangedate || $request->endrangedate) {
          $PurchasesProduct = $PurchasesProduct->whereBetween('products.created_at', [$startdate.' 00:00:00',$endate.' 23:59:59']);
      }

        $warehouseSrch =$request->warehouse_id;

        $supplier_id=$request->supplier_id;

        $reference_id=$request->reference_id;

        $listing_type=$request->producttypee;

       $product_qty=$request->purchases_pagi;

        if ($request->search != null){

                        $PurchasesProduct->orWhere('listing_type', 'like', '%'.$request->search.'%');

                        $PurchasesProduct->orWhere('products.stock_id', 'like', '%'.$request->search.'%');

                        $PurchasesProduct->orWhere('users.name', 'like', '%'.$request->search.'%');

                        $PurchasesProduct->orWhere('brands.name', 'like', '%'.$request->search.'%');

                        $PurchasesProduct->orWhere('categories.name', 'like', '%'.$request->search.'%');

                        $PurchasesProduct->orWhere('product_types.listing_type', 'like', '%'.$request->search.'%');

                        $PurchasesProduct->orWhere('product_types.product_type_name', 'like', '%'.$request->search.'%');

                        $PurchasesProduct->orWhere('products.model', 'like', '%'.$request->search.'%');

                        $PurchasesProduct->orWhere('products.partner', 'like', '%'.$request->search.'%');

                        $PurchasesProduct->orWhere('warehouse.name', 'like', '%'.$request->search.'%');

            $sort_search = $request->search;

        }

     

       
      

        if($reference_id>0)

        {

           $PurchasesData=$PurchasesProduct->where('products.id',$reference_id);

        }

        if($listing_type != "")

        {

           $PurchasesData=$PurchasesProduct->where('listing_type',$listing_type);

        }

        if($supplier_id>0)

        {

           $PurchasesData=$PurchasesProduct->where('supplier_id',$supplier_id);

        }

        if($warehouseSrch > 0)

        {

         $PurchasesData = $PurchasesProduct->where('warehouse_id', $warehouseSrch);

        }

    

        if( $request->pagination_qty == "all"){

          $PurchasesData = $PurchasesProduct->get();

        }else{

          $PurchasesData = $PurchasesProduct->paginate($pagination_qty);

        }

        return view("backend.purchases.index", compact('PurchasesData','pagination_qty','sort_search'));



    }



    public function export(Request $request)



    {



        $ids = $request->checked_id;



        // dd($ids);



          $proID = json_decode($ids, TRUE);



          $fetchLiaat = new PurchasesExport($proID);

          $dt = new \DateTime();

          $curntDate = $dt->format('m-d-Y');

        return Excel::download($fetchLiaat, 'Purchases_'.$curntDate.'.xlsx');



    }



    // function purchases_filter(Request $request,$warehouse_id)



    // {



    //           echo $warehouse_id;



    // }







    function generatePDF(Request $request, $id)



    {



        $PurchasesProduct=Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop','category','productcondition','productType')



        ->leftJoin('warehouse','warehouse.id','=','products.warehouse_id')



        ->leftJoin('users','users.id','=','products.supplier_id')



        ->leftJoin('product_types','product_types.id','=','products.product_type_id')



        ->leftJoin('brands','brands.id','=','products.brand_id')

        ->leftJoin('productconditions','productconditions.id','=','products.productcondition_id')



        ->join('product_stocks','products.id','=','product_stocks.product_id')



        ->join('site_options','site_options.option_value','=','products.model')



       ->orderBy('products.id', 'DESC')

        ->groupBy('products.id')

       ->select('products.*','warehouse.name as warehouseName','users.name as supplienName','users.email as supplienemail','users.address as supplienaddress','users.country as suppliencountry','users.city as suppliencity','users.phone as supplienphone','site_options.low_stock','memo_details.product_id','memo_details.item_status','product_types.listing_type','brands.name as brandName','product_stocks.sku','product_stocks.qty','productconditions.name as productconditions_name','memos.id as memo_id')

          ->leftJoin('memo_details','memo_details.product_id','=','products.id')

          ->leftJoin('memos', 'memos.id', '=', 'memo_details.memo_id')

          ->where('products.id',$id)->first();

        //   dd($PurchasesProduct);

      $url = 'https://gcijewel.com/public/uploads/all/vYaq660ap6T3vRppb83IikSpRb6hKf5xvKr1G6Wg.jpg';

      $image = file_get_contents($url);

        $imgbase64 = "";

      if ($image !== false){

          $imgbase64 = 'data:image/jpg;base64,'.base64_encode($image);

      }

      $sale_tax=DB::table('system_sales')->orderBy('id','DESC')->first();

      $pdf = \App::make('dompdf.wrapper');

      $pdf = InvPDF::loadView('backend.purchases.purchasesPdf',compact('PurchasesProduct','sale_tax','imgbase64'))->setOptions(['defaultFont' => 'sans-serif']);

      return $pdf->stream('purchases.pdf');

    }

}

