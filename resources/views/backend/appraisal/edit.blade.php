@extends('backend.layouts.app')



@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">

    <h5 class="mb-0 h6">{{translate('Edit')}}</h5>

</div>

<div class="">

  <form class="p-4" action="{{route('Appraisal.update',$appraisal->id)}}" method="POST" id="memo_status">

      @csrf

      <div class="mi_memo_cus">

            <div class="col-sm-12 col-md-12 col-lg-6">

                <div class="form-group">

                    <label class="col-from-label" for="notes">

                        {{ translate('Product Type')}}

                    </label>

                    <select name="template_name" id="template" class="form-control aiz-selectpicker" required>

                        <option value="">Select Template</option>

                        <option value="mylux" @if($appraisal->template_name=='mylux') selected @endif>MyLux</option>

                        <option value="mywatch" @if($appraisal->template_name=='mywatch') selected @endif>MyWatch</option>

                    </select>

                </div>

                <div class="form-group" id="partner">

                    <label class="col-from-label">{{translate('Listing Type')}}</label>

                        <select class="form-control aiz-selectpicker" name="listing_type" id="listing_type_id" data-live-search="true" required>

                            <option value="">{{ translate('Select Listing Type') }}</option>

                            @foreach (\App\SiteOptions::where('option_name', '=', 'listingtype')->get(); as $listing_type)

                            <option value="{{ $listing_type->option_value }}"@if($appraisal->listing_type==$listing_type->option_value) selected @endif >{{ $listing_type->option_value }}</option>

                            @endforeach

                        </select>

                </div>

                <div class="form-group" id="unit_price2">

                  <label class="col-from-label" for="notes">

                      {{ translate('Stock Id')}}

                  </label>

                  <select name="stock_id" id="stock_id" class="form-control stock_id_function aiz-selectpicker" data-live-search="true" required>

                  <option value="{{ $appraisal->stock_id }}">{{ $appraisal->stock_id }}</option>

                  </select>

               </div>

            <!-- start  -->

                <div class="form-group" id="cls_stock_details" style="">

                        <input type="hidden" name="appraisal_location" id="appraisal_location" value="Los Angeles, CA 900014" required>

                        <p>

                            <b>Manufacturer :</b>

                            <input type="text" name="manufacturer" id="manufacturer" value="{{$appraisal->manufacturer}}" class="form-control text_input" required>

                        </p>

                        <p>

                            <b>Model Name :</b> 

                            <input type="text" name="model_name" id="model_name" value="{{$appraisal->model_name}}" class="form-control text_input" required>

                        </p>

                        <p>

                            <b>Factory Model :</b>

                            <input type="text" name="factory_model" id="factory_model" value="{{$appraisal->factory_model}}" class="form-control text_input" required>

                        </p>

                        <p>

                            <b>Serial No :</b>

                            <input type="text" name="serial_no" id="serial_no" value="{{$appraisal->serial_no}}" class="form-control text_input" readonly>

                        </p>

                        <p>

                            <b>Size :</b>

                            <input type="text" name="size" id="size" value="{{$appraisal->size}}" class="form-control text_input" required>

                        </p>

                        <p>

                            <b>Dial :</b> 

                            <input type="text" name="dial" id="dial" value="{{$appraisal->dial}}" class="form-control text_input" required>

                        </p>

                        <p>

                            <b>Bezel :</b>

                            <input type="text" name="bazel" id="bazel" value="{{$appraisal->bazel}}" class="form-control text_input" required>

                        </p>

                        <p>

                            <b>Metal :</b>

                            <input type="text" name="metal" id="metal" value="{{$appraisal->metal}}" class="form-control text_input" required>

                        </p>

                        <p>

                            <b>Bracelet Meterial :</b> 

                            <input type="text" name="bracelet_meterial" id="bracelet_meterial" value="{{$appraisal->bracelet_meterial}}" class="form-control text_input" required>

                        </p>

                        <p>

                            <b>Crystal :</b>

                            <input type="text" name="crystal" id="crystal" required="required" class="form-control text_input" data-bv-field="crystal" value="{{$appraisal->crystal}}" required>

                        </p>

                        <br>

                        <p>

                            <b>Suggested Apparaisad Value :</b> 

                            <input type="text" name="sp_value" id="sp_value" required="required" class="form-control text_input" data-bv-field="sp_value" value="{{$appraisal->sp_value}}" required>

                        </p>

                        <p>

                            <b>Image :</b>
                            <!-- <div class="input-group">
                                <div class="camcod">
                                    <div class="d-flex">
                                        <div class="input-group mi_custom_uploader" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose') }}</div>
                                            <input type="hidden" name="image" value="{{$appraisal->image}}" class="selected-files" required>
                                        </div>
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-md-12">

                                <div class="input-group mi_custom_uploader" data-toggle="aizuploader" data-type="image">

                                    <div class="input-group-prepend">

                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>

                                    </div>

                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>

                                    <input type="hidden" name="image" value="{{$appraisal->image}}" class="selected-files">

                                </div>

                                <div class="file-preview box sm">

                                </div>

                                <!-- <small class="text-muted">{{translate('This image is visible in all product box. Use 300x300 sizes image. Keep some blank space around main object of your image as we had to crop some edge in different devices to make it responsive.')}}</small> -->

                            </div>

                        </p>

                        <p><b>Appraised For :</b></p>

                        <p>

                            <b>Name :</b> 

                            <input type="text" name="appraised_name" id="appraised_name" required="required" class="form-control text_input" data-bv-field="appraised_name" value="{{$appraisal->appraised_name}}" required>

                        </p>

                        <p>

                            <b>Address :</b> 

                            <input type="text" name="appraised_address" id="appraised_address" required="required" class="form-control text_input" data-bv-field="appraised_address" value="{{$appraisal->appraised_address}}" required>

                        </p>

                        <p>

                            <b>City :</b> 

                            <input type="text" name="appraisal_city" id="appraisal_city" required="required" class="form-control text_input" data-bv-field="appraisal_city" value="{{$appraisal->appraisal_city}}" required>

                        </p>

                        <p>

                            <b>State :</b> 

                            <input type="text" name="appraisal_state" id="appraisal_state" required="required" class="form-control text_input" data-bv-field="appraisal_state" value="{{$appraisal->appraisal_state}}" required>

                        </p>

                        <p>

                            <b>Zipcode :</b> 

                            <input type="text" name="appraisal_zipcode" id="appraisal_zipcode" required="required" class="form-control text_input" data-bv-field="appraisal_zipcode" value="{{$appraisal->appraisal_zipcode}}" required>

                        </p>

                        <p>

                            <b>Appraisal Number :</b> 

                            <input type="text" name="appraisal_number" id="appraisal_number" required="required" class="form-control text_input" data-bv-field="appraisal_number" value="{{$appraisal->appraisal_number}}" required>

                        </p>

                        <p>

                            <b>Appraisal Location :</b> 

                            Los Angeles, CA 900014

                        </p>

                        <p><input type='hidden' value="{{date('m/d/20y')}} " name="appraisal_date" >

                            <b>Appraisal Date :</b> 

                            {{ date('F  d ,20y ') }}  </p>

                    <small class="help-block" data-bv-validator="notEmpty" data-bv-for="crystal" data-bv-result="NOT_VALIDATED" style="display: none;">Please enter a value</small><small class="help-block" data-bv-validator="notEmpty" data-bv-for="sp_value" data-bv-result="NOT_VALIDATED" style="display: none;">Please enter a value</small><small class="help-block" data-bv-validator="notEmpty" data-bv-for="name" data-bv-result="NOT_VALIDATED" style="display: none;">Please enter a value</small><small class="help-block" data-bv-validator="notEmpty" data-bv-for="address" data-bv-result="NOT_VALIDATED" style="display: none;">Please enter a value</small><small class="help-block" data-bv-validator="notEmpty" data-bv-for="city" data-bv-result="NOT_VALIDATED" style="display: none;">Please enter a value</small><small class="help-block" data-bv-validator="notEmpty" data-bv-for="state" data-bv-result="NOT_VALIDATED" style="display: none;">Please enter a value</small><small class="help-block" data-bv-validator="notEmpty" data-bv-for="zipcode" data-bv-result="NOT_VALIDATED" style="display: none;">Please enter a value</small><small class="help-block" data-bv-validator="notEmpty" data-bv-for="appraisal_number" data-bv-result="NOT_VALIDATED" style="display: none;">Please enter a value</small></div>

                    <!-- <div class="form-group mb-0 text-center"> -->

            <button type="submit" class="btn btn-primary">{{translate('Update')}}</button>

        <!-- </div> -->

            </div>

        </div>

        

    </form>

