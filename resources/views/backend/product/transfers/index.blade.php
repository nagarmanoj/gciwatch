<style>
    #delete_section:hover{
      background:blue !important;
	}
	#product_delete{
	    color:#797d91;
	}
	#product_delete:hover{
	    background-color:#377af9 !important;
	    color:#fff !important;
	}
	.trans_mi {
	    width: 100%;
	    align-content: end;
	    align-items: end;
	    display: flex;
	    justify-content: end;
	}
	.trans_mi .trans_btn{
    	float: right;
		margin-right: 31px;
	}
</style>
@extends('backend.layouts.app')
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3 hideonprint">
	<div class="align-items-center">
		<!-- <h1 class="h3">{{translate('All Transfers')}}</h1> -->
	</div>
</div>
<div class="dropdown mb-2 mb-md-0 hideonprint trans_mi">
	<button class="btn border dropdown-toggle trans_btn" type="button" data-toggle="dropdown">
		<i class="las la-bars fs-20"></i>
	</button>
	<div class="dropdown-menu dropdown-menu-right">
	@if(Auth::user()->user_type == 'admin' || in_array('56', json_decode(Auth::user()->staff->role->inner_permissions)))
		<form class="delete_section" action="{{route('bulk-transfers-delete')}}" method="post">
			@csrf
			<input type="hidden" name="checked_id" id="checkox_pro" value="">
			<button id="product_delete" type="submit" style="border:none;padding-right:163px;padding-top: 0.5rem;padding-bottom: 0.5rem;background:#fff;" class="w-100 exportPro-class" disabled>Delete</button>
		</form>
		@endif
		
		<form class="" action="{{route('transfers-export.index')}}" method="post">
			@csrf
			<input type="hidden" name="checked_id" id="checkox_pro_export" value="">
			<button id="product_export" type="submit" class="w-100 exportPro-class" disabled>Export to excel</button>
		</form>
	</div>
