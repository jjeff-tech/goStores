<div class="container">
<div class="contentarea_wrapper">
    <div class="content_area_wrapper">
        <div class="content_area_inner">
            <div class="fgpwd-outer">
                <div class="main_headings">
                    <h2>Forgot Password</h2>
                </div>
                <div class="forgot-password-wrapper">
                    <div class="forgot_password_header">
 <h4>We will send you reset password link upon providing email.</h4>
                    </div>
                    <div class="pass_word_container1">
                        <form action="<?php echo ConfigUrl::base(); ?>index/forgotpwd" method="post" id="jqForgotPwd">
                          
                             
                                    <?php PageContext::renderPostAction($this->messagefunction); ?>
                                
                                   <div class="full-width">
                                    <input type="text"  class="form-control" name="txtEmail" value="" id="txtEmail" placeholder="Email Address">
                                </div>
                                <div class="full-width">
                                <input type="submit" class="small-btn" name="Submit" value="Submit" id="jqSubmit">
                            </div>
                            
                        </form>
                    </div>
                </div>
                <div class="clear"></div>
            </div>

            <div class="clear"></div>
        </div>
    </div>
    <div class="clear"></div>
</div>
</div>



