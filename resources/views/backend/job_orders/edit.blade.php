@extends('backend.layouts.app')

@section('content')
<script src="{{ static_asset('assets/js/webcam.min.js') }}" ></script>
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Edit Job Order')}}</h5>
</div>
<div class="">
  <form class="p-4" id="joborderformsubmit" action="{{route('job_orders.update', $jo_order->id)}}" method="POST">
      @csrf
      <div class="form-group row mb-5 pb-5 acCalc">
          <div class="col-lg-12  d-flex mb-4">
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Name">
                    {{ translate('Company Name')}}
                </label></b>
                <div class="input-group">
                  <select class="form-control aiz-selectpicker companyName" name="company_name" id="company_name" data-live-search="true" >
                      <option value="">{{ translate('Select Company Name') }}</option>
                      <option value="0" @if($jo_order->company_name == 0) selected @endif>Stock</option>
                      @foreach (\App\RetailReseller::orderBy('id','ASC')->get() as $seller)
                      <option data-name="{{$seller->customer_name}}" data-number="{{$seller->phone}}" value="{{ $seller->id }}" @if($jo_order->company_name == $seller->id) selected @endif>{{ $seller->company }}</option>




                      @if($seller->customer_group=='reseller')
                      <option data-name="{{$seller->customer_name}}" data-number="{{$seller->phone}}" value="{{ $seller->id }}" @if($jo_order->company_name == $seller->id) selected @endif>{{ $seller->company }}</option>
                      @else
                      <option data-name="{{$seller->customer_name}}" data-number="{{$seller->phone}}" value="{{ $seller->id }}" @if($jo_order->company_name == $seller->id) selected @endif>{{$seller->customer_name}}</option>
                      @endif




                      @endforeach
                  </select>
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#companyModal">
                      +
                    </button>
                    <!-- <input type="text" placeholder="{{ translate('Name')}}" id="name" name="name" class="form-control"> -->
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Contact Person">
                    {{ translate('Contact Person')}}
                </label></b>
                <div class="input-group">
                    <input type="text" placeholder="{{ translate('Contact Person')}}" value="{{$jo_order->contact_person}}" name="contact_person" class="form-control job_co_name">
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Contact Number">
                    {{ translate('Contact Number')}}
                </label></b>
                <div class="input-group">
                    <input type="text" placeholder="{{ translate('Contact Number')}}" value="{{$jo_order->contact_number}}" name="contact_number" class="form-control job_co_number">
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Customer Reference">
                    {{ translate('Customer Reference')}}
                </label></b>
                <div class="input-group">
                    <input type="text" placeholder="{{ translate('Customer Reference')}}" value="{{$jo_order->customer_reference}}" name="customer_reference" class="form-control">
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-12  d-flex mb-4">
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Image Upon Receipt">
                    {{ translate('Image Upon Receipt')}}
                </label></b>
                <div class="input-group mimediasec">
                  <div class="camcod">
                    <div class="input-group mi_custom_uploader" data-toggle="aizuploader" data-type="image" data-multiple="true">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" value="{{$jo_order->image_upon_receipt_job}}" name="image_upon_receipt_job" class="selected-files">
                    </div>
                    <div class="file-preview box sm"></div>
                  </div>
                   <button data-typereq="model" type="button" class="btn btn-primary jobwebcam webcamsubmit" data-toggle="modal" style="height:42px;">
                    <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>
                  </button>
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Estimated Total Cost">
                    {{ translate('Estimated Total Cost')}}
                </label></b>
                <div class="input-group">
                    <input type="text" placeholder="{{ translate('Estimated Total Cost')}}" value="{{$jo_order->estimated_total_cost}}" name="estimated_total_cost" class="form-control estimated_total_cost">
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Estimated Date Return">
                    {{ translate('Estimated Date Return')}}
                </label></b>
                <div class="input-group">
                    <input type="date" placeholder="{{ translate('Estimated Date Return')}}" value="{{$jo_order->estimated_date_return}}" name="estimated_date_return_job" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Status">
                    {{ translate('Status')}}
                </label></b>
                <div class="input-group">
                  <select class="form-control select job_order_status" name="job_order_status">
                      <option value="">Select Status</option>
                      <option value="1" @if($jo_order->job_status == 1) selected @endif>Past Due</option>
                      <option value="2" @if($jo_order->job_status == 2) selected @endif>Open</option>
                      <option value="3" @if($jo_order->job_status == 3) selected @endif>Pending</option>
                      <option value="4" @if($jo_order->job_status == 4) selected @endif>On Hold</option>
                      <option value="5" @if($jo_order->job_status == 5) selected @endif>Closed</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-12  d-flex mb-4">
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Actual Cost">
                    {{ translate('Actual Cost')}}
                </label></b>
                <div class="input-group">
                    <input type="text" placeholder="{{ translate('Actual Cost')}}" value="{{$jo_order->total_actual_cost}}" name="total_actual_cost" class="form-control total_actual_cost total_costcharge" readonly>
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Service Cost">
                    {{ translate('Service Cost')}}
                </label></b>
                <div class="input-group">
                    <input type="text" placeholder="{{ translate('Service Cost')}}" value="{{$jo_order->service_cost}}" name="service_cost" class="form-control service_cost total_costcharge">
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Misc Charges">
                    {{ translate('Misc Charges')}}
                </label></b>
                <div class="input-group">
                    <input type="text" placeholder="{{ translate('Misc Charges')}}" value="{{$jo_order->misc_charge}}"  name="misc_charges" class="form-control misc_charges total_costcharge">
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Misc Charges Notes">
                    {{ translate('Misc Charges Notes')}}
                </label></b>
                <div class="input-group">
                    <input type="text" placeholder="{{ translate('Misc Charges Notes')}}" value="{{$jo_order->misc_charge_notes}}" name="misc_charges_notes" class="form-control">
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-12  d-flex mb-4">
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Total Cost Charge To Customer">
                    {{ translate('Total Cost Charge To Customer')}}
                </label></b>
                <div class="input-group">
                    <input type="text" placeholder="{{ translate('Total Cost Charge To Customer')}}" value="{{$jo_order->total_cost_charged_to_customer}}" name="total_charge_from_customer" id="total_charge_from_customer" class="form-control" readonly>
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Date Returned">
                    {{ translate('Date Returned')}}
                </label></b>
                <div class="input-group">
                    <input type="date" placeholder="{{ translate('Date Returned')}}" value="{{$jo_order->date_returned}}" name="date_returned" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Image Upon Returned">
                    {{ translate('Image Upon Returned')}}
                </label></b>
                <div class="input-group mimediasec">
                  <div class="camcod">
                        <div class="input-group mi_custom_uploader" data-toggle="aizuploader" data-type="image" data-multiple="true">
                          <div class="input-group-prepend">
                              <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                          </div>
                          <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                          <input type="hidden" value="{{$jo_order->image_upon_returned}}"  name="image_upon_returned" class="selected-files">
                      </div>
                        <div class="file-preview box sm"></div>
                  </div>
                  <button data-typereq="model" type="button" class="btn btn-primary jobwebcam webcamsubmit" data-toggle="modal" style="height:42px;">
                    <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
      </div>
      <section class="append_gci_bag">
      @foreach ($jo_order_detail as $job_key => $job_d_data)
      <!-- @php
      print_r($job_d_data);
      @endphp -->

      <div class="form-group row mt-5 pt-5 bag_fs_child trCost" style="background:#c4c4c4;">
          <div class="col-lg-12  d-flex mb-4 micustom_cl_div">
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Bag Number">
                    {{ translate('Bag Number')}}
                </label></b>
                <div class="input-group">
                    <input type="text" placeholder="{{ translate('Bag Number')}}" value="{{$job_d_data->bag_number}}" name="bag_number[]" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Date Forwarded">
                    {{ translate('Date Forwarded')}}
                </label></b>
                <div class="input-group">
                    <input type="date" placeholder="{{ translate('Date Forwarded')}}" value="{{$job_d_data->date_forwarded}}" name="date_forwarded[]" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Listing Type">
                    {{ translate('Listing Type')}}
                </label></b>
                <div class="input-group">
                  <select class="form-control job_listing_type" name="listing_type[]"  data-live-search="true">
                      <option value="">{{ translate('Select Listing Type') }}</option>
                      @foreach (\App\SiteOptions::where('option_name', '=', 'listingtype')->get(); as $listing_type)
                      <option value="{{ $listing_type->option_value }}" @if($job_d_data->item_type == $listing_type->option_value) selected @endif>{{ $listing_type->option_value }}</option>
                      @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Stock ID">
                    {{ translate('Stock ID')}}
                </label></b>
                <div class="input-group">
                  <select class="form-control listingstock_id is_require" name="stock_id[]" required>
                    <option value="{{ $job_d_data->stock_id }}">{{ $job_d_data->stock_id }}</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-12  d-flex mb-4">
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Model number">
                    {{ translate('Model number')}}
                </label></b>
                <div class="input-group">
                    <input type="text" placeholder="{{ translate('Model number')}}" value="{{$job_d_data->model_number}}" name="model_number[]" class="form-control job_model">
                    <input type="hidden" value="{{$job_d_data->jo_product_id}}" name="pid[]" class="form-control job_pid">
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Serial Number">
                    {{ translate('Serial Number')}}
                </label></b>
                <div class="input-group">
                    <input type="text" placeholder="{{ translate('Serial Number')}}" value="{{$job_d_data->serial_number}}" name="serial_number[]" class="form-control job_serial">
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Weight">
                    {{ translate('Weight')}}
                </label></b>
                <div class="input-group">
                    <input type="text" placeholder="{{ translate('Weight')}}" value="{{$job_d_data->weight}}" name="weight[]" class="form-control job_weight">
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Screw Count">
                    {{ translate('Screw Count')}}
                </label></b>
                <div class="input-group">
                    <input type="text" placeholder="{{ translate('Screw Count')}}" value="{{$job_d_data->screw_count}}" name="screw_count[]" class="form-control job_screw">
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-12 mb-4" style="display: contents;">
              <div class="row mi_custome1">
            <div class="col-lg-3 hiddenWatch">
              <div class="form-group">
                <b><label class="col-from-label" for="Watch Test Results Upon Receipt">
                    {{ translate('Watch Test Results Upon Receipt')}}
                </label></b>
                <div class="input-group">
                  <div class="gci_watch_cs mb-2">
                    <span class="cls_span">Rate</span>
                      <input type="text" placeholder="{{ translate('Rate')}}" value="{{$job_d_data->rate}}" name="rate[]" class="form-control">
                  </div>
                  <div class="gci_watch_cs mb-2">
                    <span class="cls_span">BE</span>
                      <input type="text" placeholder="{{ translate('BE')}}" value="{{$job_d_data->b_e}}" name="b_e[]" class="form-control">
                  </div>
                  <div class="gci_watch_cs mb-2">
                    <span class="cls_span">AMP</span>
                      <input type="text" placeholder="{{ translate('AMP')}}" value="{{$job_d_data->amp}}" name="amp[]" class="form-control">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Image Upon Receipt">
                    {{ translate('Image Upon Receipt')}}
                </label></b>
                <div class="input-group mimediasec">
                  <div class="camcod">
                    <div class="input-group mi_custom_uploader" data-toggle="aizuploader" data-type="image" data-multiple="true">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" value="{{$job_d_data->image_upon_receipt}}"  name="image_upon_receipt[]" class="selected-files">
                    </div>
                    <div class="file-preview box sm"></div>
                  </div>
                  <button data-typereq="model" type="button" class="btn btn-primary jobwebcam webcamsubmit" data-toggle="modal" style="height:42px;">
                    <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>
                  </button>
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                @php
                $job_det_tag = json_decode($job_d_data->job_details);
                @endphp
                <b><label class="col-from-label" for="Job Details">
                    {{ translate('Job Details')}}
                </label></b>
                <div class="input-group">
                    <select class="js-job-details-multiple ot-tag" multiple="multiple" name="job_details[{{$job_key}}][]" >
                        <option value="">Select Job Details</option>
                        @foreach ($AlljoeditTag as $tagkey => $tagvalue)
                          <option value="{{$tagkey}}" @if(in_array($tagkey , $job_det_tag)) selected @endif>{{$tagvalue}}</option>
                        @endforeach
                    </select>
                </div>
              </div>
            </div>
            <div class="col-lg-3 tag-oth others-hide">
                <div class="form-group">
                  <b class="text-capitalize">
                    <label class="col-from-label" for="Others">
                      {{ translate('Others')}}
                    </label>
                  </b>
                  <div class="input-group">
                    <textarea name="others[]" class="form-control">{{$job_d_data->others_note}}</textarea>
                  </div>
                </div>
              </div>
            <div class="col-lg-3 hiddenWatch others-hide"></div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Note">
                    {{ translate('Note')}}
                </label></b>
                <div class="input-group">
                  <textarea name="notes[]" class="form-control">{{$job_d_data->notes}}</textarea>
                </div>
              </div>
            </div>
           </div>
          </div>

          <div class="col-lg-12  d-flex mb-4">
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Agent">
                    {{ translate('Agent')}}
                </label></b>
                <div class="input-group">
                  <select class="form-control aiz-selectpicker" name="agent[]" data-live-search="true" >
                      <option value="">{{ translate('Select Agent') }}</option>
                      @foreach (\App\Agent::orderBy('id','ASC')->get() as $agent)
                      <option value="{{ $agent->id }}"  @if($job_d_data->agent == $agent->id) selected @endif>{{ $agent->first_name }}</option>
                      @endforeach
                  </select>
                  </div>
                </div>
              </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Estimated Cost">
                    {{ translate('Estimated Cost')}}
                </label></b>
                <div class="input-group">
                  <input type="text" placeholder="{{ translate('Estimated Cost')}}" value="{{$job_d_data->estimated_cost}}" name="estimated_cost[]" class="form-control estimated_cost">
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Estimated Date Of Return">
                    {{ translate('Estimated Date Of Return')}}
                </label></b>
                <div class="input-group">
                  <input type="date" placeholder="{{ translate('Estimated Date Of Return')}}" value="{{$job_d_data->estimated_date_return}}" name="estimated_date_return[]" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Status">
                    {{ translate('Status')}}
                </label></b>
                <div class="input-group">
                  <select class="form-control select" name="job_status[]">
                      <option value="">Select Status</option>
                      <option value="1" @if($jo_order->job_status == 1) selected @endif>Past Due</option>
                      <option value="2" @if($jo_order->job_status == 2) selected @endif>Open</option>
                      <option value="3" @if($jo_order->job_status == 3) selected @endif>Pending</option>
                      <option value="4" @if($jo_order->job_status == 4) selected @endif>On Hold</option>
                      <option value="5" @if($jo_order->job_status == 5) selected @endif>Closed</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-12  d-flex mb-4">
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Agent Notes">
                    {{ translate('Agent Notes')}}
                </label></b>
                <div class="input-group">
                    <input type="text" placeholder="{{ translate('Agent Notes')}}" value="{{$job_d_data->agent_notes}}" name="agent_notes[]" class="form-control">
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Actual Cost">
                    {{ translate('Actual Cost')}}
                </label></b>
                <div class="input-group">
                    <input type="text"   placeholder="{{ translate('Actual Cost')}}" value="{{$job_d_data->actual_cost}}" name="actual_cost_datails[]" class="form-control calculatCost actual_cost">
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Parts Cost">
                    {{ translate('Parts Cost')}}
                </label></b>
                <div class="input-group">
                    <input type="text"  placeholder="{{ translate('Parts Cost')}}" value="{{$job_d_data->parts_cost}}" name="parts_cost[]" class="form-control calculatCost parts_cost">
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Total Repair Cost">
                    {{ translate('Total Repair Cost')}}
                </label></b>
                <div class="input-group">
                    <input type="text" placeholder="{{ translate('Total Repair Cost')}}" value="{{$job_d_data->total_repair_cost}}" name="total_repair_cost[]" class="form-control job_d_data" readonly>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-12  d-flex mb-4">
            <div class="col-lg-3 hiddenWatch">
              <div class="form-group">
                <b><label class="col-from-label" for="Watch Test Result Upon Returned">
                    {{ translate('Watch Test Result Upon Returned')}}
                </label></b>
                <div class="input-group">
                  <div class="gci_watch_cs mb-2">
                    <span class="cls_span">Rate</span>
                      <input type="text" placeholder="{{ translate('Rate')}}" value="{{$job_d_data->rate_returned}}" name="rate_return[]" class="form-control">
                  </div>
                  <div class="gci_watch_cs mb-2">
                    <span class="cls_span">BE</span>
                      <input type="text" placeholder="{{ translate('BE')}}" value="{{$job_d_data->b_e_returned}}" name="b_e_return[]" class="form-control">
                  </div>
                  <div class="gci_watch_cs mb-2">
                    <span class="cls_span">AMP</span>
                      <input type="text" placeholder="{{ translate('AMP')}}" value="{{$job_d_data->amp_returned}}" name="amp_return[]" class="form-control">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Image Upon Returned">
                    {{ translate('Image Upon Returned')}}
                </label></b>
                <div class="input-group mimediasec">
                  <div class="camcod">
                    <div class="input-group mi_custom_uploader" data-toggle="aizuploader" data-type="image" data-multiple="true">
                          <div class="input-group-prepend">
                              <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                          </div>
                          <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                          <input type="hidden" value="{{$job_d_data->image_upon_returned_details}}" name="image_upon_returned_details[]" class="selected-files">
                      </div>
                    <div class="file-preview box sm"> </div>
                  </div>
                  <button data-typereq="model" type="button" class="btn btn-primary jobwebcam webcamsubmit" data-toggle="modal" style="height:42px;">
                    <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>
                  </button>
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <b><label class="col-from-label" for="Date Of Return">
                    {{ translate('Date Of Return')}}
                </label></b>
                <div class="input-group">
                    <input type="date" placeholder="{{ translate('Date Of Return')}}" value="{{$job_d_data->date_of_return}}" name="date_of_return[]" class="form-control">
                </div>
              </div>
            </div>
          </div>
          <input type="hidden" name="job_detail_id[]" value="{{$job_d_data->id}}">
          <div class="col-lg-12 form-group mb-2 text-right addremovebtn">
              <button type="button" class='btn btn-danger removesec'>{{translate('Remove')}}</button>
              <button type="button" class="btn btn-success" id="update_bag_number">{{translate('Update Bag Number')}}</button>
          </div>
      </div>

    @endforeach
      </section>
    <div class="col-lg-12 form-group mb-0 text-center">
        <button type="button" class="btn btn-success add_items">{{translate('Add Bag')}}</button>
    </div>
    <div class="col-lg-12 form-group mx-4 text-left">
       <input type="hidden" name="makeinvoice" class="makeinvoice" value="">
        <!-- <input type="checkbox" name="makeinvoice" value="1" @if($jo_order->makeinvoice == 1) checked @endif>
        <label for="">Check to create invoice</label> -->
    </div>
      <div class="form-group mb-0 text-left">
          <button type="button" class="btn btn-primary updatejo">{{translate('Update')}}</button>
          <button type="button" class="btn btn-primary" onclick="micustomPrint()" ><i class="las la-print"></i> Print</button>
      </div>
  </form>
  <section class="append_gci_bag_append" style="display: none;">