</div>
<div class="row hideonprint">
	<div class="col-md-12">
		<div class="card">
		    <div class="card-header row gutters-5">
				<div class="col text-center text-md-left">
					<h5 class="mb-md-0 h6">{{ translate('All Transfers') }}</h5>
				</div>
				@if(Auth::user()->user_type == 'admin' || in_array('54', json_decode(Auth::user()->staff->role->inner_permissions)))
		        <div class="btn-group mr-2" role="group" aria-label="Third group">
		        	<a class="btn btn-soft-primary" href="{{route('transfer.create')}}" title="{{ translate('add') }}">
			        	{{translate('Add transfers')}}<i class="lar la-plus-square"></i>
		        	</a>
		        </div>
				@endif
		    </div>
			<form class="" id="sort_products" action="" method="GET">
				<div class="card-header row gutters-5 purchases_form_sec">
					<div class="col-md-3 d-flex page_form_secs">
						<label class="fillter_sel_show m-auto"><b>Show</b></label>
						<input type="hidden" name="search" id="searchinputfield">
						<select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0 purchases_filter" name="pagination_qty" id="purchases_pagi" data-live-search="true">
							<option value="25" @if($pagination_qty == 25) selected @endif>{{translate('25')}}</option>
							<option  value="50"  @if($pagination_qty == 50) selected @endif>{{translate('50')}}</option>
							<option  value="100" @if($pagination_qty == 100) selected @endif>{{translate('100')}}</option>
							<!-- <option  value="all" @if($pagination_qty == "all") selected @endif>{{translate('all')}}</option> -->
						</select>
						<button type="submit" id="purchases_pagi_sub" name="purchases_pagi_sub" class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>
					</div>
					<div class="col-6 d-flex search_form_sec">
						<label class="fillter_sel_show m-auto"><b>Search</b></label>
						<input type="text" class="form-control form-control-sm sort_search_val"  @isset($sort_search) value="{{ $sort_search }}"  @endisset>
						<button type="button" class="search_btn_field"><i class="las la-search aiz-side-nav-icon" ></i></button>
					</div>
				</div>
			    <div class="card-body">
			        <table class="table aiz-table mb-0">
		            	<thead>
			                <tr>
								<th data-orderable="false">
		                            <input type="checkbox" class="select_count" id="select_count"  name="all[]">
		                        </th>	
								<th>{{translate('Date')}}</th>
								<th>{{translate('Reference No')}}</th>
								<th>{{translate('From Warehouse')}}</th>
								<th>{{translate('To Warehouse')}}</th>
								<th>{{translate('Stock Id')}}</th>
								<th>{{translate('Model number')}}</th>
								<th>{{translate('Total Qty')}}</th>
								<th>{{translate('Grand Total')}}</th>
								<th>{{translate('Status')}}</th>
								<th class="text-right">{{translate('Options')}}</th>
		                	</tr>
			            </thead>
						<tbody>
						@foreach($transfer as $key => $optData)
							@php
								setlocale(LC_MONETARY,"en_US");
								$total = money_format('%(#1n',$optData->total)."\n";
								$grand_total = money_format('%(#1n',$optData->grand_total)."\n";
							@endphp
							<tr data-id="{{ $optData->id}}">
								<td>
									<input type="checkbox" class="pro_checkbox" data-id="{{$optData->id}}" name="all_pro[]" value="{{$optData->id}}">
								</td>
								<td  class="RetData" data-target="#orderRetModal" data-toggle="modal" data-id="{{ $optData->id}}" >{{ date('m/d/20y', strtotime($optData->date)) }}</td>
								<td  class="RetData" data-target="#orderRetModal" data-toggle="modal" data-id="{{ $optData->id}}">{{ $optData->transfer_no}}</td>
								<td  class="RetData" data-target="#orderRetModal" data-toggle="modal" data-id="{{ $optData->id}}">{{ $optData->from_warehouse_name}}</td>
								<td  class="RetData" data-target="#orderRetModal" data-toggle="modal" data-id="{{ $optData->id}}">{{ $optData->to_warehouse_name}}</td>
								<td  class="RetData" data-target="#orderRetModal" data-toggle="modal" data-id="{{ $optData->id}}">{{ $optData->product_code}}</td>
								<td  class="RetData" data-target="#orderRetModal" data-toggle="modal" data-id="{{ $optData->id}}">{{ $optData->model}}</td>
								<td  class="RetData text-center" data-target="#orderRetModal" data-toggle="modal" data-id="{{ $optData->id}}">{{ $optData->quantity }}</td>
								<td  class="RetData" data-target="#orderRetModal" data-toggle="modal" data-id="{{ $optData->id}}">{{money_format('%(#1n',$optData->grand_total)."\n"}}</td>
								<td  class="RetData" data-target="#orderRetModal" data-toggle="modal" data-id="{{ $optData->id}}">
									@if($optData->status == 1)
										{{translate('Complete')}}
									@elseif($optData->status == 2)
										{{translate('Pending')}}
									@else
										{{translate('Sent')}}
									@endif
								</td>
								<td class="text-right">
									<!-- <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="#" title="{{ translate('Edit') }}">
										<i class="las la-edit"></i>
									</a> -->
									@if(Auth::user()->user_type == 'admin' || in_array('56', json_decode(Auth::user()->staff->role->inner_permissions)))
									<a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('transfer.destroy', $optData->id)}}" title="{{ translate('Delete') }}">
										<i class="las la-trash"></i>
									</a>
									@endif
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				<p>
              Showing {{ $purchases->firstItem() }} to {{ $purchases->lastItem() }} of total {{$purchases->total()}} entries
            </p>
            <div class="aiz-pagination">
              @if($pagination_qty != "all")
                {{ $purchases->appends(request()->input())->links() }}
              @endif
            </div>

				<!-- <div class="aiz-pagination">
					{{ $purchases->appends(request()->input())->links() }}
				</div> -->
			</form>
		</div>
	</div>
</div>
</div>

<!-- model  -->

<div class="modal fade" id="orderRetModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog increasewid" style="max-width:900px;">

    <div class="modal-content" id="micustomReturn">

			<div class="modal-header">

			<img src="https://gcijewel.com/public/uploads/all/O2yCFLQfu0nitWXPCfza2pYto0xjo8kqGbfusjON.png" class="img-fluid w-25 logo-rem">

        <!-- <h5 class="modal-title">Return Items</h5> -->

			<div class="close-btn">

				<button class="btn btn-xs btn-default no-print pull-right" onclick="micustomPrint()" style="border:1px solid gainsboro !important;"> <i class="las la-print"></i> Print</button>

				<button type="button" class="close micustomclose" data-dismiss="modal" aria-label="Close">

					<span aria-hidden="true">&times;</span>

				</button>

			</div>

      </div>

		<div class="modal-body" >

			<div id="reModelData">

			</div>

		</div>

      </div>

    </div>

  </div>

</div>

@endsection

@section('modal')

    @include('modals.delete_modal')

@endsection

@section('script')

