<?php
/*
* To change this template, choose Tools | Templates
* and open the template in the editor.
*/
?>
<script type="text/javascript">
function checksubdomstatevalue(state){
if(state == "US"){ 
$("#subdom_us_states").show();
$("#subdom_non_us_states").hide();
}else{
$("#subdom_us_states").hide();
$("#subdom_non_us_states").show();
}
}

function checkdomstatevalue(state){
if(state == "US"){      
$("#dom_us_states").show();
$("#dom_us_province").show();
$("#dom_us_na").show();
$("#dom_non_us_states").hide();
}else{
$("#dom_us_states").hide();
$("#dom_us_province").hide();
$("#dom_us_na").hide();
$("#dom_non_us_states").show();
}
}
</script>
<div class="content_area_inner paynow-bg">
   <div class="container"> 
  <?php if(PageContext::$response->type!='free') { ?>   
    
<!-- New HTML for payment options -->

	
<div class="payment_option1">

<div class="payment_option1_colleft tp-mg-bm">
<!--tab panel starting-->
<div class="tab-panel">
<div class="tab-panel-hd">
<div class="tab-panel-hd-left left"></div>
<div class="tab-panel-hd-mid left">
<div id="tabs_container">
<ul id="tabs">
<li id="li_tab4"><a href="#tab4"><span>1</span> Select Plan</a></li>
<li id="li_tab1" class="active"><a href="#tab1"><span>2</span> Domain Name</a></li>
<li id="li_tab3"><a href="#tab3" id="jqBillPayTab"><span>3</span> Billing & Payment</a></li>
<div class="clear"></div>
</ul>
</div>
</div>
<div class="tab-panel-hd-right left"></div>
</div>
<div id="tabs_content_container">
    
   
    
    
<div class="for_seperator">
<div id="tab4" class="tab_content">
<?php PageContext::renderPostAction('plansnippet'); ?>
</div>

<div id="tab1" class="tab_content" style="display: block;">
<div class="tab-content-left">
<div class="payment_block_content">
<ul>
<li>
<div>
<div class="formbtn"><input type="radio" class="jqOptionStyle" value="1" name="jqOptionStyle" checked></div>
<div class="information">I want to use a subdomain</div>
<div class="clear"></div>
</div>
</li>
<li id="jqSubDomainEntryBox">
<div>
<div class="formbtn"></div>
<div class="full-width">
<div class="ht-space">
<span>http://</span>
</div>
<div class="dom-txt">

<input type="text" name="txtStoreName" id="txtStoreName" class="width1">
</div>
<div class="dom-btn">
<span>.<?php echo DOMAIN_NAME ?></span>

<input type="button" value="Check Availability" id="jqCheckSubdomainExist">
</div>
</div>
<div class="clear"></div>
</div>
</li>
<?php                                    
if(PageContext::$response->domainregistration_enable == "Y"){ ?>                                    
<li>
<div>
<div class="formbtn"><input type="radio" class="jqOptionStyle" value="2" name="jqOptionStyle"></div>
<div class="information">I want to use a new domain</div>
<div class="clear"></div>
</div>
</li>

<li id="jqDomainEntryBox" style="display: none;">
<div>
<div class="formbtn"></div>
<div class="full-width">
	<div class="ht-space2">
<span>Domain :
http://www.</span>
</div>
<div class="dom-txt2">
<input name="" type="text" class="width1" id="idsld1" name="sld1" class="form-control">
</div>
<div class="dom-btn">
<select class="domain_ext" id="tld1" name="tld1">
<option>com</option>
<option>info</option>
<option>org</option>
<option>biz</option>
</select>

&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Check Availability" id="jqCheckDomainExist">
<p><i>Additional <?php echo CURRENCY_SYMBOL.Utils::formatPrice($this->domainRegPrice); ?> will be charged per year.</i></p>
</div>
</div>
<div class="clear"></div>
</div>
</li>
<?php } ?>
<li>
<div>
<div class="formbtn"><input type="radio" class="jqOptionStyle" value="3" name="jqOptionStyle"></div>
<div class="information">I want to use my existing domain</div>
<div class="clear"></div>
</div>
</li>

<li id="jqUserDomainEntryBox" style="display: none;">
<div>
<div class="formbtn"></div>
<div class="full-width">
	<div class="ht-space3">
<span>Enter Domain :
http://www.</span>
</div>
<div class="dom-txt2">
<input name="" type="text" class="width1" id="idsld2" name="sld2" class="form-control">
</div>
<div class="dom-btn3">
<select class="domain_ext" id="tld2" name="tld2">
<option>com</option>
<option>info</option>
<option>org</option>
<option>biz</option>
</select>
</div>
</div>
<div class="clear"></div>
</div>
</li>
<li>
<div id="jqShowMessage"></div>
<div id="jqChkAvailable" class="availablityText">Checking Availability <img src="<?php echo BASE_URL; ?>project/styles/images/ajax-loader3.gif"></div>
</li>
</ul>
</div>
<div class="clear"></div>
</div>

<div class="r_float ">
<div class="l_float amount_info">
Plan Selected : <span id="jqPriceDisplayarea"><?php echo $this->planName; ?>  [ <?php  echo ADMIN_CURRENCY_SYMBOL; ?><?php echo $this->planPrice; ?> ] </span>
</div>
<div class="l_float">
<input type="button" class="button_gray_big" name="back_btn" value="BACK" id="jqBackToPlan" />
<input type="submit" class="button_orange2" name="Submit" value="PROCEED" id="jqProceedToPay" />
</div>
</div>

<div class="clear"></div>
</div>
<div id="tab3" class="tab_content">
<div class="tab-review-panel">
<div class="tab-review-row">
<div class="tab-review-right">

<div class="proceed_content_box3" style="display:none;">
<form id="frmUsers" name="frmUsers" method="POST">
<div class="payment_column1">
<div class="payment_form">

<input type="hidden" name="txtStoreName" id="txtStoreNameuserfm" value="">
<div class="full-width">
	<div class="col-md-12">
<h3>Please enter your billing info</h3>
</div>
</div>
<div class="full-width">
	<div class="col-md-4 l-ht">
First Name<span class="mandred">*</span> 
</div>
<div class="col-md-8">
<input type="text" id="fname" name="fname" autocomplete="off" value="<?php echo $this->cardDetails->irstName ? $this->cardDetails->vFirstName : PageContext::$response->firstName; ?>" class="form-control">
</div>
</div>
<div class="full-width">
	<div class="col-md-4 l-ht">
		Last Name<span class="mandred">*</span>
	</div>
	<div class="col-md-8">
		<input type="text" id="lname" name="lname" autocomplete="off" value="<?php echo $this->cardDetails->vLastName ? $this->cardDetails->vLastName : PageContext::$response->lastName; ?>" class="form-control">
	</div>
</div>
<div class="full-width">
		<div class="col-md-4 l-ht">
			Email<span class="mandred">*</span>
		</div>
		<div class="col-md-8">
			<input type="text" id="email" name="email" autocomplete="off" value="<?php echo $this->cardDetails->vEmail ? $this->cardDetails->vEmail : PageContext::$response->emailAddress; ?>" class="form-control">
<span id="jqShowAccountMessage" class="domainsearchfaild" style="display:none"> Account exists with email id.Please <a class="jqLoginInnerDiv error-link" href="#">Login</a> to continue</span>
<input type="hidden" id="jqAccountExistence1" value="0">
		</div>

</div>

<div class="full-width">
	<div class="col-md-4 l-ht">
		Phone<span class="mandred">*</span>
	</div>
	<div class="col-md-8">
		<input type="text" id="phone" name="phone" autocomplete="" value="<?php echo $this->cardDetails->vPhoneNumber ? $this->cardDetails->vPhoneNumber : PageContext::$response->phone; ?>" class="form-control">
	</div>
</div>

<div class="full-width">
	<div class="col-md-4 l-ht">
		Address<span class="mandred">*</span>
	</div>
	<div class="col-md-8">
		<input type="text" id="add1" name="add1" autocomplete="off" value="<?php echo $this->cardDetails->vAddress ? $this->cardDetails->vAddress : $this->address; ?>" class="form-control">
	</div>
</div>

<div class="full-width">
	<div class="col-md-4 l-ht">
		Country<span class="mandred">*</span>
	</div>
	<div class="col-md-8">
		<select name="country" id="country" class="form-control" onchange="checksubdomstatevalue(this.value);">
<option value="">Select Country</option>
<?php
$selectedCountry = stripslashes($this->cardDetails->vCountry ? $this->cardDetails->vCountry : $this->country);
if ($selectedCountry == "")
$countryKey = "US";
else
$countryKey = $selectedCountry;
global $countries;
foreach ($countries as $key => $value) {
?>
<option value="<?php echo $key ?>" <?php if ($key == $countryKey || $value == $this->cardDetails->vCountry)
echo "selected"; ?>><?php echo $value; ?></option>
<?php
}
?>
<option value="undefined">undefined</option>
</select>
	</div>
</div>
<div id="subdom_non_us_states" style="display:none;">
State<span class="mandred">*</span>
<input type="text" autocomplete="off" id="state" name="state" value="<?php echo $this->cardDetails->vState ? $this->cardDetails->vState : $this->state; ?>" maxlength="255">
</div>
<div class="full-width">
	<div class="col-md-4 l-ht">
		State<span class="mandred">*</span>
	</div>
	<div class="col-md-8" id="subdom_us_states">
		<select name="RegistrantState" id="RegistrantState" size="1" class="form-control">
<?php
global $usStates;
foreach ($usStates as $key => $value){
?>
<option value="<?php echo $key ?>" <?php if ($key == $countryKey || strtolower($value) == strtolower($this->cardDetails->vState))
echo "selected"; ?>><?php echo $value; ?>
</option>
<?php } ?>                                                                
</select>
	</div>
</div>

<div class="full-width">
	<div class="col-md-4 l-ht">
		City<span class="mandred">*</span>
	</div>
	<div class="col-md-8">

<input type="text" id="city" name="city" autocomplete="off" value="<?php echo $this->cardDetails->vCity ? $this->cardDetails->vCity : $this->city; ?>" class="form-control">
	</div>
</div>

                                                   
<div class="full-width">
	<div class="col-md-4 l-ht">
		ZIP/Postal&nbsp;Code<span class="mandred">*</span>
	</div>
	<div class="col-md-8">
<input type="text" id="zip" name="zip" autocomplete="off" value="<?php echo $this->cardDetails->vZipcode ? $this->cardDetails->vZipcode : $this->zip; ?>" 
class="form-control">
	</div>
</div>







</div>

</div>
<div class="payment_column2">



<!-- Cart Info -->
<div class="payment_right_container">
	<div class="col-md-12">
<h3>Item Details</h3>

<ul>
<li >
<div class="payment_right_item_new tbl_hdr">
<div class="large_new l_float">
<h5>Plan Name</h5>
</div>
<div class="centernew l_float">
<h5>Billing Duration</h5>
</div>
<div class="small_new  right_text r_float_new"><h5>Amount</h5></div>
<div class="clear"></div>
</div>
<div id="services">

</div>
<input type="hidden" value="<?php echo $this->planName; ?>" id="product_name" name="product_name">
<input type="hidden" value="<?php echo $this->vBillingInterval == 'L' ? 'Lifetime' : ($this->vBillingInterval == 'Y' ? 'Yearly' : 'Monthly'); ?>" id="product_bill">
<input type="hidden" value="<?php echo $this->planPrice; ?>" id="product_price" name="ServiceAmount" class="jqServiceAmount">
<div class="payment_right_item_new">
<div class="large_new l_float">&nbsp;</div>
<div class="centernew l_float">Sub Total</div>
<div class="small_new  right_text r_float_new"><?php echo ADMIN_CURRENCY_SYMBOL;?><span class="jqSubTotal"><?php //echo Utils::formatPrice($this->planPrice); ?></span></div>
<div class="clear"></div>
</div>
<div class="payment_right_item_new">
<div class="large_new l_float">&nbsp;</div>
<div class="centernew l_float">Discounts</div>
<div class="small_new  right_text r_float_new"><?php echo ADMIN_CURRENCY_SYMBOL;?><span class="jqDiscount"><?php echo Utils::formatPrice(0); ?></span></div>
<div class="clear"></div>
</div>
<div class="payment_right_item_new hilite3">
<div class="large_new l_float">&nbsp;</div>
<div class="centernew l_float"><b>Total</b></div>
<div class="small_new  right_text r_float_new"><h5><?php echo ADMIN_CURRENCY_SYMBOL;?><span class="jqTotalPurchaseVal"><?php echo Utils::formatPrice($this->planPrice); ?></span></h5></div>
<div class="clear"></div>
</div>
</li>
</ul>
</div>
<div class="clear"></div>
</div>

<!-- Cart Info ends -->

<!-- Credit card info -->

<!-- Credit card info ends -->
<!-- Coupon info -->
<div class="payment_right_container">
<ul>
<li>

<div class="payment_right_item">
<div class="col-md-12">
<h3>Enter Coupon Code</h3>
</div>

<div class="clear"></div>
</div>



<div class="payment_right_item">
	<div class="full-width pymntnew_tbl">
		<div class="full-width">
			<div class="col-md-3">
				Coupon Code
			</div>
			<div class="col-md-6">
				<input type="text" id="couponNumber2" name="couponNumber" class="formcontrol">
			</div>
			<div class="col-md-3">
				<input type="button" name="jqCoupon" onclick="couponCodeValidation('couponNumber2')" value="APPLY" class="button_orange2 btn-orng">
			</div>
		</div>
	</div>


<div class="clear"></div>
</div>

</li>

</ul>
<div class="clear"></div>
</div>
<!-- Coupon info ends -->


<!--  <div class="payment_right_container">
<div class="payment_right_item">
<h3>Enter Card details7777777</h3></div>


<table width="100%" cellpadding="0" border="0" class="pymntnew_tbl">
<tr>

<td align="left" width="34%">Card Number</td>
<td><input type="text" id="ccno" name="ccno" maxlength="16" class="input_newstyle"></td>

</tr>


<tr>

<td align="left" >CVV</td>
<td><input type="text" id="cvv" name="cvv" maxlength="4"></td>
</tr>
<tr>

<td align="left" >Expiration Date(MM/YYYY)</td>
<td><select name="expM" id="expM" class="widh_small">

<?php for ($i = 1; $i <= 12; $i++) { ?>
<option>
<?php
if ($i < 10) {
echo '0' . $i;
} else {
echo $i;
}
?>
</option>
<?php } ?>

</select> &nbsp;

<select name="expY" id="expY" class="widh_small">

<?php for ($i = date('Y'); $i <= (date('Y') + 50); $i++) { ?>
<option>
<?php echo $i; ?>
</option>
<?php } ?>

</select>


</td>


</tr>

<tr>
<td  align="right" colspan="2" class="bordertop">
<input type="button" class="button_orange2 jqBackToDomain" name="back_btn" value="BACK" />
<input type="submit" class= "button_orange2" name="Submit" value="PAY NOW" id="jqSubDomainCreate" />
</td>


</tr>
</table>



<div class="clear"></div>
</div>-->

<div class="payment_right_container">
<div class="payment_right_item">
<div class="payment_right_item">
<div class="col-md-12">
<h3>Select Payment Option</h3>

<div class="clear"></div>
</div>
</div>
<div class="col-md-12">
<table cellspacing="4" cellpadding="0" border="0" width="100%" class="card_options">

<tr>
<?php
//  echopre(PageContext::$response->paymnetsEnabled);
$paymentCount = 0;
if(isset (PageContext::$response->paymnetsEnabled['paypal_enable']) && PageContext::$response->paymnetsEnabled['paypal_enable'] == 'Y'){
$paymentCount++;
?>
<td>
<input type="image" src="<?php echo BASE_URL; ?>project/styles/images/paypal_logo.png"  title="Paypal"  onclick="setcurrentPaymnet('paypal')">
</td>
<?php
}
if($paymentCount % 5 ==0)
echo '</tr><tr>';

if(isset (PageContext::$response->paymnetsEnabled['paypalpro_enable']) && PageContext::$response->paymnetsEnabled['paypalpro_enable'] == 'Y'){
$paymentCount++;
?>
<td><img src="<?php echo BASE_URL; ?>project/styles/images/paypal_pro_logo.png"  title="Paypalpro" onclick="showPaymentdiv('paypalpro')"></td>
<?php
}
if($paymentCount % 5 ==0)
echo '</tr><tr>';
if(isset (PageContext::$response->paymnetsEnabled['paypalflow_enable']) && PageContext::$response->paymnetsEnabled['paypalflow_enable'] == 'Y'){
$paymentCount++;

?>
<td><img src="<?php echo BASE_URL; ?>project/styles/images/paypal_payflow_logo.png"  title="Paypalflow" onclick="showPaymentdiv('paypalflow')"></td>
<?php
}
if($paymentCount % 5 ==0)
echo '</tr><tr>';
if(isset (PageContext::$response->paymnetsEnabled['paypaladvanced_enable']) && PageContext::$response->paymnetsEnabled['paypaladvanced_enable'] == 'Y'){
$paymentCount++;

?>
<td>
<!--<img src="<?php echo BASE_URL; ?>project/styles/images/2co_logo_payment.png" height="75" width="100">-->
<input type="image" src="<?php echo BASE_URL; ?>project/styles/images/paypal_adv_logo.png"  title="Paypaladvance"  onclick="setcurrentPaymnet('paypaladvanced')">
</td>
<?php
}
if($paymentCount % 5 ==0)
echo '</tr><tr>';
if(isset (PageContext::$response->paymnetsEnabled['paypalexpress_enable']) && PageContext::$response->paymnetsEnabled['paypalexpress_enable'] == 'Y'){
$paymentCount++;

?>
<td>
<!-- <img src="<?php echo BASE_URL; ?>project/styles/images/btn_xpressCheckout.gif" height="75" width="100">-->
<input type="image" src="<?php echo BASE_URL; ?>project/styles/images/paypalcheckout_logo.png"  title="Paypalexpress"  onclick="setcurrentPaymnet('paypalxpress')">
</td>
<?php
}
if($paymentCount % 5 ==0)
echo '</tr><tr>';
if(isset (PageContext::$response->paymnetsEnabled['paypalflowlink_enable']) && PageContext::$response->paymnetsEnabled['paypalflowlink_enable'] == 'Y'){
$paymentCount++;
?>
<td>
<!--  <img src="<?php echo BASE_URL; ?>project/styles/images/paylinkicon.jpg" height="75" width="100">-->
<input type="image" src="<?php echo BASE_URL; ?>project/styles/images/payment_flowlink_logo.png"  title="Paypalflowlink" onclick="setcurrentPaymnet('paypalflowlink')">
</td>
<?php
}
if($paymentCount % 5 ==0)
echo '</tr><tr>';
?>

<?php
if(isset (PageContext::$response->paymnetsEnabled['ogone_enable']) && PageContext::$response->paymnetsEnabled['ogone_enable'] == 'Y'){
$paymentCount++;
?>
<td>
<!--<img src="<?php echo BASE_URL; ?>project/styles/images/logo_ogone.jpg" height="75" width="100">-->
<input type="image" src="<?php echo BASE_URL; ?>project/styles/images/ogone_logo.png"  title="Ogone" onclick="setcurrentPaymnet('ogone')">
</td>
<?php
}
if($paymentCount % 5 ==0)
echo '</tr><tr>';
?>
<?php
if(isset (PageContext::$response->paymnetsEnabled['authorize_enable']) && PageContext::$response->paymnetsEnabled['authorize_enable'] == 'Y'){
$paymentCount++;
?>
<td><img src="<?php echo BASE_URL; ?>project/styles/images/authorizenet_logo.png"  title="Authorize" onclick="showPaymentdiv('authorize')"></td>
<?php
}
if($paymentCount % 5 ==0)
echo '</tr><tr>';
?>
<?php
if(isset (PageContext::$response->paymnetsEnabled['bluedog_enable']) && PageContext::$response->paymnetsEnabled['bluedog_enable'] == 'Y'){
$paymentCount++;
?>
<td><img src="<?php echo BASE_URL; ?>project/styles/images/bluedog-logo.png"  title="BlueDog" onclick="showPaymentdiv('bluedog')" width="150" height="150"></td>
<?php
}
if($paymentCount % 5 ==0)
echo '</tr><tr>';
if(isset (PageContext::$response->paymnetsEnabled['twoco_enable']) && PageContext::$response->paymnetsEnabled['twoco_enable'] == 'Y'){
$paymentCount++;

?>
<td>
<!--<img src="<?php echo BASE_URL; ?>project/styles/images/2co_logo_payment.png" height="75" width="100">-->
<input type="image" src="<?php echo BASE_URL; ?>project/styles/images/2checkout_logo.png"  title="Twocheckout" onclick="setcurrentPaymnet('twocheckout')">
</td>
<?php
}if($paymentCount % 5 ==0)
echo '</tr><tr>';
if(isset (PageContext::$response->paymnetsEnabled['braintree_enable']) && PageContext::$response->paymnetsEnabled['braintree_enable'] == 'Y'){
$paymentCount++;

?>
<td>
<!--<img src="<?php echo BASE_URL; ?>project/styles/images/2co_logo_payment.png" height="75" width="100">-->
<input type="image" src="<?php echo BASE_URL; ?>project/styles/images/braintree_logo.png"  title="Braintree" onclick="setcurrentPaymnet('braintree')">
</td>
<?php
}
if($paymentCount % 5 ==0)
echo '</tr><tr>';
if(isset (PageContext::$response->paymnetsEnabled['googlecheckout_enable']) && PageContext::$response->paymnetsEnabled['googlecheckout_enable'] == 'Y'){
$paymentCount++;

?>
<td>

<input type="image" src="<?php echo BASE_URL; ?>project/styles/images/googlecheckout_logo.png"  title="Google Check Out" onclick="setcurrentPaymnet('googlecheckout')">
</td>
<?php }










if($paymentCount % 5 ==0)
echo '</tr><tr>';
if(isset (PageContext::$response->paymnetsEnabled['yourpay_enable']) && PageContext::$response->paymnetsEnabled['yourpay_enable'] == 'Y'){
$paymentCount++;

?>
<td>

<input type="image" src="<?php echo BASE_URL; ?>project/styles/images/firstdata_logo.png"  title="Your Pay" onclick="showPaymentdiv('yourpay')">
</td>
<?php }
if($paymentCount % 5 ==0)
echo '</tr><tr>';
if(isset (PageContext::$response->paymnetsEnabled['moneybookers_enable']) && PageContext::$response->paymnetsEnabled['moneybookers_enable'] == 'Y'){
$paymentCount++;

?>
<td>

<input type="image" src="<?php echo BASE_URL; ?>project/styles/images/moneybookers_logo.png"  title="Money Bookers" onclick="setcurrentPaymnet('moneybookers')">
</td>
<?php }

if($paymentCount % 5 ==0)
echo '</tr><tr>';
if(isset (PageContext::$response->paymnetsEnabled['quickbook_enable']) && PageContext::$response->paymnetsEnabled['quickbook_enable'] == 'Y'){
$paymentCount++;

?>
<td>
<img src="<?php echo BASE_URL; ?>project/styles/images/quickbooks_logo.png"  title="Quickbook" onclick="showPaymentdiv('quickbook')">
<!--  <input type="image" src="<?php echo BASE_URL; ?>project/styles/images/quickbooks_logo.png"  title="Quickbook" onclick="showPaymentdiv('quickbook')">-->
</td>
<?php }
?>




</tr>

<tr>
<td colspan="5" class="cls_payment">

<div class="error_msg_container payment_conatainer_width" style="display: none;">
<h2>Sorry !</h2>
<p class="jqErrorMessage"></p>
</div>

<input type="hidden" name="currentpaymant"  id="currentpaymant" value="">
<?php
if(isset (PageContext::$response->paymnetsEnabled['paypalpro_enable']) && PageContext::$response->paymnetsEnabled['paypalpro_enable'] == 'Y'){
?>
<div id="paypalpro" class="allpayment"  style="display:none">
<?php  PageContext::renderPostAction('paypalpro','payments');?>
</div>
<?php
}
?>
<?php
if(isset (PageContext::$response->paymnetsEnabled['paypalflow_enable']) && PageContext::$response->paymnetsEnabled['paypalflow_enable'] == 'Y'){
?>
<div id="paypalflow" class="allpayment"  style="display:none">
<?php  PageContext::renderPostAction('paypalflow','payments');?>
</div>
<?php
}
?>
<?php
if(isset (PageContext::$response->paymnetsEnabled['authorize_enable']) && PageContext::$response->paymnetsEnabled['authorize_enable'] == 'Y'){
?>
<div id="authorize" class="allpayment" style="display:none;" >
<?php
PageContext::renderPostAction('authorize','payments');?>
</div>
<?php
}
?>
<?php
if(isset (PageContext::$response->paymnetsEnabled['bluedog_enable']) && PageContext::$response->paymnetsEnabled['bluedog_enable'] == 'Y'){
?>
<div id="bluedog" class="allpayment" style="display:none;" >
<?php
PageContext::renderPostAction('bluedog','payments');?>
</div>
<?php
}
?>
<div id=yourpay class="allpayment" style="display:none" >
<?php
PageContext::renderPostAction('yourpay','payments');?>
</div>


<div id="quickbook" class="allpayment" style="display:none" >
<?php
PageContext::renderPostAction('quickbook','payments');?>
</div>


</td>
</tr></table>
</div>
<div class="clear"></div>
</div>

<input type="hidden" name="totPrice" value="<?php echo $this->productPrice; ?>" id="jqTotalPrice">
<input type="hidden" name="finalPrice" value="<?php echo $this->planPrice; ?>" id="jqFinalPrice">

<input type="hidden" name="productId" value="<?php echo $this->planId; ?>" id="productId">


</div>
<div class="clear"></div>
</form>
</div>

</div>
<!--tab panel ending-->
<!-- Domain Options starts -->

<!-- Domain Options ends -->








</div>
<div class="clear"></div>
<!-- Display area for " PROCEED TO PAY "-->
</div>

<form id="jqProductPayNow" method="post" action="" >
<!-- Display area for " PROCEED TO PAY "-->
<div class="proceed_content_box2" style="display:none;">
<div class="payment_column1">

<div class="payment_form">

<!-- Display Area for Registrar information -->

<table width="100%"  border="0" cellspacing="0" cellpadding="2" align="center">
<tr><td colspan="2" style="padding-bottom: 20px;"><h3>Registrant Information</h3></td></tr>
<tr id="jqRegisterYears">
<td align="left" width="32%" valign="center" class="maintext_new">
Period&nbsp;to&nbsp;Register<span class="mandred">*</span>
</td>

<td valign="middle" align="left">
<select class="comm_input" name="NumYears" id="jqNumYears">
<option value="1">1 year</option>
<option value="2">2 years</option>
<option value="3">3 years</option>
<option value="4">4 years</option>
<option value="5">5 years</option>
<option value="6">6 years</option>
<option value="7">7 years</option>
<option value="8">8 years</option>
<option value="9">9 years</option>
<option value="10">10 years</option>                                                  </select>
</td>

</tr>
<tr>
<td  align="left"  valign="center" >First Name<span class="mandred">*</span>                                                        
</td>
<td  align="left"><input name="RegistrantFirstName" id="RegistrantFirstName" autocomplete="off" type="text" class="comm_input" value="<?php echo $_POST["RegistrantFirstName"] ? $_POST["RegistrantFirstName"] : $this->cardDetails->vFirstName; ?>" style="width:230px; "> </td>
</tr>
<tr>
<td  align="left"  valign="center"> Last Name<span class="mandred">*</span></td>
<td align="left"><input name="RegistrantLastName" id="RegistrantLastName" autocomplete="off" type="text" class="comm_input" value="<?php echo $_POST["RegistrantLastName"] ? $_POST["RegistrantLastName"] : $this->cardDetails->vLastName; ?>" style="width:230px; "></td>
</tr>
<tr>
<td align="left"  valign="center">Email&nbsp;Address<span class="mandred">*</span></td>
<td align="left"><input name="RegistrantEmailAddress" id="RegistrantEmailAddress" autocomplete="off" type="text" class="comm_input" value="<?php echo $_POST["RegistrantEmailAddress"] ? $_POST["RegistrantEmailAddress"] : $this->cardDetails->vEmail; ?>" style="width:230px; "><span id="jqShowEmailAccountMessage" class="domainsearchfaild" style="display:none"> Account exists with email id.Please <a class="jqLoginInnerDiv" href="#">Login</a> to continue</span>
<input type="hidden" id="jqRegistrantAccountExistence" value="0"></td>
</tr>
<tr>
<td align="left"  valign="center" >Job<span class="mandred">*</span></td>
<td align="left"><input name="RegistrantJobTitle" id="RegistrantJobTitle" autocomplete="off" value="<?php echo $_POST["RegistrantJobTitle"]; ?>" type="text" class="comm_input" style="width:230px; "></td>
</tr>
<tr>
<td align="left"  valign="center">Organization&nbsp;Name<span class="mandred">*</span></td>
<td align="left"><input name="RegistrantOrganizationName" id="RegistrantOrganizationName" autocomplete="off" value="<?php echo $_POST["RegistrantOrganizationName"]; ?>" type="text" class="comm_input" style="width:230px; "></td>
</tr>
<tr>
<td align="left"  valign="center" >Address1<span class="mandred">*</span></td>
<td align="left"><input name="RegistrantAddress1" id="RegistrantAddress1" type="text" class="comm_input" autocomplete="off" value="<?php echo $_POST["RegistrantAddress1"] ? $_POST["RegistrantAddress1"] : $this->cardDetails->vAddress; ?>" style="width:230px; "></td>
</tr>
<tr>
<td align="left" valign="center" >Address2</td>
<td align="left"><input name="RegistrantAddress2" id="RegistrantAddress2" type="text" class="comm_input" autocomplete="off" value="<?php echo $_POST["RegistrantAddress2"]; ?>" style="width:230px; "></td>
</tr>
<tr>
<td align="left"  valign="center">Country<span class="mandred">*</span></td>
<td align="left"> 
<select name="RegistrantCountry" id="idRegistrantCountry" class="comm_input" style="width:230px;" onchange="checkdomstatevalue(this.value);">
<?php
global $countries;
foreach ($countries as $key => $value) {
?>
<option value="<?php echo $key ?>" <?php if ($key == $countryKey || $value == $this->cardDetails->vCountry)
echo "selected"; ?>><?php echo $value; ?></option>
<?php
}
?>
<option value="undefined">undefined</option>
</select>
</td>
</tr>  
<tr id="dom_non_us_states" style="display:none;">
<td align="left" valign="center">State<span class="mandred">*</span></td>
<td><input type="text" autocomplete="off" id="RegistrantStateOther" name="RegistrantStateOther" value="<?php echo $this->cardDetails->vState ? $this->cardDetails->vState : $this->state; ?>" maxlength="255"></td>
</tr>
<tr id="dom_us_states">
<td align="left"><?php
$strStateValue = trim($_POST["RegistrantState"] ? $_POST["RegistrantState"] : $this->cardDetails->vState );
?>
US State&nbsp;&nbsp;
<input type="radio" value="S" id="radio" name="RegistrantStateProvinceChoice"
<?php
if ($_POST["RegistrantStateProvinceChoice"] == "State" || $_POST["RegistrantStateProvinceChoice"] == "") {
echo "checked";
}
?>
onClick="fnRegistrantStateSelected()">&nbsp;&nbsp;&nbsp;</td>
<td align="left"> <select name="RegistrantState" id="RegistrantState" size="1" class="comm_input" style="width:230px; ">
<?php
global $usStates;

foreach ($usStates as $key => $value) {
?>
<option value="<?php echo $key ?>" <?php if ($key == $countryKey || strtolower($value) == strtolower($this->cardDetails->vState))
echo "selected"; ?>><?php echo $value; ?></option>
<?php
}
?>
?>
</select></td>
</tr>
<tr id="dom_us_province">
<td align="left"  valign="center" >Province
<input type="radio" size="14" value="P" name="RegistrantStateProvinceChoice" id="radio2" <?php
if ($strProvinceValue != "") {
echo "checked";
}
?> onClick="fnRegistrantProvinceSelected()" >&nbsp;&nbsp;&nbsp;</td>
<td align="left"><input  type="text" class="comm_input" name="RegistrantProvince" id="RegistrantProvince" maxlength="60"
value="<?php echo $strProvinceValue; ?>" style="width:230px; "></td>
</tr>
<tr id="dom_us_na">

<td  align="left"  valign="center">Not&nbsp;Applicable
<input type="radio" value="Blank" name="RegistrantStateProvinceChoice" id="radio3" <?php
if ($strStateValue == '' && $strProvinceValue == '') {
echo "checked";
}
?> onClick="fnRegistrantNoneSelected()">&nbsp;&nbsp;&nbsp;</td>
<td valign="middle" class="maintext_new" align="left">&nbsp;&nbsp;&nbsp;(the state/province field will be left blank) <b class="red"></b></td>

</tr>
<tr>
<td align="left"  valign="center" >City<span class="mandred">*</span></td>
<td align="left"><input name="RegistrantCity" id="RegistrantCity" type="text" class="comm_input" autocomplete="off" value="<?php echo $_POST["RegistrantCity"] ? $_POST["RegistrantCity"] : $this->cardDetails->vCity; ?>" style="width:230px; "></td>
</tr>
<tr>
<td align="left"  valign="center">ZIP/Postal&nbsp;Code<span class="mandred">*</span></td>
<td align="left"><input name="RegistrantPostalCode" id="RegistrantPostalCode" autocomplete="off" value="<?php echo $_POST["RegistrantPostalCode"] ? $_POST["RegistrantPostalCode"] : $this->cardDetails->vZipcode; ?>" type="text" class="comm_input" style="width:230px; " maxlength="10"></td>
</tr>                                                    
<tr>
<td align="left"  valign="center" >Fax</td>
<td align="left"><input name="RegistrantFax" id="RegistrantFax" autocomplete="off" type="text" class="comm_input" value="<?php echo $_POST["RegistrantFax"]; ?>" style="width:230px; " onkeypress="validate(event);"></td>
</tr>
<tr>
<td align="left"  valign="center" >Phone<span class="mandred">*</span></td>
<td align="left" width="73%"><input name="RegistrantPhone" autocomplete="off" id="RegistrantPhone" type="text" class="comm_input" value="<?php echo $_POST["RegistrantPhone"]; ?>" style="width:230px; " onkeypress="validate(event);"></td>
</tr>

<?php
//                                                  if($showUK) {
//                                                    include "includes/uk.php";
//                                                  }
?>
<?php if ($shownexus == "YES") { ?>
<input type=hidden name=shonex value="<?php echo $shownexus ?>">
<tr>
<td align="left"  colspan="2">
<?php
if ($shownexus == "YES") {
include "includes/nexus.php";
}
?>


</td>
</tr>
<!-- this ends section TWO -->

<?php } ?>
<?php if ($showca == "YES") { ?>
<input type=hidden name=showca value="<?php echo $showca ?>">
<tr>
<td align="left" class="maintext_new" colspan="2">
<?php
//												if($showca=="YES"){
//													include "includes/showca.php";
//												}
?>

</td>
</tr>
<!-- this ends section TWO -->

<?php } ?>

<tr>
<td colspan="2">

</td>

</tr>
<tr>
<td colspan="2" align="left" width="20%" valign="center">
<input type="hidden" name="UnLockRegistrar" value="ON" checked id="UnLockRegistrar">
</td>


</tr>


</table>

<!-- <input type="submit"  name="" class= "button_orange_big" value="Register Domain" id="jqRegisterDomain"> -->
</div>

</div>

<div class="payment_column2">


<!-- Cart Info -->
<div class="payment_right_container">
	
<h3>Item Details</h3>
<div class="col-md-12">
<ul>
<li>
<div class="payment_right_item_new tbl_hdr">
<div class="large_new l_float">
<h5>Item</h5>
</div>
<div class="centernew l_float">
<h5>Billing Duration</h5>
</div>
<div class="small_new  right_text r_float_new"><h5>Amount</h5></div>
<div class="clear"></div>
</div>
<div id="domain_services"></div>
<input type="hidden" value="<?php echo $this->planName; ?>" id="product_namedomain" name="product_name">
<input type="hidden" value="<?php echo $this->vBillingInterval == 'L' ? 'Lifetime' : ($this->vBillingInterval == 'Y' ? 'Yearly' : 'Monthly'); ?>" id="product_billdomain">
<input type="hidden" value="<?php echo $this->planPrice; ?>" id="product_pricedomain" name="ServiceAmount" class="jqServiceAmount">
<input type="hidden" name="productId" value="<?php echo $this->planId; ?>" id="productId">
<input type="hidden" value="" id="slddomain" name="idsld">
<input type="hidden" name="tld" value="" id="tlddomain">
<input type="hidden" name="totPriceDomain" value="0" id="jqTotalDomainPrice">
<input type="hidden" name="tldPrice" value="0" id="jqTldPrice">
<input type="hidden" name="domainFlag" value="0" id="jqdomainFlag">
<div class="payment_right_item_new" id="domainRegistration">
<div class="large_new l_float">&nbsp;</div>
<div class="centernew l_float">Domain Registration</div>
<div class="small_new  right_text r_float_new"><?php echo ADMIN_CURRENCY_SYMBOL;?><span id="spanjqTldPrice"><?php echo Utils::formatPrice($amount); ?></span></div>
<div class="clear"></div>
</div>
<div class="payment_right_item_new">
<div class="large_new l_float">&nbsp;</div>
<div class="centernew l_float">Sub Total</div>
<div class="small_new  right_text r_float_new"><?php echo ADMIN_CURRENCY_SYMBOL;?><span class="jqSubTotal"><?php //echo Utils::formatPrice($this->planPrice); ?></span></div>
<div class="clear"></div>
</div>

<div class="payment_right_item_new">
<div class="large_new l_float">&nbsp;</div>
<div class="centernew l_float">Discounts</div>
<div class="small_new  right_text r_float_new"><?php echo ADMIN_CURRENCY_SYMBOL;?><span class="jqDiscount"><?php echo Utils::formatPrice(0); ?></span></div>
<div class="clear"></div>
</div>
<div class="payment_right_item_new hilite3">
<div class="large_new l_float">&nbsp;</div>
<div class="centernew l_float"><b>Total</b></div>
<div class="small_new  right_text r_float_new"><h5><?php echo ADMIN_CURRENCY_SYMBOL;?><span class="jqTotalPurchaseVal"><?php echo Utils::formatPrice($this->planPrice); ?></span>&nbsp;</h5></div>
<div class="clear"></div>
</div>
</li>
</ul>
</div>
</div>
<div class="clear"></div>


<!-- Cart Info ends -->

<!-- Credit card info -->

<div class="payment_right_container">
<ul>
<li>

<div class="payment_right_item">
<div class="col-md-12">
<h3>Enter Coupon Code</h3>
</div>	
<div class="clear"></div>
</div>
<div class="payment_right_item">
<!--    <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="pymntnew_tbl">
<tr>
<td valign="top" align="left">Coupon Code</td>
<td valign="top" align="left">
<input type="text" id="couponNumber1" name="couponNumber" onchange="couponCodeValidation('couponNumber1')" maxlength="16" class="width1">
<input type="button" name="jqCoupon" onclick="couponCodeValidation('couponNumber1')" value="APPLY COUPON"  class="button_orange2">
<div id="couponNumber1_err"><label class="error">&nbsp;</label></div>
</td>
</tr>
</table>-->
<div class="col-md-12">
<table cellspacing="0" cellpadding="0" border="0" width="98%" class="pymntnew_tbl">
<tr>
<td valign="middle" align="left" width="105">
Coupon Code
</td>
<td valign="middle" align="left" width="310">
<input type="text" id="couponNumber1" name="couponNumber" maxlength="16" class="input_newstyle01">
<td>
<td>
<input type="button" name="jqCoupon" onclick="couponCodeValidation('couponNumber1')" value="APPLY" class="button_orange2 btn-orng">
</td>
</tr>
<tr>
<td colspan="3">

<div id="couponNumber1_err"><label class="error">&nbsp;</label></div>
</td>
</tr>
</table>
</div>




<div class="clear"></div>
</div>
</li>
</ul>
<div class="clear"></div>
</div>


<div class="payment_right_container">
<ul>
<li>

<!--  <div class="payment_right_item">

<h3>Enter your credit card details</h3>


<div class="clear"></div>
</div>-->



<!---Payment for domain starts-->
<div class="payment_right_item">
<div class="col-md-12">
<h3>Select Payment Option</h3>
</div>
<div class="clear"></div>
</div>
<div class="col-md-12">
<table cellspacing="4" cellpadding="0" border="0" width="99%" class="card_options">
<tr>
<?php
//  echopre(PageContext::$response->paymnetsEnabled);
$paymentCount = 0;
if(isset (PageContext::$response->paymnetsEnabled['paypal_enable']) && PageContext::$response->paymnetsEnabled['paypal_enable'] == 'Y'){
$paymentCount++;
?>
<td>
<input type="image" src="<?php echo BASE_URL; ?>project/styles/images/paypal_logo.png"  title="Paypal"  onclick="setcurrentPaymnet('paypal')">
</td>
<?php
}
if($paymentCount % 5 ==0)
echo '</tr><tr>';

if(isset (PageContext::$response->paymnetsEnabled['paypalpro_enable']) && PageContext::$response->paymnetsEnabled['paypalpro_enable'] == 'Y'){
$paymentCount++;
?>
<td><img src="<?php echo BASE_URL; ?>project/styles/images/paypal_pro_logo.png" title="Paypalpro" onclick="showPaymentdiv('paypalprodomain')"></td>
<?php
}
if($paymentCount % 5 ==0)
echo '</tr><tr>';
if(isset (PageContext::$response->paymnetsEnabled['paypalflow_enable']) && PageContext::$response->paymnetsEnabled['paypalflow_enable'] == 'Y'){
$paymentCount++;

?>
<td><img src="<?php echo BASE_URL; ?>project/styles/images/paypal_payflow_logo.png" title="Paypalflow" onclick="showPaymentdiv('paypalflowdomain')"></td>
<?php
}
if($paymentCount % 5 ==0)
echo '</tr><tr>';
if(isset (PageContext::$response->paymnetsEnabled['paypaladvanced_enable']) && PageContext::$response->paymnetsEnabled['paypaladvanced_enable'] == 'Y'){
$paymentCount++;

?>
<td>
<!--<img src="<?php echo BASE_URL; ?>project/styles/images/2co_logo_payment.png" height="75" width="100">-->
<input type="image" src="<?php echo BASE_URL; ?>project/styles/images/paypal_adv_logo.png"  title="Paypaladvance" onclick="setcurrentPaymnet('paypaladvanced')">
</td>
<?php
}
if($paymentCount % 5 ==0)
echo '</tr><tr>';
if(isset (PageContext::$response->paymnetsEnabled['paypalexpress_enable']) && PageContext::$response->paymnetsEnabled['paypalexpress_enable'] == 'Y'){
$paymentCount++;

?>
<td>
<!-- <img src="<?php echo BASE_URL; ?>project/styles/images/btn_xpressCheckout.gif" height="75" width="100">-->
<input type="image" src="<?php echo BASE_URL; ?>project/styles/images/paypalcheckout_logo.png"  title="Paypalexpress"  onclick="setcurrentPaymnet('paypalxpress')">
</td>
<?php
}
if($paymentCount % 5 ==0)
echo '</tr><tr>';
if(isset (PageContext::$response->paymnetsEnabled['paypalflowlink_enable']) && PageContext::$response->paymnetsEnabled['paypalflowlink_enable'] == 'Y'){
$paymentCount++;
?>
<td>
<!--  <img src="<?php echo BASE_URL; ?>project/styles/images/paylinkicon.jpg" height="75" width="100">-->
<input type="image" src="<?php echo BASE_URL; ?>project/styles/images/payment_flowlink_logo.png"  title="Paypalflowlink" onclick="setcurrentPaymnet('paypalflowlink')">
</td>
<?php
}
if($paymentCount % 5 ==0)
echo '</tr><tr>';
?>

<?php
if(isset (PageContext::$response->paymnetsEnabled['ogone_enable']) && PageContext::$response->paymnetsEnabled['ogone_enable'] == 'Y'){
$paymentCount++;
?>
<td>
<!--<img src="<?php echo BASE_URL; ?>project/styles/images/logo_ogone.jpg" height="75" width="100">-->
<input type="image" src="<?php echo BASE_URL; ?>project/styles/images/ogone_logo.png"  title="Ogone" onclick="setcurrentPaymnet('ogone')">
</td>
<?php
}
if($paymentCount % 5 ==0)
echo '</tr><tr>';
?>
<?php
if(isset (PageContext::$response->paymnetsEnabled['authorize_enable']) && PageContext::$response->paymnetsEnabled['authorize_enable'] == 'Y'){
$paymentCount++;
?>
<td><img src="<?php echo BASE_URL; ?>project/styles/images/authorizenet_logo.png"  title="Authorize" onclick="showPaymentdiv('authorizedomain')"></td>
<?php
}
if(isset (PageContext::$response->paymnetsEnabled['bluedog_enable']) && PageContext::$response->paymnetsEnabled['bluedog_enable'] == 'Y'){
$paymentCount++;
?>
<td><img src="<?php echo BASE_URL; ?>project/styles/images/bluedog.jpg"  title="BlueDog" height="150" width="150" onclick="showPaymentdiv('bluedogdomain')"></td>
<?php
}
if($paymentCount % 5 ==0)
echo '</tr><tr>';
if(isset (PageContext::$response->paymnetsEnabled['twoco_enable']) && PageContext::$response->paymnetsEnabled['twoco_enable'] == 'Y'){
$paymentCount++;

?>
<td>
<!--<img src="<?php echo BASE_URL; ?>project/styles/images/2co_logo_payment.png" height="75" width="100">-->
<input type="image" src="<?php echo BASE_URL; ?>project/styles/images/2checkout_logo.png"  title="Twocheckout" onclick="setcurrentPaymnet('twocheckout')">
</td>
<?php
}if($paymentCount % 5 ==0)
echo '</tr><tr>';
if(isset (PageContext::$response->paymnetsEnabled['braintree_enable']) && PageContext::$response->paymnetsEnabled['braintree_enable'] == 'Y'){
$paymentCount++;

?>
<td>
<!--<img src="<?php echo BASE_URL; ?>project/styles/images/2co_logo_payment.png" height="75" width="100">-->
<input type="image" src="<?php echo BASE_URL; ?>project/styles/images/braintree_logo.png"  title="Braintree" onclick="setcurrentPaymnet('braintree')">
</td>
<?php
}
if($paymentCount % 5 ==0)
echo '</tr><tr>';
if(isset (PageContext::$response->paymnetsEnabled['googlecheckout_enable']) && PageContext::$response->paymnetsEnabled['googlecheckout_enable'] == 'Y'){
$paymentCount++;

?>
<td>

<input type="image" src="<?php echo BASE_URL; ?>project/styles/images/googlecheckout_logo.png"  title="Google Check Out" onclick="setcurrentPaymnet('googlecheckout')">
</td>
<?php }










if($paymentCount % 5 ==0)
echo '</tr><tr>';
if(isset (PageContext::$response->paymnetsEnabled['yourpay_enable']) && PageContext::$response->paymnetsEnabled['yourpay_enable'] == 'Y'){
$paymentCount++;

?>
<td>

<input type="image" src="<?php echo BASE_URL; ?>project/styles/images/firstdata_logo.png"  title="Your Pay" onclick="showPaymentdiv('yourpay')">
</td>
<?php }
if($paymentCount % 5 ==0)
echo '</tr><tr>';
if(isset (PageContext::$response->paymnetsEnabled['moneybookers_enable']) && PageContext::$response->paymnetsEnabled['moneybookers_enable'] == 'Y'){
$paymentCount++;

?>
<td>

<input type="image" src="<?php echo BASE_URL; ?>project/styles/images/moneybookers_logo.png"  title="Money Bookers" onclick="setcurrentPaymnet('moneybookers')">
</td>
<?php }

if($paymentCount % 5 ==0)
echo '</tr><tr>';
if(isset (PageContext::$response->paymnetsEnabled['quickbook_enable']) && PageContext::$response->paymnetsEnabled['quickbook_enable'] == 'Y'){
$paymentCount++;

?>
<td>
<img src="<?php echo BASE_URL; ?>project/styles/images/quickbooks_logo.png"  title="Quickbook" onclick="showPaymentdiv('quickbookdomain')">
<!--  <input type="image" src="<?php echo BASE_URL; ?>project/styles/images/quickbooks_logo.png"  title="Quickbook" onclick="showPaymentdiv('quickbook')">-->
</td>
<?php }
?>













</tr>

<tr>
<td colspan="5" class="cls_payment">

<div class="error_msg_container" style="display: none;">
<h2>Sorry !</h2>
<p class="jqErrorMessage"></p>
</div>

<input type="hidden" name="currentpaymantdomain"  id="currentpaymantdomain" value="">
<?php
if(isset (PageContext::$response->paymnetsEnabled['paypalpro_enable']) && PageContext::$response->paymnetsEnabled['paypalpro_enable'] == 'Y'){
?>
<div id="paypalprodomain" class="allpayment"  style="display:none"><br>
<?php  PageContext::renderPostAction('paypalprodomain','payments');?>
</div>
<?php
}
?>
<?php
if(isset (PageContext::$response->paymnetsEnabled['paypalflow_enable']) && PageContext::$response->paymnetsEnabled['paypalflow_enable'] == 'Y'){
?>
<div id="paypalflowdomain" class="allpayment"  style="display:none"><br>
<?php  PageContext::renderPostAction('paypalflowdomain','payments');?>
</div>
<?php
}
?>
<?php
if(isset (PageContext::$response->paymnetsEnabled['authorize_enable']) && PageContext::$response->paymnetsEnabled['authorize_enable'] == 'Y'){
?>
<div id="authorizedomain" class="allpayment" style="display:none" ><br>
<?php
PageContext::renderPostAction('authorizedomain','payments');?>
</div>
<?php
  }
?>

<?php
if(isset (PageContext::$response->paymnetsEnabled['bluedog_enable']) && PageContext::$response->paymnetsEnabled['bluedog_enable'] == 'Y'){
?>
<div id="bluedogdomain" class="allpayment" style="display:none" ><br>
<?php
PageContext::renderPostAction('bluedogdomain','payments');?>
</div>
<?php
  }
?>


<div id='yourpaydomain' class="allpayment" style="display:none" ><br>
<?php
PageContext::renderPostAction('yourpay','payments');?>
</div>
<div id="quickbookdomain" class="allpayment"  style="display:none">
<?php
PageContext::renderPostAction('quickbookdomain','payments');?>
</div>
</td>
</tr></table>
</div>
<!--Payment for domain ends-->


</li>

</ul>
<div class="clear"></div>
</div>
<!-- Credit card info ends -->
<!-- Coupon info -->

<!-- Coupon info ends -->

<!--  <div class="comm_div bordertop" align="right">
<input type="button" class="button_orange2 jqBackToDomain" name="back_btn" value="BACK" />
<input type="submit" class= "button_orange2" name="Submit" value="PAY NOW" id="jqRegisterDomain">
</div>-->

</div>
</div>


<div class="clear"></div>

<!-- Display area for " PROCEED TO PAY "-->

</div>
</form>
<div class="clear"></div>
</div>
</div>
<div class="clear"></div>
</div>
    
    
   
</div>
</div>

<div class="clear"></div>
</div>
</div>
<!--tab panel ending-->
  <?php }else{ ?>

  	<div class="col-md-2">
  	</div>
   	<div class="col-md-8 paynow-inner no-pad"> 
   	<div class="col-md-6 no-pad paynow-half">
   		<h3>Start Your Free Trial</h3>
   		<h2>Today</h2>

   	</div>  
   	<div class="col-md-6 no-pad">

<div class="payment_option1 mg-pay">
      
      <div class="freetrail_option_captche_enable_admin">
<div class="border0">
        <?php
        $formID = (PageContext::$response->userLogged==true) ? 'jqProductTryForm1' : 'jqProductTryForm';
        ?>
    <form id="<?php echo $formID; ?>" action="<?php echo BASE_URL; ?>index/trynow" enctype="" method="post" onsubmit="return chekcProceedStatus();"><!-- used to be index/trynow -->
        <div class="freetrail_option_box_captche_blk_outer">
		<div class="full-width">
     
            <div class="freetrail_option_boxlbl">
                <label style="display:none;" for="jqtxtStoreName" generated="true" class="error">Please enter store name</label>
            </div>
            <input type="text" name="txtStoreName" id="jqtxtStoreName" placeholder="Your Store Name" title="STORE NAME" >

        <?php
            if(PageContext::$response->userLogged==false) {
        ?>
                    
                     <input type="hidden" name="txtEmail" id="jqtxtEmail" value="<?php echo PageContext::$response->emailAddress?>">
        <input type="hidden" name="txtPassword" id="jqtxtPUserPassword" value="<?php echo PageContext::$response->password?>">
       
                    
                    
        <?php
            } else {
        ?>
<input type="hidden" name="txtPassword" id="jqtxtPUserPassword" value="">
        <input type="hidden" name="txtEmail" id="jqtxtEmail" value="">
        <?php
            }
        ?>
        

        <input type="hidden" name="productID" id="jqproductID" value="1"><!-- 1 is for vistacart -->
        <input type="hidden" id="jqUserExistFlag" value="1">
        <input type="hidden" id="jqDomainStatusVal" value="1">
        <div class="display_table-cell pad10_left vert_align_middle wid55per">
    		<div class="full-width">
    		<div class="captche">
    		<?php if(PageContext::$response->recaptcha_enable=='Y') { ?>
            <div>
                        <?php echo PageContext::$response->recaptchaHTML; ?>
            </div>
            <?php } ?>
    </div>

    		<div class="freetrail_option_box_captcha_small">

                <input id="jqProductBuy" type="submit" value="Try Now" class="more-btn wid-100-per">
            </div>
    		
            </div>
        </div>
        </div>
        <div class="error_bottom">
            <p id="jqShowMessage"></p>
            <p id="jqShowEmailAccountMessage"></p>
        </div>
        </div>
        <div class="clear"></div>
    </form>
    </div>
</div>
              <div>
  <?php } ?>
