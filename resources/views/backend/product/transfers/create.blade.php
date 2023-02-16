@extends('backend.layouts.app')



@section('content')

<meta name="_token" content="{{ csrf_token() }}"/>

<div class="aiz-titlebar text-left mt-2 mb-3">

    <h5 class="mb-0 h6">{{translate('Add Transfer')}}</h5>

</div>

<div class="">

  <form class="p-4" action="{{route('transfer.save')}}" method="POST">

      @csrf

      <div class="row">



          <div class="col-sm-4 form-group">

            <label class="col-sm-3 col-from-label font-weight-bold" for="name">

                {{ translate('Date *')}}

            </label>

            <input type="date" placeholder="{{ translate('Date')}}"  value="<?php echo date('Y-m-d'); ?>" name="date" class="form-control" required >

          </div>

          <div class="col-sm-4 form-group">

            <label class="col-from-label font-weight-bold" for="name">

                {{ translate('Reference No')}}

            </label>

              <input type="text" placeholder="{{ translate('Reference No')}}"  name="reference_no" class="form-control">

          </div>

          <div class="col-sm-4 form-group">

            <label class="col-sm-6 col-from-label font-weight-bold" for="name">

                {{translate('To Warehouse *')}}

            </label>

            <select class="form-control aiz-selectpicker warehouse_id"  name="warehouse_id_to" id="warehouse_id_to" data-live-search="true" required>

                <option value="">{{ translate('Select Warehouse') }}</option>

                @foreach (\App\Models\Warehouse::all() as $Warehouse)

                <option value="{{ $Warehouse->id }}">{{ $Warehouse->name }}</option>

                @endforeach

            </select>

          </div>

          <div class="col-sm-4 form-group">

            <label class="col-sm-6 col-from-label font-weight-bold" for="name">

                {{translate('From Warehouse *')}}

            </label>

            <select class="form-control aiz-selectpicker warehouse_id" name="warehouse_id_from" id="warehouse_id_from" data-live-search="true" required>

                <option value="">{{ translate('Select Warehouse') }}</option>

                @foreach (\App\Models\Warehouse::all() as $Warehouse)

                <option value="{{ $Warehouse->id }}">{{ $Warehouse->name }}</option>

                @endforeach

            </select>

          </div>

      <!-- </div> -->

          <div class="col-sm-4 form-group">

            <label class="col-sm-3 col-from-label font-weight-bold" for="name">

                {{translate('status *')}}

            </label>

            <select class="form-control aiz-selectpicker"  name="status" data-live-search="true" required>

                <option value="1">{{ translate('completed') }}</option>

                <option value="2">{{ translate('pending') }}</option>

                <option value="3">{{ translate('sent') }}</option>

            </select>

          </div>

          <div class="col-sm-4 form-group">

            <label class="col-from-label font-weight-bold" for="name">

                {{ translate('Shipping')}}

            </label>

              <input type="text" placeholder="{{ translate('Shipping')}}" value="0" name="shipping" class="form-control">

          </div>

          <div class="col-sm-4 form-group">

            <label class="col-from-label font-weight-bold" for="name">

                {{ translate('Attach Document')}}

            </label>

              <input type="file" placeholder="{{ translate('Document')}}"  name="document" class="form-control">

          </div>

      </div>

      <div class="form-group row form-group">

          <div class="col-md-12 d-flex dropdown">

            <i class="las la-bars" style="font-size: 42px;"></i>

              <input type="search" class="form-control" id="pro_transfer_search"  value="">

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

      <tbody  id="autocompletrans">



      </tbody>

      <tfoot class="storefoot">

        <tr>

          <td>Total</td>

          <td class="tfootcost"></td>

          <td class="tfootqty"> </td>

          <td class="tfootstotal"></td>

          <td><i class="las la-trash"></i></td>

        </tr>

      </tfoot>

    </table>

    <div class="form-group row">

          <div class="col-md-12 d-flex">

          <label class="col-from-label font-weight-bold" for="name">

                {{ translate('Note')}}

            </label>

            <div class="col-md-12">

                <textarea class="aiz-text-editor" name="note"></textarea>

            </div>

          </div>

      </div>

      <div class="form-group mb-0 text-right">

          <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>

      </div>

  </form>

</div>



@endsection

@section('script')

<script type="text/javascript">

$(document).on('change','#warehouse_id_from',function() {

  var warehouse_id_from = $("#warehouse_id_from").val();

  var warehouse_id_to = $("#warehouse_id_to").val();

  // alert(warehouse_id_from);

  if(warehouse_id_from != ''){

  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $( "#pro_transfer_search" ).autocomplete({



        source: function(request, response) {

            $.ajax({

              url:"{{route('transfer.searchAjax')}}",

              type: 'post',

              dataType: "json",

            data: {

                    _token: CSRF_TOKEN,

                    warehouse_id_from: warehouse_id_from,

                    warehouse_id_to: warehouse_id_to,

                    term : request.term

             },

            dataType: "json",

            success: function( data ) {

               response( data );

            }

        });

    },

    select: function (event, ui) {

      var formatter = new Intl.NumberFormat('en-US', {

      style: 'currency',

      currency: 'USD',

    });

      // console.log(ui.item);

      var pro_tble = "<tr class='returnItems'><td><input type='hidden' value="+ui.item.value+" />"+ui.item.model+"-"+ui.item.sku+"-"+ui.item.weight+"-"+ui.item.custom_6+"-("+ui.item.label+")</td><td class='rprocost' data-cost="+ui.item.product_cost+">"+formatter.format(ui.item.product_cost)+"</td><td><input class='form-control rproqty' name='proitemarr["+ui.item.value+"][]' type='text' value='1' readonly /></td><td class='rprocost2' data-subtotal="+ui.item.product_cost+">"+formatter.format(ui.item.product_cost)+"</td><td><button type='button' class='btn btn-soft-danger btn-icon btn-circle btn-sm protrash'><i class='las la-trash'></i></button></td></tr>";

      $('#autocompletrans').append(pro_tble);



       // $('#pro_stock_search').val(ui.item.label);

       // $('#employeeid').val(ui.item.value);

       returnCalc();

       return false;

    }

 });

}else{

  alert("Please select above first");

}

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

   var formatter = new Intl.NumberFormat('en-US', {

    style: 'currency',

    currency: 'USD',

  });

   $('.tfootcost').text(formatter.format(rcosut));

   $('.tfootstotal').text(formatter.format(rsubtotal));

   $('.tfootqty').text(rqty);



}

$("#pro_transfer_search").keyup(function(){

     var warehouse_id_to=$("#warehouse_id_to").val();

     var warehouse_id_from=$("#warehouse_id_from").val();

     if(warehouse_id_to==warehouse_id_from)

     {

       alert("Please select different warehouse");

     }

     else

     {



     }

});

</script>

@endsection

