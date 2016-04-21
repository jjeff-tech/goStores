<?php

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

if($_GET["mt"] == "y") {
        $var_numBegin = $_GET["numBegin"];
        $var_start = $_GET["start"];
        $var_begin = $_GET["begin"];
        $var_num = $_GET["num"];
		$var_type = $_GET["tp"];
		$var_stylename = $_GET["stylename"];
		$var_styleminus = $_GET["styleminus"];
		$var_styleplus = $_GET["styleplus"];
}
elseif($_POST["mt"] == "y") {
        $var_numBegin = $_POST["numBegin"];
        $var_start = $_POST["start"];
        $var_begin = $_POST["begin"];
        $var_num = $_POST["num"];
		$var_type = $_POST["tp"];
		$var_stylename = $_POST["stylename"];
		$var_styleminus = $_POST["styleminus"];
		$var_styleplus = $_POST["styleplus"];
}

if(isset($_GET["id"])) {
        $var_id = $_GET["id"];
}
elseif(isset($_POST["label_id"])) {
        $var_id = $_POST["label_id"];
}


if(isset($_GET["label"])) {
        $var_label_name = $_GET["label"];
}
elseif(isset($_POST["label_name"])) {
        $var_label_name = $_POST["label_name"];
}

if(isset($_POST["label"])) {
        $var_label_name = $_POST["label"];
}


if($_POST["cmbDepartment"] != ""){
		$var_deptid = $_POST["cmbDepartment"];
}else if($_GET["cmbDepartment"] != ""){
		$var_deptid = $_GET["cmbDepartment"];
}

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

	mysql_free_result($rs_dept);
*/
//End Of Block - I
$arrayDept=array();
$arrayDeptName=array();
$sql = "Select nDeptId,nResponseTime,vDeptDesc from sptbl_depts";
$result = mysql_query($sql) or die(mysql_error());
if(mysql_num_rows($result) > 0) {
	while($row = mysql_fetch_array($result)) {
		$arrayDept[$row["nDeptId"]] = $row["nResponseTime"];
        $arrayDeptName[$row["nDeptId"]]=TEXT_DEPT1.$row["vDeptDesc"].TEXT_DEPT2;
		///$arrayDept[$row["nDeptId"]['name']]=TEXT_DEPT1.$row["vDeptDesc"].TEXT_DEPT2;
		//echo "sss<br>".$row["nDeptId"].$arrayDept[$row["nDeptId"]['name']];
	}
}

//echo "name==".$arrayDeptName[1];

//Delete ticket section
$var_list = "";
if($_POST["del"] == "DM") {
	for($i=0;$i<count($_POST["chkDeleteTickets"]);$i++) {
		$var_list .= mysql_real_escape_string($_POST["chkDeleteTickets"][$i]) . ",";
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
		$var_list .= mysql_real_escape_string($_POST["chkDeleteTickets"][$i]) . ",";
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
					values('','" . $val . "','".mysql_real_escape_string($row_spam['vTitle'])."','" . mysql_real_escape_string($var_message_main). "',now(),
					'" . mysql_real_escape_string($var_machineip) . "')";
				 executeQuery($sql,$conn);			 
		   }
    }
    $message="";
    deleteChecked($var_list,$message);
   	$message =  $updatedtickets . MESSAGE_RECORD_MOVED_SUCCESSFULLY."<br>";
        $flag_msg    = 'class="msg_success"';
}


if($_POST["del"] == "UP") {
    $frm_status=$_POST['cmbStatus'];
    $updatedtickets=0;

    for($i=0;$i<count($_POST["chkDeleteTickets"]);$i++) {


					        $var_ticketid=mysql_real_escape_string($_POST["chkDeleteTickets"][$i]);
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

						    $sql = "Select * from sptbl_tickets where nTicketId='" . mysql_real_escape_string($var_ticketid) . "'";
						    $rs = executeSelect($sql, $conn);
						    if (mysql_num_rows($rs) > 0) {
						        $row = mysql_fetch_array($rs);
						        $mail_refno = $row["vRefNo"];
						        $mail_title = $row["vTitle"];
						        $mail_status = $row["vStatus"];
						        $update_flag = true;

						        if ($update_flag == true) {
						            $updatedtickets++;
						            $sql = "Update sptbl_tickets set  vStatus='" . mysql_real_escape_string($frm_status) . "'  Where nTicketId='" . mysql_real_escape_string($var_ticketid) . "' ";
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
						            $var_message = MESSAGE_RECORD_UPDATED;
                                                            $flag_msg    = 'class="msg_success"';
						        }
						    }
						    mysql_free_result($rs);
	}
	$notupdate=$i-$updatedtickets;
	if($updatedtickets >0){
    	$message = $updatedtickets .  " ticket/s updated successfully.<br>";
        $flag_msg    = 'class="msg_success"';
    }
    if($notupdate>0){
    $message .=  $notupdate . " of the selected ticket/s cannot be updated!.";
    $flag_msg    = 'class="msg_error"';
    }
}
//

