@extends('backend.layouts.app')



@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">

    <h5 class="mb-0 h6">{{translate('Add New Sequence')}}</h5>

</div>

<div class="">

  <form class="p-4" action="{{ route('sequence.save') }}" method="POST">

      @csrf

      <div class="form-group row">

          <label class="col-sm-3 col-from-label" for="Sequence Name">

              {{ translate('Sequence Name')}}

          </label>

          <div class="col-sm-9">

              <input type="text" placeholder="{{ translate('Sequence Name')}}" id="sequence_name" name="sequence_name" class="form-control" required>

              @error('sequence_name')

              <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>

              @enderror

          </div>

      </div>

      <div class="form-group row">

          <label class="col-sm-3 col-from-label" for="Sequence Prefix">

              {{ translate('Sequence Prefix')}}

          </label>

          <div class="col-sm-9">

              <input type="text" placeholder="{{ translate('Sequence Prefix')}}" id="sequence_prefix" name="sequence_prefix" class="form-control" required>

          </div>

      </div>

      <div class="form-group row">

          <label class="col-sm-3 col-from-label" for="Sequence Start">

              {{ translate('Sequence Start')}}

          </label>

          <div class="col-sm-9">

              <input type="text" placeholder="{{ translate('Sequence Start')}}" id="sequence_start" name="sequence_start" class="form-control" required>

          </div>

      </div>
      @if(Auth::user()->user_type == 'admin' || in_array('5', json_decode(Auth::user()->staff->role->inner_permissions)))
      <div class="form-group row">

          <label class="col-sm-3 col-from-label" for="Cost Code Multiplier">

              {{ translate('Cost Code Multiplier')}}

          </label>

          <div class="col-sm-9">

              <input type="number" step="0.01" placeholder="{{ translate('Cost Code Multiplier')}}" id="cost_code_multiplier" name="cost_code_multiplier" class="form-control" required>

          </div>

      </div>
    @endif
      <div class="form-group mb-0 text-right">

          <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>

      </div>

  </form>

</div>



@endsection

