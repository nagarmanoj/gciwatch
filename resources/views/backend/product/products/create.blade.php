@extends('backend.layouts.app')



@section('content')

<script src="{{ static_asset('assets/js/webcam.min.js') }}" ></script>

<div class="aiz-titlebar text-left mt-2 mb-3">

    <h5 class="mb-0 h6">{{translate('Add New Product')}}</h5>

</div>

<div class="">

    <form class="form form-horizontal mar-top" action="{{route('products.store')}}" method="POST" enctype="multipart/form-data" id="choice_form">

        <div class="row gutters-5">

            <div class="col-lg-8">

                @csrf

                <input type="hidden" name="added_by" value="admin">

                <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0 h6">{{translate('Product Information')}}</h5>

                    </div>

                    <div class="card-body add_mi_product">

                        <div class="form-group row" id="product_type_id">

                          <label class="col-md-12 col-from-label">{{translate('Product Type')}}</label>

                          <div class="col-md-12">

                              <select class="form-control aiz-selectpicker" name="product_type_id" id="product_typecustom" data-live-search="true" required>

                                  <option value="">{{ translate('Select Product Type') }}</option>

                                  @foreach (\App\Models\Producttype::all() as $product_type)

                                  <option value="{{ $product_type->id }}" >{{ $product_type->product_type_name }}</option>

                                  @endforeach

                              </select>

                          </div>

                        </div>

                        <div class="form-group row">

                          <label class="col-md-12 col-from-label">{{translate('Current Stock ID')}} <span class="text-danger">*</span></label>

                          <div class="col-md-12">

                            <input type="hidden" name="seqId" id="seqId" value="">

                              <input type="text" id="stock_id_custom" class="form-control" name="stock_id" placeholder="{{ translate('Current Stock ID') }}"  readonly>

                              @error('stock_id')

                              <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>

                              @enderror

                          </div>

                      </div>

                        <div class="form-group row">

                            <label class="col-md-12 col-from-label">{{translate('Product Name')}} <span class="text-danger">*</span></label>

                            <div class="col-md-12">

                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="{{ translate('Product Name') }}" onchange="update_sku()" required>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-md-12 col-from-label">{{translate('Condition')}}</label>

                            <div class="col-md-12 d-flex">

                                <select class="form-control aiz-selectpicker" name="productcondition_id" id="productcondition" data-live-search="true">

                                    <option value="">{{ translate('Select Condition') }}</option>

                                    @foreach (\App\Models\Productcondition::orderBy('id','ASC')->get() as $productcondition)

                                    <option value="{{ $productcondition->id }}" @if( old('productcondition_id') == $productcondition->id) selected @endif>{{ $productcondition->name }}</option>

                                    @endforeach

                                </select>

                                <button data-typereq="model" type="button" class="btn btn-primary conditionsubmit" data-toggle="modal" data-target="#add_ajax_product_condition">

                                  <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-md-12 col-from-label">{{translate('Brand')}}</label>

                            <div class="col-md-12 d-flex">

                                <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id" data-live-search="true">

                                    <option value="">{{ translate('Select Brand') }}</option>

                                    @foreach (\App\Brand::all() as $brand)

                                    <option value="{{ $brand->id }}" @if( old('brand_id') == $brand->id) selected @endif>{{ $brand->getTranslation('name') }}</option>

                                    @endforeach

                                </select>

                                <button data-typereq="model" type="button" class="btn btn-primary brandsubmit" data-toggle="modal" data-target="#add_ajax_product_brand">

                                  <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group row" >

                            <label class="col-md-12 col-from-label">{{translate('Model Number')}}</label>

                            <div class="col-md-12 d-flex">

                                <select class="form-control aiz-selectpicker test" name="model" id="model" data-live-search="true">

                                    <option value="">{{ translate('Select Model Number') }}</option>

                                    @foreach (\App\SiteOptions::where('option_name', '=', 'model')->get() as $model)

                                    <option value="{{ $model->option_value }}" @if( old('model') == $model->option_value) selected @endif>{{ $model->option_value }}</option>

                                    @endforeach

                                </select>

                                <button data-typereq="model" type="button" class="btn btn-primary optionsubmit" data-toggle="modal" data-target="#add_ajax_product_unit">

                                  <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-md-12 col-from-label">

                                {{translate('Serial')}}

                            </label>

                            <div class="col-md-12">



                                <input type="text" value="{{ old('sku') }}" placeholder="{{ translate('Serial') }}" id="skuid" name="sku" class="form-control" >

                                  @error('sku')

                                  <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>

                                  @enderror

                            </div>

                        </div>

                        <div class="form-group row" id="paper_cart">

                            <label class="col-md-12 col-from-label">{{translate('Paper/Cert')}}</label>

                            <div class="col-md-12">

                                <select class="form-control aiz-selectpicker" name="paper_cart" id="paper_cart" data-live-search="true">

                                  <option value="yes">{{ translate('yes') }}</option>

                                  <option value="No">{{ translate('No') }}</option>

                                    <option value="others">{{ translate('others') }}</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-md-12 col-from-label">{{translate('Category')}} <span class="text-danger">*</span></label>

                            <div class="col-md-12 d-flex">

                                <select class="form-control aiz-selectpicker" name="category_id" id="category_id" data-live-search="true" required>

                                  <option value="">{{ translate('Select Category') }}</option>

                                    @foreach ($categories as $category)

                                    <option value="{{ $category->id }}"  @if( old('category_id') == $category->id) selected @endif>{{ $category->name }}</option>

                                    @foreach ($category->childrenCategories as $childCategory)

                                    @include('categories.child_category', ['child_category' => $childCategory])

                                    @endforeach

                                    @endforeach

                                </select>

                                <button data-typereq="model" type="button" class="btn btn-primary categorysubmit" data-toggle="modal" data-target="#add_ajax_product_category">

                                  <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                </button>

                            </div>

                        </div>

                         <div class="form-group row">

                            <label class="col-md-12 col-from-label">{{translate('Size')}}</label>

                            <div class="col-md-12 d-flex">

                                <select class="form-control aiz-selectpicker" name="size" id="size" data-live-search="true">

                                    <option value="">{{ translate('Select Size') }}</option>

                                    @foreach (\App\SiteOptions::where('option_name', '=', 'size')->get() as $size)

                                    <option value="{{ $size->option_value }}"  @if( old('size') == $size->option_value) selected @endif>{{ $size->option_value }}</option>

                                    @endforeach

                                </select>

                                <button data-typereq="size" type="button" class="btn btn-primary optionsubmit" data-toggle="modal" data-target="#add_ajax_product_unit">

                                  <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-md-12 col-from-label">{{translate('Metal')}}</label>

                            <div class="col-md-12 d-flex">

                                <select class="form-control aiz-selectpicker" name="metal" id="metal" data-live-search="true">

                                    <option value="">{{ translate('Select Metal') }}</option>

                                    @foreach (\App\SiteOptions::where('option_name', '=', 'metal')->get() as $metal)

                                    <option value="{{ $metal->option_value }}" @if( old('metal') == $metal->option_value) selected @endif>{{ $metal->option_value }}</option>

                                    @endforeach

                                </select>

                                <button data-typereq="metal" type="button" class="btn btn-primary optionsubmit" data-toggle="modal" data-target="#add_ajax_product_unit">

                                  <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-md-12 col-from-label">{{translate('Weight')}}</label>

                            <div class="col-md-12">

                                <input type="text" class="form-control" value="{{ old('weight') }}" name="weight" placeholder="{{ translate('Weight') }}" >

                            </div>

                        </div>

                        <div class="form-group row" >

                            <label class="col-md-12 col-from-label">{{translate('Partner')}}</label>

                            <div class="col-md-12 d-flex">

                                <select class="form-control aiz-selectpicker" name="partner" id="partners" data-live-search="true" required>

                                    <option value="">{{ translate('Select Partner') }}</option>

                                    @foreach (\App\SiteOptions::where('option_name', '=', 'partners')->get() as $partner)

                                    <option value="{{ $partner->option_value }}" @if( old('partner') == $partner->option_value) selected @endif>{{ $partner->option_value }}</option>

                                    @endforeach

                                </select>

                                <button data-typereq="partners" type="button" class="btn btn-primary optionsubmit" data-toggle="modal" data-target="#add_ajax_product_unit">

                                  <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group row" >

                            <label class="col-md-12 col-from-label">{{translate('Warehouse')}}</label>

                            <div class="col-md-12 d-flex">

                                <select class="form-control aiz-selectpicker" name="warehouse_id" id="warehouse_id" data-live-search="true" required>

                                    <option value="">{{ translate('Select Warehouse') }}</option>

                                    @foreach (\App\Models\Warehouse::all() as $warenouse)

                                    <option value="{{ $warenouse->id }}" @if( old('warehouse_id') == $warenouse->id) selected @endif>{{ $warenouse->name }}</option>

                                    @endforeach

                                </select>

                                <button data-typereq="model" type="button" class="btn btn-primary warehousesubmit" data-toggle="modal" data-target="#add_ajax_product_warehouse">

                                  <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-md-12 col-from-label">{{translate('unit')}}</label>

                            <div class="col-md-12 d-flex">

                                <select class="form-control aiz-selectpicker" name="unit" id="unit" data-live-search="true" required>

                                    <option value="">{{ translate('Select Unit') }}</option>

                                    @foreach (\App\SiteOptions::where('option_name', '=', 'unit')->get() as $unit)

                                    <option value="{{ $unit->option_value }}"  @if( old('unit') == $unit->option_value) selected @endif>{{ $unit->option_value }}</option>

                                    @endforeach

                                </select>

                                <button data-typereq="unit" type="button" class="btn btn-primary optionsubmit" data-toggle="modal" data-target="#add_ajax_product_unit">

                                  <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                </button>

                            </div>

                        </div>





                        <div class="form-group row">

                            <label class="col-md-12 col-from-label">{{translate('Vendor Doc Number')}}</label>

                            <div class="col-md-12">

                                <input type="text" class="form-control" value="{{ old('vendor_doc_number') }}" name="vendor_doc_number" placeholder="{{ translate('Vendor Doc Number') }}" >

                            </div>

                        </div>
                        @if(Auth::user()->user_type == 'admin' || in_array('25', json_decode(Auth::user()->staff->role->inner_permissions)))
                            <div class="form-group row">

                                <label class="col-md-12 col-from-label">{{translate('Supplier')}}</label>

                                <div class="col-md-12 d-flex">

                                    <select class="form-control aiz-selectpicker" name="supplier_id" id="supplier_id" data-live-search="true" >

                                        <option value="">{{ translate('Select Supplier') }}</option>

                                        @foreach (\App\User::select('sellers.company', 'users.id')
                                            ->where('user_type', 'seller')->join('sellers','sellers.user_id','users.id')->get() as $seller)

                                        <option value="{{ $seller->id }}"   @if( old('supplier_id') == $seller->id) selected @endif>{{ $seller->company }}</option>

                                        @endforeach

                                    </select>

                                    <button type="button" class="btn btn-primary sellersubmit" data-toggle="modal" data-target="#add_ajax_product_seller">

                                    <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                    </button>

                                </div>
                            @endif

                        </div>

                        <div class="form-group row">

                            <label class="col-md-12 col-from-label">{{translate('DOP')}}</label>

                            <div class="col-md-12">

                                <input type="text" class="form-control" value="{{ old('dop') }}" name="dop" placeholder="{{translate('Select Date')}}" >

                            </div>

                        </div>



                        <!-- <div class="form-group row">

                            <label class="col-md-3 col-from-label">{{translate('Unit')}}</label>

                            <div class="col-md-8">

                                <input type="text" class="form-control" name="unit" placeholder="{{ translate('Unit (e.g. KG, Pc etc)') }}" required>

                            </div>

                        </div> -->

                        <!-- <div class="form-group row">

                            <label class="col-md-12 col-from-label">{{translate('Minimum Purchase Qty')}} <span class="text-danger">*</span></label>

                            <div class="col-md-12">

                                <input type="number" lang="en" class="form-control" name="min_qty" value="1" min="1" required>

                            </div>

                        </div> -->





                        <div class="form-group row">

                            <label class="col-md-12 col-from-label">{{translate('Tags')}} <span class="text-danger">*</span></label>

                            <div class="col-md-12 d-flex">

                                <!-- <input type="text" class="form-control aiz-tag-input" name="tags[]" placeholder="{{ translate('Type and hit enter to add a tag') }}"> -->

                                <select class="form-control js-example-basic-multiple " id="tags_id" name="tags[]" multiple="multiple" style="border: 1px solid #dadadb;" data-live-search="true">



                                  @foreach ($tagAllData as $tagName)

                                  <option value="{{ $tagName }}" >{{ $tagName }}</option>

                                  @endforeach

                              </select>

                              <button type="button" class="btn btn-primary tagsubmit" data-toggle="modal" data-target="#add_ajax_product_tag" style="line-height: 0.5;">

                                <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                              </button>

                                <!-- <small class="text-muted">{{translate('This is used for search. Input those words by which cutomer can find this product.')}}</small> -->

                            </div>

                        </div>



                        @php

                        $pos_addon = \App\Addon::where('unique_identifier', 'pos_system')->first();

                        @endphp

                        @if ($pos_addon != null && $pos_addon->activated == 1)

                        <div class="form-group row">

                            <label class="col-md-3 col-from-label">{{translate('Barcode')}}</label>

                            <div class="col-md-8">

                                <input type="text" class="form-control" name="barcode" placeholder="{{ translate('Barcode') }}">

                            </div>

                        </div>

                        @endif



                        @php

                        $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();

                        @endphp

                        @if ($refund_request_addon != null && $refund_request_addon->activated == 1)

                        <div class="form-group row">

                            <label class="col-md-3 col-from-label">{{translate('Refundable')}}</label>

                            <div class="col-md-8">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="refundable" checked>

                                    <span></span>

                                </label>

                            </div>

                        </div>

                        @endif

                    </div>

                </div>



                <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0 h6">{{translate('Product Videos')}}</h5>

                    </div>

                    <div class="card-body add_mi_product">

                        <div class="form-group row">

                            <label class="col-md-12 col-from-label">{{translate('Video Provider')}}</label>

                            <div class="col-md-12">

                                <select class="form-control aiz-selectpicker" name="video_provider" id="video_provider">

                                    <option value="google_drives">{{translate('Google Drive')}}</option>

                                    <!--<option value="youtube">{{translate('Youtube')}}</option>-->

                                    <!-- <option value="dailymotion">{{translate('Dailymotion')}}</option>

                                    <option value="vimeo">{{translate('Vimeo')}}</option> -->

                                </select>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-md-12 col-from-label">{{translate('Video Link')}}</label>

                            <div class="col-md-12">

                                <input type="text" class="form-control" value="{{ old('video_link') }}" name="video_link" placeholder="{{ translate('Video Link') }}">

                                <small class="text-muted">{{translate("Use proper link without extra parameter. Don't use short share link/embeded iframe code.")}}</small>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0 h6">{{translate('Product Variation')}}</h5>

                    </div>

                    <div class="card-body">

                        <div class="form-group row gutters-5">

                            <div class="col-md-3">

                                <input type="text" class="form-control" value="{{translate('Colors')}}" disabled>

                            </div>

                            <div class="col-md-8">

                                <select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" name="colors[]" id="colors" multiple disabled>

                                    @foreach (\App\Color::orderBy('name', 'asc')->get() as $key => $color)

                                    <option  value="{{ $color->code }}" data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:{{ $color->code }}'></span><span>{{ $color->name }}</span></span>"></option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-1">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input value="1" type="checkbox" name="colors_active">

                                    <span></span>

                                </label>

                            </div>

                        </div>



                        <div class="form-group row gutters-5">

                            <div class="col-md-3">

                                <input type="text" class="form-control" value="{{translate('Attributes')}}" disabled>

                            </div>

                            <div class="col-md-8">

                                <select name="choice_attributes[]" id="choice_attributes" class="form-control aiz-selectpicker" data-selected-text-format="count" data-live-search="true" multiple data-placeholder="{{ translate('Choose Attributes') }}">

                                    @foreach (\App\Attribute::all() as $key => $attribute)

                                    <option value="{{ $attribute->id }}">{{ $attribute->getTranslation('name') }}</option>

                                    @endforeach

                                </select>

                            </div>

                        </div>

                        <div>

                            <p>{{ translate('Choose the attributes of this product and then input values of each attribute') }}</p>

                            <br>

                        </div>



                        <div class="customer_choice_options" id="customer_choice_options">



                        </div>

                    </div>

                </div> -->

                <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0 h6">{{translate('Product price + stock')}}</h5>

                    </div>

                    <div class="card-body add_mi_product">
                    @if(Auth::user()->user_type == 'admin' || in_array('16', json_decode(Auth::user()->staff->role->inner_permissions)))
                    <div class="form-group row">

                        <label class="col-md-12 col-from-label">{{translate('Product Cost')}} <span class="text-danger">*</span></label>

                        <div class="col-md-12">

                            <input type="number" lang="en" min="0"  step="0.01" value="{{ old('product_cost') }}" placeholder="{{ translate('Product cost') }}" id="product_cost" name="product_cost" class="form-control" required>

                        </div>

                    </div>
                    @endif

                      <input type="hidden" id="cost_code_multiplier_custom">

                        <div class="form-group row">

                            <label class="col-md-12 col-from-label">{{translate('Cost Code/Tag Price')}} <span class="text-danger">*</span></label>

                            <div class="col-md-12">

                                <input type="text" class="form-control" value="{{ old('cost_code') }}" name="cost_code" id="cost_code" placeholder="{{ translate('Cost Code') }}"  readonly>

                            </div>

                        </div>

                      <div class="form-group row">

                          <label class="col-md-12 col-from-label">{{translate('Sale Price')}} <span class="text-danger">*</span></label>

                          <div class="col-md-12">

                              <input type="number" lang="en" min="0" value="0" step="0.01" value="{{ old('unit_price') }}" placeholder="{{ translate('Product cost') }}" name="unit_price" class="form-control" required>

                          </div>

                      </div>

                      <div class="form-group row">

                          <label class="col-md-12 col-from-label">

                              {{translate('MSRP')}}

                          </label>

                          <div class="col-md-12">

                              <input type="number" lang="en" min="0" value="0" step="1" placeholder="{{ translate('1') }}" value="{{ old('msrp') }}" name="msrp" class="form-control" required>

                          </div>

                      </div>





                        <!-- <div class="form-group row">

	                        <label class="col-sm-3 control-label" for="start_date">{{translate('Discount Date Range')}}</label>

	                        <div class="col-sm-9">

  	                          <input type="text" class="form-control aiz-date-range" name="date_range" placeholder="{{translate('Select Date')}}" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">

	                        </div>

	                    </div> -->



                        <!-- <div class="form-group row">

                            <label class="col-md-12 col-from-label">{{translate('Discount')}} <span class="text-danger">*</span></label>

                            <div class="col-md-12">

                                <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ translate('Discount') }}" name="discount" class="form-control" required>

                            </div>

                            <div class="col-md-12">

                                <select class="form-control aiz-selectpicker" name="discount_type">

                                    <option value="amount">{{translate('Flat')}}</option>

                                    <option value="percent">{{translate('Percent')}}</option>

                                </select>

                            </div>

                        </div> -->



                        @if(\App\Addon::where('unique_identifier', 'club_point')->first() != null &&

                            \App\Addon::where('unique_identifier', 'club_point')->first()->activated)

                            <div class="form-group row">

                                <label class="col-md-12 col-from-label">

                                    {{translate('Set Point')}}

                                </label>

                                <div class="col-md-12">

                                    <input type="number" lang="en" min="0" value="0" step="1" placeholder="{{ translate('1') }}" name="earn_point" class="form-control">

                                </div>

                            </div>

                        @endif



                        <div id="show-hide-div">

                            <div class="form-group row">

                                <label class="col-md-12 col-from-label">{{translate('Quantity')}} <span class="text-danger">*</span></label>

                                <div class="col-md-12">

                                    <input type="number" lang="en" min="0" value="1" step="1" placeholder="{{ translate('Quantity') }}" value="{{ old('current_stock') }}" name="current_stock" class="form-control" >

                                </div>

                            </div>

                        </div>

                        <br>

                        <div class="sku_combination" id="sku_combination">



                        </div>

                    </div>

                </div>



                <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0 h6">{{translate('Product Images')}}</h5>

                    </div>

                    <div class="card-body add_mi_product">

                        <div class="form-group row">

                            <label class="col-md-12 col-form-label" for="signinSrEmail">{{translate('Gallery Images')}} <small>(600x600)</small></label>

                            <div class="col-md-12 d-flex">

                                <div class="camcod">

                                  <div class="input-group mi_custom_uploader" data-toggle="aizuploader" data-type="image" data-multiple="true">

                                      <div class="input-group-prepend">

                                          <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>

                                      </div>

                                      <div class="form-control file-amount">{{ translate('Choose File') }}</div>

                                      <input type="hidden" name="photos" class="selected-files">

                                  </div>

                                  <div class="file-preview box sm">

                                  </div>

                                  <small class="text-muted opacity-0">{{translate('These images are visible in product details page gallery. Use 600x600 sizes images.')}}</small>

                                </div>

                                <button data-typereq="model" type="button" class="btn btn-primary webcamsubmit" data-toggle="modal" style="height:40px;">

                                  <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                </button>

                            </div>

                        </div>

                        <!-- <div class="form-group row">

                            <label class="col-md-12 col-form-label" for="signinSrEmail">{{translate('Thumbnail Image')}} <small>(300x300)</small></label>

                            <div class="col-md-12">

                                <div class="input-group mi_custom_uploader" data-toggle="aizuploader" data-type="image">

                                    <div class="input-group-prepend">

                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>

                                    </div>

                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>

                                    <input type="hidden" name="thumbnail_img" class="selected-files">

                                </div>

                                <div class="file-preview box sm">

                                </div>

                                <small class="text-muted">{{translate('This image is visible in all product box. Use 300x300 sizes image. Keep some blank space around main object of your image as we had to crop some edge in different devices to make it responsive.')}}</small>

                            </div>

                        </div> -->





                        <!-- <div class="form-group row">

                            <label class="col-md-12 col-from-label">

                                {{translate('Whatsapp link')}}

                            </label>

                            <div class="col-md-12">

                                <input type="text" placeholder="{{ translate('Whatsapp link') }}" name="external_link" class="form-control">

                                <small class="text-muted">{{translate('Leave it blank if you do not use external site link')}}</small>

                            </div>

                        </div> -->

                        <!-- <div class="form-group row">

                            <label class="col-md-12 col-from-label">

                                {{translate('Google Drive link')}}

                            </label>

                            <div class="col-md-12">

                                <input type="text" placeholder="{{ translate('Google Drive link') }}" name="google_link" class="form-control">

                                <small class="text-muted">{{translate('Leave it blank if you do not use Google Drive')}}</small>

                            </div>

                        </div> -->

                    </div>

                </div>





                <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0 h6">{{translate('Product Description')}}</h5>

                    </div>

                    <div class="card-body add_mi_product">

                        <div class="form-group row">

                            <label class="col-md-12 col-from-label">{{translate('Description')}}</label>

                            <div class="col-md-12">

                                <textarea class="aiz-text-editor" name="description">{{ old('description') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>



<!--                <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0 h6">{{translate('Product Shipping Cost')}}</h5>

                    </div>

                    <div class="card-body">



                    </div>

                </div>-->



                <!-- <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0 h6">{{translate('PDF Specification')}}</h5>

                    </div>

                    <div class="card-body">

                        <div class="form-group row">

                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('PDF Specification')}}</label>

                            <div class="col-md-8">

                                <div class="input-group" data-toggle="aizuploader" data-type="document">

                                    <div class="input-group-prepend">

                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>

                                    </div>

                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>

                                    <input type="hidden" name="pdf" class="selected-files">

                                </div>

                                <div class="file-preview box sm">

                                </div>

                            </div>

                        </div>

                    </div>

                </div> -->

                <!-- <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0 h6">{{translate('SEO Meta Tags')}}</h5>

                    </div>

                    <div class="card-body add_mi_product">

                        <div class="form-group row">

                            <label class="col-md-12 col-from-label">{{translate('Meta Title')}}</label>

                            <div class="col-md-12">

                                <input type="text" class="form-control" name="meta_title" placeholder="{{ translate('Meta Title') }}">

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-md-12 col-from-label">{{translate('Description')}}</label>

                            <div class="col-md-12">

                                <textarea name="meta_description" rows="8" class="form-control"></textarea>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-md-12 col-form-label" for="signinSrEmail">{{ translate('Meta Image') }}</label>

                            <div class="col-md-12">

                                <div class="input-group" data-toggle="aizuploader" data-type="image">

                                    <div class="input-group-prepend">

                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>

                                    </div>

                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>

                                    <input type="hidden" name="meta_img" class="selected-files">

                                </div>

                                <div class="file-preview box sm">

                                </div>

                            </div>

                        </div>

                    </div>

                </div> -->



            </div>



            <div class="col-lg-4 right_side_baar">



              <div class="card">

                  <div class="card-header">

                      <h5 class="mb-0 h6">

                          {{translate('Product type fields')}}

                      </h5>

                  </div>



                  <div class="card-body producttypecustom">



                  </div>

              </div>



                <!-- <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0 h6">

                            {{translate('Shipping Configuration')}}

                        </h5>

                    </div>



                    <div class="card-body">

                        @if (get_setting('shipping_type') == 'product_wise_shipping')

                        <div class="form-group row">

                            <label class="col-md-6 col-from-label">{{translate('Free Shipping')}}</label>

                            <div class="col-md-6">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="radio" name="shipping_type" value="free" checked>

                                    <span></span>

                                </label>

                            </div>

                        </div>



                        <div class="form-group row">

                            <label class="col-md-6 col-from-label">{{translate('Flat Rate')}}</label>

                            <div class="col-md-6">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="radio" name="shipping_type" value="flat_rate">

                                    <span></span>

                                </label>

                            </div>

                        </div>



                        <div class="flat_rate_shipping_div" style="display: none">

                            <div class="form-group row">

                                <label class="col-md-6 col-from-label">{{translate('Shipping cost')}}</label>

                                <div class="col-md-6">

                                    <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ translate('Shipping cost') }}" name="flat_shipping_cost" class="form-control" required>

                                </div>

                            </div>

                        </div>



                        <div class="form-group row">

                            <label class="col-md-6 col-from-label">{{translate('Is Product Quantity Mulitiply')}}</label>

                            <div class="col-md-6">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="is_quantity_multiplied" value="1">

                                    <span></span>

                                </label>

                            </div>

                        </div>

                        @else

                        <p>

                            {{ translate('Product wise shipping cost is disable. Shipping cost is configured from here') }}

                            <a href="{{route('shipping_configuration.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index','shipping_configuration.edit','shipping_configuration.update'])}}">

                                <span class="aiz-side-nav-text">{{translate('Shipping Configuration')}}</span>

                            </a>

                        </p>

                        @endif

                    </div>

                </div> -->



                <!-- <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0 h6">{{translate('Low Stock Quantity Warning')}}</h5>

                    </div>

                    <div class="card-body">

                        <div class="form-group mb-3">

                            <label for="name">

                                {{translate('Quantity')}}

                            </label>

                            <input type="number" name="low_stock_quantity" value="1" min="0" step="1" class="form-control">

                        </div>

                    </div>

                </div> -->



                <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0 h6">

                            {{translate('Stock Visibility State')}}

                        </h5>

                    </div>



                    <div class="card-body">



                        <div class="form-group row">

                            <label class="col-md-6 col-from-label">{{translate('Show Stock Quantity')}}</label>

                            <div class="col-md-6">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="radio" name="stock_visibility_state" value="quantity" checked>

                                    <span></span>

                                </label>

                            </div>

                        </div>



                        <div class="form-group row">

                            <label class="col-md-6 col-from-label">{{translate('Show Stock With Text Only')}}</label>

                            <div class="col-md-6">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="radio" name="stock_visibility_state" value="text">

                                    <span></span>

                                </label>

                            </div>

                        </div>



                        <div class="form-group row">

                            <label class="col-md-6 col-from-label">{{translate('Hide Stock')}}</label>

                            <div class="col-md-6">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="radio" name="stock_visibility_state" value="hide">

                                    <span></span>

                                </label>

                            </div>

                        </div>



                    </div>

                </div>



                <!-- <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0 h6">{{translate('Cash On Delivery')}}</h5>

                    </div>

                    <div class="card-body">

                        @if (get_setting('cash_payment') == '1')

                            <div class="form-group row">

                                <label class="col-md-6 col-from-label">{{translate('Status')}}</label>

                                <div class="col-md-6">

                                    <label class="aiz-switch aiz-switch-success mb-0">

                                        <input type="checkbox" name="cash_on_delivery" value="1" checked="">

                                        <span></span>

                                    </label>

                                </div>

                            </div>

                        @else

                            <p>

                                {{ translate('Cash On Delivery option is disabled. Activate this feature from here') }}

                                <a href="{{route('activation.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index','shipping_configuration.edit','shipping_configuration.update'])}}">

                                    <span class="aiz-side-nav-text">{{translate('Cash Payment Activation')}}</span>

                                </a>

                            </p>

                        @endif

                    </div>

                </div> -->



                <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0 h6">{{translate('Featured')}}</h5>

                    </div>

                    <div class="card-body">

                        <div class="form-group row">

                            <label class="col-md-6 col-from-label">{{translate('Status')}}</label>

                            <div class="col-md-6">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="featured" value="1">

                                    <span></span>

                                </label>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0 h6">{{translate('Unpublish')}}</h5>

                    </div>

                    <div class="card-body">

                        <div class="form-group row">

                            <label class="col-md-6 col-from-label">{{translate('Status')}}</label>

                            <div class="col-md-6">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="published" value="0">

                                    <span></span>

                                </label>

                            </div>

                        </div>

                    </div>

                </div>



                <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0 h6">{{translate('Todays Deal')}}</h5>

                    </div>

                    <div class="card-body">

                        <div class="form-group row">

                            <label class="col-md-6 col-from-label">{{translate('Status')}}</label>

                            <div class="col-md-6">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="todays_deal" value="1">

                                    <span></span>

                                </label>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0 h6">{{translate('Flash Deal')}}</h5>

                    </div>

                    <div class="card-body">

                        <div class="form-group mb-3">

                            <label for="name">

                                {{translate('Add To Flash')}}

                            </label>

                            <select class="form-control aiz-selectpicker" name="flash_deal_id" id="flash_deal">

                                <option value="">Choose Flash Title</option>

                                @foreach(\App\FlashDeal::where("status", 1)->get() as $flash_deal)

                                    <option value="{{ $flash_deal->id}}">

                                        {{ $flash_deal->title }}

                                    </option>

                                @endforeach

                            </select>

                        </div>



                        <div class="form-group mb-3">

                            <label for="name">

                                {{translate('Discount')}}

                            </label>

                            <input type="number" name="flash_discount" value="0" min="0" step="1" class="form-control">

                        </div>

                        <div class="form-group mb-3">

                            <label for="name">

                                {{translate('Discount Type')}}

                            </label>

                            <select class="form-control aiz-selectpicker" name="flash_discount_type" id="flash_discount_type">

                                <option value="">Choose Discount Type</option>

                                <option value="amount">{{translate('Flat')}}</option>

                                <option value="percent">{{translate('Percent')}}</option>

                            </select>

                        </div>

                    </div>

                </div> -->



                <!-- <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0 h6">{{translate('Estimate Shipping Time')}}</h5>

                    </div>

                    <div class="card-body">

                        <div class="form-group mb-3">

                            <label for="name">

                                {{translate('Shipping Days')}}

                            </label>

                            <div class="input-group">

                                <input type="number" class="form-control" name="est_shipping_days" min="1" step="1" placeholder="{{translate('Shipping Days')}}">

                                <div class="input-group-prepend">

                                    <span class="input-group-text" id="inputGroupPrepend">{{translate('Days')}}</span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div> -->



                <!-- <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0 h6">{{translate('VAT & Tax')}}</h5>

                    </div>

                    <div class="card-body">

                        @foreach(\App\Tax::where('tax_status', 1)->get() as $tax)

                        <label for="name">

                            {{$tax->name}}

                            <input type="hidden" value="{{$tax->id}}" name="tax_id[]">

                        </label>



                        <div class="form-row">

                            <div class="form-group col-md-6">

                                <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ translate('Tax') }}" name="tax[]" class="form-control" required>

                            </div>

                            <div class="form-group col-md-6">

                                <select class="form-control aiz-selectpicker" name="tax_type[]">

                                    <option value="amount">{{translate('Flat')}}</option>

                                    <option value="percent">{{translate('Percent')}}</option>

                                </select>

                            </div>

                        </div>

                        @endforeach

                    </div>

                </div> -->



            </div>

            <div class="col-12">

                <div class="btn-toolbar float-right mb-3" role="toolbar" aria-label="Toolbar with button groups">

                    <!-- <div class="btn-group mr-2" role="group" aria-label="First group">

                        <button type="submit" name="button" value="draft" class="btn btn-warning draft">{{ translate('Save As Draft') }}</button>

                    </div> -->

                    <!-- <div class="btn-group mr-2" role="group" aria-label="First group">

                        <button type="submit" name="button" value="draft" class="btn btn-warning draft">{{ translate('Add & Unpublish') }}</button>

                    </div> -->

                    <!-- <div class="btn-group mr-2" role="group" aria-label="Third group">

                        <input type="submit" name="button" value="unpublish" class="btn btn-primary unpublish">{{ translate('Add & Unpublish') }}>

                    </div> -->

                    <div class="btn-group" role="group" aria-label="Second group">

                        <button type="submit" name="button"  class="btn btn-success publish">{{ translate('Add & Publish') }}</button>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>





