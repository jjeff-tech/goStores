<?php 
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: 			*/
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshith<roshith@armia.com>             		              |
// |          										                      |
// +----------------------------------------------------------------------+

require_once("../includes/decode.php");
	if(!isValid(1)) {
	echo("<script>window.location.href='../invalidkey.php'</script>");
	exit();
	}
//warning message before 10 days
if($glob_date_check == "Y")
{
	echo("<script>alert('" . MESSAGE_LICENCE_EXPIRE . $glob_date_days . MESSAGE_LICENSE_DAYS . "');</script>");
}
//end warning

$var_staffid = $_SESSION["sess_staffid"];
$fld_arr = $_SESSION["sess_fieldlist"];
$fld_prio = $_SESSION["sess_priority"];


		$var_next_limitvalue = $_GET["limitval"];

//	echo $_SESSION['next_sql'];
//	echo "<br>limitvalue=".$var_next_ticketid;
//	$nextsql = $_SESSION['next_sql']." LIMIT ".$var_next_ticketid.",1";
//	echo "<br>".$nextsql;


if($_GET["mt"] == "y") {
	$var_ticketid = $_GET["tk"];
	$var_userid = $_GET["us"];
	$var_stylename = $_GET["stylename"];
	$var_styleminus = $_GET["styleminus"];
	$var_styleplus = $_GET["styleplus"];

	//paging of correspondance
	$var_numBegin = $_GET["numBegin"];
	$var_start = $_GET["start"];
	$var_begin = $_GET["begin"];
	$var_num = $_GET["num"];
	//paging of correspondance
}
elseif($_POST["mt"] == "y") {
	$var_ticketid = $_POST["tk"];
	$var_userid = $_POST["us"];
	$var_stylename = $_POST["stylename"];
	$var_styleminus = $_POST["styleminus"];
	$var_styleplus = $_POST["styleplus"];
	//paging of correspondance
	$var_numBegin = $_POST["numBegin"];
	$var_start = $_POST["start"];
	$var_begin = $_POST["begin"];
	$var_num = $_POST["num"];
	//paging of correspondance
}
elseif($_POST["mt"] == "u") {
	$var_ticketid = $_POST["tk"];
	$var_userid = $_POST["us"];
	$frm_ownerid = $_POST["cmbOwner"];
	$frm_deptid = $_POST["cmbDepartment"];
	$frm_status = $_POST["cmbStatus"];
	$frm_lock = $_POST["cmbLock"];
        $frm_priority = $_POST["cmbPriority"];
	$var_stylename = $_POST["stylename"];
	$var_styleminus = $_POST["styleminus"];
	$var_styleplus = $_POST["styleplus"];
	//paging of correspondance
	$var_numBegin = $_POST["numBegin"];
	$var_start = $_POST["start"];
	$var_begin = $_POST["begin"];
	$var_num = $_POST["num"];
	//paging of correspondance
}elseif($_POST["mt"] == "D") {
	$var_ticketid = $_POST["tk"];
	$var_userid = $_POST["us"];
	$frm_ownerid = $_POST["cmbOwner"];
	$frm_deptid = $_POST["cmbDepartment"];
	$frm_status = $_POST["cmbStatus"];
	$frm_lock = $_POST["cmbLock"];
        $frm_priority = $_POST["cmbPriority"];
	$var_stylename = $_POST["stylename"];
	$var_styleminus = $_POST["styleminus"];
	$var_styleplus = $_POST["styleplus"];
	//paging of correspondance
	$var_numBegin = $_POST["numBegin"];
	$var_start = $_POST["start"];
	$var_begin = $_POST["begin"];
	$var_num = $_POST["num"];
	//paging of correspondance
}elseif($_POST["mt"] == "f") {
	$var_ticketid = $_POST["tk"];
	$var_userid = $_POST["us"];
	$frm_ownerid = $_POST["cmbOwner"];
	$frm_deptid = $_POST["cmbDepartment"];
	$frm_status = $_POST["cmbStatus"];
	$frm_lock = $_POST["cmbLock"];
        $frm_priority = $_POST["cmbPriority"];
	$var_stylename = $_POST["stylename"];
	$var_styleminus = $_POST["styleminus"];
	$var_styleplus = $_POST["styleplus"];
	//paging of correspondance
	$var_numBegin = $_POST["numBegin"];
	$var_start = $_POST["start"];
	$var_begin = $_POST["begin"];
	$var_num = $_POST["num"];
	$var_forward_email = $_POST["txtForward"];
	$var_forward_email_cc = $_POST["txtForwardCC"];
	$var_forward_comments = $_POST["txtComments"];
	
	//paging of correspondance
}
//Block - I (populate the allowed departments for the user)
        $lst_dept = "'',";
        $sql = "Select nDeptId from sptbl_staffdept where nStaffId='$var_staffid'";
        $rs_dept = executeSelect($sql,$conn);
        if (mysql_num_rows($rs_dept) > 0) {
                while($row = mysql_fetch_array($rs_dept)) {
                        $lst_dept .= $row["nDeptId"] . ",";
                }
        }
        $lst_dept = substr($lst_dept,0,-1);

        mysql_free_result($rs_dept);
//End Of Block - I
$arrayDept=array();
$arrayDeptName=array();
$sql = "Select nDeptId,nResponseTime,vDeptDesc from sptbl_depts";
$result = mysql_query($sql) or die(mysql_error());
if(mysql_num_rows($result) > 0) {
        while($row = mysql_fetch_array($result)) {
                $arrayDeptName[$row["nDeptId"]]=TEXT_DEPT1.$row["vDeptDesc"].TEXT_DEPT2;
               // $arrayDept[$row["nDeptId"]['name']]=TEXT_DEPT1.$row["vDeptDesc"].TEXT_DEPT2;
        }
}