<div class="form-group row mt-5 pt-5 bag_fs_child_append trCost" style="background:#c4c4c4;">
    <div class="col-lg-12  d-flex mb-4 micustom_cl_div">
      <div class="col-lg-3">
        <div class="form-group">
          <b><label class="col-from-label" for="Bag Number">
              {{ translate("Bag Number")}}
          </label></b>
          <div class="input-group">
              <input type="text" placeholder="{{ translate('Bag Number')}}" name="bag_number[]" class="form-control">
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="form-group">
          <b><label class="col-from-label" for="Date Forwarded">
              {{ translate("Date Forwarded")}}
          </label></b>
          <div class="input-group">
              <input type="date" placeholder="{{ translate('Date Forwarded')}}" name="date_forwarded[]" class="form-control">
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="form-group">
          <b><label class="col-from-label" for="Listing Type">
              {{ translate("Listing Type")}}
          </label></b>
          <div class="input-group">
            <select class="form-control job_listing_type" name="listing_type[]"  data-live-search="true">
                <option value="">{{ translate("Select Listing Type") }}</option>
                @foreach (\App\SiteOptions::where("option_name", "=", "listingtype")->get(); as $listing_type)
                <option value="{{ $listing_type->option_value }}">{{ $listing_type->option_value }}</option>
                @endforeach
            </select>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="form-group">
          <b><label class="col-from-label" for="Stock ID">
              {{ translate("Stock ID")}}
          </label></b>
          <div class="input-group">
            <select class="form-control listingstock_id" name="stock_id[]">
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12  d-flex mb-4">
      <div class="col-lg-3">
        <div class="form-group">
          <b><label class="col-from-label" for="Model number">
              {{ translate("Model number")}}
          </label></b>
          <div class="input-group">
              <input type="text" placeholder="{{ translate('Model number')}}" name="model_number[]" class="form-control job_model">
              <input type="hidden" value="" name="pid[]" class="form-control job_pid">
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="form-group">
          <b><label class="col-from-label" for="Serial Number">
              {{ translate("Serial Number")}}
          </label></b>
          <div class="input-group">
              <input type="text" placeholder="{{ translate('Serial Number')}}" name="serial_number[]" class="form-control job_serial">
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="form-group">
          <b><label class="col-from-label" for="Weight">
              {{ translate("Weight")}}
          </label></b>
          <div class="input-group">
              <input type="text" placeholder="{{ translate('Weight')}}" name="weight[]" class="form-control job_weight">
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="form-group">
          <b><label class="col-from-label" for="Screw Count">
              {{ translate("Screw Count")}}
          </label></b>
          <div class="input-group">
              <input type="text" placeholder="{{ translate('Screw Count')}}" name="screw_count[]" class="form-control job_screw">
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12  d-flex mb-4">
      <div class="col-lg-3 hiddenWatch">
        <div class="form-group">
          <b><label class="col-from-label" for="Watch Test Results Upon Receipt">
              {{ translate("Watch Test Results Upon Receipt")}}
          </label></b>
          <div class="input-group">
            <div class="gci_watch_cs mb-2">
              <span class="cls_span">Rate</span>
                <input type="text" placeholder="{{ translate('Rate')}}" name="rate[]" class="form-control">
            </div>
            <div class="gci_watch_cs mb-2">
              <span class="cls_span">BE</span>
                <input type="text" placeholder="{{ translate('BE')}}" name="b_e[]" class="form-control">
            </div>
            <div class="gci_watch_cs mb-2">
              <span class="cls_span">AMP</span>
                <input type="text" placeholder="{{ translate('AMP')}}" name="amp[]" class="form-control">
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="form-group">
          <b><label class="col-from-label" for="Image Upon Receipt">
              {{ translate("Image Upon Receipt")}}
          </label></b>
          <div class="input-group mimediasec">
            <div class="camcod">
              <div class="input-group mi_custom_uploader" data-toggle="aizuploader" data-type="image" data-multiple="true">
                  <div class="input-group-prepend">
                      <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate("Browse")}}</div>
                  </div>
                  <div class="form-control file-amount">{{ translate("Choose File") }}</div>
                  <input type="hidden" name="image_upon_receipt[]" value ="" class="selected-files">
              </div>
              <div class="file-preview box sm"></div>
            </div>
             <button data-typereq="model" type="button" class="btn btn-primary jobwebcam webcamsubmit" data-toggle="modal" style="height:42px;">
                <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>
              </button>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="form-group">
          <b><label class="col-from-label" for="Job Details">
              {{ translate("Job Details")}}
          </label></b>
          <div class="input-group">
              <select class="form-control js-job-details-multiple_app ot-tag" multiple="multiple" name="job_details[$jKey][]" >
                  <option value="">Select Job Details</option>
                  <option value="1">Polish</option>
                  <option value="2">Overhual</option>
                  <option value="3">Change/Put Dial</option>
                  <option value="4">Change/Put bezel</option>
                  <option value="5">Change/Put band</option>
                  <option value="6">Change/Put crystal</option>
                  <option value="7">Change/Put Insert</option>
                  <option value="8">Swap Dial</option>
                  <option value="9">Swap Bezel</option>
                  <option value="10">Swap Band</option>
                  <option value="11">Swap Movement</option>
                  <option value="12">Remove Dial</option>
                  <option value="13">Remove Bezel</option>
                  <option value="14">Remove Crystal</option>
                  <option value="15">Estimate</option>
                  <option value="16">Fix Crown</option>
                  <option value="17">Fix Band</option>
                  <option value="18">Assemble</option>
                  <option value="19">Disassemble</option>
                  <option value="20">PVD</option>
                  <option value="21">Disassemble for Polish Out</option>
                  <option value="22">Engrave</option>
                  <option value="23">Others(will need explanation)</option>
              </select>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="form-group">
          <b><label class="col-from-label" for="Note">
              {{ translate("Note")}}
          </label></b>
          <div class="input-group">
            <textarea name="notes[]" class="form-control"></textarea>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12 tag-oth others-hide">
      <div class="col-lg-3">
        <div class="form-group">
          <b><label class="col-from-label" for="Others">
              {{ translate("Others")}}
          </label></b>
          <div class="input-group">
            <textarea name="others[]" class="form-control"></textarea>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12  d-flex mb-4">
      <div class="col-lg-3">
        <div class="form-group">
          <b><label class="col-from-label" for="Agent">
              {{ translate("Agent")}}
          </label></b>
          <div class="input-group">
            <select class="form-control aiz-selectpicker" name="agent[]" data-live-search="true" >
                <option value="">{{ translate("Select Agent") }}</option>
                @foreach (\App\Agent::orderBy("id","ASC")->get() as $agent)
                <option value="{{ $agent->id }}">{{ $agent->first_name }}</option>
                @endforeach
            </select>
            </div>
          </div>
        </div>
      <div class="col-lg-3">
        <div class="form-group">
          <b><label class="col-from-label" for="Estimated Cost">
              {{ translate("Estimated Cost")}}
          </label></b>
          <div class="input-group">
            <input type="text" placeholder="{{ translate('Estimated Cost')}}" name="estimated_cost[]" class="form-control estimated_cost">
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="form-group">
          <b><label class="col-from-label" for="Estimated Date Of Return">
              {{ translate("Estimated Date Of Return")}}
          </label></b>
          <div class="input-group">
            <input type="date" placeholder="{{ translate('Estimated Date Of Return')}}" name="estimated_date_return[]" class="form-control">
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="form-group">
          <b><label class="col-from-label" for="Status">
              {{ translate("Status")}}
          </label></b>
          <div class="input-group">
            <select class="form-control select" name="job_status[]">
                <option value="">Select Status</option>
                <option value="1">Past Due</option>
                <option value="2">Open</option>
                <option value="3">Pending</option>
                <option value="4">On Hold</option>
                <option value="5">Closed</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12  d-flex mb-4">
      <div class="col-lg-3">
        <div class="form-group">
          <b><label class="col-from-label" for="Agent Notes">
              {{ translate("Agent Notes")}}
          </label></b>
          <div class="input-group">
              <input type="text" placeholder="{{ translate('Agent Notes')}}" name="agent_notes[]" class="form-control">
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="form-group">
          <b><label class="col-from-label" for="Actual Cost">
              {{ translate("Actual Cost")}}
          </label></b>
          <div class="input-group">
              <input type="text" placeholder="{{ translate('Actual Cost')}}" name="actual_cost_datails[]"  class="form-control calculatCost actual_cost">
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="form-group">
          <b><label class="col-from-label" for="Parts Cost">
              {{ translate("Parts Cost")}}
          </label></b>
          <div class="input-group">
              <input type="text" placeholder="{{ translate('Parts Cost')}}" name="parts_cost[]" class="form-control calculatCost parts_cost">
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="form-group">
          <b><label class="col-from-label" for="Total Repair Cost">
              {{ translate("Total Repair Cost")}}
          </label></b>
          <div class="input-group">
              <input type="text" placeholder="{{ translate('Total Repair Cost')}}" name="total_repair_cost[]"  class="form-control job_d_data" readonly>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12  d-flex mb-4">
      <div class="col-lg-3 hiddenWatch">
        <div class="form-group">
          <b><label class="col-from-label" for="Watch Test Result Upon Returned">
              {{ translate("Watch Test Result Upon Returned")}}
          </label></b>
          <div class="input-group">
            <div class="gci_watch_cs mb-2">
              <span class="cls_span">Rate</span>
                <input type="text" placeholder="{{ translate('Rate')}}" name="rate_return[]" class="form-control">
            </div>
            <div class="gci_watch_cs mb-2">
              <span class="cls_span">BE</span>
                <input type="text" placeholder="{{ translate('BE')}}" name="b_e_return[]" class="form-control">
            </div>
            <div class="gci_watch_cs mb-2">
              <span class="cls_span">AMP</span>
                <input type="text" placeholder="{{ translate('AMP')}}" name="amp_return[]" class="form-control">
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="form-group">
          <b><label class="col-from-label" for="Image Upon Returned">
              {{ translate('Image Upon Returned')}}
          </label></b>
          <div class="input-group mimediasec">
            <div class="camcod">
              <div class="input-group mi_custom_uploader" data-toggle="aizuploader" data-type="image" data-multiple="true">
                  <div class="input-group-prepend">
                      <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                  </div>
                  <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                  <input type="hidden" name="image_upon_returned_details[]" value="" class="selected-files">
              </div>
              <div class="file-preview box sm"></div>
            </div>
            <button data-typereq="model" type="button" class="btn btn-primary jobwebcam webcamsubmit" data-toggle="modal" style="height:42px;">
            <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>
            </button>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="form-group">
          <b><label class="col-from-label" for="Date Of Return">
              {{ translate("Date Of Return")}}
          </label></b>
          <div class="input-group">
              <input type="date" placeholder="{{ translate('Date Of Return')}}" name="date_of_return[]" class="form-control">
          </div>
        </div>
      </div>
    </div>
    <input type="hidden" name="job_detail_id[]" value="{{$job_d_data->id}}">
    <div class="col-lg-12 form-group mb-2 text-right addremovebtn">
        <button type="button" class="btn btn-danger removesec">{{translate("Remove")}}</button>
        <button type="button" class="btn btn-success" id="update_bag_number">{{translate("Update Bag Number")}}</button>
    </div>
