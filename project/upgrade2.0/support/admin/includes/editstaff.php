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
	$addOredit = 'Add Staff';
	if ($_GET["id"] != "") {
		$var_id = $_GET["id"];
		$addOredit = 'Edit Staff';
	}
	elseif ($_POST["id"] != "") {
		$var_id = $_POST["id"];
		$addOredit = 'Edit Staff';
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
	$var_country = "UnitedStates";
	//$var_userid = $_SESSION["sess_staffid"];
	$var_staffid = $_SESSION["sess_staffid"];
	
	$var_refreshRate = 60;
	$var_notifyAssign = 0;
	$var_notifyPvtMsg = 0;
	$var_notifyKB = 0;
	$var_watcher = 0;
	$var_notifyArrival=1;
	$var_cssId = 1;
	
	
	if ($_POST["postback"] == "" && $var_id != "") {
		
		$sql = "Select nStaffId,vStaffname,vLogin,vPassword,vOnline,vMail,vYIM,vSMSMail,vMobileNo,nCSSId,nRefreshRate,nNotifyAssign,";
		$sql .= "nNotifyPvtMsg,nNotifyKB,nNotifyArrival,vType,tSignature,nWatcher from sptbl_staffs where nStaffId = '" . addslashes($var_id) . "' AND vDelStatus='0' ";

		$var_result = executeSelect($sql,$conn); 
		if (mysql_num_rows($var_result) > 0) {
			$var_row = mysql_fetch_array($var_result);
			
			$var_staffName = $var_row["vStaffname"];
			$var_staffLogin = $var_row["vLogin"];
			$var_password = "";
			$var_online = $var_row["vOnline"];
			$var_email = $var_row["vMail"];
			$var_yim = $var_row["vYIM"];
			$var_smsMail = $var_row["vSMSMail"];
			$var_mobile = $var_row["vMobileNo"];
			$var_cssId = $var_row["nCSSId"];
			$var_refreshRate = $var_row["nRefreshRate"];
			$var_notifyAssign = $var_row["nNotifyAssign"];
			$var_notifyPvtMsg = $var_row["nNotifyPvtMsg"];
			$var_notifyKB = $var_row["nNotifyKB"];
			$var_watcher = $var_row["nWatcher"];
			$var_notifyArrival = $var_row["nNotifyArrival"];
			$var_type = $var_row["vType"];
			$var_signature = $var_row["tSignature"];
			
		}
		else {
			$var_id="";
			$var_message = MESSAGE_INVALID_STAFF_ID ;
                        $flag_msg  = 'class="msg_error"';
		}
		mysql_free_result($var_result);
	}
	elseif ($_POST["postback"] == "A") {
			$var_staffName = $_POST["txtStaffName"];
			$var_staffLogin = $_POST["txtStaffLogin"];
			$var_password = $_POST["txtPassword"];
			$var_email = $_POST["txtEmail"];
			$var_yim = $_POST["txtYim"];
			$var_smsMail = $_POST["txtSmsMail"];
			$var_mobile = $_POST["txtMobile"];
			$var_cssId = $_POST["cmbCssId"];
			$var_refreshRate = $_POST["cmbRefresh"];
			settype($var_refreshRate,integer);
			$var_notifyAssign = ($_POST["rdNotifyAssign"] == "1")?$_POST["rdNotifyAssign"]:"0";
			$var_notifyPvtMsg = ($_POST["rdNotifyPvtMsg"] == "1")?$_POST["rdNotifyPvtMsg"]:"0";
			$var_notifyKB = ($_POST["rdNotifyKB"] == "1")?$_POST["rdNotifyKB"]:"0";
			$var_watcher = ($_POST["rdWatcher"] == "1")?$_POST["rdWatcher"]:"0";
			$var_notifyArrival = ($_POST["rdNotifyArrival"] == "1")?$_POST["rdNotifyArrival"]:"0"; 
			$var_signature = $_POST["txtSignature"];

		$addition_flag = validateAddition();

		if ($addition_flag == 1) {
			if(!isUniqueEmail($var_email)) {
				$var_message = MESSAGE_NONUNIQUE_EMAIL ;
                                $flag_msg  = 'class="msg_error"';
			}
			else {
				//Insert into the company table
				$sql = "Insert into sptbl_staffs(nStaffId,vStaffname,vLogin,vPassword,vOnline,vMail,vYIM,vSMSMail,vMobileNo,nCSSId,nRefreshRate,nNotifyAssign,";
				$sql .= "nNotifyPvtMsg,nNotifyKB,nNotifyArrival,vType,nWatcher,tSignature) Values('','" . addslashes($var_staffName) . "',
						'" . addslashes($var_staffLogin). "','" . md5($var_password) . "','0','" . addslashes($var_email) . "',
						'" . addslashes($var_yim) . "','" . addslashes($var_smsMail) . "','" . addslashes($var_mobile) . "',
						'" . addslashes($var_cssId) . "','" . addslashes($var_refreshRate) . "','" . $var_notifyAssign . "',
						'" . $var_notifyPvtMsg . "','" . $var_notifyKB . "','" . $var_notifyArrival . "','S',
						'" . addslashes($var_watcher) . "','" . addslashes($var_signature) . "')";
				executeQuery($sql,$conn);
				 
				 $var_insert_id = mysql_insert_id($conn);
				 
				 $sql = "Insert into sptbl_stafffields(nStaffId,nFieldId) Values('$var_insert_id','1'),('$var_insert_id','2'),('$var_insert_id','3'),('$var_insert_id','4')";
				executeQuery($sql,$conn);
				
				//Insert the actionlog
				if(logActivity()) {
					$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_ADDITION . "','Staff','$var_insert_id',now())";			
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
				$var_mail_body .= TEXT_STAFF_LOGIN . " : " . $var_staffLogin . "<br>";
				$var_mail_body .= TEXT_STAFF_PASSWORD . " : " . $var_password . "<br><br>";
				$var_mail_body .= TEXT_MAIL_WELCOME_TAIL . "<br>" . htmlentities($var_helpdesktitle);
				$var_subject = "Your account has been created";
				
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
	
				 //sendMail($var_id,$var_email,$var_mail_body,$subject,$conn);
				//End of sending mail
					$var_staffName = "";
					$var_staffLogin = "";
					$var_password = "";
					$var_email = "";
					$var_yim = "";
					$var_smsMail = "";
					$var_mobile = "";
					$var_cssId = "";
					$var_refreshRate = "";
					$var_notifyAssign = "0";
					$var_notifyPvtMsg = "0";
					$var_notifyKB = "0";
					$var_watcher = "0";
					$var_notifyArrival = 1;
					$var_signature = "";
			}
		}
		else {
			$var_message = $addition_flag ;
                        $flag_msg  = 'class="msg_error"';
		}
	}
	elseif ($_POST["postback"] == "D") {
			$var_staffName = $_POST["txtStaffName"];
			$var_staffLogin = $_POST["txtStaffLogin"];
			$var_password = $_POST["txtPassword"];
			$var_email = $_POST["txtEmail"];
			$var_yim = $_POST["txtYim"];
			$var_smsMail = $_POST["txtSmsMail"];
			$var_mobile = $_POST["txtMobile"];
			$var_cssId = $_POST["cmbCssId"];
			$var_refreshRate = $_POST["cmbRefresh"];
			$var_notifyAssign = ($_POST["rdNotifyAssign"] == "1")?$_POST["rdNotifyAssign"]:"0";
			$var_notifyPvtMsg = ($_POST["rdNotifyPvtMsg"] == "1")?$_POST["rdNotifyPvtMsg"]:"0";
			$var_notifyKB = ($_POST["rdNotifyKB"] == "1")?$_POST["rdNotifyKB"]:"0";
			$var_watcher = ($_POST["rdWatcher"] == "1")?$_POST["rdWatcher"]:"0";
			$var_notifyArrival = ($_POST["rdNotifyArrival"] == "1")?$_POST["rdNotifyArrival"]:"0"; 
			$var_signature = $_POST["txtSignature"];
		if (validateDeletion() == true and $var_id !="1") {
			$sql = "Update sptbl_staffs set vDelStatus = '1' where nStaffId='" . addslashes($var_id) . "'";
			executeQuery($sql,$conn);
			
			//Insert the actionlog
			if(logActivity()) {
				$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Staff','" . addslashes($var_id) . "',now())";			
				executeQuery($sql,$conn);
			}
				$var_staffName = "";
				$var_staffLogin = "";
				$var_password = "";
				$var_email = "";
				$var_yim = "";
				$var_smsMail = "";
				$var_mobile = "";
				$var_cssId = "";
				$var_refreshRate = "";
				$var_refreshRate = 60;
				$var_notifyAssign = "0";
				$var_notifyPvtMsg = "0";
				$var_notifyKB = "0";
				$var_watcher = "0";
				$var_notifyArrival = 1;
				$var_signature = "";
				$var_id="";
			$var_message = MESSAGE_RECORD_DELETED;
                        $flag_msg  = 'class="msg_success"';
		}
		else {
			$var_message = MESSAGE_ADMIN_NOT_DELETE ;
                        $flag_msg  = 'class="msg_error"';
		}
	}
	elseif ($_POST["postback"] == "U") {
			$var_staffName = $_POST["txtStaffName"];
			$var_staffLogin = trim($_POST["txtStaffLogin"]);
			$var_password = $_POST["txtPassword"];
			$var_email = $_POST["txtEmail"];
			$var_yim = $_POST["txtYim"];
			$var_smsMail = $_POST["txtSmsMail"];
			$var_mobile = $_POST["txtMobile"];
			$var_cssId = $_POST["cmbCssId"];
			$var_refreshRate = $_POST["cmbRefresh"];
			settype($var_refreshRate,integer);	
  			$var_notifyAssign = ($_POST["rdNotifyAssign"] == "1")?$_POST["rdNotifyAssign"]:"0";
			$var_notifyPvtMsg = ($_POST["rdNotifyPvtMsg"] == "1")?$_POST["rdNotifyPvtMsg"]:"0";
			$var_notifyKB = ($_POST["rdNotifyKB"] == "1")?$_POST["rdNotifyKB"]:"0";
			$var_watcher = ($_POST["rdWatcher"] == "1")?$_POST["rdWatcher"]:"0";			
			$var_notifyArrival = ($_POST["rdNotifyArrival"] == "1")?$_POST["rdNotifyArrival"]:"0"; 
			$var_signature = $_POST["txtSignature"];

			$updationflag = validateUpdation();
			if ($updationflag == 1) {
				if(!isUniqueEmail($var_email,$var_id,"s")) {
					$var_message = MESSAGE_NONUNIQUE_EMAIL ;
                                        $flag_msg  = 'class="msg_error"';
				}
				else{
				$sql = "Update sptbl_staffs set vStaffname='" . addslashes($var_staffName) . "',
						" . (($var_password != "")?("vPassword='" . md5($var_password) .  "',"):"") .
						"vMail='" . addslashes($var_email) . "',
						vYIM='" . addslashes($var_yim) . "',
						vSMSMail='" . addslashes($var_smsMail) . "',
						vMobileNo='" . addslashes($var_mobile) . "',
						nCSSId='" . addslashes($var_cssId) . "',
						nRefreshRate='" . addslashes($var_refreshRate) . "',
						nNotifyAssign='" . $var_notifyAssign . "',
						nNotifyPvtMsg='" . $var_notifyPvtMsg . "',
						nNotifyKB='" . $var_notifyKB . "',
						nWatcher='" . $var_watcher . "',
						nNotifyArrival='" . $var_notifyArrival . "',
						tSignature='" . addslashes($var_signature) . "'  where nStaffId='" . addslashes($var_id) . "'"; 
				executeQuery($sql,$conn);
				
			//Insert the actionlog
			if(logActivity()) {
				$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Staff','" . addslashes($var_id) . "',now())";			
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
							$var_mail_body .= TEXT_STAFF_LOGIN . " : " . $var_staffLogin . "<br>";
							$var_mail_body .= TEXT_STAFF_PASSWORD . " : " . $var_password . "<br><br>";
							$var_mail_body .= TEXT_MAIL_WELCOME_TAIL . "<br>" . htmlentities($var_helpdesktitle);
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
							
							 //sendMail($var_id,$var_email,$var_mail_body,$subject,$conn);
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
		
		if (trim($_POST["txtStaffName"]) == "" || trim($_POST["txtStaffLogin"]) == "" || trim($_POST["txtPassword"]) == "" || trim($_POST["txtEmail"]) == "") {
			return MESSAGE_MANDATORY_FIELDS;
		}
		elseif(!isValidUsername(trim($_POST["txtStaffLogin"]))){
			return MESSAGE_INVALID_LOGINNAME;
		}elseif(!isValidEmail(trim($_POST["txtEmail"]))){
			return MESSAGE_INVALID_EMAIL;
		}
		else {
			$sql = "Select vLogin from sptbl_staffs where vLogin='" . addslashes(trim($_POST["txtStaffLogin"])) . "'";
			if (mysql_num_rows(executeSelect($sql,$conn)) > 0) {
				return MESSAGE_LOGINNAME_EXIST;
			}
			else {
				return true;
			}
		}
	}
	
	function validateDeletion() 
	{
		//implement logic here
		return true;
	}
	
	function validateUpdation() 
	{
		global $conn,$var_id;
		//implement logic here
		$sql = "Select vLogin from sptbl_staffs where vLogin='" . addslashes(trim($_POST["txtStaffLogin"])) . "' AND nStaffId != '" . addslashes($var_id) . "'";
		if(trim($_POST["txtStaffName"]) == "" || trim($_POST["txtStaffLogin"]) == ""  || trim($_POST["txtEmail"]) == "") {
			return MESSAGE_MANDATORY_FIELDS;
		}
		elseif(!isValidUsername(trim($_POST["txtStaffLogin"]))){
			return MESSAGE_INVALID_LOGINNAME;	
		}elseif(!isValidEmail(trim($_POST["txtEmail"]))){
			return MESSAGE_INVALID_EMAIL;
		}
		else{
			$sql = "Select nStaffId from sptbl_staffs where vLogin='" . addslashes(trim($_POST["txtStaffLogin"])) . "' AND nStaffId != '" . addslashes($var_id) . "'";
			if(mysql_num_rows(executeSelect($sql,$conn)) > 0) {
				return MESSAGE_LOGINNAME_EXIST;
			}	
		}
		return true;
	}
	
	
		$lst_css = "";
	//fill the css ids here
	$sql = "Select nCSSId,vCSSName from sptbl_css order by nCSSId";
	$result = executeSelect($sql,$conn);
	while ($row = mysql_fetch_array($result)) {
		$lst_css .=  "<option value=\"" . $row["nCSSId"] . "\"" . (($var_cssId == $row["nCSSId"])?"Selected":"") . ">" . htmlentities($row["vCSSName"]) . "</option>"; 
	}
	mysql_free_result($result);
	//end of fill the css ids here

?>
<form name="frmStaff" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>">
<div class="content_section">
			<div class="content_section_title">
				<h3><?php echo $addOredit; ?></h3>
			</div>
			
			
<table width="100%"  border="0">
  <tr>
    <td width="76%" valign="top">
    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
    <td>
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
?>			
         </td>

         </tr>

			<tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="2%" align="left">&nbsp;</td>
         <td width="39%" align="left" class="toplinks"><?php echo TEXT_STAFF_NAME?> <font style="color:#FF0000; font-size:9px">*</font> </td>
         <td width="59%" align="left">
         <input name="txtStaffName" type="text" class="comm_input input_width1" id="txtStaffName" size="30" maxlength="100" value="<?php echo htmlentities($var_staffName); ?>">
         </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_STAFF_LOGIN ?> <font style="color:#FF0000; font-size:9px">*</font></td>
                      <td width="59%" align="left">
                        <input name="txtStaffLogin" type="text" class="comm_input input_width1" id="txtStaffLogin" size="30" maxlength="100" value="<?php echo htmlentities($var_staffLogin); ?>">
</td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
					  
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_STAFF_PASSWORD ?> <span id="star" style=" visibility:visible"><font style="color:#FF0000; font-size:9px">*</font></span></td>
                      <td width="59%" align="left" class="toplinks">
                      <input name="txtPassword" type="password" class="comm_input input_width1" id="txtPassword" size="30" maxlength="100" value="<?php echo htmlentities($var_password); ?>">
						
                      </td>
                      </tr>
					  <tr><td colspan="3"  class="toplinks" align="center"><span id="showError" style="visibility:hidden"><br><font color="red"><?php echo TEXT_PASSWORD_NOTIFICATION; ?></font></span></td></tr>
					  <tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
					  <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_STAFF_EMAIL?> <font style="color:#FF0000; font-size:9px">*</font></td>
                      <td width="59%" align="left">
                      <input name="txtEmail" type="text" class="comm_input input_width1" id="txtEmail" size="30" maxlength="100" value="<?php echo htmlentities($var_email); ?>">
                      </td>
                      </tr>

                      <tr>
                      <td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_STAFF_YIM?></td>
                      <td width="59%" align="left">
                      <input name="txtYim" type="text" class="comm_input input_width1" id="txtYim" size="30" maxlength="100" value="<?php echo htmlentities($var_yim); ?>">
                      </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
                      
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_STAFF_SMSMAIL?></td>
                                  <td width="59%" align="left">
                                    <input name="txtSmsMail" type="text" class="comm_input input_width1" id="txtSmsMail" size="30" maxlength="100" value="<?php echo htmlentities($var_smsMail); ?>">
</td>
                                </tr>
                                                                <tr><td colspan="3">&nbsp;</td></tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_STAFF_MOBILE?> </td>
                                  <td width="59%" align="left">
                                      <input name="txtMobile" type="text" class="comm_input input_width1" id="txtMobile" size="30" maxlength="20" value="<?php echo($var_mobile); ?>">
                                  </td>
                                </tr>
                                                                <tr><td colspan="3">&nbsp;</td></tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_STAFF_CSSID?></td>
                                  <td width="59%" align="left"><select name="cmbCssId" class="comm_input input_width1a">
								  	<?php echo($lst_css); ?>
								  </select>
                                  </td>
                                </tr>
                                                                <tr><td colspan="3">&nbsp;</td></tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_STAFF_REFRESH_RATE?> <font style="color:#FF0000; font-size:9px">*</font></td>
                                  <td width="59%" align="left">
								  <select name="cmbRefresh" class="comm_input input_width1a">
								  	  <option value="0" <?php echo(($var_refreshRate == "0")?"Selected":"");?>>No Refresh</option>	
									  <option value="1" <?php echo(($var_refreshRate == "1")?"Selected":"");?>>1 minute</option>
									  <option value="2" <?php echo(($var_refreshRate == "2" || $var_refreshRate == "")?"Selected":"");?>>2 minutes</option>
									  <option value="3" <?php echo(($var_refreshRate == "3")?"Selected":"");?>>3 minutes</option>
									  <option value="4" <?php echo(($var_refreshRate == "4")?"Selected":"");?>>4 minutes</option>
									  <option value="5" <?php echo(($var_refreshRate == "5")?"Selected":"");?>>5 minutes</option>
									  <option value="6" <?php echo(($var_refreshRate == "6")?"Selected":"");?>>6 minutes</option>
									  <option value="7" <?php echo(($var_refreshRate == "7")?"Selected":"");?>>7 minutes</option>
									  <option value="8" <?php echo(($var_refreshRate == "8")?"Selected":"");?>>8 minutes</option>
									  <option value="9" <?php echo(($var_refreshRate == "9")?"Selected":"");?>>9 minutes</option>
									  <option value="10" <?php echo(($var_refreshRate == "10")?"Selected":"");?>>10 minutes</option>
								  </select>
                                  </td>
                                </tr>
                                                                <tr><td colspan="3">&nbsp;</td></tr>
								<tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_STAFF_NOTIFY_ASSIGN?> </td>
                                  <td width="59%" align="left" class="toplinks">
                                    <input name="rdNotifyAssign" type="radio" value="1" <?php echo(($var_notifyAssign == 1)?"checked":""); ?>>
                                    <?php echo TEXT_YES?> 
                                    <input name="rdNotifyAssign" type="radio" value="0"  <?php echo(($var_notifyAssign == 0)?"checked":""); ?>>
                                    <?php echo TEXT_NO?> 
</td>
                                </tr>
								                                 <tr><td colspan="3">&nbsp;</td></tr>
								<tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_STAFF_NOTIFY_ARRIVAL?> </td>
                                  <td width="59%" align="left" class="toplinks">
                                    <input name="rdNotifyArrival" type="radio" value="1" <?php echo(($var_notifyArrival == 1)?"checked":""); ?>>
                                    <?php echo TEXT_YES?> 
                                    <input name="rdNotifyArrival" type="radio" value="0"  <?php echo(($var_notifyArrival == 0)?"checked":""); ?>>
                                    <?php echo TEXT_NO?> 
</td>
                                </tr>
                                                                <tr><td colspan="3">&nbsp;</td></tr>
								<tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_STAFF_NOTIFY_PVT_MSG?></td>
                                  <td width="59%" align="left" class="toplinks"><input name="rdNotifyPvtMsg" type="radio" value="1"  <?php echo(($var_notifyPvtMsg == 1)?"checked":""); ?>>
<?php echo TEXT_YES?> 
  <input name="rdNotifyPvtMsg" type="radio" value="0"  <?php echo(($var_notifyPvtMsg == 0)?"checked":""); ?>>
<?php echo TEXT_NO?>  
                                  </td>
                                </tr>
                                <tr><td colspan="3">&nbsp;</td></tr>	
								<tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_STAFF_NOTIFY_KB?></td>
                                  <td width="59%" align="left" class="toplinks"><input name="rdNotifyKB" type="radio" value="1"  <?php echo(($var_notifyKB == 1)?"checked":""); ?>>
<?php echo TEXT_YES?> 
  <input name="rdNotifyKB" type="radio" value="0"  <?php echo(($var_notifyKB == 0)?"checked":""); ?>>
<?php echo TEXT_NO?> 
                                  </td>
                                </tr>
                                <tr><td colspan="3">&nbsp;</td></tr>	
								<tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_STAFF_WATCHER_FEATURE?></td>
                                  <td width="59%" align="left" class="toplinks">
									  <input name="rdWatcher" type="radio" value="1"  <?php echo(($var_watcher == 1)?"checked":""); ?>>
										<?php echo TEXT_YES?> 
									  <input name="rdWatcher" type="radio" value="0"  <?php echo(($var_watcher == 0)?"checked":""); ?>>
										<?php echo TEXT_NO?> 
                                  </td>
                                </tr>
                                <tr><td colspan="3">&nbsp;</td></tr>	

								<tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks" valign="top"><?php echo TEXT_STAFF_SIGNATURE?></td>
                                  <td width="59%" align="left" class="toplinks"><textarea name="txtSignature" id="txtSignature" cols="40" rows="7" class="textarea"><?php echo($var_signature);?></textarea>
                                  </td>
                                </tr>
                                                                <tr><td colspan="3" class="btm_brdr">&nbsp;</td></tr>	
																																							
                              </table>
                        </td>
                            </tr>
                        </table>
    

     
                  </td>
              </tr>
            </table>
            <table width="100%"  border="0" cellspacing="10" cellpadding="0">
              <tr>
                <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="5" >
                            <tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center"  class="listingbtnbar">
                                    <td width="22%">&nbsp;</td>
                                    <td width="16%"><input name="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD ?>" onClick="javascript:add();"></td>
                                    <td width="14%"><input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT ?>"  onClick="javascript:edit();"></td>
                                    <td width="16%"><input name="btDelete" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_DELETE ?>" onClick="javascript:deleted();"></td>
                                    <td width="12%"><input name="btCancel" type="reset" class="comm_btn" value="<?php echo BUTTON_TEXT_CANCEL ?>" ></td><!-- onClick="javascript:cancel();" -->
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
	var setValue = "<?php echo trim($var_cssId); ?>";
//	document.frmStaff.cmbCountry.text=setValue;
	try{
 	for(i=0;i<document.frmStaff.cmbCssId.options.length;i++){
            if(document.frmStaff.cmbCssId.options[i].value == setValue){
                        document.frmStaff.cmbCssId.options[i].selected=true;
                        break;
            }
    }
	}catch(e){}
	<?php
		if ($var_id == "") {
			echo("document.frmStaff.btAdd.disabled=false;");
			echo("document.frmStaff.btUpdate.disabled=true;");
			echo("document.frmStaff.btDelete.disabled=true;");
			echo("document.getElementById('showError').style.visibility='hidden';");
			echo("document.getElementById('star').style.visibility='visible';");
			echo("document.frmStaff.txtStaffLogin.readOnly=false;");
		}
		else {
			echo("document.frmStaff.btAdd.disabled=true;");
			echo("document.frmStaff.btUpdate.disabled=false;");
			echo("document.frmStaff.btDelete.disabled=false;");
			echo("document.getElementById('showError').style.visibility='visible';");
			echo("document.getElementById('star').style.visibility='hidden';");
			echo("document.frmStaff.txtStaffLogin.readOnly=true;");
		}
	?>
	document.frmStaff.txtStaffName.focus();
</script>
</form>