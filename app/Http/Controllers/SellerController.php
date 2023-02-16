<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Seller;
use App\User;
use App\Shop;
use App\Product;
use App\Order;
use App\OrderDetail;
use Illuminate\Support\Facades\Hash;
use App\Notifications\EmailVerificationNotification;
use Cache;
use Illuminate\Support\Facades\DB;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $approved = null;
        $sellers = Seller::whereIn('user_id', function ($query) {
            $query->select('id')
                ->from(with(new User)->getTable());
        })->orderBy('created_at', 'desc');

        if ($request->has('search')) {
            $sort_search = $request->search;
            $user_ids = User::where('user_type', 'seller')->where(function ($user) use ($sort_search) {
                $user->where('name', 'like', '%' . $sort_search . '%')->orWhere('email', 'like', '%' . $sort_search . '%');
            })->pluck('id')->toArray();
            $sellers = $sellers->where(function ($seller) use ($user_ids) {
                $seller->whereIn('user_id', $user_ids);
            });
        }
        if ($request->approved_status != null) {
            $approved = $request->approved_status;
            $sellers = $sellers->where('verification_status', $approved);
        }
        // dd($sellers);
        $sellers = $sellers->paginate(15);
        return view('backend.sellers.index', compact('sellers', 'sort_search', 'approved'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $detail['countries_detail'] = DB::table('countries')->get();
        return view('backend.sellers.create',$detail);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (User::where('email', $request->email)->first() != null) {
            flash(translate('Email already exists!'))->error();
            return back();
        }
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->user_type = "seller";
        // $user->password = Hash::make($request->password);

        if ($user->save()) {
            if (get_setting('email_verification') != 1) {
                $user->email_verified_at = date('Y-m-d H:m:s');
            } else {
                $user->notify(new EmailVerificationNotification());
            }
            $user->phone;

            $user->save();

            $seller = new Seller;
            $seller->user_id = $user->id;
            $seller->company = $request->company;
            $seller->id = $request->id;
            $seller->reseller_permit = $request->reseller_permit;
            $seller->tax_id = $request->tax_id;
            $seller->phone = $request->phone;
            $seller->company_address = $request->company_address;
            $seller->address = $request->address;
            $seller->city = $request->city;
            $seller->state = $request->state;
            $seller->postal_code = $request->postal_code;
            $seller->country = $request->country;
            $seller->bank_acc_name = $request->bank_acc_name;
            $seller->ach = $request->ach;
            $seller->bank_routing_no = $request->bank_routing_no;
            $seller->bank_acc_no = $request->bank_acc_no;
            $seller->bank_address = $request->bank_address;

            if ($seller->save()) {
                $shop = new Shop;
                $shop->user_id = $user->id;
                $shop->slug = 'demo-shop-' . $user->id;
                $shop->save();

                flash(translate('Seller has been inserted successfully'))->success();
                return redirect()->route('sellers.index');
            }
        }
        flash(translate('Something went wrong'))->error();
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
        $seller = Seller::findOrFail(decrypt($id));
        return view('backend.sellers.edit', compact('seller'));
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
        $seller = Seller::findOrFail($id);
        $user = $seller->user;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if (strlen($request->password) > 0) {
            $user->password = Hash::make($request->password);
        }
        if ($user->save()) {
          $seller->company = $request->company;
          $seller->id = $request->id;
          $seller->reseller_permit = $request->reseller_permit;
          $seller->tax_id = $request->tax_id;
          $seller->phone = $request->phone;
          $seller->company_address = $request->company_address;
          $seller->address = $request->address;
          $seller->city = $request->city;
          $seller->state = $request->state;
          $seller->postal_code = $request->postal_code;
          $seller->country = $request->country;
          $seller->bank_acc_name = $request->bank_acc_name;
          $seller->ach = $request->ach;
          $seller->bank_routing_no = $request->bank_routing_no;
          $seller->bank_acc_no = $request->bank_acc_no;
          $seller->bank_address = $request->bank_address;
            if ($seller->save()) {
                flash(translate('Seller has been updated successfully'))->success();
                return redirect()->route('sellers.index');
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
        $seller = Seller::findOrFail($id);

        Shop::where('user_id', $seller->user_id)->delete();

        Product::where('user_id', $seller->user_id)->delete();

        $orders = Order::where('user_id', $seller->user_id)->get();

        foreach ($orders as $key => $order) {
            OrderDetail::where('order_id', $order->id)->delete();
        }

        Order::where('user_id', $seller->user_id)->delete();

        User::destroy($seller->user->id);

        if (Seller::destroy($id)) {
            flash(translate('Seller has been deleted successfully'))->success();
            return redirect()->route('sellers.index');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function bulk_seller_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $seller_id) {
                $this->destroy($seller_id);
            }
        }

        return 1;
    }

    public function show_verification_request($id)
    {
        $seller = Seller::findOrFail($id);
        return view('backend.sellers.verification', compact('seller'));
    }

    public function approve_seller($id)
    {
        $seller = Seller::findOrFail($id);
        $seller->verification_status = 1;
        if ($seller->save()) {
            Cache::forget('verified_sellers_id');
            flash(translate('Seller has been approved successfully'))->success();
            return redirect()->route('sellers.index');
        }
        flash(translate('Something went wrong'))->error();
        return back();
    }

    public function reject_seller($id)
    {
        $seller = Seller::findOrFail($id);
        $seller->verification_status = 0;
        $seller->verification_info = null;
        if ($seller->save()) {
            Cache::forget('verified_sellers_id');
            flash(translate('Seller verification request has been rejected successfully'))->success();
            return redirect()->route('sellers.index');
        }
        flash(translate('Something went wrong'))->error();
        return back();
    }


    public function payment_modal(Request $request)
    {
        $seller = Seller::findOrFail($request->id);
        return view('backend.sellers.payment_modal', compact('seller'));
    }

    public function profile_modal(Request $request)
    {
        $seller = Seller::findOrFail($request->id);
        return view('backend.sellers.profile_modal', compact('seller'));
    }

    public function updateApproved(Request $request)
    {
        $seller = Seller::findOrFail($request->id);
        $seller->verification_status = $request->status;
        if ($seller->save()) {
            Cache::forget('verified_sellers_id');
            return 1;
        }
        return 0;
    }

    public function login($id)
    {
        $seller = Seller::findOrFail(decrypt($id));

        $user  = $seller->user;

        auth()->login($user, true);

        return redirect()->route('dashboard');
    }

    public function ban($id)
    {
        $seller = Seller::findOrFail($id);

        if ($seller->user->banned == 1) {
            $seller->user->banned = 0;
            flash(translate('Seller has been unbanned successfully'))->success();
        } else {
            $seller->user->banned = 1;
            flash(translate('Seller has been banned successfully'))->success();
        }

        $seller->user->save();
        return back();
    }
     public function productSellerAjax(Request $request)
    {
        // $validatedData = $request->validate([
        //     'email' => 'required|email|unique:users'
        // ]);
        // $user = User::create($validatedData);

      // code...




      if (User::where('email', $request->email)->first() != null) {
          flash(translate('Email already exists!'))->error();
          return response()->json(array("exists" => true));
        //   return back();
      }
      $user = new User;
      $user->name = $request->name;
      $user->email = $request->email;
      $user->user_type = "seller";
      $user->password = Hash::make($request->password);

    //   $email = $request->input('email');
    //   $isExists = \App\User::where('email',$email)->first();
    //   if($isExists){
    //       return response()->json(array("exists" => true));
    //   }else{
    //       return response()->json(array("exists" => false));
    //   }

      if ($user->save()) {
          if (get_setting('email_verification') != 1) {
              $user->email_verified_at = date('Y-m-d H:m:s');
          } else {
              $user->notify(new EmailVerificationNotification());
          }
          $user->phone;

          $user->save();

          $seller = new Seller;
          $seller->user_id = $user->id;
          $seller->company = $request->company;
          $seller->id = $request->id;
          $seller->reseller_permit = $request->reseller_permit;
          $seller->tax_id = $request->tax_id;
          $seller->phone = $request->phone;
          $seller->company_address = $request->company_address;
          $seller->address = $request->address;
          $seller->city = $request->city;
          $seller->state = $request->state;
          $seller->postal_code = $request->postal_code;
          $seller->country = $request->country;
          $seller->bank_acc_name = $request->bank_acc_name;
          $seller->ach = $request->ach;
          $seller->bank_routing_no = $request->bank_routing_no;
          $seller->bank_acc_no = $request->bank_acc_no;
          $seller->bank_address = $request->bank_address;

          if ($seller->save()) {
              $shop = new Shop;
              $shop->user_id = $user->id;
              $shop->slug = 'demo-shop-' . $user->id;
              $shop->save();
          }


         $sellerRecord = User::where('user_type', 'seller')->get();
         $sellerHtml = "";
         foreach ($sellerRecord as $seller) {
           $seller_id =$seller->id;
           $seller_name =$seller->name;
           $sellerHtml .= "<option value='".$seller_id."'>$seller_name</option>";
         }
          return response()->json(['success' => true,'selleroptionHTMl'=>$sellerHtml]);
          // exit;
      }
    }
  
}
