<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>                                  |
// |                                                                      |                // |                                                                      |
// +----------------------------------------------------------------------+
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
        $var_staffid = "1";

        if ($_POST["postback"] == "") {

                $sql = "Select vLookUpName,vLookUpValue from sptbl_lookup ";
                $var_result = executeSelect($sql,$conn);
                if (mysql_num_rows($var_result) > 0) {
                    while($var_row = mysql_fetch_array($var_result)){

                        $var_lookupName=$var_row["vLookUpName"];

                        switch ($var_lookupName) {

                        case "MailAdmin":
                             $var_mailAdmin =$var_row["vLookUpValue"];
                             break;
                        case "MailTechnical":
                             $var_mailTechnical =$var_row["vLookUpValue"];
                            break;
                        case "MailEscalation":
                             $var_mailEscalation =$var_row["vLookUpValue"];
                            break;
                        case "MailFromName":
                             $var_mailFromName =$var_row["vLookUpValue"];
                            break;
                        case "MailFromMail":
                             $var_mailFromMail =$var_row["vLookUpValue"];
                            break;
                        case "MailReplyName":
                             $var_mailReplyName =$var_row["vLookUpValue"];
                            break;
                        case "MailReplyMail":
                             $var_mailReplyMail =$var_row["vLookUpValue"];
                            break;
                        }


                    }
                }
                else {
                        $var_mailAdmin = "";
                        $var_mailTechnical = "";
                        $var_mailEscalation = "";
                        $var_mailFromName = "";
                        $var_mailFromMail = "";
                        $var_mailReplyName = "";
                        $var_mailReplyMail = "";
                }
                mysql_free_result($var_result);
        }

        elseif ($_POST["postback"] == "U") {


             $var_mailAdmin = trim($_POST["txtMailAdmin"]);
             $var_mailTechnical = trim($_POST["txtMailTechnical"]);
             $var_mailEscalation = trim($_POST["txtMailEscalation"]);
             $var_mailFromName = trim($_POST["txtMailFromName"]);
             $var_mailFromMail = trim($_POST["txtMailFromMail"]);
             $var_mailReplyName = trim($_POST["txtMailReplyName"]);
             $var_mailReplyMail = trim($_POST["txtMailReplyMail"]);

			$arr = array();
			if($var_mailAdmin != "") {
				$arr["MailAdmin"] = $var_mailAdmin;
			}
			if($var_mailAdmin != "") {
				$arr["MailTechnical"] = $var_mailTechnical;
			}
			if($var_mailAdmin != "") {
				$arr["MailEscalation"] = $var_mailEscalation;
			}
			if($var_mailAdmin != "") {
				$arr["MailFromMail"] = $var_mailFromMail;
			}
			if($var_mailAdmin != "") {
				$arr["MailReplyMail"] = $var_mailReplyMail;
			}

			if(checkLookupDetails($arr,$returnList) == true) {

				 $sql = "Update sptbl_lookup set
				 vLookUpValue='" . mysql_real_escape_string($var_mailAdmin) . "'
				 where vLookUpName = 'MailAdmin'";
				 executeQuery($sql,$conn);

				 $sql = "Update sptbl_lookup set
				 vLookUpValue='" . mysql_real_escape_string($var_mailTechnical) . "'
				 where vLookUpName = 'MailTechnical'";
				 executeQuery($sql,$conn);

				 $sql = "Update sptbl_lookup set
				 vLookUpValue='" . mysql_real_escape_string($var_mailEscalation) . "'
				 where vLookUpName='MailEscalation'";
				 executeQuery($sql,$conn);

				 $sql = "Update sptbl_lookup set
				 vLookUpValue='" . mysql_real_escape_string($var_mailFromName) . "'
				 where vLookUpName='MailFromName'";
				 executeQuery($sql,$conn);


				 $sql = "Update sptbl_lookup set
				 vLookUpValue='" . mysql_real_escape_string($var_mailFromMail) . "'
				 where vLookUpName='MailFromMail'";
				 executeQuery($sql,$conn);


				 $sql = "Update sptbl_lookup set
				 vLookUpValue='" . mysql_real_escape_string($var_mailReplyName) . "'
				 where vLookUpName='MailReplyName'";
				 executeQuery($sql,$conn);


				 $sql = "Update sptbl_lookup set
				 vLookUpValue='" . mysql_real_escape_string($var_mailReplyMail) . "'
				 where vLookUpName='MailReplyMail'";
				 executeQuery($sql,$conn);

                        //Insert the actionlog
					if(logActivity()) {
						$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Mails','0',now())";
						executeQuery($sql,$conn);
					}
					$var_message = MESSAGE_RECORD_UPDATED;
                                        $flag_msg    = 'class="msg_success"';
				}
				else {
                                    
					$var_message = MESSAGE_RECORD_ERROR ;
                                        $flag_msg    = 'class="msg_error"';
				}
             }
             else {
                                $var_message = MESSAGE_RECORD_ERROR ;
                                $flag_msg    = 'class="msg_error"';

             }

