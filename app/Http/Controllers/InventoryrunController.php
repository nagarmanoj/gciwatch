<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\InventoryRun;
use App\Product;
use App\MemoDetail;
use App\InvetoryRunExport;
use App\Models\Warehouse;
use App\JobOrderDetail;
use Excel;
use App\ProductType;
use Auth;
use App\User;
use App\Activitylog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PDF;
use Mail;
use App\Mail\EmailManager;
class InventoryrunController extends Controller
{
   // Return Start
    public function inventoryrun(Request $request)
    {
      $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
      if($request->input('pagination_qty')!=NULL){
          $pagination_qty =  $request->input('pagination_qty');
      }
      $sort_search = null;
       $PurchasesProduct = InventoryRun::select('inventory_runs.id','inventory_runs.listing_type','inventory_runs.missing','inventory_runs.duplicate','inventory_runs.extra','inventory_runs.extrakeyup','inventory_runs.created_at','product_types.product_type_name','users.name')
       ->leftJoin('users','users.id','=','inventory_runs.user')
       ->leftJoin('product_types','product_types.id','=','inventory_runs.listing_type')
       ->orderBy('inventory_runs.id', 'desc');
      //  ->get();
       $product_qty=$request->purchases_pagi;
       if ($request->search != null){
                       $PurchasesProduct->orWhere('inventory_runs.listing_type', 'like', '%'.$request->search.'%');
                       $PurchasesProduct->orWhere('users.name', 'like', '%'.$request->search.'%');
                       $PurchasesProduct->orWhere('product_types.product_type_name', 'like', '%'.$request->search.'%');
                       $PurchasesProduct->orWhere('inventory_runs.extra', 'like', '%'.$request->search.'%');

           $sort_search = $request->search;
       }
    //    if(isset($product_qty)){
    //      if($product_qty!='All'){
    //          $purchases = $PurchasesProduct->paginate(($product_qty));
    //      }
    //      else if(isset($product_qty) && $product_qty=='All'){
    //          $purchases = $detailedProduct;
    //      }
    //  }else{

    //   $purchases = $PurchasesProduct->paginate(25);

    //  }
    if( $request->pagination_qty == "all"){
      $purchases =  $PurchasesProduct->get();
    }else{
      $purchases =  $PurchasesProduct->paginate($pagination_qty);
    }
     $InventoryRun=$PurchasesProduct->get();

        return view("backend.product.inventory_run.index", compact('InventoryRun','sort_search','pagination_qty','purchases'));

    }

    public function InventoryrunCreate()

    {

      return view("backend.product.inventory_run.create");

    }



    public function saveInventoryrun(Request $Request)

