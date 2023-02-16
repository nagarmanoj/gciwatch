
<!-- test -->
@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Term Information')}}</h5>
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
            <form class="p-4" action="{{ route('memopayment.update', $memopayment->id) }}" method="POST">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="payment_name">
                        {{ translate('Payment Name')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Payment Name')}}" id="payment_name" name="payment_name" class="form-control"  value="{{ $memopayment->payment_name }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="days">
                        {{ translate('Days')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Days')}}" id="days" name="days" class="form-control" value="{{ $memopayment->days }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="percentage">
                        {{ translate('Percentage')}}
                    </label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{ translate('Percentage')}}" id="percentage" name="percentage" class="form-control"  value="{{ $memopayment->percentage }}" required>
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
