<!DOCTYPE html>
<html>
<head>
    <title>gciwatch</title>
</head>
<body>
  @php
  $date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
  @endphp
  <p>Memo Status Edited</p><br />
  @if($csName != "")
  <p>Company Name: <span>{{$csName->company}}</span></p><br />
  @endif

    @foreach ($LsProAct as $PActval)
      <p>
        User : {{auth()->user()->name}} Updated {{$PActval}} Time : {{  $date->format('H:i:s') }} Data : {{  $date->format('m/d/Y') }}
      </p>
    @endforeach
    <p>
      IP Address: {{$userIp}}
    </p>

</body>
</html>
