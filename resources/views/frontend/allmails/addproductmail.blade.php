<!DOCTYPE html>
<html>
<head>
    <title>gciwatch</title>
</head>
<body>
  @php
  $date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
  @endphp
  <p>Product Add</p><br />
    <p>
      User : {{auth()->user()->name}} Added {{$stock_id}} Time : {{  $date->format('H:i:s') }} Data : {{  $date->format('m/d/Y') }}
    </p>
    <p>
      Listing Name : {{$product_type_name}}
    </p>
    <p>
      Stock ID : {{$stock_id}}
    </p>
    <p>
      Condition : {{$condition}}
    </p>
    <p>
      Model Number : {{$model}}
    </p>
    <p>
      Metal : {{$metal}}
    </p>
    <p>
      Serial No : {{$sku}}
    </p>
    <p>
      Paper/Cert : {{$paper_cart}}
    </p>
    <p>
      C Code : {{$cost_code}}
    </p>
    <p>
      Retail Price : {{$unit_price}}
    </p>
      @for($i=1; $i < 11; $i++)
        @php
        $csField = "custom_".$i;
        $csFieldVal = "custom_".$i."_val";
        @endphp
        @if( $$csField != "")
        <p>
          {{ $$csField }}  :   {{ $$csFieldVal }}
        </p>
        @endif
      @endfor
    <p>
    Added to the inventory a Stock id {{$stock_id}} Purchased From the
    </p>
    <p>
      IP Address: {{$userIp}}
    </p>
</body>
</html>