<!-- Webcam Modal -->

<div class="modal fade" id="add_ajax_product_webcam" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header justify-content-start">

        <h5 class="modal-title">Webcam</h5>

      </div>

      <div class="modal-body">

        <form name="ajax-webcam-form" id="ajax-webcam-form" method="post" action="javascript:void(0)">

         @csrf



         <input type="hidden" name="prostock" class="prostockfval">

         <div class="form-group mb-3">

           <div id="my_camera"></div>

            <input type="button" class="btn btn-success my-3 snapShotClicker" value="Take Snapshot" onClick="take_snapshot()">



            <div id="mi_webcam" ></div>

         </div>



          <button type="submit" class="btn btn-primary" id="webcamSubmit">Add</button>

      </form>

      </div>

    </div>

  </div>

</div>

<!-- Webcam Modal -->



<!-- Site Options Modal -->

<div class="modal fade" id="add_ajax_product_unit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header justify-content-start">

        <h5 class="modal-title">Add</h5>

        <h5 class="modal-title text-capitalize ml-2" id="exampleModalLabel"></h5>

      </div>

      <div class="modal-body">

        <form name="ajax-unit-form" id="ajax-unit-form" method="post" action="javascript:void(0)">

            <input type="hidden" class="product_option_name" name="option_name" value="">

           @csrf

            <div class="form-group">

              <label for="exampleInputEmail1" id="exampleInputEmail" class="text-capitalize"></label>

              <input type="text" id="option_value" name="option_value" class="form-control">

            </div>

            <div class="form-group">

            <label for="description">Description</label>

            <textarea name="description" rows="8" cols="80" class="form-control mb-2"></textarea>

            </div>

            <div class="form-group" id="low_stock">

              <label for="low_stock" class="text-capitalize">Low Stock</label>

              <input type="text" name="low_stock" class="form-control">

            </div>

            <!-- <button type="submit" class="btn btn-primary" >Submit</button> -->

            <button type="submit" class="btn btn-primary" id="unitsubmit">Add</button>

        </form>

      </div>

    </div>

  </div>

