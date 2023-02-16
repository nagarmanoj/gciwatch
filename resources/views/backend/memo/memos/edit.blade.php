@extends('backend.layouts.app')
@section('content')
<style>
.edit-text-area {
	max-height: 175px;
	height: 175px !important;
	overflow: hidden;
	resize: none;
}
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/> -->
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Edit Memo')}}</h5>
</div>
<div class="col-lg-12 mx-auto">


    <div class="card">
        <div class="card-body p-0">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form class="p-4" action="{{route('memo.update', $memo->id)}}" method="POST" id="memo_status">
                @csrf
                <input type="hidden" name="memo_status" value="{{$memo->memo_status}}">

                <input type="hidden" name="isactive" value="1">

                 <div class="mi_memo_cus">
                    <div class="row">
                        <div class="col-md-2">
                         <div class="form-group">
                            <label class="col-from-label" for="date">
                                {{ translate('Date')}}
                            </label>
                                <input type="text" placeholder="{{ translate('Date')}}" id="memo_populated_date" name="date" class="form-control" readonly value="{{date('m/d/20y',strtotime($memo->date))  }}" required>
                         </div>
                        </div>
                        <div class="col-md-2">
                         <div class="form-group">
                            <label class="col-from-label" for="reference">
                                {{ translate('Reference')}}
                            </label>
                                <input type="text" placeholder="{{ translate('Reference')}}" id="reference" name="reference" class="form-control" value="{{ $memo->reference }}" >
                         </div>
                        </div>
                        <div class="col-md-2">
                         <div class="form-group">
                            <label class="col-from-label" for="Terms">
                                {{ translate('Terms')}}
                            </label>
                                <select class="form-control" name="payment">
                                  <option value="">Select Term</option>
                                  @foreach (App\MemoPayment::orderBy('id','ASC')->get(); as $memo_payment)
                                    <option value="{{$memo_payment->id}}" @if($memo->payment ==$memo_payment->id) selected @endif>{{$memo_payment->payment_name}}</option>
                                  @endforeach
                                </select>
                         </div>
                        </div>
                        <div class="col-md-2">
                         <div class="form-group">
                            <label class="col-from-label" for="due_date">
                                {{ translate('Due Date')}}
                            </label>
                                <input type="date" placeholder="{{ translate('Due Date')}}" readonly name="due_date" class="due_date" value="{{ $memo->due_date }}" >
                         </div>
                        </div>
                        <div class="col-md-2">
                         <div class="form-group">
                            <label class="col-from-label" for="carrier">
                                {{ translate('Carrier')}}
                            </label>
                                <input type="text" placeholder="{{ translate('Carrier')}}" id="carrier" name="carrier" class="form-control" value="{{ $memo->carrier }}" >
                         </div>
                        </div>
                        <div class="col-md-2">
                         <div class="form-group">
                            <label class="col-from-label" for="tracking">
                                {{ translate('Tracking')}}
                            </label>
                                <input type="text" placeholder="{{ translate('Tracking')}}" id="tracking" name="tracking" class="form-control" value="{{ $memo->tracking }}" >
                         </div>
                        </div>
                        <div class="col-md-5">
                          <label class="col-from-label" for="customer_name">
                              {{ translate('Company Name')}}
                          </label>
                        <div class="form-group d-flex mb-0">
                               <select class="form-control edit-customer_name" name="customer_name"  required disabled>
                                 @foreach (App\RetailReseller::orderBy('id','ASC')->get(); as $memo_payment)
                                 @if($memo_payment->customer_group)
                                <option data-customernameedit="{{$memo_payment->customer_name}}" data-office_addressedit="{{$memo_payment->office_address}}" data-cityedit="{{$memo_payment->city}}" data-stateedit="{{$memo_payment->drop_state}}" data-zipcodeedit="{{$memo_payment->zipcode}}" data-phoneedit="{{$memo_payment->phone}}" data-emailedit="{{$memo_payment->email}}" value="{{$memo_payment->id}}"  @if($memo->customer_name ==$memo_payment->id) selected @endif>{{$memo_payment->company}}</option>
                                 @else
                                <option data-customernameedit="{{$memo_payment->customer_name}}" data-office_addressedit="{{$memo_payment->office_address}}" data-cityedit="{{$memo_payment->city}}" data-stateedit="{{$memo_payment->drop_state}}" data-zipcodeedit="{{$memo_payment->zipcode}}" data-phoneedit="{{$memo_payment->phone}}" data-emailedit="{{$memo_payment->email}}" value="{{$memo_payment->id}}"  @if($memo->customer_name ==$memo_payment->id) selected @endif>{{$memo_payment->customer_name}}</option>
                                 @endif
                                 @endforeach
                               </select>

                               <button type="button" class="btn btn-primary addComp" data-toggle="modal" data-target="#companyModal">
                                 +
                               </button>
                           </div>
                               <div class='select_customer_textarea'></div>
                        </div>
                        <div class="col-md-7">
                         <div class="form-group">
                            <label class="col-from-label" for="notes">
                                {{ translate('Notes')}}
                            </label>
                                <textarea  placeholder="{{ translate('Notes')}}" id="notes" name="notes" class="form-control">{{ $memo->tracking }}</textarea>
                         </div>
                        </div>
                    </div>
                </div>

