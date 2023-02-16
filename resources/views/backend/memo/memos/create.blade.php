@extends('backend.layouts.app')

@section('content')
<style>
    th.desc_box {
    width: 40%;
}
.all_details_company {
	max-height: 175px;
	height: 175px !important;
	overflow: hidden;
	resize: none;
	color: #000;
}
</style>
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Add New Memo')}}</h5>
</div>
<div class="">
  <form class="p-4" action="{{route('memo.save')}}" method="POST" id="memo_status">
      @csrf
      <input type="hidden" name="memo_status" value="1">
      <input type="hidden" name="isactive" value="1">
      <div class="mi_memo_cus">
      <div class="row">
          <div class="col-md-2">
              <div class="form-group">
               <label class="col-from-label" for="date">Date</label>
               <input type="text" value="{{date('m/d/20y')}}"  id="" name="date" class="form-control" readonly>
             </div>
          </div>
          <div class="col-md-2">
              <div class="form-group">
               <label class="col-from-label" for="reference">
              {{ translate('Reference')}}
               </label>
               <input type="text" placeholder="{{ translate('Reference')}}" id="reference" name="reference" class="form-control" >
              </div>
          </div>
           <div class="col-md-2">
               <div class="form-group">
                 <label class="col-from-label" for="Terms">
                     {{ translate('Terms')}}
                 </label>
                     <select class="form-control" name="payment" id="payment_term">
                       <option value="">Select Term</option>
                       @foreach (App\MemoPayment::orderBy('id','ASC')->get(); as $memo_payment)
                         <option data-days="{{$memo_payment->days}}" value="{{$memo_payment->id}}">{{$memo_payment->payment_name}}</option>
                       @endforeach
                     </select>
               </div>
          </div>
           <div class="col-md-2">
               <div class="form-group">
                 <label class="col-from-label" for="due_date">
                     {{ translate('Due Date')}}
                 </label>
                     <input type="date" placeholder="{{ translate('Due Date')}}"    name="due_date" class="due_date" readonly>
                </div>
          </div>
           <div class="col-md-2">
             <div class="form-group">
                 <label class="col-from-label" for="carrier">
                     {{ translate('Carrier')}}
                 </label>
                     <input type="text" placeholder="{{ translate('Carrier')}}" id="carrier" name="carrier" class="form-control" >
             </div>
          </div>
           <div class="col-md-2">
             <div class="form-group">
                 <label class="col-from-label" for="tracking">
                     {{ translate('Tracking')}}
                 </label>
                     <input type="text" placeholder="{{ translate('Tracking')}}" id="tracking" name="tracking" class="form-control" >
             </div>
          </div>
          <div class="col-md-5">
            <label class="col-from-label" for="customer_name">
                {{ translate('Company Name')}}
            </label>
            <div class="form-group d-flex mb-0 company_customer_fild">
                   <select class="form-control company_name_select company_name_select-customer" id="rr_company_name" name="customer_name" required >

                      <option value="">Select Customer</option>
                      @foreach (App\RetailReseller::orderBy('id','ASC')->get(); as $memo_payment)
                      @if($memo_payment->customer_group=='reseller')
                        <option data-customername="{{$memo_payment->customer_name}}" data-saletax={{$saleTaxdata}} data-customer-group="{{$memo_payment->customer_group}}" data-office_address="{{$memo_payment->office_address}}" data-city="{{$memo_payment->city}}" data-state="{{$memo_payment->drop_state}}" data-zipcode="{{$memo_payment->zipcode}}" data-phone="{{$memo_payment->phone}}" data-email="{{$memo_payment->email}}" value="{{$memo_payment->id}}">{{$memo_payment->company}}</option>
                      @else
                      <option data-customername="{{$memo_payment->customer_name}}" data-saletax={{$saleTaxdata}} data-customer-group="{{$memo_payment->customer_group}}" data-office_address="{{$memo_payment->office_address}}" data-city="{{$memo_payment->city}}" data-state="{{$memo_payment->drop_state}}" data-zipcode="{{$memo_payment->zipcode}}" data-phone="{{$memo_payment->phone}}" data-email="{{$memo_payment->email}}" value="{{$memo_payment->id}}">{{$memo_payment->customer_name}}</option>
                      @endif
                      @endforeach
                    </select>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#companyModal">
                      +
                    </button>

            </div>
            <textarea name="all_details_company" class="form-control all_details_company" readonly></textarea>
          </div>
          <div class="col-md-7">
            <div class="form-group">
                <label class="col-sm-3 col-from-label" for="notes">
                    {{ translate('Notes')}}
                </label>
                    <textarea  placeholder="{{ translate('Notes')}}" id="notes" name="notes" class="form-control"></textarea>
            </div>
          </div>
      </div>
        <hr>
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-2">
                <div class="form-group company_customer_fild">
                    <label class="col-from-label" for="notes">
                        {{ translate('Product Type')}}
                    </label>
                      <select class="form-control"  id="select_product_type" required>
                        <option value="">Select Product Type</option>
                        <option value="stock_id">By Stock</option>
                        <option value="model">By Model</option>
                      </select>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-2">
                  <div class="form-group company_customer_fild">
                    <label class="col-from-label" for="notes">
                        {{ translate('Product')}}
                    </label>
                      <select class="form-control" name="product_id" required id="select_product">
                        <option value="">Select Product</option>
                      </select>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-2">
                <div class="form-group" id="unit_price2">
                    <label class="col-from-label" for="notes">
                        {{ translate('Price')}}
                    </label>
                        <input type="text" placeholder="{{ translate('Price')}}" id="unit_price" class="form-control" readonly>
               </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-2">
             <div class="form-group" >
                <label class="col-from-label" for="notes">
                    {{ translate('Qty')}}
                </label>
                    <input type="number" placeholder="{{ translate('Qty')}}" value="1" id="qty"  class="form-control" >
             </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-2">
             <div class="form-group" >
                <label class="col-from-label" for="notes">
                    {{ translate('Row Total')}}
                </label>
                    <input type="text" placeholder="{{ translate('Row Total')}}" id="row_total" class="form-control" >
             </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-2">
             <div class="form-group mb-0">
                 <br>
            <button type="button" class="btn btn-primary" id="productApend" style="margin-top:5px;" >{{translate('Add Product')}}</button>
            <button type="button" class="btn btn-primary modelOpen" data-toggle="modal" data-target="#add_ajax_model"   id="modelApend" style="margin-top:5px;" >{{translate('Load Products')}}</button>
             </div>
            </div>
        </div>
        </div>

              <div class="box-content">
                <table class="table table-bordered table-hover no-margin item-table freshorder" id="itemtable">
                <thead>
                <tr>
                <th><input type="checkbox" id="creatememoCheckboxid"  name=""> All</th>
                <th>Image</th>
                <th>Stock Id</th>
                <th class="desc_box">Description</th>
                <th>Quantity</th>
                <th>Status</th>
                <th colspan="2">Total Price</th>
                 <!--<th>Remove</th>-->
                </tr></thead>
                <tbody class="tbody cls-product-list">
                <tr id="empty-row">
                <td class="empty" colspan="12">No Product Found</td>
                </tr>
                </tbody>
                <tfoot>
                <tr class="totaltr">
                <td colspan="6" align="right"> Sub Total($)</td>
                <td><input type="text" class="form-control" value="0.00" name="sub_total" id="cls_total" placeholder="Total" readonly/></td>
                 </tr>
                 <tr class="totaltr">
                <td colspan="6" align="right"> Sales Tax($)</td>
                <td class="cls_sale_tax_td"><input type="text" class="form-control" value="0" readonly="" name="sale_tax" id="sale_tax_text" placeholder="Sales Tax" >
                </td>
                 </tr>
                <tr class="totaltr">
                <td colspan="6" align="right"> Shipping($)</td>
                <td class="cls_sale_tax_td"><input type="text" class="form-control" name="shipping_charges" value="0" id="shipping_text" placeholder="Shipping"></td>
                </tr>
                <tr class="totaltr">
                <td colspan="6" align="right"> Total($)</td>
                <td><input type="text" class="form-control" value="0.00" name="order_total" id="cls_total_final" placeholder="Total" readonly /></td>

                 </tr>
                </tfoot>

                </table>
                </div>
                <div class="mi_memo_bttns">
                    <div class="row">
                  <div class="form-group mb-0 text-left ml-4">
                      <button type="submit" class="btn btn-primary" id="create_memo_sub">{{translate('Create Memo')}}</button>
                  </div>
                  <div class="form-group mb-0 text-left ml-4">
                      <button type="button" class="btn btn-primary" id="remove_checked_memo">{{translate('Remove Memo')}}</button>
                  </div>
                  <div class="form-group mb-0 text-left ml-4">
                      <button type="reset" class="btn btn-primary" id="reset_memo_sub">{{translate('Reset Memo')}}</button>
                  </div>
                </div>
                </div>
                  </form>
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
              <select class="form-control" name="customer_group" id="customer_group" required>
                <option value="">{{ translate('Select') }}</option>
                <option value="retail">{{ translate('Retail') }}</option>
                  <option value="reseller">{{ translate('Re-seller') }}</option>
              </select>
          </div>
      </div>
      <div class="form-group row"  id="price_group_html">
          <!-- <label class="col-md-3 col-from-label">{{translate('Price Group *')}}</label>
          <div class="col-md-8" id="price_group_html">
               <select class="form-control" name="price_group" id="price_group" >
                <option value="">{{ translate('Select') }}</option>
                <option value="default">{{ translate('Default') }}</option>
                  <option value="CBG">{{ translate('CBG') }}</option>
                  <option value="IWJG">{{ translate('IWJG') }}</option>
              </select>
          </div> -->
      </div>
      <div class="form-group row" >
          <label class="col-sm-3 col-from-label" for="Customer Group"  id="company_name_required_sign">
              {{ translate('Company Name *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Company Name *')}}" name="company" class="form-control company_id_retail" required>
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" id="office_phone_no_required_sign" for="Customer Group">
              {{ translate('Office Phone No')}}
          </label>
          <div class="col-sm-9" id="office_phone_re">
               <input type="text" placeholder="{{ translate('(___) - ___ - ____')}}" id="office_phone_number" maxlength='13' name="office_phone_number" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Office Address *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Office Address * ')}}" id="office_address" name="office_address" class="form-control" required>
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('City *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('City *')}}" id="city" name="city" class="form-control" required >
          </div>
      </div>

      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('State *')}}
          </label>
          <div class="col-sm-9" id="create_state_id">

          </div>
      </div>

      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Country *')}}
          </label>
          <div class="col-sm-9">
              <select class="form-select form-control aiz-selectpicker" data-live-search="true" id="country" name="country" aria-label="Default select example" required>

                  <option  value="United States" >US (United States)</option>
                  @foreach($allcountry as $country)
                   <option  value="{{$country->name}}">{{$country->code}}({{$country->name}})</option>
                  @endforeach

              </select>
          </div>
      </div>

      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Zipcode *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Zipcode')}}" id="zipcode" name="zipcode" class="form-control" required>
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group" >
              {{ translate('Customer Name *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Customer Name *')}}" id="customer_name" name="customer_name" class="form-control">
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group"  id="customer_emaile_required_sign">
              {{ translate('Customer Email Address ')}}
          </label>
          <div class="col-sm-9">
              <input type="email" placeholder="{{ translate('Customer Address ')}}"  id="Customeremail_one" name="email" class="form-control" ><span id="Customeremail_oneErr"></span>
          </div>
      </div>
      <div class="form-group row">

          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Customer Contact No ')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('(___) - ___ - ____')}}" id="phone" name="phone" maxlength='13' class="form-control"  >
          </div>
      </div>
      <!--<div class="form-group row">-->
      <!--    <label class="col-sm-3 col-from-label" for="Customer Group">-->
      <!--        {{ translate('Contact Name *')}}-->
      <!--    </label>-->
      <!--    <div class="col-sm-9">-->
      <!--        <input type="text" placeholder="{{ translate('Contact Name')}}" id="contact_name" name="contact_name" class="form-control" required >-->
      <!--    </div>-->
      <!--</div>-->
      <!--<div class="form-group row">-->
      <!--    <label class="col-sm-3 col-from-label" for="Customer Group">-->
      <!--        {{ translate('Contact Email *')}}-->
      <!--    </label>-->
      <!--    <div class="col-sm-9">-->
      <!--        <input type="email" placeholder="{{ translate('Contact Email *')}}" id="contact_email"  name="contact_email" class="form-control" required ><span id="Contactemail_oneErr"></span>-->
      <!--    </div>-->
      <!--</div>-->
      <!--<div class="form-group row">-->
      <!--    <label class="col-sm-3 col-from-label" for="Customer Group">-->
      <!--        {{ translate('Contact Phone No *')}}-->
      <!--    </label>-->
      <!--    <div class="col-sm-9">-->
      <!--        <input type="text" placeholder="{{ translate('(___) - ___ - ____')}}"  id="contact_phone_no" name="contact_phone_no" maxlength='13' class="form-control" required >-->
      <!--    </div>-->
      <!--</div>-->
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
              {{ translate('Reseller Permit')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Reseller Permit')}}" id="text_reseller_permit" name="text_reseller_permit" class="form-control" >
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
          <div class="col-sm-9"  id="billing_state">

               <!-- <input type="text" placeholder="{{ translate('Billing State')}}" id="text_billing_state" name="text_billing_state" class="form-control" > -->
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Billing Country')}}
          </label>
          <div class="col-sm-9">
            <select class="form-select form-control aiz-selectpicker" data-live-search="true" id="billing_country" name="billing_country" aria-label="Default select example" required>
                  <option  value="United States" >US (United States)</option>
                  @foreach($allcountry as $country)
                   <option  value="{{$country->name}}">{{$country->code}}({{$country->name}})</option>
                  @endforeach

            </select>
               <!-- <input type="text" placeholder="{{ translate('Billing Country')}}" id="billing_country" name="billing_country" class="form-control" > -->
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
              {{ translate('Shipping Address')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Shipping Address')}}" id="shipping_address" name="shipping_address" class="form-control">
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Shipping City')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Shipping City')}}" id="shipping_city" name="shipping_city" class="form-control">
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Shipping State')}}
          </label>
          <div class="col-sm-9" id="shipping_state_html">
               <!-- <input type="text" placeholder="{{ translate('Shipping State')}}" id="text_shipping_state" name="text_shipping_state" class="form-control" > -->
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Shipping Country')}}
          </label>
          <div class="col-sm-9" id="shipping_country_html">

             <select class="form-select form-control aiz-selectpicker" data-live-search="true" id="shipping_country" name="shipping_country" aria-label="Default select example" required>
                  <option  value="United States" >US (United States)</option>
                  @foreach($allcountry as $country)
                   <option  value="{{$country->name}}">{{$country->code}}({{$country->name}})</option>
                  @endforeach

              </select>
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Shipping Zipcode')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Shipping Zipcode')}}" id="shipping_zipcode" name="shipping_zipcode" class="form-control" >
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

