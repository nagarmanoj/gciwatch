@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Add New Seller')}}</h5>
</div>

<div class="col-lg-6 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Seller Information')}}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('sellers.store') }}" method="POST">
            	@csrf
              <div class="form-group row">
                  <label class="col-sm-3 col-from-label" for="name">{{translate('Company Name *')}}</label>
                  <div class="col-sm-9">
                      <input type="text" placeholder="{{translate('company name')}}" id="company" name="company" class="form-control" required>
                  </div>
              </div>
              <div class="form-group row">
                  <label class="col-sm-3 col-from-label" for="name">{{translate('Contact Name *')}}</label>
                  <div class="col-sm-9">
                      <input type="text" placeholder="{{translate('Contact Name')}}" id="name" name="name" class="form-control" required>
                  </div>
              </div>
              <div class="form-group row">
                  <label class="col-sm-3 col-from-label" for="name">{{translate('Reseller Permit')}}</label>
                  <div class="col-sm-9">
                      <input type="text" placeholder="{{translate('Reseller Permit')}}" id="reseller_permit" name="reseller_permit" class="form-control">
                  </div>
              </div>
              <div class="form-group row">
                  <label class="col-sm-3 col-from-label" for="name">{{translate('Tax ID')}}</label>
                  <div class="col-sm-9">
                      <input type="text" placeholder="{{translate('Tax ID')}}" id="tax_id" name="tax_id" class="form-control">
                  </div>
              </div>
              <div class="form-group row">
                  <label class="col-sm-3 col-from-label" for="name">{{translate('Contact Number *')}}</label>
                  <div class="col-sm-9">
                      <input type="tel" placeholder="{{ translate('(___) - ___ - ____')}}" id="phone" name="phone" class="form-control" maxlength="13"  required>
                  </div>
              </div>
              <div class="form-group row">
                  <label class="col-sm-3 col-from-label" for="name">{{translate('Company Address')}}</label>
                  <div class="col-sm-9">
                      <input type="tel" placeholder="{{translate('Company Address')}}" id="company_address" name="company_address" class="form-control" >
                  </div>
              </div>
              <div class="form-group row">
                  <label class="col-sm-3 col-from-label" for="name">{{translate('Address')}}</label>
                  <div class="col-sm-9">
                      <input type="tel" placeholder="{{translate('Address')}}" id="address" name="address" class="form-control" >
                  </div>
              </div>
              <div class="form-group row">
                  <label class="col-sm-3 col-from-label" for="name">{{translate('City')}}</label>
                  <div class="col-sm-9">
                      <input type="tel" placeholder="{{translate('City')}}" id="city" name="city" class="form-control" >
                  </div>
              </div>
              <div class="form-group row">
                  <label class="col-sm-3 col-from-label" for="name">{{translate('State')}}</label>
                  <div class="col-sm-9" id="create_state_id_seller">

                </div>
              </div>

              <div class="form-group row">
                  <label class="col-sm-3 col-from-label" for="name">{{translate('Country')}}</label>
                  <div class="col-sm-9">
                    <select class="form-select form-control aiz-selectpicker" data-live-search="true" id="country_seller" name="country" aria-label="Default select example">
                            <option  value="United States" >US (United States)</option>
                            @foreach($countries_detail as $country)
                            <option  value="{{$country->name}}" >{{$country->code}} ({{$country->name}})</option>
                            @endforeach
                        </select>
                  </div>
              </div>

              <div class="form-group row">
                  <label class="col-sm-3 col-from-label" for="name">{{translate('Postal Code')}}</label>
                  <div class="col-sm-9">
                      <input type="tel" placeholder="{{translate('Postal Code')}}" id="postal_code" name="postal_code" class="form-control" >
                  </div>
              </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="email">{{translate('Email Address *')}}</label>
                    <div class="col-sm-9">
                        <input type="email" placeholder="{{translate('Email Address *')}}" id="email" name="email" class="form-control" required>
                        <span id="span"></span>
                    </div>
                </div>
                <br>
                <h6>Bank Information</h6>
                <hr>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="AC">{{translate('Account Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Account Name')}}" id="bank_acc_name" name="bank_acc_name" class="form-control" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="ACH">{{translate('ACH#')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('ACH#')}}" id="ach" name="ach" class="form-control" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Routing">{{translate('Routing#')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Routing#')}}" id="bank_routing_no" name="bank_routing_no" class="form-control" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Account No">{{translate('Account No#')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Account No#')}}" id="bank_acc_no" name="bank_acc_no" class="form-control" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Bank address">{{translate('Bank address')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Bank address')}}" id="bank_address" name="bank_address" class="form-control" >
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
            </form>
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
});

$('#email').keyup(function(){

var expr = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;;

var validEmail = $(this).val();

if (!expr.test(validEmail)) {
   $('#span').css('color','red');
   $('#span').html('Please enter valid email.');

}
else {
   $('#span').hide();
}
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
</script>
@endsection