</div>


</div>
</div>

</div>
<div class="col-md-2">
	</div>


<!-- New HTML for payment options ends -->




<!-- Payment details area start

<input type="hidden" name="totPrice" value="<?php echo $this->productPrice; ?>" id="jqTotalPrice">
<input type="hidden" name="finalPrice" value="<?php echo $this->productPrice; ?>" id="jqFinalPrice">
<input type="hidden" name="totPriceDomain" value="0" id="jqTotalDomainPrice">
<input type="hidden" name="tldPrice" value="0" id="jqTldPrice">
<input type="hidden" name="domainFlag" value="0" id="jqdomainFlag">
<input type="hidden" name="productId" value="<?php echo $this->planId; ?>" id="productId">
<!--  <input type="hidden" name="domainFlag" value="" id="jqProductService">-->

<!-- Payment details area end -->

<!-- Display area for " PROCEED TO PAY "-->
<div class="clear">
<div class="clearfix"></div>
<div class="col-md-8 col-md-offset-2">
<div>
<div class="storecration_instalation_wrapper" style="display:none"  id="jqProgress">
<h3>Please wait your installation is in progress</h3>
<div class="storecration_instalation_wrapper_inner">

<div class="store_installationimg">	</div>

<div style="display:block;">
<div class="progress_outer">
<div class="progress_bar">
<div class="pointer" style="left:-120px;">
<!-- <p><span>Phase 1: Originate</span><br /> Analyzing input, preparing installation files and scripts</p> -->
</div>
<div class="bar" style="width:150px;"></div>
</div>
<div class="progress_count">0&nbsp;%</div>
<div class="clear"></div>
</div>
</div>