function checkLookupDetails($arr_lookup,&$returnList) {
	global $conn;
	$returnList = "";
	$flag = true;
	$arr_duplicate = array();
	if(count($arr_lookup) ==  5) {
		foreach($arr_lookup as $key=>$value) {
			$sub_flag = true;
			/*foreach($arr_lookup as $key_sub=>$value_sub) {
				if($key != $key_sub && strcasecmp($value,$value_sub) == 0) {
					if($key != "MailFromMail" && $key != "MailReplyMail") {
						$flag = false;
						$sub_flag = false;
						$arr_duplicate[$key] = $value;
					}
					elseif(($key == "MailFromMail" && $key_sub != "MailReplyMail") || ($key == "MailReplyMail" && $key_sub != "MailFromMail")) {
						$flag = false;
						$sub_flag = false;
						$arr_duplicate[$key] = $value;
					}
				}
			}*/ // end foreach - II
			if($sub_flag == true) {
				$sql = "Select * from dummy d
					Left join sptbl_users u on (d.num=0 AND u.vEmail='" . mysql_real_escape_string($value) . "')
					Left JOIN sptbl_staffs s on (d.num=1 AND s.vMail='" . mysql_real_escape_string($value) . "' and s.nStaffId !='1')
					Left join sptbl_depts dt on (d.num=2 AND dt.vDeptMail='" . mysql_real_escape_string($value) . "')
					Left join sptbl_companies c on(d.num=3 AND c.vCompMail='" . mysql_real_escape_string($value) . "')
					where d.num < 4  AND (u.nUserId IS NOT NULL OR s.nStaffId IS NOT NULL OR dt.nDeptId IS NOT NULL OR c.nCompId IS NOT NULL)";
					//echo $sql;
				if(mysql_num_rows(mysql_query($sql,$conn)) > 0) {
					$arr_duplicate[$key] = $value;
				}
			}
		} // end foreach - I
		if(count($arr_duplicate) > 0) {
			$returnList = "Duplicate ";
			foreach($arr_duplicate as $key=>$value) {
				$returnList .= $key . ",";
			}
			$returnList = substr($returnList,0,-1);
			//return false;
                        return true;
		}
		else {
			$returnList = "";
			return true;
		}
	}
	else {
		$returnList = "Please submit valid email addresses for all the required fields.";
		return false;
	}
}


