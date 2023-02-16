<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Models\Productcondition;



class ProductconditionController extends Controller

{

   // Productcondition Start

    public function productcondition(Request $request)

    {

       $partnersNewData = Productcondition::orderBy('id','ASC');

       if ($request->has('search')){

           $sort_search = $request->search;

           $pData = $partnersNewData->where('name', 'like', '%'.$sort_search.'%');

       }

       $data = $partnersNewData->paginate(10);

       $partnersData = $partnersNewData->get();

        return view("backend.site_options.productcondition.index", compact('partnersData','data'));

    }



          // ajax search Start

          public function getAllcondition(Request $request){



              ## Read value

              $draw = $request->get('draw');

              $start = $request->get("start");

              $rowperpage = $request->get("length"); // Rows display per page



              $columnIndex_arr = $request->get('order');

              $columnName_arr = $request->get('columns');

              $order_arr = $request->get('order');

              $search_arr = $request->get('search');



              $columnIndex = $columnIndex_arr[0]['column']; // Column index

              $columnName = $columnName_arr[$columnIndex]['data']; // Column name

              $columnSortOrder = $order_arr[0]['dir']; // asc or desc

              $searchValue = $search_arr['value']; // Search value



              // Total records

              $totalRecords = Productcondition::select('count(*) as allcount')->count();

              $totalRecordswithFilter = Productcondition::select('count(*) as allcount')->where('name', 'like', '%' .$searchValue . '%')->count();



              // Fetch records

              $records = Productcondition::orderBy($columnName,$columnSortOrder)

                     ->where('name', 'like', '%' .$searchValue . '%')

                    ->select('*')

                    ->skip($start)

                    ->take($rowperpage)

                    ->get();



              $data_arr = array();



              foreach($records as $rkey => $record){

                 $id = $record->id;

                 $name = $record->name;

                 $description = $record->description;

                 $sno = $start + $rkey + 1;


                 $data_arr[] = array(

                     "id" => $sno,

                     "name" => $name,

                     "description" => $description,

                     "link" => '<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="'.route('productcondition.edit', ['id'=>$id, 'lang'=>env('DEFAULT_LANGUAGE')] ).'">

                         <i class="las la-edit"></i>

                     </a>

                     <a href="'.route('productcondition.destroy', $id).'" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="'.route('productcondition.destroy', $id).'">

                         <i class="las la-trash"></i>

                     </a>'

                 );

              }



              $response = array(

                 "draw" => intval($draw),

                 "iTotalRecords" => $totalRecords,

                 "iTotalDisplayRecords" => $totalRecordswithFilter,

                 "aaData" => $data_arr

              );



              return response()->json($response);

           }

          // ajax search end



    public function ProductconditionCreate()

    {

      return view("backend.site_options.productcondition.create");

    }



    public function saveProductcondition(Request $Request)

    {

        $post = new Productcondition();

        $post->name = $Request->name;

        $post->description = $Request->description;

        $post->save();

        flash(translate('Condition has been added successfully'))->success();

        return view('backend.site_options.productcondition.index');

    }



    public function productconditionedit(Request $request, $id)

    {

        $partners = Productcondition::findOrFail($id);

        return view('backend.site_options.productcondition.edit', compact('partners'));

    }



    public function productconditionUpdate(Request $request, $id)

    {

      $partnersUpdate = Productcondition::findOrFail($id);

      $partnersUpdate->name = $request->name;

      $partnersUpdate->description = $request->description;



      $partnersUpdate->save();



      flash(translate('Condition has been updated successfully'))->success();

      return view('backend.site_options.productcondition.index');

    }

    // Productcondition End





    public function optionDestroy($id)

    {

        $post = Productcondition::where('id',$id)->delete();

        // $post = Productcondition::findOrFail($id);

        // Productcondition::where('id', $post->id)->delete();

        // foreach ($post->post_translations as $key => $post_translation) {

        //     $post_translation->delete();

        // }

        // Productcondition::destroy($id);

        flash(translate('Condition has been deleted successfully'))->success();

        return redirect()->route('productcondition.index');



        // return back();

    }



    public function productConditionAjax(Request $request)

    {

      // Add Unit...

       $Productconditions = new Productcondition;

       $Productconditions->name = $request->name;

       $Productconditions->description = $request->description;

       $Productconditions->save();

       $p_condition = Productcondition::orderBy('id','ASC')->get();

       $conditionHtml = "";

       foreach ($p_condition as $condition) {

         $condition_id =$condition->id;

         $condition_name =$condition->name;

         $conditionHtml .= "<option value='".$condition_id."'>$condition_name</option>";

       }

       return response()->json(['success' => true,'conHtml'=>$conditionHtml]);

    }







}

