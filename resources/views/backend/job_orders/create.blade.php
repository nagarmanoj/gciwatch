@extends('backend.layouts.app')



@section('content')

<style media="screen">

span.select2-selection.select2-selection--single {

  height: 42px !important;

  padding: 7px !important;

  border: none !important;

}

.select2-container--default .select2-selection--single .select2-selection__arrow b {

    margin-top: 3px !important;

}

</style>

<script src="{{ static_asset('assets/js/webcam.min.js') }}" ></script>

<div class="aiz-titlebar text-left mt-2 mb-3">

    <h5 class="mb-0 h6">{{translate('Add Job Orders')}}</h5>

</div>

<div class="">

  <form class="p-4" action="{{route('job_orders.save')}}" method="POST">

      @csrf

      <div class="form-group row mb-5 pb-5">

          <div class="col-lg-12  d-flex mb-4">

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Name">

                    {{ translate('Company Name *')}}

                </label></b>

                <div class="input-group">

                  <select class="form-control aiz-selectpicker companyName" name="company_name" id="company_name" data-live-search="true" required >

                      <option value="">{{ translate('Select Company Name') }}</option>

                      <option value="0">Stock</option>

                     @foreach (App\RetailReseller::orderBy('id','ASC')->get(); as $memo_payment)
                      @if($memo_payment->customer_group=='reseller')
                      <option data-name="{{$memo_payment->customer_name}}" data-number="{{$memo_payment->phone}}" value="{{ $memo_payment->id }}">{{ $memo_payment->company }}</option>
                      @else
                      <option data-name="{{$memo_payment->customer_name}}" data-number="{{$memo_payment->phone}}" value="{{ $memo_payment->id }}">{{$memo_payment->customer_name}}</option>
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

                    {{ translate('Contact Person *')}}

                </label></b>

                <div class="input-group">

                    <input type="text" placeholder="{{ translate('Contact Person')}}" name="contact_person" class="form-control job_co_name" required>

                </div>

              </div>

            </div>

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Contact Number">

                    {{ translate('Contact Number *')}}

                </label></b>

                <div class="input-group">

                    <input type="text" placeholder="{{ translate('Contact Number')}}" name="contact_number" class="form-control job_co_number" required>

                </div>

              </div>

            </div>

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Customer Reference">

                    {{ translate('Customer Reference')}}

                </label></b>

                <div class="input-group">

                    <input type="text" placeholder="{{ translate('Customer Reference')}}" name="customer_reference" class="form-control">

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

                          <div class="form-control file-amount">{{ translate('Choose') }}</div>

                          <input type="hidden" name="image_upon_receipt_job" class="selected-files">

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

                    {{ translate('Estimated Total Cost *')}}

                </label></b>

                <div class="input-group">

                    <input type="text" placeholder="{{ translate('Estimated Total Cost')}}" name="estimated_total_cost" class="form-control estimated_total_cost" required>

                </div>

              </div>

            </div>

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Estimated Date Return">

                    {{ translate('Estimated Date Return')}}

                </label></b>

                <div class="input-group">

                    <input type="date" placeholder="{{ translate('Estimated Date Return')}}" name="estimated_date_return_job" class="form-control">

                </div>

              </div>

            </div>

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Status">

                    {{ translate('Status')}}

                </label></b>

                <div class="input-group">

                  <select class="form-control job_order_status select" name="job_order_status">

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

          <div class="col-lg-12  d-none mb-4">

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Actual Cost">

                    {{ translate('Actual Cost')}}

                </label></b>

                <div class="input-group">

                    <input type="text" placeholder="{{ translate('Actual Cost')}}" name="total_actual_cost" class="form-control">

                </div>

              </div>

            </div>

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Service Cost">

                    {{ translate('Service Cost')}}

                </label></b>

                <div class="input-group">

                    <input type="text" placeholder="{{ translate('Service Cost')}}" name="service_cost" class="form-control">

                </div>

              </div>

            </div>

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Misc Charges">

                    {{ translate('Misc Charges')}}

                </label></b>

                <div class="input-group">

                    <input type="text" placeholder="{{ translate('Misc Charges')}}" name="misc_charges" class="form-control">

                </div>

              </div>

            </div>

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Misc Charges Notes">

                    {{ translate('Misc Charges Notes')}}

                </label></b>

                <div class="input-group">

                    <input type="text" placeholder="{{ translate('Misc Charges Notes')}}" name="misc_charges_notes" class="form-control">

                </div>

              </div>

            </div>

          </div>

          <div class="col-lg-12  d-none mb-4">

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Total Cost Charge To Customer">

                    {{ translate('Total Cost Charge To Customer')}}

                </label></b>

                <div class="input-group">

                    <input type="text" placeholder="{{ translate('Total Cost Charge To Customer')}}" name="total_charge_from_customer" class="form-control">

                </div>

              </div>

            </div>

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Date Returned">

                    {{ translate('Date Returned')}}

                </label></b>

                <div class="input-group">

                    <input type="date" placeholder="{{ translate('Date Returned')}}" name="date_returned" class="form-control">

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

                        <input type="hidden" name="image_upon_returned" class="selected-files">

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

      <div class="form-group row mt-5 pt-5 bag_fs_child trCost" style="background:#c4c4c4;">

          <div class="col-lg-12  d-flex mb-4 micustom_cl_div">

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Bag Number">

                    {{ translate('Bag Number *')}}

                </label></b>

                <div class="input-group">

                    <input type="text" placeholder="{{ translate('Bag Number')}}" name="bag_number[]" class="form-control" required>

                </div>

              </div>

            </div>

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Date Forwarded">

                    {{ translate('Date Forwarded')}}

                </label></b>

                <div class="input-group">

                    <input type="date" placeholder="{{ translate('Date Forwarded')}}" name="date_forwarded[]" class="form-control">

                </div>

              </div>

            </div>

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Listing Type">

                    {{ translate('Listing Type *')}}

                </label></b>

                <div class="input-group">

                  <select class="form-control job_listing_type" name="listing_type[]"  data-live-search="true" required>

                      <option value="">{{ translate('Select Listing Type') }}</option>

                      @foreach (\App\SiteOptions::where('option_name', '=', 'listingtype')->get(); as $listing_type)

                      <option value="{{ $listing_type->option_value }}">{{ $listing_type->option_value }}</option>

                      @endforeach

                  </select>

                </div>

              </div>

            </div>

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Stock ID">

                    {{ translate('Stock ID *')}}

                </label></b>

                <div class="input-group">

                  <select class="form-control listingstock_id" name="stock_id[]" required>



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

                    <input type="text" placeholder="{{ translate('Model number')}}" name="model_number[]" class="form-control job_model">

                    <input type="hidden" value="" name="pid[]" class="form-control job_pid">

                </div>

              </div>

            </div>

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Serial Number">

                    {{ translate('Serial Number')}}

                </label></b>

                <div class="input-group">

                    <input type="text" placeholder="{{ translate('Serial Number')}}" name="serial_number[]" class="form-control job_serial">

                </div>

              </div>

            </div>

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Weight">

                    {{ translate('Weight')}}

                </label></b>

                <div class="input-group">

                    <input type="text" placeholder="{{ translate('Weight')}}" name="weight[]" class="form-control job_weight">

                </div>

              </div>

            </div>

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Screw Count">

                    {{ translate('Screw Count')}}

                </label></b>

                <div class="input-group">

                    <input type="text" placeholder="{{ translate('Screw Count')}}" name="screw_count[]" class="form-control job_screw">

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

                    {{ translate('Image Upon Receipt')}}

                </label></b>

                <div class="input-group mimediasec">

                  <div class="camcod">

                   <div class="input-group mi_custom_uploader" data-toggle="aizuploader" data-type="image" data-multiple="true">

                          <div class="input-group-prepend">

                              <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>

                          </div>

                          <div class="form-control file-amount">{{ translate('Choose') }}</div>

                          <input type="hidden" name="image_upon_receipt[]" class="selected-files">

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

                    {{ translate('Job Details *')}}

                </label></b>

                <div class="input-group">

                    <select class="js-job-details-multiple" multiple="multiple" name="job_details[0][]" id="job_details" required>

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
             <div class="col-lg-3" id="others">

              <div class="form-group">

                <b><label class="col-from-label" for="Others">

                    {{ translate('Others')}}

                </label></b>

                <div class="input-group">

                  <textarea name="others_note[]" class="form-control"></textarea>

                </div>

              </div>

            </div>
            <div class="col-lg-3 others">

            </div>
            <div class="col-lg-3 note_main _sec" id="notes">

              <div class="form-group">

                <label class="col-from-label" for="Note">

                    {{ translate('Note')}}

                <div class="input-group">

                  <textarea name="notes[]" class="form-control"></textarea>

                </div>

              </div>

            </div>


