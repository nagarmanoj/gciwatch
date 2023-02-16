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
class OpenJobReportExport implements FromCollection, WithMapping, WithHeadings
{
     function __construct() 
    {
    }
    public function collection()
    {
        $OpenJob=JobOrder::select('job_orders.*','job_order_details.stock_id','job_order_details.estimated_date_return as estimated_date','job_order_details.bag_number','job_order_details.model_number','job_order_details.serial_number','job_order_details.date_of_return','job_order_details.bag_status','job_order_details.created_at as enter_date','retail_resellers.customer_name')
        ->leftJoin('job_order_details', 'job_order_details.job_order_id', '=', 'job_orders.id')
        ->leftJoin('retail_resellers', 'retail_resellers.id', '=', 'job_orders.company_name')
        ->groupBy('job_order_details.job_order_id')
        ->orderBy('job_orders.id', 'desc')
        ->where('job_orders.job_status','2')
        ->get();
        return $OpenJob;
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

    * @var OpenJob $openJob

    */

    public function map($openJob): array

    {
        // dd($openJob);
        $job_order_status="";
        if($openJob->job_status==1)
        {
            $job_order_status= "Post Due";
        }
        elseif($openJob->job_status==2)
        {
            $job_order_status= "Open";
        }
        elseif($openJob->job_status==3)
        {
            $job_order_status= "Pendding";
        }
        elseif($openJob->job_status==4)
        {
            $job_order_status= "On Hold";
        }
        elseif($openJob->job_status==5)
        {
            $job_order_status= "Close";
        }
        if(!empty($openJob->date_of_return))
        {
            $returnDate= date('m/d/y', strtotime($openJob->date_of_return));
        }
        else
        {
            $returnDate= $openJob->date_of_return;
        }
        setlocale(LC_MONETARY,"en_US");
        $subtotal=money_format("%(#1n", $openJob->total_actual_cost)."\n";
        return [
                $openJob->customer_name,
                $openJob->bag_number,
                $openJob->job_order_number,
                $openJob->model_number,
                $openJob->serial_number,
                $openJob->stock_id,
                date('m/d/y', strtotime($openJob->enter_date)),
                date('m/d/y', strtotime($openJob->estimated_date)),
                $returnDate,
                $subtotal,
                $job_order_status,
            ];
    }

}

