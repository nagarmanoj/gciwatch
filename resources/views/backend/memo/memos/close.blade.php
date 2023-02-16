@extends('backend.layouts.app')



@section('content')

@php

setlocale(LC_MONETARY,"en_US");

@endphp

<div class="aiz-titlebar text-left mt-2 mb-3">

    <div class="row align-items-center">

        <div class="col-auto">

            <!-- <h1 class="h3">{{translate('Close Memos')}}</h1> -->

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

					<h5 class="mb-md-0 h6">{{ translate('Close Memos') }}</h5>

				</div>

                @if(Auth::user()->user_type == 'admin' || in_array('29', json_decode(Auth::user()->staff->role->inner_permissions)))
				<div class="btn-group mr-2" role="group" aria-label="Third group">

					<a class="btn btn-soft-primary" href="{{route('memo.create')}}" title="{{ translate('add') }}">

						Add Memo	<i class="lar la-plus-square"></i>

					</a>

				</div>
				
				@endif

              	@if(Auth::user()->user_type == 'admin' || in_array('32', json_decode(Auth::user()->staff->role->inner_permissions)))
			<div class="d-flex my-12">
				<form class="" action="{{route('memo-export.index')}}" method="post">
					@csrf
					<input type="hidden" name="checked_id" id="checkox_momo" value="">
					<button id="exportDemo-Id" type="submit" class="exportDemo-class btn btn-primary mr-2 text-white" disabled>Export Data</button>
				</form>
			</div>
			@endif
		    </div>
		
		    <div class="mi_custome_table mi_scroll">
				<form method="get" id="pagination_form" action="{{route('memo.close')}}">
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

		        <table class="table aiz-table mb-0" id="close_database">

							<thead>

									<tr>

											<!-- <th>#</th> -->
											<th data-orderable="false"><input type="checkbox" class="select_count" id="select_count"  name="all[]"></th>

											@foreach ($FilteredData as $filterkey => $Filter)

												<th>{{translate($Filter)}}</th>

											@endforeach

											<th class="text-right">{{translate('Options')}}</th>

									</tr>

							</thead>

		            <tbody>

		                @foreach($closememoData as $key => $optData)

		                 @php

		                    $optData->customer_name = $optData->rcustomername;

		                    @endphp



		                    <tr>
							<td><input type="checkbox" class="memo_checkbox" data-checkBoxId="{{ $optData->id}}"   value="{{ $optData->id}}" name="all_memo[]" ></td>

													@foreach ($FilteredData as $filterkey => $Filter)

													<!-- @if($filterkey == 'due_date')
													@if(!empty($optData->due_date))
													<td  class="select-tr-edit" onclick="memoeditcallback()" data-href="{{route('memo.edit', ['id'=>$optData->id,  'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="cursor: pointer;">{{ date('m/d/y',strtotime($optData->due_date))}} </td>
													@else
													<td  class="select-tr-edit" onclick="memoeditcallback()" data-href="{{route('memo.edit', ['id'=>$optData->id,  'lang'=>env('DEFAULT_LANGUAGE')] )}}" style="cursor: pointer;">00/00/0000 </td>
                           							 @endif
														@endif -->

													    @if($filterkey == 'sub_total')

													        <td> {{money_format("%(#1n", $optData->$filterkey) }}</td>

															@elseif($filterkey == 'due_date')
															@if(!empty($optData->due_date))
															<td> {{date('m/d/y',strtotime($optData->due_date))}}</td>
															@else
															<td> 00/00/0000</td>
															@endif
															@elseif($filterkey == 'item_status')
															<td>
															<?php
																if($optData->item_status==1 || $optData->item_status==0)
																	{
																		echo "Memo";

																	}

																	elseif($optData->item_status==2)

																	{

																		echo "INVOICE";

																	}

																	elseif($optData->item_status==3)

																	{

																		echo "RETURN";

																	}

																	elseif($optData->item_status==4)

																	{

																		echo "TRADE";

																	}

																	elseif($optData->item_status==5)

																	{

																		echo "VOID";

																	}

																	elseif($optData->item_status==6)

																	{

																		echo "TRADE NGD";

																	}

																	?></td>


													    @else

													         <td> {{$optData->$filterkey;}} </td>

													    @endif





                	                               @endforeach

		                        <td class="text-right">
								@if(Auth::user()->user_type == 'admin' || in_array('30', json_decode(Auth::user()->staff->role->inner_permissions)))
		                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('memo.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">

		                                <i class="las la-edit"></i>

		                            </a>
									@endif
									@if(Auth::user()->user_type == 'admin' || in_array('31', json_decode(Auth::user()->staff->role->inner_permissions)))
		                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('memo.destroy', $optData->id)}}" title="{{ translate('Delete') }}">

		                                <i class="las la-trash"></i>

		                            </a>
								@endif
		                        </td>

		                    </tr>

		                @endforeach

		            </tbody>

		        </table>
            <p>
              Showing {{ $closememoData->firstItem() }} to {{ $closememoData->lastItem() }} of {{$closememoData->total()}} entries
            </p>

		        <div class="aiz-pagination">

                        {{ $closememoData->appends(request()->input())->links() }}

            	</div>

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

			<input type="hidden" name="menu" value="memo_close">

      <div class="modal-footer">

        <button type="submit" class="btn btn-primary">Save changes</button>

      </div>

		</form>

    </div>

  </div>

</div>

@endsection

@section('modal')

    @include('modals.delete_modal')

@endsection

@section('script')

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

				// $('#add_ajax_product_seller').modal('hide');

			}

		 });

	 });



</script>

<script type="text/javascript">

	// $(document).ready(function() {

	// 	if($('#close_database').length > 0){

	// 		$('#close_database').DataTable({

 //                "bPaginate": false,

 //                "searching": false

	// 		});

	// 	}
        $(document).on("change", ".check-all", function() {
			if(this.checked) {
			// Iterate each checkbox
			$('.check-one:checkbox').each(function() {
				this.checked = true;
			});
			}
			else
			{
				$('.check-one:checkbox').each(function() {
					this.checked = false;
				});
			}
		});


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