if ($_POST["mt"] == "u") {
        $update_flag = false;

		$sql = " Select * from sptbl_lookup where vLookUpName IN('MailFromName','MailFromMail',";
	    $sql .="'MailReplyName','MailReplyMail','Emailfooter','Emailheader','MailEscalation','HelpdeskTitle')";
			$result = executeSelect($sql,$conn);
			if(mysql_num_rows($result) > 0) {
					while($row2 = mysql_fetch_array($result)) {
							switch($row2["vLookUpName"]) {
									case "MailFromName":
													$var_fromName = $row2["vLookUpValue"];
													break;
									case "MailFromMail":
													$var_fromMail = $row2["vLookUpValue"];
													break;
									case "MailReplyName":
													$var_replyName = $row2["vLookUpValue"];
													break;
									case "MailReplyMail":
													$var_replyMail = $row2["vLookUpValue"];
													break;
									case "Emailfooter":
													$var_emailfooter = $row2["vLookUpValue"];
													break;
									case "Emailheader":
													$var_emailheader = $row2["vLookUpValue"];
													break;
									case "MailEscalation":
													$var_emailescalation = $row2["vLookUpValue"];
													break;
									case "HelpdeskTitle":
													$var_helpdesktitle = $row2["vLookUpValue"];
													break;
							}
					}
			}
	   mysql_free_result($result);

        $sql = "Select * from sptbl_tickets where nTicketId='" . addslashes($var_ticketid) . "' AND nUserId='" . addslashes($var_userid) . "'";
        $rs = executeSelect($sql,$conn);
        if (mysql_num_rows($rs) > 0) {
                $row = mysql_fetch_array($rs);
                $mail_refno = $row["vRefNo"];
                $mail_title = $row["vTitle"];
				$mail_status = $row["vStatus"];
                if ($row["nLockStatus"] == "1") {
                        if ($row["nOwner"] != "0" && $row["nOwner"] != $var_staffid) {
                                $var_message = MESSAGE_RECORD_ERROR;
                                $flag_msg = "class='msg_error'";
                        }
                        else {
                                $update_flag = true;
                        }
                }
                if($frm_lock == "1" && $frm_ownerid != $var_staffid) {
                                $update_flag = false;
                                $var_message = MESSAGE_RECORD_ERROR;
                                 $flag_msg = "class='msg_error'";
                }
                else {
                        $update_flag = true;
                }
                if ($update_flag == true) {
                                $qry1 = "";
                                $qry2 = "";

                                if ($row["nOwner"] != $frm_ownerid || $row["nDeptId"] != $frm_deptid) {
                                        $sql = "Select s.vLogin,s.vStaffname,s.nNotifyAssign,s.vMail,s.vSMSMail,sd.nDeptId from sptbl_staffdept sd inner join sptbl_staffs s on
                                                        sd.nStaffId = s.nStaffId Where sd.nStaffId='" . addslashes($frm_ownerid) . "'
                                                         AND sd.nDeptId='" . addslashes($frm_deptid) . "' AND s.vDelStatus='0' AND s.vType != 'A' ";
										$rs_chk = executeSelect($sql,$conn);
	                                        if (mysql_num_rows($rs_chk) > 0) { // if 'no owner' is  selected the query returns none 
                                                $row = mysql_fetch_array($rs_chk);
                                                        if ($row["nNotifyAssign"] == "1") {
                                                                //mail staff
                                                           $var_email=$row['vMail'];
                                                     	   $var_mail_body=$var_emailheader."<br>".TEXT_MAIL_START."&nbsp;". stripslashes($row["vStaffname"]) .",<br>";
                                                           $var_mail_body .= "<br><br>";
														   $var_mail_body .= TEXT_MAIL_BODY ."[ " . $mail_refno . " ] " . TEXT_MAIL_BY . stripslashes($_SESSION['sess_staffname']) . "<br><br>";
                                                           $var_mail_body .= TEXT_MAIL_THANK . "<br>" . stripslashes($var_helpdesktitle)  . "<br>".$var_emailfooter;
                                                           $var_subject = TEXT_EMAIL_SUB;
                                                           $var_body = $var_mail_body;
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

															if(trim($row["vSMSMail"]) != "") {
																 $var_email = trim($row["vSMSMail"]);
																 $var_mail_body="";
																 $var_mail_body = TEXT_MAIL_START." ".stripslashes($row['vStaffname']).", ".
																 $var_mail_body .= TEXT_SMS1 . " : " . $mail_refno . ".  "  .TEXT_MAIL_THANK." ". stripslashes($var_helpdesktitle);

																// it is for smtp mail sending
																if($_SESSION["sess_smtpsettings"] == 1){
																	$var_smtpserver = $_SESSION["sess_smtpserver"];
																	$var_port = $_SESSION["sess_smtpport"];
														
																	SMTPMail($var_fromMail,$var_email,$var_smtpserver,$var_port,"",$var_mail_body);
																}
																else
																	$mailstatus=@mail($var_email,"",$var_mail_body,$Headers);
															}
                                                                //end mail staff
                                                        }  
                                                        $qry1 = ",nDeptId='" . addslashes($frm_deptid) . "',nOwner='" . addslashes($frm_ownerid) . "' ";
													}else if($frm_ownerid !='0'){ 
															$update_flag = false;
															$var_message = MESSAGE_RECORD_ERROR;
                                                                                                                         $flag_msg = "class='msg_error'";
													}
//	                                        $qry1 = ",nDeptId='" . addslashes($frm_deptid) . "',nOwner='" . addslashes($frm_ownerid) . "' "; 
                                        }

									if($frm_ownerid =='0')    // added on 14-11-06 by roshith for assigning 'owner' to 'no owner'
	                                        $qry1 = ",nDeptId='" . addslashes($frm_deptid) . "',nOwner='" . addslashes($frm_ownerid) . "' "; 

    	                            if ($update_flag == true) {
                                                $sql = "Update sptbl_tickets set  vStatus='" . addslashes($frm_status) . "',
                                                                nLockStatus='" . (($frm_lock == "1")?"1":0) . "',
                                                                    vPriority='" . (($frm_priority == "")?"0":$frm_priority) . "'" . $qry1 . " Where
                                                                nTicketId='" . addslashes($var_ticketid) . "' ";
												executeQuery($sql,$conn);

												//Insert the actionlog
												if(logActivity()) {
													$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Tickets','$var_ticketid',now())";
													executeQuery($sql,$conn);
												}

												//mail if the status is changed to escalated
													if ($frm_status == "escalated" && $mail_status != "escalated") { //mail admin if escalated

                                                                                                    $var_body = $var_emailheader."<br>".TEXT_MAIL_START."&nbsp; Admin,<br>";
                                                                                                    $var_body .= TEXT_ESCALATED_BODY ." ". $mail_refno . TEXT_MAIL_BY . stripslashes($_SESSION['sess_staffname']) ."<br><br>";
                                                                                                    $var_body .= TEXT_MAIL_THANK. "<br>" . stripslashes($var_helpdesktitle)  . "<br>" . $var_emailfooter;
                                                                                                    $var_subject = TEXT_ESCALATION_SUB;
                                                                                                    $Headers="From: $var_fromName <$var_fromMail>\n";
                                                                                                    $Headers.="Reply-To: $var_replyName <$var_replyMail>\n";
                                                                                                    $Headers.="MIME-Version: 1.0\n";
                                                                                                    $Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

                                                                                                    // it is for smtp mail sending
                                                                                                    if($_SESSION["sess_smtpsettings"] == 1) {
                                                                                                        $var_smtpserver = $_SESSION["sess_smtpserver"];
                                                                                                        $var_port = $_SESSION["sess_smtpport"];

                                                                                                        SMTPMail($var_fromMail,$var_emailescalation,$var_smtpserver,$var_port,$var_subject,$var_body);
                                                                                                    }
                                                                                                    else
                                                                                                        $mailstatus=@mail($var_emailescalation,$var_subject,$var_body,$Headers);
                                                                                                }																 //end mail admin
												//end mail escalated
                                                                                               if ($frm_status == "closed")// Mail send to user on ticket close
                                                                                                {
                                                                                                    sendMailUserTicketClose($mail_refno,$var_helpdesktitle);

                                                                                                }// Mail send to user on ticket close ends
                                                $var_message = MESSAGE_RECORD_UPDATED;
                                                 $flag_msg = "class='msg_success'";
                                }
                }
        }
        mysql_free_result($rs);
}else if ($_POST["mt"] == "D") {
	$tickettobedelete=$_POST['delid'];
	deleteChecked($tickettobedelete,$message);

        if($flag_del == true){
            //header("Location:tickets.php?mt=y&tp=a&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&msg=deleted");
            ?>
            <script type='text/javascript'>
                window.location.href = "tickets.php?mt=y&tp=a&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&msg=deleted";                
            </script>
            <?php
            exit;
        }
}else if ($_POST["mt"] == "f") {   // forward email starts here

		$var_staffid = $_SESSION["sess_staffid"];
		$sql ="select tSignature from sptbl_staffs where nStaffId='".addslashes($var_staffid)."'";
		$result = executeSelect($sql,$conn);
		$var_row = mysql_fetch_array($result);
		$var_signature= $var_row["tSignature"];
		mysql_free_result($result);
		$sql="select nDeptid,vTitle,nUserId,tQuestion,vRefNo from sptbl_tickets where nTicketId='".addslashes($var_ticketid)."'";
//echo $sql;
		$result = executeSelect($sql,$conn);
		if (mysql_num_rows($result) > 0) {
			while($var_row = mysql_fetch_array($result)){
				$var_deptid= $var_row["nDeptid"];
				$var_tickettitle= $var_row["vTitle"];
				$var_tqusetion=$var_row["tQuestion"];
				$var_refno= $var_row["vRefNo"];
				$var_userid= $var_row["nUserId"];
				$var_replymatter= $var_replymatter.$var_row["tQuestion"];
		 	}	
		}
		else {
			$var_message = MESSAGE_RECORD_ERROR;
                         $flag_msg = "class='msg_error'";
		}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$sql = "Select t.nTicketId,t.vTitle,t.tQuestion,t.dPostDate,t.vMachineIP,
			r.nReplyId,r.nStaffId,r.nUserId,r.vStaffLogin,r.dDate,r.tReply,r.tPvtMessage,r.vMachineIP as 'ReplyIp'
			 from dummy d
			Left join sptbl_tickets t on (d.num=0 AND t.nTicketId='" . addslashes($var_ticketid)  . "'
			 AND t.nUserId='" . addslashes($var_userid) . "')
			Left JOIN sptbl_replies r on (d.num=1 AND r.nTicketId='" . addslashes($var_ticketid) . "')
			where d.num < 2  AND (t.nTicketId IS NOT NULL OR r.nReplyId IS NOT NULL) order by r.dDate ";

	if($_SESSION["sess_messageorder"] == "1") {
		$sql .= " ASC";
	}
	else {
		$sql .= " DESC";
	}
//	echo($sql);
//	$totalrows = mysql_num_rows(executeSelect($sql,$conn));
//	settype($totalrows,integer);
//	settype($var_begin,integer);
//	settype($var_num,integer);
//	settype($var_numBegin,integer);
//	settype($var_start,integer);

//	$var_calc_begin = ($var_begin == 0)?$var_start:$var_begin;
//	$sql = $sql.$navigate[0];
	$rs = executeSelect($sql,$conn);

	$var_reply_idlist = "";
	while($row = mysql_fetch_array($rs)) {
		if($row["nReplyId"] != "") {
			$var_reply_idlist .= "," . $row["nReplyId"];
		}
	}
	if(mysql_num_rows($rs) > 0) {
		mysql_data_seek($rs,0);
		if($var_reply_idlist != "") {
			$var_subquery = " OR  nReplyId IN(" . substr($var_reply_idlist,1) . ")";
		}
		$str = "";
		while($row = mysql_fetch_array($rs)) {
			if($row["nTicketId"] != "") {  //Ticket section
				 $str = $str."<table width='100%' border='1' cellspacing='1' cellpadding='1'>
								<tr align=left  bgcolor='#CCCCCC'>
									<td  width='35%'>
										<b>User</b>";
						$user = htmlentities($var_username);
						$str .= $user."									
									</td>
									<td  width='37%' ><b>Date :</b>";
									
					$postdate = date('m-d-Y H:i',strtotime($row['dPostDate']));
						$str .= $postdate."  </td>
									<td width='28%'><b>IP :</b>";
					$machineIp = $row['vMachineIP'];
						$str .= $machineIp."<br></td>
								</tr>
								<tr align='left'>
									<td colspan='4' width='100%'>
										<br>Title:";
							$title = htmlentities($row['vTitle']);
							$str  .= $title."<br>&nbsp;
									</td>
					  			</tr>
								<tr>
								<td colspan='4' class='bodycolor' >						
									<table width='100%'  border='1' cellpadding='1' cellspacing='1'  >
										<tr align='left'  class='listing'>
											<td colspan='4' width='10%'>";
												$question = nl2br(stripslashes($row['tQuestion']));
												$str .= $question."	
											</td>
									    </tr>
									</table>
								</td>
							</tr>
						</table>";
		} //end if ticket section
		else { //else correspondance section
				$str .= "<table width='100%' border='1' cellspacing='0' cellpadding='0'>
							<tr align='left' class='headings2' bgcolor='#CCCCCC'>
								<td  width='35%' style='word-break:break-all; '>
									&nbsp;";
									if ($row['nStaffId'] != '') {
										$str .= "<b>Staff :</b>";
											$stafflogin =   htmlentities($row['vStaffLogin']);
										$str .= $stafflogin."&nbsp;&nbsp;";
									}
									elseif ($row['nUserId'] != '') {
											$var_style = 'ticketband';
											$str .= "User :". htmlentities($var_username);
									}
									$str .= "</td>
										<td  width='37%' >";
										$var_last_update_chng = date('m-d-Y H:i',strtotime($row['dDate']));
									$str .= "<b>Date :</b>".$var_last_update_chng;
							$str .= "</td>
									<td width='28%'>";
							$ip =$row['ReplyIp'];
							$str .= "<b>IP :</b>".$ip;
							$str .= "</td>
									<td>
										<br>&nbsp;
									</td>
								</tr>
								<tr>
									<td colspan='4'>
										<table width='100%'  border='0' cellpadding='0' cellspacing='0'>
											<tr align='center'>";
											  if ($showflag == true) {
													$str .=	" <td align='right'>&nbsp;</td>";
											  }else{
													$str .= " <td width='100%'>&nbsp;</td>";
											  }
											$str .= "</tr>
									  </table>
								</td>
							</tr>
							<tr>
								<td colspan='4' class='bodycolor' >
									<table width='100%'  border='0' cellpadding='0' cellspacing='0'  >
										<tr align='left'  class='listing'>
											<td colspan='4' width='10%' style='word-break:break-all;'>";
									$reply = nl2br(stripslashes($row['tReply']));
									$str .= $reply."</td>
										</tr>
										<tr align='left' >
											<td colspan='4' class='listingmaintext'>&nbsp;</td>
										</tr>";
				
								if ($var_staffid == $row['nStaffId'] && trim($row['tPvtMessage']) != '') {
								$str .= "<tr ><td colspan=4 class='commentband' align='left'>Comments</td></tr>
										<tr><td colspan=4 align='left'>";
								$private = nl2br(stripslashes($row['tPvtMessage']));
								
								$str .= $private."</td></tr>";
					 }
				
					$str .= "<tr align='left' class='listingmaintext'>
								<td colspan='2'>&nbsp;</td>
								<td colspan='2'>&nbsp;</td>
							 </tr>
						  </table></td>
						</tr>";
				
				$str .= "</table>";
		} //end else correspondance section
	  }
}			
//		$var_replymatter=$var_signature."\n######################################\n".replacestr($var_replymatter);
		$var_ereplymatter=$var_signature."<br>######################################<br>".replacestrforemail($var_replymatter);
//		$var_qtrp=$var_signature."\n######################################\n".$var_replymatter;

	   $var_fromName = htmlentities($_SESSION['sess_staffname']);

	   $var_email = $var_forward_email;
	   $var_mail_body = $var_emailheader."<br>Hi&nbsp;,<br>";
	   $var_mail_body .= "<br><br>";
	   $var_mail_body .= $var_forward_comments."<br><br>";
	   $var_mail_body .= $str."<br><br>";
	   $var_mail_body .= "<br /><br />".$var_ereplymatter;
	   $var_mail_body .= "<br />" . htmlentities($var_helpdesktitle)  . "<br>".$var_emailfooter;
	   $var_body = $var_mail_body;
	   $var_subject = TEXT_EMAIL_FORWARDED_SUB;

	   $Headers="From: $var_fromName <$var_fromMail>\n";
	   $Headers="CC: $var_forward_email_cc <$var_forward_email_cc>\n";
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

	   $var_message_forwarded = MESSAGE_RECORD_FORWARDED;
            $flag_msg = "class='msg_success'";
	   $var_forward_email ="";
	   $var_forward_email_cc ="";
	   $var_forward_comments= ""; 		// ticket forward email ends here
}else {
	$_SESSION['sess_backurl'] = getPageAddress();
}
// added on 1-11-06 by roshith for ticket reply re-directing
 if(isset($_GET['msg'])){
     $message = MESSAGE_TICKET_REPLIED;
     $flag_msg = "class='msg_success'";
 }
 
 if(isset($_GET['msg']) && $_GET['msg'] == 'Hold') {
     $message = MESSAGE_HOLD_SUCCESS;
     $flag_msg = "class='msg_success'";
 }

