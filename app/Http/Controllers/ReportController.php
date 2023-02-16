<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Product;
use App\CommissionHistory;
use App\Wallet;
use Illuminate\Support\Facades\DB;
use App\Seller;
use App\MailWatchExcel;
use App\Memo;
use App\User;
use App\MemoDetail;
use App\Search;
use App\CustomerReportExcel;
use Auth;
use App\SupplierExcel;
use App\SupplierDetailsExcel;
use App\ProductReportExcel;
use App\SellerSaleReportExcel;
use App\ShortStockExcel;
use Excel;
class ReportController extends Controller

{

    public function stock_report(Request $request)

    {
        $sort_by =null;
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
        return view('backend.reports.warehouse_stock_report', compact('warehouse','countedArr'));

    }

    public function warehouse_report(Request $request)

    {

        $sort_by =null;

        $products = Product::orderBy('created_at', 'desc');

        if ($request->has('category_id')){

            $sort_by = $request->category_id;

            $products = $products->where('category_id', $sort_by);

        }

        $products = $products->paginate(15);

        return view('backend.reports.stock_report', compact('products','sort_by'));

    }

    public function in_house_sale_report(Request $request)

    {

        $sort_by =null;

        $products = Product::orderBy('num_of_sale', 'desc')->where('added_by', 'admin');

        if ($request->has('category_id')){

            $sort_by = $request->category_id;

            $products = $products->where('category_id', $sort_by);

        }

        $products = $products->paginate(15);

        return view('backend.reports.in_house_sale_report', compact('products','sort_by'));

    }



    public function seller_sale_report(Request $request)

