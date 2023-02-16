<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Models\Warehouse;



class WarehouseController extends Controller

{

   // Warehouse Start

    public function warehouse(Request $request)

    {
      $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
      if($request->input('pagination_qty')!=NULL){
          $pagination_qty =  $request->input('pagination_qty');
      }


       $partnersNewData = Warehouse::orderBy('id','ASC');

       if ($request->has('search')){

           $sort_search = $request->search;

           $pData = $partnersNewData->where('name', 'like', '%'.$sort_search.'%');

       }

      //  $data = $partnersNewData->paginate(10);
       if( $request->pagination_qty == "all"){
        $partnersData = $partnersNewData->get();
      }else{
        $partnersData = $partnersNewData->paginate($pagination_qty);
      }
      //  $partnersData = $partnersNewData->get();

        return view("backend.site_options.warehouse.index", compact('partnersData','pagination_qty'));

    }

    public function WarehouseCreate()

    {

      return view("backend.site_options.warehouse.create");

    }



    public function saveWarehouse(Request $Request)

    {

        $post = new Warehouse();

        $post->code = $Request->code;

        $post->name = $Request->name;

        $post->price_group = $Request->price_group;

        $post->phone = $Request->phone;

        $post->email = $Request->email;

        $post->address = $Request->address;

        $post->map = $Request->map;

        $post->save();

        flash(translate('Warehouse has been added successfully'))->success();

        return back();

    }



    public function warehouseedit(Request $request, $id)

    {

        $partners = Warehouse::findOrFail($id);

        return view('backend.site_options.warehouse.edit', compact('partners'));

    }



    public function warehouseUpdate(Request $request, $id)

    {

      $partnersUpdate = Warehouse::findOrFail($id);

      $partnersUpdate->code = $request->code;

      $partnersUpdate->name = $request->name;

      $partnersUpdate->price_group = $request->price_group;

      $partnersUpdate->phone = $request->phone;

      $partnersUpdate->email = $request->email;

      $partnersUpdate->address = $request->address;

      $partnersUpdate->map = $request->map;



      $partnersUpdate->save();



      flash(translate('Warehouse has been updated successfully'))->success();

      return back();

    }

    // Warehouse End













    public function optionDestroy($id)

    {

        $post = Warehouse::where('id',$id)->delete();

        flash(translate('Warehouse has been deleted successfully'))->success();

        return back();

    }



    public function productwarehouseAjax(Request $Request)

    {

      // Add Unit...

       $post = new Warehouse;

       $post->code = $Request->code;

       $post->name = $Request->name;

       $post->price_group = $Request->price_group;

       $post->phone = $Request->phone;

       $post->email = $Request->email;

       $post->address = $Request->address;

       $post->map = $Request->map;

       $post->save();



       $warehouseOptions = Warehouse::all();

       $warehouseOptionHtml = "";

       foreach ($warehouseOptions as $warehouse) {

         $warehouse_id =$warehouse->id;

         $warehouse_name =$warehouse->name;

         $warehouseOptionHtml .= "<option value='".$warehouse_id."'>$warehouse_name</option>";

       }

       return response()->json(['success' => true,'warehouseHTML'=>$warehouseOptionHtml]);

    }







}