?>
<script type="text/javascript">
function userdetails(uid){

var clientWindow=window.open('userdetails.php?uid='+uid,'mywindow','width=300,height=100,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=no,resizable=no,maximize=no')


}

</script>

<form name="frmDetail" action="<?php echo   $_SERVER['PHP_SELF']?>" method="post">
  <input type=hidden name="delid" >
  <!--  History Section -->
  
  <?php if($message){ ?>
 <div <?php echo $flag_msg; ?>> <?php echo $message;?> </div>
 <?php }?>
  
<div class="exp_title">
<div class="left"><h4><?php echo HEADING_VIEW_HISTORY ?></h4></div>
<div class="exp_title_icon right"><a href="javascript:manageDetails('historymatter','historyimage')"><img id="historyimage" src="../images/arrow_down.png" border="0"></a></div>       
<div class="clear"></div>
</div>
			  
             
						
						<table width="100%" id="historymatter" style="display:none;" border="0" cellspacing="0" cellpadding="0" >
                            <tr>
                              <td>
							  
							  <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="list_tbl">
                                  <tr align="left">
                                    <th >&nbsp;</th>
                                    <th align="left" valign="top" ><?php echo (TEXT_REF_NO); ?> </th>
                                    <th  align="left" valign="top"><?php echo (TEXT_TITLE); ?> </th>
                                    <th align="left" valign="top"><?php echo (TEXT_STATUS); ?> </th>
                                    <th align="left" valign="top" ><?php echo (TEXT_DATE); ?> </th>
                                  </tr>
                                  <?php
//***********************HISTORY SECTION**************************
$sql = "Select t.nTicketId,t.nUserId,t.vTitle,t.vRefNo,t.vStatus,t.dPostDate,t.nDeptId,rp.nStaffId,rp.nUserId as rpuserid from sptbl_tickets t left join sptbl_replies rp on (rp.dDate=t.dLastAttempted and rp.nTicketId=t.nTicketId) WHERE t.nUserId='" . addslashes($var_userid) . "'
                AND t.vDelStatus='0' ";

$qryopt="";

//$var_back="companies.php?mt=ybegin=" . $var_begin . "&num=" . $var_num . "&numBegin=" . $var_numBegin . "&start=$var_start&cmbSearch=" . $var_cmbSearch . "&txtSearch=" . urlencode($var_search) . "&";
//$_SESSION["backurl"] = $sess_back;

$sql .= $qryopt . " Order By t.dPostDate DESC Limit 0,5 ";

