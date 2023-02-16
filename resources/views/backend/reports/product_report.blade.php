@extends('backend.layouts.app')
<style>
    td{
        text-align:center;
    }
</style>
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class=" align-items-center">
       <h1 class="h3">{{translate('Product Report')}}</h1>
	</div>
</div>

<div class="dropdown mb-2 mb-md-0 right_drop_mune_sec trans_mi">
    <div class="list_bulk_btn">
    <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
        <i class="las la-bars fs-20"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right">
        <a href="{{Route('product_report_excel.index')}}" id="xls" class="tip" title="" style="text-decoration:none;" data-original-title="Download as XLS">
            Excel
        </a>
    </div>
    </div>
</div>

<!-- <div class="box-header">
    <h2 class="blue"><i class="fa-fw fa fa-barcode"></i>Product Report
    </h2>
    <div class="box-icon">
        <ul class="btn-tasks">
            <li class="dropdown">
                <a href="{{Route('product_report_excel.index')}}" id="xls" class="tip" title="" data-original-title="Download as XLS">
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
                        <div class="col-md-3 ml-auto d-flex">
                        <!-- <label>Listing Type </label> -->
                        <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="listing_type" id="product_type" data-live-search="true">
                        <option value="">All Listing Type</option>
                            @foreach (App\SiteOptions::where('option_name', '=', 'listingtype')->get() as $p_type_filter)
                                <option value="{{$p_type_filter->option_value}}" @if($p_type_filter->option_value == Request::get('listing_type')) selected @endif>{{ $p_type_filter->option_value }}</option>
                            @endforeach;
                        </select>
                        <button type="submit" id="pro_type"  class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>
                    </div>
                    <div class="col-md-3 ml-auto d-flex">
                        <!-- <label>Customer</label> -->
                        <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="category" id="agent_id" data-live-search="true">
                            <option value="">Category</option>
                            @foreach (App\Category::all() as $category)
                                <option value="{{$category->id}}" @if($category->id == Request::get('category')) selected @endif>{{ $category->name }}</option>
                            @endforeach;
                        </select>
                        <button type="submit" id="Agent_type" class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>
                    </div>
                    <div class="col-md-3 ml-auto d-flex">
                        <!-- <lable>Model</label> -->
                        <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="model_number" id="product_model" data-live-search="true">
                            <option value="">All Model</option>
                            @foreach (App\SiteOptions::where('option_name', '=', 'model')->get() as $p_type_filter)
                                <option value="{{$p_type_filter->option_value}}" @if($p_type_filter->option_value == Request::get('model_number')) selected @endif>{{ $p_type_filter->option_value }}</option>
                            @endforeach;
                        </select>
                        <button type="submit" id="btn_model"  class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>
                    </div>
                    <div class="col-md-3 ml-auto d-flex">
                        <!-- <lable>Brand</label> -->
                        <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="brand" id="brand_repot" data-live-search="true">
                            <option value="">All Brand</option>
                            @foreach (App\Brand::all() as $brand)
                                <option value="{{$brand->id}}" @if($brand->id == Request::get('brand')) selected @endif>{{ $brand->name }}</option>
                            @endforeach;
                        </select>
                        <button type="submit" id="brandRbtn"  class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>
                    </div>
                    </div>
                    <div class="card-header row gutters-5">
                    <!-- <div class="col-md-2 ml-auto d-flex">
                        <input type="hidden" name="startrangedate" class="startrangedate" value="">
                        <input type="hidden" name="endrangedate" class="endrangedate" value="">
                        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 10px; border: 1px solid #ccc; width: 100%">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span> <i class="fa fa-caret-down"></i>
                        </div>
                        <input type="submit" value="search" name="btn">
                    </div> -->
                    
                    <div class="col-md-3 d-flex">
                        <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="partner" id="partner_repot" data-live-search="true">
                            <option value="">All Partner</option>
                            @foreach (App\Product::groupBy('partner')->get() as $partner)
                                <option value="{{$partner->partner}}" @if($partner->partner == Request::get('partner')) selected @endif>{{ $partner->partner }}</option>
                            @endforeach;
                        </select>
                        <button type="submit" id="partnerRbtn"  class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>
                    </div>
                    <div class="col-md-3 d-flex">
                        <!-- <lable>Warehouse</label> -->
                        <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="warehouse_id" id="warehouse_id" data-live-search="true">
                            <option value="">All Warehouse</option>
                            @foreach (App\Models\Warehouse::all() as $warehouse)
                                <option value="{{$warehouse->id}}" @if($warehouse->id == Request::get('warehouse_id')) selected @endif>{{ $warehouse->name }}</option>
                            @endforeach;
                        </select>
                        <button type="submit" id="warehouse_type"  class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>
                    </div>

                    @php $memo=1; $invoce=2; $trade=4; $tradeNgd=6; @endphp
                    <div class="col-md-3 d-flex">
                        <!-- <label>All Status </label> -->
                        <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="memo_status" id="memoStatus" data-live-search="true">
                            <option value="">All Status</option>
                            <option  value="{{$memo}}" @if($memo== Request::get('memo_status')) selected @endif>Memo</option>
                            <option value="{{$invoce}}" @if($invoce== Request::get('memo_status')) selected @endif>Invoice</option>
                            <option value="{{$trade}}"  @if($trade== Request::get('memo_status')) selected @endif>Trade</option>
                            <option value="{{$tradeNgd}}"  @if($tradeNgd== Request::get('memo_status')) selected @endif>TradeNGD</option>
                        </select>
                        <button type="submit" id="memostatusbtn"  class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>
                    </div>
                    <div class="col-md-3 d-flex"></div>
                     </div>
                    <div class="row page_qty_sec product_search_header">
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
                                <th>{{ translate('Stock Id') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th>{{ translate('Company Name') }}</th>
                                <th>{{ translate('Category') }}</th>
                                <th>{{ translate('Listing Type ') }}</th>
                                <th>{{ translate('Product Type') }}</th>
                                <th>{{ translate('Brand') }}</th>
                                <th>{{ translate('Model') }}</th>
                                <th>{{ translate('Serial Number') }}</th>
                                <th>{{ translate('Partner') }}</th>
                                <th>{{ translate('Warehouse') }}</th>
                                <th>{{ translate('Cost Code') }}</th>
                                <th>{{ translate('Sale Price') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($PurchasesData as $key => $row)
                            <tr>
                                 <td>{{ ($PurchasesData->currentpage()-1) * $PurchasesData->perpage() + $key + 1 }}</td>
                                <td> {{$row->stock_id}}</td>
                                <td> 
                                <?php
                                    if($row->published=='1' && $row->qty )
                                        {
                                            echo "Available";
                                        }

                                        elseif($row->item_status==1)
                                        {
                                            echo "Memo";
                                        }
                                        elseif($row->item_status=='0')
                                        {
                                            echo "Memo";
                                        }
                                        elseif($row->item_status==2)
                                        {
                                            echo "INVOICE";
                                        }
                                        elseif($row->item_status==3)
                                        {
                                        echo "RETURN";
                                        }
                                        elseif($row->item_status==4)
                                        {
                                            echo "TRADE";
                                        }
                                        elseif($row->item_status==5)
                                        {
                                            echo "VOID";
                                        }
                                        elseif($row->item_status==6)
                                        {
                                            echo "TRADE NGD";
                                        }
                                        ?>
                                </td>
                                <td><?php 
                                if(isset($row->customer_group))
                                {
                                    
                                    if($row->customer_group=="reseller")
                                    {
                                        echo $row->company;
                                    }
                                    else
                                    {
                                        echo $row->customer_name;
                                    }
                                }
                                ?></td>
                                    @php  setlocale(LC_MONETARY,"en_US");  @endphp
                                <td> {{$row->categories_name}}</td>
                                <td> {{$row->listing_type}}</td>
                                <td> {{$row->product_type_name}}</td>
                                <td> {{$row->brandName}}</td>
                                <td> {{$row->model}}</td>
                                <td> {{$row->sku}}</td>
                                <td> {{$row->partner}}</td>
                                <td> {{$row->warehouseName}}</td>
                                <td> {{money_format("%(#1n",$row->cost_code)."\n" }}</td>
                                <td> {{money_format("%(#1n",$row->unit_price)."\n" }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($pagination_qty != "all")
                            <p>
                                Showing {{ $PurchasesData->firstItem() }} to {{ $PurchasesData->lastItem() }} of total {{$PurchasesData->total()}} entries
                            </p>
                        <span style="margin-left:90%;"> {{ $PurchasesData->appends(request()->input())->links() }}</span>
                        @else
                        <p>
                        Showing {{$PurchasesData->count()}} of total {{$PurchasesData->count()}} entries
                        </p>
                    @endif
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
<!-- <script type="text/javascript">
$(function() {

    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        $('.startrangedate').val(start.format('MMMM D, YYYY'));
        $('.endrangedate').val(end.format('MMMM D, YYYY'));
        
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
</script> -->
@endsection

