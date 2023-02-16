@extends('backend.layouts.app')
@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">

    <div class="row align-items-center">

        <div class="col-auto">

            <!-- <h1 class="h3">{{translate('All Memos')}}</h1> -->

        </div>





        <div class="col text-right">

          <!-- Button trigger modal -->

          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">

            Edit Column

          </button>

        </div>

    </div>

</div>





<div class="row">

	<div class="col-md-12">

		<div class="card">

		    <div class="card-header row gutters-5">

				<div class="col text-center text-md-left">

					<h5 class="mb-md-0 h6">{{ translate('All Open Memos') }}</h5>

				</div>


                @if(Auth::user()->user_type == 'admin' || in_array('29', json_decode(Auth::user()->staff->role->inner_permissions)))
				<div class="btn-group mr-2" role="group" aria-label="Third group">

					<a class="btn btn-soft-primary" href="{{route('memo.create')}}" title="{{ translate('add') }}">

						Add Memo	<i class="lar la-plus-square"></i>

					</a>

				</div>
                @endif
                @if(Auth::user()->user_type == 'admin' || in_array('32', json_decode(Auth::user()->staff->role->inner_permissions)))

                <div class="d-flex my-2">
                    <form class="" action="{{route('memo-export.index')}}" method="post">
                    @csrf
                        <input type="hidden" name="checked_id" id="checkox_momo" value="">
                        <button id="exportDemo-Id" type="submit" class="exportDemo-class btn btn-primary mr-2 text-white" disabled>Export Data</button>
                    </form>
                </div>
                @endif
                <!--

				<form class="" id="sort_products" action="{{route('memo.index')}}" method="GET">
                <div class="col-md-2"> -->

				<!-- <select class="form-control" aria-label="Default select example">

				    <option>Select Option</option>

                  <option  value="">All Record</option>

                  <option value="memos.memo_number">Memo Number</option>

                  <option value="memos.reference">Reference Number</option>

                  <option value="retail_resellers.customer_name">Customer Name</option>

                  <option value="products.stock_id">Stock ID</option>

                  <option value="products.model">Model Number</option>

                </select>

                <div class="form-group mb-0">

                    <input type="text" class="form-control form-control-sm" id="search" name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type & Enter') }}">

                </div> -->

            </div>

		    </div>

		    <div class="mi_custome_table" style="padding-top:15px">





                <form method="get" id="pagination_form" action="{{route('memo.index')}}">



                    <div class="row page_qty_sec product_search_header">



                        <div class="col-2 d-flex page_form_sec">



                                <label class="fillter_sel_show">Show</label>



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

		        <table class="table aiz-table mb-0" id="memo_data">

		            <!-- <thead>

		                <tr> -->

		                    <!-- <th>#</th> -->

						<!-- <th><input type="checkbox" onclick="toggle(this);" id="select_count"  name="all[]"></th>

                        <th>{{translate('Memo Numbers')}}</th>

                        <th>{{translate('Company Name')}}</th>

                        <th>{{translate('Customer Name')}}</th>

                        <th>{{translate('Reference')}}</th>

                        <th>{{translate('Stock ID')}}</th>

                        <th>{{translate('Model Number')}}</th> -->

                        <!-- <th>{{translate('Serial')}}</th> -->

                        <!-- <th>{{translate('Payment')}}</th>

                        <th>{{translate('Sub Total')}}</th>

                        <th>{{translate('Tracking')}}</th>

                        <th>{{translate('Memo Status')}}</th>

                        <th>{{translate('Due Date')}}</th>

                        <th>{{translate('Date')}}</th>

		                    <th class="text-right">{{translate('Options')}}</th>

		                </tr>

		            </thead> -->

		            <thead>

		                <tr>

		                    <!-- <th>#</th> -->

						<th data-orderable="false"><input type="checkbox" class="select_count" id="select_count"  name="all[]"></th>

												@foreach ($FilteredData as $filterkey => $Filter)

                          <th>{{translate($Filter)}}</th>

                        @endforeach

		                    <th class="text-right" data-orderable="false">{{translate('Options')}}</th>

		                </tr>

		            </thead>

		            <tbody>

		                @foreach($memoData as $key => $optData)

		                    @php

		                    $optData->customer_name = $optData->rcustomername;

		                    @endphp

					     	<!-- <tr class="select-tr-edit" onclick="memoeditcallback()" data-href="{{route('memo.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}"> -->

					     	<tr>

                  <td><input type="checkbox" class="memo_checkbox" data-checkBoxId="{{ $optData->id}}"   value="{{ $optData->id}}" name="all_memo[]" ></td>

                  		@foreach ($FilteredData as $filterkey => $Filter)



                        @if($filterkey == 'due_date')
                        @if(!empty($optData->due_date))
                            <td  class="select-tr-edit" onclick="memoeditcallback()" data-href="{{route('memo.edit', ['id'=>$optData->id,  'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="cursor: pointer;">{{ date('m/d/y',strtotime($optData->due_date))}} </td>
                            @else
                            <td  class="select-tr-edit" onclick="memoeditcallback()" data-href="{{route('memo.edit', ['id'=>$optData->id,  'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="cursor: pointer;">00/00/0000 </td>
                            @endif
                            @elseif($filterkey == 'item_status')

                            <td  class="select-tr-edit" onclick="memoeditcallback()" data-href="{{route('memo.edit', ['id'=>$optData->id,  'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="cursor: pointer;">Memo </td>
                            @elseif($filterkey == 'date')
                            <td  class="select-tr-edit" onclick="memoeditcallback()" data-href="{{route('memo.edit', ['id'=>$optData->id,  'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="cursor: pointer;">{{ date('m/d/20y',strtotime($optData->date))}} </td>
                            @elseif($filterkey == 'sub_total')

                            @php

                            setlocale(LC_MONETARY,"en_US");

                            @endphp

                            <td  class="select-tr-edit" onclick="memoeditcallback()" data-href="{{route('memo.edit', ['id'=>$optData->id,  'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="cursor: pointer;"><?php  echo money_format("%(#1n", $optData->$filterkey)."\n";  ?></td>

                            @elseif($filterkey == 'remain_subtotal')

                            @if($optData->$filterkey == "")

                            <td  class="select-tr-edit" onclick="memoeditcallback()" data-href="{{route('memo.edit', ['id'=>$optData->id,  'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="cursor: pointer;"><?php  echo money_format("%(#1n", $optData->sub_total)."\n";  ?></td>

                            @else

                            <td  class="select-tr-edit" onclick="memoeditcallback()" data-href="{{route('memo.edit', ['id'=>$optData->id,  'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="cursor: pointer;"><?php  echo money_format("%(#1n", $optData->$filterkey)."\n";  ?></td>

                            @endif

                            @else

                            <td  class="select-tr-edit" onclick="memoeditcallback()" data-href="{{route('memo.edit', ['id'=>$optData->id,  'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="cursor: pointer;"> {{$optData->$filterkey;}} </td>

                            @endif

                  		@endforeach

                        <td class="text-right">

                            <a class="btn btn-soft-info btn-icon btn-circle btn-sm" href="#" data-memonumber="{{$optData->memo_number}}" title="List Deposit" id="product_related_remaining"  data-toggle="modal" data-target="#exampleModal_list_related">

                                <i class="la la-cc-mastercard"></i>

                            </a>

                            <a class="btn btn-soft-info btn-icon btn-circle btn-sm option_id" data-subtotal="{{$optData->sub_total}}"  data-memonumber="{{$optData->memo_number}}" data-memodate="{{ $optData->date}}"  href="#" title="Deposit Related" data-toggle="modal" data-target="#exampleModal-option">

                                 <i class="la la-dollar"></i>

                            </a>
                            @if(Auth::user()->user_type == 'admin' || in_array('30', json_decode(Auth::user()->staff->role->inner_permissions)))
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('memo.edit', ['id'=>$optData->id, 'status'=>$optData->item_status, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">

                                <i class="las la-edit"></i>

                            </a>
                            @endif
                            @if(Auth::user()->user_type == 'admin' || in_array('31', json_decode(Auth::user()->staff->role->inner_permissions)))

                            <a  class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('memo.destroy', $optData->id)}}" title="{{ translate('Delete') }}">

                                <i class="las la-trash"></i>

                            </a>
                            @endif
                        </td>

                </tr>

		                @endforeach

		            </tbody>

		            <!-- <tbody>

		                @foreach($memoData as $key => $optData) -->

					     	<!--<tr class="select-tr-edit" onclick="memoeditcallback()" data-href="{{route('memo.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}">-->

					     	<!-- <tr>

					     	<td><input type="checkbox" class="memo_checkbox" data-checkBoxId="{{ $optData->id}}"  value="{{ $optData->id}}" name="all_memo[]" ></td>



                            <td>{{ $optData->memo_number}}</td>

                            <td>{{ $optData->company}}</td>

                            <td>{{ $optData->customer_name}}</td>

                            <td>{{ $optData->reference}}</td>

                            <td>{{ $optData->stocks}}</td>

                            <td>{{ $optData->model_numbers}}</td> -->

                             <!-- <td>{{ $optData->payment_name}}</td> -->

                            <!-- <td>{{ $optData->payment}}</td>

                            <td>{{ $optData->sub_total}}</td>

                            <td>{{ $optData->tracking}}</td>

                            <td>Memo</td>



                            <td> {{ \Carbon\Carbon::createFromTimestamp(strtotime($optData->due_date))->format('m ,d-Y')}}  </td>

                           <td>{{ $optData->date}}</td>

		                        <td class="text-right">

		                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('memo.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">

		                                <i class="las la-edit"></i>

		                            </a>

		                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('memo.destroy', $optData->id)}}" title="{{ translate('Delete') }}">

		                                <i class="las la-trash"></i>

		                            </a>

		                        </td>

		                    </tr>

		                @endforeach

		            </tbody> -->

		        </table>

            <p>
              Showing {{ $memoData->firstItem() }} to {{ $memoData->lastItem() }} of  {{$memoData->total()}} entries
            </p>

		        <div class="aiz-pagination">

                {{ $memoData->appends(request()->input())->links() }}

		    </div>

		    </div>

		</div>

	</div>

</div>



<!-- Modal -->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

			<form id="filteredColOpt">

      <div class="modal-header">

        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <div class="modal-body">

				<div class="row">

					@foreach($memoFilteredCOll as $keycol => $valcol)

						<div class="col-lg-3">

							<div class="form-group">

										@php

										$strsel = '';

										@endphp

										@if(in_array($keycol, $columnSelArr))

											@php

											$strsel = 'checked';

											@endphp

										@endif

										<input type="checkbox" {{$strsel}} name="columnArr[{{$keycol}}]" value="{{$valcol}}">&nbsp;&nbsp;{{$valcol}} &nbsp;&nbsp;

							</div>

						</div>

						@endforeach

					</div>

      </div>

			<input type="hidden" name="menu" value="memo">

      <div class="modal-footer">

        <button type="submit" class="btn btn-primary">Save changes</button>

      </div>

		</form>

    </div>

  </div>

</div>





<!--option modal deposit payment -->

<div class="modal fade" id="exampleModal-option" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title memo_number_add_deposit_header" id="exampleModalLabel"></h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <div class="modal-body">

          <p>Please Fill the information below, The Field label marked with * are required Input field</p>

           <form  id="memo_deposit_form">

               <input type='hidden' name='subtotalValue' id="memoSubtotal" >

                <input type="hidden" name="_token" value="{{ csrf_token() }}" />

              <div class="form-group">

                <label for="exampleInputdate1">Date *</label>

                <input type="text" class="form-control" name='date' id="date_option_id" aria-describedby="" placeholder="" readonly>

              </div>

              <div class="form-group">

                <label for="examplememoNumberid">Memo Number *</label>

                <input type="text" class="form-control" name='memo_number' id="memo_number_add_deposit" placeholder="" readonly>

              </div>



              <div class="form-group">

                <label for="memoAmountId">Amount *</label>

                <input type="text" class="form-control" name='deposit_amount' id="memoAmountId" placeholder="">

              </div>



              <div class="form-group">

                <label for="paidByNameId">Paid By *</label>

                <input type="text" class="form-control" name="paid_by" id="paidByNameId" placeholder="">

              </div>

              <div class="form-group">

                <label for="memoNotesId">Notes</label>

                <textarea class="form-control" id="memoNotesId" name='memonotes' rows="3"></textarea>

              </div>

              <div class="modal-footer">

                <button type="submit"  class="btn btn-secondary" >Add Deposit</button>

              </div>

         </form>



        </div>

      </div>



    </div>

  </div>

</div>





<!--remaining deposite related-->

<div class="modal fade" id="exampleModal_list_related" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title" id="exampleModalLabel">Remaining Amount</h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <div class="modal-body">

        <div >

           <table class="table table-striped">

              <thead>

                <tr>

                  <th scope="col">Date</th>

                  <th scope="col">Memo Number</th>

                  <th scope="col">Paid By</th>

                  <th scope="col">Created By</th>

                  <th scope="col">Paid Amount</th>

                  <th scope="col">Total Amount</th>

                </tr>

              </thead>

              <tbody id="product_related_remaining_tbody">



              </tbody>

              <tfoot id='product_related_remaining_tfoot'></tfoot>

            </table>

        </div>

      </div>

      <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>



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

        // function toggle(source) {

        //     var checkboxes = document.querySelectorAll('input[type="checkbox"]');

        //     var getId=[];

        //     for (var i = 0; i < checkboxes.length; i++) {

        //         if (checkboxes[i] != source){

        //             checkboxes[i].checked = source.checked;}

        //             getId[i]=checkboxes[i].value;

        //     }

        //     console.log(getId);

        // }







    // $(document).on("change", ".memo_checkbox", function() {

    //         if(this.checked) {

    //             var v= $(this).val();

    //             console.log(v);

    //         }



    //     });

//

// let exportDemoBtn = document.getElementById('exportDemo-Id');

// exportDemoBtn.addEventListener('click',(e)=>{

//     e.preventDefault();

//     var checkedId={};

//     var checkBOXValue =document.querySelectorAll('.memo_checkbox');

//     for(var i=0;i<checkBOXValue.length;i++){

//         if(checkBOXValue[i].checked){

//             checkedId[i]=checkBOXValue[i].value;

//         }

//     }

//

//     let expoHref = document.querySelector("#exportDemo-Id").getAttribute("data-href");

//

//      $.ajax({

//       type:'post',

//       url:"{{route('memo-export.index')}}",

//       data: {checkedId,"_token": "{{ csrf_token() }}"},

//       dataType:'json',

//       success:function(reso) {

//           console.log(reso.data);

//       }

//     });

// })





    </script>

		<script>

		$('#filteredColOpt').on('submit', function(e) {

			e.preventDefault();

			$.ajaxSetup({

				headers: {

						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

				}

			});

			$.ajax({

					url: "{{route('products.FilterAjax')}}",

					type: "POST",

					data: $('#filteredColOpt').serialize(),

					success: function( response ) {

						if(response.success) {

								location.reload();

						}



					}

				 });

			 });



			 $(document).on('click','.option_id',function(){



			     var memoNumber = $(this).data('memonumber');

			     var subtotal = $(this).data('subtotal')

			     var memoDate = $(this).data('memodate');



			     $('#date_option_id').val(memoDate);

			      $('#memo_number_add_deposit').val(memoNumber);

			       $('.memo_number_add_deposit_header').html('Add Deposit('+memoNumber.toString()+')');

			       $('#memoSubtotal').val(subtotal);







			 });



            $(document).on('submit','#memo_deposit_form',function(e){

                e.preventDefault();

             var memo_depositData =  $('#memo_deposit_form').serializeArray() ;

              const memo_formdata = {}

                memo_depositData.reduce((memo_formdata, e) => {

                    memo_formdata[e.name] = e.value;

                    return memo_formdata

                }, memo_formdata)



            console.log(memo_formdata);



              if(parseFloat(memo_formdata.deposit_amount)<=parseFloat(memo_formdata.subtotalValue)){

                $.ajax({

                method: "POST",

                url: "{{route('admin.memo.payment.deposit')}}",

                data: memo_formdata

                })



                .done((response) => {



                    $('#memo_deposit_form').trigger('reset');



                    window.location.reload(true);



                })

                .fail(function (response) {

                    alert('memo deposit data failed');

                });

              }

              else{

                  alert('Your Remaining amount is less than Paid Amount');

              }

			 });





			 //remaining data

			 $(document).on('click','#product_related_remaining',function(){

			     var memoNumber = $(this).data('memonumber');

			     var html1='';

			     var html2='';





			     $('#product_related_remaining_tbody').html('');

			     $('#product_related_remaining_tfoot').html('');

			     var totalPaidAmount=0;

			     var RemainningAmount = 0;



			      $.ajax({

                method: "GET",

                url: "{{route('admin.memo.payment.receive')}}",

                data: {memoNumber : memoNumber}

                })

                .done((response) => {

                   console.log(response.shipping_charge);

                   console.log(response.saleTax);



                  response.success.map(element=>{



                      totalPaidAmount+=element.payment_depositePaid;

                      RemainningAmount =element.payment_remain;

                       html1+='<tr><td>'+element.updated_at+'</td><td>'+element.memo_deposit_num+'</td><td>'+element.payment_depositePaidBy+'</td><td>'+element.payment_depositePaidBy+'</td><td>'+element.payment_depositePaid+'</td></tr>';

                  });

                  RemainningAmount = RemainningAmount+response.shipping_charge+response.saleTax;

                  console.log(html1);

                     html2+='<tr><td colspan="5">Total With All Tax and Charge </td><td>'+response.order_total+'</td></tr><tr><td colspan="5">Shipping Charges</td><td>'+response.shipping_charge+'</td></tr> <tr><td colspan="5">SaleTax</td><td colspan="5">'+response.saleTax+'</td></tr>    <tr><td colspan="5">Total Paid</td><td>'+totalPaidAmount+'</td></tr><tr><td colspan="5">Remaining Amount</td><td>'+RemainningAmount+'</td></tr>';









                    $('#product_related_remaining_tbody').append(html1);

                     $('#product_related_remaining_tfoot').append(html2);



                })

                .fail(function (response) {

                    alert('Record Not Found');

                });

			 });





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