<br>
             <div class="iteam_data_table mi_cus_tab">
            <table class="table table-bordered table-hover no-margin item-table freshorder" id="itemtable">
                              <thead>
                                  <tr>
                                  <th><input type="checkbox" id="checkAll" /></th>
                                  <th>Stock Id</th>
                                  <th>C_Code</th>
                                  <th>Desciption</th>
                                  <th>Quantity</th>
                                  <th>Status</th>
                                  <th>Total Price</th>
                              </tr></thead>

                              <tbody class="tbody cls-product-list">

                                @foreach ($memo_details_product as $key => $memo_detail)
                                  @php
                                  if(($memo_detail->name=='New') ||  ($memo_detail->name=='New NS') || ($memo_detail->name=='NNS')  || ($memo_detail->name=='NS')){
                                  $name = 'Unworn';
                                  }else{
                                  $name = 'Preowned';
                                  }
                                  @endphp


                                  <tr>
                                      <td><input class="memocheck"  type="checkbox" name="memocheck[]" value="{{$memo_detail->id}}" onchange="memocheckChanged()"/></td>
                                       <td>{{$memo_detail->stock_id}}</td>
                                       <td>{{$memo_detail->cost_code}}</td>

                                      @if($memo_detail->listing_type=='Watch')
                                          <td>

                                             <span>{{ $name }} : </span>
                                             <span> {{ $memo_detail->model }}-</span>
                                             <span> {{ $memo_detail->sku }}-</span>
                                             <span>{{ $memo_detail->weight }}g-</span>
                                             <span>{{ $memo_detail->custom_6 }}-</span>
                                             <span>{{ $memo_detail->paper_cart }}-</span>
                                             <span>{{ $memo_detail->custom_3 }}-</span>
                                             <span>{{ $memo_detail->custom_4 }}-</span>
                                             <span>{{ $memo_detail->custom_5 }}-</span>
                                         </td>
                                        @endif

                                        @if($memo_detail->listing_type=='Bangle')
                                          <td >
                                             <span>{{ $name }} : </span>
                                             <span> {{ $memo_detail->model }}-</span>
                                             <span> {{ $memo_detail->sku }}-</span>
                                             <span>{{ $memo_detail->weight }}g-</span>
                                             <span>{{ $memo_detail->paper_cart }}</span>
                                         </td>
                                        @endif

                                        @if($memo_detail->listing_type=='Bracelet')
                                          <td>
                                             <span>{{ $name }} : </span>
                                             <span> {{ $memo_detail->model }}-</span>
                                             <span> {{ $memo_detail->sku }}-</span>
                                             <span>{{ $memo_detail->weight }}g</span>
                                         </td>
                                        @endif

                                         @if($memo_detail->listing_type=='Necklace')
                                          <td>
                                         <span>{{$name }} : </span>
                                         <span> {{ $memo_detail->model }}-</span>
                                         <span> {{ $memo_detail->sku }}-</span>
                                         <span>{{ $memo_detail->weight }}g</span>

                                         </td>
                                        @endif

                                        @if($memo_detail->listing_type=='Bezel')
                                          <td>
                                          <span>{{ $name }} : </span>
                                         <span> {{ $memo_detail->model }}-</span>
                                         <span>{{ $memo_detail->weight }}g</span>

                                         </td>
                                        @endif

                                        @if($memo_detail->listing_type=='Dial')
                                          <td>
                                          <span>{{ $name }} : </span>
                                         <span> {{ $memo_detail->model }}-</span>
                                         <span>{{ $memo_detail->metal }}</span>

                                         </td>
                                        @endif
                                        <?php setlocale(LC_MONETARY,"en_US"); ?>

                                       <td>{{$memo_detail->product_qty}}</td>
                                        <td>
                                            @if($memo_detail->item_status=='1' || $memo_detail->item_status=='0')
                                                Memo
                                            @elseif($memo_detail->item_status=='2')
                                               Invoice
                                            @elseif($memo_detail->item_status=='3')
                                            Return
                                            @elseif($memo_detail->item_status=='4')
                                            Trade
                                            @elseif($memo_detail->item_status=='5')
                                            Void
                                            @elseif($memo_detail->item_status=='6')
                                            Trade NGO
                                            @endif
                                        </td>

                                        <td><input type="text" name="row_total" value="<?php  echo money_format("%(#1n", $memo_detail->row_total)."\n";  ?>" readonly></td>

                                  </tr>

                                  @endforeach
                              </tbody>

                              <tfoot>
                                <tr class="totaltr">
                                  <td colspan="6" align="right"> Sub Total($)</td>

                                  <td><input type="text" class="form-control" value="{{$sub_total}}" name="sub_total" id="cls_total" placeholder="Total" readonly/></td>
                                </tr>

                                <tr class="totaltr">
                                  <td colspan="6" align="right"> Sales Tax($)</td>
                                  <td class="cls_sale_tax_td"><input type="text" class="form-control" value="{{ $sale_tax }}" readonly="" name="sale_tax" id="sale_tax_text" placeholder="Sales Tax" >
                                      </td>
                                </tr>

                                <tr class="totaltr">
                                  <td colspan="6" align="right"> Shipping($)</td>
                                  <td class="cls_sale_tax_td"><input type="text" class="form-control" name="shipping_charges" readonly value="{{ $shipping_charges }}" id="shipping_text" placeholder="Shipping"></td>
                                </tr>
                                 <tr class="totaltr">
                                  <td colspan="6" align="right"> Total Amount Paid($)</td>
                                  <td class="cls_sale_tax_td"><input type="text" class="form-control" name="Total_paid" readonly value="{{$totalPaidAmount}}" id="total_paid_id" placeholder=""></td>
                                </tr>

                                <tr class="totaltr">
                                  <td colspan="6" align="right"> Total($)</td>
                                  <td><input type="text" class="form-control" value="{{$remaining_total}}" name="order_total" id="cls_total_final" placeholder="Total" readonly /></td>
                                </tr>
                              </tfoot>
                          </table>


                                <div class="form-group mb-0 text-left" id="memo_edit_buttons">
                                    <button type="submit" class="btn btn-primary " id="update_memo_id">{{translate('Update Memo')}}</button>
                                    <a class="btn btn-outline-info btn-sm" target="_blank" href="{{route('memo.memopdf',['id'=>$memo->id,'status'=>$memo->memo_status])}}" id="ReturnDoc">View Document</a>
                                    <a class="btn btn-outline-warning btn-sm"  data-id="{{$memo->id}}" href="javascript:void(0);" id="email_send_id">Send Email &nbsp;<i class="fa fa-envelope"></i></a>

                                    <div class="memoCheckbtn ml-1" style="display:none;">

                                      <button type="button" data-action="2" class="btn btn-outline-primary memo-handle-action btn-sm">{{translate('Invoice')}}</button>

                                      <button type="button" data-action="3" class="btn btn-outline-primary memo-handle-action btn-sm">{{translate('Return')}}</button>

                                      <button type="button" data-action="4" class="btn btn-outline-primary memo-handle-action btn-sm">{{translate('Trade')}}</button>

                                      <button type="button" data-action="5" class="btn btn-outline-primary memo-handle-action btn-sm">{{translate('Void')}}</button>

                                      <button type="button" data-action="6" class="btn btn-outline-primary memo-handle-action btn-sm">{{translate('Trade NGD')}}</button>

                                    </div>
                                </div>

                                <br />

                                <br />


                                </div>

                            </form>
        </div>
    </div>
</div>
<div class="card">
  <!-- Tabs navs -->
  <ul class="nav nav-tabs nav-fill mb-3" id="ex1" role="tablist" style="display:-webkit-inline-box;">
      <!--<table class="table table-striped">-->
      <!--        <thead>-->
      <!--          <tr>-->
      <!--            <th scope="col">Date</th>-->
      <!--            <th scope="col">Memo Number</th>-->
      <!--            <th scope="col">Paid By</th>-->
      <!--            <th scope="col">Created By</th>-->
      <!--            <th scope="col">Paid Amount</th>-->
      <!--            <th scope="col">Remaining Amount</th>-->
      <!--          </tr>-->
      <!--        </thead>-->
      <!--        <tbody>-->
      <!--            @foreach($memoNumbermodal as $modaldata)-->
      <!--          <tr>-->
      <!--            <th>{{$modaldata->updated_at}}</th>-->
      <!--            <td>{{$modaldata->memo_deposit_num}}</td>-->
      <!--            <td>{{$modaldata->payment_depositePaidBy}}</td>-->
      <!--            <td>{{$modaldata->payment_depositePaidBy}}</td>-->
      <!--            <td>{{$modaldata->payment_depositePaid}}</td>-->
      <!--            <td>{{$modaldata->payment_remain}}</td>-->
      <!--          </tr>-->
      <!--          @endforeach-->

      <!--      </tbody>-->
      <!--      </table>-->
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
        id="ex2-tab-3"
        data-toggle="tab"
        href="#c"
        role="tab"
        aria-controls="ex2-tabs-3"
        aria-selected="false"
        >Notes</a
      >

    <!--</li>-->
  </ul>
  <table>
  	<thead>

  	</thead>
		<tbody>
			@foreach ($memo_details_product as $MDkey => $MDval)
				@php
				$arrSt = array('Memo','Memo_status');

				$activitylogData = App\Activitylog::where('prodcut_id',$MDval->product_id)->whereIn('type',$arrSt)->orderBy('created_at','DESC')->get();
				@endphp
				@if(!empty($activitylogData))
				@foreach($activitylogData as $ActLog)
					<tr>
							<td>
								 <div class="cls_store_number ml-3">
									 @php
									 echo $html = stripslashes($ActLog->activity);
									 @endphp
										{{ \Carbon\Carbon::parse($ActLog->created_at)->format('m/d/Y')}}
								 </div>
							</td>
					</tr>
				@endforeach
				@endif
				@endforeach
			<!-- @if(!empty($activitylogData))
			@foreach($activitylogData as $ActLog)
				<tr>
						<td>
							 <div class="cls_store_number ml-3">
								 @php
								 echo $html = stripslashes($ActLog->activity);
								 @endphp
									{{ \Carbon\Carbon::parse($ActLog->created_at)->format('m/d/Y')}}
							 </div>
						</td>
				</tr>
			@endforeach
			@endif -->
			<!-- @if(!empty($activitylogStatus))
			@foreach($activitylogStatus as $ActLogSt)
				<tr>
						<td>
							 <div class="cls_store_number ml-3">
								 @php
								 echo $html = stripslashes($ActLogSt->activity);
								 @endphp
									{{ \Carbon\Carbon::parse($ActLogSt->created_at)->format('m/d/Y')}}
							 </div>
						</td>
				</tr>
			@endforeach
			@endif
			@if(!empty($activitylogusr))
			@foreach($activitylogusr as $ActLogusr)
				<tr>
						<td>
							 <div class="cls_store_number ml-3">
								 @php
								 echo $html = stripslashes($ActLogusr->activity);
								 @endphp
									{{ \Carbon\Carbon::parse($ActLogusr->created_at)->format('m/d/Y')}}
							 </div>
						</td>
				</tr>
			@endforeach
			@endif -->
		</tbody>
  </table>

  <!-- Tabs content -->
  <div class="tab-content" id="ex2-content">
    <div
      class="tab-pane fade show active"
      id="a"
      role="tabpanel"
      aria-labelledby="ex2-tab-1">
     <!--<table>-->
     <!--    <tr>-->
     <!--       <td>-->
     <!--           <div class="cls_store_number">-->
     <!--           Stock ID <b><a href="#">W{{$memo->stock_id}}</a></b> was-->
     <!--            <b>Invoiced SALE{{$memo->memo_number}}</b> from <b>Memo M{{$memo->memo_number}}</b> by Customer <b><a href="#">{{$memo->customer_name}}</a></b> on <b>{{ $memo->status_updated_date}}</b> </div>-->
     <!--       </td>-->
     <!--   </tr>-->
     <!--   <tr>-->
     <!--       <td>-->
     <!--           <div class="cls_store_number">-->
     <!--           A Memo <b>{{$memo->memo_number}}</b> was created by user <b>{{$memo->customer_name}}</b> on <b>{{ $memo->created_at}}</b></div>-->
     <!--       </td>-->
     <!--   </tr>-->
     <!--</table>-->
      <!-- <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">Date</th>
                  <th scope="col">Memo Number</th>
                  <th scope="col">Paid By</th>
                  <th scope="col">Created By</th>
                  <th scope="col">Paid Amount</th>
                  <th scope="col">Remaining Amount</th>
                </tr>
              </thead>
              <tbody>
                  @foreach($memoNumbermodal as $modaldata)
                <tr>
                  <th>{{date('m/d/20y'),strtotime($modaldata->updated_at)}}</th>
                  <td>{{$modaldata->memo_deposit_num}}</td>
                  <td>{{$modaldata->payment_depositePaidBy}}</td>
                  <td>{{$modaldata->payment_depositePaidBy}}</td>
                  <td>{{$modaldata->payment_depositePaid}}</td>
                  <td>{{$modaldata->payment_remain}}</td>
                </tr>
                @endforeach

            </tbody>
            </table> -->
    </div>
    <div
      class="tab-pane fade"
      id="c"
      role="tabpanel"
      aria-labelledby="ex2-tab-3"
    >
      @foreach($memoNumbermodal as $modaldata)
         <p>{{$modaldata->payment_depositeNotes}}</p> <br>
         <hr>
      @endforeach
    </div>
  </div>
  <!-- Tabs content -->
</div>



<!-- Modal -->
<div class="modal fade" id="companyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add customer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Add New Customer')}}</h5>
</div>
<div class="">
<form name="ajax-memo-compant-form" id="ajax-memo-compant-form" method="post" action="javascript:void(0)">
      @csrf
      <div class="form-group row">
          <label class="col-md-3 col-from-label">{{translate('Customer Group *')}}</label>
          <div class="col-md-8">
              <select class="form-control aiz-selectpicker" name="customer_group" id="customer_group" data-live-search="true" >
                <option value="">{{ translate('Select') }}</option>
                <option value="retail">{{ translate('Retail') }}</option>
                  <option value="reseller">{{ translate('Re-seller') }}</option>
              </select>
          </div>
      </div>
      <div class="form-group row" id="price_group">
          <label class="col-md-3 col-from-label">{{translate('Price Group *')}}</label>
          <div class="col-md-8">
              <select class="form-control aiz-selectpicker" name="price_group" id="price_group" data-live-search="true" >
                <option value="">{{ translate('Select') }}</option>
                <option value="default">{{ translate('Default') }}</option>
                  <option value="CBG">{{ translate('CBG') }}</option>
                  <option value="IWJG">{{ translate('IWJG') }}</option>
              </select>
          </div>
      </div>
      <div class="form-group row" >
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Company Name *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Company Name *')}}" name="company" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Office Phone No *')}}
          </label>
          <div class="col-sm-9" id="office_phone_re">
               <input type="text" placeholder="{{ translate('Office Phone No *')}}" id="office_phone_number" name="office_phone_number" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Office Address *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Office Address *')}}" id="office_address" name="office_address" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('City *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('City *')}}" id="city" name="city" class="form-control" >
          </div>
      </div>

      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('State *')}}
          </label>
          <div class="col-sm-9" id="create_state_id">
              <!--<input type="text" placeholder="{{ translate('State *')}}" id="drop_state" name="drop_state" class="form-control" >-->

              <!--<select class="form-select" id="drop_state" aria-label="Default select example">-->
              <!--  <option selected value="">OpenState</option>-->
              <!--</select>-->
          </div>
      </div>

      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Country *')}}
          </label>
          <div class="col-sm-9">
              <!--<input type="text" placeholder="{{ translate('Country *')}}" id="country" name="country" class="form-control" >-->
              <select class="form-select" id="country" name="country" aria-label="Default select example">

                  <option  value="United States" >US (United States)</option>

              </select>
          </div>
      </div>

      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Zipcode *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Zipcode *')}}" id="zipcode" name="zipcode" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Contact Name *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Customer Name *')}}" id="customer_name" name="customer_name" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Customer Email *')}}
          </label>
          <div class="col-sm-9">
              <input type="email" placeholder="{{ translate('Customer Email *')}}" id="email" name="email" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Customer Phone No *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Customer Phone No *')}}" id="phone" name="phone" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Contact Name')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Contact Name')}}" id="contact_name" name="contact_name" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Contact Email')}}
          </label>
          <div class="col-sm-9">
              <input type="email" placeholder="{{ translate('Contact Email')}}" id="contact_email" name="contact_email" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Contact Phone No')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Contact Phone No')}}" id="contact_phone_no" name="contact_phone_no" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Terms')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Terms')}}" id="terms" name="terms" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Website')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Website')}}" id="website" name="website" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Tax ID')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Tax ID')}}" id="tax_id" name="tax_id" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Reseller Permit *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Reseller Permit *')}}" id="text_reseller_permit" name="text_reseller_permit" class="form-control" >
          </div>
      </div>


      <div class="form-check">
          <input  type="checkbox" class="billing_address_class"  id="billing_address_id">
            Same as Office Address
     </div>


      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Billing Address')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Billing Address')}}" id="billing_address" name="billing_address" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Billing City')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Billing City')}}" id="billing_city" name="billing_city" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Billing State')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Billing State')}}" id="text_billing_state" name="text_billing_state" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Billing Country')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Billing Country')}}" id="billing_country" name="billing_country" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Billing Zipcode')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Billing Zipcode')}}" id="billing_zipcode" name="billing_zipcode" class="form-control" >
          </div>
      </div>

      <div class="form-check">
          <input  type="checkbox" class="shipping_address_class" id="shipping_address_id">
            Same as Office Address
     </div>


      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Shipping Address *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Shipping Address *')}}" id="shipping_address" name="shipping_address" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Shipping City *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Shipping City *')}}" id="shipping_city" name="shipping_city" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Shipping State *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Shipping State *')}}" id="text_shipping_state" name="text_shipping_state" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Shipping Country *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Shipping Country *')}}" id="shipping_country" name="shipping_country" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Shipping Zipcode *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Shipping Zipcode *')}}" id="shipping_zipcode" name="shipping_zipcode" class="form-control" >
          </div>
      </div>
      <div class="form-group mb-0 text-right">
          <button type="submit" class="btn btn-primary">{{translate('Add Customer')}}</button>
      </div>
  </form>
