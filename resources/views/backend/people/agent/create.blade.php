@extends('backend.layouts.app')
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{translate('Add Agent')}}</h1>
        </div>
    </div>
</div>
<br>
<div class="col-lg-8">
@if(Session::has('user_created'))
  <div class="alert alert-success" role="alert">
    {{Session::get('user_created')}}
  </div>
  @endif
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">Add Agent</h5>
        </div>
        <form action="{{route('agent.save')}}" class="form-horizontal bv-form" role="form" id="add-customer-form" method="post" >
            @csrf;
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="company">Company *</label>                                <div class="controls">
                                <input type="text" name="company_name" value="" class="form-control" id="company_name" required="required" data-bv-field="company">
                            </div>
                            <div class="form-group">
                                <label for="company_address"> Company Address </label>
                                <div class="controls">
                                    <textarea class="form-control" id="company_address" name="company_address"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label  for="first_name">First Name </label>
                                <input type="text" name="first_name" value="" class="form-control" id="first_name" required="required" pattern=".{3,10}" data-bv-field="first_name">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="last_name">Last Name *</label>                       <input type="text" name="last_name" value="" class="form-control" id="last_name" required="required" data-bv-field="last_name">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label >Contact Number </label>
                                <div class="controls">
                                    <input type="text" name="contact_number" value="" class="form-control" id="contact_number" required="required" data-bv-field="contact_number">
                                </div>
                            </div>
                            <div class="form-group">
                                <label >Contact Person </label>
                                <div class="controls">
                                    <input type="text" name="contact_person" value="" class="form-control" id="contact_person" required="required" data-bv-field="contact_person">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email *</label> 
                                <div class="controls">
                                    <input type="email" id="email" name="email" class="form-control" required="required" data-bv-field="email">
                                </div>
                           </div>
                            <div class="form-group">
                                <label for="expertise_id">Expertise *</label> 
                                <select class="form-control select" name="expertise_id" >
                                    <option value="" readonly>Select Expertise</option>
                                    @foreach($expertiseData as $row)
                                    <option value="{{$row->id}}">{{$row->expertise_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status">Status *</label> 
                                <select name="is_active" id="status" class="form-control select">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                <input type="submit" name="add_user" value="Add Agent" class="btn btn-primary" style="margin-left:20px;">
                </div>
            </div>
        </form>
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
      
  $('#contact_number').keyup(function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
    });
   
    </script>
@endsection
