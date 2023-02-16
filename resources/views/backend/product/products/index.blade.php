<style>
    body
    {
        overflow-x: scroll;
    }
    #delete_section:hover
    {
      background:blue !important;
    }
    #product_delete
    {
        color:#797d91;
    }
    #product_delete:hover
    {
        background-color:#377af9 !important;
        color:#fff !important;
    }
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
    .all_product th 
    {
    	padding-top: 5px !important;
    	padding-bottom: 5px !important;
    	vertical-align: middle !important;
    	font-size: 14px !important;
    }
</style>
@extends('backend.layouts.app')
@section('content')
    @php
       setlocale(LC_MONETARY,"en_US");
    @endphp
    <script src="{{ static_asset('assets/js/webcam.min.js') }}" ></script>
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center product_index">
            <div class="col-auto">
                <!-- <h1 class="h3">{{translate('All products')}}</h1> -->
            </div>
            @if($type != 'Seller')
                <div class="col text-right">
                <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                    Edit Column
                    </button>
                    <!-- <a href="{{ route('products.create') }}" class="btn btn-circle btn-info">
                    <span class="aiz-side-nav-text">{{translate('Add New Product')}}</span>
                    </a> -->
                </div>
                <div class="dropdown mb-2 mb-md-0">
                    <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                    <i class="las la-bars fs-20"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                    @if(Auth::user()->user_type == 'admin' || in_array('15', json_decode(Auth::user()->staff->role->inner_permissions)))
                        <form class="delete_section" action="{{route('bulk-product-delete')}}" method="post">
                            @csrf
                            <input type="hidden" name="checked_id" id="checkox_pro" value="">
                            <button id="product_delete" type="submit" style="border:none;padding-right:85px;padding-top: 0.5rem;padding-bottom: 0.5rem;background:#fff;" class="w-100 exportPro-class" disabled>Delete selection</button>
                        </form>
                    @endif
                    @if(Auth::user()->user_type == 'admin' || in_array('27', json_decode(Auth::user()->staff->role->inner_permissions)))
                        <form class="" action="{{route('product-export.index')}}" method="post">
                            @csrf
                            <input type="hidden" name="checked_id" id="checkox_pro_export" value="">
                            <button id="product_export" type="submit" class="w-100 exportPro-class" disabled>Bulk export</button>
                        </form>
                    @endif
                    <!-- <input type="hidden" name="barcode_id" id="barcode_check_stock" value=""> -->
                    <button id="barcode_label" class="w-100 exportPro-class">Print Barcode/Label</button>
                </div>
            </div>
        @endif
    </div>



</div>



<br>



<!--



    <form method="get" id="pagination_form" action="{{route('products.admin')}}" style="display:none;">



        <div class="row">



            <div class="col-2 d-flex">



                <select class="form-control form-select" id="pagination_qty" name="pagination_qty" aria-label="Default select example">



                  <option value="25" @if($pagination_qty == 25) selected @endif>25</option>



                  <option value="50" @if($pagination_qty == 50) selected @endif>50</option>



                  <option value="100" @if($pagination_qty == 100) selected @endif>100</option>



                  <option value="200" @if($pagination_qty == 200) selected @endif>200</option>



                </select>



                <input type="submit" class="btn btn-primary btn-sm" value="Show" style="display:none;">



            </div>



        </div>



    </form>



    -->



