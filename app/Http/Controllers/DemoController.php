<?php



namespace App\Http\Controllers;



use Mail;

use App\Mail\EmailManager;

use App\Order;

use App\ProductStock;

use Illuminate\Http\Request;

use DB;

use Schema;

use ZipArchive;

use File;

use Artisan;

use App\Upload;

use App\Banner;

use App\Brand;

use App\User;

use App\Category;

use App\CategoryTranslation;

use App\SubCategory;

use App\SubCategoryTranslation;

use App\SubSubCategory;

use App\SubSubCategoryTranslation;

use App\CustomerPackage;

use App\CustomerProduct;

use App\FlashDeal;

use App\Product;

use App\ProductTax;

use App\Tax;

use App\Shop;

use App\Slider;

use App\HomeCategory;

use App\BusinessSetting;

use App\Translation;

use App\Attribute;

use App\AttributeValue;

use App\Activitylog;

use Carbon\Carbon;

use PDF;



class DemoController extends Controller

{

    public function __construct()

    {

        ini_set('memory_limit', '2048M');

        ini_set('max_execution_time', 600);



    }



    public function cron_1()

    {

        if (env('DEMO_MODE') != 'On') {

            return back();

        }

        $this->drop_all_tables();

        $this->import_demo_sql();

    }



    public function cron_2()

    {

        if (env('DEMO_MODE') != 'On') {

            return back();

        }

        $this->remove_folder();

        $this->extract_uploads();

    }





    public function drop_all_tables()

    {

        Schema::disableForeignKeyConstraints();

        foreach (DB::select('SHOW TABLES') as $table) {

            $table_array = get_object_vars($table);

            Schema::drop($table_array[key($table_array)]);

        }

    }



    public function import_demo_sql()

    {

        Artisan::call('cache:clear');

        $sql_path = base_path('demo.sql');

        DB::unprepared(file_get_contents($sql_path));

    }



    public function extract_uploads()

    {

        $zip = new ZipArchive;

        $zip->open(base_path('public/uploads.zip'));

        $zip->extractTo('public/uploads');



    }



    public function remove_folder()

    {

        File::deleteDirectory(base_path('public/uploads'));

    }



    public function migrate_attribute_values(Request $request){

        foreach (Product::all() as $product) {

            if ($product->variant_product) {

                try {

                    $choice_options = json_decode($product->choice_options);

                    foreach ($choice_options as $choice_option) {

                        foreach ($choice_option->values as $value) {

                            $attribute_value = AttributeValue::where('value', $value)->first();

                            if ($attribute_value == null) {

                                $attribute_value = new AttributeValue;

                                $attribute_value->attribute_id = $choice_option->attribute_id;

                                $attribute_value->value = $value;

                                $attribute_value->save();

                            }

                        }

                    }

                } catch (\Exception $e) {



                }



            }

        }

    }



    public function convertTaxes()

    {

        $tax = Tax::first();



        foreach (Product::all() as $product) {

            $product_tax = new ProductTax;

            $product_tax->product_id = $product->id;

            $product_tax->tax_id = $tax->id;

            $product_tax->tax = $product->tax;

            $product_tax->tax_type = $product->tax_type;

            $product_tax->save();

        }

    }



    public function convert_assets(Request $request)