<script type="text/javascript">

$(document).on("change", ".check-all", function() {

	if(this.checked) {

		// Iterate each checkbox

		$('.check-one:checkbox').each(function() {

			this.checked = true;

		});

	} else {

		$('.check-one:checkbox').each(function() {

			this.checked = false;

		});

	}



});

$(document).ready(function() {

	$(document).on('click','.pro_checkbox',function(){

		productCheckbox();

		productCheckboxExport();

	});

});

function bulk_delete()

{

	var data = new FormData($('#sort_products')[0]);

	$.ajax({

		headers: {

			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

		},

		url: "{{route('bulk-product-delete')}}",

		type: 'POST',

		data: data,

		cache: false,

		contentType: false,

		processData: false,

		success: function (response) {

			if(response == 1) {

			location.reload();

			}

		}

	});

}

$(document).on('click','.select_count',function() {

	if($(this).is(':checked'))

	{

		$('.pro_checkbox').prop('checked', true);

	}

	else

	{

		$('.pro_checkbox').prop('checked', false);

	}

	productCheckbox();

	productCheckboxExport();

});

function productCheckbox()

{

	var proCheckID = [];

	$.each($("input[name='all_pro[]']:checked"), function(){

			proCheckID.push($(this).val());

	});

	console.log(proCheckID);

	var proexpData =JSON.stringify(proCheckID);

	$('#checkox_pro').val(proexpData);

	if(proCheckID.length > 0)

	{

		$('#product_export').removeAttr('disabled');

	}

	else

	{

		$('#product_export').attr('disabled',true);

	}

	if(proCheckID.length > 0)

	{

		$('#product_delete').removeAttr('disabled');

		$('#product_delete').addClass('hoverProBtn');

	}

	else

	{

		$('#product_delete').attr('disabled',true);

		$('#product_delete').removeClass('hoverProBtn');

	}

}

function productCheckboxExport(){

	var proCheckID = [];

	$.each($("input[name='all_pro[]']:checked"), function(){

			proCheckID.push($(this).val());

	});

	var proexpData =	JSON.stringify(proCheckID);

	$('#checkox_pro_export').val(proexpData);

	if(proCheckID.length > 0)

	{

		$('#product_export').removeAttr('disabled');

	}

	else

	{

		$('#product_export').attr('disabled',true);

	}

	if(proCheckID.length > 0)

	{

		$('#product_delete').removeAttr('disabled');

	}

	else

	{

		$('#product_delete').attr('disabled',true);

	}

}



$('.RetData').on('click', function(e) {

  e.preventDefault();

	var getIdFromRow = $(event.target).closest('tr').data('id');

	// alert(getIdFromRow);

  $.ajax({

      url: "{{route('pro.transfers-return')}}",

      type: "POST",

      data: {"_token": "{{ csrf_token() }}","id" : getIdFromRow},

      success: function( response ) {

		console.log(response);

        var ReturnHtml = response.returnHtmlData;

				console.log(ReturnHtml);

        $('#reModelData').html(ReturnHtml);

      }

     });

   });

	//    $(document).on('click','.returnDataAnch',function(){

	//      var returnHref = $(this).attr('href');

	// 		 window.location.href = returnHref;

	//    });

function micustomPrint(){

	$('.no-print').hide();

	$('.hideonprint').hide();

	$('.micustomclose').hide();

	$('.micustomPrem').addClass("samClass");

	var printContents = document.getElementById("orderRetModal").innerHTML;

	var originalContents = document.body.innerHTML;

	document.body.innerHTML = originalContents;

	window.print();

	$('.hideonprint').show();

	$('.micustomclose').show();

	$('.micustomPrem').show();

	$('.no-print').show();

	$('.micustomPrem').removeClass("samClass");

	 return true;

	}

jQuery(document).on('click','.micustomclose',function() {

	    // jQuery('#orderRetModal').hide();

			jQuery('#orderRetModal').css({

        'z-index': '1040',

        'display': 'none'

    });

			jQuery('.modal-stack').css({

        'display': 'none'

    });

	});
	$(document).on('click','.search_btn_field',function() {
			prosearchform();
		});
		$(".sort_search_val").keypress(function(e){
			if(e.which == 13) {
				prosearchform();
			}
		});
		function prosearchform(){
			var searchVal = $('.sort_search_val').val();
			$('#searchinputfield').val(searchVal);
			$("#sort_products").submit();
		}
		function sort_products(el){
            $('#sort_products').submit();
        }

</script>

@endsection