</div>





<div id="print_doc">
    <style>
        @media print {
            page[size="A4"][layout="landscape"] {
              width: 29.7cm !important;
            }
            @page {
                /*margin: 3mm 3mm 0mm 3mm !important;*/
                size: A4;
                border:3px solid red !important
                /*padding:10px !important;*/
                }

            body{
            /* border-top:5px solid #b7b9bc;
            border-left:1px solid #b7b9bc; */
            /*padding:0px 10px;*/
            margin:0px;
            }
            .print_main_sec{
                margin:5px;
             border:3px solid #000 !important;
             /*padding:0px 20px !important;*/

            }
            .mi_print{
                padding-left:25px !important;
                padding-right:25px !important;
                height:688px !important;
            }
            .mi_print{
                width:100% !important;
                padding:0px;
                margin:0px;
                position: relative;
            }
            /*.mi_print hr{*/
            /*    border-bottom:2px solid #000 !important;*/
            /*    width:107%;*/
            /*    margin-left:-5px;*/
            /*}*/
            .print_logo img{
                width:80% !important;
                height:auto !important;
                margin-top:10px;

            }
            .print_text_description{
                margin-bottom:15px;
                padding-bottom: 10px !important;
            }
            .print_text_description h3{
                margin-top:10px;
                margin-bottom:10px;
                font-weight:bold;
                font-size: 30px !important;
            }
            .print_text_description h4{
                font-size:18px !important;
                letter-spacing: 1.5px;
                height: 19px;
                line-height:normal;
                font-Weight:500;
            }
            .print-text h4{
                font-size:18px !important;
                letter-spacing: 1.5px;
                height: 19px;
                font-Weight:500;
                line-height:normal;
            }
            .print_job_ouder h4{
                font-size:18px !important;
                letter-spacing: 1.5px;
                height: 19px;
                font-Weight:500;
                line-height:normal;
            }
            .print_job_details h4{
                font-size:18px !important;
                letter-spacing: 1.5px;
                height: 19px;
                font-Weight:500;
                line-height:normal;
            }

        }
    </style>
