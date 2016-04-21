<?php
$tab = $_REQUEST['tab'];
if($tab=='') $tab = 'general';
?>
<script type="text/javascript">
    $(function(){
        var tab = '<?php echo $tab ?>';
        //$('#settingtab a:first').tab('show');
        $('#settingtab a[href="#'+tab+'"]').tab('show');

    });
</script>
<div class="section_list_view ">
    <div class="row have-margin">
        <div class="tophding_blk">
            <span class="legend hdname hdblk_inr"><div class='hdblk_inr'>Section : Settings</div></span>
        </div>

        <?php if (!empty(PageContext::$response->message)) { ?>
            <div class="alert alert-<?php echo PageContext::$response->successError; ?>">
                <button class="close" data-dismiss="alert" type="button">x</button>
                <?php echo PageContext::$response->message; ?>
            </div>
        <?php } ?>

        <div class="input-append pull-right">

        </div>
    </div>
    <ul id="settingtab" class="nav nav-tabs">
        <li class=""><a data-toggle="tab" href="#general">General</a></li>
        <li class=""><a data-toggle="tab" href="#payment">Payment</a></li>
        <li class=""><a data-toggle="tab" href="#domain-registrar">Domain Registrar</a></li>
        <li class=""><a data-toggle="tab" href="#social-settings">Social Settings</a></li>
        <li class=""><a data-toggle="tab" href="#smtp-details">SMTP Details</a></li>
        <li class=""><a data-toggle="tab" href="#name-servers">Name Servers</a></li>
        <li class=""><a data-toggle="tab" data-target="#password" href="#password">Update Password</a></li>
        <!-- <li class=""><a data-toggle="tab" data-target="#server-settings" href="#server-settings">Server Settings</a></li> -->
    </ul>
    <div class="tab-content">

        <div id="general" class="tab-pane">
            <form name="generalForm" id="jqGeneralForm" method="post" enctype="multipart/form-data" class="form-horizontal" action="<?php echo BASE_URL; ?>cms?section=settings&tab=general">
                <div class="control-group">
                    <label for="siteName" class="control-label">Site Name</label>
                    <div class="controls"><?php
                //    echopre(PageContext::$response->pageContents);
                    ?>
                        <input type="text" value="<?php echo PageContext::$response->pageContents['siteName']->value; ?>"  name="siteName">
                <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pageContents['siteName']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>

                <!--<div class="control-group">
                    <label class="control-label" for="siteTitle">Site Title</label>
                    <div class="controls">
                        <input type="text" value="<?php //echo PageContext::$response->pageContents['siteTitle']->value; ?>" name="siteTitle">
                        <a href="#" class="tooltiplink" data-original-title="<?php //echo PageContext::$response->pageContents['siteTitle']->helpText; ?>"><span class="help-icon"><img src="<?php //echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>-->

                <div class="control-group">
                    <label for="secureURL" class="control-label">Secure URL</label>
                    <div class="controls">
                        <input type="text" value="<?php echo PageContext::$response->pageContents['secureURL']->value; ?>" readonly name="secureURL">
                    <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pageContents['secureURL']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>

                </div>

                <div class="control-group">
                    <label for="adminEmail" class="control-label">Admin Email</label>
                    <div class="controls">
                        <input type="text" value="<?php echo PageContext::$response->pageContents['adminEmail']->value; ?>"  name="adminEmail">
                    <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pageContents['adminEmail']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>

                <!--<div class="control-group">
                    <label for="metaKeywords" class="control-label">Meta Keywords</label>
                    <div class="controls">
                        <textarea name="metaKeywords"><?php //echo PageContext::$response->pageContents['metaKeywords']->value; ?></textarea>
                    <a href="#" class="tooltiplink" data-original-title="<?php //echo PageContext::$response->pageContents['metaKeywords']->helpText; ?>"><span class="help-icon"><img src="<?php //echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>-->

                <!--<div class="control-group">
                    <label for="metaDescription" class="control-label">Meta Description</label>
                    <div class="controls">
                        <textarea name="metaDescription"><?php //echo PageContext::$response->pageContents['metaDescription']->value; ?></textarea>
                    <a href="#" class="tooltiplink" data-original-title="<?php //echo PageContext::$response->pageContents['metaDescription']->helpText; ?>"><span class="help-icon"><img src="<?php //echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>-->

                <?php
                $checked = '';
                if(PageContext::$response->pageContents['enableGoogleAdsense']->value == 'Y'){
                    $checked = "checked='checked'";
                }
                ?>
                <div class="control-group">
                    <label for="enableGoogleAdsense" class="control-label">Enable Google Adsense</label>
                    <div class="controls">
                        <input type="checkbox" <?php echo $checked; ?>  name="enableGoogleAdsense" class="jqToggle" value="Y" id="jqAdsense"><span class="help-inline"></span>
                        <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pageContents['enableGoogleAdsense']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>
                <div class="control-group jqAdsense">
                    <label for="googleAdsense" class="control-label">Google Adsense</label>
                    <div class="controls">
                        <textarea name="googleAdsense" id="jqAdsenseValue" validate="required:validateFieldsWithCheckbox('jqAdsense','jqAdsenseValue')"><?php echo htmlspecialchars(PageContext::$response->pageContents['googleAdsense']->value); ?></textarea>
                    <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pageContents['googleAdsense']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>

                <?php
                /*
                $checked = '';
                if(PageContext::$response->pageContents[4]->value == 'Y'){
                    $checked = "checked='checked'";
                } */
                ?>
                <!--
                <div class="control-group">
                    <label for="streamsend_enable" class="control-label">Enable Site Banner</label>
                    <div class="controls">
                        <input type="checkbox" <?php //echo $checked; ?> name="enablesiteBanner" class="jqToggle" id="jqBanner" value="Y"><span class="help-inline"></span>
                    </div>
                </div-->

                <div class="control-group">
                    <label for="siteLogo" class="control-label">Site Logo</label>
                    <div class="controls">
                        <input type="file" name="siteLogo" id="siteLogo" />
                         <a href="#" class="tooltiplink" data-original-title="This is where you can change the logo of the site. Click Browse to
                               upload a file saved on your computer.For best display, size should be 345 x 75. Allowed file types are jpg,jpeg, gif and png "><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                        <?php
                        if(PageContext::$response->siteLogoName) {
                            global $imageTypes;
                            $imgName = $imageTypes['siteLogo']['prefix'].PageContext::$response->siteLogoName;
                            if(file_exists(FILE_UPLOAD_DIR.'/'.$imgName)) $imgName = $imgName;
                            else $imgName = PageContext::$response->siteLogoName;
                            ?>
                        <div class="controls" style="margin-top: 10px;">
                            <img src="<?php echo IMAGE_FILE_URL.$imgName;?>" width="345" height="75"/>
                        </div>
                            <?php }?>

                </div>

                <!--div class="control-group jqBanner">
                    <label for="banner_link" class="control-label">Banner Link</label>
                    <div class="controls">
                        <input type="text" name="banner_link" id="jqBannerLink" value="<?php //echo PageContext::$response->pageContents[14]->value; ?>" validate="required:validateFieldsWithCheckbox('jqBanner','jqBannerLink')" >
                    </div>
                </div>
                -->
                <div class="control-group">
                    <label for="googleAnalytics" class="control-label">Google Analytics</label>
                    <div class="controls">
                        <textarea name="googleAnalytics"><?php echo htmlspecialchars(PageContext::$response->pageContents['googleAnalytics']->value); ?></textarea>
                    <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pageContents['googleAnalytics']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>

                <?php
                $checked = '';
                if(PageContext::$response->pageContents['streamsend_enable']->value == 'Y'){
                    $checked = "checked='checked'";
                }
                ?>
                <div class="control-group">
                    <label for="streamsend_enable" class="control-label">Enable Streamsend</label>
                    <div class="controls">
                        <input type="checkbox" <?php echo $checked; ?> name="streamsend_enable" value="Y" class="jqToggle" id="jqStream"><span class="help-inline">
                            <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pageContents['streamsend_enable']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>

                        </span>
                    </div>

                </div>

                <div class="control-group jqStream">
                    <label for="streamsend_loginid" class="control-label">Streamsend Login ID</label>
                    <div class="controls">
                        <input type="text" value="<?php echo PageContext::$response->pageContents['streamsend_loginid']->value; ?>" name="streamsend_loginid" id="jqStreamSendLoginId" validate="required:validateFieldsWithCheckbox('jqStream','jqStreamSendLoginId')">
                    <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pageContents['streamsend_loginid']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>

                <div class="control-group jqStream">
                    <label for="streamsend_key" class="control-label">Streamsend Key</label>
                    <div class="controls">
                        <input type="text" value="<?php echo PageContext::$response->pageContents['streamsend_key']->value; ?>" name="streamsend_key" id="jqStreamsendkey" validate="required:validateFieldsWithCheckbox('jqStream','jqStreamsendkey')">
                     <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pageContents['streamsend_key']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>

                <div class="control-group jqStream">
                    <label for="streamsend_listid" class="control-label">Streamsend List ID</label>
                    <div class="controls">
                        <input type="text" value="<?php echo PageContext::$response->pageContents['streamsend_listid']->value; ?>" name="streamsend_listid" id="jqStreamsendListId" validate="required:validateFieldsWithCheckbox('jqStream','jqStreamsendListId')" >
                     <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pageContents['streamsend_listid']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>
                <div class="control-group">
                    <label for="company_name" class="control-label">Company Name</label>
                    <div class="controls">
                        <input type="text" value="<?php echo PageContext::$response->pageContents['company_name']->value; ?>" name="company_name" >
                    <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pageContents['company_name']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                 </div>
                 <div class="control-group">
                    <label for="company_email" class="control-label">Company Email</label>
                    <div class="controls">
                        <input type="text" value="<?php echo PageContext::$response->pageContents['company_email']->value; ?>" name="company_email" id="jqsite_email" >
                     <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pageContents['company_email']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                 </div>
                <div class="control-group">
                    <label for="company_website" class="control-label">Company Website</label>
                    <div class="controls">
                        <input type="text" value="<?php echo PageContext::$response->pageContents['company_website']->value; ?>" name="company_website" >
                     <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pageContents['company_website']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                 </div>
                <div class="control-group">
                    <label for="company_phone" class="control-label">Company Phone</label>
                    <div class="controls">
                        <input type="text" value="<?php echo PageContext::$response->pageContents['company_phone']->value; ?>" name="company_phone" id="jqsite_phone"   >
                     <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pageContents['company_phone']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>
                <div class="control-group">
                    <label for="company_phone_internat" class="control-label">Company Phone(International)</label>
                    <div class="controls">
                        <input type="text" value="<?php echo PageContext::$response->pageContents['company_phone_internat']->value; ?>" name="company_phone_internat" id="jqsite_phone_internat"   >
                       <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pageContents['company_phone_internat']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>
                <div class="control-group">
                    <label for="googleAnalytics" class="control-label">Company Address</label>
                    <div class="controls">
                        <textarea name="company_address"><?php echo htmlspecialchars(PageContext::$response->pageContents['company_address']->value); ?></textarea>
                    <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pageContents['company_address']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>
                 <div class="control-group">
                    <label for="currency_symbol" class="control-label">Currency Symbol</label>
                    <div class="controls">
                        <input type="text" value="<?php echo PageContext::$response->pageContents['currency_symbol']->value; ?>" name="currency_symbol">
                      <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pageContents['currency_symbol']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>
                <div class="control-group">
                    <label for="licenseKey" class="control-label">License Key</label>
                    <div class="controls">
                        <input type="text" value="<?php echo PageContext::$response->pageContents['licenseKey']->value; ?>" name="licenseKey">
                      <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pageContents['licenseKey']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>
                
             <!--    <div class="control-group">
                    <label for="inventory_source_amount" class="control-label">Inventory Source Amount</label>
                    <div class="controls">
                        <input type="text" value="<?php echo PageContext::$response->pageContents['inventory_source_amount']->value; ?>" name="inventory_source_amount">
                      <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pageContents['inventory_source_amount']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div> -->
                
               <!--  <div class="control-group">
                    <label for="inventory_source_plan_duration" class="control-label">Inventory Source Plan Duration</label>
                    <div class="controls">
                        <input type="text" value="<?php echo PageContext::$response->pageContents['inventory_source_plan_duration']->value; ?>" name="inventory_source_plan_duration">
                      <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pageContents['inventory_source_plan_duration']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div> -->
                
                
                
                    <?php
                    //... Recaptcha Area activate if needed <M>
                    $checked = '';
                    if(PageContext::$response->pageContents['recaptcha_enable']->value == 'Y') {
                        $checked = "checked='checked'";
                    }
                    ?>
