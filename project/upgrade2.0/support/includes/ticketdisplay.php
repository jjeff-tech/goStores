<!-- Ticket Display -->									
<?php

//********************TICKET DISPLAY SECTION*********************************************************
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

       
        
$tkflag = false;	//this is to check whether there is a ticket for the request
$sql = "Select t.nTicketId,t.nDeptId,t.vUserName,t.vTitle,t.vRefNo,t.dPostDate,t.tQuestion,t.vPriority,t.vStatus,t.nOwner,
		t.nLockStatus,t.vMachineIP,t.vStaffLogin,d.vDeptDesc,a.nAttachId,vAttachReference,vAttachUrl 
		from sptbl_tickets t inner join sptbl_depts d on t.nDeptId = d.nDeptId left outer join sptbl_attachments a
		on t.nTicketId=a.nTicketId Where t.nTicketId='" . addslashes($var_tid) ."' AND t.nUserId='" . addslashes($var_userid) . "' ";

$var_username = "";
$showflag = false;  // This is to check whether the ticket belong to the department assigned 
$rs = executeSelect($sql,$conn);
if(mysql_num_rows($rs) > 0) {
	$row = mysql_fetch_array($rs); 
	$tkflag = true;
	$var_username = $row["vUserName"];
	$var_deptid = $row["nDeptId"];
	$var_department = $row["vDeptDesc"];
	$var_owner_name = $row["vStaffLogin"];
	$var_owner_id = $row["nOwner"];
	$var_created_on = $row["dPostDate"];
	$var_status = $row["vStatus"];
	$var_lock = $row["nLockStatus"];        
 
?>									
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr align="left"  class="headinginner2">
												<td colspan="2" style="word-break:break-all; ">
													<?php echo "&nbsp;&nbsp;".TEXT_USER . " : " . stripslashes($row["vUserName"]); ?>
												</td>
												<td  width="26%" >
													<?php echo TEXT_DATE . " : " . date("m-d-Y",strtotime($row["dPostDate"])); ?>
												</td>
												<td width="35%">
													<?php echo TEXT_IP . " : " . $row["vMachineIP"]; ?>
												</td>
											    <td width="2%"><br>&nbsp;</td>
											</tr>
											
										
                                           <tr align="left"  class="fieldnames">
												<td colspan="5" style="word-break:break-all; ">
                                                                                                    <br><b><?php echo   TEXT_TITLE?>: <?php echo stripslashes($row["vTitle"]); ?></b><br>&nbsp;
												</td>
									  </tr>	
                                            <tr>
                                            <td colspan="5" class="bodycolor" >

                                               <table width="100%"  border="0" cellpadding="0" cellspacing="3"  >
												<tr align="left"  class="fieldnames">
													<td colspan="4" width="10%" style="word-break:break-all;">
														<?php echo stripslashes($row["tQuestion"]); ?>
													</td>
										      </tr>								  
                                              <tr align="left" >
                                                <td colspan="4" class="listingmaintext">&nbsp;</td>
                                              </tr>
                                              
                                          </table></td>
                                        </tr>
										<?php 
											if ($row["vAttachUrl"] != "") {
										?>
										<tr>
												<td colspan="5">
													<table width="100%"  border="0" cellpadding="0" cellspacing="0">
														<tr align="center">
														  <td colspan="4"><?php 
															  echo(TEXT_ATTACHMENT . " : <a href=\"javascript:var lg=window.open('./attachments/" . addslashes($row["vAttachUrl"]) . "');\"   class=\"listing\">". htmlentities($row["vAttachReference"]) . "</a>");
															  while($row = mysql_fetch_array($rs)) {
																echo("," . "<a href=\"javascript:var lg=window.open('./attachments/" . addslashes($row["vAttachUrl"]) . "');\"   class=\"listing\">". htmlentities($row["vAttachReference"]) . "</a>");
															  } 
														  ?></td>
														</tr>
												  </table>
												</td>
									  </tr>
										<?php 
											}	
										?>	
								</table>
									
<?php

}
mysql_free_result($rs);
?>									
<!-- End Of Ticket Display -->

<!-- Reply Detail -->

<?php

