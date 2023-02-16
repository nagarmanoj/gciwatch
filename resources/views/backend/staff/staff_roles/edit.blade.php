@extends('backend.layouts.app')
@section('content')

<style>

.permistion {
padding-left: 10px;
border: 1px solid #ccc;
}
.fa-solid.fa-folder-open {
border-right: 1px solid #ccc;
padding: 10px;
color:#428bca
}
.g-per{
display: inline-block;
font-size: 16px;
margin: 0px;
color:#428bca
}
.group p{
margin: 0px;
padding: 10px;
border: 1px solid #ccc;

}
.table{
  padding:10px;
  border:1px solid #ccc
}
table {
width: 100%;
border: 1px solid black;
font-family: arial, sans-serif;
border-collapse: collapse;
}
td.staff {
background-color: #428bca;
color: white;
}
.module{
border: 1px solid #ccc;
width: 11%;
}
th.permission {
border: 1px solid #ccc;
text-align:center;
}
.view{

    border: 1px solid #ccc;
}
th {
    border: 1px solid #ccc;
    font-weight: 400;
}
th.miscell{
    width: 70%;
    text-align:center;
}
tr {
    height: 25px;
}
tr:nth-child(even) {
    background-color: #f9f9f9;
  }
  td{
      text-align: center;
      border: 1px solid #ccc;
      font-size: 13px;
  }
  td.check {
    text-align: left;
}
td.memo {
    text-align: left;
    height: 40px;
}
.dash {
    text-align: left;
  }
  .deposit{
      text-align: left;
  }
  .btn-1{
    margin: 9px 5px;
    display: inline-block;
    padding: 7px 19px;
    color: white;
    background-color: #428bca;
  }
  .mi_table_permissions {
text-transform: capitalize;
}
  .mi_table_permissions tr td input{
      margin:0px 8px;
  }
</style>

<div class="aiz-titlebar text-left mt-2 mb-3">

    <h5 class="mb-0 h6">{{translate('Role Information')}}</h5>

</div>



