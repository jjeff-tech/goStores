<?php
$var_staffid = $_SESSION["sess_staffid"];
$fld_arr = $_SESSION["sess_fieldlist"];
$fld_prio = $_SESSION["sess_priority"];

if($_GET["mt"] == "y") {
        $var_numBegin = $_GET["numBegin"];
        $var_start = $_GET["start"];
        $var_begin = $_GET["begin"];
        $var_num = $_GET["num"];
		$var_company = $_GET["cmp"];
		$var_department = trim($_GET["dpt"]);
		$var_status = trim($_GET["st"]);
		$var_owner = trim($_GET["own"]);
		$var_user = trim($_GET["usr"]);
		$var_ticketno = trim($_GET["tkt"]);
		$var_title = trim($_GET["ttl"]);
		$var_email = trim($_GET["eml"]);
		$var_label = trim($_GET["ltl"]);		
		$var_question = trim($_GET["qst"]);
		$var_from = $_GET["frm"];
		$var_to = $_GET["to"];

		$var_compop = $_GET["cop"];

		$var_deptop = $_GET["dop"];
		$var_deptlp = $_GET["dlp"];

		$var_statusop = $_GET["sop"];
		$var_statuslp = $_GET["slp"];

		$var_ownerop = $_GET["oop"];
		$var_ownerlp = $_GET["olp"];

		$var_userop = $_GET["uop"];
		$var_userlp = $_GET["ulp"];

		$var_tktop = $_GET["tkop"];
		$var_tktlp = $_GET["tklp"];

		$var_qstop = $_GET["qop"];
		$var_qstlp = $_GET["qlp"];

		$var_titleop = $_GET["top"];
		$var_titlelp = $_GET["tlp"];
		
		$var_emailop = $_GET["eop"];
		$var_emaillp = $_GET["elp"];

		$var_labelop = $_GET["lop"];
		$var_labellp = $_GET["llp"];
}
elseif($_POST["mt"] == "y") {
        $var_numBegin = $_POST["numBegin"];
        $var_start = $_POST["start"];
        $var_begin = $_POST["begin"];
        $var_num = $_POST["num"];

		$var_compop = $_POST["cmbCompOp"];

		$var_deptop = $_POST["cmbDeptOp"];
		$var_deptlp = $_POST["cmbDeptLp"];

		$var_statusop = $_POST["cmbStatusOp"];
		$var_statuslp = $_POST["cmbStatusLp"];

		$var_ownerop = $_POST["cmbOwnerOp"];
		$var_ownerlp = $_POST["cmbOwnerLp"];

		$var_userop = $_POST["cmbUserOp"];
		$var_userlp = $_POST["cmbUserLp"];

		$var_tktop = $_POST["cmbTktOp"];
		$var_tktlp = $_POST["cmbTktLp"];

		$var_qstop = $_POST["cmbQstOp"];
		$var_qstlp = $_POST["cmbQstLp"];

		$var_titleop = $_POST["cmbTitleOp"];
		$var_titlelp = $_POST["cmbTitleLp"];
		
		$var_emailop = $_POST["cmbEmailOp"];
		$var_emaillp = $_POST["cmbEmailLp"];

		$var_labelop = $_POST["cmblabelOp"];
		$var_labellp = $_POST["cmblabelLp"];

		$var_company = $_POST["cmbCompany"];
		$var_department = trim($_POST["txtDepartment"]);
		$var_status = trim($_POST["txtStatus"]);
		$var_owner = trim($_POST["txtOwner"]);
		$var_user = trim($_POST["txtUser"]);
		$var_ticketno = trim($_POST["txtTicketNo"]);
		$var_title = trim($_POST["txtTitle"]);
		$var_email = trim($_POST["txtEmail"]);
		$var_label = trim($_POST["txtLabel"]);		
		$var_question = trim($_POST["txtQuestion"]);
		$var_from = $_POST["txtFrom"];
		$var_to = $_POST["txtTo"];
}

$get_query_string = "cop=" . $var_compop .
					 "&dop=" . $var_deptop .
					 "&dlp=" . $var_deptlp .
					 "&sop=" . $var_statusop .
					 "&slp=" . $var_statuslp .
					 "&oop=" . $var_ownerop .
					 "&olp=" . $var_ownerlp .
					 "&uop=" . $var_userop .
					 "&ulp=" . $var_userlp .
					 "&tkop=" . $var_tktop .
					 "&tklp=" . $var_tktlp .
					 "&qop=" . $var_qstop .
					 "&qlp=" . $var_qstlp .
					 "&top=" . $var_titleop .
					 "&tlp=" . $var_titlelp .
					 "&eop=" . $var_emailop .
					 "&elp=" . $var_emaillp .
					 "&lop=" . $var_labelop .
					 "&llp=" . $var_labellp . "&";

//Block - I (populate the allowed departments for the user)
/*	$lst_dept = "'',";
	$sql = "Select nDeptId from sptbl_staffdept where nStaffId='$var_staffid'";
	$rs_dept = executeSelect($sql,$conn);
	if (mysql_num_rows($rs_dept) > 0) {
		while($row = mysql_fetch_array($rs_dept)) {
			$lst_dept .= $row["nDeptId"] . ",";
		}
	}
	$lst_dept = substr($lst_dept,0,-1);

	mysql_free_result($rs_dept);*/