$rs = executeSelect($sql,$conn);
$count=0;
while($row = mysql_fetch_array($rs)) {
	
$lastanswerd=TEXT_LA;
		if($row['nStaffId']>0){
		  $lastanswerd=TEXT_LAS;
		}else if($row['rpuserid']>0){
		  $lastanswerd=TEXT_LAU;
		}
?>
                                  <tr>
                                    <td  align="left" valign="top" style="word-break:break-all;" align="center"><span id=link<?php echo $count ?> onMouseOver=displayAd(<?php echo $count ?>,<?php echo $row["nTicketId"] ?>); onMouseOut=hideAd();><a href="viewticket.php?mt=y&tk=<?php echo $row["nTicketId"] ?>&us=<?php echo $row["nUserId"] ?>"><img src='./../images/ticketdetails.gif' border=0></a></span></td>
                                    <td align="left" valign="top"><a href="viewticket.php?mt=y&tk=<?php echo $row["nTicketId"]; ?>&us=<?php echo($row["nUserId"]); ?>&" class="listing"><?php echo $row["vRefNo"]."<br>".$lastanswerd;?></a> </td>
                                    <td align="left" valign="top"><?php
													if (strlen($row["vTitle"]) > 32) {
															echo htmlentities(substr($row["vTitle"],0,32) . "...")."<br>".$arrayDeptName[$row["nDeptId"]];
													}
													else {
															echo htmlentities($row["vTitle"])."<br>".$arrayDeptName[$row["nDeptId"]];
													}
													 ?>
                                    </td>
                                    <td align="left" valign="top"><?php echo htmlentities($row["vStatus"]); ?> </td>
                                    <td align="left" valign="top"><?php echo date("m-d-Y",strtotime($row["dPostDate"])); ?> </td>
                                  </tr>
                                  <?php
$count++;
}
mysql_free_result($rs);
?>
                                  <input type="hidden" name="mt" value="y">
                                  <input type="hidden" name="tk" value="<?php echo $var_ticketid; ?>">
                                  <input type="hidden" name="us" value="<?php echo $var_userid; ?>">
                                  <INPUT type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
                                  <INPUT type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                                  <INPUT type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
                                  <input type="hidden" name="postback" value="">
                                  <input type="hidden" name="id" value="">
                                </table></td>
                            </tr>
                          </table>
        
  
  
  
  	  <!-- Properities -->
<div class="comm_spacediv"><!-- Horizontal spacer  --></div>
	  
<div class="exp_title">
<div class="left"><h4><?php echo TEXT_PROPERTIES ?></h4></div>
<div class="exp_title_icon right"><a href="javascript:manageDetails('propertymatter','propertyimage')"><img id="propertyimage" src="../images/arrow_down.png" border="0"></a></div>       
<div class="clear"></div>
</div> 
	  
	  
	 <?php  if($var_message){ ?>
	<div <?php echo $flag_msg; ?>>  <?php echo $var_message; ?></div>
	  <?php } ?>
        
           
                     
								
								
								<table width="100%" border="0" cellspacing="0" cellpadding="0" id="propertymatter" style="display:none;">
                                    <tr>
                                      <td  width="100%">									  									  
									  
									  <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="list_tbl"  >
                                          
                                          <tr align="left">
                                            <th width="5%"><?php echo(TEXT_ID); ?></th>
                                            <th width="15%" align="left"><?php echo(TEXT_OWNER); ?></th>
                                            <th width="15%" align="left"><?php echo(TEXT_DEPARTMENT); ?></th>
                                            <th width="10%" align="left"><?php echo(TXT_PRIORITY); ?></th>
                                            <th width="10%" align="left"><?php echo(TEXT_STATUS); ?></th>
                                            <th width="10%" align="left"><?php echo(TEXT_LOCK); ?></th>
                                          </tr>
                                          <tr align="left">
                                            <td height="27"><?php echo($var_ticketid); ?> </td>
                                            <td height="27"><select name="cmbOwner" class="comm_input input_width4">
                                              <option value="0">--No Owner--</option>
                                              <?php
                                                                                                                     //$sql = "Select nStaffId,vLogin from sptbl_staffs where vDelStatus='0' ";
                                                                                                                                $sql = "Select distinct s.nStaffId,s.vLogin from sptbl_tickets t inner join sptbl_depts d
                                                                                                                                                on t.nDeptId = d.nDeptId inner join sptbl_depts d1 on
                                                                                                                                                d.nCompId = d1.nCompId inner join sptbl_staffdept sd on
                                                                                                                                                d1.nDeptId = sd.nDeptId inner join sptbl_staffs s on
                                                                                                                                                sd.nStaffId = s.nStaffId where t.nTicketId='" . addslashes($var_ticketid) . "' Order by vLogin";
                                                                                                                                $rs = executeSelect($sql,$conn);
                                                                                                                                if (mysql_num_rows($rs) > 0) {
                                                                                                                                        while($row = mysql_fetch_array($rs)) {
                                                                                                                                         echo("<option value=\"" . $row["nStaffId"] . "\">" . htmlentities($row["vLogin"]) . "</option>");
                                                                                                                                        }
                                                                                                                                }
                                                                                                                                mysql_free_result($rs);
                                                                                                                                  ?>
                                            </select></td>
                                            <td height="27"><select name="cmbDepartment" class="comm_input input_width4">
                                              <option value="">--Select Department--</option>
                                              <?php
                                                $leafdeptarr=getLeafDepts();
                                                 if($leafdeptarr !=""){
                                                                         $leaflvldeptids=implode(",",$leafdeptarr);

                                                                 }else{
                                                                   $leaflvldeptids=0;
                                                                 }
                                                    $sql = "Select distinct d1.nDeptId,d1.vDeptDesc from sptbl_tickets t inner join sptbl_depts d
                                                    on t.nDeptId = d.nDeptId inner join sptbl_depts d1 on
                                                    d.nCompId = d1.nCompId  where d1.nDeptId IN($leaflvldeptids)  Order by vDeptDesc";
                                                    $rs = executeSelect($sql,$conn);
                                                    if (mysql_num_rows($rs) > 0) {
                                                    while($row = mysql_fetch_array($rs)) {
                                                    echo("<option value=\"" . $row["nDeptId"] . "\">" . htmlentities($row["vDeptDesc"]) . "</option>");
                                                    }
                                                    }
                                                    mysql_free_result($rs);
                                                    ?>
                                            </select></td>
                                            <td   height="27">
                                                    <?php
                                                    $sql = "select nPriorityValue ,vPriorityDesc  from sptbl_priorities order by nPriorityValue";
                                                    $rsp = executeSelect($sql,$conn);

                                                    $sql_prio = "select vPriority  from  sptbl_tickets Where nTicketId = '".$var_ticketid."'";
                                                    $rsPrio = executeSelect($sql_prio,$conn);
                                                    $rowPrio = mysql_fetch_array($rsPrio);

                                                    ?>
                                                    <select name="cmbPriority" size="1" class="comm_input" id="cmbPriority">
                                                        <option value="">--Select Priority--</option>
                                                        <?php

                                                        while($rowp = mysql_fetch_array($rsp)) {
                                                           $options ="<option value='".$rowp['nPriorityValue']."'";
                                                           if ($rowPrio['vPriority'] == $rowp['nPriorityValue']) {

                                                               $options .=" selected=\"selected\"";
                                                         }
                                                          $options .=">".$rowp['vPriorityDesc']."</option>\n";
                                                          echo $options;
                                                        }
                                                       mysql_free_result($rsp) ;

                                                        ?>

                                                            </select></td>
                                            <td height="27"><select name="cmbStatus" class="comm_input input_width4">
                                              <option value="open">open</option>
                                              <option value="closed">closed</option>
                                              <option value="escalated">escalated</option>
                                              <?php
                                                    $sql = "Select vLookUpValue from sptbl_lookup where vLookUpName='ExtraStatus' ";
                                                    $rs = executeSelect($sql,$conn);
                                                    if (mysql_num_rows($rs) > 0) {
                                                            while($row = mysql_fetch_array($rs)) {
                                                             echo("<option value=\"" . $row["vLookUpValue"] . "\">" . htmlentities($row["vLookUpValue"]) . "</option>");
                                                            }
                                                    }
                                                    mysql_free_result($rs);
                                            ?>
                                            </select></td>
                                            <td height="27"><select name="cmbLock" class="comm_input input_width4">
                                              <option value="0">Not Locked</option>
                                              <option value="1">Locked</option>
                                            </select></td>
                                          </tr>
                                          <tr align="left">
                                            <th width="5%"></th>
                                            <th width="15%" align="left"><?php echo(TEXT_CREATED_ON); ?></th>
                                            <th width="15%" align="left"><?php echo(TEXT_LAST_UPDATE); ?></th>
                                            <th width="15%" align="left"><?php echo(TEXT_LAST_REPLIER); ?></th>
                                            <th width="10%" align="left"></th>
                                            <th width="10%" align="left"></th>
                                        </tr>
                                        <tr align="left">
                                            <td   height="27"></td>
                                            <td  height="27"><input type="text" name="txtCreated" id="txtCreated" value="" class="comm_input"  size="17" readonly="true"></td>
                                            <td   height="27"><input type="text" name="txtUpdate" id="txtUpdate" value="" class="comm_input" size="17"   readonly="true"></td>
                                            <td   height="27"><input type="text" name="txtReplier" id="txtReplier" value="" class="comm_input" size="17" readonly="true"   ></td>
                                            <td  height="27"></td>
                                            <td   height="27"></td>
                                        </tr>
                                          <tr align="left"  class="listingmaintext">
                                            <td colspan="6" align="center"><input type="button" class="comm_btn" name="btUpdate" id="btUpdate" value="<?php echo(BUTTON_TEXT_UPDATE); ?>" onClick="javascript:clickUpdate();">
