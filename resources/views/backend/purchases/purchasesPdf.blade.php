<div class="box-content">
    <table width="100%" style="width: 100%;" border="0">
        <tbody>
            <tr>
                <td style="width:  60%;">
                    <img src="{{$imgbase64}}" style="width: 350px;">
                </td>
                <td style="width: 10%;">
                </td>
                <td style="width:  30%;">
                    <h4 style="font-weight: bold;text-decoration:underline">Purchase</h4>
                    <p>Purchase No :{{$PurchasesProduct->vendor_doc_number}} </p>
                    <p>DATE : {{$PurchasesProduct->dop}} </p>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <table style="width: 100%;border: 1px solid #000000;border-collapse: collapse;">
                        <tbody>
                            <tr>
                                <td style="text-align: center;width:  50%;font-weight: bold;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Bill From</td>
                                <td style="text-align: center;width:  50%;font-weight: bold;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Receive From</td>
                            </tr>
                            <tr>
                                <td style="width:  50%;border: 1px solid black;padding-left: 8px;font-size: 10px;" valign="top">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>{{$PurchasesProduct->supplienName}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{$PurchasesProduct->supplienaddress}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{$PurchasesProduct->suppliencountry}},{{$PurchasesProduct->suppliencity}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{$PurchasesProduct->supplienphone}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{$PurchasesProduct->supplienemail}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td style="width:  50%;border: 1px solid black;padding-left: 8px;font-size: 10px;" valign="top">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>{{$PurchasesProduct->supplienName}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{$PurchasesProduct->supplienaddress}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{$PurchasesProduct->suppliencountry}},{{$PurchasesProduct->suppliencity}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{$PurchasesProduct->supplienphone}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{$PurchasesProduct->supplienemail}}</td>
                                            </tr>
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
                                        The merchandise described above remains the property of <b>GCI JEWELRY</b>, until paid for, and all such merchandise is subject to <b>GCI JEWELRY\'S</b> order and control and shall be returned to it immediately upon demand. Until such merchandise is returned to <b>GCI JEWELRY</b>, Buyer shall assume all risks of loss, damage, theft, and/or any action or non-action that will detrimentally affect the value of the merchandise.  No right or power is given to the Buyer to sell, pledge, hypothecate or otherwise dispose of this merchandise until paid for in full.  In the even that <b>GCI JEWELRY</b> should fail to receive payment when due, <b>GCI JEWELRY</b> may, at its option, charge interest on the monies owed at the highest rate allowed by law. The parties acknowledge the contract made by this Agreement was made in California and agree that any action brought to enforce any of the terms hereof may only be brought in the County of Los Angeles, state of California. If either party brings actions to declare their rights under or to enforce this Agreement, the prevailing party shall be entitled to be paid reasonable attorney’s fees by the losing party.
                                    </p>
                                    <h5 style="text-align: center;font-weight: bold;margin: 3px 0 3px;font-size: 10px;">PERSONAL GUARANTY</h5>
                                    <p style="margin: 0 0 5px;">
                                        I agree in my individual capacity as well as on behalf of the Buyer, to jointly and severally pay to <b>GCI JEWELRY</b>, all indebtedness of the Buyer (whether a corporation, partnership, or otherwise) at any time arising under or relating to any purchases or merchandise delivered by consignment or creation of bailment. I further agree that I shall be fully bound to this personal guarantee and any future personal guarantees executed by myself, or any of my employees or agents that sign on behalf of the Buyer for purchase of merchandise from <b>GCI JEWELRY</b>.  As guarantor I waive (i) presentment, demand, notice, protest, notice of protest, and notice of nonpayment; (ii) any defense arising by reason of any defense of Buyer or other guarantor; and (iii) the right to require <b>GCI JEWELRY</b>, to proceed against Buyer or any other guarantor to pursue any remedy in connection with the guaranteed indebtedness, or to notify guarantor of any additional indebtedness incurred by the customer, or of any changes in the Buyer’s financial condition. I also authorize <b>GCI JEWELRY</b>, without notice or prior consent, to (i) extend, modify, compromise, accelerate, renew of increase, or otherwise change the terms of the guaranteed indebtedness; (ii) proceed against one or more guarantors without proceeding against the Buyer or another guarantor; and (iii) release or substitute any party to indebtedness or this guarantee. I agree (i) I will pay costs and reasonable attorneys fees in enforcing this guarantee; (ii) this guarantee is made in California and will be governed by California law; (iii) this guarantee shall benefit GCI JEWELRY, and it’s successors and assigns, and (iv) an electronic facsimile or email of my signature, in any capacity, may be used as evidence of my agreement to the terms of this guaranty. If any portion of the terms and conditions are held invalid as a result of the law, such portion shall be deleted, and the remaining terms and conditions shall remain enforceable.
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
                            <tbody>
                                <tr>
                                   <td style="font-size: 11px;text-align: center;font-weight: bold;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">REFERENCE</td>
                                   <td style="font-size: 11px;text-align: center;font-weight: bold;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">PAYMENT</td>
                                   <td style="font-size: 11px;text-align: center;font-weight: bold;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">TRACKING</td>
                                   <td style="font-size: 11px;text-align: center;font-weight: bold;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">DUE DATE</td>
                                </tr>
                                <tr>
                                   <td style="font-size: 11px;text-align: center;height: 13px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">
                                    </td>
                                   <td style="font-size: 11px;text-align: center;height: 13px; border-bottom: 1px solid #000000;border-right: 1px solid #000000;">
                                    </td>
                                   <td style="font-size: 11px;text-align: center;height: 13px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">
                                    </td>
                                   <td style="font-size: 11px;text-align: center;height: 13px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" valign="top">
                        <table style="width: 100%;border: 1px solid #000000;border-collapse: collapse;">
                            <tbody>
                                <tr>
                                   <td style="font-size:10px;text-align: center;font-weight: bold;width: 10%;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">ID</td>
                                   <td style="font-size:10px;text-align: center;font-weight: bold;width: 34%;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">DESCRIPTION</td>
                                   <td style="font-size:10px;text-align: center;font-weight: bold;width: 10%;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">STATUS</td>
                                   <td style="font-size:10px;text-align: center;font-weight: bold;width: 10%;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">DATE</td>
                                   <td style="font-size:10px;text-align: center;font-weight: bold;width: 7%;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">QTY</td>
                                   <td style="font-size:10px;text-align: center;font-weight: bold;width: 14%;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">UNIT PRICE</td>
                                   <td style="font-size:10px;text-align: center;font-weight: bold;border-bottom: 1px solid #000000;">TOTAL</td>
                                </tr>
                                <?php $total_quantity=0; ?>
                                    <tr>
                                        <td style="font-size:10px;text-align: center;border-bottom: 1px solid #000000;border-right: 1px solid #000000;" valign="top">
                                        {{$PurchasesProduct->stock_id}}
                                        </td>
                                        <td style="font-size:10px;text-align: left;padding: 1px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;" valign="top">
                                        <?php
                                        $model=""; $sku=""; $weight=""; $custom_6=""; $paper_cart=""; $custom_3=""; $custom_4=""; $custom_5=""; 
                                        if(!empty($PurchasesProduct->model))
                                        {
                                            $model=$PurchasesProduct->model.'-';
                                        }
                                        if(!empty($PurchasesProduct->sku))
                                        {
                                            $sku=$PurchasesProduct->sku.'-';
                                        }
                                        if(!empty($PurchasesProduct->weight))
                                        {
                                            $weight=$PurchasesProduct->weight.'-';
                                        }
                                        if(!empty($PurchasesProduct->custom_6))
                                        {
                                            $custom_6=$PurchasesProduct->custom_6.'-';
                                        }
                                        if(!empty($PurchasesProduct->paper_cart))
                                        {
                                            $paper_cart=$PurchasesProduct->paper_cart.'-';
                                        }
                                        if(!empty($PurchasesProduct->custom_3))
                                        {
                                            $custom_3=$PurchasesProduct->custom_3.'-';
                                        }
                                        if(!empty($PurchasesProduct->custom_4))
                                        {
                                            $custom_4=$PurchasesProduct->custom_4.'-';
                                        }
                                        if(!empty($PurchasesProduct->custom_5))
                                        {
                                            $custom_5=$PurchasesProduct->custom_5.'-';
                                        }
                                        

                                        ?>
                                        <span>{{$model}}{{$sku}}{{$weight}}g{{$custom_6}}{{$paper_cart}}{{$custom_3}}{{$custom_4}}{{$custom_5}}
                                          </span>
                                        </td>
                                        <td style="font-size:10px;text-align: center;padding: 2px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;" valign="top">
                                        @php
                                            if($PurchasesProduct->item_status==1 || $PurchasesProduct->item_status==0)
                                            {
                                                echo "Memo";
                                            }
                                            elseif($PurchasesProduct->item_status==2)
                                            {
                                                echo "INVOICE";
                                            }
                                            elseif($PurchasesProduct->item_status==3)
                                            {
                                                echo "RETURN";
                                            }
                                            elseif($PurchasesProduct->item_status==4)
                                            {
                                                echo "TRADE";
                                            }
                                            elseif($PurchasesProduct->item_status==5)
                                            {
                                                echo "VOID";
                                            }
                                            elseif($PurchasesProduct->item_status==6)
                                            {
                                                echo "TRADE NGD";
                                            }

                                            @endphp
                                        </td>
                                        <td style="font-size:10px;text-align: center;padding: 2px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;" valign="top">
                                     
                                        </td>
                                        <td style="font-size:10px;text-align: center;border-bottom: 1px solid #000000;border-right: 1px solid #000000;" valign="top">
                                        {{$PurchasesProduct->qty}}
                                        </td>
                                        <td style="font-size:10px;text-align: center;border-bottom: 1px solid #000000;border-right: 1px solid #000000;" valign="top">
                                        {{money_format("%(#1n",$PurchasesProduct->unit_price)."\n"}}
                                        </td>
                                        <td style="font-size:10px;text-align: center;border-bottom: 1px solid #000000;border-right: 1px solid #000000;" valign="top">
                                        @php $total=$PurchasesProduct->qty*$PurchasesProduct->unit_price @endphp
                                        {{money_format("%(#1n",$total)."\n"}}
                                        </td>
                                    </tr>
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
                                              notes<br><br>
                                           </td>

                                           <td style="padding: 5px;font-size: 10px;text-align: center;width: 21%;text-align: left;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">SUB TOTAL</td>

                                           <td style="font-size: 10px;text-align: right;padding: 2px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">

                                           {{money_format("%(#1n",$PurchasesProduct->unit_price)."\n"}}

                                            </td>

                                           </tr>

                                           <tr>

                                           <td style="padding: 5px;font-size: 10px;text-align: center;width: 21%;text-align: left;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">SALES TAX</td>

                                           <td style="font-size: 10px;text-align: right;padding: 2px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">

                                          
                                           @php
                                                $saltx=(float)$sale_tax->SaleTax * (float)$PurchasesProduct->unit_price;
                                            if($PurchasesProduct->item_status==2)

                                                {
                                                    $saltotal=(float)$sale_tax->SaleTax * (float)$PurchasesProduct->unit_price/100;

                                                echo  money_format("%(#10n", $saltotal);

                                                }

                                                else

                                                {
                                                    echo "$00.00";

                                                }

                                            @endphp
                                           

                                           </td>

                                           </tr>

                                           <tr>

                                           <td style="padding: 5px;font-size: 10px;text-align: center;width: 21%;text-align: left;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">SHIPPING</td>

                                           <td style="font-size: 10px;text-align: right;padding: 2px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">

                                           

                                           </td>

                                           </tr>

                                          

                                           <tr>

                                              

                                           <td style="padding: 5px;font-size: 10px!important;text-align: left;font-weight: bold;width: 21%;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">TOTAL</td>

                                           <td style="font-size: 10px!important;text-align: right;font-weight: bold;padding: 3px;border-bottom: 1px solid #000000;border-right: 1px solid #000000;">
                                           @php                                                                                       
                                                if(isset($saltotal))
                                                {
                                                    $total=$saltotal+$PurchasesProduct->unit_price ;
                                                }
                                                else
                                                {
                                                    $total=$PurchasesProduct->unit_price;
                                                }
                                                @endphp

     


                                             <p style="font-size:10px;"> 
                                             {{money_format("%(#10n",$total )}}
                                         

                                            </p>

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

