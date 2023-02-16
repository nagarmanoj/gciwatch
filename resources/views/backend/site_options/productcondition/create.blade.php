@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Add New Condition')}}</h5>
</div>
<div class="">
  <form class="p-4" action="{{ route('productcondition.save') }}" method="POST">
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
          <label class="col-sm-3 col-from-label" for="Description">
              {{ translate('Description')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Description')}}" id="description" name="description" class="form-control" required>
          </div>
      </div>
      <div class="form-group mb-0 text-right">
          <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
      </div>
  </form>
</div>

@endsection
