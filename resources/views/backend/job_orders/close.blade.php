@extends('backend.layouts.app')



@section('content')



<div class="aiz-titlebar text-left mt-2 mb-3">

	<div class="align-items-center">

			<h1 class="h3">{{translate('All Job Orders (Close)')}}</h1>

	</div>

</div>

<div class="row">

	<div class="col-md-12">



    <div class="dropdown mb-2 mb-md-0 trans_mi">
        <div class="list_bulk_btn">

                <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">

                    <i class="las la-bars fs-20"></i>

                </button>

                <div class="dropdown-menu dropdown-menu-right">
                @if(Auth::user()->user_type == 'admin' || in_array('36', json_decode(Auth::user()->staff->role->inner_permissions)))
                    <form class="delete_section" action="{{route('job_ordres_delete')}}" method="post">

                        @csrf

                        <input type="hidden" name="checked_id" id="checkox_pro" value="">

                        <button id="job_order_delete" type="submit" style="border:none;color:#797d91;padding-right:85px;background:#fff;" class="w-100 exportPro-class" disabled>Delete selection</button>

                    </form>
                    @endif
                    @if(Auth::user()->user_type == 'admin' || in_array('37', json_decode(Auth::user()->staff->role->inner_permissions)))

                    <form class="" action="{{route('job_orders_export.index')}}" method="post">

                        @csrf

                        <input type="hidden" name="checked_id" id="checkox_pro_export" value="">

                        <button id="job_order_export" style="border:none;color:#797d91;padding-right:90px;background:#fff;"  type="submit" class="w-100 exportPro-class" disabled>Bulk export</button>

                    </form>
                @endif
                </div>
                </div>

            </div>

		<div class="card" style="margin-top:20px;">

		    <div class="card-header row gutters-5">

				<div class="col text-center text-md-left">

					<h5 class="mb-md-0 h6">{{ translate('All Job Orders') }}</h5>

				</div>

		    </div>

            <div class="mi_custome_table">

                <form method="get" id="pagination_form" action="{{route('job_orders.close')}}">

                    <div class="row page_qty_sec product_search_header">

                        <div class="col-2 d-flex page_form_sec">



                                <label class="fillter_sel_show"><b>Show</b></label>



                                <select class="form-control form-select" id="pagination_use_qty"  aria-label="Default select example" name="pagination_qty">



                                <!-- <option value="10" @if($pagination_qty == 10) selected @endif>10</option> -->



                                <option value="25" @if($pagination_qty == 25) selected @endif>25</option>



                                <option value="50" @if($pagination_qty == 50) selected @endif>50</option>



                                <option value="100" @if($pagination_qty == 100) selected @endif>100</option>



                                <option value="200" @if($pagination_qty == 200) selected @endif>All</option>



                                </select>



                        </div>

                        <div class="col-6 d-flex search_form_sec">



                            <label class="fillter_sel_show m-auto"><b>Search</b></label>



                            <input type="text" name="search" class="form-control form-control-sm sort_search_val"  @isset($sort_search) value="{{ $sort_search }}" @endisset>



                             <button type="button" class="search_btn_field"><i class="las la-search aiz-side-nav-icon" ></i></button>



                        </div>

                    </div>

                </form>

		        <div class="card-body">

                    <table class="table aiz-table mb-0" id="Job_orders_all">

                        <thead>

                          <tr>

                            <th data-orderable="false">

                                <input type="checkbox" class="select_count" id="select_count"  name="all[]">

                            </th>

                            <th>{{translate('Image')}}</th>

                            <th>{{translate('Job Order')}}</th>

                            <th>{{translate('Customer')}}</th>

                            <th>{{translate('Customer Number')}}</th>

                            <th>{{translate('Model')}}</th>

                            <th>{{translate('Serial')}}</th>

                            <th>{{translate('Stock ID')}}</th>

                            <th>{{translate('Date Entered')}}</th>

                            <th>{{translate('Due Date')}}</th>

                            <th>{{translate('Agent')}}</th>

                            <th>{{translate('Job Status')}}</th>

                            <th class="text-center">{{translate('Options')}}</th>

                          </tr>

                        </thead>

                        <tbody>

                          @foreach($jobOrderData  as $key => $jobData)

                             @php

                                $ProImage = uploaded_asset($jobData->image_upon_receipt_job);



                            @endphp

                                <tr>

                                    <td>

                                        <input type="checkbox" class="pro_checkbox" data-id="{{$jobData->id}}" name="all_pro[]" value="{{$jobData->id}}">

                                    </td>

                                    <td> <a style="text-decoration:none;color:#000;" href="{{ route('job_orders.edit',['id'=>$jobData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}"  title="{{ translate('Edit') }}"><img src="{{$ProImage}}" width="80"></a></td>

                                    <td> <a style="text-decoration:none;color:#000;" href="{{ route('job_orders.edit',['id'=>$jobData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}"  title="{{ translate('Edit') }}">{{ $jobData->job_order_number}}</a></td>
                                    @if($jobData->company_name== 0)
									<td> <a style="text-decoration:none;color:#000;" href="{{ route('job_orders.edit',['id'=>$jobData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}"  title="{{ translate('Edit') }}">Stock</a></td>
									@else
                                    @if($jobData->customer_group=='reseller')
                                        <td> <a style="text-decoration:none;color:#000;" href="{{ route('job_orders.edit',['id'=>$jobData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}"  title="{{ translate('Edit') }}">{{ $jobData->c_name}}</a></td>
                                        @else
                                        <td> <a style="text-decoration:none;color:#000;" href="{{ route('job_orders.edit',['id'=>$jobData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}"  title="{{ translate('Edit') }}">{{ $jobData->customer_name}}</a></td>
                                        @endif
                                        @endif
                                    <!-- <td> <a style="text-decoration:none;color:#000;" href="{{ route('job_orders.edit',['id'=>$jobData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}"  title="{{ translate('Edit') }}">{{ $jobData->c_name}}</a></td> -->

                                    <td> <a style="text-decoration:none;color:#000;" href="{{ route('job_orders.edit',['id'=>$jobData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}"  title="{{ translate('Edit') }}">{{ $jobData->contact_number}}</a></td>

                                    <td> <a style="text-decoration:none;color:#000;" href="{{ route('job_orders.edit',['id'=>$jobData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}"  title="{{ translate('Edit') }}">{{ $jobData->model_number}}</a></td>

                                    <td> <a style="text-decoration:none;color:#000;" href="{{ route('job_orders.edit',['id'=>$jobData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}"  title="{{ translate('Edit') }}">{{ $jobData->serial_number}}</a></td>

                                    <td> <a style="text-decoration:none;color:#000;" href="{{ route('job_orders.edit',['id'=>$jobData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}"  title="{{ translate('Edit') }}">{{ $jobData->stock_id}}</a></td>

                                    <td> <a style="text-decoration:none;color:#000;" href="{{ route('job_orders.edit',['id'=>$jobData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}"  title="{{ translate('Edit') }}">{{ date("m/d/20y", strtotime($jobData->estimated_date_return))}}</a></td>

                                    <td> <a style="text-decoration:none;color:#000;" href="{{ route('job_orders.edit',['id'=>$jobData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}"  title="{{ translate('Edit') }}">{{ date("m/d/20y", strtotime($jobData->estimated_date))}}</a></td>

                                    <td> <a style="text-decoration:none;color:#000;" href="{{ route('job_orders.edit',['id'=>$jobData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}"  title="{{ translate('Edit') }}">{{ $jobData->agent_name}}</a></td>

                                    <td> <a style="text-decoration!important:none;color:#000!important;" href="{{ route('job_orders.edit',['id'=>$jobData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}"  title="{{ translate('Edit') }}">@if($jobData->job_status==1)

                                            {{translate('Post Due')}}

                                         @elseif($jobData->job_status==2)

                                            {{translate('Open')}}

                                        @elseif($jobData->job_status==3)

                                            {{translate('Pending')}}

                                        @elseif($jobData->job_status==4)

                                            {{translate('On Hold')}}

                                        @elseif($jobData->job_status==5)

                                            {{translate('Closed')}}

                                         @endif

                                        </a></td>

                                    <td class="text-right" style="height:60px;">

                                        <a class="btn btn-soft-success btn-icon btn-circle btn-sm"  href="{{ route('job_orders.activity',['id'=>$jobData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" target="_blank" title="{{ translate('Activity') }}"> <i class="las la-history"></i> </a>
                                        @if(Auth::user()->user_type == 'admin' || in_array('32', json_decode(Auth::user()->staff->role->inner_permissions)))
                                        <a class="btn btn-soft-success btn-icon btn-circle btn-sm"  href="{{ route('job_orders.view',['id'=>$jobData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" target="_blank" title="{{ translate('View') }}"> <i class="las la-eye"></i> </a>
                                            @endif
                                            @if(Auth::user()->user_type == 'admin' || in_array('35', json_decode(Auth::user()->staff->role->inner_permissions)))
                                        <a href="{{ route('job_orders.edit',['id'=>$jobData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" class="btn btn-soft-success btn-icon btn-circle btn-sm" title="{{ translate('Edit') }}"> <i class="las la-edit"></i></a>
                                        @endif
                                        @if(Auth::user()->user_type == 'admin' || in_array('36', json_decode(Auth::user()->staff->role->inner_permissions)))
                                        <a href="{{ route('job_orders.destroy',['id'=>$jobData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" class="btn btn-soft-danger btn-icon btn-circle btn-sm" data-href="#" title="{{ translate('Delete') }}"> <i class="las la-trash"></i></a>
                                        @endif

                                    </td>

                                </tr>

                          @endforeach

                        </tbody>

                    </table>
    					<p>
    						Showing {{ $jobOrderData->firstItem() }} to {{ $jobOrderData->lastItem() }} of {{$jobOrderData->total()}} entries
    					</p>

                    <div class="aiz-pagination">{{$jobOrderData->appends(request()->input())->links()}}</div>

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

        $(document).ready(function() {

            $(document).on('click','.pro_checkbox',function(){

                productCheckbox();

                productCheckboxExport();

            });

        });

        function productCheckbox(){

            var proCheckID = [];

            $.each($("input[name='all_pro[]']:checked"), function()

            {

                proCheckID.push($(this).val());

            });

            console.log(proCheckID);

            var proexpData =	JSON.stringify(proCheckID);

            $('#checkox_pro').val(proexpData);

            if(proCheckID.length > 0)

            {

                $('#job_order_export').removeAttr('disabled');

            }

            else

            {

                $('#job_order_export').attr('disabled',true);

            }

            if(proCheckID.length > 0)

            {

                $('#job_order_delete').removeAttr('disabled');

            }

            else

            {

                $('#job_order_delete').attr('disabled',true);

            }

        }

        function productCheckboxExport(){

            var proCheckID = [];

            $.each($("input[name='all_pro[]']:checked"), function()

            {

                proCheckID.push($(this).val());

            });

            var proexpData =	JSON.stringify(proCheckID);

            $('#checkox_pro_export').val(proexpData);

            if(proCheckID.length > 0)

            {

                $('#job_order_export').removeAttr('disabled');

            }

            else

            {

                $('#job_order_export').attr('disabled',true);

            }

            if(proCheckID.length > 0)

            {

                $('#job_order_delete').removeAttr('disabled');

            }

            else

            {

                $('#job_order_delete').attr('disabled',true);

            }

        }

        $(document).on('click','.select_count',function() {

            if($(this).is(':checked'))

            {

                $('.pro_checkbox').prop('checked', true);

            }

            else

            {

                $('.pro_checkbox').prop('checked', false);

            }

            productCheckbox();

            productCheckboxExport();

        });

        $(document).on('click','.default-filter-check', function()

        {

            if ($(this).prop('checked')==true)

            {

                var FilterDefaultVal = $('.default-filter-check-value').val();

                if(FilterDefaultVal != '')

                {

                    var FilterDefaultValData =  JSON.parse(FilterDefaultVal);

                    $('.filtered_field').prop('checked',false);

                    console.log(FilterDefaultValData);

                    $.each( FilterDefaultValData, function( keyFilter, valFilter ){

                        $('#filteredColOpt .'+keyFilter).prop('checked',true);

                        console.log(valFilter);

                    });

                }

            }

            else

            {

                $('.filtered_field').prop('checked',false);

            }

            })

         $(document).ready(function() {



        	    $(document).on('click','.search_btn_field',function() {

                    prosearchform();



                });





                $(".sort_search_val").keypress(function(e){



                    if(e.which == 13) {

                        prosearchform();



                    }



                });





        		$("#pagination_use_qty").change(function(){



                    prosearchform();



                });

	    });





    	function prosearchform(){



    	    $("#pagination_form").submit();



    	}



    </script>

@endsection