// to update label
if($_POST["labelup"] == "LABELUP") { 
    $frm_label=$_POST['cmbLabel'];
    $updatedtickets=0;

    for($i=0;$i<count($_POST["chkDeleteTickets"]);$i++) {
			$var_ticketid=mysql_real_escape_string($_POST["chkDeleteTickets"][$i]);

			$updatedtickets++;
			$sql = "Update sptbl_tickets set  nLabelId='" . mysql_real_escape_string($frm_label) . "'  Where nTicketId='" . mysql_real_escape_string($var_ticketid) . "' ";
			executeQuery($sql, $conn);
			// Insert the actionlog
			if (logActivity()) {
				$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Tickets','$var_ticketid',now())";
				executeQuery($sql, $conn);
			}
			$var_message = MESSAGE_RECORD_UPDATED;
                        $flag_msg    = 'class="msg_success"';
	}
			$notupdate=$i-$updatedtickets;
	
	if($updatedtickets >0){
    	$message =  $updatedtickets . MESSAGE_RECORD_MOVED_SUCCESSFULLY."<br>";
        $flag_msg    = 'class="msg_success"';
    }
    if($notupdate>0){
	    $message .=  $notupdate . MESSAGE_RECORD_CANNOT_MOVED ;
            $flag_msg    = 'class="msg_error"';
    }
}
//end label update

$sql = "Select t.*,rp.nStaffId,rp.nUserId as rpuserid from sptbl_tickets t left join sptbl_replies rp on (rp.dDate=t.dLastAttempted and rp.nTicketId=t.nTicketId)";
$sql .= " where t.vDelStatus='0' AND t.nLabelId='$var_id'";
if ($var_type == "o") {
	$sql .= " AND t.vStatus='open' ";
}
elseif ($var_type == "c") {
	$sql .= " AND t.vStatus='closed' ";
}
elseif ($var_type == "e") {
	$sql .= " AND t.vStatus='escalated' ";
}
$qryopt="";

if($var_deptid != ""){
		$qryopt .= " AND t.nDeptId = '" . mysql_real_escape_string($var_deptid) . "' ";
}

//$var_back="companies.php?mt=ybegin=" . $var_begin . "&num=" . $var_num . "&numBegin=" . $var_numBegin . "&start=$var_start&cmbSearch=" . $var_cmbSearch . "&txtSearch=" . urlencode($var_search) . "&";
//$_SESSION["backurl"] = $sess_back;
$_SESSION["sess_abackreplyurl"]="";
$var_back="./tickets.php?mt=y&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&tp=$var_type&begin=" . $var_begin . "&num=" . $var_num . "&numBegin=" . $var_numBegin . "&start=$var_start&cmbSearch=" . $var_cmbSearch . "&txtSearch=" . urlencode($var_search) . "&cmbDepartment=" . urlencode($var_deptid) . "&";
$_SESSION["sess_ticketbackurl"] = $var_back;

//////////////////////////// for sorting
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
//////////////////////////////////
$sql .= $qryopt . " Order By t." . $var_orderby . " ".$var_sorttype;


$var_time=time();
?>

