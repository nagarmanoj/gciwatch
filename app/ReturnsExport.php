<?php

namespace App;
use App\Product;
use App\ProductStock;

use App\ReturnProd;

use App\ReturnItems;

use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\FromQuery;

use Maatwebsite\Excel\Concerns\WithMapping;

use Maatwebsite\Excel\Concerns\WithHeadings;



class ReturnsExport implements FromCollection, WithMapping, WithHeadings

{

    protected $ids;

    function __construct($ids) {

        $this->ids = $ids;





    }

    public function collection()

    {

       $Returns=ReturnItems::select('return_items.*','products.name','products.stock_id','products.model','products.weight','products.paper_cart','products.custom_1','products.custom_2','products.custom_3','products.custom_4','products.custom_5','products.custom_6','products.custom_7','products.custom_8','products.custom_9','products.custom_10','return_prods.return_date','return_prods.reference_no as re_no','return_prods.supplier_id','return_prods.return_total','users.name','product_stocks.sku')

       ->leftJoin('products', 'products.id', '=', 'return_items.product_id')
       ->leftJoin('product_stocks','products.id','=','product_stocks.product_id')

       ->join('return_prods','return_prods.id','=','return_items.return_id')

       ->join('users', 'users.id', '=', 'return_prods.supplier_id')

       ->whereIn('return_items.return_id',$this->ids)

       ->get();

    //    ReturnProd::select('return_prods.id as ret_id','users.*')

    //     ->join('users', 'users.id', '=', 'return_prods.supplier_id')

    //     ->whereIn('return_prods.id',$this->ids)

    //     ->get();

        return $Returns;

    }



    public function headings(): array

    {

       return [

            'Date',

            'Reference',
            // 'Serial  No.',

            'Supplier',

            'Stock Id',

            'Product Details',

            'Subtotal'

        ];

    }



    /**

    * @var Returns $returns

    */

    public function map($returns): array

    {

        // dd($returns);

        $date=date('m/d/20y',strtotime($returns->return_date));

        setlocale(LC_MONETARY,"en_US");

        $subTotal=money_format("%(#1n", $returns->sub_total)."\n";

        $product_details= $returns->model.'-'.$returns->sku.'-'.$returns->weight.'-'.$returns->paper_cart.'-'.$returns->custom_1.'-'.$returns->custom_2.'-'.$returns->custom_3.'-'.$returns->custom_4.'-'.$returns->custom_5.'-'.$returns->custom_6.'-'.$returns->custom_7.'-'.$returns->custom_8.'-('.$returns->stock_id.')';

        return [

            $date,
            // $returns->sku,

            $returns->re_no,

            $returns->name,

            $returns->stock_id,

            $product_details,

            $subTotal,

        ];

    }

}
