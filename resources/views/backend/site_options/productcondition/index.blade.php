@extends('backend.layouts.app')



@section('content')



<div class="aiz-titlebar text-left mt-2 mb-3">

	<div class="align-items-center">

			<h1 class="h3">{{translate('All Condition')}}</h1>

	</div>

</div>



<div class="row">

	<div class="col-md-7">

		<div class="card">

		    <div class="card-header row gutters-5">

				<div class="col text-center text-md-left">

					<h5 class="mb-md-0 h6">{{ translate('All Condition') }}</h5>

				</div>

				<!-- <div class="col-md-4">

					<form class="" id="sort_brands" action="" method="GET">

						<div class="input-group input-group-sm">

					  		<input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name & Enter') }}">

						</div>

					</form>

				</div> -->

				<div class="btn-group mr-2" role="group" aria-label="Third group">

					<!-- <a class="btn btn-soft-primary" href="{{route('productcondition.create')}}" title="{{ translate('add') }}">

						Add Condition	<i class="lar la-plus-square"></i>

					</a> -->

				</div>

		    </div>

		    <div class="card-body">

		        <table class="table mb-0" id="site_condition">

		            <thead>

		                <tr>

		                    <th>#</th>

                        <th>{{translate('Name')}}</th>

                        <th>{{translate('Description')}}</th>

		                    <th class="text-right">{{translate('Options')}}</th>

		                </tr>

		            </thead>

		            <tbody>



		            </tbody>

		        </table>

		    </div>

		</div>

	</div>

	<div class="col-md-5">

	 <div class="card">

		 <div class="card-header row gutters-5">

				<div class="col text-center text-md-left">

				 <h5 class="mb-0 h6">{{translate('Add New Condition')}}</h5>

			 </div>

		 </div>

		 <div class="">

			 <form class="p-4" action="{{ route('productcondition.save') }}" method="POST">

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

							 <label class="col-sm-3 col-from-label" for="Description">

									 {{ translate('Description')}}

							 </label>

							 <div class="col-sm-9">

									 <input type="text" placeholder="{{ translate('Description')}}" id="description" name="description" class="form-control" required>

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

@section('script')

<script type="text/javascript">

 $(document).ready(function() {

	 if($('#site_condition').length > 0){

			$('#site_condition').DataTable({

             processing: true,

             serverSide: true,

             ajax: "{{route('partners.getAllcondition')}}",

             columns: [

                 { data: 'id' },

                 { data: 'name' },

                 { data: 'description' },

                 { data: 'link' },

             ]

         });

		}

	});

</script>

@endsection