</div>



@endsection

@section('script')

<script>

      $(document).on('change','#listing_type_id',function() {

        var id =$(this).val();
        //  alert(id);

    $.ajaxSetup({

      headers: {

          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

      }

    });

    $.ajax({

      url:"{{ route('Appraisal_listing.ajax') }}",

      type: 'post',

      dataType: 'json',

      data: {id : id},

      success: function( response ) {

       $('#stock_id').html(response.TagHTML);
       $('#stock_id').selectpicker("refresh");

      }

    });

});

// $("#cls_stock_details").hide();

$(".stock_id_function").on('change',function(){

    var stock_id=$(this).val();

    // alert(stock_id);

    $.ajaxSetup({

      headers: {

          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

      }

    });

    $.ajax({

        type:'post',

        dataType: 'json',

        url:'{{route("Appraisal_stock_id.ajax")}}',

        data:{stock_id : stock_id},

        success:function(response){

            console.log(response);

           if(response.id > 0)

           {

             $('#manufacturer').val(response.manufacturer);

             $('#model_name').val(response.model_name);

             $('#factory_model').val(response.model);

             $('#serial_no').val(response.serial_number);

             $('#size').val(response.size);

             $('#dial').val(response.custom_3);

             $('#bazel').val(response.custom_4);

             $('#metal').val(response.metal);

             $('#bracelet_meterial').val(response.metal);



           }else{

             $('#manufacturer').val(null);

             $('#model_name').val(null);

             $('#factory_model').val(null);

             $('#serial_no').val(null);

             $('#size').val(null);

             $('#dial').val(null);

             $('#bazel').val(null);

             $('#metal').val(null);

             $('#bracelet_meterial').val(null);

           }

        }



    });

});


</script>

@endsection

