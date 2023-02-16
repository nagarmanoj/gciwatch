@extends('backend.layouts.app')

@section('content')
@php
    setlocale(LC_MONETARY,"en_US");
@endphp
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">Complete Job Report</h1>
        </div>
    </div>
</div>
<!-- <div class="box-header">
    <h2 class="blue"><i class="fa-fw fa fa-barcode"></i>Complete Job Report
    </h2>
    <div class="box-icon">
        <ul class="btn-tasks">
            <li class="dropdown">
                <a href="{{Route('complete_report_export.index')}}" id="xls" class="tip" title="" data-original-title="Download as XLS">
                   Excel
                </a>
            </li>
        </ul>
    </div>
</div> -->

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
                    <a href="{{Route('complete_report_export.index')}}" id="xls" class="tip" title="" style="text-decoration:none;" data-original-title="Download as XLS">
                        Excel
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <!-- <form class="" id="sort_products" action="" method="GET"> -->
            <div class="card-header row gutters-5 purchases_form_sec" style="padding-bottom: 0px;">
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
            <div class="card-body">
            <table class="table aiz-table mb-0 footable footable-1 breakpoint breakpoint-lg list_agent_table" style="">
                <thead>
                    <tr class="footable-header">
                        <th> #</th>
                        <th>Customer</th>
                        <th>Bag Number</th>
                        <th>Job Order Number</th>
                        <th>Model</th>
                        <th>Serial</th>
                        <th>Stock Id</th>
                        <th>Date Entered</th>
                        <th> Estimated Date Of Returned</th>
                        <th>Date Returned</th>
                    <th> Actual Cost</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody> 
                    @php $count =0; @endphp
                    @foreach($complete_report as $key => $row)
                        <?php $count++ ; ?>
                        <tr> 
                              <td>{{ ($complete_report->currentpage()-1) * $complete_report->perpage() + $key + 1 }}</td>
                          <!--   <td> {{$count}} </td> -->
                            @if($row->company_name==0)
                            <td> Stock </td>
                            @else
                            @if($row->customer_group=="reseller")
                            <td> {{$row->c_name}} </td>
                            @else
                            <td> {{$row->customer_name}}</td>
                            @endif
                            @endif
                            <td> {{$row->bag_number}} </td>
                            <td> {{$row->job_order_number}} </td>
                            <td> {{$row->model_number}} </td>
                            <td> {{$row->serial_number}} </td>
                            <td> {{$row->stock_id}} </td>
                            <td> {{date('m/d/y', strtotime($row->enter_date))}} </td>
                            <td> {{date('m/d/y', strtotime($row->estimated_date))}}  </td>
                            <td> @if(!empty($row->date_of_return)) {{date('m/d/y', strtotime($row->date_of_return))}} @else {{ $row->date_of_return}} @endif</td>
                            <td> {{ money_format("%(#1n",$row->total_actual_cost)."\n"}}</td>
                            <td><?php
                            if($row->job_status==1)
                            {
                                echo "Post Due";
                            }
                            elseif($row->job_status==2)
                            {
                                echo "Open";
                            }
                            elseif($row->job_status==3)
                            {
                                echo "Pendding";
                            }
                            elseif($row->job_status==4)
                            {
                                echo "On Hold";
                            }
                            elseif($row->job_status==5)
                            {
                                echo "Close";
                            }
                            ?> </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                @if($pagination_qty != "all")
                <p>
                    Showing {{ $complete_report->firstItem() }} to {{ $complete_report->lastItem() }} of  {{$complete_report->total()}} entries
                </p>
                    {{ $complete_report->appends(request()->input())->links() }}
                    @else
                    <p>
                    Showing {{$complete_report->count()}} of  {{$complete_report->count()}} entries
                    </p>
                @endif
            </div>
        </div>
    </form>
</div>
</div>
</div>


@endsection

@section('modal')

    @include('modals.delete_modal')

@endsection

@section('script')

<script type="text/javascript">
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
    //  $('.complete_report_excel').on('click',function(){
    //     $.ajax({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         type:'get',
    //         url:"{{Route('agent_report_export.index')}}",
    //         success:function(response)
    //         {
    //             alert("success");
    //         }
    //     })
    //  });

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
    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
		$('.calendar_submit').trigger('click');
	});
});
</script>
@endsection

