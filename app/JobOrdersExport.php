<?php



namespace App;



use App\Models\Warehouse;

use App\JobOrder;

use App\JobOrderDetail;

use App\Product;

use App\ProductType;

use App\User;

use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\FromQuery;

use Maatwebsite\Excel\Concerns\WithMapping;

use Maatwebsite\Excel\Concerns\WithHeadings;



class JobOrdersExport implements FromCollection, WithMapping, WithHeadings

{

    protected $ids;

    function __construct($ids) {

        $this->ids = $ids;

    }

    public function collection()

    {

       

        $jobOrderData= JobOrderDetail::select('job_order_details.*','job_orders.id as jo_ord_id','job_orders.company_name','job_orders.service_cost','job_orders.contact_person','job_orders.contact_number','job_orders.misc_charge','job_orders.job_order_number','job_orders.misc_charge_notes','job_orders.total_cost_charged_to_customer','job_orders.estimated_total_cost','job_orders.estimated_date_return as jo_ord_estimated_date_return','job_orders.total_actual_cost','job_orders.service_cost','job_orders.total_cost_charged_to_customer','job_orders.date_returned','job_orders.job_status','agents.first_name as agent_name','retail_resellers.company as c_name')

        ->join('job_orders','job_orders.id','=','job_order_details.job_order_id')

        ->join('agents','agents.id','=','job_order_details.agent')

        ->join('retail_resellers', 'retail_resellers.id', '=', 'job_orders.company_name')

        ->where('job_orders.id',$this->ids)->get();

        return $jobOrderData;

    }



    public function headings(): array

    {

        return [

            'Job Order Number',

            'Customer Name',

            'Contact Person',

            'Contact Number',

            // 'Customer Reference',

            'Estimated Total Cost',

            'Job Status',

            'Actual Cost',

            'Service Cost',

            'Misc Charge',

            'Misc Charge Notes',

            'Total Cost Charged To',

            'Bag Numbe',

            // 'Date Forwarded',

            'Listing Type',

            'Stock ID',

            'Model Number',

            'Serial Number',

            'Weight',

            'Screw Count',

            'Rate',

            'B E',

            'AMP',

            'Job Details',

            'Agent',

            'Estimated Cost',

            'Estimated Date Of Return',

            // 'Date Of Returned',

            'Image Upon Receipt',

        ];

    }



    /**

    * @var $jobOrderData $joborderdata

    */

    public function map($joborderdata): array

    {
        dd($joborderdata);

      $jobstatus='';

      if($joborderdata->job_status==1)

      {

          $jobstatus='Post Due';

      }

      elseif($joborderdata->job_status==2)

      {

          $jobstatus='Open';

      }

      elseif($joborderdata->job_status==3)

      {

          $jobstatus='Pending';

      }

      elseif($joborderdata->job_status==4)

      {

          $jobstatus='On Hold';

      }

      elseif($joborderdata->job_status==5)

      {

          $jobstatus='Closed';

      }



        // dd($joborderdata);
        $jobDetails="";

        if($joborderdata->job_details==1)

        {

            $jobDetails='Polish';

        }

        elseif($joborderdata->job_details==2)

        {

            $jobDetails='Overhual';

        } 

        elseif($joborderdata->job_details==3)

        {

            $jobDetails='Change/Put Dial';

        } 

        elseif($joborderdata->job_details==4)

        {

            $jobDetails='Change/Put bezel';

        } 

        elseif($joborderdata->job_details==5)

        {

            $jobDetails='Change/Put band';

        } 

        elseif($joborderdata->job_details==6)

        {

            $jobDetails='Change/Put crystal';

        } 

        elseif($joborderdata->job_details==7)

        {

            $jobDetails='Change/Put Insert';

        } 

        elseif($joborderdata->job_details==8)

        {

            $jobDetails='Swap Dial';

        } 

        elseif($joborderdata->job_details==9)

        {

            $jobDetails='Swap Bezel';

        } 

        elseif($joborderdata->job_details==10)

        {

            $jobDetails='Swap Band';

        } 

        elseif($joborderdata->job_details==11)

        {

            $jobDetails='Swap Movement';

        } 

        elseif($joborderdata->job_details==12)

        {

            $jobDetails='Remove Dial';

        } 

        elseif($joborderdata->job_details==13)

        {

            $jobDetails='Remove Bezel';

        } 

        elseif($joborderdata->job_details==14)

        {

            $jobDetails='Remove Crystal';

        } 

        elseif($joborderdata->job_details==15)

        {

            $jobDetails='Estimate';

        } 

        elseif($joborderdata->job_details==16)

        {

            $jobDetails='Fix Crown';

        } 

        elseif($joborderdata->job_details==17)

        {

            $jobDetails='Fix Band';

        } 

        elseif($joborderdata->job_details==18)

        {

            $jobDetails='Assemble';

        } 

        elseif($joborderdata->job_details==19)

        {

            $jobDetails='Disassemble';

        } 

        elseif($joborderdata->job_details==20)

        {

            $jobDetails='PVD';

        } 

        elseif($joborderdata->job_details==21)

        {

            $jobDetails='Disassemble for Polish Out';

        } 

        elseif($joborderdata->job_details==22)

        {

            $jobDetails='Engrave';

        } 

        elseif($joborderdata->job_details==23)

        {

            $jobDetails='Others(will need explanation)';

        }  

       

        return [

            $joborderdata->job_order_number,

            $joborderdata->c_name,

            $joborderdata->contact_person,

            $joborderdata->contact_number,

            // $joborderdata->name,

            $joborderdata->estimated_cost,

            $jobstatus,

            $joborderdata->actual_cost,

            $joborderdata->service_cost,

            $joborderdata->misc_charge,

            $joborderdata->misc_charge_notes,

            $joborderdata->total_cost_charged_to_customer,

            $joborderdata->bag_number,

            $joborderdata->item_type,

            $joborderdata->stock_id,

            $joborderdata->model_number,

            $joborderdata->serial_number,

            $joborderdata->weight,

            $joborderdata->screw_count,

            $joborderdata->rate,

            $joborderdata->b_e,

            $joborderdata->amp,

            $jobDetails,

            $joborderdata->agent_name,

            $joborderdata->estimated_cost,

            $joborderdata->estimated_date_return,

            uploaded_asset($joborderdata->image_upon_receipt),

        ];

    }

}