    {

        $missing =  $Request->missing;

        $missing_id =  $Request->missing_id;

        // print_r($missing_id);

        $extrakeyup =  $Request->extrakeyup;

        $warehouse_id =  $Request->warehouse_id;

        $missingstr="";

        if(!empty($missing)){

         $missingstr = implode (",", $missing);

        }

        $extrakeyupstr = "";

        if(!empty($extrakeyup)){

         $extrakeyupstr = implode (",", $extrakeyup);

        }

         $id = auth()->user()->id;

         $Request->listing_type;

        $proTypeLS = ProductType::where('listing_type',$Request->listing_type)->get();

        $MemoDetailLS="";
        $JODetailsLS="";

        if(!empty($missing_id)){

        $MemoDetailLS = MemoDetail::select('products.stock_id')
                        ->leftJoin('products','products.id','=','memo_details.product_id')
                        ->whereIn('product_id',$missing_id)
                        ->where('item_status',0)
                        ->groupBy('product_id')
                        ->get();

          $JODetailsLS = JobOrderDetail::select('products.stock_id')
                          ->leftJoin('products','products.id','=','job_order_details.jo_product_id')
                          ->whereIn('jo_product_id',$missing_id)
                          ->where('bag_status',2)
                          ->groupBy('jo_product_id')
                          ->get();

          }

        $memo_stock_id[] = "";

        $jo_stock_id[] = "";

        $MemostocksStr = "";

        if($MemoDetailLS != ''){

        foreach ($MemoDetailLS as $pKey => $memopro) {

           $memo_stock_id[] = $memopro->stock_id;

          }

        }

        if($JODetailsLS != ''){

          foreach ($JODetailsLS as $pJOKey => $jopro) {

           $memo_stock_id[] = $jopro->stock_id;

          }
        }

        $memo_stock_id = array_unique($memo_stock_id);

         $memoStock = implode (",", $memo_stock_id);

         $MemostocksStr = preg_replace('/,/', '',  $memoStock, 1);


        // dd($MemoDetailLS);

        $ProTypeLsID = array();

        foreach ($proTypeLS as $ProTypr) {

          $ProTypeLsID[] = $ProTypr->id;

        }

        $post = new InventoryRun();

         $post->user = $id;

         $post->listing_type = $Request->listing_type;

         $post->extra = $MemostocksStr;

         $post->extrakeyup = $extrakeyupstr;
         $post->warehouse_id=$Request->warehouse_id;

        $Product = Product::select("products.stock_id")

                  ->whereIn('product_type_id', $ProTypeLsID)

                  ->where('warehouse_id', $warehouse_id);

        if(!empty($missing)){

          $Product->whereNotIn('stock_id', $missing);

        }

        $Product= $Product->get();

        $stock_id[] = "";

        if($Product != ''){

        foreach ($Product as $pKey => $pro) {

         $stock_id[] = $pro->stock_id;




         // $user = Auth::user();
         // $curr_uid = $user->id;
         // $curr_name = $user->name;
         // $sellerName="";
         // if(!empty($Product->supplier_id))
         // {
         //   $sellerData = User::where('id',$Product->supplier_id)->first();
         //   $sellerName = $sellerData->name;
         // }
         // $proLog = new Activitylog();
         // $proLog->type = 'Inventoryrun';
         // $proLog->user_id = $curr_uid;
         // $proLog->prodcut_id = $Request->product_id;
         // $proLog->activity = addslashes('Added to the Inventory as Stock Id '.$pro->stock_id.' Purchased From '.$sellerName.' By '.$curr_name.' on');
         // $proLog->action = 'movedToInventoryrun';
         // $proLog->save();

        }

         $stocksdata = implode (", ", $stock_id);

         $stocksStr = preg_replace('/,/', '',  $stocksdata, 1);

        }

        $post->missing = $stocksStr;

        if(!empty($missing)){

        $unique = array_unique($missing);

        $duplicates = array_diff_assoc($missing, $unique);

        $duplicatestr = implode (",", $duplicates);

         $post->duplicate = $duplicatestr;

        }



        $post->save();
        // $user = Auth::user();
        // $curr_uid = $user->id;
        // $curr_name = $user->name;
        //   $proLog = new Activitylog();
        //   $proLog->type = 'Inventoryrun';
        //   $proLog->user_id = $curr_uid;
        //   $proLog->prodcut_id = $Request->product_id;
        //   $proLog->activity = addslashes('STOCK ID '.$proDetailStock.' was Memo To Memo '.$Request->memo_number.' By Customer '.$curr_name.' on');
        //   $proLog->action = 'movedTOMemo';
        //   $proLog->save();

        $keyupVal = $extrakeyupstr;
        if(!empty($keyupVal)){
          $keyupVal = $keyupVal.",".$missingstr;
        }else{
          $keyupVal = $missingstr;
        }
        if(!empty($Request->warehouse_id)){
          $warehouse = Warehouse::findOrFail($Request->warehouse_id);
          $warehsName = $warehouse->name;
        }else{
          $warehsName = "";
        }

        $array['userIp'] = $Request->ip();
        $array['stockId'] = $keyupVal;
        $array['lsType'] = $Request->listing_type;
        $array['warehouse'] = $warehsName;
        $pdf = PDF::loadView('backend.product.inventory_run.mailpdf', $array);
        $array['from'] = env('MAIL_FROM_ADDRESS');
        Mail::send('frontend.backendMailer.addInvemail', $array, function($message) use ($array,$pdf) {
            $message->to(env("StockManager"));
            $message->subject('User Activity Notification');
            $message->attachData($pdf->output(), "inventory_run.pdf");
        });



        flash(translate('Inventory has been added successfully'))->success();

        return redirect()->route('inventory_run.index');

    }



    public function InventoryrunEdit(Request $request, $id)

    {

        $return = InventoryRun::findOrFail($id);

        // dd($returnitems);



        return view('backend.product.inventory_run.edit', compact('return'));

    }



    public function InventoryrunUpdate(Request $request, $id)

    {

      $InventoryrunUpdate = InventoryRun::findOrFail($id);

      $InventoryrunUpdate->return_date = $request->return_date;

      $InventoryrunUpdate->save();



      flash(translate('Inventory has been updated successfully'))->success();

      return back();

    }



    public function InventoryrunDestroy(Request $request,$id)

    {

        $post = InventoryRun::where('id',$id)->delete();

        flash(translate('Inventory has been deleted successfully'))->success();

        return back();

    }



    public function InventoryrunAjax(Request $request)

