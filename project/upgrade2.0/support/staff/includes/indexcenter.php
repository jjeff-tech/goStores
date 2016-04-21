
<div class="home_column_center">
    <div class="home_box_struct">







        <div class="home_box_title">
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
                    <td  align="right" width="30%">
<?php echo TEXT_USER_ID; ?>&nbsp;&nbsp;
                    </td>

                    <td align="left">
                        <input name="txtUserID" type="text"  value="<?php echo(htmlentities($_POST["txtUserID"])); ?>">
                    </td>
                </tr>





                <tr>

                    <td align="right">
<?php echo TEXT_PASSWORD; ?>&nbsp;&nbsp;
                    </td>

                    <td align="left">
                        <input name="txtPassword" type="password"  onKeyPress="javascript:passPress();">
                    </td>
                </tr>

                <tr>	
                    <td>&nbsp;</td>					
                    <td align="left" valign="top" >
                        <input name="btnSubmit" type="submit"  value="<?php echo TEXT_LOGIN; ?>" onClick="return checkLoginForm();">
                        <input type="hidden" name="postback" value="">
                        <!--<a href="#">Forgot Password?</a>-->
                    </td>




                </tr>	

            </table>
        </div>
    </div>
</div>







