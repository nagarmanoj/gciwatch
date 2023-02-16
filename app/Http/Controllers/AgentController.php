<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Agent;
use App\Activitylog;
use App\Expertise;
class AgentController extends Controller
{
   // Agent Start
    public function agent(Request $request)
    {
      $pagination_qty = isset($request->purchases_pagi)?$request->purchases_pagi:25;

        if($request->input('purchases_pagi')!=NULL){

        $product_qty =  ($request->input('purchases_pagi'));

        }
        $sort_search = null;
       $PurchasesProduct = Agent::orderBy('id','ASC');
       $product_qty=$request->purchases_pagi;
       if ($request->search != null){
                       $PurchasesProduct->orWhere('company_name', 'like', '%'.$request->search.'%');
                       $PurchasesProduct->orWhere('company_address', 'like', '%'.$request->search.'%');
                       $PurchasesProduct->orWhere('email', 'like', '%'.$request->search.'%');
                       $PurchasesProduct->orWhere('contact_number', 'like', '%'.$request->search.'%');

           $sort_search = $request->search;

       }
       if(isset($product_qty)){

         if($product_qty!='All'){

             $purchases = $PurchasesProduct->paginate(($product_qty));

         }

         else if(isset($product_qty) && $product_qty=='All'){

             $purchases = $detailedProduct;

         }

     }else{

      $purchases = $PurchasesProduct->paginate(25);

     }
     $agentData=$PurchasesProduct->get();
        return view("backend.people.agent.index", compact('agentData','sort_search','pagination_qty','purchases'));
    }
    public function AgentCreate()
    {
      $data['expertiseData'] = Expertise::orderBy('id','ASC')->get();
      return view("backend.people.agent.create", $data);
    }
    public function saveAgent(Request $Request)
    {
        $post = new Agent();
        $post->first_name = $Request->first_name;
        $post->last_name=$Request->last_name;
        $post->email=$Request->email;
        $post->expertise_id=$Request->expertise_id;
        $post->company_name=$Request->company_name;
        $post->company_address=$Request->company_address;
        $post->contact_number=$Request->contact_number;
        $post->contact_person=$Request->contact_person;
        $post->is_active=$Request->is_active;
        $post->save();
        flash(translate('Agent has been added successfully'))->success();
        return back();
    }
    public function agentedit(Request $request, $id)
    {
        $agent = Agent::findOrFail($id);
        $data['expertiseData'] = Expertise::orderBy('id','ASC')->get();
        return view('backend.people.agent.edit', compact('agent'),$data);
    }
    public function agentUpdate(Request $request, $id)
    {
      $agentUpdate = Agent::findOrFail($id);
      // $agentUpdate->code = $request->code;
      $agentUpdate->first_name = $request->first_name;
        $agentUpdate->last_name=$request->last_name;
        $agentUpdate->email=$request->email;
        $agentUpdate->expertise_id=$request->expertise_id;
        $agentUpdate->company_name=$request->company_name;
        $agentUpdate->company_address=$request->company_address;
        $agentUpdate->contact_number=$request->contact_number;
        $agentUpdate->contact_person=$request->contact_person;
        $agentUpdate->is_active=$request->is_active;
       $agentUpdate->save();
      flash(translate('Agent has been updated successfully'))->success();
      return back();
    }
    // Agent End
    public function optionDestroy($id)
    {
        $post = Agent::where('id',$id)->delete();
        flash(translate('Agent has been deleted successfully'))->success();
        return back();
    }
  public function agentAjax(Request $request)
  {
    $agentData = array();
    $post = new Agent();
    $post->first_name = $request->first_name;
    $post->last_name=$request->last_name;
    $post->email=$request->email;
    $post->expertise_id=$request->expertise_id;
    $post->company_name=$request->company_name;
    $post->company_address=$request->company_address;
    $post->contact_number=$request->contact_number;
    $post->contact_person=$request->contact_person;
    $post->is_active=$request->is_active;
    $post->save();
    $agentDataRe= Agent::orderBy('id','ASC')->get();
    $RetailResellerHTML='';
   foreach($agentDataRe as $row)
   {
    $RetailResellerHTML.="<option value='".$row->id."'>$row->first_name</option>";
   }
  //  $agentData['html'] = $RetailResellerHTML;
  //  $agentData['status'] = 'success';
  //  echo json_encode($agentData);
   return response()->json(['success' => 'Agent Created Successfully','RetailResellerHTML'=>$RetailResellerHTML,'post'=>$post]);
   exit;
    // flash(translate('Agent has been added successfully'))->success();
    // return back();
  }
  function activity(Request $request , $id)
  {
    $activitylogData = Activitylog::where('extra_id',$id)->where('type','=','joborderAgent')->orderBy('id','DESC')->get();
    return view('backend.people.agent.activity',compact('activitylogData'));
  }
}
