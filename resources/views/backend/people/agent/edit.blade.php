@extends('backend.layouts.app')
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{translate('Edit Agent')}}</h1>
        </div>
    </div>
</div>
<br>
<div class="col-lg-8">
@if(Session::has('agent_updated'))
  <div class="alert alert-success" role="alert">
    {{Session::get('agent_updated')}}
  </div>
  @endif
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">Edit Agent</h5>
        </div>
        <form action="{{route('agent.update', $agent->id)}}" class="form-horizontal bv-form" role="form" id="add-customer-form" method="post" >
            @csrf;
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="company">Company * </label>
                            <div class="controls">
                                <input type="text" name="company_name" value="{{$agent->company_name}}" class="form-control" id="company_name" required="required" data-bv-field="company">
                            </div>
                            <div class="form-group">
                                <label for="company_address"> Company Address </label>
                                <div class="controls">
                                    <textarea class="form-control" id="company_address" name="company_address">{{$agent->company_address}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label  for="first_name">First Name </label>
                                <input type="text" name="first_name" value="{{$agent->first_name}}" class="form-control" id="first_name" required="required" pattern=".{3,10}" data-bv-field="first_name">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="last_name">Last Name *</label>                       <input type="text" name="last_name" value="{{$agent->last_name}}" class="form-control" id="last_name" required="required" data-bv-field="last_name">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label >Contact Number </label>
                                <div class="controls">
                                    <input type="text" name="contact_number" value="{{$agent->contact_number}}" class="form-control" id="contact_number" required="required" data-bv-field="contact_number">
                                </div>
                            </div>
                            <div class="form-group">
                                <label >Contact Person </label>
                                <div class="controls">
                                    <input type="text" name="contact_person" value="{{$agent->contact_person}}" class="form-control" id="contact_person" required="required" data-bv-field="contact_person">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email *</label> 
                                <div class="controls">
                                    <input type="email" id="email" name="email" value="{{$agent->email}}" class="form-control" required="required" data-bv-field="email">
                                </div>
                           </div>
                            <div class="form-group">
                                <label for="expertise_id">Expertise *</label> 
                                <select class="form-control select" name="expertise_id" >
                                    <option value="" readonly>Select Expertise</option>
                                    @foreach($expertiseData as $row)
                                    <option value="{{$row->id}}"  @if($agent->expertise_id== $row->id) selected @endif >{{$row->expertise_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status">Status *</label> 
                                <select name="is_active" id="status" class="form-control select">
                                    <option value="1" @if($agent->is_active==1) selected @endif>Active</option>
                                    <option value="0"  @if($agent->is_active==0) selected @endif>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                <input type="submit" name="add_user" value="Edit Agent" class="btn btn-primary" style="margin-left:20px;">
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
        $('#contact_number').keyup(function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
    });
    </script>
@endsection
