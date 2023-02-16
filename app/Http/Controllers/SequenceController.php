<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Sequence;
class SequenceController extends Controller
{
   // Sequence Start
    public function sequence(Request $request)
    {
      $pagination_qty = isset($request->purchases_pagi)?$request->purchases_pagi:25;

        if($request->input('purchases_pagi')!=NULL){

        $product_qty =  ($request->input('purchases_pagi'));

        }
        $sort_search = null;
       $SeqData = Sequence::orderBy('sequence_name');
      //  $paginateData = $SeqData->paginate(20);
      $product_qty=$request->purchases_pagi;
      if ($request->search != null){
                      $SeqData->orWhere('sequence_name', 'like', '%'.$request->search.'%');
                      $SeqData->orWhere('sequence_prefix', 'like', '%'.$request->search.'%');
                      $SeqData->orWhere('sequence_start', 'like', '%'.$request->search.'%');

          $sort_search = $request->search;

      }
      if(isset($product_qty)){

        if($product_qty!='All'){

            $purchases = $SeqData->paginate(($product_qty));

        }

        else if(isset($product_qty) && $product_qty=='All'){

            $purchases = $detailedProduct;

        }

    }else{

     $purchases = $SeqData->paginate(25);

    }
       $partnersData = $SeqData->get();
        return view("backend.site_options.sequence.index", compact('partnersData','sort_search','pagination_qty','purchases'));
    }
    public function SequenceCreate()
    {
      return view("backend.site_options.sequence.create");
    }
    public function saveSequence(Request $Request)
    {
      $Request->validate([
        'sequence_name' => 'required|unique:sequence'
      ]);
        $post = new Sequence();
        $post->sequence_name = $Request->sequence_name;
        $post->sequence_prefix = $Request->sequence_prefix;
        $post->sequence_start = $Request->sequence_start;
        $post->cost_code_multiplier = $Request->cost_code_multiplier;
        $post->save();
        flash(translate('Sequence has been added successfully'))->success();
        return back();
    }
    public function sequenceedit(Request $request, $id)
    {
       $partners = Sequence::findOrFail($id);
       return view('backend.site_options.sequence.edit', compact('partners'));
    }
    public function sequenceUpdate(Request $request, $id)
    {
      $partnersUpdate = Sequence::findOrFail($id);
      $partnersUpdate->sequence_name = $request->sequence_name;
      $partnersUpdate->sequence_prefix = $request->sequence_prefix;
      $partnersUpdate->sequence_start = $request->sequence_start;
      $partnersUpdate->cost_code_multiplier = $request->cost_code_multiplier;
      $partnersUpdate->save();
      flash(translate('Sequence has been updated successfully'))->success();
      return back();
    }
    // Sequence End
    public function optionDestroy($id)
    {
        $post = Sequence::where('id',$id)->delete();
        flash(translate('Sequence has been deleted successfully'))->success();
        return back();
    }
}
