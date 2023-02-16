<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\SiteOptions;
use Illuminate\Validation\Rule;
class SiteOptionsController extends Controller
{
   // Partners Start
    public function partners(Request $request)
    {
       $partnersNewData = SiteOptions::where('option_name', '=', 'partners');
       if ($request->has('search')){
           $sort_search = $request->search;
           $pData = $partnersNewData->where('option_value', 'like', '%'.$sort_search.'%');
       }
       $data = $partnersNewData->paginate(10);
       $partnersData = $partnersNewData->get();
        return view("backend.site_options.partners.index", compact('partnersData','data'));
    }
    // ajax search Start
    public function getAllpartners(Request $request){
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
        $totalRecords = SiteOptions::select('count(*) as allcount')->where('option_name', '=', 'partners')->count();
        $totalRecordswithFilter = SiteOptions::select('count(*) as allcount')->where('option_name', '=', 'partners')->where('option_value', 'like', '%' .$searchValue . '%')->count();
        // Fetch records
        $records = SiteOptions::where('option_name', '=', 'partners')
               ->where('option_value', 'like', '%' .$searchValue . '%')
              ->select('*')
              ->orderBy($columnName,$columnSortOrder)
              ->skip($start)
              ->take($rowperpage)
              ->get();
        $data_arr = array();
        foreach($records as $rkey => $record){
           $id = $record->id;
           $option_value = $record->option_value;
           $description = $record->description;
           $email = $record->email;
           $sno = $start + $rkey + 1;

           $data_arr[] = array(
               "id" => $sno,
               "option_value" => $option_value,
               "description" => $description,
               "link" => '<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="'.route('partners.edit', ['id'=>$id, 'lang'=>env('DEFAULT_LANGUAGE')] ).'">
                              <i class="las la-edit"></i>
                          </a>
                          <a href=""'.route('soption.destroy', $id).'"" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="'.route('soption.destroy', $id).'">
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
    public function partnersCreate()
    {
      return view("backend.site_options.partners.create");
    }
   public function savePartner(Request $Request)
    {
      $Request->validate([
          'option_value' => [
            'required',Rule::unique('site_options')->where(function($query) use ($Request) {
                return $query->where('option_name', $Request->option_name);
                  })
                ],
        ]);
        $post = new SiteOptions();
        $post->option_name = $Request->option_name;
        $post->option_value = $Request->option_value;
        $post->description = $Request->description;
        $post->save();
        flash(translate('Partner has been added successfully',))->success();
        return view("backend.site_options.partners.index");
    }
    public function partnersedit(Request $request, $id)
    {
        $partners = SiteOptions::findOrFail($id);
        return view('backend.site_options.partners.edit', compact('partners'));
    }
    public function partnerUpdate(Request $request, $id)
    {
      $partnersUpdate = SiteOptions::findOrFail($id);
      $partnersUpdate->option_value = $request->option_value;
      $partnersUpdate->description = $request->description;
       $partnersUpdate->save();
      flash(translate('Partner has been updated successfully'))->success();
      return view("backend.site_options.partners.index");
    }
    // Partners End
    // Metal Start
     public function metal(Request $request)
     {
        $partnersNewData = SiteOptions::where('option_name', '=', 'metal');
        if ($request->has('search')){
            $sort_search = $request->search;
           $pData = $partnersNewData->where('option_value', 'like', '%'.$sort_search.'%');
        }
        $data = $partnersNewData->paginate(10);
        $partnersData = $partnersNewData->get();
         return view("backend.site_options.metal.index", compact('partnersData','data'));
     }
     // ajax search Start
     public function getAllmetal(Request $request){
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
         $totalRecords = SiteOptions::select('count(*) as allcount')->where('option_name', '=', 'metal')->count();
         $totalRecordswithFilter = SiteOptions::select('count(*) as allcount')->where('option_name', '=', 'metal')->where('option_value', 'like', '%' .$searchValue . '%')->count();
         // Fetch records
         $records = SiteOptions::where('option_name', '=', 'metal')
                ->where('option_value', 'like', '%' .$searchValue . '%')
               ->select('*')
               ->orderBy($columnName,$columnSortOrder)
               ->skip($start)
               ->take($rowperpage)
               ->get();
         $data_arr = array();
         foreach($records as $rkey => $record){
            $id = $record->id;
            $option_value = $record->option_value;
           $description = $record->description;
          $sno = $start + $rkey + 1;

            $data_arr[] = array(
                "id" => $sno,
                "option_value" => $option_value,
                "description" => $description,
                "link" => '<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="'.route('metal.edit', ['id'=>$id, 'lang'=>env('DEFAULT_LANGUAGE')] ).'">
                    <i class="las la-edit"></i>
                </a>
                <a href="'.route('soption.destroy', $id).'" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="'.route('soption.destroy', $id).'">
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
     public function metalCreate(Request $request)
     {
       return view("backend.site_options.metal.create");
     }
     public function saveMetal(Request $Request)
     {
       $Request->validate([
         'option_value' => [
           'required',Rule::unique('site_options')->where(function($query) use ($Request) {
               return $query->where('option_name', $Request->option_name);
                 })
               ],
       ]);
         $post = new SiteOptions();
         $post->option_name = $Request->option_name;
         $post->option_value = $Request->option_value;
         $post->description = $Request->description;
         $post->save();
         flash(translate('Metal has been added successfully',))->success();
         return view("backend.site_options.metal.index");
     }
     public function metaledit(Request $request, $id)
     {
         $partners = SiteOptions::findOrFail($id);
         return view('backend.site_options.metal.edit', compact('partners'));
     }
     public function metalUpdate(Request $request, $id)
     {
       $partnersUpdate = SiteOptions::findOrFail($id);
       $partnersUpdate->option_value = $request->option_value;
       $partnersUpdate->description = $request->description;
       $partnersUpdate->save();
       flash(translate('Metal has been updated successfully'))->success();
       return view("backend.site_options.metal.index");
     }
     // Metal End
     // Model Start
      public function model(Request $request)
      {
         $partnersNewData = SiteOptions::where('option_name', '=', 'model');
         if ($request->has('search')){
             $sort_search = $request->search;
             $pData = $partnersNewData->where('option_value', 'like', '%'.$sort_search.'%');
         }
         $data = $partnersNewData->paginate(10);
         $partnersData = $partnersNewData->get();
          return view("backend.site_options.model.index", compact('partnersData','data'));
      }
      // ajax search Start
      public function getAllmodel(Request $request){
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
          $totalRecords = SiteOptions::select('count(*) as allcount')->where('option_name', '=', 'model')->count();
          $totalRecordswithFilter = SiteOptions::select('count(*) as allcount')->where('option_name', '=', 'model')->where('option_value', 'like', '%' .$searchValue . '%')->count();
          // Fetch records
          $records = SiteOptions::where('option_name', '=', 'model')
                 ->where('option_value', 'like', '%' .$searchValue . '%')
                ->select('*')
                ->orderBy($columnName,$columnSortOrder)
                ->skip($start)
                ->take($rowperpage)
                ->get();
          $data_arr = array();
          foreach($records as $rkey => $record){
             $id = $record->id;
             $option_value = $record->option_value;
             $description = $record->description;
             $low_stock = $record->low_stock;
            $sno = $start + $rkey + 1;
             $data_arr[] = array(
                 "id" => $sno,
                 "option_value" => $option_value,
                 "description" => $description,
                 "low_stock" => $low_stock,
                 "link" => '<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="'.route('model.edit', ['id'=>$id, 'lang'=>env('DEFAULT_LANGUAGE')] ).'">
                     <i class="las la-edit"></i>
                 </a>
                 <a href="'.route('soption.destroy', $id).'" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="'.route('soption.destroy', $id).'">
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
      public function modelCreate()
      {
        return view("backend.site_options.model.create");
      }
      public function saveModel(Request $Request)
      {
        $Request->validate([
          'option_value' => [
            'required',Rule::unique('site_options')->where(function($query) use ($Request) {
                return $query->where('option_name', $Request->option_name);
                  })
                ],
        ]);
          $post = new SiteOptions();
          $post->option_name = $Request->option_name;
          $post->option_value = $Request->option_value;
          $post->description = $Request->description;
          $post->low_stock = $Request->low_stock;
          $post->save();
          flash(translate('Model has been added successfully',))->success();
          return view("backend.site_options.model.index");
      }
      public function modeledit(Request $request, $id)
      {
          $partners = SiteOptions::findOrFail($id);
          return view('backend.site_options.model.edit', compact('partners'));
     }
      public function modelUpdate(Request $request, $id)
      {
        $partnersUpdate = SiteOptions::findOrFail($id);
        $partnersUpdate->option_value = $request->option_value;
        $partnersUpdate->description = $request->description;
        $partnersUpdate->low_stock = $request->low_stock;
        $partnersUpdate->save();
        flash(translate('Model has been updated successfully'))->success();
        return view("backend.site_options.model.index");
      }
      // Model End
      // Size Start
       public function size(Request $request)
       {
          $partnersNewData = SiteOptions::where('option_name', '=', 'size');
          if ($request->has('search')){
              $sort_search = $request->search;
              $pData = $partnersNewData->where('option_value', 'like', '%'.$sort_search.'%');
          }
          $data = $partnersNewData->paginate(10);
          $partnersData = $partnersNewData->get();
           return view("backend.site_options.size.index", compact('partnersData','data'));
       }
       // ajax search Start
       public function getAllsize(Request $request){
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
           $totalRecords = SiteOptions::select('count(*) as allcount')->where('option_name', '=', 'size')->count();
           $totalRecordswithFilter = SiteOptions::select('count(*) as allcount')->where('option_name', '=', 'size')->where('option_value', 'like', '%' .$searchValue . '%')->count();
           // Fetch records
           $records = SiteOptions::where('option_name', '=', 'size')
                  ->where('option_value', 'like', '%' .$searchValue . '%')
                 ->select('*')
                 ->orderBy($columnName,$columnSortOrder)
                 ->skip($start)
                 ->take($rowperpage)
                 ->get();
           $data_arr = array();
           foreach($records as $rkey => $record){
              $id = $record->id;
              $option_value = $record->option_value;
              $description = $record->description;
              $email = $record->email;
             $sno = $start + $rkey + 1;

              $data_arr[] = array(
                  "id" => $sno,
                  "option_value" => $option_value,
                  "description" => $description,
                  "link" => '<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="'.route('size.edit', ['id'=>$id, 'lang'=>env('DEFAULT_LANGUAGE')] ).'">
                      <i class="las la-edit"></i>
                  </a>
                  <a href="'.route('soption.destroy', $id).'" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="'.route('soption.destroy', $id).'">
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
       public function sizeCreate()
       {
         return view("backend.site_options.size.create");
       }
       public function saveSize(Request $Request)
       {
         $Request->validate([
           'option_value' => [
             'required',Rule::unique('site_options')->where(function($query) use ($Request) {
                 return $query->where('option_name', $Request->option_name);
                   })
                 ],
         ]);
           $post = new SiteOptions();
           $post->option_name = $Request->option_name;
           $post->option_value = $Request->option_value;
           $post->description = $Request->description;
           $post->save();
           flash(translate('Size has been added successfully'))->success();
           return view("backend.site_options.size.index");
       }
       public function sizeedit(Request $request, $id)
       {
           $partners = SiteOptions::findOrFail($id);
           return view('backend.site_options.size.edit', compact('partners'));
       }
       public function sizeUpdate(Request $request, $id)
       {
         $partnersUpdate = SiteOptions::findOrFail($id);
         $partnersUpdate->option_value = $request->option_value;
         $partnersUpdate->description = $request->description;
         $partnersUpdate->save();
         flash(translate('Size has been updated successfully'))->success();
         return view("backend.site_options.size.index");
       }
       // Size End
       // Unit Start
        public function unit(Request $request)
        {
           $partnersNewData = SiteOptions::where('option_name', '=', 'unit');
           if ($request->has('search')){
               $sort_search = $request->search;
               $pData = $partnersNewData->where('option_value', 'like', '%'.$sort_search.'%');
           }
           $data = $partnersNewData->paginate(10);
           $partnersData = $partnersNewData->get();
            return view("backend.site_options.unit.index", compact('partnersData','data'));
        }
        // ajax search Start
        public function getAllunit(Request $request){
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
            $totalRecords = SiteOptions::select('count(*) as allcount')->where('option_name', '=', 'unit')->count();
            $totalRecordswithFilter = SiteOptions::select('count(*) as allcount')->where('option_name', '=', 'unit')->where('option_value', 'like', '%' .$searchValue . '%')->count();
            // Fetch records
            $records = SiteOptions::where('option_name', '=', 'unit')
                   ->where('option_value', 'like', '%' .$searchValue . '%')
                  ->select('*')
                  ->orderBy($columnName,$columnSortOrder)
                  ->skip($start)
                  ->take($rowperpage)
                  ->get();
            $data_arr = array();
            foreach($records as $rkey => $record){
               $id = $record->id;
               $option_value = $record->option_value;
               $description = $record->description;
               $email = $record->email;
               $sno = $start + $rkey + 1;

               $data_arr[] = array(
                   "id" => $sno,
                   "option_value" => $option_value,
                   "description" => $description,
                   "link" => '<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="'.route('unit.edit', ['id'=>$id, 'lang'=>env('DEFAULT_LANGUAGE')] ).'">
                       <i class="las la-edit"></i>
                   </a>
                   <a href="'.route('soption.destroy', $id).'" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="'.route('soption.destroy', $id).'">
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
       public function unitCreate()
        {
          return view("backend.site_options.unit.create");
        }
        public function saveUnit(Request $Request)
        {
          $Request->validate([
            'option_value' => [
              'required',Rule::unique('site_options')->where(function($query) use ($Request) {
                  return $query->where('option_name', $Request->option_name);
                    })
                  ],
          ]);
           $post = new SiteOptions();
            $post->option_name = $Request->option_name;
            $post->option_value = $Request->option_value;
            $post->description = $Request->description;
            $post->save();
            flash(translate('Unit has been added successfully'))->success();
            return view("backend.site_options.unit.index");
        }
        public function unitedit(Request $request, $id)
        {
            $partners = SiteOptions::findOrFail($id);
            return view('backend.site_options.unit.edit', compact('partners'));
        }
        public function unitUpdate(Request $request, $id)
        {
          $partnersUpdate = SiteOptions::findOrFail($id);
          $partnersUpdate->option_value = $request->option_value;
          $partnersUpdate->description = $request->description;
          $partnersUpdate->save();
          flash(translate('Unit has been updated successfully'))->success();
          return view("backend.site_options.unit.index");
        }
        // Unit End
               // Listing type Start
                public function listingtype(Request $request)
                {
                 $pagination_qty = isset($request->purchases_pagi)?$request->purchases_pagi:25;
                    if($request->input('purchases_pagi')!=NULL){
                    $product_qty =  ($request->input('purchases_pagi'));
                    }
                   $sort_search = null;
                   $PurchasesProduct= SiteOptions::where('option_name', '=', 'listingtype')->orderBy('id','DESC');
                   $product_qty=$request->purchases_pagi;
                   if ($request->search != null){
                                   $PurchasesProduct->where('option_value', 'like', '%'.$request->search.'%');
                                
           
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
                 $partnersData=$PurchasesProduct->get();

                    return view("backend.site_options.listingtype.index", compact('partnersData','sort_search','pagination_qty','purchases'));

                }

                public function listingtypeCreate()

                {

                  return view("backend.site_options.listingtype.create");

                }



                public function saveListingtype(Request $Request)

                {

                  $Request->validate([

                    'option_value' => [

                      'required',Rule::unique('site_options')->where(function($query) use ($Request) {

                          return $query->where('option_name', $Request->option_name);

                            })

                          ],

                  ]);

                    $post = new SiteOptions();

                    $post->option_name = $Request->option_name;

                    $post->option_value = $Request->option_value;

                    $post->save();

                    flash(translate('Listing type has been added successfully'))->success();

                    return back();

                }



                public function listingtypeedit(Request $request, $id)

                {

                    $partners = SiteOptions::findOrFail($id);

                    return view('backend.site_options.listingtype.edit', compact('partners'));

                }



                public function listingtypeUpdate(Request $request, $id)

                {

                  $partnersUpdate = SiteOptions::findOrFail($id);

                  $partnersUpdate->option_value = $request->option_value;



                  $partnersUpdate->save();



                  flash(translate('Listing type has been updated successfully'))->success();

                  return back();

                }

                // Listing type End





    public function optionDestroy($id)

    {

        $post = SiteOptions::where('id',$id)->delete();

        flash(translate('Option has been deleted successfully'))->success();

        return back();



    }







}

