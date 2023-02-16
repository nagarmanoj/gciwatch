<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Category;

use App\Product;

use App\CategoryTranslation;

use App\Utility\CategoryUtility;

use Illuminate\Support\Str;

use Cache;



class CategoryController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index(Request $request)

    {

        $sort_search =null;

        $categories = Category::orderBy('id', 'desc');

        if ($request->has('search')){

            $sort_search = $request->search;

            $categories = $categories->where('name', 'like', '%'.$sort_search.'%');

        }

        $categories = $categories->paginate(10);

        return view('backend.product.categories.index', compact('categories', 'sort_search'));

    }





        // ajax search Start

        public function getAllcategory(Request $request){



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

            $totalRecords = Category::select('count(*) as allcount')->count();

            $totalRecordswithFilter = Category::select('count(*) as allcount')->where('name', 'like', '%' .$searchValue . '%')->count();



            // Fetch records

            $records = Category::orderBy($columnName,$columnSortOrder)

                   ->where('name', 'like', '%' .$searchValue . '%')

                  ->select('*')

                  ->skip($start)

                  ->take($rowperpage)

                  ->get();



            $data_arr = array();



            foreach($records as $rkey => $record){

               $id = $record->id;

               $name = $record->getTranslation('name');

               $description = $record->meta_description;
                 $sno = $start + $rkey + 1;



               $data_arr[] = array(

                   "id" => $sno,

                   "name" => $name,

                   "description" => $description,

                   "link" => '<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="'.route('categories.edit', ['id'=>$id, 'lang'=>env('DEFAULT_LANGUAGE')] ).'">

                       <i class="las la-edit"></i>

                   </a>

                   <a href="'.route('categories.destroy',['id'=>$id]).'" onclick="return confirm("Are you sure");" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="'.route('categories.destroy',['id'=>$id]).'">

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

        $categories = Category::where('parent_id', 0)

            ->with('childrenCategories')

            ->get();

        return view('backend.product.categories.create', compact('categories'));

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

        'name' => 'required|unique:categories'

      ]);

        $category = new Category;

        $category->name = $request->name;

        $category->order_level = 0;

        if($request->order_level != null) {

            $category->order_level = $request->order_level;

        }

        $category->digital = $request->digital;

        $category->banner = $request->banner;

        $category->icon = $request->icon;

        $category->meta_title = $request->meta_title;

        $category->meta_description = $request->meta_description;



        if ($request->parent_id != "0") {

            $category->parent_id = $request->parent_id;



            $parent = Category::find($request->parent_id);

            $category->level = $parent->level + 1 ;

        }



        if ($request->slug != null) {

            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));

        }

        else {

            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);

        }

        if ($request->commision_rate != null) {

            $category->commision_rate = $request->commision_rate;

        }



        $category->save();



        $category->attributes()->sync($request->filtering_attributes);



        $category_translation = CategoryTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'category_id' => $category->id]);

        $category_translation->name = $request->name;

        $category_translation->save();



        flash(translate('Category has been inserted successfully'))->success();

        return redirect()->route('categories.index');

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

        $lang = $request->lang;

        $category = Category::findOrFail($id);

        $categories = Category::where('parent_id', 0)

            ->with('childrenCategories')

            ->whereNotIn('id', CategoryUtility::children_ids($category->id, true))->where('id', '!=' , $category->id)

            ->orderBy('name','asc')

            ->get();



        return view('backend.product.categories.edit', compact('category', 'categories', 'lang'));

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

        $category = Category::findOrFail($id);

        if($request->lang == env("DEFAULT_LANGUAGE")){

            $category->name = $request->name;

        }

        if($request->order_level != null) {

            $category->order_level = $request->order_level;

        }

        $category->digital = $request->digital;

        $category->banner = $request->banner;

        $category->icon = $request->icon;

        $category->meta_title = $request->meta_title;

        $category->meta_description = $request->meta_description;



        $previous_level = $category->level;



        if ($request->parent_id != "0") {

            $category->parent_id = $request->parent_id;



            $parent = Category::find($request->parent_id);

            $category->level = $parent->level + 1 ;

        }

        else{

            $category->parent_id = 0;

            $category->level = 0;

        }



        if($category->level > $previous_level){

            CategoryUtility::move_level_down($category->id);

        }

        elseif ($category->level < $previous_level) {

            CategoryUtility::move_level_up($category->id);

        }



        if ($request->slug != null) {

            $category->slug = strtolower($request->slug);

        }

        else {

            $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);

        }





        if ($request->commision_rate != null) {

            $category->commision_rate = $request->commision_rate;

        }



        $category->save();



        $category->attributes()->sync($request->filtering_attributes);



        $category_translation = CategoryTranslation::firstOrNew(['lang' => $request->lang, 'category_id' => $category->id]);

        $category_translation->name = $request->name;

        $category_translation->save();



        Cache::forget('featured_categories');

        flash(translate('Category has been updated successfully'))->success();

        return redirect()->route('categories.index');

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function destroy($id)

    {

        $category = Category::findOrFail($id);

        $category->attributes()->detach();



        // Category Translations Delete

        foreach ($category->category_translations as $key => $category_translation) {

            $category_translation->delete();

        }



        foreach (Product::where('category_id', $category->id)->get() as $product) {

            $product->category_id = null;

            $product->save();

        }



        CategoryUtility::delete_category($id);

        Cache::forget('featured_categories');



        flash(translate('Category has been deleted successfully'))->success();

        return redirect()->route('categories.index');

    }



    public function updateFeatured(Request $request)

    {

        $category = Category::findOrFail($request->id);

        $category->featured = $request->status;

        $category->save();

        Cache::forget('featured_categories');

        return 1;

    }





        public function productCategoryAjax(Request $request)

        {

          // Add Unit...

          $category = new Category;

          $category->name = $request->name;

          $category->order_level = 0;

          if($request->order_level != null) {

              $category->order_level = $request->order_level;

          }

          $category->digital = $request->digital;

          $category->banner = $request->banner;

          $category->icon = $request->icon;

          $category->meta_title = $request->meta_title;

          $category->meta_description = $request->meta_description;



        //   if ($request->parent_id != "0") {

        //       $category->parent_id = $request->parent_id;



        //       $parent = Category::find($request->parent_id);

        //       $category->level = $parent->level + 1 ;

        //   }



          if ($request->slug != null) {

              $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));

          }

          else {

              $category->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);

          }

          if ($request->commision_rate != null) {

              $category->commision_rate = $request->commision_rate;

          }



          $category->save();



          $category->attributes()->sync($request->filtering_attributes);



          $category_translation = CategoryTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'category_id' => $category->id]);

          $category_translation->name = $request->name;

          $category_translation->save();

          $tagsOpt =  $request->name;

          $tagHtmls =  "<option value='".$tagsOpt."' selected>".$tagsOpt."</option>";

          $categories = Category::where('parent_id', 0)

              ->with('childrenCategories')

              ->get();

          $categoryHtml = "";

          foreach ($categories as $category) {

            $categroy_id =$category->id;

            $categroy_name =$category->getTranslation('name');

            $categoryHtml .= "<option value='".$categroy_id."'>$categroy_name</option>";

          }

           return response()->json(['success' => true,'catHTML'=>$categoryHtml,'tagHtmlCat'=>$tagHtmls]);

        }

}

