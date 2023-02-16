<?php
namespace App\Http\Controllers;
use App\Productcondition;
use App\ProductTranslation;
use App\ReturnProd;
use App\OpenJobReportExport;
use App\OnHoldJobReportExcel;
use App\ClientReportExcel;
use App\CompleteReportExcel;
use App\Transfer;
use App\PendingJobReportExcel;
use App\JobOrder;
use App\Agent;
use App\PastDueJobExcel;
use App\AgentReportExcel;
use App\RetailReseller;
use App\JobOrderDetail;
use App\TransferItem;
use App\ProductStock;
use App\Models\Sequence;
use App\Models\Warehouse;
use App\Category;
use App\Activitylog;
use App\SiteOptions;
use App\FlashDealProduct;
use Illuminate\Http\Request;
use App\Models\Purchases;
use App\Brand;
use App\Product;
use App\Memo;
use App\MemoDetail;
use App\Upload;
use App\PurchasesExport;
use Excel;
use App\Models\Producttype;
use App\Tag;
use \InvPDF;
use App\ProductTax;
use App\AttributeValue;
use App\Cart;
use Illuminate\Support\Facades\DB;
use App\FilteredColumns;
class JobOrdersReportController extends Controller
{
    public function agent_report(Request $request)
    {
        $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
        if($request->input('pagination_qty')!=NULL){
            $pagination_qty =  $request->input('pagination_qty');
        }

        $sort_search='';
        $agent=JobOrder::select('job_orders.*','job_order_details.stock_id','job_order_details.estimated_date_return as estimated_date','job_order_details.bag_number','job_order_details.model_number','job_order_details.serial_number','job_order_details.date_of_return','job_order_details.created_at as enter_date','agents.first_name as agent_name')
        ->leftJoin('job_order_details', 'job_order_details.job_order_id', '=', 'job_orders.id')
        ->leftJoin('agents', 'agents.id', '=', 'job_order_details.agent')
        ->groupBy('job_order_details.job_order_id')
        ->orderBy('job_orders.id', 'desc');
        $sort_search = isset($request->search) ? $request->search : '';
        if($sort_search != null){
              $agent = $agent->where(function($query) use ($sort_search){
                $query->where('job_order_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('bag_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.model_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.serial_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.stock_id', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('agents.first_name', 'LIKE', '%'.$sort_search.'%');
            });
        }
        $agentSrch =$request->agent_id;
        if($agentSrch > 0)
        {
            $agent->where('agents.id', $agentSrch);
        }
        if( $request->pagination_qty == "all"){
            $agentReport = $agent->get();
          }else{
            $agentReport = $agent->paginate($pagination_qty);
          }
        // $agentReport = $agent->paginate($pagination_qty);


        // $sort_search='';
        return view("backend.jobOrder_report.agent_report", compact('sort_search','agentReport','pagination_qty'));
    }
    public function client_report(Request $request)
    {
        $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
        if($request->input('pagination_qty')!=NULL){
            $pagination_qty =  $request->input('pagination_qty');
        }

        $sort_search='';
        $client=JobOrder::select('job_orders.*','job_order_details.stock_id','job_order_details.estimated_date_return as estimated_date','job_order_details.bag_number','job_order_details.model_number','job_order_details.serial_number','job_order_details.date_of_return','job_order_details.created_at as enter_date','retail_resellers.customer_name')
        ->leftJoin('job_order_details', 'job_order_details.job_order_id', '=', 'job_orders.id')
        ->leftJoin('retail_resellers', 'retail_resellers.id', '=', 'job_orders.company_name')
        ->groupBy('job_order_details.job_order_id')
        ->orderBy('job_orders.id', 'desc');
        $sort_search = isset($request->search) ? $request->search : '';
        if($sort_search != null){
              $client = $client->where(function($query) use ($sort_search){
                $query->where('job_order_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('bag_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.model_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.serial_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.stock_id', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('retail_resellers.customer_name', 'LIKE', '%'.$sort_search.'%');
            });
        }
        $clientSrch =$request->client_id;
        if($clientSrch > 0)
        {
            $client->where('retail_resellers.id', $clientSrch);
        }
        if( $request->pagination_qty == "all"){
            $clientReport = $client->get();
          }else{
            $clientReport = $client->paginate($pagination_qty);
          }


        return view("backend.jobOrder_report.client_report", compact('sort_search','clientReport','pagination_qty'));
    }
    public function complete_report(Request $request)
    {
        $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
        if($request->input('pagination_qty')!=NULL){
            $pagination_qty =  $request->input('pagination_qty');
        }
        $sort_search='';
        $complete=JobOrder::select('job_orders.*','job_order_details.stock_id','job_order_details.estimated_date_return as estimated_date','job_order_details.bag_number','job_order_details.model_number','job_order_details.serial_number','job_order_details.date_of_return','job_order_details.created_at as enter_date','retail_resellers.customer_name','retail_resellers.company as c_name','retail_resellers.customer_group')
        ->leftJoin('job_order_details', 'job_order_details.job_order_id', '=', 'job_orders.id')
        ->leftJoin('retail_resellers', 'retail_resellers.id', '=', 'job_orders.company_name')
        ->groupBy('job_order_details.job_order_id')
        ->orderBy('job_orders.id', 'desc')
        ->where('job_orders.job_status','5');
        $sort_search = isset($request->search) ? $request->search : '';
        if($sort_search != null){
              $complete = $complete->where(function($query) use ($sort_search){
                $query->where('job_order_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('bag_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.model_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.serial_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.stock_id', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('retail_resellers.customer_name', 'LIKE', '%'.$sort_search.'%');
            });
        }
        $startrangedate=$request->startrangedate;
        $endrangedate=$request->endrangedate;
        $startdate=  date('y-m-d', strtotime($startrangedate));
        $endate=  date('y-m-d', strtotime($endrangedate));
        // dd($startdate.' 00:00:00');
      if ($request->startrangedate || $request->endrangedate) {
          $complete = $complete->whereBetween('job_orders.created_at', [$startdate.' 00:00:00',$endate.' 23:59:59']);
      } 
        if( $request->pagination_qty == "all"){
            $complete_report = $complete->get();
          }else{
            $complete_report = $complete->paginate($pagination_qty);
          }

// dd($complete_report);
        return view("backend.jobOrder_report.complete_report", compact('sort_search','complete_report','pagination_qty'));
    }
    public function pending_job_report(Request $request)
    {
        $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
        if($request->input('pagination_qty')!=NULL){
            $pagination_qty =  $request->input('pagination_qty');
        }
        $sort_search='';
        $pending_job=JobOrder::select('job_orders.*','job_order_details.stock_id','job_order_details.estimated_date_return as estimated_date','job_order_details.bag_number','job_order_details.model_number','job_order_details.serial_number','job_order_details.date_of_return','job_order_details.created_at as enter_date','retail_resellers.customer_name','retail_resellers.company as c_name','retail_resellers.customer_group')
        ->leftJoin('job_order_details', 'job_order_details.job_order_id', '=', 'job_orders.id')
        ->leftJoin('retail_resellers', 'retail_resellers.id', '=', 'job_orders.company_name')
        ->groupBy('job_order_details.job_order_id')
        ->orderBy('job_orders.id', 'desc')
        ->where('job_orders.job_status','3');
        $sort_search = isset($request->search) ? $request->search : '';
        if($sort_search != null){
              $pending_job = $pending_job->where(function($query) use ($sort_search){
                $query->where('job_order_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('bag_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.model_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.serial_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.stock_id', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('retail_resellers.customer_name', 'LIKE', '%'.$sort_search.'%');
            });
        }
        if( $request->pagination_qty == "all"){
            $pending_job_report = $pending_job->get();
          }else{
            $pending_job_report = $pending_job->paginate($pagination_qty);
          }
        // $pending_job_report = $pending_job->paginate($pagination_qty);

        return view("backend.jobOrder_report.pending_job_report", compact('sort_search','pending_job_report','pagination_qty'));
    }
    public function OnHoldJob_report(Request $request)
    {
        
        $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
        if($request->input('pagination_qty')!=NULL){
            $pagination_qty =  $request->input('pagination_qty');
        }
        $sort_search='';
        $OnHoldJob=JobOrder::select('job_orders.*','job_order_details.stock_id','job_order_details.estimated_date_return as estimated_date','job_order_details.bag_number','job_order_details.model_number','job_order_details.serial_number','job_order_details.date_of_return','job_order_details.created_at as enter_date','retail_resellers.customer_name','retail_resellers.company as c_name','retail_resellers.customer_group')
        ->leftJoin('job_order_details', 'job_order_details.job_order_id', '=', 'job_orders.id')
        ->leftJoin('retail_resellers', 'retail_resellers.id', '=', 'job_orders.company_name')
        ->groupBy('job_order_details.job_order_id')
        ->orderBy('job_orders.id', 'desc')
        ->where('job_orders.job_status','4');
        $sort_search = isset($request->search) ? $request->search : '';
        if($sort_search != null){
              $OnHoldJob = $OnHoldJob->where(function($query) use ($sort_search){
                $query->where('job_order_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('bag_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.model_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.serial_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.stock_id', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('retail_resellers.customer_name', 'LIKE', '%'.$sort_search.'%');
            });
        }
        $startrangedate=$request->startrangedate;
        $endrangedate=$request->endrangedate;
        $startdate=  date('y-m-d', strtotime($startrangedate));
        $endate=  date('y-m-d', strtotime($endrangedate));
        // dd($startdate.' 00:00:00');
      if ($request->startrangedate || $request->endrangedate) {
          $OnHoldJob = $OnHoldJob->whereBetween('job_orders.created_at', [$startdate.' 00:00:00',$endate.' 23:59:59']);
      }
        if( $request->pagination_qty == "all"){
            $OnHoldJob_report = $OnHoldJob->get();
          }else{
            $OnHoldJob_report = $OnHoldJob->paginate($pagination_qty);
          }
        // $OnHoldJob_report = $OnHoldJob->paginate($pagination_qty);
        return view("backend.jobOrder_report.OnHoldJob_report", compact('sort_search','OnHoldJob_report','pagination_qty'));
    }
    public function OpenJob_report(Request $request)
    {
        
        
        $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
        if($request->input('pagination_qty')!=NULL){
            $pagination_qty =  $request->input('pagination_qty');
        }
        $sort_search='';
        $OpenJob=JobOrder::select('job_orders.*','job_order_details.stock_id','job_order_details.estimated_date_return as estimated_date','job_order_details.bag_number','job_order_details.model_number','job_order_details.serial_number','job_order_details.date_of_return','job_order_details.bag_status','job_order_details.created_at as enter_date','retail_resellers.customer_name','retail_resellers.company as c_name','retail_resellers.customer_group')
        ->leftJoin('job_order_details', 'job_order_details.job_order_id', '=', 'job_orders.id')
        ->leftJoin('retail_resellers', 'retail_resellers.id', '=', 'job_orders.company_name')
        ->groupBy('job_order_details.job_order_id')
        ->orderBy('job_orders.id', 'desc')
        ->where('job_orders.job_status','2');
        $sort_search = isset($request->search) ? $request->search : '';
        if($sort_search != null){
              $OpenJob = $OpenJob->where(function($query) use ($sort_search){
                $query->where('job_order_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('bag_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.model_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.serial_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.stock_id', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('retail_resellers.customer_name', 'LIKE', '%'.$sort_search.'%');
            });
        }
        $startrangedate=$request->startrangedate;
        $endrangedate=$request->endrangedate;
        $startdate=  date('y-m-d', strtotime($startrangedate));
        $endate=  date('y-m-d', strtotime($endrangedate));
        // dd($startdate.' 00:00:00');
      if ($request->startrangedate || $request->endrangedate) {
          $OpenJob = $OpenJob->whereBetween('job_orders.created_at', [$startdate.' 00:00:00',$endate.' 23:59:59']);
      } 
        if( $request->pagination_qty == "all"){
            $OpenJob_report = $OpenJob->get();
          }else{
            $OpenJob_report = $OpenJob->paginate($pagination_qty);
          }
        // $OpenJob_report = $OpenJob->paginate($pagination_qty);
        
        return view("backend.jobOrder_report.OpenJob_report", compact('sort_search','OpenJob_report','pagination_qty'));
    }
    public function PastDueJob_report(Request $request)
    {
        $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
        if($request->input('pagination_qty')!=NULL){
            $pagination_qty =  $request->input('pagination_qty');
        }
        $sort_search='';
        $PastDueJob=JobOrder::select('job_orders.*','job_order_details.stock_id','job_order_details.estimated_date_return as estimated_date','job_order_details.bag_number','job_order_details.model_number','job_order_details.serial_number','job_order_details.date_of_return','job_order_details.bag_status','job_order_details.created_at as enter_date','retail_resellers.customer_name','retail_resellers.company as c_name','retail_resellers.customer_group')
        ->leftJoin('job_order_details', 'job_order_details.job_order_id', '=', 'job_orders.id')
        ->leftJoin('retail_resellers', 'retail_resellers.id', '=', 'job_orders.company_name')
        ->groupBy('job_order_details.job_order_id')
        ->orderBy('job_orders.id', 'desc')
        ->where('job_orders.job_status','1');
        $sort_search = isset($request->search) ? $request->search : '';
        if($sort_search != null){
              $PastDueJob = $PastDueJob->where(function($query) use ($sort_search){
                $query->where('job_order_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('bag_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.model_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.serial_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.stock_id', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('retail_resellers.customer_name', 'LIKE', '%'.$sort_search.'%');
            });
        }
        $startrangedate=$request->startrangedate;
        $endrangedate=$request->endrangedate;
        $startdate=  date('y-m-d', strtotime($startrangedate));
        $endate=  date('y-m-d', strtotime($endrangedate));
        // dd($startdate.' 00:00:00');
      if ($request->startrangedate || $request->endrangedate) {
          $PastDueJob = $PastDueJob->whereBetween('job_orders.created_at', [$startdate.' 00:00:00',$endate.' 23:59:59']);
      } 
        $PastDueJob_report = $PastDueJob->paginate($pagination_qty);
        return view("backend.jobOrder_report.PastDueJob_report", compact('sort_search','PastDueJob_report','pagination_qty'));
    }
    public function agent_report_export(Request $request)
    {
      $fetchLiaat = new AgentReportExcel();
      // dd($fetchLiaat);
      return Excel::download($fetchLiaat, 'Agent_report.xlsx');
    }
    public function client_report_export(Request $request)
    {
      $fetchLiaat = new ClientReportExcel();
      // dd($fetchLiaat);
      return Excel::download($fetchLiaat, 'Client_report.xlsx');
    }
    public function complete_report_export(Request $request)
    {
        $fetchLiaat = new CompleteReportExcel();
        return Excel::download($fetchLiaat, 'Complete_report.xlsx');
    }
    public function pendingJob_report_export(Request $request)
    {
        $fetchLiaat = new PendingJobReportExcel();
        return Excel::download($fetchLiaat, 'pendingJob_report.xlsx');
    }
    public function onHoldJob_report_export(Request $request)
    {
        $fetchLiaat = new OnHoldJobReportExcel();
        return Excel::download($fetchLiaat, 'onHoldJob_report_export.xlsx');
    }
    public function openJob_report_export(Request $request)
    {
        $fetchLiaat = new OpenJobReportExport();
        return Excel::download($fetchLiaat, 'openJob_report_export.xlsx');
    }
    public function pastDueJob_report_export(Request $request)
    {
        $fetchLiaat = new PastDueJobExcel();
        return Excel::download($fetchLiaat, 'pastDueJob_report_export.xlsx');
    }
}
?>