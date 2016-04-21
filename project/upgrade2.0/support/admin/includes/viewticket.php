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
            $flag_msg = "";
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
$arrayDeptName=array();
$sql = "Select nDeptId,nResponseTime,vDeptDesc from sptbl_depts";
$result = mysql_query($sql) or die(mysql_error());
if(mysql_num_rows($result) > 0) {
        while($row = mysql_fetch_array($result)) {
                $arrayDeptName[$row["nDeptId"]]=TEXT_DEPT1.$row["vDeptDesc"].TEXT_DEPT2;
                //$arrayDept[$row["nDeptId"]['name']]=TEXT_DEPT1.$row["vDeptDesc"].TEXT_DEPT2;
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
			$update_flag = true;  //added at the time of comment
		/*if ($row["nLockStatus"] == "1") {
			if ($row["nOwner"] != "0" && $row["nOwner"] != $var_staffid) {
				$var_message = MESSAGE_RECORD_ERROR;
			}
			else {
				$update_flag = true;
			}
		}
		if($frm_lock == "1" && $frm_ownerid != $var_staffid) {
				$update_flag = false;
				$var_message = MESSAGE_RECORD_ERROR;
		}
		else {
			$update_flag = true;
		}*/
		if ($update_flag == true) {
				$qry1 = "";
				$qry2 = "";
				if ($row["nOwner"] != $frm_ownerid || $row["nDeptId"] != $frm_deptid) {
					$sql = "Select s.vLogin,s.vStaffname,s.nNotifyAssign,s.vMail,s.vSMSMail,sd.nDeptId from sptbl_staffdept sd inner join sptbl_staffs s on
							sd.nStaffId = s.nStaffId Where sd.nStaffId='" . addslashes($frm_ownerid) . "'
							 AND sd.nDeptId='" . addslashes($frm_deptid) . "' AND s.vDelStatus='0' ";
					$rs_chk = executeSelect($sql,$conn);
					if (mysql_num_rows($rs_chk) > 0) {
						$row = mysql_fetch_array($rs_chk);
							if ($row["nNotifyAssign"] == "1") {
									//mail staff
	
									$var_email=$row['vMail'];
									//$var_email .= ((trim($row["vSMSMail"])!="")?("," . $row["vSMSMail"]):"");
								   $var_mail_body=$var_emailheader."<br>".TEXT_MAIL_START."&nbsp;". htmlentities($row["vStaffname"]) .",<br>";
								   $var_mail_body .= "<br><br>";
								   $var_mail_body .= TEXT_MAIL_BODY ."[". $mail_refno . "]" . TEXT_MAIL_BY . htmlentities($_SESSION['sess_staffname']) . "<br><br>";
								   $var_mail_body .= TEXT_MAIL_THANK."<br>". $var_helpdesktitle  . "<br>".$var_emailfooter;
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
										 $var_mail_body = TEXT_MAIL_START." ".htmlentities($row['vStaffname']).", ".
										 $var_mail_body .= TEXT_SMS1 . " : " . $mail_refno . ".  "  .TEXT_MAIL_THANK." ". htmlentities($var_helpdesktitle);

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
						}else if($frm_ownerid !='0'){  // the selected owner is 'no owner'
								$update_flag = false;
								$var_message = MESSAGE_RECORD_ASSIGNDEPT_ERROR;
                                                                    $flag_msg = "class='msg_error'";//added on October 28, 2006 by Roshith
						}
	
						if($frm_ownerid =='0')    // added on 14-11-06 by roshith for assigning 'owner' to 'no owner'
							$qry1 = ",nDeptId='" . addslashes($frm_deptid) . "',nOwner='" . addslashes($frm_ownerid) . "' ";				
				}
				if ($update_flag == true) {
						$sql = "Update sptbl_tickets set  vStatus='" . addslashes($frm_status) . "',
								nLockStatus='" . (($frm_lock == "1")?"1":0) . "'" . $qry1 . ",
                                                                    vPriority='" . (($frm_priority == "")?"0":$frm_priority) . "' Where
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
						 $var_body .= TEXT_ESCALATED_BODY ." ". $mail_refno . TEXT_MAIL_BY . htmlentities($_SESSION['sess_staffname'])  ."<br><br>";
						 $var_body .= TEXT_MAIL_THANK."<br>". htmlentities($var_helpdesktitle)  . "<br>".$var_emailfooter;
						 $var_subject = TEXT_ESCALATION_SUB;
						 $Headers="From: $var_fromName <$var_fromMail>\n";
						 $Headers.="Reply-To: $var_replyName <$var_replyMail>\n";
						 $Headers.="MIME-Version: 1.0\n";
						 $Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

						// it is for smtp mail sending
						if($_SESSION["sess_smtpsettings"] == 1){
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
                                                    //echo $frm_status;exit;
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

}else {
$_SESSION['sess_backurl'] = getPageAddress();
}
 if(isset($_GET['msg'])) {$message = MESSAGE_TICKET_REPLIED;     $flag_msg1 = "class='msg_success'";}

?>
<script>
function userdetails(uid){

var clientWindow=window.open('userdetails.php?uid='+uid,'mywindow','width=300,height=100,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=no,resizable=no,maximize=no')


}
</script>

<form name="frmDetail" action="<?php echo   $_SERVER['PHP_SELF']?>" method="post">
<input type=hidden name="delid" >
<!--  History Section -->
<div class="content_section">

<div class="exp_title">
<div class="left"><h4><?php echo HEADING_VIEW_HISTORY ?></h4></div>
<div class="exp_title_icon right"><a href="javascript:manageDetails('historymatter','historyimage')"><img id="historyimage" src="../images/arrow_down.png" border="0"></a></div>       
<div class="clear"></div>
</div>
<?php if($message){ ?>
<div <?php echo $flag_msg1;?>><?php echo $message;?></div>
<?php }
if($var_message){
?>
<div <?php echo $flag_msg; ?>> <?php echo $var_message; ?> </div>
<?php }   ?>
 

            
                          <table width="100%" id="historymatter" style="display:none;"  border="0" cellspacing="0" cellpadding="0" >
                                  <tr>
                                    <td align="right">
									
									

                                               <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="list_tbl">
												<tr>
													<th width="5%">&nbsp;</th>
													<th width="20%" align="left">
														<?php echo (TEXT_REFNO); ?>
													</th >
													<th width="45%" align="left">
														<?php echo (TEXT_TITLE); ?>
													</th>
													<th width="15%" align="left">
														<?php echo (TEXT_STATUS); ?>
													</th>
													<th width="15%" align="left">
														<?php echo (TEXT_DATE); ?>
													</th>
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

                                            <tr align="left" >
												<td width='5%' style="word-break:break-all;" align="center"><span id=link<?php echo $count ?> onMouseOver=displayAd(<?php echo $count ?>,<?php echo $row["nTicketId"] ?>); onMouseOut=hideAd();><a href="viewticket.php?mt=y&tk=<?php echo $row["nTicketId"] ?>&us=<?php echo $row["nUserId"] ?>"><img src='./../images/ticketdetails.gif' border=0></a></span></td>
												<td width="20%">
													<a href="viewticket.php?mt=y&tk=<?php echo $row["nTicketId"]; ?>&us=<?php echo($row["nUserId"]); ?>&stylename=<?php echo($var_stylename); ?>&styleminus=<?php echo($var_styleminus); ?>&styleplus=<?php echo($var_styleplus); ?>&" ><?php echo $row["vRefNo"]."<br>".$lastanswerd; ?></a>
												</td>
												<td width="45%">
													<?php
													if (strlen($row["vTitle"]) > 32) {
														echo htmlentities(substr($row["vTitle"],0,32) . "...")."<br>".$arrayDeptName[$row["nDeptId"]];
													}
													else {
														echo htmlentities($row["vTitle"])."<br>".$arrayDeptName[$row["nDeptId"]];
													}
													 ?>
												</td>
												<td width="15%">
													<?php echo htmlentities($row["vStatus"]); ?>
												</td>
												<td width="15%">
													<?php echo date("m-d-Y",strtotime($row["dPostDate"])); ?>
												</td>
                                            </tr>
<?php
$count++;
}
mysql_free_result($rs);
?>
                                              <tr align="left"  class="whitebasic">
                                                <td colspan="6">&nbsp;
												   <input type="hidden" name="mt" value="y">
												   <input type="hidden" name="tk" value="<?php echo $var_ticketid; ?>">
												   <input type="hidden" name="us" value="<?php echo $var_userid; ?>">
												   <INPUT type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
												   <INPUT type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
												   <INPUT type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
												   <input type="hidden" name="postback" value="">
												  <input type="hidden" name="id" value="">
                                                </td>
                                             </tr>
                                          </table>
									
									
									</td>
                                  </tr>
                              </table>

                  
 <!-- Properities -->
  <div class="comm_spacediv">&nbsp;</div>
  
  <div class="exp_title">
<div class="left"><h4><?php echo TEXT_PROPERTIES ?></h4></div>
<div class="exp_title_icon right"><a href="javascript:manageDetails('propertymatter','propertyimage')"><img id="propertyimage" src="../images/arrow_down.png" border="0"></a></div>       
<div class="clear"></div>
</div>
   <div class="comm_spacediv"> </div>
  
  
  


          
                      <table width="100%"  id="propertymatter" style="display:none;"   border="0" cellspacing="0" cellpadding="0" class="whitebasic">
                                  <tr>
                                    <td align="right">
									
									
									
									
										       <table width="100%"   border="0" cellpadding="0" cellspacing="0" class="list_tbl">
											  
												<tr align="left">
													<th width="5%"><?php echo(TEXT_ID); ?></th>
													<th width="15%" align="left"><?php echo(TEXT_OWNER); ?></th>
                                                                                                        <th width="15%" align="left"><?php echo(TEXT_DEPARTMENT); ?></th>
                                                                                                        <th width="10%" align="left"><?php echo(TXT_PRIORITY); ?></th>
                                                                                                        <th width="10%" align="left"><?php echo(TEXT_STATUS); ?></th>
                                                                                                        <th width="10%" align="left"><?php echo(TEXT_LOCK); ?></th>
												</tr>
												<tr align="left">
													<td  height="27">
                                                                                                    <?php echo($var_ticketid); ?> </td>
												  <td   height="27">
														<select name="cmbOwner" class="comm_input" style="width:80px; ">
															<option value="0">--No Owner--</option>
															<?php
																//$sql = "Select nStaffId,vLogin from sptbl_staffs where vDelStatus='0' ";
																$sql = "Select distinct s.nStaffId,s.vLogin from sptbl_tickets t inner join sptbl_depts d
																		on t.nDeptId = d.nDeptId inner join sptbl_depts d1 on
																		d.nCompId = d1.nCompId inner join sptbl_staffdept sd on
																		d1.nDeptId = sd.nDeptId inner join sptbl_staffs s on
																		sd.nStaffId = s.nStaffId where t.nTicketId='" . addslashes($var_ticketid) . "'  Order by vLogin";
																$rs = executeSelect($sql,$conn);
																if (mysql_num_rows($rs) > 0) {
																	while($row = mysql_fetch_array($rs)) {
																	 echo("<option value=\"" . $row["nStaffId"] . "\">" . htmlentities($row["vLogin"]) . "</option>");
																	}
																}
																mysql_free_result($rs);
															?>
														</select>

												  </td>
                                                                                                    <td   height="27"><select name="cmbDepartment" class="comm_input" style="width:100px;">
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
																		d.nCompId = d1.nCompId  where  d1.nDeptId IN($leaflvldeptids) Order by vDeptDesc";
																$rs = executeSelect($sql,$conn);
																if (mysql_num_rows($rs) > 0) {
																	while($row = mysql_fetch_array($rs)) {
																	 echo("<option value=\"" . $row["nDeptId"] . "\">" . htmlentities($row["vDeptDesc"]) . "</option>");
																	}
																}
																mysql_free_result($rs);
															?>
                                                                                                            </select></td>
                                                                                                            <td   height="27"><?php
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
                                                                                                    <td   height="27"><select name="cmbStatus" class="comm_input">
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
                                                                                            <td   height="27"><select name="cmbLock" class="comm_input" >
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
											  <tr align="left"  class="listing">
													<td colspan="6" align="center"><br><input type="button" class="comm_btn" name="btUpdate" id="btUpdate" value="<?php echo(BUTTON_TEXT_UPDATE); ?>" onClick="javascript:clickUpdate();">													<br>&nbsp;
													</td>
										      </tr>
                                           </table>
									
									
									</td>
                                  </tr>
                              </table>
                



<!-- End of Properities -->
<!-- End Of History Section -->



<!-- TWO PART Section -->

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="50%" valign="top" class="exp_title_v_column">

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
<div class="left"><h4><?php echo TEXT_PERSONAL. "&nbsp;$var_note_count"; ?></h4></div>
<div class="exp_title_icon right"><a href="javascript:manageDetails('personalmatter','personalimage')"><img id="personalimage" src="../images/arrow_down.png" border="0"></a></div>       
<div class="clear"></div>
</div>


       <div style="width:100%;">

                 
                         <table width="100%" border="0" cellspacing="0" cellpadding="0" class="whitebasic" id="personalmatter" style="display:none;">
                                    <tr>
                                      <td class="whitebasic" width="100%">
									  
									  <table width="100%"  border="0" cellpadding="0" cellspacing="0" style="border:1px solid #cfcfcf; " >
                                          
                                          <tr>
                                            <td colspan="3" width="100%" height="37" align="center">
											
											
												  
												  
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
																	<tr align="left"  class="listingmaintext">
																		<td width="25%" valign="top">
																		<?php
																		/*
																		if($row["nStaffId"] == $_SESSION["sess_staffid"]){
																			$viewurl = "editnote.php";
																		}else{
																			$viewurl = "viewnote.php";
																		}
																		*
																		*/
																		?>
																		<a href="editnote.php?mt=y&tk=<?php echo($var_ticketid); ?>&us=<?php echo($var_userid); ?>&staffid=<?php echo($var_staffid); ?>&id=<?php echo($row["nPNId"]); ?>&stylename=<?php echo($var_stylename); ?>&styleminus=<?php echo($var_styleminus); ?>&styleplus=<?php echo($var_styleplus); ?>&"><?php echo htmlentities($row["vStaffLogin"]); ?></a>
																			<!--<a href="viewpersonal.php?mt=y&id=<?php echo($row["nPNId"]); ?>&"><?php echo $row["vStaffLogin"]; ?></a>-->
																		</td>
																		<td width="52%" valign="top"  style="word-break:break-all;"><div style="overflow:hidden;">
																			<?php echo htmlentities($row["vPNTitle"]); ?></div>
																		</td>
																		<td width="23%" valign="top">
																			<?php echo date("m-d-Y",strtotime($row["dDate"])); ?>
																		</td>
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
                                           
                                          </tr>
                                        </table>
										
										
										
										
										</td>
                                    </tr>
                                  </table>

              





<!-- End Of Personal Notes -->






	</td>
	<td width="50%" valign="top">
<?php
	$sql = "Select * from sptbl_feedback Where nTicketId='" . addslashes($var_ticketid) ."'  Order By dDate DESC";
	$rs = executeSelect($sql,$conn);

	if(mysql_num_rows($rs)>0)
		$var_feedback_count = "(".mysql_num_rows($rs).")";
	else
		$var_feedback_count = ""; 
?>
<!-- User Feedback -->

<div class="exp_title">
<div class="left"><h4><?php echo HEADING_VIEW_FEEDBACK."&nbsp;$var_feedback_count"; ?></h4></div>
<div class="exp_title_icon right"><a href="javascript:manageDetails('feedbackmatter','feedbackimage')"><img id="feedbackimage" src="../images/arrow_down.png" border="0"></a></div>       
<div class="clear"></div>
</div>



                         <table width="100%"  border="0"  id="feedbackmatter" style="display:none;"  cellspacing="0" cellpadding="0">
                              <tr>
                                <td align="right">
								
								
								<table width="100%"  border="0" cellpadding="0" cellspacing="0" style="border:1px solid #cfcfcf;" >
                                          
                                         
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
                                                        <td width="75%" valign="top" align="left"><a href="<?php echo   $viewurl?>?mt=y&tk=<?php echo($var_ticketid); ?>&us=<?php echo($var_userid); ?>&id=<?php echo($row["nFBId"]); ?>&stylename=<?php echo($var_stylename); ?>&styleminus=<?php echo($var_styleminus); ?>&styleplus=<?php echo($var_styleplus); ?>&"><?php echo htmlentities($row["vFBTitle"]); ?></a>
																		 <td width="25%" valign="top" align="left">
																			<?php echo date("m-d-Y",strtotime($row["dDate"])); ?>
																		</td>
                                                      </tr>
																	
<?php

}
mysql_free_result($rs);
?></table>
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
</tr>
</table>
<div class="comm_spacediv"> </div>
<!-- End Of TWO PART Section -->


			
                  
				<?php
					  $sqlRefNo = "Select vRefNo from sptbl_tickets where nTicketId=" . addslashes($var_ticketid);
					  $rsRefNo = executeSelect($sqlRefNo,$conn);
						if (mysql_num_rows($rsRefNo) > 0) {
							while ($rowRefNo = mysql_fetch_array($rsRefNo))
								$varrefno = $rowRefNo['vRefNo'];
						}
				?>
				
				<div class="content_section_title"><h3><?php echo TEXT_TICKET_DETAIL." (".$varrefno.")"; ?></h3></div>
				
					   
                         
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

	/*
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
	*/
	$showflag=true;  //for admin showflag is set to true
/*	if($row["vStatus"] != "closed") {
		$var_close_url = "<a href=\"viewticket.php?mt=y&cl=y&tk=" . $var_ticketid . "&stylename=" . $var_stylename . "&styleminus=" . $var_styleminus . "&styleplus=" . $var_styleplus . "&\" class=\"linktext\">" . TEXT_CLOSE_TICKET . "</a>";
	}*/

	$sql = "Select t.nTicketId,t.vTitle,t.tQuestion,t.dPostDate,t.vMachineIp,
			r.nReplyId,r.nStaffId,r.nUserId,r.vStaffLogin,r.dDate,r.tReply,r.tPvtMessage,r.vMachineIp as 'ReplyIp',r.nHold,t.vViewers
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

                        }//end if in array

                    }else{

                        $sql_updateView = "Update sptbl_tickets Set vViewers = '".addslashes($var_staffid)."' Where nTicketId='" . addslashes($var_ticketid)  . "'";
                        executeQuery($sql_updateView,$conn);

                    }//end else

                }//end if

                $i++;
	}//end while
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
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
			<td  width="16%" style="word-break:break-all; " align="left" valign="top">
					<b><?php echo TEXT_USER;?></b></td>
					<td align="left" valign="top"><?php echo ":<a href=\"javascript:userdetails('$var_userdetailid')\">&nbsp;" . htmlentities($var_username)."</a>"; ?></td>
		</tr>
		<tr>
					<td   align="left" valign="top">
					<b><?php echo TEXT_DATE ;?></b></td>
					<td align="left" valign="top"><?php echo  " :&nbsp;" . date("m-d-Y H:i",strtotime($row["dPostDate"])).""; ?></td>
			</tr>
		<tr>
			<td  align="left" valign="top"><b><?php  echo TEXT_IP ;?></b></td>
					<td align="left" valign="top"><?php echo " :&nbsp; " . $row["vMachineIp"]."";?></td>
			</tr>
		
	</table>
	
	</div>
	<div class="right">
           
	<?php
		if ($showflag == true) {
	?>
	<a href="replies.php?rp=r&tk=<?php echo($var_ticketid); ?>&rid=0&stylename=<?php echo($var_stylename); ?>&styleminus=<?php echo($var_styleminus); ?>&styleplus=<?php echo($var_styleplus); ?>&" class="comm_link1"><?php echo(TEXT_REPLY); ?></a>
	<a href="replies.php?rp=q&tk=<?php echo($var_ticketid); ?>&rid=0&stylename=<?php echo($var_stylename); ?>&styleminus=<?php echo($var_styleminus); ?>&styleplus=<?php echo($var_styleplus); ?>&" class="comm_link1"><?php echo(TEXT_QUOTE_REPLY); ?></a>
	<a href="javascript:deleteTickets('<?php echo($var_ticketid); ?>');" class="comm_link1"><?php echo TEXT_MAIN_DELETE1 ?></a>
	<?php
			}
	?>  
	</div>