</div>

<!-- Site Options Modal -->



<!-- Condition Modal -->

<div class="modal fade" id="add_ajax_product_condition" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header justify-content-start">

        <h5 class="modal-title">Add Condition</h5>

      </div>

      <div class="modal-body">

        <form name="ajax-condition-form" id="ajax-condition-form" method="post" action="javascript:void(0)">

         @csrf

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="Name">

                 {{ translate('Name')}}

             </label>

             <div class="col-sm-9">

                 <input type="text" placeholder="{{ translate('Name')}}" name="name" class="form-control" >

             </div>

         </div>

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="Description">

                 {{ translate('Description')}}

             </label>

             <div class="col-sm-9">

                 <input type="text" placeholder="{{ translate('Description')}}" id="description" name="description" class="form-control" >

             </div>

         </div>

          <!-- <button type="submit" class="btn btn-primary" >Submit</button> -->

          <button type="submit" class="btn btn-primary" id="conditionsubmit">Add</button>

      </form>

      </div>

    </div>

  </div>

</div>

<!-- Condition Modal -->



<!-- Brand Modal -->

<div class="modal fade" id="add_ajax_product_brand" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header justify-content-start">

        <h5 class="modal-title">Add Brand</h5>

      </div>

      <div class="modal-body">

        <form name="ajax-brand-form" id="ajax-brand-form" method="post" action="javascript:void(0)">

         @csrf

         <div class="form-group mb-3">

           <label for="name">{{translate('Name')}}</label>

           <input type="text" placeholder="{{translate('Name')}}" name="name" class="form-control" >

         </div>

         <div class="form-group mb-3">

           <label for="name">{{translate('Logo')}} <small>({{ translate('120x80') }})</small></label>

           <div class="input-group mi_custom_uploader" data-toggle="aizuploader" data-type="image">

             <div class="input-group-prepend">

                 <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>

             </div>

             <div class="form-control file-amount">{{ translate('Choose File') }}</div>

             <input type="hidden" name="logo" class="selected-files">

           </div>

           <div class="file-preview box sm">

           </div>

         </div>

         <!-- <div class="form-group mb-3">

           <label for="name">{{translate('Meta Title')}}</label>

           <input type="text" class="form-control" name="meta_title" placeholder="{{translate('Meta Title')}}">

         </div> -->

         <div class="form-group mb-3">

           <label for="name">{{translate('Description')}}</label>

           <textarea name="meta_description" rows="5" class="form-control"></textarea>

         </div>

          <!-- <button type="submit" class="btn btn-primary" >Submit</button> -->

          <button type="submit" class="btn btn-primary" id="brandsubmit">Add</button>

      </form>

      </div>

    </div>

  </div>

