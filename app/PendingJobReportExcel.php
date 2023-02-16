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
class PendingJobReportExcel implements FromCollection, WithMapping, WithHeadings
{
     function __construct() 
    {
    }
    public function collection()
    {
        $Pending_job=JobOrder::select('job_orders.*','job_order_details.stock_id','job_order_details.estimated_date_return as estimated_date','job_order_details.bag_number','job_order_details.model_number','job_order_details.serial_number','job_order_details.date_of_return','job_order_details.created_at as enter_date','retail_resellers.customer_name')
        ->leftJoin('job_order_details', 'job_order_details.job_order_id', '=', 'job_orders.id')
        ->leftJoin('retail_resellers', 'retail_resellers.id', '=', 'job_orders.company_name')
        ->groupBy('job_order_details.job_order_id')
        ->orderBy('job_orders.id', 'desc')
        ->where('job_orders.job_status','3')
        ->get();
        return $Pending_job;
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

    * @var Pending_job $pending_job

    */

    public function map($pending_job): array

    {
        // dd($pending_job);
        $job_order_status="";
        if($pending_job->job_status==1)
        {
            $job_order_status= "Post Due";
        }
        elseif($pending_job->job_status==2)
        {
            $job_order_status= "Open";
        }
        elseif($pending_job->job_status==3)
        {
            $job_order_status= "Pendding";
        }
        elseif($pending_job->job_status==4)
        {
            $job_order_status= "On Hold";
        }
        elseif($pending_job->job_status==5)
        {
            $job_order_status= "Close";
        }
        if(!empty($pending_job->date_of_return))
        {
            $returnDate= date('m/d/y', strtotime($pending_job->date_of_return));
        }
        else
        {
            $returnDate= $pending_job->date_of_return;
        }
        setlocale(LC_MONETARY,"en_US");
        $subtotal=money_format("%(#1n", $pending_job->total_actual_cost)."\n";
        return [
                $pending_job->customer_name,
                $pending_job->bag_number,
                $pending_job->job_order_number,
                $pending_job->model_number,
                $pending_job->serial_number,
                $pending_job->stock_id,
                date('m/d/y', strtotime($pending_job->enter_date)),
                date('m/d/y', strtotime($pending_job->estimated_date)),
                $returnDate,
                $subtotal,
                $job_order_status,
            ];
    }

}

