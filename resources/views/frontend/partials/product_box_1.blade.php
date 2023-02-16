<div class="aiz-card-box border border-light rounded hov-shadow-md mt-1 mb-2 has-transition bg-white product_list">
    <div class="position-relative">
        <a href="{{ route('product', $product->slug) }}" class="d-block">
            <img
                class="img-fit lazyload mx-auto h-140px h-md-210px"
                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                alt="{{  $product->getTranslation('name')  }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
            >
        </a>


        <div class="absolute-top-right aiz-p-hov-icon">
            @php
             $listenType = \App\Models\Producttype::where('id','=',$product->product_type_id)->first();
             @endphp

            <a href="https://wa.me/12133734424?text=Hi Gciwatch, I would like to place inquiry to this 'https://gcijewel.com/{{$listenType->listing_type}}/{{ $product->model }}/{{ $product->stock_id }}'" >
                <i class="lab la-whatsapp"></i>
            </a>
            <!-- <a href="javascript:void(0)" onclick="addToWishList({{ $product->id }})" data-toggle="tooltip" data-title="{{ translate('Add to wishlist') }}" data-placement="left">
                <i class="la la-heart-o"></i>
            </a> -->
            <a href="javascript:void(0)" onclick="addToCompare({{ $product->id }})" data-toggle="tooltip" data-title="{{ translate('Add to compare') }}" data-placement="left">
                <i class="las la-sync"></i>
            </a>
            <a href="#" class="EnqModelId" data-toggle="modal" data-id="{{ $product->id }}" data-target="#imqModel" data-toggle="tooltip" data-title="{{ translate('Inquire') }}" data-placement="left">
                <i class="las la-info"></i>
            </a>
            <!-- <a href="javascript:void(0)" onclick="showAddToCartModal({{ $product->id }})" data-toggle="tooltip" data-title="{{ translate('Add to cart') }}" data-placement="left">
                <i class="las la-shopping-cart"></i>
            </a> -->
        </div>
    </div>

    <div class="p-md-3 p-2 text-left">
        <div class="product_sec">
          <?php
            if($product->brand_id != ""){
                    $brandData = App\Brand::findOrFail($product->brand_id);
                    $brand_name =  $brandData->name;
                  }else{
                    $brand_name =  "";
                  }
            if($product->category_id != ""){
                    $categoryData = App\Category::findOrFail($product->category_id);
                    $category_name =  $categoryData->name;
                  }else{
                    $category_name =  "";
                  }
                  // print_r($product->category_id);
          ?>

        <h6 class="fs-13 text-truncate-2 lh-1-4 mb-0 d-flex" style="height:30px;">
           <a href="{{ route('product', $product->slug) }}" class="d-block text-reset">{{  ($brand_name)  }}</a> <b class="mx-2">|</b>
           <a href="{{ route('product', $product->slug) }}" class="d-block text-reset">{{  ($category_name)  }}</a>
        </h6>
        <div class="fs-15">
            @if(home_base_price($product) != home_discounted_base_price($product))
                <del class="fw-600 opacity-50 mr-1">{{ home_base_price($product) }}</del>
            @endif
            <span class="fw-700 text-primary">{{ home_discounted_base_price($product) }}</span>
        </div>
        <!-- <div class="rating rating-sm mt-1">
            {{ renderStarRating($product->rating) }}
        </div> -->
        <!-- <h1 class="fs-18 text-truncate-2 lh-1-4 mb-0 mt-1">
            <a href="{{ route('product', $product->slug) }}" class="d-block text-reset">{{  $product->getTranslation('name')  }}</a>
        </h1> -->
        <!-- <h6 class="fs-13 text-truncate-2 lh-1-4 mb-0">
            <a href="{{ route('product', $product->slug) }}" class="d-block text-reset">Stock: {{  ($product->stock_id)  }}</a>
        </h6> -->
        <h6 class="fs-13 text-truncate-2 lh-1-4 mb-0" style="height:30px;">
            <a href="{{ route('product', $product->slug) }}" class="d-block text-reset">Model Number: {{  ($product->model)  }}</a>
        </h6>
        <!-- <h6 class="fs-13 text-truncate-2 lh-1-4 mb-0">
            <a href="{{ route('product', $product->slug) }}" class="d-block text-reset">Desc: {{  (substr(strip_tags($product->description),0,50))  }}</a>
        </h6> -->
        <!-- <h6 class="fs-13 text-truncate-2 lh-1-4 mb-0">
            <a href="{{ route('product', $product->slug) }}" class="d-block text-reset">Retail Price: ${{  ($product->product_cost)  }}</a>
        </h6> -->
        <?php
        if($product->product_type_id != ""){
        $proTypeData = App\Models\Producttype::findOrFail($product->product_type_id);
        $product_type_name =  $proTypeData->product_type_name;
        $listing_type =  $proTypeData->listing_type;
        }else{
          $product_type_name =  "";
          $listing_type =  "";
        }
        // echo $custom_1 =  $proTypeData->custom_1;
        // echo $custom_1_value =  $proTypeData->custom_1_value;
        // echo $custom_2 =  $proTypeData->custom_2;
        // echo $custom_2_value =  $proTypeData->custom_2_value;
        // echo $custom_3 =  $proTypeData->custom_3;
        // echo $custom_3_value =  $proTypeData->custom_3_value;
        // echo $custom_4 =  $proTypeData->custom_4;
        // echo $custom_4_value =  $proTypeData->custom_4_value;
        // if($product->brand_id != ""){
        //   $brandData = App\Brand::findOrFail($product->brand_id);
        //   $brand_name =  $brandData->name;
        // }else{
        //   $brand_name =  "";
        // }

        if($product->productcondition_id != ""){
        $conditionData = App\Productcondition::findOrFail($product->productcondition_id);
        $conditionData =  $conditionData->name;
      }else{
        $conditionData = "";
      }
        ?>
        <!-- <h6 class="fs-13 text-truncate-2 lh-1-4 mb-0">
            <a href="{{ route('product', $product->slug) }}" class="d-block text-reset">Product Type: {{  ($product_type_name)  }}</a>
        </h6> -->
        <!-- <h6 class="fs-13 text-truncate-2 lh-1-4 mb-0">
            <a href="{{ route('product', $product->slug) }}" class="d-block text-reset">Listing: {{  ($listing_type)  }}</a>
        </h6> -->
        <!-- <h6 class="fs-13 text-truncate-2 lh-1-4 mb-0">
            <a href="{{ route('product', $product->slug) }}" class="d-block text-reset">Brand: {{  ($brand_name)  }}</a>
        </h6> -->
        <h6 class="fs-13 text-truncate-2 lh-1-4 mb-0">
            <a href="{{ route('product', $product->slug) }}" class="d-block text-reset">Condition: {{  ($conditionData)  }}</a>
        </h6>
        <h6 class="fs-13 text-truncate-2 lh-1-4 mb-0">
            <a href="{{ route('product', $product->slug) }}" class="d-block text-reset">Paper: {{  ($product->paper_cart)  }}</a>
        </h6>

        @if (addon_is_activated('club_point'))
            <!-- <div class="rounded px-2 mt-2 bg-soft-primary border-soft-primary border">
                {{ translate('Club Point') }}:
                <span class="fw-700 float-right">{{ $product->earn_point }}</span>
            </div> -->
        @endif
        </div>
    </div>
</div>

<style>
    .product_sec h6 {
	font-size: 14px !important;
	padding: 3px 0px;
}
.product_sec h1 a {
	font-size: 16px;
	font-weight: bold;
	padding-bottom: 3px;
}
</style>
<script>
function doWhatsapp(id) {

var url_s = "https://api.whatsapp.com/send?phone=1213-373-4424&text=Hi Gciwatch, I would like to place inquiry for this Product -https://gcijewel.com/product/"+id;
window.location.href=url_s;
}

$(document).on('click','.EnqModelId', function(){
  var id = $(this).data('id');
  // alert(id);
  $('.enqProId').val(id);
});
</script>
