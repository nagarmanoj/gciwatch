<div class="box-content">
    <table width="100%" style="width: 100%;" border="0">
        <tbody><tr>
            <td style="width:  60%;">   
                <img src="{{$imgbase64}}" style="width: 350px;">
            </td>
                       <td style="width: 10%;">



                       </td>

                       <td style="width:  30%;">

                           @if($memo->memo_status == '1')

                           <h4 style="font-weight: bold;text-decoration:underline">Memo</h4>

                           <p>Memo No : M101{{$memo->id}}</p>



                           @endif

                           <p>DATE :  @if($memo->created_at) {{ date('m/d/Y', strtotime($memo->created_at)) }} @endif</p>

                       </td>

                   </tr>



                   <tr>

                       <td colspan="3">

                           <table style="width: 100%;border: 1px solid #000000;border-collapse: collapse;">

                                   <tbody>

                                       <tr>

                                       <td style="text-align: center;width:  50%;font-weight: bold;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Bill To</td>

                                       <td style="text-align: center;width:  50%;font-weight: bold;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Ship To</td>

                                   </tr>

                                   <tr>

                                   <td style="width:  50%;border: 1px solid black;padding-left: 8px;font-size: 10px;" valign="top">

                                       <table>

                                           <tbody>

                                               @if($memo->customer_group=='reseller')



                                               <tr>

                                                   <td>{{$memo->company}}</td>

                                               </tr>

                                               <tr>

                                                   <td>{{$memo->customer_name}}</td>

                                               </tr>

                                               <tr>

                                                   <td>{{$memo->office_address}}</td>

                                               </tr>

                                               <tr>

                                                   <td>{{$memo->city}},{{$memo->drop_state}} {{$memo->zipcode}}</td>

                                               </tr>

                                               <tr>

                                                   <td>{{$memo->office_phone_number}}</td>

                                               </tr>

                                               <tr>

                                                   <td>{{$memo->email}}</td>

                                               </tr>



                                                @elseif ($memo->customer_group=='retail')

                                                <tr>

                                                   <td>{{$memo->customer_name}}</td>

                                               </tr>



                                               <tr>

                                                   <td>{{$memo->office_address}}</td>

                                               </tr>

                                               <tr>

                                                   <td>{{$memo->city}},{{$memo->drop_state}} {{$memo->zipcode}}</td>

                                               </tr>

                                               <tr>

                                                   <td> {{$memo->phone}}</td>

                                               </tr>

                                               @endif



                                           </tbody>

                                       </table>

                                   </td>

                                   <td style="font-size: 10px;text-align: left;width:  50%;padding-left: 8px;border-right:1px solid #000000;" valign="top">

                                       <table>

                                           <tbody>

                                               @if($memo->customer_group=='reseller')



                                               <tr>

                                                   <td>{{$memo->company}}</td>

                                               </tr>

                                               <tr>

                                                   <td>{{$memo->customer_name}}</td>

                                               </tr>

                                               <tr>

                                                   <td>{{$memo->office_address}}</td>

                                               </tr>

                                               <tr>

                                                   <td>{{$memo->city}},{{$memo->drop_state}} {{$memo->zipcode}}</td>

                                               </tr>

                                               <tr>

                                                   <td>{{$memo->office_phone_number}}</td>

                                               </tr>

                                                @elseif($memo->customer_group=='retail')

                                                <tr>

                                                   <!-- <td>{{$memo->customer_name}}</td> -->

                                               </tr>



                                               <tr>

                                                   <td>{{$memo->office_address}}</td>

                                               </tr>

                                               <tr>

                                                   <td>{{$memo->city}},{{$memo->drop_state}} {{$memo->zipcode}}</td>

                                               </tr>

                                               <tr>

                                                    <td>{{$memo->phone}}</td>

                                               </tr>

                                               @endif

                                           </tbody>

                                       </table>

                                   </td>

                                   </tr>



                               </tbody>

                           </table>

                       </td>

                   </tr>

                                                    @php

                                                setlocale(LC_MONETARY, 'en_US');

                                                @endphp

                   <tr>

                       <td colspan="5">

                           <table style="width: 100%;border: 1px solid #000000;border-collapse: collapse;">

                               <tbody>

                                   <tr>

                                       <td style="text-align: justify;width: 100%;font-size: 8px;padding: 1px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;"  valign="top">

                                           <p style="margin: 0 0 5px;">

                                               By signing this document (the "Agreement") and/or accepting delivery of the goods, the individual or entity purchasing the merchandise as indicated at the top of this Agreement ("Buyer") accepts all the terms and conditions in this Agreement.  All terms and conditions in this Agreement are nonnegotiable and can only be changed if agreed to in writing by <b>Shak Corp., d.b.a., GCI jewelry</b> (hereinafter referred to as "<b>GCI JEWELRY</b>").  Any handwritten changes made by Buyer to this Agreement are not enforceable unless agreed to in writing by <b>GCI JEWELRY</b>.

                                           </p>

                                           <p style="margin: 0 0 5px;">

                                               The merchandise described above remains the property of <b>GCI JEWELRY</b>, until paid for, and all such merchandise is subject to <b>GCI JEWELRY\'S</b> order and control and shall be returned to it immediately upon demand. Until such merchandise is returned to <b>GCI JEWELRY</b>, Buyer shall assume all risks of loss, damage, theft, and/or any action or non-action that will detrimentally affect the value of the merchandise.  No right or power is given to the Buyer to sell, pledge, hypothecate or otherwise dispose of this merchandise until paid for in full.  In the even that <b>GCI JEWELRY</b> should fail to receive payment when due, <b>GCI JEWELRY</b> may, at its option, charge interest on the monies owed at the highest rate allowed by law. The parties acknowledge the contract made by this Agreement was made in California and agree that any action brought to enforce any of the terms hereof may only be brought in the County of Los Angeles, state of California. If either party brings actions to declare their rights under or to enforce this Agreement, the prevailing party shall be entitled to be paid reasonable attorney???s fees by the losing party.

                                           </p>

                                           <h5 style="text-align: center;font-weight: bold;margin: 3px 0 3px;font-size: 10px;">PERSONAL GUARANTY</h5>

                                           <p style="margin: 0 0 5px;">

                                               I agree in my individual capacity as well as on behalf of the Buyer, to jointly and severally pay to <b>GCI JEWELRY</b>, all indebtedness of the Buyer (whether a corporation, partnership, or otherwise) at any time arising under or relating to any purchases or merchandise delivered by consignment or creation of bailment. I further agree that I shall be fully bound to this personal guarantee and any future personal guarantees executed by myself, or any of my employees or agents that sign on behalf of the Buyer for purchase of merchandise from <b>GCI JEWELRY</b>.  As guarantor I waive (i) presentment, demand, notice, protest, notice of protest, and notice of nonpayment; (ii) any defense arising by reason of any defense of Buyer or other guarantor; and (iii) the right to require <b>GCI JEWELRY</b>, to proceed against Buyer or any other guarantor to pursue any remedy in connection with the guaranteed indebtedness, or to notify guarantor of any additional indebtedness incurred by the customer, or of any changes in the Buyer???s financial condition. I also authorize <b>GCI JEWELRY</b>, without notice or prior consent, to (i) extend, modify, compromise, accelerate, renew of increase, or otherwise change the terms of the guaranteed indebtedness; (ii) proceed against one or more guarantors without proceeding against the Buyer or another guarantor; and (iii) release or substitute any party to indebtedness or this guarantee. I agree (i) I will pay costs and reasonable attorneys fees in enforcing this guarantee; (ii) this guarantee is made in California and will be governed by California law; (iii) this guarantee shall benefit GCI JEWELRY, and it???s successors and assigns, and (iv) an electronic facsimile or email of my signature, in any capacity, may be used as evidence of my agreement to the terms of this guaranty. If any portion of the terms and conditions are held invalid as a result of the law, such portion shall be deleted, and the remaining terms and conditions shall remain enforceable.

                                           </p>

                                       </td>

                                   </tr>

                               </tbody>

                           </table>

                       </td>

                   </tr>

               </tbody>

           </table>

           <table width="100%" style="width: 100%;">

               <tbody>

                   <tr>

                       <td colspan="3">

                           <table style="width: 100%;border: 1px solid #000000;border-collapse: collapse;">

                               <tbody><tr>

                                   <td style="font-size: 11px;text-align: center;font-weight: bold;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">REFERENCE</td>

                                   <td style="font-size: 11px;text-align: center;font-weight: bold;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">PAYMENT</td>

                                   <td style="font-size: 11px;text-align: center;font-weight: bold;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">TRACKING</td>

                                   <td style="font-size: 11px;text-align: center;font-weight: bold;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">DUE DATE</td>

                               </tr>

                               @if($memo->reference==''|| $memo->reference==NULL){$memo->reference='';}@endif

                               @if($memo->payment=='' || $memo->payment==NULL){$memo->payment='';}@endif

                               @if($memo->tracking=='' || $memo->tracking==NULL){$memo->tracking='';}@endif

                               @if($memo->due_date=='' || $memo->due_date==NULL){$memo->due_date='';}@endif

                               <tr>

                                   <td style="font-size: 11px;text-align: center;height: 13px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">{{$memo->reference}}</td>

                                   <td style="font-size: 11px;text-align: center;height: 13px; border-bottom: 1px solid #000000;border-right: 1px solid #000000;">{{$memo->payment}}</td>

                                   <td style="font-size: 11px;text-align: center;height: 13px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">{{$memo->tracking}}</td>

                                   <td style="font-size: 11px;text-align: center;height: 13px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">@if($memo->due_date) {{ date('m/d/Y', strtotime($memo->due_date)) }} @endif</td>

                               </tr>

                           </tbody></table>

                       </td>

                   </tr>

                   <tr>

                       <td colspan="3" valign="top">

                           <table style="width: 100%;border: 1px solid #000000;border-collapse: collapse;">

                               <tbody><tr>

                                   <td style="font-size:10px;text-align: center;font-weight: bold;width: 10%;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">ID</td>

                                   <td style="font-size:10px;text-align: center;font-weight: bold;width: 34%;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">DESCRIPTION</td>

                                   <td style="font-size:10px;text-align: center;font-weight: bold;width: 10%;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">STATUS</td>

                                   <td style="font-size:10px;text-align: center;font-weight: bold;width: 10%;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">DATE</td>

                                   <td style="font-size:10px;text-align: center;font-weight: bold;width: 7%;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">QTY</td>

                                   <td style="font-size:10px;text-align: center;font-weight: bold;width: 14%;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">UNIT PRICE</td>

                                   <td style="font-size:10px;text-align: center;font-weight: bold;border-bottom: 1px solid #000000;">TOTAL</td>

                               </tr>

                               <?php $total_quantity=0; ?>

                                      @foreach ($memo_details_data as  $memo_det)

                                       <tr>

                                           <td style="font-size:10px;text-align: center;border-bottom: 1px solid #000000;border-right: 1px solid #000000;" valign="top">

                                             {{$memo_det->stock_id}}

                                           </td>

                                              @php

                                              if(($memo_det->name=='New') ||  ($memo_det->name=='New NS') || ($memo_det->name=='NNS')  || ($memo_det->name=='NS')){

                                              $name = 'Unworn';

                                              }else{

                                              $name = 'Preowned';

                                              }

                                              @endphp

                                           <td style="font-size:10px;text-align: left;padding: 1px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;" valign="top">

                                             @if($memo_det->listing_type=='Watch')

                                                  <span>{{$name}}:</span>

                                                 <span>{{$memo_det->model}}-{{$memo_det->sku}}-{{$memo_det->weight}}g-{{$memo_det->custom_6}}-{{$memo_det->paper_cart}}-{{$memo_det->custom_3}}-{{$memo_det->custom_4}}-{{$memo_det->custom_5}}

                                                 </span>

                                                 <!-- <span>{{$memo_det->sku}}-</span> -->

                                                 <!-- <span>{{$memo_det->weight}}g-</span> -->

                                                 <!-- <span>{{$memo_det->custom_6}}-</span>  -->

                                                 <!-- <span>{{$memo_det->paper_cart}}-</span>    -->

                                                 <!-- <span>{{$memo_det->custom_3}}-</span>  -->

                                                 <!-- <span>{{$memo_det->custom_4}}-</span>    -->

                                                 <!-- <span>{{$memo_det->custom_5}}</span>   -->

                                               @endif



                                               @if($memo_det->listing_type=='Bracelet')

                                                  <span>{{$name}}:{{$memo_det->model}}-{{$memo_det->sku }}-{{$memo_det->weight}}g</span>

                                                 <!-- <span>{{$memo_det->model}}-</span> -->

                                                 <!-- <span>{{$memo_det->sku }}-</span> -->

                                                 <!-- <span>{{$memo_det->weight}}g</span> -->

                                               @endif



                                               @if($memo_det->listing_type=='Bangle')

                                                  <span>{{$name}}:{{$memo_det->model}}-{{$memo_det->sku}}-{{$memo_det->weight}}g-{{$memo_det->paper_cart }}</span>

                                                 <!-- <span>{{$memo_det->model}}-</span> -->

                                                 <!-- <span>{{$memo_det->sku}}-</span> -->

                                                 <!-- <span>{{$memo_det->weight}}g-</span> -->

                                                 <!-- <span>{{$memo_det->paper_cart }}</span>    -->

                                                @endif



                                                @if($memo_det->listing_type=='Necklace')

                                                  <span>{{$name}}:{{$memo_det->model}}-{{$memo_det->sku}}-{{$memo_det->weight}}g</span>

                                                 <!-- <span>{{$memo_det->model}}-</span> -->

                                                 <!-- <span>{{$memo_det->sku}}-</span> -->

                                                 <!-- <span>{{$memo_det->weight}}g</span> -->

                                                 @endif

                                               @if($memo_det->listing_type=='Bezel')

                                                  <span>{{$name}}:{{$memo_det->model}}-{{$memo_det->weight}}g</span>

                                                 <!-- <span>{{$memo_det->model}}-</span> -->

                                                 <!-- <span>{{$memo_det->weight}}g</span> -->

                                                @endif

                                              @if($memo_det->listing_type=='Dial')

                                                  <span>{{$name}}:{{$memo_det->model}}-{{ $memo_det->metal }}</span>

                                                 <!-- <span>{{$memo_det->model}}-</span> -->

                                                 <span>{{ $memo_det->metal }}</span>

                                                @endif

                                           </td>



                                           <td style="font-size:10px;text-align: center;padding: 2px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;" valign="top">

                                           @php

                                           if($memo_det->item_status==1 || $memo_det->item_status==0)

                                           {

                                               echo "Memo";

                                           }

                                           elseif($memo_det->item_status==2)

                                           {

                                               echo "INVOICE";

                                           }

                                           elseif($memo_det->item_status==3)

                                           {

                                               echo "RETURN";

                                           }

                                           elseif($memo_det->item_status==4)

                                           {

                                               echo "TRADE";

                                           }

                                           elseif($memo_det->item_status==5)

                                           {

                                               echo "VOID";

                                           }

                                           elseif($memo_det->item_status==6)

                                           {

                                               echo "TRADE NGD";

                                           }

                                           @endphp





                                           </td>



                                           <td style="font-size:10px;text-align: center;padding: 2px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;" valign="top">

                                             {{ date('m/d/Y', strtotime($memo->date)) }}

                                           </td>



                                           <td style="font-size:10px;text-align: center;border-bottom: 1px solid #000000;border-right: 1px solid #000000;" valign="top">

                                               {{$memo_det->product_qty}}

                                               <?php $total_quantity=$memo_det->product_qty+$total_quantity; ?>

                                           </td>



                                           <td style="font-size:10px;text-align: center;border-bottom: 1px solid #000000;border-right: 1px solid #000000;" valign="top">

                                                       {{ money_format("%(#10n", $memo_det->product_price)}}

                                           </td>



                                           <td style="font-size:10px;text-align: center;border-bottom: 1px solid #000000;border-right: 1px solid #000000;" valign="top">

                                                 {{ money_format("%(#10n", $memo_det->row_total)}}

                                           </td>



                                       </tr>

                                       @endforeach

                               </tbody>

                           </table>

                       </td>

                   </tr>

                           <tr>

                               <td colspan="7">

                                   <table style="width: 100%;border: 1px solid #000000;border-collapse: collapse;">

                                       <tbody>

                                           <tr>

                                               <td style="font-size: 10px;text-align: center;font-weight: bold;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Notes</td>

                                               <td colspan="2" style="font-size: 10px;text-align: center;font-weight: bold;border-bottom: 1px solid #000000;border-right: 1px solid #000000;"></td>

                                           </tr>



                                           <tr>

                                           <td style="padding: 5px;font-size: 10px;text-align: left;width: 63.95%;font-size: 10px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;" rowspan="5" valign="top">

                                              {{$memo->notes}}<br><br>

                                           </td>

                                           <td style="padding: 5px;font-size: 10px;text-align: center;width: 21%;text-align: left;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">SUB TOTAL</td>

                                           <td style="font-size: 10px;text-align: right;padding: 2px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">

                                               {{ money_format("%(#10n", $memo->sub_total)}}</td>

                                           </tr>

                                           <tr>

                                           <td style="padding: 5px;font-size: 10px;text-align: center;width: 21%;text-align: left;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">SALES TAX</td>

                                           <td style="font-size: 10px;text-align: right;padding: 2px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">

                                           @php
                                                $saltx=(float)$sale_tax->SaleTax * (float)$memo->sub_total;
                                            if($memo_det->item_status==2)

                                                {
                                                    $saltotal=(float)$sale_tax->SaleTax * (float)$memo->sub_total/100;

                                                echo  money_format("%(#10n", $saltotal);

                                                }

                                                else

                                                {

                                                    $s_tax=(float)$memo->sale_tax;

                                                    echo money_format("%(#10n",$s_tax );

                                                }

                                            @endphp



                                           </td>

                                           </tr>

                                           <tr>

                                           <td style="padding: 5px;font-size: 10px;text-align: center;width: 21%;text-align: left;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">SHIPPING</td>

                                           <td style="font-size: 10px;text-align: right;padding: 2px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">

                                                {{ money_format("%(#10n", $memo->shipping_charges)}}

                                           </td>

                                           </tr>

                                           <tr>

                                           <td style="padding: 5px;font-size: 10px;text-align: center;width: 21%;text-align: left;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">TOTAL QTY</td>

                                           <td style="font-size: 10px;text-align: right;padding: 2px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">

                                             {{ $total_quantity}}

                                               </td>

                                           </tr>

                                           <tr>

                                               @php

                                               
                                                if(isset($saltotal))
                                                {
                                                    $total=$saltotal+$total_quantity *$memo->order_total;
                                                }
                                                else
                                                {
                                                    $total=$total_quantity *$memo->order_total;
                                                }
                                               @endphp

                                           <td style="padding: 5px;font-size: 10px!important;text-align: left;font-weight: bold;width: 21%;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">TOTAL</td>

                                           <td style="font-size: 10px!important;text-align: right;font-weight: bold;padding: 3px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">

                                             <p style="font-size:10px;"> {{ money_format("%(#10n", $total)}}</p>

                                               </td>

                                           </tr>







                                   </tbody>

                               </table>

                           </td>

                       </tr>

                       <tr>

                        <td colspan="5">

                            <table style="width: 100%;border: 1px solid #000000;border-collapse: collapse;">

                                <tbody><tr>

                                    <td style="font-size: 10px;text-align: left;width: 63.95%; padding:2px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;"  valign="top">

                                                    <table>

                                                        <tr>

                                                            <td><b>Bank Wire/ACH Info:</b></td>

                                                        </tr>

                                                        <tr>

                                                            <td><b>Bank: </b>Wells Fargo</td>

                                                        </tr>

                                                        <tr>

                                                            <td><b>Payable To: </b>GCI Jewelry and/or Shak Corp</td>

                                                        </tr>

                                                        <tr>

                                                            <td><b>Account: </b>5561495648</td>

                                                        </tr>

                                                        <tr>

                                                            <td><b>Dir. Dep./ACH: </b>121042882</td>

                                                        </tr>

                                                        <tr>

                                                            <td><b>Wire transfers: </b>121000248</td>

                                                        </tr>

                                                    </table>

                                                </td>

                                    <td></td>

                                    <td style="font-size: 10px;text-align: center;width: 63.95%;border-bottom: 1px solid #000000;border-right: 1px solid #000000;" valign="bottom">

                                        <br><br>

                                        <p>SIGNATURE</p>

                                    </td>

                                </tr>

                            </tbody></table>

                        </td>

                    </tr>

                </tbody></table>
