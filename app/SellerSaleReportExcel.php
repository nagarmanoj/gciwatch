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
class SellerSaleReportExcel implements FromCollection, WithMapping, WithHeadings
{
     function __construct() 
    {
    }
    public function collection()
    {
       $Sellerreport= Memo::select('memos.*', 'retail_resellers.company','retail_resellers.customer_name as rcustomername','memo_payments.payment_name','retail_resellers.customer_group','product_stocks.sku','memo_details.item_status','product_types.listing_type')
        ->join('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
        ->join('memo_details', 'memos.id', '=', 'memo_details.memo_id')
        ->join('products', 'memo_details.product_id', '=', 'products.id')
        ->join('product_stocks','products.id','=','product_stocks.product_id')
        ->leftJoin('product_types','product_types.id','=','products.product_type_id')
        ->leftJoin('memo_payments', 'memo_payments.id', '=', 'memos.payment')
        ->selectRaw('GROUP_CONCAT(stock_id) as stocks')
        ->selectRaw('GROUP_CONCAT(model) as model_numbers')
        ->groupBy('memo_details.memo_id')
        ->orderBy('id', 'DESC')
        ->get();
        return $Sellerreport;
    }
    public function headings(): array
    {
        return [
            'Date',
            'Memo Number',
            'Status',
            'Customer Name',
            'Stock Id',
            'Listing Type',
            'Model Number',
            'Serial',
            'Sales Price',
        ];

    }



    /**

    * @var Sellerreport $sellerreport

    */

    public function map($sellerreport): array

    {
        // dd($sellerreport);
        $sellerreport->customer_name = $sellerreport->rcustomername;
        if($sellerreport->item_status==1 || $sellerreport->item_status==0)
        {
            $memostatus= "Memo";
        }
        elseif($sellerreport->item_status==2)
        {
            $memostatus= "INVOICE";
        }
        elseif($sellerreport->item_status==3)

        {

            $memostatus= "RETURN";

        }

        elseif($sellerreport->item_status==4)

        {

            $memostatus= "TRADE";

        }

        elseif($sellerreport->item_status==5)

        {

            $memostatus= "VOID";

        }

        elseif($sellerreport->item_status==6)

        {

            $memostatus= "TRADE NGD";

        }
        setlocale(LC_MONETARY,"en_US");
        $subtotal=money_format("%(#1n", $sellerreport->sub_total)."\n";
        return [
            date('m/d/y',strtotime($sellerreport->date)),
                $sellerreport->memo_number,
                $memostatus,
                $sellerreport->customer_name,
                $sellerreport->stocks,
                $sellerreport->listing_type,
                $sellerreport->model_numbers,
                $sellerreport->sku,
                $subtotal,
            ];
    }

}

