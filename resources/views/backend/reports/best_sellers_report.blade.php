@extends('backend.layouts.app')
<style>
    td
    {
        text-align:center;
    }
</style>
@php  setlocale(LC_MONETARY,"en_US");  @endphp
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class=" align-items-center">
       <h1 class="h3">{{translate(' Best Sellers')}}</h1>
	</div>
</div>
<div class="row">
    <div class="col-md-12 mx-auto">
        <div class="card">
            <form class="warehouse_table" id="sort_products" action="" method="GET">
                <div class="row page_qty_sec product_search_header">
                <div class="col-md-6">
               
                  <input type="hidden" name="startrangedate" class="startrangedate" value="">
                  <input type="hidden" name="endrangedate" class="endrangedate" value="">
                  <div id="reportrange" style="background: #fff; cursor: pointer; padding: 6px; border: 1px solid #ccc; width: 46%">
                  <i class="las la-calendar"></i>
                      <i class="fa fa-calendar"></i>&nbsp;
                      <span></span> <i class="fa fa-caret-down"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <i class="las la-angle-down"></i>
                  </div>
                  <!-- <input type="submit" value="search" name="btn"> -->
                  <button type="submit" id="warehouse_type" name="warehouse_type" class="d-none calendar_submit"><i class="las la-search aiz-side-nav-icon" ></i></button>
                </div>
                <div class="col-md-6">
                    <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="listing_type" id="product_type" data-live-search="true">
                        <option value="">All Listing Type</option>
                        @foreach (App\SiteOptions::where('option_name', '=', 'listingtype')->get() as $p_type_filter)
                            <option value="{{$p_type_filter->option_value}}" @if($p_type_filter->option_value == Request::get('listing_type')) selected @endif>{{ $p_type_filter->option_value }}</option>
                        @endforeach;
                   </select>
                    <button type="submit" id="pro_type"  class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>
                </div>
                </div>
                <div class="row page_qty_sec product_search_header" style="padding-top: 25px;">
                    <input type="hidden" name="search" id="searchinputfield">
                    <select class="form-control form-select" id="pagination_qty" name="pagination_qty" style="display:none">
                        <option value="25" @if($pagination_qty == 25) selected @endif>25</option>
                        <option value="50" @if($pagination_qty == 50) selected @endif>50</option>
                        <option value="100" @if($pagination_qty == 100) selected @endif>100</option>
                        <option  @if($pagination_qty == "all") selected @endif value="all">All</option>
                    </select>
                    <div class="col-2 d-flex page_form_sec">
                        <label class="fillter_sel_show m-auto"><b>Show</b></label>
                        <select class="form-control form-select" id="pagination_use_qty"  aria-label="Default select example">
                            <option value="25" @if($pagination_qty == 25) selected @endif>25</option>
                            <option value="50" @if($pagination_qty == 50) selected @endif>50</option>
                            <option value="100" @if($pagination_qty == 100) selected @endif>100</option>
                            <option value="all" @if($pagination_qty == "all") selected @endif>All</option>
                        </select>
                    </div>
                    <div class="col-6 d-flex search_form_sec">
                            <label class="fillter_sel_show m-auto"><b>Search</b></label>
                            <input type="text" class="form-control form-control-sm sort_search_val"  @isset($sort_search) value="{{ $sort_search }}" @endisset style="width:200px;">
                            <button type="button" class="search_btn_field"><i class="las la-search aiz-side-nav-icon" ></i></button>
                        </div>
                </div>
                <div class="card-body">
                 <table class="table table-bordered aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('#') }}</th>
                            <th>{{ translate('Model Number') }}</th>
                            <th>{{ translate('Qty Sold ') }}</th>
                            <th>{{ translate('Sales Value') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $count =0; @endphp
                        @foreach($best_seller_report as $key => $row)

                                 <td>{{ ($best_seller_report->currentpage()-1) * $best_seller_report->perpage() + $key + 1 }}</td>
                            <?php $count++; ?>
                            <tr>
                                <td>{{$count}} </td>
                                <td> {{$row->model}}</td>
                                <td> {{$row->totalQty}}</td>
                                <td> {{money_format("%(#1n",$row->memoSubTotal)."\n"}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                @if($pagination_qty != "all")
                <p>
                    Showing {{ $best_seller_report->firstItem() }} to {{ $best_seller_report->lastItem() }} of  {{$best_seller_report->total()}} entries
                </p>
                    {{ $best_seller_report->appends(request()->input())->links() }}
                    @else
                    <p>
                    Showing {{$best_seller_report->count()}} of  {{$best_seller_report->count()}} entries
                    </p>
                @endif
            </div>
              </div>
            </form>
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


<script type="text/javascript">
$(function() {

    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('MM / DD / YYYY') + ' - ' + end.format('MM  / DD / YYYY'));
        $('.startrangedate').val(start.format('MM / DD /YYYY'));
        $('.endrangedate').val(end.format('MM / DD /YYYY'));
        
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
    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
		$('.calendar_submit').trigger('click');
});
});
</script>
@endsection