<div class="print_main_sec">
    @foreach ($jo_order_detail as $job_key => $job_d_data)
    <div class="mi_print" style=" border-bottom:2px solid #000 !important;width:100% !important; padding-left:10px; padding-right:10px;">
  <div class="row print_sec">
    <div class='col-6 print_logo'>
      <img src="https://gcijewel.com/public/uploads/all/logo_gci1.jpg" class="img-fluid w-25 logo-rem" style="margin-top:20px;">
    </div>
    <div class='col-6 print-text print_right' style="padding-top:40px; padding-left:80px;">
      <h4> Job Order No. : {{$jo_order->job_order_number}}</h4>
      <h4 style="text-transform: uppercase;" >Date : {{ \Carbon\Carbon::parse($jo_order->date_returned)->format('m/d/Y')}}</h4>
      <h4 >Bag Number : {{$job_d_data->bag_number}}</h4>
    </div>
    </div>
    <div class="row">
    <div class='col-6 print_text_description'>
      <h3>Description </h3>
      <div class="print_dec">
        <h4> Job Order No. : {{$jo_order->job_order_number}}</h4>
        <h4>Stock Id : {{$job_d_data->stock_id}}</h4>
        <h4>Model Number : {{$job_d_data->model_number}}</h4>
        <h4>Serial No. : {{$job_d_data->serial_number}}</h4>
        <h4>Weight : {{$job_d_data->weight}}</h4>
        <h4>Screw Count : {{$job_d_data->screw_count}}</h4>
      </div>

      @php
        $photos_array='';
        $gallery_img=explode(",",trim($job_d_data->image_upon_receipt));

        foreach($gallery_img as $photos)
        {
            $photos_array.=uploaded_asset($photos)." , ";
        }
        $img=explode(" , ", $photos_array);
      @endphp
       <div class="print_job_ouder_img">
           <p style="padding:0px ; margin:15px 0px 0px 0px; font-size: 23px; font-weight:bold;"> Images :</p>
           @foreach($img as $r)
             @if($r != '')
              <img src="{{$r}}" style="width: 70px !important; height:50px !important; margin-bottom:20px !important; margin-top:20px !important; margin-right:10px !important">
              @endif
          @endforeach
        </div>
        <div class="print_job_ouder">
           <h4>Agent : {{$job_d_data->first_name}} </h4>
           <h4>Agent Note : {{$job_d_data->agent_notes}} </h4>
           <h4>Parts Cost : {{$job_d_data->parts_cost}}</h4>
           <h4> Service Cost :{{$jo_order->service_cost}}</h4>
           <h4> Total Cost :{{$jo_order->total_actual_cost}}</h4>
        </div>
    </div>
    <div class="col-6 print-img rigth_img_sec print_right">
       <img src="{{uploaded_asset($job_d_data->image_upon_receipt)}}" style="margin-bottom:20px !important; margin-top:-10px; width:100% !important; max-height:260px !important; margin-right:5px !important;">
       <div class="print_job_details" style="padding-top:10px;">
         @php
         $commSepArr = array();
         @endphp
         @foreach ($AlljoeditTag as $tagkey => $tagvalue)
           @if(in_array($tagkey , $job_det_tag))
             @php
              $tagvalue = str_replace('will need explanation',$job_d_data->others_note,$tagvalue);
              $commSepArr[] = $tagvalue;
             @endphp
           @endif
         @endforeach
       <h4>Job Details : <span style="font-size:16px;">{{implode(',',$commSepArr)}}</span></h4>
       <h4>Notes : {{$job_d_data->notes}} </h4>
       <h4>Parts Cost : {{$job_d_data->parts_cost}}</h4>
       <h4> Service Cost :{{$jo_order->service_cost}}</h4>
       <h4> Total Cost :{{$jo_order->total_actual_cost}}</h4>
       </div>
    </div>
    </div>
    @php
    $gallery_img=explode(",",trim($job_d_data->image_upon_receipt));
    $photos_array='';
        foreach($gallery_img as $photos){
            $photos_array.=uploaded_asset($photos)." , ";
            }
    @endphp
