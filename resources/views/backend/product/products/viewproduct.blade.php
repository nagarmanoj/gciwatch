@extends('backend.layouts.app')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

<style>

    .zoom {

  -webkit-transition: all 2s ease;

      -moz-transition: all 2s ease;

      -ms-transition: all 2s ease;

      transition: all 2s ease;

}

 .zoom:hover {

  -ms-transform: scale(1.5); /* IE 9 */

  -webkit-transform: scale(1.5); /* Safari 3-8 */

  transform: scale(1.5);

}

.zoom {

    display : none;

}



.zoom.active {

    display: block;

}

/**/

.cus_mi_popup .modal-body.thumbnail-list {

	padding: 10px 10px;

}

.cus_mi_popup .modal-content .carousel-control-next, .cus_mi_popup .modal-content .carousel-control-prev{

width:8%;

}

.cus_mi_popup .modal-body .carousel-control-next-icon, .cus_mi_popup .modal-body .carousel-control-prev-icon{

 width:30px;

 height:30px;

-webkit-filter: grayscale(100%) brightness(0);

filter: grayscale(100%) brightness(0);

}

.cus_mi_popup .modal-body .carousel-inner .carousel-item img{

width: 100%;

aspect-ratio: 7.8/7;

object-fit: contain;

}

</style>

<br>

<div class="card">

      <!-- Tabs navs -->

  <ul class="nav nav-tabs nav-fill mb-3" id="ex1" role="tablist" style="display:-webkit-inline-box;">

    <li class="nav-item" role="presentation" style="width:100px !important;" >

      <a

        class="nav-link active"

        style="width:100px;"

        id="ex2-tab-1"

        data-toggle="tab"

        href="#a"

        role="tab"

        aria-controls="ex2-tabs-1"

       aria-selected="true"

        >Product Details</a

      >

    </li>

    <li class="nav-item" role="presentation" style="width:100px !important;">

      <a

        class="nav-link"

        style="width:100px;"

        id="ex2-tab-2"

        data-toggle="tab"

        href="#b"

       role="tab"

        aria-controls="ex2-tabs-2"

        aria-selected="false"

        >Activities</a

      >

    </li>

    <li class="nav-item" role="presentation" style="width:100px !important;">

      <a

        class="nav-link"

        style="width:100px;"

        id="ex2-tab-3"

        data-toggle="tab"

        href="#c"

        role="tab"

        aria-controls="ex2-tabs-3"

        aria-selected="false"

        >Notes</a

      >

    </li>

  </ul>

  <!-- Tabs navs -->

@php

    $photos_array='';

       $gallery_img  = array();

    $proGImg = isset($product->photos)?$product->photos: "";

    if($proGImg != ""){

      $gallery_img=explode(",",trim($proGImg));

    }







    foreach($gallery_img as $photos)



    {



        $photos_array.=uploaded_asset($photos)." , ";



    }



     $img=explode(" , ", $photos_array);







@endphp



  <!-- Tabs content -->



  <style>



.cls_multiimages {

	display: inline-block;

	width: 48%;

	vertical-align: top;

}

.product_main_img img {

	width: 100%;

	object-fit: contain;

	aspect-ratio: 5.5/7;

}

.cls_multiimage_li img {

	width: 100%;

	height: 100%;

	aspect-ratio: 5.8/7;

	object-fit: contain;

}

.cls_multiimage_ul {



	padding: 0px;

	margin-bottom:10px;



}



.btn-group .tip.btn{



    padding: 0.6rem 1.2rem !important;



}



.details_table{



    width:90%;



}

.zoom {

    display : none;

}



