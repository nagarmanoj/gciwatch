@extends('backend.layouts.app')
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{translate('Edit Expertise')}}</h1>
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
            <h5 class="mb-0 h6">Edit Expertise</h5>
        </div>
        <form action="{{route('expertise.update',$expertise->id)}}" class="form-horizontal bv-form" role="form" id="add-customer-form" method="post" >
            @csrf;
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="company">Expertise *</label>                               
                             <div class="controls">
                                <input type="text" name="expertise_name" value="{{$expertise->expertise_name}}" class="form-control" id="expertise_name" required="required" data-bv-field="company">
                            </div>
                </div>
                <input type="submit" name="add_user" value="Edit Expertise" class="btn btn-primary">
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
        // $(document).on("change", ".check-all", function() {
        //     if(this.checked) {
        //         // Iterate each checkbox
        //         $('.check-one:checkbox').each(function() {
        //             this.checked = true;
        //         });
        //     } else {
        //         $('.check-one:checkbox').each(function() {
        //             this.checked = false;
        //         });
        //     }
        // });

           
    </script>
@endsection
