<script type="text/javascript">
    $(function(){
        // Datepicker
        $('#txtExpiryDate').datepicker({
            inline: true
        });
    });
</script>
<!--- Start Modifications -->
<div class="form_container"> 
   <div class="form_top"><?php echo $this->pageTitle; ?></div>
   <div class="form_bgr">
   <?php PageContext::renderPostAction('errormessage','index');
$this->messageFunction ='';
?>
   <form method="post" id="frmgenerateCoupons" action="<?php echo ConfigUrl::base(); ?>coupon/createcoupon">
    <div class="errorBox"><?php //echo $this->errMsg ?></div>
    <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="formstyle">
        <tr>
            <th align="left" valign="top" width="15%" ><label for="noofcoupons">Coupon Name <span class="mandred">*</span></label></th>
            <td align="left" valign="top"><div><div class="l_float">&nbsp;&nbsp;&nbsp;</div><div class="l_float"><input id="txtCouponName" name="txtCouponName" value="<?php echo stripslashes($this->coupon->vCouponCode);?>" title="Coupon Name" tabindex="4" type="text"/><!--<a href="javascript:void(0);" id="generateCouponName">Generate Coupon Name</a>--></div></div></td>
        </tr>
        <tr>
            <th align="left" valign="top" ><label for="noofcoupons">Number of Coupons<span class="mandred">*</span></label></th>
            <td align="left" valign="top"><div><div class="l_float">&nbsp;&nbsp;&nbsp;</div><div class="l_float"><input id="txtNoOfCoupons" name="txtNoOfCoupons" value="<?php echo $this->coupon->nCouponCount;?>" title="NoofCoupons" tabindex="4" type="text"/></div></div></td>
        </tr>
        <tr>
            <td align="left" valign="top"><label for="expirydate">Expiry Date<span class="mandred">*</span></label></td>
            <td align="left" valign="top"><div><div class="l_float">&nbsp;&nbsp;&nbsp;</div><div class="l_float"><input id="txtExpiryDate" class="inp-form" name="txtExpiryDate" value="<?php echo $this->coupon->dExpireOn?date("m/d/Y",strtotime($this->coupon->dExpireOn)):'';?>" title="ExpiryDate" tabindex="5" type="text" /></div></div></td>
        </tr>
        <tr>
            <td align="left" valign="top"><label for="pricingMode">Pricing Mode</label></td>
            <td align="left" valign="top"><div><div class="l_float">&nbsp;&nbsp;&nbsp;</div><div class="l_float">
                <?php echo $this->radio('pricingMode', 'percentage', $this->coupon->vPricingMode, NULL, '&nbsp;', NULL); ?> Percentage&nbsp;&nbsp;
                <?php echo $this->radio('pricingMode', 'rate', $this->coupon->vPricingMode, NULL, '&nbsp;', NULL); ?> Rate</div></div>
            </td>
        </tr>
        <tr>
            <td align="left" valign="top"><label for="discountvalue">Coupon Value<span class="mandred">*</span></label></td>
            <td align="left" valign="top"><div><div class="l_float" id="jqCurrency">&nbsp;&nbsp;&nbsp;</div><div class="l_float"><input id="txtDiscountRate" name="txtDiscountRate" class="inp-form" value="<?php echo $this->coupon->nCouponValue;?>" title="DiscountRate" tabindex="5" type="text" /> &nbsp;</div></div></td>
        </tr>
        <tr>
            <td align="left" valign="top"><label for="description">Description</label></td>
            <td align="left" valign="top"><div><div class="l_float">&nbsp;&nbsp;&nbsp;</div><div class="l_float"><input id="txtDescription" name="txtDescription" class="inp-form" value="<?php echo stripslashes($this->coupon->vCouponDescription);?>" title="Description" tabindex="5" type="text" /></div></div></td>
        </tr>
        
        <?php if($this->couponId)
        {
            echo '<input id="txtCouponId" name="txtCouponId" value="'.$this->couponId.'" type="hidden" />';
            echo '<tr><th align="left" valign="top"><div class="cancel"><a href="'.BASE_URL.'admin/coupon">Cancel</a></div></th><td align="left" valign="top">
                    <div><div class="l_float">&nbsp;&nbsp;&nbsp;</div><div class="l_float"><input id="submitCoupon" name="Update_Coupon" value="Save Changes" tabindex="6" type="submit"/></div></div></td>
                </tr>';
        } else {  ?>
                <tr>
                    <th align="left" valign="top"><div class="cancel"><a href="<?php echo BASE_URL;?>admin/coupon">Cancel</a></div></th>
                    <td align="left" valign="top"><div><div class="l_float">&nbsp;&nbsp;&nbsp;</div><div class="l_float"><input id="submitCoupon" name="submitCoupon" value="Save Changes" tabindex="6" type="submit"/></div></div></td>
                </tr>
        <?php } ?>
    </table>
   </form>      
   </div>   
</div>
<!-- End Modifications -->