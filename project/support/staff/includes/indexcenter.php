
<div class="adm_home_column_center">
    <div class="adm_home_box_struct">







        <div class="staff_home_box_title">
            STAFF LOGIN
        </div>
        <div class="home_box_content">				

            <?php if ($error) { ?>
            <div class="msg_error"><?php echo $errormessage; ?>	</div>
                <?php }


            if ($message) {
                ?>
            <div class="msg_success"><?php echo $infomessage; ?></div>
                <?php } ?>

            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="home_login">

                <tr>
                    <td  align="right" width="30%" style="font-family:'open Sans Condensed', arial; font-size: 16px;">
                        <?php echo TEXT_USER_ID; ?>&nbsp;&nbsp;
                    </td>

                    <td align="left">
                        <input name="txtUserID" type="text" class="jQcheckcontent" value="<?php echo(htmlentities($_POST["txtUserID"])); ?>">
                    </td>
                </tr>
                <tr>

                    <td align="right" style="font-family:'open Sans Condensed', arial; font-size: 16px;">
                        <?php echo TEXT_PASSWORD; ?>&nbsp;&nbsp;
                    </td>

                    <td align="left">
                        <input name="txtPassword" type="password" class="jQcheckcontent"  onKeyPress="javascript:passPress();">
                    </td>
                </tr>

                <tr>	
                    <td>&nbsp;</td>					
                    <td align="left" valign="top" >
                        <input name="btnSubmit" type="submit" class="staflogin_submit_btn"  value="<?php echo TEXT_LOGIN; ?>" onClick="return checkLoginForm();">
                        <input type="hidden" name="postback" value="">
                        <!--<a href="#">Forgot Password?</a>-->
                    </td>




                </tr>	

            </table>
        </div>
    </div>
</div>