?>
<form name="frmMails" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>">
	<div class="content_section">
			<div class="content_section_title">
				<h3><?php echo TEXT_EDIT_MAILS ?></h3>
			</div>
			<table width="100%"  border="0">
			  <tr>
				<td width="76%" valign="top">
				<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
			
							 <tr>
					 <td align="center" colspan=3 >&nbsp;</td>
			
					 </tr>
					 <tr>
					 <td align="center" colspan=3 >
                                             <?php
                                             if ($var_message != ""){?>
                                                <div <?php echo $flag_msg; ?>><?php echo $var_message ?></div>
                                             <?php
                                             }?>
                                            </td>
			
					 </tr>
									   <tr>
					<td >&nbsp;</td>
					 <td align="left" colspan=2 class="toplinks">
					 <?php echo TEXT_FIELDS_MANDATORY ?></td>
			
					 </tr>
					 <tr><td colspan="3">&nbsp;</td></tr>
					 <tr>
					 <td width="2%" align="left">&nbsp;</td>
					 <td width="38%" align="left" class="toplinks"><?php echo TEXT_MAIL_ADMIN?> <font style="color:#FF0000; font-size:9px">*</font> </td>
					 <td width="60%" align="left">
					 <input name="txtMailAdmin" type="text" class="comm_input input_width1a" id="txtMailAdmin" size="30" maxlength="100" value="<?php echo htmlentities($var_mailAdmin); ?>">
					 </td>
					 </tr>
					 <tr><td colspan="3">&nbsp;</td></tr>
					 <tr>
					 <td width="2%" align="left">&nbsp;</td>
					 <td width="38%" align="left" class="toplinks"><?php echo TEXT_MAIL_TECHNICAL?> <font style="color:#FF0000; font-size:9px">*</font> </td>
					 <td width="60%" align="left">
					 <input name="txtMailTechnical" type="text" class="comm_input input_width1a" id="txtMailTechnical" size="30" maxlength="100" value="<?php echo htmlentities($var_mailTechnical); ?>">
					 </td>
					 </tr>
					 <tr><td colspan="3">&nbsp;</td></tr>
					 <tr>
					 <td width="2%" align="left">&nbsp;</td>
					 <td width="38%" align="left" class="toplinks"><?php echo TEXT_MAIL_ESCALATION?> <font style="color:#FF0000; font-size:9px">*</font> </td>
					 <td width="60%" align="left">
					 <input name="txtMailEscalation" type="text" class="comm_input input_width1a" id="txtMailEscalation" size="30" maxlength="100" value="<?php echo htmlentities($var_mailEscalation); ?>">
					 </td>
					 </tr>
					 <tr><td colspan="3">&nbsp;</td></tr>
					 <tr>
					 <td width="2%" align="left">&nbsp;</td>
					 <td width="38%" align="left" class="toplinks"><?php echo TEXT_MAIL_FROM_NAME?> <font style="color:#FF0000; font-size:9px">*</font> </td>
					 <td width="60%" align="left">
					 <input name="txtMailFromName" type="text" class="comm_input input_width1a" id="txtMailFromName" size="30" maxlength="100" value="<?php echo htmlentities($var_mailFromName); ?>">
					 </td>
					 </tr>
					 <tr><td colspan="3">&nbsp;</td></tr>
					 <tr>
					 <td width="2%" align="left">&nbsp;</td>
					 <td width="38%" align="left" class="toplinks"><?php echo TEXT_MAIL_FROM_MAIL?> <font style="color:#FF0000; font-size:9px">*</font> </td>
					 <td width="60%" align="left">
					 <input name="txtMailFromMail" type="text" class="comm_input input_width1a" id="txtMailFromMail" size="30" maxlength="100" value="<?php echo htmlentities($var_mailFromMail); ?>">
					 </td>
					 </tr>
					 <tr><td colspan="3">&nbsp;</td></tr>
					 <tr>
					 <td width="2%" align="left">&nbsp;</td>
					 <td width="38%" align="left" class="toplinks"><?php echo TEXT_MAIL_REPLY_NAME?> <font style="color:#FF0000; font-size:9px">*</font> </td>
					 <td width="60%" align="left">
					 <input name="txtMailReplyName" type="text" class="comm_input input_width1a" id="txtMailReplyName" size="30" maxlength="100" value="<?php echo htmlentities($var_mailReplyName); ?>">
					 </td>
					 </tr>
					 <tr><td colspan="3">&nbsp;</td></tr>
					 <tr>
					 <td width="2%" align="left">&nbsp;</td>
					 <td width="38%" align="left" class="toplinks"><?php echo TEXT_MAIL_REPLY_MAIL?> <font style="color:#FF0000; font-size:9px">*</font> </td>
					 <td width="60%" align="left">
					 <input name="txtMailReplyMail" type="text" class="comm_input input_width1a" id="txtMailReplyMail" size="30" maxlength="100" value="<?php echo htmlentities($var_mailReplyMail); ?>">
					 </td>
					 </tr>
			
					<tr><td colspan="3">&nbsp;</td></tr>
										  </table>
						<table width="100%"  border="0" cellspacing="10" cellpadding="0">
						  <tr>
							<td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
								<tr>
								  <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
								</tr>
							  </table>
								<table width="100%"  border="0" cellspacing="0" cellpadding="0">
								  <tr >
									<td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
									<td ><table width="100%"  border="0" cellspacing="0" cellpadding="5" >
										<tr>
										  <td>
			
										  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
											  <tr align="center"  class="listingbtnbar">
												<td width="23%">&nbsp;</td>
												
												<td width="20%" align=right><input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT; ?>"  onClick="javascript:edit();"></td>
                                                                                                <td width="20%"><input name="reset" type="reset" class="comm_btn_black" value="<?php echo BUTTON_TEXT_CLEAR; ?>" onclick="return cancel();"></td><!-- onClick="javascript:cancel();" -->
                                                                                                <td width="10%"></td>
												<td width="20%">
												<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
												<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
												<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">

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
</form>
