<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Product;
use App\Transfer;
use App\TransfersExport;
use Excel;
use Auth;
use App\User;
use App\Activitylog;
use App\TransferItem;
use App\ReturnProd;
use App\ReturnItems;
use App\ProductStock;
use Illuminate\Support\Facades\DB;
class TransfersController extends Controller
{
   // Return Start
    public function transfer(Request $request)
    {

      $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
      if($request->input('pagination_qty')!=NULL){
          $pagination_qty =  $request->input('pagination_qty');
      }

      // $pagination_qty = isset($request->purchases_pagi)?$request->purchases_pagi:25;

      //   if($request->input('purchases_pagi')!=NULL){

      //   $product_qty =  ($request->input('purchases_pagi'));

      //   }
        $sort_search = null;
      $PurchasesProduct = Transfer::select('transfers.*','transfer_items.product_id','transfer_items.transfer_id','transfer_items.quantity','products.model')
        ->join('transfer_items','transfer_items.transfer_id','transfers.id')
        ->join('products','products.id','transfer_items.product_id')
        ->selectRaw('GROUP_CONCAT(transfer_items.product_code) as product_code')
        // ->selectRaw('GROUP_CONCAT(products.model) as model')
        ->groupBy('transfers.id')
        ->orderBy('transfers.id', 'desc');
        $product_qty=$request->purchases_pagi;
        if ($request->search != null){
                    $PurchasesProduct= $PurchasesProduct->orWhere('transfer_items.product_code', 'like', '%'.$request->search.'%');
                    $PurchasesProduct=     $PurchasesProduct->orWhere('products.model', 'like', '%'.$request->search.'%');
                    $PurchasesProduct=   $PurchasesProduct->orWhere('transfers.from_warehouse_name', 'like', '%'.$request->search.'%');
                    $PurchasesProduct=   $PurchasesProduct->orWhere('transfers.to_warehouse_name', 'like', '%'.$request->search.'%');
                    $PurchasesProduct=   $PurchasesProduct->orWhere('transfer_no', 'like', '%'.$request->search.'%');
                    $PurchasesProduct=   $PurchasesProduct->orWhere('quantity', 'like', '%'.$request->search.'%');
                    $PurchasesProduct=   $PurchasesProduct->orWhere('grand_total', 'like', '%'.$request->search.'%');

            $sort_search = $request->search;

        }
        if( $request->pagination_qty == "all"){
          $purchases =  $PurchasesProduct->get();
        }else{
          $purchases =  $PurchasesProduct->paginate($pagination_qty);
        }

      //   if(isset($product_qty)){

      //     if($product_qty!='All'){

      //         $purchases = $PurchasesProduct->paginate(($product_qty));

      //     }

      //     else if(isset($product_qty) && $product_qty=='All'){

      //         $purchases = $detailedProduct;

      //     }

      // }else{

      //  $purchases = $PurchasesProduct->paginate(25);

      // }
      $transfer=$PurchasesProduct->get();
      // DB::raw("(GROUP_CONCAT(transfer_items.product_code SEPARATOR ',')) as `product_code`")
      // dd($transfer);
        return view("backend.product.transfers.index", compact('transfer','sort_search','pagination_qty','purchases'));
    }
    public function transferCreate(Request $request)
    {
      return view("backend.product.transfers.create");
    }
    public function saveTransfer(Request $Request)
    {
      $toWarehouse = $Request->warehouse_id_to; echo "<br/>";
      $fromWarehouse = $Request->warehouse_id_from; echo "<br/>";
      // exit;
      $warehuseTo = Warehouse::where('id',$toWarehouse)->firstOrFail();
      $wtCode = $warehuseTo->code;
      $wtName = $warehuseTo->name;
      $WarehouseFrom = Warehouse::where('id',$fromWarehouse)->firstOrFail();
      $wfCode = $WarehouseFrom->code;
      $wfName = $WarehouseFrom->name;
      $post= new Transfer;
      $post->date=$Request->date;
      $post->transfer_no = $Request->reference_no;
      $post->from_warehouse_id=$fromWarehouse;
      $post->from_warehouse_code=$wfCode;
      $post->from_warehouse_name=$wfName;
      $post->to_warehouse_id=$toWarehouse;
      $post->to_warehouse_code=$wtCode;
      $post->to_warehouse_name=$wtName;
      $post->note=$Request->note;
      $post->total_tax=$Request->total_tax;
      $post->created_by=$Request->created_by;
      $post->status=$Request->status;
      $post->shipping=$Request->shipping;
      $post->attachment=$Request->document;
      // dd($post);
      $post->save();
      $transfer_id = $post->id;
      $totalReturn = 0;
      $reaArrData = $Request->proitemarr;
      if(!empty($reaArrData)){
        foreach ($reaArrData as $proid => $prodata) {
        $proQty = array_sum($prodata);
        $returnProData = Product::findOrFail($proid);
        $subtotalReturn = $returnProData->product_cost* $proQty;
          $proret = new TransferItem();
          $proret->transfer_id = $transfer_id;
          $proret->product_id = $proid;
          $proret->product_code = $returnProData->stock_id;
          $proret->product_name = $returnProData->name;
          $proret->quantity = $proQty;
          $proret->tax_rate_id = 0;
          $proret->net_unit_cost = $subtotalReturn;
          $proret->subtotal = $subtotalReturn;
          $proret->quantity_balance = $proQty;
          $proret->unit_cost = $returnProData->product_cost;
          $proret->real_unit_cost = $subtotalReturn;
          $proret->date = $Request->date;
          $proret->product_unit_id = $returnProData->unit;
          $proret->product_unit_code = $returnProData->unit;
          $proret->unit_quantity = $proQty;
          $proret->save();
          $totalReturn = $totalReturn+$subtotalReturn;
          // $returnProData->save();
          // $returnProData = ProductStock::where('product_id',$proid)->firstOrFail();
          // $proTotalQty = $returnProData->qty - $proQty;
          // $returnProData->qty = $proTotalQty;
          // $returnProData->save();
          $returnProData->warehouse_id=$toWarehouse;
          $this->ids=$proid;
          $returnProData = Product::findOrFail($proid);
          $returnProData->warehouse_id=$toWarehouse;
           $returnProData->save();


           $user = Auth::user();
           $curr_uid = $user->id;
           $curr_name = $user->name;
             $proLog = new Activitylog();
             $proLog->type = 'Transfers';
             $proLog->user_id = $curr_uid;
             $proLog->prodcut_id = $proid;
             $proLog->activity = addslashes('STOCK ID <a href="">'.$returnProData->stock_id.'</a>transfered From Warehouse <a href="#">'. $wfName.' TO '. $wtName.'</a> on');
             $proLog->action = 'movedTOTransfers';
             $proLog->save();


        }
      }
      // echo $returnProData;exit;
      $post->total = $totalReturn;
      $post->grand_total = $totalReturn;
      $post->save();
      flash(translate('Transfer has been added successfully'))->success();
      return redirect()->route('transfer.index');
    }
    public function transferedit(Request $request, $id)
    {
    }
    public function transferUpdate(Request $request, $id)
    {
    }
    public function optionDestroy($id)
    {
      $post = Transfer::where('id',$id)->delete();
      $post = TransferItem::where('transfer_id',$id)->delete();
      flash(translate('Return has been deleted successfully'))->success();
      return back();
    }
    public function transferSearchAjax(Request $request)
    {
     $warehouse_id_from = $request->warehouse_id_from;
     $warehouse_id_to = $request->warehouse_id_to;
     $search= $request->term;
     if($search == ''){
        $autocomplete = Product::orderby('name','asc')->select('id','stock_id','model','weight','product_cost','name')->limit(5)->get();
     }else{
        $autocomplete = Product::orderby('name','asc')->select('products.id','stock_id','model','weight','product_cost','name','custom_6','product_stocks.sku')
              ->leftJoin('product_stocks','products.id','=','product_stocks.product_id')
              ->where('products.stock_id', 'like', '%' .$search . '%')
              ->where('products.warehouse_id',$warehouse_id_from)
              ->get();
     }
     $response = array();
     foreach($autocomplete as $autoData){
        $product_cost = money_format('%.2n',$autoData->product_cost);
        $response[] = array("value"=>$autoData->id,"label"=>$autoData->stock_id,"name"=>$autoData->name,"model"=>$autoData->model,"weight"=>$autoData->weight,"custom_6"=>$autoData->custom_6,"sku"=>$autoData->sku,"product_cost"=>$product_cost);
     }
     echo json_encode($response);
     exit;
    }
    function transfers_delete(Request $request)
    {
      $ids = $request->checked_id;
      $proID = json_decode($ids, TRUE);
      // dd($proID);
      if($proID) {
          foreach ($proID as $jo_order_id) {
              // $this->destroy($jo_order_id);
              Transfer::where('id',$jo_order_id)->delete();
          }
      }
      flash(translate('Transfers has been deleted successfully'))->success();
      return back();
    }
    public function export(Request $request)
    {
      $ids = $request->checked_id;
      $proID = json_decode($ids, TRUE);
      // dd($proID);
      $fetchLiaat = new TransfersExport($proID);
      // dd($fetchLiaat);
      return Excel::download($fetchLiaat, 'Transfers.xlsx');
    }
    public function preReturn(Request $request)
    {
      $returnID= $request->id;
      // echo $returnID;exit;
      if($returnID != "")
      {
        $currentUser = \Auth::user()->name;
        $ReturnProData = Transfer::where('transfers.id',$returnID)
        ->join('warehouse','warehouse.id','=','transfers.from_warehouse_id')
        ->select('transfers.*')
        ->first();
        // date("m-d-Y", strtotime($orgDate));
        $ReturntableAppend = "<table class='table table-bordered table-hover table-striped print-table order-table table'>
        <div class='well well-sm' style='border: 1px solid #ddd;background-color: #f6f6f6;box-shadow: none;border-radius: 0px;padding: 9px;min-height: 20px;margin-bottom:10px;'>
          <div class='row bold tb-big'>
            <div class='col-xs-5' style='margin-left: 15px;'>
             <b>Date: ".date('m/d/20y', strtotime($ReturnProData->date))."</b><br>
             <b>Reference:$ReturnProData->transfer_no </b><br>
             <b>Transferred To: $ReturnProData->to_warehouse_name </b><br>
             <b>Transferred From: $ReturnProData->from_warehouse_name </b><br>
            </div>
        </div>
      </div>
        <thead>
          <tr class='bg-primary text-white'>
            <th scope='col'>No.</th>
            <th scope='col'>Description</th>
            <th scope='col'>Quantity</th>
            <th scope='col'>Unit Price</th>
            <th scope='col'>Subtotal</th>
          </tr>
        </thead>
        <tbody>";
        $datasum = TransferItem::select('transfer_items.*',DB::raw('SUM(transfer_items.subtotal) as memoSubTotal'),'products.name',DB::raw('SUM(transfer_items.quantity) as subqty'),'products.name','products.stock_id','products.model','products.weight','products.paper_cart','products.custom_1','products.custom_2','products.custom_3','products.custom_4','products.custom_5','products.custom_6','products.custom_7','products.custom_8','products.custom_9','products.custom_10')
        ->leftJoin('products', 'products.id', '=', 'transfer_items.product_id')
        ->where('transfer_items.transfer_id',$returnID)
        ->get();
        foreach ($datasum as $RProItem) {
          $subtotal=$RProItem->memoSubTotal;
          $qty_total=$RProItem->subqty;
        }
        $subqty =(int)$qty_total;
        $ReturnItemData = TransferItem::select('transfer_items.*','products.name','product_stocks.sku','products.stock_id','products.model','products.weight','products.paper_cart','products.custom_1','products.custom_2','products.custom_3','products.custom_4','products.custom_5','products.custom_6','products.custom_7','products.custom_8','products.custom_9','products.custom_10')
        ->leftJoin('products', 'products.id', '=', 'transfer_items.product_id')
        ->leftJoin('product_stocks', 'product_stocks.product_id', '=', 'products.id')
        ->where('transfer_items.transfer_id',$returnID)
        ->get();
        // print_r($ReturnItemData);exit;
        foreach ($ReturnItemData as $RProItem) {
          $quantity=$RProItem->quantity;
          // $subtotal=$RProItem->memoSubTotal;
          $product_cost = $RProItem->subtotal;
          $qty = $RProItem->qty;
          $unit_cost=$RProItem->unit_cost;
          $name = $RProItem->name;
          $stock_id = $RProItem->stock_id;
          $model = $RProItem->model;
          $weight = $RProItem->weight;
          $sku = $RProItem->sku;
          $paper_cart = $RProItem->paper_cart;
          if($RProItem->custom_1 != ""){
            $custom_1 = $RProItem->custom_1;
            $custom_1 = $custom_1."-";
          }else{
            $custom_1 = "";
          }
          if($RProItem->custom_2 != ""){
            $custom_2 = $RProItem->custom_2;
            $custom_2 = $custom_2."-";
          }else{
            $custom_2 = "";
          }
          if($RProItem->custom_3 != ""){
            $custom_3 = $RProItem->custom_3;
            $custom_3 = $custom_3."-";
          }else{
            $custom_3 = "";
          }
          if($RProItem->custom_4 != ""){
            $custom_4 = $RProItem->custom_4;
            $custom_4 = $custom_4."-";
          }else{
            $custom_4 = "";
          }
          if($RProItem->custom_5 != ""){
            $custom_5 = $RProItem->custom_5;
            $custom_5 = $custom_5."-";
          }else{
            $custom_5 = "";
          }
          if($RProItem->custom_6 != ""){
            $custom_6 = $RProItem->custom_6;
            $custom_6 = $custom_6."-";
          }else{
            $custom_6 = "";
          }
          if($RProItem->custom_7 != ""){
            $custom_7 = $RProItem->custom_7;
            $custom_7 = $custom_7."-";
          }else{
            $custom_7 = "";
          }
          if($RProItem->custom_8 != ""){
            $custom_8 = $RProItem->custom_8;
            $custom_8 = $custom_8."-";
          }else{
            $custom_8 = "";
          }
          if($RProItem->custom_9 != ""){
            $custom_9 = $RProItem->custom_9;
            $custom_9 = $custom_9."-";
          }else{
            $custom_9 = "";
          }
          if($RProItem->custom_10 != ""){
            $custom_10 = $RProItem->custom_10;
            $custom_10 = $custom_10."-";
           }else{
            $custom_10 = "";
          }
          if($RProItem->sku != ""){
            $sku = $RProItem->sku;
            $sku = $sku."-";
          }else{
            $sku = "";
          }
          if($RProItem->weight != ""){
            $weight = $RProItem->weight;
            $weight = $weight."-";
          }else{
            $weight = "";
          }
          if($RProItem->paper_cart != ""){
            $paper_cart = $RProItem->paper_cart;
            $paper_cart = $paper_cart."-";
          }else{
            $paper_cart = "";
          }
           setlocale(LC_MONETARY,"en_US");
            // $total_qty=$qty->count();
         $ReturntableAppend .= "
            <tr>
              <th scope='row'>1</th>
              <td>$model- $sku $weight $paper_cart $custom_1 $custom_2 $custom_3 $custom_4 $custom_5 $custom_6 $custom_7 $custom_8 $custom_9 $custom_10($stock_id)</td>
              <td>$quantity</td>
              <td>".money_format("%(#1n", $unit_cost)."\n"."</td>
              <td>".money_format("%(#1n", $product_cost)."\n"."</td>
            </tr>";
        }
        $ReturntableAppend .= "</tbody>
        <tfoot>
        <tr>
               <td colspan='2' style='text-align:right;'>Total Qty</td>
                <td> $subqty</td>
                <td>Total</td>
                <td>".money_format("%(#1n", $subtotal)."\n"."</td></tr>
      </tfoot>
      </table>
      <div class='text-right col-4 tb-big-foot'  style='border: 1px solid #ddd;background-color: #f6f6f6;box-shadow: none;border-radius: 0px;padding: 9px;min-height: 20px;margin-bottom:10px;margin-left:67%;'>
        <b><span class='mr-5'>Created By:$currentUser</span></b><br>
        <b><span class='mr-5'>Date:".date('m/d/20y', strtotime($ReturnProData->date))."</span></b>
      </div>
      ";
      }
      return response()->json(['success' => true,'returnHtmlData'=>$ReturntableAppend]);
    }
}
