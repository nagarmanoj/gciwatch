@extends('backend.layouts.app')
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<div class="aiz-titlebar text-left mt-2 mb-3">
</div>
<br>
<div class="card">
  <!-- Tabs navs -->
  <ul class="nav nav-tabs nav-fill mb-3" id="ex1" role="tablist" style="display:-webkit-inline-box;">
    <li class="nav-item" role="presentation" style="width:100px !important;" >
      <a class="nav-link active" style="width:100px;" id="ex2-tab-1" data-toggle="tab" href="#a" role="tab" aria-controls="ex2-tabs-1" aria-selected="true">All</a
      >
    </li>
    <li class="nav-item" role="presentation" style="width:100px !important;">
      <a class="nav-link" style="width:100px" id="ex2-tab-2" data-toggle="tab" href="#b" role="tab" aria-controls="ex2-tabs-2" aria-selected="false">Activities</a>
    </li>
    <li class="nav-item" role="presentation" style="width:100px !important;">
      <a class="nav-link" style="width:100px" id="ex2-tab-3" data-toggle="tab" href="#c" role="tab" aria-controls="ex2-tabs-3" aria-selected="false">Notes</a>
      <!-- <a
        class="nav-link"
        style="width:100px;"
        id="ex2-tab-3"
        data-toggle="tab"
        href="#c"
        role="tab"
        aria-controls="ex2-tabs-3"
        aria-selected="false">Notes</a> -->
    </li>
  </ul>
  <!-- Tabs navs -->

  <!-- Tabs content -->
  <div class="tab-content" id="ex2-content">
    <div class="tab-pane fade show active" id="a" role="tabpanel" aria-labelledby="ex2-tab-1">
     <table>

       @if(!empty($activitylogData))

       @foreach($activitylogData as $ActLog)

         <tr >
           <td>
            <div class="cls_store_number ml-3">
              @php
              echo $html = stripslashes($ActLog->activity);
              @endphp
               {{ \Carbon\Carbon::parse($ActLog->created_at)->format('m/d/Y')}}
            </div>
           </td>
       </tr>
       @endforeach
         @endif
     </table>
    </div>
    <div class="tab-pane fade" id="b" role="tabpanel" aria-labelledby="ex2-tab-2">
    <table>

             @if(!empty($activitylogData))

             @foreach($activitylogData as $ActLog)

               <tr>
                 <td>
                  <div class="cls_store_number ml-3">
                    @php
                    echo $html = stripslashes($ActLog->activity);
                    @endphp
                     {{ \Carbon\Carbon::parse($ActLog->created_at)->format('m/d/Y')}}
                  </div>
                 </td>
             </tr>
             @endforeach
               @endif
     </table>
    </div>
    <div class="tab-pane fade" id="c" role="tabpanel" aria-labelledby="ex2-tab-3">
      <p>No Notes Found.</p>
    </div>
  </div>
  <!-- Tabs content -->
</div>
@endsection
@section('modal')
    @include('modals.delete_modal')
@endsection
<script type="text/javascript">
$(document).ready(function(){

  var agenturlVal = $('.agenturlVal').val();

  var joburlVal = $('.joburlVal').val();

  var memourl = $('.memourl').val();

  var csurl = $('.csurl').val();

  var prourl = $('.prourl').val();



  $(".agenturl").each(function() {

      var agenturl = $(this).data('uid');

      var url = '{{ route("agent.activity", ":id") }}';

          url = url.replace(':id', agenturl);

          // alert(url);

      $(this).attr("href", url);

  });

  $(".joburl").each(function() {

    var joburl = $(this).data('uid');

    var url = '{{ route("job_orders.edit", ":id") }}';

        url = url.replace(':id', joburl);

        // alert(url);

    $(this).attr("href", url);

  });

  $(".memourl").each(function() {

      var memourl = $(this).data('uid');

      var url = '{{ route("memo.edit", ":id") }}';

          url = url.replace(':id', memourl);

      $(this).attr("href", url);

  });

  $(".csurl").each(function() {

      var csurl = $(this).data('uid');

      var url = '{{ route("retailreseller.activities", ":id") }}';

          url = url.replace(':id', csurl);

      $(this).attr("href", url);

  });

  $(".prourl").each(function() {

      var prourl = $(this).data('uid');

      var url = '{{ route("products.viewproduct", ":id") }}';

          url = url.replace(':id', prourl);

      $(this).attr("href", url);

  });

});
</script>
