<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Expertise;

class ExpertiseController extends Controller
{
    public function expertise()
    {
       $data['expertiseData'] = Expertise::orderBy('id','ASC')->get();
        return view("backend.people.expertise.index", $data);
    }
    public function ExpertiseCreate()
    {
      return view("backend.people.expertise.create");
    }

    public function saveExpertise(Request $Request)
    {
        $post = new Expertise();
        $post->expertise_name = $Request->expertise_name;
        $post->save();
        flash(translate('Expertise has been added successfully'))->success();
        return back();
    }
    public function expertiseedit(Request $request, $id)
    {
        $expertise = Expertise::findOrFail($id);
        return view('backend.people.expertise.edit', compact('expertise'));
    }

    public function expertiseUpdate(Request $request, $id)
    {
      $expertiseUpdate = Expertise::findOrFail($id);
      $expertiseUpdate->expertise_name = $request->expertise_name;
      $expertiseUpdate->save();

      flash(translate('Expertise has been updated successfully'))->success();
      return back();
    }
    public function optionDestroy($id)
    {
        $post = Expertise::where('id',$id)->delete();
        flash(translate('Expertise has been deleted successfully'))->success();
        return back();
    }
}