</div>
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>
@endsection
@section('script')
<script>
  $(document).ready(function() {
  $('#email_send_id').click(function() {
    myFunction();
  });




});
function myFunction() {
var dataID = $('#email_send_id').attr('data-id');
        var formData = {
            id: dataID,
            _token: "{{ csrf_token() }}",
        };
        // console.log(formData);

        $.ajax({
            type: 'POST',
            url: "{{route('edit.email')}}",
            data: formData,
            dataType: 'json',
             })
             .done(function( data ) {
              alert( "Data Saved: " + data );
            })
             .fail(function( data ) {
              alert( "Data Not Saved: ");
            })


    }
</script>

<!-- // Company Ajax -->
<script>
$('#ajax-memo-compant-form').on('submit', function(e) {
  e.preventDefault();
  $.ajax({
      url: "{{route('Memo.MemoCompanyAjax')}}",
      type: "POST",
      data: $('#ajax-memo-compant-form').serialize(),
      success: function( response ) {
          alert("success");
        $('#rr_company_name').html(response.RetailResellerHTML);
        $('#rr_company_name').selectpicker("refresh");
        $('#ajax-memo-compant-form').trigger("reset");
        $('#companyModal').modal('hide');
        $('#reset_memo_sub').removeAttr('disabled',false);
        $('#create_memo_sub').removeAttr('disabled',false);
        $('#update_memo_id').removeAttr('disabled',false);
        $('#ReturnDoc').removeAttr('disabled',false);
        $('.memo-handle-action').removeAttr('disabled',false);
        $('.addComp').removeAttr('disabled',false);
        window.location.reload();
      }
     });
   });



</script>


@endsection
