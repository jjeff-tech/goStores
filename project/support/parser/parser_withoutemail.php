<?php
//set_magic_quotes_runtime(0);
// Check if magic_quotes_runtime is active
if(get_magic_quotes_runtime())
{
    // Deactivate
   // set_magic_quotes_runtime(false);
}
$dotdotreal="..";
$dotreal=".";
require_once("$dotdotreal/includes/decode.php");
if(!isValidForParser(1,'P',$dotdotreal)) {
	exit();
}
//read the mail from the input stream
$fd = fopen("php://stdin", "r");
$email = "";
while (!feof($fd)) {
    $email .= fread($fd, 1024);
}
fclose($fd);
include_once("$dotdotreal/config/settings.php");
include_once("$dotreal/includes/functions/miscfunctions.php");
include_once("$dotreal/includes/functions/impfunctions.php");
include_once("$dotreal/includes/functions/dbfunctions.php");
include_once("$dotreal/includes/mimedecode.inc.php");
include_once("$dotreal/includes/RFC822.php");
include_once("$dotreal/languages/en/parser.php");
$conn = getConnection();

//get the look up tables' values to $arr_lookupvalues
	$arr_lookupvalues = getLookupDetails();

$mimedecoder=new MIMEDECODE($email,"\r\n");
$var_message_main = $mimedecoder->get_parsed_message();
$var_machineip="self";
$var_fromaddress = "user@armia.com";
$var_frommailbox = $structure[0]->mailbox;
$var_valid_size = $arr_lookupvalues['var_maxfilesize'];
	$sql = "Select * from sptbl_lookup where vLookUpName ='Attachments'";
	$result = executeSelect($sql,$conn);
	if(mysql_num_rows($result) > 0) {
		while($row = mysql_fetch_array($result)) {
			$var_attach_typearr =explode("|",$row["vLookUpValue"]);
			$atype=$atype.strtolower($var_attach_typearr[1]).",";
			$atype_extension=$atype_extension.strtolower($var_attach_typearr[0]).",";
		}
	}
	mysql_free_result($result);

	$atype = substr($atype,0,-1);
	$atype_extension = substr($atype_extension,0,-1);
	$arr_valid_attachtypes = explode(",",$atype);
	$arr_valid_attachext = explode(",",$atype_extension);
	$atype = "";
if(validateFromAddress($var_fromaddress,$arr_lookupvalues['var_post2postgap']) == false) {
	exit;
}
	$var_toaddress = array();
	for($j = 0;$j < 3; $j++) {
		$structure="";
		switch($j) {
			case 0:
				$structure = Mail_RFC822::parseAddressList($mimedecoder->_mailheader->_headerto, 'example.com', true);
				break;
			case 1:
				$structure = Mail_RFC822::parseAddressList($mimedecoder->_mailheader->_headercc, 'example.com', true);
				break;
			case 2:
				$structure = Mail_RFC822::parseAddressList($mimedecoder->_mailheader->_headerbcc, 'example.com', true);
				break;
		}
		$cnt = count($structure);
		for($i=0;$i < $cnt; $i++) {
			$var_temp = $structure[$i]->mailbox . "@" . strtolower($structure[$i]->host);
			if($structure[$i]->mailbox != "" && !isset($var_toaddress[$var_temp])) {
				$var_toaddress[$var_temp] = $structure[$i]->mailbox;
			}
		}
	}

//print_r($var_toaddress);
/*
 *Case-sensitivity for isset - it is case sensitive
 *so same email addresses with different case may create two tickets
 *se we convert all domain names to lower case here
$arr["Test"] = "h";
if(!isset($arr["Test"])) {
	echo("cannot be used");
}

 *Testing for preg_match in subject line for ticket id
 $subject = "testing from ticket id#[111-2111-3111-3222]ticket id#[111-2111-3111-3223]";
 preg_match_all("/ticket id#\[[0-9]{0,}-[0-9]{0,}-[0-9]{0,}-[0-9]{0,}\]/i",$subject,$array);
 echo("match:" . count($array) . " and match =" . count($array[0]) . " and " . $array[0][0] . " and " . $array[0][1]);
*/
/*
 * stripping characters for username
 $string = "hello\\\"'<>?/@#$%^&*()_-+=";
 $newstring = preg_replace("/[^a-z0-9]/i","",$string);
 echo($newstring);
*/
/*
$strToAddress="";
foreach($var_toaddress as $var_deptmail=>$var_name)
	$strToAddress .= ",'" . $var_deptmail . "'";
}
$strToAddress = ($strToAddress != "")?substr($strToAddress,1):"''";
*/
//trace_function
//echo("count :" . count($var_toaddress) . "<br>");
//print_r($var_toaddress);
//echo("subject: " . $mimedecoder->_mailheader->_headersubject . "<br>");