</div>

<!-- Brand Modal -->



<!-- warehouse Modal -->

<div class="modal fade" id="add_ajax_product_warehouse" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header justify-content-start">

        <h5 class="modal-title">Add Warehouse</h5>

      </div>

      <div class="modal-body">

        <form name="ajax-warehouse-form" id="ajax-warehouse-form" method="post" action="javascript:void(0)">

         @csrf

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="Name">

                 {{ translate('Name')}}

             </label>

             <div class="col-sm-9">

                 <input type="text" placeholder="{{ translate('Name')}}"  name="name" class="form-control" required>

             </div>

         </div>

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="Code">

                 {{ translate('Description')}}

             </label>

             <div class="col-sm-9">

               <textarea class="form-control" placeholder="{{ translate('Description')}}" name="code" cols="30"></textarea>

                 <!-- <input type="text" placeholder="{{ translate('Description')}}" id="code" name="code" class="form-control" required> -->

             </div>

         </div>

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="Address">

                 {{ translate('Address')}}

             </label>

             <div class="col-sm-9">

                 <input type="text" placeholder="{{ translate('Address')}}" name="address" class="form-control" required>

             </div>

         </div>

          <!-- <button type="submit" class="btn btn-primary" >Submit</button> -->

          <button type="submit" class="btn btn-primary" id="warehousesubmit">Add</button>

      </form>

      </div>

    </div>

  </div>

