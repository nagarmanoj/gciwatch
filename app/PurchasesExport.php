<?php
namespace App;
use App\Product;
use App\Models\Producttype;
use App\Memo;
use App\MemoDetail;

use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\FromQuery;

use Maatwebsite\Excel\Concerns\WithMapping;

use Maatwebsite\Excel\Concerns\WithHeadings;
class PurchasesExport implements FromCollection, WithMapping, WithHeadings

{

    protected $ids;

    function __construct($ids) {

        $this->ids = $ids;

    }

    public function collection()

    {

        $Product = Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop','category','productcondition','productType')
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
        ->select('products.*','warehouse.name as warehouseName','users.name as supplier_name','categories.name as catName','site_options.low_stock','memo_details.product_id','memo_details.item_status','product_types.listing_type','brands.name as brandName','product_stocks.sku','product_stocks.qty','productconditions.name as productconditions_name','memos.memo_number')
        ->leftJoin('memo_details','memo_details.product_id','=','products.id')
        ->leftJoin('memos', 'memos.id', '=', 'memo_details.memo_id')
        ->whereIn('products.id',$this->ids)
        ->get();
        return $Product;
    }
    public function headings(): array
    {
        $proHeading= [
            'Memo Number',
            'Memo Status',
            'product type',
            'current stock id',
            'Product Name',
            'serial',
            'Quantity',
            'condition',
            'Brand',
            'model number',
            'Product Cost',
            'cost code',
            'MSRP',
            'sale price',
            'Category',
            // 'subcategory',
            'Metal',
            'Warehouse',
            'size',
            'Weight',
            'Supplier',
            'vendor doc number',
            'Unit',
            'DOP',
            'Paper/Cert',
            'Partner',
            'Gallery Images',
            'Tags',
            'Product Description',
            // 'Serial LTR',
            // 'Age',
            // 'Dial',
            // 'Bezel',
            // 'Band',
            // 'Screw Count',
            // 'Band Grade',
            // 'product details',
            // 'Other Notes',
            // 'Featured',
            // 'Product Status',
            // 'Images',
            // 'Referense',
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
    //   dd($product);
    $memonumber =$product->memo_number;
    $memostatus='';
    $photos_array='';
    $gallery_img=explode(",",trim($product->photos));
    foreach($gallery_img as $photos){
        $photos_array.=uploaded_asset($photos)." , ";
        }

                $memo_detail_status=$product->item_status;
                if ($product->is_repair=='1') {
                    $memostatus="Repair";
                  }
                  else if($product->qty >=1)
                {
                    $memostatus="Available";
                }

               else if($product->item_status=='1')
                {
                    $memostatus='Memo';
                }
                else if($product->item_status=='0')
                {
                    $memostatus='Memo';
                }
                else if($product->item_status=='2')
                {
                    $memostatus='Invoice';
                }
                else if($product->item_status=='3')
                {
                    $memostatus='Available';
                    $memonumber = '';
                }
                else if($product->item_status=='4')
                {
                    $memostatus='Trade';
                }
                else if($product->item_status=='5')
                {
                    $memostatus='Void';
                }
                else if($product->item_status=='6')
                {
                    $memostatus='TradeNGO';
                }
                else
                {
                    $memostatus="Not Available";
                }
                $product_tag=isset($product->tags)?$product->tags:"";
            $product_tags="";
            if(unserialize($product_tag)==false){
             $product_tags=$product_tag;
            }
            else{
              $product_tags= implode(',', unserialize($product_tag));
            }

            //  dd($memo_detail_status);
        //       $memo_id=$memos_details->memo_id;
        //      $memos_number=Memo::where('id','=',$memo_id)->first();
        // dd($memos_number);
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
    //    if(!empty( $product->memo_number))
    //    {
    //       $memoNumber= $product->memo_number;
    //    }
       return [
            $memonumber,
            $memostatus,
            $product->listing_type,
            $product->stock_id,
            $product->name,
            $product->sku,
            $product->qty,
            $product->productconditions_name,
            $product->brandName,
            $product->model,
            $product->product_cost,
            $product->cost_code,
            $product->msrp,
            $product->unit_price,
            $product->catName,
            $product->metal,
            $product->warehouseName,
            $product->size,
            $product->weight,
            $product->supplier_name,
            $product->vendor_doc_number,
            $product->unit,
            $product->dop,
            $product->paper_cart,
            $product->partner,
            $photos_array,
            $product_tags,
            strip_tags(html_entity_decode($product->description)),
            $product->custom_1,
            $product->custom_2,
            $product->custom_3,
            $product->custom_4,
            $product->custom_5,
            $product->custom_6,
            $product->custom_7,

        ];
    }
}
