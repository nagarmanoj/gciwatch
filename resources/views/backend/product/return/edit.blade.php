@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Edit Return')}}</h5>
</div>
<div class="">
  <form class="p-4" action="{{route('return.update', $return->id)}}" method="POST">
      @csrf
      <div class="form-group row">

          <div class="col-sm-4">
            <label class="col-sm-3 col-from-label font-weight-bold" for="name">
                {{ translate('Date')}}
            </label>
            <input type="date" placeholder="{{ translate('Date')}}"  name="return_date" value="{{$return->return_date}}" class="form-control" required >
          </div>
          <div class="col-sm-4">
            <label class="col-from-label font-weight-bold" for="name">
                {{ translate('Reference No')}}
            </label>
              <input type="text" placeholder="{{ translate('Reference No')}}"  value="{{$return->reference_no}}" name="reference_no" class="form-control" required>
          </div>
          <div class="col-sm-4">
            <label class="col-sm-3 col-from-label font-weight-bold" for="name">
                {{translate('Supplier')}}
            </label>
            <select class="form-control aiz-selectpicker" name="supplier_id" data-live-search="true" required>
                <option value="">{{ translate('Select Supplier') }}</option>
                @foreach (\App\User::where('user_type', 'seller')->get() as $seller)
                <option  value="{{ $seller->id }}" @if($seller->id == $return->supplier_id) selected @endif>{{ $seller->name }}</option>
                @endforeach
            </select>
          </div>
      </div>
      <div class="form-group row">
          <div class="col-md-12 d-flex">
            <i class="las la-bars" style="font-size: 42px;"></i>
              <input type="search" class="form-control" id="pro_stock_search"  value="">
          </div>
      </div>

      <table class="table">
      <thead class="thead-light" >
        <tr>
          <th>Product (Model Number - Serial Number - Weight - Screw Count - (Stock Id))</th>
          <th>Product Cost</th>
          <th>Quantity</th>
          <th>Subtotal (USD)</th>
          <th> <i class="las la-trash"></i> </th>
        </tr>
      </thead>
      <tbody  id="autocomplepro">
          @php
          $costcount = 0;
          $qtycount = 0;
          $stotalcount = 0;
          @endphp
          @if(!empty($returnitems))
          @foreach($returnitems as $returnitem)
          <tr class="returnItems">
            <td>{{$returnitem->model}}-{{$returnitem->sku}}-{{$returnitem->weight}}-({{$returnitem->stock_id}})</td>
            <td  class="rprocost" data-cost="{{$returnitem->product_cost}}">${{$returnitem->product_cost}}</td>
            <td><input class='form-control rproqty' name='qty' type='text' value='{{$returnitem->qty}}' readonly /></td>
            <td class="rprocost2" data-subtotal='{{$returnitem->product_cost * $returnitem->qty}}'>${{$returnitem->product_cost * $returnitem->qty}}</td>
            <td><a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('itemreturn.destroy',['id'=>$returnitem->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Delete') }}">
                <i class="las la-trash"></i>
                </a>
            </td>
          </tr>
          @php
          $costcount =$costcount + $returnitem->product_cost;
          $qtycount =$qtycount + $returnitem->qty;
          $stotalcount =$stotalcount + ($returnitem->product_cost * $returnitem->qty);
          @endphp
          @endforeach
          @endif
      </tbody>
      <tfoot class="storefoot">
        <tr>
          <td>Total</td>
          <td class="tfootcost">${{$costcount}}</td>
          <td class="tfootqty"> {{$qtycount}} </td>
          <td class="tfootstotal">${{$stotalcount}}</td>
          <td><i class="las la-trash"></i></td>
        </tr>
      </tfoot>
    </table>

    <div class="form-group row">

        <div class="col-md-6">
          <label class="col-sm-3 col-from-label font-weight-bold" for="name">
              {{ translate('Return Note')}}
          </label>
            <!-- <input type="text" placeholder="{{ translate('Return Note')}}"  name="note" class="form-control" > -->
            <textarea name="note" class="form-control" placeholder="{{ translate('Return Note')}}" rows="8" cols="80">{{ $return->note }}</textarea>
        </div>

        <div class="col-md-6">
          <label class="col-sm-3 col-from-label font-weight-bold" for="name">
              {{ translate('Staff Note')}}
          </label>
            <!-- <input type="text" placeholder="{{ translate('Staff Note')}}"  name="staff_note" class="form-control" > -->
            <textarea name="staff_note" class="form-control" placeholder="{{ translate('Staff Note')}}" rows="8" cols="80">{{ $return->staff_note }}</textarea>
        </div>
    </div>


      <div class="form-group mb-0 text-right">
          <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
      </div>
  </form>
  <table class="table">
<tfoot class="storefoot">
  <tr>
    <td class="tfootqty"><span class="mr-5">Item</span> {{$qtycount}} </td>
    <td class="tfootcost"><span class="mr-5">Total</span> ${{$stotalcount}}</td>
    <td><span class="mr-5">Order Discount</span> $0.00</td>
    <td><span class="mr-5">Order Tax</span> $0.00</td>
    <td class="tfootcost"><span class="mr-5">Grand Total</span> ${{$stotalcount}}</td>
  </tr>
</tfoot>
</table>
</div>

@endsection
@section('modal')
    @include('modals.delete_modal')
@endsection
@section('script')
<script type="text/javascript">
$(document).ready(function() {
  $( "#pro_stock_search" ).autocomplete({

      source: function(request, response) {
          $.ajax({
          url:  + '/' +"autocomplete",
          data: {
                  term : request.term
           },
          dataType: "json",
          success: function(data){
             var resp = $.map(data,function(obj){
                  return obj.pro_stock_search;
             });

             response(resp);
          }
      });
  },
  minLength: 2
});
});
</script>
<script type="text/javascript">

// CSRF Token
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$(document).ready(function(){

  $( "#pro_stock_search" ).autocomplete({
    source: function( request, response ) {
      // Fetch data
      $.ajax({
        url:"{{ route('products.returnAjax') }}",
        type: 'post',
        dataType: "json",
        data: {
           _token: CSRF_TOKEN,
           search: request.term
        },
        success: function( data ) {
           response( data );
        }
      });
    },
    select: function (event, ui) {
      // console.log(ui.item);
      var pro_tble = "<tr class='returnItems'><td><input type='hidden' value="+ui.item.value+" />"+ui.item.model+"-"+ui.item.weight+"-("+ui.item.label+")</td><td class='rprocost' data-cost="+ui.item.product_cost+">$"+ui.item.product_cost+"</td><td><input class='form-control rproqty' name='proitemarr["+ui.item.value+"][]' type='text' value='1' readonly /></td><td class='rprocost2' data-subtotal="+ui.item.product_cost+">$"+ui.item.product_cost+"</td><td><button type='button' class='btn btn-soft-danger btn-icon btn-circle btn-sm protrash'><i class='las la-trash'></i></button></td></tr>";
      $('#autocomplepro').append(pro_tble);

       // $('#pro_stock_search').val(ui.item.label);
       // $('#employeeid').val(ui.item.value);
       returnCalc();
       return false;
    }
  });
});
function returnCalc()
{
  var rcosut = 0;
  var rsubtotal = 0;
  var rqty = 0;
  $(".returnItems").each(function() {
     var cost = $(this).find('.rprocost').data('cost');
     var subtotal = $(this).find('.rprocost2').data('subtotal');
     var qty = $(this).find('.rproqty').val();
     cost = parseFloat(cost);
     qty = parseInt(qty);
     var trowtotal = cost * qty;
     rsubtotal += trowtotal;
     rcosut += cost;
     rqty += qty;
   });
   $('.tfootcost').text("$"+rcosut);
   $('.tfootstotal').text("$"+rsubtotal);
   $('.tfootqty').text(rqty);

}

</script>
@endsection
