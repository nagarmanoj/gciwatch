<?php
  namespace App;
  use App\Product;
  use App\Memo;
  use App\MemoDetail;
  use App\Models\Producttype;
  use Maatwebsite\Excel\Concerns\FromCollection;
  use Maatwebsite\Excel\Concerns\FromQuery;
  use Maatwebsite\Excel\Concerns\WithMapping;
  use Maatwebsite\Excel\Concerns\WithHeadings;
  class BarcodeProductExcel implements FromCollection, WithMapping, WithHeadings
  {
    protected $ids,$proHeadingPush;
    function __construct($ids) {
        $this->ids = $ids;
     }
    public function collection()
    {
        $Product =Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop','category','productcondition','productType')
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
            ->select('products.*','warehouse.name as warehouse_name','users.name as supplier_name','site_options.low_stock','memo_details.product_id','memo_details.item_status','product_types.listing_type','brands.name as brand_name','product_stocks.sku','product_stocks.qty','productconditions.name as productconditions_name','categories.name as categories_name','memo_details.memo_id as memosdetailId','memos.memo_number','retail_resellers.customer_group','retail_resellers.company','retail_resellers.customer_name')
              ->leftJoin('memo_details','memo_details.product_id','=','products.id')
              ->leftJoin('memos', 'memos.id', '=', 'memo_details.memo_id')
              ->LeftJoin('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
              ->whereIn('products.id',$this->ids)
              ->get();
              return $Product;
     }
    public function headings(): array
    {
        $proHeading= [
            'Barcode',
            'Referense',
            'Product Status',
            'Product Type',
            'Current Stock ID',
            'Product Name',
            'Condition',
            'Brand',
            'Model Number',
            'Serial',
            'Paper/Cert',
            'Category',
            'Size',
            'Metal',
            'Weight',
            'Partners',
            'Warehouse',
            'Unit',
            'Vendor Doc Number',
            'Supplier',
            'DOP',
            'Tags',
            'Product Cost',
            'Cost Code',
            'Sale Price',
            'MSRP',
            'Quantity',
            'Gallery Images',
            'Thumbnail Image',
            'Whatsapp Link',
            'Google Link',
            'Product Description',
        ];
        $firstId = $this->ids;
        $allssetID = isset($firstId[0])?$firstId[0]:"";
        if($allssetID != ""){
          $allPRodDataCS = Product::findOrFail($allssetID);
          $product_type_id = $allPRodDataCS->product_type_id;
          $allPRodData = Producttype::findOrFail($product_type_id);
          $proTyArr = [
            $allPRodData->custom_1,
            $allPRodData->custom_2,
            $allPRodData->custom_3,
            $allPRodData->custom_4,
            $allPRodData->custom_5,
            $allPRodData->custom_6,
            $allPRodData->custom_7,
          ];
        }
        if(!empty($proTyArr)){
          $proHeading = array_merge($proHeading,$proTyArr);
        }
            return $proHeading;
    }
   /**
    * @var Product $product
    */
   public function map($product): array
   {
     
     $stock_id=$product->stock_id;
     $barcode="";
      //  $barcode= DNS2D::getBarcodeHTML($stock_id, 'QRCODE', 'C39',1,30,'black', true);
        $memostatus='';
        $memonumber =$product->memo_number;
        $memo_detail_status=$product->item_status;
        if ($product->is_repair=='1') {
            $memostatus="Repair";
          }
          else if($memo_detail_status=='3')
          {
            $memostatus='Available';
            $memonumber = '';
          }
          elseif($product->qty >=1)
          {
            $memostatus="Available";
          }
          else if($memo_detail_status=='1')
          {
            $memostatus='Memo';
          }
          elseif( $memo_detail_status=='0')
          {
            $memostatus='Memo';
          }
          else if($memo_detail_status=='2')
          {
            $memostatus='Invoice';
          }
          else if($memo_detail_status=='4')
          {
             $memostatus='Trade';
          }
          else if($memo_detail_status=='5')
          {
             $memostatus='Void';
          }
          else if($memo_detail_status=='6')
          {
            $memostatus='TradeNGO';
          }
          else
          {
             $memostatus='Not Available';
          }
        $whatsapplink='https://api.whatsapp.com/send?phone=1213-373-4424&text=Hi Gciwatch, I would like to place inquiry for this Product -https://gcijewel.com/product/';
        $qty = 0;
        $photos_array='';
        $gallery_img=explode(",",trim($product->photos));
        foreach($gallery_img as $photos){
            $photos_array.=uploaded_asset($photos)." , ";
            }
        foreach ($product->stocks as $key => $stock) {
          $qty += $stock->qty;
        }
        $product_tag=isset($product->tags)?$product->tags:"";
        $product_tags="";
        if(unserialize($product_tag)==false){
             $product_tags=$product_tag;
            }
            else{
              $product_tags= implode(',', unserialize($product_tag));
            }
            $returnProHeading = [
                $barcode,
                $memonumber,
                $memostatus,
                $product->listing_type,
                $product->stock_id,
                 $product->name,
                $product->productconditions_name,
                $product->brand_name,
                $product->model,
                $product->sku, //product_stock
                $product->paper_cart,
                $product->categories_name,
                $product->size,
                $product->metal,
                $product->weight,
                $product->partner,
                $product->warehouse_name,
                $product->unit,
                $product->vendor_doc_number,
                $product->supplier_name,
                $product->dop,
                $product_tags,
                $product->product_cost,
                $product->cost_code,
                $product->unit_price,
                $product->msrp,
               $product->qty,
                $photos_array,
                 uploaded_asset($product->thumbnail_img),
                $whatsapplink.''.$product->slug,
                 $product->video_link,
                strip_tags(html_entity_decode($product->description)),
                $product->custom_1,
                 $product->custom_2,
                  $product->custom_3,
                $product->custom_4,
                $product->custom_5,
                $product->custom_6,
                $product->custom_7,
            ];
        return $returnProHeading;
    }
}

