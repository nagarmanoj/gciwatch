<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\ReturnProd;
use App\ReturnItems;
use Excel;
use App\ReturnsExport;
use App\Product;
use App\ProductStock;
use Auth;
use App\User;
use App\Activitylog;
class ReturnController extends Controller
{
   // Return Start
    public function return(Request $request)
    {
      //  $partnersNewData = ReturnProd::select('return_prods.id as ret_id','users.*')
      //  ->join('users', 'users.id', '=', 'return_prods.supplier_id');
      //  // if ($request->has('search')){
      //  //     $sort_search = $request->search;
      //  //     $pData = $partnersNewData->where('name', 'like', '%'.$sort_search.'%');
      //  // }
      //  // $data = $partnersNewData->paginate(10);
      //  $returnData = $partnersNewData->get();
      $pagination_qty = isset($request->purchases_pagi)?$request->purchases_pagi:25;
      if($request->input('purchases_pagi')!=NULL){
      $product_qty =  ($request->input('purchases_pagi'));
      }
      $sort_search = null;
       $PurchasesProduct = ReturnItems::select('return_items.*','return_prods.id as ret_id','return_prods.reference_no','return_prods.return_date','return_prods.supplier_id','products.name','products.stock_id','products.model','products.weight','products.paper_cart','products.custom_1','products.custom_2','products.custom_3','products.custom_4','products.custom_5','products.custom_6','products.custom_7','products.custom_8','products.custom_9','products.custom_10','users.name as supplier_name')
       ->join('return_prods','return_prods.id','=','return_items.return_id')
       ->join('users','users.id','=','return_prods.supplier_id')
       ->leftJoin('products', 'products.id', '=', 'return_items.product_id')
       ->orderBy('id', 'desc');
       $product_qty=$request->purchases_pagi;
       if ($request->search != null){
          $PurchasesProduct->orWhere('users.name', 'like', '%'.$request->search.'%');
          $PurchasesProduct->orWhere('products.stock_id', 'like', '%'.$request->search.'%');
          $PurchasesProduct->orWhere('return_prods.reference_no', 'like', '%'.$request->search.'%');
          $PurchasesProduct->orWhere('products.name', '%'.$request->search.'%');
          $sort_search = $request->search;
        }
        if(isset($product_qty)){
          if($product_qty!='All'){
              $purchases = $PurchasesProduct->paginate(($product_qty));
          }
          else if(isset($product_qty) && $product_qty=='All'){
              $purchases = $detailedProduct;
          }
      }else{
       $purchases = $PurchasesProduct->paginate(25);
      }
      $returnData= $PurchasesProduct->get();
      //  dd($ReturnItemData);
        return view("backend.product.return.index", compact('returnData','purchases','pagination_qty','sort_search'));
    }
    public function ReturnCreate()
    {
      return view("backend.product.return.create");
    }
    public function saveReturn(Request $Request)
    {
        $post = new ReturnProd();
        $post->return_date = $Request->return_date;
        $post->reference_no = $Request->reference_no;
        $post->supplier_id = $Request->supplier_id;
        $post->note = $Request->note;
        $post->staff_note = $Request->staff_note;
        $post->save();
        $return_id = $post->id;
        $totalReturn = 0;
        $reaArrData = $Request->proitemarr;

        $supplier = User::findOrFail($Request->supplier_id);
        $supplier_name=$supplier->name;
        if(!empty($reaArrData)){
          foreach ($reaArrData as $proid => $prodata) {
          $proQty = array_sum($prodata);
          $returnProData = Product::findOrFail($proid);
          $sotck_id=$returnProData->stock_id;
          $subtotalReturn = $returnProData->product_cost* $proQty;
            $proret = new ReturnItems();
            $proret->return_id = $return_id;
            $proret->product_id = $proid;
            $proret->product_cost = $returnProData->product_cost;
            $proret->qty = $proQty;
            $proret->sub_total = $subtotalReturn;
            $proret->save();
            $totalReturn = $totalReturn+$subtotalReturn;
            $returnProData = ProductStock::where('product_id',$proid)->firstOrFail();
            $proTotalQty = $returnProData->qty - $proQty;
            $returnProData->qty = $proTotalQty;
            $returnProData->save();

            $user = Auth::user();
            $curr_uid = $user->id;
            $curr_name = $user->name;
              $proLog = new Activitylog();
              $proLog->type = 'Retruns';
              $proLog->user_id = $curr_uid;
              $proLog->prodcut_id = $proid;
              $proLog->activity = addslashes('STOCK ID <a href="">'.$sotck_id.'</a> is returned to <a href="#">'. $curr_name.' TO '. $supplier_name.'</a> on');
              $proLog->action = 'movedToReturns';
              $proLog->save();

          }

        }

        $post->return_total = $totalReturn;

        $post->save();

        flash(translate('Return has been added successfully'))->success();

        return redirect()->route('return.index');

    }



