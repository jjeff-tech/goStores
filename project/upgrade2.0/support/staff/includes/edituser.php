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
	$var_message="";
	if ($_POST["postback"] == "" && $var_id != "") {
		
		$sql = "Select nUserId,nCompId,vUserName,vEmail,vLogin,vPassword,ddate,vOnline,vCodeForPass,";
		$sql .= "vBanned from sptbl_users where nUserId = '" . addslashes($var_id) . "' AND vDelStatus='0' ";
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
		}
		else {
			$var_message = MESSAGE_RECORD_ERROR;
                         $flag_msg = "class='msg_error'";

		}
		mysql_free_result($var_result);
	}
	elseif ($_POST["postback"] == "A") {
			$var_userName = $_POST["txtUserName"];
			$var_userLogin = $_POST["txtUserLogin"];
			$var_password = $_POST["txtPassword"];
			$var_online = "";
			$var_email = $_POST["txtEmail"];
			$var_banned = ($_POST["rdBanned"] == "1")?$_POST["rdBanned"]:"0";
			$var_compId = $_POST["cmbCompanyId"];
			$var_date =  date("m-d-Y h:i:s");
			$validate_msg = validateAddition();
		if ($validate_msg != "failure" && strlen($validate_msg)<8) {
			if(isUniqueEmailUser($var_email,0,$var_compId)) {
				//Insert into the company table
				$sql = "Insert into sptbl_users(nUserId,nCompId,vUserName,vEmail,vLogin,vPassword,ddate,vOnline,";
				$sql .= "vBanned,vDelStatus) Values('','" . addslashes($var_compId) . "',
						'" . addslashes($var_userName). "','" . addslashes($var_email) . "','" . addslashes($var_userLogin) . "',
						'" . md5($var_password) . "',now(),'0','0','0')";
				executeQuery($sql,$conn);
				 
				 $var_insert_id = mysql_insert_id($conn);
				
				//Insert the actionlog
				if(logActivity()) {
				$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . addslashes(TEXT_ADDITION) . "','Users','$var_insert_id',now())";
				executeQuery($sql,$conn);
				}
				
				$var_message = MESSAGE_RECORD_ADDED;
                                 $flag_msg = "class='msg_success'";
				//Send mail with the password to the user here
				$sql = "Select * from sptbl_lookup where vLookUpName IN('MailFromName','MailFromMail','MailReplyName','MailReplyMail','Emailfooter','Emailheader','HelpdeskTitle','LoginURL')";
					$result = executeSelect($sql,$conn);
					if(mysql_num_rows($result) > 0) {
						while($row = mysql_fetch_array($result)) {
							switch($row["vLookUpName"]) {
								case "MailFromName":
												$var_fromName = $row["vLookUpValue"];
												break;
								case "MailFromMail":
												$var_fromMail = $row["vLookUpValue"];
												break;
								case "MailReplyName":
												$var_replyName = $row["vLookUpValue"];
												break;
								case "MailReplyMail":
												$var_replyMail = $row["vLookUpValue"];
												break;
								case "Emailfooter":
												$var_emailfooter = $row["vLookUpValue"];
												break;
								case "Emailheader":
												$var_emailheader = $row["vLookUpValue"];
												break;	
								case "HelpdeskTitle":
												$var_helpdesktitle = $row["vLookUpValue"];
												break;			
								case "LoginURL":
												$var_loginurl = $row["vLookUpValue"];
												break;								
							}
						}
					}
					mysql_free_result($result);
	
				$var_mail_body = TEXT_MAIL_WELCOME_HEAD . "<br>";
				$var_mail_body .= TEXT_USER_LOGIN . " : " . $var_userLogin . "<br>";
				$var_mail_body .= TEXT_USER_PASSWORD . " : " . $var_password . "<br><br>";
				$var_mail_body .= "<a href=\"" . $var_loginurl . "\">" . TEXT_CLICK_HERE . "</a>" . TEXT_LOGIN_ACCOUNT . "<br><br>";
				$var_mail_body .= TEXT_MAIL_WELCOME_TAIL . "<br>" . htmlentities($var_helpdesktitle);
				$subject = "Your account has been created";
					
				$var_body = $var_emailheader . "<br>" . $var_mail_body . "<br>" . $var_emailfooter;
				$Headers="From: $var_fromName <$var_fromMail>\n";
				$Headers.="Reply-To: $var_replyName <$var_replyMail>\n";
				$Headers.="MIME-Version: 1.0\n";
				$Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

				// it is for smtp mail sending
				if($_SESSION["sess_smtpsettings"] == 1){
					$var_smtpserver = $_SESSION["sess_smtpserver"];
					$var_port = $_SESSION["sess_smtpport"];
		
					SMTPMail($var_fromMail,$var_email,$var_smtpserver,$var_port,$var_subject,$var_body);
				}
				else		
					$mailstatus=@mail($var_email,$subject,$var_body,$Headers);
			
				//End of sending mail
				$var_userName = "";
				$var_userLogin = "";
				$var_password = "";
				$var_online = "";
				$var_email = "";
				$var_banned = "0";
				$var_compId = "";
				$var_date =  date("m-d-Y h:i:s");
			}
			else {
				$var_message = MESSAGE_NONUNIQUE_EMAIL;
                                $flag_msg = "class='msg_error'";
                                
			}	
		}
		else {
				if(strlen($validate_msg)>8){
				$var_message =$validate_msg;
                                 $flag_msg = "class='msg_error'";
				}else{
					$var_message = MESSAGE_JUNK_FOUND;
                                        $flag_msg = "class='msg_error'";
				}
		}
	}
	elseif ($_POST["postback"] == "D") {
			$var_userName = $_POST["txtUserName"];
			$var_userLogin = $_POST["txtUserLogin"];
			$var_password = $_POST["txtPassword"];
			$var_online = "";
			$var_email = $_POST["txtEmail"];
			$var_banned = ($_POST["rdBanned"] == "1")?$_POST["rdBanned"]:"0";
			$var_compId = $_POST["cmbCompanyId"];
			$var_date =  date("m-d-Y h:i:s");
			
		if (validateDeletion($var_id) == true) {
			$sql = "Update sptbl_users set vDelStatus = '1' where nUserId='" . addslashes($var_id) . "'";
			executeQuery($sql,$conn);
			
			//Insert the actionlog
			if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Users','" . addslashes($var_id) . "',now())";			
			executeQuery($sql,$conn);
			}

				$var_userName = "";
				$var_userLogin = "";
				$var_password = "";
				$var_online = "";
				$var_email = "";
				$var_banned = "0";
				$var_compId = "";
				$var_date =  date("m-d-Y h:i:s");
				$var_id="";
			$var_message = MESSAGE_RECORD_DELETED;
                        $flag_msg = "class='msg_success'";
		}
		else {
			$var_message = MESSAGE_RECORD_ERROR;
                        $flag_msg = "class='msg_error'";
		}
	}
	elseif ($_POST["postback"] == "U") {
			$var_userName = $_POST["txtUserName"];
			$var_userLogin = $_POST["txtUserLogin"];
			$var_password = $_POST["txtPassword"];
			$var_online = "";
			$var_email = $_POST["txtEmail"];
			$var_banned = ($_POST["rdBanned"] == "1")?$_POST["rdBanned"]:"0";
			$var_compId = $_POST["cmbCompanyId"];
			$var_date = date("m-d-Y h:i:s");
			if (validateUpdation() == true) {
				if(isUniqueEmailUser($var_email,$var_id,$var_compId)) {
						$sql = "Update sptbl_users set vUserName='" . addslashes($var_userName) . "',
								" . (($var_password != "")?("vPassword='" . md5($var_password) .  "',"):"") .
								"vEmail='" . addslashes($var_email) . "',
								nCompId='" . addslashes($var_compId) . "',
								vBanned='" . addslashes($var_banned) . "'
								where nUserId='" . addslashes($var_id) . "'"; 
						executeQuery($sql,$conn);
						
					//Insert the actionlog
					if(logActivity()) {
					$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Users','" . addslashes($var_id) . "',now())";			
					executeQuery($sql,$conn);
					}
						
						$var_message = MESSAGE_RECORD_UPDATED;
                                                $flag_msg = "class='msg_success'";
						 if($var_password != "") {
								//mail the user the changed password
								$sql = "Select * from sptbl_lookup where vLookUpName IN('MailFromName','MailFromMail','MailReplyName','MailReplyMail','Emailfooter','Emailheader')";
								$result = executeSelect($sql,$conn);
								if(mysql_num_rows($result) > 0) {
									while($row = mysql_fetch_array($result)) {
										switch($row["vLookUpName"]) {
											case "MailFromName":
															$var_fromName = $row["vLookUpValue"];
															break;
											case "MailFromMail":
															$var_fromMail = $row["vLookUpValue"];
															break;
											case "MailReplyName":
															$var_replyName = $row["vLookUpValue"];
															break;
											case "MailReplyMail":
															$var_replyMail = $row["vLookUpValue"];
															break;
											case "Emailfooter":
															$var_emailfooter = $row["vLookUpValue"];
															break;
											case "Emailheader":
															$var_emailheader = $row["vLookUpValue"];
															break;				
										}
									}
								}
								mysql_free_result($result);
		
								$var_mail_body = TEXT_MAIL_MODIFY_HEAD . "<br>";
								$var_mail_body .= TEXT_USER_LOGIN . " : " . $var_userLogin . "<br>";
								$var_mail_body .= TEXT_USER_PASSWORD . " : " . $var_password . "<br>";
								$var_mail_body .= TEXT_MAIL_WELCOME_TAIL;
								$var_subject = "Your account has been modified";
								
								$var_body = $var_emailheader . "<br>" . $var_mail_body . "<br>" . $var_emailfooter;
								$Headers="From: $var_fromName <$var_fromMail>\n";
								$Headers.="Reply-To: $var_replyName <$var_replyMail>\n";
								$Headers.="MIME-Version: 1.0\n";
								$Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

								// it is for smtp mail sending
								if($_SESSION["sess_smtpsettings"] == 1){
									$var_smtpserver = $_SESSION["sess_smtpserver"];
									$var_port = $_SESSION["sess_smtpport"];
						
									SMTPMail($var_fromMail,$var_email,$var_smtpserver,$var_port,$var_subject,$var_body);
								}
								else				
									$mailstatus=@mail($var_email,$var_subject,$var_body,$Headers);
						}  
					}
					else {
						$var_message = MESSAGE_NONUNIQUE_EMAIL;
                                                $flag_msg = "class='msg_error'";
					}	
					$var_password="";
			}
			else {
				$var_message = MESSAGE_RECORD_ERROR;
                                $flag_msg = "class='msg_error'";
			}
	}
	
	function validateAddition() 
	{
		global $conn;
		if (trim($_POST["txtUserName"]) == "" || trim($_POST["txtUserLogin"]) == "" || trim($_POST["txtPassword"]) == "" || trim($_POST["txtEmail"]) == "") {
			return "failure";
		}
		elseif(!isValidUsername(trim($_POST["txtUserLogin"])) || !isValidEmail(trim($_POST["txtEmail"]))){
			return MESSAGE_JUNK_FOUND;	
		}
		else if($_POST["cmbCompanyId"]){
			 $sql = "Select nCompId from sptbl_companies where vDelStatus='0' AND nCompId='" . addslashes($_POST["cmbCompanyId"]) . "' ";
			 if (mysql_num_rows(executeSelect($sql,$conn)) <= 0) {
				return "failure";
			}
			else {
				$sql = "Select nUserId from sptbl_users where vLogin='" . addslashes(trim($_POST["txtUserLogin"])) . "'";
				if (mysql_num_rows(executeSelect($sql,$conn)) > 0) {
					return EXIST_MESSAGE;
				}
				else {
					return "success";	
				}
			}
		}
		
	}
	
	function validateDeletion($var_list) 
	{
		//implement logic here
		global $conn;
		$sql = "Select nTicketId from sptbl_tickets where vStatus !='closed' AND nUserId IN($var_list) ";
		if (mysql_num_rows(executeSelect($sql,$conn)) > 0) {
			return false;
		}
		else {
			return true;
		}
	}
	
	function validateUpdation() 
	{
		global $conn,$var_id;
		//implement logic here
		if (trim($_POST["txtUserName"]) == "" || trim($_POST["txtUserLogin"]) == ""  || trim($_POST["txtEmail"]) == "") {
			return false;
		}
		elseif(!isValidUsername(trim($_POST["txtUserLogin"])) || !isValidEmail(trim($_POST["txtEmail"]))){
			return false;	
		}
		else {
			$sql = "Select nCompId from sptbl_companies where vDelStatus='0' AND nCompId='" . addslashes($_POST["cmbCompanyId"]) . "' ";
			if (mysql_num_rows(executeSelect($sql,$conn)) <= 0) {
				return false;
			}
			else {
				$sql = "Select nUserId from sptbl_users where vLogin='" . addslashes(trim($_POST["txtUserLogin"])) . "' AND nUserId != '" . addslashes($var_id) . "'";
				if (mysql_num_rows(executeSelect($sql,$conn)) > 0) {
					return false;
				}
			}
		}
		return true;
	}
	
	
		$lst_comp = "";
	//fill the css ids here
	$sql = "Select nCompId,vCompName from sptbl_companies  where (vDelStatus='0') and (nCompId IN (".getStaffCompanies($_SESSION["sess_staffid"]).")) order by vCompName ";
	$result = executeSelect($sql,$conn);
	while ($row = mysql_fetch_array($result)) {
		$lst_comp .=  "<option value=\"" . $row["nCompId"] . "\"" . (($var_compId == $row["nCompId"])?"Selected":"") . ">" . htmlentities($row["vCompName"]) . "</option>"; 
	}
	mysql_free_result($result);
	//end of fill the css ids here