</div>



<!-- Tag Modal -->

<div class="modal fade" id="add_ajax_product_tag" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header justify-content-start">

        <h5 class="modal-title">Add Tag</h5>

      </div>

      <div class="modal-body">

        <form name="ajax-tag-form" id="ajax-tag-form" method="post" action="javascript:void(0)">

         @csrf

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="Name">

                 {{ translate('Name')}}

             </label>

             <div class="col-sm-9">

                 <input type="text" placeholder="{{ translate('Name')}}"  name="tags" class="form-control">

             </div>

         </div>

          <!-- <button type="submit" class="btn btn-primary" >Submit</button> -->

          <button type="submit" class="btn btn-primary" id="tagsubmit">Add</button>

      </form>

      </div>

    </div>

  </div>

</div>



<!-- Category Modal -->

<div class="modal fade" id="add_ajax_product_category" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header justify-content-start">

        <h5 class="modal-title">Add Category</h5>

      </div>

      <div class="modal-body">

        <form name="ajax-category-form" id="ajax-category-form" method="post" action="javascript:void(0)">

         @csrf

                 <div class="form-group row">

                     <label class="col-md-3 col-form-label">{{translate('Category Code *')}}</label>

                     <div class="col-md-9">

                         <input type="text" class="form-control" name="meta_title" placeholder="{{translate('Category Code *')}}">

                     </div>

                 </div>

                   <div class="form-group row">

                       <label class="col-md-3 col-form-label">{{translate('Category Name')}}</label>

                       <div class="col-md-9">

                           <input type="text" placeholder="{{translate('Name')}}"  name="name" class="form-control" >

                           <input type="hidden" name="digital" value="0">

                       </div>

                   </div>





                   <!-- <div class="form-group row">

                       <label class="col-md-3 col-form-label">{{translate('Digital')}}</label>

                       <div class="col-md-9">

                           <select name="digital" required class="form-control aiz-selectpicker mb-2 mb-md-0">

                               <option value="0">{{translate('Physical')}}</option>

                               <option value="1">{{translate('Digital')}}</option>

                           </select>

                       </div>

                   </div> -->

                   <div class="form-group row">

                       <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Category Image')}} <small>({{ translate('200x200') }})</small></label>

                       <div class="col-md-9">

                           <div class="input-group mi_custom_uploader" data-toggle="aizuploader" data-type="image">

                               <div class="input-group-prepend">

                                   <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>

                               </div>

                               <div class="form-control file-amount">{{ translate('Choose File') }}</div>

                               <input type="hidden" name="banner" class="selected-files">

                           </div>

                           <div class="file-preview box sm">

                           </div>

                       </div>

                   </div>

                   <!-- <div class="form-group row">

                       <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Icon')}} <small>({{ translate('32x32') }})</small></label>

                       <div class="col-md-9">

                           <div class="input-group" data-toggle="aizuploader" data-type="image">

                               <div class="input-group-prepend">

                                   <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>

                               </div>

                               <div class="form-control file-amount">{{ translate('Choose File') }}</div>

                               <input type="hidden" name="icon" class="selected-files">

                           </div>

                           <div class="file-preview box sm">

                           </div>

                       </div>

                   </div> -->





                   <div class="form-group row">

                       <label class="col-md-3 col-form-label">{{translate('Description')}}</label>

                       <div class="col-md-9">

                           <textarea name="meta_description" rows="5" class="form-control"></textarea>

                       </div>

                   </div>

                   @if (get_setting('category_wise_commission') == 1)

                       <div class="form-group row">

                           <label class="col-md-3 col-form-label">{{translate('Commission Rate')}}</label>

                           <div class="col-md-9 input-group">

                               <input type="number" lang="en" min="0" step="0.01" placeholder="{{translate('Commission Rate')}}" id="commision_rate" name="commision_rate" class="form-control">

                               <div class="input-group-append">

                                   <span class="input-group-text">%</span>

                               </div>

                           </div>

                       </div>

                   @endif

                   <!-- <div class="form-group row">

                       <label class="col-md-3 col-form-label">{{translate('Filtering Attributes')}}</label>

                       <div class="col-md-9">

                           <select class="select2 form-control aiz-selectpicker" name="filtering_attributes[]" data-toggle="select2" data-placeholder="Choose ..."data-live-search="true" multiple>

                               @foreach (\App\Attribute::all() as $attribute)

                                   <option value="{{ $attribute->id }}">{{ $attribute->getTranslation('name') }}</option>

                               @endforeach

                           </select>

                       </div>

                   </div> -->

                   <!-- <div class="form-group row">

                       <label class="col-md-3 col-form-label">{{translate('Parent Category')}}</label>

                       <div class="col-md-9">

                           <select class="select2 form-control aiz-selectpicker" name="parent_id" data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">

                               <option value="0">{{ translate('No Parent') }}</option>

                               @foreach ($categories as $category)

                                   <option value="{{ $category->id }}">{{ $category->getTranslation('name') }}</option>

                                   @foreach ($category->childrenCategories as $childCategory)

                                       @include('categories.child_category', ['child_category' => $childCategory])

                                   @endforeach

                               @endforeach

                           </select>

                       </div>

                   </div> -->

                   <!-- <div class="form-group row">

                        <label class="col-md-3 col-form-label">

                            {{translate('Order Code *')}}

                        </label>

                        <div class="col-md-9">

                            <input type="number" name="order_level" class="form-control" id="order_level" placeholder="{{translate('Order Level')}}">

                            <small>{{translate('Higher number has high priority')}}</small>

                        </div>

                   </div> -->

          <!-- <button type="submit" class="btn btn-primary" >Submit</button> -->

          <button type="submit" class="btn btn-primary" id="categorysubmit">Add</button>

      </form>

      </div>

    </div>

  </div>

</div>

<!-- Category Modal -->



<!-- Seller Modal -->

