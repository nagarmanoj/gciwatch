@extends('backend.layouts.app')
@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class=" align-items-center">
        <h1 class="h3">{{translate('Short Stock')}}</h1>
        </div>
    </div>
    <form method="get" id="pagination_form" action="">
        <div class="dropdown mb-2 mb-md-0 right_drop_mune_sec trans_mi style="margin-left: 0 !important; max-width:28% !important;">
            <div class="date_year_box">
                <input type="hidden" name="startrangedate" class="startrangedate" value="">
                <input type="hidden" name="endrangedate" class="endrangedate" value="">
                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc; width: 100%">
                    <i class="las la-calendar"></i>
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <i class="las la-angle-down"></i>
                </div>
                <button type="submit" id="warehouse_type" name="warehouse_type" class="d-none calendar_submit"><i class="las la-search aiz-side-nav-icon" ></i></button>
                <!-- <input type="submit" class="calendar_submit" value="search" name="btn"> -->
            </div>
            <div class="list_bulk_btn">
                <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                    <i class="las la-bars fs-20"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{Route('short_stock_excel.index')}}" id="xls" class="tip" title="" style="text-decoration:none;color:#000;" data-original-title="Download as XLS">
                        Excel
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <!-- <form method="get" id="pagination_form" action=""> -->
                        <div class="row page_qty_sec product_search_header" style="margin-top:0px; margin-bottom:20px;">
                            <input type="hidden" name="search" id="searchinputfield">
                            <select class="form-control form-select" id="pagination_qty" name="pagination_qty" style="display:none">
                                <option value="25" @if($pagination_qty == 25) selected @endif>25</option>
                                <option value="50" @if($pagination_qty == 50) selected @endif>50</option>
                                <option value="100" @if($pagination_qty == 100) selected @endif>100</option>
                                <option  @if($pagination_qty == "all") selected @endif value="all">All</option>
                            </select>
                            <div class="col-2 d-flex page_form_sec" style="padding-left: 0px;">
                                <label class="fillter_sel_show m-auto"><b>Show</b></label>
                                <select class="form-control form-select" id="pagination_use_qty"  aria-label="Default select example">
                                    <option value="25" @if($pagination_qty == 25) selected @endif>25</option>
                                    <option value="50" @if($pagination_qty == 50) selected @endif>50</option>
                                    <option value="100" @if($pagination_qty == 100) selected @endif>100</option>
                                    <option value="all" @if($pagination_qty == "all") selected @endif>All</option>
                                </select>
                            </div>
                            <div class="col-6 d-flex search_form_sec" style="padding-right: 0px;">
                                    <label class="fillter_sel_show m-auto"><b>Search</b></label>
                                    <input type="text" class="form-control form-control-sm sort_search_val"  @isset($sort_search) value="{{ $sort_search }}" @endisset style="width:200px;">
                                    <button type="button" class="search_btn_field"><i class="las la-search aiz-side-nav-icon" ></i></button>
                                </div>
                        </div>
                        <table class="table table-bordered aiz-table mb-0">
                            <thead>
                                <tr>
                                    <th>{{ translate('#') }}</th>
                                    <th>{{ translate('Model Numer') }}</th>
                                    <th>{{ translate('Sold Quantity ') }}</th>
                                    <th>{{ translate('On Hold') }}</th>
                                    <th>{{ translate('Available Quantity') }}</th>
                                    <th>{{ translate('Total Quantity') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($short_stock_data as $key => $row)
                                @php
                                $totalModelQty = 0;
                                if(!empty($row->model)){
                                $mt = $row->model;
                                $ModelQty = DB::table('products')
                                            ->leftJoin('product_stocks','products.id','=','product_stocks.product_id')
                                            ->where('model',$mt)
                                            ->select('product_stocks.qty')
                                            ->get();
                                }

                                $sumVal1 = 0;
                                $sumVal2 = 0;
                                if($row->item_status == 2 || $row->item_status == 4 || $row->item_status == 6){
                                $sumVal1 = $row->memoqtysum;
                                }
                                if($row->item_status == 0 || $row->item_status == 1){
                                $sumVal2 = $row->memoqtysum;
                                }
                                $sumQtyVal = $sumVal1 + $sumVal2;
                                @endphp
                                @if($row->model != "")
                                <tr>
                                    <td class="text-center">{{$key + 1}}</td>
                                    <td class="text-center">{{$row->model}} </td>
                                    <td class="text-center">
                                      @if($row->item_status == 2 || $row->item_status == 4 || $row->item_status == 6)
                                        {{$row->memoqtysum}}
                                        @else
                                        {{'0'}}
                                      @endif
                                    </td>
                                    <td class="text-center">
                                      @if($row->item_status == 0 || $row->item_status == 1)
                                       {{$row->memoqtysum}}
                                       @else
                                       {{'0'}}
                                      @endif
                                    </td>
                                    <td class="text-center">
                                      @foreach($ModelQty as $MQtyrow)
                                        @php
                                        $totalModelQty += $MQtyrow->qty;
                                        @endphp
                                      @endforeach
                                      {{$totalModelQty}}
                                    </td>
                                    <td class="text-center">{{ $sumQtyVal + $totalModelQty}}</td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                        <div class="aiz-pagination">
                            @if($pagination_qty != "all")
                            <p>
                                Showing {{ $short_stock_data->firstItem() }} to {{ $short_stock_data->lastItem() }} of  {{$short_stock_data->total()}} entries
                            </p>
                            {{ $short_stock_data->appends(request()->input())->links() }}
                            @else
                            <p>
                            Showing {{$short_stock_data->count()}} of  {{$short_stock_data->count()}} entries
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
    if(e.which == 13)
    {
        prosearchform();
    }

});
    $("#pagination_use_qty").change(function(){
        var pageQty = $(this).val();
        $('#pagination_qty').val(pageQty);
        $("#sort_products").submit();
    });
    function prosearchform(){
        var searchVal = $('.sort_search_val').val();
        $('#searchinputfield').val(searchVal);
        $("#sort_products").submit();
    }
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
    // $('#date_reng').trigger('click');

});
$('#reportrange').on('apply.daterangepicker', function(ev, picker) {
		$('.calendar_submit').trigger('click');
});
</script>
@endsection
