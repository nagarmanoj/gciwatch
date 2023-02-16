<!DOCTYPE html>
<html>
<head>
    <title>gciwatch</title>
    <style>
  .mi_inv_pdf{
    width: 100%;
  }
  .mi_inv_pdf tr th, .mi_inv_pdf tr td{
    border:1px solid #000;
    padding: 10px;
    text-align: center;
    font-size: 18px !important;
  }
  .mi_inv_pdf tr td.stock_id{
    width: 50%;
  }
    </style>
</head>
<body>
  @php
  $date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
  @endphp
  <table class="mi_inv_pdf">
  <thead>
  <tr>
    <th>Product Type Name</th>
    <th>Warehouse Name</th>
    <th style="width:50%;">Stock ids</th>
    <th>Date & Time</th>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td>{{$lsType}}</td>
    <td>{{$warehouse}}</td>
    <td class="stock_id">
      <span>{{$stockId}}</span>
    </td>
    <td>{{  $date->format('m/d/Y') }} {{  $date->format('H:i:s') }}</td>
  </tr>
  </tbody>
  </table>

</body>
</html>
