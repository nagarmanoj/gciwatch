<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MemoPayment;

class MemopaymentController extends Controller
{
   // Memo Start
    public function memopayment(Request $request)
    {
        $pagination_qty = isset($request->pagination_qty)?$request->pagination_qty:25;
        if($pagination_qty < 1){
            $pagination_qty = 25;
        }
       $data['memopaymentData'] = MemoPayment::orderBy('id','ASC')->paginate($pagination_qty);
       $data['pagination_qty'] = $pagination_qty;
        return view("backend.memo.memopayment.index", $data);
    }
    public function MemopaymentCreate()
    {
      return view("backend.memo.memopayment.create");
    }

    public function saveMemopayment(Request $Request)
    {
        $post = new MemoPayment();
        $post->payment_name = $Request->payment_name;
        $post->days = $Request->days;
        $post->percentage = $Request->percentage;
        $post->save();
        flash(translate('Payment Terms has been added successfully'))->success();
        return back();
    }

    public function memopaymentedit(Request $request, $id)
    {
        $memopayment = MemoPayment::findOrFail($id);
        return view('backend.memo.memopayment.edit', compact('memopayment'));
    }

    public function memopaymentUpdate(Request $request, $id)
    {
      $memoUpdate = MemoPayment::findOrFail($id);
      $memoUpdate->payment_name = $request->payment_name;
      $memoUpdate->days = $request->days;
      $memoUpdate->percentage = $request->percentage;

      $memoUpdate->save();

      flash(translate('Payment Terms has been updated successfully'))->success();
      return back();
    }
    // Memo End


    public function memopaymentDestroy($id)
    {
        $post = MemoPayment::where('id',$id)->delete();
        flash(translate('Payment Terms has been deleted successfully'))->success();
        return back();
    }



}
