<?php
$email=$emailmarkedasspam;
$posteddate = $dpostdate;

$arr_lookupvalues = getLookupDetails();
$var_autoreturn_flag = $arr_lookupvalues['NewTicketAutoReturnMail'];
$mimedecoder=new MIMEDECODE($email,"\r\n");
$var_message_main = $mimedecoder->get_parsed_message();


//included to remove htmlcharacters,and other characters
/*
$search = array ('@<script[^>]*?>.*?</script>@si', // Strip out javascript
                 '@<[\/\!]*?[^<>]*?>@si',          // Strip out HTML tags
                 '@([\r\n])[\s]+@',                // Strip out white space
                 '@&(quot|#34);@i',                // Replace HTML entities
                 '@&(amp|#38);@i',
                 '@&(lt|#60);@i',
                 '@&(gt|#62);@i',
                 '@&(nbsp|#160);@i',
                 '@&(iexcl|#161);@i',
                 '@&(cent|#162);@i',
                 '@&(pound|#163);@i',
                 '@&(copy|#169);@i',
                 '@&#(\d+);@e');                    // evaluate as php

$replace = array ('',
                 '',
                 '\1',
                 '"',
                 '&',
                 '<',
                 '>',
                 ' ',
                 chr(161),
                 chr(162),
                 chr(163),
                 chr(169),
                 'chr(\1)');
//$var_message_main = preg_replace($search, $replace, $var_message_main);
*/
//end included
if(is_array($mimedecoder->_mailheader->_headerreceived)) {
    $var_machineip=getIpFromHeader($mimedecoder->_mailheader->_headerreceived[(count($mimedecoder->_mailheader->_headerreceived) - 1)]);
}
else {
    $var_machineip=getIpFromHeader($mimedecoder->_mailheader->_headerreceived);
}

//return value structure will be of the form
//Array ( [0] => stdClass Object ( [personal] => "Johnson Mathew" [comment] => Array ( ) [mailbox] => johnson [host] => armia.com ) )
//For more than one -- Array ( [0] => stdClass Object ( [personal] => [comment] => Array ( ) [mailbox] => armia [host] => armia.com ) [1] => stdClass Object ( [personal] => [comment] => Array ( ) [mailbox] => jimmy.jos [host] => armia.com ) )
$structure = Mail_RFC822::parseAddressList($mimedecoder->_mailheader->_headerreplyto, 'example.com', true);
//trace_function
//print_r($structure);
//First we check for the reply-to header, if it is present we make the from address the reply-to address
//Else we make the from header the from address
//$var_fromaddress	==>	is the from address
//$var_frommailbox	==>	is the from name
if(count($structure) > 0 && $structure[0]->mailbox != "") {
    $var_fromaddress = $structure[0]->mailbox . "@" . strtolower($structure[0]->host);
    $var_frommailbox = $structure[0]->mailbox;
}
else {
    $structure = Mail_RFC822::parseAddressList($mimedecoder->_mailheader->_headerfrom, 'example.com', true);
    $var_fromaddress = $structure[0]->mailbox . "@" . strtolower($structure[0]->host);
    $var_frommailbox = $structure[0]->mailbox;
}

$var_valid_size = $arr_lookupvalues['var_maxfilesize'];

/*	$sql = "Select * from sptbl_lookup where vLookUpName IN('Attachments')";
	$result = executeSelect($sql,$conn);
	if(mysql_num_rows($result) > 0) {
		while($row = mysql_fetch_array($result)) {
			switch($row["vLookUpName"]) {
			  case "Attachments":
							$var_attach_typearr =explode("|",$row["vLookUpValue"]);
							$atype=$atype.strtolower($var_attach_typearr[1]).",";
							$atype_extension=$atype_extension.strtolower($var_attach_typearr[0]).",";
							break;
		   }
		}
	}
	mysql_free_result($result);
*/
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
//$arr_valid_attachtypes,$arr_valid_attachext
$arr_valid_attachtypes = explode(",",$atype);
$arr_valid_attachext = explode(",",$atype_extension);
$atype = "";
/*	echo("valid types:");
	print_r($arr_valid_attachtypes);
	echo("<br>valid exts:");
	print_r($arr_valid_attachext);
	echo("<br>");
*/
if(validateFromAddress($var_fromaddress,$arr_lookupvalues['var_post2postgap']) == false) {
    //echo("false");
    //exit;
}

//exit();
//get all the destined addresses from to,cc,bcc into the array $var_toaddress
//do check for duplicates and insert all valid addresses to $var_toaddress array
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
    /* spam filtering will comes here*/
    //foreach($arr_new as $key=>$val) {
    //$val deptid
    //}
    /*********************************/

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
				'" . addslashes($var_message_main). "','0','".$posteddate."',
				'" . addslashes($var_machineip) . "',now())";

            executeQuery($sql,$conn);
            $var_insert_id = mysql_insert_id($conn);
            $var_ticketid = $var_insert_id;
            $currDate = date('Y-m-d H:i:s');                       
            $sql  = "insert into sptbl_ticket_statistics(ticket_id,posted_date) VALUES($var_ticketid,'".mysql_real_escape_string($currDate)."')";    
            executeQuery($sql,$conn);
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

            $sql_update_ticket = "update sptbl_tickets set vRefNo='".$var_refno."' where nTicketId='".$var_ticketid."'" ;

            executeQuery($sql_update_ticket,$conn);

            $sql1 = "insert into sptbl_attachments(nTicketId,vAttachReference,vAttachUrl) values";
            $sql = "";
            foreach($mimedecoder->_attachments as $objattach) {
                if((validateAttachments($objattach->_attachmentname,$objattach->_attachmenttype) == true) && (getDataSize($objattach->_attachmentcontent) < $var_valid_size)) {
                    $var_act_filename = uniqid("fl",true) . "." . getExtension($objattach->_attachmentname);
                    $sql .=  ",('" . $var_ticketid . "','" . $objattach->_attachmentname . "','" . addslashes($var_act_filename) . "')";
                    $fp = fopen("$dotdotreal/attachments/" . $var_act_filename, "w");
                    fwrite($fp, $objattach->_attachmentcontent);
                    fclose($fp);
                }
            }
            ($sql != "")?executeQuery($sql1 . substr($sql,1),$conn):"";
            if($var_autoreturn_flag == 1 || $var_autoreturn_flag == '1' ){
                mailUserOnTicketCreation($val,$total_count,$var_refno,$var_tmp_userid,$mimedecoder->_mailheader->_headersubject);
            }
            mailAllStaff($val,$var_refno);
        }
        //mailAllStaff($arr_new);
    }
}//end if mail received to a valid department


?>