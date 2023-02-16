<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

    <title>Barcode</title>
    <style media="screen">
    *{
      margin: 0;
      padding: 0;
    }
    .mi_barcode_col .col-md-6{
      width: 48%;
      display: inline-block;
      margin-top: 40px;
      margin-left: 10px;
    }
      .card-body{
        width: 100%;
      }
      .bottomspace{
        margin-bottom: 20px;
      }
    </style>
  </head>
  <body>
    <div class="container">
      			@if($proarr != "")
      			@foreach($proarr as $proData)
                  @php
                  if($proData->productcondition_id != ""){
                    $proCondition = \App\Productcondition::where("id",$proData->productcondition_id)->firstOrFail();
                    $proConName= "";
                    if($proCondition->name != ""){
                      $proConName = $proCondition->name;
                    }
                  }
                  if($proData->category_id != ""){
                    $proCategory = \App\Category::where("id",$proData->category_id)->firstOrFail();
                    $proCatName= "";
                    if($proCategory->name != ""){
                      $proCatName = $proCategory->name;
                    }
                  }
                  if($proData->category_id != ""){
                    $ProductStocks = \App\ProductStock::where("product_id",$proData->id)->first();
                    $sku= "";
                    if($ProductStocks->sku != ""){
                      $sku = $ProductStocks->sku;
                    }
                  }

                  $proBrand = \App\Brand::where("id",$proData->brand_id)->first();
                  $brandName= "";
                  if($proCondition->name != ""){
                    $brandName = $proBrand->name;
                  }
                  $custom_1= "";
                  if($proData->custom_1 != ""){
                    $custom_1 = $proData->custom_1;
                  }
                  $custom_2= "";
                  if($proData->custom_2 != ""){
                    $custom_2 = $proData->custom_2;
                  }
                  $custom_3= "";
                  if($proData->custom_3 != ""){
                    $custom_3 = $proData->custom_3;
                  }
                  $custom_4= "";
                  if($proData->custom_4 != ""){
                    $custom_4 = $proData->custom_4;
                  }
                  $custom_5= "";
                  if($proData->custom_5 != ""){
                    $custom_5 = $proData->custom_5;
                  }
                  $custom_6= "";
                  if($proData->custom_6 != ""){
                    $custom_6 = $proData->custom_6;
                  }
                  $custom_7= "";
                  if($proData->custom_7 != ""){
                    $custom_7 = $proData->custom_7;
                  }
                  $paper_cart= "";
                  if($proData->paper_cart != ""){
                    $paper_cart = $proData->paper_cart;
                  }
                  $model= "";
                  if($proData->model != ""){
                    $model = $proData->model;
                  }
                  @endphp
                  <div class="row mi_barcode_col">
                    <div class="col-md-6">
                      <div class="card-body">
                        <p style="font-size:15px;">GCI WEB PORTAL</p>
    					          <p class="barcodeImage">{!! DNS1D::getBarcodeHTML($proData->stock_id, 'C39',1,30,'black', true) !!}</p>
                        <p style="font-size:8px;">{{$proData->stock_id}}</p>
                        <div class="barcoe_r1">
                          <p style="font-size:8px;"><span>{{$proConName}}</span> <span>{{ $brandName }}</span> <span>{{ $custom_7 }}</span> <span>{{ $paper_cart }}</span> <span>{{ $custom_3 }}</span></p>
                        </div>
                        <div class="barcoe_r2">
                          <p style="font-size:8px;"><span style="margin-right: 20px;">{{$model}}</span> <span>{{$custom_4}}</span></p>
                          <p style="font-size:8px;"><span style="margin-right: 20px;">{{$sku}}</span> <span>{{$custom_5}}</span></p>
                        </div>
                        <div class="barcoe_r3">
                          <p style="font-size:8px;">C:<span style="margin-right: 20px;">{{$proData->cost_code}} @if($proData->unit_price != "") | @endif {{$proData->unit_price}} @if($proData->msrp != "") | @endif {{$proData->msrp}}</span>  <span>{{$proData->weight}} @if($custom_6 != "") | @endif  {{$custom_6}} @if($custom_2 != "") | @endif  {{$custom_2}}</span></p>
                        </div>
                        <div class="barcoe_r3">
                          <p style="font-size:8px;"><span>{{$proCatName}} @if($proData->size != "") | @endif {{$proData->size}}@if($proData->description != "") | @endif {{$proData->description}}</span> </p>
                        </div>
          						</div>
                    </div>
                    <div class="col-md-6">
                      <div class="card-body bottomspace">
                        <p style="font-size:15px;"><span>{{$proData->stock_id}}</span> <span>{{$custom_1}}</span></p>
                        <div class="barcoe_r1">
                          <p style="font-size:8px;"><span>{{$proConName}}</span> <span>{{ $brandName }}</span> <span>R: {{$proData->msrp}}</span></p>
                          <p style="font-size:8px;"><span>{{$model}}</span> <span>{{ $brandName }}</span> <span>{{$sku}}</span></p>
                          <p style="font-size:8px;"><span>{{$paper_cart}}</span> <span>{{ $proData->cost_code }}</span> <span>{{ $proData->weight }}</span> <span>{{$custom_6}}</span> <span>{{$custom_7}}</span></p>
                        </div>
          						</div>
                      <div class="card-body">
                        <p style="font-size:15px;"><span>{{$proData->stock_id}}</span> <span>{{$custom_1}}</span></p>
                        <div class="barcoe_r1">
                          <p style="font-size:8px;"><span>{{$proConName}}</span> <span>{{ $brandName }}</span> <span>R: {{$proData->msrp}}</span></p>
                          <p style="font-size:8px;"><span>{{$model}}</span> <span>{{ $brandName }}</span> <span>{{$sku}}</span></p>
                          <p style="font-size:8px;"><span>{{$paper_cart}}</span> <span>{{ $proData->cost_code }}</span> <span>{{ $proData->weight }}</span> <span>{{$custom_6}}</span> <span>{{$custom_7}}</span></p>
                        </div>
          						</div>
                    </div>
                  </div>

      				@endforeach
      			@endif
    </div>


    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>

  </body>
</html>
