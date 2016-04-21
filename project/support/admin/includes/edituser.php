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
	$var_staffid = $_SESSION["sess_staffid"];
	
	if ($_POST["postback"] == "" && $var_id != "") {
		
		$sql = "Select nUserId,nCompId,vUserName,vEmail,vLogin,vPassword,ddate,vOnline,vCodeForPass,";
		$sql .= "vBanned,vDelStatus from sptbl_users where nUserId = '" . mysql_real_escape_string($var_id) . "'";
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
			$var_active = $var_row["vDelStatus"];
		}
		else {
			$var_id = "";
			$var_message = MESSAGE_USER_NOTEXIST ;
                        $flag_msg  = 'class="msg_error"';
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
			$var_active = ($_POST["rdActive"] == "1")?$_POST["rdActive"]:"0";

		$addition_flag = validateAddition();

		if (validateAddition() == 1) {
			if(!isUniqueEmailUser($var_email,0,$var_compId)) {
				$var_message = MESSAGE_NONUNIQUE_EMAIL ;
                                $flag_msg  = 'class="msg_error"';
			}
			else {
				//Insert into the company table
				$sql = "Insert into sptbl_users(nUserId,nCompId,vUserName,vEmail,vLogin,vPassword,ddate,vOnline,";
				$sql .= "vBanned,vDelStatus) Values('','" . mysql_real_escape_string($var_compId) . "',
						'" . mysql_real_escape_string($var_userName). "','" . mysql_real_escape_string($var_email) . "','" . mysql_real_escape_string($var_userLogin) . "',
						'" . md5($var_password) . "',now(),'0','$var_banned','$var_active')";
				executeQuery($sql,$conn);
				 
				 $var_insert_id = mysql_insert_id($conn);
				
				//Insert the actionlog
				if(logActivity()) {
				$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_ADDITION . "','Users','$var_insert_id',now())";			
				executeQuery($sql,$conn);
				}
				
				$var_message = MESSAGE_RECORD_ADDED;
                                $flag_msg  = 'class="msg_success"';
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

				$var_mail_body = TEXT_MAIL_START . "<br>";
				$var_mail_body .= TEXT_MAIL_WELCOME_HEAD . "<br>";
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
		}
		else {
			$var_message = $addition_flag ;
                        $flag_msg  = 'class="msg_error"';
		}
	}
	elseif ($_POST["postback"] == "D") {
			$var_userName 	= $_POST["txtUserName"];
			$var_userLogin 	= $_POST["txtUserLogin"];
			$var_password 	= $_POST["txtPassword"];
			$var_online 	= "";
			$var_email 		= $_POST["txtEmail"];
			$var_banned 	= ($_POST["rdBanned"] == "1")?$_POST["rdBanned"]:"0";
			$var_compId 	= $_POST["cmbCompanyId"];
			$var_date 		=  date("m-d-Y h:i:s");
			
			if (validateDeletion($var_id) == true) {
				$sql = "Update sptbl_users set vDelStatus = '2' where nUserId='" . mysql_real_escape_string($var_id) . "'";
				executeQuery($sql,$conn);
				
				//Insert the actionlog
				if(logActivity()) {
					$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Users','" . mysql_real_escape_string($var_id) . "',now())";			
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
                        $flag_msg  = 'class="msg_error"';
		}
		else {
			$var_message = MESSAGE_OPEN_TICKET_EXIST ;
                        $flag_msg  = 'class="msg_error"';
		}
	}
	elseif ($_POST["postback"] == "U") {
			$var_userName = $_POST["txtUserName"];
			$var_userLogin = $_POST["txtUserLogin"];
			$var_password = $_POST["txtPassword"];
                        $var_olduserName = $_POST["txtOldUserName"];
			$var_online = "";
			$var_email = $_POST["txtEmail"];
			$var_banned = ($_POST["rdBanned"] == "1")?$_POST["rdBanned"]:"0";
			$var_compId = $_POST["cmbCompanyId"];
			$var_date = date("m-d-Y h:i:s");
			$var_active = ($_POST["rdActive"] == "0")?$_POST["rdActive"]:"1";
			
			$updationflag = validateUpdation();
			
			if ($updationflag == 1) {
				if(!isUniqueEmailUser($var_email,$var_id,$var_compId)) {
					$var_message = MESSAGE_NONUNIQUE_EMAIL ;
                                        $flag_msg  = 'class="msg_error"';
				}
				else {
					$sql = "Update sptbl_users set vUserName='" . mysql_real_escape_string($var_userName) . "',
						" . (($var_password != "")?("vPassword='" . md5($var_password) .  "',"):"") .
						"vEmail='" . mysql_real_escape_string($var_email) . "',
						nCompId='" . mysql_real_escape_string($var_compId) . "',
						vBanned='" . mysql_real_escape_string($var_banned) . "',
						vDelStatus='" . mysql_real_escape_string($var_active) . "'
					    where nUserId='" . mysql_real_escape_string($var_id) . "'"; 
					executeQuery($sql,$conn);
                                        
                                        // Host Manager Section
                                        $sql	= "UPDATE autohoster_users 
                                                           SET  vname  = '".addslashes($var_userName)."',
                                                                        vemail = '".addslashes($var_email)."' ".
                                                                        (($var_password != "")?(", vpassword = '" . md5($var_password) .  "'"): " ")."
                                                           WHERE vuser_name = '".addslashes($var_olduserName)."'";
                                       // executeQuery($sql,$conn);
                                        // Host Manager Section Ends Here

                                        // Site Builder Section
                                        $sql	= "UPDATE tbl_user_mast 
                                                           SET  vuser_name = '".addslashes($var_userName)."',
                                                                        vuser_email = '".addslashes($var_email)."'".
                                                                        (($var_password != "")?(", vuser_password = '" . md5($var_password) .  "'"):" ")."
                                                           WHERE vuser_login = '".addslashes($var_olduserName)."'";

                                       // executeQuery($sql,$conn);
                                        // Site Builder Section Ends Here
				
					//Insert the actionlog
					if(logActivity()) {
						$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Users','" . mysql_real_escape_string($var_id) . "',now())";			
						executeQuery($sql,$conn);
					}
				
					$var_message = MESSAGE_RECORD_UPDATED;
                                        $flag_msg  = 'class="msg_success"';
					if($var_password != "") {
						//mail the user the changed password
						
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

							$var_mail_body = TEXT_MAIL_START . "<br>";
							$var_mail_body .= TEXT_MAIL_MODIFY_HEAD . "<br>";
							$var_mail_body .= TEXT_USER_LOGIN . " : " . $var_userLogin . "<br>";
							$var_mail_body .= TEXT_USER_PASSWORD . " : " . $var_password . "<br><br>";
							$var_mail_body .= "<a href=\"" . $var_loginurl . "\">" . TEXT_CLICK_HERE . "</a>" . TEXT_LOGIN_ACCOUNT . "<br><br>";		
							$var_mail_body .= TEXT_MAIL_WELCOME_TAIL  . "<br>" . htmlentities($var_helpdesktitle);
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
					$var_password="";
				}
			}
			else {
				$var_message = $updationflag ;
                                $flag_msg  = 'class="msg_error"';
			}
	}
	
	function validateAddition() 
	{
		global $conn;
                global $passwordLength;
		if (trim($_POST["txtUserName"]) == "" || trim($_POST["txtUserLogin"]) == "" || trim($_POST["txtPassword"]) == "" || trim($_POST["txtEmail"]) == "") {
			return MESSAGE_MANDATORY_FIELDS;
		}
		elseif(!isValidUsername(trim($_POST["txtUserLogin"]))){
			return MESSAGE_INVALID_LOGINNAME;
		}
		elseif(!isValidEmail(trim($_POST["txtEmail"]))){
			return MESSAGE_INVALID_EMAIL;
		}
                elseif(strlen($_POST["txtPassword"]) < $passwordLength){
			return MESSAGE_NEW_PASSWORD_LENGTH;
		}
		else {
			$sql = "Select nCompId from sptbl_companies where vDelStatus='0' AND nCompId='" . mysql_real_escape_string($_POST["cmbCompanyId"]) . "' ";
			if (mysql_num_rows(executeSelect($sql,$conn)) <= 0) {
				return MESSAGE_INVALID_COMAPANY;
			}
			else {
				$sql = "Select nUserId from sptbl_users where vLogin='" . mysql_real_escape_string(trim($_POST["txtUserLogin"])) . "' AND vDelStatus = 0";
				if (mysql_num_rows(executeSelect($sql,$conn)) > 0) {
					return MESSAGE_LOGINNAME_EXIST;
				}
				else {
					return true;	
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
                global $passwordLength;
		//implement logic here
		if (trim($_POST["txtUserName"]) == "" || trim($_POST["txtUserLogin"]) == ""  || trim($_POST["txtEmail"]) == "") {
			return MESSAGE_MANDATORY_FIELDS;
		}
		elseif(!isValidUsername(trim($_POST["txtUserLogin"]))){
			return MESSAGE_INVALID_LOGINNAME;
		}elseif(!isValidEmail(trim($_POST["txtEmail"]))){
			return MESSAGE_INVALID_EMAIL;
		}
                elseif(trim($_POST["txtPassword"]) != '' && strlen($_POST["txtPassword"]) < $passwordLength){
			return MESSAGE_NEW_PASSWORD_LENGTH;
		}
		else {
			$sql = "Select nCompId from sptbl_companies where vDelStatus='0' AND nCompId='" . mysql_real_escape_string($_POST["cmbCompanyId"]) . "' ";
			if (mysql_num_rows(executeSelect($sql,$conn)) <= 0) {
				return MESSAGE_INVALID_COMAPANY;
			}
			else {
				$sql = "Select nUserId from sptbl_users where vLogin='" . mysql_real_escape_string(trim($_POST["txtUserLogin"])) . "' AND nUserId != '" . mysql_real_escape_string($var_id) . "'";
				if (mysql_num_rows(executeSelect($sql,$conn)) > 0) {
					return MESSAGE_LOGINNAME_EXIST;
				}
			}
		}
		return true;
	}
	
	
		$lst_comp = "";
	//fill the css ids here
	$sql = "Select nCompId,vCompName from sptbl_companies where vDelStatus='0' order by vCompName ";
	$result = executeSelect($sql,$conn);
	while ($row = mysql_fetch_array($result)) {
		$lst_comp .=  "<option value=\"" . $row["nCompId"] . "\"" . (($var_compId == $row["nCompId"])?"Selected":"") . ">" . htmlentities($row["vCompName"]) . "</option>"; 
	}
	mysql_free_result($result);
	//end of fill the css ids here

?>
<form name="frmUser" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>">
<div class="content_section">
			<div class="content_section_title">
				<h3> <?php 
	 if(isset($_REQUEST['id'])) echo TEXT_EDIT_USER;
	 else echo "Add User";
	 ?></h3>
			</div>	
<table width="100%"  border="0">
  <tr>
    <td width="76%" valign="top">
    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
     <tr>
     <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
     <td class="pagecolor">
     

     <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="column1">
     <tr>
     <td>
         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">

                 <tr>
         <td align="center" colspan=3 >&nbsp;</td>

         </tr>

		<tr>
		<td>&nbsp;</td>
         <td align="left" colspan=2 class="toplinks">
         <?php echo TEXT_FIELDS_MANDATORY ?></td>

         </tr>

         <tr>
         <td align="center" colspan=3 >
         <?php

			if ($var_message != ""){
			?>
				<div <?php echo $flag_msg; ?>>
			<b><?php echo($var_message); ?></b>
			</div>
			<?php
			}
			?>			</td>

         </tr>
		<tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="2%" align="left">&nbsp;</td>
         <td width="39%" align="left" class="toplinks"><?php echo TEXT_USER_COMPANY?></td>
         <td width="59%" align="left">
         <select name="cmbCompanyId" class="comm_input input_width1a">
			<?php echo($lst_comp); ?>		 
		 </select>
         </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>

         <tr>
         <td width="2%" align="left">&nbsp;</td>
         <td width="39%" align="left" class="toplinks"><?php echo TEXT_USER_NAME1?> <font style="color:#FF0000; font-size:9px">*</font> </td>
         <td width="59%" align="left">
         <input name="txtUserName" type="text" class="comm_input input_width1" id="txtUserName" size="30" maxlength="100" value="<?php echo htmlentities($var_userName); ?>">
         </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_USER_LOGIN ?> <font style="color:#FF0000; font-size:9px">*</font></td>
                      <td width="59%" align="left">
                        <input name="txtUserLogin" type="text" class="comm_input input_width1" id="txtUserLogin" size="30" maxlength="100" value="<?php echo htmlentities($var_userLogin); ?>">
</td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
					  
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_USER_PASSWORD ?> <span id="star" style=" visibility:visible"><font style="color:#FF0000; font-size:9px">*</font></span></td>
                      <td width="59%" align="left" class="toplinks">
                      <input name="txtPassword" type="password" class="comm_input input_width1" id="txtPassword" size="30" maxlength="100" value="">
						<span id="showError" style="visibility:hidden"><br><font color="red"><?php echo TEXT_PASSWORD_NOTIFICATION; ?></font></span>
                      </td>
                      </tr>
					  
					  <tr><td colspan="3">&nbsp;</td></tr>
					  
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks">Confirm Password <span id="constar" style=" visibility:visible"><font style="color:#FF0000; font-size:9px">*</font></span></td>
                      <td width="59%" align="left" class="toplinks">
                      <input name="conPassword" type="password" class="comm_input input_width1" id="conPassword" size="30" maxlength="100" >
                      <input type="hidden" name="txtOldUserName" value="<?php echo htmlentities($var_userName); ?>">
                      </td>
                      </tr>

                      <tr><td colspan="3">&nbsp;</td></tr>
					  <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_USER_EMAIL?> <font style="color:#FF0000; font-size:9px">*</font></td>
                      <td width="59%" align="left">
                      <input name="txtEmail" type="text" class="comm_input input_width1" id="txtEmail" size="30" maxlength="100" value="<?php echo htmlentities($var_email); ?>">
                      </td>
                      </tr>
                       <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks">&nbsp;</td>
                      <td width="59%" align="left">
                     
                      <?php
                      $userEmailarray = getUserEmail($var_id);
                      $emailcount  = 1;
                      if(count($userEmailarray) > 0){
                          echo '<h4>'. MESSAGE_EMAIL_LIST .'</h4>';
                          foreach ($userEmailarray as $key => $value) {
                                echo $emailcount . ')&nbsp;&nbsp;&nbsp;' . $value.'<br>';
                                $emailcount++;
                          }
                      }

                      ?>
                      </td>
                      </tr>
                      <tr>
                      <td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_STATUS?></td>
                      <td width="59%" align="left" class="toplinks">
                      <input name="rdActive" type="radio" value="0" <?php echo(($var_active == 0)?"checked":""); ?>>
						<?php echo TEXT_ACTIVE;?> 
						<input name="rdActive" type="radio" value="1"  <?php echo(($var_active == 1)?"checked":""); ?>>
						<?php echo TEXT_INACTIVE;?>
                      </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_USER_BANNED?></td>
                      <td width="59%" align="left" class="toplinks">
                      <input name="rdBanned" type="radio" value="1" <?php echo(($var_banned == 1)?"checked":""); ?>>
						Yes 
						<input name="rdBanned" type="radio" value="0"  <?php echo(($var_banned == 0)?"checked":""); ?>>
						No
                      </td>
                      </tr>
                      <tr><td class="btm_brdr" colspan="3">&nbsp;</td></tr>
																																							
                              </table>
                        </td>
                            </tr>
                        </table></td>
                      <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
            <table width="100%"  border="0" cellspacing="10" cellpadding="0">
              <tr>
                <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr >
                        <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                        <td ><table width="100%"  border="0" cellspacing="0" cellpadding="5" >
                            <tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center"  class="listingbtnbar">
                                    <td width="22%">&nbsp;</td>
                                    <td width="16%"><input name="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD; ?>" onClick="javascript:add();"></td>
                                    <td width="14%"><input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT; ?>"  onClick="javascript:edit();"></td>
                                    <td width="16%"><input name="btDelete" type="button" class="comm_btn_greyad" value="<?php echo BUTTON_TEXT_DELETE; ?>" onClick="javascript:deleted();"></td>
                                    <td width="12%"><input name="btCancel" type="button" class="comm_btn_greyad" value="<?php echo BUTTON_TEXT_CLEAR; ?>" onClick="javascript:cancel();"></td>
                                    <td width="20%">
									<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
									<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
									<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">																
									<input type="hidden" name="id" value="<?php echo($var_id); ?>">
									<input type="hidden" name="postback" value="">
									</td>
                                  </tr>
                              </table></td>
                            </tr>
                        </table></td>
                        <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                    </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                  </table></td>
              </tr>
            </table>
            <p class="ashbody">&nbsp;</p></td>

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
			echo("document.frmUser.btUpdate.disabled=true;");
			echo("document.frmUser.btDelete.disabled=true;");
			echo("document.getElementById('showError').style.visibility='hidden';");
			echo("document.getElementById('star').style.visibility='visible';");
			echo("document.getElementById('constar').style.visibility='visible';");
			echo("document.frmUser.txtUserLogin.readOnly=false;");
		}
		else {
			echo("document.frmUser.btAdd.disabled=true;");
			echo("document.frmUser.btUpdate.disabled=false;");
			echo("document.frmUser.btDelete.disabled=false;");
			echo("document.getElementById('showError').style.visibility='visible';");
			echo("document.getElementById('star').style.visibility='hidden';");
			echo("document.getElementById('constar').style.visibility='hidden';");
			echo("document.frmUser.txtUserLogin.readOnly=true;");
		}
	?>
</script>
</form>