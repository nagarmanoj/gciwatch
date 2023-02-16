<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Staff;
use App\Role;
use Excel;
use App\StaffsExport;
use App\User;
use Hash;
class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
    * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pagination_qty = isset($request->purchases_pagi)?$request->purchases_pagi:25;
        if($request->input('purchases_pagi')!=NULL){
        $product_qty =  ($request->input('purchases_pagi'));
        }
        $sort_search = null;
        $PurchasesProduct = Staff::select('staff.*','staff.id as s_id','users.*','roles.name as roleName')
                                ->leftJoin('users','users.id','=','staff.user_id')
                                ->leftJoin('roles','roles.id','=','staff.role_id')
                                ->orderBy('staff.user_id','DESC');
        $product_qty=$request->purchases_pagi;
        if ($request->search != null){
                        $PurchasesProduct->orWhere('roles.name', 'like', '%'.$request->search.'%');
                        $PurchasesProduct->orWhere('users.company', 'like', '%'.$request->search.'%');
                        $PurchasesProduct->orWhere('users.name', 'like', '%'.$request->search.'%');
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
        $staffs=$PurchasesProduct->get();
        return view('backend.staff.staffs.index', compact('staffs','pagination_qty','purchases','sort_search'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        return view('backend.staff.staffs.create', compact('roles'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(User::where('email', $request->email)->first() == null){
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->mobile;
            $user->company=$request->company;
            $user->user_type = "staff";
            $user->status = $request->status;
            $user->gender = $request->gender;
            $user->password = Hash::make($request->password);
            if($user->save()){
                $staff = new Staff;
                $staff->user_id = $user->id;
                $staff->role_id = $request->role_id;
                if($staff->save()){
                    flash(translate('Staff has been inserted successfully'))->success();
                    return redirect()->route('staffs.index');
                }
            }
        }
        flash(translate('Email already used'))->error();
        return back();
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $staff = Staff::findOrFail(decrypt($id));
        $roles = Role::all();
        return view('backend.staff.staffs.edit', compact('staff', 'roles'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);
        $user = $staff->user;
        $user->name = $request->name;
        $user->company = $request->company;
        $user->phone = $request->mobile;
        $user->status = $request->status;
        $user->avatar=$request->avatar;
        $user->gender = $request->gender;
        if(strlen($request->password) > 0){
            $user->password = Hash::make($request->password);
        }
        if($user->save()){
            $staff->role_id = $request->role_id;
            if($staff->save()){
               flash(translate('Staff has been updated successfully'))->success();
                return redirect()->route('staffs.index');
            }
        }
        flash(translate('Something went wrong'))->error();
        return back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy(Staff::findOrFail($id)->user->id);
        if(Staff::destroy($id)){
            flash(translate('Staff has been deleted successfully'))->success();
            return redirect()->route('staffs.index');
        }
        flash(translate('Something went wrong'))->error();
        return back();
    }
    function bulk_order_delete(Request $request)
    {
      $ids = $request->checked_id;
      $proID = json_decode($ids, TRUE);
      // dd($proID);
      if($proID) {
          foreach ($proID as $jo_order_id) {
              // $this->destroy($jo_order_id);
              Staff::where('id',$jo_order_id)->delete();
          }
      }
      flash(translate('Users has been deleted successfully'))->success();
      return back();
    }
    public function export(Request $request)
    {
      $ids = $request->checked_id;
      $proID = json_decode($ids, TRUE);
      // dd($proID);
      $fetchLiaat = new StaffsExport($proID);
    //   dd($fetchLiaat);
    //   exit;
    $dt = new \DateTime();
    $curntDate = $dt->format('m-d-Y');
      return Excel::download($fetchLiaat, 'users_'.$curntDate.'.xlsx');
    }
    function status(Request $request)
    {
        $id=$request->id;
        $data=User::findOrFail($id);
        if($data->status==1)
        {
            $data->status=0;
        }
        else
        {
            $data->status=1;
        }
        $data->save();
        flash(translate('status has been updated successfully'))->success();
    }
    function activity($id)
    {
        return view('backend.staff.staffs.activite'); 
    }
}