<form name="frmDetail" action="<?php echo($_SERVER["REQUEST_URI"]); ?>" method="post">
<table width="100%"  border="0" cellspacing="10" cellpadding="0">

            <tr>
              <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                  <tr>
                    <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
                  </tr>
                </table>
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="1"  ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                      <td class="pagecolor"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                         <tr>
                            <td colspan="5" align="CENTER">
							 <select name="cmbDepartment" class="textbox" onChange="javascript:changeDepartment();">
                              <option value=""><?php echo TEXT_DEPARTMENT_FILTER ?></option>
                              <?php
							  	$sql2 = "Select d.nDeptId,CONCAT(CONCAT(CONCAT('[',d.vDeptCode),']'),CONCAT(d.vDeptDesc,CONCAT(CONCAT('(',c.vCompName),')'))) as 'description' from
								sptbl_depts d  inner join sptbl_companies c
								 on d.nCompId = c.nCompId  ";
								$lst_dept_opt = "";
								$rs_dept = executeSelect($sql2,$conn);
								if (mysql_num_rows($rs_dept) > 0) {
									while($row = mysql_fetch_array($rs_dept)) {
										$lst_dept_opt .= "<option value=\"" . $row["nDeptId"] . "\" >" . htmlentities($row["description"]) . "</option>";
									}
								}
								echo($lst_dept_opt);
							  ?>
                            </select></td>
                          </tr>

						  <tr>
                            <td width="93%" class="heading" align="left"><?php echo HEADING_LABELED_TICKETS." ( ".$var_label_name." )"; ?></td>
                          </tr>
                        </table>
                          <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="whitebasic">
							<tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="0">

                                                                            <tr>
                                          <td class="bodycolor" >

                                               <table width="100%"  border="0" cellpadding="2" cellspacing="1" class="column1">
												<?php
													if($_POST["del"] == "DM" or $_POST["del"] == "UP" or $_POST["labelup"] == "LABELUP" or $message !="") {
														echo("<tr><td align=\"center\"  ".$flag_msg." colspan=11>" . $message . "</td></tr>");
													}
												?>
												<tr align="left"  class="whitebasic">
												<td width="3%" align="center"><input name="checkall" id="checkall" type=checkbox class=checkbox onclick="checkallfn()">
											   </td>
                                               <td width="4%" class="linktext" style="text-decoration:none;">&nbsp;</td>									   
                                                <?php
													$cnt = 0;
												while($cnt < count($fld_arr)) {
												 if($var_orderby == $fld_arr[$cnt][0]) 
													$img_path = "<img src=./../images/".$var_filename.">";
												 else 
													$img_path = "";
												 ?>
												<td style="text-decoration:none;"><?php echo "<b><a href='?val=".$fld_arr[$cnt][0]."&sorttype=".$var_sorttype."&tp=".$var_type."&id=$var_id&label=$var_label_name&cmbDepartment=".$var_deptid."&numBegin=$var_numBegin&start=$var_start&begin=$var_begin&num=$var_num&mt=y&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus' class=listing>".constant($fld_arr[$cnt][1])."</a></b>&nbsp;&nbsp;".$img_path; ?></td>
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
$navigate = pageBrowser($totalrows,10,$var_maxposts,"&id=$var_id&label=$var_label_name&val=$var_orderby&sorttype=$var_sorttype&pagenum=yes&mt=y&tp=$var_type&cmbDepartment=$var_deptid&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus&",$var_numBegin,$var_start,$var_begin,$var_num);

//execute the new query with the appended SQL bit returned by the function
$sql = $sql.$navigate[0];
//echo "sql==$sql";
//echo $sql;
//echo "<br>".time();
//$rs = mysql_query($sql,$conn);
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
?>

                                              <tr align="left"  class="listingmaintext" >
											  <td align="center"  width="3%">
													<input type="checkbox" name="chkDeleteTickets[]" id="chkDeleteTickets<?php echo($cntr);?>" value="<?php echo($row["nTicketId"]); ?>" class="checkbox">
												</td>
											  	<?php for($i=0;$i < count($fld_arr);$i++) {
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
														 echo "<td width='12%'><a href=\"viewticket.php?limitval=$limitstart&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" . date("m-d-Y",strtotime($row[($fld_arr[$i][0])])) . "</a></td>";
														 break;
													 case "nLockStatus":
													 		 echo "<td width='4%' align=\"center\"><a href=\"viewticket.php?limitval=$limitstart&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" . (($row[($fld_arr[$i][0])] == "1")?TEXT_LOCK_YES:TEXT_LOCK_NO) . "</a></td>";
															 break;
													case "vRefNo":
                                                        echo "<td width='1%'  style=\"word-break:break-all;\" align=center><span  id=\"link".$count."\"  onMouseOver=\"displayAd($count," . $row["nTicketId"] . ");\" onMouseOut=\"hideAd();\" ><a href=\"viewticket.php?mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'><img src='./../images/ticketdetails.gif' border=0></span></a></td>";
													  	echo "<td width='13%'><a href=\"viewticket.php?limitval=$limitstart&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" .  htmlentities($row[($fld_arr[$i][0])]) ."<br>".$lastanswerd . "</a></td>";
														break;
													case "vTitle":
													  	echo "<td width=48% style=\"word-break:break-all;\"><div style=\"overflow:hidden;\"><a href=\"viewticket.php?limitval=$limitstart&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" .  htmlentities($row[($fld_arr[$i][0])]) .	"<br>".$arrayDeptName[$row["nDeptId"]]. "</a></div></td>";
														break;
													  default:
													  	echo "<td width=10%><a href=\"viewticket.php?limitval=$limitstart&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" .  htmlentities($row[($fld_arr[$i][0])]). "</a></td>";
														break;
												 }
												  ?>
												<?php
												 }
												?>
												<td align="center"  width="6%">
													<?php

														//First parameter is the response time for the department which is taken from the array
														//populated with deptId -->  response time
														//Second parameter is the time stamp same for all the ten records shown in this page
														//Third parameter is the ticket post date.
														if($row["vStatus"] != "closed") {
															echo("<a href=\"viewticket.php?limitval=$limitstart&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" . getResponseTime($arrayDept[$row["nDeptId"]],$var_time,strtotime($row["dLastAttempted"])) . "</a>");
														}
													?>
													</td>

                                            </tr>