&nbsp; </td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                  </table>
              
				
<div class="comm_spacediv"><!-- Horizontal spacer  --></div>
				
     
        <!-- End of Properities -->
  <!-- End Of History Section -->
  <!-- TWO PART Section -->
  <table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>      
      <td valign="top" class="exp_title_v_column" width="33%">
<?php
$sql = "Select * from sptbl_personalnotes Where nTicketId='" . addslashes($var_ticketid) ."'  Order By dDate DESC ";
$rs = executeSelect($sql,$conn); 
if(mysql_num_rows($rs)>0)
	$var_note_count = "(".mysql_num_rows($rs).")";
else	
	$var_note_count = "";
?>	  
	  <!--  Personal Notes -->
       
	<div class="exp_title">
<div class="left"><h4><?php echo TEXT_PERSONAL. "&nbsp;$var_note_count";?></h4></div>
<div class="exp_title_icon right"><a href="javascript:manageDetails('personalmatter','personalimage')"><img id="personalimage" src="../images/arrow_down.png" border="0"></a></div>       
<div class="clear"></div>
</div> 
	     

								
								<table width="100%" border="0" cellspacing="0" cellpadding="0" class="whitebasic" id="personalmatter" style="display:none;">
                                    <tr>
                                      <td class="whitebasic" width="100%">
									  
									  <table width="100%"  border="0" cellpadding="0" cellspacing="0"  >
                                          
                                          <tr>
                                            <td colspan="3" width="100%" height="37" align="center">
											
											
												  
												  
												<div class="overflow_div" id="sbspan" >
                                               <table width="100%" border="0" cellpadding="0" cellspacing="0" class="list_tbl" align="center">
												<tr align="left">
												<th width="30%" align="left" valign="top"><?php echo(TEXT_STAFF); ?> </th>
												<th width="45%" align="left" valign="top"><?php echo(TEXT_TITLE); ?> </th>
												<th width="25%" align="left" valign="top"><?php echo(TEXT_DATE); ?> </th>
											  </tr>
                                                        <?php
//***********************HISTORY SECTION**************************
// $sql = "Select * from sptbl_personalnotes Where nTicketId='" . addslashes($var_ticketid) ."'  Order By dDate DESC ";
// $rs = executeSelect($sql,$conn);
while($row = mysql_fetch_array($rs)) {
?>
                                                        <tr align="left" class="listingmaintext">
                                                          <td width="30%" valign="top"><?php
                                                                                                                                                if($row["nStaffId"] == $_SESSION["sess_staffid"]){
                                                                                                                                                        $viewurl = "editnote.php";
                                                                                                                                                }else{
                                                                                                                                                        $viewurl = "viewnote.php";
                                                                                                                                                }
                                                                                                                                                ?>
                                                            <a href="<?php echo   $viewurl?>?mt=y&tk=<?php echo($var_ticketid); ?>&us=<?php echo($var_userid); ?>&staffid=<?php echo($var_staffid); ?>&id=<?php echo($row["nPNId"]); ?>&stylename=<?php echo($var_stylename); ?>&styleminus=<?php echo($var_styleminus); ?>&styleplus=<?php echo($var_styleplus); ?>&" class="listing"><?php echo htmlentities($row["vStaffLogin"]); ?></a>
                                                            <!--<a href="viewpersonal.php?mt=y&id=<?php echo($row["nPNId"]); ?>&"><?php echo $row["vStaffLogin"]; ?></a>-->
                                                          </td>
                                                          <td width="45%" valign="top"  style="word-break:break-all;"><?php echo htmlentities($row["vPNTitle"]); ?> </td>
                                                          <td width="25%" valign="top"><?php echo date("m-d-Y",strtotime($row["dDate"])); ?> </td>
                                                        </tr>
                                                        
                                                        <?php

}
mysql_free_result($rs);
?>
                                                      </table>
                                                 </div>
													
													
											  
											  
											  </td>
                                          </tr>
                                          <tr align="left"  class="listingmaintext">
                                            <td colspan="2" align="center"><a href="editnote.php?mt=y&tk=<?php echo($var_ticketid); ?>&us=<?php echo($var_userid); ?>&stylename=<?php echo($var_stylename); ?>&styleminus=<?php echo($var_styleminus); ?>&styleplus=<?php echo($var_styleplus); ?>&" class="listing"><?php echo TEXT_ADD_NOTE ?></a></td>
                                            <td>&nbsp;</td>
                                          </tr>
                                        </table>
										
										
										
										
										</td>
                                    </tr>
                                  </table>
   
		
		
		
		
        <!-- End Of Personal Notes -->
		</td>
		
		
		
		<td valign="top" class="exp_title_v_column" width="33%">
        <!-- User Feedback -->
<?php
	$sql = "Select * from sptbl_feedback Where nTicketId='" . addslashes($var_ticketid) ."'  Order By dDate DESC";
	$rs = executeSelect($sql,$conn);
	if(mysql_num_rows($rs)>0)
		$var_feedback_count = "(".mysql_num_rows($rs).")";
	else
		$var_feedback_count = ""; 
?>
        
	
<div class="exp_title">
<div class="left"><h4><?php echo HEADING_VIEW_FEEDBACK."&nbsp;$var_feedback_count"; ?></h4></div>
<div class="exp_title_icon right"><a href="javascript:manageDetails('feedbackmatter','feedbackimage')"><img id="feedbackimage" src="../images/arrow_down.png" border="0"></a></div>       
<div class="clear"></div>
</div> 
		
		
         
               
                      <table width="100%"  border="0"  id="feedbackmatter" style="display:none;"  cellspacing="0" cellpadding="0">
                              <tr>
                                <td align="right">
								
								
								<table width="100%"  border="0" cellpadding="0" cellspacing="3"  >
                                          
                                         
                                          <tr>
                                            <td colspan="2" width="100%" height="37">
											
											<table border="0" width="100%" height="80px">
                                                <tr>
                                                  <td width="100%">
												  <div class="overflow_div" id="sbspan">
                                                    <table border="0" width="100%" cellpadding="0" cellspacing="0" class="list_tbl">
													
													<tr   class="listingmaintext">
                                            <th width="75%"><?php echo(TEXT_TITLE); ?> </th>
                                            <th width="25%"><?php echo(TEXT_DATE); ?> </th>
                                          </tr>
													
													 
                                                      <?php
//***********************HISTORY SECTION**************************
// $sql = "Select * from sptbl_feedback Where nTicketId='" . addslashes($var_ticketid) ."'  Order By dDate DESC";
// $rs = executeSelect($sql,$conn);
$viewurl = "viewfeedback.php";
while($row = mysql_fetch_array($rs)) {
?>
                                                      <tr align="left">
                                                        <td width="75%" valign="top" align="left"><a href="<?php echo   $viewurl?>?mt=y&tk=<?php echo($var_ticketid); ?>&us=<?php echo($var_userid); ?>&id=<?php echo($row["nFBId"]); ?>&stylename=<?php echo($var_stylename); ?>&styleminus=<?php echo($var_styleminus); ?>&styleplus=<?php echo($var_styleplus); ?>&"><?php echo htmlentities($row["vFBTitle"]); ?></a> </td>
                                                        <td width="25%" valign="top" align="left"><?php echo date("m-d-Y",strtotime($row["dDate"])); ?> </td>
                                                      </tr>
                                                      
                                                      <?php

}
mysql_free_result($rs);
?>
                                                    </table>
                                                    </div> </td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                        </table>
								  
								  </td>
                              </tr>
                            </table>
		
		
        <!-- End of user feedback  -->
		</td>
		
		<td valign="top" width="33%">
        <!-- Foreward -->
        
		
