
<div class="payment_right_container">
    <ul>
        <li>
 <h3>Enter Card details</h3>
           
            <div class="payment_right_item">
                <div class="small right_text l_float">
			Card Number<span class="mandred">*</span>&nbsp;&nbsp;&nbsp;
                </div>
                <div class="large l_float left_text">
                    <input type="text" id="ccno1_paypalflow" name="ccno1_paypalflow" maxlength="16" class="width1">
                </div>
                <div class="clear"></div>
            </div>

            <div class="payment_right_item">
                <div class="small right_text l_float">
			Expiry Date(MM/YYYY)<span class="mandred">*</span>&nbsp;&nbsp;&nbsp;
                </div>
                <div class="large l_float left_text">
                    <select name="expM_paypalflow" id="expM1_paypalflow" class="width2">

                        <?php for($i=1; $i<=12; $i++) { ?>
                        <option>
                                <?php if($i<10) {
                                    echo '0'.$i;
                                }else {
                                    echo $i;
                                }?>
                        </option>
                            <?php                         } ?>

                    </select>
                    <select name="expY_paypalflow" id="expY1_paypalflow" class="width2">

                       
                        <?php
						$pyear	=	date("Y");
                                                $pyearvalue	=	date("y");
                                               	$nyr	=	$pyear + 25;
						for($year=$pyear;$year<=$nyr;$year++){
                                                ?>
						<option value="<?php echo $pyearvalue; ?>"><?php echo $year; ?></option>
						<?php
                                                $pyearvalue++;

                                                }  ?>


                    </select>
                </div>
                <div class="clear"></div>
            </div>
          <!--  <input type="hidden" name="productId" value="<?php// echo $this->productid;?>" id="productId">-->
            <div class="payment_right_item">
                <div class="small right_text l_float">
			CVV/CVV2 No.<span class="mandred">*</span>&nbsp;&nbsp;&nbsp;
                </div>
                <div class="large l_float left_text">
                    <input type="text" id="cvv1_paypalflow" name="cvv1_paypalflow" maxlength="4" class="width2">&nbsp;&nbsp;<!--<a href="#">Where do I find this?</a>-->
                </div>
                <div class="clear"></div>
            </div>
            <div class="payment_right_item">
                <div class="small right_text l_float">
			&nbsp;&nbsp;&nbsp;
                </div>
                <div class="large l_float left_text" style="width:auto;">
                    <input type="button" value="BACK" name="back_btn" class="button_orange2 jqBackToDomain">
                   <input type="submit"  name="btnCompleteOrderpaypro" value="Pay Now"  class="button_orange2"><div class="jqShowPaymentprocess1 loader-form" style="z-index: 9999;margin-top: 9px;"><img src="<?php echo IMAGE_URL; ?>loadder1.gif"></div>
                </div>
                <div class="clear"></div>
            </div>


        </li>

    </ul>
    <div class="clear"></div>
</div>
