@extends('backend.layouts.app')
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class=" align-items-center">
       <h1 class="h3">{{translate('Customers Report')}}</h1>
	</div>
</div>

<div class="dropdown mb-2 mb-md-0 right_drop_mune_sec trans_mi">
    <div class="list_bulk_btn">
    <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
        <i class="las la-bars fs-20"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right">
        <a href="{{Route('customer_report_excel.index')}}" id="xls" class="tip" title="" style="text-decoration:none;" data-original-title="Download as XLS">
            Excel
        </a>
    </div>
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
        <div class="card">
                <form class="" id="sort_products" action="" method="GET">
                    <div class="card-header row gutters-5">
                    <div class="col-md-2 ml-auto d-flex">
                        <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="customer_name" id="agent_id" data-live-search="true">
                            <option value="">All Customer</option>
                            @foreach (App\RetailReseller::all() as $whouse_filter)
                                <option value="{{$whouse_filter->id}}" @if($whouse_filter->id == Request::get('customer_name')) selected @endif>{{ $whouse_filter->customer_name }}</option>
                            @endforeach;
                        </select>
                        <button type="submit" id="Agent_type" class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>
                    </div>
                </div>
                    <div class="row page_qty_sec product_search_header" style="padding-top: 15px;">
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
			      
                </form>
                <div class="card-body">
                      <table class="table table-bordered aiz-table mb-0">
                        <thead>
                            <tr>
                                <th> #</th>
                                <th>{{ translate('Company') }}</th>
                                <th>{{ translate('Name') }}</th>
                                <!-- <th>{{ translate('Company Name') }}</th> -->
                                <th>{{ translate('Phone') }}</th>
                                <th>{{ translate('Email Address') }}</th>
                                <th>{{ translate('Total Sales') }}</th>
                                <th>{{ translate('Actions') }}</th>
                            </tr>
                        </thead>
                        @php  setlocale(LC_MONETARY,"en_US");  @endphp
                        @foreach($customer_report as $key => $row)
                        <tr>
                            
                                 <td>{{ ($customer_report->currentpage()-1) * $customer_report->perpage() + $key + 1 }}</td>
                            <td><a href="#" style="test-decoration:none;color:#000;">{{$row->company}} </a> </td>
                            <td><a href="#" style="test-decoration:none;color:#000;">{{$row->cu_name}} </a> </td>
                            <td><a href="#" style="test-decoration:none;color:#000;">{{$row->phone}} </a> </td>
                            <td><a href="#" style="test-decoration:none;color:#000;">{{$row->email}} </a> </td>
                            <td><a href="#" style="test-decoration:none;color:#000;">{{money_format("%(#1n",$row->memoSubTotal)."\n"}}</a> </td>
                            <td><a href="{{route('customer_reports.index', ['id'=>$row->company_id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" >View Report </a> </td>
                        </tr>
                        @endforeach
                        <tbody>
                        
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        @if($pagination_qty != "all")
                        <p>
                            Showing {{ $customer_report->firstItem() }} to {{ $customer_report->lastItem() }} of  {{$customer_report->total()}} entries
                        </p>
                            {{ $customer_report->appends(request()->input())->links() }}
                            @else
                            <p>
                            Showing {{$customer_report->count()}} of  {{$customer_report->count()}} entries
                            </p>
                        @endif
                    </div>
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
@endsection