<div class="exp_title">
<div class="left"><h4><?php echo HEADING_VIEW_FORWARD ?></h4></div>
<div class="exp_title_icon right"><a href="javascript:manageDetails('forewardmatter','forewardimage')"><img id="forewardimage" src="../images/arrow_down.png" border="0"></a></div>       
<div class="clear"></div>
</div> 
		
		
		
		
        
                      <table width="100%" id="forewardmatter" style="display:none;"  border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td align="right">
								
								
								<table border="0" width="100%" height="120px" cellpadding="0" cellspacing="0">
                                                <tr>
                                                  <td width="100%">
												<div <?php echo $flag_msg; ?>>  <?php echo $var_message_forwarded; ?> </div>
												  <div class="overflow_div" id="sbspan">
                                                      <table width="100%" border="0" cellpadding="0" cellspacing="0" border="0" class="list_tbl">
                                                       
                                                        <tr align="left"  >
                                                          <td width="28%" ><?php echo TEXT_TO1 ?></td>
                                                          <td width="72%" valign="top"><input type="text" name="txtForward" class="comm_input input_width3" value="<?php echo $var_forward_email; ?>" class="textbox" size="38">
                                                          </td>
                                                        </tr>
                                                        <tr align="left"  >
                                                          <td width="28%" ><?php echo TEXT_CC1 ?></td>
                                                          <td width="72%" ><input type="text" name="txtForwardCC" class="comm_input input_width3" value="<?php echo $var_forward_email_cc; ?>" class="textbox" size="38">
                                                          </td>
                                                        </tr>
                                                        <tr>
                                                          <td  align="left" valign="top"><?php echo TEXT_NOTES1 ?></td>
                                                          <td align="left"><textarea name="txtComments" cols="25" rows="2" class="textarea" class="comm_input input_width3"><?php echo $var_forward_comments; ?></textarea>
                                                          </td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="2" align="center"><input type="button" class="comm_btn" name="btForward" id="btForward" value="<?php echo(BUTTON_TEXT_FORWARD); ?>" onClick="javascript:clickForward();">
                                                          </td>
                                                        </tr>
                                                      </table>
                                                    </div>
													
													</td>
                                                </tr>
                                              </table>
											  
										
								  
								  
								  </td>
                              </tr>
                            </table>
		
		
		
		
        <!-- end of foreward -->
      </td>
    </tr>
  </table>
  
  
  <div class="comm_spacediv"><!-- Horizontal spacer  --></div>
  
  
  <!-- End Of TWO PART Section -->


	 
       
<?php 
	  $sqlRefNo = "Select vRefNo from sptbl_tickets where nTicketId= ' " . addslashes($var_ticketid). "'";
	  $rsRefNo = executeSelect($sqlRefNo,$conn);
		if (mysql_num_rows($rsRefNo) > 0) {
			while ($rowRefNo = mysql_fetch_array($rsRefNo))
				$varrefno = $rowRefNo['vRefNo'];
		}
?>
		
		<div class="content_section_title"><h3><?php echo TEXT_TICKET_DETAIL." (".$varrefno.")"; ?></h3></div>
		
		<div class="comm_spacediv">&nbsp;</div>
		
			  
			  
			  
            
	  	  		 <div style="overflow:auto">
				 <!-- Updated ticket/correspondance display section -->
                          <?php
