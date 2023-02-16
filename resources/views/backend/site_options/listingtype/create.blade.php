@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Add New Listing')}}</h5>
</div>
<div class="">
  <form class="p-4" action="{{ route('Listingtype.save') }}" method="POST">
      <input type="hidden" name="option_name" value="listingtype">
      @csrf
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="name">
              {{ translate('Listing Name')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Name')}}" id="option_value" name="option_value" class="form-control" required>
              @error('option_value')
              <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
              @enderror
          </div>
      </div>
      <div class="form-group mb-0 text-right">
          <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
      </div>
  </form>
</div>

@endsection