.zoom.active {

    display: block;

}



  </style>



  <div class="tab-content" id="ex2-content">



    <div class="tab-pane fade show active" id="a" role="tabpanel" aria-labelledby="ex2-tab-1">



      <div class="box-content">



                <div class="row">



                    <div class="col-lg-12">



                        <p class="introtext">Product Details</p>



                        <div class="row">



                            <div class="col-sm-7">



                                <div class="row">



                                    <div class="col-sm-7 product_main_img mainImg" >

                                         <div class="product_main_img">

                                        <img src="{{ uploaded_asset(isset($product->thumbnail_img)?$product->thumbnail_img:"")}}" alt="W7251" class="img-responsive img-thumbnail">

                                       </div>

                                    </div>



                                        <div class="col-sm-5">





                                        <div class="product_thum_img">

                                        @foreach($img as $r)

                                          @if($r != "")

                                            <div id="" class="cls_multiimages"><ul class="cls_multiimage_ul" style="list-style:none;">

                                                    <li class="cls_multiimage_li ">

                                                        <input type='hidden' class="multi_img" name='card[]' value="{{$r}}">

                                                    <img src="{{$r}}" data-value="{{$r}}" onclick="shoDetailImg('{{$r}}')" alt=""   data-toggle="modal" data-target="#add_ajax_product_unit" class="img-responsive getImageVal img-thumbnail active">

                                                </li>

                                                </ul></div>

                                            @endif

                                        @endforeach

                                        @if(isset($product->video_link) && $product->video_link !='')

                                            <div id="" class="cls_multiimages"style="text-align: center;">

                                                <ul class="cls_multiimage_ul" style="list-style:none;">

                                                    <li class="cls_multiimage_li" style="border-radius: 0.25rem;border: 1px solid #dee2e6;padding: 1.65rem;">

                                                        <a href="{{$product->video_link}}" target="_blank">

                                                            <img src="https://gciwatch.com/assets/video2.png" class="mw-100 size-50px mx-auto ls-is-cached lazyloaded">

                                                        </a>

                                                    </li>

                                                </ul>

                                            </div>

                                            @else

                                            <div id="" class="cls_multiimages">

                                                <ul class="cls_multiimage_ul" style="list-style:none;">

                                                    <li class="cls_multiimage_li">

                                                    <img src="https://gcijewel.com/public/uploads/all/h8d9CwMqkOZXfaRfkVPSMByFlstXrKBvmamgfrkU.jpg"  alt="" class="img-responsive  img-thumbnail">

                                                </li>

                                                </ul>

                                            </div>

                                        @endif

                                     </div>

                                    </div>



                                </div>



                            </div>



                            <div class="col-sm-2" style="padding: 0px 30px 0px 30px;display: none !important;">



                                <img src="{{ uploaded_asset(isset($product->thumbnail_img)?$product->thumbnail_img:"")}}" alt="W7251" class="img-responsive img-thumbnail">



                                <div id="multiimages" class="">



                                    <div class="clearfix"></div>



                                </div>



                                <div class="col-sm-14" style="display: none;">



                                    <img src="{{ uploaded_asset(isset($product->thumbnail_img)?$product->thumbnail_img:"")}}" alt="W7251" class="img-responsive img-thumbnail">



                                    <div id="multiimages" class="">



                                        <div class="clearfix"></div>



                                    </div>



                                </div>



                            </div>



                            @php



                                setlocale(LC_MONETARY,"en_US");



                            @endphp



                            <div class="col-sm-5">



                                <div class="table-responsive details_table">



                                    <table class="table table-borderless table-striped dfTable table-right-left">



                                        <tbody>

                                          @if(!empty($product))



                                        <tr>



                                            <td colspan="2" style="background-color:#FFF;"></td>



                                        </tr>



                                        <tr>



                                            <td>Listing Name</td>



                                            <td><b>{{$product->listing_type}}</b></td>



                                        </tr>



                                        <tr>



                                           <td>Stock ID</td>



                                            <td><b>{{$product->stock_id}}</b></td>

                                            </tr>

                                            @if(Auth::user()->user_type == 'admin' || in_array('16', json_decode(Auth::user()->staff->role->inner_permissions)))

                                            <tr>

                                            <td>Product Cost</td>



                                            <td><b>{{$product->product_cost}}</td>

                                            </tr>

                                            @endif

                                            <tr>

                                            <td>Product Status</td>



                                            <td>

                                              @if(!empty($memo))

                                                @if($memo->item_status == 1)

                                                  <b>Memo</b>

                                                  @elseif($memo->item_status == 2)

                                                  <b>Invoice</b>

                                                  @elseif($memo->item_status == 3)

                                                  <b>Return</b>

                                                  @elseif($memo->item_status == 4)

                                                  <b>Trade</b>

                                                  @elseif($memo->item_status == 5)

                                                  <b>Void</b>

                                                  @elseif($memo->item_status == 6)

                                                  <b>Trade NGD</b>

                                                  @else

                                                  <b> Memo </b>

                                                @endif

                                              @elseif(!empty($JobOrderstatus))

                                              <b> Open Job Order </b>
                                              
                                              @else

                                                <b>

                                                    @if($product->published == 1)

                                                        Available

                                                    @endif

                                                </b>

                                              @endif

                                              </td>

                                            </tr>





                                        <tr>



                                            <td>Condition</td>



                                            <td><b>New NS</b></td>



                                        </tr>



                                        <tr>



                                            <td>Model Number</td>



                                            <td><b>{{$product->model}}</b></td>



                                        </tr>



                                        <tr>



                                            <td>Metal</td>



                                            <td><b>{{$product->metal}}</b></td>



                                        </tr>



                                        <tr>



                                            <td>Serial No</td>



                                            <td><b>{{$product->sku}}</b></td>



                                        </tr>



                                        <tr>



                                            <td>Paper/Cert</td>



                                            <td><b>{{$product->paper_cart}}</b></td>



                                        </tr>



                                        <tr>



                                            <td>C_Code</td>



                                            <td><b>{{number_format($product->cost_code , 3, '.', ',')}}</b></td>



                                        </tr>



                                        <tr>



                                            <td>Retail Price</td>



                                            <td><b>{{money_format("%(#1n", $product->msrp)."\n"}}</b></td>



                                        </tr>



                                            @if($product->ptCS1 != "")

                                            <tr>

                                                <!-- <td>Serial LTR</td> -->

                                                <td>{{$product->ptCS1}}</td>

                                                <td><b>{{$product->custom_1}}</b></td>

                                            </tr>

                                            @endif



                                            @if($product->ptCS2 != "")

                                            <tr>

                                                <!-- <td>Age</td> -->

                                                <td>{{$product->ptCS2}}</td>

                                                <td><b>{{$product->custom_2}} </b></td>

                                            </tr>

                                            @endif





                                            @if($product->ptCS3 != "")

                                            <tr>

                                                <!-- <td>Dial</td> -->

                                                <td>{{$product->ptCS3}}</td>

                                                <td><b>{{$product->custom_3}} </b></td>

                                            </tr>

                                            @endif



                                            @if($product->ptCS4 != "")

                                            <tr>

                                                <!-- <td>Bezel</td> -->

                                                <td>{{$product->ptCS4}}</td>

                                                <td><b>{{$product->custom_4}} </b></td>

                                            </tr>

                                            @endif



                                            @if($product->ptCS5 != "")

                                            <tr>

                                                <!-- <td>Band</td> -->

                                                <td>{{$product->ptCS5}}</td>

                                                <td><b>{{$product->custom_5}}</b> </td>

                                            </tr>

                                            @endif



                                            @if($product->ptCS6 != "")

                                            <tr>

                                                <!-- <td>Screw Count</td> -->

                                                <td>{{$product->ptCS6}}</td>

                                                <td><b>{{$product->custom_6}}</b> </td>

                                            </tr>

                                            @endif



                                            @if($product->ptCS7 != "")

                                            <tr>

                                                <!-- <td>Band Grade</td> -->

                                                <td>{{$product->ptCS7}}</td>

                                                <td><b>@if($product->custom_7!=NULL) {{$product->custom_7}}  @endif</b> </td>

                                            </tr>

                                            @endif



                                        @endif



                                          </tbody>



                                    </table>



                                </div>







                                <div class="buttons">



                                    <div class="btn-group btn-group-justified cls_btn_box_main d-flex">



                                        <ul class="btn_box_ul" style="list-style:none;display: flex;justify-content: space-between;align-items: center;">



                                            <li class="btn_box_li cls_print_btn mr-1">



                                                <div class="btn-group">



                                                    <a href="{{route('products.barcodestorelist', ['id'=>$product->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" class="tip btn btn-primary" title="" data-original-title="Print Barcode/Label">



                                                        <i class="fa fa-print"></i>



                                                        <span class="hidden-sm hidden-xs">Barcode/Label</span>



                                                    </a>



                                                </div>



                                            </li>



                                            <li class="btn_box_li mr-2">



                                                <div class="btn-group" >



                                                    <a href="#" class="tip btn btn-primary" title="" data-original-title="PDF">



                                                        <i class="fa fa-download"></i> <span class="hidden-sm hidden-xs">PDF</span>



                                                    </a>



                                                </div>



                                            </li>


                                            @if(Auth::user()->user_type == 'admin' || in_array('14', json_decode(Auth::user()->staff->role->inner_permissions)))
                                            <li class="btn_box_li mr-2">



                                                <div class="btn-group">



                                                    <a href="{{route('products.admin.edit', ['id'=>$product->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" class="tip btn btn-warning tip" title="" data-original-title="Edit Product">



                                                        <i class="fa fa-edit"></i> <span class="hidden-sm hidden-xs">Edit</span>



                                                    </a>



                                                </div>



                                            </li>
                                                @endif

                                            @if(Auth::user()->user_type == 'admin' || in_array('15', json_decode(Auth::user()->staff->role->inner_permissions)))
                                            <li class="btn_box_li mr-2">



                                                <div class="btn-group">



                                                    <a href="#"  class="tip btn btn-danger tip btn-sm"  title=""  data-html="true" data-placement="top" >



                                                        <i class="fa fa-trash-o"></i> <span class="hidden-sm hidden-xs">Delete</span>



                                                    </a>



                                                </div>



                                            </li>
                                            @endif



                                        </ul>







                                    </div>



                                </div>



                            </div>



                            <!-- <div class="clearfix"></div>



                            <div class="col-sm-12">



                                <div class="row">



                                    <div class="col-sm-5">  </div>



                                    <div class="col-sm-7">   </div>



                                </div>



                            </div> -->



                            <div class="col-sm-12" style="display: none;">



                                <div class="panel panel-primary">



                                    <div class="panel-heading">Product More Details</div>



                                        <div class="panel-body">



                                            <div class="col-sm-4">



                                                <div class="table-responsive">



                                                    <table class="table table-borderless table-striped dfTable table-right-left">



                                                        <tbody>



                                                        <tr>



                                                            <td colspan="2" style="background-color:#FFF;"></td>



                                                        </tr>



                                                        <!-- END CODE FOR CUSTOM FIELDS -->



                                                        </tbody>



                                                    </table>



                                                </div>



                                            </div>



                                        </div>



                                    </div>



                                   </div>



                        </div>



                           <div class="buttons" style="display: none;">



                            <div class="btn-group btn-group-justified">



                                <div class="btn-group">



                                    <a href="https://gciwatch.com/admin/prodctus/print_barcodes/7322" class="tip btn btn-primary" title="" data-original-title="Print Barcode/Label">



                                        <i class="fa fa-print"></i>



                                        <span class="hidden-sm hidden-xs">Print Barcode/Label</span>



                                    </a>



                                </div>



                                <div class="btn-group">



                                    <a href="https://gciwatch.com/admin/products/pdf/7322" class="tip btn btn-primary" title="" data-original-title="PDF">



                                        <i class="fa fa-download"></i> <span class="hidden-sm hidden-xs">PDF</span>



                                    </a>



                                </div>



                                <div class="btn-group">



                                    <a href="https://gciwatch.com/admin/products/edit/7322" class="tip btn btn-warning tip" title="" data-original-title="Edit Product">



                                        <i class="fa fa-edit"></i> <span class="hidden-sm hidden-xs">Edit</span>



                                    </a>



                                </div>



                                <div class="btn-group">



                                    <a href="#" class="tip btn btn-danger bpo" title="" data-content="<div style='width:150px;'><p>Are you sure?</p><a class='btn btn-danger' href='https://gciwatch.com/admin/products/delete/7322'>Yes I'm sure</a> <button class='btn bpo-close'>No</button></div>" data-html="true" data-placement="top" data-original-title="<b>Delete Product</b>">



                                        <i class="fa fa-trash-o"></i> <span class="hidden-sm hidden-xs">Delete</span>



                                    </a>



                                </div>



                            </div>



                        </div>







                        <div class="row">



                            <div class="col-sm-12">



                                <!-- #################################activity_all section################################ -->



                                <div class="box">



                                    <div class="box-content">



                                        <div class="row">



                                            <div class="col-lg-12">



                                                <ul class="nav nav-tabs">



                                                    <li class="active">



                                                      <a href="#1" data-toggle="tab">Activity</a>



                                                    </li>



                                                </ul>



                                                <div class="tab-content ">



                                                    <div class="tab-pane active cls_main_div" id="1">



                                                        <table id="CusData" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-condensed table-hover table-striped">



                                                        <tbody>





                                                                                                                  @if(!empty($proactivitylogclosedByUser))

                                                                                                                  @foreach($proactivitylogclosedByUser as $proActLogclose)

                                                                                                                    <tr>

                                                                                                                      <td>

                                                                                                                        <div class="cls_img_main_block">

                                                                                                                            <div class="text-center">

                                                                                                                                <a href="javascript:void()">

                                                                                                                                <img src="{{ uploaded_asset($product->thumbnail_img)}}" alt="" style="width:30px; height:30px;">

                                                                                                                                </a>

                                                                                                                            </div>

                                                                                                                        </div>

                                                                                                                      </td>

                                                                                                                      <td>



                                                                                                                       <div class="cls_store_number">

                                                                                                                         @php

                                                                                                                         $CUser = \App\User::where(['id' => $proActLogclose->user_id])->first();

                                                                                                                          $Cname = $CUser->name;

                                                                                                                         echo $html = stripslashes($proActLogclose->activity);

                                                                                                                         @endphp

                                                                                                                        {{ \Carbon\Carbon::parse($proActLogclose->created_at)->format('m/d/Y')}} by {{$Cname}}

                                                                                                                       </div>

                                                                                                                      </td>

                                                                                                                  </tr>

                                                                                                                  @endforeach

                                                                                                                  @endif





                                                        @if(!empty($proactivitylog))

                                                        @foreach($proactivitylog as $proActLog)

                                                          <tr>

                                                            <td>

                                                              <div class="cls_img_main_block">

                                                                  <div class="text-center">

                                                                      <a href="javascript:void()">

                                                                      <img src="{{ uploaded_asset($product->thumbnail_img)}}" alt="" style="width:30px; height:30px;">

                                                                      </a>

                                                                  </div>

                                                              </div>

                                                            </td>

                                                            <td>



                                                             <div class="cls_store_number">

                                                               @php

                                                               $CUser = \App\User::where(['id' => $proActLog->user_id])->first();

                                                                $Cname = $CUser->name;

                                                               echo $html = stripslashes($proActLog->activity);

                                                               @endphp

                                                              {{ \Carbon\Carbon::parse($proActLog->created_at)->format('m/d/Y')}} by {{$Cname}}

                                                             </div>

                                                            </td>

                                                        </tr>

                                                        @endforeach

                                                        @endif





                                                    @if(isset($inventoryRun->user))

                                                    @if($inventoryRun->user == $product->user_id)

                                                    <tr>

                                                        <td class="">

                                                            <div class="cls_img_main_block">

                                                                <div class="text-center">

                                                                    <a href="javascript:void()">

                                                                    <img src="{{ uploaded_asset($product->thumbnail_img)}}" alt="" style="width:30px; height:30px;">

                                                                    </a>

                                                                </div>

                                                           </div>

                                                        </td>

                                                        <td>

                                                            <div class="cls_store_number">Added to the Inventory as Stock Id <a href =""><b>{{$product->stock_id}}</b></a> Purchased From <b><a href="#"> {{$product->username}}</a> </b> on {{date('m/d/20y' ,strtotime($product->dop))}}</div>

                                                        </td>

                                                    </tr>

                                                    @endif

                                                    @endif

                                                     <!-- @if(isset($memo->product_id))



                                                    @if($memo->product_id == $product->id)



                                                    <tr>



                                                        <td class="">



                                                            <div class="cls_img_main_block">



                                                                <div class="text-center">



                                                                    <a href="javascript:void()">



                                                                    <img src="{{ uploaded_asset($product->thumbnail_img)}}" alt="" style="width:30px; height:30px;">



                                                                    </a>



                                                                </div>



                                                            </div>



                                                        </td>







                                                        <td>



                                                            <div class="cls_store_number">StockId <a href =""><b>{{$product->stock_id}}</b></a> was Memo To Memo <b> {{$memo->memo_number}} </b>By Customer <a href="">

                                                            @if($memo->customer_group=='reseller')

                                                            <b>{{$memo->company}}</b>

                                                            @else

                                                            <b>{{$memo->customer_name}}</b>

                                                            @endif



                                                        </a> on {{date('m/d/20y' ,strtotime($memo->updated_at))}}</div>



                                                        </td>



                                                    </tr>



                                                    @endif



                                                    @endif -->



                                                    </tbody>



                                                  </table>



                                                    </div>



                                                </div>



                                            </div>



                                        </div>



                                    </div>



                                </div>



                                <!-- ################################# END activity_all section################################ -->



                            </div>



                        </div>



                    </div>



                </div>



            </div>



































































    </div>



    <div



      class="tab-pane fade"



      id="b"



      role="tabpanel"



      aria-labelledby="ex2-tab-2"



    >



    <p>second tap </p>



   </div>



    <div



      class="tab-pane fade"



      id="c"



      role="tabpanel"



      aria-labelledby="ex2-tab-3"



    >



      <p>No Notes Found.</p>



    </div>



  </div>



  <!-- Tabs content -->











