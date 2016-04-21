<div class="contentarea_wrapper">
    <div class="content_area_wrapper">
        <div class="content_area_inner">

                <div class="main_headings">
                    <h2>Forgot Password</h2>
                </div>
                <?php
                    if($this->displayResetForm){
                ?>
            <div class="forgot-password-wrapper width-fix">

                        <form action="<?php echo ConfigUrl::base(); ?>index/resetpassword" method="post" id="jqResetPwd">
                            <input type="hidden" value="<?php echo $this->userid;?>" name="val" />
                            <input type="hidden" value="<?php echo $this->activationKey;?>" name="key" />
                            <ul>
                                <li>
                                    <?php PageContext::renderPostAction($this->messagefunction); ?></li>
                                <li>

                                    <input name="password" placeholder="Password" id="password" validate="required:true" minlength="8" class="txt_area" type="password" value="<?php echo $_POST['password']; ?>">
                                </li>
                                <li>

                                    <input name="confirm_password" placeholder="Confirm Password" validate="required:true" minlength="8"  id="confirm_password" class="txt_area" type="password" value="<?php echo $_POST['confirm_password']; ?>">
                                </li>
                                <li><input type="submit" name="submitLogin" id="jqSubmit" class="small-btn" value="Reset Password" /></li>
                            </ul>
                        </form>

                </div>
                <?php
              }else{ ?>
                    <div id="message-red">
                    <div class="error"><?php echo PageContext::$response->error_message ;?></div>
                    </div>
                <?php }
                ?>

                <!-- <div class="frgt_pwd_wrapper">
                    <div class="forgot_password_header">
                        <h4 class="sub">We will send you reset password link upon providing email.</h4>
                    </div>
                    <div class="pass_word_container1">
                        <form action="<?php echo ConfigUrl::base(); ?>index/forgotpwd" method="post" id="jqForgotPwd">
                            <ul>
                                <li>
                                    <?php PageContext::renderPostAction($this->messagefunction); ?></li>
                                <li>
                                    <span class="r_label"><label>Email Address</label></span>
                                    <input type="text"  class="txt_area" name="txtEmail" value="" id="txtEmail">
                                </li>
                                <li><input type="submit" class="orng_btnfreetrail" name="Submit" value="Submit" id="jqSubmit"></li>
                            </ul>
                        </form>
                    </div>
                </div> -->
                <div class="clear"></div>


            <div class="clear"></div>
        </div>
    </div>
    <div class="clear"></div>
</div>
