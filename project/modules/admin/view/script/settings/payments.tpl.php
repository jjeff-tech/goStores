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
                <li><a href="<?php echo BASE_URL;?>admin/settings/index">General Settings</a></li>
                <li><a href="<?php echo BASE_URL;?>admin/settings/payments" class="selected">Payment Settings</a></li>
            </ul>
            <div class="clear"></div>
        </div>
        <!-- *** Site Tab Area Ends *** -->
        <div class="admin_tab_contents">
            <!-- ****** Admin side setting Area ************* -->
            <!-- Site Settings -->
            <form action="" method="POST" id="frmPaymentSettings" name="frmPaymentSettings" class="formstyle">
           <!--     <div class="comm_box2">
                    <h4>Currency Settings</h4>
                </div>


                <table border="0" width="98%" align="center" cellspacing="1" cellpadding="0">
                    <tr class="row_color1">
                        <td align="left" width="30%">Admin Currency</td>
                        <td align="left"><input type="text" id="currency" name="currency" class="comm_input"  value="<?php echo $this->currency;?>"></td>
                    </tr>
                </table>-->

                <div class="comm_box2">
                    <h4>Paypal Settings</h4>
                </div>

                <table  width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="formstyle">

                    <tr>
                        <td align="left" valign="top" width="25%">Enabled</td>
                        <td align="left" valign="top"><input id="p_paypal" name="p_paypal" type="checkbox" <?php echo $val= ($this->enablePaypal=='Y') ? 'checked="checked"' : '';?>></td>
                    </tr>
                    <tr>
                        <td align="left" valign="top">PayPal Email</td>
                        <td align="left" valign="top"><input id="p_email" name="p_email" type="text" class="comm_input" value="<?php echo $this->paypalEmail;?>"></td>
                    </tr>
                    <tr>
                        <td align="left" valign="top">Paypal SandBox</td>
                        <td align="left" valign="top"><input id="p_sandbox" name="p_sandbox" type="checkbox" <?php echo $val= ($this->enableSandBox=='Y') ? 'checked="checked"' : '' ;?>></td>
                    </tr>
                    <tr>
                        <td align="left" valign="top">PayPal Token</td>
                        <td align="left" valign="top"><input id="p_tocken" name="p_tocken" class="comm_input" type="text" value="<?php echo $this->paypalTocken;?>" size="30"></td>
                    </tr>



                </table>





                <div class="comm_box2">
                    <h4>Authorize.net Settings</h4>
                </div>

                <table  width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="formstyle">
                    <tr>
                        <td align="left" valign="top" width="25%">Enable</td>
                        <td align="left" valign="top"><input type="checkbox" name="e_auth"  <?php echo $val= ($this->authEnable=='Y') ? 'checked="checked"' : '';?>></td>
                    </tr>
                    <tr>
                        <td align="left" valign="top">Merchant Email</td>
                        <td align="left" valign="top"><input id="a_email"  class="comm_input" name="a_email" type="text" value="<?php echo $this->authEmail;?>"></td>
                    </tr>
                    <tr>
                        <td height="37" align="left">Test Mode</td>
                        <td height="37" align="left"><input   type="checkbox" name="a_test" <?php echo $val= ($this->authTestMode=='Y') ? 'checked="checked"' : '' ;?>></td>
                    </tr>
                    <tr>
                        <td align="left" valign="top">Login Id</td>
                        <td align="left" valign="top"><input id="a_logid" class="comm_input" name="a_logid" type="text" value="<?php echo $this->authLoginId;?>"></td>
                    </tr>
                    <tr>
                        <td align="left" valign="top">Transaction Key</td>
                        <td align="left" valign="top"><input id="a_tkey" class="comm_input" name="a_tkey" type="text" value="<?php echo $this->authtransKey;?>"></td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td  align="left">
                           <div class="comm_div"><br><input type="submit" class="btn_styles" name="Save" value="Save Changes"></div>

                        </td>
                    </tr>

                </table>



            </form>
            <!-- ****** Admin side setting Area Ends ******** -->
        </div>

    </div>
    <div class="form_bottom"></div>

</div>
