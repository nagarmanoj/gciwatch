<?php
namespace App;
use App\Memo;
use App\Product;
use App\JobOrder;
use App\MemoDetail;
use App\RetailReseller;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
class ProductReportExcel implements FromCollection, WithMapping, WithHeadings
{
     function __construct() 
    {
    }
    public function collection()
    {  
        $PurchasesProduct=Product::with('reviews', 'brand', 'stocks', 'user', 'user.shop','category','productcondition','productType')
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
        ->select('products.*','warehouse.name as warehouseName','categories.name as categories_name','users.name as supplienName','site_options.low_stock','memo_details.product_id','memo_details.item_status','product_types.listing_type','brands.name as brandName','product_stocks.sku','product_stocks.qty','productconditions.name as productconditions_name','memos.customer_name as customer_name_id','retail_resellers.customer_group','retail_resellers.company','retail_resellers.customer_name','product_types.product_type_name')
          ->leftJoin('memo_details','memo_details.product_id','=','products.id')
          ->leftJoin('memos', 'memos.id', '=', 'memo_details.memo_id')
          ->leftJoin('retail_resellers','retail_resellers.id' , '=' , 'memos.customer_name')
        ->get();
        return $PurchasesProduct;
    }
    public function headings(): array
    {
        return [
            'Stock Id',
            'Status',
            'Company Name',
            'Category',
            'Listing Name',
            'Brand',
            'Model ',
            'Serial',
            'Partner',
            ' Cost Code',
            'Sale Price',

        ];

    }



    /**

    * @var PurchasesProduct $purchasesProduct

    */

    public function map($purchasesProduct): array

    {
        $customer_name='';
        if(isset($purchasesProduct->customer_group))
        {
            
            if($purchasesProduct->customer_group=="reseller")
            {
                $customer_name= $purchasesProduct->company;
            }
            else
            {
                $customer_name= $purchasesProduct->customer_name;
            }
        }
        $memoStatus="";
        if($purchasesProduct->qty>0 )
        {
            $memoStatus= "Available";
        }

        elseif($purchasesProduct->item_status==1)
        {
            $memoStatus= "Memo";
        }
        elseif($purchasesProduct->item_status=='0')
        {
            $memoStatus= "Memo";
        }
        elseif($purchasesProduct->item_status==2)
        {
            $memoStatus= "INVOICE";
        }
        elseif($purchasesProduct->item_status==3)
        {
        $memoStatus= "RETURN";
        }
        elseif($purchasesProduct->item_status==4)
        {
            $memoStatus= "TRADE";
        }
        elseif($purchasesProduct->item_status==5)
        {
            $memoStatus= "VOID";
        }
        elseif($purchasesProduct->item_status==6)
        {
            $memoStatus= "TRADE NGD";
        }
       
        setlocale(LC_MONETARY,"en_US");
        $cost_code=money_format("%(#1n",$purchasesProduct->cost_code)."\n" ;
        $unit_price=money_format("%(#1n",$purchasesProduct->unit_price)."\n" ;
        return [
                $purchasesProduct->stock_id,
                $memoStatus,
                $customer_name,
                $purchasesProduct->categories_name,
                $purchasesProduct->listing_type,
                $purchasesProduct->brandName,
                $purchasesProduct->model,
                $purchasesProduct->sku,
                $purchasesProduct->partner,
                $cost_code,
                $unit_price,
            ];
    }

}