    {

        $type = array(

            "jpg" => "image",

            "jpeg" => "image",

            "png" => "image",

            "svg" => "image",

            "webp" => "image",

            "gif" => "image",

            "mp4" => "video",

            "mpg" => "video",

            "mpeg" => "video",

            "webm" => "video",

            "ogg" => "video",

            "avi" => "video",

            "mov" => "video",

            "flv" => "video",

            "swf" => "video",

            "mkv" => "video",

            "wmv" => "video",

            "wma" => "audio",

            "aac" => "audio",

            "wav" => "audio",

            "mp3" => "audio",

            "zip" => "archive",

            "rar" => "archive",

            "7z" => "archive",

            "doc" => "document",

            "txt" => "document",

            "docx" => "document",

            "pdf" => "document",

            "csv" => "document",

            "xml" => "document",

            "ods" => "document",

            "xlr" => "document",

            "xls" => "document",

            "xlsx" => "document"

        );

        foreach (Banner::all() as $key => $banner) {

            if ($banner->photo != null) {

                $arr = explode('.', $banner->photo);

                $upload = Upload::create([

                    'file_original_name' => null, 'file_name' => $banner->photo, 'user_id' => User::where('user_type', 'admin')->first()->id, 'extension' => $arr[1],

                    'type' => isset($type[$arr[1]]) ?  $type[$arr[1]] : "others", 'file_size' => 0

                ]);



                $banner->photo = $upload->id;

                $banner->save();

            }

        }



        foreach (Brand::all() as $key => $brand) {

            if ($brand->logo != null) {

                $arr = explode('.', $brand->logo);

                $upload = Upload::create([

                    'file_original_name' => null, 'file_name' => $brand->logo, 'user_id' => User::where('user_type', 'admin')->first()->id, 'extension' => $arr[1],

                    'type' => isset($type[$arr[1]]) ?  $type[$arr[1]] : "others", 'file_size' => 0

                ]);



                $brand->logo = $upload->id;

                $brand->save();

            }

        }



        foreach (Category::all() as $key => $category) {

            if ($category->banner != null) {

                $arr = explode('.', $category->banner);

                $upload = Upload::create([

                    'file_original_name' => null, 'file_name' => $category->banner, 'user_id' => User::where('user_type', 'admin')->first()->id, 'extension' => $arr[1],

                    'type' => isset($type[$arr[1]]) ?  $type[$arr[1]] : "others", 'file_size' => 0

                ]);



                $category->banner = $upload->id;

                $category->save();

            }

            if ($category->icon != null) {

                $arr = explode('.', $category->icon);

                $upload = Upload::create([

                    'file_original_name' => null, 'file_name' => $category->icon, 'user_id' => User::where('user_type', 'admin')->first()->id, 'extension' => $arr[1],

                    'type' => isset($type[$arr[1]]) ?  $type[$arr[1]] : "others", 'file_size' => 0

                ]);



                $category->icon = $upload->id;

                $category->save();

            }

        }



        foreach (CustomerPackage::all() as $key => $package) {

            if ($package->logo != null) {

                $arr = explode('.', $package->logo);

                $upload = Upload::create([

                    'file_original_name' => null, 'file_name' => $package->logo, 'user_id' => User::where('user_type', 'admin')->first()->id, 'extension' => $arr[1],

                    'type' => isset($type[$arr[1]]) ?  $type[$arr[1]] : "others", 'file_size' => 0

                ]);



                $package->logo = $upload->id;

                $package->save();

            }

        }



        foreach (CustomerProduct::all() as $key => $product) {

            if ($product->photos != null) {

                $files = array();

                foreach (json_decode($product->photos) as $key => $photo) {

                    $arr = explode('.', $photo);

                    $upload = Upload::create([

                        'file_original_name' => null, 'file_name' => $photo, 'user_id' => $product->user_id, 'extension' => $arr[1],

                        'type' => isset($type[$arr[1]]) ?  $type[$arr[1]] : "others", 'file_size' => 0

                    ]);

                    array_push($files, $upload->id);

                }



                $product->photos = implode(',', $files);

                $product->save();

            }

            if ($product->thumbnail_img != null) {

                $arr = explode('.', $product->thumbnail_img);

                $upload = Upload::create([

                    'file_original_name' => null, 'file_name' => $product->thumbnail_img, 'user_id' => $product->user_id, 'extension' => $arr[1],

                    'type' => isset($type[$arr[1]]) ?  $type[$arr[1]] : "others", 'file_size' => 0

                ]);



                $product->thumbnail_img = $upload->id;

                $product->save();

            }

            if ($product->meta_img != null) {

                $arr = explode('.', $product->meta_img);

                $upload = Upload::create([

                    'file_original_name' => null, 'file_name' => $product->meta_img, 'user_id' => $product->user_id, 'extension' => $arr[1],

                    'type' => isset($type[$arr[1]]) ?  $type[$arr[1]] : "others", 'file_size' => 0

                ]);



                $product->meta_img = $upload->id;

                $product->save();

            }

        }



        foreach (FlashDeal::all() as $key => $flash_deal) {

            if ($flash_deal->banner != null) {

                $arr = explode('.', $flash_deal->banner);

                $upload = Upload::create([

                    'file_original_name' => null, 'file_name' => $flash_deal->banner, 'user_id' => User::where('user_type', 'admin')->first()->id, 'extension' => $arr[1],

                    'type' => isset($type[$arr[1]]) ?  $type[$arr[1]] : "others", 'file_size' => 0

                ]);



                $flash_deal->banner = $upload->id;

                $flash_deal->save();

            }

        }



        foreach (Product::all() as $key => $product) {

            if ($product->photos != null) {

                $files = array();

                foreach (json_decode($product->photos) as $key => $photo) {

                    $arr = explode('.', $photo);

                    $upload = Upload::create([

                        'file_original_name' => null, 'file_name' => $photo, 'user_id' => $product->user_id, 'extension' => $arr[1],

                        'type' => isset($type[$arr[1]]) ?  $type[$arr[1]] : "others", 'file_size' => 0

                    ]);

                    array_push($files, $upload->id);

                }



                $product->photos = implode(',', $files);

                $product->save();

            }

            if ($product->thumbnail_img != null) {

                $arr = explode('.', $product->thumbnail_img);

                $upload = Upload::create([

                    'file_original_name' => null, 'file_name' => $product->thumbnail_img, 'user_id' => $product->user_id, 'extension' => $arr[1],

                    'type' => isset($type[$arr[1]]) ?  $type[$arr[1]] : "others", 'file_size' => 0

                ]);



                $product->thumbnail_img = $upload->id;

                $product->save();

            }

            if ($product->featured_img != null) {

                $arr = explode('.', $product->featured_img);

                $upload = Upload::create([

                    'file_original_name' => null, 'file_name' => $product->featured_img, 'user_id' => $product->user_id, 'extension' => $arr[1],

                    'type' => isset($type[$arr[1]]) ?  $type[$arr[1]] : "others", 'file_size' => 0

                ]);



                $product->featured_img = $upload->id;

                $product->save();

            }

            if ($product->flash_deal_img != null) {

                $arr = explode('.', $product->flash_deal_img);

                $upload = Upload::create([

                    'file_original_name' => null, 'file_name' => $product->flash_deal_img, 'user_id' => $product->user_id, 'extension' => $arr[1],

                    'type' => isset($type[$arr[1]]) ?  $type[$arr[1]] : "others", 'file_size' => 0

                ]);



                $product->flash_deal_img = $upload->id;

                $product->save();

            }

            if ($product->meta_img != null) {

                $arr = explode('.', $product->meta_img);

                $upload = Upload::create([

                    'file_original_name' => null, 'file_name' => $product->meta_img, 'user_id' => $product->user_id, 'extension' => $arr[1],

                    'type' => isset($type[$arr[1]]) ?  $type[$arr[1]] : "others", 'file_size' => 0

                ]);



                $product->meta_img = $upload->id;

                $product->save();

            }

        }



        foreach (Shop::all() as $key => $shop) {

            if ($shop->sliders != null) {

                $files = array();

                foreach (json_decode($shop->sliders) as $key => $photo) {

                    $arr = explode('.', $photo);

                    $upload = Upload::create([

                        'file_original_name' => null, 'file_name' => $photo, 'user_id' => $shop->user_id, 'extension' => $arr[1],

                        'type' => isset($type[$arr[1]]) ?  $type[$arr[1]] : "others", 'file_size' => 0

                    ]);

                    array_push($files, $upload->id);

                }



                $shop->sliders = implode(',', $files);

                $shop->save();

            }

            if ($shop->logo != null) {

                $arr = explode('.', $shop->logo);

                $upload = Upload::create([

                    'file_original_name' => null, 'file_name' => $shop->logo, 'user_id' => $shop->user_id, 'extension' => $arr[1],

                    'type' => isset($type[$arr[1]]) ?  $type[$arr[1]] : "others", 'file_size' => 0

                ]);



                $shop->logo = $upload->id;

                $shop->save();

            }

        }



        foreach (Slider::all() as $key => $slider) {

            if ($slider->photo != null) {

                $arr = explode('.', $slider->photo);

                $upload = Upload::create([

                    'file_original_name' => null, 'file_name' => $slider->photo, 'user_id' => User::where('user_type', 'admin')->first()->id, 'extension' => $arr[1],

                    'type' => isset($type[$arr[1]]) ?  $type[$arr[1]] : "others", 'file_size' => 0

                ]);



                $slider->photo = $upload->id;

                $slider->save();

            }

        }



        foreach (User::all() as $key => $user) {

            if ($user->avatar_original != null) {

                $arr = explode('.', $user->avatar_original);

                $upload = Upload::create([

                    'file_original_name' => null, 'file_name' => $user->avatar_original, 'user_id' => $user->id, 'extension' => $arr[1],

                    'type' => isset($type[$arr[1]]) ?  $type[$arr[1]] : "others", 'file_size' => 0

                ]);



                $user->avatar_original = $upload->id;

                $user->save();

            }

        }



        $business_setting = BusinessSetting::where('type', 'home_slider_images')->first();

        $business_setting->value = json_encode(Slider::pluck('photo')->toArray());

        $business_setting->save();



        $business_setting = BusinessSetting::where('type', 'home_slider_links')->first();

        $business_setting->value = json_encode(Slider::pluck('link')->toArray());

        $business_setting->save();



        $business_setting = BusinessSetting::where('type', 'home_banner1_images')->first();

        $business_setting->value = json_encode(Banner::where('position', 1)->pluck('photo')->toArray());

        $business_setting->save();



        $business_setting = BusinessSetting::where('type', 'home_banner1_links')->first();

        $business_setting->value = json_encode(Banner::where('position', 1)->pluck('url')->toArray());

        $business_setting->save();



        $business_setting = BusinessSetting::where('type', 'home_banner2_images')->first();

        $business_setting->value = json_encode(Banner::where('position', 2)->pluck('photo')->toArray());

        $business_setting->save();



        $business_setting = BusinessSetting::where('type', 'home_banner2_links')->first();

        $business_setting->value = json_encode(Banner::where('position', 2)->pluck('url')->toArray());

        $business_setting->save();



        $business_setting = BusinessSetting::where('type', 'home_categories')->first();

        $business_setting->value = json_encode(HomeCategory::pluck('category_id')->toArray());

        $business_setting->save();



        $business_setting = BusinessSetting::where('type', 'top10_categories')->first();

        $business_setting->value = json_encode(Category::where('top', 1)->pluck('id')->toArray());

        $business_setting->save();



        $business_setting = BusinessSetting::where('type', 'top10_brands')->first();

        $business_setting->value = json_encode(Brand::where('top', 1)->pluck('id')->toArray());

        $business_setting->save();



        $code = 'en';

        $jsonString = [];

        if(File::exists(base_path('resources/lang/'.$code.'.json'))){

            $jsonString = file_get_contents(base_path('resources/lang/'.$code.'.json'));

            $jsonString = json_decode($jsonString, true);

        }



        foreach($jsonString as $key => $string){

            $translation_def = new Translation;

            $translation_def->lang = $code;

            $translation_def->lang_key = $key;

            $translation_def->lang_value = $string;

            $translation_def->save();

        }

    }



