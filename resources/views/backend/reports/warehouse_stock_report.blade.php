@extends('backend.layouts.app')
<style>
    .catgers_table td
    {
        width: 33.33%;
        text-align: center;
        padding: 70px !important;
        color:#fff;
        font-size:30px !important;
    }
    .cat_name
    {
        background-color:#5bc0de;
    }
    .cat_velu{
        background-color:#428bca;
    }
    .cat_pric
    {
        background-color:#78cd51;
    }
</style>
@section('content')
@php  setlocale(LC_MONETARY,"en_US");  @endphp
    <div class="aiz-titlebar text-left mt-2 mb-3">
    	<div class=" align-items-center">
           <h1 class="h3">{{translate('Warehouse Stock Chart (All)')}}</h1>
    	</div>
    </div>
    <div class="row">
        <div class="col-md-12 mx-auto">
            <div class="card">
                <!--card body-->
                <form class="warehouse_table" id="sort_products" action="" method="GET">
                    <div class="row page_qty_sec product_search_header">
                    <div class="col-md-4 warehouse_tab">
                        <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="warehouse_id" id="warehouse_id" data-live-search="true">
                        <option value="">All Warehouse</option>
                        @foreach (App\Models\Warehouse::all() as $whouse_filter)
                            <option value="{{$whouse_filter->id}}" @if($whouse_filter->id == Request::get('warehouse_id')) selected @endif>{{ $whouse_filter->name }}</option>
                        @endforeach;
                        </select>
                        <button type="submit" id="warehouse_type" class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>
                    </div>
                    <div class="col-md-4 warehouse_tab">
                        <!-- <label class="fillter_sel_show m-auto"><b>Stock</b></label> -->
                        <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="stock" id="stock_filter_type" data-live-search="true">
                           
                            <!-- <option value="">Select Stock</option> -->
                            <option >All</option>
                            <option value="2" @if(Request::get('stock')==2) selected @endif>Available</option>
                            <option value="3"  @if(Request::get('stock')==3) selected @endif>On Memo</option>
                        </select>
                        <button type="submit" id="stock_filter_btn" class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>
                    </div>
                    <div class="col-md-4 warehouse_tab">
                        
                                @php $value= Request::get('value'); @endphp
                            <input type="hidden" value="{{$value}}" id="value_w">
                        <!-- <label class="fillter_sel_show m-auto"><b>Value</b></label> -->
                        <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="value" id="warehouse_values" data-live-search="true">
                            <option value="">Select Values</option>
                            <option value="1" @if(Request::get('value')==1) selected @endif>Gross </option>
                            <option value="2" @if(Request::get('value')==2) selected @endif>Net</option>
                        </select>
                        <button type="submit" id="values_filterbtn" class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>
                    </div>
                            <div class="card-body" style="width: 100%;">
                                    <table class="table aiz-table mb-0">
                                        <tbody>
                                            @php
                                                $countTotal = 0;
                                                $countAmount = 0;
                                            @endphp
                                            @foreach($warehouse as $row)
                                                @php
                                                    $filterTotal =  isset($countedArr[$row->listing_type]) ? $countedArr[$row->listing_type] : "";                                                  
                                                    if($filterTotal > 0){
                                                      $row->totalPrice = $row->totalPrice - $filterTotal;
                                                      $row->totalPrice = $row->totalPrice + ($filterTotal/2);
                                                    }
                                                    $countAmount+=$row->totalPrice;
                                                    $countTotal+=$row->total_count
                                                @endphp
                                                <tr class="catgers_table">
                                                    <td class="cat_name"> {{$row->listing_type}} </td>
                                                    <td class="cat_velu">
                                                    {{$row->total_count}}

                                                    </td>
                                                    <td class="cat_pric"> {{ money_format("%(#1n", $row->totalPrice)."\n"}} <br>
                                                    <span style='text-align:center ;cursor:pointer;'data-toggle="modal" data-id="{{ $row->listing_type}}" onclick="warehousedataAjax('{{$row->listing_type}}')" > <i class="las la-eye"></i></span></td>
                                                </tr>
                                            @endforeach
                                            @php $listing_type=''; @endphp
                                            <tr class="catgers_table">
                                                <td class="cat_name"> Total</td>
                                                <td class="cat_velu"> {{$countTotal}}</td>
                                                <td class="cat_pric"> {{ money_format("%(#1n", $countAmount)."\n"}}<br><span style='text-align:center ;cursor:pointer;'data-toggle="modal" onclick="warehousedataAjax('{{$listing_type}}')" data-id=""> <i class="las la-eye"></i></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="aiz-pagination">
                                    </div>
                                </div>
                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- warehouse model  -->
    <div class="modal fade" id="warehousemodel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width:700px;">
            <div class="modal-content">
                <div class="card">
                    <div class="card-header row gutters-5">
                        <div class="col text-center text-md-left">
                            <h5 class="mb-md-0 h6 products_type_name"></h5>
                        </div>
                    </div>
                    <div class="card-body warehouseResponse">
                        <div class="aiz-pagination"> </div>
                    </div>
                </div>
                <div class="close-btn"> </div>
            </div>
            <div class="modal-body" >
                <div id="warehouseData">  </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function warehousedataAjax(listing_type)
        {
            var warehouse_values=$('#value_w').val();
            // alert(warehouse_values);
            $.ajax({
                type:'post',
                url:'{{route("reportAjax.warehouseData")}}',
                // dataType:'json',
                data:{"_token": "{{ csrf_token() }}","listing_type":listing_type,"warehouse_values":warehouse_values},
                success:function(response)
                {
                    // alert(response);
                    var ReturnHtml = response.listingTypeData;
                    // alert(response);
                    $("#warehousemodel").modal('toggle');
                    $('.warehouseResponse').html(ReturnHtml);
                    $('.products_type_name').text(response.product_type);
                }
            });
        }
    </script>
@endsection
