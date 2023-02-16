
<!-- test -->
@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Sequence Information')}}</h5>
</div>

<div class="col-lg-8 mx-auto">
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
            <form class="p-4" action="{{ route('retailreseller.update', $retailreseller->id) }}" method="POST">
                @csrf
                <div class="form-group row" id="customer_group">
                    <label class="col-md-3 col-from-label">{{translate('Customer Group *')}}</label>
                    <div class="col-md-8">
                        <select class="form-control aiz-selectpicker" name="customer_group" id="customer_group" data-live-search="true">
                          <option value="">{{ translate('Select') }}</option>
                          <option value="retail" @if($retailreseller->customer_group == 'retail') selected @endif>{{ translate('Retail') }}</option>
                            <option value="reseller" @if($retailreseller->customer_group == 'reseller') selected @endif>{{ translate('Re-seller') }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row" id="price_group">
                    <label class="col-md-3 col-from-label">{{translate('Price Group *')}}</label>
                    <div class="col-md-8">
                        <select class="form-control aiz-selectpicker" name="price_group" id="price_group" data-live-search="true">
                          <option value="">{{ translate('Select') }}</option>
                          <option value="default" @if($retailreseller->price_group == 'default') selected @endif>{{ translate('Default') }}</option>
                            <option value="CBG" @if($retailreseller->price_group == 'CBG') selected @endif>{{ translate('CBG') }}</option>
                            <option value="IWJG" @if($retailreseller->price_group == 'IWJG') selected @endif>{{ translate('IWJG') }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Company Name *')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Company Name *')}}" id="company" name="company" class="form-control" value="{{ $retailreseller->company }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Office Phone No *')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Office Phone No *')}}" id="office_phone_number" name="office_phone_number" class="form-control" value="{{ $retailreseller->office_phone_number }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Office Address *')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Office Address *')}}" id="office_address" name="office_address" class="form-control" value="{{ $retailreseller->office_address }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('City *')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('City *')}}" id="city" name="city" class="form-control" value="{{ $retailreseller->city }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('State *')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('State *')}}" id="drop_state" name="drop_state" class="form-control" value="{{ $retailreseller->drop_state }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Country *')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Country *')}}" id="country" name="country" class="form-control" value="{{ $retailreseller->country }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Zipcode *')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Zipcode *')}}" id="zipcode" name="zipcode" class="form-control" value="{{ $retailreseller->zipcode }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Contact Name *')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Customer Name *')}}" id="customer_name" name="customer_name" class="form-control" value="{{ $retailreseller->customer_name }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Customer Email *')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Customer Email *')}}" id="email" name="email" class="form-control" value="{{ $retailreseller->email }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Customer Phone No *')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Customer Phone No *')}}" id="phone" name="phone" class="form-control" value="{{ $retailreseller->phone }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Contact Name')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Contact Name')}}" id="contact_name" name="contact_name" class="form-control" value="{{ $retailreseller->contact_name }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Contact Email')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Contact Email')}}" id="contact_email" name="contact_email" class="form-control" value="{{ $retailreseller->contact_email }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Contact Phone No')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Contact Phone No')}}" id="contact_phone_no" name="contact_phone_no" class="form-control" value="{{ $retailreseller->contact_phone_no }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Terms')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Terms')}}" id="terms" name="terms" class="form-control" value="{{ $retailreseller->terms }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Website')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Website')}}" id="website" name="website" class="form-control" value="{{ $retailreseller->website }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Tax ID')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Tax ID')}}" id="tax_id" name="tax_id" class="form-control" value="{{ $retailreseller->tax_id }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Reseller Permit *')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Reseller Permit *')}}" id="text_reseller_permit" name="text_reseller_permit" class="form-control" value="{{ $retailreseller->text_reseller_permit }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Billing Address')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Billing Address')}}" id="billing_address" name="billing_address" class="form-control" value="{{ $retailreseller->billing_address }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Billing City')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Billing City')}}" id="billing_city" name="billing_city" class="form-control" value="{{ $retailreseller->billing_city }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Billing State')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Billing State')}}" id="text_billing_state" name="text_billing_state" class="form-control" value="{{ $retailreseller->text_billing_state }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Billing Country')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Billing Country')}}" id="billing_country" name="billing_country" class="form-control" value="{{ $retailreseller->billing_country }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Billing Zipcode')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Billing Zipcode')}}" id="billing_zipcode" name="billing_zipcode" class="form-control" value="{{ $retailreseller->billing_zipcode }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Shipping Address *')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Shipping Address *')}}" id="shipping_address" name="shipping_address" class="form-control" value="{{ $retailreseller->shipping_address }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Shipping City *')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Shipping City *')}}" id="shipping_city" name="shipping_city" class="form-control" value="{{ $retailreseller->shipping_city }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Shipping State *')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Shipping State *')}}" id="text_shipping_state" name="text_shipping_state" class="form-control" value="{{ $retailreseller->text_shipping_state }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Shipping Country *')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Shipping Country *')}}" id="shipping_country" name="shipping_country" class="form-control" value="{{ $retailreseller->shipping_country }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Customer Group">
                        {{ translate('Shipping Zipcode *')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Shipping Zipcode *')}}" id="shipping_zipcode" name="shipping_zipcode" class="form-control" value="{{ $retailreseller->shipping_zipcode }}" required>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Update Customer')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
