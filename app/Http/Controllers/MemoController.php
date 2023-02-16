<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Memo;
use App\TradeExport;
use App\InvoiceExport;
use App\MemoDetail;
use App\Product;
use App\User;
use App\Activitylog;
use App\TradeNGDExport;
use App\ReturnExport;
use App\FilteredColumns;
use Auth;
use Illuminate\Support\Facades\DB;
use \InvPDF;
use App\MemoExport;
use Excel;
use Mail;
use PDF;
use App\Mail\EmailManager;
// use App\Memo::paginate(10);
use App\Productcondition;
use App\Country;
use App\ProductStock;
use App\Models\payment_deposit_memoModel;
use App\Models\Producttype;

class MemoController extends Controller
{

   // Memo Start
    public function memo(Request $request)
    {
      $pagination_qty='';
      $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
         if($request->input('pagination_qty')!=NULL){
            $product_qty =  ($request->input('pagination_qty'));
        }
      $memoFilteredCOll = array(
        "memo_number" => "Memo Number",
        "company" => "Company Name",
        "customer_name" => "Customer Name",
        "reference" => "Reference",
        "stocks" => "Stock ID",
        "model_numbers" => "Model Number",
        "sku"=>"Serial Number ",
        "payment_name" => "Payment",
        "sub_total" => "Sub Total",
        "tracking" => "Tracking",
        "item_status" => "Memo Status",
        "due_date" => "Due Date",
        "date" => "Date",
        "remain_subtotal"=>"Open Balance",
        // ""=>"serial no."
      );

      $search_type = $request->search_type;
      $search_data = $request->search;

          $arrSt = array(0,1);
        $memoQuery = Memo::select('memos.*', 'retail_resellers.company','retail_resellers.customer_name as rcustomername','memo_payments.payment_name','retail_resellers.customer_group','product_stocks.sku','memo_details.item_status')
                  ->join('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
                  ->join('memo_details', 'memos.id', '=', 'memo_details.memo_id')
                  ->join('products', 'memo_details.product_id', '=', 'products.id')
                  ->join('product_stocks','products.id','=','product_stocks.product_id')
                  ->leftJoin('memo_payments', 'memo_payments.id', '=', 'memos.payment')
                  ->selectRaw('GROUP_CONCAT(stock_id) as stocks')
                  ->selectRaw('GROUP_CONCAT(model) as model_numbers')
                  ->groupBy('memo_details.memo_id')
                  ->whereIn('memo_details.item_status', $arrSt)
                  ->orderBy('memos.id', 'DESC');
        $sort_search = isset($request->search) ? $request->search : '';
        if($sort_search != null){
              $memoQuery = $memoQuery->where(function($query) use ($sort_search){
                $query->where('memo_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('retail_resellers.company', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('retail_resellers.customer_name', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('memos.reference', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('products.stock_id', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('product_stocks.sku', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('products.model', 'LIKE', '%'.$sort_search.'%');
            });
        }

        $memoData = $memoQuery->paginate($pagination_qty);

      // $memoData =  $memoData->get();

          // dd($memoData);
      $memo = DB::table('memos')
              ->join('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
              ->join('memo_details', 'memos.id', '=', 'memo_details.memo_id')
              ->join('products', 'memo_details.product_id', '=', 'products.id')
              ->join('product_stocks','products.id','=','product_stocks.product_id')->leftJoin('memo_payments', 'memo_payments.id', '=', 'memos.payment')
              ->groupBy('memo_details.memo_id')
              ->paginate(10);

            /*
            if ($request->type != null){
              $var = explode(",", $request->type);
              $col_name = $var[0];
              $query = $var[1];
              $memoData = $memoData->orderBy($col_name, $query);
              $sort_type = $request->type;
            }
            */

            $FilteredData = FilteredColumns::get_table_model('memo');
            if(empty($FilteredData)){
              $FilteredData = $memoFilteredCOll;
            }
            $columnSelArr =  array_keys($FilteredData);



    // dd($memoData);
  //   if(isset($product_qty) && $product_qty!='All'){
  //     if($product_qty!='All'){
  //         $memoData = $memoData->paginate(($product_qty));
  // }
  // elseif(isset($product_qty) && $product_qty=='All'){
  //     $memoData = $memoData;
  // }
  // }else{
  //  $memoData = $memo->paginate(25);
  // }
        return view("backend.memo.memos.index", compact('memoData','sort_search','memoFilteredCOll','FilteredData','columnSelArr','memo','pagination_qty'));
    }

    public function closememo(Request $request)
    {

      $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
      if($pagination_qty < 1){
          $pagination_qty = 25;
      }
      $memoFilteredCOll = array(
        "memo_number" => "Memo Number",
        "company" => "Company Name",
        "customer_name" => "Customer Name",
        "reference" => "Reference",
        "stocks" => "Stock ID",
        "model_numbers" => "Model Number",
        "sku"=>"Serial Number ",
        "payment_name" => "Payment",
        "sub_total" => "Sub Total",
        "tracking" => "Tracking",
        "item_status" => "Memo Status",
        "due_date" => "Due Date",
        "date" => "Date",
        "remain_subtotal"=>"Open Balance",
      );


      $arrStatus = array(2,3,4,6);
      $closeQry =
      Memo::select('memos.*', 'retail_resellers.company','retail_resellers.customer_name as rcustomername','memo_payments.payment_name','retail_resellers.customer_group','product_stocks.sku','memo_details.item_status')
      ->join('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
      ->join('memo_details', 'memos.id', '=', 'memo_details.memo_id')
      ->join('products', 'memo_details.product_id', '=', 'products.id')
      ->join('product_stocks','products.id','=','product_stocks.product_id')
      ->leftJoin('memo_payments', 'memo_payments.id', '=', 'memos.payment')
      ->selectRaw('GROUP_CONCAT(stock_id) as stocks')
      ->selectRaw('GROUP_CONCAT(model) as model_numbers')
      ->groupBy('memo_details.memo_id')
      // Memo::select('memos.*', 'retail_resellers.company','retail_resellers.customer_name as rcustomername','memo_payments.payment_name','retail_resellers.customer_group','product_stocks.sku','memo_details.item_status')
      //             ->join('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
      //             ->join('memo_details', 'memos.id', '=', 'memo_details.memo_id')
      //             ->join('products', 'memo_details.product_id', '=', 'products.id')
      //             ->join('product_stocks','products.id','=','product_stocks.product_id')
      //             ->leftJoin('memo_payments', 'memo_payments.id', '=', 'memos.payment')
      //             ->selectRaw('GROUP_CONCAT(stock_id) as stocks')
      //             ->selectRaw('GROUP_CONCAT(model) as model_numbers')
      //             ->groupBy('memo_details.memo_id')
                  ->whereIn('memo_details.item_status', $arrStatus)
                  ->orderBy('id', 'DESC');

                $sort_search = isset($request->search) ? $request->search : '';
                if($sort_search != null){
                      $closeQry = $closeQry->where(function($query) use ($sort_search){
                        $query->where('memo_number', 'LIKE', '%'.$sort_search.'%')
                        ->orWhere('retail_resellers.company', 'LIKE', '%'.$sort_search.'%')
                        ->orWhere('retail_resellers.customer_name', 'LIKE', '%'.$sort_search.'%')
                        ->orWhere('memos.reference', 'LIKE', '%'.$sort_search.'%')
                        ->orWhere('products.stock_id', 'LIKE', '%'.$sort_search.'%')
                        ->orWhere('product_stocks.sku', 'LIKE', '%'.$sort_search.'%')
                        ->orWhere('products.model', 'LIKE', '%'.$sort_search.'%');
                    });
                }

                  // dd($closememoData);
                  $FilteredData = FilteredColumns::get_table_model('memo_close');
                  if(empty($FilteredData)){
                    $FilteredData = $memoFilteredCOll;
                  }
                  $columnSelArr =  array_keys($FilteredData);

            $closememoData = $closeQry->paginate($pagination_qty);
            // dd($closememoData);

        return view("backend.memo.memos.close", compact('closememoData','memoFilteredCOll','FilteredData','columnSelArr','pagination_qty', 'sort_search'));
    }

    public function MemoCreate()
    {
        // $saleTaxdata=DB::table('system_sales')->first()->SaleTax;
        $saleTaxdata=DB::table('system_sales')->orderBy('id', 'desc')->first()->SaleTax;
        $allCountries =Country::all();


      return view("backend.memo.memos.create",['allcountry'=>$allCountries,'saleTaxdata'=>$saleTaxdata]);
    }

public function floatvalue($val){
            $val = str_replace(",",".",$val);
            $val = preg_replace('/\.(?=.*\.)/', '', $val);
            return floatval($val);
}
    public function saveMemo(Request $Request)
    {


        $post = new Memo();
        $post->memo_number = $Request->memo_number;
        $post->customer_name = $Request->customer_name; //retail or resaler

        // if(isset($Request->sale_tax)){
        //   $post->sale_tax =  $this->floatvalue(trim($Request->sale_tax, "$ ") );
        // }else{
        //     $post->sale_tax = $Request->sale_tax;
        // }
        $post->sale_tax = 0;

        $post->shipping_charges =$this->floatvalue(trim($Request->shipping_charges, "$ ") ) ;
        $post->order_total =$this->floatvalue(trim($Request->order_total, "$ ") );
        $post->notes =isset($Request->notes) ? $Request->notes: NULL;
        $post->reference = isset($Request->reference) ? $Request->reference: NULL;
        $post->payment = isset($Request->payment) ? $Request->payment :NULL;
        $post->tracking = isset($Request->tracking) ? $Request->tracking : NULL;
        $post->carrier = isset($Request->carrier) ? $Request->carrier : NULL;
        $post->misc = $Request->misc;
        $post->sub_total =$this->floatvalue(trim($Request->sub_total, "$ ") );   //check it
        $post->memo_status = $Request->memo_status;
        $post->isactive = $Request->isactive;
        $post->date = $Request->date;
        // echo $post->date;exit;
        $post->due_date = isset($Request->due_date)? $Request->due_date:NULL;
        $post->deposit_amount = $Request->deposit_amount;
        $post->save();
        $memoID = $post->id;
        $memoStatus = $post->memo_status;
        $post->memo_number ="101".$memoID;
        $post->save();

        $memoDetailsArr = $Request->memoitems;
        $sale_tax=0;
        // dd($memoDetailsArr);

        foreach ($memoDetailsArr as $pId => $proData) {
          $proDetailPrice = $proData['price'];
          $proDetailStock = $proData['stock'];
          $proDetailQuantity = $proData['quantity'];
          $proDetailRowtotal = $proData['rowtotal'];


    //   product quantity reduce here
          $productStock = ProductStock::where('product_id','=',$pId)->first();
          // if(!empty($productStock->qty))
          // {
            if(($productStock->qty-$proDetailQuantity)>=0){
              $productStock->qty = $productStock->qty-$proDetailQuantity;
              $productStock->save();
              $productPblsh = Product::where('id','=',$pId)->firstOrFail();
              $productPblsh->published = 0;
              $productPblsh->save();
            }else{
                flash(translate('Please Enter Memo quantity less then Product Stock Quantity,  Memo not saved'))->success();
                return back();
            }
          // }



          $post = new MemoDetail();
          $post->memo_id = $memoID;
          $post->product_id = $pId;
          $post->product_price = $proDetailPrice;
          $post->product_qty = $proDetailQuantity;
          $memo = Memo::where('id','=',$memoID)->first();
          $memon=$memo->memo_number;
        //   $post->item_status = 1;

          $post->row_total =(float)($this->floatvalue(trim($proDetailRowtotal, "$ ") ));
          $post->save();

        }
        $cs_name=$Request->customer_name;
        $array = array();
        if($cs_name != ""){
          $csName =  DB::table('retail_resellers')->select('company','customer_group','customer_name')->where('id',$cs_name)->first();
          $user = Auth::user();
          $curr_uid = $user->id;
          $curr_name = $user->name;
            $proLog = new Activitylog();
            $proLog->type = 'Memo';
            $proLog->user_id = $curr_uid;
            $proLog->prodcut_id = $Request->product_id;
            if($csName->customer_group == 'reseller'){
              echo $proLog->activity = addslashes('STOCK ID <a href"#">'.$proDetailStock.'</a> was Memo To Memo <a href="#" class="memourl" data-uid="'.$memoID.'">'.$memon.'</a> By Customer <a href="#" class="csurl" data-uid="'.$cs_name.'">'.$csName->company.'</a> by '.$curr_name.' on');
            }elseif($csName->customer_group == 'retail'){
              $proLog->activity = addslashes('STOCK ID <a href=""> '.$proDetailStock.'</a> was Memo To Memo <a href="#" class="memourl" data-uid="'.$memoID.'">'.$memon.'</a> By Customer <a href="#" class="csurl" data-uid="'.$cs_name.'"> '.$csName->customer_name.'</a>  by '.$curr_name.' on');
            }
            $proLog->action = 'movedToMemo';
            $proLog->save();

            $proLogcs = new Activitylog();
            $proLogcs->type = 'MemoUser';
            $proLogcs->user_id = $curr_uid;
            $proLogcs->prodcut_id = $Request->product_id;
            $proLogcs->activity = addslashes('A Memo <a href="#" class="memourl" data-uid="'.$memoID.'">'.$memon.'</a> was created by user <a href="#" class="userurl" data-uid="'.$curr_uid.'"> '.$curr_name.'</a> on');
            $proLogcs->action = 'createdByUser';
            $proLogcs->save();

          $array['csName'] = $csName;
        }
        $array['LsProAct'] = "M101".$memoID;
        $array['userIp'] = $Request->ip();      $array['userIp'] = $Request->ip();
        $array['from'] = env('MAIL_FROM_ADDRESS');



        $url = env('APP_URL').'/public/uploads/all/vYaq660ap6T3vRppb83IikSpRb6hKf5xvKr1G6Wg.jpg';
        $image = file_get_contents($url);
        $imgbase64 = "";
        if ($image !== false){
            $imgbase64 = 'data:image/jpg;base64,'.base64_encode($image);
        }

         $memo =DB::table('memos')
             ->join('retail_resellers', 'retail_resellers.id', '=', 'memos.customer_name')
             ->join('memo_details','memo_details.memo_id','=','memos.id')
             ->select('memos.*','retail_resellers.company','retail_resellers.customer_name','retail_resellers.billing_address','retail_resellers.billing_city',
             'retail_resellers.text_billing_state','retail_resellers.billing_zipcode','retail_resellers.phone','retail_resellers.shipping_address','retail_resellers.city',
             'retail_resellers.shipping_city','retail_resellers.text_shipping_state','retail_resellers.shipping_zipcode','memo_details.id as memodetailid','memo_details.product_id',
             'retail_resellers.customer_group','retail_resellers.office_address','retail_resellers.zipcode','retail_resellers.email','retail_resellers.drop_state','retail_resellers.office_phone_number')
             ->where('memos.id',$memoID)
             ->where('memos.memo_status',$memoStatus)
             ->first();


        $memo_details_data = DB::table('memo_details')
                        ->join('products', 'products.id', '=', 'memo_details.product_id')
                        ->join('product_types','product_types.id','=','products.product_type_id')
                        ->join('product_stocks','products.id','=','product_stocks.product_id')
                        ->leftJoin('productconditions','productconditions.id','products.productcondition_id')
                        ->select('memo_details.id','memo_details.item_status','memo_details.row_total',
                        'memo_details.product_qty','products.stock_id','products.product_type_id','products.model',
                        'products.productcondition_id','products.custom_6','products.paper_cart','products.custom_4',
                        'products.custom_3','products.metal','products.custom_5','products.weight','product_types.listing_type','product_stocks.sku','productconditions.name','memo_details.product_price')
                        ->where('memo_details.memo_id',$memoID)
                        ->get();

        $sale_tax=DB::table('system_sales')->orderBy('id','DESC')->first();



        $array['memo'] = $memo;
        $array['memo_details_data'] = $memo_details_data;
        $array['imgbase64'] = $imgbase64;
        $array['sale_tax'] = $sale_tax;

        $pdf = PDF::loadView('backend.memo.invoice.viewmemoinvoice', $array);
        Mail::send('frontend.backendMailer.addmemoemail', $array, function($message) use ($array,$pdf) {
            $message->to(env("StockManager"));
            $message->subject('User Activity Notification');
            $message->attachData($pdf->output(), "memo.pdf");
        });

        /*
        Mail::send('frontend.backendMailer.addmemoemail', $array, function($message) use ($array) {
            $message->to(env("StockManager"));
            $message->subject('User Activity Notification');
        });
        */


        flash(translate('Memo has been added successfully'))->success();
        return back();
    }



    public function memoedit(Request $request, $id)
    {
        $memo = Memo::findOrFail($id);
        // dd($memo);
        $memoDetail = MemoDetail::where('memo_id','=',$id)->first();
        // dd($memoDetail);
        $memo_status_item = $memoDetail->item_status;
        // dd($memo_status_item);
        $totalPaidAmount=0;
        $memo_details_product = DB::table('memo_details')
                              ->select('memo_details.*', 'products.stock_id','productconditions.name','products.photos','products.description','products.cost_code','products.product_cost','products.custom_6','products.custom_3','products.custom_4','products.custom_5','product_types.listing_type','products.model','products.weight','products.paper_cart','products.metal','product_stocks.sku')
                              ->join('products', 'memo_details.product_id', '=', 'products.id')
                              ->join('product_types', 'products.product_type_id', '=', 'product_types.id')
                              ->leftJoin('productconditions','products.productcondition_id','=','productconditions.id')
                              ->join('product_stocks','products.id','=','product_stocks.product_id')

                              ->where('memo_details.memo_id', $memoDetail->memo_id)
                              ->get();
                              // dd($memo_details_product);


        // get deposit payment detail
        $memoNumbermodal = payment_deposit_memoModel::where('memo_deposit_num','=',$memo->memo_number)->get();
        foreach($memoNumbermodal as $paid){
            $totalPaidAmount+=$paid->payment_depositePaid;
        }

        $sub_total = '$ '.number_format($memo->sub_total, 2);
        $sale_tax=$memo->sale_tax;
        $shipping_charges = '$ '.number_format( $memo->shipping_charges, 2);

        $remaining_total = $memo->sub_total - $totalPaidAmount +$memo->shipping_charges+$sale_tax;
          $sale_tax = '$ '.number_format($sale_tax, 2);
           $remaining_total = '$ '.number_format($remaining_total, 2);

        $totalPaidAmount = '$ '.number_format($totalPaidAmount, 2);
        // for agent
        // $arrSt = array('Memo');
        // foreach ($memo_details_product as $MDkey => $MDval) {
        //   // code...
        //   $activitylogData = Activitylog::where('prodcut_id',$MDval->product_id)->whereIn('type',$arrSt)->orderBy('created_at','DESC')->get();
        //
        // }
        // dd($activitylogData);

        return view('backend.memo.memos.edit', compact('memo',/*'activitylogusr','activitylogStatus','activitylogData',*/'totalPaidAmount','memoNumbermodal','remaining_total','memo_details_product','sub_total','sale_tax','shipping_charges'));
    }





    public function memoUpdate(Request $request, $id)
    {
      $memoUpdate = Memo::findOrFail($id);
      // dd($memoUpdate);

      $memoUpdate->memo_number = $request->memo_number;
      // echo $memoUpdate->memo_number;exit;
    //   $memoUpdate->customer_name = $request->customer_name;
      $memoUpdate->sale_tax =$this->floatvalue(trim($request->sale_tax, "$ ") );
      // echo  $memoUpdate->sale_tax;exit;
      $memoUpdate->shipping_charges =$this->floatvalue(trim($request->shipping_charges, "$ "));
      $memoUpdate->order_total =$this->floatvalue(trim($request->order_total, "$ "));
      $memoUpdate->notes = $request->notes;
      $memoUpdate->reference = $request->reference;
      $memoUpdate->payment = $request->payment;
      $memoUpdate->tracking = $request->tracking;
      $memoUpdate->carrier = $request->carrier;
      $memoUpdate->misc = $request->misc;
      $memoUpdate->sub_total =$this->floatvalue(trim($request->sub_total, "$ "));
      $memoUpdate->memo_status = $request->memo_status;
      // echo  $memoUpdate->memo_status ; exit;
      $memoUpdate->isactive = $request->isactive;
      $memoUpdate->date = $request->date;
      $memoUpdate->due_date = $request->due_date;
      $memoUpdate->deposit_amount =$this->floatvalue(trim($request->deposit_amount, "$ "));
      $memoUpdate->save();
      $memoID = $memoUpdate->id;
      $memoUpdate->memo_number ="101".$memoID;
      $memoUpdate->save();

      flash(translate('Memo has been updated successfully'))->success();
      return  back();
    }
    // Memo End


    public function memoDestroy($id)
    {
        $post = Memo::where('id',$id)->delete();
        flash(translate('Memo has been deleted successfully'))->success();
        return back();
    }



    public function memoAjax(Request $request)
    {
      $proStatusData = array();
      $product_stock = ProductStock::all();
      $memoItemStatus = array('0','1','2','3','4','5','6');
      $typedata = isset($request->type) ? $request->type : '';
      $memoProductData = MemoDetail::selectRaw('GROUP_CONCAT(product_id) as p_id')
                    ->where('product_qty','>',0)
                    ->whereIn('item_status',$memoItemStatus)
                    ->get();
                    // dd($memoProductData);

                    $memoP_id = '';
                    foreach($memoProductData as $memoProData){
                        if(!empty($memoProData->p_id)){
                        $memoP_id = explode(',',$memoProData->p_id);
                        $memoP_id = array_unique($memoP_id);
                        }
                    }
                    if($memoP_id == ''){
                        $allProductData = Product::select('products.*','product_stocks.qty','product_stocks.sku')
                                          ->join('product_stocks','product_stocks.product_id','=','products.id')
                                          ->where('products.is_repair',0)
                                          ->get();

                    }else{
                      $allProductData = Product::select('products.*','product_stocks.qty','product_stocks.sku')
                                        ->join('product_stocks','product_stocks.product_id','=','products.id')
                                        ->whereNotIn('products.id',$memoP_id)
                                        ->where('products.is_repair',0)
                                        ->get();

                    }






      $htmlopt = '<option value="">Select Product</option>';

      foreach ($allProductData as $productData) {

          $reqOpt = $productData->$typedata;
          if($reqOpt=='' || $reqOpt==NULL){
              continue;
          }

          $reqDescr = '';
          $ProductTypeID =  Producttype::where('id','=',$productData->product_type_id)->first();

            $reqPrice = $productData->unit_price - $productData->discount;

            if(isset($productData->productcondition_id)){          //product condition according to new or old or resaial or trial
                 $productionCondition = Productcondition::where('id','=',$productData->productcondition_id)->first();
                 $ProductConditionValue='';
                if(isset($productionCondition)){
                 if(($productionCondition->name=='New') ||  ($productionCondition->name=='New NS') || ($productionCondition->name=='NNS')  || ($productionCondition->name=='NS')){
                     $ProductConditionValue = 'Unworn';
                 }else{
                     $ProductConditionValue = 'Preowned ';
                 }
            }}

             $reqImage = uploaded_asset($productData->thumbnail_img);
            // $listingType =$ProductTypeID->listing_type; // give here listing type such as watch ,bangles,bazel



            if($productData->model !=''){
                 $model= $productData->model; //model
            }
                 $serialNumber=$productData->sku; //serial number
                 $weight =$productData->weight; // weight

                 $bezeltype = '';
                 if( $productData->custom_4 !=''){
                 $bezeltype=$productData->custom_4;//bezel
                 }

            $screwCount='';
            if( $productData->custom_6 !=''){
                 $screwCount =$productData->custom_6;   //screw count
            }

            $paperCart='';
            if( $productData->paper_cart !=''){           //paper
                 $paperCart=$productData->paper_cart;
            }

            $dial='';
            if( $productData->custom_3 !=''){                        //dial
                 $dial =$productData->custom_3;
            }

            $metal='';
             if( $productData->metal !=''){                        //metal
                 $metal=$productData->metal;
            }

            $band='';
            if( $productData->custom_5 !=''){                        //band
                 $band=$productData->custom_5;
            }


            if($ProductTypeID->listing_type == 'Bracelet')
            {
                $reqDescr = $ProductConditionValue.' : '.$model.'-'.$serialNumber.'-'.$weight.'g ';
            }

            else if($ProductTypeID->listing_type == 'Watch')
            {
                $reqDescr = $ProductConditionValue.' : '.$model.'-'.$serialNumber.'-'.$weight.'g - '.$screwCount.'- '.$paperCart.' - '.$dial.' - '.$bezeltype.'-'.$band.'';
            }
            else if($ProductTypeID->listing_type == 'Bangle')
            {
                $reqDescr = $ProductConditionValue.' : '.$model.'-'.$serialNumber.'-'.$weight.'g - '.$paperCart.'';
            }

             else if($ProductTypeID->listing_type == 'Necklace')
            {
                $reqDescr = $ProductConditionValue.' : '.$model.'-'.$serialNumber.'-'.$weight.'g ';
            }
             else if($ProductTypeID->listing_type == 'Bezel')
            {
                $reqDescr = $ProductConditionValue.' : '.$model.'-'.$weight.'g ';
            }
             else if($ProductTypeID->listing_type == 'Dial')
            {
                $reqDescr = $ProductConditionValue.' : '.$model.'-'.$metal.')';
            }
            else if($ProductTypeID->listing_type == 'Straps')
            {
                $reqDescr = $ProductConditionValue.' : '.$model.'-'.$metal.')';
            }
            else{
              $reqDescr = $ProductConditionValue.' : '.$model.'-'.$metal.')';
            }


            $htmlopt .= '<option data-Desc="'.$reqDescr.'" data-qty="'.$productData->qty.'" data-image="'.$reqImage.'" data-price="'.$reqPrice.'" value="'.$productData->id.'">'.$reqOpt.'</option>';

      }

      $proStatusData['html'] = $htmlopt;
      $proStatusData['status'] = 'success';
      echo json_encode($proStatusData);
      exit;
    }



    public function memoeditAjax(Request $request)
    {

      $memoactiondata = isset($request->action) ? $request->action : ''; //give btn action
    //  echo $memoactiondata; exit;
      $memoproItemsdata = isset($request->items) ? $request->items : ''; //given value
      $memoproItems = explode(",", $memoproItemsdata);
      // print_r($memoproItems);
      $sale_tax=0;
      foreach ($memoproItems as $proItems) {
        $memoDetails = MemoDetail::findOrFail($proItems);
        $proId=$memoDetails->product_id;
        $productd=Product::findOrFail($proId);
        $stock_id=$productd->stock_id;
        // print_r($memoDetails);exit;
        $memo = Memo::where('id','=',$memoDetails->memo_id)->first();
        $memon=$memo->memo_number;
        $cs_name=$memo->customer_name;
        // print_r($memo);exit;
        $csName =  DB::table('retail_resellers')->where('id',$cs_name)->first();
        // print_r($csName->customer_group);exit;
        $model =  DB::table('system_sales')->select('SaleTax')->orderBy('id', 'DESC')->first();
        $memoDetails->item_status = $memoactiondata;
        // dd($memoactiondata);
        $memoDetails->status_updated_date = date('Y-m-d H:i:s');
        // $cs_name=$Request->customer_name;
        if(!empty($proId)){
        $proLog = Activitylog::where('prodcut_id',$proId)->where('type','Memo_status')->first();
        $user = Auth::user();
        $curr_uid = $user->id;
        $curr_name = $user->name;
        $proLogAct = array();
          // $proLog = new Activitylog();
          if($proLog != ""){
          $proLog->type = 'Memo_status';
          $proLog->user_id = $curr_uid;
          $proLog->prodcut_id = $proId;
          $proLog->extra_id = $cs_name;

        if($memoactiondata=='1'){
        $memoDetails->item_status =1 ;
        // $proLog->activity = addslashes('STOCK ID <a href="">'.$returnProData->stock_id.'</a> was Memo  <a href="#">'. $wfName.' TO '. $wtName.'</a> on');
        //   $proLog->action = 'movedToMemo';
        }
        else if($memoactiondata=='2'){
        $memoDetails->item_status =2 ;
            if($csName->customer_group=="retail")
            {
                $proLog->activity = addslashes('STOCK ID <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Invoice Sale <a href="#" class="memourl" data-uid="'.$memoDetails->memo_id.'">'. $memon.' </a> by Customer <a href="#" class="csurl" data-uid="'.$cs_name.'">'. $csName->customer_name.' </a>  by '.$curr_name.' on');
            }
            else
            {
              $proLog->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Invoice Sale <a href="#" class="memourl" data-uid="'.$memoDetails->memo_id.'">'. $memon.' </a> by Customer <a href="#" class="csurl" data-uid="'.$cs_name.'">'. $csName->company.' </a>  by '.$curr_name.' on');
            }
          $proLog->action = 'movedToInvoice';
          $proLogAct[] = 'M'.$memon.' And Memo Status Changed From Memo To Invoice';
        }
        else if($memoactiondata=='3'){
        $memoDetails->item_status =3 ;


        if($csName->customer_group=="retail")
        {
            $proLog->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Return From Memo <a href="#" class="memourl" data-uid="'.$memoDetails->memo_id.'">'. $memon.' </a> by Customer <a href="#" class="csurl" data-uid="'.$cs_name.'">'. $csName->customer_name.' </a>  by '.$curr_name.' on');
        }
        else
        {
          $proLog->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Return From Memo <a href="#" class="memourl" data-uid="'.$memoDetails->memo_id.'">'. $memon.' </a> by Customer <a href="#" class="csurl" data-uid="'.$cs_name.'">'. $csName->company.' </a>  by '.$curr_name.' on');
        }



        $productd->published = 1;
        $productd->save();
        $productQty=ProductStock::where('product_id',$proId)->firstOrFail();
        $productQty->qty = $productQty->qty + 1;
        $productQty->save();
        // $proLog->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Return SALE'. $memon.'  by Customer '. $curr_name.' on');
        $proLog->action = 'movedToReturn';
        $proLogAct[] = 'M'.$memon.' And Memo Status Changed From Memo To Return';
        }
        else if($memoactiondata=='4'){
        $memoDetails->item_status =4 ;

        if($csName->customer_group=="retail")
        {
            $proLog->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Trade TRD <a href="#" class="memourl" data-uid="'.$memoDetails->memo_id.'">'. $memon.' </a> by Customer <a href="#" class="csurl" data-uid="'.$cs_name.'">'. $csName->customer_name.' </a>  by '.$curr_name.' on');
        }
        else
        {
          $proLog->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Trade TRD <a href="#" class="memourl" data-uid="'.$memoDetails->memo_id.'">'. $memon.' </a> by Customer <a href="#" class="csurl" data-uid="'.$cs_name.'">'. $csName->company.' </a>  by '.$curr_name.' on');
        }


        // $proLog->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Trade SALE'. $memon.'  by Customer '. $curr_name.' on');
        $proLog->action = 'movedToTrade';
        $proLogAct[] = 'M'.$memon.' And Memo Status Changed From Memo To Trade';
        }
        else if($memoactiondata=='5'){
        $memoDetails->item_status =5 ;

        if($csName->customer_group=="retail")
        {
            $proLog->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was  Voided From Memo <a href="#" class="memourl" data-uid="'.$memoDetails->memo_id.'">'. $memon.' </a> by Customer <a href="#" class="csurl" data-uid="'.$cs_name.'">'. $csName->customer_name.' </a>  by '.$curr_name.' on');
        }
        else
        {
          $proLog->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was  Voided From Memo <a href="#" class="memourl" data-uid="'.$memoDetails->memo_id.'">'. $memon.' </a> by Customer <a href="#" class="csurl" data-uid="'.$cs_name.'">'. $csName->company.' </a>  by '.$curr_name.' on');
        }


        // $proLog->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Void SALE'. $memon.'  by Customer '. $curr_name.' on');
        $proLog->action = 'movedToVoid';
        $proLogAct[] = 'M'.$memon.' And Memo Status Changed From Memo To Void';
        }
        else if($memoactiondata=='6'){
        $memoDetails->item_status =6 ;

        if($csName->customer_group=="retail")
        {
            $proLog->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Trade NGD TNGD <a href="#" class="memourl" data-uid="'.$memoDetails->memo_id.'">'. $memon.' </a> by Customer <a href="#" class="csurl" data-uid="'.$cs_name.'">'. $csName->customer_name.' </a>  by '.$curr_name.' on');
        }
        else
        {
          $proLog->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Trade NGD TNGD <a href="#" class="memourl" data-uid="'.$memoDetails->memo_id.'">'. $memon.' </a> by Customer <a href="#" class="csurl" data-uid="'.$cs_name.'">'. $csName->company.' </a>  by '.$curr_name.' on');
        }


        // $proLog->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Trade NGD SALE'. $memon.'  by Customer '. $curr_name.' on');
        $proLog->action = 'movedToTrade NGO';
        $proLogAct[] = 'M'.$memon.' And Memo Status Changed From Memo To Trade NGD';
        }
        $proLog->save();
      }else{
        //samtest
        $proLogNew = new Activitylog();
        $proLogNew->type = 'Memo_status';
        $proLogNew->user_id = $curr_uid;
        $proLogNew->prodcut_id = $proId;
        $proLogNew->extra_id = $cs_name;

      if($memoactiondata=='1'){
      $memoDetails->item_status =1 ;
      // $proLogNew->activity = addslashes('STOCK ID <a href="">'.$returnProData->stock_id.'</a> was Memo  <a href="#">'. $wfName.' TO '. $wtName.'</a> on');
      //   $proLogNew->action = 'movedToMemo';
      }
      else if($memoactiondata=='2'){
      $memoDetails->item_status =2 ;
          if($csName->customer_group=="retail")
          {
              $proLogNew->activity = addslashes('STOCK ID <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Invoice Sale <a href="#" class="memourl" data-uid="'.$memoDetails->memo_id.'">'. $memon.' </a> by Customer <a href="#" class="csurl" data-uid="'.$cs_name.'">'. $csName->customer_name.' </a>  by '.$curr_name.' on');
          }
          else
          {
            $proLogNew->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Invoice Sale <a href="#" class="memourl" data-uid="'.$memoDetails->memo_id.'">'. $memon.' </a> by Customer <a href="#" class="csurl" data-uid="'.$cs_name.'">'. $csName->company.' </a>  by '.$curr_name.' on');
          }
        $proLogNew->action = 'movedToInvoice';
        $proLogNewAct[] = 'M'.$memon.' And Memo Status Changed From Memo To Invoice';
      }
      else if($memoactiondata=='3'){
      $memoDetails->item_status =3 ;


      if($csName->customer_group=="retail")
      {
          $proLogNew->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Return From Memo <a href="#" class="memourl" data-uid="'.$memoDetails->memo_id.'">'. $memon.' </a> by Customer <a href="#" class="csurl" data-uid="'.$cs_name.'">'. $csName->customer_name.' </a>  by '.$curr_name.' on');
      }
      else
      {
        $proLogNew->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Return From Memo <a href="#" class="memourl" data-uid="'.$memoDetails->memo_id.'">'. $memon.' </a> by Customer <a href="#" class="csurl" data-uid="'.$cs_name.'">'. $csName->company.' </a>  by '.$curr_name.' on');
      }



      $productd->published = 1;
      $productd->save();
      $productQty=ProductStock::where('product_id',$proId)->firstOrFail();
      $productQty->qty = $productQty->qty + 1;
      $productQty->save();
      // $proLogNew->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Return SALE'. $memon.'  by Customer '. $curr_name.' on');
      $proLogNew->action = 'movedToReturn';
      $proLogNewAct[] = 'M'.$memon.' And Memo Status Changed From Memo To Return';
      }
      else if($memoactiondata=='4'){
      $memoDetails->item_status =4 ;

      if($csName->customer_group=="retail")
      {
          $proLogNew->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Trade TRD <a href="#" class="memourl" data-uid="'.$memoDetails->memo_id.'">'. $memon.' </a> by Customer <a href="#" class="csurl" data-uid="'.$cs_name.'">'. $csName->customer_name.' </a>  by '.$curr_name.' on');
      }
      else
      {
        $proLogNew->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Trade TRD <a href="#" class="memourl" data-uid="'.$memoDetails->memo_id.'">'. $memon.' </a> by Customer <a href="#" class="csurl" data-uid="'.$cs_name.'">'. $csName->company.' </a>  by '.$curr_name.' on');
      }


      // $proLogNew->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Trade SALE'. $memon.'  by Customer '. $curr_name.' on');
      $proLogNew->action = 'movedToTrade';
      $proLogNewAct[] = 'M'.$memon.' And Memo Status Changed From Memo To Trade';
      }
      else if($memoactiondata=='5'){
      $memoDetails->item_status =5 ;

      if($csName->customer_group=="retail")
      {
          $proLogNew->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was  Voided From Memo <a href="#" class="memourl" data-uid="'.$memoDetails->memo_id.'">'. $memon.' </a> by Customer <a href="#" class="csurl" data-uid="'.$cs_name.'">'. $csName->customer_name.' </a>  by '.$curr_name.' on');
      }
      else
      {
        $proLogNew->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was  Voided From Memo <a href="#" class="memourl" data-uid="'.$memoDetails->memo_id.'">'. $memon.' </a> by Customer <a href="#" class="csurl" data-uid="'.$cs_name.'">'. $csName->company.' </a>  by '.$curr_name.' on');
      }


      // $proLogNew->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Void SALE'. $memon.'  by Customer '. $curr_name.' on');
      $proLogNew->action = 'movedToVoid';
      $proLogNewAct[] = 'M'.$memon.' And Memo Status Changed From Memo To Void';
      }
      else if($memoactiondata=='6'){
      $memoDetails->item_status =6 ;

      if($csName->customer_group=="retail")
      {
          $proLogNew->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Trade NGD TNGD <a href="#" class="memourl" data-uid="'.$memoDetails->memo_id.'">'. $memon.' </a> by Customer <a href="#" class="csurl" data-uid="'.$cs_name.'">'. $csName->customer_name.' </a>  by '.$curr_name.' on');
      }
      else
      {
        $proLogNew->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Trade NGD TNGD <a href="#" class="memourl" data-uid="'.$memoDetails->memo_id.'">'. $memon.' </a> by Customer <a href="#" class="csurl" data-uid="'.$cs_name.'">'. $csName->company.' </a>  by '.$curr_name.' on');
      }


      // $proLogNew->activity = addslashes('STOCK ID  <a href="#" class="prourl" data-uid="'.$proId.'">'.$stock_id.'</a> was Trade NGD SALE'. $memon.'  by Customer '. $curr_name.' on');
      $proLogNew->action = 'movedToTrade NGO';
      $proLogNewAct[] = 'M'.$memon.' And Memo Status Changed From Memo To Trade NGD';
      }
      $proLogNew->save();
      }
    }
        $memoDetails->save();


        $proLogusr = new Activitylog();
        $proLogusr->type = 'MemoUser';
        $proLogusr->user_id = $curr_uid;
        $proLogusr->prodcut_id = $proId;
        $proLogusr->activity = addslashes('A Memo <a href="#" class="memourl" data-uid="'.$memoDetails->memo_id.'">'.$memon.'</a> was closed by user <a href="#" class="userurl" data-uid="'.$curr_uid.'"> '.$curr_name.'</a> on');
        $proLogusr->action = 'closedByUser';
        $proLogusr->save();
        // $memo = Memo::findOrFail($memoDetails->memo_id);
        $array['csName'] = $csName;
        $array['LsProAct'] = $proLogAct;
        $array['userIp'] = $request->ip();
        $array['from'] = env('MAIL_FROM_ADDRESS');
        Mail::send('frontend.backendMailer.edit_memo_email', $array, function($message) use ($array) {
            $message->to(env("StockManager"));
            $message->subject('User Activity Notification');
        });
      }

      return response()->json(['message' => 'Success']);
    }

    public function invoice(Request $request)
    {
        $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
        if($pagination_qty < 1){
            $pagination_qty = 25;
        }

       $invoiceQry = Memo::select('memos.id as memoid','memo_details.id as memodetailsid','memo_details.item_status','retail_resellers.*',
                              'products.stock_id','products.model','memo_details.row_total','memo_details.status_updated_date','memos.order_total','product_stocks.sku')
                              ->join('memo_details', 'memo_details.memo_id', '=', 'memos.id')
                              ->join('retail_resellers', 'retail_resellers.id', '=', 'memos.customer_name')
                              ->join('products', 'products.id', '=', 'memo_details.product_id')
                              ->leftJoin('product_stocks','products.id','=','product_stocks.product_id')
                              ->selectRaw('GROUP_CONCAT(stock_id) as stocks')
                              ->selectRaw('GROUP_CONCAT(model) as model_numbers')
                              ->where('memo_details.item_status','=','2')
                              ->groupBy('memo_details.memo_id');

        $sort_search = isset($request->search) ? $request->search : '';
        if($sort_search != null){
            $searchInput =  str_replace("SALE101","",$sort_search);
            $invoiceQry = $invoiceQry->where(function($query) use ($searchInput){
                $query->where('retail_resellers.customer_name', 'LIKE', '%'.$searchInput.'%');
                $query->orWhere('products.stock_id', 'LIKE', '%'.$searchInput.'%');
                $query->orWhere('products.model', 'LIKE', '%'.$searchInput.'%');
                $query->orWhere('memos.id', 'LIKE', '%'.$searchInput.'%');
            });

        }

        $pagination  = $invoiceQry->paginate($pagination_qty);
        $memoInvoiceData=$invoiceQry->get();
        // dd($memoInvoiceData);

        return view("backend.memo.invoice.index", compact('memoInvoiceData','pagination_qty','pagination','sort_search'));
    }

    public function trade(Request $request)
    {
        $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
        if($pagination_qty < 1){
            $pagination_qty = 25;
        }

       $tradeQry = Memo::select('memos.order_total','memos.id as memoid','memo_details.id','memo_details.item_status','retail_resellers.customer_name','products.stock_id','products.model','memo_details.row_total','memo_details.status_updated_date','product_stocks.sku')
                              ->join('memo_details', 'memo_details.memo_id', '=', 'memos.id')
                              ->join('retail_resellers', 'retail_resellers.id', '=', 'memos.customer_name')
                              ->join('products', 'products.id', '=', 'memo_details.product_id')
                              ->leftJoin('product_stocks','products.id','=','product_stocks.product_id')
                              ->selectRaw('GROUP_CONCAT(stock_id) as stocks')
                              ->selectRaw('GROUP_CONCAT(model) as model_numbers')
                              ->where('memo_details.item_status','=','4')
                              ->groupBy('memo_details.memo_id');



        $sort_search = isset($request->search) ? $request->search : '';
        if($sort_search != null){
            $searchInput =  str_replace("TRD101","",$sort_search);
            $tradeQry = $tradeQry->where(function($query) use ($searchInput){
                $query->where('retail_resellers.customer_name', 'LIKE', '%'.$searchInput.'%');
                $query->orWhere('products.stock_id', 'LIKE', '%'.$searchInput.'%');
                $query->orWhere('products.model', 'LIKE', '%'.$searchInput.'%');
                $query->orWhere('memos.id', 'LIKE', '%'.$searchInput.'%');
            });
        }

        $pagination = $tradeQry->paginate($pagination_qty);
        $memotradeData=$tradeQry->get();
        // dd($memotradeData);

        return view("backend.memo.trade.index", compact('memotradeData', 'pagination_qty','pagination', 'sort_search'));
    }

    public function return(Request $request)
    {
        $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
        if($pagination_qty < 1){
            $pagination_qty = 25;
        }

       $returnQry =
      Memo::select('memos.id as memoid','memo_details.id as memodetailsid','memo_details.item_status','retail_resellers.*',
                              'products.stock_id','products.model','memo_details.row_total','memo_details.status_updated_date','memos.order_total','product_stocks.sku')
                              ->join('memo_details', 'memo_details.memo_id', '=', 'memos.id')
                              ->join('retail_resellers', 'retail_resellers.id', '=', 'memos.customer_name')
                              ->join('products', 'products.id', '=', 'memo_details.product_id')
                              ->leftJoin('product_stocks','products.id','=','product_stocks.product_id')
                              ->selectRaw('GROUP_CONCAT(stock_id) as stocks')
                              ->selectRaw('GROUP_CONCAT(model) as model_numbers')
                              ->where('memo_details.item_status','=','3')
                              ->groupBy('memo_details.memo_id');


        $sort_search = isset($request->search) ? $request->search : '';
        if($sort_search != null){
            $searchInput =  str_replace("SALE101","",$sort_search);
            $returnQry = $returnQry->where(function($query) use ($searchInput){
                $query->where('retail_resellers.customer_name', 'LIKE', '%'.$searchInput.'%');
                $query->orWhere('products.stock_id', 'LIKE', '%'.$searchInput.'%');
                $query->orWhere('products.model', 'LIKE', '%'.$searchInput.'%');
                $query->orWhere('memos.id', 'LIKE', '%'.$searchInput.'%');
            });

        }
        $pagination = $returnQry->paginate($pagination_qty);
        $memoreturnData=$returnQry->get();
// dd($memoreturnData);

        return view("backend.memo.return.index", compact('memoreturnData','pagination_qty','pagination', 'sort_search'));
    }

    public function trade_ngd(Request $request)
    {
        $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
        if($pagination_qty < 1){
            $pagination_qty = 25;
        }

       $tradeQry = Memo::select('memos.id as memoid','memo_details.id as memodetailsid','memo_details.item_status','retail_resellers.*',
                              'products.stock_id','products.model','memo_details.row_total','memo_details.status_updated_date','memos.order_total','product_stocks.sku')
                              ->join('memo_details', 'memo_details.memo_id', '=', 'memos.id')
                              ->join('retail_resellers', 'retail_resellers.id', '=', 'memos.customer_name')
                              ->join('products', 'products.id', '=', 'memo_details.product_id')
                              ->leftJoin('product_stocks','products.id','=','product_stocks.product_id')
                              ->selectRaw('GROUP_CONCAT(stock_id) as stocks')
                              ->selectRaw('GROUP_CONCAT(model) as model_numbers')
                              ->where('memo_details.item_status','=','6')
                              ->groupBy('memo_details.memo_id');

        $sort_search = isset($request->search) ? $request->search : '';
        if($sort_search != null){
            $searchInput =  str_replace("SALE101","",$sort_search);
            $tradeQry = $tradeQry->where(function($query) use ($searchInput){
                $query->where('retail_resellers.customer_name', 'LIKE', '%'.$searchInput.'%');
                $query->orWhere('products.stock_id', 'LIKE', '%'.$searchInput.'%');
                $query->orWhere('products.model', 'LIKE', '%'.$searchInput.'%');
                $query->orWhere('memos.id', 'LIKE', '%'.$searchInput.'%');
            });
        }

        $pagination = $tradeQry->paginate($pagination_qty);
        $memotrade_ngdData=$tradeQry->get();
        // dd($memotrade_ngdData);

        return view("backend.memo.trade-ngd.index", compact('memotrade_ngdData','pagination', 'pagination_qty','sort_search'));
    }
    public function activitiinvoice(Request $request, $id)
    {

      $memo = Memo::findOrFail($id);
      $memoInvoiceData = DB::table('memos')
                            ->join('memo_details', 'memo_details.memo_id', '=', 'memos.id')
                            ->join('retail_resellers', 'retail_resellers.id', '=', 'memos.customer_name')
                            ->join('products', 'products.id', '=', 'memo_details.product_id')
                            ->select('memos.id as memoid','memo_details.memo_id','memo_details.id','memo_details.item_status','memo_details.item_status','retail_resellers.*','products.stock_id','products.model','memo_details.row_total','memo_details.status_updated_date',
                            'memo_details.product_price','memos.order_total')
                            ->where('memos.id',$id)
                            ->first();

                            // dd($memoInvoiceData);
                            // $array = array_except($memoInvoiceData, array('keys', 'to', 'remove'));
        return view("backend.memo.invoice.activitiinvoice", compact('memo','memoInvoiceData'));
    }

        function generatePDF(Request $request, $id,$status)
        {
          // dd($status);
        $memo =DB::table('memos')
             ->join('retail_resellers', 'retail_resellers.id', '=', 'memos.customer_name')
             ->join('memo_details','memo_details.memo_id','=','memos.id')
             ->select('memos.*','retail_resellers.company','retail_resellers.customer_name','retail_resellers.billing_address','retail_resellers.billing_city',
             'retail_resellers.text_billing_state','retail_resellers.billing_zipcode','retail_resellers.phone','retail_resellers.shipping_address','retail_resellers.city',
             'retail_resellers.shipping_city','retail_resellers.text_shipping_state','retail_resellers.shipping_zipcode','memo_details.id as memodetailid','memo_details.product_id',
             'retail_resellers.customer_group','retail_resellers.office_address','retail_resellers.zipcode','retail_resellers.email','retail_resellers.drop_state','retail_resellers.office_phone_number')
             ->where('memos.id',$id)
             ->where('memos.memo_status',$status)
             ->first();


        $product= Product::where('id','=',$memo->product_id)->first();
        // dd($product);

     $ProductStockID = ProductStock::where('product_id','=',$memo->product_id)->first();
    // dd($ProductStockID);
    $productTypeId= $product->product_type_id;
    // dd($productTypeId);
     $productconditionid = $product->productcondition_id;
    // dd($productconditionid);




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
                        // dd($memo_details_data);

                    //   dd($memo_details_data);
                    $sale_tax=DB::table('system_sales')->orderBy('id','DESC')->first();

                    // dd($sale_tax->SaleTax);
          $url = 'https://gcijewel.com/public/uploads/all/vYaq660ap6T3vRppb83IikSpRb6hKf5xvKr1G6Wg.jpg';
          $image = file_get_contents($url);
          $imgbase64 = "";
          if ($image !== false){
              $imgbase64 = 'data:image/jpg;base64,'.base64_encode($image);
          }
          $pdf = \App::make('dompdf.wrapper');
          $pdf = InvPDF::loadView('backend.memo.invoice.viewmemoinvoice',compact('memo','memo_details_data','imgbase64','sale_tax'))->setOptions(['defaultFont' => 'sans-serif']);
          return $pdf->stream('memo.pdf');
        }
        public function index()
        {
                return view('backend.memo.memos.index');
        }

        public function export(Request $request){
              // $ids = 10;
              $ids = $request->checked_id;
              // echo $ids;
              // exit;
              $memID = json_decode($ids, TRUE);
              // $memID = array(10,12);
              $dt = new \DateTime();
              $curntDate = $dt->format('m-d-Y');
              $fetchLiaat = new MemoExport($memID);
            return Excel::download($fetchLiaat, 'memoExport_'.$curntDate.'.xlsx');
        }

        public function memo_deposit_payment(Request $request){
                $memotable =  Memo::where('memo_number','=',$request->memo_number)->first();
                $subtotal = $memotable->sub_total;
                $ordertotal = $memotable->order_total;
                $oldPaymentModel = payment_deposit_memoModel::where('memo_deposit_num','=',$request->memo_number)->orderBy('created_at', 'desc')->first();

                $old_remaining_amount = isset($oldPaymentModel->payment_remain) ? $oldPaymentModel->payment_remain : $subtotal ;

                if($old_remaining_amount>=(float)$request->deposit_amount){
                $paymentModel = new payment_deposit_memoModel;
                $paymentModel->memo_deposit_num = $request->memo_number;
                $paymentModel->payment_subTotal = $subtotal;
                $paymentModel->payment_depositePaidBy= $request->paid_by;
                $paymentModel->payment_remain= (float)$old_remaining_amount-(float)$request->deposit_amount;
                $paymentModel->payment_depositePaid= (float)$request->deposit_amount;
                $paymentModel->payment_depositeNotes= $request->memonotes;
                $paymentModel->save();


                $memotable->remain_subtotal =$ordertotal - (float)$request->deposit_amount;
                $memotable->save();


                return response()::json(['success' => "data submitted successfully"]);
                }else{

                    return response()::json(['success' =>  $remaining_amount]);
                }

        }
        public function memo_receive_payment_record(Request $request){
                $paymentModel = payment_deposit_memoModel::where('memo_deposit_num','=',$request->memoNumber)->get();
                $shipping_charge = Memo::where('memo_number','=',$request->memoNumber)->first();

                return response()::json(['success' => $paymentModel,'shipping_charge'=>$shipping_charge->shipping_charges,'saleTax'=>$shipping_charge->sale_tax,'order_total'=>$shipping_charge->order_total]);
        }
        public function modelPro(Request $request){
          $model = $request->model;
          $modelProduct = Product::where('model',$model)->where("published",1)->get();
          $modelHtml ='<table class="w-100">
          <thead>
              <tr class="bg-primary">
                  <th class="p-2 text-white">Products</th>
                  <th class="p-2 text-white">Price</th>
              </tr>
          </thead>
          <tbody id="cls_model_contain">';
          foreach ($modelProduct as $mpkey => $mpval) {
            $modelPID = $mpval->id;
            $product_cost=$mpval->product_cost;
            $modelStock = $mpval->stock_id;
            $modelHtml .='<tr class="micustomModel">
            		<td class="border-dark"><input type="checkbox" name="model_by_ids[]" class="cls_model_by_ids" data-stock="'.$modelStock.'" value="'.$modelPID.'">&nbsp;&nbsp;'.$modelStock.'</td>
            		<td class="border-dark"><input type="text" name="model_by_sale_ids[]" class="form-control model_by_sale_ids" sale_price="0.00" value="'.$product_cost.'"></td>
            	</tr>
            ';
          }
          $modelHtml .= '</tbody></table>';
          // dd($modelProduct);
          return response()->json(['success' => true,"modelHtml"=>$modelHtml]);
        }
        function invoice_export(Request $request)
        {
          $ids = $request->checked_id;
          $proID = json_decode($ids, TRUE);
          // dd($proID);
          $fetchLiaat = new InvoiceExport($proID);
          $dt = new \DateTime();
          $curntDate = $dt->format('m-d-Y');
          // dd($fetchLiaat);
          return Excel::download($fetchLiaat, 'Invoice_'.$curntDate.'.xlsx');
        }
        function trade_export(Request $request)
        {
          $ids = $request->checked_id;
          $proID = json_decode($ids, TRUE);
          $fetchLiaat = new TradeExport($proID);
          $dt = new \DateTime();
          $curntDate = $dt->format('m-d-Y');
          return Excel::download($fetchLiaat, 'trade_'.$curntDate.'.xlsx');
        }
        function tradeNGD_export(Request $request)
        {
          $ids = $request->checked_id;
          $proID = json_decode($ids, TRUE);
          $fetchLiaat = new TradeNGDExport($proID);
          $dt = new \DateTime();
          $curntDate = $dt->format('m-d-Y');
          return Excel::download($fetchLiaat, 'tradeNGD_'.$curntDate.'.xlsx');
        }
        function return_export(Request $request)
        {
          $ids = $request->checked_id;
          $proID = json_decode($ids, TRUE);
          $fetchLiaat = new ReturnExport($proID);
          $dt = new \DateTime();
          $curntDate = $dt->format('m-d-Y');
          return Excel::download($fetchLiaat, 'Returns_'.$curntDate.'.xlsx');
        }
        public function returnactivity(Request $Request, $id)
        {
          $memoDetail = MemoDetail::where('memo_id','=',$id)->first();
          $proId = $memoDetail->product_id;
          $activitylogData = Activitylog::where('prodcut_id',$proId)->where('type','=','Memo_status')->where('action','=','movedToReturn')->orderBy('created_at','DESC')->get();
          return view("backend.memo.invoice.allact.return",compact('activitylogData'));
        }
}