<div class="modal fade" id="add_ajax_model" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header justify-content-start">
        <h5 class="modal-title">PRODUCT LIST</h5>
      </div>
      <div class="addWithModel">
        <div class="modal-body apendPromodelHtml">

        </div>
        <button type="button" class="btn btn-primary m-4 addModeNpro" >Add</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
    //do not interfare here , listen me
document.getElementById('creatememoCheckboxid').onclick = function() {
    var checkboxes = document.getElementsByClassName('removeCheckedMemodata');

    for (var checkbox of checkboxes) {
        checkbox.checked = !checkbox.checked;
        console.log(checkboxes.value);
        alert(checkboxes.value);
    }
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
          alert(response.success);
        $('#rr_company_name').html(response.RetailResellerHTML);
        $('#rr_company_name').selectpicker("refresh");
        $('#ajax-memo-compant-form').trigger("reset");
        $('#companyModal').modal('hide');
        $('#reset_memo_sub').removeAttr('disabled',false);
        $('#create_memo_sub').removeAttr('disabled',false);
        location.reload(true);
      }
     });
   });

</script>
<script type="text/javascript">
$(document).ready(function(){
  $("input[name='phone']").keyup(function() {
    $(this).val($(this).val().replace(/^(\d{3})(\d{3})(\d+)$/, "($1)$2-$3"));
  });
  $("input[name='contact_phone_no']").keyup(function() {

    $(this).val($(this).val().replace(/^(\d{3})(\d{3})(\d+)$/, "($1)$2-$3"));
  });
  $(document).on("keyup","input[name='office_phone_number']", function() {
    $(this).val($(this).val().replace(/^(\d{3})(\d{3})(\d{4})$/, "($1)$2-$3"));
  });
});


