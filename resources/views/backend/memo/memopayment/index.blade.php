@extends('backend.layouts.app')



@section('content')



<div class="aiz-titlebar text-left mt-2 mb-3">

	<div class="align-items-center">

			<!-- <h1 class="h3">{{translate('All Payment Terms')}}</h1> -->

	</div>

</div>



<div class="row">

	<div class="col-md-12">

		<div class="card">

		    <div class="card-header row gutters-5">

				<div class="col text-center text-md-left">

					<h5 class="mb-md-0 h6">{{ translate('All Payment Terms') }}</h5>

				</div>

				<div class="btn-group mr-2" role="group" aria-label="Third group">

					<a class="btn btn-soft-primary" href="{{route('memopayment.create')}}" title="{{ translate('add') }}">

						Add Payment Terms	<i class="lar la-plus-square"></i>

					</a>

				</div>

		    </div>

		    <div class="mi_custome_table">

		        <div class="row page_qty_sec">

                    <div class="col-2 d-flex">

                        <form method="get" id="pagination_form" action="{{route('memopayment.index')}}">

                            <label class="fillter_sel_show">show</label>

                            <select class="form-control form-select" id="pagination_use_qty"  aria-label="Default select example" name="pagination_qty">

                            <!-- <option value="10" @if($pagination_qty == 10) selected @endif>10</option> -->

                            <option value="25" @if($pagination_qty == 25) selected @endif>25</option>

                            <option value="50" @if($pagination_qty == 50) selected @endif>50</option>

                            <option value="100" @if($pagination_qty == 100) selected @endif>100</option>

                            <option value="200" @if($pagination_qty == 200) selected @endif>200</option>

                            </select>

                        </form>

                    </div>

                </div>

		        <div class="card-body">

    		        <table class="table aiz-table mb-0" id="memopaymentdetail">

    		            <thead>

    		                <tr>

    		                    <!-- <th>#</th> -->

                            <th>{{translate('Payment Name')}}</th>

                            <th>{{translate('Days')}}</th>

                            <th>{{translate('Percentage')}}</th>

    		                    <th class="text-right">{{translate('Options')}}</th>

    		                </tr>

    		            </thead>

    		            <tbody>

    		                @foreach($memopaymentData as $key => $optData)

    		                    <tr>

                                <!-- <td>{{ $optData->id}}</td> -->

                                <td>{{ $optData->payment_name}}</td>

                                <td>{{ $optData->days}}</td>

                                <td>{{ $optData->percentage}}</td>

    		                        <td class="text-right">

    		                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('memopayment.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">

    		                                <i class="las la-edit"></i>

    		                            </a>

    		                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('memopaymentoption.destroy', $optData->id)}}" title="{{ translate('Delete') }}">

    		                                <i class="las la-trash"></i>

    		                            </a>

    		                        </td>

    		                    </tr>

    		                @endforeach

    		            </tbody>

    		        </table>

								<p>

		              Showing {{ $memopaymentData->firstItem() }} to {{ $memopaymentData->lastItem() }} of {{$memopaymentData->total()}} entries

		            </p>

    		        <div class="aiz-pagination">

                        {{ $memopaymentData->appends(request()->input())->links() }}

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

	// $(document).ready(function() {

	// 	if($('#memopaymentdetail').length > 0){

	// 		$('#memopaymentdetail').DataTable({

	// 			"bPaginate": false

	// 		});

	// 	}



		$("#pagination_use_qty").change(function(){

            $("#pagination_form").submit();

        });

	});

</script>

@endsection

