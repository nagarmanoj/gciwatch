@extends('backend.layouts.app')
@section('content')
<div class="dropdown mb-2 mb-md-0 hideonprint trans_mi">
    <div class="list_bulk_btn">
	<button class="btn border dropdown-toggle trans_btn" type="button" data-toggle="dropdown">
		<i class="las la-bars fs-20"></i>
	</button>
	<div class="dropdown-menu dropdown-menu-right">
		<form class="delete_section" action="{{route('bulk-user-delete')}}" method="post">
			@csrf
			<input type="hidden" name="checked_id" id="checkox_pro" value="">
			<button id="product_delete" type="submit" style="border:none;padding-right:163px;padding-top: 0.5rem;padding-bottom: 0.5rem;background:#fff;"  onclick="return confirm('Are you sure?');" class="w-100 exportPro-class" disabled>Delete</button>
		</form>
		<form class="" action="{{route('users_export.index')}}" method="post">
			@csrf
			<input type="hidden" name="checked_id" id="checkox_pro_export" value="">
			<button id="product_export" type="submit" class="w-100 exportPro-class" disabled>Export to excel</button>
		</form>
	</div>
	</div>
</div>
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('All Users')}}</h1>
		</div>
		<div class="col-md-4 text-md-right">
			<!-- <a href="{{ route('staffs.create') }}" class="btn btn-circle btn-info">
				<span>{{translate('Add New Users')}}</span>
			</a> -->
		</div>
        <div class="btn-group col-md-2 text-md-right" role="group" aria-label="Third group">
			<a class="btn btn-soft-primary" href="{{route('staffs.create')}}" title="{{ translate('add') }}">
			Add Users<i class="lar la-plus-square"></i>
			</a>
        </div>
	</div>
</div>
<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Users')}}</h5>
    </div>
    <form class="" id="sort_products" action="" method="GET">
    <div class="card-header row gutters-5 purchases_form_sec">
			<div class="col-md-2 d-flex page_form_secs">
			    <label class="fillter_sel_show m-auto"><b>Show</b></label>
				<input type="hidden" name="search" id="searchinputfield">
                <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0 purchases_filter" name="purchases_pagi" id="purchases_pagi" data-live-search="true">

                    <!-- <option value="">Show</option> -->

                    <!-- <option  @if($pagination_qty == 10) selected @endif>{{translate('10')}}</option> -->

                    <option  @if($pagination_qty == 25) selected @endif>{{translate('25')}}</option>

                    <option  @if($pagination_qty == 50) selected @endif>{{translate('50')}}</option>

                    <option  @if($pagination_qty == 100) selected @endif>{{translate('100')}}</option>

                    <option  @if($pagination_qty == 200) selected @endif>{{translate('200')}}</option>

                </select>

                <button type="submit" id="purchases_pagi_sub" name="purchases_pagi_sub" class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>

            </div>
			<div class="col-6 d-flex search_form_sec">
				<label class="fillter_sel_show m-auto"><b>Search</b></label>
				<input type="text" class="form-control form-control-sm sort_search_val"  @isset($sort_search) value="{{ $sort_search }}" @endisset>
 				<button type="button" class="search_btn_field"><i class="las la-search aiz-side-nav-icon" ></i></button>
			</div>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th> <input type="checkbox" class="select_count" id="select_count"  name="all[]"></th>
                        <th>{{translate('Name')}}</th>
                        <th>{{translate('Email Address')}}</th>
                        <th>{{translate('Company')}}</th>
                        <th>{{translate('Group')}}</th>
                        <th>{{translate('Status')}}</th>
                        <th>{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staffs as $key => $staff)
                        @if($staff->user != null)
                            <tr>
                                <td style="text-align:center;"><input type="checkbox" class="pro_checkbox" data-id="{{$staff->s_id}}" name="all_pro[]" value="{{$staff->s_id}}"></td>
                                <td>{{$staff->user->name}}</td>
                                <td>{{$staff->user->email}}</td>
                                <td>{{$staff->user->company}}</td>
                                <td>
                                    @if ($staff->role != null)
                                        {{ $staff->role->getTranslation('name') }}
                                    @endif
							    </td>
                                <td>
                                    <center>
                                        @if ($staff->user->status=='1')
                                            <button class="btn btn-success mi_active_btn" style="" onclick="status('{{$staff->user->id}}')"> <i class="las la-check"></i> Active </button>
                                        @else
                                            <button class="btn btn-danger" style="width:95px;" onclick="status('{{$staff->user->id}}')">Deactive</button>
                                        @endif
                                    </center>
							    </td>
                                <td class="text-right">
		                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('staffs.edit', encrypt($staff->s_id))}}" title="{{ translate('Edit') }}">
		                                <i class="las la-edit"></i>
		                            </a>
                                    <!-- <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('staffs.activity', encrypt($staff->s_id))}}" title="{{ translate('activity') }}">
                                    <i class="las la-history"></i>
		                            </a> -->
		                            <!-- <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('staffs.destroy', $staff->s_id)}}" title="{{ translate('Delete') }}">
		                                <i class="las la-trash"></i>
		                            </a> -->
		                        </td>
                            </tr>
                        @endif
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
@endsection
@section('modal')
    @include('modals.delete_modal')
@endsection
@section('script')
<script>
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
    url: "{{route('bulk-user-delete')}}",
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

        function status(id)
        {
            // alert(id);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                type:'post',
                url:"{{route('status.index')}}",
                data:{'id':id},
                success:function(response)
                {
                    // alert(response);
                }
            })
        }
</script>
@endsection