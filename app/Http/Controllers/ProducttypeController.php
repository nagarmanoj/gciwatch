<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Models\Producttype;

use DB;



class ProducttypeController extends Controller

{

   // Producttype Start

    public function producttype(Request $request)

    {
      $pagination_qty = isset($request->purchases_pagi)?$request->purchases_pagi:25;
      if($request->input('purchases_pagi')!=NULL){
        $product_qty =  ($request->input('purchases_pagi'));
      }
      $sort_search = null;
       $partnersData = DB::table('product_types')

               ->select ('product_types.*','sequence.sequence_name')

               ->join('sequence', 'product_types.sequence_id', '=', 'sequence.id')

               ->orderBy('product_types.product_type_name');
               $product_qty=$request->purchases_pagi;
               if ($request->search != null){
                               $partnersData->orWhere('sequence.sequence_name', 'like', '%'.$request->search.'%');
                               $partnersData->orWhere('product_types.product_type_name', 'like', '%'.$request->search.'%');
                               $partnersData->orWhere('product_types.listing_type', 'like', '%'.$request->search.'%');
                               $partnersData->orWhere('product_types.serial_no', 'like', '%'.$request->search.'%');
       
                   $sort_search = $request->search;
       
               }
               if(isset($product_qty)){
       
                 if($product_qty!='All'){
       
                     $purchases = $partnersData->paginate(($product_qty));
       
                 }
       
                 else if(isset($product_qty) && $product_qty=='All'){
       
                     $purchases = $detailedProduct;
       
                 }
       
             }else{
       
              $purchases = $partnersData->paginate(25);
       
             }
               $data = $partnersData->get();

               // dd($data);

        return view("backend.site_options.producttype.index", compact('data','sort_search','pagination_qty','purchases'));

    }

    public function ProducttypeCreate()

    {

      return view("backend.site_options.producttype.create");

    }



    public function saveProducttype(Request $Request)

    {

      $Request->validate([

        'product_type_name' => 'required|unique:product_types'

      ]);



        $post = new Producttype();

        $post->product_type_name  = $Request->product_type_name;

        $post->sequence_id        = $Request->sequence_id;

        $post->listing_type       = $Request->listing_type;

        $post->product_type_code  = $Request->product_type_code;

        $post->serial_no          = $Request->serial_no;

        $post->custom_1           = $Request->custom_1;

        $post->custom_1_type      = $Request->custom_1_type;

        $post->custom_1_value     = $Request->custom_1_value;

        $post->custom_2           = $Request->custom_2;

        $post->custom_2_type      = $Request->custom_2_type;

        $post->custom_2_value     = $Request->custom_2_value;

        $post->custom_3           = $Request->custom_3;

        $post->custom_3_type      = $Request->custom_3_type;

        $post->custom_3_value     = $Request->custom_3_value;

        $post->custom_4           = $Request->custom_4;

        $post->custom_4_type      = $Request->custom_4_type;

        $post->custom_4_value     = $Request->custom_4_value;

        $post->custom_5           = $Request->custom_5;

        $post->custom_5_type      = $Request->custom_5_type;

        $post->custom_5_value     = $Request->custom_5_value;

        $post->custom_6           = $Request->custom_6;

        $post->custom_6_type      = $Request->custom_6_type;

        $post->custom_6_value     = $Request->custom_6_value;

        $post->custom_7           = $Request->custom_7;

        $post->custom_7_type      = $Request->custom_7_type;

        $post->custom_7_value     = $Request->custom_7_value;

        $post->custom_8           = $Request->custom_8;

        $post->custom_8_type      = $Request->custom_8_type;

        $post->custom_8_value     = $Request->custom_8_value;

        $post->custom_9           = $Request->custom_9;

        $post->custom_9_type      = $Request->custom_9_type;

        $post->custom_9_value     = $Request->custom_9_value;

        $post->custom_10          = $Request->custom_10;

        $post->custom_10_type     = $Request->custom_10_type;

        $post->custom_10_value    = $Request->custom_10_value;

        $post->is_active          = $Request->is_active;

        $post->save();

        flash(translate('Product Type has been added successfully'))->success();

        return back();

    }



    public function producttypeedit(Request $request, $id)

    {

        $partners = Producttype::findOrFail($id);

        return view('backend.site_options.producttype.edit', compact('partners'));

    }



    public function producttypeUpdate(Request $request, $id)

    {

      $partnersUpdate = Producttype::findOrFail($id);

      $partnersUpdate->product_type_name  = $request->product_type_name;

      $partnersUpdate->sequence_id        = $request->sequence_id;

      $partnersUpdate->listing_type       = $request->listing_type;

      $partnersUpdate->product_type_code  = $request->product_type_code;

      $partnersUpdate->serial_no          = $request->serial_no;

      $partnersUpdate->custom_1           = $request->custom_1;

      $partnersUpdate->custom_1_type      = $request->custom_1_type;

      $partnersUpdate->custom_1_value     = $request->custom_1_value;

      $partnersUpdate->custom_2           = $request->custom_2;

      $partnersUpdate->custom_2_type      = $request->custom_2_type;

      $partnersUpdate->custom_2_value     = $request->custom_2_value;

      $partnersUpdate->custom_3           = $request->custom_3;

      $partnersUpdate->custom_3_type      = $request->custom_3_type;

      $partnersUpdate->custom_3_value     = $request->custom_3_value;

      $partnersUpdate->custom_4           = $request->custom_4;

      $partnersUpdate->custom_4_type      = $request->custom_4_type;

      $partnersUpdate->custom_4_value     = $request->custom_4_value;

      $partnersUpdate->custom_5           = $request->custom_5;

      $partnersUpdate->custom_5_type      = $request->custom_5_type;

      $partnersUpdate->custom_5_value     = $request->custom_5_value;

      $partnersUpdate->custom_6           = $request->custom_6;

      $partnersUpdate->custom_6_type      = $request->custom_6_type;

      $partnersUpdate->custom_6_value     = $request->custom_6_value;

      $partnersUpdate->custom_7           = $request->custom_7;

      $partnersUpdate->custom_7_type      = $request->custom_7_type;

      $partnersUpdate->custom_7_value     = $request->custom_7_value;

      $partnersUpdate->custom_8           = $request->custom_8;

      $partnersUpdate->custom_8_type      = $request->custom_8_type;

      $partnersUpdate->custom_8_value     = $request->custom_8_value;

      $partnersUpdate->custom_9           = $request->custom_9;

      $partnersUpdate->custom_9_type      = $request->custom_9_type;

      $partnersUpdate->custom_9_value     = $request->custom_9_value;

      $partnersUpdate->custom_10          = $request->custom_10;

      $partnersUpdate->custom_10_type     = $request->custom_10_type;

      $partnersUpdate->custom_10_value    = $request->custom_10_value;

      $partnersUpdate->is_active          = $request->is_active;



      $partnersUpdate->save();



      flash(translate('Product Type has been updated successfully'))->success();

      return back();

    }

    // Producttype End













    public function optionDestroy($id)

    {

        $post = Producttype::where('id',$id)->delete();

        flash(translate('Product Type has been deleted successfully'))->success();

        return back();

    }







}