<div class="col-lg-12 mx-auto">

    <div class="card">

        <div class="card-body p-0">

            <ul class="nav nav-tabs nav-fill border-light">

      				@foreach (\App\Language::all() as $key => $language)

      					<li class="nav-item">

      						<a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3" href="{{ route('roles.edit', ['id'=>$role->id, 'lang'=> $language->code] ) }}">

      							<img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">

      							<span>{{$language->name}}</span>

      						</a>

      					</li>

    	            @endforeach

      			</ul>

            <form class="p-4" action="{{ route('roles.update', $role->id) }}" method="POST">

                <input name="_method" type="hidden" value="PATCH">

                <input type="hidden" name="lang" value="{{ $lang }}">

            	   @csrf

                <div class="form-group row">

                    <label class="col-md-3 col-from-label" for="name">{{translate('Name')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>

                    <div class="col-md-9">

                        <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" class="form-control" value="{{ $role->getTranslation('name', $lang) }}" required>

                    </div>

                </div>

                <div class="card-header">

                    <h5 class="mb-0 h6">{{ translate('Permissions') }}</h5>

                </div>

                <br>

                @php

                    $permissions = json_decode($role->permissions);
                    $inner_permissions = json_decode($role->inner_permissions);

                @endphp

                <div class="form-group row">

                    <label class="col-md-2 col-from-label" for="banner"></label>

                    <div class="col-md-8">

                        @if (addon_is_activated('pos_system'))

                          <div class="row">

                              <div class="col-md-10">

                                  <label class="col-from-label">{{ translate('POS System') }}</label>

                              </div>

                              <div class="col-md-2">

                                  <label class="aiz-switch aiz-switch-success mb-0">

                                      <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="1" @php if(in_array(1, $permissions)) echo "checked"; @endphp>

                                      <span class="slider round"></span>

                                  </label>

                              </div>

                          </div>

                        @endif

                        <div class="row">

                            <div class="col-md-10">

                                <label class="col-from-label">{{ translate('Products') }}</label>

                            </div>

                            <div class="col-md-2">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="2" @php if(in_array(2, $permissions)) echo "checked"; @endphp>

                                    <span class="slider round"></span>

                                </label>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-10">

                                <label class="col-from-label">{{ translate('Memos') }}</label>

                            </div>

                            <div class="col-md-2">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="3" @php if(in_array(3, $permissions)) echo "checked"; @endphp>

                                    <span class="slider round"></span>

                                </label>

                            </div>

                        </div>

                        <!-- <div class="row">

                            <div class="col-md-10">

                                <label class="col-from-label">{{ translate('All Orders') }}</label>

                            </div>

                            <div class="col-md-2">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="3" @php if(in_array(3, $permissions)) echo "checked"; @endphp>

                                    <span class="slider round"></span>

                                </label>

                            </div>

                        </div> -->

                        <!-- <div class="row">

                            <div class="col-md-10">

                                <label class="col-from-label">{{ translate('Inhouse orders') }}</label>

                            </div>

                            <div class="col-md-2">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="4" @php if(in_array(4, $permissions)) echo "checked"; @endphp>

                                    <span class="slider round"></span>

                                </label>

                            </div>

                        </div> -->

                        <!-- <div class="row">

                            <div class="col-md-10">

                                <label class="col-from-label">{{ translate('Seller Orders') }}</label>

                            </div>

                            <div class="col-md-2">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="5" @php if(in_array(5, $permissions)) echo "checked"; @endphp>

                                    <span class="slider round"></span>

                                </label>

                            </div>

                        </div> -->

                        <!-- <div class="row">

                            <div class="col-md-10">

                                <label class="col-from-label">{{ translate('Pick-up Point Order') }}</label>

                            </div>

                            <div class="col-md-2">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="6" @php if(in_array(6, $permissions)) echo "checked"; @endphp>

                                    <span class="slider round"></span>

                                </label>

                            </div>

                        </div> -->

                        <!-- @if (addon_is_activated('refund_request'))

                          <div class="row">

                              <div class="col-md-10">

                                  <label class="col-from-label">{{ translate('Refunds') }}</label>

                              </div>

                              <div class="col-md-2">

                                  <label class="aiz-switch aiz-switch-success mb-0">

                                      <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="7" @php if(in_array(7, $permissions)) echo "checked"; @endphp>

                                      <span class="slider round"></span>

                                  </label>

                              </div>

                          </div>

                        @endif -->

                         <div class="row">

                             <div class="col-md-10">

                                <label class="col-from-label">{{ translate('Customers') }}</label>

                            </div>

                            <div class="col-md-2">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="8" @php if(in_array(8, $permissions)) echo "checked"; @endphp>

                                    <span class="slider round"></span>

                                </label>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-10">

                                <label class="col-from-label">{{ translate('Sellers') }}</label>

                            </div>

                            <div class="col-md-2">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="9" @php if(in_array(9, $permissions)) echo "checked"; @endphp>

                                    <span class="slider round"></span>

                                </label>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-10">

                                <label class="col-from-label">{{ translate('Reports') }}</label>

                            </div>

                            <div class="col-md-2">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="10" @php if(in_array(10, $permissions)) echo "checked"; @endphp>

                                    <span class="slider round"></span>

                                </label>

                            </div>

                        </div>

                        <!-- <div class="row">

                            <div class="col-md-10">

                                <label class="col-from-label">{{ translate('Marketing') }}</label>

                            </div>

                            <div class="col-md-2">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="11" @php if(in_array(11, $permissions)) echo "checked"; @endphp>

                                    <span class="slider round"></span>

                                </label>

                            </div>

                        </div> -->

                        <!-- <div class="row">

                            <div class="col-md-10">

                                <label class="col-from-label">{{ translate('Support') }}</label>

                            </div>

                            <div class="col-md-2">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="12" @php if(in_array(12, $permissions)) echo "checked"; @endphp>

                                    <span class="slider round"></span>

                                </label>

                            </div>

                        </div> -->

                        <div class="row">

                            <div class="col-md-10">

                                <label class="col-from-label">{{ translate('Website Setup') }}</label>

                            </div>

                            <div class="col-md-2">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="13" @php if(in_array(13, $permissions)) echo "checked"; @endphp>

                                    <span class="slider round"></span>

                                </label>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-10">

                                <label class="col-from-label">{{ translate('Setup & Configurations') }}</label>

                            </div>

                            <div class="col-md-2">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="14" @php if(in_array(14, $permissions)) echo "checked"; @endphp>

                                    <span class="slider round"></span>

                                </label>

                            </div>

                        </div>

                        <!-- @if (addon_is_activated('affiliate_system'))

                          <div class="row">

                              <div class="col-md-10">

                                  <label class="col-from-label">{{ translate('Affiliate System') }}</label>

                              </div>

                              <div class="col-md-2">

                                  <label class="aiz-switch aiz-switch-success mb-0">

                                      <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="15" @php if(in_array(15, $permissions)) echo "checked"; @endphp>

                                      <span class="slider round"></span>

                                  </label>

                              </div>

                          </div>

                        @endif -->

                        <!-- @if (addon_is_activated('offline_payment'))

                          <div class="row">

                              <div class="col-md-10">

                                  <label class="col-from-label">{{ translate('Offline Payment System') }}</label>

                              </div>

                              <div class="col-md-2">

                                  <label class="aiz-switch aiz-switch-success mb-0">

                                      <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="16" @php if(in_array(16, $permissions)) echo "checked"; @endphp>

                                      <span class="slider round"></span>

                                  </label>

                              </div>

                          </div>

                        @endif -->

                        <!-- @if (addon_is_activated('paytm'))

                          <div class="row">

                              <div class="col-md-10">

                                  <label class="col-from-label">{{ translate('Paytm Payment Gateway') }}</label>

                              </div>

                              <div class="col-md-2">

                                  <label class="aiz-switch aiz-switch-success mb-0">

                                      <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="17" @php if(in_array(17, $permissions)) echo "checked"; @endphp>

                                      <span class="slider round"></span>

                                  </label>

                              </div>

                          </div>

                        @endif -->

                        <!-- @if (addon_is_activated('club_point'))

                          <div class="row">

                              <div class="col-md-10">

                                  <label class="col-from-label">{{ translate('Club Point System') }}</label>

                              </div>

                              <div class="col-md-2">

                                  <label class="aiz-switch aiz-switch-success mb-0">

                                      <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="18" @php if(in_array(18, $permissions)) echo "checked"; @endphp>

                                      <span class="slider round"></span>

                                  </label>

                              </div>

                          </div>

                        @endif -->

                        <!-- @if (addon_is_activated('otp_system'))

                          <div class="row">

                              <div class="col-md-10">

                                  <label class="col-from-label">{{ translate('OTP System') }}</label>

                              </div>

                              <div class="col-md-2">

                                  <label class="aiz-switch aiz-switch-success mb-0">

                                      <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="19" @php if(in_array(19, $permissions)) echo "checked"; @endphp>

                                      <span class="slider round"></span>

                                  </label>

                              </div>

                          </div>

                        @endif -->

                       <div class="row">

                            <div class="col-md-10">

                                <label class="col-from-label">{{ translate('Staffs') }}</label>

                            </div>

                             <div class="col-md-2">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="20" @php if(in_array(20, $permissions)) echo "checked"; @endphp>

                                    <span class="slider round"></span>

                                </label>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-10">

                                <label class="col-from-label">{{ translate('Addon Manager') }}</label>

                            </div>

                            <div class="col-md-2">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="21" @php if(in_array(21, $permissions)) echo "checked"; @endphp>

                                    <span class="slider round"></span>

                                </label>

                            </div>

                        </div>



                        <div class="row">

                            <div class="col-md-10">

                                <label class="col-from-label">{{ translate('Uploaded Files') }}</label>

                            </div>

                            <div class="col-md-2">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="22" @php if(in_array(22, $permissions)) echo "checked"; @endphp>

                                    <span class="slider round"></span>

                                </label>

                            </div>

                        </div>

                        <!-- <div class="row">

                            <div class="col-md-10">

                                <label class="col-from-label">{{ translate('Blog System') }}</label>

                            </div>

                            <div class="col-md-2">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="23" @php if(in_array(23, $permissions)) echo "checked"; @endphp>

                                    <span class="slider round"></span>

                                </label>

                            </div>

                        </div> -->

                        <div class="row">

                            <div class="col-md-10">

                                <label class="col-from-label">{{ translate('System') }}</label>

                            </div>

                            <div class="col-md-2">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="permissions[]" class="form-control demo-sw" value="24" @php if(in_array(21, $permissions)) echo "checked"; @endphp>

                                    <span class="slider round"></span>

                                </label>

                            </div>

                        </div>

                    </div>

                </div>

                                <div class="group">
                                    <div class="permistion">
                                    <i class="fa-solid fa-folder-open"></i>
                                    <h1 class="g-per">Group Permission</h1>
                                </div>
                                    <p>please set group permission bellow</p>
                                </div>
                                <div class="table">
                                <table class="mi_table_permissions">
                                    <tr class="staff">
                                        <td class="staff" colspan="6">staff (staff) group permitions</td>
                                    </tr>
                                    <tr class="rowspan">
                                        <th class="module" rowspan="2">module name</th>

                                        <th class="permission" colspan="5">permission</th>
                                    </tr>
                                    <tr class="view">

                                        <th>view</th>
                                        <th>add</th>
                                        <th>edit</th>
                                        <th>delete</th>
                                        <th class="miscell">miscellaneous</th>


                                    </tr>
                                    <tr>
                                        <th>sequence</th>
                                        <td> <input type="checkbox" name="inner_permissions[]" @php if(in_array(1, $inner_permissions)) echo "checked"; @endphp value="1"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(2, $inner_permissions)) echo "checked"; @endphp value="2"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(3, $inner_permissions)) echo "checked"; @endphp value="3"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(4, $inner_permissions)) echo "checked"; @endphp value="4"></td>
                                        <td class="check"><input type="checkbox" name="inner_permissions[]" @php if(in_array(5, $inner_permissions)) echo "checked"; @endphp value="5"> cost code multiplier <input type="checkbox" name="inner_permissions[]" @php if(in_array(6, $inner_permissions)) echo "checked"; @endphp value="6"> export </td>
                                    </tr>
                                    <tr>
                                        <th>product type</th>
                                        <td> <input type="checkbox" name="inner_permissions[]" @php if(in_array(7, $inner_permissions)) echo "checked"; @endphp value="7"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(8, $inner_permissions)) echo "checked"; @endphp value="8"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(9, $inner_permissions)) echo "checked"; @endphp value="9"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(10, $inner_permissions)) echo "checked"; @endphp value="10"></td>
                                        <td class="check"><input type="checkbox" name="inner_permissions[]" @php if(in_array(11, $inner_permissions)) echo "checked"; @endphp value="11"> export</td>
                                    </tr>
                                    <tr>
                                        <th>product</th>
                                        <td> <input type="checkbox" name="inner_permissions[]" @php if(in_array(12, $inner_permissions)) echo "checked"; @endphp value="12"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(13, $inner_permissions)) echo "checked"; @endphp value="13"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(14, $inner_permissions)) echo "checked"; @endphp value="14"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(15, $inner_permissions)) echo "checked"; @endphp value="15"></td>
                                        <td class="check"> <input type="checkbox" name="inner_permissions[]" @php if(in_array(16, $inner_permissions)) echo "checked"; @endphp value="16">product cost <input type="checkbox" name="inner_permissions[]" @php if(in_array(17, $inner_permissions)) echo "checked"; @endphp value="17">units <input
                                                type="checkbox" name="inner_permissions[]" @php if(in_array(18, $inner_permissions)) echo "checked"; @endphp value="18">partners <input type="checkbox" name="inner_permissions[]" @php if(in_array(19, $inner_permissions)) echo "checked"; @endphp value="19">brands <input type="checkbox" name="inner_permissions[]" @php if(in_array(20, $inner_permissions)) echo "checked"; @endphp value="20">conditions <input
                                                type="checkbox" name="inner_permissions[]" @php if(in_array(21, $inner_permissions)) echo "checked"; @endphp value="21">model <input type="checkbox" name="inner_permissions[]" @php if(in_array(22, $inner_permissions)) echo "checked"; @endphp value="22">metel <input type="checkbox" name="inner_permissions[]" @php if(in_array(23, $inner_permissions)) echo "checked"; @endphp value="23">sizes <input
                                                type="checkbox" name="inner_permissions[]" @php if(in_array(24, $inner_permissions)) echo "checked"; @endphp value="24">warehouses <input type="checkbox" name="inner_permissions[]" @php if(in_array(25, $inner_permissions)) echo "checked"; @endphp value="25">supplier <input type="checkbox" name="inner_permissions[]" @php if(in_array(26, $inner_permissions)) echo "checked"; @endphp value="26">import <input
                                                type="checkbox" name="inner_permissions[]" @php if(in_array(27, $inner_permissions)) echo "checked"; @endphp value="27">export</td>
                                    </tr>
                                    <tr>
                                        <th>memo</th>
                                        <td> <input type="checkbox" name="inner_permissions[]" @php if(in_array(28, $inner_permissions)) echo "checked"; @endphp value="28"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(29, $inner_permissions)) echo "checked"; @endphp value="29"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(30, $inner_permissions)) echo "checked"; @endphp value="30"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(31, $inner_permissions)) echo "checked"; @endphp value="31"></td>
                                        <td class="check"><input type="checkbox" name="inner_permissions[]" @php if(in_array(32, $inner_permissions)) echo "checked"; @endphp value="32">export</td>
                                    </tr>
                                    <tr>
                                        <th>job order</th>
                                        <td> <input type="checkbox" name="inner_permissions[]" @php if(in_array(33, $inner_permissions)) echo "checked"; @endphp value="33"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(34, $inner_permissions)) echo "checked"; @endphp value="34"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(35, $inner_permissions)) echo "checked"; @endphp value="35"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(36, $inner_permissions)) echo "checked"; @endphp value="36"></td>
                                        <td class="check"><input type="checkbox" name="inner_permissions[]" @php if(in_array(37, $inner_permissions)) echo "checked"; @endphp value="37">export</td>
                                    </tr>
                                    <tr>
                                        <th>customers</th>
                                        <td> <input type="checkbox" name="inner_permissions[]" @php if(in_array(38, $inner_permissions)) echo "checked"; @endphp value="38"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(39, $inner_permissions)) echo "checked"; @endphp value="39"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(40, $inner_permissions)) echo "checked"; @endphp value="40"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(41, $inner_permissions)) echo "checked"; @endphp value="41"></td>
                                        <td class="check"><input type="checkbox" name="inner_permissions[]" @php if(in_array(42, $inner_permissions)) echo "checked"; @endphp value="42">export</td>
                                    </tr>
                                    <tr>
                                        <th>suppliers</th>
                                        <td> <input type="checkbox" name="inner_permissions[]" @php if(in_array(43, $inner_permissions)) echo "checked"; @endphp value="43"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(44, $inner_permissions)) echo "checked"; @endphp value="44"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(45, $inner_permissions)) echo "checked"; @endphp value="45"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(46, $inner_permissions)) echo "checked"; @endphp value="46"></td>
                                        <td class="check"><input type="checkbox" name="inner_permissions[]" @php if(in_array(47, $inner_permissions)) echo "checked"; @endphp value="47">export</td>
                                    </tr>
                                    <tr>
                                        <th>agent</th>
                                        <td> <input type="checkbox" name="inner_permissions[]" @php if(in_array(48, $inner_permissions)) echo "checked"; @endphp value="48"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(49, $inner_permissions)) echo "checked"; @endphp value="49"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(50, $inner_permissions)) echo "checked"; @endphp value="50"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(51, $inner_permissions)) echo "checked"; @endphp value="51"></td>
                                        <td class="check"><input type="checkbox" name="inner_permissions[]" @php if(in_array(52, $inner_permissions)) echo "checked"; @endphp value="52">export</td>
                                    </tr>
                                    <tr>
                                        <th>transfers</th>
                                        <td> <input type="checkbox" name="inner_permissions[]" @php if(in_array(53, $inner_permissions)) echo "checked"; @endphp value="53"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(54, $inner_permissions)) echo "checked"; @endphp value="54"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(55, $inner_permissions)) echo "checked"; @endphp value="55"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(56, $inner_permissions)) echo "checked"; @endphp value="56"></td>

                                    </tr>
                                    <tr>
                                        <th>return</th>
                                        <td> <input type="checkbox" name="inner_permissions[]" @php if(in_array(57, $inner_permissions)) echo "checked"; @endphp value="57"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(58, $inner_permissions)) echo "checked"; @endphp value="58"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(59, $inner_permissions)) echo "checked"; @endphp value="59"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(60, $inner_permissions)) echo "checked"; @endphp value="60"></td>

                                    </tr>
                                    <tr>
                                        <th>appraisal</th>
                                        <td> <input type="checkbox" name="inner_permissions[]" @php if(in_array(61, $inner_permissions)) echo "checked"; @endphp value="61"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(62, $inner_permissions)) echo "checked"; @endphp value="62"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(63, $inner_permissions)) echo "checked"; @endphp value="63"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(64, $inner_permissions)) echo "checked"; @endphp value="64"></td>

                                    </tr>
                                    <tr>
                                        <th>price group</th>
                                        <td> <input type="checkbox" name="inner_permissions[]" @php if(in_array(65, $inner_permissions)) echo "checked"; @endphp value="65"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(66, $inner_permissions)) echo "checked"; @endphp value="66"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(67, $inner_permissions)) echo "checked"; @endphp value="67"></td>
                                        <td><input type="checkbox" name="inner_permissions[]" @php if(in_array(68, $inner_permissions)) echo "checked"; @endphp value="68"></td>
                                        <td class="check"><input type="checkbox" name="inner_permissions[]" @php if(in_array(69, $inner_permissions)) echo "checked"; @endphp value="69">export</td>
                                    </tr>
                                    <tr class="rowspan">
                                        <th class="report" rowspan="2">reports</th>

                                        <td class="memo" colspan="5"><input type="checkbox" name="inner_permissions[]" @php if(in_array(70, $inner_permissions)) echo "checked"; @endphp value="70"> report <input type="checkbox" name="inner_permissions[]" @php if(in_array(71, $inner_permissions)) echo "checked"; @endphp value="71"> warehouses stock <input
                                                type="checkbox" name="inner_permissions[]" @php if(in_array(72, $inner_permissions)) echo "checked"; @endphp value="72"> best sellers <input type="checkbox" name="inner_permissions[]" @php if(in_array(73, $inner_permissions)) echo "checked"; @endphp value="73"> products <input type="checkbox" name="inner_permissions[]" @php if(in_array(74, $inner_permissions)) echo "checked"; @endphp value="74"> profit and/or
                                            loss <input type="checkbox" name="inner_permissions[]" @php if(in_array(75, $inner_permissions)) echo "checked"; @endphp value="75"> purchases <input type="checkbox" name="inner_permissions[]" @php if(in_array(76, $inner_permissions)) echo "checked"; @endphp value="76"> customers <input type="checkbox" name="inner_permissions[]" @php if(in_array(77, $inner_permissions)) echo "checked"; @endphp value="77">
                                            suppliers <input type="checkbox" name="inner_permissions[]" @php if(in_array(78, $inner_permissions)) echo "checked"; @endphp value="78"> report summery</td>


                                    </tr>
                                    <tr class="sales" style="background:none!important;color:#000;">

                                        <td class="memo" colspan="5"><input type="checkbox" name="inner_permissions[]" @php if(in_array(79, $inner_permissions)) echo "checked"; @endphp value="79">sales <input type="checkbox" name="inner_permissions[]" @php if(in_array(80, $inner_permissions)) echo "checked"; @endphp value="80">memo <input
                                                type="checkbox" name="inner_permissions[]" @php if(in_array(81, $inner_permissions)) echo "checked"; @endphp value="81">invoice <input type="checkbox" name="inner_permissions[]" @php if(in_array(82, $inner_permissions)) echo "checked"; @endphp value="82">trade <input type="checkbox" name="inner_permissions[]" @php if(in_array(83, $inner_permissions)) echo "checked"; @endphp value="83">trade ngd</td>


                                    </tr>
                                    <tr class="dashbord">
                                        <th class="dashbord">dashbord</th>
                                        <td class="dash" colspan="5"> <input type="checkbox" name="inner_permissions[]" @php if(in_array(84, $inner_permissions)) echo "checked"; @endphp value="84"> customers <input type="checkbox" name="inner_permissions[]" @php if(in_array(85, $inner_permissions)) echo "checked"; @endphp value="85"> suppliers <input
                                                type="checkbox" name="inner_permissions[]" @php if(in_array(86, $inner_permissions)) echo "checked"; @endphp value="86"> summary <input type="checkbox" name="inner_permissions[]" @php if(in_array(87, $inner_permissions)) echo "checked"; @endphp value="87"> memo <input type="checkbox" name="inner_permissions[]" @php if(in_array(88, $inner_permissions)) echo "checked"; @endphp value="88"> quotations <br> <input
                                                type="checkbox" name="inner_permissions[]" @php if(in_array(89, $inner_permissions)) echo "checked"; @endphp value="89"> job orders</td>

                                    </tr>
                                    <tr>
                                        <th>deposits</th>
                                        <td class="deposit" colspan="5"><input type="checkbox" name="inner_permissions[]" @php if(in_array(90, $inner_permissions)) echo "checked"; @endphp value="90">add deposits</td>
                                    </tr>
                                    <tr>
                                        <th>job order report</th>
                                        <td class="deposit" colspan="5"><input type="checkbox" name="inner_permissions[]" @php if(in_array(91, $inner_permissions)) echo "checked"; @endphp value="91"> job order by agent <input type="checkbox" name="inner_permissions[]" @php if(in_array(92, $inner_permissions)) echo "checked"; @endphp value="92"> job order
                                            by client <input type="checkbox" name="inner_permissions[]" @php if(in_array(93, $inner_permissions)) echo "checked"; @endphp value="93"> complete job report <input type="checkbox" name="inner_permissions[]" @php if(in_array(94, $inner_permissions)) echo "checked"; @endphp value="94"> pending job report <input
                                                type="checkbox" name="inner_permissions[]" @php if(in_array(95, $inner_permissions)) echo "checked"; @endphp value="95"> on hold job report <input type="checkbox" name="inner_permissions[]" @php if(in_array(96, $inner_permissions)) echo "checked"; @endphp value="96"> open job report <input type="checkbox" name="inner_permissions[]" @php if(in_array(97, $inner_permissions)) echo "checked"; @endphp value="97">
                                            pastdue job report</td>

                                    </tr>

                                </table>
                            </div>

                <div class="form-group mb-0 text-right">

                    <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>

                </div>

            </div>

        </form>

    </div>

</div>



@endsection