<div class="card">



    <form class="" id="sort_products" action="" method="GET">



        <div class="card-header row gutters-5">



            <div class="col">



                <h5 class="mb-md-0 h6">{{ translate('All Product') }}</h5>



            </div>



            <!-- <div class="dropdown mb-2 mb-md-0">



                <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">



                    {{translate('Bulk Action')}}



                </button>



                <div class="dropdown-menu dropdown-menu-right">



                    <a class="dropdown-item" href="#" onclick="bulk_delete()"> {{translate('Delete selection')}}</a>



                </div>



            </div> -->



            @if($type == 'Seller')



            <!-- <div class="col-md-2 ml-auto">



                <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" id="user_id" name="user_id" onchange="sort_products()">



                    <option value="">{{ translate('All Sellers') }}</option>



                    @foreach (App\Seller::all() as $key => $seller)



                        @if ($seller->user != null && $seller->user->shop != null)



                            <option value="{{ $seller->user->id }}" @if ($seller->user->id == $seller_id) selected @endif>{{ $seller->user->shop->name }} ({{ $seller->user->name }})</option>



                        @endif



                    @endforeach



                </select>



            </div> -->



            @endif



            @if($type == 'All')



            <!-- <div class="col-md-2 ml-auto">



                <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" id="user_id" name="user_id" onchange="sort_products()">



                    <option value="">{{ translate('All Sellers') }}</option>



                        @foreach (App\User::where('user_type', '=', 'admin')->orWhere('user_type', '=', 'seller')->get() as $key => $seller)



                            <option value="{{ $seller->id }}" @if ($seller->id == $seller_id) selected @endif>{{ $seller->name }}</option>



                        @endforeach



                </select>



            </div> -->



            @endif







            <input type="hidden" name="search" id="searchinputfield">



            <select class="form-control form-select" id="pagination_qty" name="pagination_qty" style="display:none">



                  <option value="25" @if($pagination_qty == 25) selected @endif>25</option>



                  <option value="50" @if($pagination_qty == 50) selected @endif>50</option>



                  <option value="100" @if($pagination_qty == 100) selected @endif>100</option>



                  <option  @if($pagination_qty == "all") selected @endif value="all">All</option>



            </select>



            <div class="col-md-2 ml-auto d-flex">



                <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="warehouse_id" id="warehouse_id" data-live-search="true">



                  <option value="">All Warehouse</option>



                  @foreach (App\Models\Warehouse::all() as $whouse_filter)



                    <option value="{{$whouse_filter->id}}" @if($whouse_filter->id == Request::get('warehouse_id')) selected @endif>{{ $whouse_filter->name }}</option>



                  @endforeach;



                </select>



                <button type="submit" id="warehouse_type" class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>



            </div>



            <div class="col-md-2 ml-auto d-flex">



                <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="listing_type" id="product_type" data-live-search="true">



                    <option value="">All Listing Type</option>



                    @foreach (App\SiteOptions::where('option_name', '=', 'listingtype')->get() as $p_type_filter)



                    <option value="{{$p_type_filter->option_value}}" @if($p_type_filter->option_value == Request::get('listing_type')) selected @endif>{{ $p_type_filter->option_value }}</option>



                  @endforeach;



                </select>



                <button type="submit" id="pro_type"  class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>



            </div>



            <!-- <div class="col-md-2 ml-auto d-flex">



                <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="product_type" id="product_type" data-live-search="true">



                  <option value="">All Listing Type</option>



                  @foreach (App\Models\Producttype::all() as $p_type_filter)



                    <option value="{{$p_type_filter->id}}" @if($p_type_filter->id == Request::get('product_type')) selected @endif>{{ $p_type_filter->product_type_name }}</option>



                  @endforeach;



                </select>



                <button type="submit" id="pro_type" name="pro_type" class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>



            </div> -->



            <div class="col-md-2 ml-auto d-flex">



                <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="availability" id="availability">



                    <option value="">All</option>



                  <option value="availability" @if($proAvailability=='availability') selected @endif>Availability</option>



                </select>



                <button type="submit" id="pro_warehouse"  class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>



            </div>





            <!-- <div class="col-md-2"> -->



                    <!--<select class="form-select" name="search_type" aria-label="Default select example">-->



                    <!--  <option selected>-Select-</option>-->



                    <!--  <option value="products.name">Name</option>-->



                    <!--  <option value="product_stocks.qty">Avaiable</option>-->



                    <!--  <option value="">All</option>-->



                    <!--  <option value="warehouse.name">Warehouse</option>-->



                    <!--</select>-->



                <!-- <div class="form-group mb-0">



                    <input type="text" class="form-control form-control-sm" id="search" name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type & Enter') }}">



                </div>



            </div> -->



        </div>



          <div class="row page_qty_sec product_search_header">



            <div class="col-2 d-flex page_form_sec">



                <label class="fillter_sel_show m-auto"><b>Show</b></label>



                <select class="form-control form-select" id="pagination_use_qty"  aria-label="Default select example">



                  <!-- <option value="10" @if($pagination_qty == 10) selected @endif>10</option> -->



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



            <table class="table aiz-table mb-0" id="ProductDataTable">



                <thead>



                    <tr class="all_product">



                        <th data-orderable="false">



                            <input type="checkbox" class="select_count" id="select_count"  name="all[]">



                        </th>



                        <!--<th data-breakpoints="lg">#</th>-->



                        @foreach ($FilteredData as $filterkey => $Filter)



                          @if($filterkey == 'custom')

                             @if(is_array($Filter))

                                @foreach ($Filter as $fkey => $Fval)

                                    @if(is_array($Fval))

                                        @foreach ($Fval as $finnerkey => $Finnerval)

                                            <th>{{translate($Finnerval)}} ({{$fkey}})</th>

                                        @endforeach

                                     @endif

                                @endforeach

                             @endif

                          @elseif(is_array($Filter))



                            @foreach ($Filter as $Fval)



                              <th>{{translate($Fval)}}</th>



                            @endforeach



                          @else



                          <th>{{translate($Filter)}}</th>



                          @endif



                        @endforeach



                        <th class="text-center" data-orderable="false">



                          Options



                        </th>



                    </tr>

                    <!--

                    <tr>

                        <td></td>

                        @foreach ($FilteredData as $filterkey => $Filter)



                          @if($filterkey == 'custom')

                             @if(is_array($Filter))

                                @foreach ($Filter as $fkey => $Fval)

                                    @if(is_array($Fval))

                                        @foreach ($Fval as $finnerkey => $Finnerval)

                                            <td></td>

                                        @endforeach

                                     @endif

                                @endforeach

                             @endif

                          @elseif(is_array($Filter))



                            @foreach ($Filter as $fkeyIn => $Fval)

                                @if(in_array($filterkey, $searchFieldArr))

                                    @php

                                        $fdatakey = isset($databaseField[$filterkey]) ? $databaseField[$filterkey] : $filterkey;

                                        $valSearchCol = isset($searchCol[$fdatakey]) ? $searchCol[$fdatakey] : '';

                                    @endphp

                                    <td class="no-sort"><input type="text" name="searchCol[{{$fdatakey}}]"value="{{$valSearchCol}}"></td>

                                @else

                                    <td class="no-sort"></td>

                                @endif

                            @endforeach



                          @else



                           @if(in_array($filterkey, $searchFieldArr))

                                @php

                                    $fdatakey = isset($databaseField[$filterkey]) ? $databaseField[$filterkey] : $filterkey;

                                    $valSearchCol = isset($searchCol[$fdatakey]) ? $searchCol[$fdatakey] : '';

                                @endphp

                                    <td class="no-sort"><input type="text" name="searchCol[{{$fdatakey}}]"value="{{$valSearchCol}}"></td>

                                @else

                                    <td class="no-sort"></td>

                                @endif



                          @endif



                        @endforeach

                        <td></td>



                    </tr> -->



                </thead>



                <tbody>



                    @if(count($detailedProductList) > 0)



                        @foreach($detailedProductList as $key => $detailedProduct)



                            <tr>



                          <td class="text-center">



                            <input type="checkbox" class="pro_checkbox" data-id="{{$detailedProduct->id}}" name="all_pro[]" value="{{$detailedProduct->id}}">



                          </td>



                          @foreach ($FilteredData as $filterkey => $Filter)



                              @if($filterkey == 'sku')



                                @php



                                $sku = "";



                                foreach ($detailedProduct->stocks as $key => $stock) {



                                    $sku = $stock->sku;



                                    break;



                                }



                                @endphp



                                <td><a href="{{ route('products.viewproduct',['id'=>$detailedProduct->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{ $sku }}</a></td>



                              @elseif($filterkey == 'qty')



                                  @php



                                  $qty = 0;



                                  foreach ($detailedProduct->stocks as $key => $stock) {



                                      $qty += $stock->qty;



                                  }



                                  @endphp



                                  <td><a href="{{ route('products.viewproduct',['id'=>$detailedProduct->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;"> {{ $qty; }}</a> </td>







                              @elseif($filterkey == 'thumbnail_img')



                                @php



                                $ProImage = uploaded_asset($detailedProduct->thumbnail_img);



                                @endphp



                                <td ><a href="{{ route('products.viewproduct',['id'=>$detailedProduct->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;"> <img src="{{ $ProImage }}" alt="" style="width:60px;"> </td></a>



                                @elseif($filterkey == 'description')



                                  <td> <a href="{{ route('products.viewproduct',['id'=>$detailedProduct->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{ strip_tags($detailedProduct->$filterkey) }} </a> </td>



                                  @elseif($filterkey == 'cost_code')



                                  <td><a href="{{ route('products.viewproduct',['id'=>$detailedProduct->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{number_format($detailedProduct->cost_code , 3, '.', ',')}}</a></td>



                                 @elseif($filterkey == 'msrp')



                                 <td><a href="{{ route('products.viewproduct',['id'=>$detailedProduct->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{money_format("%(#1n", $detailedProduct->msrp)."\n"}}</a></td>















                                 @elseif($filterkey == 'low_stock')



                                 <td><a href="{{ route('products.viewproduct',['id'=>$detailedProduct->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{ $detailedProduct->low_stock}}</a></td>































                                 <!-- <td>{{number_format($detailedProduct->msrp , 3, '.',',')}}</td> -->



                                @elseif($filterkey == 'unit_price')



                                <td><a href="{{ route('products.viewproduct',['id'=>$detailedProduct->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{money_format("%(#1n", $detailedProduct->unit_price)."\n"}}</a></td>



                                 @elseif($filterkey == 'product_cost')

                                 @if(Auth::user()->user_type == 'admin' || in_array('16', json_decode(Auth::user()->staff->role->inner_permissions)))

                                 <td><a href="{{ route('products.viewproduct',['id'=>$detailedProduct->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{money_format("%(#1n", $detailedProduct->product_cost)."\n"}}</a></td>

                                 @endif



                                 @elseif($filterkey == 'product_cost')

                                 @if(Auth::user()->user_type == 'admin' || in_array('16', json_decode(Auth::user()->staff->role->inner_permissions)))

                                 <td><a href="{{ route('products.viewproduct',['id'=>$detailedProduct->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{money_format("%(#1n", $detailedProduct->product_cost)."\n"}}</a></td>

                                 @endif



                                  @elseif($filterkey == 'user_name')



                                 <td><a href="{{ route('products.viewproduct',['id'=>$detailedProduct->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;"> {{$detailedProduct->user_name}}</a></td>



                                 @elseif($filterkey == 'custom')

                                    @php

                                        $proType = isset($detailedProduct->productType) ? $detailedProduct->productType : '';

                                        $proTypeName = isset($proType->product_type_name) ? $proType->product_type_name : '';

                                    @endphp

                                    @if(is_array($Filter))

                                        @foreach ($Filter as $fkey => $Fval)

                                            @if(is_array($Fval))

                                                @foreach ($Fval as $finnerkey => $Finnerval)

                                                    @if($proTypeName !='' && strtolower($proTypeName) == strtolower($fkey))

                                                      <td> {{ $detailedProduct->$finnerkey}}</td>

                                                    @else

                                                     <td></td>

                                                    @endif

                                                @endforeach

                                             @endif

                                        @endforeach

                                     @endif



                                @else







                            @if(is_array($Filter))



                              @foreach ($Filter as $fkey => $Fval)



                              <td><a href="{{ route('products.viewproduct',['id'=>$detailedProduct->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">



                                  @if($detailedProduct->$filterkey != null)



                                    {{ $detailedProduct->$filterkey->$fkey }}



                                  @endif



                                </a>



                              </td>



                              @endforeach



                            @else



                              <td> <a href="{{ route('products.viewproduct',['id'=>$detailedProduct->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">{{$detailedProduct->$filterkey;}} </a></td>



                            @endif



                          @endif



                          @endforeach



                         <!-- <td> {{$detailedProduct->low_stock}} </td> -->



                          <!-- <td><a href="{{ route('products.viewproduct',['id'=>$detailedProduct->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="color:#000;">







                                @if($detailedProduct->low_stock<= 0)



                                    <span class="badge badge-inline badge-danger">Low</span>



                                    @else



                                        {{$detailedProduct->low_stock}}







                                @endif



                                </a>



                            </td> -->







                          <td class="text-right">

                          @if(Auth::user()->user_type == 'admin' || in_array('12', json_decode(Auth::user()->staff->role->inner_permissions)))

                                    <a class="btn btn-soft-success btn-icon btn-circle btn-sm"  href="{{ route('products.viewproduct',['id'=>$detailedProduct->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" target="_blank" title="{{ translate('View') }}">



                                        <i class="las la-eye"></i>



                                    </a>

                                    @endif







                              <a data-toggle="modal" data-camid='{{$detailedProduct->id}}' data-stock="{{$detailedProduct->stock_id}}" class="btn btn-soft-success btn-icon btn-circle btn-sm webcamsubmit"  data-typereq="model" href="#" title="{{ translate('camera') }}">



                              <i class="las la-camera"></i>



                              </a>





                              @if(Auth::user()->user_type == 'admin' || in_array('14', json_decode(Auth::user()->staff->role->inner_permissions)))

                              @if ($type == 'Seller')



                              <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('products.seller.edit', ['id'=>$detailedProduct->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">



                                  <i class="las la-edit"></i>



                              </a>



                              @else



                              <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('products.admin.edit', ['id'=>$detailedProduct->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">



                                  <i class="las la-edit"></i>



                              </a>



                              @endif

                              @endif



                              <!-- <a class="btn btn-soft-warning btn-icon btn-circle btn-sm" href="{{route('products.duplicate', ['id'=>$detailedProduct->id, 'type'=>$type]  )}}" title="{{ translate('Duplicate') }}">



                                  <i class="las la-copy"></i>



                              </a> -->



                              <a class="btn btn-soft-success btn-icon btn-circle btn-sm"  href="{{ route('products.activityproduct',['id'=>$detailedProduct->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" target="_blank" title="{{ translate('Activity') }}">



                                  <i class="las la-history"></i>



                              </a>

                                @if(Auth::user()->user_type == 'admin' || in_array('15', json_decode(Auth::user()->staff->role->inner_permissions)))

                                    <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('products.destroy', $detailedProduct->id)}}" title="{{ translate('Delete') }}">



                                        <i class="las la-trash"></i>



                                    </a>

                                @endif



                          </td>



                          </tr>



                        @endforeach



                    @else



                        <tr><td>No Matching record found</td></tr>



                    @endif



                </tbody>



            </table>



            <div class="aiz-pagination">

              @if($pagination_qty != "all")

              <p>

                Showing {{ $detailedProductList->firstItem() }} to {{ $detailedProductList->lastItem() }} of  {{$detailedProductList->total()}} entries

              </p>

                {{ $detailedProductList->appends(request()->input())->links() }}

                @else

                <p>

                  Showing {{$detailedProductList->count()}} of  {{$detailedProductList->count()}} entries

                </p>

              @endif

            </div>





        </div>



    </form>



</div>















        <!-- Modal -->



        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">



            <div class="modal-dialog modal-lg" role="document">



                <div class="modal-content">



                    <form id="filteredColOpt">



                        <div class="modal-header">



                            <h5 class="modal-title" id="exampleModalLabel">Choose Options</h5>



                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">



                              <span aria-hidden="true">&times;</span>



                            </button>



                        </div>



                        <div class="modal-body">



                            <div class="row">



                                @foreach($allFilteredCOll as $keycol => $valcol)



                                <div class="col-lg-3">



                                  <div class="form-group">



                                    @if(is_array($valcol))



                                        @foreach($valcol as $keyinncol => $valinncol)



                                          @php



                                          $strsela = '';



                                          @endphp



                                          @if(in_array($keycol, $columnSelArr))



                                            @php



                                            $strsela = 'checked';



                                            @endphp



                                          @endif



                                        @endforeach



                                        <input type="checkbox" {{$strsela}} class="filtered_field {{$keycol}}" name="columnArr[{{$keycol}}][{{$keyinncol}}]" value="{{$valinncol}}">&nbsp;&nbsp;{{$valinncol}} &nbsp;&nbsp;



                                    @else



                                        @php



                                        $strsel = '';



                                        @endphp



                                        @if(in_array($keycol, $columnSelArr))



                                          @php



                                          $strsel = 'checked';



                                          @endphp



                                        @endif







                                        <input type="checkbox" {{$strsel}} class="filtered_field {{$keycol}}" name="columnArr[{{$keycol}}]" value="{{$valcol}}">&nbsp;&nbsp;{{$valcol}} &nbsp;&nbsp;



                                    @endif



                                  </div>



                                </div>



                                @endforeach



                              </div>



                            <div class="row">



                                <div class="col-12">



                                    <ul class="nav nav-tabs" id="tabContent">



                                    @foreach ($allCustomArr as $cskeyTab => $csValTab)



                                    <li role="presentation" class="{{ $cskeyTab == 1 ? 'active' : '' }} m-2">



                                      <a href="#home{{ $cskeyTab }}" aria-controls="home" role="tab" data-toggle="tab">{{ $cskeyTab }}</a>



                                    </li>



                                    @endforeach



                                  </ul>



                                    <div class="tab-content">







                                        @foreach ($allCustomArr as $cskeyTab => $csValTab)



                                        <div role="tabpanel" class="tab-pane {{ $cskeyTab == 1 ? 'active' : '' }}" id="home{{ $cskeyTab }}" class="active">



                                          <ul>



                                            @foreach ($csValTab as $cskeycol => $csValCol)



                                                    @php

                                                       $selStr =  isset($FilteredData['custom'][$cskeyTab][$cskeycol]) ? $FilteredData['custom'][$cskeyTab][$cskeycol] : '';

                                                       if($selStr !=""){

                                                            $selStr = 'checked';

                                                       }

                                                    @endphp

                                               <input type="checkbox" {{$selStr}} class="filtered_field {{$cskeycol}}" name="columnArr[custom][{{$cskeyTab}}][{{$cskeycol}}]" value="{{$csValCol}}">&nbsp;&nbsp;{{$csValCol}} &nbsp;&nbsp;



                                            @endforeach



                                          </ul>



                                        </div>



                                        @endforeach



                                    </div>



                                </div>



                            </div>



                            <input type="hidden" name="menu" value="product">



                            <div class="modal-footer">



                                <input type="checkbox" class="default-filter-check" name="default_option" value="1"><span>Default Select</span>



                                <input type="hidden" class="default-filter-check-value" name="menu_default" value='{{$FilteredDefault}}'>



                                <button type="submit" class="btn btn-primary">Save</button>



                            </div>



                        </div>



                     </form>



                </div>



            </div>

        </div>



        <!-- Webcam Modal -->



        <div class="modal fade" id="add_index_product_webcam" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">



          <div class="modal-dialog">



            <div class="modal-content">



              <!-- <div class="modal-header justify-content-start">



                <h5 class="modal-title">Webcam</h5>



              </div> -->



              <div class="modal-body">



                <form name="index-webcam-form" id="index-webcam-form" method="post" action="javascript:void(0)">



                 @csrf



                 <input type="hidden" class="prostockfval">



                 <div class="form-group mb-3">



                   <input type="hidden" class="hiddenids" name="hiddenids" value="">



                   <div id="my_camera"></div>



                    <input type="button" class="btn btn-success my-3 snapShotClicker" value="Take Snapshot" onClick="take_snapshot()">







                    <div id="mi_webcam" ></div>



                 </div>



                 <div class="col-md-8">



                     <div class="input-group mi_custom_uploader" data-toggle="aizuploader" data-type="image" data-multiple="true">



                         <!-- <div class="input-group-prepend">



                             <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>



                         </div> -->



                         <!-- <div class="form-control file-amount">{{ translate('Choose File') }}</div>



                         <input type="hidden" name="photos" value="" class="selected-files">



                     </div> -->



                     <div class="file-preview box sm">



                     </div>



                 </div>







                  <button type="submit" class="btn btn-primary" id="webcamSubmit">Add</button>



              </form>



              </div>



            </div>



          </div>



        </div>



        <!-- Webcam Modal -->



@endsection
@section('modal')
   @include('modals.delete_modal')
@endsection
@section('script')
    <script type="text/javascript">
        $(document).on("change", ".check-all", function() {
            if(this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }
        });
       $(document).ready(function(){
           //$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
        });
        function update_todays_deal(el){
           if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('products.todays_deal') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Todays Deal updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
       }
        function update_published(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('products.published') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Published products updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                   }
            });
       }
        function update_approved(el){
            if(el.checked){
                var approved = 1;
            }
            else{
                var approved = 0;
            }
            $.post('{{ route('products.approved') }}', {
                _token      :   '{{ csrf_token() }}',
                id          :   el.value,
                approved    :   approved
            }, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Product approval update successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
       }
        function update_featured(el){
            if(el.checked){
                var status = 1;
           }
            else{
                var status = 0;
            }
            $.post('{{ route('products.featured') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Featured products updated successfully') }}');
               }
               else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
               }
            });
        }
        function sort_products(el){
           $('#sort_products').submit();
       }
        function bulk_delete() {
            var data = new FormData($('#sort_products')[0]);
            // var proCheckID = [];
  			// 		$.each($("input[name='all_pro[]']:checked"), function(){

  			// 				proCheckID.push($(this).val());
  			// 		});
  			// 		var data =	JSON.stringify(proCheckID);
            //  alert(proexpData);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
                url: "{{route('bulk-product-delete')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response == 1) {
                        location.reload();
                    }
                }
            });
        }
    </script>
   <script>
    $('#filteredColOpt').on('submit', function(e) {
      e.preventDefault();
          $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
          });
          $.ajax({
              url: "{{route('products.FilterAjax')}}",
              type: "POST",
                data: $('#filteredColOpt').serialize(),
              success: function( response ) {
            if(response.success) {
                location.reload();
           }
            // $('#add_ajax_product_seller').modal('hide');
          }
         });
       });
    </script>
    <script type="text/javascript">
  				$(document).ready(function() {
 						$(document).on('click','.pro_checkbox',function(){
  							productCheckbox();
                              productCheckboxExport();
  						});
  				});
  				function productCheckbox(){
            // alert();
  					var proCheckID = [];
  					$.each($("input[name='all_pro[]']:checked"), function(){
  							proCheckID.push($(this).val());
 					});
                      console.log(proCheckID);
  					var proexpData =	JSON.stringify(proCheckID);
            // alert(proexpData);
  					$('#checkox_pro').val(proexpData);
  					if(proCheckID.length > 0){
  						$('#product_export').removeAttr('disabled');
  					}else{
  						$('#product_export').attr('disabled',true);
  					}
            if(proCheckID.length > 0){

						$('#product_delete').removeAttr('disabled');



  						$('#product_delete').addClass('hoverProBtn');



  					}else{



  						$('#product_delete').attr('disabled',true);



              $('#product_delete').removeClass('hoverProBtn');



  					}



  				}



                  function productCheckboxExport(){



            // alert();



  					var proCheckID = [];



  					$.each($("input[name='all_pro[]']:checked"), function(){



  							proCheckID.push($(this).val());







  							// alert(proCheckID);



  					});



  					var proexpData =	JSON.stringify(proCheckID);



  					$('#checkox_pro_export').val(proexpData);



  					if(proCheckID.length > 0){



  						$('#product_export').removeAttr('disabled');



  					}else{



  						$('#product_export').attr('disabled',true);



  					}



                      if(proCheckID.length > 0){



  						$('#product_delete').removeAttr('disabled');



  					}else{



  						$('#product_delete').attr('disabled',true);



  					}



  				}











  					$(document).on('click','.select_count',function() {



  				     if($(this).is(':checked')){



  							 $('.pro_checkbox').prop('checked', true);



  						 }else{



  							 $('.pro_checkbox').prop('checked', false);



  						 }



  						 productCheckbox();



                           productCheckboxExport();



  					});







            $(document).on('click','.default-filter-check', function(){



              if ($(this).prop('checked')==true){







              var FilterDefaultVal = $('.default-filter-check-value').val();



              if(FilterDefaultVal != ''){



              var FilterDefaultValData =  JSON.parse(FilterDefaultVal);



              $('.filtered_field').prop('checked',false);







              console.log(FilterDefaultValData);



              $.each( FilterDefaultValData, function( keyFilter, valFilter ) {



                    $('#filteredColOpt .'+keyFilter).prop('checked',true);



                    console.log(valFilter);



                });



              }



            }else{



                $('.filtered_field').prop('checked',false);



            }



            })



















            // $("#availability").change(function(){



            //     var value = $(this).val();



            //     alert(value);



            // })



            // $("#pagination_qty").change(function(){



            //    var value=$(this).val();



            //       $.ajax({



            //           type:'get',



            //           url:"{{route('products.admin')}}",



            //           success:function(response){



            //               alert(response);



            //           }



            //       });



            // });







  	</script>



    <script language="JavaScript">



     Webcam.set({



         width: 320,



         height: 240,



         image_format: 'jpeg',



         jpeg_quality: 90



     });











    // <!-- Code to handle taking the snapshot and displaying it locally -->



    function take_snapshot() {







       // take snapshot and get image data



       Webcam.snap( function(data_uri) {



           // display mi_webcam in page



           // document.getElementById('mi_webcam').innerHTML =



           //  '<img src="'+data_uri+'"/>';



            $("#mi_webcam").append('<div class="mi_remove_web_img"><input type="hidden" class="camera_image_upload" name="mi_custom_cam_image[]" value="'+data_uri+'"><img class="mi_custom_cam_image"  src="'+data_uri+'" style="margin: 10px 0;"/> <button class="webcamRemove">x</button></div>');



        } );



    }







    $('#mi_webcam').on('click', '.webcamRemove', function(e) {



        e.preventDefault();



        $(this).closest('.mi_remove_web_img').remove();



    });



    $('#index-webcam-form').submit(function(){



      $.ajaxSetup({



        headers: {



            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')



        }



      });



      $.ajax({



        url:"{{ route('products.mi_custom_index_image') }}",



        type: 'post',



        data: $('#index-webcam-form').serialize(),



        success: function( responce ) {



           // console.log(responce.camImageData);



           $('.file-preview').append(responce.camImageData);



           $('[name="photos"]').val(responce.camImageDataID);



           $('#index-webcam-form').trigger("reset");



           $('#add_index_product_webcam').modal('hide');



           $('.btn-primary').removeAttr('disabled',false);



           $('#webcamSubmit').removeAttr('disabled',false);



            location.reload();



        }



      });



    });



    $(document).on('click','.webcamsubmit',function() {



        var ids = $(this).data('camid');



        $('.hiddenids').val(ids);







       var constraints = {video: true};



       navigator.mediaDevices.getUserMedia(constraints)



        .then(function(stream) {



         Webcam.attach( '#my_camera' );



          $('#add_index_product_webcam').modal('show');



        })



        .catch(function(err) {



          alert('Error: Could not access webcam: NotFoundError: Requested device not found');



        });







    });







     $(document).on('click','.search_btn_field',function() {



          prosearchform();



    });







    $(".sort_search_val").keypress(function(e){



        if(e.which == 13) {



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



    /*



    $(document).ready(function() {

      var pageURL = $(location).attr("href");

      if(pageURL == "https://gcijewel.com/admin/products/all"){

        $(".aiz-content-wrapper").addClass("productRedesign");

      }else{

        $(".aiz-content-wrapper").removeClass("productRedesign");

      }

    });

*/





    </script>
    <script>
        $("#barcode_label").on('click',function(){
        var  proId= $("#checkox_pro_export").val();
          alert(proId);
            $.ajax({
                    url: '{{ route('products.BarcodeAjaxLabel') }}',
                    dataType: "json",
                    type: "POST",
                    data:{"_token": "{{ csrf_token() }}","proId": proId },
                    success: function (response)
                    {
                        alert('success');
                    },
                    error :function(){
                        alert('fail');
                    }
                });
        });

       
    </script>



@endsection