<p style="text-align:center; margin:-10px 0px 0px 0px !important; font-size:20px;">Agent Copy</p>
   <!--<hr>-->
  </div>
    <!--<hr style="height:3px; color:#000 !important; background-color:#000 !important; border-color:#000 !important; margin:0px !important; padding:0px !important; width:101%; margin-left:-4px !important; z-index:0;">-->
  @endforeach



    @foreach ($jo_order_detail as $job_key => $job_d_data)
    <div class="mi_print" style="padding:10px 10px 5px 10px;">
  <div class="row print_sec">
    <div class='col-6 print_logo'>
      <img src="https://gcijewel.com/public/uploads/all/logo_gci1.jpg" class="img-fluid w-25 logo-rem">
    </div>
    <div class='col-5 print-text print_right' style="padding-top:30px; padding-left:80px;">
      <h4> Job Order No. : {{$jo_order->job_order_number}}</h4>
      <h4 style="text-transform: uppercase;" >Date : {{ \Carbon\Carbon::parse($jo_order->date_returned)->format('m/d/Y')}}</h4>
      <h4 >Bag Number : {{$job_d_data->bag_number}}</h4>
    </div>
    </div>
    <div class="row">
    <div class='col-6 print_text_description'>
        <h3>Description </h3>
        <div class="print_dec">
      <h4>Stock Id : {{$job_d_data->stock_id}}</h4>
      <h4>Model Number : {{$job_d_data->model_number}}</h4>
      <h4>Serial No. : {{$job_d_data->serial_number}}</h4>
      <h4>Weight : {{$job_d_data->weight}}</h4>
      <h4>Screw Count : {{$job_d_data->screw_count}}</h4>
      </div>

       <div class="print_job_ouder_img">
          <p style="padding:0px ; margin:15px 0px 0px 0px; font-size: 23px; font-weight:bold;"> Images :</p>
       @foreach($img as $r)
          @if($r != "")
        <img src="{{$r}}" style="width: 70px !important; height:50px !important; margin-bottom:20px !important; margin-top:20px !important; margin-right:10px !important">
        @endif
      @endforeach
        </div>
