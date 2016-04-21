<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>    		                      |
// |          									                          |
// +----------------------------------------------------------------------+
	if ($_GET["id"] != "") {
		$var_id = $_GET["id"];
	}
	elseif ($_POST["id"] != "") {
		$var_id = $_POST["id"];
	} 
	if ($_GET["stylename"] != "") {
		$var_styleminus = $_GET["styleminus"];
		$var_stylename = $_GET["stylename"];
		$var_styleplus = $_GET["styleplus"];
	}
	else {
		$var_styleminus = $_POST["styleminus"];
		$var_stylename = $_POST["stylename"];
		$var_styleplus = $_POST["styleplus"];
	}	
	//$var_userid = $_SESSION["sess_staffid"];
	$var_staffid = 1;
	if ($_POST["postback"] == "" && $var_id != "") {
		
		$sql = "Select u.nUserId,u.nCompId,u.vUserName,u.vEmail,u.vLogin,u.ddate,u.vOnline,";
		$sql .= "u.vBanned , c.vCompName FROM sptbl_users u INNER JOIN 
		sptbl_companies c ON c.nCompId = u.nCompId
		 where u.nUserId = '" . addslashes($var_id) . "' AND u.vDelStatus='0' ";
		$var_result = executeSelect($sql,$conn); 
		if (mysql_num_rows($var_result) > 0) {
			$var_row = mysql_fetch_array($var_result);
			
			$var_userName = $var_row["vUserName"];
			$var_userLogin = $var_row["vLogin"];
			$var_password = "";
			$var_online = $var_row["vOnline"];
			$var_email = $var_row["vEmail"];
			$var_banned = $var_row["vBanned"];
			$var_compId = $var_row["nCompId"];
			$var_date = $var_row["ddate"];
			$var_comp_name = $var_row["vCompName"];
		}
		else {
			$var_message = "<font color=red>" . MESSAGE_RECORD_ERROR . "</font>";
		}
		mysql_free_result($var_result);
	}
	
	

?>
<form name="frmUser" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>">


    <div class="content_section">
    <div class="content_section_title">
	<h3><?php echo TEXT_VIEW_USER ?></h3>
	</div>

    
     
   
         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
         <tr>
         
         </tr>
         <tr>
         <td width="100%" align="center" colspan=3 class="messsagebar">
         <?php
												
												if ($var_message != ""){
												?>
													<div class="msg_error">
												<?php echo($var_message); ?>
												</div>
												<?php
												}
												?>

         </tr>
               <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="13%" align="left">&nbsp;</td>
         <td width="26%" align="left" class="toplinks"><?php echo TEXT_USER_NAME?></td>
         <td width="61%" align="left">
         <?php echo htmlentities($var_userName); ?>
         </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_USER_LOGIN ?></td>
                      <td width="61%" align="left">
                        <?php echo htmlentities($var_userLogin); ?>
					</td>
                      </tr>
                      

                      <tr><td colspan="3">&nbsp;</td></tr>
					  <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_USER_EMAIL?></td>
                      <td width="61%" align="left">
                      <?php echo htmlentities($var_email); ?>
                      </td>
                      </tr>
						<tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="13%" align="left">&nbsp;</td>
         <td width="26%" align="left" class="toplinks"><?php echo TEXT_USER_COMPANY?></td>
         <td width="61%" align="left">
         <?php echo(htmlentities($var_comp_name)); ?>	
         </td>
         </tr>
                      
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_USER_BANNED?></td>
                      <td width="61%" align="left">
                      <?php echo(($var_banned == 1)?"Yes":"No"); ?>
                      </td>
                      </tr>
                      <tr></tr>
																																							
                              </table>
                      
                  
				  
            <table width="100%"  border="0" cellspacing="10" cellpadding="0">
              <tr>
                <td>
                   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center"  class="whitebasic">
                                    <td width="22%">&nbsp;</td>
                                    <td width="16%" colspan="4"><input name="btnBack" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_BACK; ?>" onClick="javascript:goBack();"></td>
                                    
                                    <td width="20%">
									<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
									<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
									<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">																
									<input type="hidden" name="id" value="<?php echo($var_id); ?>">
									<input type="hidden" name="postback" value="">
									</td>
                                  </tr>
                              </table>
                    </td>
              </tr>
            </table>
     
</div>
</form>