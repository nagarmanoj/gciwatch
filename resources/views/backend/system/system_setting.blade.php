@extends('backend.layouts.app')

@section('content')

<!-- <div class='container'>
    @if(Session::has('message'))
    {{$message ?? ''  }}
    @endif
    <form action="{{route('system.setting.save')}}" method="post">
        @csrf
      <div class="row">
        <div class="col">
          <input type="text" name="system_saleTax" value="{{$model->SaleTax ?? ''}}"  class="form-control" placeholder="Sale Tax">
        </div>
        
      </div>
      <input type="submit" value="Sales">
    </form>
</div> -->











<div class="card">
  <!-- Tabs navs -->
  <ul class="nav nav-tabs nav-fill mb-3" id="ex1" role="tablist" style="display:-webkit-inline-box;">
    <li class="nav-item" role="presentation" style="width:100px !important;" >
      <a class="nav-link active"  style="color:#000;font-weight:bold;"  id="ex2-tab-1" data-toggle="tab" href="#tex_setting" role="tab" aria-controls="ex2-tabs-1" aria-selected="true">Tax</a>
    </li>
    <li class="nav-item" role="presentation" style="width:100px !important;">
      <a class="nav-link" style="width:100px;color:#000;font-weight:bold;" id="ex2-tab-2" data-toggle="tab" href="#other" role="tab" aria-controls="ex2-tabs-2" aria-selected="false">Activity</a>
    </li>
  </ul>
  <!-- Tabs navs -->

  <!-- Tabs content -->
  <div class="tab-content" id="ex2-content">
    <div
      class="tab-pane fade show active"
      id="tex_setting"
      role="tabpanel"
      aria-labelledby="ex2-tab-1">
        <div class='container'>
          @if(Session::has('message'))
            {{$message ?? ''  }}
          @endif
          <form action="{{route('system.setting.save')}}" method="post" class="mi_system">
            @csrf
            <div class="row">
              <div class="col form-group">
                <span>Sales Tax</span><input type="text" name="system_saleTax" value="{{$model->SaleTax ?? ''}} "  class="form-control numbersOnly" placeholder="Sale Tax">
                <input type="submit" class="form-controll btn btn-primary" value="Update">
              </div>
        
            </div>
             <!-- <input type="submit" class="form-controll btn btn-primary" value="Sales"> -->
          </form>
        </div>
      </div>
      <div class="tab-pane fade" id="other" role="tabpanel" aria-labelledby="ex2-tab-2">
        <p>No Notes Found.</p>
      </div>
    </div>
  <!-- Tabs content -->
</div>

@endsection




@section('script')
<script>
  jQuery('.numbersOnly').keyup(function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
});
  </script>
@endsection
