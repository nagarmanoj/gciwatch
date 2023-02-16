<?php
namespace App;
use App\Memo;
use App\Product;
use App\JobOrder;
use App\Seller;
use App\MemoDetail;
use App\RetailReseller;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
class SupplierDetailsExcel implements FromCollection, WithMapping, WithHeadings
{
     function __construct($id) 
    {
        $id= $id;
    }
    public function collection()
    {
        $supplierdetailsQry= Seller::select('sellers.*','products.stock_id','products.unit_price','products.published','memo_details.item_status','products.model','warehouse.name as warehouse_name','users.name as supplier_name','product_stocks.sku','product_stocks.qty','products.vendor_doc_number')
        ->leftJoin('users','users.id','=','sellers.user_id')
        ->leftJoin('products','products.supplier_id','users.id')
        ->leftJoin('product_stocks','product_stocks.product_id','=','products.id')
        ->leftJoin('memo_details','memo_details.product_id','=','products.id')
        ->leftJoin('warehouse','warehouse.id','=','products.warehouse_id')
        ->orderBy('sellers.id','DESC')
        ->where('sellers.id',$id)
        ->get();
        return $supplierdetailsQry;
    }
    public function headings(): array
    {
        return [
          'Date ',
          'Stock Id',
          'Status',
          'Model Number',
          'Serial',
          'Reference',
          'Warehouse',
          'Supplier',
          'Product Cost',
        ];

    }



    /**

    * @var supplierdetailsQry $supplierdetailsqry

    */

    public function map($supplierdetailsqry): array

    {
        // dd($supplierdetailsqry);
        $supplierdetailsqry->customer_name = $supplierdetailsqry->rcustomername;
        if($supplierdetailsqry->item_status==1 || $supplierdetailsqry->item_status==0)
        {
            $memostatus= "Memo";
        }
        elseif($supplierdetailsqry->item_status==2)
        {
            $memostatus= "INVOICE";
        }
        elseif($supplierdetailsqry->item_status==3)

        {

            $memostatus= "RETURN";

        }

        elseif($supplierdetailsqry->item_status==4)

        {

            $memostatus= "TRADE";

        }

        elseif($supplierdetailsqry->item_status==5)

        {

            $memostatus= "VOID";

        }

        elseif($supplierdetailsqry->item_status==6)

        {

            $memostatus= "TRADE NGD";

        }
        setlocale(LC_MONETARY,"en_US");
        $subtotal=money_format("%(#1n", $supplierdetailsqry->unit_price)."\n";
        return [
            date('m/d/y',strtotime($supplierdetailsqry->created_at)),
                $supplierdetailsqry->stock_id,
                $memostatus,
                $supplierdetailsqry->model,
                $supplierdetailsqry->sku,
                $supplierdetailsqry->vendor_doc_number,
                $supplierdetailsqry->warehouse_name,
                $supplierdetailsqry->supplier_name,
                $subtotal,
            ];
    }

}