    public function returnedit(Request $request, $id)

    {

        $return = ReturnProd::findOrFail($id);

        $returnitems = ReturnItems::select('return_items.*','products.name','product_stocks.sku','products.product_cost','products.stock_id','products.model','products.weight','products.product_cost')

        ->leftJoin('product_stocks', 'product_stocks.product_id', '=', 'return_items.product_id')

        ->join('products','products.id','=','return_items.product_id')

        ->where('return_id',$return->id)

        ->get();

        // dd($returnitems);



        return view('backend.product.return.edit', compact('return','returnitems'));

    }



    public function returnUpdate(Request $request, $id)

    {

      $ReturnProdUpdate = ReturnProd::findOrFail($id);

      $ReturnProdUpdate->return_date = $request->return_date;

      $ReturnProdUpdate->reference_no = $request->reference_no;

      $ReturnProdUpdate->supplier_id = $request->supplier_id;

      $ReturnProdUpdate->note = $request->note;

      $ReturnProdUpdate->staff_note = $request->staff_note;

      $ReturnProdUpdate->save();
      $supplier = User::findOrFail($Request->supplier_id);
      $supplier_name=$supplier->name;

      $return_id = $id;



      $totalReturn = 0;

      $reaArrData = $request->proitemarr;

      if(!empty($reaArrData)){

        foreach ($reaArrData as $proid => $prodata) {

        $proQty = array_sum($prodata);

        $returnProData = Product::findOrFail($proid);
        $sotck_id=$returnProData->stock_id;
        $subtotalReturn = $returnProData->product_cost* $proQty;

          $proret = new ReturnItems();

          $proret->return_id = $return_id;

          $proret->product_id = $proid;

          $proret->product_cost = $returnProData->product_cost;

          $proret->qty = $proQty;

          $proret->sub_total = $subtotalReturn;

          $proret->save();

          $totalReturn = $totalReturn+$subtotalReturn;

          $user = Auth::user();
          $curr_uid = $user->id;
          $curr_name = $user->name;
            $proLog = new Activitylog();
            $proLog->type = 'Retruns';
            $proLog->user_id = $curr_uid;
            $proLog->prodcut_id = $proid;
            $proLog->activity = addslashes('STOCK ID <a href="">'.$sotck_id.'</a> is returned to <a href="#">'. $curr_name.' TO '. $supplier_name.'</a> on');
            $proLog->action = 'movedToReturns';
            $proLog->save();

        }

      }

      $ReturnProdUpdate->return_total = $totalReturn;

      $ReturnProdUpdate->save();



      flash(translate('Return has been updated successfully'))->success();

      return back();

    }

    // Return End













    public function optionDestroy(Request $request,$id)

    {

        $post = ReturnProd::where('id',$id)->delete();

        $post = ReturnItems::where('return_id',$id)->delete();

        flash(translate('Return has been deleted successfully'))->success();

        return back();

    }





    public function itemDestroy(Request $request,$id)

    {

        $ReturnProVal = ReturnItems::findOrFail($id);

        $pro_sub_total = $ReturnProVal->sub_total;

        $return_id = $ReturnProVal->return_id;

        $ReturnProdUpdate = ReturnProd::findOrFail($return_id);

        $proUpdatetotal = $ReturnProdUpdate->return_total - $pro_sub_total;

        $ReturnProdUpdate->return_total = $proUpdatetotal;

        $ReturnProdUpdate->save();

        $post = ReturnItems::where('id',$id)->delete();

        flash(translate('Return has been deleted successfully'))->success();

        return back();

    }



    public function proSearchAjax(Request $request)

