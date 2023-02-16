@extends('backend.layouts.app')
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<div class="aiz-titlebar text-left mt-2 mb-3">
</div>
<br>
<div class="card"> 
  <!-- Tabs navs -->
  <ul class="nav nav-tabs nav-fill mb-3" id="ex1" role="tablist" style="display:-webkit-inline-box;">
    <li class="nav-item" role="presentation" style="width:100px !important;" >
      <a class="nav-link active" style="width:100px;" id="ex2-tab-1" data-toggle="tab" href="#a" role="tab" aria-controls="ex2-tabs-1" aria-selected="true">Activities</a>
    </li>
  </ul>
  <!-- Tabs navs -->

  <!-- Tabs content -->
  <div class="tab-content" id="ex2-content">
    <div class="row">  
        <div class="tab-pane fade show active" id="a" role="tabpanel"     aria-labelledby="ex2-tab-1">  
            <div class="col-lg-12">
                <div class="alerts-con"></div>
                <div class="box">
                    <div class="box-header">
                        <h6 class="blue"> {{$jo_order->job_order_number}} </h6>
                    </div>
                    <div class="box-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="tab-content ">
                                    <div class="tab-pane active cls_main_div" id="1">
                                        <table id="CusData" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-condensed table-hover table-striped">
                                            <tbody>
                                                <tr>
                                                    <td class="">
                                                        <div class="cls_img_main_block">
                                                            <div class="text-center">
                                                                <a href="javascript:void()">
                                                                    <img src="https://gciwatch.com/assets/uploads/no_image.png" alt="" style="width:30px; height:30px;">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="cls_store_number">              Stock Id 
                                                            <b>{{$jo_order_detail->stock_id}}</b> is being   repaired under {{$jo_order->job_order_number}} on {{ date("m/d/y", strtotime($jo_order->estimated_date_return) );}}
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="">
                                                        <div class="cls_img_main_block">
                                                            <div class="text-center">
                                                                <a href="javascript:void()">
                                                                    <img src="https://gciwatch.com/assets/uploads/" alt="" style="width:30px; height:30px;">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="cls_store_number">
                                                             Agent <b>{{$jo_order->agent_name}}</b> is working on Job order <b> {{$jo_order->job_order_number}}</b> and Bag Number <b>{{$jo_order->bag_number}} </b>  
                                                        </div>
                                                    </td>
                                                </tr> 
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><div class="clearfix"></div>
            </div>
        </div>
    </div>
  <!-- </div> -->
  <!-- Tabs content -->
</div>
@endsection
@section('modal')
    @include('modals.delete_modal')
@endsection
