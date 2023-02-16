@extends('backend.layouts.app')
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Add Inventory')}}</h5>
</div>
<div class="">
  <form class="p-4" action="{{route('inventory_run.save')}}" method="POST">
    @csrf
    <div class="form-group row">
      <div class="col-sm-4">
        <label class="col-sm-6 col-from-label font-weight-bold" for="name">
          {{translate('Listing Type *')}}
        </label>
        <select class="form-control aiz-selectpicker" id="listing_type_val"  name="listing_type" data-live-search="true" required>
          <option value="">{{ translate('Select List Type') }}</option>
          @foreach (App\SiteOptions::where('option_name', '=', 'listingtype')->get() as $product_type)
          <option value="{{ $product_type->option_value }}">{{ $product_type->option_value }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-sm-4">
        <label class="col-sm-6 col-from-label font-weight-bold" for="name">
          {{translate('Warehouse *')}}
        </label>
        <select class="form-control aiz-selectpicker" id="warehouse_id"  name="warehouse_id" data-live-search="true" required>
          <option value="">{{ translate('Select Warehouse') }}</option>
            @foreach (\App\Models\Warehouse::all() as $Warehouse)
            <option value="{{ $Warehouse->id }}">{{ $Warehouse->name }}</option>
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
        <th>Product Name (Stock ID - Condition - Model Number - Serial - Weight - Screw Count - Paper Cert - Dial - Bazel - Band)</th>
        <th> <i class="las la-trash"></i> </th>
      </tr>
    </thead>
    <tbody  id="autocompleInv">

    </tbody>
  </table>
  <input type="hidden" id="hdn_product_type" name="hdn_product_type" value="">
  <input type="hidden" id="hdn_warehouse" name="hdn_warehouse" value="">
  <div class="form-group mb-0 text-left">
    <button type="submit" class="btn btn-primary">{{translate('Submit')}}</button>
  </div>
</form>
</div>
@endsection
@section('script')
<script type="text/javascript">
  $(document).on('change','#listing_type_val',function(){
    product_type = $("#listing_type_val").val();
    // alert(product_type);
    $("#hdn_product_type").val(product_type); 
    $('#autocompleInv').html("");
    autocom();
  });
  $(document).on('change','#warehouse_id',function(){
    warehouses_name = $("#warehouse_id").val();
    // alert(warehouses_name);
    $("#hdn_warehouse").val(warehouses_name);
    $('#autocompleInv').html("");
    autocom();
  });
  // CSRF Token
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  function autocom(){
  $( "#pro_stock_search" ).autocomplete({
    source: function( request, response ) {
      // alert(product_type);
      // Fetch data
      $.ajax({
        url:"{{route('inventory_run.ajax')}}",
        type: 'post',
        dataType: "json",
        data: {
           _token: CSRF_TOKEN,
           warehouse: warehouses_name,
           product_type: product_type,
           search: request.term
          },
          success: function( data ) {
          // console.log(data.length);
          if(data.length == 1){
            // $("ul .ui-menu-item:nth-child(1)").trigger('click');
            setTimeout(function () {
              $("ul .ui-menu-item:nth-child(1)").trigger('click');
            }, 1000);
          }else if(data.length < 1){
            GetAutoComVal();
          }
           response( data );
        }
      });
    },
    selectFirst:true,
    select: function (event, ui) {
      // console.log(ui.item);
      var pro_tble = "<tr class='returnItems'><td><input name='missing_id[]' type='hidden' value="+ui.item.value+" /><input name='missing[]' type='hidden' value="+ui.item.label+" />"+ui.item.name+"-"+ui.item.model+"-"+ui.item.sku+"-"+ui.item.weight+"-"+ui.item.custom_1+"-"+ui.item.custom_2+"-"+ui.item.custom_3+"-"+ui.item.custom_4+"-"+ui.item.paper_cart+"-("+ui.item.label+")</td><td><button type='button' class='btn btn-soft-danger btn-icon btn-circle btn-sm protrash'><i class='las la-trash'></i></button></td></tr>";
      $('#autocompleInv').append(pro_tble);
       // $('#employeeid').val(ui.item.value);
       return false;
    }
  });
}
function GetAutoComVal(){
  var searchVal = $("#pro_stock_search").val();
  if(searchVal.length >= 5){
    var searchData ="<tr class='returnItems'><td><input name='extrakeyup[]' type='hidden' value="+searchVal+" />"+searchVal+"</td><td><button type='button' class='btn btn-soft-danger btn-icon btn-circle btn-sm protrash'><i class='las la-trash'></i></button></td></tr>";
    $('#autocompleInv').append(searchData);
     return false;
  }
}
</script>
@endsection