$showflag = false;
$var_maxposts = (int)$_SESSION["sess_maxpostperpage"];
$var_maxposts = ($var_maxposts < 1)?1:$var_maxposts;
$sql = "Select * from sptbl_tickets where nTicketId='" . addslashes($var_ticketid) . "' AND nUserId = '" . addslashes($var_userid)  . "'";
$rs = executeSelect($sql,$conn);
if(mysql_num_rows($rs) > 0) { //if main
	$row = mysql_fetch_array($rs);
	$var_userdetailid=$row["nUserId"];
	$var_username = $row["vUserName"];
	$var_deptid = $row["nDeptId"];
	//$var_department = $row["vDeptDesc"];
	//$var_owner_name = $row["vStaffLogin"];
	$var_owner_id = $row["nOwner"];
	$var_created_on = $row["dPostDate"];
	$var_status = $row["vStatus"];
	$var_lock = $row["nLockStatus"];
	$var_last_update = date("m-d-Y H:i",strtotime($row["dLastAttempted"]));
	$var_last_replier = $row["vStaffLogin"];

	//This section decides to show the reply , quote reply for the ticket for staff
	$arr_dept = explode(",",$lst_dept);
	 for($j=0;$j < count($arr_dept);$j++) {
			 if ($var_deptid == $arr_dept[$j]) {
					$showflag = true;
					break;
			}
	 }
	 if ($row["nLockStatus"] == "1" && $row["nOwner"] != $var_staffid) {
			 $showflag = false;
	 }
	//End section

/*	if($row["vStatus"] != "closed") {
		$var_close_url = "<a href=\"viewticket.php?mt=y&cl=y&tk=" . $var_ticketid . "&stylename=" . $var_stylename . "&styleminus=" . $var_styleminus . "&styleplus=" . $var_styleplus . "&\" class=\"linktext\">" . TEXT_CLOSE_TICKET . "</a>";
	}*/

	$sql = "Select t.nTicketId,t.vTitle,t.tQuestion,t.dPostDate,t.vMachineIP,
			r.nReplyId,r.nStaffId,r.nUserId,r.vStaffLogin,r.dDate,r.tReply,r.tPvtMessage,r.vMachineIP as 'ReplyIp',r.nHold,t.vViewers
			 from dummy d
			Left join sptbl_tickets t on (d.num=0 AND t.nTicketId='" . addslashes($var_ticketid)  . "'
			 AND t.nUserId='" . addslashes($var_userid) . "')
			Left JOIN sptbl_replies r on (d.num=1 AND r.nTicketId='" . addslashes($var_ticketid) . "')
			where d.num < 2  AND (t.nTicketId IS NOT NULL OR r.nReplyId IS NOT NULL) order by r.dDate ";

	if($_SESSION["sess_messageorder"] == "1") {
		$sql .= " ASC";
	}
	else {
		$sql .= " DESC";
	}
	//echo($sql);
	$totalrows = mysql_num_rows(executeSelect($sql,$conn));
	settype($totalrows,integer);
	settype($var_begin,integer);
	settype($var_num,integer);
	settype($var_numBegin,integer);
	settype($var_start,integer);

	$var_calc_begin = ($var_begin == 0)?$var_start:$var_begin;
	 if(($totalrows <= $var_calc_begin)) {
			 $var_nor = $var_maxposts;   //presently assuming nor is number of rows
			$var_nol = 10;	  //presently assuming nol is number of links
			 if($var_num > $var_numBegin) {
					$var_num = $var_num - 1;
					$var_numBegin = $var_numBegin;
					$var_begin = $var_begin - $var_nor;
			}
			elseif($var_num == $var_numBegin) {
					$var_num = $var_num - 1;
					$var_numBegin = $var_numBegin - $var_nol;
					$var_begin = $var_calc_begin - $var_nor;
					$var_start="";
			}
	 }

	//echo("$totalrows,2,2,\"&ddlSearchType=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&id=$var_batchid&\",$var_numBegin,$var_start,$var_begin,$var_num");
	$navigate = pageBrowser($totalrows,10,$var_maxposts,"&mt=y&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus&tk=" . $var_ticketid . "&us=" . $var_userid . "&",$var_numBegin,$var_start,$var_begin,$var_num);

	//execute the new query with the appended SQL bit returned by the function
	$sql = $sql.$navigate[0];


	$rs = executeSelect($sql,$conn);

	$var_reply_idlist = "";
        $i=0;
	while($row = mysql_fetch_array($rs)) {
		if($row["nReplyId"] != "") {
			$var_reply_idlist .= "," . $row["nReplyId"];
		}

                /*
                 *  Update Viewed Tickets
                 */
                if($i == 0){

                    if($row["vViewers"] != "") {

                        $Viewersarray = explode(',',$row["vViewers"]);
                        if(!in_array($var_staffid, $Viewersarray)){

                           $newViewersList = $row["vViewers"].','.$var_staffid;
                           $sql_updateView = "Update sptbl_tickets Set vViewers = '".$newViewersList."' Where nTicketId='" . addslashes($var_ticketid)  . "'";
                           executeQuery($sql_updateView,$conn);

                        }//end if

                    }else{

                        $sql_updateView = "Update sptbl_tickets Set vViewers = '".addslashes($var_staffid)."' Where nTicketId='" . addslashes($var_ticketid)  . "'";
                        executeQuery($sql_updateView,$conn);

                    }//end else

                }//end if

                $i++;

	}
	if(mysql_num_rows($rs) > 0) {
		mysql_data_seek($rs,0);
		if($var_reply_idlist != "") {
			$var_subquery = " OR  nReplyId IN(" . substr($var_reply_idlist,1) . ")";
		}

		$sql_attach = "Select * from sptbl_attachments where nTicketId='" . addslashes($var_ticketid)  . "'
					" . $var_subquery . " ORDER BY nTicketId DESC,nReplyId DESC";
		$rs_attach = executeSelect($sql_attach,$conn);

		while($row = mysql_fetch_array($rs)) {
			if($row["nTicketId"] != "") {  //Ticket section

 ?>
 
 					<div class="ticket_conv_user">
 					<div class="content_section_data">
					
					<div class="clear btm_brdr">
						<div class="left ticket_user_info">
							<table cellpadding="0" cellspacing="0" border="0" class="comm_tbl2" width="100%">
							 <tr align="left">
                             <td  width="16%" style="word-break:break-all; "><?php echo TEXT_USER;?></td>
							 <td><?php echo " :<a href=\"javascript:userdetails('$var_userdetailid')\" > <b>" . htmlentities(stripslashes($var_username))."</a></b>"; ?> </td>
                             </tr>
							 <tr>
							  <td><?php echo TEXT_DATE ?></td>
							  <td><?php echo  " :&nbsp;<b><span>" . date("m-d-Y H:i",strtotime($row["dPostDate"]))."</b></span>"; ?> </td>
							 </tr>
							 <tr>
                              <td ><?php  echo TEXT_IP ?></td>
							  <td><?php echo " :&nbsp;<b><span>" . $row["vMachineIP"]."</b></span>"; ?>
                               
                              </td>
                              
                            </tr>
							</table>
						
						</div>
						<div class="right">
						<?php
									if ($showflag == true) { 
							?>
                                    <a class="comm_link1" href="replies.php?rp=r&tk=<?php echo($var_ticketid); ?>&rid=0&stylename=<?php echo($var_stylename); ?>&styleminus=<?php echo($var_styleminus); ?>&styleplus=<?php echo($var_styleplus); ?>&"  ><?php echo(TEXT_REPLY); ?></a>
									<a class="comm_link1" href="replies.php?rp=q&tk=<?php echo($var_ticketid); ?>&rid=0&stylename=<?php echo($var_stylename); ?>&styleminus=<?php echo($var_styleminus); ?>&styleplus=<?php echo($var_styleplus); ?>&" ><?php echo(TEXT_QUOTE_REPLY); ?></a>
									<a class="comm_link1" href="javascript:deleteTickets('<?php echo($var_ticketid); ?>');" ><?php echo TEXT_MAIN_DELETE1 ?></a>
                                    <?php
							 }
							?>
						
						</div>
					<div class="clear"></div>
					</div>
					
                          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="comm_tbl2">

                           <tr>
                             <td style="word-break:break-all; " width="15%" align="left" valign="top"><?php echo TEXT_TITLE;?></td>
                             <td align="left" valign="top">:&nbsp;<b><?php echo nl2br(stripslashes($row["vTitle"])); ?></b></td>
                           </tr>

                            <tr>
                                <td valign="top"><?php echo TICKET_DESCRIPTION;?></td>
				<td align="left" valign="top" class="leftpadding">:&nbsp;<?php echo nl2br(stripslashes($row["tQuestion"])); ?></td>						  
                            </tr>

                            <tr>
                              <td colspan="2">							 
					<!--<div class="content_sub_box">-->									
					<?php 	 echo(getAttachment($var_ticketid,""));   ?>									
					<!-- </div>-->																	
                              </td>
                            </tr>
                            
                          </table>
						  
						  </div>
						  </div>
						 
						  
						  
                          <?php
		} //end if ticket section
		else
                { //else correspondance section
		
		 if($row["nStaffId"] != "")
                 {
		     $var_style      = "ticket_conv_staff";
		     $var_styletitle = "comm_tbl2";
		 }
		 elseif ($row["nUserId"] != "")
                 {
		     $var_style      = "ticket_conv_user";
		     $var_styletitle = "comm_tbl2";
		 }
?>
					<div class="<?php echo $var_style;?>">
 					<div class="content_section_data">
					
					<div class="clear btm_brdr">
						<div class="left ticket_user_info">
						 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="comm_tbl2">
						  <?php
						 if ($row["nStaffId"] != "") {
						  $var_style = "replyband";
						 ?>
                          <tr>
                              <td  width="16%" style="word-break:break-all; "><?php echo TEXT_STAFF;?></td>
			      <td><?php echo  " :<b><span> " .  htmlentities($row["vStaffLogin"]) ."</span></b>";?></td>
			  </tr>
							<?php
					}
					elseif ($row["nUserId"] != "") {
					
							$var_style = "ticketband";
							?>
							<tr>
                              <td style="word-break:break-all; "><?php echo TEXT_USER;?></td>
							  <td><?php echo  " : <b><span>" .  htmlentities($var_username) ."</span></b>";?></td>
						</tr>
							<?php
						}
				?>
						<tr>
                              <td style="word-break:break-all; "><?php echo TEXT_DATE ;?></td>
							  <td><?php echo  " :<b><span> " .  date("m-d-Y H:i",strtotime($row["dDate"])) ."</span></b>";?></td>
						</tr>
						<tr>
                              <td style="word-break:break-all; "><?php echo TEXT_IP ."</span></b>" ;?></td>
							  <td><?php echo  " : <b><span>" .  $row["ReplyIp"];?></td>
						</tr>
                      </table>
						
						</div>
						<div class="right">
						<?php if($row['nHold']==1){  ?>
        <a href="replies.php?rp=r&tk=<?php echo($var_ticketid); ?>&rid=0&stylename=<?php echo($var_stylename); ?>&styleminus=<?php echo($var_styleminus); ?>&styleplus=<?php echo($var_styleplus); ?>&nHold=<?php echo $row['nHold']?>&nReplyId=<?php echo $row['nReplyId'];?>" class="comm_link1"><?php echo 'Post Hold'; ?></a>
<?php } ?>
        <?php
							if ($showflag == true && $row['nHold']==0) { 
					?>
                                    <a href="replies.php?rp=r&tk=<?php echo($var_ticketid); ?>&rid=<?php echo($row["nReplyId"]); ?>&stylename=<?php echo($var_stylename); ?>&styleminus=<?php echo($var_styleminus); ?>&styleplus=<?php echo($var_styleplus); ?>&"  class="comm_link1"><?php echo(TEXT_REPLY); ?></a>
									<a href="replies.php?rp=q&tk=<?php echo($var_ticketid); ?>&rid=<?php echo($row["nReplyId"]); ?>&stylename=<?php echo($var_stylename); ?>&styleminus=<?php echo($var_styleminus); ?>&styleplus=<?php echo($var_styleplus); ?>&"  class="comm_link1"><?php echo(TEXT_QUOTE_REPLY); ?></a>
                    <?php
					 } 
					 
					 ?>
						</div>
						
						 <div class="clear"></div>
						  </div>
					
                        <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="comm_tbl2">
                                  <tr align="left">
                                    <td colspan="4" width="10%" style="word-break:break-all;"><?php  echo nl2br(stripslashes($row["tReply"]));  ?> </td>
                                  </tr>
                                  
                                  <?php
/*
	if ($var_staffid == $row["nStaffId"] && trim($row["tPvtMessage"]) != "") {
?>
                                  <tr >
                                    <td colspan=4 class="commentband" align="left"><?php echo   TEXT_COMMENTS?></td>
                                  </tr>
                                  <tr>
                                    <td colspan=4 align="left"><?php echo nl2br(htmlentities($row["tPvtMessage"])); ?></td>
                                  </tr>
                                  <?php

	 }
*/
?>
                                  <?php
		if ($var_staffid == $row["nStaffId"] && trim($row["tPvtMessage"]) != "") {
?>
                                  <tr>
                                    <td colspan=4 class="listing" align="left">Comments</td>
                                  </tr>
                                  <tr>
                                    <td colspan=4 align="left" style="word-break:break-all;" class="listing"><?php echo nl2br(stripslashes($row["tPvtMessage"])); ?></td>
                                  </tr>
                                  <?php

		 }
?>
                                 
                                </table></td>
                            </tr>
                            <?php
//if ($row["vAttachUrl"] != "") {
?>
                            <tr>
                              <td colspan="4"><table width="100%"  border="0" cellpadding="0" cellspacing="0" class="content_sub_box">
                                  <tr align="center">
                                    <td colspan="4"><?php
							 echo(getAttachment("",$row["nReplyId"]));
					  ?></td>
                                  </tr>
                                </table>
                            <?php
/*}
elseif ($row = mysql_fetch_array($rs)) {
	$flag_main = true;
}*/
?>
                         
						 	</div>
						  </div>
						  
                          <?php
		} //end else correspondance section
	  }
	}
}//end if main
//link display
//echo($navigate[2]);

