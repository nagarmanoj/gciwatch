@extends('backend.layouts.app')



@section('content')



<div class="aiz-titlebar text-left mt-2 mb-3">

	<div class="align-items-center">

			<h1 class="h3">{{translate('All Appraisal')}}</h1>

	</div>

</div>



<div class="row">

	<div class="col-md-12">

		<div class="card">

		    <div class="card-header row gutters-5">

				<div class="col text-center text-md-left">

					<h5 class="mb-md-0 h6">{{ translate('All Appraisal') }}</h5>

				</div>
                @if(Auth::user()->user_type == 'admin' || in_array('62', json_decode(Auth::user()->staff->role->inner_permissions)))
				<div class="btn-group mr-2" role="group" aria-label="Third group">

					<a class="btn btn-soft-primary" href="{{route('Appraisal.create')}}" title="{{ translate('add') }}">

						Add Appraisal	<i class="lar la-plus-square"></i>

					</a>

				</div>
                @endif
                <!-- <div class="d-flex my-2">

                    <a href="#"  class="btn btn-primary mr-2">Export Data</a>

                </div> -->

				<!-- <div class="col-md-2"> -->

				

                <!-- <div class="form-group mb-0">

                    <input type="text" class="form-control form-control-sm" id="search" name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type & Enter') }}">

                </div> -->

            </div>

		    </div>
            <form class="" id="sort_products" action="" method="GET"> 
                <div class="card-header row gutters-5 appraisal_form_sec purchases_form_sec"> 
                <div class="col-md-3 d-flex page_form_secs">
                <input type="hidden" name="search" id="searchinputfield">
                    <label class="fillter_sel_show m-auto"><b>Show</b></label>
                    <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0 purchases_filter" name="pagination_qty" id="purchases_pagi" data-live-search="true">
                        <option  @if($pagination_qty == 25) selected @endif>{{translate('25')}}</option>

                        <option  @if($pagination_qty == 50) selected @endif>{{translate('50')}}</option>

                        <option  @if($pagination_qty == 100) selected @endif>{{translate('100')}}</option>

                        <option  @if($pagination_qty == 'All') selected @endif>{{translate('All')}}</option>

                    </select>

                    <button type="submit" id="purchases_pagi_sub"  class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>

                </div>
                <div class="col-md-6 d-flex"></div>
                <div class="col-md-3 d-flex search_form_sec">
                    <label class="fillter_sel_show m-auto"><b>Search</b></label>
                    <input type="text" class="form-control form-control-sm sort_search_val"  @isset($sort_search) value="{{ $sort_search }}" @endisset>
                    <button type="button" class="search_btn_field"><i class="las la-search aiz-side-nav-icon" ></i></button>
			    </div>
                </div>

                <div class="mi_custome_table">

                <div class="card-body">

                    <table class="table aiz-table mb-0" >

                        <thead>

                            <tr>

                                <th>#</th>

                            <!-- <th><input type="checkbox" onclick="toggle(this);" id="select_count"  name="all[]"></th> -->

                            <th>{{translate('Template Name')}}</th>

                            <th>{{translate('Listing Type')}}</th>

                            <th>{{translate('Stock id')}}</th>

                            <th>{{translate('Manufacturer')}}</th>

                            <th>{{translate('Model Name')}}</th>

                            <th>{{translate('Factory Model')}}</th>

                            <th>{{translate('Serial No')}}</th>

                            <th>{{translate('Sp Value')}}</th>

                            <th>{{translate('Appraisal Date')}}</th>

                            <th class="text-right">{{translate('Options')}}</th>

                            </tr>

                        </thead>

                        <tbody>
                            @php $count=0; @endphp

                            @foreach($appraisal as $key => $optData)
                            @php $count++; @endphp

                                <tr>

                                    <!-- <td><input type="checkbox" class="memo_checkbox" value="{{ $optData->id}}" name="all_memo[]" id="memo_data"></td> -->

                                    <td onclick="modelAp({{$optData->id}})">{{ $count}}</td>

                                    <td onclick="modelAp({{$optData->id}})">{{ $optData->template_name}}</td>

                                    <td onclick="modelAp({{$optData->id}})">{{ $optData->listing_type}}</td>
                                    <td onclick="modelAp({{$optData->id}})">{{ $optData->stock_id}}</td>
                                    <td onclick="modelAp({{$optData->id}})">{{ $optData->manufacturer}}</td>
                                    <td onclick="modelAp({{$optData->id}})">{{ $optData->model_name}}</td>
                                    <td onclick="modelAp({{$optData->id}})">{{ $optData->factory_model}}</td>
                                    <td onclick="modelAp({{$optData->id}})">{{ $optData->serial_no}}</td>
                                    <td onclick="modelAp({{$optData->id}})">{{ $optData->sp_value}}</td>
                                    <td onclick="modelAp({{$optData->id}})"> {{$optData->appraisal_date}} </td>
                                    <td class="text-right">
                                    @if(Auth::user()->user_type == 'admin' || in_array('63', json_decode(Auth::user()->staff->role->inner_permissions)))
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('Appraisal.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                        @endif
                                        @if(Auth::user()->user_type == 'admin' || in_array('61', json_decode(Auth::user()->staff->role->inner_permissions)))
                                        <a class="btn btn-soft-success btn-icon btn-circle btn-sm"  href="#"  title="{{ translate('View') }}" data-toggle="modal" onclick="modelAp({{$optData->id}})" >
                                            <i class="las la-eye"></i>
                                       </a>
                                       @endif
                                        <a class="btn btn-soft-success btn-icon btn-circle btn-sm"  type="button"  onclick="micustomPrint({{$optData->id}})"  target="_blank"><i class="las la-print"></i></a>
                                        @if(Auth::user()->user_type == 'admin' || in_array('64', json_decode(Auth::user()->staff->role->inner_permissions)))
                                        <a href="{{route('appraisal.destroy', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" class="btn btn-soft-success btn-icon btn-circle btn-sm" onclick="return confirm('Are you sure?');">

                                                <i class="las la-trash"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p>
              Showing {{ $appraisal->firstItem() }} to {{ $appraisal->lastItem() }} of total {{$appraisal->total()}} entries
            </p>
            <div class="aiz-pagination">
              @if($pagination_qty != "all")
                {{ $appraisal->appends(request()->input())->links() }}
              @endif
            </div>
                </div>
                </div>
            </form>
		</div>
	</div>
</div>
<!-- Modal -->

<div class="modal fade" id="view" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title template_name" id="exampleModalLabel"></h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <div class="modal-body">

        <div class="aiz-titlebar text-left mt-2 mb-3">

        <!-- <h5 class="mb-0 h6">{{translate('My Watch')}}</h5> -->

        <table class="table aiz-table mb-0">

		            <tbody>

		                <tr>

                            <td>{{translate('Listing Type')}}</td>

                            <td class="listing_type"></td>

		                </tr>

                        <tr>

                            <td>{{translate('Stock Id')}}</td>

                            <td class="stock_id"></td>

		                </tr>

                        <tr>

                            <td>{{translate('Manufacturer')}}</td>

                            <td class="manufacturer"></td>

		                </tr>

                        <tr>

                            <td>{{translate('Model Name')}}</td>

                            <td class="model_name"></td>

		                </tr>

                        <tr>

                            <td>{{translate('Factory Model')}}</td>

                            <td class='factory_model'></td>

		                </tr>

                        <tr>

                            <td>{{translate('Serial No')}}</td>

                            <td class='serial_no'></td>

		                </tr>

                        <tr>

                            <td>{{translate('Size')}}</td>

                            <td class='size'></td>

		                </tr>

                        <tr>

                            <td>{{translate('Dial')}}</td>

                            <td class='dial'></td>

		                </tr>

                        <tr>

                            <td>{{translate('Bazel')}}</td>

                            <td class='bazel'></td>

		                </tr>

                        <tr>

                            <td>{{translate('Metal')}}</td>

                            <td class='metal'></td>

		                </tr>

                        <tr>

                            <td>{{translate('Bracelet Meterial')}}</td>

                            <td class='bracelet_meterial'></td>

		                </tr>

                        <tr>

                            <td>{{translate('Crystal')}}</td>

                            <td class='crystal'></td>

		                </tr>

                        <tr>

                            <td>{{translate('Suggested Apparaisad Value')}}</td>

                            <td class='suggested_apparaised_value'></td>

		                </tr>

                        <tr>

                            <td>{{translate('Image')}}</td>

                            <td class='image'> </td>

		                </tr>

                        <tr>

                            <td>{{translate('Appraised For:Name ,Address ,City, State, zipcode')}}</td>

                            <td class='appraised_address'></td>

		                </tr>

                        <tr>

                            <td>{{translate('Appraisal Number')}}</td>

                            <td class='appraisal_number'></td>

		                </tr>

                        <tr>

                            <td>{{translate('Appraisal Location')}}</td>

                            <td class='appraisal_location'></td>

		                </tr>

                        <tr>

                            <td>{{translate('Appraisal Date')}}</td>

                            <td class='appraisal_date'></td>

		                </tr>

		            </tbody>

		        </table>
                <p>
              Showing {{ $appraisal->firstItem() }} to {{ $appraisal->lastItem() }} of total {{$appraisal->total()}} entries
            </p>
            <div class="aiz-pagination">
              @if($pagination_qty != "all")
                {{ $appraisal->appends(request()->input())->links() }}
              @endif
            </div>


    </div>

    <div class="">

    </div>

</div>


<!-- print function  -->

<div id="print_doc">
    <style>
        @media print {
            @page {
                margin: 0mm 0mm 0mm 2mm !important;
                width:100% !important;
                size: A4;
                }
            body{
            padding:0px !important;
            border:none !important;
            }
            .mi_print{
                wodth:100% !important;
                padding:10px;
            }
            .print_sec.left .print_innner_sec{
                margin-right:2px;
                padding:5px 5px;
            }
            .print_sec.right .print_innner_sec{
               margin-left:2px;
               padding:5px 5px;
            }
            .imgLogo{
                width:350px;
                margin-top:20px !important;
                margin:0 auto;
            }
            .report_text h3{
                margin-bottom:10px;
                margin-top:20px;
                font-weight: 600;
                font-size:30px;
            }
            .report_text p{
                font-size:22px;
                margin:0px;
                padding-left:0px;
                line-height:40px !important;
            }
            .appraised_value{
                margin-top:20px;
                margin-bottom:10px;
            }
            .appraised_value p{
                border:5px solid #f3cb84;
                padding:5px 5px;
                font-size:25px;
                font-weight: 600;
            }
            .product_img{
                width:100%;
                text-align:center;
            }
            .product_img p{
                text-align:left;
                font-size:22px;
            }
            .product_img img{
                width:500px;
                height:500px;
                margin:0 auto;
            }
            .appraised_ditel{
                width:100%;
                text-align:center;
                color:#000 !important;
                /*background-image: url('https://gcijewel.com/public/uploads/all/bg-black.jpg') !important;*/
                background-color:#f3cb84 !important;
            }
            .signature_div{
                text-align:center;
            }
            .appraised_ditel h3{
                 font-weight: 600;
            }
            .appraised_ditel p{
                font-size:20px;
            }
            .appraisal_nu-loc-dat h3{
                font-weight: 600;
            }
            .appraisal_nu-loc-dat p{
                font-size:22px;
                margin:0px;
                padding-left:15px;
            }
            .signature_div p{
                font-size:20px;
            }
            .appraisal_dec p{
                font-size:16px;
                text-align: justify;
                
            }
            .print_sec.right{
                margin-top:8% !important;
            }
            p.retort_apr{
                font-weight: 600;
                font-size:16px;
            }
            
        }
    </style>
    <div class="mi_print">
        <div class="row">
            <div class="print_sec left col-12">               
                 <div class="print_innner_sec">
                   <center> <div style='backgroud:#000;' class="mywatch retort_apr"> <img src='https://gcijewel.com/public/uploads/all/XntIbIwqcnhixLCgdDcJlo41Rb5UZ3hkSGj0KREf.jpg' class='imgLogo retort_apr'> </div>
                    <div style='backgroud:#000; ' class="mylux_watch retort_apr"> <img src='https://gcijewel.com/public/uploads/all/logo_my.png' class='imgLogo retort_apr'> </div></center>
                    <div class="report_text">
                    <h3 class='retort_apr'>Appraisal Report</h3>
                    <p style="letter-spacing: 0px !important;text-align:center;" class='retort_apr'>The following appraisal service report identifies the characteristics of the referenced watch at the time of inspection. This appraisal report establishes the new retail replacement value in the most common and appropriate jewelry markets, to provide a basis for obtaining insurance.</p>
                    <h3 style="margin-top:10px !important;" class='retort_apr'>Features</h3>
                    <p class='retort_apr'><b>Manufacturer</b> : <span class="Manufacturer_print"> </span></p>
                    <p class='retort_apr'><b>Model Name</b> : <span class="model_number_print"> </span></p>
                    <p class='retort_apr'><b>Factory Model</b> : <span class="factory_model_print"> </span></p>
                    <p class='retort_apr'><b>Serial No.</b> : <span class="serial_no_print"> </span></p>
                    <p class='retort_apr'><b>size</b> : <span class="size_print"> </span></p>
                    <p class='retort_apr'><b>Dial</b> : <span class="dial_print"> </span></p>
                    <p class='retort_apr'><b>Bezal</b> : <span id="bezalPrint"> </span></p>
                    <p class='retort_apr'><b>Metal</b> : <span class="metal_print"> </span></p>
                    <p class='retort_apr'><b>Bracelet Meterial</b> : <span class="bracelet_meterial_print"> </span></p>
                    <!--<p><b>Crystal</b> : <span class="Crystal_print"> </span></p>-->
                    </div>
                    <div class="appraised_value retort_apr">
                        <p style="text-align:center;" class='retort_apr'>Suggested Appraised Value*: <span  class="sp_value_print"> </span></p>
                    </div>
                    <div class='image_print product_img retort_apr' >
                    </div>
                </div>
            </div>
        
            <div class="print_sec right col-12">
                <div class="print_innner_sec retort_apr">
                    <center> <div style='backgroud:#000;' class="mywatch"> <img src='https://gcijewel.com/public/uploads/all/XntIbIwqcnhixLCgdDcJlo41Rb5UZ3hkSGj0KREf.jpg' class='imgLogo'> </div>
                    <div style='backgroud:#000; ' class="mylux_watch"> <img src='https://gcijewel.com/public/uploads/all/logo_my.png' class='imgLogo'> </div></center>
                    <br>
                    <h3 style=" margin-bottom:10px; margin-top:10px;font-weight: 600; font-size:30px;text-align:center;" class='retort_apr'>Appraised For</h3>
                    <div class="appraised_ditel">
                        <h3 class='retort_apr'>Danny yoo</h3>
                        <p class='retort_apr'>2215 cedar spring rd apt 204<br>Dallas texas</p>
                    </div>
                    <div class="signature_div">
                        <p class='retort_apr'>Inspected/Appraised by Myluxapp.com</p>
                        <br>
                        <p class='retort_apr'></p>
                        <br>
                    </div>
                    <div class="appraisal_nu-loc-dat retort_apr">
                    <h3 class='retort_apr'>Details of Appraisal</h3>
                    <p class='retort_apr'>Appraisal Number: <b><span class='appraisal_number_print'></span></b></p>
                    <p class='retort_apr'>Appraisal Location: <b><span class='appraisal_location_print'></span></b></p>
                    <p class='retort_apr'>Date: <b><span class='appraisal_date_print'></span></b></p>
                    </div>
                    <center> <div style='backgroud:#000;' class="mywatch"> <img src='https://gcijewel.com/public/uploads/all/XntIbIwqcnhixLCgdDcJlo41Rb5UZ3hkSGj0KREf.jpg' class='imgLogo retort_apr'> </div>
                    <div style='backgroud:#000; ' class="mylux_watch"> <img src='https://gcijewel.com/public/uploads/all/logo_my.png' class='imgLogo retort_apr'> </div></center>
                    <br>
                    <div class="appraisal_dec retort_apr">
                        <p class='retort_apr'>Any questions regarding this appraisal can be sent to myWatchDealer.com via email at sales@mywatchdealer.com or can be mailed to:<br> myWatchDealer.com 650 S. Hill St. Suite 317  Los Angeles, CA 90014 213-985-3753 </p>
                        <p class='retort_apr'>*myluxapp.com is a registered DBA of Shak Corp Inc. This is not an invoice or receipt of sale. Suggested MSRP/Appraised Value is calculated based on estimated market prices for this item sold in other retail stores at the time of appraisal. myluxapp.com provides appraisal and other related services for Watches, Jewelry, Diamonds and Gold. myluxapp.com is a not affiliated with any of the brands of watches, jewelry, diamonds and/or gold that have been appraised in this report, unless specified otherwise. The values set forth herein are estimates of the current market price at which the appraised jewelry may be purchased in theaverage fine jewelry store at the time of appraisal. Because jewelry appraisal and evaluation is subjective, estimates of replacement value may vary from one appraiser toanother and such a variance does not necessarily constitute error on part of theappraiser. This appraisal should not be used as the basis for the purchase or sale of the items set forth herein and is provided, solely as an estimate of approximate replacement values of said items at this time and place. Accordingly, we assume no liability with respect to any legal action that may be taken, as a result of, the information contained in this appraisal.</p>
                    </div>
                   
                </div>
            </div>
        </div>

    </div>
</div>



@endsection

@section('modal')

    @include('modals.delete_modal')

@endsection

@section('script')

    <script type="text/javascript">
        $('.retort_apr').hide();

$('.imgLogo').hide();

        $(document).on("change", ".check-all", function() {

            if(this.checked) {

                // Iterate each checkbox

                $('.check-one:checkbox').each(function() {

                    this.checked = true;

                });

            } else {

                $('.check-one:checkbox').each(function() {

                    this.checked = false;

                });

            }



        });



        $(document).ready(function(){

               $(document).on("click", ".select-tr-edit", function() {

                var editHref = $(this).data('href');

               window.location.href = editHref;

        });

        });



        function update_todays_deal(el){

            if(el.checked){

                var status = 1;

            }

            else{

                var status = 0;

            }

            $.post('{{ route('products.todays_deal') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){

                if(data == 1){

                    AIZ.plugins.notify('success', '{{ translate('Todays Deal updated successfully') }}');

                }

                else{

                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');

                }

            });

        }



        function update_published(el){

            if(el.checked){

                var status = 1;

            }

            else{

                var status = 0;

            }

            $.post('{{ route('products.published') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){

                if(data == 1){

                    AIZ.plugins.notify('success', '{{ translate('Published products updated successfully') }}');

                }

                else{

                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');

                }

            });

        }



        function update_approved(el){

            if(el.checked){

                var approved = 1;

            }

            else{

                var approved = 0;

            }

            $.post('{{ route('products.approved') }}', {

                _token      :   '{{ csrf_token() }}',

                id          :   el.value,

                approved    :   approved

            }, function(data){

                if(data == 1){

                    AIZ.plugins.notify('success', '{{ translate('Product approval update successfully') }}');

                }

                else{

                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');

                }

            });

        }



        function update_featured(el){

            if(el.checked){

                var status = 1;

            }

            else{

                var status = 0;

            }

            $.post('{{ route('products.featured') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){

                if(data == 1){

                    AIZ.plugins.notify('success', '{{ translate('Featured products updated successfully') }}');

                }

                else{

                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');

                }

            });

        }



        function sort_products(el){

            $('#sort_products').submit();

        }



        function bulk_delete() {

            var data = new FormData($('#sort_products')[0]);

            $.ajax({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                },

                url: "{{route('bulk-product-delete')}}",

                type: 'POST',

                data: data,

                cache: false,

                contentType: false,

                processData: false,

                success: function (response) {

                    if(response == 1) {

                        location.reload();

                    }

                }

            });

        }

        function toggle(source) {

            var checkboxes = document.querySelectorAll('input[type="checkbox"]');

            for (var i = 0; i < checkboxes.length; i++) {

                if (checkboxes[i] != source)

                    checkboxes[i].checked = source.checked;

            }

        }

       function modelAp(id)

        {

           $.ajax({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                },

               type:'post',

               url:'{{route("Appraisal_view.ajax")}}',

               data:{id : id},

               dataType:'json',

               success:function(response)

               {
                //    alert(response.img_data);
                $("#view").modal("toggle");

                $('.listing_type').text(response.data['listing_type']);
                $('.stock_id').text(response.data['stock_id']);

                $('.manufacturer').text(response.data['manufacturer']);

                $('.model_name').text(response.data['model_name']);

                $('.factory_model').text(response.data['factory_model']);

                $('.serial_no').text(response.data['serial_no']);

                $('.size').text(response.data['size']);

                $('.dial').text(response.data['dial']);

                $('.bazel').text(response.data['bazel']);

                $('.metal').text(response.data['metal']);

                $('.bracelet_meterial').text(response.data['bracelet_meterial']);

                $('.crystal').text(response.data['crystal']);

                $('.suggested_apparaised_value').text(response.data['sp_value']);

                $('.image').html(response.html);

                $('.appraised_address').text(response.data['appraised_name'] +' , '+ response.data['appraised_address'] +' , '+ response.data['appraisal_city ']+' , '+ response.data['appraisal_state'] +' , '+response.data['appraisal_zipcode']);

                $('.appraisal_number').text(response.data['appraisal_number']);

                $('.appraisal_location').text(response.data['appraisal_location']);

                $('.appraisal_date').text(response.data['appraisal_date']);

                $('.template_name').text(response.data['template_name']);



                //    alert(response.template_name);

               }

           });

        }

        // $("#print_doc").hide();
        // $('#print_doc_mylux').hide();
            
        function micustomPrint(id){

            // alert(id);

            $.ajax({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                },

               type:'post',

               url:'{{route("Appraisal_view.ajax")}}',

               data:{id : id},

               dataType:'json',

               success:function(response)

               {   
                        $('.imgLogo').show();
                        $('.retort_apr').show();
                        $('.mylux_watch').addClass('myLuxWatch');
                        $('.mywatch').addClass('my_watch');
                //    alert(response.data['template_name']);
                    var printContents = document.getElementById("print_doc").innerHTML;

                    var originalContents = document.body.innerHTML;

                    document.body.innerHTML = printContents;

                if(response.data['template_name']=='mywatch')
                 {
                     $('.my_watch').show();
                     $('.myLuxWatch').hide();
                 }
                 else
                 {
                     $('.myLuxWatch').show();
                     $('.my_watch').hide();
                 }
                 var formatter = new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: 'USD',
                                });
                    $("#print_doc").show();
                    
                    $('.my_lux_watch').hide();

                    $('.Manufacturer_print').text(response.data['manufacturer']);

                    $('.model_number_print').text(response.data['model_name']);

                    $('.factory_model_print').text(response.data['factory_model']);

                    $('.serial_no_print').text(response.data['serial_no']);

                    $('.size_print').text(response.data['size']);
                    var imghtml='<p><b>image</b></p>'+ response.html;
                    $('.image_print').html(imghtml);

                    $('.dial_print').text(response.data['dial']);

                    $('#bezalPrint').text(response.data['bazel']);

                    $('.metal_print').text(response.data['metal']);

                    $('.bracelet_meterial_print').text(response.data['bracelet_meterial']);

                    $('.Crystal_print').text(response.data['crystal']);

                    $('.sp_value_print').text(formatter.format(response.data['sp_value']));

                    $('.appraisal_number_print').text(response.data['appraisal_number']);

                    $('.appraisal_location_print').text(response.data['appraisal_location']);

                    $('.appraisal_date_print').text(response.data['appraisal_date']);

                    window.print();

                    document.body.innerHTML = originalContents;

                    return true;

               }

            });

        }

        // $(document).ready(function() {

        //     if($('#appraisal_data').length > 0)

        //     {

        //         $('#appraisal_data').DataTable({

        //             "bPaginate": false,
        //             "searching": false

        //          });

        //         }

        //  });

 
         $(document).on('click','.search_btn_field',function() {
			prosearchform();
		});
		$(".sort_search_val").keypress(function(e){
			if(e.which == 13) {
				prosearchform();
			}
		});
		function prosearchform(){
			var searchVal = $('.sort_search_val').val();
			$('#searchinputfield').val(searchVal);
			$("#sort_products").submit();
		}
		function sort_products(el){
            $('#sort_products').submit();
        }
    </script>

@endsection

