<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Appraisal;

use App\Models\Warehouse;

use App\JobOrder;

use App\JobOrderDetail;

use App\Product;

use \InvPDF;

use App\ProductType;

use App\User;

use App\Expertise;

use App\JobOrdersExport;

use Illuminate\Support\Facades\DB;

class AppraisalController extends Controller

{

   // Appraisal Start

    public function appraisal( Request $request)
    {
      $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
        if($request->input('pagination_qty')!=NULL){
            $pagination_qty =  $request->input('pagination_qty');
        }
        $sort_search = null;
        $data = Appraisal::orderBy('id','DESC');
        if ($request->search != null)
        {
            $data->orWhere('template_name', 'like', '%'.$request->search.'%');
            $data->orWhere('stock_id', 'like', '%'.$request->search.'%');
            $data->orWhere('listing_type', 'like', '%'.$request->search.'%');
            $data->orWhere('manufacturer', 'like', '%'.$request->search.'%');  
            $data->orWhere('model_name', 'like', '%'.$request->search.'%');  
            $data->orWhere('factory_model', 'like', '%'.$request->search.'%');  
            $data->orWhere('serial_no', 'like', '%'.$request->search.'%');  
            $data->orWhere('sp_value', 'like', '%'.$request->search.'%');  
            $data->orWhere('manufacturer', 'like', '%'.$request->search.'%');  
            $sort_search = $request->search;

        }
       

        
        if($pagination_qty == 'All'){
            $appraisal = $data->get();
        }else{
            $appraisal = $data->paginate(($pagination_qty));
        }

        return view("backend.appraisal.index",compact('pagination_qty','appraisal','sort_search'));

    }

    public function AppraisalCreate()

    {

      return view("backend.appraisal.create");

    }



    public function saveAppraisal(Request $Request)

    {

        $post = new Appraisal();

        $post->template_name = $Request->template_name;

        $post->listing_type = $Request->listing_type;

        $post->stock_id = $Request->stock_id;

        $post->manufacturer = $Request->manufacturer;

        $post->model_name = $Request->model_name;

        $post->factory_model = $Request->factory_model;

        $post->serial_no = $Request->serial_no;

        $post->size = $Request->size;

        $post->dial = $Request->dial;

        $post->bazel = $Request->bazel;

        $post->metal = $Request->metal;

        $post->bracelet_meterial = $Request->bracelet_meterial;

        $post->crystal = $Request->crystal;

        $post->sp_value = $Request->sp_value;

        $post->image = $Request->image;
        // echo $post->image  ; exit;

        $post->appraised_name = $Request->appraised_name;

        $post->appraised_address = $Request->appraised_address;

        $post->appraisal_city = $Request->appraisal_city;

        $post->appraisal_state = $Request->appraisal_state;

        $post->appraisal_zipcode = $Request->appraisal_zipcode;

        $post->appraisal_number = $Request->appraisal_number;

        $post->appraisal_location = $Request->appraisal_location;

        $post->appraisal_date = $Request->appraisal_date;
        $post->save();
        // dd($post);
        flash(translate('Appraisal has been added successfully'))->success();
        return redirect()->route('Appraisal.index');

    }



    public function appraisaledit(Request $request, $id)

    {

        $appraisal = Appraisal::findOrFail($id);

        // dd($appraisal);

        return view('backend.appraisal.edit', compact('appraisal'));

    }



    public function appraisalUpdate(Request $request, $id)

    {

      $appraisalUpdate = Appraisal::findOrFail($id);

      $appraisalUpdate->template_name = $request->template_name;

      $appraisalUpdate->listing_type = $request->listing_type;

      $appraisalUpdate->stock_id = $request->stock_id;

      $appraisalUpdate->manufacturer = $request->manufacturer;

      $appraisalUpdate->model_name = $request->model_name;

      $appraisalUpdate->factory_model = $request->factory_model;

      $appraisalUpdate->serial_no = $request->serial_no;

      $appraisalUpdate->size = $request->size;

      $appraisalUpdate->dial = $request->dial;

      $appraisalUpdate->bazel = $request->bazel;

      $appraisalUpdate->metal = $request->metal;

      $appraisalUpdate->bracelet_meterial = $request->bracelet_meterial;

      $appraisalUpdate->crystal = $request->crystal;

      $appraisalUpdate->sp_value = $request->sp_value;

      $appraisalUpdate->image = $request->image;

      $appraisalUpdate->appraised_name = $request->appraised_name;

      $appraisalUpdate->appraised_address = $request->appraised_address;

      $appraisalUpdate->appraisal_city = $request->appraisal_city;

      $appraisalUpdate->appraisal_state = $request->appraisal_state;

      $appraisalUpdate->appraisal_zipcode = $request->appraisal_zipcode;

      $appraisalUpdate->appraisal_number = $request->appraisal_number;

      $appraisalUpdate->appraisal_location = $request->appraisal_location;

      $appraisalUpdate->appraisal_date = $request->appraisal_date;

      // dd($appraisalUpdate);exit;



      $appraisalUpdate->save();



      flash(translate('Appraisal has been updated successfully'))->success();

      return redirect()->route('Appraisal.index');

    }

