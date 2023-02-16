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
class CustomerReportExcel implements FromCollection, WithMapping, WithHeadings
{
     function __construct() 
    {
    }
    public function collection()
    {  
        $Customer= Memo::select('memos.*',DB::raw('SUM(memos.sub_total) as memoSubTotal')  ,'retail_resellers.company','retail_resellers.customer_name as cu_name','memo_details.item_status','retail_resellers.id as company_id','retail_resellers.phone','retail_resellers.email')
        ->join('retail_resellers', 'memos.customer_name', '=', 'retail_resellers.id')
        ->join('memo_details', 'memos.id', '=', 'memo_details.memo_id')
        ->groupBy('retail_resellers.company')
        ->get();
        return $Customer;
    }
    public function headings(): array
    {
        return [
            'Company Name',
            'Customer Name',
            'Phone',
            'Email Address',
            'Total Sales ',
        ];

    }



    /**

    * @var Customer $customer

    */

    public function map($customer): array

    {
        setlocale(LC_MONETARY,"en_US");
        $memoSubTotal=money_format("%(#1n",$customer->memoSubTotal)."\n" ;
        return [
                $customer->company,
                $customer->cu_name,
                $customer->phone,
                $customer->email,
                $memoSubTotal,
            ];
    }

}