    public function convert_category()

    {

        foreach (SubCategory::all() as $key => $value) {

            $category = new Category;

            $parent = Category::find($value->category_id);



            $category->name = $value->name;

            $category->digital = $parent->digital;

            $category->banner = null;

            $category->icon = null;

            $category->meta_title = $value->meta_title;

            $category->meta_description = $value->meta_description;



            $category->parent_id = $parent->id;

            $category->level = $parent->level + 1;

            $category->slug = $value->slug;

            $category->commision_rate = $parent->commision_rate;



            $category->save();



            foreach (SubCategoryTranslation::where('sub_category_id', $value->id)->get() as $translation) {

                $category_translation = new CategoryTranslation;

                $category_translation->category_id = $category->id;

                $category_translation->lang = $translation->lang;

                $category_translation->name = $translation->name;

                $category_translation->save();

            }

        }



        foreach (SubSubCategory::all() as $key => $value) {

            $category = new Category;

            $parent = Category::find(Category::where('name', SubCategory::find($value->sub_category_id)->name)->first()->id);



            $category->name = $value->name;

            $category->digital = $parent->digital;

            $category->banner = null;

            $category->icon = null;

            $category->meta_title = $value->meta_title;

            $category->meta_description = $value->meta_description;



            $category->parent_id = $parent->id;

            $category->level = $parent->level + 1;

            $category->slug = $value->slug;

            $category->commision_rate = $parent->commision_rate;



            $category->save();



            foreach (SubSubCategoryTranslation::where('sub_sub_category_id', $value->id)->get() as $translation) {

                $category_translation = new CategoryTranslation;

                $category_translation->category_id = $category->id;

                $category_translation->lang = $translation->lang;

                $category_translation->name = $translation->name;

                $category_translation->save();

            }

        }



        foreach (Product::all() as $key => $value) {

            try {

                if ($value->subsubcategory_id == null) {

                    $value->category_id = Category::where('name', SubCategory::find($value->subcategory_id)->name)->first()->id;

                    $value->save();

                } else {

                    $value->category_id = Category::where('name', SubSubCategory::find($value->subsubcategory_id)->name)->first()->id;

                    $value->save();

                }

            } catch (\Exception $e) {



            }

        }



        foreach (CustomerProduct::all() as $key => $value) {

            try {

                if ($value->subsubcategory_id == null) {

                    $value->category_id = Category::where('name', SubCategory::find($value->subcategory_id)->name)->first()->id;

                    $value->save();

                } else {

                    $value->category_id = Category::where('name', SubSubCategory::find($value->subsubcategory_id)->name)->first()->id;

                    $value->save();

                }

            } catch (\Exception $e) {



            }

        }



        // foreach (Product::all() as $key => $product) {

        //     if (is_array(json_decode($product->tags))) {

        //         $tags = array();

        //         foreach (json_decode($product->tags) as $tag) {

        //             array_push($tags, $tag->value);

        //         }

        //         $product->tags = implode(',', $tags);

        //         $product->save();

        //     }

        // }

    }



    public function insert_product_variant_forcefully(Request $request)

    {

        foreach (Product::all() as $product) {

            if ($product->stocks->isEmpty()) {

                $product_stock = new ProductStock;

                $product_stock->product_id = $product->id;

                $product_stock->variant = '';

                $product_stock->price = $product->unit_price;

                $product_stock->sku = $product->sku;

                $product_stock->qty = $product->current_stock;

                $product_stock->save();

            }

        }

    }



    public function update_seller_id_in_orders($id_min, $id_max)

    {

        $orders = Order::where('id', '>=', $id_min)->where('id', '<=', $id_max)->get();



        foreach ($orders as $order) {

            $this->update_seller_id_in_order($order);

        }



    }



    public function update_seller_id_in_order($order)

    {

        if($order->seller_id == 0){

            //dd($order->orderDetails[0]->seller_id);

            $order->seller_id = $order->orderDetails[0]->seller_id;

            $order->save();

        }

    }



