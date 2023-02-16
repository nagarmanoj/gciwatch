<?php
namespace App;
use App\Memo;
use App\Product;
use App\Seller;
use App\JobOrder;
use App\MemoDetail;
use App\RetailReseller;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
class SupplierExcel implements FromCollection, WithMapping, WithHeadings
{
     function __construct() 
    {
    }
    public function collection()
    {
        $supplierQry= Seller::select('users.name','users.email','sellers.phone','sellers.company',DB::raw('sum(products.unit_price) as  unit_price'),DB::raw('sum(product_stocks.qty) as  qty'),'products.supplier_id','sellers.id')
                    ->leftJoin('users','users.id','=','sellers.user_id')
                    ->leftJoin('products','products.supplier_id','users.id')
                    ->leftJoin('product_stocks','product_stocks.product_id','=','products.id')
                    ->groupBy('sellers.company')
                    ->orderBy('sellers.id','DESC')
        ->get();
        return $supplierQry;
    }
    public function headings(): array
    {
        return [
           'Company',
           'Name ',
           'Phone',
           'Email Address',
           'Total Purchases',
           'Total Amount',
        ];

    }



    /**

    * @var supplierQry $supplierqry

    */

    public function map($supplierqry): array

    {
        
        setlocale(LC_MONETARY,"en_US");
        $subtotal=money_format("%(#1n", $supplierqry->unit_price)."\n";
        return [
                $supplierqry->company,
                $supplierqry->name,
                $supplierqry->phone,
                $supplierqry->email,
                $supplierqry->qty,
                $subtotal,
            ];
    }

}