    // Appraisal End



    public function optionDestroy($id)

    {
        // dd($id);
        $post = Appraisal::where('id',$id)->delete();

        flash(translate('Appraisal has been deleted successfully'))->success();

        return back();

    }



    public function AppraisalCreateStockId(Request $request){

          $name = $request->name;





        //   $option = DB::table('site_options')->where('option_value', $name)->first();

        //   $listing_type_id = $option->id;

        //   $apparisalVal = DB::table('appraisals')->where('listing_type', $listing_type_id)->first();

        //   return response()->json(['success'=>$apparisalVal]);

        return response()->json(['success'=>"hello"]);

    }

    public function listingAjax(Request $request)

    {

      $listingName = $request->id;

      $ProListing = 
      Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop','category','productcondition','productType')
      ->leftJoin('warehouse','warehouse.id','=','products.warehouse_id')
      ->leftJoin('users','users.id','=','products.supplier_id')
      ->leftJoin('product_types','product_types.id','=','products.product_type_id')
      ->leftJoin('brands','brands.id','=','products.brand_id')
      ->leftJoin('productconditions','productconditions.id','=','products.productcondition_id')
      ->join('product_stocks','products.id','=','product_stocks.product_id')
      ->join('site_options','site_options.option_value','=','products.model')
      ->orderBy('products.stock_id','ASC')
      ->groupBy('products.id')
      ->select('products.*','brands.name as manufacturer','product_stocks.sku','categories.name as model_name','product_stocks.sku as serial_number')
      ->leftJoin('categories','categories.id','=','products.category_id')
      ->where('product_types.listing_type',$listingName)->get();
            // Product::select('products.*','product_stocks.sku','product_types.listing_type')
            // ->leftJoin('product_types', 'product_types.id', '=', 'products.product_type_id')
            // ->leftJoin('product_stocks', 'product_stocks.product_id', '=', 'products.id')
            // ->orderBy('products.stock_id','ASC')
            // ->where('product_types.listing_type',$listingName)

            // ->get();

            $TagOptionHtml= "<option value=''>Select Stock ID</option><option value='NIS'>NIS</option>";

            foreach ($ProListing as $Pdata) {

              $stock =$Pdata->stock_id;

              $model =$Pdata->model;

              $weight =$Pdata->weight;

              $model =$Pdata->model;

              $sku =$Pdata->sku;

              $screw_count =$Pdata->custom_6;

              $TagOptionHtml .= "<option data-weight='".$weight."' data-screw='".$screw_count."' data-model='".$model."' data-sku='".$sku."' value='".$stock."'>$stock</option>";

            }



            return response()->json(['success' => true,'TagHTML'=>$TagOptionHtml]);

    }

    function StockIdAjax(Request $request)

    {
      $stock_id=$request->stock_id;

      if($stock_id != 'NIS')

      {

      $product=
      
      Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop','category','productcondition','productType')

        ->leftJoin('warehouse','warehouse.id','=','products.warehouse_id')

        ->leftJoin('users','users.id','=','products.supplier_id')

        ->leftJoin('product_types','product_types.id','=','products.product_type_id')

        ->leftJoin('brands','brands.id','=','products.brand_id')
        ->leftJoin('productconditions','productconditions.id','=','products.productcondition_id')

        ->join('product_stocks','products.id','=','product_stocks.product_id')

        ->join('site_options','site_options.option_value','=','products.model')

        ->orderBy('products.id', 'DESC')

        ->groupBy('products.id')

        ->select('products.*','brands.name as manufacturer','product_stocks.sku','categories.name as model_name','product_stocks.sku as serial_number')
        ->leftJoin('categories','categories.id','=','products.category_id')
          ->where('stock_id',$stock_id)->first();
      
      
      // Product::select('products.*','brands.name as manufacturer','categories.name as model_name','product_stocks.sku as serial_number','product_stocks.product_id')

      // ->leftJoin('brands','brands.id','=','products.brand_id')

      // ->leftJoin('categories','categories.id','=','products.category_id')

      // ->leftJoin('product_stocks','product_stocks.product_id','=','products.id')

      // ->where('stock_id',$stock_id)

      // ->first();

      if($product){

        echo json_encode($product);

      }else{

        $array = array();

        echo json_encode($array);

      }

    }else{

      $array = array();

      echo json_encode($array);

    }

    exit;

    }

    function view(Request $request)

    {

      $id=$request->id;
      $array='';
      $apparisalVal=Appraisal::findOrFail($id);
      // echo ;
      $stock_id=$apparisalVal->stock_id;
      $img_path=uploaded_asset($apparisalVal->image);
      $img="<img src='".$img_path."' width='100px' alt='".$stock_id."'>";

      $proStatusData['html'] = $img;
      $proStatusData['data'] = $apparisalVal;
      // $proStatusData['img_data']=$apparisalVal->image;
      echo json_encode($proStatusData);
      // $array=[$img,$apparisalVal];
      // echo json_encode($array);

    }


}