<?php
$count++;
$cntr++;
$newcount++;
}
mysql_free_result($rs);
?>
                                              <tr align="left"  class="listing">
											  	<td colspan="<?php echo $cnt+1; ?>" width="100%">
													<table  cellpadding="0" cellspacing="0" border="0" width="100%"  class="listing">
														<tr class="listing">
														<td colspan="2">
																											 <table width="54%" border="0" align="center" class="column1" cellpadding="0" cellspacing="1">
																											   <tr class="listing">
																											     <td width="26%" class="listing"><?php echo "<b>".TEXT_ACTION_TICKETS."</b>"; ?></td>
																											     <td width="28%" align=left class="listing"><?php echo "<b>".TEXT_CHANGESTATUS_TICKETS."</b>"; ?></td>
																											     <td width="28%" class="listing"><?php echo "<b>".TEXT_CHANGELABEL_TICKETS."</b>"; ?></td>
																												 <td width="18%" rowspan="2" align="center"><a href="javascript:clickUpdate(0);"  class="listing" style="text-decoration:none;"><img src='./../languages/<?php echo $_SESSION['sess_language'];?>/images/go.gif' border=0></a></td>
																											   </tr>
																											   <tr class="listing">
																											     <td>
																												    <select name="cmbAction" class="combobox" style="width:110px; ">
                                                                                                                        <option value=""><?php echo TEXT_SELECTACTION; ?></option>
																														<option value="delete"><?php echo TEXT_DELTE_TICKETS; ?></option>
																														<option value="spam"><?php echo TEXT_MARKS_AS_SPAM_TICKETS; ?></option>
																												    </select>		
																											     </td>																											   
																												 <td align=left class="listingmaintext">
																												  <select name="cmbStatus" class="combobox" style="width:110px; ">
                                                                                                                        <option value=""><?php echo TEXT_SELECTSTATUS; ?></option>
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
																												 </td>
																												 <td align=left>
																												  <select name="cmbLabel" class="combobox" style="width:110px;">
																													    <option value="0"><?php echo TEXT_SELECTLABEL; ?></option>
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
																												</select>
																												 </td>
																											   </tr>																											   
																												 
														  </table>
														  </td>

														</tr>
														<tr align="left" class="listing">
															<td width="40%"><?php echo($navigate[1] ."&nbsp;".TEXT_OF."&nbsp;".$totalrows."&nbsp;" .TEXT_RESULTS ); ?></td>
															<td><?php echo($navigate[2]); ?>
																  <input type="hidden" name="numBegin" value="<?php echo   $var_numBegin?>">
																  <input type="hidden" name="start" value="<?php echo   $var_start?>">
																  <input type="hidden" name="begin" value="<?php echo   $var_begin?>">
																  <input type="hidden" name="num" value="<?php echo   $var_num?>">
																   <input type="hidden" name="mt" value="y">
																   <input type="hidden" name="tp" value="<?php echo($var_type); ?>">
																   <input type="hidden" name="postback" value="">
																  <input type="hidden" name="id" value="">
																  <input type="hidden" name="del" value="">
																  <input type="hidden" name="labelup" value="">
																  <input type="hidden" name="label_id" value="<?php echo $var_id; ?>">
																  <input type="hidden" name="label_name" value="<?php echo $var_label_name; ?>">
																  
																   <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
																   <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
																   <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
															</td>
														</tr>
													</table>
												</td>
                                             </tr>




                                          </table></td>
                                        </tr>
                                    </table></td>
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

</form>