<?php 
include "languages/".$_SP_language."/loginbox.php";

require_once("includes/decode.php");
/*...........Section for active directoryy variables......................*/
$sql = "Select vLookUpName,vLookUpValue from sptbl_lookup ";
$var_result = executeSelect($sql,$conn);
if (mysql_num_rows($var_result) > 0) {
	while($var_row = mysql_fetch_array($var_result)){

		$var_lookupName=$var_row["vLookUpName"];

		switch ($var_lookupName) {

		case "AD_AUTHENTICATION":
			$AD_AUTHENTICATION =  $var_row["vLookUpValue"];
			break;
		case "AD_USER":
			$AD_USER =  $var_row["vLookUpValue"];
			break;
		case "AD_PASS":
			$AD_PASS =  $var_row["vLookUpValue"];
			break;
		case "AD_DOMAIN":
			$AD_DOMAIN =  $var_row["vLookUpValue"];
			break;
		case "AD_HOST":
			$AD_HOST =  $var_row["vLookUpValue"];
			break;
		case "AD_USER_DIR":
			$AD_USER_DIR =  $var_row["vLookUpValue"];
			break;
		}
	}
}
/*...........Section for active directoryy variables......................*/
if(!isValid(0)) {
	echo("<script>window.location.href='invalidkey.php'</script>");
	exit();
}
if(userLoggedIn()){
        return;
}else{//user

                $sql = "Select vLookUpName,vLookUpValue from sptbl_lookup WHERE vLookUpName IN('LangChoice',
                                'DefaultLang','HelpdeskTitle','Logourl','logactivity','MaxPostsPerPage','OldestMessageFirst')";
                $rs = executeSelect($sql,$conn);

                if (mysql_num_rows($rs) > 0) {
                        while($row = mysql_fetch_array($rs)){
                                switch($row["vLookUpName"]) {
                                        case "LangChoice":
                                                        $_SESSION["sess_langchoice"] = $row["vLookUpValue"];
                                                        break;
                                        case "DefaultLang":
                                                        $_SESSION["sess_defaultlang"] = $row["vLookUpValue"];
                                                        break;
                                        case "HelpdeskTitle":
                                                        $_SESSION["sess_helpdesktitle"] = $row["vLookUpValue"];
                                                        break;
                                        case "Logourl":
                                                        $_SESSION["sess_logourl"] = $row["vLookUpValue"];
                                                        break;
										case "logactivity":    //this session variable decides to log activities or not
														$_SESSION["sess_logactivity"] = $row["vLookUpValue"];
														break;
										case "MaxPostsPerPage":
														$_SESSION["sess_maxpostperpage"] = $row["vLookUpValue"];
														break;
										case "OldestMessageFirst":
														 $_SESSION["sess_messageorder"] = $row["vLookUpValue"];
														break;												
                                }
                        }
                }
                mysql_free_result($rs);
				
				if($_SESSION["sess_userlangchange"] =="1"){
				   ;
				}else{
				
				    if (($_SESSION["sess_defaultlang"] !=$_SESSION["sess_language"])) {
                        $_SESSION["sess_language"] = $_SESSION["sess_defaultlang"];
                        echo("<script>window.location.href='index.php'</script>");
						exit();
						
						//header("location:index.php");
                        //exit;
                   }  
				
				} 
				
              
                //$_SESSION["sess_language"] = "fr";
				/*if ($_SESSION["sess_defaultlang"] != $_SESSION["sess_language"]) {
                        $_SESSION["sess_language"] = $_SESSION["sess_defaultlang"];
						echo("<script>window.location.href='index.php'</script>");
                       // header("location:index.php");
                        //exit;
                }*/

			
                /*if ($_SESSION["sess_defaultlang"] != "en") {
                        $_SESSION["sess_language"] = $_SESSION["sess_defaultlang"];
                        echo("<script>window.location.href='index.php'</script>");
						exit();
						
						//header("location:index.php");
                        //exit;
                }
				if($_SESSION["sess_userlangchange"] =="1"){
				}else{
				    if ($_SESSION["sess_defaultlang"] != "en" and ($_SESSION["sess_defaultlang"] !=$_SESSION["sess_language"])) {
                        $_SESSION["sess_language"] = $_SESSION["sess_defaultlang"];
                        echo("<script>window.location.href='index.php'</script>");
						exit();
						
						//header("location:index.php");
                        //exit;
                   }  
				
				} 
				
				if ($_SESSION["sess_defaultlang"] != "en" and ($_SESSION["sess_defaultlang"] !=$_SESSION["sess_language"])) {
                        $_SESSION["sess_language"] = $_SESSION["sess_defaultlang"];
                        echo("<script>window.location.href='index.php'</script>");
						exit();
						
						//header("location:index.php");
                        //exit;
                }*/



         if($_POST["postback"] == "Login"){
		 if($AD_AUTHENTICATION == "Y"){
		 	$error = false;
			$errormessage = "" ;
			if(isNotNull($_POST["txtUserID"])){
				$loginname = trim($_POST["txtUserID"]);
				$username = $AD_USER;
				$password = $AD_PASS;
				$domain = $AD_DOMAIN;
				$domain_username  = $username . "@" . $domain;
				$bdn	=	explode(".",$AD_DOMAIN);
				$bdr	=	"";
				for($i=0;$i<sizeof($bdn);$i++){
					$bdr	.=	"DC=".$bdn[$i];
					if($i != (sizeof($bdn)-1)){
						$bdr	.= ",";
					}
				}
				$user_dir = $AD_USER_DIR.",".$bdr;
				$ldap_server = $AD_HOST;
				$ldap_conn = ldap_connect($ldap_server);
				ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3) or die ("Could not set LDAP Protocol version");
				if($ldapbind = ldap_bind($ldap_conn, $domain_username, $password) == true)
				{
					$attributes = array ("mail" , "name");
					$filter = "(&(objectclass=user) (objectcategory=Person)(sAMAccountName=".$loginname."))";
					$search_result = ldap_search( $ldap_conn, $user_dir, $filter, $attributes ) or die ("LDAP search has failed");
					$info = ldap_get_entries( $ldap_conn, $search_result );
					if($info["count"] > 0){
						@ldap_unbind($ldap_conn);
						@ldap_close($ldap_conn);
					}else{
						$error = true;
						$errormessage = MESSAGE_INVALID_LOGIN;
					}
				}
				@ldap_unbind($ldap_conn);
				@ldap_close($ldap_conn);
				if($error != true){
					$sql  = "SELECT u.nUserId ,u.nCompId, u.vUserName , u.vEmail , u.vLogin , u.vBanned, u.vPassword,u.nCSSId,c.vCSSURL FROM sptbl_users u left outer join sptbl_css c on u.nCSSId = c.nCSSID   ";
					$sql .= " WHERE vLogin = '".addslashes($loginname)."' and vDelStatus='0' ";
					// $sql .= " WHERE vLogin = '".addslashes($loginname)."' and  vPassword ='".addslashes(md5($password))."' and vDelStatus='0' ";
					$result = executeSelect($sql,$conn);
					if (mysql_num_rows($result) > 0) {
						$row = mysql_fetch_array($result);
						if ($row["vBanned"] == "0") {
							$userid   = $row["nUserId"];
							$username = $row["vLogin"];
							$useremail = $row["vEmail"];
							$userfullname = $row["vUserName"];
							$compid = $row["nCompId"];
							$cssurl = $row["vCSSURL"];
							$_SESSION["sess_cssurl"] 		= $cssurl;
							$_SESSION["sess_username"] 		= $username;
							$_SESSION["sess_userid"] 		= $userid;
							$_SESSION["sess_useremail"] 	= $useremail;
							$_SESSION["sess_userfullname"] 	= $userfullname;
							$_SESSION["sess_usercompid"]	= $compid;
							$sql1  = "UPDATE sptbl_users  ";
							$sql1 .= " SET vOnline = '1' WHERE nUserId = '".$userid."' ";
							$result1 = executeSelect($sql1,$conn);
							$sql2  = "Select nPriorityValue,vTicketColor,vPriorityDesc from sptbl_priorities ORDER BY nPriorityValue ";
							$rs2 = executeSelect($sql2,$conn);
							if (mysql_num_rows($rs2) > 0) {
								$cnt = 0;
								while($row2 = mysql_fetch_array($rs2)) {
									$fld_prio[$cnt][0] = $row2["nPriorityValue"];
									$fld_prio[$cnt][1] = $row2["vTicketColor"];
									$fld_prio[$cnt][2] = $row2["vPriorityDesc"];
									$cnt++;
								}
							}
							$_SESSION["sess_priority"] = $fld_prio;
							mysql_free_result($rs2);
							echo "<script>window.location.href='index.php';</script>";
							exit;
						}else {
							$error = true;
							$errormessage = MESSAGE_USER_BANNED;
						}	
					}else{
						$var_userName = $info[0]["name"][0];
						$var_userLogin = $loginname;
						$var_password = $password;
						$var_online = "";
						if(isset($info[0]["mail"][0]) && $info[0]["mail"][0] <>" "){
							$var_email  = $info[0]["mail"][0];
						}else{
							$var_email  = "user@activedirectory.com";
						}
						$var_banned = "0";
						$sql	=	"SELECT * FROM sptbl_companies WHERE vDelStatus = '0' LIMIT 0 , 1";
						$result = executeSelect($sql,$conn);
						if (mysql_num_rows($result) > 0) {
							$row = mysql_fetch_array($result);
							$var_compId = $row["nCompId"];
						}else{
							$var_compId = 1;
						}
						$var_date   = date("m-d-Y h:i:s");
						$var_active = "0";
						$sql = "INSERT INTO sptbl_users set vUserName='" . addslashes($var_userName) . "',
						" . (($var_password != "")?("vPassword='" . md5($var_password) .  "',"):"") .
						"vEmail='" . addslashes($var_email) . "',
						vLogin='".addslashes($var_userLogin)."',
						nCompId='" . addslashes($var_compId) . "',
						vBanned='" . addslashes($var_banned) . "',
						vDelStatus='" . addslashes($var_active) . "'"; 
						executeQuery($sql,$conn);
						$sql  = "SELECT u.nUserId ,u.nCompId, u.vUserName , u.vEmail , u.vLogin , u.vBanned, u.vPassword,u.nCSSId,c.vCSSURL FROM sptbl_users u left outer join sptbl_css c on u.nCSSId = c.nCSSID   ";
						$sql .= " WHERE vLogin = '".addslashes($loginname)."' and vDelStatus='0' ";
						// $sql .= " WHERE vLogin = '".addslashes($loginname)."' and  vPassword ='".addslashes(md5($password))."' and vDelStatus='0' ";
						$result = executeSelect($sql,$conn);
						if (mysql_num_rows($result) > 0) {
							$row = mysql_fetch_array($result);
							if ($row["vBanned"] == "0") {
								$userid   = $row["nUserId"];
								$username = $row["vLogin"];
								$useremail = $row["vEmail"];
								$userfullname = $row["vUserName"];
								$compid = $row["nCompId"];
								$cssurl = $row["vCSSURL"];
								$_SESSION["sess_cssurl"] 		= $cssurl;
								$_SESSION["sess_username"] 		= $username;
								$_SESSION["sess_userid"] 		= $userid;
								$_SESSION["sess_useremail"] 	= $useremail;
								$_SESSION["sess_userfullname"] 	= $userfullname;
								$_SESSION["sess_usercompid"]	= $compid;
								
								$sql1  = "UPDATE sptbl_users  ";
								$sql1 .= " SET vOnline = '1' WHERE nUserId = '".$userid."' ";
								$result1 = executeSelect($sql1,$conn);
								
								$sql2  = "Select nPriorityValue,vTicketColor,vPriorityDesc from sptbl_priorities ORDER BY nPriorityValue ";
								$rs2 = executeSelect($sql2,$conn);
						
								if (mysql_num_rows($rs2) > 0) {
									$cnt = 0;
									while($row2 = mysql_fetch_array($rs2)) {
										$fld_prio[$cnt][0] = $row2["nPriorityValue"];
										$fld_prio[$cnt][1] = $row2["vTicketColor"];
										$fld_prio[$cnt][2] = $row2["vPriorityDesc"];
										$cnt++;
									}
								}
								$_SESSION["sess_priority"] = $fld_prio;
								mysql_free_result($rs2);
								
								//header("Location: index.php");
								echo "<script>window.location.href='index.php';</script>";
								exit;
						
							}
						}
					}
				}
			}else{//user name null
				$error = true;
				$errormessage .= MESSAGE_USER_ID_REQUIRED . "<br>";
			}
		 }else{ // Active directory not require...
                $error = false;
                $errormessage = "" ;
                if(isNotNull($_POST["txtUserID"])){
                        $loginname = trim($_POST["txtUserID"]);
                }else{//user name null
                        $error = true;
                        $errormessage .= MESSAGE_USER_ID_REQUIRED . "<br>";
                }
                if(isNotNull($_POST["txtPassword"])){
                        $password = $_POST["txtPassword"];
                }else{//user name null
                        $error = true;
                        $errormessage .= MESSAGE_PASSWORD_REQUIRED . "<br>";
                }

                if($error){
                        $errormessage = MESSAGE_ERRORS_FOUND . "<br>" .$errormessage;
                }else{//no error so validate
                        $sql  = "SELECT u.nUserId ,u.nCompId, u.vUserName , u.vEmail , u.vLogin , u.vBanned, u.vPassword,u.nCSSId,c.vCSSURL FROM sptbl_users u left outer join sptbl_css c on u.nCSSId = c.nCSSID   ";
                        $sql .= " WHERE vLogin = '".addslashes($loginname)."' and  vPassword ='".addslashes(md5($password))."' and vDelStatus='0' ";
                        $result = executeSelect($sql,$conn);
                        if (mysql_num_rows($result) > 0) {
                                $row = mysql_fetch_array($result);
								if ($row["vBanned"] == "0") {
									$userid   = $row["nUserId"];
									$username = $row["vLogin"];
									$useremail = $row["vEmail"];
									$userfullname = $row["vUserName"];
									$compid = $row["nCompId"];
									$cssurl = $row["vCSSURL"];
									$_SESSION["sess_cssurl"] 		= $cssurl;
									$_SESSION["sess_username"] 		= $username;
									$_SESSION["sess_userid"] 		= $userid;
									$_SESSION["sess_useremail"] 	= $useremail;
									$_SESSION["sess_userfullname"] 	= $userfullname;
									$_SESSION["sess_usercompid"]	= $compid;
	
									$sql1  = "UPDATE sptbl_users  ";
									$sql1 .= " SET vOnline = '1' WHERE nUserId = '".$userid."' ";
									$result1 = executeSelect($sql1,$conn);
	
									$sql2  = "Select nPriorityValue,vTicketColor,vPriorityDesc from sptbl_priorities ORDER BY nPriorityValue ";
									$rs2 = executeSelect($sql2,$conn);
	
									if (mysql_num_rows($rs2) > 0) {
											$cnt = 0;
											while($row2 = mysql_fetch_array($rs2)) {
													$fld_prio[$cnt][0] = $row2["nPriorityValue"];
													$fld_prio[$cnt][1] = $row2["vTicketColor"];
													$fld_prio[$cnt][2] = $row2["vPriorityDesc"];
													$cnt++;
											}
									}
									$_SESSION["sess_priority"] = $fld_prio;
									mysql_free_result($rs2);
	
									//header("Location: index.php");
									echo "<script>window.location.href='index.php';</script>";
									exit;
								}
								else {
									$error = true;
									$errormessage = MESSAGE_USER_BANNED;
								}	
                        }else{
                                $error = true;
                                $errormessage = MESSAGE_INVALID_LOGIN;
                        }
                }
			}

        }else if($_POST["postback"] == "Get Password"){
                $error = false;
                $errormessage = "" ;
                if(isNotNull($_POST["txtUserEmail"])){
                        $useremail = trim($_POST["txtUserEmail"]);
                }else{//user email null
                        $error = true;
                        $errormessage .= MESSAGE_USER_EMAIL_REQUIRED . "<br>";
                }
                if($error){
                        $errormessage = MESSAGE_ERRORS_FOUND . "<br>" .$errormessage;
                }else{//no error so validate and send the
                        $sql  = "SELECT nUserId , vUserName , vEmail , vLogin , vPassword FROM sptbl_users  ";
                        $sql .= " WHERE vEmail = '".addslashes($useremail)."' ";
                        $result = executeSelect($sql,$conn);
                        if (mysql_num_rows($result) > 0) {
                                $row = mysql_fetch_array($result);
                                $userid   = $row["nUserId"];
                                $username = $row["vLogin"];
                                $useremail = $row["vEmail"];
                                $userfullname = $row["vUserName"];

                                $code = rand(1, 999999);

                                $sql  = "UPDATE sptbl_users  ";
                                $sql .= " SET vCodeForPass = '".addslashes($code)."' WHERE nUserId = '".$userid."' ";
//								echo $sql;
                                $result = executeSelect($sql,$conn);

                                //$path = substr($thisfile,0,)
                                $link = getPath()."/resetpass.php?action=resetpass&code=".$code;
                                $message=true;
								
								
								/*****************************************************************************/
								$sql = " Select * from sptbl_lookup where vLookUpName IN('Post2PostGap','MailFromName','MailFromMail',";
								$sql .="'MailReplyName','MailReplyMail','Emailfooter','Emailheader','AutoLock','HelpdeskTitle')";
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
											case "AutoLock":
															$var_autoclock = $row["vLookUpValue"];
															break;	
											case "HelpdeskTitle":
															$var_helpdeskname = $row["vLookUpValue"];
															break;	
																										
										}
									}
								}
								mysql_free_result($result);
								
								$var_mail_body  = $var_emailheader."<br>".
								$var_mail_body .= MESSAGE_YOU_HAVE_REQUESTED_FOR_PASSWORD_RESET . $var_helpdeskname . "<br>";
								$var_mail_body .= MESSAGE_CLICK_TO_RESET_PASSWORD. "<br><br>";
								$var_mail_body .= "<a href=\"$link\">".$link."</a><br><br>";
								$var_mail_body .= $var_emailfooter;
								
								$var_body = $var_mail_body;
								$var_subject = MESSAGE_CONFIRM_PASSWORD_RESET_REQUEST ;
								$var_email_to = $useremail;
								$Headers="From: $var_fromName <$var_fromMail>\n";
								$Headers.="Reply-To: $var_replyName <$var_replyMail>\n";
								$Headers.="MIME-Version: 1.0\n";
								$Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

							   // it is for smtp mail sending
							   if($_SESSION["sess_smtpsettings"] == 1){
									$var_smtpserver = $_SESSION["sess_smtpserver"];
									$var_port = $_SESSION["sess_smtpport"];
						
									SMTPMail($var_fromMail,$var_email_to,$var_smtpserver,$var_port,$var_subject,$var_body);
							   }
							   else				
									@mail($var_email_to,$var_subject,$var_body,$Headers);
								/*****************************************************************************/
								
                                $infomessage = MESSAGE_LINK_FOR_PASSWORD_RESET_SENT_TO . "'".$var_email_to."'";
                        }else{
                                $error = true;
                                $errormessage = MESSAGE_INVALID_EMAIL;
                        }
                }
        }


