@extends('backend.layouts.app')


@section('content')

<style>
@media print{
    .no-print, .hideonprint, .micustomclose, .micustomPrem{
        display:none !important;
    }
    @page :footer{
        display:none !important;
    }
}
</style>

<div class="row hideonprint inventory_mi">
	<!--<div class="aiz-titlebar text-left mt-2 mb-3">-->
	<!--	<div class="align-items-center">-->
	<!--		<h1 class="h3">{{translate('All Inventory Run')}}</h1>-->
	<!--	</div>-->
	<!--</div>-->
	<div class="dropdown mb-2 mb-md-10 inventory_mi_cus">
	    <label class="inv_hedding"><h1 class="h3">{{translate('All Inventory Run')}}</h1></label>
		<button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
			<i class="las la-bars fs-20"></i>
		</button>
		<div class="dropdown-menu dropdown-menu-right">
		<!-- <a href="{{route('inventory_run.create')}}" style="text-decoration:none;color:#000;padding-right:85px;padding-top: 0.5rem;padding-bottom: 0.5rem;">
                <span class="aiz-side-nav-text">{{translate('Add Inventory Run')}}</span>
            </a> -->
			<form class="" action="{{route('inventory_run-export.index')}}" method="post">
				@csrf
				<input type="hidden" name="checked_id" id="checkox_pro_export" value="">
				<button id="product_export" type="submit" class="w-100 exportPro-class" disabled>Export to excel</button>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header row gutters-5">
					<div class="col text-center text-md-left">
						<h5 class="mb-md-0 h6">{{ translate('All Listing') }}</h5>
					</div>
					<div class="btn-group mr-2" role="group" aria-label="Third group">
						<a class="btn btn-soft-primary" href="{{route('inventory_run.create')}}" title="{{ translate('add') }}">
							 Inventory Run	<i class="lar la-plus-square"></i>
						</a>
					</div>
				</div>
				<form class="" id="sort_products" action="" method="GET">
					<div class="card-header row gutters-5 purchases_form_sec">
						<div class="col-md-2 d-flex page_form_secs in_page">
							<label class="fillter_sel_show m-auto"><b>Show</b></label>
							<input type="hidden" name="search" id="searchinputfield">
							<select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0 purchases_filter" name="pagination_qty" id="purchases_pagi" data-live-search="true">
								<option value="25" @if($pagination_qty == 25) selected @endif>{{translate('25')}}</option>
								<option  value="50" @if($pagination_qty == 50) selected @endif>{{translate('50')}}</option>
								<option  value="100" @if($pagination_qty == 100) selected @endif>{{translate('100')}}</option>
								<option  value="200" @if($pagination_qty == 200) selected @endif>{{translate('All')}}</option>
								<!-- <option  value="all"  @if($pagination_qty == "all") selected @endif>{{translate('All')}}</option> -->
							</select>
							<button type="submit" id="purchases_pagi_sub" name="purchases_pagi_sub" class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>
						</div>
						<div class="col-md-10 d-flex search_form_sec in_search">
						    <div class="inv_search">
							<label class="fillter_sel_show m-auto"><b>Search</b></label>
							<input type="text" class="form-control form-control-sm sort_search_val"  @isset($sort_search) value="{{ $sort_search }}"  @endisset>
							<button type="button" class="search_btn_field"><i class="las la-search aiz-side-nav-icon" ></i></button>
							</div>
						</div>
					</div>
			    	<div class="card-body">
						<table class="table aiz-table mb-0" style="text-align: center;">
							<thead>
								<tr>
									<th data-orderable="false">
										<input type="checkbox" class="select_count" id="select_count"  name="all[]">
									</th>
									<th>{{translate('User')}}</th>
									<th>{{translate('Listing Type')}}</th>
									<th style="width:200px;">{{translate('Missing')}}</th>
									<th style="width:200px;">{{translate('Duplicate')}}</th>
									<th style="width:200px;">{{translate('Extra')}}</th>
									<!--<th>{{translate('Extra')}}</th>-->
									<th>{{translate('Date')}}</th>
									<th class="text-right">{{translate('Options')}}</th>
								</tr>
							</thead>
							<tbody>
								@foreach($InventoryRun as $key => $optData)
									<tr class="invTable" data-id="{{ $optData->id}}">
										<td>
											<input type="checkbox" class="pro_checkbox" data-id="{{$optData->id}}" name="all_pro[]" value="{{$optData->id}}">
										</td>
										<td  class="RetData" data-toggle="modal" data-id="{{ $optData->id}}" data-target="#orderRetModal">{{ $optData->name}}</td>
										<td  class="RetData" data-toggle="modal" data-id="{{ $optData->id}}" data-target="#orderRetModal">{{ $optData->listing_type}}</td>
										<td  class="RetData missing_mi_data" data-toggle="modal" data-id="{{ $optData->id}}" data-target="#orderRetModal"><span class="missingtd">{{ $optData->missing}}</span></td>
										<!-- <td  class="RetData missing_mi_data" data-toggle="modal" data-id="{{ $optData->id}}" data-target="#orderRetModal"><span class="missingtd">{{ $optData->missing}}</span></td> -->
										<td  class="RetData" data-toggle="modal" data-id="{{ $optData->id}}" data-target="#orderRetModal"><span class="duplicate_mi">{{ $optData->duplicate}}</span></td>
										<!-- <td  class="RetData" data-toggle="modal" data-id="{{ $optData->id}}" data-target="#orderRetModal"><span class="extra_mi">{{$optData->extra}}</span></td>  -->
										<!--<td  class="RetData" data-toggle="modal" data-id="{{ $optData->id}}" data-target="#orderRetModal">gbfdsh</td>-->
										<td  class="RetData" data-toggle="modal" data-id="{{ $optData->id}}" data-target="#orderRetModal" id="extra_mi">{{$optData->extra}} @if(!empty($optData->extra)) , @endif{{$optData->extrakeyup}}</td>
										<td  class="RetData" data-toggle="modal" data-id="{{ $optData->id}}" data-target="#orderRetModal">{{ date('m/d/Y', strtotime($optData->created_at)) }}</td>
										<!-- <td>{{ date('j F, Y', strtotime($optData->created_at)) }}</td> -->
										<td class="text-center">
											<a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('inventory_run.destroy',['id'=>$optData->id] )}}" title="{{ translate('Delete') }}">
													<i class="las la-trash"></i>
											</a>
											<!-- <a href="#" class="btn btn-soft-success btn-icon btn-circle btn-sm confirm-delete" data-href="#" title="{{ translate('Delete') }}">
													<i class="las la-activity"></i>
											</a> -->
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
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- model  -->
<div class="modal fade orderRetModal" id="orderRetModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog increasewid">
    <div class="modal-content" id="micustomReturn">
		<div class="modal-header">
			<img src="https://gcijewel.com/public/uploads/all/O2yCFLQfu0nitWXPCfza2pYto0xjo8kqGbfusjON.png" class="img-fluid w-25 logo-rem">
	        <!-- <h5 class="modal-title">Return Items</h5> -->
			<div class="close-btn">
				<button class="btn btn-xs btn-default no-print pull-right" onclick="window.print()" style="border:1px solid gainsboro !important;"> <i class="las la-print"></i> Print</button>
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
	// var proexpData =JSON.stringify(proCheckID);
	// $('#checkox_pro').val(proexpData);
	if(proCheckID.length > 0)
	{
		$('#product_export').removeAttr('disabled');
	}
	else
	{
		$('#product_export').attr('disabled',true);
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
}


$('.RetData').on('click', function(e) {
	e.preventDefault();
	var id= $('.data_id').val();
	var getIdFromRow = $(event.target).closest('tr').data('id');
	// alert(getIdFromRow);
	$.ajax({
		url: "{{route('inventory_run.return')}}",
		type: "POST",
		data: {"_token": "{{ csrf_token() }}","id" : getIdFromRow},
		success: function( response ) {
			// alert(response);
			var ReturnHtml = response.returnHtmlData;
			$('#reModelData').html(ReturnHtml);
		}
	});
});
$(document).on('click','.returnDataAnch',function(){
	var returnHref = $(this).attr('href');
	window.location.href = returnHref;
});
function micustomPrint(){

	var originalContents = document.body.innerHTML;
	document.body.innerHTML = originalContents;
	window.print();
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
