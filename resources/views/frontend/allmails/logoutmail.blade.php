<!DOCTYPE html>
<html>
<head>
    <title>gciwatch</title>
</head>
<body>
  @php
  $date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
  @endphp
    <p>Login Failed</p><br />
    <p>User Mail : {{$email}} <br />
      Login Time : {{  $date->format('H:i:s') }}
      Data : {{  $date->format('m/d/Y') }} <br />
      IP Address {{$userIp}}
    </p>
</body>
</html>
