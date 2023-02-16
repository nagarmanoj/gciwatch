

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

            <form class="p-4" action="{{ route('sequence.update', $partners->id) }}" method="POST">

                @csrf

                <div class="form-group row">

                    <label class="col-sm-3 col-from-label" for="Sequence Name">

                        {{ translate('Sequence Name')}}

                    </label>

                    <div class="col-sm-9">

                        <input type="text" placeholder="{{ translate('Sequence Name')}}" id="sequence_name" name="sequence_name" class="form-control" required value="{{ $partners->sequence_name }}">

                    </div>

                </div>

                <div class="form-group row">

                    <label class="col-sm-3 col-from-label" for="Sequence Prefix">

                        {{ translate('Sequence Prefix')}}

                    </label>

                    <div class="col-sm-9">

                        <input type="text" placeholder="{{ translate('Sequence Prefix')}}" id="sequence_prefix" name="sequence_prefix" class="form-control" required value="{{ $partners->sequence_prefix }}">

                    </div>

                </div>

                <div class="form-group row">

                    <label class="col-sm-3 col-from-label" for="Sequence Start">

                        {{ translate('Sequence Start')}}

                    </label>

                    <div class="col-sm-9">

                        <input type="text" placeholder="{{ translate('Sequence Start')}}" id="sequence_start" name="sequence_start" class="form-control" required value="{{ $partners->sequence_start }}">

                    </div>

                </div>
                @if(Auth::user()->user_type == 'admin' || in_array('5', json_decode(Auth::user()->staff->role->inner_permissions)))
                <div class="form-group row">

                    <label class="col-sm-3 col-from-label" for="Cost Code Multiplier">

                        {{ translate('Cost Code Multiplier')}}

                    </label>

                    <div class="col-sm-9">

                        <input type="number" step="0.01"  placeholder="{{ translate('Cost Code Multiplier')}}" id="cost_code_multiplier" name="cost_code_multiplier" class="form-control" required value="{{ $partners->cost_code_multiplier }}">

                    </div>

                </div>
                @endif

                <div class="form-group mb-0 text-right">

                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>

                </div>

            </form>

        </div>

    </div>

</div>



@endsection

