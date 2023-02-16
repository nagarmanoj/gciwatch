<!DOCTYPE html>
<html>
<head>
    <title>gciwatch</title>
</head>
<body>
  @php
  $date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
  @endphp
  <p>Inventory Run</p>
    <p>
      User : {{auth()->user()->name}} Run Inventory Time : {{  $date->format('H:i:s') }} Data : {{  $date->format('m/d/Y') }}
    </p>
    <p>
      IP Address: {{$userIp}}
    </p>
    <!-- <table>
	<thead>
		<tr>
			<th>Product Type Name</th>
			<th>Warehouse Name</th>
			<th>Stock ids</th>
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
</table> -->


</body>
</html>
