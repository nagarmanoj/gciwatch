@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Edit Seller Information')}}</h5>
</div>

<div class="col-lg-6 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Seller Information')}}</h5>
        </div>

        <div class="card-body">
          <form action="{{ route('sellers.update', $seller->id) }}" method="POST">
                <input name="_method" type="hidden" value="PATCH">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Company Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('company name')}}" id="company" name="company" class="form-control" value="{{$seller->company}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Contact Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Contact Name')}}" id="name" name="name" class="form-control" value="{{$seller->user->name}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Reseller Permit')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Reseller Permit')}}" id="reseller_permit" name="reseller_permit" class="form-control" value="{{$seller->reseller_permit}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Tax ID')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Tax ID')}}" id="tax_id" name="tax_id" class="form-control" value="{{$seller->tax_id}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Contact Number')}}</label>
                    <div class="col-sm-9">
                        <input type="tel" placeholder="{{translate('Contact Number')}}" id="phone" name="phone" class="form-control" value="{{$seller->phone}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Company Address*')}}</label>
                    <div class="col-sm-9">
                        <input type="tel" placeholder="{{translate('Company Address*')}}" id="company_address" name="company_address" class="form-control" value="{{$seller->company_address}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Address*')}}</label>
                    <div class="col-sm-9">
                        <input type="tel" placeholder="{{translate('Address*')}}" id="address" name="address" class="form-control" value="{{$seller->address}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('City*')}}</label>
                    <div class="col-sm-9">
                        <input type="tel" placeholder="{{translate('City*')}}" id="city" name="city" class="form-control" value="{{$seller->city}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('State*')}}</label>
                    <div class="col-sm-9">
                        <input type="tel" placeholder="{{translate('State*')}}" id="state" name="state" class="form-control" value="{{$seller->state}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Postal Code*')}}</label>
                    <div class="col-sm-9">
                        <input type="tel" placeholder="{{translate('Postal Code*')}}" id="postal_code" name="postal_code" class="form-control" value="{{$seller->postal_code}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Country*')}}</label>
                    <div class="col-sm-9">
                        <input type="tel" placeholder="{{translate('Country*')}}" id="country" name="country" class="form-control" value="{{$seller->country}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="email">{{translate('Email Address')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Email Address')}}" id="email" name="email" class="form-control" value="{{$seller->user->email}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="password">{{translate('Password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" placeholder="{{translate('Password')}}" id="password" name="password" class="form-control">
                    </div>
                </div>
                <br>
                <h6>Bank Information</h6>
                <hr>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="AC">{{translate('Account Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Account Name')}}" id="bank_acc_name" name="bank_acc_name" class="form-control" value="{{$seller->bank_acc_name}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="ACH">{{translate('ACH#')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('ACH#')}}" id="ach" name="ach" class="form-control" value="{{$seller->ach}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Routing">{{translate('Routing#')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Routing#')}}" id="bank_routing_no" name="bank_routing_no" class="form-control" value="{{$seller->bank_routing_no}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Account No">{{translate('Account No#')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Account No#')}}" id="bank_acc_no" name="bank_acc_no" class="form-control" value="{{$seller->bank_acc_no}}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="Bank address">{{translate('Bank address')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Bank address')}}" id="bank_address" name="bank_address" class="form-control" value="{{$seller->bank_address}}" required>
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