<!--                    <div class="control-group">
                        <label for="recaptcha_enable" class="control-label">Enable Recaptcha</label>
                        <div class="controls">
                            <input type="checkbox" <?php echo $checked; ?>  name="recaptcha_enable" class="jqToggle float-left" value="Y" id="jqRecaptcha" ><span class="help-inline"></span>

                            <a href="javascript:void(0)" class="tooltiplink help-icon" title="You can enable / disable recaptcha in site."></a>

                        </div>
                    </div>
                    <div class="control-group jqRecaptcha">
                        <div class="control-group">
                            <label for="recaptcha_public_key" class="control-label">Recaptcha Public Key</label>
                            <div class="controls">
                                <textarea name="recaptcha_public_key" id="recaptcha_public_key" class="float-left" validate="required:validateFieldsWithCheckbox('jqRecaptcha','recaptcha_public_key')"><?php echo htmlspecialchars(PageContext::$response->pageContents['recaptcha_public_key']->value); ?></textarea>
                                <a href="javascript:void(0)" class="tooltiplink help-icon" title="Enter Recaptcha Public Key"></a>
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="recaptcha_private_key" class="control-label">Recaptcha Private Key</label>
                            <div class="controls">
                                <textarea name="recaptcha_private_key" id="recaptcha_private_key" validate="required:validateFieldsWithCheckbox('jqRecaptcha','recaptcha_private_key')" class="float-left"><?php echo htmlspecialchars(PageContext::$response->pageContents['recaptcha_private_key']->value); ?></textarea>
                                <a href="javascript:void(0)" class="tooltiplink help-icon" title="Enter Recaptcha Private Key"></a>
                            </div>
                        </div>
                    </div>-->

                <div class="controls">
                    <input type="submit" name="submitBtn" value="Save" class="submitButton btn" />
                </div>
            </form>
        </div>


        <!-- Payment -->
        <div id="payment" class="tab-pane">
          <form name="paypalForm" id="jqPaypalForm" method="post" class="form-horizontal" action="<?php echo BASE_URL; ?>cms?section=settings&tab=payment">

                <!-- <div class="control-group">
                    <label for="enablepaypal" class="control-label">Enable PayPal</label>
                    <div class="controls">
                        <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['enablepaypal']->value == 'Y')?'checked':''; ?> name="enablepaypal" value="Y" class="jqToggle" id="jqEnablePaypal"><span class="help-inline"></span>
                    </div>
                </div> -->
                <div class="control-group jqEnablePaypal">
                    <!-- <div class="control-group">
                        <label class="control-label" for="enablepaypalsandbox">PayPal Test Mode</label>
                        <div class="controls">
                            <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['enablepaypalsandbox']->value == 'Y')?'checked':'';?> name="enablepaypalsandbox" value="Y"><span class="help-inline"></span>
                        </div>
                    </div> -->
                    <!--div class="control-group">
                        <label for="paypalidentitytoken" class="control-label">PayPal Identity Token</label>
                        <div class="controls">
                            <input type="text" value="<?php //echo PageContext::$response->pagePaymentContents['paypalidentitytoken']->value; ?>" name="paypalidentitytoken" id="paypalidentitytoken" ></div>
                    </div>-->
                    <!-- <div class="control-group">
                        <label for="paypalemail" class="control-label">PayPal Email</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['paypalemail']->value; ?>" name="paypalemail" id="jqPaypalEmail"></div>
                    </div> -->
                    <!--div class="control-group">
                        <label for="paypal_api_username" class="control-label">PayPal Username</label>
                        <div class="controls">
                            <input type="text" value="<?php //echo PageContext::$response->pagePaymentContents['paypal_api_username']->value; ?>" name="paypal_api_username" id="jqPaypalUsername" ></div>
                    </div>
                    <div class="control-group">
                        <label for="paypal_api_password" class="control-label">PayPal Password</label>
                        <div class="controls">
                            <input type="password" value="<?php //echo PageContext::$response->pagePaymentContents['paypal_api_password']->value; ?>" name="paypal_api_password" id="jqPaypalPassword">
                        </div>
                    </div>
                  <div class="control-group">
                        <label for="paypal_api_signature" class="control-label">PayPal Signature</label>
                        <div class="controls">
                            <input type="text" value="<?php //echo PageContext::$response->pagePaymentContents['paypal_api_signature']->value; ?>" name="paypal_api_signature" id="jqPaypalSignature" >
                        </div>
                  </div>
                  <div class="control-group">
                        <label for="paypal_application_app_id" class="control-label">PayPal Application ID</label>
                        <div class="controls">
                            <input type="text" value="<?php //echo PageContext::$response->pagePaymentContents['paypal_application_app_id']->value; ?>" name="paypal_application_app_id" id="jqPaypalApplicationId" >
                        </div>
                  </div-->
                    <!-- <div class="control-group">
                        <label for="paypal_bn_code" class="control-label">PayPal BN Code</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['paypal_bn_code']->value; ?>" name="paypal_bn_code" id="jqPaypalBNCode" >
                            <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pagePaymentContents['paypal_bn_code']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                        </div>
                    </div> -->
              </div>

                <div class="control-group">
                    <label for="authorize_enable" class="control-label">Enable Authorize.Net</label>
                    <div class="controls">
                        <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['authorize_enable']->value == 'Y')?'checked':''; ?> name="authorize_enable" class="jqToggle" value="Y" id="jqAuthorize"><span class="help-inline"></span>
                    <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pagePaymentContents['authorize_enable']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>
               <div class="control-group jqAuthorize">
                   <div class="control-group">
                        <label for="authorize_test_mode" class="control-label">Authorize.Net Test Mode</label>
                        <div class="controls">
                            <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['authorize_test_mode']->value == 'Y')?'checked':''; ?> name="authorize_test_mode" value="Y"><span class="help-inline"></span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="authorize_loginid" class="control-label">Authorize.Net Login ID</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['authorize_loginid']->value; ?>" name="authorize_loginid">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="authorize_transkey" class="control-label">Authorize.Net Transaction Key</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['authorize_transkey']->value; ?>" name="authorize_transkey">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="authorize_email" class="control-label">Authorize.Net Email</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['authorize_email']->value; ?>" name="authorize_email">
                        </div>
                    </div>
               </div>


               <div class="control-group">
                    <label for="bluedog_enable" class="control-label">Enable BlueDog</label>
                    <div class="controls">
                        <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['bluedog_enable']->value == 'Y')?'checked':''; ?> name="bluedog_enable" class="jqToggle" value="Y" id="jqBlueDog"><span class="help-inline"></span>

