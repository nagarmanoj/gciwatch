@extends('backend.layouts.app')
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
			<!-- <h1 class="h3">{{translate('All Invoice')}}</h1> -->
	</div>
</div>
<div class="dropdown mb-2 mb-md-0 hideonprint trans_mi">
    <div class="list_bulk_btn">
	<button class="btn border dropdown-toggle trans_btn" type="button" data-toggle="dropdown">
		<i class="las la-bars fs-20"></i>
	</button>
	<div class="dropdown-menu dropdown-menu-right">
		<!-- <form class="delete_section" action="{{route('bulk-transfers-delete')}}" method="post">
			@csrf
			<input type="hidden" name="checked_id" id="checkox_pro" value="">
			<button id="product_delete" type="submit" style="border:none;padding-right:163px;padding-top: 0.5rem;padding-bottom: 0.5rem;background:#fff;" class="w-100 exportPro-class" disabled>Delete</button>
		</form> -->
		<form class="" action="{{route('memo_invoice_export.index')}}" method="post">
			@csrf
			<input type="hidden" name="checked_id" id="checkox_pro_export" value="">
			<button id="product_export" type="submit" class="w-100 exportPro-class" disabled>Export to excel</button>
		</form>
	</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
		    <div class="card-header row gutters-5">
				<div class="col text-center text-md-left">
					<h5 class="mb-md-0 h6">{{ translate('All Invoice') }}</h5>
				</div>
		    </div>
		    <div class="mi_custome_table">
		        <form method="get" id="pagination_form" action="{{route('memostatus.index')}}">
                    <div class="row page_qty_sec product_search_header">
                        <div class="col-2 d-flex page_form_sec">
                            <label class="fillter_sel_show">Show</label>
                                <select class="form-control form-select" id="pagination_use_qty"  aria-label="Default select example" name="pagination_qty">
                                <!-- <option value="10" @if($pagination_qty == 10) selected @endif>10</option> -->
                                <option value="25" @if($pagination_qty == 25) selected @endif>25</option>
                                <option value="50" @if($pagination_qty == 50) selected @endif>50</option>
                                <option value="100" @if($pagination_qty == 100) selected @endif>100</option>
                                <option value="200" @if($pagination_qty == 200) selected @endif>All</option>
                                </select>
						</div>
                         <div class="col-6 d-flex search_form_sec">
                            <label class="fillter_sel_show m-auto"><b>Search</b></label>
                            <input type="text" name="search" class="form-control form-control-sm sort_search_val"  @isset($sort_search) value="{{ $sort_search }}" @endisset>
                             <button type="button" class="search_btn_field"><i class="las la-search aiz-side-nav-icon" ></i></button>
                        </div>
                    </div>
                </form>
		    <div class="card-body">
		        <table class="table aiz-table mb-0" id="invoce_datatable">
		            <thead>
		                <tr>
		                    <th> <input type="checkbox" class="select_count" id="select_count"  name="all[]"></th>
		                    <th>{{translate('Memo Number')}}</th>
		                    <th>{{translate('Customer Name')}}</th>
		                    <th>{{translate('Stock Id')}}</th>
		                    <th>{{translate('Model Number')}}</th>
		                    <th>{{translate('Serial')}}</th>
		                    <th>{{translate('Sub Total')}}</th>
		                    <th>{{translate('Date')}}</th>
		                    <th class="text-right">{{translate('Options')}}</th>
		                </tr>
		            </thead>
		            <tbody>
		                @foreach($memoInvoiceData  as $key => $invoice)
				          <tr>
	                          <td style="text-align:center;"><input type="checkbox" class="pro_checkbox" data-id="{{$invoice->memodetailsid}}" name="all_pro[]" value="{{$invoice->memodetailsid}}"></td>
		                        <td>SALE101{{ $invoice->memoid}}</td>

								@if($invoice->customer_group=='reseller')
		                        <td>{{ $invoice->company}}</td>
								@else
								<td>{{ $invoice->customer_name}}</td>
								@endif
                                <td>{{ $invoice->stocks}}</td>
		                        <td>{{ $invoice->model_numbers}}</td>
		                        <td>{{ $invoice->sku}}</td>
								<td>{{ $invoice->order_total}}</td>
		                        <td>{{ date('m/d/20y' ,strtotime($invoice->status_updated_date))}}</td>
		                        <td class="text-right">
									<a href="{{route('memo.generatepdf',['id'=>$invoice->memoid,'status'=>$invoice->item_status])}}" class="btn btn-soft-success btn-icon btn-circle btn-sm" title="{{ translate('View') }}">
											<i class="las la-eye"></i>
									</a>
									<a href="{{route('memo.activitiinvoice',['id'=>$invoice->memoid,'status'=>$invoice->item_status])}}" class="btn btn-soft-success btn-icon btn-circle btn-sm" data-href="#" title="{{ translate('Activite') }}">
										<i class="las la-history"></i>
									</a>
		                        </td>
		                    </tr>
		                @endforeach
		            </tbody>
		        </table>
						<p>
              Showing {{ $pagination->firstItem() }} to {{ $pagination->lastItem() }} of total {{$pagination->total()}} entries
            </p>
		        <div class="aiz-pagination">
                    {{ $pagination->appends(request()->input())->links() }}
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
	if(this.checked)
	{
		$('.check-one:checkbox').each(function() {
			this.checked = true;
		});
	}
	else
	{
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

	var proexpData =JSON.stringify(proCheckID);

	// $('#checkox_pro').val(proexpData);

	if(proCheckID.length > 0)

	{

		$('#product_export').removeAttr('disabled');

	}

	else

	{

		$('#product_export').attr('disabled',true);

	}

	// if(proCheckID.length > 0)

	// {

	// 	$('#product_delete').removeAttr('disabled');

	// 	$('#product_delete').addClass('hoverProBtn');

	// }

	// else

	// {

	// 	$('#product_delete').attr('disabled',true);

	// 	$('#product_delete').removeClass('hoverProBtn');

	// }

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






		 $(document).on('click','.search_btn_field',function() {
            prosearchform();
        });
        $(".sort_search_val").keypress(function(e){
            if(e.which == 13) {
               prosearchform();
            }
        });
		$("#pagination_use_qty").change(function(){
            prosearchform();
        });

	function prosearchform(){
	    $("#pagination_form").submit();
	}


</script>



@endsection
