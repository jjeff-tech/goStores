<?php
require_once("$dotdotreal/includes/decode.php");
include_once("$dotdotreal/config/settings.php");
include_once("$dotreal/includes/mimedecode.inc.php");
include_once("$dotreal/includes/RFC822.php");
include_once("$dotreal/languages/en/parser.php");
global $conn;
function getUserLogin($var_mailbox,$val,&$var_username,&$var_userlogin) {
	$var_mailbox = preg_replace("/[^a-z0-9]/i","",$var_mailbox);
	$var_mailbox = (strlen($var_mailbox) > 50)?(substr($var_mailbox,0,50)):$var_mailbox;
	$var_username = $var_mailbox;
	$sql = "Select nUserId from sptbl_users where vLogin='" . addslashes($var_mailbox) . "'";
	while(mysql_num_rows(mysql_query($sql)) > 0) {
		$var_mailbox = uniqid($var_mailbox);
		$sql = "Select nUserId from sptbl_users where vLogin='" . addslashes($var_mailbox) . "'";
	}
	$var_userlogin = $var_mailbox;
}
function getRegisterUserLogin($userid) {
    $sql = "Select vLogin  from sptbl_users where nUserId ='" . addslashes($userid) . "'";

	$exc=mysql_query($sql);
	$row=mysql_fetch_array($exc);

	$var_userlogin = $row['vLogin'];
	return $var_userlogin;

}

function getUserPassword($var_fromaddress) {
	return uniqid(false);
}

function enterReply($var_ticketid,$var_userid,$var_deptid,$var_refno,$var_mail_subject) {
	global $conn,$var_message_main,$var_machineip,$mimedecoder,$var_valid_size;
	$sql = "insert into sptbl_replies(nReplyId,nTicketId,nUserId,dDate,tReply,vMachineIP) Values('',
			'" . addslashes($var_ticketid) . "',
			'" . addslashes($var_userid) . "',
			now(),
			'" . addslashes($var_message_main) . "',
			'" . addslashes($var_machineip) . "')";
	executeQuery($sql,$conn);
	$var_replyid = mysql_insert_id();
	$sql = "update sptbl_tickets set vStatus='open',dLastAttempted=now()  where nTicketId='".addslashes($var_ticketid)."'";
	executeQuery($sql,$conn);

	$sql1 = "insert into sptbl_attachments(nReplyId,vAttachReference,vAttachUrl) values";
	$sql = "";
	foreach($mimedecoder->_attachments as $objattach) {
		if((validateAttachments($objattach->_attachmentname,$objattach->_attachmenttype) == true) && (getDataSize($objattach->_attachmentcontent) < $var_valid_size)) {
			$var_act_filename = uniqid("fl",true) . "." . getExtension($objattach->_attachmentname);
			$sql .=  ",('" . $var_replyid . "','" . $objattach->_attachmentname . "','" . addslashes($var_act_filename) . "')";
			$fp = fopen("$dotdotreal/attachments/" . $var_act_filename, "w");
			fwrite($fp, $objattach->_attachmentcontent);
			fclose($fp);
		}
	}
	($sql != "")?executeQuery($sql1 . substr($sql,1),$conn):"";
	mailAllStaff($var_deptid,$var_refno);
	acknowledgeUserOnReply($var_deptid,$var_refno,$var_mail_subject);
}

