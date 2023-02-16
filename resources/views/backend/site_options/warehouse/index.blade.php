@extends('backend.layouts.app')



@section('content')



<div class="aiz-titlebar text-left mt-2 mb-3">

	<div class="align-items-center">

			<!-- <h1 class="h3">{{translate('All Warehouse')}}</h1> -->

	</div>

</div>



<div class="row">

	<div class="col-md-8">

		<div class="card">

		    <div class="card-header row gutters-5">

				<div class="col text-center text-md-left">

					<h5 class="mb-md-0 h6">{{ translate('All Warehouse') }}</h5>

				</div>

				<div class="col-md-4">

					<form class="" id="sort_brands" action="" method="GET">

						<div class="input-group input-group-sm">

					  		<input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name & Enter') }}">

						</div>

					</form>

				</div>

				<div class="btn-group mr-2" role="group" aria-label="Third group">

					<!-- <a class="btn btn-soft-primary" href="{{route('warehouse.create')}}" title="{{ translate('add') }}">

						Add Warehouse	<i class="lar la-plus-square"></i>

					</a> -->

				</div>

		    </div>
			<form class="" id="sort_products" action="" method="GET">
				<select class="form-control form-select" id="pagination_qty" name="pagination_qty" style="display:none">

					<option value="25" @if($pagination_qty == 25) selected @endif>25</option>

					<option value="50" @if($pagination_qty == 50) selected @endif>50</option>

					<option value="100" @if($pagination_qty == 100) selected @endif>100</option>

					<option  @if($pagination_qty == "all") selected @endif value="all">All</option>

				</select>
				<div class="col-2 d-flex page_form_sec">

					<label class="fillter_sel_show m-auto"><b>Show</b></label>

					<select class="form-control form-select" id="pagination_use_qty"  aria-label="Default select example">
						<option value="25" @if($pagination_qty == 25) selected @endif>25</option>

						<option value="50" @if($pagination_qty == 50) selected @endif>50</option>

						<option value="100" @if($pagination_qty == 100) selected @endif>100</option>

						<option value="all" @if($pagination_qty == "all") selected @endif>All</option>
					</select>

				</div>
				<div class="card-body">

					<table class="table aiz-table mb-0">

						<thead>

							<tr>

								<!-- <th>#</th> -->

							<!-- <th>{{translate('Map')}}</th>

							<th>{{translate('Code')}}</th> -->

							<th>{{translate('Name')}}</th>

							<th>{{translate('Description')}}</th>

							<!-- <th>{{translate('Phone')}}</th>

							<th>{{translate('Email')}}</th>-->

								<th>{{translate('Address')}}</th>

								<th class="text-right">{{translate('Options')}}</th>

							</tr>

						</thead>

						<tbody>

							@foreach($partnersData as $key => $optData)

								<tr>

								<!-- <td>{{ $optData->id}}</td> -->

								<!-- <td>

								<div class="row gutters-5">

									<div class="col-auto">

										<img src="{{ uploaded_asset($optData->map)}}" alt="Image" class="size-50px img-fit">

									</div>

								</div>

								</td> -->

								<td>{{ $optData->name}}</td>
								<td>{{ $optData->code}}</td>
								<td>{{ $optData->address}}</td>
								<td class="text-right">
									<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('warehouse.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
										<i class="las la-edit"></i>
									</a>
									<a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('wareoption.destroy', $optData->id)}}" title="{{ translate('Delete') }}">
										<i class="las la-trash"></i>
									</a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					<div class="aiz-pagination">
				@if($pagination_qty != "all")
				<p>
					Showing {{ $partnersData->firstItem() }} to {{ $partnersData->lastItem() }} of total {{$partnersData->total()}} entries
				</p>
					{{ $partnersData->appends(request()->input())->links() }}
					@else
					<p>
					Showing {{$partnersData->count()}} of total {{$partnersData->count()}} entries
					</p>
				@endif
				</div>
		      
			</form>
		    </div>
		</div>
	</div>
	<div class="col-md-4">
	 <div class="card">

		 <div class="card-header row gutters-5">

				<div class="col text-center text-md-left">

				 <h5 class="mb-0 h6">{{translate('Add New Warehouse')}}</h5>

			 </div>

		 </div>

		 <div class="">

			 <form class="p-4" action="{{ route('warehouse.save') }}" method="POST">

					 @csrf

					 <div class="form-group row">

							 <label class="col-sm-3 col-from-label" for="Name">

									 {{ translate('Name')}}

							 </label>

							 <div class="col-sm-9">

									 <input type="text" placeholder="{{ translate('Name')}}" id="name" name="name" class="form-control" required>

							 </div>

					 </div>

					 <div class="form-group row">

							 <label class="col-sm-3 col-from-label" for="Code">

									 {{ translate('Description')}}

							 </label>

							 <div class="col-sm-9">

								 <textarea class="form-control" placeholder="{{ translate('Description')}}" name="code" cols="30"></textarea>

									 <!-- <input type="text" placeholder="{{ translate('Description')}}" id="code" name="code" class="form-control" required> -->

							 </div>

					 </div>

					 <div class="form-group row">

							 <label class="col-sm-3 col-from-label" for="Address">

									 {{ translate('Address')}}

							 </label>

							 <div class="col-sm-9">

									 <input type="text" placeholder="{{ translate('Address')}}" id="address" name="address" class="form-control" required>

							 </div>

					 </div>

					 <div class="form-group mb-0 text-right">

							 <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>

					 </div>

			 </form>

		 </div>

	 </div>

	</div>

</div>

@endsection

@section('modal')

    @include('modals.delete_modal')

@endsection

