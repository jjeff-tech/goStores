
<!-- Credit card info -->
<div class="payment_right_container">
    <ul>
        <li>

          <h3>Enter Card details</h3>

            <div class="payment_right_item">
                <div class="small right_text l_float">
			Payment Method<span class="mandred">*</span>&nbsp;&nbsp;&nbsp;
                </div>
                <div class="large l_float left_text">
                    <select name="paymentmethod_paypalpro" id="paymentmethod_paypalprodomain" class="width2">
                        <?php
                        foreach (PageContext::$response->creditcard as $key => $value) { ?>
                        <option value="<?php echo $key;?>"><?php echo $value;?></option>
                            <?php   } ?>

                    </select>
                </div>
                <div class="clear"></div>
            </div>

            <div class="payment_right_item">
                <div class="small right_text l_float">
			Card Number<span class="mandred">*</span>&nbsp;&nbsp;&nbsp;
                </div>
                <div class="large l_float left_text">
                    <input type="text" id="ccno1_paypalprodomain" name="ccno1_paypalpro" maxlength="16" class="width1">
                </div>
                <div class="clear"></div>
            </div>

            <div class="payment_right_item">
                <div class="small right_text l_float">
			Expiry Date(MM/YYYY)<span class="mandred">*</span>&nbsp;&nbsp;&nbsp;
                </div>
                <div class="large l_float left_text">
                    <select name="expM_paypalpro" id="expM1_paypalprodomain" class="width2">

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
                    <select name="expY_paypalpro" id="expY1_paypalprodomain" class="width2">

                        <?php for($i=date('Y'); $i<=(date('Y')+50); $i++) { ?>
                        <option>
                                <?php echo $i;?>
                        </option>
                            <?php                                             } ?>

                    </select>
                </div>
                <div class="clear"></div>
            </div>
         <!---   <input type="hidden" name="productId" value="<?php// echo $this->productid;?>" id="productId">-->
            <div class="payment_right_item">
                <div class="small right_text l_float">
			CVV/CVV2 No.<span class="mandred">*</span>&nbsp;&nbsp;&nbsp;
                </div>
                <div class="large l_float left_text">
                    <input type="text" id="cvv1_paypalprodomain" name="cvv1_paypalpro" maxlength="4" class="width2">&nbsp;&nbsp;<!--<a href="#">Where do I find this?</a>-->
                </div>
                <div class="clear"></div>
            </div>
            <div class="payment_right_item">
                <div class="small right_text l_float">
			&nbsp;&nbsp;&nbsp;
                </div>
                <div class="large l_float left_text" style="width: auto;">
                    <input type="button" class="button_orange2 jqBackToDomain" name="back_btn" value="BACK" />
                    <input type="submit" class= "button_orange2" name="Submit" value="PAY NOW" id="jqRegisterDomain"><div class="jqShowPaymentprocess1 loader-form" style="z-index: 9999;margin-top: 9px;"><img src="<?php echo IMAGE_URL; ?>loadder1.gif"></div>
                </div>
                <div class="clear"></div>
            </div>


        </li>

    </ul>
    <div class="clear"></div>
</div>
<!-- Credit card info ends -->