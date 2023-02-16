@extends('backend.layouts.app')
@section('content')
<!-- <div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
			<h1 class="h3">{{translate('All Listing')}}</h1>
	</div>
</div> -->
<div class="row">
	<div class="col-md-12">
		<div class="card">
		    <div class="card-header row gutters-5">
				<div class="col text-center text-md-left">
					<h5 class="mb-md-0 h6">{{ translate('Print Barcode/Label') }}</h5>
				</div>
				<form class="" action="{{route('barcode_product_excel')}}" method="post">
					@csrf
					<input type="hidden" name="ids" id="checkox_pro" value="">
					<button id="excel_download" type="submit" class="w-100 exportPro-class">export</button>
				</form>
		        <div class="form-group mb-0">
		            <input type="text" class="form-control form-control-sm" id="search_pro_stock" name="search_pro_stock" placeholder="{{ translate('Stock Id') }}">
		        </div>
		    </div>
		    <div class="card-body">
				<div class="htmlappend">
				</div>
					<form  action="{{route('products.barcodestore')}}" method="post">
						@csrf
				        <table class="table" id="StockDatahtmlappend">
				            <thead>
				                <tr>
									<!-- <th>#</th> -->
									<th>{{translate('Product Name (Stock Id)')}}</th>
									<th>{{translate('Quantity')}}</th>
				                    <th>{{translate('Options')}}</th>
				                </tr>
				            </thead>
				            <tbody>
									@if(!empty($proarr))
									@foreach($proarr as $proData)
									<tr>
									<td> <input type="hidden" class="barcode_id" data-id="{{$proData->id}}" name="all_pro[]" value="{{$proData->id}}"></td>



										<td>{{ $proData->stock_id }}</td>
										<td><input type='text' class='form-control' name="proarrkey[{{$proData->id}}]" value='1'></td>
										<td><button type='button' class='btn btn-danger removeStockData' name='button'><i class='las la-trash'></i></button></td>
									</tr>
									@endforeach
									@endif
				            </tbody>
				        </table>
						<input type="hidden" name="ispdfprint" class="pdfprint" value="">
						<button type="submit" class="btn btn-success triggerbarcode" name="update">Update</button>
					</form>
			    </div>
			</div>
		</div>
	</div>
	<div class="row">
		@if($proarr != "")
			<a href="#" type="button" class="btn btn-primary w-100 genratepropdf" name="button">Pdf</a> <br>
			<!-- <a href="{{route('barcode_product_excel')}}" type="button" class="btn btn-primary w-100 " name="button">Excel</a> -->
			<!-- <button  class="excel_download">excel </button> -->
			@foreach($proarr as $proData)
				<div class="col-lg-4">
					<div class="card">
						<div class="card-body">
							{!! DNS1D::getBarcodeHTML($proData->stock_id, 'C39+',1,30,'black', true) !!}
						</div>
					</div>
				</div>
			@endforeach
		@endif
	</div>
</div>
@endsection
@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
<script>
	$(document).ready(function() {
		$(document).on('click','#excel_download',function(){
			productCheckbox();
		});
	});
	function productCheckbox(){
		var proCheckID = [];
		$.each($("input[name='all_pro[]']"), function(){
		proCheckID.push($(this).val());
	});
	console.log(proCheckID);
	var proexpData =	JSON.stringify(proCheckID);
		$('#checkox_pro').val(proexpData);
	}
</script>
@endsection