function mailUserOnTicketCreation($var_deptid,$total_count,$var_refno,$var_user_id,$var_subject) {
	global $arr_tolist,$var_fromaddress,$arr_lookupvalues,$arr_user_login,$arr_new_user;
	for($i=0;$i<$total_count;$i++) {
		if($arr_tolist[$i][0] == $var_deptid) {
			break;
		}
	}
	 $var_body = $arr_lookupvalues['var_emailheader'] ."<br>".TEXT_MAIL_START.",<br>&nbsp;<br>";
	 $var_body .= TEXT_TICKET_CREATION_BODY1 . " ( " . htmlentities($var_subject) . " ) " ;
	 $var_body .= TEXT_TICKET_CREATION_BODY2 . date("m-d-Y H:i");
	 $var_body .= TEXT_TICKET_CREATION_BODY3 . " [" . $var_refno ."].<br><br>";
	 $var_body .= "<a href=\"" . $arr_lookupvalues['var_loginurl'] . "?mt=y&email=" . urlencode($var_fromaddress) . "&ref=" . $var_refno . "&\">" . TEXT_CLICK_HERE . "</a> " . TEXT_VIEW_TICKET_STATUS . "<BR><BR>";
	 if(isset($arr_new_user[$var_user_id])) {
	 	$var_body .= TEXT_NEW_USER . "<br>&nbsp;<br>" . TEXT_LOGIN_URL . " : " . "" . $arr_lookupvalues['var_loginurl'];
		$var_body .= "<br>" . TEXT_LOGIN_NAME . " : " . $arr_user_login[$var_user_id] . "<br>";
		$var_body .= TEXT_PASSWORD . " : " . $arr_new_user[$var_user_id] . "<br>&nbsp;<br>";
	 }else{
	       $varlogin=getRegisterUserLogin($var_user_id);
	       $var_body .= TEXT_NEW_USER . "<br>&nbsp;<br>" . TEXT_LOGIN_URL . " : " . "" . $arr_lookupvalues['var_loginurl'];
		   $var_body .= "<br>" . TEXT_LOGIN_NAME . " : " . $varlogin . "<br>&nbsp;<br>";


	 }
	 //$var_body .= TEXT_MAIL_THANK."<br>" . getDepartmentMailFromId($var_deptid) . "<br>" . $arr_lookupvalues['var_emailfooter'];
	 $var_body .= TEXT_MAIL_THANK."<br>" . htmlentities($arr_lookupvalues['var_helpdesktitle']) . "<br>" . $arr_lookupvalues['var_emailfooter'];
	 $var_subject = TEXT_TICKET_CREATION_SUBJECT . $arr_lookupvalues['var_helpdesktitle'] . "  Id#[" . $var_refno . "]";
	 $Headers="From: " . $arr_tolist[$i][1] . "\n";
	 $Headers.="Reply-To: " . $arr_tolist[$i][1] . "\n";
	 $Headers.="MIME-Version: 1.0\n";
	 $Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	 @mail($var_fromaddress,$var_subject,$var_body,$Headers);




	// echo($var_body);
	// exit;
}

function acknowledgeUserOnReply($var_deptid,$var_refno,$var_mail_subject) {
	global $arr_tolist,$var_fromaddress,$arr_lookupvalues,$arr_user_login,$arr_new_user,$total_count;
	for($i=0;$i<$total_count;$i++) {
		if($arr_tolist[$i][0] == $var_deptid) {
			break;
		}
	}
	 $var_body = $arr_lookupvalues['var_emailheader'] ."<br>".TEXT_MAIL_START.",<br>&nbsp;<br>";
	 $var_body .= TEXT_TICKET_ACKNOWLEDGE_BODY1 . " ( " . htmlentities($var_mail_subject) . " ) " ;
	 $var_body .= TEXT_TICKET_ACKNOWLEDGE_BODY2 . date("m-d-Y H:i");
	 $var_body .= TEXT_TICKET_ACKNOWLEDGE_BODY3 . " [" . $var_refno ."].<br><br>";
	 $var_body .= "<a href=\"" . $arr_lookupvalues['var_loginurl'] . "?mt=y&email=" . urlencode($var_fromaddress) . "&ref=" . $var_refno . "&\">" . TEXT_CLICK_HERE . "</a> " . TEXT_VIEW_TICKET_STATUS . "<BR><BR>";
	 $var_body .= TEXT_TICKET_ACKNOWLEDGE_BODY4 . "<br><BR>";
	 $var_body .= TEXT_MAIL_THANK."<br>" . htmlentities($arr_lookupvalues['var_helpdesktitle']) . "<br>" . $arr_lookupvalues['var_emailfooter'];
	 $var_subject = TEXT_TICKET_ACKNOWLEDGE_SUBJECT . $arr_lookupvalues['var_helpdesktitle'];
	 $Headers="From: " . $arr_tolist[$i][1] . "\n";
	 $Headers.="Reply-To: " . $arr_tolist[$i][1] . "\n";
	 $Headers.="MIME-Version: 1.0\n";
	 $Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	 @mail($var_fromaddress,$var_subject,$var_body,$Headers);
}