</div>

          </div>
          <!--<div class="col-lg-12">-->
          <!--  <div class="col-lg-3" id="others">-->

          <!--    <div class="form-group">-->

          <!--      <label class="col-from-label" for="Note">-->

          <!--          {{ translate('Others')}}-->

          <!--      <div class="input-group">-->

          <!--        <textarea name="others_note[]" class="form-control"></textarea>-->

          <!--      </div>-->

          <!--    </div>-->

          <!--  </div>-->
          <!--</div>-->

          <div class="col-lg-12  d-flex mb-4">

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Agent">

                    {{ translate('Agent *')}}

                </label></b>

                <div class="input-group agentmaindiv">

                  <select class="form-control" id="agentajax" name="agent[]" data-live-search="true" required>

                      <option value="">{{ translate('Select Agent') }}</option>

                      @foreach (\App\Agent::orderBy('id','ASC')->get() as $agent)

                      <option value="{{ $agent->id }}">{{ $agent->first_name }}</option>

                      @endforeach

                  </select>

                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AgentModel">

                      +

                    </button>

                  </div>

                </div>

              </div>

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Estimated Cost">

                    {{ translate('Estimated Cost *')}}

                </label></b>

                <div class="input-group">

                  <input type="text" placeholder="{{ translate('Estimated Cost')}}" name="estimated_cost[]" class="form-control estimated_cost" required>

                </div>

              </div>

            </div>

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Estimated Date Of Return">

                    {{ translate('Estimated Date Of Return')}}

                </label></b>

                <div class="input-group">

                  <input type="date" placeholder="{{ translate('Estimated Date Of Return')}}" name="estimated_date_return[]" class="form-control">

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

          <div class="col-lg-12  d-none mb-4">

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Agent Notes">

                    {{ translate('Agent Notes')}}

                </label></b>

                <div class="input-group">

                    <input type="text" placeholder="{{ translate('Agent Notes')}}" name="agent_notes[]" class="form-control">

                </div>

              </div>

            </div>

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Actual Cost">

                    {{ translate('Actual Cost')}}

                </label></b>

                <div class="input-group">

                    <input type="text" placeholder="{{ translate('Actual Cost')}}" name="actual_cost_datails[]" class="form-control">

                </div>

              </div>

            </div>

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Parts Cost">

                    {{ translate('Parts Cost')}}

                </label></b>

                <div class="input-group">

                    <input type="text" placeholder="{{ translate('Parts Cost')}}" name="parts_cost[]" class="form-control">

                </div>

              </div>

            </div>

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Total Repair Cost">

                    {{ translate('Total Repair Cost')}}

                </label></b>

                <div class="input-group">

                    <input type="date" placeholder="{{ translate('Total Repair Cost')}}" name="total_repair_cost[]" class="form-control">

                </div>

              </div>

            </div>

          </div>

          <div class="col-lg-12  d-none mb-4">

            <div class="col-lg-3">

              <div class="form-group">

                <b><label class="col-from-label" for="Watch Test Result Upon Returned">

                    {{ translate('Watch Test Result Upon Returned')}}

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

                        <input type="hidden" name="image_upon_returned_details[]" class="selected-files">

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

                    <input type="date" placeholder="{{ translate('Date Of Return')}}" name="date_of_return[]" class="form-control">

                </div>

              </div>

            </div>

          </div>

          <div class="col-lg-12 form-group mb-2 text-right addremovebtn">

              <button type="button" class='btn btn-danger removesec'>{{translate('Remove')}}</button>

          </div>

      </div>

    </section>

    <div class="col-lg-12 form-group mb-0 text-center">

        <button type="button" class="btn btn-success add_items">{{translate('Add Bag')}}</button>

    </div>

      <div class="form-group mb-0 text-left">

          <button type="submit" class="btn btn-primary save_jo">{{translate('Save')}}</button>

      </div>

  </form>



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



















     <!-- Agent Modal -->

     <div class="modal fade" id="AgentModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

      <div class="modal-dialog">

        <div class="modal-content add_agent_main_sec">

          <div class="modal-header justify-content-start">

            <h5 class="modal-title">Add Agent</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

          </div>

          <div class="modal-body">

          <form  id="ajax_agent" class="form-horizontal bv-form" role="form" method="post" >

            @csrf

            <div class="row">

                <div class="col-lg-12">

                    <div class="col-md-12">

                        <div class="form-group">

                            <label for="company">Company </label>

                            <div class="controls">

                                <input type="text" name="company_name" value="" class="form-control" id="company_name" data-bv-field="company">

                            </div>

                            <div class="form-group">

                                <label for="company_address"> Company Address </label>

                                <div class="controls">

                                    <textarea class="form-control" id="company_address" name="company_address"></textarea>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-12">

                        <div class="col-md-12">

                          <div class="form-group">

                            <label  for="first_name">First Name *</label>

                            <input type="text" name="first_name" value="" class="form-control" id="first_name" required="required" pattern=".{3,10}" data-bv-field="first_name">

                          </div>

                        </div>

                        <div class="col-md-12">

                          <div class="form-group">

                            <label for="last_name">Last Name </label>

                            <input type="text" name="last_name" value="" class="form-control" id="last_name" data-bv-field="last_name">

                          </div>

                        </div>

                    </div>

                    <div class="col-lg-12">

                        <div class="col-md-12">

                            <div class="form-group">

                              <label >Contact Number *</label>

                              <div class="controls">

                                <input type="text" name="contact_number" value="" class="form-control" id="contact_number" required="required" data-bv-field="contact_number">

                              </div>

                            </div>

                            <!-- <div class="form-group">

                                <label >Contact Person </label>

                                <div class="controls">

                                    <input type="text" name="contact_person" value="" class="form-control" id="contact_person" data-bv-field="contact_person">

                                </div>

                            </div> -->

                            <div class="form-group">

                                <label for="email">Email </label>

                                <div class="controls">

                                    <input type="email" id="email" name="email" class="form-control" data-bv-field="email">

                                </div>

                           </div>

                            <div class="form-group">

                                <label for="expertise_id">Expertise *</label>

                                <select class="form-control select" name="expertise_id" required>

                                    <option value="" readonly>Select Expertise</option>

                                    @foreach($expertiseData as $row)

                                    <option value="{{$row->id}}">{{$row->expertise_name}}</option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="form-group">

                                <label for="status">Status *</label>

                                <select name="is_active" id="status" class="form-control select" required>

                                    <option value="1">Active</option>

                                    <option value="0">Inactive</option>

                                </select>

                            </div>

                        </div>

                    </div>
                <div class="custom_bttn">
                    <input type="submit" name="add_user" value="Add Agent" class="btn btn-primary" style="margin-left:20px;">
                </div>
                </div>

            </div>

        </form>

          </div>

        </div>

      </div>

    </div>

    <!-- Agent Modal -->





























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