//query the db for the department id, dept. mail, parent dept., and company id.
//we build an array nX4 , of which first column contains deptid, second column is deptmail,
//third is deptparent, and fourth is company id.
$arr_tolist="";
$i=0;
$sql  = "select nDeptId,vDeptMail,nDeptParent,nCompId from sptbl_depts order by nDeptId ASC";
$result = executeSelect($sql,$conn);
if(mysql_num_rows($result) > 0) {
	while($row = mysql_fetch_array($result)) {
		$arr_tolist[$i][0] = $row['nDeptId'];
		$arr_tolist[$i][1] = $row['vDeptMail'];
		$arr_tolist[$i][2] = $row['nDeptParent'];
		$arr_tolist[$i][3] = $row['nCompId'];
		$i++;
	}
}

/*
	//testing
	$var_toaddress = array();
	//$var_toaddress['a@a.com'] = 'a';
	//$var_toaddress['e@a.com'] = 'e';
	//$var_toaddress['f@a.com'] = 'f';
	$var_toaddress['c@a.com'] = 'c';

	$arr_tolist = array();
	$arr_tolist[0][0] = 1;
	$arr_tolist[0][1] = "a@a.com";
	$arr_tolist[0][2] = 0;
	$arr_tolist[0][3] = 1;

	$arr_tolist[1][0] = 2;
	$arr_tolist[1][1] = "b@a.com";
	$arr_tolist[1][2] = 1;
	$arr_tolist[1][3] = 1;

	$arr_tolist[2][0] = 3;
	$arr_tolist[2][1] = "c@a.com";
	$arr_tolist[2][2] = 2;
	$arr_tolist[2][3] = 1;

	$arr_tolist[3][0] = 4;
	$arr_tolist[3][1] = "d@a.com";
	$arr_tolist[3][2] = 2;
	$arr_tolist[3][3] = 1;

	$arr_tolist[4][0] = 5;
	$arr_tolist[4][1] = "e@a.com";
	$arr_tolist[4][2] = 4;
	$arr_tolist[4][3] = 1;

	$arr_tolist[5][0] = 6;
	$arr_tolist[5][1] = "f@a.com";
	$arr_tolist[5][2] = 5;
	$arr_tolist[5][3] = 1;

	$arr_tolist[6][0] = 7;
	$arr_tolist[6][1] = "g@a.com";
	$arr_tolist[6][2] = 6;
	$arr_tolist[6][3] = 1;

//end testing
*/

//Get the departmentidlist
//here case-insensitive search for deptmail is done  using strcasecmp
//if in future we need case-sensitive search use teh function strcmp
$arr_deptid = array();
$cnt = count($arr_tolist);
foreach($var_toaddress as $var_deptmail=>$var_name) {
	for($i=0;$i < $cnt;$i++) {
		if(strcasecmp($arr_tolist[$i][1],$var_deptmail) == 0) {
			$arr_deptid[] = $arr_tolist[$i][0];
		}
	}
}

//Get the leaf level departments
$var_deptlist = getLeafDepts();
if($var_deptlist != "") {
	$arr_leafdept = explode(",",$var_deptlist);
}
else {
	$arr_leafdept=array();
}

/*
//testing2 start
	$arr_leafdept = array();
	$arr_leafdept[0]= 7;
	//$arr_leafdept[1]= 6;
	//$arr_leafdept[2]= 7;
//end testing2
*/
//main array that contains valid leaf departments
$arr_main = array_intersect($arr_deptid,$arr_leafdept);
//trace_function
//echo("<br>arr_main:<br>" . count($arr_main));
//print_r($arr_main);
//departments which are not leaf, but received mails to
$arr_diff = array_diff($arr_deptid,$arr_leafdept);
//trace_function
//echo("<br>arr_diff:<br>" . count($arr_diff));
//print_r($arr_diff);