?>
<form name="frmUser" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>">

<div class="content_section">
 
       <div class="content_section_title">
	<h3><?php echo TEXT_ADD_USER ?></h3>
	</div>
        
     


   
         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
		<tr>
		
         <td width="100%" align="center" colspan=3 class="toplinks">
         <?php echo TEXT_FIELDS_MANDATORY ?></td>

         </tr>

         <tr>
         <td width="100%" align="center" colspan=3 class="messsage">
        <div <?php echo $flag_msg; ?>> <?php echo $var_message ?></div></td>
         </tr>
		<tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="13%" align="left">&nbsp;</td>
         <td width="26%" align="left" class="toplinks"><?php echo TEXT_USER_COMPANY?></td>
         <td width="61%" align="left">
         <select name="cmbCompanyId" class="comm_input input_width1a">
			<?php echo($lst_comp); ?>		 
		 </select>
         </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>

         <tr>
         <td width="13%" align="left">&nbsp;</td>
         <td width="26%" align="left" class="toplinks"><?php echo TEXT_USER_NAME?> <font style="color:#FF0000; font-size:9px">*</font> </td>
         <td width="61%" align="left">
         <input name="txtUserName" type="text" class="comm_input input_width1a" id="txtUserName" size="30" maxlength="100" value="<?php echo htmlentities($var_userName); ?>">
         </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_USER_LOGIN ?> <font style="color:#FF0000; font-size:9px">*</font></td>
                      <td width="61%" align="left">
                        <input name="txtUserLogin" type="text" class="comm_input input_width1a" id="txtUserLogin" size="30" maxlength="100" value="<?php echo htmlentities($var_userLogin); ?>">
                       </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
					  
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_USER_PASSWORD ?> <span id="star" style=" visibility:visible"><font style="color:#FF0000; font-size:9px">*</font></span></td>
                      <td width="61%" align="left" class="toplinks">
                      <input name="txtPassword" type="text" class="comm_input input_width1a" id="txtPassword" size="30" maxlength="100" value="<?php echo htmlentities($var_password); ?>">
						<span id="showError" style="visibility:hidden"><br><font color="red"><?php echo TEXT_PASSWORD_NOTIFICATION; ?></font></span>
                      </td>
                      </tr>

                      <tr><td colspan="3">&nbsp;</td></tr>
					  <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_USER_EMAIL?> <font style="color:#FF0000; font-size:9px">*</font></td>
                      <td width="61%" align="left">
                      <input name="txtEmail" type="text" class="comm_input input_width1a" id="txtEmail" size="30" maxlength="100" value="<?php echo htmlentities($var_email); ?>">
                      </td>
                      </tr>

                      <tr>
                      <td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_USER_BANNED?></td>
                      <td width="61%" align="left">
                      <input name="rdBanned" type="radio" value="1" <?php echo(($var_banned == 1)?"checked":""); ?>>
						Yes 
						<input name="rdBanned" type="radio" value="0"  <?php echo(($var_banned == 0)?"checked":""); ?>>
						No
                      </td>
                      </tr>
                      <tr>
					  
					  
					  </tr>																
                   </table>
							  
							  
							  
                       
           <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center"  class="whitebasic">
                                    <td width="22%">&nbsp;</td>
                                    <td width="58%" colspan="3">
									<input name="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD; ?>" onClick="javascript:add();">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									<input name="btCancel" type="reset" class="comm_btn" value="<?php echo BUTTON_TEXT_CANCEL; ?>" onClick="return clearForm(this.form);" >&nbsp;&nbsp;
									</td>
                                    <td width="20%">
									<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
                                    <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">									
                                    <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">																
									<input type="hidden" name="id" value="<?php echo($var_id); ?>">
									<input type="hidden" name="postback" value="">
									</td>
                                  </tr>
                              </table>
                    
   

