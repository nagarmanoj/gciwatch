<?php
namespace App;
use App\Product;

use App\ProductStock;

use App\SiteOptions;

use App\Models\Warehouse;

use App\Category;

use App\ProductType;

use App\Productcondition;

use App\Models\Sequence;

use App\Tag;

use App\Activitylog;

use App\User;

use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Maatwebsite\Excel\Concerns\WithValidation;

use Illuminate\Support\Collection;

use Maatwebsite\Excel\Concerns\ToCollection;

use Illuminate\Support\Str;

use Auth;





// class ProductsImport implements ToModel, WithHeadingRow, WithValidation

class ProductsImport implements ToCollection, WithHeadingRow, ToModel,WithValidation

{

    private $rows = 0;

    private function proTypeSlug($P_slug){

      $ProSlug = preg_replace('/[^A-Za-z0-9\_]/', '', str_replace(' ', '_', strtolower($P_slug)));

      // echo $ProSlug;exit;

      return $ProSlug;

    }



    public function collection(Collection $rows) {

        $canImport = true;

        // echo $canImport;exit;

        if (\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null &&

                \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated){

            if(Auth::user()->user_type == 'seller' && count($rows) > Auth::user()->seller->remaining_uploads) {

                $canImport = false;

                flash(translate('Upload limit has been reached. Please upgrade your package.'))->warning();

            }

        }

        // echo $canImport;exit;

        if($canImport) {



          $siteOptionsKeys = SiteOptions::all();

          // echo $siteOptionsKeys;exit;

          $optionArr = array();

          foreach ($siteOptionsKeys as $options) {

            $optionArr[$options->option_name][]=$options->option_value;

          }

           // print_r($optionArr);exit;

          $ProductKeys = Product::select('id','stock_id')->get();

          // print_r($ProductKeys);exit;

          $ProductArr = array();

          foreach ($ProductKeys as $proK) {

            $ProductArr[$proK->stock_id] = $proK->id;

          }

          // print_r($ProductArr);exit;



           $warehouse = Warehouse::all();

           $categories = Category::all();

           $Sellers = User::all();

           $brands = Brand::all();

           $allConditionArr = array();

           $Productconditions = Productcondition::all();

           foreach ($Productconditions as $ProductconditionData) {

             $conditionName = strtolower($ProductconditionData->name);

             $allConditionArr[$conditionName] = $ProductconditionData->id;

           }

           $ProductType = ProductType::all();

           $alltags = Tag::all();

           $tagsDbArr = array();

           foreach ($alltags as $tag) {

             $tagsDbArr[] = $tag->tags;

           }

           // print_r($ProductType);exit;



          $ProductKeys = ProductStock::select('product_id','sku')->whereNotNull('sku')->get();

          $proStockArr = array();

          foreach($ProductKeys as $proInfo){

              $proStockArr[$proInfo->sku]= $proInfo->product_id;

          }
           $allProTypeArr = array();
          // dd($ProductType);
          foreach ($ProductType as $ProTypeval) {

            $ProTypeId = $ProTypeval->id;

            $ProTypeName = $ProTypeval->product_type_name;

            $ProTypeName = $this->proTypeSlug($ProTypeName);

            $allProTypeArr[$ProTypeName]['id'] =  $ProTypeId;

            // echo $allProTypeArr[$ProTypeName]['id'] ."=".$ProTypeId."<br>";

            for ($i=1; $i < 11; $i++) {

                $customKey = 'custom_'.$i;

                $custVal = isset($ProTypeval->$customKey) ? $ProTypeval->$customKey : '' ;

                if($custVal !=''){

                  $custVal = $this->proTypeSlug($custVal);

                  $allProTypeArr[$ProTypeName]['custom'][$custVal] =  $customKey;

                }



            }

          }


          $PSeqData = ProductType::select('product_types.sequence_id','product_types.product_type_name','sequence.sequence_name','sequence.sequence_prefix','sequence.sequence_start')
                      ->leftjoin('sequence','sequence.id','=','product_types.sequence_id')
                      ->get();
                      // dd($PSeqData);
        // $SeqData =  Sequence::orderBy('sequence_name')->get();
          $seqName = array();
          if(!empty($PSeqData)){
            foreach ($PSeqData as $Seqkey => $Seqval) {
              $seqName[$Seqval->product_type_name]['prefix']= $Seqval->sequence_prefix;
              $seqName[$Seqval->product_type_name]['start']= $Seqval->sequence_start;
              $seqName[$Seqval->product_type_name]['sid']= $Seqval->sequence_id;
            }
          }
          // echo "<pre>";
            // print_r($proStockArr);
            // echo "<hr>";
            // echo "<hr>";
            // echo "<pre>";
            // print_r($ProductArr);
            // exit;
           $ExistStock = array();
            foreach ($rows as $row) {

              if($row['product_name'] == ''){

                continue;

              }




                $proSku = $row['serial'];
                $stocks = $row['current_stock_id'];

                $serProNumber = "";

                if($proSku !=""){

                    $proSku = trim($proSku);

                    $serProNumber = isset($proStockArr[$proSku]) ? $proStockArr[$proSku] :'';

                }
                $proStId = 0;
                if($stocks !=""){
                    $stocks = trim($stocks);
                    $proStId = isset($ProductArr[$stocks]) ? $ProductArr[$stocks] :'';
                }

                // here is the issue
                if($serProNumber != ''){
                  if($proStId > 0){

                  }else{
                  $ExistStock[] = $row['current_stock_id'];
                    continue;
                  }
                }
                $RowPtype = isset($row['product_type']) ? $row['product_type'] : "";
                $RowPtype = $this->proTypeSlug($RowPtype);
                $RProCArr = isset($allProTypeArr[$RowPtype]['custom']) ? $allProTypeArr[$RowPtype]['custom'] : "";


              $tags = $row['tags'];
              // echo $tags;exit;
              if($tags != ""){
                $tagsArr = explode(",", $tags);
                  foreach ($tagsArr as $alltags) {
                    if(!in_array($alltags,$tagsDbArr)){
                      $tagdata = new Tag;
                      $tagdata->tags = $alltags;
                      $tagdata->save();
                       $tagdata;
                    }
                  }
                  $tags= serialize($tagsArr);
              }

              $photos = $row['gallery_images'];

              // echo $photos;exit;

              $photostr = "";

              if($photos != ""){

                $photos = explode(",", $photos);

                $photoArr = array();

                foreach ($photos as $photo) {

                  $photoArr[] =  $this->downloadThumbnail($photo);

                }

                if(!empty($photoArr)){

                  $photostr = implode(',',$photoArr);

                }

              }

               $unitArr = $optionArr['unit'];

               $unit = $row['unit'];

              //  echo $unit;exit;

               if ($unit != "") {

                 if (!empty($unitArr) && in_array($unit,$unitArr)) {

                 }else{

                  $SiteOptions = new SiteOptions;

                  $SiteOptions->option_name = "unit";

                  $SiteOptions->option_value = $unit;

                  $SiteOptions->save();

                 }

               }



               $partnersArr = $optionArr['partners'];

               $partners = $row['partners'];

               if ($partners != "") {

                 if (!empty($partnersArr) && in_array($partners,$partnersArr)) {

                 }else{

                  $SiteOptions = new SiteOptions;

                  $SiteOptions->option_name = "partners";

                  $SiteOptions->option_value = $partners;

                  $SiteOptions->save();

                 }

               }

               $sizeArr = $optionArr['size'];

               $size = $row['size'];

               if ($size != "") {

                 if (!empty($sizeArr) && in_array($size,$sizeArr)) {

                 }else{

                  $SiteOptions = new SiteOptions;

                  $SiteOptions->option_name = "size";

                  $SiteOptions->option_value = $size;

                  $SiteOptions->save();

                 }

               }



               $modelArr = $optionArr['model'];

               $model = $row['model_number'];

               if ($model != "") {

                 if (!empty($modelArr) && in_array($model,$modelArr)) {

                 }else{

                  $SiteOptions = new SiteOptions;

                  $SiteOptions->option_name = "model";

                  $SiteOptions->option_value = $model;

                  $SiteOptions->save();

                 }

               }

              //  exit;



               $metalArr = $optionArr['metal'];

               $metal = $row['metal'];

               if ($metal != "") {

                 if (!empty($metalArr) && in_array($metal,$metalArr)) {

                 }else{

                  $SiteOptions = new SiteOptions;

                  $SiteOptions->option_name = "metal";

                  $SiteOptions->option_value = $metal;

                  $SiteOptions->save();

                 }

               }

               $category = "";

               if(!empty($categories) && $row['category'] != ""){

                 foreach ($categories as $catData) {

                   $cat_name = $catData->name;

                   if($cat_name == $row['category']){

                     $category = $catData->id;

                     break;

                   }

                 }

               }



               $Seller_id = "";

               if(!empty($Sellers) && $row['supplier'] != ""){

                 foreach ($Sellers as $SellerData) {

                   $Seller_name = $SellerData->name;

                   if($Seller_name == $row['supplier']){

                     $Seller_id = $SellerData->id;

                     break;

                   }

                 }

               }



               $brands_id = "";

               if(!empty($brands) && $row['brand'] != ""){

                 foreach ($brands as $brandsData) {

                   $brands_name = $brandsData->name;

                   if($brands_name == $row['brand']){

                     $brands_id = $brandsData->id;

                     break;

                   }

                 }

               }


               $warehouse_id = "";

               if(!empty($warehouse) && $row['warehouse'] != ""){

                 foreach ($warehouse as $warehouseData) {

                   $warehouse_name = $warehouseData->name;

                   if($warehouse_name == $row['warehouse']){

                     $warehouse_id = $warehouseData->id;

                     break;

                   }

                 }

               }


               $Productconditions_id = "";
               $importConVal = isset($row['condition'])?$row['condition']:"";
               if(!empty($allConditionArr) && $importConVal != ""){
                 $importConVal = strtolower($importConVal);
                 $Productconditions_id = isset($allConditionArr[$importConVal]) ? $allConditionArr[$importConVal] : 0;
               }

               if($Productconditions_id  < 1 && $importConVal != ""){
                 $ProCondition = new Productcondition();
                 $ProCondition->name = $importConVal;
                 $ProCondition->save();
                 $Productconditions_id = $ProCondition->id;
               }


                $dopDate =  $row['dop'];

                $proCreateArr = array(

                  'name' => $row['product_name'],

                  'stock_id'=>$row['current_stock_id'],

                  'product_type_id'=>isset($row['product_type']) ? $row['product_type'] : "",

                  'user_id' => Auth::user()->user_type == 'seller' ? Auth::user()->id : User::where('user_type', 'admin')->first()->id,

                  'category_id' => $category,

                  'brand_id' => $brands_id,

                  'photos'=> $photostr,

                  'thumbnail_img' => $this->downloadThumbnail($row['thumbnail_image']),

                  'tags'=>$tags,

                  'description'=>$row['description'],

                  'unit_price' => $row['sale_price'],

                  'product_cost'=>$row['product_cost'],

                  'paper_cart'=>$row['paper_cart'],

                  'published'=>$row['published'],

                  'featured'=>$row['featured'],

                  'current_stock'=>$row['quantity'],

                  'unit' => $unit,

                  'metal'=>$metal,

                  'model'=>$model,

                  'cost_code'=>$row['cost_code'],

                  'dop'=>$dopDate,

                  'size'=>$size,

                  // 'dop'=>Date::dateTimeToExcel($row['dop']),

                  'partner'=>$partners,

                  'warehouse_id'=>$warehouse_id,

                  'weight'=>$row['weight'],

                  'vendor_doc_number'=>$row['vendor_doc_number'],

                  'supplier_id'=>$Seller_id,

                  'productcondition_id'=>$Productconditions_id,

                  'msrp'=>$row['msrp'],

                  // 'min_qty'=>$row['min_qty'],

                  // 'low_stock_quantity'=>$row['low_stock_quantity'],

                  // 'discount'=>$row['discount'],

                  // 'discount_type'=>$row['discount_type'],

                  // 'discount_start_date'=>$row['discount_start_date'],

                  // 'discount_end_date'=>$row['discount_end_date'],

                  // 'tax'=>$row['tax'],

                  // 'tax_type'=>$row['tax_type'],

                  // 'shipping_type'=>$row['shipping_type'],

                  // 'shipping_cost'=>$row['shipping_cost'],

                  // 'is_quantity_multiplied'=>$row['is_quantity_multiplied'],

                  // 'est_shipping_days'=>$row['est_shipping_days'],

                  // 'num_of_sale'=>$row['num_of_sale'],

                  // 'meta_title' => $row['meta_title'],

                  // 'meta_description' => $row['meta_description'],

                  // 'meta_img'=>$row['meta_img'],

                  // 'pdf'=>$row['pdf'],

                  'slug' => preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', strtolower($row['product_name']))) . '-' . Str::random(5),

                  // 'rating'=>$row['rating'],

                  // 'barcode'=>$row['barcode'],

                  // 'digital'=>$row['digital'],

                  // 'auction_product'=>$row['auction_product'],

                  // 'file_name'=>$row['file_name'],

                  // 'file_path'=>$row['file_path'],

                  'external_link'=>$row['external_link'],

                  'google_link'=>$row['google_link'],

                  // 'created_at'=>$row['created_at'],

                  // 'updated_at'=>$row['updated_at']

                );

                  $ProductTypeid = "";

                  $rowProType = isset($row['product_type']) ? $row['product_type'] : "";

                  if(!empty($allProTypeArr) && $rowProType != ""){
                   $rowProType = $this->proTypeSlug($rowProType);
                   $ProductTypeid = isset($allProTypeArr[$rowProType]['id'])?$allProTypeArr[$rowProType]['id']: "";
                   $ProTyCustom = isset($allProTypeArr[$rowProType]['custom'])?$allProTypeArr[$rowProType]['custom']: "";
                   // dd($ProTyCustom);
                  if($ProTyCustom!='')
                  {
                   foreach ($ProTyCustom as $ProCustKey => $ProCustVal) {
                       $customVal = isset($row[$ProCustKey]) ? $row[$ProCustKey] : '';
                       if($customVal != ''){
                         $proCreateArr[$ProCustVal] = $customVal;
                       }
                    }
                  }
                }
                 $proCreateArr['product_type_id'] = $ProductTypeid;

                // product create new start
                $product_name = $row['product_name'];

                if ($stocks != "") {

                 $prodId = isset($ProductArr[$stocks]) ? $ProductArr[$stocks] : "";

                  if ($prodId > 0) {

                    $user = Auth::user();
                    $curr_uid = $user->id;
                    $curr_name = $user->name;

                    $Actproduct = Product::where('id',$prodId)->firstOrFail();
                    // echo "<pre>";
                    // print_r($Actproduct);
                    $proActConID = $Actproduct->productcondition_id;
                    $proActstock = $Actproduct->stock_id;
                    $proActbrand = $Actproduct->brand_id;
                    $proActmodel = $Actproduct->model;
                    $proActcategory = $Actproduct->category_id;
                    $proActsize = $Actproduct->size;
                    $proActmetal = $Actproduct->metal;
                    $proActweight = $Actproduct->weight;
                    $proActpartner = $Actproduct->partner;
                    $proActwarehouse = $Actproduct->warehouse_id;
                    $proActproduct_cost = $Actproduct->product_cost;
                    $proActcost_code = $Actproduct->cost_code;
                    $proActsaleCost = $Actproduct->unit_price;
                    $proActmsrp = $Actproduct->msrp;

                    foreach ($RProCArr as $RProCskey => $RProCsval) {
                      // code...
                      $RPcustomVal = isset($row[$RProCskey]) ? $row[$RProCskey] : '';
                      $ActcustomVal = isset($Actproduct->$RProCsval) ? $Actproduct->$RProCsval : '';
                      if($RPcustomVal != $ActcustomVal){
                        $proLog = new Activitylog();
                        $proLog->type = 'product';
                        $proLog->user_id = $curr_uid;
                        $proLog->prodcut_id = $Actproduct->id;

                        $proLog->activity = addslashes('STOCK ID '.$proActstock.' Such as '.$RProCskey.' was changed from '.$ActcustomVal.' to '.$RPcustomVal.' by '.$curr_name.' on');

                        $proLog->action = 'edited';
                        $proLog->save();
                      }
                    }


                    if($Productconditions_id != $proActConID && $proActConID > 0){
                      $proLog = new Activitylog();
                      $proLog->type = 'product';
                      $proLog->user_id = $curr_uid;
                      $proLog->prodcut_id = $Actproduct->id;

                      $old_p_data=Productcondition::findOrFail($proActConID);
                      $new_p_data=Productcondition::findOrFail($Productconditions_id);
                      $proLog->activity = addslashes('STOCK ID '.$proActstock.' Such as Condition was changed from '.$old_p_data->name.' to  '.$new_p_data->name.' by '.$curr_name.' on');

                      $proLog->action = 'edited';
                      $proLog->save();
                    }

                    // product Name
                    if($Actproduct->name != $product_name){
                        $proLog = new Activitylog();
                        $proLog->type = 'product';
                        $proLog->user_id = $curr_uid;
                        $proLog->prodcut_id = $Actproduct->id;

                        $proLog->activity = addslashes('STOCK ID '.$proActstock.' Such as Name was changed from '.$Actproduct->name.' to '.$product_name.' by '.$curr_name.' on');

                        $proLog->action = 'edited';
                        $proLog->save();
                      }
                      //Brand
                      if($proActbrand != $brands_id && $proActbrand > 0){

                        $proLog = new Activitylog();
                        $proLog->type = 'product';
                        $proLog->user_id = $curr_uid;
                        $proLog->prodcut_id = $Actproduct->id;
                          $new_brand_name=Brand::findOrFail($brands_id);
                          $old_brand_name=Brand::findOrFail($proActbrand);
                          $new_name=$new_brand_name->name;
                          $old_name=$old_brand_name->name;
                        $proLog->activity = addslashes('STOCK ID '.$proActstock.' Such as Brand was changed from '.$old_name.' to '.$new_name.' by '.$curr_name.' on');

                        $proLog->action = 'edited';
                        $proLog->save();

                      }

                      if($proActmodel != $row['model_number']){
                        $old_model = $row['model_number'];
                        $proLog = new Activitylog();
                        $proLog->type = 'product';
                        $proLog->user_id = $curr_uid;
                        $proLog->prodcut_id = $Actproduct->id;
                        $proLog->activity = addslashes('STOCK ID '.$proActstock.' Such as Model was changed from '.$proActmodel.' to '.$old_model.' by '.$curr_name.' on');
                        $proLog->action = 'edited';
                        $proLog->save();
                      }

                      if($proActcategory != $category && $proActcategory > 0){

                        $proLog = new Activitylog();
                        $proLog->type = 'product';
                        $proLog->user_id = $curr_uid;
                        $proLog->prodcut_id = $Actproduct->id;
                        $old_category=Category::findOrFail($proActcategory);
                        $new_category=Category::findOrFail($category);
                        $old_c_name=$old_category->name;
                        $new_c_name=$new_category->name;

                        $proLog->activity = addslashes('STOCK ID '.$proActstock.' Such as Category was changed from '.$old_c_name.' to  '.$new_c_name.' by '.$curr_name.' on');
                        $proLog->action = 'edited';
                        $proLog->save();

                      }

                      if($proActsize != $size && $proActsize > 0){

                        $proLog = new Activitylog();
                        $proLog->type = 'product';
                        $proLog->user_id = $curr_uid;
                        $proLog->prodcut_id = $Actproduct->id;

                        $old_data=Product::where('size',$proActsize)->first();
                        $new_data=Product::where('size',$size)->first();
                        $old_size=$old_data->size;
                        $new_siz=$new_data->size;

                        $proLog->activity = addslashes('STOCK ID '.$proActstock.' Such as Size was changed from '.$old_size.' to  '.$new_siz.' by '.$curr_name.' on');
                        $proLog->action = 'edited';
                        $proLog->save();

                      }

                      if($row['metal'] != $proActmetal){

                        $old_metal = $proActmetal;

                        $proLog = new Activitylog();
                        $proLog->type = 'product';
                        $proLog->user_id = $curr_uid;
                        $proLog->prodcut_id = $Actproduct->id;
                        $proLog->activity = addslashes('STOCK ID '.$proActstock.' Such as Metal was changed from '.$old_metal.' to  '.$row['metal'].' by '.$curr_name.' on');
                        $proLog->action = 'edited';
                        $proLog->save();

                      }


                      if($row['weight'] != $proActweight){

                        $old_weight = $proActweight;
                        $proLog = new Activitylog();
                        $proLog->type = 'product';
                        $proLog->user_id = $curr_uid;
                        $proLog->prodcut_id = $Actproduct->id;
                        $proLog->activity = addslashes('STOCK ID '.$proActstock.' Such as Weight was changed from '.$old_weight.' to  '.$row['weight'].' by '.$curr_name.' on');
                        $proLog->action = 'edited';
                        $proLog->save();

                      }


                      if($partners != $proActpartner){

                        $old_partner = $proActpartner;
                        $proLog = new Activitylog();
                        $proLog->type = 'product';
                        $proLog->user_id = $curr_uid;
                        $proLog->prodcut_id = $Actproduct->id;
                        $proLog->activity = addslashes('STOCK ID '.$proActstock.' Such as Partner was changed from '.$old_partner.' to  '.$partners.' by '.$curr_name.' on');
                        $proLog->action = 'edited';
                        $proLog->save();

                      }

                      if($proActwarehouse != $warehouse_id && $proActwarehouse > 0){

                        $proLog = new Activitylog();
                        $proLog->type = 'product';
                        $proLog->user_id = $curr_uid;
                        $proLog->prodcut_id = $Actproduct->id;
                        $old_warehouse=Warehouse::findOrFail($proActwarehouse);
                        $new_warehouse=Warehouse::findOrFail($warehouse_id);
                        $proLog->activity = addslashes('STOCK ID '.$proActstock.' Such as Warehouse was changed from '.$old_warehouse->name.' to  '.$new_warehouse->name.' by '.$curr_name.' on');
                        $proLog->action = 'edited';
                        $proLog->save();

                      }

                      if($row['product_cost'] != $proActproduct_cost){

                        $old_product_cost = $proActproduct_cost;
                        $proLog = new Activitylog();
                        $proLog->type = 'product';
                        $proLog->user_id = $curr_uid;
                        $proLog->prodcut_id = $Actproduct->id;
                        $proLog->activity = addslashes('STOCK ID '.$proActstock.' Such as Product Cost was changed from '.$old_product_cost.' to  '.$row['product_cost'].' by '.$curr_name.' on');
                        $proLog->action = 'edited';
                        $proLog->save();

                      }

                      if($row['cost_code'] != $proActcost_code){

                        $old_cost_code = $proActcost_code;
                        $proLog = new Activitylog();
                        $proLog->type = 'product';
                        $proLog->user_id = $curr_uid;
                        $proLog->prodcut_id = $Actproduct->id;
                        $proLog->activity = addslashes('STOCK ID '.$proActstock.' Such as Cost Code was changed from '.$old_cost_code.' to  '.$row['cost_code'].' by '.$curr_name.' on');
                        $proLog->action = 'edited';
                        $proLog->save();

                      }

                      if($row['sale_price'] != $proActsaleCost){
                        $proLog = new Activitylog();
                        $proLog->type = 'product';
                        $proLog->user_id = $curr_uid;
                        $proLog->prodcut_id = $Actproduct->id;
                        $proLog->activity = addslashes('STOCK ID '.$proActstock.' Such as Sale Price was changed from '.$proActsaleCost.' to  '.$row['sale_price'].' by '.$curr_name.' on');
                        $proLog->action = 'edited';
                        $proLog->save();

                      }


                      if($row['msrp'] != $proActmsrp){

                        $proLog = new Activitylog();
                        $proLog->type = 'product';
                        $proLog->user_id = $curr_uid;
                        $proLog->prodcut_id = $Actproduct->id;
                        $proLog->activity = addslashes('STOCK ID '.$proActstock.' Such as MSRP was changed from '.$proActmsrp.' to  '.$row['msrp'].' by '.$curr_name.' on');
                        $proLog->action = 'edited';
                        $proLog->save();

                      }


                     Product::updateOrCreate(

                              ['id' => $prodId],

                              $proCreateArr

                          );



                          $arrStock = array(

                                'qty' => $row['quantity'],

                                'price' => $row['sale_price']

                          );

                      if($serProNumber > 0){



                       }else{

                           // $arrStock['sku'] = $proSku;

                       }

                     ProductStock::updateOrCreate(

                              ['product_id' => $prodId],

                             // $arrStock

                          );

                  }else{

                   $productId = Product::create($proCreateArr);

                   if($serProNumber > 0){

                       $proSku = NULL;

                   }

                   $stockdata = ProductStock::create([

                         'product_id' => $productId->id,

                         'qty' => $row['quantity'],

                         'price' => $row['sale_price'],

                         'sku' => $proSku,

                     ]);

                  }

                  $productTpe = isset($row['product_type']) ? $row['product_type'] : "";

                  if($productTpe != ""){
                    $seqArr = isset($seqName[$productTpe]) ? $seqName[$productTpe] : array();
                    $prefix = isset($seqArr['prefix']) ? $seqArr['prefix'] : "";
                    $start = isset($seqArr['start']) ? $seqArr['start'] : "";
                    $sid = isset($seqArr['sid']) ? $seqArr['sid'] : "";
                    $currProseq = str_ireplace($prefix,"",$row['current_stock_id']);
                   if($prefix != ""){
                     if($currProseq >= $start){
                       $seqUpdate = Sequence::where('id',$sid)->firstOrFail();
                       $seqUpdate->sequence_start = $currProseq + 1;
                       $seqUpdate->save();
                     }
                   }
                  }

                }

            }
            $message = "Products imported successfully.";
            $messageto = "";
            if(!empty($ExistStock)){
              $ExistStock = implode(",",$ExistStock);
              $messageto .= " The uploaded Stock ".$ExistStock." does not appear to be writable because its serial already exist in other product and remain products has been Uploded Successfully.";
              flash(translate($messageto))->error();
            }else{
              flash(translate($message))->success();
            }



        }

    }



