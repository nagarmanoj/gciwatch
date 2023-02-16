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
      <a
        class="nav-link active"
        style="width:100px;"
        id="ex2-tab-1"
        data-toggle="tab"
        href="#a"
        role="tab"
        aria-controls="ex2-tabs-1"
        aria-selected="true"
        >All</a
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

  <!-- Tabs content -->
  <div class="tab-content" id="ex2-content">
    <div
      class="tab-pane fade show active"
      id="a"
      role="tabpanel"
      aria-labelledby="ex2-tab-1">
     <table>
         <tr>
            <td>
                <div class="cls_store_number">
                Stock ID <b><a href="#"> {{$memoInvoiceData->stock_id}} </a></b> was
                 <b> Invoiced SALE{{$memo->memo_number}} </b> from <b> Memo M{{$memo->memo_number}} </b> by Customer <b><a href="#"> {{$memoInvoiceData->customer_name}} </a></b> on <b>{{ date('m/d/20y'),strtotime($memo->updated_at)}}</b> </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="cls_store_number">
                A Memo <b> {{$memo->memo_number}} </b> was created by user <b>{{$memoInvoiceData->customer_name}}</b> on <b> {{ date('m/d/20y'),strtotime($memo->created_at)}}</b></div>
            </td>
        </tr>
     </table>
    </div>
    <div
      class="tab-pane fade"
      id="b"
      role="tabpanel"
      aria-labelledby="ex2-tab-2"
    >
    <table>
         <tr>
            <td>
                <div class="cls_store_number">
                Stock ID <b><a href="#"> {{$memoInvoiceData->stock_id}} </a></b> was
                 <b> Invoiced SALE{{$memo->memo_number}} </b> from <b> Memo {{$memo->memo_number}} </b> by Customer <b><a href="#"> {{$memo->product_type_name}} </a></b> on <b> {{date('m/d/20y'),strtotime($memo->updated_at)}}</b> </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="cls_store_number">
                A Memo <b> {{$memo->memo_number}} </b> was created by user <b> {{$memoInvoiceData->customer_name}} </b> on <b> {{ date('m/d/20y'),strtotime($memo->created_at)}}</b></div>
            </td>
        </tr>
     </table>
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
@endsection
@section('modal')
    @include('modals.delete_modal')
@endsection