</div>

<script>
	var setValue = "<?php echo trim($var_compId); ?>";
//	document.frmUser.cmbCountry.text=setValue;
	try{
 	for(i=0;i<document.frmUser.cmbCompanyId.options.length;i++){
            if(document.frmUser.cmbCompanyId.options[i].value == setValue){
                        document.frmUser.cmbCompanyId.options[i].selected=true;
                        break;
            }
    }
	}catch(e){}
	<?php
		if ($var_id == "") {
			echo("document.frmUser.btAdd.disabled=false;");
			echo("document.getElementById('showError').style.visibility='hidden';");
			echo("document.getElementById('star').style.visibility='visible';");
			echo("document.frmUser.txtUserLogin.readOnly=false;");
		}
		else {
			echo("document.frmUser.btAdd.disabled=true;");
			echo("document.getElementById('showError').style.visibility='visible';");
			echo("document.getElementById('star').style.visibility='hidden';");
			echo("document.frmUser.txtUserLogin.readOnly=true;");
		}
	?>
	
	function clearForm(oForm)
	{
		var frm_elements = oForm.elements;
		for (i = 0; i < frm_elements.length; i++)
		{
			field_type = frm_elements[i].type.toLowerCase();
			switch (field_type)
			{
			case "text":
			case "password":
			case "textarea":
				frm_elements[i].value = "";
				break;
			default:
				break;
			}
		}
		return false;
	}
</script>
</form>