<div class="modal fade" id="add_ajax_product_seller" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header justify-content-start">

        <h5 class="modal-title">Add Seller</h5>

      </div>

      <div class="modal-body">

        <form name="ajax-seller-form" id="ajax-seller-form" method="post" action="javascript:void(0)">

         @csrf

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="name">{{translate('Company Name * ')}}</label>

             <div class="col-sm-9">

                 <input type="text" placeholder="{{translate('company name *')}}" id="company" name="company" class="form-control" required>

             </div>

         </div>

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="name">{{translate('Contact Name *')}}</label>

             <div class="col-sm-9">

                 <input type="text" placeholder="{{translate('Contact Name *')}}" name="name" class="form-control" required>

             </div>

         </div>

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="name">{{translate('Reseller Permit')}}</label>

             <div class="col-sm-9">

                 <input type="text" placeholder="{{translate('Reseller Permit')}}" id="reseller_permit" name="reseller_permit" class="form-control" >

             </div>

         </div>

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="name">{{translate('Tax ID')}}</label>

             <div class="col-sm-9">

                 <input type="text" placeholder="{{translate('Tax ID')}}" id="tax_id" name="tax_id" class="form-control" >

             </div>

         </div>

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="name">{{translate('Contact Number *')}}</label>

             <div class="col-sm-9">

                 <input type="tel" placeholder="{{ translate('(___) - ___ - ____')}}" id="phone" name="phone" maxlength="13" class="form-control" required>

             </div>

         </div>

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="name">{{translate('Company Address')}}</label>

             <div class="col-sm-9">

                 <input type="tel" placeholder="{{translate('Company Address')}}" id="company_address" name="company_address" class="form-control" >

             </div>

         </div>

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="name">{{translate('Address')}}</label>

             <div class="col-sm-9">

                 <input type="tel" placeholder="{{translate('Address')}}" name="address" class="form-control" >

             </div>

         </div>

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="name">{{translate('City')}}</label>

             <div class="col-sm-9">

                 <input type="tel" placeholder="{{translate('City')}}" id="city" name="city" class="form-control" >

             </div>

         </div>



         <div class="form-group row">

            <label class="col-sm-3 col-from-label" for="name">{{translate('State')}}</label>

            <div class="col-sm-9" id="create_state_id_seller">

            </div>

         </div>

         <div class="form-group row">

            <label class="col-sm-3 col-from-label" for="name">{{translate('Country')}}</label>

            <div class="col-sm-9">

                 <select class="form-select form-control aiz-selectpicker" id="country_seller" name="country" aria-label="Default select example" data-live-search="true">

                    <option  value="United States" >US (United States)</option>

                    @foreach($countries_detail as $country)

                    <option  value="{{$country->name}}" >{{$country->code}} ({{$country->name}})</option>

                    @endforeach

                </select>

            </div>

        </div>

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="name">{{translate('Postal Code')}}</label>

             <div class="col-sm-9">

                 <input type="tel" placeholder="{{translate('Postal Code')}}" id="postal_code" name="postal_code" class="form-control" >

             </div>

         </div>



           <div class="form-group row">

               <label class="col-sm-3 col-from-label" for="email">{{translate('Email Address *')}}</label>

               <div class="col-sm-9">

                   <input type="email" placeholder="{{translate('Email Address *')}}" id="email" name="email" class="form-control" required>

                   <span id="span"></span>

                   <!-- @if ($errors->has('email'))

                    <span class="text-danger">{{ $errors->first('email') }}</span>

                    @endif -->

               </div>

           </div>

           <!-- <div class="form-group row">

               <label class="col-sm-3 col-from-label" for="password">{{translate('Password')}}</label>

               <div class="col-sm-9">

                   <input type="password" placeholder="{{translate('Password')}}" id="password" name="password" class="form-control" >

               </div>

           </div> -->

           <br>

           <h6>Bank Information</h6>

           <hr>

           <div class="form-group row">

               <label class="col-sm-3 col-from-label" for="AC">{{translate('Account Name')}}</label>

               <div class="col-sm-9">

                   <input type="text" placeholder="{{translate('Account Name')}}" id="bank_acc_name" name="bank_acc_name" class="form-control" >

               </div>

           </div>

           <div class="form-group row">

               <label class="col-sm-3 col-from-label" for="ACH">{{translate('ACH#')}}</label>

               <div class="col-sm-9">

                   <input type="text" placeholder="{{translate('ACH#')}}" id="ach" name="ach" class="form-control" >

               </div>

           </div>

           <div class="form-group row">

               <label class="col-sm-3 col-from-label" for="Routing">{{translate('Routing#')}}</label>

               <div class="col-sm-9">

                   <input type="text" placeholder="{{translate('Routing#')}}" id="bank_routing_no" name="bank_routing_no" class="form-control" >

               </div>

           </div>

           <div class="form-group row">

               <label class="col-sm-3 col-from-label" for="Account No">{{translate('Account No#')}}</label>

               <div class="col-sm-9">

                   <input type="text" placeholder="{{translate('Account No#')}}" id="bank_acc_no" name="bank_acc_no" class="form-control" >

               </div>

           </div>

           <div class="form-group row">

               <label class="col-sm-3 col-from-label" for="Bank address">{{translate('Bank address')}}</label>

               <div class="col-sm-9">

                   <input type="text" placeholder="{{translate('Bank address')}}" id="bank_address" name="bank_address" class="form-control" >

               </div>

           </div>

          <!-- <button type="submit" class="btn btn-primary" >Submit</button> -->

          <button type="submit" class="btn btn-primary" id="sellersubmit">Add</button>

      </form>

      </div>

    </div>

  </div>

</div>

<!-- Seller Modal -->

<div class="bg-overlay">

  <div id="dialog" title="Restock Product">

      <p>Serial Number already exists !</p>

      <h6>Restock Product</h6>

      <div class="button">

        <button type="button" name="restock" data-toggle="modal" data-target="#add_ajax_restock"  class="btn btn-success restock">Restock</button>

        <button type="button" class="btn btn-danger restockcancel">Cancel</button>

      </div>

  </div>

</div>



<div class="modal fade" id="add_ajax_restock" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header justify-content-start">

        <h5 class="modal-title">Restock product</h5>

      </div>

      <div class="modal-body">

        <form name="ajax-restock-form" id="ajax-restock-form" method="post" action="javascript:void(0)">

         @csrf

         <input type="hidden" name="restockid" class="restockid" value="">

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="Serial">

                 {{ translate('Serial')}}

             </label>

             <div class="col-sm-9">

                 <input type="text" placeholder="{{ translate('Serial')}}"  name="sku" class="form-control">

             </div>

         </div>

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="Quantity">

                 {{ translate('Quantity')}}

             </label>

             <div class="col-sm-9">

                 <input type="text" placeholder="{{ translate('Quantity')}}" value="1"  name="qty" class="form-control">

             </div>

         </div>

          <!-- <button type="submit" class="btn btn-primary" >Submit</button> -->

          <button type="submit" class="btn btn-primary" id="tagsubmit">Add</button>

      </form>

      </div>

    </div>

  </div>

</div>



@endsection



@section('script')



<script type="text/javascript">

    $('form').bind('submit', function (e) {

        // Disable the submit button while evaluating if the form should be submitted

        $("button[type='submit']").prop('disabled', true);



        var valid = true;



        if (!valid) {

            e.preventDefault();



            // Reactivate the button if the form was not submitted

            $("button[type='submit']").button.prop('disabled', false);

        }

    });



    $("[name=shipping_type]").on("change", function (){

        $(".flat_rate_shipping_div").hide();



        if($(this).val() == 'flat_rate'){

            $(".flat_rate_shipping_div").show();

        }



    });



    // function add_more_customer_choice_option(i, name){
    //
    //     $.ajax({
    //
    //         headers: {
    //
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //
    //         },
    //
    //         type:"POST",
    //
    //         url:'{{ route('products.add-more-choice-option') }}',
    //
    //         data:{
    //
    //            attribute_id: i
    //
    //         },
    //
    //         success: function(data) {
    //
    //             var obj = JSON.parse(data);
    //
    //             $('#customer_choice_options').append('\
    //
    //             <div class="form-group row">\
    //
    //                 <div class="col-md-3">\
    //
    //                     <input type="hidden" name="choice_no[]" value="'+i+'">\
    //
    //                     <input type="text" class="form-control" name="choice[]" value="'+name+'" placeholder="{{ translate('Choice Title') }}" readonly>\
    //
    //                 </div>\
    //
    //                 <div class="col-md-8">\
    //
    //                     <select class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_'+ i +'[]" multiple>\
    //
    //                         '+obj+'\
    //
    //                     </select>\
    //
    //                 </div>\
    //
    //             </div>');
    //
    //             AIZ.plugins.bootstrapSelect('refresh');
    //
    //        }
    //
    //    });
    //
    //
    //
    //
    //
    // }



    $('input[name="colors_active"]').on('change', function() {

        if(!$('input[name="colors_active"]').is(':checked')) {

            $('#colors').prop('disabled', true);

            AIZ.plugins.bootstrapSelect('refresh');

        }

        else {

            $('#colors').prop('disabled', false);

            AIZ.plugins.bootstrapSelect('refresh');

        }

        update_sku();

    });

    $(document).on("click",".optionsubmit",function(){

      var optionValPro = $(this).data('typereq');

      $(".product_option_name").val(optionValPro);

      $("#exampleModalLabel").text(optionValPro);

      $("#exampleInputEmail").text(optionValPro);



      // alert(optionValPro);

    });



    $(document).on("change", ".attribute_choice",function() {

        update_sku();

    });



    $('#colors').on('change', function() {

        update_sku();

    });



    $('input[name="unit_price"]').on('keyup', function() {

        update_sku();

    });



    $('input[name="unit_cost"]').on('keyup', function() {

        update_sku();

    });



    $('input[name="name"]').on('keyup', function() {

        update_sku();

    });



    function delete_row(em){

        $(em).closest('.form-group row').remove();

        update_sku();

    }



    function delete_variant(em){

        $(em).closest('.variant').remove();

    }



    function update_sku(){

       //  $.ajax({

       //     type:"POST",

       //     url:'{{ route('products.sku_combination') }}',

       //     data:$('#choice_form').serialize(),

       //     success: function(data) {

       //          $('#sku_combination').html(data);

       //          AIZ.uploader.previewGenerate();

       //          AIZ.plugins.fooTable();

       //          if (data.length > 1) {

       //             $('#show-hide-div').hide();

       //          }

       //          else {

       //              $('#show-hide-div').show();

       //          }

       //     }

       // });

    }



    $('#choice_attributes').on('change', function() {

        $('#customer_choice_options').html(null);

        $.each($("#choice_attributes option:selected"), function(){

            add_more_customer_choice_option($(this).val(), $(this).text());

        });



        update_sku();

    });



