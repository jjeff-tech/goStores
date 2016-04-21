<div class="login_screen" style="position:relative;">	    
    <form method="post" id="frmuserLogin" action="<?php echo BASE_URL; ?>admin/login">
    <div class="errorBox errorbox_contain"><?php echo $this->errMsg ?></div>
	   <table width="351" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td height="100" colspan="2"><div class="adminstrator_text"><h2>ADMINISTRATOR</h2></div></td>
            </tr>
            <tr align="left">
              <td height="30" colspan="2" class="gray_text">User Name </td>
            </tr>
            <tr align="center" valign="middle">
              <td height="31" colspan="2">
                <div class="login_text_field_bgr">
                       <input id="txtUsername" name="txtUsername" value="" title="username" class="login_text_field" tabindex="4" type="text"/>
                </div>
               </td>
            </tr>
            <tr align="left">
              <td height="37" colspan="2" class="gray_text">Password</td>
            </tr>
            <tr>
              <td height="31" colspan="2">
                  <div class="login_text_field_bgr">
			<input id="txtPassword" name="txtPassword" value="" title="password" class="login_text_field2" tabindex="5" type="password" />
		  </div></td>
            </tr>
            <tr>
                <td width="240" height="66" align="left"><a href="<?php echo BASE_URL ;?>admin/login/forgotpassword" id="resend_password_link" class="forgot_password">Forgot your password?</a></td>
              <td width="111" align="right" valign="middle">
			  <input id="submitLogin" name="submitLogin" value="LOGIN" tabindex="6" type="submit" class="button_orange"/>
			</td>
            </tr>
          </table>
   </form>
</div>