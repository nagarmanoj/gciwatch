<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <style media="screen">

      .mi_daily_report tr th, .mi_daily_report tr td{
        border:1px solid #000;
        padding: 5px;
        font-weight: bold;
        font-size: 14px;
      }
      .mi_daily_report tr th{
        font-size: 18px;
      }

    </style>
  </head>
  <body>
    <table class="mi_daily_report">
      <thead>
        <tr>
          <th>Image</th>
          <th>Activity</th>
        </tr>
        <tr>
          <th colspan="2">Memo Summary</th>
        </tr>
      </thead>
      <tbody>
        @if($daily_mail != "")
        @foreach($daily_mail as $report)
        @if($report->type == 'Memo_status' || $report->type == 'MemoUser' || $report->type == 'Memo')
        <tr>
          <td>Image</td>
          <td>
            @php
            echo $html = stripslashes($report->activity);
            @endphp
           {{ \Carbon\Carbon::parse($report->created_at)->format('m/d/Y')}}
          </td>
        </tr>
        @endif
        @endforeach
        @endif
      </tbody>
    </table>
    <table class="mi_daily_report">
      <thead>
        <tr>
          <th colspan="2">Product Summary</th>
        </tr>
      </thead>
      <tbody>
        @if($daily_mail != "")
        @foreach($daily_mail as $report)
        @if($report->type == 'product')
        <tr>
          <td>Image</td>
          <td>
            @php
            echo $html = stripslashes($report->activity);
            @endphp
           {{ \Carbon\Carbon::parse($report->created_at)->format('m/d/Y')}}
          </td>
        </tr>
        @endif
        @endforeach
        @endif
      </tbody>
    </table>
    <table class="mi_daily_report">
      <thead>
        <tr>
          <th colspan="2">Job Order Summary</th>
        </tr>
      </thead>
      <tbody>
        @if($daily_mail != "")
        @foreach($daily_mail as $report)
        @if($report->type == 'joborder' || $report->type == 'joborderAgent')
        <tr>
          <td>Image</td>
          <td>
            @php
            echo $html = stripslashes($report->activity);
            @endphp
           {{ \Carbon\Carbon::parse($report->created_at)->format('m/d/Y')}}
          </td>
        </tr>
        @endif
        @endforeach
        @endif
      </tbody>
    </table>
  </body>
</html>
