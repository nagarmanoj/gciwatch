@extends('backend.layouts.app')
@php  setlocale(LC_MONETARY,"en_US");  @endphp
@section('content')
<style>
    .amount_cal{
        width:150px;
        height:90px;
        color:#fff;
        text-align:center;
    }
</style>

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class=" align-items-center">
       <h1 class="h3">{{translate(' Supplier Purchase')}}</h1>
	</div>
</div>
<div class="row">
    <div class="col-md-12 mx-auto">
        <div class="dropdown mb-2 mb-md-0 right_drop_mune_sec trans_mi">
            <div class="list_bulk_btn">
    <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
        <i class="las la-bars fs-20"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right">
        <a href="{{Route('supplier_details_excel.index')}}" id="xls" class="tip" title="" style="text-decoration:none;color:#000;" data-original-title="Download as XLS">
            Excel
        </a>
    </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        @php  setlocale(LC_MONETARY,"en_US");  @endphp
        <!-- <center> -->
        <table style=" margin-bottom:20px; width: 100%;">
            <tr>
                <td class="amount_cal" style="background:#428BCA !important;">{{money_format("%(#1n",$totalPurchases)."\n"}}  <br> Purchases Amount</td>
                <td class="amount_cal" style="background:#fa603d ;"> {{$count}} <br> Total Purchases</td>
            </tr>
        </table>
        <!-- </center> -->
        <form class="" id="sort_products" action="" method="GET">
            <div class="card-header row gutters-5 purchases_form_sec">
                <input type="hidden" name="search" id="searchinputfield">
                <select class="form-control form-select" id="pagination_qty" name="pagination_qty" style="display:none">
                    <option value="25" @if($pagination_qty == 25) selected @endif>25</option>
                    <option value="50" @if($pagination_qty == 50) selected @endif>50</option>
                    <option value="100" @if($pagination_qty == 100) selected @endif>100</option>
                    <option  @if($pagination_qty == "all") selected @endif value="all">All</option>
                </select>
                <div class="col-2 d-flex page_form_sec report_agent_show">
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
            <table class="table table-bordered aiz-table mb-0">
                <thead>
                    <tr>
                        <th> # </th>
                        <th>{{ translate('Date ') }}</th>
                        <th>{{ translate('Stock Id') }}</th>
                        <th>{{ translate('Status') }}</th>
                        <th>{{ translate('Model Number') }}</th>
                        <th>{{ translate('Serial') }}</th>
                        <th>{{ translate('Reference') }}</th>
                        <th>{{ translate('Warehouse') }}</th>
                        <th>{{ translate('Supplier') }}</th>
                        <th>{{ translate('Product Cost') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $count=0; @endphp 
                    @foreach($supplier_details as $key  => $row)
                        <tr>
                             
                             <td> {{ ($supplier_details->currentpage()-1) * $supplier_details->perpage() + $key + 1 }}</td>
                            <td>{{date('m/d/y',strtotime($row->created_at))}} </td>
                            <td> {{$row->stock_id}} </td>
                            <td><?php
                            if($row->item_status==1 || $row->item_status=='0')
                            {
                                echo "Memo";
                            }
                            elseif($row->item_status==2)
                            {
                                echo "Invoice";
                            }
                            elseif($row->item_status==3)
                            {
                                echo "Return";
                            }
                            elseif($row->item_status==4)
                            {
                                echo "Trade";
                            }
                            elseif($row->item_status==6)
                            {
                                echo "TradeNGD";
                            }
                            elseif($row->qty >0 && $row->published==1)
                            {
                                echo "Available";
                            }
                                ?> </td>
                            <td> {{$row->model}} </td>
                            <td> {{$row->sku}} </td>
                            <td> {{$row->vendor_doc_number}} </td>
                            <td> {{$row->warehouse_name}} </td>
                            <td> {{$row->supplier_name}} </td>
                            <td>{{money_format("%(#1n",$row->unit_price)."\n"}} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                @if($pagination_qty != "all")
                <p>
                    Showing {{ $supplier_details->firstItem() }} to {{ $supplier_details->lastItem() }} of  {{$supplier_details->total()}} entries
                </p>
                    {{ $supplier_details->appends(request()->input())->links() }}
                    @else
                    <p>
                    Showing {{$supplier_details->count()}} of  {{$supplier_details->count()}} entries
                    </p>
                @endif
            </div>
        </form>
    </div>
</div>
    <!-- </div>
</div> -->
@endsection
@section('script')
<script>
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
@endsection
