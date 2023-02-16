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
class CompleteReportExcel implements FromCollection, WithMapping, WithHeadings
{
     function __construct() 
    {
    }
    public function collection()
    {
        $Complete=JobOrder::select('job_orders.*','job_order_details.stock_id','job_order_details.estimated_date_return as estimated_date','job_order_details.bag_number','job_order_details.model_number','job_order_details.serial_number','job_order_details.date_of_return','job_order_details.created_at as enter_date','retail_resellers.customer_name')
        ->leftJoin('job_order_details', 'job_order_details.job_order_id', '=', 'job_orders.id')
        ->leftJoin('retail_resellers', 'retail_resellers.id', '=', 'job_orders.company_name')
        ->groupBy('job_order_details.job_order_id')
        ->orderBy('job_orders.id', 'desc')
        ->where('job_orders.job_status','5')
        ->get();
        return $Complete;
    }
    public function headings(): array
    {
        return [
            'Customer',
            'Bag Number',
            'Job Order',
            'Model',
            'Serial',
            'Stock ID',
            'Date Entered',
            'Due Date',
            'Date Returned',
            'Actual Cost',
            'Status',

        ];

    }



    /**

    * @var Complete $complete

    */

    public function map($complete): array

    {
        // dd($complete);
        $job_order_status="";
        if($complete->job_status==1)
        {
            $job_order_status= "Post Due";
        }
        elseif($complete->job_status==2)
        {
            $job_order_status= "Open";
        }
        elseif($complete->job_status==3)
        {
            $job_order_status= "Pendding";
        }
        elseif($complete->job_status==4)
        {
            $job_order_status= "On Hold";
        }
        elseif($complete->job_status==5)
        {
            $job_order_status= "Close";
        }
        if(!empty($complete->date_of_return))
        {
            $returnDate= date('m/d/y', strtotime($complete->date_of_return));
        }
        else
        {
            $returnDate= $complete->date_of_return;
        }
        setlocale(LC_MONETARY,"en_US");
        $subtotal=money_format("%(#1n", $complete->total_actual_cost)."\n";
        return [
                $complete->customer_name,
                $complete->bag_number,
                $complete->job_order_number,
                $complete->model_number,
                $complete->serial_number,
                $complete->stock_id,
                date('m/d/y', strtotime($complete->enter_date)),
                date('m/d/y', strtotime($complete->estimated_date)),
                $returnDate,
                $subtotal,
                $job_order_status,
            ];
    }

}

