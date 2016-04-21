<div class="form_container">
    <div class="form_top">Settings</div>
    <div class="form_bgr">
        <!-- ****** Success Message Area ************* -->
        <?php if(!empty($this->message)) { ?>
        <div class="green_box"><?php echo $this->message; ?></div>
            <?php } ?>
        <div  align="left">
            <?php PageContext::renderPostAction('successmessage');
            $this->messageFunction ='';?>
        </div>
        <!-- ****** Success Message Area Ends ************* -->
        <!-- *** Site Tab Area *** -->
        <div class="admin_tab_menu">
            <ul>
                <li><a href="<?php echo BASE_URL;?>admin/settings/index" class="selected">General Settings</a></li>
                <li><a href="<?php echo BASE_URL;?>admin/settings/payments">Payment Settings</a></li>
            </ul>
            <div class="clear"></div>
        </div>
        <!-- *** Site Tab Area Ends *** -->
        <div class="admin_tab_contents">
            <!-- ****** Admin side setting Area ************* -->
            <!-- Site Settings -->
            <div>
                <form id="frmSettings" name="frmSettings" method="post" action="" enctype="multipart/form-data">
                    <table  width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="formstyle">
                        <tr>
                            <td align="left" valign="top" width="25%">Site Name<span class="mandred">*</span></td>
                            <td align="left" valign="top"><input type="text" id="siteName" name="siteName" validate="required:true" value="<?php echo stripslashes($this->setting['siteName']);?>"/></td>
                        </tr>
                        <tr>
                            <td align="left" valign="top">Site Secure URL</td>
                            <td align="left" valign="top"><input type="text"  id="secureURL" name="secureURL" value="<?php echo stripslashes($this->setting['secureURL']);?>"/></td>
                        </tr>
                        <tr>
                            <td align="left" valign="top"> Admin Email <span class="mandred">*</span></td>
                            <td align="left" valign="top"><input type="text" id="adminEmail" class="{required:true, email:true, messages:{required:'Please enter your email address', email:'Please enter a valid email address'}}"  validate="required:true" name="adminEmail" value="<?php echo stripslashes($this->setting['adminEmail']);?>" /></td>
                        </tr>
                  <!--      <tr>
                            <td height="37" align="left" valign="top"> Enable Site Banner :</td>
                            <td height="37"><?php //echo $this->radio('enablesiteBanner', array('Y','N'), $this->setting['enablesiteBanner'] , 'rdo_btn', array('&nbsp;Yes&nbsp;','&nbsp;No&nbsp;'), array('enablesiteBanner1','enablesiteBanner2')) ?></td>
                        </tr>
                        <tr>
                            <td height="55" align="left" valign="top"> Site Banner :<br>
                          (Ideal size is 728 X 100 or greater.)</td>
                            <td height="55"><input type="file" class="txt_area" name="siteBanner">-->
                        <?php
                        // $siteBanner = BASE_PAtd.'project/styles/images/'.$this->setting['siteBanner'];
                        //  if(is_file($siteBanner)) {
                        ?>
                                  <!--  <a href="<?php //echo BASE_URL.'project/styles/images/'.$this->setting['siteBanner'];?>" title="Site Banner" class="tdickbox"><img src="<?php echo BASE_URL; ?>project/styles/images/preview_file.png" border="0" /></a>-->
                        <?php
                        //  }
                        ?>
                        <!--  </td>
                        </tr>
                        <tr>
                            <td height="37" align="left" valign="top">Banner URL</td>
                            <td height="37">
                                <input type="text" class="txt_area" name="banner_link" value="<?php echo $this->setting['banner_link']; ?>">
                            </td>
                        </tr>-->
                        <tr>
                            <td align="left" valign="top"> Meta Keywords </td>
                            <td align="left" valign="top"><textarea  name="metaKeywords"  cols="30" rows="5"></textarea></td>

                        </tr>
                        <tr>
                            <td align="left" valign="top"> Meta Description</td>
                            <td align="left" valign="top"><textarea  name="metaDescription" cols="30" rows="5"></textarea></td>

                        </tr>
                <!--        <tr>
                            <td height="37" align="left" valign="top"> Enable Google AdSense :</td>
                            <td height="37"><?php //echo $this->radio('enableGoogleAdsense', array('Y','N'), $this->setting['enableGoogleAdsense'] , 'rdo_btn', array('&nbsp;Yes&nbsp;','&nbsp;No&nbsp;'), array('enableGoogleAdsense1','enableGoogleAdsense2')) ?></td>

                        </tr>
                        <tr>
                            <td height="37" align="left" valign="top"> Google AdSense Value :</td>
                            <td height="37"><textarea class="txt_area" name="googleAdsense" cols="75" rows="2"><?php //echo $this->setting['googleAdsense'] ?></textarea></td>

                        </tr>-->
                        <tr>
                            <td  align="left" valign="top"> Google Analytics Value </td>
                            <td align="left" valign="top"><textarea  name="googleAnalytics" cols="30" rows="5"><?php echo $this->setting['googleAnalytics']; ?></textarea></td>

                        </tr>
                        
                       
                    </table>


                    <table  width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="formstyle">
                        <tr>
                            <td colspan="2"> <div class="comm_box3">
                                    <h4>Domain Registrar Settings</h4>
                                </div></td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" width="25%">Domain Registrar<span class="mandred">*</span></td>
                            <td align="left" valign="top"><input type="text" id="domain_registrar" name="domain_registrar" validate="required:true" value="<?php echo stripslashes($this->setting['domain_registrar']);?>"/></td>
                        </tr>
                        <tr>
                            <td align="left" valign="top">User Id<span class="mandred">*</span></td>
                            <td align="left" valign="top"><input type="text"  id="enom_uiseripd" name="enom_uiseripd" validate="required:true" value="<?php echo stripslashes($this->setting['enom_uiseripd']);?>"/></td>
                        </tr>
                        <tr>
                            <td align="left" valign="top"> Password <span class="mandred">*</span></td>
                            <td align="left" valign="top"><input type="text" id="enom_password"   validate="required:true" name="enom_password" value="<?php echo stripslashes($this->setting['enom_password']);?>" /></td>
                        </tr>
                        <tr>
                            <td height="37" align="left">Test Mode</td>
                            <td height="37" align="left"><input   type="checkbox" name="enom_testmode" id="enom_testmode" value="YES" <?php echo $val= ($this->setting['enom_testmode']=='YES') ? 'checked="checked"' : '' ;?>></td>
                        </tr>
                    </table>
                    <table  width="98%" border="0" align="left" cellpadding="0" cellspacing="0" class="formstyle">
                        <tr>
                            <td colspan="2"> <div class="comm_box3">
                                    <h4>Name Server Settings</h4>
                                </div></td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" width="25%">Name Server 1<span class="mandred">*</span></td>
                            <td align="left" valign="top"><input type="text" id="NS1" name="NS1" validate="required:true" value="<?php echo stripslashes($this->setting['NS1']);?>"/></td>
                        </tr>
                        <tr>
                            <td align="left" valign="top">Name Server 2<span class="mandred">*</span></td>
                            <td align="left" valign="top"><input type="text"  id="NS2" name="NS2" validate="required:true" value="<?php echo stripslashes($this->setting['NS2']);?>"/></td>
                        </tr>
                        <tr>
                            <td align="left" valign="top">Name Server 3</td>
                            <td align="left" valign="top"><input type="text" id="NS3"    name="NS3" value="<?php echo stripslashes($this->setting['NS3']);?>" /></td>
                        </tr>
                        <tr>
                            <td height="37" align="left">Name Server 4</td>
                            <td height="37" align="left"><input type="text" id="NS4"    name="NS4" value="<?php echo stripslashes($this->setting['NS4']);?>" /></td>
                        </tr>
                    </table>
                    <table  width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="formstyle">
                        <tr>
                            <td colspan="2"> <div class="comm_box3">
                                    <h4>Server Settings</h4>
                                </div></td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" width="25%">WHM Login<span class="mandred">*</span></td>
                            <td align="left" valign="top"><input type="text" id="whm_user_login" name="whm_user_login" validate="required:true" value="<?php echo stripslashes($this->setting['whm_user_login']);?>"/></td>
                        </tr>
                        <tr>
                            <td align="left" valign="top">WHM Password<span class="mandred">*</span></td>
                            <td align="left" valign="top"><input type="text"  id="whm_user_password" name="whm_user_password" validate="required:true" value="<?php echo stripslashes($this->setting['whm_user_password']);?>"/></td>
                        </tr>
                        <tr>
                            <td align="left" valign="top">WHM Host<span class="mandred">*</span></td>
                            <td align="left" valign="top"><input type="text" id="whm_user_host"    name="whm_user_host" validate="required:true" value="<?php echo stripslashes($this->setting['whm_user_host']);?>" /></td>
                        </tr>
                        <tr>
                            <td align="left" valign="top">Subdomain Location<span class="mandred">*</span></td>
                            <td align="left" valign="top"><input type="text"  id="subdomain_location" name="subdomain_location" validate="required:true" value="<?php echo stripslashes($this->setting['subdomain_location']);?>"/></td>
                        </tr>
                        <tr>
                            <td align="left" valign="top">Product Location<span class="mandred">*</span></td>
                            <td align="left" valign="top"><input type="text" id="product_location"    name="product_location" validate="required:true" value="<?php echo stripslashes($this->setting['product_location']);?>" /></td>
                        </tr>
                    </table>

                    <table  width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="formstyle">
                        <tr>
                            <td colspan="2"> <div class="comm_box3">
                                    <h4>Cron/Scheduled Jobs</h4>
                                </div></td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" width="25%">Bill Auto Pay Initial Attempt URL</td>
                            <td align="left" valign="top"><?php echo BASE_URL; ?>admin/crongeneration/</td>
                        </tr>
                        <tr>
                            <td align="left" valign="top">Bill Auto Pay Attempts URL</td>
                            <td align="left" valign="top"><?php echo BASE_URL; ?>admin/crongeneration/billattempt</td>
                        </tr>
                        <tr>
                            <td align="left" valign="top">Free Trial Expiry Notification URL</td>
                            <td align="left" valign="top"><?php echo BASE_URL; ?>admin/crongeneration/freetrialexpirynotification</td>
                        </tr>
                        <tr>
                            <td align="left" valign="top">Bill Notification URL</td>
                            <td align="left" valign="top"><?php echo BASE_URL; ?>admin/crongeneration/billnotification</td>
                        </tr>                      
                    </table>

                    <table  width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="formstyle">

                        <tr>
                            <td width="25%">&nbsp;</td>
                            <td align="left"  valign="top">
                                <input type="submit"  value="Save Changes"  name="submit" class="btn_styles"/>
                            </td>
                        </tr>
                    </table>
                </form>
                <!-- ****** Admin side setting Area Ends ******** -->
            </div>
        </div>
        <div class="form_bottom"></div>

    </div>
</div>

