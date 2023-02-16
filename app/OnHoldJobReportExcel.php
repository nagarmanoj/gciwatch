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
class OnHoldJobReportExcel implements FromCollection, WithMapping, WithHeadings
{
     function __construct() 
    {
    }
    public function collection()
    {
        $OnHoldJob=JobOrder::select('job_orders.*','job_order_details.stock_id','job_order_details.estimated_date_return as estimated_date','job_order_details.bag_number','job_order_details.model_number','job_order_details.serial_number','job_order_details.date_of_return','job_order_details.created_at as enter_date','retail_resellers.customer_name')
        ->leftJoin('job_order_details', 'job_order_details.job_order_id', '=', 'job_orders.id')
        ->leftJoin('retail_resellers', 'retail_resellers.id', '=', 'job_orders.company_name')
        ->groupBy('job_order_details.job_order_id')
        ->orderBy('job_orders.id', 'desc')
        ->where('job_orders.job_status','4')
        ->get();
        return $OnHoldJob;
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

    * @var OnHoldJob $onHoldJob

    */

    public function map($onHoldJob): array

    {
        // dd($onHoldJob);
        $job_order_status="";
        if($onHoldJob->job_status==1)
        {
            $job_order_status= "Post Due";
        }
        elseif($onHoldJob->job_status==2)
        {
            $job_order_status= "Open";
        }
        elseif($onHoldJob->job_status==3)
        {
            $job_order_status= "Pendding";
        }
        elseif($onHoldJob->job_status==4)
        {
            $job_order_status= "On Hold";
        }
        elseif($onHoldJob->job_status==5)
        {
            $job_order_status= "Close";
        }
        if(!empty($onHoldJob->date_of_return))
        {
            $returnDate= date('m/d/y', strtotime($onHoldJob->date_of_return));
        }
        else
        {
            $returnDate= $onHoldJob->date_of_return;
        }
        setlocale(LC_MONETARY,"en_US");
        $subtotal=money_format("%(#1n", $onHoldJob->total_actual_cost)."\n";
        return [
                $onHoldJob->customer_name,
                $onHoldJob->bag_number,
                $onHoldJob->job_order_number,
                $onHoldJob->model_number,
                $onHoldJob->serial_number,
                $onHoldJob->stock_id,
                date('m/d/y', strtotime($onHoldJob->enter_date)),
                date('m/d/y', strtotime($onHoldJob->estimated_date)),
                $returnDate,
                $subtotal,
                $job_order_status,
            ];
    }

}