$('#Customeremail_one').keyup(function(){

     var expr = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;;

    var validEmail = $(this).val();

    if (!expr.test(validEmail)) {
        $('#Customeremail_oneErr').css('color','red');
        $('#Customeremail_oneErr').html('Please enter valid email.');

    }
    else {
        $('#Customeremail_oneErr').hide();

    }
});

$('#contact_email').keyup(function(){

     var expr = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    var validEmail = $(this).val();

    if (!expr.test(validEmail)) {
        $('#Contactemail_oneErr').css('color','red');
        $('#Contactemail_oneErr').html('Please enter valid email.');

    }
    else {
        $('#Contactemail_oneErr').hide();

    }
});
$(document).ready(function(){
    if($('.company_name_select').length>0){
    $('.company_name_select').select2();
}
});
$(document).ready(function(){
    if($('#select_product').length>0){
    $('#select_product').select2();
}

});
// $("input[name='contact_phone_no']").keyup(function() {
//     $(this).val($(this).val().replace(/^(\d{3})(\d{3})(\d+)$/, "($1)$2-$3"));
//   });

function add_more_customer_choice_option(i, name){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:"POST",
            url:'{{ route('products.add-more-choice-option') }}',
            data:{
               attribute_id: i
            },
            success: function(data) {
                var obj = JSON.parse(data);
                $('#customer_choice_options').append('\
                <div class="form-group row">\
                    <div class="col-md-3">\
                        <input type="hidden" name="choice_no[]" value="'+i+'">\
                        <input type="text" class="form-control" name="choice[]" value="'+name+'" placeholder="{{ translate('Choice Title') }}" readonly>\
                    </div>\
                    <div class="col-md-8">\
                        <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_'+ i +'[]" multiple>\
                            '+obj+'\
                        </select>\
                    </div>\
                </div>');
                AIZ.plugins.bootstrapSelect('refresh');
           }
       });
}




 $(document).on('change','#rr_company_name',function(){
    var customername =  $(this).find(':selected').attr('data-customername');
    var officeaddress =  $(this).find(':selected').attr('data-office_address');
    var city =  $(this).find(':selected').attr('data-city');
    var state =  $(this).find(':selected').attr('data-state');
    var zipcode =  $(this).find(':selected').attr('data-zipcode');
    var phone =  $(this).find(':selected').attr('data-phone');
    var email =  $(this).find(':selected').attr('data-email');

    //  alert(phone);

    var all_details = customername+"\n";
        all_details+= officeaddress+"\n";
        all_details+= city+", "+state+" "+zipcode+"\n";
        all_details+= phone+"\n";
        all_details+= email+"\n";

        $(".all_details_company").text("")
        $(".all_details_company").append(all_details);

  });

</script>
@endsection
