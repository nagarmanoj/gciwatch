@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">

    <div class="row align-items-center">

        <div class="col-auto">

            <h1 class="h3">{{translate('All Agent')}}</h1>

        </div>

    </div>

</div>

<br>

<div class="card">

    <div class="card-header row gutters-5">

        <div class="col">

            <h5 class="mb-md-0 h6">{{ translate('All Agent') }}</h5>

        </div>

    </div>
    <form class="" id="sort_products" action="" method="GET">
				<div class="card-header row gutters-5 purchases_form_sec">
					<div class="col-md-2 d-flex page_form_secs">
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
    <table class="table aiz-table mb-0 footable footable-1 breakpoint breakpoint-lg list_agent_table" style="">

        <thead>

            <tr class="footable-header">

                <th> #</th>

                <th style="display: table-cell;">Company Name</th>

                <th style="display: table-cell;">Company Address</th>

                <th data-breakpoints="sm" style="display: table-cell;">Name</th>

                <th data-breakpoints="md" style="display: table-cell;">Email</th>

                <th style="display: table-cell;">Contact Number</th>

                <th style="display: table-cell;">Contact Person</th>

                <th style="display: table-cell;"> expertise_id</th>

                <th style="display: table-cell;">Staus</th>

                <th data-breakpoints="sm" class=" footable-last-visible" style="display: table-cell;">Action</th>

            </tr>

        </thead>

        <tbody> 

        @foreach($agentData as $row)                      

            <tr> 

                <td>  {{ $row->id }} </td>

                <td>{{ $row->company_name }}</td>

                <td> {{ $row->company_address }}</td>

                <td>{{ $row->first_name }} {{ $row->last_name }}</td>

                <td> {{ $row->email }}</td>

                <td> {{ $row->contact_number }}</td>

                <td> {{ $row->contact_person }}</td>

                <td> {{ $row->expertise_id }} </td>

                <td> @if($row->is_active==1) <span style="color:green;">Active</span>@else <span style="color:red;">Deactive</span>@endif </td>

                <td>

                    <!-- <a href="#" style="font-size:15px;text-decoration:none;">  <i class="las la-eye"></i></a> -->
                    @if(Auth::user()->user_type == 'admin' || in_array('50', json_decode(Auth::user()->staff->role->inner_permissions)))
                    <a href="{{route('agent.edit', $row->id) }}" style="font-size:15px;text-decoration:none;"> <i class="las la-edit"></i></a>
                    @endif
                    @if(Auth::user()->user_type == 'admin' || in_array('51', json_decode(Auth::user()->staff->role->inner_permissions)))
                    <a href="{{route('agent.destroy', $row->id) }}" onclick="return confirm('Are you sure You want to remove it ?')" style="font-size:15px;text-decoration:none;"><i class="las la-trash"></i></a>  
                    @endif
                    <a href="{{route('agent.activity',$row->id)}}" class="btn btn-soft-success btn-icon btn-circle btn-sm" data-href="#" title="{{ translate('Activite') }}">

                        <i class="las la-history"></i>

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

            //$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');

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