//yet to be implemented that mail all staff on the ticket status change notification
function mailAllStaff($var_deptid,$var_refno) {
	global $arr_tolist,$var_fromaddress,$arr_lookupvalues,$arr_user_login,$arr_new_user,$conn;
	$sql = "Select s.vLogin,s.vMail,s.vSMSMail from sptbl_staffdept sd inner join sptbl_staffs s on
		sd.nStaffId = s.nStaffId where sd.nDeptId='" . $var_deptid . "' AND s.nNotifyArrival='1' and s.vDelStatus='0'";
	$result = executeSelect($sql,$conn);

	$var_tolist="";
	$var_smslist="";
	while($row = mysql_fetch_array($result)) {
		$var_tolist .= "," . $row["vMail"];
		$var_smslist .= "," . $row["vSMSMail"];
	}
	$var_tolist = ($var_tolist != "")?substr($var_tolist,1):"";
	$var_smslist = ($var_smslist != "")?substr($var_smslist,1):"";
	if($var_tolist != "") {
		/*for($i=0;$i<$total_count;$i++) {
			if($arr_tolist[$i][0] == $var_deptid) {
				break;
			}
		}*/
		 $var_body = $arr_lookupvalues['var_emailheader'] ."<br>".TEXT_MAIL_START.",<br>&nbsp;<br>";
		 $var_body .= "<br><br>";
		 $var_body .= TEXT_BEGIN_MAIL . date("m-d-Y H:i") . "<br>";
		 $var_body .= TEXT_TICKET_REFERENCE_NUMBER . " : " . $var_refno . "<br><br>";
		 $var_body .= TEXT_MAIL_THANK."<br>" . htmlentities($arr_lookupvalues['var_helpdesktitle']) . "<br>" . $arr_lookupvalues['var_emailfooter'];
		 $var_subject = "  [" . $var_refno . "]" . TEXT_TICKET_REPLY_SUBJECT . $arr_lookupvalues['var_helpdesktitle'];
		 $Headers="From: " . $arr_lookupvalues['var_fromMail'] . "\n";
		 $Headers.="Reply-To: " . $arr_lookupvalues['var_replyMail'] . "\n";
		 $Headers.="MIME-Version: 1.0\n";
		 $Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		 @mail($var_tolist,$var_subject,$var_body,$Headers);
	}
	if($var_smslist != "") {
		 $var_email=$var_smslist;
		 $var_mail_body="";
		 $var_mail_body=TEXT_MAIL_START.", ".
		 $var_mail_body .= " ";
		 $var_mail_body .= TEXT_TICKET_REFERENCE_NUMBER . " : " . $var_refno . "  " . TEXT_SMS_CONT  . " " . TEXT_MAIL_THANK . htmlentities($arr_lookupvalues['var_helpdesktitle']);
		 //$var_subject = $var_refno . " - " . TEXT_EMAIL_SUB;

		$var_body = $var_mail_body;
		$Headers="From: " . $arr_lookupvalues['var_fromMail'] . "\n";
		$Headers.="Reply-To: " . $arr_lookupvalues['var_replyMail'] . "\n";
		$Headers.="MIME-Version: 1.0\n";
		$Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$mailstatus=@mail($var_email,"",$var_body,$Headers);
	}
	return true;
}


function getTicketRefno($var_subject,&$var_ticketrefno) {
	//$var_subject = "testing from Ticket Id#[111-2111-3134411-3222]Ticket Id#[1-1-1-1]";
	preg_match_all("/ Id#\[[0-9]{0,}[0-9]{0,}[0-9]{0,}[0-9]{0,}\]/i",$var_subject,$array);
	$var_len = count($array[0]);
	if($var_len > 0) {
		$var_ticketrefno = $array[0][($var_len - 1)];
		//echo("<br>before:" . $var_ticketrefno . " and after : ");
		$var_ticketrefno = substr($var_ticketrefno,5,(strlen($var_ticketrefno) - 6));
		//echo($var_ticketrefno);
		return true;
	}
	else {
		$var_ticketrefno = "";
		return false;
	}
}
function getIpFromHeader($var_headerstring) {
	$var_headerstring = ($var_headerstring != "" && $var_headerstring != NULL)?$var_headerstring:"No value";
	preg_match("/from \[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\]/i",$var_headerstring,$array);
	return((count($array) > 0)?substr($array[0],6,(strlen($array[0]) - 7)):"");
}
function getCompanyId($deptid,$total_count) {
	global $arr_tolist;
	for($i = 0;$i < $total_count;$i++) {
		if($arr_tolist[$i][0] == $deptid) {
			return $arr_tolist[$i][3];
			break;
		}
	}
}