?>
<script language="JavaScript">
function checkLoginForm(){
        var frm = window.document.frmLogin;
        var errors="";
        if(frm.txtUserID.value == ""){
                errors += "<?php echo MESSAGE_USER_ID_REQUIRED; ?>"+ "\n";
        }
        if(frm.txtPassword.value == ""){
                errors += "<?php echo MESSAGE_PASSWORD_REQUIRED; ?>" + "\n";
        }
        if(errors !=""){
                errors = "<?php echo MESSAGE_ERRORS_FOUND; ?>"+  "\n" +  "\n" + errors;
                alert(errors);
                return false;
        }else{
                frm.postback.value = "Login";
                frm.submit();
        }
}
function toggleVisibility(divstyle){
    var frm = window.document.frmLogin;
        if(document.getElementById(divstyle).style.display=="none"){
          document.getElementById(divstyle).style.display='';
          frm.txtUserEmail.focus();
        }else{
          document.getElementById(divstyle).style.display='none';
        }

}
	function isValidEmail(email){
		var str1=email;
		var arr=str1.split('@');
		var eFlag=true;
		if(arr.length != 2)
		{
			eFlag = false;
		}
		else if(arr[0].length <= 0 || arr[0].indexOf(' ') != -1 || arr[0].indexOf("'") != -1 || arr[0].indexOf('"') != -1 || arr[1].indexOf('.') == -1)
		{
				eFlag = false;
		}
		else
		{
			var dot=arr[1].split('.');
			if(dot.length < 2)
			{
				eFlag = false;
			}
			else
			{
				if(dot[0].length <= 0 || dot[0].indexOf(' ') != -1 || dot[0].indexOf('"') != -1 || dot[0].indexOf("'") != -1)
				{
					eFlag = false;
				}
	
				for(i=1;i < dot.length;i++)
				{
					if(dot[i].length <= 0 || dot[i].indexOf(' ') != -1 || dot[i].indexOf('"') != -1 || dot[i].indexOf("'") != -1)
					{
						eFlag = false;
					}
				}
				if(dot[i-1].length > 4)
					eFlag = false;				
			}
		}
			return eFlag;	
	}

