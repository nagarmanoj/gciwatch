@extends('backend.layouts.app')
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
		<!-- <h1 class="h3">{{translate('All Listing')}}</h1> -->
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
		    <div class="card-header row gutters-5">
				<div class="col text-center text-md-left">
					<h5 class="mb-md-0 h6">{{ translate('All Listing') }}</h5>
				</div>
				<div class="btn-group mr-2" role="group" aria-label="Third group">
					<a class="btn btn-soft-primary" href="{{route('Listingtype.create')}}" title="{{ translate('add') }}">
						Add Listing	<i class="lar la-plus-square"></i>
					</a>
				</div>
		    </div>
			<form class="" id="sort_products" action="" method="GET">
				<div class="card-header row gutters-5 purchases_form_sec">
					<div class="col-md-3 d-flex page_form_secs">
						<label class="fillter_sel_show m-auto"><b>Show</b></label>
						<input type="hidden" name="search" id="searchinputfield">
						<select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0 purchases_filter" name="purchases_pagi" id="purchases_pagi" data-live-search="true">
							<option  @if($pagination_qty == 25) selected @endif>{{translate('25')}}</option>
							<option  @if($pagination_qty == 50) selected @endif>{{translate('50')}}</option>
							<option  @if($pagination_qty == 100) selected @endif>{{translate('100')}}</option>
							<option  @if($pagination_qty == 200) selected @endif>{{translate('200')}}</option>
						</select>
						<button type="submit" id="purchases_pagi_sub" name="purchases_pagi_sub" class="d-none"><i class="las la-search aiz-side-nav-icon" ></i></button>
					</div>
					<div class="col-6 d-flex search_form_sec">
						<label class="fillter_sel_show m-auto"><b>Search</b></label>
						<input type="text" class="form-control form-control-sm sort_search_val"  @isset($sort_search) value="{{ $sort_search }}"  @endisset>
						<button type="button" class="search_btn_field"><i class="las la-search aiz-side-nav-icon" ></i></button>
					</div>
				</div>
		    	<div class="card-body">
			        <table class="table aiz-table mb-0">
			            <thead>
			                <tr>
			                    <th>#</th>
			                    <th>{{translate('Listing Name')}}</th>
			                    <th class="text-right">{{translate('Options')}}</th>
			                </tr>
			            </thead>
			            <tbody>
							@php $count=0; @endphp
		                @foreach($partnersData as $key => $optData)
						<?php  $count++; ?>
							<tr>
	                            <td>{{ $count}}</td>
		                        <td>{{ $optData->option_value}}</td>
		                        <td class="text-right">
		                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('Listingtype.edit', ['id'=>$optData->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
		                                <i class="las la-edit"></i>
		                            </a>
		                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('soption.destroy', $optData->id)}}" title="{{ translate('Delete') }}">
		                                <i class="las la-trash"></i>
		                            </a>
		                        </td>
		                    </tr>
		                @endforeach
		            </tbody>
		        </table>
				<p>
              Showing {{ $purchases->firstItem() }} to {{ $purchases->lastItem() }} of total {{$purchases->total()}} entries
            </p>
            <div class="aiz-pagination">
              @if($pagination_qty != "all")
                {{ $purchases->appends(request()->input())->links() }}
              @endif
            </div>
		        <!-- <div class="aiz-pagination">
					{{ $purchases->appends(request()->input())->links() }}
            	</div> -->
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
<script>
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