function getAttachment($var_ticketid="",$var_replyid="") {
	global $rs_attach;
	$var_return = "";
	$flag = false;
	if(mysql_num_rows($rs_attach) > 0) {
		mysql_data_seek($rs_attach,0);
		if($var_ticketid != "") {
		//$var_return  = "<div class='content_sub_box'>";
			while($row = mysql_fetch_array($rs_attach)) {
				if($row["nTicketId"] != "") {
					if($row["nTicketId"] == $var_ticketid) {
						$var_return .= "," . " <a href=\"javascript:var lg=window.open('../attachments/" . addslashes($row["vAttachUrl"]) . "');\"  class=\"attachband\">". htmlentities($row["vAttachReference"]) . "</a>";
						$flag = true;
					}
					elseif($flag == true) {
						break;
					}
				}
				else {
					break;
				}
			}
		}
		elseif($var_replyid != "") {
			while($row = mysql_fetch_array($rs_attach)) {
				if($row["nReplyId"] == $var_replyid) {
					$var_return .= "," . " <a href=\"javascript:var lg=window.open('../attachments/" . addslashes($row["vAttachUrl"]) . "');\"  class=\"attachband\">". htmlentities($row["vAttachReference"]) . "</a>";
					$flag = true;
				}
				elseif($flag == true) {
					break;
				}
			}
		}
		//$var_return .= "</div>" ;
	}
	else {
		return "";
	}
	return (($var_return != "")?TEXT_ATTACHMENTS . " : " . substr($var_return,1):"");
}
?>
                          <!-- END OF Updated ticket/correspondance display section -->
                   
				   
				   
				       
					</div>
					
		
  

 <div class="comm_spacediv">&nbsp;</div>
  
<div class="content_section">
<div class="content_section_data" align="center">
		  <?php

			  if($_GET['stat'] == "new")
				  $sess_next_sql = $_SESSION['next_sql_new'];
			  else	  
				  $sess_next_sql = $_SESSION['next_sql'];
		  
			  if($var_next_limitvalue>0){
				  $var_previous_limitvalue = $var_next_limitvalue-1;

				  $sqlprevious = $sess_next_sql ." LIMIT ".$var_previous_limitvalue." ,1";
			  	  $rsprevious = executeSelect($sqlprevious,$conn);
//  echo $sqlprevious
				  while($row = mysql_fetch_array($rsprevious)) {
					 $previousticketid =  $row['nTicketId'];
					 $previoususerid   =  $row['nUserId'];
				  }	 

				  if(mysql_num_rows($rsprevious)>0) {?>
				  		<input type="button" class="comm_btn" name="btPrevious" id="btPrevious" value="<?php echo(TEXT_MAIN_PREVIOUS);?>" onClick="javascript:clickPrevious();"></a>
				 <?php	}
			 }

                         $var_next_limitvalue = $var_next_limitvalue+1;

			   $sqlnext = $sess_next_sql." LIMIT ".$var_next_limitvalue." ,1";
//		  	  echo $sqlnext;
			  $rsnext = executeSelect($sqlnext,$conn);

			  while($row = mysql_fetch_array($rsnext)) {
				 $nextticketid =  $row['nTicketId'];
				 $nextuserid   =  $row['nUserId'];
			  }	 
				 ?>  
				&nbsp;&nbsp;&nbsp;
                        <?php
					if ($showflag == true) {
			  ?>
              <input type="button" class="comm_btn" name="btReply" id="btReply" value="<?php echo(TEXT_REPLY); ?>" onClick="javascript:clickReply();">
&nbsp;&nbsp;&nbsp;
              <input type="button" class="comm_btn" name="btDelete" id="btDelete" value="<?php echo(TEXT_MAIN_DELETE); ?>" onClick="javascript:deleteTickets('<?php echo($var_ticketid); ?>');">
              <?php
				  	}
			  ?>

&nbsp;&nbsp;&nbsp;
<?php 
 if(mysql_num_rows($rsnext)>0) {?>
			  		<input type="button" class="comm_btn" name="btNextReply" id="btNextReply" value="<?php echo(TEXT_REPLY_NEXT); ?>" onClick="javascript:clickReplyNext();">
			 <?php } ?>
       &nbsp;&nbsp;&nbsp;
              <input type="button" class="comm_btn" name="btBack" id="btBack" value="<?php echo(TEXT_MAIN_BACK); ?>" onClick="javascript:clickBack();">
&nbsp;&nbsp;&nbsp;
		  <?php
			  

			  if(mysql_num_rows($rsnext)>0) {?>
			  		<input type="button" class="comm_btn" name="btNext" id="btNext" value="<?php echo(TEXT_MAIN_NEXT);?>" onClick="javascript:clickNext();"></a>			  
			 <?php } ?>



</div>
</div>
  
  
  
  <input type="hidden" name="numBegin" value="<?php echo   $var_numBegin ?>">
  <input type="hidden" name="start" value="<?php echo   $var_start ?>">
  <input type="hidden" name="begin" value="<?php echo   $var_begin ?>">
  <input type="hidden" name="num" value="<?php echo   $var_num ?>">
  <script type="text/javascript">
        var own = '<?php echo($var_owner_id); ?>';
        var dept = '<?php echo($var_deptid); ?>';
        var ctd = '<?php echo ($var_created_on<>'')?date("m-d-Y H:i",strtotime($var_created_on)):''; ?>';
        var st = '<?php echo($var_status); ?>';
        var lstupdate = '<?php echo($var_last_update); ?>';
        var lstreplier = '<?php echo($var_last_replier); ?>';
        var lck = '<?php echo($var_lock); ?>';


        document.frmDetail.cmbOwner.value=own;
        document.frmDetail.cmbDepartment.value=dept;
        document.frmDetail.txtCreated.value=ctd;
        document.frmDetail.txtUpdate.value=lstupdate;
        document.frmDetail.txtReplier.value=lstreplier;
        document.frmDetail.cmbStatus.value=st;
        document.frmDetail.cmbLock.value=lck;

</script>
  <?php 
// added on 1-11-06 by roshith
	if ($_GET["tk"] != "") 
		$var_tid = $_REQUEST["tk"];

                    $backurl='viewticket.php?limitval=0&mt=y&tk='.$var_tid.'&us=2&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1';
	
	    //$backurl="newtickets.php?mt=y&tk=".$var_tid."&us=".$var_userid."&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&msg=replied";

            $_SESSION["sess_backurl_reply_success"] = $backurl;
?>
</form>
<?php
if($_GET['stat'] == "new") 
	$stat = "new";
else
	$stat = "";	
?>

<form name=frmReply  id="frmReply" action="<?php echo "replies.php?rp=r&tk=$var_ticketid&rid=0&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus&limitval=$var_next_limitvalue";?>" method=post >
</form>

<form name=frmPrevious action="<?php echo "viewticket.php?limitval=$var_previous_limitvalue&stat=$stat&mt=y&tk=$previousticketid&us=$previoususerid&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1"?>" method=post >
</form>

<form name=frmNext action="<?php echo "viewticket.php?limitval=$var_next_limitvalue&mt=y&tk=$nextticketid&us=$nextuserid&val=$var_orderby&sorttype=$var_sorttype&pagenum=yes&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1";?>" method=post >
</form>

<script language="javascript">
// added on 2-11-06 by roshith
function clickReply() // function called when clicking 'reply' button
{
	document.frmReply.submit();
//	window.location.href='<?php echo "replies.php?rp=r&tk=$var_ticketid&rid=0&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus";?>';
}
function clickBack()  // function called when clicking 'back' button
{
	window.location.href='<?php echo $_SESSION["sess_ticketbackurl"];?>';
}
function clickPrevious() // function called when clicking 'previous' button
{
	document.frmPrevious.submit();
}
function clickNext() // function called when clicking 'next' button
{
	document.frmNext.submit();
}

function manageDetails(row,arrimage){
	
	if(document.getElementById(row).style.display=="none"){
		
		document.getElementById(row).style.display="";
		document.getElementById(arrimage).src="../images/arrow_up.png";
	
	}else{
	
		document.getElementById(row).style.display="none";	
		document.getElementById(arrimage).src="../images/arrow_down.png";		
	
	}	

}
function clickReplyNext() // function called when clicking 'reply' button
{
    
    
    var action = $("#frmReply").attr('action')+"&next=1";
    $("#frmReply").attr('action',action);
     document.frmReply.submit();
//	window.location.href='<?php echo "replies.php?next=1&rp=r&tk=$var_ticketid&rid=0&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus";?>';
}
var mt='<?php echo   $_POST["mt"]?>';
if(mt=='u'){
	manageDetails('propertymatter','propertyimage');
}else if(mt=='f'){
	manageDetails('forewardmatter','forewardimage');
}
</script>