//End Of Block - I
$arrayDept=array();
$arrayDeptName=array();
$sql = "Select nDeptId,nResponseTime,vDeptDesc  from sptbl_depts";
$result = mysql_query($sql) or die(mysql_error());
if(mysql_num_rows($result) > 0) {
	while($row = mysql_fetch_array($result)) {
		$arrayDept[$row["nDeptId"]] = $row["nResponseTime"];
		$arrayDeptName[$row["nDeptId"]]=TEXT_DEPT1.$row["vDeptDesc"].TEXT_DEPT2;
		//$arrayDept[$row["nDeptId"]['name']]=TEXT_DEPT1.$row["vDeptDesc"].TEXT_DEPT2;
	}
}

//Delete Ticket section
$var_list = "";
if($_POST["del"] == "DM") {
	for($i=0;$i<count($_POST["chkDeleteTickets"]);$i++) {
		$var_list .= addslashes($_POST["chkDeleteTickets"][$i]) . ",";
	}
	$var_list = substr($var_list,0,-1);
	$message="";
	deleteChecked($var_list,$message);
}
if($_POST["del"] == "MS") {
   $sqlFilter = "Select * from sptbl_lookup where vLookUpName ='spamfiltertype'";
   $resultFilter = executeSelect($sqlFilter,$conn);
   $rowFilterType = mysql_fetch_array($resultFilter);
   $filtertype=$rowFilterType['vLookUpValue'];


   require("../spamfilter/spamfilterclass.php");

   for($i=0;$i<count($_POST["chkDeleteTickets"]);$i++) {
		$var_list .= addslashes($_POST["chkDeleteTickets"][$i]) . ",";
	}
	$var_list = substr($var_list,0,-1);
	$qry="select * from sptbl_tickets where  nTicketId in($var_list)";

	$result_spam = mysql_query($qry) or die(mysql_error());

	if(mysql_num_rows($result_spam) > 0) {
			while($row_spam = mysql_fetch_array($result_spam)) {
			     $_REQUEST['cat']='spam';
			     $_REQUEST['docid']="ticket_".$row_spam['nTicketId'];

			     if($filtertype=="SUBJECT"){
                               $_REQUEST['document']=$row_spam['vTitle'];
				 }else if($filtertype=="BODY"){
                               $_REQUEST['document']= $row_spam['tQuestion'];
				 }else if($filtertype=="BOTH"){
                                 $_REQUEST['document']=$row_spam['vTitle'] ." ".$row_spam['tQuestion'];
				 }
                 //echo " doc==". $_REQUEST['document'];
			     train();

				 $val = $row_spam['nDeptId'];
				 $var_machineip = $row_spam['vMachineIP'];
				 $var_message_main = $row_spam['tQuestion'];

				 $sql = "insert into sptbl_spam_tickets(nSpamTicketId,nDeptId,vTitle,tQuestion,dPostDate,vMachineIP)
					values('','" . $val . "','".addslashes($row_spam['vTitle'])."','" . addslashes($var_message_main). "',now(),
					'" . addslashes($var_machineip) . "')";
				 executeQuery($sql,$conn);			 
		   }
    }
    $message="";
    deleteChecked($var_list,$message);
   	$message =  $updatedtickets . MESSAGE_RECORD_MOVED_SUCCESSFULLY."<br>";
        $flag_msg = 'class="msg_success"';
}
if($_POST["del"] == "UP") {

    $frm_status=$_POST['cmbStatus'];
    $updatedtickets=0;

    for($i=0;$i<count($_POST["chkDeleteTickets"]);$i++) {

					        $var_ticketid=addslashes($_POST["chkDeleteTickets"][$i]);
							$update_flag = false;

						    $sql = " Select * from sptbl_lookup where vLookUpName IN('MailFromName','MailFromMail',";
						    $sql .= "'MailReplyName','MailReplyMail','Emailfooter','Emailheader','MailEscalation','HelpdeskTitle')";
						    $result = executeSelect($sql, $conn);
						    if (mysql_num_rows($result) > 0) {
						        while ($row2 = mysql_fetch_array($result)) {
						            switch ($row2["vLookUpName"]) {
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

						    $sql = "Select * from sptbl_tickets where nTicketId='" . addslashes($var_ticketid) . "'";
						    $rs = executeSelect($sql, $conn);
						    if (mysql_num_rows($rs) > 0) {
						        $row = mysql_fetch_array($rs);
						        $mail_refno = $row["vRefNo"];
						        $mail_title = $row["vTitle"];
						        $mail_status = $row["vStatus"];
						        $update_flag = true;

						        if ($update_flag == true) {
						            $updatedtickets++;
						            $sql = "Update sptbl_tickets set  vStatus='" . addslashes($frm_status) . "'  Where nTicketId='" . addslashes($var_ticketid) . "' ";
						            executeQuery($sql, $conn);
						            // Insert the actionlog
						            if (logActivity()) {
						                $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Tickets','$var_ticketid',now())";
						                executeQuery($sql, $conn);
						            }
						            // mail if the status is changed to escalated
						            if ($frm_status == "escalated" && $mail_status != "escalated") { // mail admin if escalated
						                $var_body = $var_emailheader . "<br>" . TEXT_MAIL_START . "&nbsp; Admin,<br>";
						                $var_body .= TEXT_ESCALATED_BODY . " " . $mail_refno . TEXT_MAIL_BY . htmlentities($_SESSION['sess_staffname']) . "<br><br>";
						                $var_body .= TEXT_MAIL_THANK . "<br>" . htmlentities($var_helpdesktitle) . "<br>" . $var_emailfooter;
						                $var_subject = TEXT_ESCALATION_SUB;
						                $Headers = "From: $var_fromName <$var_fromMail>\n";
						                $Headers .= "Reply-To: $var_replyName <$var_replyMail>\n";
						                $Headers .= "MIME-Version: 1.0\n";
						                $Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

										// it is for smtp mail sending
										if($_SESSION["sess_smtpsettings"] == 1){
											$var_smtpserver = $_SESSION["sess_smtpserver"];
											$var_port = $_SESSION["sess_smtpport"];
								
											SMTPMail($var_fromMail,$var_emailescalation,$var_smtpserver,$var_port,$var_subject,$var_body);
										}
										else					                
											$mailstatus = @mail($var_emailescalation, $var_subject, $var_body, $Headers);		            
									} //end mail admin
						            // end mail escalated
                                                            if ($frm_status == "closed")// Mail send to user on ticket close
                                                            {
                                                                //echo $frm_status;exit;
                                                                sendMailUserTicketClose($mail_refno,$var_helpdesktitle);

                                                            }// Mail send to user on ticket close ends

						            $var_message = MESSAGE_RECORD_UPDATED;
                                                            $flag_msg = 'class="msg_success"';
						        }
						    }
						    mysql_free_result($rs);
	}
	$notupdate=$i-$updatedtickets;
	if($updatedtickets >0){
    	$message = $updatedtickets .  " ticket/s updated successfully.<br>";
        $flag_msg = 'class="msg_success"';
    }
    if($notupdate>0){
    $message .= $notupdate . " of the selected ticket/s cannot be updated!.";
    $flag_msg = 'class="msg_error"';
    }
}
//

// to update label
if($_POST["labelup"] == "LABELUP") {
    $frm_label=$_POST['cmbLabel'];
    $updatedtickets=0;

    for($i=0;$i<count($_POST["chkDeleteTickets"]);$i++) {
			$var_ticketid=addslashes($_POST["chkDeleteTickets"][$i]);

			$updatedtickets++;
			$sql = "Update sptbl_tickets set  nLabelId='" . addslashes($frm_label) . "'  Where nTicketId='" . addslashes($var_ticketid) . "' ";
			executeQuery($sql, $conn);
			// Insert the actionlog
			if (logActivity()) {
				$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Tickets','$var_ticketid',now())";
				executeQuery($sql, $conn);
			}
			$var_message = MESSAGE_RECORD_UPDATED;
                        $flag_msg = 'class="msg_success"';
	}
			$notupdate=$i-$updatedtickets;
	
	if($updatedtickets >0){
    	$message = $updatedtickets . MESSAGE_RECORD_MOVED_SUCCESSFULLY."<br>";
        $flag_msg = 'class="msg_success"';
    }
    if($notupdate>0){
	    $message .= $notupdate . MESSAGE_RECORD_CANNOT_MOVED;
            $flag_msg = 'class="msg_error"';
    }
}
//end label update

$sql = "Select d.nDeptId,CONCAT(CONCAT(CONCAT('[',d.vDeptCode),']'),CONCAT(d.vDeptDesc,CONCAT(CONCAT('(',c.vCompName),')'))) as 'description' from
		sptbl_depts d  inner join sptbl_companies c
		 on d.nCompId = c.nCompId ";
$lst_dept_opt = "";
$rs_dept = executeSelect($sql,$conn);
if (mysql_num_rows($rs_dept) > 0) {
	while($row = mysql_fetch_array($rs_dept)) {
		$lst_dept_opt .= "<option value=\"" . $row["nDeptId"] . "\">" . htmlentities($row["description"]) . "</option>";
	}
}
mysql_free_result($rs_dept);

// label starts here
$sqllabel = "Select l.nLabelId,l.vLabelname from sptbl_labels l where l.nStaffId='$var_staffid'";
$lst_label_opt = "";
$rs_label = executeSelect($sqllabel,$conn);
if (mysql_num_rows($rs_label) > 0) {
	while($row = mysql_fetch_array($rs_label)) {
		$lst_label_opt .= "<option value=\"" . $row["nLabelId"] . "\">" . htmlentities($row["vLabelname"]) . "</option>";
	}
}
mysql_free_result($rs_label);
// label end

$sql = "Select t.nTicketId,t.nDeptId,d.vDeptDesc,t.vRefNo,t.nUserId,t.vUserName,t.vTitle,t.tQuestion,t.vPriority,
		t.dPostDate,t.vStatus,s.vStaffname as 'vOwner',t.nLockStatus,t.dLastAttempted,rp.nUserId as rpuserid, t.vViewers from
		sptbl_tickets t inner join sptbl_depts d on t.nDeptId = d.nDeptId 
		left outer join sptbl_labels l on t.nLabelId = l.nLabelId  left outer join
		sptbl_staffs s on t.nOwner = s.nStaffId left join sptbl_replies rp on (rp.dDate=t.dLastAttempted and rp.nTicketId=t.nTicketId) left join sptbl_users u on ( t.nUserId = u.nUserId)  WHERE  ";

$qryopt="";
$flag_qry = false;
if ($var_company != "") {
	$var_logical = ($flag_qry == true)?"AND":"";
	$flag_qry = true;
	$var_operator = "=";
	switch($var_compop) {
		case "m":
			$var_operator = "=";
			break;
		case "n":
			$var_operator = "!=";
			break;
	}
	$qryopt .=  $var_logical . "  d.nCompId" . $var_operator . "'" . addslashes($var_company) . "' ";
}
if ($var_department != "") {
	$var_logical = ($flag_qry == true)?(($var_deptlp == "and")?"AND":"OR"):"";
	$flag_qry = true;
	$var_operator = "=";
	switch($var_deptop) {
		case "m":
			$var_operator = "=";
			break;
		case "n":
			$var_operator = "!=";
			break;
	}
	$qryopt .=  $var_logical . "  d.nDeptId" . $var_operator . "'" . addslashes($var_department) . "' ";
}
if ($var_status != "") {
	$var_logical = ($flag_qry == true)?(($var_statuslp == "and")?"AND":"OR"):"";
	$flag_qry = true;
	$var_operator = "=";
	switch($var_statusop) {
		case "m":
			$var_operator = " LIKE ";
			break;
		case "n":
			$var_operator = " NOT LIKE ";
			break;
	}
	$qryopt .= $var_logical . "  t.vStatus" . $var_operator . "'%" . addslashes($var_status) . "%' ";
}
if ($var_owner != "") {
	$var_logical = ($flag_qry == true)?(($var_ownerlp == "and")?"AND":"OR"):"";
	$flag_qry = true;
	$var_operator = "=";
	switch($var_ownerop) {
		case "m":
			$var_operator = " LIKE ";
			break;
		case "n":
			$var_operator = " NOT LIKE ";
			break;
	}
	$qryopt .= $var_logical . "  s.vStaffname" . $var_operator . "'%" . addslashes($var_owner) . "%' ";
}
if ($var_user != "") {
	$var_logical = ($flag_qry == true)?(($var_userlp == "and")?"AND":"OR"):"";
	$flag_qry = true;
	$var_operator = "=";
	switch($var_userop) {
		case "m":
			$var_operator = " LIKE ";
			break;
		case "n":
			$var_operator = " NOT LIKE ";
			break;
	}
	$qryopt .= $var_logical . "  t.vUserName" . $var_operator . "'%" . addslashes($var_user) . "%' ";
}
if ($var_ticketno != "") {
	$var_logical = ($flag_qry == true)?(($var_tktlp == "and")?"AND":"OR"):"";
	$flag_qry = true;
	$var_operator = "=";
	switch($var_tktop) {
		case "m":
			$var_operator = " LIKE ";
			break;
		case "n":
			$var_operator = " NOT LIKE ";
			break;
	}
	$qryopt .=  $var_logical . "  t.vRefNo" . $var_operator ."'%" . addslashes($var_ticketno) . "%' ";
}
if ($var_title != "") {
	$var_logical = ($flag_qry == true)?(($var_titlelp == "and")?"AND":"OR"):"";
	$flag_qry = true;
	$var_operator = "=";
	switch($var_titleop) {
		case "m":
			$var_operator = " LIKE ";
			break;
		case "n":
			$var_operator = " NOT LIKE ";
			break;
	}
	$qryopt .= $var_logical . " t.vTitle" . $var_operator . "'%" . addslashes($var_title) . "%' ";
}
/*Newly Added on 280709*/
if ($var_email != "") {
	$var_logical = ($flag_qry == true)?(($var_emaillp == "and")?"AND":"OR"):"";
	$flag_qry = true;
	$var_operator = "=";
	switch($var_emailop) {
		case "m":
			$var_operator = " LIKE ";
			break;
		case "n":
			$var_operator = " NOT LIKE ";
			break;
	}
	$qryopt .= $var_logical . " u.vEmail" . $var_operator . "'%" . addslashes($var_email) . "%' ";
}
/*Newly Added on 280709*/
if ($var_label != "") {
	$var_logical = ($flag_qry == true)?(($var_labellp == "and")?"AND":"OR"):"";
	$flag_qry = true;
	$var_operator = "=";
	switch($var_labeltop) {
		case "m":
			$var_operator = "=";
			break;
		case "n":
			$var_operator = "!=";
			break;
	}
	$qryopt .=  $var_logical . "  l.nLabelId" . $var_operator . "'" . addslashes($var_label) . "' ";
}

if ($var_question != "") {
	$var_logical = ($flag_qry == true)?(($var_qstlp == "and")?"AND":"OR"):"";
	$flag_qry = true;
	$var_operator = "=";
	switch($var_qstop) {
		case "m":
			$var_operator = " LIKE ";
			break;
		case "n":
			$var_operator = " NOT LIKE ";
			break;
	}
	$qryopt .= $var_logical . " t.tQuestion" . $var_operator . "'%" . addslashes($var_question) . "%' ";
}
if ($var_from != "") {
		$var_logical = ($flag_qry == true)?"AND":"";
		$flag_qry = true;
		$arr_alert = explode("-",$var_from);
		$arr_year = explode(" ",$arr_alert[2]);
		$arr_tm = explode(":",$arr_year[1]);
		//$var_time1 = $arr_year[0] ."-" . $arr_alert[0] . "-" . $arr_alert[1] . " " . $arr_tm[0] . ":" . $arr_tm[1];

                $var_time1 = $arr_year[0] ."-" . $arr_alert[0] . "-" . $arr_alert[1];// . " " . $arr_tm[0] . ":" . $arr_tm[1];


	//$qryopt .=  $var_logical . "  t.dPostDate >='" . addslashes($var_time1) . "' ";
        $qryopt .=  $var_logical . "  DATE_FORMAT(t.dPostDate,'%Y-%m-%d') >=' " . addslashes($var_time1) . "' ";
}
if ($var_to != "") {
		$var_logical = ($flag_qry == true)?"AND":"";
		$flag_qry = true;
		$arr_alert = explode("-",$var_to);
		$arr_year = explode(" ",$arr_alert[2]);
		$arr_tm = explode(":",$arr_year[1]);
		//$var_time2 = $arr_year[0] ."-" . $arr_alert[0] . "-" . $arr_alert[1] . " " . $arr_tm[0] . ":" . $arr_tm[1];
                $var_time2 = $arr_year[0] ."-" . $arr_alert[0] . "-" . $arr_alert[1];// . " " . $arr_tm[0] . ":" . $arr_tm[1];

	//$qryopt .=  $var_logical . " t.dPostDate <='" . addslashes($var_time2) . "' ";
        $qryopt .=  $var_logical . "  DATE_FORMAT(t.dPostDate,'%Y-%m-%d') <= '" . addslashes($var_time2) . "' ";
}

$qryopt = (strlen($qryopt) > 0)?(" ( " . $qryopt . " ) AND t.vDelStatus='0' "):" t.vDelStatus='0'";

//$var_back="companies.php?mt=ybegin=" . $var_begin . "&num=" . $var_num . "&numBegin=" . $var_numBegin . "&start=$var_start&cmbSearch=" . $var_cmbSearch . "&txtSearch=" . urlencode($var_search) . "&";
//$_SESSION["backurl"] = $sess_back;

/////////////////// for sorting
if(isset($_GET["tp"]))
	$var_type = $_GET["tp"];

if(isset($_GET['val']))
	$var_orderby = $_GET['val'];
else	// default case
	$var_orderby = "dLastAttempted";

if(isset($_GET['pagenum']) != 'yes'){
	if($_GET['sorttype'] == 'DESC'){
		$var_sorttype = "ASC";
		$var_filename = "s_asc.png";
	}	
	else if($_GET['sorttype'] == 'ASC'){
		$var_sorttype = "DESC";
		$var_filename = "s_desc.png";
	}else{	// default case
		$var_sorttype = "DESC";
		$var_filename = "s_desc.png";
	}
}else{
	if(isset($_GET['sorttype']) && $_GET['sorttype']=='ASC'){
		$var_sorttype = $_GET['sorttype'];
		$var_filename = "s_asc.png";
	}
	else{
		$var_sorttype = $_GET['sorttype'];
		$var_filename = "s_desc.png";
	}
}

$sql .= $qryopt . " Order By t." . $var_orderby . " ".$var_sorttype;

//echo($sql);
?>

<form name="frmDetail" action="<?php echo   $_SERVER['PHP_SELF']?>" method="post">
<input type=hidden name="cmbCompLp">
<input type=hidden name="cmbCompOp">
<input type=hidden name="cmbCompany">
<input type=hidden name="cmbDeptLp">
<input type=hidden name="cmbDeptOp">
<input type=hidden name="txtDepartment">

<input type=hidden name="cmbStatusLp">
<input type=hidden name="cmbStatusOp">
<input type=hidden name="txtStatus">
<input type=hidden name="cmbOwnerLp">
<input type=hidden name="cmbOwnerOp">
<input type=hidden name="txtOwner">
<input type=hidden name="cmbUserLp">
<input type=hidden name="cmbUserOp">
<input type=hidden name="txtUser">
<input type=hidden name="cmbTktLp">
<input type=hidden name="cmbTktOp">
<input type=hidden name="txtTicketNo">
<input type=hidden name="cmbQstLp">
<input type=hidden name="cmbQstOp">
<input type=hidden name="txtQuestion">
<input type=hidden name="cmbTitleLp">
<input type=hidden name="cmbTitleOp">
<input type=hidden name="txtTitle">
<!--Newly Added on 280709-->
<input type=hidden name="cmbEmailLp">
<input type=hidden name="cmbEmailOp">
<input type=hidden name="txtEmail">
<!--Newly Added on 280709 ends-->
<input type=hidden name="cmbLabelLp">
<input type=hidden name="cmbLabelOp">
<input type=hidden name="txtLabel">
<input type=hidden name="txtFrom">
<input type=hidden name="txtTo">
<input type=hidden name="cmbCompLp">
<input type=hidden name="cmbCompLp">


<div class="content_section">
<div class="content_section_title"><h3><?php echo HEADING_ADVANCED_RESULT ?></h3></div>

                
                     
                                               <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="list_tbl">
											   <?php
												if($_POST["del"] == "DM" or $_POST["del"] == "UP" or $_POST["labelup"] == "LABELUP" or $message !="") {
													echo("<tr><td align=\"center\"  ".$flag_msg." colspan=11>" . $message . "</td></tr>");
												}
											  ?>

												<tr align="left"  class="whitebasic">
												<td width="3%" align="center"><input name="checkall" id="checkall" type="checkbox"  onclick="checkallfn()">
											   </td>
                                                                                           <td width="15%" align="center" ><?php echo "<b>".TEXT_FOLLOW."</b>"; ?></td>
                                               <td width="4%" class="linktext" style="text-decoration:none;">&nbsp;</td>											   
                                                <?php
													$cnt = 0;                                                                                                       
												while($cnt < count($fld_arr)) {
												 if($var_orderby == $fld_arr[$cnt][0]) 
													$img_path = "<img src=./../images/".$var_filename.">";
												 else 
													$img_path = "";
												 ?>
												  <td style="text-decoration:none;"><?php echo "<b><a href='?val=".$fld_arr[$cnt][0]."&sorttype=".$var_sorttype."&tp=".$var_type."&cmbDepartment=".$var_deptid."&frm=$var_from&to=$var_to&numBegin=$var_numBegin&start=$var_start&begin=$var_begin&num=$var_num&mt=y&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus' class=listing>".constant($fld_arr[$cnt][1])."</a></b>&nbsp;&nbsp;".$img_path; ?></td>
                                               <?php
											   		$cnt++;
												}
												//$cnt++;
												$cnt += 2;
											   ?>
											   <td width="15%" class="listing"><?php echo "<b>".TEXT_DUE."</b>";?></td>

										    </tr>
<?php

$var_maxposts = (int)$_SESSION["sess_maxpostperpage"];
$var_maxposts = ($var_maxposts < 1)?1:$var_maxposts;

//$totalrows = mysql_num_rows(mysql_query($sql,$conn));
$totalrows = mysql_num_rows(executeSelect($sql,$conn));
settype($totalrows,integer);
settype($var_begin,integer);
settype($var_num,integer);
settype($var_numBegin,integer);
settype($var_start,integer);

$var_calc_begin = ($var_begin == 0)?$var_start:$var_begin;
 if(($totalrows <= $var_calc_begin)) {
         $var_nor = $var_maxposts;
        $var_nol = 10;
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

$_SESSION['next_sql'] = $sql;
//echo("$totalrows,2,2,\"&ddlSearchType=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&id=$var_batchid&\",$var_numBegin,$var_start,$var_begin,$var_num");
$navigate = pageBrowser($totalrows,10,$var_maxposts,"&val=$var_orderby&sorttype=$var_sorttype&pagenum=yes&mt=y&cmp=$var_company&dpt=". urlencode($var_department) . "&st=" . urlencode($var_status) . "&own=" . urlencode($var_owner) . "&usr=" . urlencode($var_user) . "&tkt=" . urlencode($var_ticketno) . "&ttl=" . urlencode($var_title) . "&eml=" . urlencode($var_email) . "&ltl=" . urlencode($var_label) . "&qst=" . urlencode($var_question) . "&frm=" . urlencode($var_from) . "&to=" . urlencode($var_to) . "&" . $get_query_string,$var_numBegin,$var_start,$var_begin,$var_num);
$var_back="./advancedresult.php?mt=y&cmp=$var_company&dpt=". urlencode($var_department) . "&st=" . urlencode($var_status) . "&own=" . urlencode($var_owner) . "&usr=" . urlencode($var_user) . "&tkt=" . urlencode($var_ticketno) . "&ttl=" . urlencode($var_title) . "&eml=" . urlencode($var_email) . "&ltl=" . urlencode($var_label) . "&qst=" . urlencode($var_question) . "&frm=" . urlencode($var_from) . "&to=" . urlencode($var_to) . "&cop=$var_compop&dop=$var_deptop&dlp=$var_deptlp&sop=$var_statusop&slp=$var_statuslp&oop=$var_ownerop&olp=$var_ownerlp&qop=$var_qstop&qlp=$var_qstlp&uop=$var_userop&ulp=$var_userlp&tkop=$var_tktop&tklp=$var_tktlp&top=$var_titleop&tlp=$var_titlelp&begin=$var_begin&num=$var_num&numBegin=$var_numBegin&start=$var_start&";
$_SESSION["sess_abackreplyurl"]="";
$_SESSION["sess_ticketbackurl"] = $var_back;
//execute the new query with the appended SQL bit returned by the function
$sql = $sql.$navigate[0];
//echo "sql==$sql";
//$rs = mysql_query($sql,$conn);
$var_time=time();
$cntr = 1;
$count=0;
$rs = executeSelect($sql,$conn);

// it is for next and previous ticket
	$startvalue=0;
	if($begin=="" and $numBegin==""){
		$startvalue=0;
	}else if($begin==""){
		$startvalue=(($numBegin-1)*10);
	}else{
		$startvalue=$begin;
	}
	$newcount=0;
/////

while($row = mysql_fetch_array($rs)) {
    $limitstart=$startvalue+$newcount;
	
	$lastanswerd=TEXT_LA;
		if($row['nStaffId']>0){
		  $lastanswerd=TEXT_LAS;
		}else if($row['rpuserid']>0){
		  $lastanswerd=TEXT_LAU;
		}

                $Viewersarray = explode(',',$row["vViewers"]);
                if(in_array($var_staffid, $Viewersarray)){
                    $viewedClass = "class='readTK'";
                }else{
                    $viewedClass = "class='unreadTK'";
                }
?>

                                              <tr align="left"   <?php echo $viewedClass; ?>>
											  <td align="center">
													<input type="checkbox" name="chkDeleteTickets[]" id="chkDeleteTickets<?php echo($cntr);?>" value="<?php echo($row["nTicketId"]); ?>">
												</td>
                                                                                                <td align="center">
                                                                <img class="imgFollow" name="imgFollow[]" id="<?php echo($row["nTicketId"]); ?>"   border="0" src="./../images/star-grey.png">
                                                         </td>  
											  	<?php                                                                                        
                                                                                                for($i=0;$i < count($fld_arr);$i++) {
                                                switch($fld_arr[$i][0]) {
													 case "vPriority":
																for($j=0;$j < count($fld_prio);$j++) {
													 				//echo($fld_prio[$j][0] . " and " . $row[($fld_arr[$i][0])] . " and " . $fld_prio[$j][2]);
																	if ($fld_prio[$j][0] == $row[($fld_arr[$i][0])]) {
																		echo ("<td align=\"center\"><table width=\"70%\"><tr><td bgcolor=" . $fld_prio[$j][1] . " align='center'><a href=\"viewticket.php?limitval=$limitstart&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" . $fld_prio[$j][2] . "</a></td></tr></table></td>");
																	}
																}
													 		break;
													 case "dPostDate":
														 echo "<td width=\"12%\"><a href=\"viewticket.php?limitval=$limitstart&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" . date("m-d-Y",strtotime($row[($fld_arr[$i][0])])) . "</a></td>";
														 break;
													 case "nLockStatus":
													 		 echo "<td width=\"4%\" align=\"center\"><a href=\"viewticket.php?limitval=$limitstart&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>".(($row[($fld_arr[$i][0])] == "1")?TEXT_LOCK_YES:TEXT_LOCK_NO) . "</a></td>";
															 break;
													case "vRefNo":
                                                        //echo "<td width='1%' align=center style=\"word-break:break-all;\"><span  id=\"link".$count."\"  onMouseOver=\"displayAd($count," . $row["nTicketId"] . ");\" onMouseOut=\"hideAd();\" ><a href=\"viewticket.php?mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'><img src='./../images/ticketdetails.gif' border=0></span></a></td>";
                                                                                                            echo "<td width='1%'  style=\"word-break:break-all;\" align=center><a id=\"".$row["nTicketId"]."x".$row["nUserId"]."\" href=\"#\" class='tooltip'><img src='./../images/ticketdetails.gif' border=0></a></td>";
													  	echo "<td width='13%' style=\"word-break:break-all;\"><a href=\"viewticket.php?limitval=$limitstart&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" .  htmlentities($row[($fld_arr[$i][0])]) ."<br>".$lastanswerd."</a></td>";
														break;
													case "vTitle":
													  	echo "<td width=48% style=\"word-break:break-all;\"><a href=\"viewticket.php?limitval=$limitstart&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>".  htmlentities($row[($fld_arr[$i][0])]) ."<br>".$arrayDeptName[$row["nDeptId"]].  "</a></td>";
														break;
													  default:
													  	echo "<td width=10%><a href=\"viewticket.php?limitval=$limitstart&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" .  ucfirst(htmlentities($row[($fld_arr[$i][0])])) . "</td>";
														break;
												 }
												  ?>
												<?php
												 }
												?>
												<td align="center" width="6%">
													<?php
														//First parameter is the response time for the department which is taken from the array
														//populated with deptId -->  response time
														//Second parameter is the time stamp same for all the ten records shown in this page
														//Third parameter is the ticket post date.
														if($row["vStatus"] != "closed") {
															echo("<a href=\"viewticket.php?limitval=$limitstart&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>". getResponseTime($arrayDept[$row["nDeptId"]],$var_time,strtotime($row["dLastAttempted"])) . "</a>");
														}
													?>
													</td>

                                            </tr>
<?php
$cntr++;
$count++;
$newcount++;
}
mysql_free_result($rs);
?>
                                              <tr align="left"  class="listingmaintext">
											  	<td colspan="<?php echo $cnt+2; ?>" width="100%" class="subtbl">
													
													
											<div class="content_search_container">
						<div class="left rightmargin topmargin">
						<?php echo TEXT_ACTION_TICKETS; ?>
						</div>
						
						<div class="left rightmargin">
						 <select name="cmbAction" class="comm_input input_width1" >
																															<option value="">Select Action</option>
																															<option value="delete"><?php echo TEXT_DELTE_TICKETS; ?></option>
																															<option value="spam"><?php echo TEXT_MARKS_AS_SPAM_TICKETS; ?></option>
																													  </select>		
						</div>
						
						<div class="left rightmargin topmargin">
						<?php echo TEXT_CHANGESTATUS_TICKETS;  ?>
						</div>
						
						<div class="left">
						<select name="cmbStatus" class="comm_input input_width1">
                                                                                                                        <option value="">Select Status</option>
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
                                                                                                                </select>
						</div>
						
						<div class="left rightmargin topmargin">
						<?php echo TEXT_CHANGELABEL_TICKETS;  ?>
						</div>
						
						<div class="left">
						  <select name="cmbLabel" class="comm_input input_width1">
																														<option value="0">Select Label</option>
																														<?php
																																$sql = "Select nLabelId,vLabelname from sptbl_labels  where nStaffId='$var_staffid'";
																																$rs = executeSelect($sql,$conn);
																																if (mysql_num_rows($rs) > 0) {
																																		while($row = mysql_fetch_array($rs)) {
																																		 echo("<option value=\"" . $row["nLabelId"] . "\">" . htmlentities($row["vLabelname"]) . "</option>");
																																		}
																																}
																																mysql_free_result($rs);
																														?>
																												</select>&nbsp;&nbsp;
						</div>
						
						<div class="left topmargin">
						<a href="javascript:clickUpdate(0);"  class="listing" style="text-decoration:none;"><img src='./../languages/<?php echo $_SESSION['sess_language'];?>/images/go.gif' border=0></a>
						</div>
														
					<div class="clear"></div>
					</div>

											
													
													<table cellpadding="0" cellspacing="0" border="0" width="100%">
														
														<tr align="left" class="listingmaintext">
															<td  align="left"><?php echo($navigate[1] ."&nbsp;".TEXT_OF."&nbsp;".$totalrows."&nbsp;" .TEXT_RESULTS ); ?>
															</td>
															<td align="right">
															<?php echo($navigate[2]); ?>
															  <input type="hidden" name="numBegin" value="<?php echo   $var_numBegin?>">
															  <input type="hidden" name="start" value="<?php echo   $var_start?>">
															  <input type="hidden" name="begin" value="<?php echo   $var_begin?>">
															  <input type="hidden" name="num" value="<?php echo   $var_num?>">
															   <input type="hidden" name="mt" value="y">
															   <input type="hidden" name="del" value="">
															   <input type="hidden" name="labelup" value="">
															   <input type="hidden" name="postback" value="">
															  <input type="hidden" name="id" value="">
														   </td>
														</tr>
													</table>
												</td>
                                             </tr>
                                          </table>

                

</form>

</div>
<script>
<!--
	var cmp = '<?php echo($var_company); ?>';
	var dept = '<?php echo($var_department); ?>';
	var status = '<?php echo(addslashes($var_status)); ?>';
	var owner = '<?php echo(addslashes($var_owner)); ?>';
	var user = '<?php echo(addslashes($var_user)); ?>';
	var ticketno = '<?php echo(addslashes($var_ticketno)); ?>';
	var title = '<?php echo(addslashes($var_title)); ?>';
	var email = '<?php echo(addslashes($var_email)); ?>';

	var label = '<?php echo(addslashes($var_label)); ?>';	
	var qst = '<?php echo(addslashes($var_question)); ?>';
	var from = '<?php echo($var_from); ?>';
	var to = '<?php echo($var_to); ?>';

	var cop = '<?php echo($var_compop); ?>';
	var dop = '<?php echo($var_deptop); ?>';
	var dlp = '<?php echo($var_deptlp); ?>';
	var sop = '<?php echo($var_statusop); ?>';
	var slp = '<?php echo($var_statuslp); ?>';
	var oop = '<?php echo($var_ownerop); ?>';
	var olp = '<?php echo($var_ownerlp); ?>';
	var qop = '<?php echo($var_qstop); ?>';
	var qlp = '<?php echo($var_qstlp); ?>';
	var uop = '<?php echo($var_userop); ?>';
	var ulp = '<?php echo($var_userlp); ?>';
	var tkop = '<?php echo($var_tktop); ?>';
	var tklp = '<?php echo($var_tktlp); ?>';
	var top = '<?php echo($var_titleop); ?>';
	var tlp = '<?php echo($var_titlelp); ?>';
	/*Newly Added on 280709 starts*/
	var eop = '<?php echo($var_emailop); ?>';
	var elp = '<?php echo($var_emaillp); ?>';
	/*Newly Added on 280709 ends*/
	var lop = '<?php echo($var_labelop); ?>';
	var llp = '<?php echo($var_labellp); ?>';
 -->
</script>
