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
class PastDueJobExcel implements FromCollection, WithMapping, WithHeadings
{
     function __construct() 
    {
    }
    public function collection()
    { 
        $PastDueJob=JobOrder::select('job_orders.*','job_order_details.stock_id','job_order_details.estimated_date_return as estimated_date','job_order_details.bag_number','job_order_details.model_number','job_order_details.serial_number','job_order_details.date_of_return','job_order_details.bag_status','job_order_details.created_at as enter_date','retail_resellers.customer_name')
        ->leftJoin('job_order_details', 'job_order_details.job_order_id', '=', 'job_orders.id')
        ->leftJoin('retail_resellers', 'retail_resellers.id', '=', 'job_orders.company_name')
        ->groupBy('job_order_details.job_order_id')
        ->orderBy('job_orders.id', 'desc')
        ->where('job_orders.job_status','1')
        ->get();
        return $PastDueJob;
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

    * @var PastDueJob $pastDueJob

    */

    public function map($pastDueJob): array

    {
        // dd($pastDueJob);
        $job_order_status="";
        if($pastDueJob->job_status==1)
        {
            $job_order_status= "Post Due";
        }
        elseif($pastDueJob->job_status==2)
        {
            $job_order_status= "Open";
        }
        elseif($pastDueJob->job_status==3)
        {
            $job_order_status= "Pendding";
        }
        elseif($pastDueJob->job_status==4)
        {
            $job_order_status= "On Hold";
        }
        elseif($pastDueJob->job_status==5)
        {
            $job_order_status= "Close";
        }
        if(!empty($pastDueJob->date_of_return))
        {
            $returnDate= date('m/d/y', strtotime($pastDueJob->date_of_return));
        }
        else
        {
            $returnDate= $pastDueJob->date_of_return;
        }
        setlocale(LC_MONETARY,"en_US");
        $subtotal=money_format("%(#1n", $pastDueJob->total_actual_cost)."\n";
        return [
                $pastDueJob->customer_name,
                $pastDueJob->bag_number,
                $pastDueJob->job_order_number,
                $pastDueJob->model_number,
                $pastDueJob->serial_number,
                $pastDueJob->stock_id,
                date('m/d/y', strtotime($pastDueJob->enter_date)),
                date('m/d/y', strtotime($pastDueJob->estimated_date)),
                $returnDate,
                $subtotal,
                $job_order_status,
            ];
    }

}
