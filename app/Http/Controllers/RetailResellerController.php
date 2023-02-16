<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Memo;
use App\MemoDetail;
use App\Product;
use App\Activitylog;
use App\RetailReseller;
use Illuminate\Support\Facades\DB;
class RetailResellerController extends Controller
{
   // RetailReseller Start

    public function retailreseller(Request $request)

    {
      $pagination_qty = isset($request->purchases_pagi)?$request->purchases_pagi:25;

        if($request->input('purchases_pagi')!=NULL){

        $product_qty =  ($request->input('purchases_pagi'));

        }
        $sort_search = null;

       $PurchasesProduct = RetailReseller::orderBy('id','ASC');
       $product_qty=$request->purchases_pagi;
        if ($request->search != null){
                        $PurchasesProduct->orWhere('customer_name', 'like', '%'.$request->search.'%');
                        $PurchasesProduct->orWhere('company', 'like', '%'.$request->search.'%');
                        $PurchasesProduct->orWhere('email', 'like', '%'.$request->search.'%');
                        $PurchasesProduct->orWhere('phone', 'like', '%'.$request->search.'%');

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
      $retailreseller=$PurchasesProduct->get();

        return view("backend.people.retailreseller.index", compact('retailreseller','sort_search','pagination_qty','purchases'));

    }

    public function RetailResellerCreate()

    {

        // $customer_group['customer_group']=['Retail','reseller'];

        // dd($customer_group);

        $detail['countries_detail'] = DB::table('countries')->get();

        // dd($detail);

      return view("backend.people.retailreseller.create",$detail);

    }



    public function saveRetailReseller(Request $Request)

    {

        $post = new RetailReseller();

        $post->customer_group = $Request->customer_group;

        $post->price_group = $Request->price_group;

        $post->company = $Request->company;

        $post->office_phone_number = $Request->office_phone_number;

        $post->office_address = $Request->office_address;

        $post->city = $Request->city;

        $post->drop_state = $Request->drop_state;

        $post->country = $Request->country;

        $post->zipcode = $Request->zipcode;

        $post->customer_name = $Request->customer_name;

        $post->email = $Request->email;

        $post->phone = $Request->phone;

        // $post->contact_name = $Request->contact_name;

        // $post->contact_email = $Request->contact_email;

        // $post->contact_phone_no = $Request->contact_phone_no;

        $post->terms = $Request->terms;

        $post->website = $Request->website;

        $post->tax_id = $Request->tax_id;

        $post->text_reseller_permit = $Request->text_reseller_permit;

        $post->billing_address = $Request->billing_address;

        $post->billing_city = $Request->billing_city;

        $post->text_billing_state = $Request->text_billing_state;

        $post->billing_country = $Request->billing_country;

        $post->billing_zipcode = $Request->billing_zipcode;

        $post->shipping_address = $Request->shipping_address;

        $post->shipping_city = $Request->shipping_city;

        $post->text_shipping_state = $Request->text_shipping_state;

        $post->shipping_country = $Request->shipping_country;

        $post->shipping_zipcode = $Request->shipping_zipcode;

        $post->save();

        flash(translate('RetailReseller has been added successfully'))->success();

        return back();

    }



    public function retailreselleredit(Request $request, $id)

    {

        $retailreseller = RetailReseller::findOrFail($id);

        return view('backend.people.retailreseller.edit', compact('retailreseller'));

    }



    public function retailresellerUpdate(Request $request, $id)

    {

      $retailreseller = RetailReseller::findOrFail($id);

      $retailreseller->customer_group = $request->customer_group;

      $retailreseller->price_group = $request->price_group;

      $retailreseller->company = $request->company;

      $retailreseller->office_phone_number = $request->office_phone_number;

      $retailreseller->office_address = $request->office_address;

      $retailreseller->city = $request->city;

      $retailreseller->drop_state = $request->drop_state;

      $retailreseller->country = $request->country;

      $retailreseller->zipcode = $request->zipcode;

      $retailreseller->customer_name = $request->customer_name;

      $retailreseller->email = $request->email;

      $retailreseller->phone = $request->phone;

    //   $retailreseller->contact_name = $request->contact_name;

    //   $retailreseller->contact_email = $request->contact_email;

    //   $retailreseller->contact_phone_no = $request->contact_phone_no;

      $retailreseller->terms = $request->terms;

      $retailreseller->website = $request->website;

      $retailreseller->tax_id = $request->tax_id;

      $retailreseller->text_reseller_permit = $request->text_reseller_permit;

      $retailreseller->billing_address = $request->billing_address;

      $retailreseller->billing_city = $request->billing_city;

      $retailreseller->text_billing_state = $request->text_billing_state;

      $retailreseller->billing_country = $request->billing_country;

      $retailreseller->billing_zipcode = $request->billing_zipcode;

      $retailreseller->shipping_address = $request->shipping_address;

      $retailreseller->shipping_city = $request->shipping_city;

      $retailreseller->text_shipping_state = $request->text_shipping_state;

      $retailreseller->shipping_country = $request->shipping_country;

      $retailreseller->shipping_zipcode = $request->shipping_zipcode;



      $retailreseller->save();



      flash(translate('RetailReseller has been updated successfully'))->success();

      return back();

    }

    // RetailReseller End













    public function optionDestroy($id)

    {

        $post = RetailReseller::where('id',$id)->delete();

        flash(translate('RetailReseller has been deleted successfully'))->success();

        return back();

    }

    public function activities($id)

    {

        $activities = DB::table('memos')

                        ->join('memo_details', 'memo_details.memo_id', '=', 'memos.id')

                        ->join('retail_resellers', 'retail_resellers.id', '=', 'memos.customer_name')

                        ->join('products', 'products.id', '=', 'memo_details.product_id')

                        ->select('memos.id as memoid','memo_details.memo_id','memo_details.id','memo_details.item_status','memo_details.item_status','retail_resellers.*','products.stock_id','products.model','memo_details.row_total','memo_details.status_updated_date')

                        ->where('retail_resellers.id','=' ,$id)

                        ->first();
        $activitylogData = Activitylog::where('extra_id',$id)->where('type','=','Memo_status')->orderBy('created_at','DESC')->get();

                        // $activities->memo_id;

                        // echo $id;exit();

                        // dd($activities);

                        return view("backend.people.retailreseller.activitis" , compact('activities','activitylogData'));

    }



    public function MemoCompanyAjax(Request $Request)

    {



      // Add Company...

      $post = new RetailReseller();

      $post->customer_group = $Request->customer_group;

      $post->price_group = $Request->price_group;

      $post->company = $Request->company;

      $post->office_phone_number = $Request->office_phone_number;

      $post->office_address = $Request->office_address;

      $post->city = $Request->city;

      $post->drop_state = $Request->drop_state;

      $post->country = $Request->country;

      $post->zipcode = $Request->zipcode;

      $post->customer_name = $Request->customer_name;

      $post->email = $Request->email;

      $post->phone = $Request->phone;

    //   $post->contact_name = $Request->contact_name;

    //   $post->contact_email = $Request->contact_email;

    //   $post->contact_phone_no = $Request->contact_phone_no;

      $post->terms = $Request->terms;

      $post->website = $Request->website;

      $post->tax_id = $Request->tax_id;

      $post->text_reseller_permit = $Request->text_reseller_permit;

      $post->billing_address = $Request->billing_address;

      $post->billing_city = $Request->billing_city;

      $post->text_billing_state = $Request->text_billing_state;

      $post->billing_country = $Request->billing_country;

      $post->billing_zipcode = $Request->billing_zipcode;

      $post->shipping_address = $Request->shipping_address;

      $post->shipping_city = $Request->shipping_city;

      $post->text_shipping_state = $Request->text_shipping_state;

      $post->shipping_country = $Request->shipping_country;

      $post->shipping_zipcode = $Request->shipping_zipcode;

      $post->save();



       $RetailResellerOptions = RetailReseller::all();

       $RetailResellerOptionHtml = "";

       foreach ($RetailResellerOptions as $RetailReseller) {

         $RetailReseller_id =$RetailReseller->id;

         $company_name =$RetailReseller->company;

         $RetailResellerOptionHtml .= "<option value='".$RetailReseller_id."'>$company_name</option>";

       }

       return response()->json(['success' => 'Data Stored Successfully','RetailResellerHTML'=>$RetailResellerOptionHtml,'post'=>$post]);

    }







}