</div>







<div class="modal fade cus_mi_popup" id="add_ajax_product_unit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog">

    <div class="modal-content">

      <!-- <div class="modal-header justify-content-start">

        <h5 class="modal-title text-capitalize ml-2" id="exampleModalLabel"></h5>

      </div> -->

      <div class="modal-body thumbnail-list">

        <center>

          <div id="demo" class="carousel slide" data-ride="carousel">

            <div class="carousel-inner">

              @foreach($img as $r)

                @if($r != "")

                  <div class="carousel-item @if($loop->first)active @endif">

                      <img src="{{$r}}" data-value="{{$r}}" onclick="shoDetailImg('{{$r}}')" alt=""   data-toggle="modal" data-target="#add_ajax_product_unit" class="img-responsive  img-thumbnail">

                  </div>

                @endif

              @endforeach

            </div>

            <a class="carousel-control-prev" href="#demo" data-slide="prev">

              <div class="img-carousel-icon">

                <span class="carousel-control-prev-icon"></span>

              </div>

            </a>

            <a class="carousel-control-next" href="#demo" data-slide="next">

              <div class="img-carousel-icon">

                <span class="carousel-control-next-icon"></span>

              </div>

            </a>

          </div>

        </center>

      </div>

    </div>

  </div>

</div>

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

        function shoDetailImg(r)

        {

            var showImg ='<img src="'+r+'" alt="W7251" class="img-responsive img-thumbnail zoom active">';

            $(".mainImg").html(showImg);

        }







        $(document).ready(function(){



          var agenturlVal = $('.agenturlVal').val();



          var joburlVal = $('.joburlVal').val();



          var memourl = $('.memourl').val();



          var csurl = $('.csurl').val();



          var prourl = $('.prourl').val();







          $(".agenturl").each(function() {



              var agenturl = $(this).data('uid');



              var url = '{{ route("agent.activity", ":id") }}';



                  url = url.replace(':id', agenturl);



                  // alert(url);



              $(this).attr("href", url);



          });



          $(".joburl").each(function() {



            var joburl = $(this).data('uid');



            var url = '{{ route("job_orders.edit", ":id") }}';



                url = url.replace(':id', joburl);



                // alert(url);



            $(this).attr("href", url);



          });



          $(".memourl").each(function() {



              var memourl = $(this).data('uid');



              var url = '{{ route("memo.edit", ":id") }}';



                  url = url.replace(':id', memourl);



              $(this).attr("href", url);



          });



          $(".csurl").each(function() {



              var csurl = $(this).data('uid');



              var url = '{{ route("retailreseller.activities", ":id") }}';



                  url = url.replace(':id', csurl);



              $(this).attr("href", url);



          });



        });



        $(document).on("click",".getImageVal",function(){

          var imagVAlArrt = $(this).data("value");

          $(".carousel-item img").each(function() {

            var caroImg =  $(this).data("value");

            if(imagVAlArrt == caroImg){

              $(this).closest(".carousel-item").addClass("active");

            }else{

              $(this).closest(".carousel-item").removeClass("active");

            }

          });

        })



        $(".prourl").each(function() {



            var prourl = $(this).data('uid');



            var url = '{{ route("products.viewproduct", ":id") }}';



                url = url.replace(':id', prourl);



            $(this).attr("href", url);



        });



    </script>



@endsection
