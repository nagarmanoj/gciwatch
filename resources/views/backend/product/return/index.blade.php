@extends('backend.layouts.app')
@section('content')
<div class="row hideonprint">
	<div class="col-md-12">
		<div class="card">
		    <div class="card-header row gutters-5">
				<div class="col text-center text-md-left">
					<h5 class="mb-md-0 h6">{{ translate('All Return') }}</h5>
				</div>
				<div class="dropdown mb-2 mb-md-0">
            		<button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
            			<i class="las la-bars fs-20"></i>
            		</button>
            		<div class="dropdown-menu dropdown-menu-right">
					<!-- <a href="{{route('return.create')}}">Add new</a> -->
					@if(Auth::user()->user_type == 'admin' || in_array('60', json_decode(Auth::user()->staff->role->inner_permissions)))
                	<form class="delete_section" action="{{route('return.delete_section')}}" method="post">
						@csrf
						<input type="hidden" name="checked_id" id="checkox_pro" value="">
						<button id="product_delete" type="submit" style="border:none;padding-right:163px;padding-top: 0.5rem;padding-bottom: 0.5rem;background:#fff;" class="w-100 exportPro-class" disabled>Delete</button>
                	</form>
					@endif
					<form class="" action="{{route('return-export.index')}}" method="post">
						@csrf
						<input type="hidden" name="checked_id" id="checkox_pro_export" value="">
						<button id="product_export" type="submit" class="w-100 exportPro-class" disabled>Bulk export</button>
                	</form>
            	</div>
        	</div>
			<!-- <div class="col-md-4">
				<form class="" id="sort_brands" action="" method="GET">
					<div class="input-group input-group-sm">
						<input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name & Enter') }}">
					</div>
				</form>
			</div> -->
			<div class="btn-group mr-2" role="group" aria-label="Third group">
				<!-- <a class="btn btn-soft-primary" href="{{route('metal.create')}}" title="{{ translate('add') }}">
					Add Metals	<i class="lar la-plus-square"></i>
				</a> -->
			</div>
		</div>
		<div class="card-header row gutters-5">
		<div class="col text-center text-md-left">
			<!-- <h5 class="mb-md-0 h6">{{ translate('All Return') }}</h5> -->
		</div>
		@if(Auth::user()->user_type == 'admin' || in_array('58', json_decode(Auth::user()->staff->role->inner_permissions)))
        <div class="btn-group mr-2" role="group" aria-label="Third group">
			<a class="btn btn-soft-primary" href="{{route('return.create')}}" title="{{ translate('add') }}">
			Add Return<i class="lar la-plus-square"></i>
			</a>
        </div>
		@endif
	</div>
	<form class="" id="sort_products" action="" method="GET">
		<div class="card-header row gutters-5 purchases_form_sec">
			<div class="col-md-3 d-flex page_form_secs">
				<label class="fillter_sel_show m-auto"><b>Show</b></label>
				<input type="hidden" name="search" id="searchinputfield">
				<select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0 purchases_filter" name="purchases_pagi" id="purchases_pagi" data-live-search="true">
					<option  @if($pagination_qty == 25) selected @endif>{{translate('25')}}</option>
					<option  @if($pagination_qty == 50) selected @endif>{{translate('50')}}</option>
					<option  @if($pagination_qty == 100) selected @endif>{{translate('100')}}</option>
					<option  @if($pagination_qty == 200) selected @endif>{{translate('200')}}</option>
				</select>
				<button type="submit" id="purchases_pagi_sub" name="purchases_pagi_sub" class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>
			</div>
			<div class="col-md-3 d-flex search_form_sec">
				<label class="fillter_sel_show m-auto"><b>Search</b></label>
				<input type="text" class="form-control form-control-sm sort_search_val"  @isset($sort_search) value="{{ $sort_search }}"  @endisset>
				<button type="button" class="search_btn_field"><i class="las la-search aiz-side-nav-icon" ></i></button>
			</div>
		</div>
		<div class="card-body">
			<table class="table aiz-table mb-0" >
				<thead>
					<tr>
						<th data-orderable="false">
                            <input type="checkbox" class="select_count" id="select_count"  name="all[]">
                        </th>
						<th>{{translate('Date')}}</th>
						<!-- <th>{{translate('Comapny Name')}}</th> -->
						<th>{{translate('Reference')}}</th>
						<th>{{translate('Supplier')}}</th>
						<th>{{translate('Model Number')}}</th>
						<th>{{translate('Stock Id')}}</th>
						<th class="text-right">{{translate('Options')}}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($returnData as $key => $optData)
						<tr data-id="{{ $optData->ret_id}}">
							<td>
								<input type="checkbox" class="pro_checkbox" data-id="{{$optData->id}}" name="all_pro[]" value="{{$optData->ret_id}}">
							</td>
	               			<td  class="RetData" data-toggle="modal" data-id="{{ $optData->ret_id}}" data-target="#orderRetModal">{{ date('m/d/20y',strtotime($optData->return_date))}}</td>
							<!-- <td  data-toggle="modal" data-id="{{ $optData->ret_id}}" data-target="#orderRetModal">compny</td> -->
							<td class="RetData" data-toggle="modal" data-id="{{ $optData->ret_id}}" data-target="#orderRetModal">{{$optData->reference_no }}</td>
							<td class="RetData" data-toggle="modal" data-id="{{ $optData->ret_id}}" data-target="#orderRetModal">{{$optData->supplier_name }}</td>
							<td class="RetData" data-toggle="modal" data-id="{{ $optData->ret_id}}" data-target="#orderRetModal">{{ $optData->model}}</td>
							<td  class="RetData" data-toggle="modal" data-id="{{ $optData->ret_id}}" data-target="#orderRetModal">{{ $optData->stock_id}}</td>
							<td class="text-right">
							@if(Auth::user()->user_type == 'admin' || in_array('59', json_decode(Auth::user()->staff->role->inner_permissions)))
								<a class="btn btn-soft-primary btn-icon btn-circle btn-sm returnDataAnch" href="{{ route('return.edit',['id'=>$optData->ret_id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
									<i class="las la-edit"></i>
								</a>
								@endif
								@if(Auth::user()->user_type == 'admin' || in_array('60', json_decode(Auth::user()->staff->role->inner_permissions)))
								<a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('proreturn.destroy',['id'=>$optData->ret_id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Delete') }}">
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
		</div>
	</form>
</div>
</div>
</div>
<!-- model  -->
<div class="modal fade" id="orderRetModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog increasewid">
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
<!-- unit Ajax start -->
<script>
$('.RetData').on('click', function(e) {
 e.preventDefault();
	var getIdFromRow = $(event.target).closest('tr').data('id');
  $.ajax({
      url: "{{route('pro.return')}}",
      type: "POST",
      data: {"_token": "{{ csrf_token() }}","id" : getIdFromRow},
      success: function( response ) {
        var ReturnHtml = response.returnHtmlData;
				// console.log(ReturnHtml);
        $('#reModelData').html(ReturnHtml);
      }
     });
   });
	   $(document).on('click','.returnDataAnch',function(){
	     var returnHref = $(this).attr('href');
			 window.location.href = returnHref;
	   });
</script>
<script type="text/javascript">
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
	// function micustomPrint()
  // {
  //     $('.aiz-main-content').hide();
  //     var mywindow = window.open();
  //     var content = document.getElementById('orderRetModal').innerHTML;
  //     var realContent = document.body.innerHTML;
  //     mywindow.document.write(content);
  //     mywindow.document.close(); // necessary for IE >= 10
  //     mywindow.focus(); // necessary for IE >= 10*/
  //     mywindow.print();
  //     document.body.innerHTML = realContent;
  //     mywindow.close();
  //         $('.aiz-main-content').show();
  //     return true;
  // }
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
$(document).ready(function() {
	$(document).on('click','.pro_checkbox',function(){
		productCheckbox();
		productCheckboxExport();
	});
});
function productCheckbox(){
	var proCheckID = [];
	$.each($("input[name='all_pro[]']:checked"), function()
	{
		proCheckID.push($(this).val());
	});
	console.log(proCheckID);
	var proexpData =	JSON.stringify(proCheckID);
	$('#checkox_pro').val(proexpData);
	if(proCheckID.length > 0){
		$('#product_export').removeAttr('disabled');
	}else{
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
function productCheckboxExport()
{
	var proCheckID = [];
	$.each($("input[name='all_pro[]']:checked"), function()
	{
		proCheckID.push($(this).val());
	});
	var proexpData =JSON.stringify(proCheckID);
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
$(document).on('click','.select_count',function() {
	if($(this).is(':checked')){
			$('.pro_checkbox').prop('checked', true);
		}else{
			$('.pro_checkbox').prop('checked', false);
		}
		productCheckbox();
		productCheckboxExport();
});
$(document).on('click','.default-filter-check', function(){
	if ($(this).prop('checked')==true)
	{
		var FilterDefaultVal = $('.default-filter-check-value').val();
	if(FilterDefaultVal != '')
	{
		var FilterDefaultValData =  JSON.parse(FilterDefaultVal);
		$('.filtered_field').prop('checked',false);
		console.log(FilterDefaultValData);
		$.each( FilterDefaultValData, function( keyFilter, valFilter )
		{
			$('#filteredColOpt .'+keyFilter).prop('checked',true);
			console.log(valFilter);
		});
	}
	}
	else
	{
		$('.filtered_field').prop('checked',false);
	}
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
