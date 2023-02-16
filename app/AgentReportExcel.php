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
class AgentReportExcel implements FromCollection, WithMapping, WithHeadings
{
     function __construct() {

    }

    public function collection()

    {

        $Agent=JobOrder::select('job_orders.*','job_order_details.stock_id','job_order_details.estimated_date_return as estimated_date','job_order_details.bag_number','job_order_details.model_number','job_order_details.serial_number','job_order_details.date_of_return','job_order_details.created_at as enter_date','agents.first_name as agent_name')
        ->leftJoin('job_order_details', 'job_order_details.job_order_id', '=', 'job_orders.id')
        ->leftJoin('agents', 'agents.id', '=', 'job_order_details.agent')
        ->groupBy('job_order_details.job_order_id')
        ->orderBy('job_orders.id', 'desc')
        ->get();

        return $Agent;

}



    public function headings(): array

    {

        return [
            'Agent',
            'Bag Number',
            'Job Order',
            'Model',
            'Serial',
            'Stock ID',
            'Date Entered',
            'Due Date',
            'Date Returned',
            'Total Repair Cost',
            'Status',

        ];

    }



    /**

    * @var Agent $agent

    */

    public function map($agent): array

    {
        // dd($agent);
        $job_order_status="";
        if($agent->job_status==1)
        {
            $job_order_status= "Post Due";
        }
        elseif($agent->job_status==2)
        {
            $job_order_status= "Open";
        }
        elseif($agent->job_status==3)
        {
            $job_order_status= "Pendding";
        }
        elseif($agent->job_status==4)
        {
            $job_order_status= "On Hold";
        }
        elseif($agent->job_status==5)
        {
            $job_order_status= "Close";
        }
        if(!empty($agent->date_of_return))
        {
            $returnDate= date('m/d/y', strtotime($agent->date_of_return));
        }
        else
        {
            $returnDate= $agent->date_of_return;
        }
        setlocale(LC_MONETARY,"en_US");
        $subtotal=money_format("%(#1n", $agent->total_repair_cost)."\n";
        return [
                $agent->agent_name,
                $agent->bag_number,
                $agent->job_order_number,
                $agent->model_number,
                $agent->serial_number,
                $agent->stock_id,
                date('m/d/y', strtotime($agent->enter_date)),
                date('m/d/y', strtotime($agent->estimated_date)),
                $returnDate,
                $subtotal,
                $job_order_status,
            ];

    }

}

