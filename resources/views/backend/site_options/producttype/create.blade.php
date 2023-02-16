@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Add New Product type')}}</h5>
</div>
<div class="">
  <form class="p-4" action="{{ route('Producttype.save') }}" method="POST">
      @csrf
      <input type="hidden" name="is_active" value="1">
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Sequence Name">
              {{ translate('Sequence Product Type Name')}}
          </label>
          <div class="col-sm-8">
              <input type="text" placeholder="{{ translate('Product Type Name')}}" id="product_type_name" name="product_type_name" class="form-control" required>
              @error('product_type_name')
              <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
              @enderror
          </div>
      </div>
      <div class="form-group row" id="partner">
          <label class="col-md-3 col-from-label">{{translate('Sequence')}}</label>
          <div class="col-md-8">
              <select class="form-control aiz-selectpicker" name="sequence_id" id="sequence_id" data-live-search="true" required>
                  <option value="">{{ translate('Select Sequence') }}</option>
                  @foreach (\App\Models\Sequence::orderBy('id','ASC')->get() as $sequence)
                  <option value="{{ $sequence->id }}">{{ $sequence->sequence_name }}</option>
                  @endforeach
              </select>
          </div>
      </div>
      <div class="form-group row" id="partner">
          <label class="col-md-3 col-from-label">{{translate('Listing Type')}}</label>
          <div class="col-md-8">
              <select class="form-control aiz-selectpicker" name="listing_type" id="listing_type" data-live-search="true" required>
                  <option value="">{{ translate('Select Listing Type') }}</option>
                  @foreach (\App\SiteOptions::where('option_name', '=', 'listingtype')->get(); as $listing_type)
                  <option value="{{ $listing_type->option_value }}">{{ $listing_type->option_value }}</option>
                  @endforeach
              </select>
          </div>
      </div>
      <div class="form-group row" id="serial_no">
          <label class="col-md-3 col-from-label">{{translate('Serial No.')}}</label>
          <div class="col-md-8">
              <select class="form-control aiz-selectpicker" name="serial_no" id="serial_no" data-live-search="true" required>
                <option value="yes">{{ translate('yes') }}</option>
                <option value="No">{{ translate('No') }}</option>
              </select>
          </div>
      </div>

      <div class="form-group row">
        @for($i=1;$i<=10;$i++)
        <div class="col-md-12 boxCheckItem">
           <div class="row">
              <div class="col-md-3">
                 <div class="form-group">
                    <label >Custom {{$i}}</label>
                  <input type="text" name="custom_{{$i}}" class="form-control input-tip" >
                 </div>
              </div>
              <div class="col-md-3">
                 <div class="form-group">
                    <label for="Field_Type">Field Type</label>
                    <div class="cls_rnd_box">
                       <div class="iradio_square-blue checked" aria-checked="true" aria-disabled="false">
                         <input type="radio"  value="1" name="custom_{{$i}}_type" class="typeboxes" />
                         <p class="static_typ">Static</p>
                       </div>
                       <div class="iradio_square-blue" aria-checked="false" aria-disabled="false">
                         <input type="radio"  value="2" name="custom_{{$i}}_type" class="typeboxes"/>
                         <p class="dynamic_typ">Dynamic</p>
                       </div>
                    </div>
                 </div>
              </div>
              <div class="col-md-5 cls_Dynamic_box">
                 <div class="form-group">
                    <label for="Field_Value">Field Value</label>
                  <textarea class="form-control" name="custom_{{$i}}_value" placeholder="Enter Comma(,) separated values.."></textarea>
                 </div>
              </div>
           </div>
        </div>
        @endfor
      </div>


      <div class="form-group mb-0 text-right">
          <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
      </div>
  </form>
</div>


@endsection
<style media="screen">
/*your custom css goes here*/
.cls_Dynamic_box{
  display: none;
}
.showtypebox{
  display: block !important;
}
p.static_typ {
    margin:auto 15px !important;
    font-size: 14px;
}
.iradio_square-blue {
    display: flex !important;
    align-items: center !important;
    margin-left: 20px;
}
.cls_rnd_box {
    display: flex !important;
    align-items: center !important;
}
p.dynamic_typ {
    margin:auto 15px !important;
}
input#rnd_txt_custom1_Static {
    transform: scale(2.5) !important;
}
input#rnd_txt_custom1_dynamic {
    transform: scale(2.5) !important;
}

</style>
