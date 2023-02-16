@extends('backend.layouts.app')
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
       <h1 class="h3">{{translate('Customers Report')}}</h1>
	</div>
</div>

<!-- <div class="box-header">
    <h2 class="blue"><i class="fa-fw fa fa-barcode"></i>Customers Report
    </h2>
    <div class="box-icon">
        <ul class="btn-tasks">
            <li class="dropdown">
                <a href="{{Route('customer_report_excel.index')}}" id="xls" class="tip" title="" data-original-title="Download as XLS">
                   Excel
                </a>
            </li>
        </ul>
    </div>
</div> -->
<div class="row">
    <div class="col-md-12 mx-auto">
        <div class="dropdown mb-2 mb-md-0 right_drop_mune_sec trans_mi">
            <div class="list_bulk_btn">
    <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
        <i class="las la-bars fs-20"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right">
        <a href="{{Route('customer_report_excel.index')}}" id="xls" class="tip" title="" style="text-decoration:none;color:#000;" data-original-title="Download as XLS">
            Excel
        </a>
    </div>
    </div>
</div>
        <div class="card">
            <div class="card-body">
                 @php  setlocale(LC_MONETARY,"en_US");  @endphp
                <center>
                    <table style=" margin-bottom:20px; width: 100%;">
                      @php
                      $sales = 0;
                      $Invoice = 0;
                      $Trade = 0;
                      $TradeNGD = 0;
                      $Due = 0;
                      @endphp
                        @foreach($calculat_amount as $row)
                        @php
                        if($row->item_status==2){ $sales += $row->memoSubTotal; }
                        if($row->item_status==2){ $Invoice += $row->memoSubTotal; }
                        if($row->item_status==4){ $Trade += $row->memoSubTotal; }
                        if($row->item_status==6){ $TradeNGD += $row->memoSubTotal; }
                        if($row->item_status==1 || $row->item_status==0){ $Due += $row->memoSubTotal; }
                        @endphp

                        @endforeach
                        <tr>
                            <td class="amount_cal" style="background:#428BCA !important;"> {{money_format("%(#1n",$sales)."\n"}}<br> sales Amount</td>
                            <td class="amount_cal" style="background:#fa603d ;"> {{money_format("%(#1n",$Invoice)."\n"}}<br> Total Invoice</td>
                            <td class="amount_cal" style="background:#78cd51 !important;"> {{money_format("%(#1n",$Trade)."\n"}} <br> Total Trade</td>
                            <td class="amount_cal" style="background:#5BC0DE !important;"> {{money_format("%(#1n",$TradeNGD)."\n"}} <br> Total TradeNGD</td>
                            <td class="amount_cal" style="background:#fa603d ;"> {{money_format("%(#1n",$Due)."\n"}} <br> Due Total</td>
                        </tr>
                    </table>
                </center>
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
                                <th>#</th>
                                <th>{{ translate('Memo Number ') }}</th>
                                <th>{{ translate('Company Name') }}</th>
                                <th>{{ translate('Customer Name') }}</th>
                                <th>{{ translate('Stock Sequence number') }}</th>
                                <th>{{ translate('Model Number') }}</th>
                                <th>{{ translate('Serial') }}</th>
                                <th>{{ translate('Reference') }}</th>
                                <th>{{ translate('Payment') }}</th>
                                <th>{{ translate('Sub Total') }}</th>
                                <th>{{ translate('Tracking') }}</th>
                                <th>{{ translate('Memo Status') }}</th>
                                <th>{{ translate('Due Date') }}</th>
                                <th>{{ translate('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($memoDashData as $key => $row)
                                <tr>
                                      <td>{{ ($$memoDashData->currentpage()-1) * $$memoDashData->perpage() + $key + 1 }}</td>

                                    <td>{{$row->memo_number}} </td>
                                    <td>{{$row->company}} </td>
                                    <td>{{$row->customer_name}} </td>
                                    <td>{{$row->stock_id}} </td>
                                    <td>{{$row->model}} </td>
                                    <td>{{$row->sku}} </td>
                                    <td><?php
                                    if($row->job_order_number=='')
                                    {
                                        echo $row->reference;
                                    }
                                    else
                                    {
                                        echo $row->job_order_number;
                                    }
                                    ?></td>
                                    <td>{{$row->payment_name}} </td>
                                    <td>{{money_format("%(#1n",$row->sub_total)."\n"}} </td>
                                    <td>{{$row->tracking}} </td>
                                    <td><?php
                                    if($row->item_status==1)
                                    {
                                        echo 'Memo';
                                    }
                                    elseif($row->item_status==0)
                                    {
                                        echo "Memo";
                                    }
                                    elseif($row->item_status==2)
                                    {
                                        echo 'Invoice';
                                    }
                                    elseif($row->item_status==3)
                                    {
                                        echo "Return";
                                    }
                                    elseif($row->item_status==4)
                                    {
                                        echo "Trade";
                                    }
                                    elseif($row->item_status==5)
                                    {
                                        echo "void";
                                    }
                                    elseif($row->item_status==6)
                                    {
                                        echo "TradeNGD";
                                    }
                                    ?> </td>
                                    <td>@if($row->due_date=="") 00/00/0000 @else {{date('m/d/y',strtotime($row->due_date))}} @endif</td>
                                    <td>{{date('m/d/y', strtotime($row->date))}} </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        @if($pagination_qty != "all")
                        <p>
                            Showing {{ $memoDashData->firstItem() }} to {{ $memoDashData->lastItem() }} of  {{$memoDashData->total()}} entries
                        </p>
                            {{ $memoDashData->appends(request()->input())->links() }}
                            @else
                            <p>
                            Showing {{$memoDashData->count()}} of  {{$memoDashData->count()}} entries
                            </p>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
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
