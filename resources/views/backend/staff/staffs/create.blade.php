@extends('backend.layouts.app')
@section('content')
<div class="row">
   <div class="col-lg-12 mx-auto">
        <div class="card">
            <div class="card-header">
               <h5 class="mb-0 h6">{{translate('
                   Add User')}}</h5>
            </div>
            <form class="form-horizontal" action="{{ route('staffs.store') }}" method="POST" enctype="multipart/form-data">
            	@csrf
                <div class="card-body">
                    <div class="col-6" style="float: left;">
                        <div class="form-group  ">
                            <label class="col-sm-3 col-from-label" for="name">{{translate('Name *')}}</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-from-label" for="name">{{translate('Gender *')}}</label>
                            <div class="col-sm-9">
                                <select  id="gender" name="gender" class="form-control" required>
                                    <option value='Male'> Male</option>
                                    <option value='Female'>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-from-label" for="company">{{translate('Company *')}}</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{translate('company')}}" id="company" name="company" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="col-sm-3 col-from-label">{{translate('Phone *')}}</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{translate('Phone')}}" id="mobile" name="mobile" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-from-label" >{{translate('Email *')}}</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{translate('Email')}}" id="email" name="email" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="col-sm-3 col-from-label" for="password">{{translate('Password *')}}</label>
                            <div class="col-sm-9">
                                <input type="password" placeholder="{{translate('Password')}}" id="password" name="password" class="form-control" required>
                                <span class="help-block">At least 1 capital, 1 lowercase, 1 number and more than 8 characters long</span>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="col-sm-3 col-from-label" for="password">{{translate('confirm Password *')}}</label>
                            <div class="col-sm-9">
                                <input type="password" placeholder="{{translate('Password')}}" class="form-control confirm_password" required>
                                <span class="error_pas_msg" style="color:red;"> The password and confirm password are not the same</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6" style="float: left;" >
                        <div class="form-group">
                            <label class="col-sm-3 col-from-label" for="name">{{translate('status *')}}</label>
                            <div class="col-sm-9">
                                <select  id="status" name="status" class="form-control" required>
                                    <option value='1'> Active</option>
                                    <option value='0'>Deactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 col-from-label" for="name">{{translate('Group *')}}</label>
                            <div class="col-sm-9">
                                <select name="role_id" required class="form-control aiz-selectpicker">
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{$role->getTranslation('name')}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <label class="checkbox" for="notify">
                                    <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" name="notify" value="1" id="notify" checked="checked" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                                    Notify User by Email                                    </label>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="form-group mb-0 ">
                        <button type="submit" id="btn_submit" class="btn btn-sm btn-primary">{{translate('Add User')}}</button>
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
            $('#btn_submit').attr('disabled',true);
            // this.submit();
        }
        else
        {
        $('.error_pas_msg').hide();
            $('#btn_submit').attr('disabled',false);
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
</script>
@endsection