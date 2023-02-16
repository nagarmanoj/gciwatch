<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Product;
use App\Memo;
use App\User;
use App\Productcondition;
use App\MemoDetail;
use App\Upload;
use App\JobOrder;
use App\ProductsExport;
use App\Models\Producttype;
use App\ProductTranslation;
use App\ReturnProd;
use App\Transfer;
use App\TransferItem;
use App\ProductStock;
use App\Models\Sequence;
use App\Models\Warehouse;
use App\Category;
use App\Tag;
use App\Activitylog;
use App\SiteOptions;
use App\FlashDealProduct;
use App\ProductTax;
use App\Brand;
use App\BarcodeProductExcel;
use Excel;
use App\AttributeValue;
use App\Cart;
use App\FilteredColumns;
use Auth;
use Carbon\Carbon;
use Combinations;
use Mail;
use App\Mail\EmailManager;
use CoreComponentRepository;
use Illuminate\Support\Str;
use Artisan;
use Cache;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function admin_products(Request $request)

    {

      $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;

         if($request->input('pagination_qty')!=NULL){

            $product_qty =  ($request->input('pagination_qty'));

        }

        CoreComponentRepository::instantiateShopRepository();

        $col_name = null;

        $query = null;

        $seller_id = null;

        $sort_search = null;

        $allFilteredCOll = array(

            'brand' => array('name' => 'Brand'),

            'cost_code' => 'Cost Code',

            'productcondition' =>array('name' => 'Condition'),

            'product_cost' => 'Cost',

            'category' =>array('name' => 'Category'),

            'dop' => 'DOP',

            'thumbnail_img' => 'Image',

            'productType' =>array('listing_type' => 'Listing Name'),

            'model' => 'Model Number',

            'msrp' => 'MSRP',

            'metal' => 'Metal',

            'name' => 'Name',

            'productType' =>array('product_type_name' => 'Product Type'),

            'paper_cart' => 'Paper/Cert',

            'description' => 'Product Details',

            'partner' => 'Partner',

            'qty' => 'Quantity',

            'stock_id' => 'Stock ID',

            'unit_price' => 'Sale Price',

            'user_name' => 'Source',

            'sku' =>'Serial',

            'size' => 'Size',

            'unit' => 'Unit',

            'vendor_doc_number' => 'Vendor Doc Number',

            'weight' => 'Weight',

            'warehouse_name' => 'Warehouse',

        );

        // dd($allFilteredCOll);

        $detailedProduct  = Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop','category','productcondition','productType')

        ->leftJoin('warehouse','warehouse.id','=','products.warehouse_id')

        ->leftJoin('users','users.id','=','products.supplier_id')

        ->join('site_options','site_options.option_value','=','products.model')

        ->orderBy('products.id', 'DESC')

        ->select('products.*','warehouse.name as warehouse_name','users.name as user_name','site_options.low_stock');

        // dd($detailedProduct);

        $products = Product::select('products.*')

                ->join('product_types', 'product_types.id', '=', 'products.product_type_id');

        // dd($detailedProduct);

        if ($request->has('user_id') && $request->user_id != null) {

            $products = $products->where('user_id', $request->user_id);

            $seller_id = $request->user_id;

        }

        if ($request->search != null){

            $products = $detailedProduct

                        ->where('products.name', 'like', '%'.$request->search.'%');

            $sort_search = $request->search;

        }

        if ($request->type != null){

            $var = explode(",", $request->type);

            $col_name = $var[0];

            $query = $var[1];

            $products = $products->orderBy($col_name, $query);

            $sort_type = $request->type;

        }

        $proAvailability =$request->availability;

        if($proAvailability != null)

        {

         $products = $detailedProduct->where('published', '=', '1');

        }

        if($proAvailability != null)

        {

         $products = $detailedProduct->where('product_stocks.qty','>=',1);

        }

        $proSearchType =$request->product_type;



        if($proSearchType != null)

        {

         $detailedProduct = $detailedProduct->where('product_type_id', 'LIKE', '%'.$proSearchType.'%');

        }

        $warehouseSrch =$request->warehouse_id;



        if($warehouseSrch > 0)

        {

            $products = $detailedProduct->where('warehouse_id', $warehouseSrch);

        }



        if(isset($product_qty) && $product_qty!='All'){

            if($product_qty!='All'){

                $products = $detailedProduct->paginate(($product_qty));

        }

        elseif(isset($product_qty) && $product_qty=='All'){

            $products = $detailedProduct;

        }

        }else{

         $products = $detailedProduct->paginate(25);

        }



         $type = 'All';

         $FilteredData = FilteredColumns::get_table_model('product');

         if(empty($FilteredData)){

           $FilteredData = $allFilteredCOll;

           $FilteredDefault = "";

         }else{

           $FilteredDefault = FilteredColumns::default_table_model('product');

         }

         $columnSelArr =  array_keys($FilteredData);



           if(isset($product_qty) && $product_qty=='All'){

          $detailedProduct = $detailedProduct->get();

           }

           else{

              $detailedProduct = $detailedProduct->get();

           }
        return view('backend.product.products.index', compact('pagination_qty','products','type', 'col_name', 'query', 'seller_id', 'sort_search','detailedProduct','FilteredData','allFilteredCOll','columnSelArr','FilteredDefault','proAvailability'));

    }



    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function seller_products(Request $request)

    {

        $col_name = null;

        $query = null;

        $seller_id = null;

        $sort_search = null;

        $products = Product::where('added_by', 'seller')->where('auction_product',0);

        if ($request->has('user_id') && $request->user_id != null) {

            $products = $products->where('user_id', $request->user_id);

            $seller_id = $request->user_id;

        }

        if ($request->search != null){

            $products = $products

                        ->where('name', 'like', '%'.$request->search.'%');

            $sort_search = $request->search;

        }

        if ($request->type != null){

            $var = explode(",", $request->type);

            $col_name = $var[0];

            $query = $var[1];

            $products = $products->orderBy($col_name, $query);

            $sort_type = $request->type;

        }

        $products="";

        $products = $products->where('digital', 0)->orderBy('created_at', 'desc')->paginate(10);

        $type = 'Seller';

        // dd($products);



        return view('backend.product.products.index', compact('products','type', 'col_name', 'query', 'seller_id', 'sort_search'));

    }



    public function all_products(Request $request)

    {

        $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
        if($request->input('pagination_qty')!=NULL){
            $pagination_qty =  $request->input('pagination_qty');
        }



        $col_name = null;

        $query = null;

        $seller_id = null;

        $sort_search = null;

        //product_stocks.qty , product_stocks.product_id

        //product_types.product_type_name , product_types.sequence_id

        //warehouse.name



        $databaseField = array(
            'productType' => 'product_types.product_type_name',
            'stock_id' => 'stock_id',
            'productcondition' => 'productconditions.name',
            'brand' => 'brands.name',
            'model' => 'model',
            'qty' => 'qty',
            'sku' => 'sku',
            'paper_cart' => 'paper_cart',
            'category'=> 'categories.name',
            'size' => 'size',
            'metal' => 'metal',
            'weight' => 'weight',
            'description' => 'products.description',
            'partner' => 'partner',
            'warehouse_name' => 'warehouse.name',
            'name'=> 'products.name',
            'user_name' => 'users.name',
            'low_stock' => 'low_stock'
        );

        $searchFieldArr = array_keys($databaseField);

        $allFilteredCOll = array(

          'thumbnail_img' => 'Image',

         // 'productType' =>array('listing_type' => 'Listing Name'),

          'productType' =>array('product_type_name' => 'Product Type'),

          'stock_id' => 'Stock ID',

          'productcondition' =>array('name' => 'Condition'),

          'brand' => array('name' => 'Brand'),

          'model' => 'Model Number',

          'qty' => 'Quantity',

          'sku' =>'Serial',

          'paper_cart' => 'Paper/Cert',

          'category' =>array('name' => 'Category'),

          'size' => 'Size',

          'metal' => 'Metal',

          'weight' => 'Weight',

          'description' => 'Product Details',

          'partner' => 'Partner',

          'warehouse_name' => 'Warehouse',

          'unit' => 'Unit',

          'product_cost' => 'Product Cost',

          'cost_code' => 'Cost Code',

          'unit_price' => 'Sale Price',

          'msrp' => 'MSRP',

          'dop' => 'DOP',

          'name' => 'Name',

          'user_name' => 'Source',



        );

        $allCustomArr = array();

        $ptypeCustom = Producttype::all();

        foreach ($ptypeCustom as $pTykey => $pTyvalue) {

          for ($i=1; $i<11 ; $i++) {

            $cKey = 'custom_'.$i;

            $cName = isset($pTyvalue->$cKey) ? $pTyvalue->$cKey : "";

            if($cName != ""){

              $allCustomArr[$pTyvalue->product_type_name][$cKey] = $cName;

            }

          }

        }


        $searchCol = $request->searchCol;


        $lowstock=Product::select('products.*','site_options.low_stock as product_low_stock')

                            ->join('site_options','site_options.option_value','=','products.model')->get();

        $productQry  = Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop','category','productcondition','productType')

        ->leftJoin('warehouse','warehouse.id','=','products.warehouse_id')

        ->leftJoin('users','users.id','=','products.supplier_id')

        ->leftJoin('product_types','product_types.id','=','products.product_type_id')

        ->leftJoin('brands','brands.id','=','products.brand_id')

        ->leftJoin('product_stocks','products.id','=','product_stocks.product_id')

        ->leftJoin('site_options','site_options.option_value','=','products.model')

        ->leftJoin('productconditions','productconditions.id','=','products.productcondition_id')

        ->leftJoin('categories','categories.id','=','products.category_id')

        ->orderBy('products.id', 'DESC')

        ->groupBy('products.id')

        ->select('products.*','warehouse.name as warehouse_name','users.name as user_name','site_options.low_stock');

        if ($request->has('user_id') && $request->user_id != null) {

            $seller_id = $request->user_id;

        }


        $proAvailability =$request->availability;


        if($proAvailability != null)

        {

            $productQry = $productQry->where('product_stocks.qty','>=',1);

        }

        if($proAvailability != null)

        {

            $productQry = $productQry->where('published', '=', '1');

        }

        $proSearchType =$request->listing_type;



        if($proSearchType != null)

        {

            $productQry = $productQry->where('product_types.listing_type', 'LIKE', '%'.$proSearchType.'%');

        }

        $warehouseSrch =$request->warehouse_id;



        if($warehouseSrch > 0)

        {

         $productQry = $productQry->where('warehouse_id', $warehouseSrch);

        }

        if(!empty($searchCol)){

            foreach($searchCol as $searchKey => $searchField){

                if($searchField !=""){
                    $productQry = $productQry->where($searchKey, 'LIKE', '%'.$searchField.'%');
                }
            }
        }

        if ($request->search != null){

            $sort_search = $request->search;

            $productQry = $productQry

                        ->orWhere('products.name', 'like', '%'.$sort_search.'%')

                        ->orWhere('products.stock_id', 'like', '%'.$sort_search.'%')

                        ->orWhere('product_stocks.sku', 'like', '%'.$sort_search.'%')

                        ->orWhere('products.model', 'like', '%'.$sort_search.'%')

                        ->orWhere('products.metal', 'like', '%'.$sort_search.'%')

                        ->orWhere('brands.name', 'like', '%'.$sort_search.'%')

                        ->orWhere('product_types.listing_type', 'like', '%'.$sort_search.'%')
                        ->orWhere('productconditions.name', 'like', '%'.$sort_search.'%')
                        ->orWhere('categories.name', 'like', '%'.$sort_search.'%')
                        ->orWhere('products.size', 'like', '%'.$sort_search.'%')
                        ->orWhere('products.weight', 'like', '%'.$sort_search.'%')

                        ->orWhere('products.custom_1', 'like', '%'.$sort_search.'%')
                        ->orWhere('products.custom_2', 'like', '%'.$sort_search.'%')
                        ->orWhere('products.custom_3', 'like', '%'.$sort_search.'%')
                        ->orWhere('products.custom_4', 'like', '%'.$sort_search.'%')
                        ->orWhere('products.custom_5', 'like', '%'.$sort_search.'%')
                        ->orWhere('products.custom_6', 'like', '%'.$sort_search.'%')
                        ->orWhere('products.custom_7', 'like', '%'.$sort_search.'%')
                        ->orWhere('products.custom_8', 'like', '%'.$sort_search.'%')
                        ->orWhere('products.custom_9', 'like', '%'.$sort_search.'%')
                        ->orWhere('products.custom_10', 'like', '%'.$sort_search.'%');



        }



         $type = 'all';

         $FilteredData = FilteredColumns::get_table_model('product');

         if(empty($FilteredData)){

           $FilteredData = $allFilteredCOll;

           $FilteredDefault = "";

         }else{

           $FilteredDefault = FilteredColumns::default_table_model('product');

         }

         $columnSelArr =  array_keys($FilteredData);


         // $detailedProductList = $productQry->paginate($pagination_qty);
         if( $request->pagination_qty == "all"){
           $detailedProductList = $productQry->get();
         }else{
           $detailedProductList = $productQry->paginate($pagination_qty);
         }


        return view('backend.product.products.index', compact('allCustomArr','pagination_qty','databaseField','searchCol','type', 'col_name', 'seller_id', 'sort_search','detailedProductList','FilteredData','allFilteredCOll','columnSelArr','FilteredDefault','proAvailability','lowstock','searchFieldArr'));

    }





    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

        CoreComponentRepository::initializeCache();



        $categories = Category::where('parent_id', 0)

            ->where('digital', 0)

            ->with('childrenCategories')

            ->get();

            $detail['countries_detail'] = DB::table('countries')->get();

            $TagOptions = Tag::all();

            $Tag_name = array();

            foreach ($TagOptions as $Tag) {

              $Tag_name[] =$Tag->tags;

            }

            $tagAllData = Product::getalltagsname();

            // dd($tagCarData)



        return view('backend.product.products.create',$detail, compact('categories','Tag_name','tagAllData'));

    }



    public function add_more_choice_option(Request $request) {

        $all_attribute_values = AttributeValue::with('attribute')->where('attribute_id', $request->attribute_id)->get();



        $html = '';



        foreach ($all_attribute_values as $row) {

            $html .= '<option value="' . $row->value . '">' . $row->value . '</option>';

        }



        echo json_encode($html);

    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)

    {

      $pTypeData = Producttype::findOrFail($request->product_type_id);
      $sku = isset($request->sku) ? $request->sku : "";

        $array['subject'] = 'Login Failed';
        $array['userIp'] = $request->ip();
        if(!empty($pTypeData)){
          $array['product_type_name'] = $pTypeData->product_type_name;
          $array['stock_id'] = $request->stock_id;
          $csField="";
          $csFieldVal="";
          for ($i=1; $i < 11; $i++) {
            $csField = "custom_".$i;
            $csFieldVal = "custom_".$i."_val";
            $array[$csField] = $pTypeData->$csField;
            $array[$csFieldVal] = $request->$csField;
          }
        }
        if(!empty($request->productcondition_id)){
          $PtyCondition = Productcondition::findOrFail($request->productcondition_id);
          $array['condition'] = $PtyCondition->name;
        }
        $array['model'] = $request->model;
        $array['metal'] = $request->metal;
        $array['sku'] = $request->sku;
        $array['paper_cart'] = $request->paper_cart;
        $array['cost_code'] = $request->cost_code;
        $array['unit_price'] = $request->unit_price;
        $array['from'] = env('MAIL_FROM_ADDRESS');
        Mail::send('frontend.allmails.addproductmail', $array, function($message) use ($array) {
            $message->to(env("StockManager"));
            $message->subject('User Activity Notification');
        });

        if(!empty($pTypeData)){

          $snNo = $pTypeData->serial_no;

          $validationArray = array(

            'stock_id' => 'required|unique:products'

          );

              if($snNo == "No"){

                if($sku != ""){

                    $validationArray['sku'] = 'unique:product_stocks';

                }

              }else{

                $validationArray['sku'] = 'required|unique:product_stocks';

              }

              $request->validate($validationArray);

        }



        $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();



        $product = new Product;

        $product->name = $request->name;

        $product->product_type_id = $request->product_type_id;

        $product->stock_id = $request->stock_id;

        $product->added_by = $request->added_by;

        if(Auth::user()->user_type == 'seller'){

            $product->user_id = Auth::user()->id;

            if(get_setting('product_approve_by_admin') == 1) {

                $product->approved = 0;

            }

        }

        else{

            $product->user_id = \App\User::where('user_type', 'admin')->first()->id;

        }

        $product->category_id = $request->category_id;

        $product->brand_id = $request->brand_id;

        $product->warehouse_id = $request->warehouse_id;

        $product->barcode = $request->barcode;



        if ($refund_request_addon != null && $refund_request_addon->activated == 1) {

            if ($request->refundable != null) {

                $product->refundable = 1;

            }

            else {

                $product->refundable = 0;

            }

        }

        $product->photos = $request->photos;

        $thumbnail_img =$request->photos;

        $thumb_id = 0 ;

        if($thumbnail_img != ""){

          $thumbArr = explode(',',$thumbnail_img);

          $thumb_id = isset($thumbArr[0]) ? $thumbArr[0] : "";

        }

        if($thumb_id > 0 ){

          $product->thumbnail_img = $thumb_id;

        }

        // echo $request->model; exit();

        $product->unit = $request->unit;

        $product->product_cost = $request->product_cost;

        $product->cost_code = $request->cost_code;

        $product->partner = $request->partner;

        $product->metal = $request->metal;

        $product->model = $request->model;

        $product->weight = $request->weight;

        $product->size = $request->size;

        $product->productcondition_id = $request->productcondition_id;

        $product->vendor_doc_number = $request->vendor_doc_number;

        $product->supplier_id = $request->supplier_id;

        $product->dop = $request->dop;

        $product->custom_1 = $request->custom_1;

        $product->custom_2 = $request->custom_2;

        $product->custom_3 = $request->custom_3;

        $product->custom_4 = $request->custom_4;

        $product->custom_5 = $request->custom_5;

        $product->custom_6 = $request->custom_6;

        $product->custom_7 = $request->custom_7;

        $product->custom_8 = $request->custom_8;

        $product->custom_9 = $request->custom_9;

        $product->custom_10 = $request->custom_10;

        $product->msrp = $request->msrp;

        $product->min_qty = $request->min_qty;

        $product->low_stock_quantity = $request->low_stock_quantity;

        $product->stock_visibility_state = $request->stock_visibility_state;

        // $product->external_link = $request->external_link;

        // $product->google_link = $request->google_link;

        $tags = $request->tags;

        if(!empty($tags)){

          $tags =serialize($tags);

        }

        $product->tags   = $tags;



        // $tags = array();

        // if($request->tags[0] != null){

        //     foreach (json_decode($request->tags[0]) as $key => $tag) {

        //         array_push($tags, $tag->value);

        //     }

        // }

        // $product->tags = implode(',', $tags);



        $product->description = $request->description;

        $product->video_provider = $request->video_provider;

        $product->video_link = $request->video_link;

        $product->unit_price = $request->unit_price;

        // $product->unit_cost = $request->unit_cost;

        $product->paper_cart = $request->paper_cart;

        $product->discount = $request->discount;

        $product->discount_type = $request->discount_type;



        if ($request->date_range != null) {

            $date_var               = explode(" to ", $request->date_range);

            $product->discount_start_date = strtotime($date_var[0]);

            $product->discount_end_date   = strtotime( $date_var[1]);

        }



        $product->shipping_type = $request->shipping_type;

        $product->est_shipping_days  = $request->est_shipping_days;



        if (\App\Addon::where('unique_identifier', 'club_point')->first() != null &&

                \App\Addon::where('unique_identifier', 'club_point')->first()->activated) {

            if($request->earn_point) {

                $product->earn_point = $request->earn_point;

            }

        }



        if ($request->has('shipping_type')) {

            if($request->shipping_type == 'free'){

                $product->shipping_cost = 0;

            }

            elseif ($request->shipping_type == 'flat_rate') {

                $product->shipping_cost = $request->flat_shipping_cost;

            }

            elseif ($request->shipping_type == 'product_wise') {

                $product->shipping_cost = json_encode($request->shipping_cost);

            }

        }

        if ($request->has('is_quantity_multiplied')) {

            $product->is_quantity_multiplied = 1;

        }



        $product->meta_title = $request->meta_title;

        $product->meta_description = $request->meta_description;



        if($request->has('meta_img')){

            $product->meta_img = $request->meta_img;

        } else {

            $product->meta_img = $product->thumbnail_img;

        }



        if($product->meta_title == null) {

            $product->meta_title = $product->name;

        }



        if($product->meta_description == null) {

            $product->meta_description = strip_tags($product->description);

        }



        if($product->meta_img == null) {

            $product->meta_img = $product->thumbnail_img;

        }



        if($request->hasFile('pdf')){

            $product->pdf = $request->pdf->store('uploads/products/pdf');

        }



        $product->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', strtolower($request->stock_id)));



        if(Product::where('slug', $product->slug)->count() > 0){

            flash(translate('Another product exists with same slug. Please change the slug!'))->warning();

            return back();

        }



        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){

            $product->colors = json_encode($request->colors);

        }

        else {

            $colors = array();

            $product->colors = json_encode($colors);

        }



        $choice_options = array();



        if($request->has('choice_no')){

            foreach ($request->choice_no as $key => $no) {

                $str = 'choice_options_'.$no;



                $item['attribute_id'] = $no;



                $data = array();

                // foreach (json_decode($request[$str][0]) as $key => $eachValue) {

                foreach ($request[$str] as $key => $eachValue) {

                    // array_push($data, $eachValue->value);

                    array_push($data, $eachValue);

                }



                $item['values'] = $data;

                array_push($choice_options, $item);

            }

        }



        if (!empty($request->choice_no)) {

            $product->attributes = json_encode($request->choice_no);

        }

        else {

            $product->attributes = json_encode(array());

        }



        $product->choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);

        // if($request->current_stock <= 0){
        //   $product->published = 0;
        // }
        if ($request->has('published')) {

            $product->published = 0;

        }


        if ($request->has('cash_on_delivery')) {

            $product->cash_on_delivery = 1;

        }

        if ($request->has('featured')) {

            $product->featured = 1;

        }

        if ($request->has('todays_deal')) {

            $product->todays_deal = 1;

        }

        $product->cash_on_delivery = 1;

        // $product->published = 0;

        // if ($request->published) {

        //     $product->published = 0;

        // }

        if ($request->cash_on_delivery) {

            $product->cash_on_delivery = 1;

        }

        //$variations = array();



        $product->save();

        $seqId = isset($request->seqId) ? $request->seqId : 0;

        if($seqId > 0){

          $sequence = Sequence::findOrFail($seqId);

          $sequence->sequence_count = $sequence->sequence_count+1;

          $sequence->sequence_start = $sequence->sequence_start+1;

          $sequence->save();

        }







        //VAT & Tax

        if($request->tax_id) {

            foreach ($request->tax_id as $key => $val) {

                $product_tax = new ProductTax;

                $product_tax->tax_id = $val;

                $product_tax->product_id = $product->id;

                $product_tax->tax = $request->tax[$key];

                $product_tax->tax_type = $request->tax_type[$key];

                $product_tax->save();

            }

        }

        //Flash Deal

        if($request->flash_deal_id) {

            $flash_deal_product = new FlashDealProduct;

            $flash_deal_product->flash_deal_id = $request->flash_deal_id;

            $flash_deal_product->product_id = $product->id;

            $flash_deal_product->save();

        }



        //combinations start

        $options = array();

        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {

            $colors_active = 1;

            array_push($options, $request->colors);

        }



        if($request->has('choice_no')){

            foreach ($request->choice_no as $key => $no) {

                $name = 'choice_options_'.$no;

                $data = array();

                foreach ($request[$name] as $key => $eachValue) {

                    array_push($data, $eachValue);

                }

                array_push($options, $data);

            }

        }



        //Generates the combinations of customer choice options

        $combinations = Combinations::makeCombinations($options);

        if(count($combinations[0]) > 0){

            $product->variant_product = 1;

            foreach ($combinations as $key => $combination){

                $str = '';

                foreach ($combination as $key => $item){

                    if($key > 0 ){

                        $str .= '-'.str_replace(' ', '', $item);

                    }

                    else{

                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){

                            $color_name = \App\Color::where('code', $item)->first()->name;

                            $str .= $color_name;

                        }

                        else{

                            $str .= str_replace(' ', '', $item);

                        }

                    }

                }

                $product_stock = ProductStock::where('product_id', $product->id)->where('variant', $str)->first();

                if($product_stock == null){

                    $product_stock = new ProductStock;

                    $product_stock->product_id = $product->id;

                }

                $product_stock->variant = $str;

                $product_stock->price = $request['price_'.str_replace('.', '_', $str)];

                $product_stock->sku = $request['sku_'.str_replace('.', '_', $str)];

                $product_stock->qty = $request['qty_'.str_replace('.', '_', $str)];

                $product_stock->image = $request['img_'.str_replace('.', '_', $str)];

                $product_stock->save();

            }

        }

        else{

            $product_stock              = new ProductStock;

            $product_stock->product_id  = $product->id;

            $product_stock->variant     = '';

            $product_stock->price       = $request->unit_price;

            $product_stock->sku         = $request->sku;

            $product_stock->qty         = $request->current_stock;

            $product_stock->save();

        }

        //combinations end



	    $product->save();



        // Product Translations

        $product_translation = ProductTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'product_id' => $product->id]);

        $product_translation->name = $request->name;

        $product_translation->unit = $request->unit;

        $product_translation->description = $request->description;

        $product_translation->save();



        flash(translate('Product has been inserted successfully'))->success();



        // Artisan::call('view:clear');
        //
        // Artisan::call('cache:clear');



        if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff'){

            return redirect()->route('products.all');

        }

        else{

            if(addon_is_activated('seller_subscription')){

                $seller = Auth::user()->seller;

                $seller->remaining_uploads -= 1;

                $seller->save();

            }

            // return redirect()->route('seller.products');

        }

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

     public function admin_product_edit(Request $request, $id)

     {



        CoreComponentRepository::initializeCache();



        $product = Product::findOrFail($id);

        // return $product;exit;

        if($product->digital == 1) {

            return redirect('digitalproducts/' . $id . '/edit');

        }



        $lang = $request->lang;

        $tags = unserialize($product->tags);

        if(!(is_array($tags))){

          $tags = array();

        }
        // print_r($tags);
        // exit;

        $categories = Category::where('parent_id', 0)

            ->where('digital', 0)

            ->with('childrenCategories')

            ->get();





        $sma_product_type = Producttype::findOrFail($product->product_type_id);

        // dd($sma_product_type);



        $sma_productseqData = Sequence::findOrFail($sma_product_type->sequence_id);

        $tagAllData = Product::getalltagsname();

        return view('backend.product.products.edit', compact('product','tagAllData','categories', 'tags','lang','sma_product_type','sma_productseqData'));

     }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function seller_product_edit(Request $request, $id)

    {

        $product = Product::findOrFail($id);

        return $product;exit;

        if($product->digital == 1) {

            return redirect('digitalproducts/' . $id . '/edit');

        }

        $lang = $request->lang;

        $tags = json_decode($product->tags);

        $categories = Category::all();

        return view('backend.product.products.edit', compact('product', 'categories', 'tags','lang'));

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

        $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();

        $product= Product::findOrFail($id);
        $user = Auth::user();
        $curr_uid = $user->id;
        $curr_name = $user->name;
        $oldData = $product;

        // if($request->name != "" && !empty($request->name)){
          if($request->name != $oldData->name){
            $old_name = $oldData->name;
            $proLog = new Activitylog();
            $proLog->type = 'product';
            $proLog->user_id = $curr_uid;
            $proLog->prodcut_id = $id;
            $proLog->activity = addslashes('STOCK ID '.$request->stock_id.' Such as Name was changed from '.$old_name.' to '.$request->name.' by '.$curr_name.' on');
            $proLog->action = 'edited';
            $proLog->save();
          }
        // }


        // if($request->productcondition_id != "" && !empty($request->productcondition_id)){
          if($request->productcondition_id != $oldData->productcondition_id){
            $old_productcondition_id = $oldData->productcondition_id;
            $proLog = new Activitylog();
            $proLog->type = 'product';
            $proLog->user_id = $curr_uid;
            $proLog->prodcut_id = $id;
              $old_p_data=Productcondition::findOrFail($oldData->productcondition_id);
              $new_p_data=Productcondition::findOrFail($request->productcondition_id);
            $proLog->activity = addslashes('STOCK ID '.$request->stock_id.' Such as Condition was changed from '.$old_p_data->name.' to  '.$new_p_data->name.' by '.$curr_name.' on');
            $proLog->action = 'edited';
            $proLog->save();
          }
        // }


        // if($request->brand_id != "" && !empty($request->brand_id)){
          if($request->brand_id != $oldData->brand_id){
            $old_brand_id = $oldData->brand_id;
            $proLog = new Activitylog();
            $proLog->type = 'product';
            $proLog->user_id = $curr_uid;
            $proLog->prodcut_id = $id;
              $new_brand_name=Brand::findOrFail($request->brand_id);
              $old_brand_name=Brand::findOrFail($oldData->brand_id);
              $new_name=$new_brand_name->name;
              $old_name=$old_brand_name->name;
            $proLog->activity = addslashes('STOCK ID '.$request->stock_id.' Such as Brand was changed from '.$old_name.' to '.$new_name.' by '.$curr_name.' on');
            $proLog->action = 'edited';
            $proLog->save();
          }
        // }

        // if($request->model != "" && !empty($request->model)){
        if($request->model != $oldData->model){
          $old_model = $oldData->model;
          $proLog = new Activitylog();
          $proLog->type = 'product';
          $proLog->user_id = $curr_uid;
          $proLog->prodcut_id = $id;
          $proLog->activity = addslashes('STOCK ID '.$request->stock_id.' Such as Model was changed from '.$old_model.' to '.$request->model.' by '.$curr_name.' on');
          $proLog->action = 'edited';
          $proLog->save();
        }
      // }

        // if($request->category_id != "" && !empty($request->category_id)){
          if($request->category_id != $oldData->category_id){
            $old_category_id = $oldData->category_id;
            $proLog = new Activitylog();
            $proLog->type = 'product';
            $proLog->user_id = $curr_uid;
            $proLog->prodcut_id = $id;
            $old_category=Category::findOrFail($oldData->category_id);
            $new_category=Category::findOrFail($request->category_id);
            $old_c_name=$old_category->name;
            $new_c_name=$new_category->name;
            $proLog->activity = addslashes('STOCK ID '.$request->stock_id.' Such as Category was changed from '.$old_c_name.' to  '.$new_c_name.' by '.$curr_name.' on');
            $proLog->action = 'edited';
            $proLog->save();
          }
        // }


        // if($request->size != "" && !empty($request->size)){
          if($request->size != $oldData->size){
            $old_size = $oldData->size;
            $proLog = new Activitylog();
            $proLog->type = 'product';
            $proLog->user_id = $curr_uid;
            $proLog->prodcut_id = $id;
            $old_data=Product::where('size',$oldData->size)->first();
            $new_data=Product::where('size',$request->size)->first();
            $old_size=$old_data->size;
            $new_siz=$new_data->size;
            $proLog->activity = addslashes('STOCK ID '.$request->stock_id.' Such as Size was changed from '.$old_size.' to  '.$new_siz.' by '.$curr_name.' on');
            $proLog->action = 'edited';
            $proLog->save();
          }
        // }

        // if($request->metal != "" && !empty($request->metal)){
          if($request->metal != $oldData->metal){
            $old_metal = $oldData->metal;
            $proLog = new Activitylog();
            $proLog->type = 'product';
            $proLog->user_id = $curr_uid;
            $proLog->prodcut_id = $id;
            $proLog->activity = addslashes('STOCK ID '.$request->stock_id.' Such as Metal was changed from '.$old_metal.' to  '.$request->metal.' by '.$curr_name.' on');
            $proLog->action = 'edited';
            $proLog->save();
          }
        // }


        // if($request->weight != "" && !empty($request->weight)){
          if($request->weight != $oldData->weight){
            $old_weight = $oldData->weight;
            $proLog = new Activitylog();
            $proLog->type = 'product';
            $proLog->user_id = $curr_uid;
            $proLog->prodcut_id = $id;
            $proLog->activity = addslashes('STOCK ID '.$request->stock_id.' Such as Weight was changed from '.$old_weight.' to  '.$request->weight.' by '.$curr_name.' on');
            $proLog->action = 'edited';
            $proLog->save();
          }
        // }

        // if($request->partner != "" && !empty($request->partner)){
          if($request->partner != $oldData->partner){
            $old_partner = $oldData->partner;
            $proLog = new Activitylog();
            $proLog->type = 'product';
            $proLog->user_id = $curr_uid;
            $proLog->prodcut_id = $id;
            $proLog->activity = addslashes('STOCK ID '.$request->stock_id.' Such as Partner was changed from '.$old_partner.' to  '.$request->partner.' by '.$curr_name.' on');
            $proLog->action = 'edited';
            $proLog->save();
          }
        // }


        // if($request->metal != "" && !empty($request->metal)){
          if($request->metal != $oldData->metal){
            $old_metal = $oldData->metal;
            $proLog = new Activitylog();
            $proLog->type = 'product';
            $proLog->user_id = $curr_uid;
            $proLog->prodcut_id = $id;
            $proLog->activity = addslashes('STOCK ID '.$request->stock_id.' Such as Metal was changed from '.$old_metal.' to  '.$request->metal.' by '.$curr_name.' on');
            $proLog->action = 'edited';
            $proLog->save();
          }
        // }


        // if($request->warehouse_id != "" && !empty($request->warehouse_id)){
          if($request->warehouse_id != $oldData->warehouse_id){
            $old_warehouse_id = $oldData->warehouse_id;
            $proLog = new Activitylog();
            $proLog->type = 'product';
            $proLog->user_id = $curr_uid;
            $proLog->prodcut_id = $id;
            $old_warehouse=Warehouse::findOrFail($oldData->warehouse_id);
            $new_warehouse=Warehouse::findOrFail($request->warehouse_id);
            $proLog->activity = addslashes('STOCK ID '.$request->stock_id.' Such as Warehouse was changed from '.$old_warehouse->name.' to  '.$new_warehouse->name.' by '.$curr_name.' on');
            $proLog->action = 'edited';
            $proLog->save();
          }
        // }


        // if($request->product_cost != "" && !empty($request->product_cost)){
          if($request->product_cost != $oldData->product_cost){
            $old_product_cost = $oldData->product_cost;
            $proLog = new Activitylog();
            $proLog->type = 'product';
            $proLog->user_id = $curr_uid;
            $proLog->prodcut_id = $id;
            $proLog->activity = addslashes('STOCK ID '.$request->stock_id.' Such as Product Cost was changed from '.$old_product_cost.' to  '.$request->product_cost.' by '.$curr_name.' on');
            $proLog->action = 'edited';
            $proLog->save();
          }
        // }


        // if($request->cost_code != "" && !empty($request->cost_code)){
          if($request->cost_code != $oldData->cost_code){
            $old_cost_code = $oldData->cost_code;
            $proLog = new Activitylog();
            $proLog->type = 'product';
            $proLog->user_id = $curr_uid;
            $proLog->prodcut_id = $id;
            $proLog->activity = addslashes('STOCK ID '.$request->stock_id.' Such as Cost Code was changed from '.$old_cost_code.' to  '.$request->cost_code.' by '.$curr_name.' on');
            $proLog->action = 'edited';
            $proLog->save();
          }
        // }

        // if($request->unit_price != "" && !empty($request->unit_price)){
          if($request->unit_price != $oldData->unit_price){
            $old_unit_price = $oldData->unit_price;
            $proLog = new Activitylog();
            $proLog->type = 'product';
            $proLog->user_id = $curr_uid;
            $proLog->prodcut_id = $id;
            $proLog->activity = addslashes('STOCK ID '.$request->stock_id.' Such as Sale Price was changed from '.$old_unit_price.' to  '.$request->unit_price.' by '.$curr_name.' on');
            $proLog->action = 'edited';
            $proLog->save();
          }
        // }

        // if($request->msrp != "" && !empty($request->msrp)){
          if($request->msrp != $oldData->msrp){
            $old_msrp = $oldData->msrp;
            $proLog = new Activitylog();
            $proLog->type = 'product';
            $proLog->user_id = $curr_uid;
            $proLog->prodcut_id = $id;
            $proLog->activity = addslashes('STOCK ID '.$request->stock_id.' Such as MSRP was changed from '.$old_msrp.' to  '.$request->msrp.' by '.$curr_name.' on');
            $proLog->action = 'edited';
            $proLog->save();
          }
        // }

        // if($request->vendor_doc_number != "" && !empty($request->vendor_doc_number)){
          if($request->vendor_doc_number != $oldData->vendor_doc_number){
            $old_vendor_doc_number = $oldData->vendor_doc_number;
            $proLog = new Activitylog();
            $proLog->type = 'product';
            $proLog->user_id = $curr_uid;
            $proLog->prodcut_id = $id;
            $proLog->activity = addslashes('STOCK ID '.$request->stock_id.' Such as MSRP was changed from '.$old_vendor_doc_number.' to  '.$request->vendor_doc_number.' by '.$curr_name.' on');
            $proLog->action = 'edited';
            $proLog->save();
          }
        // }

        // if($request->paper_cart != "" && !empty($request->paper_cart)){
          if($request->paper_cart != $oldData->paper_cart){
            $old_paper_cart = $oldData->paper_cart;
            $proLog = new Activitylog();
            $proLog->type = 'product';
            $proLog->user_id = $curr_uid;
            $proLog->prodcut_id = $id;
            $proLog->activity = addslashes('STOCK ID '.$request->stock_id.' Such as paper-cert was changed from '.$old_paper_cart.' to  '.$request->paper_cart.' by '.$curr_name.' on');
            $proLog->action = 'edited';
            $proLog->save();
          }
        // }

        // if($request->description != "" && !empty($request->description)){
          if($request->description != $oldData->description){
            $old_description = $oldData->description;
            $proLog = new Activitylog();
            $proLog->type = 'product';
            $proLog->user_id = $curr_uid;
            $proLog->prodcut_id = $id;
            $proLog->activity = addslashes('STOCK ID '.$request->stock_id.' Such as Description was changed from '.$old_description.' to  '.$request->description.' by '.$curr_name.' on');
            $proLog->action = 'edited';
            $proLog->save();
          }
        // }


        if($request->supplier_id != "" && !empty($request->supplier_id)){
          $req_sup_data = User::where('id',$request->supplier_id)->first();
          $req_sup_name = $req_sup_data->name;
          $old_sup_data = User::where('id',$oldData->supplier_id)->first();
          $old_sup_name = $old_sup_data->name;
          if($req_sup_name != $old_sup_name){
            $proLog = new Activitylog();
            $proLog->type = 'product';
            $proLog->user_id = $curr_uid;
            $proLog->prodcut_id = $id;
            $proLog->activity = addslashes('STOCK ID '.$request->stock_id.' Such as Supplier was changed from '.$old_sup_name.' to  '.$req_sup_name.' by '.$curr_name.' on');
            $proLog->action = 'edited';
            $proLog->save();
          }
        }

        $product_type_id = Producttype::where('id',$request->product_type_id)->first();
        for ($i=1; $i < 11; $i++) {
            $customValPro = 'custom_'.$i;
                if($request->$customValPro != $oldData->$customValPro){
                  $old_customValPro = $oldData->$customValPro;
                  $product_type_name = $product_type_id->$customValPro;
                  $proLog = new Activitylog();
                  $proLog->type = 'product';
                  $proLog->user_id = $curr_uid;
                  $proLog->prodcut_id = $id;
                  $proLog->activity = addslashes('STOCK ID '.$request->stock_id.' Such as '.$product_type_name.' was changed from '.$old_customValPro.' to  '.$request->$customValPro.' by '.$curr_name.' on');
                  $proLog->action = 'edited';
                  $proLog->save();
                }
        }



        $product->category_id       = $request->category_id;

        $product->brand_id          = $request->brand_id;

        $product->warehouse_id      = $request->warehouse_id;

        $product->barcode           = $request->barcode;

        $product->cash_on_delivery = 0;

        $product->featured = 0;

        $product->todays_deal = 0;

        $product->is_quantity_multiplied = 0;



        if ($refund_request_addon != null && $refund_request_addon->activated == 1) {

            if ($request->refundable != null) {

                $product->refundable = 1;

            }

            else {

                $product->refundable = 0;

            }

        }



        if($request->lang == env("DEFAULT_LANGUAGE")){

            $product->name          = $request->name;

            $product->product_type_id  = $request->product_type_id;

            $product->stock_id = $request->stock_id;

            $product->unit          = $request->unit;

            $product->product_cost = $request->product_cost;

            $product->cost_code = $request->cost_code;

            $product->paper_cart = $request->paper_cart;

            $product->partner       = $request->partner;

            $product->metal         = $request->metal;

            $product->model         = $request->model;

            $product->weight        = $request->weight;

            $product->size          = $request->size;

            $product->productcondition_id = $request->productcondition_id;

            $product->vendor_doc_number = $request->vendor_doc_number;

            $product->supplier_id = $request->supplier_id;

            $product->dop           = $request->dop;

            $product->custom_1      = $request->custom_1;

            $product->custom_2      = $request->custom_2;

            $product->custom_3      = $request->custom_3;

            $product->custom_4      = $request->custom_4;

            $product->custom_5      = $request->custom_5;

            $product->custom_6      = $request->custom_6;

            $product->custom_7      = $request->custom_7;

            $product->custom_8      = $request->custom_8;

            $product->custom_9      = $request->custom_9;

            $product->custom_10     = $request->custom_10;

            $product->msrp = $request->msrp;

            $product->description   = $request->description;

            $product->slug          = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', strtolower($request->slug)));

        }



        if($request->slug == null){

            $product->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', strtolower($request->name)));

        }



        if(Product::where('id', '!=', $product->id)->where('slug', $product->slug)->count() > 0){

            flash(translate('Another product exists with same slug. Please change the slug!'))->warning();

            return back();

        }



        $product->photos                 = $request->photos;

        $thumbnail_img =$request->photos;

        $thumb_id = 0 ;

        if($thumbnail_img != ""){

          $thumbArr = explode(',',$thumbnail_img);

          $thumb_id = isset($thumbArr[0]) ? $thumbArr[0] : "";

        }

        if($thumb_id > 0 ){

          $product->thumbnail_img = $thumb_id;

        }

        $product->min_qty                = $request->min_qty;

        $product->low_stock_quantity     = $request->low_stock_quantity;

        $product->stock_visibility_state = $request->stock_visibility_state;

        // $product->external_link = $request->external_link;

        // $product->google_link = $request->google_link;

        $tags = $request->tags;

        if(!empty($tags)){

          $tags =serialize($tags);

        }

        $product->tags   = $tags;

        // $tags = array();

        // if($request->tags[0] != null){

        //     foreach (json_decode($request->tags[0]) as $key => $tag) {

        //         array_push($tags, $tag->value);

        //     }

        // }

        // $product->tags           = implode(',', $tags);



        $product->video_provider = $request->video_provider;

        $product->video_link     = $request->video_link;

        $product->unit_price     = $request->unit_price;

        $product->discount       = $request->discount;

        $product->discount_type     = $request->discount_type;



        if ($request->date_range != null) {

            $date_var               = explode(" to ", $request->date_range);

            $product->discount_start_date = strtotime($date_var[0]);

            $product->discount_end_date   = strtotime( $date_var[1]);

        }



        $product->shipping_type  = $request->shipping_type;

        $product->est_shipping_days  = $request->est_shipping_days;



        if (\App\Addon::where('unique_identifier', 'club_point')->first() != null &&

                \App\Addon::where('unique_identifier', 'club_point')->first()->activated) {

            if($request->earn_point) {

                $product->earn_point = $request->earn_point;

            }

        }



        if ($request->has('shipping_type')) {

            if($request->shipping_type == 'free'){

                $product->shipping_cost = 0;

            }

            elseif ($request->shipping_type == 'flat_rate') {

                $product->shipping_cost = $request->flat_shipping_cost;

            }

            elseif ($request->shipping_type == 'product_wise') {

                $product->shipping_cost = json_encode($request->shipping_cost);

            }

        }



        if ($request->has('is_quantity_multiplied')) {

            $product->is_quantity_multiplied = 1;

        }

        if ($request->has('cash_on_delivery')) {

            $product->cash_on_delivery = 1;

        }


        if ($request->has('published')) {

            $product->published = 0;

        }else{

            $product->published = 1;

        }

        // if($request->current_stock <= 0){
        //
        //   $product->published = 0;
        //
        // }



        if ($request->has('featured')) {

            $product->featured = 1;

        }



        if ($request->has('todays_deal')) {

            $product->todays_deal = 1;

        }





        $product->meta_title        = $request->meta_title;

        $product->meta_description  = $request->meta_description;

        $product->meta_img          = $request->meta_img;



        if($product->meta_title == null) {

            $product->meta_title = $product->name;

        }



        if($product->meta_description == null) {

            $product->meta_description = strip_tags($product->description);

        }



        if($product->meta_img == null) {

            $product->meta_img = $product->thumbnail_img;

        }



        $product->pdf = $request->pdf;



        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){

            $product->colors = json_encode($request->colors);

        }

        else {

            $colors = array();

            $product->colors = json_encode($colors);

        }



        $choice_options = array();



        if($request->has('choice_no')){

            foreach ($request->choice_no as $key => $no) {

                $str = 'choice_options_'.$no;



                $item['attribute_id'] = $no;



                $data = array();

                foreach ($request[$str] as $key => $eachValue) {

                    array_push($data, $eachValue);

                }



                $item['values'] = $data;

                array_push($choice_options, $item);

            }

        }



        foreach ($product->stocks as $key => $stock) {

            $stock->delete();

        }



        if (!empty($request->choice_no)) {

            $product->attributes = json_encode($request->choice_no);

        }

        else {

            $product->attributes = json_encode(array());

        }



        $product->choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);





        //combinations start

        $options = array();

        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){

            $colors_active = 1;

            array_push($options, $request->colors);

        }



        if($request->has('choice_no')){

            foreach ($request->choice_no as $key => $no) {

                $name = 'choice_options_'.$no;

                $data = array();

                foreach ($request[$name] as $key => $item) {

                    array_push($data, $item);

                }

                array_push($options, $data);

            }

        }



        $combinations = Combinations::makeCombinations($options);

        if(count($combinations[0]) > 0){

            $product->variant_product = 1;

            foreach ($combinations as $key => $combination){

                $str = '';

                foreach ($combination as $key => $item){

                    if($key > 0 ){

                        $str .= '-'.str_replace(' ', '', $item);

                    }

                    else{

                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){

                            $color_name = \App\Color::where('code', $item)->first()->name;

                            $str .= $color_name;

                        }

                        else{

                            $str .= str_replace(' ', '', $item);

                        }

                    }

                }



                $product_stock = ProductStock::where('product_id', $product->id)->where('variant', $str)->first();

                if($product_stock == null){

                    $product_stock = new ProductStock;

                    $product_stock->product_id = $product->id;

                }

                if(isset($request['price_'.str_replace('.', '_', $str)])) {



                    $product_stock->variant = $str;

                    $product_stock->price = $request['price_'.str_replace('.', '_', $str)];

                    $product_stock->sku = $request['sku_'.str_replace('.', '_', $str)];

                    $product_stock->qty = $request['qty_'.str_replace('.', '_', $str)];

                    $product_stock->image = $request['img_'.str_replace('.', '_', $str)];



                    $product_stock->save();

                }

            }

        }

        else{

            $product_stock              = new ProductStock;

            $product_stock->product_id  = $product->id;

            $product_stock->variant     = '';

            $product_stock->price       = $request->unit_price;

            $product_stock->sku         = $request->sku;

            $product_stock->qty         = $request->current_stock;

            $product_stock->save();

        }



        $product->save();

        $seqId = isset($request->seqId) ? $request->seqId : 0;

        if($seqId > 0){

          $sequence = Sequence::findOrFail($seqId);

          $sequence->sequence_count = $sequence->sequence_count+1;

          $sequence->sequence_start = $sequence->sequence_start+1;

          $sequence->save();

        }



        //Flash Deal

        if($request->flash_deal_id) {

            if($product->flash_deal_product){

                $flash_deal_product = FlashDealProduct::findOrFail($product->flash_deal_product->id);

                if(!$flash_deal_product) {

                    $flash_deal_product = new FlashDealProduct;

                }

            } else {

                $flash_deal_product = new FlashDealProduct;

            }



            $flash_deal_product->flash_deal_id = $request->flash_deal_id;

            $flash_deal_product->product_id = $product->id;

            $flash_deal_product->discount = $request->flash_discount;

            $flash_deal_product->discount_type = $request->flash_discount_type;

            $flash_deal_product->save();

//            dd($flash_deal_product);

        }



        //VAT & Tax

        if($request->tax_id) {

            ProductTax::where('product_id', $product->id)->delete();

            foreach ($request->tax_id as $key => $val) {

                $product_tax = new ProductTax;

                $product_tax->tax_id = $val;

                $product_tax->product_id = $product->id;

                $product_tax->tax = $request->tax[$key];

                $product_tax->tax_type = $request->tax_type[$key];

                $product_tax->save();

            }

        }



        // Product Translations

        $product_translation                = ProductTranslation::firstOrNew(['lang' => $request->lang, 'product_id' => $product->id]);

        $product_translation->name          = $request->name;

        $product_translation->unit          = $request->unit;

        $product_translation->description   = $request->description;

        $product_translation->save();





        flash(translate('Product has been updated successfully'))->success();



        // Artisan::call('view:clear');
        //
        // Artisan::call('cache:clear');


        return redirect()->route('products.all');
        // return back();

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function destroy($id)

    {

        $product = Product::findOrFail($id);

        foreach ($product->product_translations as $key => $product_translations) {

            $product_translations->delete();

        }



        foreach ($product->stocks as $key => $stock) {

            $stock->delete();

        }



        if(Product::destroy($id)){

            Cart::where('product_id', $id)->delete();



            flash(translate('Product has been deleted successfully'))->success();


            //
            // Artisan::call('view:clear');
            //
            // Artisan::call('cache:clear');


            return redirect()->route('products.all');
            // return back();

        }

        else{

            flash(translate('Something went wrong'))->error();

            // return back();
            return redirect()->route('products.all');

        }

    }



    public function bulk_product_delete(Request $request) {

        $ids = $request->checked_id;

        $proID = json_decode($ids, TRUE);

        // dd($proID);

        if($proID) {

            foreach ($proID as $product_id) {

                $this->destroy($product_id);

            }

        }

        flash(translate('Product has been deleted successfully'))->success();

        return back();

    }



    /**

     * Duplicates the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function duplicate(Request $request, $id)

    {

        $product = Product::find($id);



        if(Auth::user()->id == $product->user_id || Auth::user()->user_type == 'staff'){

            $product_new = $product->replicate();

            $product_new->slug = $product_new->slug.'-'.Str::random(5);

            $product_new->save();



            foreach ($product->stocks as $key => $stock) {

                $product_stock              = new ProductStock;

                $product_stock->product_id  = $product_new->id;

                $product_stock->variant     = $stock->variant;

                $product_stock->price       = $stock->price;

                $product_stock->sku         = $stock->sku;

                $product_stock->qty         = $stock->qty;

                $product_stock->save();



            }



            flash(translate('Product has been duplicated successfully'))->success();

            if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff'){

              if($request->type == 'In House')

                return redirect()->route('products.admin');

              elseif($request->type == 'Seller')

                return redirect()->route('products.seller');

              elseif($request->type == 'All')

                return redirect()->route('products.all');

            }

            else{

                if (addon_is_activated('seller_subscription')) {

                    $seller = Auth::user()->seller;

                    $seller->remaining_uploads -= 1;

                    $seller->save();

                }

                return redirect()->route('seller.products');

            }

        }

        else{

            flash(translate('Something went wrong'))->error();

            return back();

        }

    }



    public function get_products_by_brand(Request $request)

    {

        $products = Product::where('brand_id', $request->brand_id)->get();

        return view('partials.product_select', compact('products'));

    }



    public function updateTodaysDeal(Request $request)

    {

        $product = Product::findOrFail($request->id);

        $product->todays_deal = $request->status;

        $product->save();

        Cache::forget('todays_deal_products');

        return 1;

    }



    public function updatePublished(Request $request)

    {

        $product = Product::findOrFail($request->id);

        $product->published = $request->status;



        if($product->added_by == 'seller' && addon_is_activated('seller_subscription')){

            $seller = $product->user->seller;

            if($seller->invalid_at != null && Carbon::now()->diffInDays(Carbon::parse($seller->invalid_at), false) <= 0){

                return 0;

            }

        }



        $product->save();

        return 1;

    }



    public function updateProductApproval(Request $request)

    {

        $product = Product::findOrFail($request->id);

        $product->approved = $request->approved;



        if($product->added_by == 'seller' && addon_is_activated('seller_subscription')){

            $seller = $product->user->seller;

            if($seller->invalid_at != null && Carbon::now()->diffInDays(Carbon::parse($seller->invalid_at), false) <= 0){

                return 0;

            }

        }



        $product->save();

        return 1;

    }



    public function updateFeatured(Request $request)

    {

        $product = Product::findOrFail($request->id);

        $product->featured = $request->status;

        if($product->save()){

            // Artisan::call('view:clear');
            //
            // Artisan::call('cache:clear');

            return 1;

        }

        return 0;

    }



    public function updateSellerFeatured(Request $request)

    {

        $product = Product::findOrFail($request->id);

        $product->seller_featured = $request->status;

        if($product->save()){

            return 1;

        }

        return 0;

    }



    public function sku_combination(Request $request)

    {

        $options = array();

        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){

            $colors_active = 1;

            array_push($options, $request->colors);

        }

        else {

            $colors_active = 0;

        }



        $unit_price = $request->unit_price;

        $product_name = $request->name;



        if($request->has('choice_no')){

            foreach ($request->choice_no as $key => $no) {

                $name = 'choice_options_'.$no;

                $data = array();

                // foreach (json_decode($request[$name][0]) as $key => $item) {

                foreach ($request[$name] as $key => $item) {

                    // array_push($data, $item->value);

                    array_push($data, $item);

                }

                array_push($options, $data);

            }

        }



        $combinations = Combinations::makeCombinations($options);

        return view('backend.product.products.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));

    }



    public function sku_combination_edit(Request $request)

    {

        $product = Product::findOrFail($request->id);



        $options = array();

        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){

            $colors_active = 1;

            array_push($options, $request->colors);

        }

        else {

            $colors_active = 0;

        }



        $product_name = $request->name;

        $unit_price = $request->unit_price;



        if($request->has('choice_no')){

            foreach ($request->choice_no as $key => $no) {

                $name = 'choice_options_'.$no;

                $data = array();

                // foreach (json_decode($request[$name][0]) as $key => $item) {

                foreach ($request[$name] as $key => $item) {

                    // array_push($data, $item->value);

                    array_push($data, $item);

                }

                array_push($options, $data);

            }

        }



        $combinations = Combinations::makeCombinations($options);

        return view('backend.product.products.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));

    }



    public function producttypesAjax(Request $request)

    {

      $proStatusData = array();

        $pTypeData = Producttype::findOrFail($request->id);

        $proidreq = isset($request->proid) ? $request->proid : '';

        if($proidreq > 0){

          $proData = Product::findOrFail($proidreq);

        }else{

          $proData = '';

        }

        $pseqData = Sequence::findOrFail($pTypeData->sequence_id);



        $prefixStock = $pseqData->sequence_start;

        $prefixStock = $pseqData->sequence_prefix.$prefixStock;

        $seqName = $pseqData->sequence_name;

        $seqId = $pseqData->id;

        $cost_code_multiplier = $pseqData->cost_code_multiplier;



        $pTypeDatahtml = '<div class="form-group row">';

                      for ($i=1; $i <= 10; $i++) {

                        $keyf = "custom_".$i;

                        $CustomField = isset($pTypeData->$keyf) ? $pTypeData->$keyf : '';

                        if($CustomField !=''){

                          $pTypeDatahtml .='<div class="col-md-12">

                              <label class="col-from-label mt-2">'.$pTypeData->$keyf.'</label>';

                              $keyType = 'custom_'.$i.'_type';

                              $keyVal = 'custom_'.$i.'_value';

                               $CustomFType = isset($pTypeData->$keyType) ? $pTypeData->$keyType : '';

                               $pValData = isset($proData->$keyf) ? $proData->$keyf : '';

                              if($CustomFType == 2){

                                  $CustomFVal = isset($pTypeData->$keyVal) ? $pTypeData->$keyVal : '';

                                  if($CustomFVal != ""){

                                  $CustFVal = explode(",",$CustomFVal);

                                  $pTypeDatahtml .= '

                                  <select class="form-control aiz-selectpicker customRefresh" name="'.$keyf.'"  data-live-search="true"><option value="">Select '.$pTypeData->$keyf.'</option>';

                                      foreach($CustFVal as $cstmVal){

                                        if($cstmVal != ""){



                                          $pTypeDatahtml .=  '<option value="'.$cstmVal.'"  '. (($pValData == $cstmVal) ? "selected" :"").'>'.$cstmVal.'</option>';

                                        }

                                      }

                                $pTypeDatahtml .=  '</select>';

                                }

                              }else{

                                  $pTypeDatahtml .= '<input type="text" name="'.$keyf.'" value="'.$pValData.'" class="form-control">';

                              }

                                      $pTypeDatahtml .=  '</div>';

                          }

                      }

                  $pTypeDatahtml .=   '</div>';



              $proStatusData['status'] = 'success';

              $proStatusData['html'] = $pTypeDatahtml;

              $proStatusData['stock_id'] = $prefixStock;

              $proStatusData['cost_code_multiplier'] = $cost_code_multiplier;

              $proStatusData['seqName'] = $seqName;

              $proStatusData['seqId'] = $seqId;

              echo json_encode($proStatusData);

              exit;

    }



    public function productUnitAjax(Request $request)

    {

      // Add Unit...

       $SiteOptionsUnit = new SiteOptions;

       $SiteOptionsUnit->option_name = $request->option_name;

       $SiteOptionsUnit->option_value = $request->option_value;

       $SiteOptionsUnit->description = $request->description;

       $SiteOptionsUnit->save();

       $tagsOpt =  $request->option_value;

       $tagHtmls =  "<option value='".$tagsOpt."' selected>".$tagsOpt."</option>";

       $SiteOptionsRecord = SiteOptions::where('option_name', '=', $request->option_name)->get();

       $siteOptionHtml = "";

       foreach ($SiteOptionsRecord as $SiteOptions) {

         $option_id =$SiteOptions->id;

         $option_name =$SiteOptions->option_name;

         $option_value =$SiteOptions->option_value;

         $siteOptionHtml .= "<option value='".$option_value."'>$option_value</option>";

       }

       return response()->json(['success' => true,'optionHtml'=>$siteOptionHtml,'optname'=>$request->option_name,'tagHtmlData'=>$tagHtmls]);

    }





    public function viewproduct($id)

    {
      // for agent
      // $activitylogData = Activitylog::where('prodcut_id',$id)->where('type','=','joborder')->orderBy('created_at','DESC')->get();

      // for product
      $proactivitylog = Activitylog::where('prodcut_id',$id)->where('action','!=','closedByUser')->orderBy('created_at','DESC')->get();

      $proactivitylogclosedByUser = Activitylog::where('prodcut_id',$id)->where('action','closedByUser')->groupBy('action')->orderBy('created_at','DESC')->get();
    //   $proactivitylog = Activitylog::where('prodcut_id',$id)->where('type','=','Memo')->orderBy('id','DESC')->get();

      $product = Product::select('users.created_at as usercreateddata','users.name as username','users.email as useremail','products.*',

      'product_types.listing_type','product_types.serial_no','products.product_cost','products.published','product_types.product_type_name'

      ,'products.cost_code' ,'product_stocks.sku','product_types.custom_1 as ptCS1','product_types.custom_2 as ptCS2','product_types.custom_3 as ptCS3','product_types.custom_4 as ptCS4','product_types.custom_5 as ptCS5','product_types.custom_6 as ptCS6','product_types.custom_7 as ptCS7')

      ->leftjoin('product_types', 'product_types.id', '=', 'products.product_type_id')

      ->leftjoin('users','users.id','=','products.supplier_id')

      ->leftjoin('product_stocks','product_stocks.product_id','=','products.id')

      ->where('products.id',$id)

      ->first();


      $returnProd = ReturnProd::select('return_prods.id as ret_id','users.*','return_items.product_id as p_id')

      ->join('users', 'users.id', '=', 'return_prods.supplier_id')

      ->join('return_items', 'return_items.return_id', '=', 'return_prods.id')

      ->where('return_items.product_id',$id);

      $returnProData = $returnProd->first();


      $transfer = TransferItem::select("transfer_items.*",'transfers.from_warehouse_name','transfers.to_warehouse_name')->join('transfers','transfers.id','=','transfer_items.transfer_id')->where('transfer_items.product_id',$id)->first();


      $memo = MemoDetail::select('memo_details.*','memos.memo_number', 'memos.customer_name','memo_details.item_status','retail_resellers.customer_name','retail_resellers.customer_group','retail_resellers.company')

             ->join('memos','memos.id','=','memo_details.memo_id')

             ->join('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')

             ->where('memo_details.product_id',$id)

             ->first();

            $inventoryRun='';

            if(!empty($product->user_id))

            {

            $inventoryRun=Product::select("products.user_id","products.dop","products.stock_id",'users.name as username','inventory_runs.created_at','inventory_runs.user')

             ->join('users', 'users.id', '=', 'products.user_id')

             ->join('inventory_runs','inventory_runs.user','products.user_id')

             ->where('inventory_runs.user',$product->user_id)->first();

            }
            $JobOrderstatus = JobOrder::select('job_orders.job_status')
                                 ->leftJoin('job_order_details', 'job_order_details.job_order_id', '=', 'job_orders.id')
                                 ->where('job_order_details.jo_product_id',$id)
                                 ->where('job_orders.job_status',2)
                                 ->first();

                                     // dd($JobOrderstatus);

      return view('backend.product.products.viewproduct', compact('product','JobOrderstatus','proactivitylogclosedByUser','proactivitylog','returnProData','transfer','memo','inventoryRun'));

    }



    public function activityproduct($id)

    {

      $product = Product::findOrFail($id);

    //   dd($product->stock_id);

      $transfer = TransferItem::select("transfer_items.*",'transfers.from_warehouse_name','transfers.to_warehouse_name')->join('transfers','transfers.id','=','transfer_items.transfer_id')->where('transfer_items.product_id',$product->id)->first();

      $memo = MemoDetail::select('memo_details.*','memos.memo_number', 'memos.customer_name','retail_resellers.customer_name')

             ->join('memos','memos.id','=','memo_details.memo_id')

             ->join('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')

             ->where('memo_details.product_id',$product->id)->first();

            //  dd($memo);

            $inventoryRun=Product::select("products.user_id","products.dop","products.stock_id",'users.name as username','inventory_runs.created_at','inventory_runs.user')

            ->join('users', 'users.id', '=', 'products.user_id')

            ->join('inventory_runs','inventory_runs.user','products.user_id')

            ->where('inventory_runs.user',$product->user_id)->first();

           //  dd($inventoryRun);

      return view('backend.product.products.activity', compact('product','transfer','memo','inventoryRun'));

    }



    public function BarcodeAjax(Request $request)

    {

     $proStockreq = isset($request->proStock) ? $request->proStock : '';

      $ProTStockHtml = "";

      $proData = Product::where('stock_id', '=', $proStockreq)->first();

      // echo $proData->name;

      $ProTStockHtml .= "<tr><td>$proData->name ($proData->stock_id)</td><td><input type='text' class='form-control' name='proarrkey[$proData->id]' value='1'></td><td><button type='button' class='btn btn-danger removeStockData' name='button'><i class='las la-trash'></i></button></td></tr>";

      //

      // dd($proData);

      $ProStockHtml['status'] = 'success';

      $ProStockHtml['html'] = $ProTStockHtml;

      echo json_encode($ProStockHtml);

      exit;

    }
    public function BarcodeAjaxLabel()
    {
      $ids = $request->proId;
      echo $ids; exit;
      
    }





    public function productTagAjax(Request $request)

    {

      // Add Unit...

       $Tag = new Tag;

       $Tag->tags = $request->tags;

       $Tag->save();

       $TagOptionHtml = "";

       $TagOptions = Tag::all();

       foreach ($TagOptions as $Tag) {

         $Tag_id =$Tag->id;

         $Tag_name =$Tag->tags;

         $TagOptionHtml .= "<option value='".$Tag_id."'>$Tag_name</option>";

       }

       return response()->json(['success' => true,'TagHTML'=>$TagOptionHtml]);

    }





    public function productRestock(Request $request)
    {
      $user = Auth::user();
      $curr_uid = $user->id;

      $prodID = $request->restockid;
      $prodDetail = Product::findOrFail($prodID);
      $stockID = $prodDetail->stock_id;
      $supplier_id = $prodDetail->supplier_id;
      $userDetail = User::findOrFail($supplier_id);
      $userName = $userDetail->name;
      $product = ProductStock::where('product_id',$prodID)->firstOrFail();
      $product->sku = $request->sku;
      $product->qty = $request->qty;
      $product->save();

      $proLog = new Activitylog();
      $proLog->type = 'product';
      $proLog->user_id = $curr_uid;
      $proLog->prodcut_id = $prodID;
      $proLog->activity = addslashes('Restock of item Stock ID: '.$stockID.' by Seller '.$userName.' on');
      $proLog->action = 'edited';
      $proLog->save();
       return response()->json(['success' => true]);

    }

    public function inventory_run()

    {

        return view('backend.product.inventory_run.index');

    }

    public function getStockId(Request $request){

        // $id="";

        $warehouse_id_from=$request->warehouse_id_from;

        $warehouse_id_to=$request->warehouse_id_to;

        $stock_data=Product::select('products.*','product_stocks.sku','product_stocks.qty')

                     ->join('warehouse','warehouse.id','=','products.warehouse_id')

                     ->join('product_stocks','product_stocks.product_id','=','products.id')

                     ->where('products.warehouse_id','=', $warehouse_id_from)->get();

                        //    $json_data=implode('',$stock_data);

                echo json_encode($stock_data);

    }



    public function mi_custom_uploader(Request $request)

    {

      $camImage = $request->mi_custom_cam_image;

      $picts = array();

      $imageAddHtml = "";

      foreach ($camImage as $image) {

        $image_parts = explode(";base64,", $image);

        $image_type_aux = explode("image/", $image_parts[0]);

        $image_type = $image_type_aux[1];



        $image_base64 = base64_decode($image_parts[1]);

        $extension = 'png';

        $filename = 'uploads/all/'.Str::random(5).'.'.$extension;

        $fullpath = 'public/'.$filename;

        file_put_contents($fullpath, $image_base64);

        $returnValue = explode('/',$fullpath);

        $returnValue2 = array_reverse($returnValue);

        $fullName = $returnValue2[0];

        $fullNameArr = explode('.',$fullName);

        $fullName1 = $fullNameArr[0];

        $fullName2 = '.'.$fullNameArr[1];



        $prostock = isset($request->prostock) ? $request->prostock : "";



        $upload = new Upload;

        if($prostock !=''){

             $upload->prosku = $prostock;

        }

        $upload->extension = strtolower($extension);

        $upload->file_original_name = $fullName1;

        $upload->file_name = $filename;

        $upload->user_id = Auth::user()->id;

        $upload->type = "image";

        $upload->file_size = filesize(base_path($fullpath));

        $upload->save();



        $camImageId = $upload->id;

        $picts[$camImageId] = $fullpath;

        $imageAddHtml .='<div class="d-flex justify-content-between align-items-center mt-2 file-preview-item" data-id="'.$camImageId.'" title="'.$fullName.'"><div class="align-items-center align-self-stretch d-flex justify-content-center thumb"><img src="//user6.kustom.io/gciwatch/'.$fullpath.'" class="img-fit"></div><div class="col body"><h6 class="d-flex"><span class="text-truncate title">'.$fullName1.'</span><span class="flex-shrink-0 ext">'.$fullName2.'</span></h6></div><div class="remove"><button class="btn btn-sm btn-link remove-attachment" type="button"><i class="la la-close"></i></button></div></div>';

      }





      $imageAddID = array_keys($picts);

      return response()->json(['success' => true,'camImageData'=>$imageAddHtml,'camImageDataID'=>$imageAddID]);



    }



    public function mi_custom_index_uploader(Request $request)

    {

      $camImage = $request->mi_custom_cam_image;

      $hiddenids = $request->hiddenids;

      $picts = array();

      $imageAddHtml = "";

      foreach ($camImage as $image) {

        $image_parts = explode(";base64,", $image);

        $image_type_aux = explode("image/", $image_parts[0]);

        $image_type = $image_type_aux[1];



        $image_base64 = base64_decode($image_parts[1]);

        $extension = 'png';

        $filename = 'uploads/all/'.Str::random(5).'.'.$extension;

        $fullpath = 'public/'.$filename;

        file_put_contents($fullpath, $image_base64);

        $returnValue = explode('/',$fullpath);

        $returnValue2 = array_reverse($returnValue);

        $fullName = $returnValue2[0];

        $fullNameArr = explode('.',$fullName);

        $fullName1 = $fullNameArr[0];

        $fullName2 = '.'.$fullNameArr[1];



        $upload = new Upload;

        $upload->extension = strtolower($extension);



        $upload->file_original_name = $fullName1;

        $upload->file_name = $filename;

        $upload->user_id = Auth::user()->id;

        $upload->type = "image";

        $upload->file_size = filesize(base_path($fullpath));

        $upload->save();



        $camImageId = $upload->id;

        $picts[$camImageId] = $fullpath;

        $imageAddHtml .='<div class="d-flex justify-content-between align-items-center mt-2 file-preview-item" data-id="'.$camImageId.'" title="'.$fullName.'"><div class="align-items-center align-self-stretch d-flex justify-content-center thumb"><img src="//user6.kustom.io/gciwatch/'.$fullpath.'" class="img-fit"></div><div class="col body"><h6 class="d-flex"><span class="text-truncate title">'.$fullName1.'</span><span class="flex-shrink-0 ext">'.$fullName2.'</span></h6></div><div class="remove"><button class="btn btn-sm btn-link remove-attachment" type="button"><i class="la la-close"></i></button></div></div>';

      }



      $imageAddID = array_keys($picts);

      $updatePhotos = implode(',',$imageAddID);

      $product = Product::findOrFail($hiddenids);

      $product->photos = $updatePhotos;

      $thumbnail_img =$camImageId;

      $thumb_id = 0 ;

      if($thumbnail_img != ""){

        $thumbArr = explode(',',$thumbnail_img);

        $thumb_id = isset($thumbArr[0]) ? $thumbArr[0] : "";

      }

      if($thumb_id > 0 ){

        $product->thumbnail_img = $thumb_id;

      }

      $product->save();

      return response()->json(['success' => true,'camImageData'=>$imageAddHtml,'camImageDataID'=>$imageAddID]);



    }



    public function productskuretrieve(Request $request){

        $proSku = $request->sku;

        $ProListing = Product::select('products.id as pro_id','product_stocks.sku','product_stocks.qty')

              ->join('product_stocks', 'product_stocks.product_id', '=', 'products.id')

              ->where('product_stocks.sku',$proSku)

              ->first();

            //   dd($ProListing);

          $pSku = isset($ProListing->sku) ? $ProListing->sku : '';
          $pqty = isset($ProListing->qty) ? $ProListing->qty : '';
          $pro_id = isset($ProListing->pro_id) ? $ProListing->pro_id : '';

          if($pSku == ""){

            return response()->json(['success' => false]);

          }else{

            return response()->json(['success' => true,'TagHTML'=>$pSku,'QtyHTML'=>$pqty,'idHTML'=>$pro_id]);

          }

    }

function purchases()

{

    return view('backend.product.purchases.index');

}
public function barcode_product_excel(Request $request)
{
        $ids = $request->ids;
        $proID = json_decode($ids, TRUE);
        $fetchLiaat = new BarcodeProductExcel($proID);
        $proID = json_decode($ids, TRUE);
        $fetchLiaat = new BarcodeProductExcel($proID);
        $dt = new \DateTime();
        $curntDate = $dt->format('m-d-Y');
        return Excel::download($fetchLiaat,'BarcodeProExport.xlsx');

}


}