<!--                        <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pagePaymentContents['bluedog_enable']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                   -->
                    </div>
                </div>
              <div class="control-group jqBlueDog">
                   <div class="control-group">
                        <label for="bluedog_test_mode" class="control-label">BlueDog Test Mode</label>
                        <div class="controls">
                            <input type="hidden" name="bluedog_test_mode" value="">
                            <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['bluedog_test_mode']->value == 'Y')?'checked':''; ?> name="bluedog_test_mode" value="Y"><span class="help-inline"></span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="bluedog_live_apikey" class="control-label">BlueDog Live Api Key</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['bluedog_live_apikey']->value; ?>" name="bluedog_live_apikey">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="bluedog_sandbox_apikey" class="control-label">BlueDog Sandbox Api Key</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['bluedog_sandbox_apikey']->value; ?>" name="bluedog_sandbox_apikey">
                        </div>
                    </div>

               </div>

<div class="control-group">
                    <label for="stripe_enable" class="control-label">Enable Stripe</label>
                    <div class="controls">
                        <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['stripe_enable']->value == 'Y')?'checked':''; ?> name="stripe_enable" class="jqToggle" value="Y" id="jqStripe"><span class="help-inline"></span>

<!--                        <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pagePaymentContents['bluedog_enable']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                   -->
                    </div>
                </div>
              <div class="control-group jqStripe">
                   <div class="control-group">
                        <label for="stripe_test_mode" class="control-label">Stripe Test Mode</label>
                        <div class="controls">
                            <input type="hidden" name="stripe_test_mode" value="">
                            <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['stripe_test_mode']->value == 'Y')?'checked':''; ?> name="stripe_test_mode" value="Y" id="jqTestStripe"><span class="help-inline"></span>
                        </div>
                    </div>
                    <div id="stripe_sandbox_div" class="control-group jqTestStripe" >
                    <div class="control-group ">
                        <label for="stripe_sandbox_publishkey" class="control-label">Stripe Sandbox Publishable key</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['stripe_sandbox_publishkey']->value; ?>" name="stripe_sandbox_publishkey">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="stripe_sandbox_secretkey" class="control-label">Stripe Sandbox Secret key</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['stripe_sandbox_secretkey']->value; ?>" name="stripe_sandbox_secretkey">
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="stripe_sandbox_secretkey" class="control-label">Stripe Webhook Url</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['stripe_webhook_url']->value; ?>" name="stripe_webhook_url">
                            <a href="#" class="tooltiplink" data-original-title="set a webhook Endpoint in your stripe account as YOUR DOMAIN/index/stripepayment/ "><span class="help-icon"><img src="http://localhost/gostores/modules/cms/images/help_icon.png"></span></a>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="stripe_webhook_secret_key" class="control-label">Stripe Sandbox Webhook Secret Key</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['stripe_webhook_secret_key']->value; ?>" name="stripe_webhook_secret_key">
                        </div>
                    </div>
                </div>
                <div id="stripe_live_div">

                    <div class="control-group">
                        <label for="stripe_live_publishkey" class="control-label">Stripe Live Publishable key</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['stripe_live_publishkey']->value; ?>" name="stripe_live_publishkey">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="stripe_live_secretkey" class="control-label">Stripe Live Secret key</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['stripe_live_secretkey']->value; ?>" name="stripe_live_secretkey">
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="stripe_live_webhook_secret_key" class="control-label">Stripe Live Webhook Secret Key</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['stripe_live_webhook_secret_key']->value; ?>" name="stripe_live_webhook_secret_key">
                        </div>
                    </div>

                </div>

               </div>



               <!-- <div class="control-group">
                    <label for="twoco_enable" class="control-label">Enable TwoCheckout</label>
                    <div class="controls">
                        <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['twoco_enable']->value == 'Y')?'checked':''; ?> name="twoco_enable" class="jqToggle" value="Y" id="jqTwoCheckout"><span class="help-inline"></span>
                    </div>
                </div> -->
                <!-- <div class="control-group jqTwoCheckout">
                   <div class="control-group">
                        <label for="twoco_testmode" class="control-label">TwoCheckout Test Mode</label>
                        <div class="controls">
                            <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['twoco_testmode']->value == 'Y')?'checked':''; ?> name="twoco_testmode" value="Y"><span class="help-inline"></span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="twoco_vendorId" class="control-label">TwoCheckout Vendor ID</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['twoco_vendorId']->value; ?>" name="twoco_vendorId">
                        </div>
                    </div>
                </div> -->

               <!-- <div class="control-group">
                    <label for="paypalpro_enable" class="control-label">Enable PayPal Pro</label>
                    <div class="controls">
                        <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['paypalpro_enable']->value == 'Y')?'checked':''; ?> name="paypalpro_enable" class="jqToggle" value="Y" id="jqPaypalPro"><span class="help-inline"></span>
                    </div>
                </div> -->
                <!-- <div class="control-group jqPaypalPro">
                   <div class="control-group">
                        <label for="paypalpro_testmode" class="control-label">PayPal Pro Test Mode</label>
                        <div class="controls">
                            <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['paypalpro_testmode']->value == 'Y')?'checked':''; ?> name="paypalpro_testmode" value="Y"><span class="help-inline"></span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="paypalpro_username" class="control-label">PayPal Pro Username</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['paypalpro_username']->value; ?>" name="paypalpro_username">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="paypalpro_password" class="control-label">PayPal Pro Password</label>
                        <div class="controls">
                            <input type="password" value="<?php echo PageContext::$response->pagePaymentContents['paypalpro_password']->value; ?>" name="paypalpro_password">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="paypalpro_signature" class="control-label">PayPal Pro Signature</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['paypalpro_signature']->value; ?>" name="paypalpro_signature">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="paypalpro_bn_code" class="control-label">PayPal Pro BN Code</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['paypalpro_bn_code']->value; ?>" name="paypalpro_bn_code" id="jqPaypalProBNCode" >
                            <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pagePaymentContents['paypalpro_bn_code']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                        </div>
                    </div>
                </div> -->

               <!-- <div class="control-group">
                    <label for="paypalexpress_enable" class="control-label">Enable PayPal Express</label>
                    <div class="controls">
                        <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['paypalexpress_enable']->value == 'Y')?'checked':''; ?> name="paypalexpress_enable" class="jqToggle" value="Y" id="jqPaypalExpress"><span class="help-inline"></span>
                    </div>
                </div> -->
                <!-- <div class="control-group jqPaypalExpress">
                   <div class="control-group">
                        <label for="paypalexpress_testmode" class="control-label">PayPal Express Test Mode</label>
                        <div class="controls">
                            <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['paypalexpress_testmode']->value == 'Y')?'checked':''; ?> name="paypalexpress_testmode" value="Y"><span class="help-inline"></span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="paypalexpress_username" class="control-label">PayPal Express Username</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['paypalexpress_username']->value; ?>" name="paypalexpress_username">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="paypalexpress_password" class="control-label">PayPal Express Password</label>
                        <div class="controls">
                            <input type="password" value="<?php echo PageContext::$response->pagePaymentContents['paypalexpress_password']->value; ?>" name="paypalexpress_password">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="paypalexpress_signature" class="control-label">PayPal Express Signature</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['paypalexpress_signature']->value; ?>" name="paypalexpress_signature">
                        </div>
                    </div>
                </div> -->


               <!-- <div class="control-group">
                    <label for="paypaladvanced_enable" class="control-label">Enable PayPal Advanced</label>
                    <div class="controls">
                        <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['paypaladvanced_enable']->value == 'Y')?'checked':''; ?> name="paypaladvanced_enable" class="jqToggle" value="Y" id="jqPaypalAdvanced"><span class="help-inline"></span>
                    </div>
                </div> -->
                <!-- <div class="control-group jqPaypalAdvanced">
                   <div class="control-group">
                        <label for="paypaladvanced_testmode" class="control-label">PayPal Advanced Test Mode</label>
                        <div class="controls">
                            <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['paypaladvanced_testmode']->value == 'Y')?'checked':''; ?> name="paypaladvanced_testmode" value="Y"><span class="help-inline"></span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="paypaladvanced_username" class="control-label">PayPal Advanced Username</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['paypaladvanced_username']->value; ?>" name="paypaladvanced_username">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="paypaladvanced_password" class="control-label">PayPal Advanced Password</label>
                        <div class="controls">
                            <input type="password" value="<?php echo PageContext::$response->pagePaymentContents['paypaladvanced_password']->value; ?>" name="paypaladvanced_password">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="paypaladvanced_partnerid" class="control-label">PayPal Advanced Partner ID</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['paypaladvanced_partnerid']->value; ?>" name="paypaladvanced_partnerid">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="paypaladvanced_vendorid" class="control-label">PayPal Advanced Vendor ID</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['paypaladvanced_vendorid']->value; ?>" name="paypaladvanced_vendorid">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="paypaladvanced_bn_code" class="control-label">PayPal Advanced BN Code</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['paypaladvanced_bn_code']->value; ?>" name="paypaladvanced_bn_code" id="jqPaypalAdvancedBNCode" >
                            <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pagePaymentContents['paypaladvanced_bn_code']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                        </div>
                    </div>
                </div> -->

                <!-- <div class="control-group">
                    <label for="paypalflow_enable" class="control-label">Enable PayPal Flow </label>
                    <div class="controls">
                        <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['paypalflow_enable']->value == 'Y')?'checked':''; ?> name="paypalflow_enable" class="jqToggle" value="Y" id="jqPaypalFlow"><span class="help-inline"></span>
                    </div>
                </div> -->
                <!-- <div class="control-group jqPaypalFlow">
                   <div class="control-group">
                        <label for="paypalflow_testmode" class="control-label">PayPal Flow Test Mode</label>
                        <div class="controls">
                            <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['paypalflow_testmode']->value == 'Y')?'checked':''; ?> name="paypalflow_testmode" value="Y"><span class="help-inline"></span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="paypalflow_partnerid" class="control-label">PayPal Flow Partner ID</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['paypalflow_partnerid']->value; ?>" name="paypalflow_partnerid">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="paypalflow_vendorid" class="control-label">PayPal Flow Vendor ID</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['paypalflow_vendorid']->value; ?>" name="paypalflow_vendorid">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="payflow_password" class="control-label">PayPal Flow Password</label>
                        <div class="controls">
                            <input type="password" value="<?php echo PageContext::$response->pagePaymentContents['payflow_password']->value; ?>" name="payflow_password">
                        </div>
                    </div>
                </div> -->


               <!-- <div class="control-group">
                    <label for="paypalflowlink_enable" class="control-label">Enable PayPal Flow Link</label>
                    <div class="controls">
                        <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['paypalflowlink_enable']->value == 'Y')?'checked':''; ?> name="paypalflowlink_enable" class="jqToggle" value="Y" id="jqPaypalFlowLink"><span class="help-inline"></span>
                    </div>
                </div> -->
                <!-- <div class="control-group jqPaypalFlowLink">
                   <div class="control-group">
                        <label for="paypalflowlink_testmode" class="control-label">PayPal Flow Link Test Mode</label>
                        <div class="controls">
                            <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['paypalflowlink_testmode']->value == 'Y')?'checked':''; ?> name="paypalflowlink_testmode" value="Y"><span class="help-inline"></span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="paypalflowlink_partnerid" class="control-label">PayPal Flow Link Partner ID</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['paypalflowlink_partnerid']->value; ?>" name="paypalflowlink_partnerid">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="paypalflowlink_vendorid" class="control-label">PayPal Flow Link Vendor ID</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['paypalflowlink_vendorid']->value; ?>" name="paypalflowlink_vendorid">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="payflowlink_password" class="control-label">PayPal Flow Link Password</label>
                        <div class="controls">
                            <input type="password" value="<?php echo PageContext::$response->pagePaymentContents['payflowlink_password']->value; ?>" name="payflowlink_password">
                        </div>
                    </div>
                </div> -->


               <!-- <div class="control-group">
                    <label for="ogone_enable" class="control-label">Enable Ogone</label>
                    <div class="controls">
                        <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['ogone_enable']->value == 'Y')?'checked':''; ?> name="ogone_enable" class="jqToggle" value="Y" id="jqOgone"><span class="help-inline"></span>
                    </div>
                </div> -->
                <!-- <div class="control-group jqOgone">
                   <div class="control-group">
                        <label for="ogone_testmode" class="control-label">Ogone Test Mode</label>
                        <div class="controls">
                            <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['ogone_testmode']->value == 'Y')?'checked':''; ?> name="ogone_testmode" value="Y"><span class="help-inline"></span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="ogone_partnerid" class="control-label"> Ogone PSPID </label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['ogone_partnerid']->value; ?>" name="ogone_partnerid">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="ogone_vendorid" class="control-label"> Ogone Passphrase </label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['ogone_vendorid']->value; ?>" name="ogone_vendorid">
                        </div>
                    </div>
                </div> -->

               <!-- <div class="control-group">
                    <label for="moneybookers_enable" class="control-label">Enable Moneybookers</label>
                    <div class="controls">
                        <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['moneybookers_enable']->value == 'Y')?'checked':''; ?> name="moneybookers_enable" class="jqToggle" value="Y" id="jqMoneybookers"><span class="help-inline"></span>
                    </div>
                </div> -->
                <!-- <div class="control-group jqMoneybookers">
                   <div class="control-group">
                        <label for="moneybookers_testmode" class="control-label">Moneybookers Test Mode</label>
                        <div class="controls">
                            <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['moneybookers_testmode']->value == 'Y')?'checked':''; ?> name="moneybookers_testmode" value="Y"><span class="help-inline"></span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="moneybookers_emailid" class="control-label"> Moneybookers Email ID </label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['moneybookers_emailid']->value; ?>" name="moneybookers_emailid">
                        </div>
                    </div>
                </div> -->


               <!-- <div class="control-group">
                    <label for="braintree_enable" class="control-label">Enable Braintree</label>
                    <div class="controls">
                        <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['braintree_enable']->value == 'Y')?'checked':''; ?> name="braintree_enable" class="jqToggle" value="Y" id="jqBrainTree"><span class="help-inline"></span>
                    </div>
                </div> -->
                <!-- <div class="control-group jqBrainTree">
                   <div class="control-group">
                        <label for="braintree_testmode" class="control-label">Braintree Test Mode</label>
                        <div class="controls">
                            <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['braintree_testmode']->value == 'Y')?'checked':''; ?> name="braintree_testmode" value="Y"><span class="help-inline"></span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="braintree_merchantId" class="control-label"> Braintree Merchant ID </label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['braintree_merchantId']->value; ?>" name="braintree_merchantId">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="braintree_publickey" class="control-label"> Braintree Public Key </label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['braintree_publickey']->value; ?>" name="braintree_publickey">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="braintree_privatekey" class="control-label"> Braintree Private Key </label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['braintree_privatekey']->value; ?>" name="braintree_privatekey">
                        </div>
                    </div>
                </div> -->


               <!-- <div class="control-group">
                    <label for="enable_googlecheckout" class="control-label">Enable Google Checkout</label>
                    <div class="controls">
                        <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['enable_googlecheckout']->value == 'Y')?'checked':''; ?> name="enable_googlecheckout" class="jqToggle" value="Y" id="jqGoogleCheckout"><span class="help-inline"></span>
                    </div>
                </div> -->
                <!-- <div class="control-group jqGoogleCheckout">
                   <div class="control-group">
                        <label for="gcheck_merchant_id" class="control-label">Google Checkout Merchant ID</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['gcheck_merchant_id']->value; ?>" name="gcheck_merchant_id">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="gcheck_merchant_key" class="control-label"> Google Checkout Merchant Key</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['gcheck_merchant_key']->value; ?>" name="gcheck_merchant_key">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="gcheck_server_type" class="control-label"> Google Checkout Server Type </label>
                        <div class="controls">
                            <select name="gcheck_server_type">
                                <option value="sandbox" <?php echo (PageContext::$response->pagePaymentContents['gcheck_server_type']->value=='sandbox')?'selected':'';?>>Sandbox</option>
                                <option value="live" <?php echo (PageContext::$response->pagePaymentContents['gcheck_server_type']->value=='live')?'selected':'';?>>Live</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="gcheck_currency" class="control-label"> Google Checkout Currency </label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['gcheck_currency']->value; ?>" name="gcheck_currency">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="gcheck_btn_checkout" class="control-label"> Google Checkout Button Type </label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['gcheck_btn_checkout']->value; ?>" name="gcheck_btn_checkout">
                        </div>
                    </div>
                </div> -->

               <!-- <div class="control-group">
                    <label for="yourpay_enable" class="control-label">Enable Yourpay</label>
                    <div class="controls">
                        <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['yourpay_enable']->value == 'Y')?'checked':''; ?> name="yourpay_enable" class="jqToggle" value="Y" id="jqYourpay"><span class="help-inline"></span>
                    </div>
                </div> -->
                <!-- <div class="control-group jqYourpay">
                   <div class="control-group">
                        <label for="yourpay_demo" class="control-label"> Yourpay Test Mode</label>
                        <div class="controls">
                            <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['yourpay_demo']->value == 'Y')?'checked':''; ?> name="yourpay_demo" value="Y" ><span class="help-inline"></span>
                        </div>
                    </div>
                   <div class="control-group">
                        <label for="yourpay_storeid" class="control-label">Yourpay Store ID</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['yourpay_storeid']->value; ?>" name="yourpay_storeid">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="yourpay_pemfile" class="control-label">Yourpay PEM file </label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['yourpay_pemfile']->value; ?>" name="yourpay_pemfile">
                        </div>
                    </div>
                </div> -->

                <!-- <div class="control-group">
                    <label for="quickbook_enable" class="control-label">Enable Quickbook</label>
                    <div class="controls">
                        <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['quickbook_enable']->value == 'Y')?'checked':''; ?> name="quickbook_enable" class="jqToggle" value="Y" id="jqQuickbook"><span class="help-inline"></span>
                    </div>
                </div> -->
                <!-- <div class="control-group jqQuickbook">
                   <div class="control-group">
                        <label for="quickbook_testmode" class="control-label"> Quickbook Test Mode</label>
                        <div class="controls">
                            <input type="checkbox" <?php echo (PageContext::$response->pagePaymentContents['quickbook_testmode']->value == 'Y')?'checked':''; ?> name="quickbook_testmode" value="Y" ><span class="help-inline"></span>
                        </div>
                    </div>
                   <div class="control-group">
                        <label for="quickbook_appname" class="control-label">Quickbook Application Name</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['quickbook_appname']->value; ?>" name="quickbook_appname">
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="quickbook_key" class="control-label">Quickbook Key </label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pagePaymentContents['quickbook_key']->value; ?>" name="quickbook_key">
                        </div>
                    </div>
                </div> -->
                <div class="controls">
                    <input type="submit" name="paymentSubmitBtn" value="Save" class="submitButton btn" />
                </div>
            </form>

        </div>
        <!-- Payment -->


        <!-- Domain Registrar -->
        <div id="domain-registrar" class="tab-pane">
            <div class="note">
                <p>
                     A domain name registrar is an organization or commercial entity that manages the reservation of Internet domain names.
                       To register domain names using iScripts GoStores you can either use enom.com or godaddy.com.
                      Please signup with one of these registrars to get a reseller account.
                     After signing up please provide the required details below.
                </p>
                <br><br>

            </div>

            <form name="DomainRegistrarForm" id="jqDomainRegistrarForm" method="post" class="form-horizontal" action="<?php echo BASE_URL; ?>cms?section=settings&tab=domain-registrar">
               <div class="control-group">
                        <label for="enableDomiainRegistration" class="control-label">Enable Domain Registration</label>
                        <div class="controls">
                            <input type="checkbox" <?php echo (PageContext::$response->pageDomainContents['enableDomiainRegistration']->value == 'Y')?'checked':''; ?> name="enableDomiainRegistration" value="Y" ><span class="help-inline"></span>
                            <a href="#" class="tooltiplink" data-original-title=" Enable / Disable Domain Registration"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                        </div>
                </div>
                <div class="control-group">
                    <label for="domain_registrar" class="control-label">Domain Registrar Type</label>
                    <div class="controls">
                        <select name="domain_registrar" class="jqDomainType" id="jqDomainTypeId">
                            <option value="GODADDY" <?php echo (PageContext::$response->pageDomainContents['domain_registrar']->value=='GODADDY')?'selected':'';?>>GODADDY</option>
                            <option value="ENOM" <?php echo (PageContext::$response->pageDomainContents['domain_registrar']->value=='ENOM')?'selected':'';?>>ENOM</option>
                        </select>
                       <a href="#" class="tooltiplink" data-original-title=" Select the registrar type"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>
                <div class="control-group">
                        <label for="priceDomiainRegistration" class="control-label">Price of Domain Registration Plan</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pageDomainContents['priceDomiainRegistration']->value; ?>" name="priceDomiainRegistration">
                        <a href="#" class="tooltiplink" data-original-title="Enter the price of domain registration plan "><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                        </div>
                </div>
                <div class="control-group jqGoDaddy" style="display:<?php echo (PageContext::$response->pageDomainContents['domain_registrar']->value=='GODADDY')?'block':'none';?>">
                    <div class="control-group">
                        <label for="godaddy_testmode" class="control-label">GoDaddy Test Mode</label>
                        <div class="controls">
                            <input type="checkbox" <?php echo (PageContext::$response->pageDomainContents['godaddy_testmode']->value == 'Y')?'checked':''; ?> name="godaddy_testmode" value="Y" ><span class="help-inline"></span>
                            <a href="#" class="tooltiplink" data-original-title=" Enable / Disable the GoDaddy test mode"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="godaddy_id">GoDaddy API Login ID</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pageDomainContents['godaddy_id']->value; ?>" name="godaddy_id">

                            <a href="#" class="tooltiplink" data-original-title=" Enter your GoDaddy API Login ID"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="secureURL" class="control-label"></label>
                      <!--   <div class="controls"> Click <a href="javascript:void(0)" onclick="loadCertifier()">here</a> to certify your Godaddy account
                        </div> -->
                    </div>
                    <div class="control-group">
                        <label for="godaddy_password" class="control-label">GoDaddy API Password</label>
                        <div class="controls">
                            <input type="password" value="<?php echo PageContext::$response->pageDomainContents['godaddy_password']->value; ?>" name="godaddy_password">
                            <a href="#" class="tooltiplink" data-original-title=" Enter your GoDaddy API password"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                            </div>

                    </div>
                </div>

                <div class="control-group jqEnom" style="display:<?php echo (PageContext::$response->pageDomainContents['domain_registrar']->value=='ENOM')?'block':'none';?>">
                    <div class="control-group">
                        <label for="enom_testmode" class="control-label">Enom Test Mode</label>
                        <div class="controls">
                            <input type="checkbox" <?php echo (PageContext::$response->pageDomainContents['enom_testmode']->value == 'Y')?'checked':''; ?> name="enom_testmode"  value="Y"><span class="help-inline"></span>
                            <a href="#" class="tooltiplink" data-original-title="Enable /Disable Enom test mode "><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="enom_user">Enom Username</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pageDomainContents['enom_user']->value; ?>" name="enom_user">
                            <a href="#" class="tooltiplink" data-original-title="Enter Enom username "><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="enom_password" class="control-label">Enom Password</label>
                        <div class="controls">
                            <input type="password" value="<?php echo PageContext::$response->pageDomainContents['enom_password']->value; ?>" name="enom_password">
                            <a href="#" class="tooltiplink" data-original-title="Enter Enom password"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                            </div>
                    </div>
                    <div class="control-group">
                        <label for="enom_password" class="control-label">WHM Server IP</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pageDomainContents['enom_uiseripd']->value; ?>" name="enom_uiseripd">
                            <a href="#" class="tooltiplink" data-original-title="Enter WHM IP Address"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                            </div>
                    </div>
                </div>
                <div class="controls">
                    <input type="submit" name="domainSubmitBtn" value="Save" class="submitButton btn" />
                </div>
            </form>
        </div>
        <!-- Domain Registrar -->


        <!-- Social Settings -->
        <div id="social-settings" class="tab-pane">
            <form name="SocialSettingsForm" id="jqSocialSettingsForm" method="post" class="form-horizontal" action="<?php echo BASE_URL; ?>cms?section=settings&tab=social-settings">
                <div class="control-group">
                    <label for="enable_fb" class="control-label">Enable Facebook</label>
                    <div class="controls">
                        <input type="checkbox" <?php echo (PageContext::$response->pageSocialSettingContents['enable_fb']->value == 'Y')?'checked':''; ?> name="enable_fb" value="Y"  class="jqToggle" id="jqFacebook" ><span class="help-inline"></span>
                        <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pageSocialSettingContents['enable_fb']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>
                <div class="control-group jqFacebook">
                    <div class="control-group">
                        <label class="control-label" for="facebookUrl">Facebook Link</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pageSocialSettingContents['facebookUrl']->value; ?>" name="facebookUrl">
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label for="enable_twitter" class="control-label">Enable Twitter</label>
                    <div class="controls">
                        <input type="checkbox" <?php echo (PageContext::$response->pageSocialSettingContents['enable_twitter']->value == 'Y')?'checked':''; ?> name="enable_twitter" value="Y"  class="jqToggle" id="jqTwitter" ><span class="help-inline"></span>
                        <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pageSocialSettingContents['enable_twitter']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>
                <div class="control-group jqTwitter">
                    <div class="control-group">
                        <label class="control-label" for="twitterUrl"> Twitter Link</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pageSocialSettingContents['twitterUrl']->value; ?>" name="twitterUrl">
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <label for="enable_ln" class="control-label">Enable LinkedIn</label>
                    <div class="controls">
                        <input type="checkbox" <?php echo (PageContext::$response->pageSocialSettingContents['enable_ln']->value == 'Y')?'checked':''; ?> name="enable_ln" value="Y"  class="jqToggle" id="jqLinkedIn" ><span class="help-inline"></span>
                        <a href="#" class="tooltiplink" data-original-title="<?php echo PageContext::$response->pageSocialSettingContents['enable_ln']->helpText; ?>"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>
                <div class="control-group jqLinkedIn">
                    <div class="control-group">
                        <label class="control-label" for="linkedInUrl"> LinkedIn Link</label>
                        <div class="controls">
                            <input type="text" value="<?php echo PageContext::$response->pageSocialSettingContents['linkedInUrl']->value; ?>" name="linkedInUrl">
                        </div>
                    </div>
                </div>
                <div class="controls">
                    <input type="submit" name="socialSubmitBtn" value="Save" class="submitButton btn" />
                </div>
            </form>
        </div>
        <!-- Social Settings -->

        <!-- Name Servers -->
        <div id="name-servers" class="tab-pane">
            <div class="note">
                <p>
                     iScripts GoStores requires you to have a reseller account with a webhosting company. The cart software will be automatically installed on this reseller account for your customers. For the domain names to show up these cart correctly, the name servers provided during domain registration should match the reseller account's nameserver. The details of the nameservers could be obtained from the web hosting company.
                </p>
                <br><br>
            </div>

            <form name="NameServersForm" id="jqNameServersForm" method="post" class="form-horizontal" action="<?php echo BASE_URL; ?>cms?section=settings&tab=name-servers">
                <div class="control-group">
                    <label for="name_server_1" class="control-label">Name Server 1</label>
                    <div class="controls">
                        <input type="text" value="<?php echo PageContext::$response->pageNameServerContents['name_server_1']->value; ?>" name="name_server_1">
                        <a href="#" class="tooltiplink" data-original-title="Enter Name Server 1 Details"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>
                <div class="control-group">
                    <label for="name_server_2" class="control-label">Name Server 2</label>
                    <div class="controls">
                        <input type="text" value="<?php echo PageContext::$response->pageNameServerContents['name_server_2']->value; ?>" name="name_server_2">
                         <a href="#" class="tooltiplink" data-original-title="Enter Name Server 2 Details"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>
                <div class="control-group">
                    <label for="name_server_3" class="control-label">Name Server 3</label>
                    <div class="controls">
                        <input type="text" value="<?php echo PageContext::$response->pageNameServerContents['name_server_3']->value; ?>" name="name_server_3">
                         <a href="#" class="tooltiplink" data-original-title="Enter Name Server 3 Details"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>
                <div class="control-group">
                    <label for="name_server_4" class="control-label">Name Server 4</label>
                    <div class="controls">
                        <input type="text" value="<?php echo PageContext::$response->pageNameServerContents['name_server_4']->value; ?>" name="name_server_4">
                         <a href="#" class="tooltiplink" data-original-title="Enter Name Server 4 Details"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>
                    </div>
                </div>

                <div class="controls">
                    <input type="submit" name="nameServerSubmitBtn" value="Save" class="submitButton btn" />
                </div>
            </form>
        </div>


          <!-- Name Servers -->
        <div id="smtp-details" class="tab-pane">
            <div class="note">
                <p>

                </p>
                <br><br>
            </div>

            <form name="SMTPForm" id="jqSMTPForm" method="post" class="form-horizontal" action="<?php echo BASE_URL; ?>cms?section=settings&tab=smtp-details">

                <div class="control-group">
                    <label for="smtp_host" class="control-label">SMTP Host</label>
                    <div class="controls">
                        <input type="text" value="<?php echo PageContext::$response->pageSMTPDataContents['smtp_host']->value; ?>" name="smtp_host">


                    </div>
                </div>

                <div class="control-group">
                    <label for="smtp_username" class="control-label">SMTP Username</label>
                    <div class="controls">
                        <input type="text" value="<?php echo PageContext::$response->pageSMTPDataContents['smtp_username']->value; ?>" name="smtp_username">