</div>

<section class="append_gci_bag_append" style="display: none;">

  <div class="form-group row mt-5 pt-5 bag_fs_child_append trCost" style="background:#c4c4c4;">

      <div class="col-lg-12  d-flex mb-4 micustom_cl_div">

        <div class="col-lg-3">

          <div class="form-group">

            <label class="col-from-label" for="Bag Number">

                {{ translate("Bag Number")}}

            </label>

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

              <select class="form-control listingstock_id " name="stock_id[]" data-live-search="true">

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

                    <input type="hidden" name="image_upon_receipt[]" class="selected-files">

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

                <select class="form-control js-job-details-multiple_app" multiple="multiple" name="job_details[$jKey][]" >

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

        <div class="col-lg-3 d-none">

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

              <select class="form-control" id="agentajax" name="agent[]" data-live-search="true" >

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

      <div class="col-lg-12 form-group mb-2 text-right addremovebtn">

          <button type="button" class="btn btn-danger removesec">{{translate("Remove")}}</button>

      </div>

  </div>

  </section>

@endsection

@section('script')

<script type="text/javascript">

$(document).ready(function() {

    $('.js-job-details-multiple').select2();

});



$(document).on("change", ".estimated_cost", function() {

    var sum = 0;

    $(".estimated_cost").each(function(){

        sum += +$(this).val();

    });

    $(".estimated_total_cost").val(sum);

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

         jobThis.closest('.micustom_cl_div').find('select.listingstock_id').select2();

      }

    });

    if(id != 'Watch'){

      $('.hiddenWatch').css({"display":"none"});

    }else{

      $('.hiddenWatch').css({"display":"block"});

    }

});