    {

        $sort_by =null;
        $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
        if($request->input('pagination_qty')!=NULL){
            $pagination_qty =  $request->input('pagination_qty');
        }
    //   if($pagination_qty < 1){
    //       $pagination_qty = 25;
    //   }
        $closeQry =
        Memo::select('memos.*', 'retail_resellers.company','retail_resellers.customer_name as rcustomername','memo_payments.payment_name','retail_resellers.customer_group','product_stocks.sku','memo_details.item_status','product_types.listing_type')
        ->join('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
        ->join('memo_details', 'memos.id', '=', 'memo_details.memo_id')
        ->join('products', 'memo_details.product_id', '=', 'products.id')
        ->join('product_stocks','products.id','=','product_stocks.product_id')
        ->leftJoin('product_types','product_types.id','=','products.product_type_id')
        ->leftJoin('memo_payments', 'memo_payments.id', '=', 'memos.payment')
        ->selectRaw('GROUP_CONCAT(stock_id) as stocks')
        ->selectRaw('GROUP_CONCAT(model) as model_numbers')
        ->groupBy('memo_details.memo_id')
        ->orderBy('id', 'DESC');
        $sort_search = isset($request->search) ? $request->search : '';
        $proSearchType =$request->listing_type;
        if($proSearchType != null)
        {
            $closeQry = $closeQry->where('product_types.listing_type', 'LIKE', '%'.$proSearchType.'%');
        }


        $startrangedate=$request->startrangedate;
        $endrangedate=$request->endrangedate;
        $startdate=  date('y-m-d', strtotime($startrangedate));
        $endate=  date('y-m-d', strtotime($endrangedate));
        // dd($startdate.' 00:00:00');
        if ($request->startrangedate || $request->endrangedate) {
            $closeQry = $closeQry->whereBetween('memos.created_at', [$startdate.' 00:00:00',$endate.' 23:59:59']);
        }





        // $startrangedate=$request->startrangedate;
        // $endrangedate=$request->endrangedate;


        // $startdate=  date('y20-m-d', strtotime($startrangedate));
        //   $endate=  date('y20-m-d', strtotime($endrangedate));
        // if ($request->startrangedate || $request->endrangedate) {
        //     $closeQry = $closeQry->whereBetween('memos.date',[$startdate, $endate]);
        // }




        $modelnumber =$request->model_number;
        if($modelnumber != null)
        {
            $closeQry = $closeQry->where('model', 'LIKE', '%'.$modelnumber.'%');
        }
        $customername =$request->customer_name;
        if($customername != null)
        {
            $closeQry = $closeQry->where('retail_resellers.id', 'LIKE', '%'.$customername.'%');
        }
        $memostatus =$request->memo_status;
        if($memostatus != null)
        {
            $closeQry = $closeQry->where('memo_details.item_status', 'LIKE', '%'.$memostatus.'%');
        }
        // $memo=1;
        if($sort_search != null){
              $closeQry = $closeQry->where(function($query) use ($sort_search){
                $query->where('memo_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('retail_resellers.company', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('retail_resellers.customer_name', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('listing_type', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('memos.reference', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('products.stock_id', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('product_stocks.sku', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('products.model', 'LIKE', '%'.$sort_search.'%');
            });
        }

        // if($sort_search=="memo")
        // {
        //     $closeQry->orWhere('memo_details.item_status', 'LIKE', '% '.$memo.' %');
        // }
        if( $request->pagination_qty == "all"){
            $closememoData = $closeQry->get();
          }else{
            $closememoData = $closeQry->paginate($pagination_qty);
          }
        // $closememoData = $closeQry->paginate($pagination_qty);
// dd($closememoData);
        return view('backend.reports.seller_sale_report', compact('closememoData','pagination_qty', 'sort_search'));

    }



    public function wish_report(Request $request)

    {

        $sort_by =null;

        $products = Product::orderBy('created_at', 'desc');

        if ($request->has('category_id')){

            $sort_by = $request->category_id;

            $products = $products->where('category_id', $sort_by);

        }

        $products = $products->paginate(10);

        return view('backend.reports.wish_report', compact('products','sort_by'));

    }



    public function user_search_report(Request $request){

        $searches = Search::orderBy('count', 'desc')->paginate(10);

        return view('backend.reports.user_search_report', compact('searches'));

    }



    public function commission_history(Request $request) {

        $seller_id = null;

        $date_range = null;



        if(Auth::user()->user_type == 'seller') {

            $seller_id = Auth::user()->id;

        } if($request->seller_id) {

            $seller_id = $request->seller_id;

        }



        $commission_history = CommissionHistory::orderBy('created_at', 'desc');



        if ($request->date_range) {

            $date_range = $request->date_range;

            $date_range1 = explode(" / ", $request->date_range);

            $commission_history = $commission_history->where('created_at', '>=', $date_range1[0]);

            $commission_history = $commission_history->where('created_at', '<=', $date_range1[1]);

        }

        if ($seller_id){



            $commission_history = $commission_history->where('seller_id', '=', $seller_id);

        }



        $commission_history = $commission_history->paginate(10);

        if(Auth::user()->user_type == 'seller') {

            return view('frontend.user.seller.reports.commission_history_report', compact('commission_history', 'seller_id', 'date_range'));

        }

        return view('backend.reports.commission_history_report', compact('commission_history', 'seller_id', 'date_range'));

    }



    public function wallet_transaction_history(Request $request) {

        $user_id = null;

        $date_range = null;



        if($request->user_id) {

            $user_id = $request->user_id;

        }



        $users_with_wallet = User::whereIn('id', function($query) {

            $query->select('user_id')->from(with(new Wallet)->getTable());

        })->get();



        $wallet_history = Wallet::orderBy('created_at', 'desc');



        if ($request->date_range) {

            $date_range = $request->date_range;

            $date_range1 = explode(" / ", $request->date_range);

            $wallet_history = $wallet_history->where('created_at', '>=', $date_range1[0]);

            $wallet_history = $wallet_history->where('created_at', '<=', $date_range1[1]);

        }

        if ($user_id){

            $wallet_history = $wallet_history->where('user_id', '=', $user_id);

        }



        $wallets = $wallet_history->paginate(10);



        return view('backend.reports.wallet_history_report', compact('wallets', 'users_with_wallet', 'user_id', 'date_range'));

    }




    public function product_repot(Request $request)

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
        ->select('products.*','warehouse.name as warehouseName','categories.name as categories_name','users.name as supplienName','site_options.low_stock','memo_details.product_id','memo_details.item_status','product_types.listing_type','brands.name as brandName','product_stocks.sku','product_stocks.qty','productconditions.name as productconditions_name','memos.customer_name as customer_name_id','retail_resellers.customer_group','retail_resellers.company','retail_resellers.customer_name','product_types.product_type_name')
          ->leftJoin('memo_details','memo_details.product_id','=','products.id')
          ->leftJoin('memos', 'memos.id', '=', 'memo_details.memo_id')
          ->leftJoin('retail_resellers','retail_resellers.id' , '=' , 'memos.customer_name');
          $warehouseSrch =$request->warehouse_id;
          $partner=$request->partner;
          $listing_type=$request->producttypee;
          if ($request->search != null){
            $PurchasesProduct->orWhere('listing_type', 'like', '%'.$request->search.'%');
            $PurchasesProduct->orWhere('products.stock_id', 'like', '%'.$request->search.'%');
            $PurchasesProduct->orWhere('users.name', 'like', '%'.$request->search.'%');
            $PurchasesProduct->orWhere('brands.name', 'like', '%'.$request->search.'%');
            $PurchasesProduct->orWhere('retail_resellers.customer_name', 'like', '%'.$request->search.'%');
            $PurchasesProduct->orWhere('retail_resellers.company', 'like', '%'.$request->search.'%');
            $PurchasesProduct->orWhere('categories.name', 'like', '%'.$request->search.'%');
            $PurchasesProduct->orWhere('product_types.listing_type', 'like', '%'.$request->search.'%');
            $PurchasesProduct->orWhere('product_types.product_type_name', 'like', '%'.$request->search.'%');
            $PurchasesProduct->orWhere('products.model', 'like', '%'.$request->search.'%');
            $PurchasesProduct->orWhere('products.partner', 'like', '%'.$request->search.'%');
            $PurchasesProduct->orWhere('warehouse.name', 'like', '%'.$request->search.'%');
            $sort_search = $request->search;
        }
    //     $startrangedate=$request->startrangedate;
    //     $endrangedate=$request->endrangedate;
    //     $startdate=  date('y-m-d', strtotime($startrangedate));
    //     $endate=  date('y-m-d', strtotime($endrangedate));
    //     // dd($startdate.' 00:00:00');
    //   if ($request->startrangedate || $request->endrangedate) {
    //       $PurchasesProduct = $PurchasesProduct->whereBetween('products.created_at', [$startdate.' 00:00:00',$endate.' 23:59:59']);
    //   }

          if($partner>0)
          {
             $PurchasesData=$PurchasesProduct->where('products.id',$partner);
          }
          $category=$request->category;
          if($category>0)
          {
             $category=$PurchasesProduct->where('categories.id',$category);
          }
          $listing_type=$request->listing_type;
          if($listing_type != "")
          {
             $PurchasesData=$PurchasesProduct->where('listing_type',$listing_type);
          }
          $model_number=$request->model_number;
          if($model_number != "")
          {
             $PurchasesData=$PurchasesProduct->where('model',$model_number);
          }
          $brand=$request->brand;
          if($brand != "")
          {
             $PurchasesData=$PurchasesProduct->where('brands.id',$brand);
          }
          $partner=$request->partner;
          if($partner != "")
          {
             $PurchasesData=$PurchasesProduct->where('products.partner',$partner);
          }
          $warehouseSrch=$request->warehouse_id;
          if($warehouseSrch > 0)
          {
           $PurchasesData = $PurchasesProduct->where('warehouse.id', $warehouseSrch);
          }
          $memostatus =$request->memo_status;
          if($memostatus != null)
          {
              $PurchasesData = $PurchasesProduct->where('memo_details.item_status', 'LIKE', '%'.$memostatus.'%');
          }

        if( $request->pagination_qty == "all"){
            $PurchasesData = $PurchasesProduct->get();
          }else{
            $PurchasesData = $PurchasesProduct->paginate($pagination_qty);
          }
//   dd($PurchasesData);
        return view('backend.reports.product_report', compact('PurchasesData','pagination_qty','sort_search'));

    }
    public function profit_loss(Request $request)
    {
        $arrSt = array(0,1);
        $selprice = Memo::select(DB::raw('SUM(memos.sub_total) as memoSubTotal'),'products.unit_price')
                    ->whereIn('memo_details.item_status', $arrSt)
                    ->join('memo_details', 'memos.id', '=', 'memo_details.memo_id')
                    ->join('products','products.id','memo_details.product_id');
        $startrangedate=$request->startrangedate;
        $endrangedate=$request->endrangedate;
        $startdate=  date('y-m-d', strtotime($startrangedate));
        $endate=  date('y-m-d', strtotime($endrangedate));
        // dd($startdate.' 00:00:00');
        if ($request->startrangedate || $request->endrangedate) {
            $PurchasesProduct = $selprice->whereBetween('memos.created_at', [$startdate.' 00:00:00',$endate.' 23:59:59']);
        }

                   $totalselprice= $selprice->first();
                //   ->sum('memos.sub_total');
                  $salescount=$totalselprice->count();
                //   dd($salescount);
        $invoiceprice = Memo::select(DB::raw('SUM(memos.sub_total) as memoSubTotal'),'products.unit_price')
                    ->where('memo_details.item_status', 2)
                    ->join('memo_details', 'memos.id', '=', 'memo_details.memo_id')
                    ->join('products','products.id','memo_details.product_id');
                    $startrangedate=$request->startrangedate;
                    $endrangedate=$request->endrangedate;
                    $startdate=  date('y-m-d', strtotime($startrangedate));
                    $endate=  date('y-m-d', strtotime($endrangedate));
                    // dd($startdate.' 00:00:00');
                    if ($request->startrangedate || $request->endrangedate) {
                        $PurchasesProduct = $invoiceprice->whereBetween('memos.created_at', [$startdate.' 00:00:00',$endate.' 23:59:59']);
                    }

                    $TotalInvoiceprice= $invoiceprice->first();
                    $invoicecount=$TotalInvoiceprice->count();
                    // ->sum('memos.sub_total');
        $tradePrice = Memo::select(DB::raw('SUM(memos.sub_total) as memoSubTotal'),'products.unit_price')
                    ->where('memo_details.item_status', 4)
                    ->join('memo_details', 'memos.id', '=', 'memo_details.memo_id')
                    ->join('products','products.id','memo_details.product_id');
                    $startrangedate=$request->startrangedate;
                    $endrangedate=$request->endrangedate;
                    $startdate=  date('y-m-d', strtotime($startrangedate));
                    $endate=  date('y-m-d', strtotime($endrangedate));
                    // dd($startdate.' 00:00:00');
                    if ($request->startrangedate || $request->endrangedate) {
                        $PurchasesProduct = $tradePrice->whereBetween('memos.created_at', [$startdate.' 00:00:00',$endate.' 23:59:59']);
                    }

                    $totalTradePrice= $tradePrice->first();
                    // ->sum('memos.sub_total');
                    $tradecount=$totalTradePrice->count();
        $tradengdprice = Memo::select(DB::raw('SUM(memos.sub_total) as memoSubTotal'),'products.unit_price')
                    ->where('memo_details.item_status', 6)
                    ->join('memo_details', 'memos.id', '=', 'memo_details.memo_id')
                    ->join('products','products.id','memo_details.product_id');
                    $startrangedate=$request->startrangedate;
                    $endrangedate=$request->endrangedate;
                    $startdate=  date('y-m-d', strtotime($startrangedate));
                    $endate=  date('y-m-d', strtotime($endrangedate));
                    // dd($startdate.' 00:00:00');
                    if ($request->startrangedate || $request->endrangedate) {
                        $PurchasesProduct = $tradengdprice->whereBetween('memos.created_at', [$startdate.' 00:00:00',$endate.' 23:59:59']);
                    }

                    $totaltradengdprice= $tradengdprice->first();
                    // ->sum('memos.sub_total');
                    $tradengdcount=$totaltradengdprice->count();
        return view('backend.reports.profit_loss_report', compact('totalselprice','TotalInvoiceprice','totalTradePrice','totaltradengdprice','salescount','invoicecount','tradecount','tradengdcount'));
    }
    public function customer(Request $request)
    {

        $sort_search =null;
        $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
        if($request->input('pagination_qty')!=NULL){
            $pagination_qty =  $request->input('pagination_qty');
        }
        $customer= Memo::select('memos.*',DB::raw('SUM(memos.sub_total) as memoSubTotal')  ,'retail_resellers.company','retail_resellers.customer_name as cu_name','memo_details.item_status','retail_resellers.id as company_id','retail_resellers.phone','retail_resellers.email')
        ->join('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
        ->join('memo_details', 'memos.id', '=', 'memo_details.memo_id')
        // ->leftJoin('products','products.id','memo_details.product_id')
        // ->leftJoin('product_types','product_types.id','=','products.product_type_id')
        ->groupBy('retail_resellers.company');

        $customer_name=$request->customer_name;
        if($customer_name > 0)
        {
         $customer = $customer->where('retail_resellers.id', $customer_name);
        }
        $summary_pagi='';
        if ($request->search != null){
            $customer->orWhere('retail_resellers.company', 'like', '%'.$request->search.'%');
            $customer->orWhere('retail_resellers.customer_name', 'like', '%'.$request->search.'%');
            $customer->orWhere('retail_resellers.email', 'like', '%'.$request->search.'%');
            $customer->orWhere('retail_resellers.phone', 'like', '%'.$request->search.'%');
            $sort_search = $request->search;
        }
        if( $request->pagination_qty == "all"){
            $customer_report = $customer->get();
          }else{
            $customer_report = $customer->paginate($pagination_qty);
          }
        return view('backend.reports.customer_report_report', compact('pagination_qty','sort_search','customer_report'));
    }
    public function customer_report(Request $request, $id)
    {
        $sort_search =null;
        $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
        if($request->input('pagination_qty')!=NULL){
            $pagination_qty =  $request->input('pagination_qty');
        }
        $memo = Memo::select('memos.id','memos.memo_number','memos.sub_total','memos.due_date','memos.date','memos.tracking','memo_details.item_status','retail_resellers.company','retail_resellers.customer_name','products.stock_id','product_stocks.sku','products.model','job_orders.job_order_number','memo_payments.payment_name','memos.reference')
        ->leftjoin('memo_details', 'memos.id', '=', 'memo_details.memo_id')
        ->leftJoin('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')

        ->leftJoin('memo_payments', 'memo_payments.id', '=', 'memos.payment')
        ->leftJoin('products','products.id','memo_details.product_id')
        ->leftJoin('job_orders','job_orders.company_name','retail_resellers.id')
        ->leftJoin('product_stocks','products.id','=','product_stocks.product_id')

        ->where('memos.customer_name',$id);
        if ($request->search != null){
            $memo->orWhere('retail_resellers.company', 'like', '%'.$request->search.'%');
            $memo->orWhere('retail_resellers.customer_name', 'like', '%'.$request->search.'%');
            $memo->orWhere('memo_number', 'like', '%'.$request->search.'%');
            $memo->orWhere('stock_id', 'like', '%'.$request->search.'%');
            $memo->orWhere('model', 'like', '%'.$request->search.'%');
            $memo->orWhere('sku', 'like', '%'.$request->search.'%');
            $sort_search = $request->search;
        }
        if( $request->pagination_qty == "all"){
            $memoDashData = $memo->get();
          }else{
            $memoDashData = $memo->paginate($pagination_qty);
          }
        $calculat_amount= Memo::select('memos.*',DB::raw('SUM(memos.sub_total) as memoSubTotal'),'memos.customer_name as memo_customer_name'  ,'retail_resellers.company','retail_resellers.customer_name','memo_details.item_status','retail_resellers.id as company_id','retail_resellers.phone','retail_resellers.email')
        ->join('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
        ->join('memo_details', 'memos.id', '=', 'memo_details.memo_id')
        ->groupBy('memo_details.item_status')->where('memos.customer_name',$id)->get();

        // dd($calculat_amount);


        return view('backend.reports.customer_reports_details', compact('pagination_qty','sort_search','memoDashData','calculat_amount'));

    }
    public function short_stock(Request $request)
    {
        $sort_search =null;
        $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
        if($request->input('pagination_qty')!=NULL){
            $pagination_qty =  $request->input('pagination_qty');
        }
        $arrSt = array(0,1);
        $arrStatus = array(0,1,2,4,6);
        $shortstock = MemoDetail::select('products.model','memo_details.product_qty',DB::raw('sum(product_stocks.qty) as stockqtysum'),'memo_details.item_status',DB::raw('sum(memo_details.product_qty) as  memoqtysum'),'products.created_at')
                  ->leftJoin('products','products.id','=','memo_details.product_id')
                  ->leftJoin('product_stocks','products.id','=','product_stocks.product_id')
                  ->groupBy('products.model')
                  ->whereIn('memo_details.item_status',$arrStatus);
                        $search= $request->search; 
                  if ($search != null){
                    $shortstock->where('products.model', 'like', '%'.$search.'%');
                    $sort_search = $request->search;
                }
            $startrangedate=$request->startrangedate;
            $endrangedate=$request->endrangedate;
            $startdate=  date('y-m-d', strtotime($startrangedate));
            $endate=  date('y-m-d', strtotime($endrangedate));
        //     echo $startdate . " --" . $endate ; exit
        if ($request->startrangedate != NULL || $request->endrangedate != NULL) {
            $shortstock = $shortstock->whereBetween('memo_details.created_at', [$startdate.' 00:00:00',$endate.' 23:59:59']);
        }
        if( $request->pagination_qty == "all"){
            $short_stock_data = $shortstock->get();
          }else{
            $short_stock_data = $shortstock->paginate($pagination_qty);
          }
        //   dd($short_stock_data);
        return view('backend.reports.short_stock', compact('pagination_qty','sort_search','short_stock_data'));

    }
    public function suppliers_report(Request $request)
    {
        $sort_search =null;
        $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
        if($request->input('pagination_qty')!=NULL){
            $pagination_qty =  $request->input('pagination_qty');
        }
        $supplierQry= Seller::select('users.name','users.email','sellers.phone','sellers.company',DB::raw('sum(products.unit_price) as  unit_price'),DB::raw('sum(product_stocks.qty) as  qty'),'products.supplier_id','sellers.id')
                    ->leftJoin('users','users.id','=','sellers.user_id')
                    ->leftJoin('products','products.supplier_id','users.id')
                    ->leftJoin('product_stocks','product_stocks.product_id','=','products.id')
                    ->groupBy('sellers.company')
                    ->orderBy('sellers.id','DESC');
                    if ($request->search != null){
                        $supplierQry->orWhere('sellers.company', 'like', '%'.$request->search.'%');
                        $supplierQry->orWhere('users.name', 'like', '%'.$request->search.'%');
                        $supplierQry->orWhere('users.email', 'like', '%'.$request->search.'%');
                        $sort_search = $request->search;
                    }
                    $supplier=$request->supplier;
                    if($supplier != "")
                    {
                       $supplierQry=$supplierQry->where('sellers.company',$supplier);
                    }
                    if( $request->pagination_qty == "all"){
                        $supplier = $supplierQry->get();
                      }else{
                        $supplier = $supplierQry->paginate($pagination_qty);
                      }
                    // dd($supplier);
        return view('backend.reports.suppliers_report', compact('pagination_qty','sort_search','supplier'));
    }
    public function supplier_details_report(Request $request, $id)
    {
        $sort_search =null;
        $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
        if($request->input('pagination_qty')!=NULL){
            $pagination_qty =  $request->input('pagination_qty');
        }
        $supplier_detailsQry= Seller::select('sellers.*','products.stock_id','products.unit_price','products.published','memo_details.item_status','products.model','warehouse.name as warehouse_name','users.name as supplier_name','product_stocks.sku','product_stocks.qty','products.vendor_doc_number')
        ->leftJoin('users','users.id','=','sellers.user_id')
        ->leftJoin('products','products.supplier_id','users.id')
        ->leftJoin('product_stocks','product_stocks.product_id','=','products.id')
        ->leftJoin('memo_details','memo_details.product_id','=','products.id')
        ->leftJoin('warehouse','warehouse.id','=','products.warehouse_id')
        ->orderBy('sellers.id','DESC')
        ->where('sellers.id',$id);
        if ($request->search != null){
            $supplier_detailsQry->orWhere('products.stock_id', 'like', '%'.$request->search.'%');
            $supplier_detailsQry->orWhere('products.model', 'like', '%'.$request->search.'%');
            $supplier_detailsQry->orWhere('warehouse.name', 'like', '%'.$request->search.'%');
            $supplier_detailsQry->orWhere('users.name', 'like', '%'.$request->search.'%');
            $sort_search = $request->search;
        }
      $totalPurchases= $supplier_detailsQry->sum('products.unit_price');
      $count=$supplier_detailsQry->count();
        if( $request->pagination_qty == "all"){
            $supplier_details = $supplier_detailsQry->get();
          }else{
            $supplier_details = $supplier_detailsQry->paginate($pagination_qty);
          }
        // dd($supplier_details);
        return view('backend.reports.supplier_details_report', compact('pagination_qty','sort_search','supplier_details','totalPurchases','count'));
    }
    public function best_sellers_report(Request $request)
    {
        $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
        if($request->input('pagination_qty')!=NULL){
            $pagination_qty =  $request->input('pagination_qty');
        }
        $best_seller=Memo::select('memo_details.item_status','products.model',DB::raw('SUM(memos.sub_total) as memoSubTotal'),DB::raw('SUM(memo_details.product_qty) as totalQty'),'product_types.listing_type')
        ->join('memo_details', 'memo_details.memo_id', '=', 'memos.id')
        ->join('products', 'products.id', '=', 'memo_details.product_id')
        ->leftJoin('product_stocks','products.id','=','product_stocks.product_id')
        ->leftJoin('product_types','product_types.id','=','products.product_type_id')
        ->where('memo_details.item_status','=','2')
        ->groupBy('products.model');
        $sort_search = isset($request->search) ? $request->search : '';
        if($sort_search != null){
              $best_seller = $best_seller->where(function($query) use ($sort_search){
                $query->where('products.model', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('memo_details.product_qty', 'LIKE', '%'.$sort_search.'%');
            });
        }
        $startrangedate=$request->startrangedate;
        $endrangedate=$request->endrangedate;
        $startdate=  date('y-m-d', strtotime($startrangedate));
        $endate=  date('y-m-d', strtotime($endrangedate));
        // dd($startdate.' 00:00:00');
      if ($request->startrangedate || $request->endrangedate) {
          $best_seller = $best_seller->whereBetween('memos.created_at', [$startdate.' 00:00:00',$endate.' 23:59:59']);
      }






        $proSearchType =$request->listing_type;
        if($proSearchType != null)
        {
            $best_seller = $best_seller->where('product_types.listing_type', 'LIKE', '%'.$proSearchType.'%');
        }
        if( $request->pagination_qty == "all"){
            $best_seller_report = $best_seller->get();
          }else{
            $best_seller_report = $best_seller->paginate($pagination_qty);
          }
        return view('backend.reports.best_sellers_report', compact('pagination_qty','sort_search','best_seller_report'));
    }
    public function seller_sale_report_excel(Request $request)
    {
        $fetchLiaat = new SellerSaleReportExcel();
        return Excel::download($fetchLiaat, 'seller_sale_report_excel.xlsx');
    }
    public function product_report_excel(Request $request)
    {
        $fetchLiaat = new ProductReportExcel();
        return Excel::download($fetchLiaat, 'product_report_excel.xlsx');
    }
    public function customer_report_excel(Request $request)
    {
        $fetchLiaat = new CustomerReportExcel();
        return Excel::download($fetchLiaat, 'customer_report_excel.xlsx');
    }
    public function short_stock_excel(Request $request)
    {
        $fetchLiaat = new ShortStockExcel();
        return Excel::download($fetchLiaat, 'short_stock_excel.xlsx');
    }
    public function supplier_excel(Request $request)
    {
        $fetchLiaat = new SupplierExcel();
        return Excel::download($fetchLiaat, 'supplier_excel.xlsx');
    }
    public function supplier_details_excel(Request $request)
    {
        $fetchLiaat = new SupplierDetailsExcel();
        return Excel::download($fetchLiaat, 'supplier_details_excel.xlsx');
    }
    public function warehouseDataAjax(Request $request)
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
      $stockvalue =$request->warehouse_values;
    //   echo $stockvalue; exit;
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
    // print_r($countedArr); exit;
    //   echo $listing_type;
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
      $countTotal = 0;
      $countAmount = 0;
      setlocale(LC_MONETARY,"en_US");
      foreach($warehouse_detail as $row)
      {

        $filterTotal =  isset($countedArr[$row->product_type_name]) ? $countedArr[$row->product_type_name] : "";
        if($filterTotal > 0){
          $row->totalPrice = $row->totalPrice - $filterTotal;
          $row->totalPrice = $row->totalPrice + ($filterTotal/2);
        }
        // $countAmount+=$row->totalPrice;
        // $countTotal+=$row->total_count


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
