<div class="forgot_container">
    <?php PageContext::renderPostAction($this->messageFunction, 'settings');?>
<h1>Forgot Password</h1>
<form method="post" id="frmuserPassword" action="<?php echo BASE_URL; ?>admin/login/forgotpassword/">
    
    <table width="100%" cellpadding="0" cellspacing="3" border="0">
        <tr>
            <td width="35%" align="left"><label for="useremail">Please provide the email address to receive the password<span class="mandred">*</span></label></td>
            <td width="38%" align="left"><div class="search_box4"><input id="txtuseremail" class="forgot_search_box" name="txtuseremail" value="" title="useremail" tabindex="1" type="text"/></div></td>
            <td width="15%" align="center"><input id="submit" name="submit"  class="forgot_pass" value="Get Password" tabindex="2" type="submit"/></td>
            <td width="12%" align="center"><div class="addnew"><a  href="<?php echo BASE_URL;?>admin/index">Back</a></div></td>
        </tr>
        <tr>
            <td align="left">&nbsp;</td>
            <td height="40" colspan="3" align="left" valign="middle"><div class="errorBox red"><?php echo $this->errMsg ?></div></td>
        </tr>                   
    </table>
</form>
</div>