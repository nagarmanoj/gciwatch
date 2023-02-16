<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Memo;
use App\Category;
use App\JobOrder;
use App\JobOrderDetail;
use App\ProductType;
use App\Product;
use App\RetailReseller;
use App\SiteOptions;
use Cache;
use CoreComponentRepository;
use Illuminate\Support\Facades\DB;
class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
     public function admin_dashboard(Request $request)
     {

         CoreComponentRepository::initializeCache();

         $root_categories = Category::where('level', 0)->get();
         $partnersNewData = SiteOptions::where('option_name', 'model')->get();

         $arrSt = array(0,1);
         $memos= Memo::select('memos.*','memos.customer_name as memo_customer_name'  ,'retail_resellers.company','retail_resellers.customer_group','retail_resellers.customer_name as cu_name','memo_details.item_status','retail_resellers.id as company_id','product_stocks.sku')
                 ->join('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
                 ->join('memo_details', 'memos.id', '=', 'memo_details.memo_id')
                 ->join('products', 'memo_details.product_id', '=', 'products.id')
                 ->join('product_stocks','products.id','=','product_stocks.product_id')
                 ->leftJoin('memo_payments', 'memo_payments.id', '=', 'memos.payment')
                 ->selectRaw('GROUP_CONCAT(stock_id) as stocks')
                 ->selectRaw('GROUP_CONCAT(model) as model_numbers')
                 ->groupBy('memo_details.memo_id')
                 ->whereIn('memo_details.item_status',$arrSt)
                 ->orderBy('memos.id', 'DESC');
         $sort_search = isset($request->memo_s) ? $request->memo_s : '';
         if($sort_search != null){
           $memos = $memos->where(function($query) use ($sort_search){
             $query->where('memos.memo_number', 'LIKE', '%'.$sort_search.'%');
           });
         }
         $memo_details=$memos->paginate(25,["*"],'memo');










         $warehouse_detail=Product::select('products.*',DB::raw('SUM(products.unit_price) as totalPrice'),'product_types.id as pro_ty_id','product_types.listing_type','warehouse.id',\DB::raw('COUNT(product_types.listing_type) as total_count'),'memo_details.item_status','product_stocks.qty')
         ->join('product_types','product_types.id','=','products.product_type_id')
         ->leftJoin('memo_details', 'memo_details.product_id', '=', 'products.id')
         ->leftJoin('product_stocks','products.id','=','product_stocks.product_id')
         ->join('warehouse','warehouse.id','=','products.warehouse_id')
         ->groupBy('product_types.listing_type');
         $watch_item='';


         $warehouseSrch =$request->warehouse_id;
         if($warehouseSrch > 0)
         {
             $warehouse_detail = $warehouse_detail->where('warehouse_id', $warehouseSrch);
         }

         $arrSt = array(0,1);
         $stock =$request->stock;
         if($stock == 2)
         {
             $warehouse_detail = $warehouse_detail->where('published', '=', '1')->where('product_stocks.qty','>=',1);
         }
         elseif($stock == 3)
         {
             $warehouse_detail = $warehouse_detail->whereIn('memo_details.item_status', $arrSt);
         }

         $stockvalue =$request->value;
         $countedArr = array();
         if($stockvalue == 2)
         {
             $warehouse_detailNet = $warehouse_detail->where('published', '=', '1')
                                 ->where('partner', '!=' ,'GCI');
             $warehouse_detailNet = $warehouse_detailNet->get();
             foreach ($warehouse_detailNet as $warehouse_value) {
               $countedArr[$warehouse_value->listing_type] = $warehouse_value->totalPrice;
             }
         }
         elseif($stockvalue == 1)
         {
             $warehouse_detail = $warehouse_detail;
         }


         $total=$warehouse_detail->count();
         $warehouse= $warehouse_detail->get();










         $sumary_d= Memo::select('memos.*',DB::raw('SUM(memos.sub_total) as memoSubTotal'),'memos.customer_name as memo_customer_name'  ,'retail_resellers.company','memo_details.item_status','retail_resellers.id as company_id','retail_resellers.phone','retail_resellers.email')
                   ->join('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
                   ->join('memo_details', 'memos.id', '=', 'memo_details.memo_id')
                   ->where('item_status','=','0')
                   ->groupBy('retail_resellers.company');
         $sort_search = isset($request->Customers_s) ? $request->Customers_s : '';
         if($sort_search != null){
           $sumary_d = $sumary_d->where(function($query) use ($sort_search){
             $query->where('retail_resellers.company', 'LIKE', '%'.$sort_search.'%');
           });
         }
         $summery_details=$sumary_d->paginate(25,["*"],'Customers');

         $sumary_comp = Memo::select('memos.*',DB::raw('SUM(memos.sub_total) as memoSubTotal'),'memos.customer_name as memo_customer_name'  ,'retail_resellers.company','memo_details.item_status','retail_resellers.id as company_id','retail_resellers.phone','retail_resellers.email')
                   ->join('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
                   ->join('memo_details', 'memos.id', '=', 'memo_details.memo_id')
                   ->where('item_status','=','0')
                   ->groupBy('retail_resellers.company');
         $sort_search = isset($request->summarypane_s) ? $request->summarypane_s : '';
         if($sort_search != null){
           $sumary_comp = $sumary_comp->where(function($query) use ($sort_search){
             $query->where('retail_resellers.company', 'LIKE', '%'.$sort_search.'%');
           });
         }
         $all_summary = $sumary_comp->paginate(25,["*"],'summarypane');
         $low_stock = Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop','category','productcondition','productType')
                       ->leftJoin('warehouse','warehouse.id','=','products.warehouse_id')
                       ->leftJoin('brands','brands.id','=','products.brand_id')
                       ->leftJoin('users','users.id','=','products.supplier_id')
                       ->leftJoin('product_types','product_types.id','=','products.product_type_id')
                       ->join('product_stocks','products.id','=','product_stocks.product_id')
                       ->join('site_options','site_options.option_value','=','products.model')
                       ->orderBy('products.id', 'DESC')
                       ->select('products.*',DB::raw('SUM(product_stocks.qty) as prostock'),'product_types.listing_type','warehouse.name as warehouse_name','brands.name as bname','users.name as user_name','site_options.low_stock')
                       ->where('product_stocks.qty','>=',1)
                       ->groupBy('products.model')
                       ->where('published', '=', '1')
                       ->having('site_options.low_stock', '>=', DB::raw('SUM(product_stocks.qty)'));
         $sort_search = isset($request->low_stock_data_s) ? $request->low_stock_data_s : '';
         if($sort_search != null){
           $low_stock = $low_stock->where(function($query) use ($sort_search){
             $query->where('product_types.listing_type', 'LIKE', '%'.$sort_search.'%');
             $query->orWhere('brands.name', 'LIKE', '%'.$sort_search.'%');
           });
         }
         $detailedProduct=$low_stock->paginate(25,['*'],'low_stock_data');

         $jobdetails = JobOrder::select('job_orders.*','job_order_details.model_number','job_order_details.serial_number','job_order_details.stock_id','job_order_details.estimated_date_return as estimated_date','agents.first_name as agent_name','retail_resellers.company as c_name')
                       ->leftJoin('job_order_details', 'job_order_details.job_order_id', '=', 'job_orders.id')
                       ->leftJoin('agents', 'agents.id', '=', 'job_order_details.agent')
                       ->leftJoin('retail_resellers', 'retail_resellers.id', '=', 'job_orders.company_name')
                       ->where('job_status','2');
         $sort_search = isset($request->job_orders_s) ? $request->job_orders_s : '';
         if($sort_search != null){
           $jobdetails = $jobdetails->where(function($query) use ($sort_search){
             $query->where('job_orders.job_order_number', 'LIKE', '%'.$sort_search.'%');
           });
         }
         $jobOrderData=$jobdetails->paginate(25,['*'],'job_orders');

         $customer_d= RetailReseller::select('retail_resellers.*')->orderBy('retail_resellers.company','ASC');
         $sort_search = isset($request->suppliers_s) ? $request->suppliers_s : '';
         if($sort_search != null){
           $customer_d = $customer_d->where(function($query) use ($sort_search){
             $query->where('retail_resellers.company', 'LIKE', '%'.$sort_search.'%');
           });
         }
         $Customer_details=$customer_d->paginate(25,['*'],'suppliers');
         $total_memo= $memo_details->count();

         $cached_graph_data = Cache::remember('cached_graph_data', 86400, function() use ($root_categories){
             $num_of_sale_data = null;
             $qty_data = null;
             foreach ($root_categories as $key => $category){
                 $category_ids = \App\Utility\CategoryUtility::children_ids($category->id);
                 $category_ids[] = $category->id;
                 $products = Product::with('stocks')->whereIn('category_id', $category_ids)->get();
                 $qty = 0;
                 $sale = 0;
                 foreach ($products as $key => $product) {
                     $sale += $product->num_of_sale;
                     foreach ($product->stocks as $key => $stock) {
                         $qty += $stock->qty;
                        }
                   }
                 $qty_data .= $qty.',';
                 $num_of_sale_data .= $sale.',';
             }
             $item['num_of_sale_data'] = $num_of_sale_data;
             $item['qty_data'] = $qty_data;
             return $item;
         });
         $arrSt = array(0,1);
         $memoQuery = Memo::select('memos.id')
                   ->join('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
                   ->join('memo_details', 'memos.id', '=', 'memo_details.memo_id')
                   ->join('products', 'memo_details.product_id', '=', 'products.id')
                   ->join('product_stocks','products.id','=','product_stocks.product_id')
                   ->groupBy('memo_details.memo_id')
                   ->whereIn('memo_details.item_status', $arrSt)
                   ->orderBy('memos.id', 'DESC')
                   ->get();

                   $productQry  = Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop','category','productcondition','productType')
                                 ->leftJoin('warehouse','warehouse.id','=','products.warehouse_id')
                                 ->leftJoin('users','users.id','=','products.supplier_id')
                                 ->leftJoin('product_types','product_types.id','=','products.product_type_id')
                                 ->leftJoin('brands','brands.id','=','products.brand_id')
                                 ->leftJoin('product_stocks','products.id','=','product_stocks.product_id')
                                 ->leftJoin('site_options','site_options.option_value','=','products.model')
                                 ->leftJoin('productconditions','productconditions.id','=','products.productcondition_id')
                                 ->where('published', '=', '1')
                                 ->where('product_stocks.qty','>=',1)
                                 ->leftJoin('categories','categories.id','=','products.category_id')
                                 ->orderBy('products.id', 'DESC')
                                 ->groupBy('products.id')
                                 ->get();

         return view('backend.dashboard', compact('root_categories','all_summary','cached_graph_data','memoQuery','partnersNewData','memo_details','Customer_details','total_memo','detailedProduct','jobOrderData','summery_details','warehouse','watch_item','total','productQry'));
     }

    public function getListingTypeId($id)
    {
      echo $id;
    }
    public function memoDaAjax(Request $request)
    {

        $company_id = $request->id;
        $memoDashData = Memo::select('memos.id','memos.memo_number','memos.sub_total','memo_details.item_status')
            ->leftjoin('memo_details', 'memos.id', '=', 'memo_details.memo_id')
            ->where('customer_name',$company_id)
            ->where('memo_details.item_status',0)
            ->get();
        $ReturntableAppend = "<table class='table table-bordered table-hover table-striped print-table order-table table'>
        <thead>
          <tr class='bg-primary text-white'>
            <th scope='col'>Memo Number</th>
            <th scope='col'>Open Balance</th>
            <th scope='col'>Status</th>
          </tr>
        </thead>
        <tbody>";
        foreach ($memoDashData as $RProItem) {
          $memo_number = $RProItem->memo_number;
          $sub_total = $RProItem->sub_total;
          setlocale(LC_MONETARY,"en_US");
          $ReturntableAppend .= "
            <tr>
              <td>$memo_number</td>
              <td>".money_format("%(#1n", $sub_total)."\n"."</td>
              <td>Open Balance</td>
            </tr>";
        }
        $ReturntableAppend .= "</tbody>
      </table>";
      return response()->json(['success' => true,'memoDaHtmlData'=>$ReturntableAppend]);

    }
    public function stockChart(Request $request)
    {
        $listing = $request->id;
        $listing = ProductType::where('listing_type', $listing)->get();
        // dd($listing);
        $ProTyprID = array();
        foreach ($listing as $ProTypr) {
          $ProTyprID[] = $ProTypr->id;
        }
        $productStocks = Product::whereIn('product_type_id',$ProTyprID)
            ->join('product_stocks','product_stocks.product_id','=','products.id')
            ->select('product_stocks.qty','products.stock_id','products.id','products.model')
             ->orderBy('products.model','asc')
            ->groupBy('products.model')
            ->get();
          return response()->json(['success' => true,'stockChart'=>$productStocks]);
    }
    function ListingTypeAjax(Request $request)
    {
      $tisting_type_id = $request->id;
      if($tisting_type_id != ""){
      $detailedProduct  = Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop','category','productcondition','productType')
      ->leftJoin('warehouse','warehouse.id','=','products.warehouse_id')
      ->leftJoin('brands','brands.id','=','products.brand_id')
      ->leftJoin('users','users.id','=','products.supplier_id')
      ->leftJoin('product_types','product_types.id','=','products.product_type_id')
      ->join('product_stocks','products.id','=','product_stocks.product_id')
      ->join('site_options','site_options.option_value','=','products.model')
      ->orderBy('products.id', 'DESC')
      ->select('products.*', DB::raw('SUM(product_stocks.qty) as prostock'),'product_types.listing_type','brands.name as bname','warehouse.name as warehouse_name','users.name as user_name','site_options.low_stock')
      ->where('product_stocks.qty','>=',1)
      ->groupBy('products.model')
      ->where('published', '=', '1')
      ->where('product_types.listing_type', '=',$tisting_type_id )
     ->having('site_options.low_stock', '>=', DB::raw('SUM(product_stocks.qty)'))
      ->get();

      $ReturntableAppend = "<table class='table table-bordered table-hover table-striped print-table order-table table' id='low_stock_filter'>
        <thead>
          <tr class='bg-primary text-white'>
            <th>#</th>
            <th>Listing Type</th>

            <th>Brand</th>

            <th>Model</th>

            <th>Stock</th>

            <th>Product stock</th>

          </tr>

        </thead>

        <tbody>";

        $count = 1;

        foreach($detailedProduct as $row)

        {

          $id=$count;

          $name=$row->bname;

          $model=$row->model;

          $stock=$row->low_stock;

          $listing_type=$row->listing_type;

          $prostock=$row->prostock;

          $count++;

          // echo $listing_type;

          $ReturntableAppend .= "

        <tr>

        <td>$id</td>

        <td>$listing_type</td>

        <td>$name</td>

        <td>$model</td>

        <td>";

            if($stock >  0 && $stock >= $prostock)

            {

              $ReturntableAppend .= "<span class='badge badge-inline badge-danger'>Low</span>($stock)";

            }

            else

            {

               $ReturntableAppend .= $stock;

            }

            $ReturntableAppend .= " </td>

        <td>$prostock</td>

        </tr>";

        }

        $ReturntableAppend .= "</tbody>

        </table>

        ";

      }else{

        $detailedProduct  = Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop','category','productcondition','productType')

        ->leftJoin('warehouse','warehouse.id','=','products.warehouse_id')

        ->leftJoin('brands','brands.id','=','products.brand_id')

        ->leftJoin('users','users.id','=','products.supplier_id')

        ->leftJoin('product_types','product_types.id','=','products.product_type_id')

        ->join('product_stocks','products.id','=','product_stocks.product_id')

        ->join('site_options','site_options.option_value','=','products.model')

        ->orderBy('products.id', 'DESC')

        ->select('products.*',DB::raw('SUM(product_stocks.qty) as prostock'),'product_types.listing_type','brands.name as bname','warehouse.name as warehouse_name','users.name as user_name','site_options.low_stock')

        ->where('product_stocks.qty','>=',1)

        ->groupBy('products.model')

        ->where('published', '=', '1')

        ->having('site_options.low_stock', '>=', DB::raw('SUM(product_stocks.qty)'))

        ->get();

        $ReturntableAppend = "<table class='table table-bordered table-hover table-striped print-table order-table table' id='low_stock_filter'>

          <thead>

            <tr class='bg-primary text-white'>

              <th>#</th>

              <th>Listing Type</th>

              <th>Brand</th>

              <th>Model</th>

              <th>Stock</th>

              <th>Product Stock</th>

            </tr>

          </thead>

          <tbody>";

          $count = 1;

          foreach($detailedProduct as $row)

          {

            $id=$count;

            $name=$row->bname;

            $model=$row->model;

            $stock=$row->low_stock;

            $listing_type=$row->listing_type;

            $prostock=$row->prostock;

            $count++;

            // echo $listing_type;

            $ReturntableAppend .= "

          <tr>

          <td>$id</td>

          <td>$listing_type</td>

          <td>$name</td>

          <td>$model</td>

          <td>";

              if($stock >  0 && $stock >= $prostock)

              {

                $ReturntableAppend .= "<span class='badge badge-inline badge-danger'>Low</span>($stock)";

              }

              else

              {

                 $ReturntableAppend .= $stock;

              }

              $ReturntableAppend .= " </td>



          <td>$prostock</td>

          </tr>";

          }

          $ReturntableAppend .= "</tbody>

          </table>

          ";

      }

        return response()->json(['success' => true,'listingTypeData'=>$ReturntableAppend]);
    }
    public function warehouseData(Request $request)
    {
      $listing_type=$request->listing_type;
      if($listing_type == '')
      {
        $warehouse_detail=Product::select('products.*',DB::raw('SUM(products.unit_price) as totalPrice'),'product_types.id as pro_ty_id','product_types.listing_type','product_types.product_type_name',\DB::raw('COUNT(product_types.product_type_name) as total_count'))
        // ->groupBy('product_types.product_type_name')
        ->join('product_types','product_types.id','=','products.product_type_id')
        ->leftJoin('memo_details', 'memo_details.product_id', '=', 'products.id')
        ->leftJoin('product_stocks','products.id','=','product_stocks.product_id')
        ->join('warehouse','warehouse.id','=','products.warehouse_id')
        ->groupBy('product_types.listing_type')->get();

      }
      else
      {
      $warehouse_detail=Product::select('products.*',DB::raw('SUM(products.unit_price) as totalPrice'),'product_types.id as pro_ty_id','product_types.listing_type','product_types.product_type_name',\DB::raw('COUNT(product_types.product_type_name) as total_count'))
      ->groupBy('product_types.product_type_name')
      ->join('product_types','product_types.id','=','products.product_type_id')
      ->leftJoin('memo_details', 'memo_details.product_id', '=', 'products.id')
      ->leftJoin('product_stocks','products.id','=','product_stocks.product_id')
      ->join('warehouse','warehouse.id','=','products.warehouse_id')
      ->where('product_types.listing_type' ,$listing_type )->get();
      }
      // echo $listing_type;
      $ReturntableAppend = "<table class='table table-bordered table-hover table-striped print-table order-table table'>
      <thead>
        <tr class='bg-primary text-white'>
          <th>#</th>
          <th>Partners</th>
          <th>Items</th>
          <th>Cost</th>
        </tr>
      </thead>
      <tbody>";
      $count = 1;
      $total_cost=0;
      setlocale(LC_MONETARY,"en_US");
      foreach($warehouse_detail as $row)
      {
        $total_cost+=$row->totalPrice;
        $id=$count;
        $name=$row->product_type_name;
        $total_count=$row->total_count;
        $unit_price=money_format("%(#1n",$row->totalPrice)."\n";
        $count++;
      //  $total= $total_cost+$unit_price;
        $ReturntableAppend .= "
        <tr>
          <td>$id</td>
          <td>$name</td>
          <td>$total_count</td>
          <td>$unit_price</td>
        </tr>";
      }
      $total_amount=money_format("%(#1n",$total_cost)."\n";
      $ReturntableAppend .= "
      <tr>
        <td colspan='3'> Total Cost</td>
        <td> $total_amount</td>
      </tr>
      </tbody>
    </table> ";
      return response()->json(['success' => true,'listingTypeData'=>$ReturntableAppend,'product_type'=>$listing_type]);
    }

}
