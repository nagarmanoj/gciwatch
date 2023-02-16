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
class ShortStockExcel implements FromCollection, WithMapping, WithHeadings
{
     function __construct() 
    {
    }
    public function collection()
    {  
        $Shortstock = MemoDetail::select('products.model','memo_details.product_qty',DB::raw('sum(product_stocks.qty) as stockqtysum'),'memo_details.item_status',DB::raw('sum(memo_details.product_qty) as  memoqtysum'))
                    ->leftJoin('products','products.id','=','memo_details.product_id')
                    ->leftJoin('product_stocks','products.id','=','product_stocks.product_id')
                    ->groupBy('products.model')
                    ->where('memo_details.item_status','0')
                    ->orWhere('memo_details.item_status','1')
                    ->orWhere('memo_details.item_status','2')
                    ->orWhere('memo_details.item_status','4')
                    ->orWhere('memo_details.item_status','6')
                    ->get();
        return $Shortstock;
    }
    public function headings(): array
    {
        return [
            'Model Number',
            'Sold Quantity',
            'On Hold',
            'Available Quantity',
            'Total Quantity',

        ];

    }



    /**

    * @var Shortstock $shortstock

    */

    public function map($shortstock): array

    {
        
        $totalModelQty = 0;
        $ModelQty="";
        if(!empty($shortstock->model)){
        $mt = $shortstock->model;
        $ModelQty = DB::table('products')
                    ->leftJoin('product_stocks','products.id','=','product_stocks.product_id')
                    ->where('model',$mt)
                    ->select('product_stocks.qty')
                    ->get();
        }

        $sumVal1 = 0;
        $sumVal2 = 0;
        if($shortstock->item_status == 2 || $shortstock->item_status == 4 || $shortstock->item_status == 6){
        $sumVal1 = $shortstock->memoqtysum;
        }
        if($shortstock->item_status == 0 || $shortstock->item_status == 1){
        $sumVal2 = $shortstock->memoqtysum;
        }
        $sumQtyVal = $sumVal1 + $sumVal2;
        $modenlNumber='';
        if($shortstock->model!='')
        {
            $modenlNumber=$shortstock->model;
        }


        if($shortstock->item_status == 2 || $shortstock->item_status == 4 || $shortstock->item_status == 6)
        {
                $soldqty=$shortstock->memoqtysum;
        }
        else
        {
            $soldqty=0;
        }
        if($shortstock->item_status == 0 || $shortstock->item_status == 1)
        {
            $holdqty=$shortstock->memoqtysum;
        }
        else
        {
            $holdqty=0;
        }
        $availabl="";
      $totalqty="";
        // dd($shortstock);
        return [
                $modenlNumber,
                $soldqty,
                $holdqty,
                $availabl,
                $totalqty,
            ];
    }

}