<div class="print_job_ouder">
   <h4>Job Details : <span style="font-size:16px !important;">@php echo implode(',',$commSepArr); @endphp</span></h4>
        <h4>Agent : {{$job_d_data->first_name}} </h4>
       <h4>Agent Note : {{$job_d_data->agent_notes}} </h4>
</div>
    </div>
    <div class="col-6 print-img rigth_img_sec print_right">
       <img src="{{uploaded_asset($job_d_data->image_upon_receipt)}}" style="margin-bottom:5px !important;margin-top:20px !important; width:100% !important; max-height:260px !important;">
       <div class="print_job_details" style="padding-top:40px;">
           <h4> {{$jo_order->job_order_number}} </h4>
           <h4 style="border-bottom:1px solid #000; padding-bottom:10px; height:40px !important;">Agent Signature : </h4>
       </div>
    </div>
     <p style="text-align:center; font-size:20px; margin-bottom:0px !important;margin-top:60px !important; padding:0px !important; width:100%;">GCI Copy</p>
    </div>


    @php
    $gallery_img=explode(",",trim($job_d_data->image_upon_receipt));
    $photos_array='';
        foreach($gallery_img as $photos){
            $photos_array.=uploaded_asset($photos)." , ";
            }
    @endphp

  </div>
  @endforeach
</div>

</div>

</section>
</div>


    <!-- Webcam Modal -->
    <div class="modal fade" id="add_ajax_product_webcam" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header justify-content-start">
            <h5 class="modal-title">Webcam</h5>
          </div>
          <div class="modal-body">
            <form name="ajax-webcam-form" id="ajax-webcam-form" method="post" action="javascript:void(0)">
             @csrf
             <div class="form-group mb-3">
               <div id="my_camera"></div>
                <input type="button" class="btn btn-success my-3 snapShotClicker" value="Take Snapshot" onClick="take_snapshot()">

                <div id="mi_webcam" ></div>
             </div>

              <button type="submit" class="btn btn-primary" id="webcamSubmit">Add</button>
          </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Webcam Modal -->
    <!-- Modal -->
<div class="modal fade" id="companyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add customer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="aiz-titlebar text-left mt-2 mb-3">
          <h5 class="mb-0 h6">{{translate('Add New Customer')}}</h5>
      </div>