<div class="overlay" id="overlay" style="display:none;">

<div class="clear"></div>
</div>

<div class="clear"></div>
</div> </div>
</div></div></div>





<input type="hidden" id="jqCouponFlag" value="0">
<input type="hidden" id="jqUsedCoupon" value="">
<input type="hidden" id="jqCpricemode" value="">
<input type="hidden" id="jqCvalue" value="0.00">
<input type="hidden" id="jqUserExistFlag" value="1">
<input type="hidden" id="jqProceedFlag" value="0">

<div style:position:relative>
<div class="jqInnerLoginFormDiv  popup" style="display: none;">
<form id="loginInnerForm2"  onsubmit="return loginuseractionfrominner();">
<div class="errorBox err-padding" id="jqLoginError"></div>
<div class="popup-hd01">
<h6>Login</h6>
<a href="#" class="jqInnerLoginClose"><img src="<?php echo IMAGE_URL; ?>close-icon.png"></a>
</div>
<fieldset id="body">
<fieldset>
<span for="email">Email Address</span>
<input type="text" name="txtUsernameInner" id="txtUsernameInner" class="popup-txt"/>
</fieldset>
<fieldset>
<span for="password">Password</span>
<input type="password" name="txtPasswordInner" id="txtPasswordInner" class="popup-txt"/>
</fieldset>
<input type="hidden" name="locFlag" value="2">
<span>&nbsp;</span>
<input type="submit" class="loginFromInner popup-btn" value="Sign in"/>
<!--<label for="checkbox"><input type="checkbox" id="checkbox" />Remember me</label>-->
<p><a href="<?php echo ConfigUrl::base(); ?>index/forgotpwd" target="_blank">Forgot your password?</a></p>
<div id="jqInnerLoginError" class="popup-msg"></div>
</fieldset>
</form>                                               
</div></div>

<div class="clear">

<div class="col-md-8 col-md-offset-2">
<div>
<!-- installation completed----------------------------------------------------------------------- -->
<div id="jqMessage"></div>
<div class="storecration_instalation_wrapper" style="display:none">
<h3>Your Installation Successfully Completed</h3>
<div class="storecration_instalation_wrapper_inner">
<div class="instalation_completed_img "></div>
<h4>Congratulations!!!</h4>
</div>
<div class="clear"></div>
</div> </div> </div> </div>


</div>

<!-- installation completed----------------------------------------------------------------------- -->
</div>

<?php /*
<script>
$(document).ready(function()
{
<?php
if(isset (PageContext::$response->paymnetsEnabled['paypalpro_enable']) && PageContext::$response->paymnetsEnabled['paypalpro_enable'] == 'Y'){
?>
jQuery('#paypalpro').show();
<?php
}else{
jQuery('#paypalpro').hide();
}
?>
});
</script>
*/
?>
