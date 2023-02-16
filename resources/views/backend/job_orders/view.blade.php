@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
			<h1 class="h3">{{translate('DetailsJob Orders')}}</h1>
	</div>
</div>
<!-- <div class="row">
	<div class="col-md-12">
		<div class="card" style="margin-top:20px;">
		    <div class="card-header row gutters-5">
				<div class="col text-center text-md-left">
					<h5 class="mb-md-0 h6">{{ translate('Details Job Orders') }}</h5>
				</div>
		    </div> 
		</div>
	</div>
</div> -->










<div class="row">
    <div class="col-lg-12">
        <div class="alerts-con"></div><div class="container">
            <div class="box" id="cls_main_div">
                <div class="box-content">
                    <div class="row">
                        <div id="cls_border">
                            <table width="100%" border="0">
                                <tbody>
                                    <tr>
                                        <td style="width:  60%; padding: 5px;">
                                            <img src="https://gciwatch.com/assets/images/GCI.jpg" style="width: 65%;">
                                        </td>
                                        <td style="width:  25%; padding: 5px;">
                                            <p id="cls_job_details">Job Order No :{{$jo_order->job_order_number}}</p>
                                            <p id="cls_job_details">DATE:{{ date("m/d/20y", strtotime($jo_order->estimated_date_return) );}}</p>
                                            <p id="cls_job_details">Bag Number:{{$jo_order->bag_number}}</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-6" style="margin-top: 50px;padding-left: 50px;">
                                    <p id="cls_job_details">Description</p><br>
                                    <p id="cls_job_details">Stock ID : {{$jo_order_detail->stock_id}}</p>
                                    <p id="cls_job_details">Model Number : {{$jo_order_detail->model_number}}</p>
                                    <p id="cls_job_details">Serial Number :{{$jo_order_detail->serial_number}}</p>
                                    <p id="cls_job_details">Weight :{{$jo_order_detail->weight}}</p>
                                    <p id="cls_job_details">Screw Count : {{$jo_order_detail->screw_count}}</p>
                                    <p id="cls_job_details">Images :<br>
                                    </p>    
                                    <p id="cls_job_details">Job Details :
                                        @if($jo_order_detail->job_details==1)
                                            {{translate('Polish')}} 
                                        @elseif($jo_order_detail->job_details==2)
                                            {{translate('Overhual')}}  
                                        @elseif($jo_order_detail->job_details==3)
                                            {{translate('Change/Put Dial')}}  
                                        @elseif($jo_order_detail->job_details==4)
                                            {{translate('Change/Put bezel')}}  
                                        @elseif($jo_order_detail->job_details==5)
                                            {{translate('Change/Put band')}}  
                                        @elseif($jo_order_detail->job_details==6)
                                            {{translate('Change/Put crystal')}}  
                                        @elseif($jo_order_detail->job_details==7)
                                            {{translate('Change/Put Insert')}}  
                                        @elseif($jo_order_detail->job_details==8)
                                            {{translate('Swap Dial')}}  
                                        @elseif($jo_order_detail->job_details==9)
                                            {{translate('Swap Bezel')}}  
                                        @elseif($jo_order_detail->job_details==10)
                                            {{translate('Swap Band')}}  
                                        @elseif($jo_order_detail->job_details==11)
                                            {{translate('Swap Movement')}}  
                                        @elseif($jo_order_detail->job_details==12)
                                            {{translate('Remove Dial')}}  
                                        @elseif($jo_order_detail->job_details==13)
                                            {{translate('Remove Bezel')}}  
                                        @elseif($jo_order_detail->job_details==14)
                                            {{translate('Remove Crystal')}}  
                                        @elseif($jo_order_detail->job_details==15)
                                            {{translate('Estimate')}}  
                                        @elseif($jo_order_detail->job_details==16)
                                            {{translate('Fix Crown')}}  
                                        @elseif($jo_order_detail->job_details==17)
                                            {{translate('Fix Band')}}  
                                        @elseif($jo_order_detail->job_details==18)
                                            {{translate('Assemble')}}  
                                        @elseif($jo_order_detail->job_details==19)
                                            {{translate('Disassemble')}}  
                                        @elseif($jo_order_detail->job_details==20)
                                            {{translate('PVD')}}  
                                        @elseif($jo_order_detail->job_details==21)
                                            {{translate('Disassemble for Polish Out')}}  
                                        @elseif($jo_order_detail->job_details==22)
                                            {{translate('Engrave')}}  
                                        @elseif($jo_order_detail->job_details==23)
                                            {{translate('Others(will need explanation)')}} 
                                        @endif    
                                    </p><br>
                                    <p id="cls_job_details">Notes : </p><br>
                                    <p id="cls_job_details">Agent :{{$jo_order->agent_name}}</p>
                                </div>
                                <div class="col-md-6" style="margin-top: 50px;padding-left: 50px;">
                                    <p id="cls_job_details"></p><div class="job_item" style="display:inline-block;width=" 70px;"=""><a href="https://gciwatch.com/assets/job/" data-toggle="lightbox" data-gallery="multiimages"><img class="img-responsive img-thumbnail" src="https://gciwatch.com/assets/job/" alt=""></a></div>
                                    <p></p>
                                </div>
                            </div>
                            <p style="border: none;"></p>                                      
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style type="text/css">
            #cls_job_details{font-weight: bold;}
            #cls_border{border: 2px solid;}
            #cls_main_div{width: 50%;}
        </style><div class="clearfix"></div>
    </div>
</div>
@endsection
@section('modal')
    @include('modals.delete_modal')
@endsection
