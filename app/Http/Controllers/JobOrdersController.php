<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\JobOrder;
use App\ProductStock;
use App\JobOrderDetail;
use App\Memo;
use App\Agent;
use App\MemoDetail;
use App\Product;
use Auth;
use App\Activitylog;
use App\ProductType;
use App\Models\Sequence;
use App\User;
use App\Expertise;
use App\JobOrdersExport;
use Excel;
use Mail;
use App\Mail\EmailManager;
use Illuminate\Support\Str;
use PDF;
use Illuminate\Support\Facades\DB;
class JobOrdersController extends Controller
{
   // Warehouse Start
    public function index(Request $request)
    {
      $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
      if($request->input('pagination_qty')!=NULL){
        $pagination_qty =  $request->input('pagination_qty');
      }
         $jobOrderQry = JobOrder::select('job_orders.*','job_order_details.stock_id','job_order_details.estimated_date_return as estimated_date','agents.first_name as agent_name','retail_resellers.company as c_name','retail_resellers.customer_group','retail_resellers.customer_name')
                          ->leftJoin('job_order_details', 'job_order_details.job_order_id', '=', 'job_orders.id')
                          ->leftJoin('agents', 'agents.id', '=', 'job_order_details.agent')
                          ->selectRaw('GROUP_CONCAT(job_order_details.model_number) as model_number')
                          ->selectRaw('GROUP_CONCAT(job_order_details.serial_number) as serial_number')

                          ->groupBy('job_order_details.job_order_id')

                          ->leftJoin('retail_resellers', 'retail_resellers.id', '=', 'job_orders.company_name')

                          ->orderBy('job_orders.id', 'desc');
                          // $count = $jobOrderQry->count();

        $sort_search = isset($request->search) ? $request->search : '';
        if($sort_search != null){
              $jobOrderQry = $jobOrderQry->where(function($query) use ($sort_search){
                $query->where('job_order_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('retail_resellers.company', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_orders.contact_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.model_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.serial_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.stock_id', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('agents.first_name', 'LIKE', '%'.$sort_search.'%');
            });
        }

        $jobOrderData = $jobOrderQry->paginate($pagination_qty);

        // dd($jobOrderData);
        return view("backend.job_orders.index", compact('jobOrderData','pagination_qty','sort_search'));

    }

    public function jotagarr(){
      $AllTagArr = array('1' => 'Polish',
                   '2' => 'Overhual',
                   '3' => 'Change/Put Dial',
                   '4' => 'Change/Put bezel',
                   '5' => 'Change/Put band',
                   '6' => 'Change/Put crystal',
                   '7' => 'Change/Put Insert',
                   '8' => 'Swap Dial',
                   '9' => 'Swap Bezel',
                   '10' => 'Swap Band',
                   '11' => 'Swap Movement',
                   '12' => 'Remove Dial',
                   '13' => 'Remove Bezel',
                   '14' => 'Remove Crystal',
                   '15' => 'Estimate',
                   '16' => 'Fix Crown',
                   '17' => 'Fix Band',
                   '18' => 'Assemble',
                   '19' => 'Disassemble',
                   '20' => 'PVD',
                   '21' => 'Disassemble for Polish Out',
                   '22' => 'Engrave',
                   '23' => 'Others(will need explanation)'
                 );
                 return $AllTagArr;
    }

    public function open(Request $request)

    {

        $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
        if($request->input('pagination_qty')!=NULL){
            $pagination_qty =  $request->input('pagination_qty');
        }
        $sort_search = isset($request->search) ? $request->search : '';

         $jobOrderQry = JobOrder::select('job_orders.*','job_order_details.stock_id','job_order_details.estimated_date_return as estimated_date','agents.first_name as agent_name','retail_resellers.company as c_name','retail_resellers.customer_group','retail_resellers.customer_name')

                          ->leftJoin('job_order_details', 'job_order_details.job_order_id', '=', 'job_orders.id')

                          ->leftJoin('agents', 'agents.id', '=', 'job_order_details.agent')

                          ->selectRaw('GROUP_CONCAT(job_order_details.model_number) as model_number')

                          ->selectRaw('GROUP_CONCAT(job_order_details.serial_number) as serial_number')

                          ->groupBy('job_order_details.job_order_id')

                          ->leftJoin('retail_resellers', 'retail_resellers.id', '=', 'job_orders.company_name')

                          ->where('job_orders.job_status',2)

                          ->orderBy('job_orders.id', 'desc');

                    if($sort_search != null){
                          $jobOrderQry = $jobOrderQry->where(function($query) use ($sort_search){
                            $query->where('job_order_number', 'LIKE', '%'.$sort_search.'%')
                            ->orWhere('retail_resellers.company', 'LIKE', '%'.$sort_search.'%')
                            ->orWhere('job_orders.contact_number', 'LIKE', '%'.$sort_search.'%')
                            ->orWhere('job_order_details.model_number', 'LIKE', '%'.$sort_search.'%')
                            ->orWhere('job_order_details.serial_number', 'LIKE', '%'.$sort_search.'%')
                            ->orWhere('job_order_details.stock_id', 'LIKE', '%'.$sort_search.'%')
                            ->orWhere('agents.first_name', 'LIKE', '%'.$sort_search.'%');
                        });
                    }

        $jobOrderData = $jobOrderQry->paginate($pagination_qty);

        return view("backend.job_orders.open", compact('jobOrderData','pagination_qty','sort_search'));

    }

    public function close(Request $request)
    {

        $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
        if($request->input('pagination_qty')!=NULL){
            $pagination_qty =  $request->input('pagination_qty');
        }
        $sort_search = isset($request->search) ? $request->search : '';

        $jobOrderQry = JobOrder::select('job_orders.*','job_order_details.stock_id','job_order_details.estimated_date_return as estimated_date','agents.first_name as agent_name','retail_resellers.company as c_name','retail_resellers.customer_group','retail_resellers.customer_name')
                          ->leftJoin('job_order_details', 'job_order_details.job_order_id', '=', 'job_orders.id')
                         ->leftJoin('agents', 'agents.id', '=', 'job_order_details.agent')
                         ->selectRaw('GROUP_CONCAT(job_order_details.model_number) as model_number')
                          ->selectRaw('GROUP_CONCAT(job_order_details.serial_number) as serial_number')
                          ->groupBy('job_order_details.job_order_id')
                          ->leftJoin('retail_resellers', 'retail_resellers.id', '=', 'job_orders.company_name')
                          ->where('job_orders.job_status',5)
                          ->orderBy('job_orders.id', 'desc');
                          // dd($jobOrderData);
             if($sort_search != null){
            $jobOrderQry = $jobOrderQry->where(function($query) use ($sort_search){
                $query->where('job_order_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('retail_resellers.company', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_orders.contact_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.model_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.serial_number', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('job_order_details.stock_id', 'LIKE', '%'.$sort_search.'%')
                ->orWhere('agents.first_name', 'LIKE', '%'.$sort_search.'%');
            });
        }

        $jobOrderData = $jobOrderQry->paginate($pagination_qty);

        return view("backend.job_orders.close", compact('jobOrderData','pagination_qty','sort_search'));

    }

    public function store()

    {

      $data['expertiseData'] = Expertise::orderBy('id','ASC')->get();

      $allcountry=DB::table('countries')->get();

      return view("backend.job_orders.create", $data,compact('allcountry'));

    }



    public function storeJoborders(Request $Request)

    {
        $user = auth()->user();
        $curr_uid = $user->id;
        $post = new JobOrder();
        $post->company_name = $Request->company_name;
        $post->contact_person = $Request->contact_person;
        $post->contact_number = $Request->contact_number;
        $post->customer_reference = $Request->customer_reference;
        $post->estimated_total_cost = $Request->estimated_total_cost;
        $post->estimated_date_return = $Request->estimated_date_return_job;
        $post->total_actual_cost = $Request->total_actual_cost;
        $post->image_upon_receipt_job = $Request->image_upon_receipt_job;
        $post->image_upon_returned = $Request->image_upon_returned;
        $post->service_cost = $Request->service_cost;
        $post->misc_charge = $Request->misc_charges;
        $post->misc_charge_notes = $Request->misc_charges_notes;
        $post->total_cost_charged_to_customer = $Request->total_charge_from_customer;
        $post->date_returned = $Request->date_returned;
        $post->job_status = $Request->job_order_status;
        $post->created_by = $curr_uid;
        $post->save();
        $job_orderID = $post->id;
        $post->job_order_number = 'JO000'.$job_orderID;
        $post->save();

        $jo_bag_number = $Request->bag_number;
        foreach ($jo_bag_number as $jo_key => $jo_num) {
          $job_details = isset($Request->job_details[$jo_key]) ? $Request->job_details[$jo_key] : "";
          $job_bag_details = "";
          if(!empty($job_details)){
          $job_bag_details = json_encode($job_details);
          }
          $jo_details = new JobOrderDetail();
          $jo_details->job_order_id = $job_orderID;
          $jo_details->bag_number = $Request->bag_number[$jo_key];
          $jo_details->date_forwarded = $Request->date_forwarded[$jo_key];

          $jo_details->item_type = $Request->listing_type[$jo_key];

          $jo_details->stock_id = $Request->stock_id[$jo_key];

          $jo_details->model_number = $Request->model_number[$jo_key];

          $jo_details->jo_product_id = $Request->pid[$jo_key];

          $jo_details->serial_number = $Request->serial_number[$jo_key];

          $jo_details->weight = $Request->weight[$jo_key];

          $jo_details->screw_count = $Request->screw_count[$jo_key];

          // $jo_details->brand = $Request->brand[$jo_key];

          $jo_details->rate = $Request->rate[$jo_key];

          $jo_details->b_e = $Request->b_e[$jo_key];

          $jo_details->amp = $Request->amp[$jo_key];

          $jo_details->image_upon_receipt = $Request->image_upon_receipt[$jo_key];

          // echo $jo_details->image_upon_receipt;exit;

          $jo_details->job_details =$job_bag_details;

          $jo_details->notes = $Request->notes[$jo_key];

          $jo_details->others_note = isset($Request->others_note[$jo_key]) ? $Request->others_note[$jo_key] : "";

          $jo_details->agent = $Request->agent[$jo_key];

          $jo_details->estimated_cost = $Request->estimated_cost[$jo_key];

          $jo_details->estimated_date_return = $Request->estimated_date_return[$jo_key];

          // $jo_details->date_of_return = $Request->date_of_return[$jo_key];

          // $jo_details->actual_cost = $Request->actual_cost[$jo_key];

          // $jo_details->parts_cost = $Request->parts_cost[$jo_key];

          // $jo_details->total_repair_cost = $Request->total_repair_cost[$jo_key];

          // $jo_details->agent_notes = $Request->agent_notes[$jo_key];

          $jo_details->bag_status = $Request->job_status[$jo_key];

          // $jo_details->rate_returned = $Request->rate_returned[$jo_key];

          // $jo_details->b_e_returned = $Request->b_e_returned[$jo_key];

          // $jo_details->amp_returned = $Request->amp_returned[$jo_key];

          $jo_details->created_by = $curr_uid;

          // exit;

          $jo_details->save();

          if($Request->job_order_status == 2){

            if($Request->stock_id[$jo_key] != "NIS"){

              $productUpdate = Product::where('id',$Request->pid[$jo_key])->firstOrFail();
              $productUpdate->published = 0;
              $productUpdate->featured = 0;
              $productUpdate->is_repair = 1;
              $productUpdate->save();

              $ProductStockUpdate = ProductStock::where('product_id',$Request->pid[$jo_key])->firstOrFail();
              $ProductStockUpdate->qty = $ProductStockUpdate->qty - 1;
              $ProductStockUpdate->save();
            }

            $agentFullName = "";
            $agentID = "";
            if(!empty($Request->agent[$jo_key])){
              $AgentName = Agent::where('id',$Request->agent[$jo_key])->firstOrFail();
              $agentID = $AgentName->id;
              $agentFullName = $AgentName->first_name ." ".$AgentName->last_name;
            }

            $htmlopen = 'STOCK ID '.$Request->stock_id[$jo_key].' is being repaired by <a href="#" class="agenturl" data-uid="'.$agentID.'">'.$agentFullName.'</a> under <a href="#" class="joburl" data-uid="'.$job_orderID.'"> JO000'.$job_orderID.'</a> on';

            $htmlagentopen = 'STOCK ID '.$Request->stock_id[$jo_key].' is being repaired under <a href="#" class="joburl" data-uid="'.$job_orderID.'"> JO000'.$job_orderID.'</a> on';
            $jonOrderLog = new Activitylog();
            $jonOrderLog->type = 'joborder';
            $jonOrderLog->user_id = $curr_uid;
            $jonOrderLog->prodcut_id = $Request->pid[$jo_key];
            $jonOrderLog->activity = addslashes($htmlopen);
            $jonOrderLog->action = 'opened';
            $jonOrderLog->save();
            $agentLog = new Activitylog();
            $agentLog->type = 'joborderAgent';
            $agentLog->user_id = $curr_uid;
            // $agentLog->prodcut_id = $Request->pid[$jo_key];
            $agentLog->activity = addslashes($htmlagentopen);
            $agentLog->action = 'agentopen';
            $agentLog->extra_id = $agentID;
            $agentLog->save();
          }
        }
        // $array['stock_id'] = 'JO000'.$job_orderID;
        // $array['userIp'] = $Request->ip();
        // $array['from'] = env('MAIL_FROM_ADDRESS');
        // Mail::send('frontend.backendMailer.addjobemail', $array, function($message) use ($array) {
        //     $message->to(env("StockManager"));
        //     $message->subject('User Activity Notification');
        // });

        $AlljoeditTag = $this->jotagarr();
        $jo_order = JobOrder::findOrFail($job_orderID);
        $allcountry=DB::table('countries')->get();
        $jo_order_detail = JobOrderDetail::select('job_order_details.*','agents.first_name')
                            ->leftJoin('agents', 'agents.id', '=', 'job_order_details.agent')
                            ->where('job_order_id','=',$job_orderID)->get();

                            $array['userIp'] = $Request->ip();
                            $array['stock_id'] = 'JO000'.$job_orderID;
                            $array['from'] = env('MAIL_FROM_ADDRESS');
                            $array['AlljoeditTag'] = $AlljoeditTag;
                            $array['jo_order'] = $jo_order;
                            $array['allcountry'] = $allcountry;
                            $array['jo_order_detail'] = $jo_order_detail;
                            $pdf = PDF::loadView('frontend.backendMailer.jo_email_attachment', $array);
                            $array['from'] = env('MAIL_FROM_ADDRESS');
                            Mail::send('frontend.backendMailer.addjobemail', $array, function($message) use ($array,$pdf) {
                                $message->to(env("StockManager"));
                                $message->subject('User Activity Notification');
                                $message->attachData($pdf->output(), "job_order.pdf");
                            });

    flash(translate('Job Order has been added successfully'))->success();
     return redirect()->route('job_orders.index');
    }



    public function job_ordersedit(Request $request, $id)

    {

        $AlljoeditTag = $this->jotagarr();

        $jo_order = JobOrder::findOrFail($id);

        $allcountry=DB::table('countries')->get();

        $jo_order_detail = JobOrderDetail::select('job_order_details.*','agents.first_name')
                            ->leftJoin('agents', 'agents.id', '=', 'job_order_details.agent')
                            ->where('job_order_id','=',$id)->get();

        return view('backend.job_orders.edit',compact('jo_order','allcountry','jo_order_detail','AlljoeditTag'));

    }



    public function job_ordersUpdate(Request $Request, $id)

    {

      $user = auth()->user();

      $curr_uid = $user->id;

      $post = JobOrder::findOrFail($id);

      $post->company_name = $Request->company_name;

      $post->contact_person = $Request->contact_person;

      $post->contact_number = $Request->contact_number;

      $post->customer_reference = $Request->customer_reference;

      $post->image_upon_receipt_job = $Request->image_upon_receipt_job;

      $post->image_upon_returned = $Request->image_upon_returned;

      $post->estimated_total_cost = $Request->estimated_total_cost;

      $post->estimated_date_return = $Request->estimated_date_return_job;

      $post->total_actual_cost = $Request->total_actual_cost;

      $post->service_cost = $Request->service_cost;

      $post->misc_charge = $Request->misc_charges;

      $post->misc_charge_notes = $Request->misc_charges_notes;

      $post->total_cost_charged_to_customer = $Request->total_charge_from_customer;

      $post->date_returned = $Request->date_returned;

      $post->image_upon_returned = $Request->image_upon_returned;

      $post->job_status = $Request->job_order_status;

      $post->makeinvoice = $Request->makeinvoice;

      $post->created_by = $curr_uid;

      $post->save();

      $job_orderID = $post->id;

      $post->job_order_number = 'JO000'.$job_orderID;

      $post->save();

      $job_detail_id = $Request->job_detail_id;

      $totalprocost = 0;

      foreach ($job_detail_id as $jo_key => $jo_d_id) {

        $jo_details = JobOrderDetail::findOrFail($jo_d_id);

        if($Request->stock_id[$jo_key] != "NIS"){

          $proDetailStock = Product::where('stock_id',$Request->stock_id[$jo_key])->firstOrFail();

          if(!empty($proDetailStock)){

            $totalprocost = $totalprocost + $proDetailStock->unit_price;

          }

        }





        $old_bag_number = "";
        $new_bag_number=$Request->bag_number[$jo_key];
        if($jo_details->bag_number != $new_bag_number){

          $old_bag_number = $jo_details->bag_number;
          $user = Auth::user();
          $curr_uid = $user->id;
          $curr_name = $user->name;
          $proLog = new Activitylog();
          $proLog->type = 'JOboreder_details';
          $proLog->user_id = $curr_uid;
          $proLog->prodcut_id = $jo_details->jo_product_id;
          $old_bag_number=$jo_details->bag_number;
          $proLog->activity = addslashes('STOCK ID '.$Request->stock_id[$jo_key].' Such as bag_number was changed from '.$old_bag_number.' to  '.$new_bag_number.' by '.$curr_name.' on');
          $proLog->action = 'edited_jobOreder';
          $proLog->save();

        }

        $jo_details->bag_number = $Request->bag_number[$jo_key];

        $jo_details->date_forwarded = $Request->date_forwarded[$jo_key];

        $jo_details->item_type = $Request->listing_type[$jo_key];

        $jo_details->stock_id = $Request->stock_id[$jo_key];

        $jo_details->model_number = $Request->model_number[$jo_key];

        $jo_details->serial_number = $Request->serial_number[$jo_key];

        $jo_details->weight = $Request->weight[$jo_key];

        $jo_details->screw_count = $Request->screw_count[$jo_key];

        // $jo_details->brand = $Request->brand[$jo_key];

        $jo_details->rate = $Request->rate[$jo_key];

        $jo_details->b_e = $Request->b_e[$jo_key];

        $jo_details->amp = $Request->amp[$jo_key];

        $jo_details->image_upon_receipt = $Request->image_upon_receipt[$jo_key];

        $jo_details->job_details = isset($Request->job_details[$jo_key]) ? $Request->job_details[$jo_key]: "";

        $jo_details->notes = $Request->notes[$jo_key];

        $jo_details->others_note = isset($Request->others[$jo_key]) ? $Request->others[$jo_key] : "";

        $jo_details->agent = $Request->agent[$jo_key];

        $jo_details->estimated_cost = $Request->estimated_cost[$jo_key];

        $jo_details->estimated_date_return = $Request->estimated_date_return[$jo_key];

        $jo_details->date_of_return = $Request->date_of_return[$jo_key];

        $jo_details->actual_cost = $Request->actual_cost_datails[$jo_key];

        $jo_details->parts_cost = $Request->parts_cost[$jo_key];

        $jo_details->total_repair_cost = $Request->total_repair_cost[$jo_key];

        $jo_details->agent_notes = $Request->agent_notes[$jo_key];

        $jo_details->bag_status = $Request->job_status[$jo_key];

        $jo_details->rate_returned = $Request->rate_return[$jo_key];

        $jo_details->b_e_returned = $Request->b_e_return[$jo_key];

        $jo_details->amp_returned = $Request->amp_return[$jo_key];

        $jo_details->image_upon_returned_details = $Request->image_upon_returned_details[$jo_key];

        $jo_details->created_by = $curr_uid;

        $jo_details->save();

        if($Request->job_order_status == 5){

          if($Request->stock_id[$jo_key] != "NIS"){

            $productUpdate = Product::where("id",$Request->pid[$jo_key])->firstOrFail();

            if(!empty($productUpdate)){

              $pTypeData = Producttype::where("id",$productUpdate->product_type_id)->firstOrFail();

              $pseqData = Sequence::where("id",$pTypeData->sequence_id)->firstOrFail();

              if($Request->makeinvoice != 1){

              $productUpdate->product_cost = $Request->total_actual_cost + $productUpdate->product_cost;

              $productUpdate->published = 1;

              $productUpdate->is_repair = 0;

              $productUpdate->save();

              $productUpdate->cost_code = $productUpdate->product_cost * $pseqData->cost_code_multiplier;

              $productUpdate->save();

              $ProductStockUpdate = ProductStock::where('product_id',$Request->pid[$jo_key])->firstOrFail();

              $ProductStockUpdate->qty = $ProductStockUpdate->qty + 1;

              $ProductStockUpdate->save();
              }elseif($Request->makeinvoice == 1){
                  $productUpdate->is_repair = 0;
                  $productUpdate->save();
                }
            }


          }


          }



          $agentID = "";

          $agentFullName = "";

          if(!empty($Request->agent[$jo_key])){

            $AgentName = Agent::where("id",$Request->agent[$jo_key])->firstOrFail();

            $agentID = $AgentName->id;

            $agentFullName = $AgentName->first_name ." ".$AgentName->last_name;

            $agentLog = new Activitylog();

            $agentLog->type = 'joborderAgent';

            $agentLog->user_id = $curr_uid;

            // $agentLog->prodcut_id = $Request->pid[$jo_key];

            $agentLog->activity = addslashes('STOCK ID '.$Request->stock_id[$jo_key].' repaire is done <a href="#" class="joburl" data-uid="'.$job_orderID.'"> JO000'.$job_orderID.'</a> on');

            $agentLog->action = 'agentclose';

            $agentLog->extra_id = $agentID;

            $agentLog->save();

          }

      }

      if($Request->makeinvoice == 1){

        if($Request->job_order_status == 5){

          $post = new Memo();

          $post->customer_name = $Request->company_name;

          $post->sale_tax = 0;

          $post->order_total = $totalprocost;

          $post->reference = 'JO000'.$job_orderID;

          $post->sub_total = $totalprocost;   //check it

          $post->memo_status = 0;

          $post->isactive = 1;

          $post->date = date('Y-m-d H:i:s');

          $post->due_date = "0000-00-00";

          $post->save();

          $memoID = $post->id;

          $post->memo_number ="101".$memoID;




          if($Request->job_order_status=="5")
        {

        $user = Auth::user();
        $curr_uid = $user->id;
        $curr_name = $user->name;
          $proLog = new Activitylog();
          $proLog->type = 'Memo';
          $proLog->user_id = $curr_uid;
          $proLog->prodcut_id = $jo_details->jo_product_id;
          $old_bag_number=$jo_details->bag_number;


          $proLog->activity = addslashes('STOCK ID <a href="#">'.$Request->stock_id[$jo_key].' </a> Was Memo to Memo <a href="#">'.$post->memo_number.'</a> By Customer <a href="#"> '. $Request->contact_person.' </a> on');
          $proLog->action = 'edited_jobOreder';
          $proLog->save();
        }

          $post->save();

          $proCost = "";

          $proid = "";

          foreach ($job_detail_id as $jo_key => $jo_d_id) {

            if($Request->stock_id[$jo_key] != "NIS"){

              $proDetailStock = Product::where('stock_id',$Request->stock_id[$jo_key])->firstOrFail();

              if(!empty($proDetailStock)){

                $proCost = $proDetailStock->product_cost;

                $proid = $proDetailStock->id;

              }

            }

            $memoDpost = new MemoDetail();

            $memoDpost->memo_id = $memoID;

            $memoDpost->product_id = $proid;

            $memoDpost->product_price = $proCost;

            $memoDpost->product_qty = 1;

            $memoDpost->item_status = 1;



            $memoDpost->row_total = $proCost;

            $memoDpost->save();

          }

          $mdRec = MemoDetail::where('memo_id',$memoID)->firstOrFail();
          $mdRec->row_total = $totalprocost;
          $mdRec->save();

        }

      }

      $user = Auth::user();
      $curr_uid = $user->id;
      $curr_name = $user->name;
      $jonOrderLogCls = new Activitylog();
      $jonOrderLogCls->type = 'joborder';
      $jonOrderLogCls->user_id = $curr_uid;
      $jonOrderLogCls->prodcut_id = $Request->pid[$jo_key];
      $jonOrderLogCls->activity = addslashes('STOCK ID '.$Request->stock_id[$jo_key].' was repaired by <a href="#" class="agenturl" data-uid="'.$agentID.'">'.$agentFullName.'</a> under <a href="#" class="joburl" data-uid="'.$job_orderID.'"> JO000'.$job_orderID.'</a> by '.$curr_name.' on');
      $jonOrderLogCls->action = 'closed';
      $jonOrderLogCls->save();



      if($Request->makeinvoice == 1){

        flash(translate('Memo has been created successfully'))->success();

        return redirect()->route('memo.index');

      }else{

        flash(translate('Job Order has been updated successfully'))->success();

        return redirect()->route('job_orders.index');

      }

    }

    // Warehouse End





    public function view(Request $request, $id)

    {

        $jo_order =JobOrder::select('job_orders.*','job_order_details.model_number','job_order_details.serial_number','job_order_details.stock_id','job_order_details.estimated_date_return as estimated_date','agents.first_name as agent_name','retail_resellers.company as c_name')

        ->leftJoin('job_order_details', 'job_order_details.job_order_id', '=', 'job_orders.id')

        ->leftJoin('agents', 'agents.id', '=', 'job_order_details.agent')

        ->selectRaw('GROUP_CONCAT(job_order_details.model_number) as model_number')

        ->selectRaw('GROUP_CONCAT(job_order_details.serial_number) as serial_number')

        ->groupBy('job_order_details.job_order_id')

        ->leftJoin('retail_resellers', 'retail_resellers.id', '=', 'job_orders.company_name')

        ->where('job_orders.id',$id)->first();

        $jo_order_detail = JobOrderDetail::where('job_order_id','=',$id)->first();

        // dd($jo_order_detail);

        return view('backend.job_orders.view',compact('jo_order','jo_order_detail'));

    }





    public function activity(Request $request, $id)

    {

        $jo_order =JobOrder::select('job_orders.*','job_order_details.model_number','job_order_details.serial_number','job_order_details.stock_id','job_order_details.estimated_date_return as estimated_date','agents.first_name as agent_name','retail_resellers.company as c_name')

        ->leftJoin('job_order_details', 'job_order_details.job_order_id', '=', 'job_orders.id')

        ->leftJoin('agents', 'agents.id', '=', 'job_order_details.agent')

        ->selectRaw('GROUP_CONCAT(job_order_details.model_number) as model_number')

        ->selectRaw('GROUP_CONCAT(job_order_details.serial_number) as serial_number')

        ->groupBy('job_order_details.job_order_id')

        ->leftJoin('retail_resellers', 'retail_resellers.id', '=', 'job_orders.company_name')

        ->where('job_orders.id',$id)->first();

        $jo_order_detail = JobOrderDetail::where('job_order_id','=',$id)->first();

        // dd($jo_order_detail);

        return view('backend.job_orders.activity',compact('jo_order','jo_order_detail'));

    }



    public function listingAjax(Request $request)

    {

      $listingName = $request->id;

      $ProListing = Product::select('products.id','products.stock_id','products.weight','products.model','product_stocks.sku','products.custom_6')

            ->leftJoin('product_types', 'product_types.id', '=', 'products.product_type_id')

            ->leftJoin('site_options', 'site_options.option_value', '=', 'product_types.listing_type')

            ->leftJoin('product_stocks', 'product_stocks.product_id', '=', 'products.id')

            ->where('site_options.option_value',$listingName)

            ->where('site_options.option_name','listingtype')

            ->orderBy('products.stock_id','ASC')

            ->get();

            $TagOptionHtml = "<option value=''>Select Stock ID</option><option value='NIS'>NIS</option>";

            foreach ($ProListing as $Pdata) {

              $jo_pid =$Pdata->id;

              $stock =$Pdata->stock_id;

              $model =$Pdata->model;

              $weight =$Pdata->weight;

              $model =$Pdata->model;

              $sku =$Pdata->sku;

              $screw_count =$Pdata->custom_6;

              $TagOptionHtml .= "<option data-id='".$jo_pid."' data-weight='".$weight."' data-screw='".$screw_count."' data-model='".$model."' data-sku='".$sku."' value='".$stock."'>$stock</option>";

            }



            return response()->json(['success' => true,'TagHTML'=>$TagOptionHtml]);

    }





        public function jobordersDestroy(Request $request,$id)

        {

            $ReturnProVal = JobOrder::where('id',$id)->delete();

            $post = JobOrderDetail::where('job_order_id',$id)->delete();

            flash(translate('Job Order has been deleted successfully'))->success();

            return back();

        }

        public function export(Request $request){

          $ids = $request->checked_id;

          $proID = json_decode($ids, TRUE);

          $fetchLiaat = new JobOrdersExport($proID);

          // dd($fetchLiaat);

        return Excel::download($fetchLiaat, 'job_orders.xlsx');



    }

    public function bulk_job_orders_delete(Request $request) {

      $ids = $request->checked_id;

      $proID = json_decode($ids, TRUE);

      // dd($proID);

      if($proID) {

          foreach ($proID as $jo_order_id) {

              // $this->destroy($jo_order_id);

              JobOrder::where('id',$jo_order_id)->delete();

              JobOrderDetail::where('job_order_id',$jo_order_id)->delete();

          }

      }

      flash(translate('Job orders has been deleted successfully'))->success();

      return back();

  }



}