</script>

<script>

  $(function() {

    $('input[name="dop"]').daterangepicker({

      singleDatePicker: true,

      showDropdowns: true,

      minYear: 1901,

      maxYear: parseInt(moment().format('YYYY'),10)

    });

  });

</script>



<!-- unit Ajax start -->

<script>

$('#ajax-unit-form').on('submit', function(e) {

  e.preventDefault();

  $.ajax({

      url: "{{route('products.unitAjax')}}",

      type: "POST",

      data: $('#ajax-unit-form').serialize(),

      success: function( response ) {



        var optName = response.optname;

        console.log(optName);

        $('#'+optName).html(response.optionHtml);

        if(optName == "model"){

          $('#tags_id').append(response.tagHtmlData);

        }

        $('#tags_id').select2();

        $('#'+optName).selectpicker("refresh");

        $('#ajax-unit-form').trigger("reset");

        $('#add_ajax_product_unit').modal('hide');

        $('#categorysubmit').removeAttr('disabled',false);

        $('#unitsubmit').removeAttr('disabled',false);

        $('#sellersubmit').removeAttr('disabled',false);

        $('#conditionsubmit').removeAttr('disabled',false);

        $('#brandsubmit').removeAttr('disabled',false);

        $('.unpublish').removeAttr('disabled',false);

        $('.draft').removeAttr('disabled',false);

        $('.publish').removeAttr('disabled',false);

        $('#warehousesubmit').removeAttr('disabled',false);

        $('#tagsubmit').removeAttr('disabled',false);

        $('#webcamSubmit').removeAttr('disabled',false);

        Swal.fire({

        position: 'top-end',

        title: optName +' Added Successfully',

        color: '#ffffff',

        background: '#63bf81',

        showConfirmButton: false,

        timer: 1500,

      })

      }

     });

   });



</script>

<!-- // Condition Ajax -->

<script>

$('#ajax-condition-form').on('submit', function(e) {

  e.preventDefault();

  $.ajax({

      url: "{{route('products.ConditionAjax')}}",

      type: "POST",

      data: $('#ajax-condition-form').serialize(),

      success: function( response ) {



        $('#productcondition').html(response.conHtml);

        $('#productcondition').selectpicker("refresh");

        // $('form').html(response);

        // $('#add_ajax_product_unit').model('hide');

        // alert('Ajax form has been submitted successfully');

        $('#ajax-condition-form').trigger("reset");

        $('#add_ajax_product_condition').modal('hide');

        $('#categorysubmit').removeAttr('disabled',false);

        $('#unitsubmit').removeAttr('disabled',false);

        $('#sellersubmit').removeAttr('disabled',false);

        $('#conditionsubmit').removeAttr('disabled',false);

        $('#brandsubmit').removeAttr('disabled',false);

        $('.unpublish').removeAttr('disabled',false);

        $('.draft').removeAttr('disabled',false);

        $('.publish').removeAttr('disabled',false);

        $('#warehousesubmit').removeAttr('disabled',false);

        $('#tagsubmit').removeAttr('disabled',false);

        $('#webcamSubmit').removeAttr('disabled',false);



        Swal.fire({

        position: 'top-end',

        title: 'Condition Added Successfully',

        color: '#ffffff',

        background: '#63bf81',

        showConfirmButton: false,

        timer: 1500,

      })

      }

     });

   });



</script>

<!-- // Brand Ajax -->

<script>

$('#ajax-brand-form').on('submit', function(e) {

  e.preventDefault();

  $.ajax({

      url: "{{route('products.BrandAjax')}}",

      type: "POST",

      data: $('#ajax-brand-form').serialize(),

      success: function( response ) {



        $('#brand_id').html(response.brandHTML);

        $('#tags_id').append(response.tagHtmlData);

        $('#tags_id').select2();

        $('#brand_id').selectpicker("refresh");

        // $('#add_ajax_product_unit').model('hide');

        // alert('Ajax form has been submitted successfully');

        $('#ajax-brand-form').trigger("reset");

        $('#add_ajax_product_brand').modal('hide');

        $('#categorysubmit').removeAttr('disabled',false);

        $('#unitsubmit').removeAttr('disabled',false);

        $('#sellersubmit').removeAttr('disabled',false);

        $('#conditionsubmit').removeAttr('disabled',false);

        $('#brandsubmit').removeAttr('disabled',false);

        $('.unpublish').removeAttr('disabled',false);

        $('.draft').removeAttr('disabled',false);

        $('.publish').removeAttr('disabled',false);

        $('#warehousesubmit').removeAttr('disabled',false);

        $('#tagsubmit').removeAttr('disabled',false);

        $('#webcamSubmit').removeAttr('disabled',false);



        Swal.fire({

        position: 'top-end',

        title: 'Brand Added Successfully',

        color: '#ffffff',

        background: '#63bf81',

        showConfirmButton: false,

        timer: 1500,

      })

      }

     });

   });



</script>





<!-- // Warehouse Ajax -->

<script>

$('#ajax-warehouse-form').on('submit', function(e) {

  e.preventDefault();

  $.ajax({

      url: "{{route('products.WarehouseAjax')}}",

      type: "POST",

      data: $('#ajax-warehouse-form').serialize(),

      success: function( response ) {



        $('#warehouse_id').html(response.warehouseHTML);

        $('#warehouse_id').selectpicker("refresh");

        // $('#add_ajax_product_unit').model('hide');

        // alert('Ajax form has been submitted successfully');

        $('#ajax-warehouse-form').trigger("reset");

        $('#add_ajax_product_warehouse').modal('hide');

        $('#categorysubmit').removeAttr('disabled',false);

        $('#unitsubmit').removeAttr('disabled',false);

        $('#sellersubmit').removeAttr('disabled',false);

        $('#conditionsubmit').removeAttr('disabled',false);

        $('#brandsubmit').removeAttr('disabled',false);

        $('#warehousesubmit').removeAttr('disabled',false);

        $('#tagsubmit').removeAttr('disabled',false);

        $('.unpublish').removeAttr('disabled',false);

        $('.draft').removeAttr('disabled',false);

        $('.publish').removeAttr('disabled',false);

        $('#webcamSubmit').removeAttr('disabled',false);



        Swal.fire({

        position: 'top-end',

        title: 'Warehouse Added Successfully',

        color: '#ffffff',

        background: '#63bf81',

        showConfirmButton: false,

        timer: 1500,

      })

      }

     });

   });



</script>





<!-- // Tag Ajax -->

<script>

$('#ajax-tag-form').on('submit', function(e) {

  e.preventDefault();

  $.ajax({

      url: "{{route('products.TagAjax')}}",

      type: "POST",

      data: $('#ajax-tag-form').serialize(),

      success: function( response ) {



        $('#tags_id').append(response.TagHTML);

        $('#tags_id').selectpicker("refresh");

        // $('#add_ajax_product_unit').model('hide');

        // alert('Ajax form has been submitted successfully');

        $('#ajax-tag-form').trigger("reset");

        $('#add_ajax_product_tag').modal('hide');

        $('#categorysubmit').removeAttr('disabled',false);

        $('#unitsubmit').removeAttr('disabled',false);

        $('#sellersubmit').removeAttr('disabled',false);

        $('#conditionsubmit').removeAttr('disabled',false);

        $('#brandsubmit').removeAttr('disabled',false);

        $('#warehousesubmit').removeAttr('disabled',false);

        $('#tagsubmit').removeAttr('disabled',false);

        $('.unpublish').removeAttr('disabled',false);

        $('.draft').removeAttr('disabled',false);

        $('.publish').removeAttr('disabled',false);

        $('#webcamSubmit').removeAttr('disabled',false);

            Swal.fire({

            position: 'top-end',

            title: 'Tag Added Successfully',

            color: '#ffffff',

            background: '#63bf81',

            showConfirmButton: false,

            timer: 1500,

          })

      }

     });

   });





   //restock Ajax

$('#ajax-restock-form').on('submit', function(e) {

  e.preventDefault();

  $.ajax({

      url: "{{route('products.Restock')}}",

      type: "POST",

      data: $('#ajax-restock-form').serialize(),

      success: function( response ) {



        $('#ajax-tag-form').trigger("reset");

        $('#add_ajax_product_tag').modal('hide');

        $('#add_ajax_restock').modal('hide');

        $('#dialog').modal('hide');

        $('#categorysubmit').removeAttr('disabled',false);

        $('#unitsubmit').removeAttr('disabled',false);

        $('#sellersubmit').removeAttr('disabled',false);

        $('#conditionsubmit').removeAttr('disabled',false);

        $('#brandsubmit').removeAttr('disabled',false);

        $('#warehousesubmit').removeAttr('disabled',false);

        $('#tagsubmit').removeAttr('disabled',false);

        $('.unpublish').removeAttr('disabled',false);

        $('.draft').removeAttr('disabled',false);

        $('.publish').removeAttr('disabled',false);

        $('#webcamSubmit').removeAttr('disabled',false);

            Swal.fire({

            position: 'top-end',

            title: 'Product Restocked',

            color: '#ffffff',

            background: '#63bf81',

            showConfirmButton: false,

            timer: 1500,

          })

      }

     });

   });