<div class="">
<form name="ajax-memo-compant-form" id="ajax-memo-compant-form" method="post" action="javascript:void(0)">
      @csrf
      <div class="form-group row">
          <label class="col-md-3 col-from-label">{{translate('Customer Group *')}}</label>
          <div class="col-md-8">
              <select class="form-control" name="customer_group" id="customer_group" required>
                <option value="">{{ translate('Select') }}</option>
                <option value="retail">{{ translate('Retail') }}</option>
                  <option value="reseller">{{ translate('Re-seller') }}</option>
              </select>
          </div>
      </div>
      <div class="form-group row"  id="price_group_html">
      </div>
      <div class="form-group row" >
          <label class="col-sm-3 col-from-label" for="Customer Group"  id="company_name_required_sign">
              {{ translate('Company Name *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Company Name *')}}" name="company" class="form-control company_id_retail" required>
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" id="office_phone_no_required_sign" for="Customer Group">
              {{ translate('Office Phone No')}}
          </label>
          <div class="col-sm-9" id="office_phone_re">
               <input type="text" placeholder="{{ translate('(___) - ___ - ____')}}" id="office_phone_number" maxlength='13' name="office_phone_number" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Office Address *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Office Address * ')}}" id="office_address" name="office_address" class="form-control" required>
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('City *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('City *')}}" id="city" name="city" class="form-control" required >
          </div>
      </div>

      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('State *')}}
          </label>
          <div class="col-sm-9" id="create_state_id">

          </div>
      </div>

      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Country *')}}
          </label>
          <div class="col-sm-9">
              <select class="form-select form-control aiz-selectpicker" data-live-search="true" id="country" name="country" aria-label="Default select example" required>

                  <option  value="United States" >US (United States)</option>
                  @foreach($allcountry as $country)
                   <option  value="{{$country->name}}">{{$country->code}}</option>
                  @endforeach

              </select>
          </div>
      </div>

      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Zipcode *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Zipcode')}}" id="zipcode" name="zipcode" class="form-control" required>
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group" >
              {{ translate('Customer Name *')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Customer Name *')}}" id="customer_name" name="customer_name" class="form-control">
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group"  id="customer_emaile_required_sign">
              {{ translate('Customer Email Address ')}}
          </label>
          <div class="col-sm-9">
              <input type="email" placeholder="{{ translate('Customer Address ')}}"  id="Customeremail_one" name="email" class="form-control" ><span id="Customeremail_oneErr"></span>
          </div>
      </div>
      <div class="form-group row">

          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Customer Contact No ')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('(___) - ___ - ____')}}" id="phone" name="phone" maxlength='13' class="form-control"  >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Terms')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Terms')}}" id="terms" name="terms" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Website')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Website')}}" id="website" name="website" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Tax ID')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Tax ID')}}" id="tax_id" name="tax_id" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Reseller Permit')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Reseller Permit')}}" id="text_reseller_permit" name="text_reseller_permit" class="form-control" >
          </div>
      </div>


      <div class="form-check">
          <input  type="checkbox" class="billing_address_class"  id="billing_address_id">
            Same as Office Address
     </div>


      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Billing Address')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Billing Address')}}" id="billing_address" name="billing_address" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Billing City')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Billing City')}}" id="billing_city" name="billing_city" class="form-control" >
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Billing State')}}
          </label>
          <div class="col-sm-9"  id="billing_state">
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Billing Country')}}
          </label>
          <div class="col-sm-9">
            <select class="form-select form-control aiz-selectpicker" data-live-search="true" id="billing_country" name="billing_country" aria-label="Default select example" required>
                  <option  value="United States" >US (United States)</option>
                  @foreach($allcountry as $country)
                   <option  value="{{$country->name}}">{{$country->code}}</option>
                  @endforeach

            </select>
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Billing Zipcode')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Billing Zipcode')}}" id="billing_zipcode" name="billing_zipcode" class="form-control" >
          </div>
      </div>

      <div class="form-check">
          <input  type="checkbox" class="shipping_address_class" id="shipping_address_id">
            Same as Office Address
     </div>


      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Shipping Address')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Shipping Address')}}" id="shipping_address" name="shipping_address" class="form-control">
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Shipping City')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Shipping City')}}" id="shipping_city" name="shipping_city" class="form-control">
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Shipping State')}}
          </label>
          <div class="col-sm-9" id="shipping_state_html">
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Shipping Country')}}
          </label>
          <div class="col-sm-9" id="shipping_country_html">

             <select class="form-select form-control aiz-selectpicker" data-live-search="true" id="shipping_country" name="shipping_country" aria-label="Default select example" required>
                  <option  value="United States" >US (United States)</option>
                  @foreach($allcountry as $country)
                   <option  value="{{$country->name}}">{{$country->code}}</option>
                  @endforeach

              </select>
          </div>
      </div>
      <div class="form-group row">
          <label class="col-sm-3 col-from-label" for="Customer Group">
              {{ translate('Shipping Zipcode')}}
          </label>
          <div class="col-sm-9">
              <input type="text" placeholder="{{ translate('Shipping Zipcode')}}" id="shipping_zipcode" name="shipping_zipcode" class="form-control" >
          </div>
      </div>
      <div class="form-group mb-0 text-right">
          <button type="submit" class="btn btn-primary">{{translate('Add Customer')}}</button>
      </div>
  </form>
</div>
      </div>
    </div>
<!-- end company name  -->














@endsection
@section('script')
<script type="text/javascript">
$(document).ready(function() {
    $('.js-job-details-multiple').select2();
});

// $(document).on('click', '.add_items', function() {
//   var html = $(".append_gci_bag .bag_fs_child:first-child").clone();
//   $('.append_gci_bag').append(html);
//   // console.log(html);
// });

$(document).on('click', '.add_items', function() {
  var html = $(".append_gci_bag_append").html();
  var multipleTag = $('.append_gci_bag .trCost').length;
  html = html.replace("$jKey", multipleTag);
  $('.append_gci_bag').append(html);
  $('.append_gci_bag .js-job-details-multiple_app').select2();
  // console.log(html);


});


$(document).on('click', '.removesec', function() {
  $(this).closest('.bag_fs_child_append').remove();
  // console.log(html);
});

$(document).on('change','.job_listing_type',function() {
    var id=$(this).val();
    var jobThis = $(this);
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      url:"{{ route('listing.ajax') }}",
      type: 'post',
      dataType: 'json',
      data: {id : id},
      success: function( response ) {
        // console.log(response.TagHTML);
        jobThis.closest('.micustom_cl_div').find('select.listingstock_id').html(response.TagHTML);
      }
    });
    if(id != 'Watch'){
      $('.hiddenWatch').css({"display":"none"});
    }else{
      $('.hiddenWatch').css({"display":"block"});
    }
});

$(document).on('change','.listingstock_id',function() {
    var weight = $(this).find(':selected').data('weight');
    var model = $(this).find(':selected').data('model');
    var sku =  $(this).find(':selected').data('sku');
    var screw =  $(this).find(':selected').data('screw');
    // $(this).closest('.trCost').find('.job_pid').val(jo_pids);
    $(this).closest('.trCost').find('.job_model').val(model);
    $(this).closest('.trCost').find('.job_serial').val(sku);
    $(this).closest('.trCost').find('.job_weight').val(weight);
    $(this).closest('.trCost').find('.job_screw').val(screw);
});


$(document).on("change", ".estimated_cost", function() {
    var sum = 0;
    $(".estimated_cost").each(function(){
        sum += +$(this).val();
    });
    $(".estimated_total_cost").val(sum);
});

