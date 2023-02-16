<?php



namespace App;



use App\Transfer;

use App\TransferItem;

use App\Product;

use App\Models\Warehouse;

use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\FromQuery;

use Maatwebsite\Excel\Concerns\WithMapping;

use Maatwebsite\Excel\Concerns\WithHeadings;



class TransfersExport implements FromCollection, WithMapping, WithHeadings

{

    protected $ids;

    function __construct($ids) {

        $this->ids = $ids;

    }

    public function collection()

    {

    //    $Transfers=$transfer = Transfer::select('transfers.*')

    //    ->whereIn('transfers.id',$this->ids)

    //    ->get();







    $Transfers=TransferItem::select('transfer_items.*','products.name','products.stock_id','products.model','products.weight','products.paper_cart','products.custom_1','products.custom_2','products.custom_3','products.custom_4','products.custom_5','products.custom_6','products.custom_7','products.custom_8','products.custom_9','products.custom_10','transfers.transfer_no','transfers.from_warehouse_name','transfers.to_warehouse_name','transfers.grand_total','transfers.date','transfers.status as t_status','product_stocks.sku')

        ->leftJoin('products', 'products.id', '=', 'transfer_items.product_id')
        ->leftJoin('product_stocks','products.id','=','product_stocks.product_id')

        ->join('transfers','transfers.id','=','transfer_items.transfer_id')

        ->whereIn('transfer_items.transfer_id',$this->ids)

        ->get();

        return $Transfers;

    }



    public function headings(): array

    {

      return [

            'Date',

            'Reference No',
            // 'Serial  No.',

            'From Warehouse',

            'To Warehouse',

            'Stock Id',

            'Product Details',

            'Grand Total',

            'Status',



        ];

    }



    /**

    * @var Transfers $transfers

    */

    public function map($transfers): array

    {

        // dd($transfers);

        if($transfers->t_status == 1)

        {

           $statu='Complete';

        }

        elseif($transfers->t_status == 2)

        {

           $statu='Pending';

        }

        else

        {

           $statu='Sent';

        }

        $custom1="";

        $custom2="";$custom3="";$custom4="";$custom5="";$custom6="";$custom7="";$custom8="";$custom9="";$custom10="";$model="";$weight="";$paper_cart="";

        if(!empty($transfers->custom_1))

        {

             $custom1=$transfers->custom_1.'-';

        }

        if(!empty($transfers->custom_2))

        {

             $custom2=$transfers->custom_2.'-';

        }

        if(!empty($transfers->custom_3))

        {

             $custom3=$transfers->custom_3.'-';

        }

        if(!empty($transfers->custom_4))

        {

             $custom4=$transfers->custom_4.'-';

        }

        if(!empty($transfers->custom_5))

        {

            $custom5=$transfers->custom_5.'-';

        }

        if(!empty($transfers->custom_6))

        {

            $custom6=$transfers->custom_6.'-';

        }

        if(!empty($transfers->custom_7))

        {

            $custom7=$transfers->custom_7.'-';

        }

        if(!empty($transfers->custom_8))

        {

            $custom8=$transfers->custom_8.'-';

        }

        if(!empty($transfers->custom_9))

        {

            $custom9=$transfers->custom_9.'-';

        }

        if(!empty($transfers->custom_10))

        {

            $custom10=$transfers->custom_10.'-';

        }

        if(!empty($transfers->model))

        {

           $model= $transfers->model.'-';

        }

        if(!empty($transfers->weight))

        {

           $weight= $transfers->weight.'-';

        }

        if(!empty($transfers->paper_cart))

        {

           $paper_cart= $transfers->paper_cart.'-';

        }
        if(!empty($transfers->sku))

        {

           $sku= $transfers->sku.'-';

        }



        // dd($transfers);exit;

        // echo $transfers->grand_total."<br>";

        setlocale(LC_MONETARY,"en_US");

        $grandTotal=money_format('%(#1n',$transfers->grand_total)."\n";

        $product_details= $model.$sku.$weight.$paper_cart. $custom1. $custom2. $custom3. $custom4. $custom5. $custom6. $custom7. $custom8. $custom9. $custom10.'('. $transfers->stock_id.')';

        // echo $product_details;exit;

        return [

            date('m/d/20y', strtotime($transfers->date)),

            $transfers->transfer_no,
            // $transfers->sku,

            $transfers->from_warehouse_name,

            $transfers->to_warehouse_name,

            $transfers->stock_id,

            $product_details,

            $grandTotal,

            $statu,

        ];

    }

}
