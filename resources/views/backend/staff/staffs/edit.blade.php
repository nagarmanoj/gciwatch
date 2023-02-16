@extends('backend.layouts.app')
@section('content')
<div class="row">
    <div class="col-12 ">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Edit User')}}</h5>
            </div>
           <form action="{{ route('staffs.update', $staff->id) }}" method="POST">
                <input name="_method" type="hidden" value="PATCH">
               	@csrf
               <div class="card-body">
                    <div class="col-6" style="float: left;">
                        <div class="form-group">
                        <label class="col-sm-3 col-from-label" for="name">{{translate('Name *')}}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" value="{{ $staff->user->name }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-from-label" for="name">{{translate('gender *')}}</label>
                            <div class="col-sm-9">
                                <select  id="gender" name="gender" class="form-control" required>
                                    <option value='Male' @if($staff->user->gender=='Male'){{'selected'}}@endif> Male</option>
                                    <option value='Female' @if($staff->user->gender=='Female'){{'selected'}}@endif>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-from-label" for="company">{{translate('Company *')}}</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{translate('company')}}" id="company" name="company" class="form-control" value="{{ $staff->user->company }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-from-label" >{{translate('Email *')}}</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{translate('Email')}}" id="email" name="email" value="{{ $staff->user->email }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="col-sm-3 col-from-label">{{translate('Phone *')}}</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{translate('Phone')}}" id="mobile" name="mobile" value="{{ $staff->user->phone }}" class="form-control" required>
                            </div>
                        </div>




                          <div class="form-group row">

                            <label class="col-md-12 col-form-label" for="signinSrEmail">{{translate('Change Avatare')}} <small>(300x300)</small></label>

                            <div class="col-md-12">

                                <div class="input-group mi_custom_uploader" data-toggle="aizuploader" data-type="image">

                                    <div class="input-group-prepend">

                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>

                                    </div>

                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>

                                    <input type="hidden" name="avatar" class="selected-files">

                                </div>

                                <div class="file-preview box sm">

                                </div>

                                <!-- <small class="text-muted">{{translate('This image is visible in all product box. Use 300x300 sizes image. Keep some blank space around main object of your image as we had to crop some edge in different devices to make it responsive.')}}</small> -->

                            </div>

                        </div>




                        <div class="form-group ">
                            <label class="col-sm-3 col-from-label" for="password">{{translate('Password ')}}</label>
                            <div class="col-sm-9">
                                <input type="password" placeholder="{{translate('Password')}}" id="password" name="password" class="form-control">
                                <span class="help-block">At least 1 capital, 1 lowercase, 1 number and more than 8 characters long</span>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="col-sm-3 col-from-label" for="password">{{translate('Confirm Password *')}}</label>
                            <div class="col-sm-9">
                                <input type="password" placeholder="{{translate('Password')}}" class="form-control confirm_password">
                                <span class="error_pas_msg" style="color:red;"> The password and confirm password are not the same</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6" style="float: left;">
                        <div class="form-group ">
                            <label class="col-sm-3 col-from-label" for="name">{{translate('status *')}}</label>
                            <div class="col-sm-9">
                                <select  id="status" name="status" class="form-control" required>
                                    <option value='1'  @if($staff->user->status=='1'){{'selected'}}@endif> Active</option>
                                    <option value='0'  @if($staff->user->status=='0'){{'selected'}}@endif>Deactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="col-sm-3 col-from-label" for="name">{{translate('Role')}}</label>
                            <div class="col-sm-9">
                                <select name="role_id" required class="form-control aiz-selectpicker">
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}" @php if($staff->role_id == $role->id) echo "selected"; @endphp >{{$role->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0 ">
                        <button type="submit" id="btn_update_user" class="btn btn-sm btn-primary">{{translate('Update')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
        $('.error_pas_msg').hide();
    $('.confirm_password').keyup(function(){
        var password=$('#password').val();
        var confirm_password=$('.confirm_password').val();
        var pattern = /^.*(?=.{8,20})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%&!-_]).*$/;
        if(password != confirm_password)
        {
        $('.error_pas_msg').show();
            $('#btn_update_user').attr('disabled',true);
            // this.submit();
        }
        else
        {
        $('.error_pas_msg').hide();
            $('#btn_update_user').attr('disabled',false);
            // this.submit();
        }
        if(!pattern.test(password)) 
        {
            $('.help-block').css('color','red');
            $('#btn_submit').attr('disabled',true);
        }
        else
        {
            $('.help-block').css('color','green');
            $('#btn_submit').attr('disabled',false);
        }

    })


    $('#password').keyup(function(){
      var password=$('#password').val();
      if(password.length > 0){
        $(".confirm_password").prop('required',true);
      }else{
        $(".confirm_password").prop('required',false);
      }
    })
</script>
@endsection