</script>





<!-- // Category Ajax -->

<script>

$('#ajax-category-form').on('submit', function(e) {

  e.preventDefault();

  $.ajax({

      url: "{{route('products.CategoryAjax')}}",

      type: "POST",

      data: $('#ajax-category-form').serialize(),

      success: function( response ) {



        $('#category_id').html(response.catHTML);

        $('#tags_id').append(response.tagHtmlCat);

        $('#tags_id').select2();

        $('#category_id').selectpicker("refresh");

        // $('#add_ajax_product_unit').model('hide');

        // alert('Ajax form has been submitted successfully');

        $('#ajax-category-form').trigger("reset");

        $('#add_ajax_product_category').modal('hide');

        $('#categorysubmit').removeAttr('disabled',false);

        $('#unitsubmit').removeAttr('disabled',false);

        $('#sellersubmit').removeAttr('disabled',false);

        $('#conditionsubmit').removeAttr('disabled',false);

        $('#brandsubmit').removeAttr('disabled',false);

        $('.unpublish').removeAttr('disabled',false);

        $('.draft').removeAttr('disabled',false);

        $('.publish').removeAttr('disabled',false);

        $('#warehousesubmit').removeAttr('disabled',false);

        $('#tagsubmit').removeAttr('disabled',false);

        $('#webcamSubmit').removeAttr('disabled',false);



        Swal.fire({

        position: 'top-end',

        title: 'Category Added Successfully',

        color: '#ffffff',

        background: '#63bf81',

        showConfirmButton: false,

        timer: 1500,

      })

      }

     });

   });



</script>

<!-- // Seller Ajax -->

<script>

$('#ajax-seller-form').on('submit', function(e) {

  e.preventDefault();

  $.ajaxSetup({

    headers: {

        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

    }

  });

  $.ajax({

      url: "{{route('products.SellerAjax')}}",

      type: "POST",

    //   dataType: "json",

      data: $('#ajax-seller-form').serialize(),

      success: function( response ) {

        //   alert(response.exists);

        $('#supplier_id').html(response.selleroptionHTMl);

        $('#supplier_id').selectpicker("refresh");

        $('#ajax-seller-form').trigger("reset");

        $('#add_ajax_product_seller').modal('hide');

        $('#categorysubmit').removeAttr('disabled',false);

        $('#unitsubmit').removeAttr('disabled',false);

        $('#sellersubmit').removeAttr('disabled',false);

        $('#conditionsubmit').removeAttr('disabled',false);

        $('#brandsubmit').removeAttr('disabled',false);

        $('.unpublish').removeAttr('disabled',false);

        $('.draft').removeAttr('disabled',false);

        $('.publish').removeAttr('disabled',false);

        $('#warehousesubmit').removeAttr('disabled',false);

        $('#tagsubmit').removeAttr('disabled',false);

        $('#webcamSubmit').removeAttr('disabled',false);

        if(response.exists== true){

            Swal.fire({

            position: 'top-end',

            title: 'Email already exists !',

            color: '#ffffff',

            background: '#F31607',

            showConfirmButton: false,

            timer: 1500,

           })

        }

        else{

            Swal.fire({

            position: 'top-end',

            title: 'Seller Added Successfully',

            color: '#ffffff',

            background: '#63bf81',

            showConfirmButton: false,

            timer: 1500,

           })

        }

      }

     });

   });

   // In your Javascript (external .js resource or <script> tag)

    $(document).ready(function() {

        $('.js-example-basic-multiple').select2();

    });

    //

    // $(document).on('click','.file-amount',function(){

    //   setTimeout(function () {

    //        $('#aizUpload').trigger('click');

    //  }, 2000);

    // })





    $(document).ready(function(){



$("input[name='phone']").keyup(function() {

  $(this).val($(this).val().replace(/^(\d{3})(\d{3})(\d+)$/, "($1)$2-$3"));

});

});

</script>



<script type="text/javascript">



$(document).ready(function() {

  $('#brand_id').change(function() {

    var selectedOpt = $('#brand_id option:selected').text();

    console.log(selectedOpt);

    $("#tags_id option[value='"+selectedOpt+"']").prop('selected', true);

      $('.js-example-basic-multiple').select2();

  });



  $('#category_id').change(function() {

    var selectedOptCat = $('#category_id option:selected').text();

    var selectedOptCatRes = selectedOptCat.replace("---- ","");

    var selectedOptCatRes = selectedOptCatRes.replace("-- ","");

    console.log(selectedOptCatRes);

    $("#tags_id option[value='"+selectedOptCatRes+"']").prop('selected', true);

      $('.js-example-basic-multiple').select2();

  });



  $('#model').change(function() {

    var selectedOptModel = $('#model option:selected').text();

    $("#tags_id option[value='"+selectedOptModel+"']").prop('selected', true);

      $('.js-example-basic-multiple').select2();

  });

});











$('#email').keyup(function(){

var expr = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

var validEmail = $(this).val();

if (!expr.test(validEmail)) {

   $('#span').css('color','red');

   $('#span').html('Please enter valid email.');;

  }

  else {

   $('#span').hide();

  }

})



</script>

<!-- Configure a few settings and attach camera -->

<script language="JavaScript">

 Webcam.set({

     width: 320,

     height: 240,

     image_format: 'jpeg',

     jpeg_quality: 90

 });



// <!-- Code to handle taking the snapshot and displaying it locally -->

function take_snapshot() {



   // take snapshot and get image data

   Webcam.snap( function(data_uri) {

       // display mi_webcam in page

       // document.getElementById('mi_webcam').innerHTML =

       //  '<img src="'+data_uri+'"/>';

        $("#mi_webcam").append('<div class="mi_remove_web_img"><input type="hidden" class="camera_image_upload" name="mi_custom_cam_image[]" value="'+data_uri+'"><img class="mi_custom_cam_image"  src="'+data_uri+'" style="margin: 10px 0;"/> <button class="webcamRemove">x</button></div>');

    } );

}



$('#mi_webcam').on('click', '.webcamRemove', function(e) {

    e.preventDefault();

    $(this).closest('.mi_remove_web_img').remove();

});



</script>

<script type="text/javascript">

$('.webcamsubmit').click(function(){

    var stockadded = $("#stock_id_custom").val();

    if(stockadded == ""){

        alert("First fill up all required details");

        return false

    }else{

        $('.prostockfval').val(stockadded);

    }



    var constraints = {video: true};

    navigator.mediaDevices.getUserMedia(constraints)

    .then(function(stream) {

        Webcam.attach( '#my_camera' );

        $('#add_ajax_product_webcam').modal('show');

    })

    .catch(function(err) {

      alert('Error: Could not access webcam: NotFoundError: Requested device not found');

    });

});



$('#ajax-webcam-form').submit(function(){





  $.ajaxSetup({

    headers: {

        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

    }

  });

  $.ajax({

    url:"{{ route('products.mi_custom_cam_image') }}",

    type: 'post',

    data: $('#ajax-webcam-form').serialize(),

    success: function( responce ) {

       // console.log(responce.camImageData);

       $('.file-preview').append(responce.camImageData);

       $('[name="photos"]').val(responce.camImageDataID);

       $('#ajax-webcam-form').trigger("reset");

       $('#add_ajax_product_webcam').modal('hide');

       $('#categorysubmit').removeAttr('disabled',false);

       $('#unitsubmit').removeAttr('disabled',false);

       $('#sellersubmit').removeAttr('disabled',false);

       $('#conditionsubmit').removeAttr('disabled',false);

       $('#brandsubmit').removeAttr('disabled',false);

       $('.unpublish').removeAttr('disabled',false);

       $('.draft').removeAttr('disabled',false);

       $('.publish').removeAttr('disabled',false);

       $('#warehousesubmit').removeAttr('disabled',false);

       $('#tagsubmit').removeAttr('disabled',false);

       $('#webcamSubmit').removeAttr('disabled',false);

       $('#mi_webcam').html("");

    }

  });

});



  $(document).ready(()=>{

      $(document).on("blur",'#skuid', function(e) {

          let sku = $('#skuid').val();

          // console.log(sku);

          // alert(sku);

          $.ajax({

            method: "POST",

            url: "{{route('product.sku.create.product')}}",

            data:{'sku':sku, "_token":"{{ csrf_token()}}"},

            success: function (res) {

              if(res.success){

                if(res.QtyHTML > 0){

                  Swal.fire({

                      position: 'top',

                      title: 'Serial Number already exists !',

                      color: '#ffffff',

                      background: '#F31607',

                      showConfirmButton: false,

                     });

                }else{

                  $(function() {



                     $( "#dialog" ).dialog({

                       modal: true,

                     });

                     $('.restockcancel').data("id",res.idHTML);

                     $('.restockid').val(res.idHTML);

                 });

                }

               }

            }

          })

      });

  });



  $('.restock').on("click", function(){

    $( "#dialog" ).dialog('destroy').remove();

  });





  $('.restockcancel').on("click", function(){

    var id = $(this).data("id");

    var url = '{{ route("products.admin.edit", ":id") }}';

    url = url.replace(':id', id);

    window.location.href = url;

  });



</script>



@endsection
