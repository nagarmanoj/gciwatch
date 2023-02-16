<!DOCTYPE html>
<html>
<head>
    <title>gciwatch</title>
</head>
<body>
  @php
  $date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
  @endphp
  <p>Logout</p><br />
    <p>User : {{auth()->user()->name}} <br />
        Logout Time : {{  $date->format('H:i:s') }} <br />
        Data : {{  $date->format('m/d/Y') }} <br />
        IP Address {{$userIp}}
    </p>
</body>
</html>
