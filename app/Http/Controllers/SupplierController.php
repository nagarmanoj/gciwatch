<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
   // Supplier Start
    public function supplier()
    {
       $data['partnersData'] = Supplier::orderBy('id','ASC')->get();
        return view("backend.people.supplier.index", $data);
    }
    public function SupplierCreate()
    {
      return view("backend.people.supplier.create");
    }

    public function saveSupplier(Request $Request)
    {
        $post = new Supplier();
        $post->sequence_name = $Request->sequence_name;
        $post->save();
        flash(translate('Supplier has been added successfully'))->success();
        return back();
    }

    public function supplieredit(Request $request, $id)
    {
        $partners = Supplier::findOrFail($id);
        return view('backend.people.supplier.edit', compact('partners'));
    }

    public function supplierUpdate(Request $request, $id)
    {
      $partnersUpdate = Supplier::findOrFail($id);
      $partnersUpdate->sequence_name = $request->sequence_name;

      $partnersUpdate->save();

      flash(translate('Supplier has been updated successfully'))->success();
      return back();
    }
    // Supplier End






    public function optionDestroy($id)
    {
        $post = Supplier::where('id',$id)->delete();
        flash(translate('Supplier has been deleted successfully'))->success();
        return back();
    }



}