function checkGetPasswordForm(){
        var frm = window.document.frmLogin;
        var errors="";
        if(frm.txtUserEmail.value == ""){
                errors += "<?php echo MESSAGE_USER_EMAIL_REQUIRED; ?>"+ "\n";
        }
		else if(!isValidEmail(frm.txtUserEmail.value)) {
			errors += "<?php echo MESSAGE_INVALID_EMAIL; ?>"+ "\n";
		}
        if(errors !=""){
                errors = "<?php echo MESSAGE_ERRORS_FOUND; ?>"+  "\n" +  "\n" + errors;
                alert(errors);
                return false;
        }else{
                frm.postback.value = "Get Password";
                frm.submit();
        }
}

function passPress()
{
if(window.event.keyCode=="13"){
		checkLoginForm();
	}
}
</script>
<form name="frmLogin" method="post" action="<?php echo SITE_URL;?>index.php">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="14"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td ><img src="images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>


                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="1" ><img src="images/spacerr.gif" width="1" height="1"></td>
                        <td class="pagecolor">
                                                <table width="100%"  border="0" cellspacing="0" cellpadding="5">
                          <tr>
                            <td width="93%" class="left_item_title"><?php echo HEADING_LOGIN; ?></td>
                          </tr>
                        </table>

                        <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
                              <tr>
                                <td width="9%">&nbsp;</td>
                                <td colspan="2">&nbsp;</td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td colspan="2" class="toplinks"><?php echo TEXT_USER_ID;?></td>
                                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td colspan="2"><input name="txtUserID" type="text" class="comm_input" id="txtUserID" value="<?php echo(htmlentities($_POST["txtUserID"]));?>" style="width:150px"></td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td colspan="2" class="toplinks"><?php echo TEXT_PASSWORD;?></td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td colspan="2"><input name="txtPassword" type="password" class="comm_input" onKeyPress="javascript:passPress();" style="width:150px"></td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td colspan="2" align="center">&nbsp;</td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td width="20%" align="center">&nbsp;</td>
                                <td width="71%" align="left">
                                  <input name="btnSubmit" type="button" class="comm_btn" value="<?php echo TEXT_LOGIN;?>" onClick="return checkLoginForm();" >
                                                                  <input type="hidden" name="postback" value="">
                                </td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td colspan="2">&nbsp;</td>
                              </tr>
                              <tr>
                                <td colspan="3" align="center" >
								<?php if($_SESSION["sess_postticket_before_register"]==1) 
								  { 
								?>
									<a href="<?php echo SITE_URL;?>register.php" class="listing"><?php echo TEXT_NEW_USER_SIGNUP;?></a><br>&nbsp;<br>
							    <?php 
							 	  }
								?>	 
									
								<a href="#" name="forgotPassword" onClick="javascript:toggleVisibility('GetPass');" class="listing"><?php echo TEXT_FORGOT_PASSWORD;?></a></td>
                              </tr>
							  <tr><td colspan="3">&nbsp;</td></tr>
                          </table>
                            <div id="GetPass">
                                 <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
                                      <tr>
                                        <td width="9%">&nbsp;</td>
                                        <td colspan="2" class="listing"><?php echo TEXT_USER_EMAIL;?></td>
                                      </tr>
                                      <tr>
									    <td>&nbsp;</td>
                                        <td colspan="2"><input name="txtUserEmail" id="txtUserEmail" type="text" class="textbox" style="width:150px"></td>
                                      </tr>

                                      <tr>
                                        <td>&nbsp;</td>
                                        <td colspan="2" align="center">&nbsp;</td>
                                      </tr>
                                      <tr>
                                        <td>&nbsp;</td>
                                        <td colspan="2" width="91%" align="left">
                                          <input name="btnGetPassword" type="button" class="button" value="<?php echo TEXT_GET_PASSWORD;?>" onClick="return checkGetPasswordForm();" >
                                        </td>
                                      </tr>
                                      <tr><td colspan="3">&nbsp;</td></tr>
                                  </table>
                            </div>
                        </td>
                        <td width="1" ><img src="images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                    </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td ><img src="images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                  </table></td>
              </tr>
          </table>
</form>
<script>
document.getElementById('GetPass').style.display='none';
</script>
<?php
}
?>