    {

      //  $supplier_id=$request->supplier_id;

      $search= $request->search;



     if($search == ''){

        $autocomplate = Product::orderby('name','asc')->select('id','stock_id','model','weight','product_cost','name')->limit(5)->get();

     }else{

        $autocomplate = Product::orderby('name','asc')

                        ->leftJoin('product_stocks','product_stocks.product_id','=','products.id')

                        ->select('products.id','products.stock_id','products.model','products.weight','products.product_cost','products.name','products.custom_6','product_stocks.sku')

                        ->where('stock_id', 'like', '%' .$search . '%')

                        ->get();

                        // dd($autocomplate);

     }



     $response = array();

     foreach($autocomplate as $autoData){

       $product_cost = money_format('%(#1n',$autoData->product_cost)."\n";

        $response[] = array("value"=>$autoData->id,"label"=>$autoData->stock_id,"name"=>$autoData->name,"model"=>$autoData->model,"weight"=>$autoData->weight,"product_cost"=>$product_cost,"sku"=>$autoData->sku,"custom_6"=>$autoData->custom_6);

     }



     echo json_encode($response);

     exit;

    }



    public function preReturn(Request $request)

    {

      $returnID= $request->id;

      if($returnID != "")

      {

        $currentUser = \Auth::user()->name;

        $ReturnProData = ReturnProd::where('return_prods.id',$returnID)

        ->join('users','users.id','=','return_prods.supplier_id')

        ->select('return_prods.*','users.name')

        ->first();

        // date("m-d-Y", strtotime($orgDate));

        $ReturntableAppend = "<table class='table table-bordered table-hover table-striped print-table order-table table'>

        <div class='well well-sm' style='border: 1px solid #ddd;background-color: #f6f6f6;box-shadow: none;border-radius: 0px;padding: 9px;min-height: 20px;margin-bottom:10px;'>

          <div class='row bold tb-big'>

            <div class='col-xs-5' style='margin-left: 15px;'>

             <b>Date: ".date('m/d/20y', strtotime($ReturnProData->return_date))."</b><br>

             <b>Type: Return Purchase </b><br>

             <b>Reference:$ReturnProData->reference_no </b><br>

             <b>Return To: $ReturnProData->name </b><br>

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





        $ReturnItemData = ReturnItems::select('return_items.*','products.name','product_stocks.sku','products.stock_id','products.model','products.weight','products.paper_cart','products.custom_1','products.custom_2','products.custom_3','products.custom_4','products.custom_5','products.custom_6','products.custom_7','products.custom_8','products.custom_9','products.custom_10')

        ->leftJoin('products', 'products.id', '=', 'return_items.product_id')

        ->leftJoin('product_stocks', 'product_stocks.product_id', '=', 'products.id')

        ->where('return_items.return_id',$returnID)

        ->get();

        // dd($ReturnItemData);



        foreach ($ReturnItemData as $RProItem) {

          $product_cost = $RProItem->product_cost;

          $qty = $RProItem->qty;

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

          $ReturntableAppend .= "

            <tr>

              <th scope='row'>1</th>

              <td>$model- $sku $weight $paper_cart $custom_1 $custom_2 $custom_3 $custom_4 $custom_5 $custom_6 $custom_7 $custom_8 $custom_9 $custom_10($stock_id)</td>

              <td>$qty</td>

              <td>".money_format("%(#1n", $product_cost)."\n"."</td>

              <td>".money_format("%(#1n", $product_cost)."\n"."</td>

            </tr>";



        }

        $ReturntableAppend .= "</tbody>

        <tfoot>

      </tfoot>

      </table>

      <div class='text-right col-4 tb-big-foot'  style='border: 1px solid #ddd;background-color: #f6f6f6;box-shadow: none;border-radius: 0px;padding: 9px;min-height: 20px;margin-bottom:10px;margin-left:67%;'>

        <b><span class='mr-5'>Created By:$currentUser</span></b><br>

        <b><span class='mr-5'>Date:".date('m/d/20y', strtotime($ReturnProData->return_date))."</span></b>

      </div>

      ";

      }

      return response()->json(['success' => true,'returnHtmlData'=>$ReturntableAppend]);



    }





  public function delete_sction(Request $request)

  {

    $ids = $request->checked_id;

    $proID = json_decode($ids, TRUE);

      ReturnProd::whereIn('id',$proID)->delete();

      ReturnItems::whereIn('return_id',$proID)->delete();

    flash(translate('Returns has been deleted successfully'))->success();

      return back();

  }

  public function export(Request $request)

  {

    $ids = $request->checked_id;

    // dd($ids);

    $proID = json_decode($ids, TRUE);

    $fetchLiaat = new ReturnsExport($proID);

    return Excel::download($fetchLiaat, 'Returns.xlsx');

  }

}
