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
		t.nLockStatus,t.vMachineIp,t.vStaffLogin,d.vDeptDesc,a.nAttachId,vAttachReference,vAttachUrl 
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
												<td colspan="2" width="40%" style="word-break:break-all; ">
													&nbsp;&nbsp;<?php echo TEXT_USER.": ".htmlentities($row["vUserName"]); ?>
												</td>
												<td  width="30%" >
													<?php echo date("m-d-Y H:i",strtotime($row["dPostDate"])); ?>
												</td>
												<td>
													<?php echo $row["vMachineIP"]; ?>
												</td>
											</tr>
										
                                           <tr align="left"  class="listingmaintext">
												<td colspan="4" width="100%" style="word-break:break-all; ">
													<br><b>Title : <?php echo stripslashes($row["vTitle"]); ?></b><br>&nbsp;
												</td>
									       </tr>	
                                           <tr>
                                            <td colspan="4" class="whitebasic" >

                                               <table width="100%"  border="0" cellpadding="0" cellspacing="3"  >
												<tr align="left"  class="listingmaintext">
													<td colspan="4" width="10%" style="word-break:break-all;">
														<?php echo stripslashes($row["tQuestion"]); ?>
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
												<td colspan="4">
													<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="attachband">
														<tr align="center">
														  <td colspan="4"><?php 
															  echo("<a href=\"javascript:var lg=window.open('../attachments/" . addslashes($row["vAttachUrl"]) . "');\" class=attachband>". htmlentities($row["vAttachReference"]) . "</a>");
															  while($row = mysql_fetch_array($rs)) {
																echo("," . "<a href=\"javascript:var lg=window.open('../attachments/" . addslashes($row["vAttachUrl"]) . "');\" class=attachband>". htmlentities($row["vAttachReference"]) . "</a>");
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
											<tr align="left"  class="headinginner3">
												<td colspan="2" width="40%" style="word-break:break-all; ">
													<?php 
														if ($row["nStaffId"] != "") {
															echo(htmlentities($row["vStaffLogin"]));
															$var_last_replier = $row["vStaffLogin"];
														}
														elseif ($row["nUserId"] != "") {
															echo(htmlentities($var_username));
														}
													?>
												</td>
												<td  width="30%" >
													<?php 
														$var_last_update = date("m-d-Y H:i",strtotime($row["dDate"]));
													echo $var_last_update; ?>
												</td>
												<td>
													<?php echo $row["vMachineIP"]; ?>
												</td>
											</tr>
											<tr>
												<td colspan="4">
													<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="blackbg">
														<tr align="center" class="ticketdetail1">
														<?php 
															if ($showflag == true) {
														?>
														  <td width="57%" align="left">&nbsp;</td>
														  <td width="14%" align="center"><a href="replies.php?rp=r&tk=<?php echo($var_ticketid); ?>&rid=<?php echo($row["nReplyId"]); ?>&&stylename=<?php echo($var_stylename); ?>&styleminus=<?php echo($var_styleminus); ?>&styleplus=<?php echo($var_styleplus); ?>&">reply</a></td>
														  <td width="18%" align="center"><a href="replies.php?rp=q&tk=<?php echo($var_ticketid); ?>&rid=<?php echo($row["nReplyId"]); ?>&&stylename=<?php echo($var_stylename); ?>&styleminus=<?php echo($var_styleminus); ?>&styleplus=<?php echo($var_styleplus); ?>&">quote &amp; reply</a> </td>
														  <td width="11%" align="center">comment</td>
														 <?php 
														 }
														 else{
														 ?>
														  <td width="57%" align="left">&nbsp;</td>
														  <td width="14%" align="center">&nbsp;</td>
														  <td width="18%" align="center">&nbsp;</td>
														  <td width="11%" align="center">&nbsp;</td>
														 <?php
														 }
														 ?> 
														</tr>
												  </table>
												</td>
											</tr>
                                            <tr>
                                            <td colspan="4" class="whitebasic" >

                                               <table width="100%"  border="0" cellpadding="0" cellspacing="3"  >
												<tr align="left"  class="listingmaintext">
													<td colspan="4" width="10%" style="word-break:break-all;">
														<?php echo stripslashes($row["tReply"]); ?>
													</td>
										      </tr>								  
                                              <tr align="left" >
                                                <td colspan="4" class="listingmaintext">&nbsp;</td>
                                              </tr>
											<?php 
												if ($var_staffid == $row["nStaffId"] && trim($row["tPvtMessage"]) != "") {
											?>
											<tr><td colspan=4 bgcolor=red >Comments</td></tr>
											<tr><td colspan=4 style="word-break:break-all;" class="fieldnames"><?php echo nl2br(htmlentities($row["tPvtMessage"])); ?></td></tr>					
											 <?php
														
												 }
											?> 						

                                              

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
												<td colspan="4">
													<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="attachband">
														<tr align="center">
														  <td colspan="4" style="word-break:break-all;"><?php 
															  echo("<a href=\"javascript:var lg=window.open('../attachments/" . addslashes($row["vAttachUrl"]) . "');\" class=attachband>". htmlentities($row["vAttachReference"]) . "</a>");
															  while($row = mysql_fetch_array($rs)) {
																if ($row["nReplyId"] == $chk_id) {
																	echo("," . "<a href=\"javascript:var lg=window.open('../attachments/" . addslashes($row["vAttachUrl"]) . "');\" class=attachband>". htmlentities($row["vAttachReference"]) . "</a>");
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