    public function model(array $row)

    {

        ++$this->rows;

    }

    public function getRowCount(): int

    {

        return $this->rows;

    }



    public function rules(): array

    {

        return [

             // Can also use callback validation rules

             'unit_price' => function($attribute, $value, $onFailure) {

                  if (!is_numeric($value)) {

                       $onFailure('Unit price is not numeric');

                  }

              }

        ];

    }

    public function file_get_contents_curl($url) {

      $ch = curl_init();



      curl_setopt($ch, CURLOPT_HEADER, 0);

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

      curl_setopt($ch, CURLOPT_URL, $url);



      $data = curl_exec($ch);

      curl_close($ch);



      return $data;

    }



    public function downloadThumbnail($url){

        try {



            $extension = pathinfo($url, PATHINFO_EXTENSION);

            $filename = 'uploads/all/'.Str::random(5).'.'.$extension;

            $fullpath = 'public/'.$filename;

            if (strpos($url, 'http') !== false) {

              $file = $this->file_get_contents_curl($url);

            }else{

              $file = file_get_contents($url);

            }



            file_put_contents($fullpath, $file);

            $upload = new Upload;

            $upload->extension = strtolower($extension);



            $upload->file_original_name = $filename;

            $upload->file_name = $filename;

            $upload->user_id = Auth::user()->id;

            $upload->type = "image";

            $upload->file_size = filesize(base_path($fullpath));

            $upload->save();



            return $upload->id;

        } catch (\Exception $e) {

            //dd($e);

        }

        return null;

    }

}
