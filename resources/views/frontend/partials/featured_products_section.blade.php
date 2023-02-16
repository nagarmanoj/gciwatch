@php
    $featured_products = Cache::remember('featured_products', 3600, function () {
        return filter_products(\App\Product::where('published', 1)->groupBy('model')->where('featured', '1'))->limit(12)->get();
    });
@endphp

@if (count($featured_products) > 0)
    <section class="mb-4">
        <div class="container">
            <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
                <div class="d-flex mb-3 align-items-baseline border-bottom">
                    <h3 class="h5 fw-700 mb-0">
                        <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block">{{ translate('Featured Products') }}</span>
                    </h3>
                </div>
                <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="6" data-xl-items="5" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true'>
                    @foreach ($featured_products as $key => $product)
                    <div class="carousel-box">
                        @include('frontend.partials.product_box_1',['product' => $product])
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
      <div class="modal fade" id="imqModel" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title fw-600 h5">{{ translate('Any query about this product')}}</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body">
              <form class="" action="{{ route('conversations.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" class="enqProId" name="product_id" value="">
                        <div class="modal-body gry-bg px-3 pt-3">
                            <div class="form-group">
                                <label>Company Name *</label>
                                <input type="text" class="form-control mb-3" name="title" value="" placeholder="{{ translate('Product Name') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Reference *</label>
                                <input type="text" class="form-control mb-3" name="title" value="" placeholder="{{ translate('Product Name') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Contact Name *</label>
                                <input type="text" class="form-control mb-3" name="title" value="" placeholder="{{ translate('Product Name') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Email *</label>
                                <input type="text" class="form-control mb-3" name="title" value="" placeholder="{{ translate('Product Name') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Contact Number *</label>
                                <input type="text" class="form-control mb-3" name="title" value="" placeholder="{{ translate('Product Name') }}" required>
                            </div>
                            <!-- <div class="form-group">
                                <label>Message *</label>
                                <input type="text" class="form-control mb-3" name="title" value="{{ $product->name }}" placeholder="{{ translate('Product Name') }}" required>
                            </div> -->
                            <div class="form-group">
                              <label>Message *</label>
                                <textarea class="form-control" rows="8" name="message" required placeholder="{{ translate('Your Question') }}"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary fw-600" data-dismiss="modal">{{ translate('Cancel')}}</button>
                            <button type="submit" class="btn btn-primary fw-600">{{ translate('Send')}}</button>
                        </div>
                    </form>
            </div>
          </div>

        </div>
      </div>
@endif