<!--                        <a href="#" class="tooltiplink" data-original-title="Enter Name Server 1 Details"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>-->
                    </div>
                </div>
                <div class="control-group">
                    <label for="smtp_password" class="control-label">SMTP Password</label>
                    <div class="controls">
                        <input type="text" value="<?php echo PageContext::$response->pageSMTPDataContents['smtp_password']->value; ?>" name="smtp_password">

                    </div>
                </div>

                <div class="control-group">
                    <label for="smtp_port" class="control-label">SMTP Port</label>
                    <div class="controls">
                        <input type="text" value="<?php echo PageContext::$response->pageSMTPDataContents['smtp_port']->value; ?>" name="smtp_port">

                    </div>
                </div>

                 <div class="control-group">
                    <label for="smtp_protocol" class="control-label">SMTP Protocol</label>
                    <div class="controls">
                        <input type="radio" name="smtp_protocol" value="ssl" <?php if(PageContext::$response->pageSMTPDataContents['smtp_protocol']->value=='ssl'){echo "checked";} ?>> SSL  <input type="radio" name="smtp_protocol" value="tls" <?php if(PageContext::$response->pageSMTPDataContents['smtp_protocol']->value=='tls'){echo "checked";} ?>> TLS
                    </div>
                </div>

                <div class="controls">
                    <input type="submit" name="SMTPSubmitBtn" value="Save" class="submitButton btn" />
                </div>
            </form>
        </div>


        <!-- Social Settings -->

        <div id="password" class="tab-pane">
            <form name="passwordForm" id="jqPasswordForm" method="post" class="form-horizontal" action="<?php echo BASE_URL; ?>cms?section=settings&tab=password">
                <div class="control-group">
                    <label class="control-label">Current Password <span style="color: red;">*</span></label>
                    <div class="controls">
                        <input type="password" value="" name="current_password"></div>
                </div>

                <div class="control-group">
                    <label class="control-label">New Password <span style="color: red;">*</span></label>
                    <div class="controls">
                        <input type="password" value="" name="new_password"></div>
                </div>

                <div class="control-group">
                    <label class="control-label">Re-Type Password <span style="color: red;">*</span></label>
                    <div class="controls">
                        <input type="password" value="" name="retype_password"></div>
                </div>

                <div class="controls">
                    <input type="submit" name="passwordSubmitBtn" value="Save" class="submitButton btn" />
                </div>
            </form>
        </div>
        <!-- Server Settings -->
        <?php
        // Commenting this for the immediate release
        /*
        <div id="server-settings" class="tab-pane">
            <div class="note">
                <p>
                     iScripts GoStores requires you to chose an operation mode for cart software installation. The cart software will be automatically installed on this reseller account for your customers based on the server settings whether your site and cart installation account is single server or two different servers. If your option is multiple server, then temporary urls should be on and it is required to configure your server settings to "Allow users to park
subdomains/domains of the server's hostname".
                </p>
                <br><br>
            </div>

            <form name="ServerSettingsForm" id="jqServerSettingsForm" method="post" class="form-horizontal" action="<?php echo BASE_URL; ?>cms?section=settings&tab=server-settings">
                <div class="control-group">
                    <label for="site_operation_mode" class="control-label">Site Operation Mode<a href="#" class="tooltiplink" data-original-title="Choose the option 'single server' if your website and store installations are in same server else 'multiple server'"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a></label>

                    <div class="controls">
                        <?php
                        $siteOperationMode = PageContext::$response->pageServerSettingContents['site_operation_mode']->value;
                        $operationModeCheck1 = ($siteOperationMode=='S') ? ' checked="checked"' : '';
                        $operationModeCheck2 = ($siteOperationMode=='M') ? ' checked="checked"' : '';
                        ?>
                        Single Server &nbsp;&nbsp;&nbsp;<input type="radio" value="S" name="site_operation_mode"<?php echo $operationModeCheck1; ?>><br>
                        Multiple Server &nbsp;<input type="radio" value="M" name="site_operation_mode"<?php echo $operationModeCheck2; ?>>
                    </div>
                </div>
                <div class="control-group">
                    <label for="site_operation_park_domain" class="control-label">Temporary URLs<a href="#" class="tooltiplink" data-original-title="If you have chosen 'Yes', please configure your server setings to 'Allow users to park subdomains/domains of the server's hostname'"><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a></label>
                    <div class="controls">
                        <?php
                        $siteOperationParkDomain = PageContext::$response->pageServerSettingContents['site_operation_park_domain']->value;
                        $operationPDCheck1 = ($siteOperationParkDomain=='Y') ? ' checked="checked"' : '';
                        $operationPDCheck2 = ($siteOperationParkDomain=='N') ? ' checked="checked"' : '';
                        ?>
                        Yes&nbsp;<input type="radio" value="Y" name="site_operation_park_domain"<?php echo $operationPDCheck1; ?>><br>
                        No&nbsp;&nbsp;<input type="radio" value="N" name="site_operation_park_domain"<?php echo $operationPDCheck2; ?>>
                    </div>
                </div>
                <div class="controls">
                    <input type="submit" name="serverSettingsSubmitBtn" value="Save" class="submitButton btn" />
                </div>
            </form>
        </div>
         */
        ?>
        <!-- End Server Settings -->
    </div>
</div>
<?php //print_r(PageContext::$response->pageContents); ?>
