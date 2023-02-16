<style>
	#job_order_delete:hover, #job_order_export:hover
	{
        background-color: #428bca !important;
        color: #fff !important;
	}
	#job_order_delete, #job_order_export
	{
        text-align: left;
        padding-left: 20px;
        padding-top: 10px;
        padding-bottom: 10px;
	}
</style>
@extends('backend.layouts.app')
@section('content')
	<div class="aiz-titlebar text-left mt-2 mb-3">
		<div class="align-items-center">
			<h1 class="h3">{{translate('All Purchases')}}</h1>
		</div>
	</div>
	<div class="row ouder_report_agent">
    	<div class="col-md-12">
    		<form class="" id="sort_products" action="" method="GET">
        		<div class="dropdown mb-2 mb-md-0 right_drop_mune_sec trans_mi">
            		<div class="date_year_box" style="margin-left: 0 !important; max-width:28% !important;">
						<input type="hidden" name="startrangedate" class="startrangedate" value="">
						<input type="hidden" name="endrangedate" class="endrangedate" value="">
						<div id="reportrange" style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc; width: 100%">
							<i class="las la-calendar"></i>
							<i class="fa fa-calendar"></i>&nbsp;
							<span></span> <i class="fa fa-caret-down"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<i class="las la-angle-down"></i>
                		</div>
						<!-- <input type="submit" value="search" name="btn"> -->
						<button type="submit" id="warehouse_type" name="warehouse_type" class="d-none calendar_submit"><i class="las la-search aiz-side-nav-icon" ></i></button>
            		</div>
					<div class="list_bulk_btn">
						<button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
							<i class="las la-bars fs-20"></i>
						</button>
						<div class="dropdown-menu dropdown-menu-right">
							<a href="{{Route('purchases.export')}}" id="xls" class="tip" title="" style="text-decoration:none;" data-original-title="Download as XLS">
								Excel
							</a>
						</div>
					</div>
				</div>
					<!-- <div class="dropdown mb-2 mb-md-0 right_drop_mune_sec">
						<button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
							<i class="las la-bars fs-20"></i>
						</button>
						<div class="dropdown-menu dropdown-menu-right">
							<form class="" action="{{route('purchases.export')}}" method="post">
								@csrf
								<input type="hidden" name="checked_id" id="checkox_pro_export" value="">
								<button id="product_export" type="submit" class="w-100 exportPro-class" disabled>Bulk export</button>
							</form>
						</div>
					</div> -->`
				<div class="card">
				<!-- <form class="" id="sort_products" action="" method="GET"> -->
				<div class="card-header row gutters-5">
					<div class="col text-center text-md-left">
						<h5 class="mb-md-0 h6">{{ translate('All Purchases') }}</h5>
					</div>
					<!-- <div class="col-md-2 ml-auto d-flex  style="margin-left: 0 !important; max-width:28% !important;">
						<input type="hidden" name="startrangedate" class="startrangedate" value="">
						<input type="hidden" name="endrangedate" class="endrangedate" value="">
						<div id="reportrange" style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc; width: 100%">
							<i class="las la-calendar"></i>
							<i class="fa fa-calendar"></i>&nbsp;
							<span></span> <i class="fa fa-caret-down"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<i class="las la-angle-down"></i>
						</div>
						<button type="submit" id="warehouse_type" name="warehouse_type" class="d-none calendar_submit"><i class="las la-search aiz-side-nav-icon" ></i></button>
						 <input type="submit" class="calendar_submit" value="search" name="btn">
					</div> -->
					<div class="col-md-2 ml-auto d-flex">
						<select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0 purchases_filter" name="warehouse_id" id="warehouse_id" data-live-search="true">
							<option value="">All Warehouse</option>
							@foreach (App\Models\Warehouse::all() as $whouse_filter)
								<option value="{{$whouse_filter->id}}" @if($whouse_filter->id == Request::get('warehouse_id')) selected @endif>{{ $whouse_filter->name }}</option>
							@endforeach;
						</select>
						<button type="submit" id="warehouse_type" name="warehouse_type" class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>
					</div>
					<div class="col-md-2 ml-auto d-flex">
						<select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0 purchases_filter" name="supplier_id" id="supplier_id" data-live-search="true">
						<option value="">All Supplier</option>
						@foreach (App\User::all() as $supplier_filter)
							<option value="{{$supplier_filter->id}}" @if($supplier_filter->id == Request::get('supplier_id')) selected @endif>{{ $supplier_filter->name }}</option>
						@endforeach;
						</select>
						<button type="submit" id="supplier_type" name="supplier_type" class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>
					</div>
					<div class="col-md-2 ml-auto d-flex">
						<select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0 purchases_filter" name="reference_id" id="reference_id" data-live-search="true">

						<option value="">All Reference  </option>

						@foreach (App\Product::all() as $reference_filter)

							<option value="{{$reference_filter->id}}" @if($reference_filter->id == Request::get('reference_id')) selected @endif>{{ $reference_filter->vendor_doc_number }}</option>

						@endforeach;

						</select>
						<button type="submit" id="reference_type" name="reference_type" class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>
					</div>
            		<div class="col-md-2 ml-auto d-flex">

						<select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0 purchases_filter" name="producttypee" id="product_type" data-live-search="true">

							<option value="">All Listing Type</option>

							@foreach (App\SiteOptions::where('option_name', '=', 'listingtype')->get() as $p_type_filter)

							<option value="{{$p_type_filter->option_value}}" @if($p_type_filter->option_value == Request::get('producttypee')) selected @endif>{{ $p_type_filter->option_value }}</option>

						@endforeach;

						</select>

						<button type="submit" id="pro_type" name="pro_type" class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>

            		</div>
		        </div>
				<div class="card-header row gutters-5 purchases_form_sec">
					<input type="hidden" name="search" id="searchinputfield">
					<select class="form-control form-select" id="pagination_qty" name="pagination_qty" style="display:none">
						<option value="25" @if($pagination_qty == 25) selected @endif>25</option>
						<option value="50" @if($pagination_qty == 50) selected @endif>50</option>
						<option value="100" @if($pagination_qty == 100) selected @endif>100</option>
						<option  @if($pagination_qty == "all") selected @endif value="all">All</option>
					</select>
					<div class="col-2 d-flex page_form_sec">
						<label class="fillter_sel_show"><b>Show</b></label>
						<select class="form-control form-select" id="pagination_use_qty"  aria-label="Default select example">
							<option value="25" @if($pagination_qty == 25) selected @endif>25</option>
							<option value="50" @if($pagination_qty == 50) selected @endif>50</option>
							<option value="100" @if($pagination_qty == 100) selected @endif>100</option>
							<option value="all" @if($pagination_qty == "all") selected @endif>All</option>
						</select>
					</div>
					<div class="col-6 d-flex search_form_sec">
						<label class="fillter_sel_show m-auto"><b>Search</b></label>
						<input type="text" class="form-control form-control-sm sort_search_val"  @isset($sort_search) value="{{ $sort_search }}" @endisset>
							<button type="button" class="search_btn_field"><i class="las la-search aiz-side-nav-icon" ></i></button>
					</div>
				</div>
				<div class="card-body" style="padding-top:5px;">
					<table class="table aiz-table mb-0">

						<thead>

							<tr>

								<th> <input type="checkbox" class="select_count" id="select_count"  name="all[]"></th>

								<!--

								<th>{{translate('Product Id')}}</th> -->

								<th>{{translate('Listing Type')}}</th>

								<th>{{translate('Stock Id')}}</th>

								<th>{{translate('Brand')}}</th>

								<th>{{translate('Condition')}}</th>

								<th>{{translate('Model Number')}}</th>

								<th>{{translate('Serial No.')}}</th>

								<th>{{translate('Weight')}}</th>

								<th>{{translate('Paper/Cert')}}</th>

								<th>{{translate('Reference No')}}</th>

								<th>{{translate('Supplier')}}</th>

								<th>{{translate('DOP')}}</th>

								<th>{{translate('Cost code')}}</th>

								<th>{{translate('Warehouse')}}</th>

								<th> {{translate('Status')}}</th>

								<!-- <th>{{translate('Quantity')}}</th>

								<th>{{translate('MSRP')}}</th>

								<th>{{translate('Sale Price')}}</th>

								<th>{{translate('Metal')}}</th>

								<th>{{translate('Size')}}</th> -->

								<th class="text-right">{{translate('Options')}}</th>

							</tr>

						</thead>

						@php

							setlocale(LC_MONETARY,"en_US");

						@endphp

						<tbody>

							@foreach($PurchasesData as $key => $optData)

								<tr>
										
                                 <td>{{ ($PurchasesData->currentpage()-1) * $PurchasesData->perpage() + $key + 1 }}</td>
									<td><input type="checkbox" class="pro_checkbox" data-id="{{$optData->id}}" name="all_pro[]" value="{{$optData->id}}"></td>

									<td><a href="{{route('products.admin.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{ $optData->listing_type}}</td>

									<td><a href="{{route('products.admin.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{ $optData->stock_id}}</td>

									<td><a href="{{route('products.admin.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{ $optData->brandName}}</td>

									<td><a href="{{route('products.admin.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{ $optData->productconditions_name}}</td>

									<td><a href="{{route('products.admin.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{ $optData->model}}</td>

									<td><a href="{{route('products.admin.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{ $optData->sku}}</td>



									<td><a href="{{route('products.admin.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{ $optData->weight}}</td>

									<td><a href="{{route('products.admin.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{ $optData->paper_cart}}</td>

									<td><a href="{{route('products.admin.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{ $optData->vendor_doc_number}}</td>

									<td><a href="{{route('products.admin.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{ $optData->supplienName}}</td>

									<td><a href="{{route('products.admin.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{ $optData->dop}}</td>



									<td><a href="{{route('products.admin.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{ $optData->cost_code}}</td>

									<td><a href="{{route('products.admin.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{ $optData->warehouseName}}</td>



									<td><a href="{{route('products.admin.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">

									@php
									if($optData->published=='1' && $optData->qty )
										{
											echo "Available";
										}

										elseif($optData->item_status==1)
										{
											echo "Memo";
										}
										elseif($optData->item_status=='0')
										{
											echo "Memo";
										}
										elseif($optData->item_status==2)
										{
											echo "INVOICE";
										}
										elseif($optData->item_status==3)
										{
										echo "RETURN";
										}
										elseif($optData->item_status==4)
										{
											echo "TRADE";
										}
										elseif($optData->item_status==5)
										{
											echo "VOID";
										}
										elseif($optData->item_status==6)
										{
											echo "TRADE NGD";
										}
									@endphp
								</td>
									<!-- <td><a href="{{route('products.admin.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{money_format("%(#1n",$optData->msrp)."\n" }}</td>

									<td><a href="{{route('products.admin.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{money_format("%(#1n",$optData->unit_price)."\n" }}</td>

									<td><a href="{{route('products.admin.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{ $optData->metal}}</td>

									<td><a href="{{route('products.admin.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{ $optData->size}}</td> -->

									<td class="text-right">

									<a class="btn btn-soft-success btn-icon btn-circle btn-sm"  href="{{ route('products.viewproduct',['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" target="_blank" title="{{ translate('View') }}">

								<i class="las la-eye"></i>

									</a>

									<a class="btn btn-soft-success btn-icon btn-circle btn-sm"  href="{{ route('purchases.purchasespdf',['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" target="_blank" title="{{ translate('View') }}">

									<i class="las la-file-pdf"></i>

									</a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					<div class="aiz-pagination">
					@if($pagination_qty != "all")
					<p>
						Showing {{ $PurchasesData->firstItem() }} to {{ $PurchasesData->lastItem() }} of  {{$PurchasesData->total()}} entries
					</p>
						{{ $PurchasesData->appends(request()->input())->links() }}
						@else
						<p>
						Showing {{$PurchasesData->count()}} of  {{$PurchasesData->count()}} entries
						</p>
					@endif
				</div>
			</form>
		</div>
	</div>
	<!-- </div>
	</div> -->
<!-- Modal -->
 <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">

            <div class="modal-content">

            <form id="filteredColOpt">

              <div class="modal-header">

                <h5 class="modal-title" id="exampleModalLabel">Choose Options</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                  <span aria-hidden="true">&times;</span>

                </button>

              </div>

              <div class="modal-body">



              <input type="hidden" name="menu" value="product">



              <div class="modal-footer">

                <input type="checkbox" class="default-filter-check" name="default_option" value="1"><span>Default Select</span>

                <input type="hidden" class="default-filter-check-value" name="menu_default" value=''>

                <button type="submit" class="btn btn-primary">Save</button>

              </div>

              </form>

            </div>

          </div>

        </div>



		<!-- end edit column -->

@endsection

@section('modal')

    @include('modals.delete_modal')

@endsection

@section('script')

<script>


	    $(document).on("change", ".check-all", function() {

            if(this.checked) {

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

		}
		// $('#product_export').on('click',function(){

		// })

		function productCheckboxExport(){

			var proCheckID = [];

			$.each($("input[name='all_pro[]']:checked"), function(){

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

		}

		$(document).on('click','.select_count',function() {

			if($(this).is(':checked'))

			{

				$('.pro_checkbox').prop('checked', true);

			0}

			else

			{

				$('.pro_checkbox').prop('checked', false);

			}

			productCheckbox();

			productCheckboxExport();

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
        $("#pagination_use_qty").change(function(){
            var pageQty = $(this).val();
            $('#pagination_qty').val(pageQty);
            $("#sort_products").submit();
        });

</script>
<script type="text/javascript">

$(function() {

    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('MM / DD / YYYY') + ' - ' + end.format('MM / DD / YYYY'));
        $('.startrangedate').val(start.format('MM / DD / YYYY'));
        $('.endrangedate').val(end.format('MM / DD / YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);
});

$('#reportrange').on('apply.daterangepicker', function(ev, picker) {
		// $(".calendar_submit").click();
		$('.calendar_submit').trigger('click');
});

</script>

@endsection