function getIndLeaf($arr_param,$total_count) {
	global $arr_tolist;
	$arr_result=array();
	$main_flag = false;
	foreach($arr_param as $key=>$value) {
		$flag = false;
		for($i=0;$i<$total_count;$i++) {
			if($arr_tolist[$i][2] == $value) {
				$arr_result[] = $arr_tolist[$i][0];
				$flag = true;
			}
		}
		if($flag == false) {
			$arr_result = array();
			$arr_result[0] = $value;
			$main_flag = true;
			break;
		}
	}
	if($main_flag == true) {
		return $arr_result;
	}
	else {
		return(getIndLeaf($arr_result,$total_count));
	}
}
//var_dump ($mimedecoder->_mailparts);
//echo "<br><br><br>";
//print_r($mimedecoder->_mailheader);
//print_r($mimedecoder->_header);
//echo $msg;
/*foreach($mimedecoder->_attachments as $obj) {
	echo($obj->_attachmentname . "<br>");
}*/
/*
$ob = $mimedecoder->decode();
echo $ob->headers[to];
echo "<br>";
echo $ob->headers[from];
print_r($ob->headers[from]);*/
if(!function_exists('getLeafDepts')) {
	function getLeafDepts(){
			  global $conn;
			  $dids="";
			  $pids = "";
			  $sql ="select nDeptId,nDeptParent from sptbl_depts ";
			  $rs = executeSelect($sql,$conn);
			  if(mysql_num_rows($rs)!=0){
							while($row = mysql_fetch_array($rs)){
											$dids .= ",".$row["nDeptId"];
											$pids .= ",".$row["nDeptParent"];

							}
			  }else{
							return "";
			  }
					$pids = substr($pids,1);
					$dids = substr($dids,1);

					if($dids !=""){
					  $pidarr=explode(",",$pids );
					  $didarr=explode(",",$dids );
					  $diffarray=array_diff($didarr,$pidarr);
					  return  $diffarray;
					}else{
					  return "";
					}
	}
}
function getExtension($filename)
{
	$ext  = @strtolower(@substr($filename, (@strrpos($filename, ".") ? @strrpos($filename, ".") + 1 : @strlen($filename)), @strlen($filename)));
	return ($ext == 'jpeg') ? 'jpg' : $ext;
}
function getFileName($filename)
{
	$fn  = @strtolower(@substr($filename, 0, (@strrpos($filename, ".") ? @strrpos($filename, ".") : @strlen($filename))));
	return ($fn);
}
function getLookupDetails() {
	global $conn;
	$arr=array();
	$sql = " Select * from sptbl_lookup where vLookUpName IN('Post2PostGap','MailFromName','MailFromMail',";
	$sql .="'MailReplyName','MailReplyMail','Emailfooter','Emailheader','AutoLock','MailEscalation',";
	$sql .="'Post2PostGap','HelpdeskTitle','MaxfileSize','LoginURL','EmailPiping')";
	$result = executeSelect($sql,$conn);
	if(mysql_num_rows($result) > 0) {
		while($row = mysql_fetch_array($result)) {
			switch($row["vLookUpName"]) {
				case "MailFromName":
								$arr['var_fromName'] = $row["vLookUpValue"];
								break;
				case "MailFromMail":
								$arr['var_fromMail'] = $row["vLookUpValue"];
								break;
				case "MailReplyName":
								$arr['var_replyName'] = $row["vLookUpValue"];
								break;
				case "MailReplyMail":
								$arr['var_replyMail'] = $row["vLookUpValue"];
								break;
				case "Emailfooter":
								$arr['var_emailfooter'] = $row["vLookUpValue"];
								break;
				case "Emailheader":
								$arr['var_emailheader'] = $row["vLookUpValue"];
								break;
				case "AutoLock":
								$arr['var_autoclock'] = $row["vLookUpValue"];
								break;
			   case "MailEscalation":
								$arr['var_emailescalation'] = $row["vLookUpValue"];
								break;
			   case "Post2PostGap":
							   $arr['var_post2postgap'] = $row["vLookUpValue"];
							   break;
			   case "HelpdeskTitle":
			   					$arr['var_helpdesktitle'] = $row["vLookUpValue"];
								break;
			   case "MaxfileSize":
			   					$arr['var_maxfilesize'] = $row["vLookUpValue"];
								break;
				case "LoginURL":
								$arr['var_loginurl'] = $row["vLookUpValue"];
								break;
				case "EmailPiping":
								$arr['var_emailpiping'] = $row["vLookUpValue"];
								break;
			}
		}
	}
	mysql_free_result($result);
	return $arr;
}
function validateFromAddress($var_fromaddress,$var_posttopostgap=0) {
	global $conn;

	$sql ="select date_add(dLastAttempted,interval $var_posttopostgap MINUTE) < now() as ptop,u.vBanned,u.vDelStatus
		from sptbl_tickets t inner join sptbl_users u on
		t.nUserId=u.nUserId Where u.vEmail='" . addslashes($var_fromaddress) . "' order by t.dLastAttempted desc limit 0,1";
	$result = executeSelect($sql,$conn);
	if(mysql_num_rows($result) > 0) {
		$row = mysql_fetch_array($result);
		if($row['ptop'] != "1") {
			return false;
		}
		do{
			if($row["vBanned"] != "0" || $row["vDelStatus"] != "0") {
				return false;
			}
		}while($row=mysql_fetch_array($result));
	}
	$sql = "Select * from dummy d
		Left JOIN sptbl_staffs s on (d.num=0 AND s.vMail='" . addslashes($var_fromaddress) . "')
		Left join sptbl_depts dt on (d.num=1 AND dt.vDeptMail='" . addslashes($var_fromaddress) . "')
		Left join sptbl_companies c on(d.num=2 AND c.vCompMail='" . addslashes($var_fromaddress) . "')
		where d.num < 3  AND (s.nStaffId IS NOT NULL OR dt.nDeptId IS NOT NULL OR c.nCompId IS NOT NULL)";
	if(mysql_num_rows(executeSelect($sql,$conn)) > 0) {
		return false;
	}
	else {
		$sql = "Select nLookUpId from sptbl_lookup where vLookUpValue='" . addslashes($var_fromaddress) . "'
		AND vLookUpName IN('MailAdmin','MailTechnical','MailEscalation','MailFromMail','MailReplyMail')";
		if(mysql_num_rows(executeSelect($sql,$conn)) > 0) {
			return false;
		}
	}
	return true;
}
if(!function_exists('stripslashes_deep')) {
function stripslashes_deep($value){
	$value = is_array($value) ?
	array_map('stripslashes_deep', $value) :
	stripslashes($value);
	return $value;
}
}
/*
function validateUploads() {
	$sql = "Select * from sptbl_lookup where vLookUpName IN('Attachments','MaxfileSize')";
	$result = executeSelect($sql,$conn);
	if(mysql_num_rows($result) > 0) {
		while($row = mysql_fetch_array($result)) {
			switch($row["vLookUpName"]) {
			  case "Attachments":
							$var_attach_typearr =explode("|",$row["vLookUpValue"]);
							$atype=$atype.$var_attach_typearr[1].",";
							$atype_extension=$atype_extension.$var_attach_typearr[0].",";
							break;
			   case "MaxfileSize":
							$alsize = $row["vLookUpValue"];
							break;
		   }

		}
	}
	mysql_free_result($result);
	$atype = substr($atype,0,-1);
	$allowetype_extn_array=explode(",",$atype_extension);
    $file_type_extension=substr($_FILES[$fname]['name'],strrpos($_FILES[$fname]['name'],".")+1);
	foreach($allowetype_extn_array as $key=>$value){
	  if($file_type_extension == $value){
		$allowedextn_flag=1;
		break;
	  }
}*/
function validateAttachments($var_name,$var_type) {
	global $arr_valid_attachtypes,$arr_valid_attachext,$var_valid_size;
	//echo("name:" . $var_name . " and type:" . $var_type . " and ");
	if(in_array(getExtension($var_name),$arr_valid_attachext) && in_array(strtolower($var_type),$arr_valid_attachtypes)) {
		//echo("true<br>");
		return true;
	}
	else {
		//echo("false<br>");
		return false;
	}
}
function getDataSize($data) {
	//$has_mbstring = extension_loaded('mbstring') ||@dl(PHP_SHLIB_PREFIX.'mbstring.'.PHP_SHLIB_SUFFIX);
        $has_mbstring = extension_loaded('mbstring') || function_exists('mb_get_info');
	$has_mb_shadow = (int) ini_get('mbstring.func_overload');

	if ($has_mbstring && ($has_mb_shadow & 2) ) {
	   $size = mb_strlen($data,'latin1');
	} else {
	   $size = strlen($data);
	}
	return $size;
}
function getDepartmentMailFromId($deptid) {
	global $arr_tolist;
	$count = count($arr_tolist);
	for($i=0;$i < $count;$i++){
		if($arr_tolist[$i][0] == $deptid) {
			return $arr_tolist[$i][1];
			break;
		}
	}
	return  " ";
}
?>