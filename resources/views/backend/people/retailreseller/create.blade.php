@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Add New Retail Or Reseller')}}</h5>
</div>
<div class="">
  <form name="ajax-memo-compant-form" id="ajax-memo-compant-form" method="post" action="{{ route('retailreseller.save') }}">
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
      <div class="form-group row" id="price_group_html">
          <!-- <label class="col-md-3 col-from-label">{{translate('Price Group *')}}</label>
          <div class="col-md-8" >
               <select class="form-control" name="price_group" id="price_group">
                <option value="">{{ translate('Select') }}</option>
                <option value="default">{{ translate('Default') }}</option>
                  <option value="CBG">{{ translate('CBG') }}</option>
                  <option value="IWJG">{{ translate('IWJG') }}</option>
              </select>
          </div> -->
      </div>
      <div class="form-group row" >
          <label class="col-sm-3 col-from-label" for="Customer Group" id="company_name_required_sign">
              {{ translate('Company Name *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Company Name')}}" name="company" class="form-control company_id_retail" required>
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label "  id="office_phone_no_required_sign" for="Customer Group">
              {{ translate('Office Phone No')}}
          </label>
          <div class="col-sm-9" id="office_phone_re">
               <input type="text" placeholder="{{ translate('(__) - __ - ____')}}" id="office_phone_number" maxlength='13' name="office_phone_number" class="form-control" >
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
                  @foreach($countries_detail as $country)
                   <option  value="{{$country->name}}">{{$country->code}} ({{$country->name}})</option>
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
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Customer Name *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Customer Name *')}}" id="customer_name" name="customer_name" class="form-control" required>
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group" id="customer_emaile_required_sign">
              {{ translate('Customer Email Address ')}}
          </label>
          <div class="col-sm-9">
              <input type="email" placeholder="{{ translate('Customer Email Address ')}}"  id="Customeremail_one" name="email" class="form-control" ><span id="Customeremail_oneErr"></span>
          </div>
      </div>
      <div class="form-group row">

          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Customer Contact No * ')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('(__) - __ - ____')}}" id="phone" name="phone" maxlength='13' class="form-control"  required>
          </div>
      </div>
      <!-- <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Contact Name *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Contact Name')}}" id="contact_name" name="contact_name" class="form-control" required >
          </div>
      </div> -->
      <!-- <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Contact Email *')}}
          </label>
          <div class="col-sm-9">
              <input type="email" placeholder="{{ translate('Contact Email *')}}" id="contact_email"  name="contact_email" class="form-control" required ><span id="Contactemail_oneErr"></span>
          </div>
      </div> -->
      <!-- <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Contact Phone No *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('(__) - __ - ____')}}"  id="contact_phone_no" name="contact_phone_no" maxlength='13' class="form-control" required >
          </div>
      </div> -->
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
          <div class="col-sm-9" id="billing_state">
               <!-- <input type="text" placeholder="{{ translate('Billing State')}}" id="text_billing_state" name="text_billing_state" class="form-control" >  -->
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Billing Country')}}
          </label>
          <div class="col-sm-9">
               <!-- <input type="text" placeholder="{{ translate('Billing Country')}}" id="billing_country" name="billing_country" class="form-control" >  -->
               <select class="form-select form-control aiz-selectpicker" data-live-search="true" id="billing_country" name="billing_country" aria-label="Default select example" required>
                  <option  value="United States" >US (United States)</option>
                  @foreach($countries_detail as $country)
                   <option  value="{{$country->name}}">{{$country->code}} ({{$country->name}})</option>
                  @endforeach

              </select>
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
               <!-- <input type="text" placeholder="{{ translate('Shipping State')}}" id="text_shipping_state" name="text_shipping_state" class="form-control" >  -->
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Shipping Country')}}
          </label>
          <div class="col-sm-9">
          <select class="form-select form-control aiz-selectpicker" data-live-search="true" id="shipping_country" name="shipping_country" aria-label="Default select example" required>
                  <option  value="United States" >US (United States)</option>
                  @foreach($countries_detail as $country)
                   <option  value="{{$country->name}}">{{$country->code}} ({{$country->name}})</option>
                  @endforeach

              </select>
          
               <!-- <input type="text" placeholder="{{ translate('Shipping Country')}}" id="shipping_country" name="shipping_country" class="form-control" >  -->
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Shipping Zipcode')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Shipping Zipcode ')}}" id="shipping_zipcode" name="shipping_zipcode" class="form-control" >
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
</div>

@endsection
@section('script')
<script type="text/javascript">
$(document).ready(function(){
  $("input[name='phone']").keyup(function() {
    $(this).val($(this).val().replace(/^(\d{3})(\d{3})(\d+)$/, "($1)$2-$3"));
  });
  $("input[name='contact_phone_no']").keyup(function() {
    $(this).val($(this).val().replace(/^(\d{3})(\d{3})(\d+)$/, "($1)$2-$3"));
  });
  $("input[name='office_phone_number']").keyup(function() {
    $(this).val($(this).val().replace(/^(\d{3})(\d{3})(\d+)$/, "($1)$2-$3"));
  });
});

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



$('#email').keyup(function(){
    var expr = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    var validEmail = $(this).val();
   if (!expr.test(validEmail)) {
        $('#span').css('color','red');
        $('#span').html('Please enter valid email.');

   }
   else 
    {
      $('#span').hide();
    }
});
$('#Customeremail_one').keyup(function(){
    var expr = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    var validEmail = $(this).val();
   if (!expr.test(validEmail)) {
        $('#Customeremail_oneErr').css('color','red');
        $('#Customeremail_oneErr').html('Please enter valid email.');

   }
   else 
    {
      $('#Customeremail_oneErr').hide();
    }
});
</script>
@endsection
