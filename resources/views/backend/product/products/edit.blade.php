@extends('backend.layouts.app')

@section('content')

<script src="{{ static_asset('assets/js/webcam.min.js') }}" ></script>

<div class="aiz-titlebar text-left mt-2 mb-3">

    <h1 class="mb-0 h6">{{ translate('Edit Product') }}</h5>

</div>

<div class="">

    <form class="form form-horizontal mar-top" action="{{route('products.update', $product->id)}}" method="POST" enctype="multipart/form-data" id="choice_form">

        <div class="row gutters-5">

            <div class="col-lg-8">

                <input name="_method" type="hidden" value="POST">

                <input type="hidden" name="id" value="{{ $product->id }}" class="prodCustomid">

                <input type="hidden" name="lang" value="{{ $lang }}">

                @csrf

                <div class="card">

                    <!-- <ul class="nav nav-tabs nav-fill border-light">

                        @foreach (\App\Language::all() as $key => $language)

                        <li class="nav-item">

                            <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3" href="{{ route('products.admin.edit', ['id'=>$product->id, 'lang'=> $language->code] ) }}">

                                <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">

                                <span>{{$language->name}}</span>

                            </a>

                        </li>

                        @endforeach

                    </ul> -->

                    <div class="card-body">

                        <div class="form-group row" id="product_type_id">

                            <label class="col-md-3 col-from-label">{{translate('Product Type')}}</label>

                            <div class="col-md-8">

                                <select class="form-control aiz-selectpicker" name="product_type_id" id="product_typecustom" data-live-search="true" required>

                                    <option value="">{{ translate('Select Product Type') }}</option>

                                    @foreach (\App\Models\Producttype::all() as $product_type)

                                    <option value="{{ $product_type->id }}" @if($product->product_type_id == $product_type->id) selected @endif>{{ $product_type->product_type_name }}</option>

                                    @endforeach

                                </select>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-md-3 col-from-label">{{translate('Current Stock ID')}} <span class="text-danger">*</span></label>

                            <div class="col-md-8">

                              <input type="hidden" name="seqId" id="seqId" value="">

                                <input type="text" id="stock_id_custom" class="form-control" name="stock_id" value="{{ $product->stock_id }}" placeholder="{{ translate('Current Stock ID') }}"  readonly>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-lg-3 col-from-label">{{translate('Product Name')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>

                            <div class="col-lg-8">

                                <input type="text" class="form-control" name="name" placeholder="{{translate('Product Name')}}" value="{{ $product->name }}" required>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-md-3 col-from-label">{{translate('Condition')}}</label>

                            <div class="col-md-8 d-flex">

                                <select class="form-control aiz-selectpicker" name="productcondition_id" id="productcondition" data-live-search="true" >

                                    <option value="">{{ translate('Select Condition') }}</option>

                                    @foreach (\App\Models\Productcondition::orderBy('id','ASC')->get() as $productcondition)

                                    <option value="{{ $productcondition->id }}" @if($product->productcondition_id == $productcondition->id) selected @endif>{{ $productcondition->name }}</option>

                                    @endforeach

                                </select>

                                <button data-typereq="model" type="button" class="btn btn-primary conditionsubmit" data-toggle="modal" data-target="#add_ajax_product_condition">

                                  <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-lg-3 col-from-label">{{translate('Brand')}}</label>

                            <div class="col-lg-8 d-flex">

                                <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id" data-live-search="true">

                                    <option value="">{{ translate('Select Brand') }}</option>

                                    @foreach (\App\Brand::all() as $brand)

                                    <option value="{{ $brand->id }}" @if($product->brand_id == $brand->id) selected @endif>{{ $brand->getTranslation('name') }}</option>

                                    @endforeach

                                </select>

                                <button data-typereq="model" type="button" class="btn btn-primary brandsubmit" data-toggle="modal" data-target="#add_ajax_product_brand">

                                  <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-md-3 col-from-label">{{translate('Model Number')}}</label>

                            <div class="col-md-8 d-flex">

                                <select class="form-control aiz-selectpicker" name="model" id="model" data-live-search="true">

                                    <option value="">{{ translate('Select Model Number') }}</option>

                                    @foreach (\App\SiteOptions::where('option_name', '=', 'model')->get() as $model)

                                    <option value="{{ $model->option_value }}" @if($product->model == $model->option_value) selected @endif>{{ $model->option_value }}</option>

                                    @endforeach

                                </select>

                                <button data-typereq="model" type="button" class="btn btn-primary optionsubmit" data-toggle="modal" data-target="#add_ajax_product_unit">

                                  <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-md-3 col-from-label">

                                {{translate('Serial')}}

                            </label>

                            <div class="col-md-8">

                                <input type="text" placeholder="{{ translate('SKU') }}" value="{{ optional($product->stocks->first())->sku }}" name="sku" class="form-control">

                            </div>

                        </div>

                        <div class="form-group row" id="paper_cart">

                            <label class="col-md-3 col-from-label">{{translate('Paper/Cert')}}</label>

                            <div class="col-md-8">

                                <select class="form-control aiz-selectpicker" name="paper_cart" id="paper_cart" data-live-search="true">

                                    <option value="yes" @if($product->paper_cart == 'yes') selected @endif>{{ translate('yes') }}</option>

                                    <option value="No" @if($product->paper_cart == 'No') selected @endif>{{ translate('No') }}</option>

                                    <option value="others" @if($product->paper_cart == 'others') selected @endif>{{ translate('others') }}</option>

                                </select>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-lg-3 col-from-label">{{translate('Category')}}</label>

                            <div class="col-lg-8 d-flex">

                                <select class="form-control aiz-selectpicker" name="category_id" id="category_id" data-selected="{{ $product->category_id }}" data-live-search="true" required>

                                    @foreach ($categories as $category)

                                    <option value="{{ $category->id }}">{{ $category->getTranslation('name') }}</option>

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

                            <label class="col-md-3 col-from-label">{{translate('Size')}}</label>

                            <div class="col-md-8 d-flex">

                                <select class="form-control aiz-selectpicker" name="size" id="size" data-live-search="true">

                                    <option value="">{{ translate('Select Size') }}</option>

                                    @foreach (\App\SiteOptions::where('option_name', '=', 'size')->get() as $size)

                                    <option value="{{ $size->option_value }}" @if($product->size == $size->option_value) selected @endif>{{ $size->option_value }}</option>

                                    @endforeach

                                </select>

                                <button data-typereq="size" type="button" class="btn btn-primary optionsubmit" data-toggle="modal" data-target="#add_ajax_product_unit">

                                  <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-md-3 col-from-label">{{translate('Metal')}}</label>

                            <div class="col-md-8 d-flex">

                                <select class="form-control aiz-selectpicker" name="metal" id="metal" data-live-search="true">

                                    <option value="">{{ translate('Select Metal') }}</option>

                                    @foreach (\App\SiteOptions::where('option_name', '=', 'metal')->get() as $metal)

                                    <option value="{{ $metal->option_value }}" @if($product->metal == $metal->option_value) selected @endif>{{ $metal->option_value }}</option>

                                    @endforeach

                                </select>

                                <button data-typereq="metal" type="button" class="btn btn-primary optionsubmit" data-toggle="modal" data-target="#add_ajax_product_unit">

                                  <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-md-3 col-from-label">{{translate('Weight')}}</label>

                            <div class="col-md-8">

                                <input type="text" class="form-control" name="weight" placeholder="{{ translate('Weight') }}" value="{{$product->weight}}" >

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-md-3 col-from-label">{{translate('Partner')}}</label>

                            <div class="col-md-8 d-flex">

                                <select class="form-control aiz-selectpicker" name="partner" id="partner" data-live-search="true" required>

                                    <option value="">{{ translate('Select Partner') }}</option>

                                    @foreach (\App\SiteOptions::where('option_name', '=', 'partners')->get() as $partner)

                                    <option value="{{ $partner->option_value }}" @if($product->partner == $partner->option_value) selected @endif>{{ $partner->option_value }}</option>

                                    @endforeach

                                </select>

                                <button data-typereq="partners" type="button" class="btn btn-primary optionsubmit" data-toggle="modal" data-target="#add_ajax_product_unit">

                                  <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-lg-3 col-from-label">{{translate('Warehouse')}}</label>

                            <div class="col-lg-8 d-flex">

                                <select class="form-control aiz-selectpicker" name="warehouse_id" id="warehouse_id" data-live-search="true">

                                    <option value="">{{ translate('Select Warehouse') }}</option>

                                    @foreach (\App\Models\Warehouse::all() as $warehouse)

                                    <option value="{{ $warehouse->id }}" @if($product->warehouse_id == $warehouse->id) selected @endif>{{ $warehouse->name }}</option>

                                    @endforeach

                                </select>

                                <button data-typereq="model" type="button" class="btn btn-primary warehousesubmit" data-toggle="modal" data-target="#add_ajax_product_warehouse">

                                  <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-md-3 col-from-label">{{translate('unit')}}</label>

                            <div class="col-md-8 d-flex">

                                <select class="form-control aiz-selectpicker" name="unit" id="unit" data-live-search="true" required>

                                    <option value="">{{ translate('Select Unit') }}</option>

                                    @foreach (\App\SiteOptions::where('option_name', '=', 'unit')->get() as $unit)

                                    <option value="{{ $unit->option_value }}" @if($product->unit == $unit->option_value) selected @endif>{{ $unit->option_value }}</option>

                                    @endforeach

                                </select>

                                <button data-typereq="unit" type="button" class="btn btn-primary optionsubmit" data-toggle="modal" data-target="#add_ajax_product_unit">

                                  <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                </button>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-md-3 col-from-label">{{translate('Vendor Doc Number')}}</label>

                            <div class="col-md-8">

                                <input type="text" class="form-control" name="vendor_doc_number" value="{{$product->vendor_doc_number}}" placeholder="{{ translate('Vendor Doc Number') }}" >

                            </div>

                        </div>


                        @if(Auth::user()->user_type == 'admin' || in_array('25', json_decode(Auth::user()->staff->role->inner_permissions)))
                            <div class="form-group row">

                                <label class="col-md-3 col-from-label">{{translate('Supplier')}}</label>

                                <div class="col-md-8 d-flex">

                                    <select class="form-control aiz-selectpicker" name="supplier_id" id="supplier_id" data-live-search="true" >

                                        <option value="">{{ translate('Select Supplier') }}</option>

                                        @foreach (\App\User::select('sellers.company', 'users.id')
                                            ->where('user_type', 'seller')->join('sellers','sellers.user_id','users.id')->get() as $seller)

                                        <option value="{{ $seller->id }}" @if($product->supplier_id==$seller->id) selected @endif>{{ $seller->company }}</option>

                                        @endforeach

                                    </select>

                                    <button type="button" class="btn btn-primary sellersubmit" data-toggle="modal" data-target="#add_ajax_product_seller">

                                    <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                    </button>

                                </div>

                            </div>
                        @endif

                        <div class="form-group row">

                            <label class="col-md-3 col-from-label">{{translate('DOP')}}</label>

                            <div class="col-md-8">

                                <!-- <input type="date" class="form-control" name="dop" placeholder="{{translate('Select Date')}}" value="2022-03-31" > -->

                                <input type="text" class="form-control" name="dop" placeholder="{{translate('Select Date')}}" value="{{date('m/d/Y', strtotime($product->dop))}}" >

                            </div>

                            </div>

                            <div class="form-group row">

                              @php

                              if($product->tags != ""){

                                $proTagName = unserialize($product->tags);

                              }else{

                                $proTagName = array();

                              }

                              $oldTags = old('tags') ? old('tags') : $proTagName;

                              @endphp

                                <label class="col-md-3 col-from-label">{{translate('Tags')}} <span class="text-danger">*</span></label>

                                <div class="col-md-8 d-flex">

                                    <select class="js-example-basic-multiple form-control" name="tags[]" multiple="multiple">

                                      @foreach ($tagAllData as $tagName)

                                      <option value="{{ $tagName }}" @if(in_array($tagName,$oldTags)) selected @endif>{{ $tagName }}</option>

                                      @endforeach

                                    </select>

                                    <button type="button" class="btn btn-primary tagsubmit" data-toggle="modal" data-target="#add_ajax_product_tag" style="line-height: 0.5;">

                                      <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                    </button>

                                <!-- <small class="text-muted">{{translate('This is used for search. Input those words by which cutomer can find this product.')}}</small> -->

                            </div>

                        </div>

                        <!-- <div class="form-group row">

                            <label class="col-md-3 col-from-label">{{translate('Supplier')}}</label>

                            <div class="col-md-8 d-flex">

                                <select class="form-control aiz-selectpicker" name="supplier_id" id="supplier_id" data-live-search="true" >

                                    <option value="">{{ translate('Select Seller') }}</option>

                                    @foreach (\App\User::where('user_type', 'seller')->get() as $seller)

                                    <option value="{{ $seller->id }}" @if($product->supplier_id == $seller->id) selected @endif>{{ $seller->name }}</option>

                                    @endforeach

                                </select>

                                <button type="button" class="btn btn-primary sellersubmit" data-toggle="modal" data-target="#add_ajax_product_seller">

                                  <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                </button>

                            </div>

                        </div> -->

                        <!-- <div class="form-group row">

                            <label class="col-md-3 col-from-label">{{translate('DOP')}}</label>

                            <div class="col-md-8">

                                <input type="text" class="form-control" name="dop" placeholder="{{translate('Select Date')}}" >

                            </div> -->

                        <!-- </div> -->

                        <!-- <div class="form-group row">

                            <label class="col-md-3 col-from-label">{{translate('Condition')}}</label>

                            <div class="col-md-8 d-flex">

                                <select class="form-control aiz-selectpicker" name="productcondition_id" id="productcondition" data-live-search="true" >

                                    <option value="">{{ translate('Select Condition') }}</option>

                                    @foreach (\App\Models\Productcondition::orderBy('id','ASC')->get() as $productcondition)

                                    <option value="{{ $productcondition->id }}" @if($product->productcondition_id == $productcondition->id) selected @endif>{{ $productcondition->name }}</option>

                                    @endforeach

                                </select>

                                <button data-typereq="model" type="button" class="btn btn-primary conditionsubmit" data-toggle="modal" data-target="#add_ajax_product_condition">

                                  <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                </button>

                            </div>

                        </div> -->

                        <!-- <div class="form-group row" id="paper_cart">

                            <label class="col-md-3 col-from-label">{{translate('Paper/Cert')}}</label>

                            <div class="col-md-8">

                                <select class="form-control aiz-selectpicker" name="paper_cart" id="paper_cart" data-live-search="true">

                                  <option value="yes" @if($product->paper_cart == 'yes') selected @endif>{{ translate('yes') }}</option>

                                  <option value="No" @if($product->paper_cart == 'No') selected @endif>{{ translate('No') }}</option>

                                    <option value="others" @if($product->paper_cart == 'others') selected @endif>{{ translate('others') }}</option>

                                </select>

                            </div>

                        </div> -->

                        <!-- <div class="form-group row">

                            <label class="col-lg-3 col-from-label">{{translate('Minimum Purchase Qty')}}</label>

                            <div class="col-lg-8">

                                <input type="number" lang="en" class="form-control" name="min_qty" value="@if($product->min_qty <= 1){{1}}@else{{$product->min_qty}}@endif" min="1" >

                            </div>

                        </div> -->

                        <!-- <div class="form-group row">

                            <label class="col-lg-3 col-from-label">{{translate('Tags')}}</label>

                            <div class="col-lg-8">

                                <div class="form-group row">

                                    <label class="col-md-12 col-from-label">{{translate('Tags')}} <span class="text-danger">*</span></label>

                                    <div class="col-md-12">

                                        <select class="js-example-basic-multiple form-control" name="tags[]" multiple="multiple">

                                          @foreach (App\Brand::all() as $brand)

                                              <option value="{{ $brand->name }}" @if(in_array($brand->name,$tags)) selected @endif>{{ $brand->getTranslation('name') }}</option>

                                          @endforeach

                                          @foreach (App\Category::all() as $Category)

                                              <option value="{{ $Category->name }}"@if(in_array($Category->name,$tags)) selected @endif>{{ $Category->getTranslation('name') }}</option>

                                          @endforeach

                                      </select>

                                        <small class="text-muted">{{translate('This is used for search. Input those words by which cutomer can find this product.')}}</small>

                                    </div>

                                </div>

                            </div>

                        </div> -->

                        @php

                        $pos_addon = \App\Addon::where('unique_identifier', 'pos_system')->first();

                        @endphp

                        @if ($pos_addon != null && $pos_addon->activated == 1)

                        <div class="form-group row">

                            <label class="col-lg-3 col-from-label">{{translate('Barcode')}}</label>

                            <div class="col-lg-8">

                                <input type="text" class="form-control" name="barcode" placeholder="{{ translate('Barcode') }}" value="{{ $product->barcode }}">

                            </div>

                        </div>

                        @endif



                        @php

                        $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();

                        @endphp

                        @if ($refund_request_addon != null && $refund_request_addon->activated == 1)

                        <div class="form-group row">

                            <label class="col-lg-3 col-from-label">{{translate('Refundable')}}</label>

                            <div class="col-lg-8">

                                <label class="aiz-switch aiz-switch-success mb-0" style="margin-top:5px;">

                                    <input type="checkbox" name="refundable" @if ($product->refundable == 1) checked @endif>

                                    <span class="slider round"></span></label>

                                </label>

                            </div>

                        </div>

                        @endif

                    </div>

                </div>



                <!-- <div class="form-group row">

                    <label class="col-md-3 col-from-label">

                        {{translate('Whatsapp link')}}

                    </label>

                    <div class="col-md-9">

                        <input type="text" placeholder="{{ translate('Whatsapp link') }}" name="external_link" value="{{ $product->external_link }}" class="form-control">

                        <small class="text-muted">{{translate('Leave it blank if you do not use external site link')}}</small>

                    </div>

                </div> -->

                <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0 h6">{{translate('Product Videos')}}</h5>

                    </div>

                    <div class="card-body">

                        <div class="form-group row">

                            <label class="col-lg-3 col-from-label">{{translate('Video Provider')}}</label>

                            <div class="col-lg-8">

                                <select class="form-control aiz-selectpicker" name="video_provider" id="video_provider">

                                    <option value="google_drives" <?php if ($product->video_provider == 'google_drives') echo "selected"; ?> >{{translate('Google Drive')}}</option>

                                    <!--<option value="youtube" <?php if ($product->video_provider == 'youtube') echo "selected"; ?> >{{translate('Youtube')}}</option>-->

                                    <!-- <option value="dailymotion" <?php if ($product->video_provider == 'dailymotion') echo "selected"; ?> >{{translate('Dailymotion')}}</option>

                                    <option value="vimeo" <?php if ($product->video_provider == 'vimeo') echo "selected"; ?> >{{translate('Vimeo')}}</option> -->

                                </select>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-lg-3 col-from-label">{{translate('Video Link')}}</label>

                            <div class="col-lg-8">

                                <input type="text" class="form-control" name="video_link" value="{{ $product->video_link }}" placeholder="{{ translate('Video Link') }}">

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

                            <div class="col-lg-3">

                                <input type="text" class="form-control" value="{{translate('Colors')}}" disabled>

                            </div>

                            <div class="col-lg-8">

                                <select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" name="colors[]" id="colors" multiple>

                                    @foreach (\App\Color::orderBy('name', 'asc')->get() as $key => $color)

                                    <option

                                        value="{{ $color->code }}"

                                        data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:{{ $color->code }}'></span><span>{{ $color->name }}</span></span>"

                                        <?php

                                         // if (in_array($color->code, json_decode($product->colors))) echo 'selected'

                                         ?>

                                        ></option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-lg-1">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input value="1" type="checkbox" name="colors_active" <?php

                                    // if (count(json_decode($product->colors)) > 0) echo "checked";

                                     ?> >

                                    <span></span>

                                </label>

                            </div>

                        </div>



                        <div class="form-group row gutters-5">

                            <div class="col-lg-3">

                                <input type="text" class="form-control" value="{{translate('Attributes')}}" disabled>

                            </div>

                            <div class="col-lg-8">

                                <select name="choice_attributes[]" id="choice_attributes" data-selected-text-format="count" data-live-search="true" class="form-control aiz-selectpicker" multiple data-placeholder="{{ translate('Choose Attributes') }}">

                                    @foreach (\App\Attribute::all() as $key => $attribute)

                                    <option value="{{ $attribute->id }}" @if($product->attributes != null && in_array($attribute->id, json_decode($product->attributes, true))) selected @endif>{{ $attribute->getTranslation('name') }}</option>

                                    @endforeach

                                </select>

                            </div>

                        </div>



                        <div class="">

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

                    <div class="card-body">
                    @if(Auth::user()->user_type == 'admin' || in_array('16', json_decode(Auth::user()->staff->role->inner_permissions)))
                      <div class="form-group row">

                          <label class="col-md-3 col-from-label">{{translate('Product Cost')}} <span class="text-danger">*</span></label>

                          <div class="col-md-6">

                              <input type="number" lang="en" min="0"  step="0.01" value="{{$product->product_cost}}" placeholder="{{ translate('Product cost') }}" id="product_cost" name="product_cost" class="form-control" required>

                          </div>

                      </div>
                        @endif
                        <input type="hidden" id="cost_code_multiplier_custom" value="{{$sma_productseqData->cost_code_multiplier}}">

                          <div class="form-group row">

                              <label class="col-lg-3 col-from-label">{{translate('Cost Code/Tag Price')}} <span class="text-danger">*</span></label>

                              <div class="col-lg-6">

                                  <input type="text" class="form-control" name="cost_code" id="cost_code" value="{{$product->cost_code}}" placeholder="{{ translate('Cost Code') }}"  readonly>

                              </div>

                          </div>

                        <div class="form-group row">

                            <label class="col-lg-3 col-from-label">{{translate('Sale Price')}}</label>

                            <div class="col-lg-6">

                                <input type="text" placeholder="{{translate('Product cost')}}" name="unit_price" class="form-control" value="{{$product->unit_price}}" required>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-lg-3 col-from-label">

                                {{translate('MSRP')}}

                            </label>

                            <div class="col-lg-6">

                                <input type="number" lang="en" min="0" value="{{$product->msrp}}" step="1" placeholder="{{ translate('1') }}" name="msrp" class="form-control" required>

                            </div>

                        </div>

                        <div class="form-group row" id="quantity">

                                <label class="col-lg-3 col-from-label">{{translate('Quantity')}}</label>

                                <div class="col-lg-6">

                                    <input type="number" lang="en" value="{{ optional($product->stocks->first())->qty }}" step="1" placeholder="{{translate('Quantity')}}" name="current_stock" class="form-control" >

                                </div>



                        <!-- @php

                          $start_date = date('d-m-Y H:i:s', $product->discount_start_date);

                          $end_date = date('d-m-Y H:i:s', $product->discount_end_date);

                        @endphp



                        <div class="form-group row">

                            <label class="col-sm-3 col-from-label" for="start_date">{{translate('Discount Date Range')}}</label>

                            <div class="col-sm-9">

                              <input type="text" class="form-control aiz-date-range" value="{{ $start_date.' to '.$end_date }}" name="date_range" placeholder="{{translate('Select Date')}}" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">

                            </div>

                        </div> -->



                        <!-- <div class="form-group row">

                            <label class="col-lg-3 col-from-label">{{translate('Discount')}}</label>

                            <div class="col-lg-6">

                                <input type="number" lang="en" min="0" step="0.01" placeholder="{{translate('Discount')}}" name="discount" class="form-control" value="{{ $product->discount }}" required>

                            </div>

                            <div class="col-lg-3">

                                <select class="form-control aiz-selectpicker" name="discount_type" required>

                                    <option value="amount" <?php if ($product->discount_type == 'amount') echo "selected"; ?> >{{translate('Flat')}}</option>

                                    <option value="percent" <?php if ($product->discount_type == 'percent') echo "selected"; ?> >{{translate('Percent')}}</option>

                                </select>

                            </div>

                        </div> -->



                        @if(\App\Addon::where('unique_identifier', 'club_point')->first() != null &&

                            \App\Addon::where('unique_identifier', 'club_point')->first()->activated)

                            <div class="form-group row">

                                <label class="col-md-3 col-from-label">

                                    {{translate('Set Point')}}

                                </label>

                                <div class="col-md-6">

                                    <input type="number" lang="en" min="0" value="{{ $product->earn_point }}" step="1" placeholder="{{ translate('1') }}" name="earn_point" class="form-control">

                                </div>

                            </div>

                        @endif



                        <div id="show-hide-div">

                            <!-- <div class="form-group row" id="quantity">

                                <label class="col-lg-3 col-from-label">{{translate('Quantity')}}</label>

                                <div class="col-lg-6">

                                    <input type="number" lang="en" value="{{ optional($product->stocks->first())->qty }}" step="1" placeholder="{{translate('Quantity')}}" name="current_stock" class="form-control" >

                                </div>

                            </div> -->

                            <!-- <div class="form-group row">

                                <label class="col-md-3 col-from-label">

                                    {{translate('SKU')}}

                                </label>

                                <div class="col-md-6">

                                    <input type="text" placeholder="{{ translate('SKU') }}" value="{{ optional($product->stocks->first())->sku }}" name="sku" class="form-control">

                                </div>

                            </div> -->

                        </div>

                        <!-- <div class="form-group row">

                            <label class="col-md-3 col-from-label">

                                {{translate('External link')}}

                            </label>

                            <div class="col-md-9">

                                <input type="text" placeholder="{{ translate('External link') }}" name="external_link" value="{{ $product->external_link }}" class="form-control">

                                <small class="text-muted">{{translate('Leave it blank if you do not use external site link')}}</small>

                            </div>

                        </div> -->

                        <br>

                        <div class="sku_combination" id="sku_combination">



                        </div>

                    </div>

                </div>

                <!-- <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0 h6">{{translate('Product Description')}}</h5>

                    </div>

                    <div class="card-body">

                        <div class="form-group row">

                            <label class="col-lg-3 col-from-label">{{translate('Description')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>

                            <div class="col-lg-9">

                                <textarea class="aiz-text-editor" name="description">{{ $product->getTranslation('description', $lang) }}</textarea>

                            </div>

                        </div>

                    </div>

                </div> -->

            </div>



                        <div class="card">

                            <div class="card-header">

                                <h5 class="mb-0 h6">{{translate('Product Images')}}</h5>

                            </div>

                            <div class="card-body">



                                <div class="form-group row">

                                    <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Gallery Images')}}</label>

                                    <div class="col-md-8">

                                        <div class="input-group mi_custom_uploader" data-toggle="aizuploader" data-type="image" data-multiple="true">

                                            <div class="input-group-prepend">

                                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>

                                            </div>

                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>

                                            <input type="hidden" name="photos" value="{{ $product->photos }}" class="selected-files">

                                        </div>

                                        <div class="file-preview box sm">

                                        </div>

                                    </div>

                                    <button data-typereq="model" type="button" class="btn btn-primary webcamsubmit" data-toggle="modal">

                                      <i class="las la-plus text-white" title="{{translate('Translatable')}}"></i>

                                    </button>

                                </div>

                                <!-- <div class="form-group row">

                                    <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Thumbnail Image')}} <small>(290x300)</small></label>

                                    <div class="col-md-8">

                                        <div class="input-group mi_custom_uploader" data-toggle="aizuploader" data-type="image">

                                            <div class="input-group-prepend">

                                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>

                                            </div>

                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>

                                            <input type="hidden" name="thumbnail_img" value="{{ $product->thumbnail_img }}" class="selected-files">

                                        </div>

                                        <div class="file-preview box sm">

                                        </div>

                                    </div>

                                </div> -->

                                 <div class="form-group row">

                                <!-- <label class="col-lg-3 col-from-label">{{translate('Gallery Images')}}</label> -->

                                <div class="col-lg-8">

                                    <div id="photos">

                                        @if(is_array(json_decode($product->photos)))

                                        @foreach (json_decode($product->photos) as $key => $photo)

                                        <div class="col-md-4 col-sm-4 col-xs-6">

                                            <div class="img-upload-preview">

                                                <img loading="lazy"  src="{{ uploaded_asset($photo) }}" alt="" class="img-responsive">

                                                    <input type="hidden" name="previous_photos[]" value="{{ $photo }}">

                                                    <button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>

                                            </div>

                                        </div>

                                        @endforeach

                                        @endif

                                    </div>

                                </div>

                            </div>



                            <!-- <div class="form-group row">

                                <label class="col-lg-3 col-from-label">

                                    {{translate('Whatsapp link')}}

                                </label>

                                <div class="col-lg-8">

                                    <input type="text" placeholder="{{ translate('Whatsapp link') }}" name="external_link" value="{{ $product->external_link }}" class="form-control">

                                    <small class="text-muted">{{translate('Leave it blank if you do not use external site link')}}</small>

                                </div>

                            </div> -->

                            <!-- <div class="form-group row">

                                <label class="col-md-3 col-from-label">

                                    {{translate('Google Drive link')}}

                                </label>

                                <div class="col-md-8">

                                    <input type="text" placeholder="{{ translate('Google Drive link') }}" value="{{ $product->google_link }}" name="google_link" class="form-control">

                                    <small class="text-muted">{{translate('Leave it blank if you do not use Google Drive')}}</small>

                                </div>

                            </div> -->

                                 <!-- <div class="form-group row">

                                    <label class="col-lg-3 col-from-label">{{translate('Thumbnail Image')}} <small>(290x300)</small></label>

                                    <div class="col-lg-8">

                                        <div id="thumbnail_img">

                                            @if ($product->thumbnail_img != null)

                                            <div class="col-md-4 col-sm-4 col-xs-6">

                                                <div class="img-upload-preview">

                                                    <img loading="lazy"  src="{{ uploaded_asset($product->thumbnail_img) }}" alt="" class="img-responsive">

                                                    <input type="hidden" name="previous_thumbnail_img" value="{{ $product->thumbnail_img }}">

                                                    <button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>

                                                </div>

                                            </div>

                                            @endif

                                        </div>

                                    </div>

                                </div> -->

                            </div>

                        </div>

























                        <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0 h6">{{translate('Product Description')}}</h5>

                    </div>

                    <div class="card-body">

                        <div class="form-group row">

                            <label class="col-lg-3 col-from-label">{{translate('Description')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>

                            <div class="col-lg-9">

                                <textarea class="aiz-text-editor" name="description">{{ $product->getTranslation('description', $lang) }}</textarea>

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

                                <div class="input-group" data-toggle="aizuploader">

                                    <div class="input-group-prepend">

                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>

                                    </div>

                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>

                                    <input type="hidden" name="pdf" value="{{ $product->pdf }}" class="selected-files">

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

                    <div class="card-body">

                        <div class="form-group row">

                            <label class="col-lg-3 col-from-label">{{translate('Meta Title')}}</label>

                            <div class="col-lg-8">

                                <input type="text" class="form-control" name="meta_title" value="{{ $product->meta_title }}" placeholder="{{translate('Meta Title')}}">

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-lg-3 col-from-label">{{translate('Description')}}</label>

                            <div class="col-lg-8">

                                <textarea name="meta_description" rows="8" class="form-control">{{ $product->meta_description }}</textarea>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Meta Images')}}</label>

                            <div class="col-md-8">

                                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">

                                    <div class="input-group-prepend">

                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>

                                    </div>

                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>

                                    <input type="hidden" name="meta_img" value="{{ $product->meta_img }}" class="selected-files">

                                </div>

                                <div class="file-preview box sm">

                                </div>

                            </div>

                        </div>

                        <div class="form-group row">

                            <label class="col-md-3 col-form-label">{{translate('Slug')}}</label>

                            <div class="col-md-8">

                                <input type="text" placeholder="{{translate('Slug')}}" id="slug" name="slug" value="{{ $product->slug }}" class="form-control">

                            </div>

                        </div>

                    </div>

                </div> -->

            </div>



            <div class="col-lg-4">



              <div class="card">

                  <div class="card-header">

                      <h5 class="mb-0 h6">{{translate('PRODUCT TYPE')}}</h5>

                  </div>

                  <div class="card-body producttypecustom">

                    <div class="form-group ">

                      @for($i=1;$i<=10;$i++)

                      @php

                      $countCustom = "custom_".$i;

                      $countValue = 'custom_'.$i.'_value';

                      $countValueType = 'custom_'.$i.'_type';

                      $static="";

                      $dynamic="";

                      $customClass = "";

                      $Customselect = $product->$countCustom;

                      if($product->$countValueType == '2')

                      {

                        $customClass = "showtypebox";

                        $dynamic = 'checked';

                      }else{

                        $static = 'checked';

                      }

                      @endphp

                      @if($sma_product_type->$countCustom != "")

                      @if($sma_product_type->$countValueType == 2)

                            <div class="col-md-12">

                               <div class="form-group">

                                <label >{{ $sma_product_type->$countCustom }}</label>

                                <select class="form-control aiz-selectpicker" name="custom_{{$i}}" data-live-search="true">

                                    @php

                                   $CustFValCustFVal = isset($sma_product_type->$countValue) ? $sma_product_type->$countValue : '';

                                    if($CustFValCustFVal != ""){

                                    $CustFVales = explode(",",$CustFValCustFVal);

                                    }

                                    @endphp

                                  @foreach($CustFVales as  $CustFValCustFValNew)



                                  <option value="{{$CustFValCustFValNew}}"  {{ (trim($Customselect) == trim($CustFValCustFValNew) ? "selected":"") }} >{{$CustFValCustFValNew}}</option>





                                  @endforeach

                                </select>

                               </div>

                            </div>

                      @else

                            <div class="col-md-12">

                               <div class="form-group">

                                <label >{{ $sma_product_type->$countCustom }}</label>

                                <input type="text" name="custom_{{$i}}" class="form-control input-tip" value="{{ $product->$countCustom }}" />

                               </div>

                            </div>

                      @endif

                      @endif

                      @endfor

                    </div>

                  </div>

              </div>



                <!-- <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0 h6" class="dropdown-toggle" data-toggle="collapse" data-target="#collapse_2">

                            {{translate('Shipping Configuration')}}

                        </h5>

                    </div>

                    <div class="card-body collapse show" id="collapse_2">

                        @if (get_setting('shipping_type') == 'product_wise_shipping')

                        <div class="form-group row">

                            <label class="col-lg-6 col-from-label">{{translate('Free Shipping')}}</label>

                            <div class="col-lg-6">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="radio" name="shipping_type" value="free" @if($product->shipping_type == 'free') checked @endif>

                                    <span></span>

                                </label>

                            </div>

                        </div>



                        <div class="form-group row">

                            <label class="col-lg-6 col-from-label">{{translate('Flat Rate')}}</label>

                            <div class="col-lg-6">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="radio" name="shipping_type" value="flat_rate" @if($product->shipping_type == 'flat_rate') checked @endif>

                                    <span></span>

                                </label>

                            </div>

                        </div>



                        <div class="flat_rate_shipping_div" style="display: none">

                            <div class="form-group row">

                                <label class="col-lg-6 col-from-label">{{translate('Shipping cost')}}</label>

                                <div class="col-lg-6">

                                    <input type="number" lang="en" min="0" value="{{ $product->shipping_cost }}" step="0.01" placeholder="{{ translate('Shipping cost') }}" name="flat_shipping_cost" class="form-control">

                                </div>

                            </div>

                        </div>



                        <div class="form-group row">

                            <label class="col-md-6 col-from-label">{{translate('Is Product Quantity Mulitiply')}}</label>

                            <div class="col-md-6">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="checkbox" name="is_quantity_multiplied" value="1" @if($product->is_quantity_multiplied == 1) checked @endif>

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

                            <input type="number" name="low_stock_quantity" value="{{ $product->low_stock_quantity }}" min="0" step="1" class="form-control">

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

                                    <input type="radio" name="stock_visibility_state" value="quantity" @if($product->stock_visibility_state == 'quantity') checked @endif>

                                    <span></span>

                                </label>

                            </div>

                        </div>



                        <div class="form-group row">

                            <label class="col-md-6 col-from-label">{{translate('Show Stock With Text Only')}}</label>

                            <div class="col-md-6">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="radio" name="stock_visibility_state" value="text" @if($product->stock_visibility_state == 'text') checked @endif>

                                    <span></span>

                                </label>

                            </div>

                        </div>



                        <div class="form-group row">

                            <label class="col-md-6 col-from-label">{{translate('Hide Stock')}}</label>

                            <div class="col-md-6">

                                <label class="aiz-switch aiz-switch-success mb-0">

                                    <input type="radio" name="stock_visibility_state" value="hide" @if($product->stock_visibility_state == 'hide') checked @endif>

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

                            <div class="col-md-12">

                                <div class="form-group row">

                                    <label class="col-md-6 col-from-label">{{translate('Status')}}</label>

                                    <div class="col-md-6">

                                        <label class="aiz-switch aiz-switch-success mb-0">

                                            <input type="checkbox" name="cash_on_delivery" value="1" @if($product->cash_on_delivery == 1) checked @endif>

                                            <span></span>

                                        </label>

                                    </div>

                                </div>

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

                            <div class="col-md-12">

                                <div class="form-group row">

                                    <label class="col-md-6 col-from-label">{{translate('Status')}}</label>

                                    <div class="col-md-6">

                                        <label class="aiz-switch aiz-switch-success mb-0">

                                            <input type="checkbox" name="featured" value="1" @if($product->featured == 1) checked @endif>

                                            <span></span>

                                        </label>

                                    </div>

                                </div>

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

                                    <input type="checkbox" name="published" value="0" @if($product->published == 0) checked @endif name="published" value="0">

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

                            <div class="col-md-12">

                                <div class="form-group row">

                                    <label class="col-md-6 col-from-label">{{translate('Status')}}</label>

                                    <div class="col-md-6">

                                        <label class="aiz-switch aiz-switch-success mb-0">

                                            <input type="checkbox" name="todays_deal" value="1" @if($product->todays_deal == 1) checked @endif>

                                            <span></span>

                                        </label>

                                    </div>

                                </div>

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

                            <select class="form-control aiz-selectpicker" name="flash_deal_id" id="video_provider">

                                <option value="">Choose Flash Title</option>

                                @foreach(\App\FlashDeal::where("status", 1)->get() as $flash_deal)

                                    <option value="{{ $flash_deal->id}}" @if($product->flash_deal_product && $product->flash_deal_product->flash_deal_id == $flash_deal->id) selected @endif>

                                        {{ $flash_deal->title }}

                                    </option>

                                @endforeach

                            </select>

                        </div>



                        <div class="form-group mb-3">

                            <label for="name">

                                {{translate('Discount')}}

                            </label>

                            <input type="number" name="flash_discount" value="{{$product->flash_deal_product ? $product->flash_deal_product->discount : '0'}}" min="0" step="1" class="form-control">

                        </div>

                        <div class="form-group mb-3">

                            <label for="name">

                                {{translate('Discount Type')}}

                            </label>

                            <select class="form-control aiz-selectpicker" name="flash_discount_type" id="">

                                <option value="">Choose Discount Type</option>

                                <option value="amount" @if($product->flash_deal_product && $product->flash_deal_product->discount_type == 'amount') selected @endif>

                                    {{translate('Flat')}}

                                </option>

                                <option value="percent" @if($product->flash_deal_product && $product->flash_deal_product->discount_type == 'percent') selected @endif>

                                    {{translate('Percent')}}

                                </option>

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

                                <input type="number" class="form-control" name="est_shipping_days" value="{{ $product->est_shipping_days }}" min="1" step="1" placeholder="{{translate('Shipping Days')}}">

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



                        @php

                        $tax_amount = 0;

                        $tax_type = '';

                        foreach($tax->product_taxes as $row) {

                            if($product->id == $row->product_id) {

                                $tax_amount = $row->tax;

                                $tax_type = $row->tax_type;

                            }

                        }

                        @endphp



                        <div class="form-row">

                            <div class="form-group col-md-6">

                                <input type="number" lang="en" min="0" value="{{ $tax_amount }}" step="0.01" placeholder="{{ translate('Tax') }}" name="tax[]" class="form-control" required>

                            </div>

                            <div class="form-group col-md-6">

                                <select class="form-control aiz-selectpicker" name="tax_type[]">

                                    <option value="amount" @if($tax_type == 'amount') selected @endif>

                                        {{translate('Flat')}}

                                    </option>

                                    <option value="percent" @if($tax_type == 'percent') selected @endif>

                                        {{translate('Percent')}}

                                    </option>

                                </select>

                            </div>

                        </div>

                        @endforeach

                    </div>

                </div> -->



            </div>

            <div class="col-12">

                <div class="mb-3 text-right">

                    <button type="submit" name="button" class="btn btn-info">{{ translate('Update Product') }}</button>

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



         <input type="hidden" name="prostock" value="{{ $product->stock_id }}">

         <div class="form-group mb-3">

           <div id="my_camera"></div>

            <input type="button" value="Take Snapshot" onClick="take_snapshot()">



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

          <input type="hidden" class="product_option_name" name="option_name" value="unit">

         @csrf

          <div class="form-group">

            <label for="exampleInputEmail1" id="exampleInputEmail" class="text-capitalize"></label>

            <input type="text" id="option_value" name="option_value" class="form-control">

          </div>



          <div class="form-group mb-3">

            <label for="description">Description</label>

            <textarea name="description" rows="8" cols="80" class="form-control"></textarea>

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

         <div class="form-group mb-3">

           <label for="name">{{translate('Meta Title')}}</label>

           <input type="text" class="form-control" name="meta_title" placeholder="{{translate('Meta Title')}}">

         </div>

         <div class="form-group mb-3">

           <label for="name">{{translate('Meta Description')}}</label>

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

                 <input type="text" placeholder="{{ translate('Name')}}" name="name" class="form-control" required>

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

<!-- Warehouse Modal -->



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

                           <input type="text" placeholder="{{translate('Name')}}" name="name" class="form-control">

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

             <label class="col-sm-3 col-from-label" for="name">{{translate('Company Name')}}</label>

             <div class="col-sm-9">

                 <input type="text" placeholder="{{translate('company name')}}" id="company" name="company" class="form-control" >

             </div>

         </div>

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="name">{{translate('Contact Name')}}</label>

             <div class="col-sm-9">

                 <input type="text" placeholder="{{translate('Contact Name')}}" name="name" class="form-control" >

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

             <label class="col-sm-3 col-from-label" for="name">{{translate('Contact Number')}}</label>

             <div class="col-sm-9">

                 <input type="tel" placeholder="{{translate('Contact Number')}}" id="phone" name="phone" class="form-control" >

             </div>

         </div>

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="name">{{translate('Company Address*')}}</label>

             <div class="col-sm-9">

                 <input type="tel" placeholder="{{translate('Company Address*')}}" id="company_address" name="company_address" class="form-control" >

             </div>

         </div>

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="name">{{translate('Address*')}}</label>

             <div class="col-sm-9">

                 <input type="tel" placeholder="{{translate('Address*')}}" name="address" class="form-control" >

             </div>

         </div>

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="name">{{translate('City*')}}</label>

             <div class="col-sm-9">

                 <input type="tel" placeholder="{{translate('City*')}}" id="city" name="city" class="form-control" >

             </div>

         </div>

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="name">{{translate('State*')}}</label>

             <div class="col-sm-9" id="create_state_id_seller">

                 <!-- <input type="tel" placeholder="{{translate('State*')}}" id="state" name="state" class="form-control" > -->

             </div>

         </div>

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="name">{{translate('Postal Code*')}}</label>

             <div class="col-sm-9">

                 <input type="tel" placeholder="{{translate('Postal Code*')}}" id="postal_code" name="postal_code" class="form-control" >

             </div>

         </div>

         <div class="form-group row">

             <label class="col-sm-3 col-from-label" for="name">{{translate('Country*')}}</label>

             <!-- <div class="col-sm-9"> -->
                 <!-- <input type="tel" placeholder="{{translate('Country*')}}" id="country" name="country" class="form-control" > -->
             <!-- </div> -->
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

               <label class="col-sm-3 col-from-label" for="email">{{translate('Email Address')}}</label>

               <div class="col-sm-9">

                   <input type="email" placeholder="{{translate('Email Address')}}" id="email" name="email" class="form-control" >

               </div>

           </div>
           <!--<div class="form-group row">
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







@endsection



@section('script')



<script type="text/javascript">

    $(document).ready(function (){

        show_hide_shipping_div();

    });



    $("[name=shipping_type]").on("change", function (){

        show_hide_shipping_div();

    });



    function show_hide_shipping_div() {

        var shipping_val = $("[name=shipping_type]:checked").val();



        $(".flat_rate_shipping_div").hide();



        if(shipping_val == 'flat_rate'){

            $(".flat_rate_shipping_div").show();

        }

    }


    // 
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
    //
    //

    $('input[name="colors_active"]').on('change', function() {

        if(!$('input[name="colors_active"]').is(':checked')){

            $('#colors').prop('disabled', true);

            AIZ.plugins.bootstrapSelect('refresh');

        }

        else{

            $('#colors').prop('disabled', false);

            AIZ.plugins.bootstrapSelect('refresh');

        }

        update_sku();

    });



    $(document).on("change", ".attribute_choice",function() {

        update_sku();

    });



    $('#colors').on('change', function() {

        update_sku();

    });



    function delete_row(em){

        $(em).closest('.form-group').remove();

        update_sku();

    }



    function delete_variant(em){

        $(em).closest('.variant').remove();

    }



    function update_sku(){

        $.ajax({

           type:"POST",

           url:'{{ route('products.sku_combination_edit') }}',

           data:$('#choice_form').serialize(),

           success: function(data){

                $('#sku_combination').html(data);

                AIZ.uploader.previewGenerate();

                AIZ.plugins.fooTable();

                if (data.length > 1) {

                    $('#show-hide-div').hide();

                }

                else {

                    $('#show-hide-div').show();

                }

           }

        });

    }



    AIZ.plugins.tagify();



    $(document).ready(function(){

        update_sku();



        $('.remove-files').on('click', function(){

            $(this).parents(".col-md-4").remove();

        });

    });



    $('#choice_attributes').on('change', function() {

        $.each($("#choice_attributes option:selected"), function(j, attribute){

            flag = false;

            $('input[name="choice_no[]"]').each(function(i, choice_no) {

                if($(attribute).val() == $(choice_no).val()){

                    flag = true;

                }

            });

            if(!flag){

                add_more_customer_choice_option($(attribute).val(), $(attribute).text());

            }

        });



        var str = @php echo $product->attributes @endphp;



        $.each(str, function(index, value){

            flag = false;

            $.each($("#choice_attributes option:selected"), function(j, attribute){

                if(value == $(attribute).val()){

                    flag = true;

                }

            });

            if(!flag){

                $('input[name="choice_no[]"][value="'+value+'"]').parent().parent().remove();

            }

        });



        update_sku();

    });



</script>

<script>

  $(function() {

    // $('input[name="dop"]').daterangepicker({

    //   singleDatePicker: true,

    //   showDropdowns: true,

    //   locale: {

    //         format: 'MM-DD-YYYY'

    //     }

    // });

    $('input[name="dop"]').datepicker({

    format: 'mm/dd/yyyy',

    startDate: '-3d'

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

        $('#'+optName).html(response.optionHtml);

        $('#'+optName).selectpicker("refresh");

        $('#ajax-unit-form').trigger("reset");

        $('#add_ajax_product_unit').modal('hide');

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

        $('.publish').removeAttr('disabled',false);

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

        $('#brand_id').selectpicker("refresh");

        // $('#add_ajax_product_unit').model('hide');

        // alert('Ajax form has been submitted successfully');

        $('#ajax-brand-form').trigger("reset");

        $('#add_ajax_product_brand').modal('hide');

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

        $('#category_id').selectpicker("refresh");

        // $('#add_ajax_product_unit').model('hide');

        // alert('Ajax form has been submitted successfully');

        $('#ajax-category-form').trigger("reset");

        $('#add_ajax_product_category').modal('hide');

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

      data: $('#ajax-seller-form').serialize(),

      success: function( response ) {

        $('#supplier_id').html(response.selleroptionHTMl);

        $('#supplier_id').selectpicker("refresh");

        $('#ajax-seller-form').trigger("reset");

        $('#add_ajax_product_seller').modal('hide');

        Swal.fire({

        position: 'top-end',

        title: 'Seller Added Successfully',

        color: '#ffffff',

        background: '#63bf81',

        showConfirmButton: false,

        timer: 1500,

      })

      }

     });

   });



   // In your Javascript (external .js resource or <script> tag)

    $(document).ready(function() {

        $('.js-example-basic-multiple').select2();

        $('.js-example-basic-multiple').prop('selected', true)

    });



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

        $("#mi_webcam").append('<div class="mi_remove_web_img"><input type="hidden" class="camera_image_upload" name="mi_custom_cam_image[]" value="'+data_uri+'"><img class="mi_custom_cam_image"  src="'+data_uri+'"/> <button class="webcamRemove">x</button></div>');

    } );

}



$('#mi_webcam').on('click', '.webcamRemove', function(e) {

    e.preventDefault();

    $(this).closest('.mi_remove_web_img').remove();

});



</script>

<script type="text/javascript">

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

       console.log(responce.camImageData);

       var inputPreview = $("input[name=photos]").val();

       $('.file-preview').append(responce.camImageData);

       if(inputPreview != ""){

         $('[name="photos"]').val(inputPreview +','+responce.camImageDataID);

       }else{

         $('[name="photos"]').val(responce.camImageDataID);

       }

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



$('.webcamsubmit').click(function(){

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



</script>



@endsection