$(document).on('change','.listingstock_id',function() {

    var jo_pids = $(this).find(':selected').data('id');

    var weight = $(this).find(':selected').data('weight');

    var model = $(this).find(':selected').data('model');

    var sku =  $(this).find(':selected').data('sku');

    var screw =  $(this).find(':selected').data('screw');

    $(this).closest('.trCost').find('.job_pid').val(jo_pids);

    $(this).closest('.trCost').find('.job_model').val(model);

    $(this).closest('.trCost').find('.job_serial').val(sku);

    $(this).closest('.trCost').find('.job_weight').val(weight);

    $(this).closest('.trCost').find('.job_screw').val(screw);

    // $('.job_model').val(model);

    // $('.job_serial').val(sku);

    // $('.job_weight').val(weight);

    // $('.job_screw').val(screw);

});



$(document).on('change','.companyName',function() {

    var name = $(this).find(':selected').data('name');

    var number = $(this).find(':selected').data('number');

    $('.job_co_name').val(name);

    $('.job_co_number').val(number);

});



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

       // $('[name="image_upon_receipt_job"]').val(responce.camImageDataID);

       // $('[name="image_upon_receipt[]"]').val(responce.camImageDataID);

       $('#ajax-webcam-form').trigger("reset");

       $('#add_ajax_product_webcam').modal('hide');

       $('.add_items').removeAttr('disabled',false);

       $('.save_jo').removeAttr('disabled',false);

       $('#webcamSubmit').removeAttr('disabled',false);

       $("#mi_webcam").html("");

    }

  });

});




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