//********************CORRESPONDANCE SECTION*********************************************************
$sql = "Select r.nReplyId,r.nStaffId,r.vStaffLogin,r.nUserId,r.dDate,r.vMachineIP,tReply,
		r.tPvtMessage,a.nAttachId,vAttachReference,vAttachUrl  from sptbl_replies r left outer join sptbl_attachments a on 
		r.nReplyId = a.nReplyId Where r.nTicketId='" . addslashes($var_tid) ."'  ORDER BY r.dDate ";
$rs = executeSelect($sql,$conn);
if($tkflag == true && mysql_num_rows($rs) > 0) {
	if ($row = mysql_fetch_array($rs)) { 
		$flag_main = true;
		while($flag_main == true) {
			$flag_main = false;
			$chk_id = $row["nReplyId"];
?>
										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr align="left"  class="headinginner2">
												<td colspan="2" style="word-break:break-all; ">
													<?php 
														if ($row["nStaffId"] != "") {
															$var_style = "replyband";
															echo(TEXT_STAFF . " : " .  stripslashes($row["vStaffLogin"]));
															$var_last_replier = $row["vStaffLogin"];
														}
														elseif ($row["nUserId"] != "") {
															$var_style = "ticketband";
															echo(TEXT_USER . " : " . stripslashes($var_username));
														}
													?>
												</td>
												<td  width="30%" > 
													<?php 
														$var_last_update = date("m-d-Y",strtotime($row["dDate"]));
													echo TEXT_DATE . " : " . $var_last_update; ?>
												</td>
												<td width="28%">
													<?php if($row["vStaffLogin"]==''){echo TEXT_IP . " : ". $row["vMachineIP"];} ?>
												</td>
											    <td width="2%"><br>&nbsp;</td>
											</tr>
											<tr>
												<td colspan="5">
													<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="<?php echo($var_style); ?>">
														<tr align="center">
														  <td width="57%" align="left">&nbsp;</td>
														  <td width="14%" align="center">&nbsp;</td>
														  <td width="18%" align="center">&nbsp;</td>
														  <td width="11%" align="center">&nbsp;</td>
														</tr>
												  </table>
												</td>
											</tr>
                                            <tr>
                                            <td colspan="5" class="bodycolor" >

                                               <table width="100%"  border="0" cellpadding="0" cellspacing="3"  >
												<tr align="left"  class="fieldnames">
													<td colspan="4" width="10%" style="word-break:break-all;">
														<?php echo stripslashes($row["tReply"]); ?>
													</td>
										      </tr>								  
                                              <tr align="left" >
                                                <td colspan="4" class="listingmaintext">&nbsp;</td>
                                              </tr>
											  <tr align="left"  class="listingmaintext">
                                                <td colspan="2">&nbsp;</td>
                                                <td colspan="2">&nbsp;</td>
                                             </tr>
                                          </table></td>
                                        </tr>
										
										<?php 
											if ($row["vAttachUrl"] != "") {
										?>
										<tr>
												<td colspan="5">
													<table width="100%"  border="0" cellpadding="0" cellspacing="0">
														<tr align="center">
														  <td colspan="4"  style="word-break:break-all;" class="fieldnames"><?php 
															  echo(TEXT_ATTACHMENT . " : <a href=\"javascript:var lg=window.open('./attachments/" . addslashes($row["vAttachUrl"]) . "');\"  class=\"listing\">". htmlentities($row["vAttachReference"]) . "</a>");
															  while($row = mysql_fetch_array($rs)) {
																if ($row["nReplyId"] == $chk_id) {
																	echo("," . "<a href=\"javascript:var lg=window.open('./attachments/" . addslashes($row["vAttachUrl"]) . "');\"  class=\"listing\">". htmlentities($row["vAttachReference"]) . "</a>");
																}
																else {
																	$flag_main = true;
																	break;
																}	
															  } 
														  ?></td>
														</tr>
												  </table>
												</td>
										  </tr>
										<?php 
											}
											elseif ($row = mysql_fetch_array($rs)) {
												$flag_main = true;
											}	
										?>											
                                    </table>

<?php
		}
	}
}
mysql_free_result($rs);
?>

<!-- End Of Reply Detail -->