//This loop fetches the first child leaf node of the dept id
//and adds it to the main array
$cnt = count($arr_diff);
$total_count = count($arr_tolist);
foreach($arr_diff as $key=>$val) {
	//trace_function
	//echo("<br>leaf of " . $val . ":");
	//get the first leaf child of the depratment in $val
	$temp = getIndLeaf(array($val),$total_count);
	//trace_function
	//print_r($temp);
	//echo("<br>");
	$arr_main[] = $temp[0];
}
//trace_function
//echo("<br>final:<br>" . count($arr_main));

//get unique id from the list of leaf departments from the main array
$arr_new = array_unique($arr_main);

//trace_function
//print_r($arr_new);
//echo("here");

//Get the company list
$var_companylist="";
if(count($arr_new) > 0) {	//If mail received to a valid department
	//The company id of the departments is fetched and assigned to the
	//$arr_compid array
	$arr_compid = array();
	$total_count = count($arr_tolist);
	foreach($arr_new as $key=>$val) {
		$arr_compid[] = getCompanyId($val,$total_count);

	}
	//get unique company ids' from the $arr_compid
	$arr_compid_final = array_unique($arr_compid);
	//trace_function
	//echo("<br>companyid:<br>" . "'" .implode("','",$arr_compid_final) . "'<br>");
	//print_r($arr_compid_final);


	$var_mail_ticketrefno="";
	$bool_new_ticket = true;	// This flag is set to false if the ticket id, from email, and dept id matches.
	if(getTicketRefno($mimedecoder->_mailheader->_headersubject,$var_mail_ticketrefno) === true) {
		$sql = "select t.nTicketId,t.nUserId,t.nDeptId,t.vRefNo,t.vTitle,u.nCompId from sptbl_tickets t inner join sptbl_users u
			 on t.nUserId = u.nUserId where u.vEmail='" . addslashes($var_fromaddress) . "'
			AND t.vRefNo ='" . addslashes($var_mail_ticketrefno) . "' ";
		$result = executeSelect($sql,$conn);
		if(mysql_num_rows($result) > 0) {
			$row = mysql_fetch_array($result);
			if(in_array($row["nDeptId"],$arr_new) == true) {
				$bool_new_ticket = false;
				//If the ticket id, from address, and deptaddress matches then iti s entered as a reply
				//for the ticket id through the function enterReply
				enterReply($row["nTicketId"],$row["nUserId"],$row["nDeptId"],$row["vRefNo"],$row["vTitle"]);
			}
		}
	}
	if($bool_new_ticket == true) {
		$arr_user_login = array();
		$sql = "select nUserId,nCompId,vLogin from sptbl_users where
		vEmail='" . addslashes($var_fromaddress) . "' AND
		nCompId IN('" . implode("','",$arr_compid_final) . "')";
		$result = executeSelect($sql,$conn);
		$arr_comp_toregister = array();
		if(mysql_num_rows($result) > 0) {
			while($row = mysql_fetch_array($result)) {
				$arr_comp_toregister[$row["nUserId"]] = $row["nCompId"];
				$arr_user_login[$row["nUserId"]] = $row["vLogin"];
			}
		}
		$arr_new_user = array();
		$arr_comp_diff = array_diff($arr_compid_final,$arr_comp_toregister);
		foreach($arr_comp_diff as $key=>$val) {
			$var_username = "";
			$var_userlogin = "";
			getUserLogin($var_frommailbox,$val,$var_username,$var_userlogin);
			$var_userpassword = ($var_userpassword != "")?$var_userpassword:getUserPassword($var_fromaddress);
			$sql = "Insert into sptbl_users(nUserId,nCompId,vUserName,vEmail,vLogin,vPassword,dDate,nCSSId)
				Values('',
				'" . addslashes($val) . "',
				'" . addslashes($var_username) . "',
				'" . addslashes($var_fromaddress) . "',
				'" . addslashes($var_userlogin) . "',
				'" . md5($var_userpassword) . "',
				now(),'1')";
				executeQuery($sql,$conn);
				$var_id = mysql_insert_id();
				$arr_comp_toregister[$var_id] = $val;
				$arr_user_login[$var_id] = $var_username;
				$arr_new_user[$var_id] = $var_userpassword;
		}
		foreach($arr_new as $key=>$val) {
			$var_tmp_compid = getCompanyId($val,$total_count);
			$var_tmp_userid = array_search($var_tmp_compid,$arr_comp_toregister);
			$var_userlogin = $arr_user_login[$var_tmp_userid];
			$sql = "insert into sptbl_tickets(nTicketId,nDeptId,vRefNo,nUserId,vUserName,vTitle,tQuestion,
			vPriority,dPostDate,vMachineIP,dLastAttempted)
				values('','" . $val . "','1','" . $var_tmp_userid . "',
				'".addslashes($var_userlogin)."',
				'".addslashes($mimedecoder->_mailheader->_headersubject)."',
				'" . addslashes($var_message_main). "','0',now(),
				'" . addslashes($var_machineip) . "',now())";
				executeQuery($sql,$conn);
				$var_insert_id = mysql_insert_id($conn);
                                $currDate = date('Y-m-d H:i:s');
                                $sql = "insert into sptbl_ticket_statistics(ticket_id,posted_date) VALUES($var_insert_id,'" . mysql_real_escape_string($currDate) . "')";
                                executeQuery($sql, $conn);
	//update reference number
				//	modified on 15-11-06 by roshith	for constatnt length ref.no.

				// 'zero' added for 2 digit companyid
			    if($var_tmp_compid<10)
					$var_tmp_compid = "0".$var_tmp_compid;

				// 'zero' added for 2 digit departmentid
			    if($val<10)
					$val = "0".$val;

				// 'zeros' added for 4 digit userid
			    if($var_tmp_userid<10)
					$var_tmp_userid = "000".$var_tmp_userid;  // 9   0009
			    else if($var_tmp_userid<100)
					$var_tmp_userid = "00".$var_tmp_userid;  // 99   0099
			    else if($var_tmp_userid<1000)
					$var_tmp_userid = "0".$var_tmp_userid;  // 999   0999

				// 'zeros' added for 5 digit ticket no
			    if($var_insert_id<10)                 // 9   00009
					$var_insert_id = "0000".$var_insert_id;
				else if($var_insert_id<100)          // 99   00099
					$var_insert_id = "000".$var_insert_id;
				else if($var_insert_id<1000)        // 999   00999
					$var_insert_id = "00".$var_insert_id;
				else if($var_insert_id<10000)      // 9999   09999
					$var_insert_id = "0".$var_insert_id;
				
			    //CompId.DeptID.UserId.nTcketId
//				 $var_refno=$var_tmp_compid."-".$val."-".$var_tmp_userid."-".$var_insert_id;

			    $var_refno=$var_tmp_compid.$val.$var_tmp_userid.$var_insert_id;

			    $sql_update_ticket = "update sptbl_tickets set vRefNo='".$var_refno."' where nTicketId='".$var_insert_id."'" ;

			    executeQuery($sql_update_ticket,$conn);

				$sql1 = "insert into sptbl_attachments(nTicketId,vAttachReference,vAttachUrl) values";
				$sql = "";
				foreach($mimedecoder->_attachments as $objattach) {
					if((validateAttachments($objattach->_attachmentname,$objattach->_attachmenttype) == true) && (getDataSize($objattach->_attachmentcontent) < $var_valid_size)) {
						$var_act_filename = uniqid("fl",true) . "." . getExtension($objattach->_attachmentname);
						$sql .=  ",('" . $var_insert_id . "','" . $objattach->_attachmentname . "','" . addslashes($var_act_filename) . "')";
						$fp = fopen("$dotdotreal/attachments/" . $var_act_filename, "w");
						fwrite($fp, $objattach->_attachmentcontent);
						fclose($fp);
					}
				}
				($sql != "")?executeQuery($sql1 . substr($sql,1),$conn):"";
				mailUserOnTicketCreation($val,$total_count,$var_refno,$var_tmp_userid,$mimedecoder->_mailheader->_headersubject);
				mailAllStaff($val,$var_refno);
		}
		//mailAllStaff($arr_new);
	}
}//end if mail received to a valid department


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