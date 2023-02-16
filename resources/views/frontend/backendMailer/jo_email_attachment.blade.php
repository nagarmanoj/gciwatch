<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css">
    /*<style>*/

    /*            body{*/
    /*            margin:0px;*/
    /*            }*/
    /*            .print_main_sec{*/
    /*                margin:1px;*/
    /*             border:1px solid #000 !important;*/
    /*             width: 100%;*/

    /*            }*/
    /*            .pdf_sec{*/
    /*                width: 100%;*/
                    

    /*            }*/
    /*            .print_logo{*/
    /*                width: 45%;*/
    /*                display: inline-block;*/
    /*            }*/
    /*            .print_right{*/
    /*                width: 50% !important;*/
    /*                display: inline-block;*/
    /*                vertical-align: top;*/
    /*            }*/
    /*            .print_text_description{*/
    /*               width: 45% !important;*/
    /*                display: inline-block;*/
    /*            }*/
    /*            .rigth_img_sec{*/
    /*                width: 50%;*/
    /*                display: inline-block;*/
    /*                vertical-align: top;*/
    /*            }*/


                /*.mi_print hr{*/
                /*    border-bottom:2px solid #000 !important;*/
                /*    width:107%;*/
                /*    margin-left:-5px;*/
                /*}*/
    /*            .print_logo img{*/
    /*                width:80% !important;*/
    /*                height:auto !important;*/
    /*                margin-top:10px;*/

    /*            }*/
    /*            .print_text_description{*/
    /*                margin-bottom:15px;*/
    /*                padding-bottom: 10px !important;*/
    /*            }*/
    /*            .print_text_description h3{*/
    /*                margin-top:10px;*/
    /*                margin-bottom:10px;*/
    /*                font-weight:bold;*/
    /*                font-size: 30px !important;*/
    /*            }*/
    /*            .print_text_description h4{*/
    /*                font-size:18px !important;*/
    /*                letter-spacing: 1.5px;*/
    /*                height: 19px;*/
    /*                line-height:normal;*/
    /*                font-Weight:500;*/
    /*            }*/
    /*            .print-text h4{*/
    /*                font-size:18px !important;*/
    /*                letter-spacing: 1.5px;*/
    /*                height: 19px;*/
    /*                font-Weight:500;*/
    /*                line-height:normal;*/
    /*            }*/
    /*            .print_job_ouder h4{*/
    /*                font-size:18px !important;*/
    /*                letter-spacing: 1.5px;*/
    /*                height: 19px;*/
    /*                font-Weight:500;*/
    /*                line-height:normal;*/
    /*            }*/
    /*            .print_job_details h4{*/
    /*                font-size:18px !important;*/
    /*                letter-spacing: 1.5px;*/
    /*                height: 19px;*/
    /*                font-Weight:500;*/
    /*                line-height:normal;*/
    /*            }*/

    /*    </style>*/
  </head>
  <body>
    <div id="mail">
        
    <div class="print_main_sec">
        @foreach ($jo_order_detail as $job_key => $job_d_data)
        <div class="mi_print" style=" border-bottom:2px solid #000 !important;width:100% !important; padding-left:10px; padding-right:10px;">
      <div class="row pdf_sec">
        <div class='col-6 print_logo' style="width:45%; display: inline-block;">
          <img src="https://gcijewel.com/public/uploads/all/logo_gci1.jpg" class="img-fluid w-25 logo-rem" style="margin-top:20px;">
        </div>
        <div class='col-6 print-text print_right' style="padding-top:40px; padding-left:40px; width:45%; display: inline-block;">
          <h4> Job Order No. : {{$jo_order->job_order_number}}</h4>
          <h4 style="text-transform: uppercase;" >Date : {{ \Carbon\Carbon::parse($jo_order->date_returned)->format('m/d/Y')}}</h4>
          <h4 >Bag Number : {{$job_d_data->bag_number}}</h4>
        </div>
        </div>
        <div class="row">
        <div class='col-6 print_text_description'>
          <h3>Description </h3>
          <div class="print_dec">
            <h4> Job Order No. : {{$jo_order->job_order_number}}</h4>
            <h4>Stock Id : {{$job_d_data->stock_id}}</h4>
            <h4>Model Number : {{$job_d_data->model_number}}</h4>
            <h4>Serial No. : {{$job_d_data->serial_number}}</h4>
            <h4>Weight : {{$job_d_data->weight}}</h4>
            <h4>Screw Count : {{$job_d_data->screw_count}}</h4>
          </div>

          @php
            $photos_array='';
            $gallery_img=explode(",",trim($job_d_data->image_upon_receipt));

            foreach($gallery_img as $photos)
            {
                $photos_array.=uploaded_asset($photos)." , ";
            }
            $img=explode(" , ", $photos_array);
          @endphp
           <div class="print_job_ouder_img">
               <p style="padding:0px ; margin:15px 0px 0px 0px; font-size: 23px; font-weight:bold;"> Images :</p>
               @foreach($img as $r)
                 @if($r != '')
                  <img src="{{$r}}" style="width: 70px !important; height:50px !important; margin-bottom:20px !important; margin-top:20px !important; margin-right:10px !important">
                  @endif
              @endforeach
            </div>
            <div class="print_job_ouder">
               <h4>Agent : {{$job_d_data->first_name}} </h4>
               <h4>Agent Note : {{$job_d_data->agent_notes}} </h4>
               <h4>Parts Cost : {{$job_d_data->parts_cost}}</h4>
               <h4> Service Cost :{{$jo_order->service_cost}}</h4>
               <h4> Total Cost :{{$jo_order->total_actual_cost}}</h4>
            </div>
        </div>
        <div class="col-6 print-img rigth_img_sec print_right">
           <img src="{{uploaded_asset($job_d_data->image_upon_receipt)}}" style="margin-bottom:20px !important; margin-top:-10px; width:100% !important; max-height:260px !important; margin-right:5px !important;">
           <div class="print_job_details" style="padding-top:10px;">
             @php
             $commSepArr = array();
             $job_det_tag = json_decode($job_d_data->job_details);
             @endphp
             @foreach ($AlljoeditTag as $tagkey => $tagvalue)
               @if(in_array($tagkey , $job_det_tag))
                 @php
                  $tagvalue = str_replace('will need explanation',$job_d_data->others_note,$tagvalue);
                  $commSepArr[] = $tagvalue;
                 @endphp
               @endif
             @endforeach
           <h4>Job Details : <span style="font-size:16px;">{{implode(',',$commSepArr)}}</span></h4>
           <h4>Notes : {{$job_d_data->notes}} </h4>
           <h4>Parts Cost : {{$job_d_data->parts_cost}}</h4>
           <h4> Service Cost :{{$jo_order->service_cost}}</h4>
           <h4> Total Cost :{{$jo_order->total_actual_cost}}</h4>
           </div>
        </div>
        </div>
        @php
        $gallery_img=explode(",",trim($job_d_data->image_upon_receipt));
        $photos_array='';
            foreach($gallery_img as $photos){
                $photos_array.=uploaded_asset($photos)." , ";
                }
        @endphp
    <p style="text-align:center; margin:-10px 0px 0px 0px !important; font-size:20px;">Agent Copy</p>
       <!--<hr>-->
      </div>
        <!--<hr style="height:3px; color:#000 !important; background-color:#000 !important; border-color:#000 !important; margin:0px !important; padding:0px !important; width:101%; margin-left:-4px !important; z-index:0;">-->
      @endforeach



        @foreach ($jo_order_detail as $job_key => $job_d_data)
        <div class="mi_print" style="padding:10px 10px 5px 10px;">
      <div class="row print_sec">
        <div class='col-6 print_logo'>
          <img src="https://gcijewel.com/public/uploads/all/logo_gci1.jpg" class="img-fluid w-25 logo-rem">
        </div>
        <div class='col-5 print-text print_right' style="padding-top:30px; padding-left:80px;">
          <h4> Job Order No. : {{$jo_order->job_order_number}}</h4>
          <h4 style="text-transform: uppercase;" >Date : {{ \Carbon\Carbon::parse($jo_order->date_returned)->format('m/d/Y')}}</h4>
          <h4 >Bag Number : {{$job_d_data->bag_number}}</h4>
        </div>
        </div>
        <div class="row">
        <div class='col-6 print_text_description'>
            <h3>Description </h3>
            <div class="print_dec">
          <h4>Stock Id : {{$job_d_data->stock_id}}</h4>
          <h4>Model Number : {{$job_d_data->model_number}}</h4>
          <h4>Serial No. : {{$job_d_data->serial_number}}</h4>
          <h4>Weight : {{$job_d_data->weight}}</h4>
          <h4>Screw Count : {{$job_d_data->screw_count}}</h4>
          </div>

           <div class="print_job_ouder_img">
              <p style="padding:0px ; margin:15px 0px 0px 0px; font-size: 23px; font-weight:bold;"> Images :</p>
           @foreach($img as $r)
              @if($r != "")
            <img src="{{$r}}" style="width: 70px !important; height:50px !important; margin-bottom:20px !important; margin-top:20px !important; margin-right:10px !important">
            @endif
          @endforeach
            </div>
    <div class="print_job_ouder">
       <h4>Job Details : <span style="font-size:16px !important;">@php echo implode(',',$commSepArr); @endphp</span></h4>
            <h4>Agent : {{$job_d_data->first_name}} </h4>
           <h4>Agent Note : {{$job_d_data->agent_notes}} </h4>
    </div>
        </div>
        <div class="col-6 print-img rigth_img_sec print_right">
           <img src="{{uploaded_asset($job_d_data->image_upon_receipt)}}" style="margin-bottom:5px !important;margin-top:20px !important; width:100% !important; max-height:260px !important;">
           <div class="print_job_details" style="padding-top:40px;">
               <h4> {{$jo_order->job_order_number}} </h4>
               <h4 style="border-bottom:1px solid #000; padding-bottom:10px; height:40px !important;">Agent Signature : </h4>
           </div>
        </div>
         <p style="text-align:center; font-size:20px; margin-bottom:0px !important;margin-top:60px !important; padding:0px !important; width:100%;">GCI Copy</p>
        </div>


        @php
        $gallery_img=explode(",",trim($job_d_data->image_upon_receipt));
        $photos_array='';
            foreach($gallery_img as $photos){
                $photos_array.=uploaded_asset($photos)." , ";
                }
        @endphp

      </div>
      @endforeach
    </div>

    </div>
  </body>
</html>
