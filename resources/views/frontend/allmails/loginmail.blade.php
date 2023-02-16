<!DOCTYPE html>
<html>
<head>
    <title>gciwatch</title>
</head>
<body>
  @php
  $date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
  @endphp
  <p>Login</p><br />
    <p>User : {{auth()->user()->name}} <br />
        Login Time : {{  $date->format('H:i:s') }} <br />
        Data : {{  $date->format('m/d/Y') }} <br />
        IP Address {{$userIp}}
    </p>
</body>
</html>