    public function createCSV() {

      $date = new \DateTime(null, new \DateTimeZone('America/Los_Angeles'));

      $pst = $date->format('m-d-Y H:i');



      $low_stock  = Product::select('products.name','products.model','products.stock_id',DB::raw('SUM(product_stocks.qty) as prostock'),'site_options.low_stock')

      ->leftJoin('warehouse','warehouse.id','=','products.warehouse_id')

      ->leftJoin('product_types','product_types.id','=','products.product_type_id')

      ->join('product_stocks','products.id','=','product_stocks.product_id')

      ->join('site_options','site_options.option_value','=','products.model')

      ->orderBy('products.id', 'DESC')

      ->where('product_stocks.qty','>=',1)

      ->groupBy('products.model')

      ->where('published', '=', '1')

      ->having('site_options.low_stock', '>=', DB::raw('SUM(product_stocks.qty)'))

      ->get();

      // dd($low_stock);







    header('Content-Type: text/csv; charset=utf-8');

    //header without attachment; this instructs the function not to download the csv

    header("Content-Disposition: filename=Low_Stock.csv");



    //Temporarily open a file and store it in a temp file using php's wrapper function php://temp. You can also use php://memory but I prefered temp.



    $Myfile = fopen('php://temp', 'w');



    //state headers / column names for the csv

    $headers = array('Model Number','Current Qty','Low Stock Qty','Date Time');



    //write the headers to the opened file

    fputcsv($Myfile, $headers);



    //parse data to get rows

    foreach ($low_stock as $data) {

        $row=array(

            // $data->name,

            $data->model,

            $data->prostock,

            $data->low_stock,

            $pst,

        );



        //write the data to the opened file;

        fputcsv($Myfile, $row);

    }

    //rewind is a php function that sets the pointer at begining of the file to handle the streams of data

    rewind($Myfile);



    //stream the data to Myfile

    return stream_get_contents($Myfile);

    }

    public function lowstockemail()

       {

         $date = new \DateTime(null, new \DateTimeZone('America/Los_Angeles'));

         $pstCheck = $date->format('H:i');

         if($pstCheck == "9:00" || $pstCheck == "18:00"){

           try {

             Mail::send('frontend.cronjob', array(''),

                  function($message){

                      $message->to(env("StockManager"))

                      ->subject('Urgent Low Stock Notification')

                      ->attachData($this->createCSV(), "lowstock.csv");

                  });

           } catch (\Exception $e) {

               dd($e);

           }

         }

        }