    {

      //  $supplier_id=$request->supplier_id;

      $search = $request->search;

      $hdn_product_type = $request->product_type;

      $ProTyprData = ProductType::where('listing_type',$hdn_product_type)->get();

      $ProTyprID = array();

      foreach ($ProTyprData as $ProTypr) {

        $ProTyprID[] = $ProTypr->id;

      }

      $hdn_warehouse = $request->warehouse;



     if($search == ''){

        $autocomplate = Product::orderby('name','asc')->select('id','stock_id','model','weight','product_cost','name','product_stocks.sku')->leftJoin('product_stocks','products.id','=','product_stocks.product_id')->whereIn('product_type_id',$hdn_product_type)->where('warehouse_id',$hdn_warehouse)->limit(5)->get();

     }else{

        $autocomplate = Product::select('products.id','stock_id','warehouse_id','model','weight','product_cost','name','custom_1','custom_2','custom_3','custom_4','custom_5','custom_6','custom_7','custom_8','custom_9','custom_10','product_stocks.sku')

        ->leftJoin('product_stocks','products.id','=','product_stocks.product_id')

        ->where('stock_id', 'like', '%' .$search . '%')

        ->where('warehouse_id',$hdn_warehouse)

        ->whereIn('products.product_type_id',$ProTyprID)

        ->get();

     }

     // dd($autocomplate);



     $response = array();

     foreach($autocomplate as $autoData){

         $skuNumber = isset($autoData->sku) ? $autoData->sku : '';

        $response[] = array("value"=>$autoData->id,"label"=>$autoData->stock_id,"name"=>$autoData->name,"warehouse"=>$autoData->warehouse_id,"model"=>$autoData->model,"weight"=>$autoData->weight,"custom_1"=>$autoData->custom_1,"custom_2"=>$autoData->custom_2,"custom_3"=>$autoData->custom_3,"custom_4"=>$autoData->custom_4,"paper_cart"=>$autoData->paper_cart, 'sku' => $skuNumber);

     }



     echo json_encode($response);

     exit;

    }

    public function export(Request $request){

      $ids = $request->checked_id;


      $proID = json_decode($ids, TRUE);

      $fetchLiaat = new InvetoryRunExport($proID);

    return Excel::download($fetchLiaat, 'InvetoryRunExport.xlsx');

}
public function preReturn(Request $request)

{

  $inventoryId= $request->id;

  if($inventoryId != "")

  {

    $currentUser = \Auth::user()->name;

    $InventoryData = InventoryRun::select('inventory_runs.id','inventory_runs.listing_type','inventory_runs.missing','inventory_runs.duplicate','inventory_runs.extra','inventory_runs.extrakeyup','inventory_runs.created_at','product_types.product_type_name','users.name','warehouse.name as warehouse_name','warehouse.address')
    ->leftJoin('users','users.id','=','inventory_runs.user')
    ->leftJoin('product_types','product_types.id','=','inventory_runs.listing_type')
    ->leftJoin('warehouse','warehouse.id','=','inventory_runs.warehouse_id')
    ->orderBy('inventory_runs.id', 'desc')
    ->where('inventory_runs.id',$inventoryId)
    ->first();
    $listing_type=$InventoryData->listing_type;
    $missingStock=$InventoryData->missing;
    $duplicateStock=$InventoryData->duplicate;
    $extraStock=$InventoryData->extra;
    $comma = "";
     if(!empty($optData->extra)){
       $comma = ",";
     }
    $extrakeyupStock=$InventoryData->extrakeyup;
    $allExtra = $extraStock.$comma.$extrakeyupStock;
    $warehouse_name=$InventoryData->warehouse_name;
    $address=$InventoryData->address;
    $date=date('m/d/20y',strtotime($InventoryData->created_at));
    $ReturntableAppend = "<table class='table table-bordered table-hover table-striped print-table order-table table'>
      <div class='well well-sm' style='border: 1px solid #ddd;background-color: #f6f6f6;box-shadow: none;border-radius: 0px;padding: 9px;min-height: 20px;margin-bottom:10px;'>
      </div>
      <thead>
        <tr class='bg-primary text-white'>

          <th scope='col'> User :</th>
          <td>$currentUser </td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Listing Types :</td>
          <td> $listing_type</td>
        </tr>
        <tr>
          <td>Missing </td>
          <td>$missingStock </td>
        </tr>
        <tr>
          <td>Duplicate </td>
          <td>$duplicateStock </td>
        </tr>
        <tr>
          <td>Extra </td>
          <td>$allExtra </td>
        </tr>
        <tr>
          <td>Date </td>
          <td>$date </td>
        </tr>
        <tr>
          <td>Warehouse </td>
          <td>$warehouse_name </td>
        </tr>
        <tr>
          <td>Address </td>
          <td> $address</td>
        </tr>";
    $ReturntableAppend .= "</tbody>
             <tfoot>
             </tfoot>
        </table> ";

  }
  return response()->json(['success' => true,'returnHtmlData'=>$ReturntableAppend]);
}


}
