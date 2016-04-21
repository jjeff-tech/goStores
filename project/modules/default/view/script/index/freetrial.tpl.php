<?php
/* The scenarios were free trial box need to be changed
 * Case 1 : RE-Captcha not enabled plus handles both user logged in and user without log in case
 * Case 1 : RE-Captcha not enabled plus handles both user logged in and user without log in case
 */


if(PageContext::$response->freetrialStatus==1) {
if(PageContext::$response->recaptcha_enable!='Y') {
/* Case : RE-Captcha not enabled plus handles both user logged in and user without log in case */
?>
<div class="freetrail_option top-margin">
        <?php
        $formID = (PageContext::$response->userLogged==true) ? 'jqProductTryForm1' : 'jqProductTryForm';
        ?>
    <form id="<?php echo $formID; ?>" action="<?php echo BASE_URL; ?>index/trynow" enctype="" method="post" onsubmit="return chekcProceedStatus();"><!-- used to be index/trynow -->
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 free-trl">
            <h3>Start Your Free Trial</h3>
            <h2>Today</h2>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-8">
        <div class="display_table wid100p marg20_top">
            <div class="display_table-cell pad10_left">
                
                <input type="text" name="txtStoreName" id="jqtxtStoreName" placeholder="Your Store Name" title="STORE NAME" class="form-control ht55px radius-35 pad20_left discaptchatxt">
                <div class="freetrail_option_boxlbl">
                    <label style="display:none;" for="jqtxtStoreName" generated="true" class="error">Please enter store name</label>
                </div>
            </div>
            <?php
                if(PageContext::$response->userLogged==false) {
                /* Case : user without log in */

            ?>
            <div class="display_table-cell pad10_left">
                <input type="text" name="txtEmail" id="jqtxtEmail" placeholder="Email Address" title="EMAIL ADDRESS" class="form-control ht55px radius-35 pad20_left discaptchatxt">
                <div class="freetrail_option_boxlbl">
                    <label style="display:none;" for="jqtxtEmail" generated="true" class="error">Please enter a valid email</label>
                </div>
            </div>

            <div class="display_table-cell pad10_left">
                <input type="password" name="txtPassword" id="jqtxtPUserPassword" placeholder="Password" title="PASSWORD" class="form-control ht55px radius-35 pad20_left discaptchatxt">
                <div class="freetrail_option_boxlbl">
                    <label  style="display:none;" for="jqtxtPUserPassword" generated="true" class="error">Please enter password</label></div>
            </div>
        
        <?php
            } else {
            /* Case : user logged in */

        ?>
        <input type="hidden" name="txtPassword" id="jqtxtPUserPassword" value="">
        <input type="hidden" name="txtEmail" id="jqtxtEmail" value="">
        <?php
            }
        ?>
        </div>
        </div>


        <input type="hidden" name="productID" id="jqproductID" value="1"><!-- 1 is for vistacart -->
        <input type="hidden" id="jqUserExistFlag" value="1">
        <input type="hidden" id="jqDomainStatusVal" value="1">
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-2">
		<div class="freetrail_option_box_small">

            <input id="jqProductBuy" type="submit" value="Try Now" class="search-btn">
        </div>
		</div>
        <div class="error_bottom">
            <p id="jqShowMessage"></p>
            <p id="jqShowEmailAccountMessage"></p>
        </div>
        <div class="clear"></div>
    </form>
    <div class="clear"></div>
</div>
<?php } else {
/* Case : RE-Captcha enabled */
if(PageContext::$response->userLogged!=true) {
/* Case : RE-Captcha enabled and user not logged in */
?>
<div class="freetrail_option_captche">
        <?php
        $formID = (PageContext::$response->userLogged==true) ? 'jqProductTryForm1' : 'jqProductTryForm';
        ?>
    <form id="<?php echo $formID; ?>" action="<?php echo BASE_URL; ?>index/trynow" enctype="" method="post" onsubmit="return chekcProceedStatus();"><!-- used to be index/trynow -->
        <div class="freetrail_option_box_captche_blk_outer">
        <div class="col-xs-12 col-sm-12 col-md-2"></div>
        <div class="col-xs-12 col-sm-12 col-md-5">
        <div class="pad20_left">
		<div class="freetrail_option_box_captche">
            
            <input type="text" name="txtStoreName" id="jqtxtStoreName" placeholder="Your Store Name" title="STORE NAME" class="form-control marg10_top">
            <div class="freetrail_option_boxlbl">
                <label style="display:none;" for="jqtxtStoreName" generated="true" class="error">Please enter store name</label>
            </div>
            <div class="clear"></div>
        </div>
        <?php
            if(PageContext::$response->userLogged==false) {
        ?>
        <div class="freetrail_option_box_captche">
            <input type="text" name="txtEmail" id="jqtxtEmail" placeholder="Email Address" title="EMAIL ADDRESS" class="form-control marg10_top">
            <div class="freetrail_option_boxlbl">
                <label style="display:none;" for="jqtxtEmail" generated="true" class="error">Please enter a valid email</label>
            </div>
            <div class="clear"></div>
        </div>

        <div class="freetrail_option_box_captche">
            
            <input type="password" name="txtPassword" id="jqtxtPUserPassword" placeholder="Password" title="PASSWORD" class="form-control marg10_col">
        	<div class="freetrail_option_boxlbl">
                <label  style="display:none;" for="jqtxtPUserPassword" generated="true" class="error">Please enter password</label></div>
            </div>
            <div class="clear"></div>
        </div>
        </div>
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
       <div class="col-xs-12 col-sm-12 col-md-5">
       <div class="table-responsive">
		<div class="captche_display_blk pad10px_top">
		<div class="captche">
		<?php if(PageContext::$response->recaptcha_enable=='Y') { ?>
        <div>
                    <?php echo PageContext::$response->recaptchaHTML; ?>
        </div>
        <?php } ?>
</div>

		<div class="freetrail_option_box_captcha_small">

            <input id="jqProductBuy" type="submit" value="Try Now" class="orng_btnfreetrail_captche">
        </div>
		
        </div>
       </div>
        </div>
        <div class="error_bottom">
            <p id="jqShowMessage"></p>
            <p id="jqShowEmailAccountMessage"></p>
        </div>
        <div class="clear"></div>
        </div>
    </form>

</div>
<?php
}

}
?>
<?php
if(PageContext::$response->recaptcha_enable=='Y' && PageContext::$response->userLogged==true) {
?>

<div class="freetrail_option_captche_enable_admin">
<div class="table-responsive border0">
        <?php
        $formID = (PageContext::$response->userLogged==true) ? 'jqProductTryForm1' : 'jqProductTryForm';
        ?>
    <form id="<?php echo $formID; ?>" action="<?php echo BASE_URL; ?>index/trynow" enctype="" method="post" onsubmit="return chekcProceedStatus();"><!-- used to be index/trynow -->
        <div class="freetrail_option_box_captche_blk_outer">
		<div class="display_table wid100p">
        <div class="display_table-cell pad10_left vert_align_middle wid45per">
            <div class="freetrail_option_boxlbl">
                <label style="display:none;" for="jqtxtStoreName" generated="true" class="error">Please enter store name</label>
            </div>
            <input type="text" name="txtStoreName" id="jqtxtStoreName" placeholder="Your Store Name" title="STORE NAME" class="form-control marg15_left_767">

        </div>
        <?php
            if(PageContext::$response->userLogged==false) {
        ?>
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
    		<div class="captche_display_blk_admin">
    		<div class="captche">
    		<?php if(PageContext::$response->recaptcha_enable=='Y') { ?>
            <div>
                        <?php echo PageContext::$response->recaptchaHTML; ?>
            </div>
            <?php } ?>
    </div>

    		<div class="freetrail_option_box_captcha_small">

                <input id="jqProductBuy" type="submit" value="Try Now" class="orng_btnfreetrail_captche">
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

<?php
}
?>
<div class="jqInnerLoginFormDiv popup" style="display: none;">
    <form id="loginInnerForm1"  onsubmit="return loginuseractionfrominner();">
        <div class="popup-hd01">
            <h6>Login</h6>
            <a href="#" class="jqInnerLoginClose"><img src="<?php echo IMAGE_URL; ?>close-icon.png"></a>
        </div>
        <div class="errorBox" id="jqLoginError">&nbsp;</div>
        <fieldset id="body">
            <fieldset>
                <span for="email">Email Address</span>
                <input type="text" name="txtUsernameInner" id="txtUsernameInner" class="popup-txt"/>
            </fieldset>
            <fieldset>
                <span for="password">Password</span>
                <input type="password" name="txtPasswordInner" id="txtPasswordInner" class="popup-txt" />
            </fieldset>
            <input type="hidden" name="locFlag" value="1">
            <span>&nbsp;</span>
            <input type="submit" class="loginFromInner popup-btn" value="Sign in"/>
            <p><a href="<?php echo ConfigUrl::base(); ?>index/forgotpwd" target="_blank">Forgot your password?</a></p>
            <!--<label for="checkbox"><input type="checkbox" id="checkbox" />Remember me</label>-->
        </fieldset>

        <div id="jqInnerLoginError" class="popup-msg"></div>
    </form>
</div>
    <?php
}
?>