    public function watchExcel() {
        $date = new \DateTime(null, new \DateTimeZone('America/Los_Angeles'));
        $pst = $date->format('m-d-Y H:i');
        $product =Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop','category','productcondition','productType')
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
                        ->select('products.*','warehouse.name as warehouse_name','users.name as supplier_name','site_options.low_stock','memo_details.product_id','memo_details.item_status','product_types.listing_type','brands.name as brand_name','product_stocks.sku','product_stocks.qty','productconditions.name as productconditions_name','categories.name as categories_name','memo_details.memo_id as memosdetailId','memos.memo_number','retail_resellers.customer_group','retail_resellers.company','retail_resellers.customer_name','product_types.product_type_name')
                        ->leftJoin('memo_details','memo_details.product_id','=','products.id')
                        ->leftJoin('memos', 'memos.id', '=', 'memo_details.memo_id')
                        ->LeftJoin('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
                        ->where('product_types.listing_type','Watch')
                        ->get();
                    // dd($low_stock);
                    header('Content-Type: text/csv; charset=utf-8');
                    //header without attachment; this instructs the function not to download the csv
                      header("Content-Disposition: filename=Low_Stock.csv");
                    //Temporarily open a file and store it in a temp file using php's wrapper function php://temp. You can also use php://memory but I prefered temp.
                      $Myfile = fopen('php://temp', 'w');
                    //state headers / column names for the csv
                     $headers = array( 
                            // 'Memo Number',
                            // 'Memo Status',
                            'product type',
                            'current stock id',
                            'product name',
                            // 'serial',
                            'quantity',
                            'condition',
                            'brand',
                            'model number',
                            'product cost',
                            'cost code',
                            'MSRP',
                            'sale price',
                            'category',
                            'Metal',
                            'Warehouse',
                            'size',
                            'Weight',
                            'Supplier',
                            'vendor doc number',
                            'unit',
                            'DOP',
                            'paper_cart',
                            'Partner',
                            'product details',
                            'Serial LTR',
                            'Age',
                            'Dial',
                            'Bezel',
                            'Band',
                            'Screw Count',
                            'Band Grade'
                        );
                     //write the headers to the opened file
                      fputcsv($Myfile, $headers);
                      foreach ($product as $data) {
                        $productname=$data->name;
                        if(!empty($productname))
                        {
                            $productname=$data->name;
                        }
                        else
                        {
                            $productname="";
                        }
                        $row=array(
                                // $data->memo_number,
                                // $data->stock_id,
                                $data->product_type_name,
                                $data->stock_id,
                                $productname,
                                // $data->sku,
                                $data->qty,
                                $data->productconditions_name,
                                $data->brand_name,
                                $data->model,
                                $data->product_cost,
                                $data->cost_code,
                                $data->msrp,
                                $data->unit_price,
                                $data->categories_name,
                                $data->metal,
                                $data->warehouse_name,
                                $data->size,
                                $data->weight,
                                $data->supplier_name,
                                $data->vendor_doc_number,
                                $data->unit_price,
                                $data->dop,
                                $data->paper_cart,
                                $data->partner,
                                $data->description,
                                $data->custom_1,
                                $data->custom_2,
                                $data->custom_3,
                                $data->custom_4,
                                $data->custom_5,
                                $data->custom_6,
                                $data->custom_7,
                            );
                          fputcsv($Myfile, $row);
                        }
                     rewind($Myfile);
                     return stream_get_contents($Myfile);
    }
    public function bandExcel()
    {
        $date = new \DateTime(null, new \DateTimeZone('America/Los_Angeles'));
        $pst = $date->format('m-d-Y H:i');
        $product =Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop','category','productcondition','productType')
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
                        ->select('products.*','warehouse.name as warehouse_name','users.name as supplier_name','site_options.low_stock','memo_details.product_id','memo_details.item_status','product_types.listing_type','brands.name as brand_name','product_stocks.sku','product_stocks.qty','productconditions.name as productconditions_name','categories.name as categories_name','memo_details.memo_id as memosdetailId','memos.memo_number','retail_resellers.customer_group','retail_resellers.company','retail_resellers.customer_name','product_types.product_type_name')
                        ->leftJoin('memo_details','memo_details.product_id','=','products.id')
                        ->leftJoin('memos', 'memos.id', '=', 'memo_details.memo_id')
                        ->LeftJoin('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
                        ->where('product_types.listing_type','Straps')
                        ->get();
                    // dd($low_stock);
                    header('Content-Type: text/csv; charset=utf-8');
                    //header without attachment; this instructs the function not to download the csv
                      header("Content-Disposition: filename=Low_Stock.csv");
                    //Temporarily open a file and store it in a temp file using php's wrapper function php://temp. You can also use php://memory but I prefered temp.
                      $Myfile = fopen('php://temp', 'w');
                    //state headers / column names for the csv
                     $headers = array( 
                            // 'Memo Number',
                            // 'Memo Status',
                            'product type',
                            'current stock id',
                            'product name',
                            // 'serial',
                            'quantity',
                            'condition',
                            'brand',
                            'model number',
                            'product cost',
                            'cost code',
                            'MSRP',
                            'sale price',
                            'category',
                            'Metal',
                            'Warehouse',
                            'size',
                            'Weight',
                            'Supplier',
                            'vendor doc number',
                            'unit',
                            'DOP',
                            'paper_cart',
                            'Partner',
                            'product details'
                        );
                     //write the headers to the opened file
                      fputcsv($Myfile, $headers);
                      foreach ($product as $data) {
                          $productname=$data->name;
                          if(!empty($productname))
                          {
                              $productname=$data->name;
                          }
                          else
                          {
                              $productname="";
                          }
                        $row=array(
                                // $data->memo_number,
                                // $data->stock_id,
                                $data->listing_type,
                                $data->stock_id,
                                $data->name,
                                // $data->sku,
                                $data->qty,
                                $data->productconditions_name,
                                $data->brand_name,
                                $data->model,
                                $data->product_cost,
                                $data->cost_code,
                                $data->msrp,
                                $data->unit_price,
                                $data->categories_name,
                                $data->metal,
                                $data->warehouse_name,
                                $data->size,
                                $data->weight,
                                $data->supplier_name,
                                $data->vendor_doc_number,
                                $data->unit_price,
                                $data->dop,
                                $data->paper_cart,
                                $data->partner,
                                $data->description,
                            );
                          fputcsv($Myfile, $row);
                        }
                     rewind($Myfile);
                     return stream_get_contents($Myfile);
    }
    public function JewelriesExcel()
    {
        $date = new \DateTime(null, new \DateTimeZone('America/Los_Angeles'));
        $pst = $date->format('m-d-Y H:i');
        $product =Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop','category','productcondition','productType')
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
                        ->select('products.*','warehouse.name as warehouse_name','users.name as supplier_name','site_options.low_stock','memo_details.product_id','memo_details.item_status','product_types.listing_type','brands.name as brand_name','product_stocks.sku','product_stocks.qty','productconditions.name as productconditions_name','categories.name as categories_name','memo_details.memo_id as memosdetailId','memos.memo_number','retail_resellers.customer_group','retail_resellers.company','retail_resellers.customer_name','product_types.product_type_name')
                        ->leftJoin('memo_details','memo_details.product_id','=','products.id')
                        ->leftJoin('memos', 'memos.id', '=', 'memo_details.memo_id')
                        ->LeftJoin('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
                        ->where('product_types.listing_type','Jewelries')
                        ->get();
                    // dd($low_stock);
                    header('Content-Type: text/csv; charset=utf-8');
                    //header without attachment; this instructs the function not to download the csv
                      header("Content-Disposition: filename=Low_Stock.csv");
                    //Temporarily open a file and store it in a temp file using php's wrapper function php://temp. You can also use php://memory but I prefered temp.
                      $Myfile = fopen('php://temp', 'w');
                    //state headers / column names for the csv
                     $headers = array( 
                            // 'Memo Number',
                            // 'Memo Status',
                            'product type',
                            'current stock id',
                            'product name',
                            // 'serial',
                            'quantity',
                            'condition',
                            'brand',
                            'model number',
                            'product cost',
                            'cost code',
                            'MSRP',
                            'sale price',
                            'category',
                            'Metal',
                            'Warehouse',
                            'size',
                            'Weight',
                            'Supplier',
                            'vendor doc number',
                            'unit',
                            'DOP',
                            'paper_cart',
                            'Partner',
                            'product details',
                            'Age'
                        );
                     //write the headers to the opened file
                      fputcsv($Myfile, $headers);
                      foreach ($product as $data) {
                        $row=array(
                                $data->memo_number,
                                // $data->stock_id,
                                $data->listing_type,
                                $data->stock_id,
                                $data->name,
                                // $data->sku,
                                $data->qty,
                                $data->productconditions_name,
                                $data->brand_name,
                                $data->model,
                                $data->product_cost,
                                $data->cost_code,
                                $data->msrp,
                                $data->unit_price,
                                $data->categories_name,
                                $data->metal,
                                $data->warehouse_name,
                                $data->size,
                                $data->weight,
                                $data->supplier_name,
                                $data->vendor_doc_number,
                                $data->unit_price,
                                $data->dop,
                                $data->paper_cart,
                                $data->partner,
                                $data->description,
                                $data->custom_2,
                            );
                          fputcsv($Myfile, $row);
                        }
                     rewind($Myfile);
                     return stream_get_contents($Myfile);

    }
    public function BezelExcel()
    {
        $date = new \DateTime(null, new \DateTimeZone('America/Los_Angeles'));
        $pst = $date->format('m-d-Y H:i');
        $product =Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop','category','productcondition','productType')
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
                        ->select('products.*','warehouse.name as warehouse_name','users.name as supplier_name','site_options.low_stock','memo_details.product_id','memo_details.item_status','product_types.listing_type','brands.name as brand_name','product_stocks.sku','product_stocks.qty','productconditions.name as productconditions_name','categories.name as categories_name','memo_details.memo_id as memosdetailId','memos.memo_number','retail_resellers.customer_group','retail_resellers.company','retail_resellers.customer_name','product_types.product_type_name')
                        ->leftJoin('memo_details','memo_details.product_id','=','products.id')
                        ->leftJoin('memos', 'memos.id', '=', 'memo_details.memo_id')
                        ->LeftJoin('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
                        ->where('product_types.listing_type','Bezel')
                        ->get();
                    // dd($low_stock);
                    header('Content-Type: text/csv; charset=utf-8');
                    //header without attachment; this instructs the function not to download the csv
                      header("Content-Disposition: filename=Low_Stock.csv");
                    //Temporarily open a file and store it in a temp file using php's wrapper function php://temp. You can also use php://memory but I prefered temp.
                      $Myfile = fopen('php://temp', 'w');
                    //state headers / column names for the csv
                     $headers = array( 
                            // 'Memo Number',
                            // 'Memo Status',
                            'product type',
                            'current stock id',
                            'product name',
                            // 'serial',
                            'quantity',
                            'condition',
                            'brand',
                            'model number',
                            'product cost',
                            'cost code',
                            'MSRP',
                            'sale price',
                            'category',
                            'Metal',
                            'Warehouse',
                            'size',
                            'Weight',
                            'Supplier',
                            'vendor doc number',
                            'unit',
                            'DOP',
                            'paper_cart',
                            'Partner',
                            'product details',
                            'Dia Ct Wt',
                            'Stone Count',
                            'Stone Size'
                        );
                     //write the headers to the opened file
                      fputcsv($Myfile, $headers);
                      foreach ($product as $data) {
                        $row=array(
                                // $data->memo_number,
                                // $data->stock_id,
                                $data->listing_type,
                                $data->stock_id,
                                $data->name,
                                // $data->sku,
                                $data->qty,
                                $data->productconditions_name,
                                $data->brand_name,
                                $data->model,
                                $data->product_cost,
                                $data->cost_code,
                                $data->msrp,
                                $data->unit_price,
                                $data->categories_name,
                                $data->metal,
                                $data->warehouse_name,
                                $data->size,
                                $data->weight,
                                $data->supplier_name,
                                $data->vendor_doc_number,
                                $data->unit_price,
                                $data->dop,
                                $data->paper_cart,
                                $data->partner,
                                $data->description,
                                $data->custom_4,
                                $data->custom_5,
                                $data->custom_6,
                            );
                          fputcsv($Myfile, $row);
                        }
                     rewind($Myfile);
                     return stream_get_contents($Myfile);

    }
    public function BraceletExcel()
    {
        $date = new \DateTime(null, new \DateTimeZone('America/Los_Angeles'));
        $pst = $date->format('m-d-Y H:i');
        $product =Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop','category','productcondition','productType')
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
                        ->select('products.*','warehouse.name as warehouse_name','users.name as supplier_name','site_options.low_stock','memo_details.product_id','memo_details.item_status','product_types.listing_type','brands.name as brand_name','product_stocks.sku','product_stocks.qty','productconditions.name as productconditions_name','categories.name as categories_name','memo_details.memo_id as memosdetailId','memos.memo_number','retail_resellers.customer_group','retail_resellers.company','retail_resellers.customer_name','product_types.product_type_name')
                        ->leftJoin('memo_details','memo_details.product_id','=','products.id')
                        ->leftJoin('memos', 'memos.id', '=', 'memo_details.memo_id')
                        ->LeftJoin('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
                        ->where('product_types.listing_type','Bracelet')
                        ->get();
                    // dd($low_stock);
                    header('Content-Type: text/csv; charset=utf-8');
                    //header without attachment; this instructs the function not to download the csv
                      header("Content-Disposition: filename=Low_Stock.csv");
                    //Temporarily open a file and store it in a temp file using php's wrapper function php://temp. You can also use php://memory but I prefered temp.
                      $Myfile = fopen('php://temp', 'w');
                    //state headers / column names for the csv
                     $headers = array( 
                            // 'Memo Number',
                            // 'Memo Status',
                            'product type',
                            'current stock id',
                            'product name',
                            // 'serial',
                            'quantity',
                            'condition',
                            'brand',
                            'model number',
                            'product cost',
                            'cost code',
                            'MSRP',
                            'sale price',
                            'category',
                            'Metal',
                            'Warehouse',
                            'size',
                            'Weight',
                            'Supplier',
                            'vendor doc number',
                            'unit',
                            'DOP',
                            'paper_cart',
                            'Partner',
                            'product details',
                            'Dia Ct Wt',
                            'Stone Count',
                            'Stone Size'
                        );
                     //write the headers to the opened file
                      fputcsv($Myfile, $headers);
                      foreach ($product as $data) {
                        $row=array(
                                // $data->memo_number,
                                // $data->stock_id,
                                $data->listing_type,
                                $data->stock_id,
                                $data->name,
                                // $data->sku,
                                $data->qty,
                                $data->productconditions_name,
                                $data->brand_name,
                                $data->model,
                                $data->product_cost,
                                $data->cost_code,
                                $data->msrp,
                                $data->unit_price,
                                $data->categories_name,
                                $data->metal,
                                $data->warehouse_name,
                                $data->size,
                                $data->weight,
                                $data->supplier_name,
                                $data->vendor_doc_number,
                                $data->unit_price,
                                $data->dop,
                                $data->paper_cart,
                                $data->partner,
                                $data->description,
                                $data->custom_4,
                                $data->custom_5,
                                $data->custom_6,
                            );
                          fputcsv($Myfile, $row);
                        }
                     rewind($Myfile);
                     return stream_get_contents($Myfile);

    }
    public function NecklaceExcel()
    {
        $date = new \DateTime(null, new \DateTimeZone('America/Los_Angeles'));
        $pst = $date->format('m-d-Y H:i');
        $product =Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop','category','productcondition','productType')
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
                        ->select('products.*','warehouse.name as warehouse_name','users.name as supplier_name','site_options.low_stock','memo_details.product_id','memo_details.item_status','product_types.listing_type','brands.name as brand_name','product_stocks.sku','product_stocks.qty','productconditions.name as productconditions_name','categories.name as categories_name','memo_details.memo_id as memosdetailId','memos.memo_number','retail_resellers.customer_group','retail_resellers.company','retail_resellers.customer_name','product_types.product_type_name')
                        ->leftJoin('memo_details','memo_details.product_id','=','products.id')
                        ->leftJoin('memos', 'memos.id', '=', 'memo_details.memo_id')
                        ->LeftJoin('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
                        ->where('product_types.listing_type','Necklace')
                        ->get();
                    // dd($low_stock);
                    header('Content-Type: text/csv; charset=utf-8');
                    //header without attachment; this instructs the function not to download the csv
                      header("Content-Disposition: filename=Necklace.csv");
                    //Temporarily open a file and store it in a temp file using php's wrapper function php://temp. You can also use php://memory but I prefered temp.
                      $Myfile = fopen('php://temp', 'w');
                    //state headers / column names for the csv
                     $headers = array( 
                            // 'Memo Number',
                            // 'Memo Status',
                            'product type',
                            'current stock id',
                            'product name',
                            // 'serial',
                            'quantity',
                            'condition',
                            'brand',
                            'model number',
                            'product cost',
                            'cost code',
                            'MSRP',
                            'sale price',
                            'category',
                            'Metal',
                            'Warehouse',
                            'size',
                            'Weight',
                            'Supplier',
                            'vendor doc number',
                            'unit',
                            'DOP',
                            'paper_cart',
                            'Partner',
                            'product details',
                            'Dia Ct Wt',
                            'Stone Count',
                            'Stone Size'
                        );
                     //write the headers to the opened file
                      fputcsv($Myfile, $headers);
                      foreach ($product as $data) {
                        $row=array(
                                // $data->memo_number,
                                // $data->stock_id,
                                $data->listing_type,
                                $data->stock_id,
                                $data->name,
                                // $data->sku,
                                $data->qty,
                                $data->productconditions_name,
                                $data->brand_name,
                                $data->model,
                                $data->product_cost,
                                $data->cost_code,
                                $data->msrp,
                                $data->unit_price,
                                $data->categories_name,
                                $data->metal,
                                $data->warehouse_name,
                                $data->size,
                                $data->weight,
                                $data->supplier_name,
                                $data->vendor_doc_number,
                                $data->unit_price,
                                $data->dop,
                                $data->paper_cart,
                                $data->partner,
                                $data->description,
                                $data->custom_4,
                                $data->custom_5,
                                $data->custom_6,
                            );
                          fputcsv($Myfile, $row);
                        }
                     rewind($Myfile);
                     return stream_get_contents($Myfile);

    }
    public function RingExcel()
    {
        $date = new \DateTime(null, new \DateTimeZone('America/Los_Angeles'));
        $pst = $date->format('m-d-Y H:i');
        $product =Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop','category','productcondition','productType')
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
                        ->select('products.*','warehouse.name as warehouse_name','users.name as supplier_name','site_options.low_stock','memo_details.product_id','memo_details.item_status','product_types.listing_type','brands.name as brand_name','product_stocks.sku','product_stocks.qty','productconditions.name as productconditions_name','categories.name as categories_name','memo_details.memo_id as memosdetailId','memos.memo_number','retail_resellers.customer_group','retail_resellers.company','retail_resellers.customer_name','product_types.product_type_name')
                        ->leftJoin('memo_details','memo_details.product_id','=','products.id')
                        ->leftJoin('memos', 'memos.id', '=', 'memo_details.memo_id')
                        ->LeftJoin('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
                        ->where('product_types.listing_type','Ring')
                        ->get();
                    // dd($low_stock);
                    header('Content-Type: text/csv; charset=utf-8');
                    //header without attachment; this instructs the function not to download the csv
                      header("Content-Disposition: filename=Low_Stock.csv");
                    //Temporarily open a file and store it in a temp file using php's wrapper function php://temp. You can also use php://memory but I prefered temp.
                      $Myfile = fopen('php://temp', 'w');
                    //state headers / column names for the csv
                     $headers = array( 
                            // 'Memo Number',
                            // 'Memo Status',
                            'product type',
                            'current stock id',
                            'product name',
                            // 'serial',
                            'quantity',
                            'condition',
                            'brand',
                            'model number',
                            'product cost',
                            'cost code',
                            'MSRP',
                            'sale price',
                            'category',
                            'Metal',
                            'Warehouse',
                            'size',
                            'Weight',
                            'Supplier',
                            'vendor doc number',
                            'unit',
                            'DOP',
                            'paper_cart',
                            'Partner',
                            'product details',
                            'Age'
                        );
                     //write the headers to the opened file
                      fputcsv($Myfile, $headers);
                      foreach ($product as $data) {
                        $row=array(
                                // $data->memo_number,
                                // $data->stock_id,
                                $data->listing_type,
                                $data->stock_id,
                                $data->name,
                                // $data->sku,
                                $data->qty,
                                $data->productconditions_name,
                                $data->brand_name,
                                $data->model,
                                $data->product_cost,
                                $data->cost_code,
                                $data->msrp,
                                $data->unit_price,
                                $data->categories_name,
                                $data->metal,
                                $data->warehouse_name,
                                $data->size,
                                $data->weight,
                                $data->supplier_name,
                                $data->vendor_doc_number,
                                $data->unit_price,
                                $data->dop,
                                $data->paper_cart,
                                $data->partner,
                                $data->description,
                                $data->custom_2,
                            );
                          fputcsv($Myfile, $row);
                        }
                     rewind($Myfile);
                     return stream_get_contents($Myfile);

    }
    public function EarringExcel()
    {
        $date = new \DateTime(null, new \DateTimeZone('America/Los_Angeles'));
        $pst = $date->format('m-d-Y H:i');
        $product =Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop','category','productcondition','productType')
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
                        ->select('products.*','warehouse.name as warehouse_name','users.name as supplier_name','site_options.low_stock','memo_details.product_id','memo_details.item_status','product_types.listing_type','brands.name as brand_name','product_stocks.sku','product_stocks.qty','productconditions.name as productconditions_name','categories.name as categories_name','memo_details.memo_id as memosdetailId','memos.memo_number','retail_resellers.customer_group','retail_resellers.company','retail_resellers.customer_name','product_types.product_type_name')
                        ->leftJoin('memo_details','memo_details.product_id','=','products.id')
                        ->leftJoin('memos', 'memos.id', '=', 'memo_details.memo_id')
                        ->LeftJoin('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
                        ->where('product_types.listing_type','Earring')
                        ->get();
                    // dd($low_stock);
                    header('Content-Type: text/csv; charset=utf-8');
                    //header without attachment; this instructs the function not to download the csv
                      header("Content-Disposition: filename=Low_Stock.csv");
                    //Temporarily open a file and store it in a temp file using php's wrapper function php://temp. You can also use php://memory but I prefered temp.
                      $Myfile = fopen('php://temp', 'w');
                    //state headers / column names for the csv
                     $headers = array( 
                            // 'Memo Number',
                            // 'Memo Status',
                            'product type',
                            'current stock id',
                            'product name',
                            // 'serial',
                            'quantity',
                            'condition',
                            'brand',
                            'model number',
                            'product cost',
                            'cost code',
                            'MSRP',
                            'sale price',
                            'category',
                            'Metal',
                            'Warehouse',
                            'size',
                            'Weight',
                            'Supplier',
                            'vendor doc number',
                            'unit',
                            'DOP',
                            'paper_cart',
                            'Partner',
                            'product details',
                            'Age'
                        );
                     //write the headers to the opened file
                      fputcsv($Myfile, $headers);
                      foreach ($product as $data) {
                        $row=array(
                                // $data->memo_number,
                                // $data->stock_id,
                                $data->listing_type,
                                $data->stock_id,
                                $data->name,
                                // $data->sku,
                                $data->qty,
                                $data->productconditions_name,
                                $data->brand_name,
                                $data->model,
                                $data->product_cost,
                                $data->cost_code,
                                $data->msrp,
                                $data->unit_price,
                                $data->categories_name,
                                $data->metal,
                                $data->warehouse_name,
                                $data->size,
                                $data->weight,
                                $data->supplier_name,
                                $data->vendor_doc_number,
                                $data->unit_price,
                                $data->dop,
                                $data->paper_cart,
                                $data->partner,
                                $data->description,
                                $data->custom_2,
                            );
                          fputcsv($Myfile, $row);
                        }
                     rewind($Myfile);
                     return stream_get_contents($Myfile);

    }
    public function DialExcel()
    {
        $date = new \DateTime(null, new \DateTimeZone('America/Los_Angeles'));
        $pst = $date->format('m-d-Y H:i');
        $product =Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop','category','productcondition','productType')
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
                        ->select('products.*','warehouse.name as warehouse_name','users.name as supplier_name','site_options.low_stock','memo_details.product_id','memo_details.item_status','product_types.listing_type','brands.name as brand_name','product_stocks.sku','product_stocks.qty','productconditions.name as productconditions_name','categories.name as categories_name','memo_details.memo_id as memosdetailId','memos.memo_number','retail_resellers.customer_group','retail_resellers.company','retail_resellers.customer_name','product_types.product_type_name')
                        ->leftJoin('memo_details','memo_details.product_id','=','products.id')
                        ->leftJoin('memos', 'memos.id', '=', 'memo_details.memo_id')
                        ->LeftJoin('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
                        ->where('product_types.listing_type','Dial')
                        ->get();
                    // dd($low_stock);
                    header('Content-Type: text/csv; charset=utf-8');
                    //header without attachment; this instructs the function not to download the csv
                      header("Content-Disposition: filename=dial.csv");
                    //Temporarily open a file and store it in a temp file using php's wrapper function php://temp. You can also use php://memory but I prefered temp.
                      $Myfile = fopen('php://temp', 'w');
                    //state headers / column names for the csv
                     $headers = array( 
                            // 'Memo Number',
                            // 'Memo Status',
                            'product type',
                            'current stock id',
                            'product name',
                            // 'serial',
                            'quantity',
                            'condition',
                            'brand',
                            'model number',
                            'product cost',
                            'cost code',
                            'MSRP',
                            'sale price',
                            'category',
                            'Metal',
                            'Warehouse',
                            'size',
                            'Weight',
                            'Supplier',
                            'vendor doc number',
                            'unit',
                            'DOP',
                            'paper_cart',
                            'Partner',
                            'product details',
                            'Age'
                        );
                     //write the headers to the opened file
                      fputcsv($Myfile, $headers);
                      foreach ($product as $data) {
                        $row=array(
                                // $data->memo_number,
                                // $data->stock_id,
                                $data->listing_type,
                                $data->stock_id,
                                $data->name,
                                // $data->sku,
                                $data->qty,
                                $data->productconditions_name,
                                $data->brand_name,
                                $data->model,
                                $data->product_cost,
                                $data->cost_code,
                                $data->msrp,
                                $data->unit_price,
                                $data->categories_name,
                                $data->metal,
                                $data->warehouse_name,
                                $data->size,
                                $data->weight,
                                $data->supplier_name,
                                $data->vendor_doc_number,
                                $data->unit_price,
                                $data->dop,
                                $data->paper_cart,
                                $data->partner,
                                $data->description,
                                $data->custom_2,
                            );
                          fputcsv($Myfile, $row);
                        }
                     rewind($Myfile);
                     return stream_get_contents($Myfile);

    }
    public function daily_report_mail(Request $Request)
    {
        $daily_mail = Activitylog::whereDate('created_at', Carbon::today())->get();

        // dd($daily_mail);

        $array['userIp'] = $Request->ip();

        $array['daily_mail'] = $daily_mail;

        $pdf = PDF::loadView('frontend.allmails.dr_mail', $array);

        $array['from'] = env('MAIL_FROM_ADDRESS');

        Mail::send('frontend.allmails.dr_mail_file', $array, function($message) use ($array,$pdf) {

            $message->to(env("StockManager"));

            $message->subject('End of the Day Summary');

            $message->attachData($pdf->output(), "end_of_day_summary.pdf");

        });



        try {

            Mail::send('frontend.allmails.dr_mail_file', array(''),

                function($message){

                    $message->to(env("StockManager"))

                    ->subject('End of the Day Summary')

                    ->attachData($this->watchExcel(), "watch.csv");

                });

        } catch (\Exception $e) {

            dd($e);

        }



        try {

            Mail::send('frontend.allmails.dr_mail_file', array(''),

                function($message){

                    $message->to(env("StockManager"))

                    ->subject('End of the Day Summary')

                    ->attachData($this->bandExcel(), "Band.csv");

                });

        } catch (\Exception $e) {

            dd($e);

        }
        try {

            Mail::send('frontend.allmails.dr_mail_file', array(''),

                function($message){

                    $message->to(env("StockManager"))

                    ->subject('End of the Day Summary')

                    ->attachData($this->JewelriesExcel(), "Jewelries.csv");

                });

        } catch (\Exception $e) {

            dd($e);

        }
        try {

            Mail::send('frontend.allmails.dr_mail_file', array(''),

                function($message){

                    $message->to(env("StockManager"))

                    ->subject('End of the Day Summary')

                    ->attachData($this->BezelExcel(), "BezelExcel.csv");

                });

        } catch (\Exception $e) {

            dd($e);

        }
        try {

            Mail::send('frontend.allmails.dr_mail_file', array(''),

                function($message){

                    $message->to(env("StockManager"))

                    ->subject('End of the Day Summary')

                    ->attachData($this->BraceletExcel(), "BraceletExcel.csv");

                });

        } catch (\Exception $e) {

            dd($e);

        }
        try {

            Mail::send('frontend.allmails.dr_mail_file', array(''),

                function($message){

                    $message->to(env("StockManager"))

                    ->subject('End of the Day Summary')

                    ->attachData($this->RingExcel(), "RingExcel.csv");

                });

        } catch (\Exception $e) {

            dd($e);

        }
        try {

            Mail::send('frontend.allmails.dr_mail_file', array(''),

                function($message){

                    $message->to(env("StockManager"))

                    ->subject('End of the Day Summary')

                    ->attachData($this->NecklaceExcel(), "NecklaceExcel.csv");

                });

        } catch (\Exception $e) {

            dd($e);

        }
        try {

            Mail::send('frontend.allmails.dr_mail_file', array(''),

                function($message){

                    $message->to(env("StockManager"))

                    ->subject('End of the Day Summary')

                    ->attachData($this->EarringExcel(), "EarringExcel.csv");

                });

        } catch (\Exception $e) {

            dd($e);

        }
        try {

            Mail::send('frontend.allmails.dr_mail_file', array(''),

                function($message){

                    $message->to(env("StockManager"))

                    ->subject('End of the Day Summary')

                    ->attachData($this->DialExcel(), "DialExcel.csv");

                });

        } catch (\Exception $e) {

            dd($e);

        }

        // return view('frontend.allmails.dr_mail',compact('daily_mail'));

    }

}