<div class="clear"></div>
</div>
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
	
	
<tr align="left">
<td  width="15%" style="word-break:break-all;">
<b><?php echo   TEXT_TITLE?></b>

</td>
<td align="left"> : &nbsp;<b><?php echo htmlentities($row["vTitle"]); ?></b></td>
					  </tr>
<tr>
    <td valign="top"><b> <?php echo TICKET_DESCRIPTION;?> </b></td>
<td align="left" valign="top">

<!--word-break:break-all-->
															 : &nbsp;<?php echo stripslashes($row["tQuestion"]); ?>
													</td>
								  </tr>




</table>


									  <?php
											 echo(getAttachment($var_ticketid,""));
									  ?>
									
					
</div>
</div>

<?php
		} //end if ticket section
		else { //else correspondance section
		if ($row["nStaffId"] != "") {
			$var_style = "ticket_conv_staff";
			$var_styletitle = "comm_tbl2";
			}
		elseif ($row["nUserId"] != "") {
			$var_style = "ticket_conv_user";
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
                              <td  width="16%" style="word-break:break-all; "><b><?php echo TEXT_STAFF;?></b></td>
							  <td><?php echo  " : " .  htmlentities($row["vStaffLogin"]) ."";?></td>
						</tr>
							<?php
					}
					elseif ($row["nUserId"] != "") {
					
							$var_style = "ticketband";
							?>
							<tr>
                              <td style="word-break:break-all; "><b><?php echo TEXT_USER;?></b></td>
							  <td><?php echo  " :" .  htmlentities($var_username) ."";?></td>
						</tr>
							<?php
						}
				?>
<tr>
                              <td style="word-break:break-all; "><b><?php echo TEXT_DATE ;?></b></td>
							  <td><?php echo  " :" .  date("m-d-Y H:i",strtotime($row["dDate"])) ."</span></b>";?></td>
						</tr>
						<tr>
                              <td style="word-break:break-all; "><b><?php echo TEXT_IP ."" ;?></b></td>
							  <td><?php echo  " :" .  $row["ReplyIp"];?></td>
						</tr>
						</table>


</div>


<div class="right">
    <?php if($row['nHold']==1){ ?>
        <a href="replies.php?rp=r&tk=<?php echo($var_ticketid); ?>&rid=0&stylename=<?php echo($var_stylename); ?>&styleminus=<?php echo($var_styleminus); ?>&styleplus=<?php echo($var_styleplus); ?>&nHold=<?php echo $row['nHold']?>&nReplyId=<?php echo $row['nReplyId'];?>" class="comm_link1"><?php echo 'Post Hold'; ?></a>
<?php } ?>
        <?php
							if ($showflag == true && $row['nHold']==0) {
					?>
					  <a href="replies.php?rp=r&tk=<?php echo($var_ticketid); ?>&rid=<?php echo($row["nReplyId"]); ?>&stylename=<?php echo($var_stylename); ?>&styleminus=<?php echo($var_styleminus); ?>&styleplus=<?php echo($var_styleplus); ?>&" class="comm_link1"><?php echo(TEXT_REPLY); ?></a>
					  <a href="replies.php?rp=q&tk=<?php echo($var_ticketid); ?>&rid=<?php echo($row["nReplyId"]); ?>&stylename=<?php echo($var_stylename); ?>&styleminus=<?php echo($var_styleminus); ?>&styleplus=<?php echo($var_styleplus); ?>&" class="comm_link1"><?php echo(TEXT_QUOTE_REPLY); ?></a>
					  <?php
					 }
					 
					 ?>

</div>
<div class="clear"></div>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">

<tr>
<td colspan="4" class="bodycolor" >

<table width="100%"  border="0" cellpadding="0" cellspacing="3"  >
	<tr align="left" >
			<td colspan="4" width="10%" style="word-break:break-all;">
					<?php echo stripslashes($row["tReply"]); ?>
			</td>
</tr>

<?php
		//$var_staffid == $row["nStaffId"] &&   Condition removed for admin
		if (trim($row["tPvtMessage"]) != "") {
?>
<tr ><td colspan=4  align="left">Comments</td></tr>
<tr><td colspan=4 align="left" style="word-break:break-all;" ><?php echo nl2br(stripslashes($row["tPvtMessage"])); ?></td></tr>
 <?php

		 }
?>




</table></td>
</tr>

<?php
//if ($row["vAttachUrl"] != "") {
?>
<tr>
	<td colspan="4">
	<?php
							 echo(getAttachment("",$row["nReplyId"]));
					  ?>
			<!--<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="content_sub_box">
					<tr align="center">
					  <td colspan="4"></td>
					</tr>
	  </table>-->
	</td>
</tr>
<?php
/*}
elseif ($row = mysql_fetch_array($rs)) {
	$flag_main = true;
}*/
?>

</table>
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
			while($row = mysql_fetch_array($rs_attach)) {
				if($row["nTicketId"] != "") {
					if($row["nTicketId"] == $var_ticketid) {
						$var_return .= "," . " <a href=\"javascript:var lg=window.open('../attachments/" . addslashes($row["vAttachUrl"]) . "');\" class=attachband>". htmlentities($row["vAttachReference"]) . "</a>";
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
	}
	else {
		return "";
	}
	return (($var_return != "")?TEXT_ATTACHMENTS . " : " . substr($var_return,1):"");
}
?>
<!-- END OF Updated ticket/correspondance display section -->

									
							  
							  
							  
							  </div>
							 

                  
  
  
  
  
           <div class="content_section_data" align="center">
				  <?php
				  if($var_next_limitvalue>0){
					  $var_previous_limitvalue = $var_next_limitvalue-1;
		
					  $sqlprevious = $_SESSION['next_sql']." LIMIT ".$var_previous_limitvalue." ,1";
//						echo "<br>sql=".$sqlprevious;
					  $rsprevious = executeSelect($sqlprevious,$conn);
					  						  
					  while($row = mysql_fetch_array($rsprevious)) {
						 $previousticketid =  $row['nTicketId'];
						 $previoususerid   =  $row['nUserId'];
					  }

                                         	 
		
					  if(mysql_num_rows($rsprevious)>0) {
//					  echo "viewticket.php?limitval=$var_previous_limitvalue&mt=y&tk=$previousticketid&us=$userid&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1";
?>
							<input type="button" class="comm_btn" name="btPrevious" id="btPrevious" value="<?php echo(TEXT_MAIN_PREVIOUS);?>" onClick="javascript:clickPrevious();">  
					 <?php } 
				}
                                            $var_next_limitvalue = $var_next_limitvalue+1;

					  $sqlnext = $_SESSION['next_sql']." LIMIT ".$var_next_limitvalue." ,1";
		//				echo "<br>sql=".$sqlnext;
					  $rsnext = executeSelect($sqlnext,$conn);

					  while($row = mysql_fetch_array($rsnext)) {
						 $nextticketid =  $row['nTicketId'];
						 $nextuserid   =  $row['nUserId'];
					  }

                                ?>
                                        &nbsp;&nbsp;&nbsp;
							<input type="button" class="comm_btn" name="btReply" id="btReply" value="<?php echo(TEXT_REPLY); ?>" onClick="javascript:clickReply();">					  
						   &nbsp;&nbsp;&nbsp;
							<input type="button" class="comm_btn" name="btDelete" id="btDelete" value="<?php echo(TEXT_MAIN_DELETE); ?>" onClick="javascript:deleteTickets('<?php echo($var_ticketid); ?>');">
						   &nbsp;&nbsp;&nbsp;
                        <?php
                        if(mysql_num_rows($rsnext)>0) {?>
			  		<input type="button" class="comm_btn" name="btNextReply" id="btNextReply" value="<?php echo(TEXT_REPLY_NEXT); ?>" onClick="javascript:clickReplyNext();">
			 <?php } ?>
                                        &nbsp;&nbsp;&nbsp;
							<input type="button" class="comm_btn" name="btBack" id="btBack" value="<?php echo(TEXT_MAIN_BACK); ?>" onClick="javascript:clickBack();">
						   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <?php
					  
		
					  if(mysql_num_rows($rsnext)>0) {?>
							<input type="button" class="comm_btn" name="btNext" id="btNext" value="<?php echo(TEXT_MAIN_NEXT);?>" onClick="javascript:clickNext();"></a>			  
					 <?php } ?>
					 
                  
		  <input type="hidden" name="numBegin" value="<?php echo   $var_numBegin ?>">
		  <input type="hidden" name="start" value="<?php echo   $var_start ?>">
		  <input type="hidden" name="begin" value="<?php echo   $var_begin ?>">
		  <input type="hidden" name="num" value="<?php echo   $var_num ?>">
</div>
</div>
<script>
	var own = '<?php echo($var_owner_id); ?>';
	var dept = '<?php echo($var_deptid); ?>';
	var ctd = '<?php echo(date("m-d-Y H:i",strtotime($var_created_on))); ?>';
	var st = '<?php echo($var_status); ?>';
	var lstupdate = '<?php echo($var_last_update); ?>';
	var lstreplier = '<?php echo($var_last_replier); ?>';
	var lck = '<?php echo($var_lock); ?>';
</script>

<?php 
// added on 1-11-06 by roshith
	if ($_GET["tk"] != "") 
		$var_tid = $_GET["tk"];

                $backurl='viewticket.php?limitval=0&mt=y&tk='.$var_tid.'&us=2&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1';
	
                //$backurl="tickets.php?mt=y&tk=".$var_tid."&tp=o&us=".$var_userid."&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&msg=replied";
                $_SESSION["sess_backurl_reply_success"] = $backurl;
?>
</form>
</div>
<form name=frmBack  action="<?php echo $_SESSION['sess_ticketbackurl'];?>" method=post >
</form>

<form name=frmReply  id="frmReply" action="<?php echo "replies.php?rp=r&tk=$var_ticketid&rid=0&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus&limitval=$var_next_limitvalue";?>" method=post >
</form>

<form name=frmPrevious action="<?php echo "viewticket.php?limitval=$var_previous_limitvalue&mt=y&tk=$previousticketid&us=$previoususerid&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1";?>" method=post >
</form>

<form name=frmNext action="<?php echo "viewticket.php?limitval=$var_next_limitvalue&mt=y&tk=$nextticketid&us=$nextuserid&val=$var_orderby&sorttype=$var_sorttype&pagenum=yes&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1";?>" method=post >
</form>

<script language="javascript">
// added on 2-11-06 by roshith
function clickReply() // function called when clicking 'reply' button
{
	document.frmReply.submit();
}
function clickPrevious() // function called when clicking 'previous' button
{
	document.frmPrevious.submit();
}
function clickNext() // function called when clicking 'next' button
{
	document.frmNext.submit();
}
function clickBack()  // function called when clicking 'back' button
{ 
	//submit the back form to prevent httpheader loss and redirection to admin main
	document.frmBack.submit();
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
</script>