$(document).on('change','.companyName',function() {
    var name = $(this).find(':selected').data('name');
    var number = $(this).find(':selected').data('number');
    $('.job_co_name').val(name);
    $('.job_co_number').val(number);
});
$(document).on('keyup','.calculatCost',function() {
  var actual_cost =$(this).closest('.trCost').find(".actual_cost").val();
  var parts_cost =$(this).closest('.trCost').find(".parts_cost").val();
  var totalRepaireCost = parseInt(actual_cost) + parseInt(parts_cost);
  $(this).closest('.trCost').find(".job_d_data").val(totalRepaireCost);
  JoActualCost();
});

$(document).on('keyup','.total_costcharge',function() {
  var total_actual_cost = parseFloat($(".total_actual_cost").val());
  var service_cost = parseFloat($(".service_cost").val());
  var misc_charges= parseFloat($(".misc_charges").val());
  var tcSum = total_actual_cost+service_cost+misc_charges;
   $("#total_charge_from_customer").val(tcSum);
});

function JoActualCost() {
  // alert('sam');
    var sum = 0;
    $(".job_d_data").each(function(){
        var tcost = $(this).val();
        tcost  = parseFloat(tcost);
        if(tcost > 0){
          sum += tcost;
        }
        // console.log(sum);
    });
    $(".total_actual_cost").val(sum);
}
setTimeout(function(){
 	JoActualCost();
}, 3000);
$("#print_doc").hide();
function micustomPrint(){

  var printContents = document.getElementById("print_doc").innerHTML;
	var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
  $("#print_doc").show();
	window.print();
  document.body.innerHTML = originalContents;
  return true;

	}








  $('#ajax-webcam-form').submit(function(){
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $.ajax({
    url:"{{ route('products.mi_custom_cam_image') }}",
    type: 'post',
    data: $('#ajax-webcam-form').serialize(),
    success: function( responce ) {
       // console.log(responce.camImageData);
       $('.newCamImage').append(responce.camImageData);
       $('.newCamImageID').val(responce.camImageDataID);
       $('#ajax-webcam-form').trigger("reset");
       $('#add_ajax_product_webcam').modal('hide');
       $('.add_items').removeAttr('disabled',false);
       $('.save_jo').removeAttr('disabled',false);
       $('#webcamSubmit').removeAttr('disabled',false);
       $("#mi_webcam").html("");
    }
  });
});
Webcam.set({
     width: 320,
     height: 240,
     image_format: 'jpeg',
     jpeg_quality: 90
 });

function take_snapshot() {
// take snapshot and get image data
Webcam.snap( function(data_uri) {
     $("#mi_webcam").append('<div class="mi_remove_web_img"><input type="hidden" class="camera_image_upload" name="mi_custom_cam_image[]" value="'+data_uri+'"><img class="mi_custom_cam_image"  src="'+data_uri+'" style="margin: 10px 0;"/> <button class="webcamRemove">x</button></div>');
 } );
}


$('#ajax-memo-compant-form').on('submit', function(e) {
  e.preventDefault();
  $.ajax({
      url: "{{route('Memo.MemoCompanyAjax')}}",
      type: "POST",
      data: $('#ajax-memo-compant-form').serialize(),
      success: function( response ) {
          alert(response.success);
        $('#company_name').html(response.RetailResellerHTML);
        $('#company_name').selectpicker("refresh");
        $('#ajax-memo-compant-form').trigger("reset");
        $('#companyModal').modal('hide');
        $('#reset_memo_sub').removeAttr('disabled',false);
        $('#create_memo_sub').removeAttr('disabled',false);
        // location.reload(true);
      }
     });
   });

   $(document).on('click','.webcamsubmit',function(){
     $('.file-preview').removeClass('newCamImage');
     $('.selected-files').removeClass('newCamImageID');
     $(this).closest('.camcod').find('.file-preview').addClass('newCamImage');
     $(this).closest('.camcod').find('.selected-files').addClass('newCamImageID');
   })

   $('.updatejo').on('click', function () {
     var csStock = $(".companyName").find(':selected').val();
     var validationError = 0;
     $( ".is_require" ).removeClass('error');
     var job_order_status = $('.job_order_status').val();
     $( ".is_require" ).each(function() {
      var req_vals = $(this).val();
      if(req_vals == ""){
        $(this).addClass('error');
        validationError = 1;
      }
    });
     // alert(job_order_status);
     if(validationError == 1){
       return false;
     }
     if(csStock == 0){
       $('#joborderformsubmit').submit();
     }else{
       if(job_order_status == 5){
         Swal.fire({
            title: "<h5 style='color:black'>Do you want to create a memo ?</h5>",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'No',
            confirmButtonText: 'Yes',
            allowOutsideClick: false,
            allowEscapeKey: false
          }).then((result) => {
            if (result.isConfirmed) {
              $('.makeinvoice').val("1");
            } else {
              $('.makeinvoice').val("0");
            }
            $('#joborderformsubmit').submit();
          })
          // if (confirm(text) == true) {
          //   $('.makeinvoice').val("1");
          // } else {
          //   $('.makeinvoice').val("0");
          // }
        } else if (job_order_status == 2){
          // alert('sam');
          $('#joborderformsubmit').submit();
        }
        else if (job_order_status == 3){
          // alert('sam');
          $('#joborderformsubmit').submit();
        }
        else if (job_order_status == 4){
          // alert('sam');
          $('#joborderformsubmit').submit();
        }

        else if (job_order_status == 1){
          // alert('sam');
          $('#joborderformsubmit').submit();
        }
      }

   });
   $(document).on('click','.webcamsubmit',function(){

      $('.file-preview').removeClass('newCamImage');

      $('.selected-files').removeClass('newCamImageID');

      $(this).closest('.mimediasec').find('.file-preview').addClass('newCamImage');

      $(this).closest('.mimediasec').find('.selected-files').addClass('newCamImageID');

       var constraints = {video: true};
        navigator.mediaDevices.getUserMedia(constraints)
        .then(function(stream) {
            Webcam.attach( '#my_camera' );
            $('#add_ajax_product_webcam').modal('show');
        })
        .catch(function(err) {
          alert('Error: Could not access webcam: NotFoundError: Requested device not found');
        });

    });

    $('#mi_webcam').on('click', '.webcamRemove', function(e) {
        e.preventDefault();
        $(this).closest('.mi_remove_web_img').remove();
    });

    $(document).on('change','.ot-tag',function(){
     var tagName = $(this).val();
     if(tagName == "23"){
       $(this).closest('.trCost').find('.tag-oth').removeClass('others-hide');
     }else{
       $(this).closest('.trCost').find('.tag-oth').addClass('others-hide');
     }
    })

    $(document).ready(function(){
      var tagName = $('.ot-tag').val();
      if(tagName == "23"){
        $('.ot-tag').closest('.trCost').find('.tag-oth').removeClass('others-hide');
      }
    })

    $(document).ready(function(){
      var tagName = $('.job_listing_type').val();
      if(tagName != "Watch"){
        $('.ot-tag').closest('.trCost').find('.hiddenWatch').addClass('others-hide');
      }
    })

</script>
@endsection
