<table cellspacing="4" cellpadding="0" border="0" width="99%" class="card_options">
    <tr>
        <?php
        //  echopre(PageContext::$response->paymnetsEnabled);
        $paymentCount = 0;
        if(isset (PageContext::$response->paymnetsEnabled['paypal_enable']) && PageContext::$response->paymnetsEnabled['paypal_enable'] == 'Y') {
            $paymentCount++;
            ?>
        <td>
            <input type="image" src="<?php echo BASE_URL; ?>project/styles/images/paypal_logo.png" height="75" title="Paypal" width="100" onclick="setcurrentPayment('paypal')">
        </td>
            <?php
        }
        if($paymentCount % 5 ==0)
            echo '</tr><tr>';

        if(isset (PageContext::$response->paymnetsEnabled['paypalpro_enable']) && PageContext::$response->paymnetsEnabled['paypalpro_enable'] == 'Y') {
            $paymentCount++;
            ?>
        <td><img src="<?php echo BASE_URL; ?>project/styles/images/paypal_pro_logo.png" height="75" width="100" title="Paypalpro" onclick="showpaymentOption('paypalpro')"></td>
            <?php
        }
        if($paymentCount % 5 ==0)
            echo '</tr><tr>';
        if(isset (PageContext::$response->paymnetsEnabled['paypalflow_enable']) && PageContext::$response->paymnetsEnabled['paypalflow_enable'] == 'Y') {
            $paymentCount++;

            ?>
        <td><img src="<?php echo BASE_URL; ?>project/styles/images/paypal_payflow_logo.png" height="75" width="100" title="Paypalflow" onclick="showpaymentOption('paypalflow')"></td>
            <?php
        }
        if($paymentCount % 5 ==0)
            echo '</tr><tr>';
        if(isset (PageContext::$response->paymnetsEnabled['paypaladvanced_enable']) && PageContext::$response->paymnetsEnabled['paypaladvanced_enable'] == 'Y') {
            $paymentCount++;

            ?>
        <td>
           <!--<img src="<?php echo BASE_URL; ?>project/styles/images/2co_logo_payment.png" height="75" width="100">-->
            <input type="image" src="<?php echo BASE_URL; ?>project/styles/images/paypal_adv_logo.png" height="75" title="Paypaladvance" width="100" onclick="setcurrentPayment('paypaladvanced')">
        </td>
            <?php
        }
        if($paymentCount % 5 ==0)
            echo '</tr><tr>';
        if(isset (PageContext::$response->paymnetsEnabled['paypalexpress_enable']) && PageContext::$response->paymnetsEnabled['paypalexpress_enable'] == 'Y') {
            $paymentCount++;

            ?>
        <td>
           <!-- <img src="<?php echo BASE_URL; ?>project/styles/images/btn_xpressCheckout.gif" height="75" width="100">-->
            <input type="image" src="<?php echo BASE_URL; ?>project/styles/images/paypalcheckout_logo.png" height="75" title="Paypalexpress" width="100" onclick="setcurrentPayment('paypalxpress')">
        </td>
            <?php
        }
        if($paymentCount % 5 ==0)
            echo '</tr><tr>';
        if(isset (PageContext::$response->paymnetsEnabled['paypalflowlink_enable']) && PageContext::$response->paymnetsEnabled['paypalflowlink_enable'] == 'Y') {
            $paymentCount++;
            ?>
        <td>
          <!--  <img src="<?php echo BASE_URL; ?>project/styles/images/paylinkicon.jpg" height="75" width="100">-->
            <input type="image" src="<?php echo BASE_URL; ?>project/styles/images/payment_flowlink_logo.png" height="75" width="100" title="Paypalflowlink" onclick="setcurrentPayment('paypalflowlink')">
        </td>
            <?php
        }
        if($paymentCount % 5 ==0)
            echo '</tr><tr>';
        ?>

        <?php
        if(isset (PageContext::$response->paymnetsEnabled['ogone_enable']) && PageContext::$response->paymnetsEnabled['ogone_enable'] == 'Y') {
            $paymentCount++;
            ?>
        <td>
            <!--<img src="<?php echo BASE_URL; ?>project/styles/images/logo_ogone.jpg" height="75" width="100">-->
            <input type="image" src="<?php echo BASE_URL; ?>project/styles/images/ogone_logo.png" height="75" width="100" title="Ogone" onclick="setcurrentPayment('ogone')">
        </td>
            <?php
        }
        if($paymentCount % 5 ==0)
            echo '</tr><tr>';
        ?>
        <?php
        if(isset (PageContext::$response->paymnetsEnabled['authorize_enable']) && PageContext::$response->paymnetsEnabled['authorize_enable'] == 'Y') {
            $paymentCount++;
            ?>
        <td><img src="<?php echo BASE_URL; ?>project/styles/images/authorizenet_logo.png" height="75" width="100" title="Authorize" onclick="showpaymentOption('authorize')"></td>
            <?php
        }
        if($paymentCount % 5 ==0)
            echo '</tr><tr>';
        if(isset (PageContext::$response->paymnetsEnabled['twoco_enable']) && PageContext::$response->paymnetsEnabled['twoco_enable'] == 'Y') {
            $paymentCount++;

            ?>
        <td>
           <!--<img src="<?php echo BASE_URL; ?>project/styles/images/2co_logo_payment.png" height="75" width="100">-->
            <input type="image" src="<?php echo BASE_URL; ?>project/styles/images/2checkout_logo.png" height="75" width="100" title="Twocheckout" onclick="setcurrentPayment('twocheckout')">
        </td>
            <?php
        }
        if($paymentCount % 5 ==0)
            echo '</tr><tr>';
        if(isset (PageContext::$response->paymnetsEnabled['braintree_enable']) && PageContext::$response->paymnetsEnabled['braintree_enable'] == 'Y') {
            $paymentCount++;

            ?>
        <td>
           <!--<img src="<?php echo BASE_URL; ?>project/styles/images/2co_logo_payment.png" height="75" width="100">-->
            <input type="image" src="<?php echo BASE_URL; ?>project/styles/images/braintree_logo.png" height="75" width="100" title="Braintree" onclick="setcurrentPayment('braintree')">
        </td>
            <?php
        }
        if($paymentCount % 5 ==0)
            echo '</tr><tr>';
        if(isset (PageContext::$response->paymnetsEnabled['googlecheckout_enable']) && PageContext::$response->paymnetsEnabled['googlecheckout_enable'] == 'Y') {
            $paymentCount++;

            ?>
        <td>

            <input type="image" src="<?php echo BASE_URL; ?>project/styles/images/googlecheckout_logo.png" height="75" width="100" title="Google Check Out" onclick="setcurrentPaymnet('googlecheckout')">
        </td>
            <?php }

        if($paymentCount % 5 ==0)
            echo '</tr><tr>';
        if(isset (PageContext::$response->paymnetsEnabled['yourpay_enable']) && PageContext::$response->paymnetsEnabled['yourpay_enable'] == 'Y') {
            $paymentCount++;

            ?>
        <td>

            <input type="image" src="<?php echo BASE_URL; ?>project/styles/images/firstdata_logo.png" height="75" width="100" title="Your Pay" onclick="showpaymentOption('yourpay')">
        </td>
            <?php }
        if($paymentCount % 5 ==0)
            echo '</tr><tr>';
        if(isset (PageContext::$response->paymnetsEnabled['moneybookers_enable']) && PageContext::$response->paymnetsEnabled['moneybookers_enable'] == 'Y') {
            $paymentCount++;

            ?>
        <td>

            <input type="image" src="<?php echo BASE_URL; ?>project/styles/images/moneybookers_logo.png" height="75" width="100" title="Money Bookers" onclick="setcurrentPayment('moneybookers')">
        </td>
            <?php }
        if($paymentCount % 5 ==0)
            echo '</tr><tr>';
        if(isset (PageContext::$response->paymnetsEnabled['quickbook_enable']) && PageContext::$response->paymnetsEnabled['quickbook_enable'] == 'Y') {
            $paymentCount++;

            ?>
        <td>

            <input type="image" src="<?php echo BASE_URL; ?>project/styles/images/quickbooks_logo.png" height="75" width="100" title="Quickbook" onclick="showpaymentOption('quickbook')">
        </td>
            <?php }
        ?>

    </tr>
    <tr>
        <td colspan="5" class="cls_payment">
            <input type="hidden" name="paymentOption" id="paymentOption" value="">
            <div id="insetPayment" class="allpayment" style="display:none;">
                <!-- Personal Info Container -->
				<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="noborder">
				  <tr>
					<td valign="top" align="left">
					<ul>
                        <li>
                            <h3>Please enter your billing info</h3>
                            <div class="">
                                <div class="payment_form">

                                    <table border="0" cellpadding="0" width="100%" align="center" class="noborder">

                                        <tr><td><tr>

                                            <td align="left" width="32%" valign="center">First Name<span class="mandred">*</span> </td>
                                            <td align="left"><input type="text" id="fname" name="fname" value="<?php echo isset(PageContext::$response->cardDetails->vFirstName) ? PageContext::$response->cardDetails->vFirstName : PageContext::$response->userDetails->vFirstName; ?>" maxlength="255" ></td>

                                        </tr>

                                        <tr>

                                            <td align="left" valign="center">Last Name<span class="mandred">*</span></td>
                                            <td><input type="text" id="lname" name="lname" value="<?php echo PageContext::$response->cardDetails->vLastName ? PageContext::$response->cardDetails->vLastName : PageContext::$response->userDetails->vLastName; ?>" maxlength="255" ></td>

                                        </tr>

                                        <tr>

                                            <td align="left" valign="center">Email<span class="mandred">*</span></td>
                                            <td><input type="text" id="email" name="email" value="<?php echo PageContext::$response->cardDetails->vEmail ? PageContext::$response->cardDetails->vEmail : PageContext::$response->userDetails->vEmail; ?>" maxlength="255" ></td>

                                        </tr>

                                        <tr>

                                            <td align="left" valign="center">Address<span class="mandred">*</span></td>
                                            <td><input type="text" id="add1" name="add1" value="<?php echo PageContext::$response->cardDetails->vAddress ? PageContext::$response->cardDetails->vAddress : PageContext::$response->userDetails->vAddress; ?>" maxlength="255" ></td>

                                        </tr>
                                        <tr>

                                            <td align="left" valign="center">City<span class="mandred">*</span></td>
                                            <td><input type="text" id="city" name="city" value="<?php echo PageContext::$response->cardDetails->vCity ? PageContext::$response->cardDetails->vCity : PageContext::$response->userDetails->vCity; ?>" maxlength="255" ></td>

                                        </tr>
                                        <tr>

                                            <td align="left" valign="center">State<span class="mandred">*</span></td>
                                            <td><input type="text" id="state" name="state" value="<?php echo PageContext::$response->cardDetails->vState ? PageContext::$response->cardDetails->vState : PageContext::$response->userDetails->vState; ?>" maxlength="255"></td>

                                        </tr>
                                        <tr>

                                            <td align="left" valign="center">ZIP<span class="mandred">*</span></td>
                                            <td><input type="text" id="zip" name="zip" value="<?php echo PageContext::$response->cardDetails->vZipcode ? PageContext::$response->cardDetails->vZipcode : PageContext::$response->userDetails->vZipcode; ?>" maxlength="20"></td>

                                        </tr>
                                        <tr>

                                            <td align="left" valign="center">Country<span class="mandred">*</span></td>

                                            <td>
                                                <select name="country" id="country" >
                                                    <option value="">Select Country</option>
                                                    <?php
                                                    $selectedCountry = stripslashes(PageContext::$response->cardDetails->vCountry ? PageContext::$response->cardDetails->vCountry : PageContext::$response->userDetails->vCountry);
                                                    if ($selectedCountry == "")
                                                        $countryKey = "US";
                                                    else
                                                        $countryKey = $selectedCountry;
                                                    global $countries;
                                                    foreach ($countries as $key => $value) {
                                                        ?>
                                                    <option value="<?php echo $key ?>" <?php if ($key == $countryKey || $value == PageContext::$response->cardDetails->vCountry || PageContext::$response->userDetails->vCountry)
                                                                    echo "selected"; ?>><?php echo $value; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                    <option value="undefined">undefined</option>
                                                </select>

                                            </td>

                                        </tr>
                                        
                                        

                                        


                                    </table>

                                </div>

                            </div>

                        </li>
                    </ul>
					</td>
					<td width="1%" class="r_brdr">
					&nbsp;
					</td>
					<td width="1%">
					&nbsp;
					</td>

					<td valign="top" align="left">
					<div class="payment_right_container">
                        <ul>
                            <li>
                                <h3>Enter Card details</h3>
								<br><br>
                                <div class="payment_right_item" id="paymentMethod" style="display:none;">
                                    <div class="small right_text l_float">
                                        Payment Method<span class="mandred">*</span>&nbsp;&nbsp;&nbsp;
                                    </div>
                                    <div class="large l_float left_text">
                                        <select name="paymentmethod_paypalpro" id="paymentmethod_paypalpro" class="width2">
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
                                        <input type="text" id="ccno" name="ccno" maxlength="16" class="width1">
                                    </div>
                                    <div class="clear"></div>
                                </div>

                                <div class="payment_right_item">
                                    <div class="small right_text l_float">
			Expiry Date(MM/YYYY)<span class="mandred">*</span>&nbsp;&nbsp;&nbsp;
                                    </div>
                                    <div class="large l_float left_text">
                                        <select name="expM" id="expM" class="width2">

                                            <?php for($i=1; $i<=12; $i++) { ?>
                                            <option>
                                                    <?php if($i<10) {
                                                        echo '0'.$i;
                                                    }else {
                                                        echo $i;
                                                    }?>
                                            </option>
                                                <?php  } ?>

                                        </select>
                                        <select name="expY" id="expY" class="width2">
                                            <?php
                                            $pyear	=	date("Y");
                                            $pyearvalue	=	date("y");
                                            $nyr	=	$pyear + 25;
                                            for($year=$pyear;$year<=$nyr;$year++) {
                                                ?>
                                            <option value="<?php echo $pyearvalue; ?>"><?php echo $year; ?></option>
                                                <?php
                                                $pyearvalue++;

                                            }  ?>
                                        </select>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div class="payment_right_item">
                                    <div class="small right_text l_float">
			CVV/CVV2 No.<span class="mandred">*</span>&nbsp;&nbsp;&nbsp;
                                    </div>
                                    <div class="large l_float left_text">
                                        <input type="text" id="cvv" name="cvv" maxlength="4" class="width2">&nbsp;&nbsp;<a href="http://www.cvvnumber.com/cvv.html" target="_blank">Where do I find this?</a>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div class="payment_right_item">
                                    <div class="small right_text l_float">
                                        &nbsp;&nbsp;&nbsp;
                                    </div>
                                    <div class="large l_float left_text">
                                        <input type="button" value="BACK" name="back_btn" class="button_orange2" onclick="javascript:history.go(-1);">
                                        <input type="submit"  name="btnCompleteOrderpaypro" value="Pay Now"  class="button_orange2">
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </li>
                        </ul>
                        <div class="clear"></div>
                    </div>

					</td>
				  </tr>
				</table>

             
            </div>
                <!-- End Credit Card Info Container -->
        </td>
    </tr></table>