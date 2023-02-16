@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Add New Warehouse')}}</h5>
</div>
<div class="">
  <form class="p-4" action="{{ route('warehouse.save') }}" method="POST">
      @csrf
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Name">
              {{ translate('Name')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Name')}}" id="name" name="name" class="form-control" required>
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Code">
              {{ translate('Description')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Description')}}" id="code" name="code" class="form-control" required>
          </div>
      </div>
      <!-- <div class="form-group row" id="price_group">
          <label class="col-md-3 col-from-label">{{translate('Price Group')}}</label>
          <div class="col-md-8">
              <select class="form-control aiz-selectpicker" name="price_group" id="price_group" data-live-search="true">
                <option value="">{{ translate('Select Price Group') }}</option>
                <option value="default">{{ translate('Default') }}</option>
                <option value="CBG">{{ translate('CBG') }}</option>
                  <option value="IWJG">{{ translate('IWJG') }}</option>
              </select>
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Phone">
              {{ translate('Phone')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Phone')}}" id="phone" name="phone" class="form-control" required>
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Email">
              {{ translate('Email')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Email')}}" id="email" name="email" class="form-control" required>
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Address">
              {{ translate('Address')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Address')}}" id="address" name="address" class="form-control" required>
          </div>
      </div> -->
      <!-- <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Warehouse Map">
              {{ translate('Warehouse Map')}}
          </label>
          <div class="col-sm-9">
              <input type="file" placeholder="{{ translate('Warehouse Map')}}" id="map" name="map" class="form-control" required>
          </div>
      </div> -->
      <!-- <div class="form-group row">
          <label class="col-md-3 col-form-label" for="Warehouse Map">{{translate('Warehouse Map')}}</label>
          <div class="col-md-8">
              <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                  <div class="input-group-prepend">
                      <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                  </div>
                  <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                  <input type="hidden" name="map" class="selected-files">
              </div>
              <div class="file-preview box sm">
              </div>
          </div>
      </div> -->
      <div class="form-group mb-0 text-right">
          <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
      </div>
  </form>
</div>

@endsection
