<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Brand;

use App\BrandTranslation;

use App\Product;

use Illuminate\Support\Str;



class BrandController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index(Request $request)

    {

        $sort_search =null;

        $brands = Brand::orderBy('name', 'asc');

        if ($request->has('search')){

            $sort_search = $request->search;

            $brands = $brands->where('name', 'like', '%'.$sort_search.'%');

        }

        $brands = $brands->paginate(10);

        return view('backend.product.brands.index', compact('brands', 'sort_search'));

    }



    // ajax search Start

    public function getAllbrand(Request $request){



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

        $totalRecords = Brand::select('count(*) as allcount')->count();

        $totalRecordswithFilter = Brand::select('count(*) as allcount')->where('name', 'like', '%' .$searchValue . '%')->count();



        // Fetch records

        $records = Brand::orderBy($columnName,$columnSortOrder)

               ->where('name', 'like', '%' .$searchValue . '%')

              ->select('*')

              ->skip($start)

              ->take($rowperpage)

              ->get();



        $data_arr = array();



        foreach($records as $rkey => $record){

           $id = $record->id;

           $name = $record->getTranslation('name');

           $logo = uploaded_asset($record->logo);

           $description = $record->meta_description;

            $sno = $start + $rkey + 1;




           $data_arr[] = array(

               "id" => $sno,

               "name" => $name,

               "logo" => '<img src="'.$logo.'" class="h-50px">',

               "description" => $description,

               "link" => '<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="'.route('brands.edit', ['id'=>$id, 'lang'=>env('DEFAULT_LANGUAGE')] ).'">

                   <i class="las la-edit"></i>

               </a>

               <a href="'.route('brands.destroy', $id).'" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="'.route('brands.destroy', $id).'">

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





    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)

    {

      $request->validate([

        'name' => 'required|unique:brands'

      ]);

        $brand = new Brand;

        $brand->name = $request->name;

        $brand->meta_title = $request->meta_title;

        $brand->meta_description = $request->meta_description;

        if ($request->slug != null) {

            $brand->slug = str_replace(' ', '-', $request->slug);

        }

        else {

            $brand->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);

        }



        $brand->logo = $request->logo;

        $brand->save();



        $brand_translation = BrandTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'brand_id' => $brand->id]);

        $brand_translation->name = $request->name;

        $brand_translation->save();



        flash(translate('Brand has been inserted successfully'))->success();

        return redirect()->route('brands.index');



    }



    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function show($id)

    {

        //

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function edit(Request $request, $id)

    {

        $lang   = $request->lang;

        $brand  = Brand::findOrFail($id);

        return view('backend.product.brands.edit', compact('brand','lang'));

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, $id)

    {

        $brand = Brand::findOrFail($id);

        if($request->lang == env("DEFAULT_LANGUAGE")){

            $brand->name = $request->name;

        }

        $brand->meta_title = $request->meta_title;

        $brand->meta_description = $request->meta_description;

        if ($request->slug != null) {

            $brand->slug = strtolower($request->slug);

        }

        else {

            $brand->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);

        }

        $brand->logo = $request->logo;

        $brand->save();



        $brand_translation = BrandTranslation::firstOrNew(['lang' => $request->lang, 'brand_id' => $brand->id]);

        $brand_translation->name = $request->name;

        $brand_translation->save();



        flash(translate('Brand has been updated successfully'))->success();

        return redirect()->route('brands.index');



    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function destroy($id)

    {

        $brand = Brand::findOrFail($id);

        Product::where('brand_id', $brand->id)->delete();

        foreach ($brand->brand_translations as $key => $brand_translation) {

            $brand_translation->delete();

        }

        Brand::destroy($id);



        flash(translate('Brand has been deleted successfully'))->success();

        return redirect()->route('brands.index');



    }

    public function productBrandAjax(Request $request)

    {

      // Add Unit...

       $brand = new Brand;

       $brand->name = $request->name;

       $brand->meta_title = $request->meta_title;

       $brand->meta_description = $request->meta_description;

       if ($request->slug != null) {

           $brand->slug = str_replace(' ', '-', $request->slug);

       }

       else {

           $brand->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);

       }



       $brand->logo = $request->logo;

       $brand->save();



       $brand_translation = BrandTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'brand_id' => $brand->id]);

       $brand_translation->name = $request->name;

       $brand_translation->save();

       $tagsOpt =  $request->name;

       $tagHtmls =  "<option value='".$tagsOpt."' selected>".$tagsOpt."</option>";

       $brandOptions = Brand::all();

       $brandOptionHtml = "";

       foreach ($brandOptions as $brand) {

         $brand_id =$brand->id;

         $brand_name =$brand->getTranslation('name');

         $brandOptionHtml .= "<option value='".$brand_id."'>$brand_name</option>";

       }

       return response()->json(['success' => true,'brandHTML'=>$brandOptionHtml,'tagHtmlData'=>$tagHtmls]);

    }

}