$('#ajax_agent').on('submit', function(e) {

  e.preventDefault();

  $.ajax({

      url: "{{route('agent.agentAjax')}}",

      type: "POST",

      data: $('#ajax_agent').serialize(),

      success: function( response ) {

          alert(response.success);

        $('#agentajax').html(response.RetailResellerHTML);

        $('#ajax_agent').trigger("reset");

        $('#AgentModel').modal('hide');

        // $('#reset_memo_sub').removeAttr('disabled',false);

        // $('#create_memo_sub').removeAttr('disabled',false);

        // location.reload(true);

      }

     });

   });

$(document).ready(function(){

  $('#agentajax').select2();

})



 Webcam.set({

     width: 320,

     height: 240,

     image_format: 'jpeg',

     jpeg_quality: 90

 });

function take_snapshot() {

// take snapshot and get image data

Webcam.snap( function(data_uri) {

    // display mi_webcam in page

    // document.getElementById('mi_webcam').innerHTML =

    //  '<img src="'+data_uri+'"/>';

     $("#mi_webcam").append('<div class="mi_remove_web_img"><input type="hidden" class="camera_image_upload" name="mi_custom_cam_image[]" value="'+data_uri+'"><img class="mi_custom_cam_image"  src="'+data_uri+'" style="margin: 10px 0;"/> <button class="webcamRemove">x</button></div>');

 } );

}

$("#others").hide();
$(".others").hide();

$("#job_details").change(function(){

  var val=$(this).val();

  if(val=="23")

  {

    // $("#notes").hide();

    $("#others").show();
    $(".others").show();

  }

  else{

    // $("#notes").show();

    $(".others").hide();
    $("#others").hide();

  }

})

$(document).on('change','.job_listing_type',function() {
  var lsType = $(this).val();
  if(lsType != "Watch"){
    $('#job_details').on('change',function(){
      var otherVasl = $(this).val();
      if(otherVasl == 23){
        $('.others').addClass("samClass");
      }else{
        $('.others').removeClass("samClass");
      }
    })
  }
})


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

</script>

@endsection
