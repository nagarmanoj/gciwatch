<?php



namespace App;



use App\Memo;

use App\Product;

use App\MemoDetail;

use App\RetailReseller;

use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\FromQuery;

use Maatwebsite\Excel\Concerns\WithMapping;

use Maatwebsite\Excel\Concerns\WithHeadings;

use Illuminate\Support\Facades\DB;



class MemoExport implements FromCollection, WithMapping, WithHeadings

{

    protected $ids ;



    function __construct($ids ) {

        $this->ids = $ids ;

    }

    public function collection()

    {

        $memoData =    Memo::select('memos.*', 'retail_resellers.company','retail_resellers.customer_name as rcustomername','memo_payments.payment_name','retail_resellers.customer_group','product_stocks.sku','memo_details.item_status')
        ->join('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
        ->join('memo_details', 'memos.id', '=', 'memo_details.memo_id')
        ->join('products', 'memo_details.product_id', '=', 'products.id')
        ->join('product_stocks','products.id','=','product_stocks.product_id')
        ->leftJoin('memo_payments', 'memo_payments.id', '=', 'memos.payment')
        ->selectRaw('GROUP_CONCAT(stock_id) as stocks')
        ->selectRaw('GROUP_CONCAT(model) as model_numbers')
        ->groupBy('memo_details.memo_id')
        ->whereIn('memos.id',$this->ids)
        ->orderBy('id', 'DESC')

        ->get();

        return $memoData;

}



    public function headings(): array

    {

        return [

            'memo_number',

            'company',

            // 'customer_name',

            'reference',

            'stocks',

            'model_numbers',
            'Serial Number',

            'payment',

            'sub_total',

            'tracking',
            // 'memo_status',

            'due_date',

            'date',

        ];

    }



    /**

    * @var Memo $memo

    */

    public function map($memoData): array

    {
        // dd($memoData);
        $company="";
        if($memoData->customer_group=="reseller")
        {
            $company=$memoData->company;
        }
        else
        {
            $company=$memoData->rcustomername;
        }
        setlocale(LC_MONETARY,"en_US");
        $subtotal=money_format("%(#1n", $memoData->sub_total)."\n";
        return [

            $memoData->memo_number,

            $company,

            // $memoData->customer_name,

            $memoData->reference,

            $memoData->stocks,

            $memoData->model_numbers,
            $memoData->sku,

            $memoData->payment_name,

            $subtotal,

            $memoData->tracking,

            // $memoData->memo_status,

            $memoData->due_date,

            $memoData->date,

            // $qty,

        ];

    }

}

