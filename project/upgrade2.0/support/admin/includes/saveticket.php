<?php
		$var_staffid = $_SESSION["sess_staffid"];
        $var_posttopostgap="2";
		$var_post_flag=true;
	    //Modification on September 29, 2005
		$var_username="";
		$var_email="";
		$var_compid="";
        $sql = "Select * from sptbl_lookup where vLookUpName IN('Post2PostGap','MailFromName','MailFromMail','MailReplyName','MailReplyMail','Emailfooter','Emailheader','HelpdeskTitle','LoginURL','MessageRule')";
		$result = executeSelect($sql,$conn);
		if(mysql_num_rows($result) > 0) {
			while($row = mysql_fetch_array($result)) {
				switch($row["vLookUpName"]) {
					case "MailFromName":
								$var_fromName = $row["vLookUpValue"];
								break;
					case "MailFromMail":
								$var_fromMail = $row["vLookUpValue"];
								break;
					case "MailReplyName":
								$var_replyName = $row["vLookUpValue"];
								break;
					case "MailReplyMail":
								$var_replyMail = $row["vLookUpValue"];
								break;
					case "Post2PostGap":
								$var_posttopostgap = $row["vLookUpValue"];
								break;
					case "Emailfooter":
							    $var_emailfooter = $row["vLookUpValue"];
							    break;	
					case "Emailheader":
								$var_emailheader = $row["vLookUpValue"];
								break;
					case "HelpdeskTitle":
								$var_helpdesktitle = $row["vLookUpValue"];
								break;
					case "LoginURL":
								$var_loginurl = $row["vLookUpValue"];
								break;															
					case "MessageRule":
								$var_messagerule = $row["vLookUpValue"];
								break;
				}
			}
		}
	   mysql_free_result($result);
	

	 //check the post to post interval ;
/*
 *	Here this query is modified dPostdate => dLastAttempted.
 *  The actual workaround would be an extra field for last update time 
 *	by user in sptbl_tickets and use that field for checking.
 *	That field should be modified when the user posts a ticket and when the user updates a ticket.  	 
     $sql ="select date_add(dPostDate,interval $var_posttopostgap MINUTE) < now() as ptop from sptbl_tickets ";
	 $sql .=" where nUserId=$var_userid order by dPostDate desc limit 0,1";
*/	 
     $sql ="select date_add(dPostDate,interval $var_posttopostgap MINUTE) < now() as ptop from sptbl_tickets ";
	 $sql .=" where nUserId=$var_userid order by dPostDate desc limit 0,1";
	
     $result = executeSelect($sql,$conn);
	 
	 if(mysql_num_rows($result)>0){
	    $row=mysql_fetch_array($result);
	    if($row['ptop']=="1")
		   $var_post_flag=true;
		else
		  $var_post_flag=false;    
	 }
	 $var_final_flag = false;
	 $var_continue_exec=true;	// variable added on October 3, 2005
	 if($var_post_flag==true){
	 
	 			//Modification on September 29, 2005
	               $sql = "Select nCompId,vUserName,vEmail from sptbl_users where nUserId='$var_userid'";
				   $result = executeSelect($sql,$conn);
				   if(mysql_num_rows($result) > 0) {
				   	$row = mysql_fetch_array($result);
					$var_compid=$row["nCompId"];
					$var_username=$row["vUserName"];
					$var_email=$row["vEmail"];
				   } 
				   mysql_free_result($result);
				   
				  $deptid=$var_deptpid;
				  $title=$var_title;
				  $qstion=$var_desc;
				  $vAttachmentfiles=$var_uploadfiles;
				  //$tempticketid=$row['nTpTicketId'];
				  $priority=$var_prty;
				   
				   if(isValidCredentials($var_userid,$deptid,$priority)) {
				   $var_final_flag = true;
				   //Modification on October 3, 2005
				   	$sql = "Select nDeptId from sptbl_depts where nDeptParent='$deptid'";
					$rs = executeSelect($sql,$conn); 
					if(mysql_num_rows($rs) > 0) {
						$var_continue_exec=false;
					}
				   //End Modification
				   
				   if($var_continue_exec == true) {
				   //get ip address
				    $varclip=getClientIP();
	             //insert into ticket
				 $sql_insert_ticket ="insert into sptbl_tickets(nTicketId,nDeptId,vRefNo,nUserId,vUserName,vTitle,tQuestion,vPriority,dPostDate,vMachineIP,dLastAttempted)";
				 $sql_insert_ticket .="values('','$deptid','1','$var_userid','".addslashes($var_username)."','".addslashes($title)."','";
				 $sql_insert_ticket .=addslashes($qstion)."','$priority',now(),'$varclip',now())";
				
				 executeQuery($sql_insert_ticket,$conn);
				 $var_insert_id = mysql_insert_id($conn);
			 	 $var_ticketid = $var_insert_id;
//update reference number
//	modified on 15-11-06 by roshith	for constatnt length ref.no.

// 'zero' added for 2 digit companyid
			    if($var_compid<10)
					$var_compid = "0".$var_compid;

				$dept_id = $deptid; // to send mail
// 'zero' added for 2 digit departmentid
			    if($deptid<10)
					$deptid = "0".$deptid;

// 'zeros' added for 4 digit userid
			    if($var_userid<10)
					$var_userid = "000".$var_userid;  // 9   0009
			    else if($var_userid<100)
					$var_userid = "00".$var_userid;  // 99   0099
			    else if($var_userid<1000)
					$var_userid = "0".$var_userid;  // 999   0999

// 'zeros' added for 5 digit ticket no
			    if($var_insert_id<10)                 // 9   00009
					$var_insert_id = "0000".$var_insert_id;
				else if($var_insert_id<100)          // 99   00099
					$var_insert_id = "000".$var_insert_id;
				else if($var_insert_id<1000)        // 999   00999
					$var_insert_id = "00".$var_insert_id;
				else if($var_insert_id<10000)      // 9999   09999
					$var_insert_id = "0".$var_insert_id;
				
			    //CompId-DeptID-UserId-nTcketId
//				$var_refno=$var_compid."-".$deptid."-".$var_userid."-".$var_insert_id;
			    $var_refno=$var_compid.$deptid.$var_userid.$var_insert_id;
			   
			    $sql_update_ticket="update sptbl_tickets set vRefNo='".$var_refno."' where nTicketId='".$var_ticketid."'" ;
			    
			    executeQuery($sql_update_ticket,$conn);			
				
    			$sql_insert_attach="insert into sptbl_attachments(nTicketId,vAttachReference,vAttachUrl) values";
				if($vAttachmentfiles !=""){
				    $vAttacharr=explode("|",$vAttachmentfiles);
				    foreach($vAttacharr as $key=>$value){
				       
				       $split_name_url=explode("*",$value);
					   $sql_insert_attach .= "('$var_ticketid','".addslashes($split_name_url[1])."','".addslashes($split_name_url[0])."'),";
				    }
				    $sql_insert_attach = substr($sql_insert_attach,0,-1);
				    executeQuery($sql_insert_attach,$conn);
				  }
                   //insert into action log
				   if(logActivity()) {
				   $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_ADDITION . "','Ticket','" . addslashes($var_ticketid) . "',now())";			
			        executeQuery($sql,$conn);
					}
				   //$sql = "Insert into sptbl_actionlog(nALId,nUserId,vAction,vArea,nRespId,dDate) Values('','$var_userid','" . TEXT_ADDITION . "','Attachments','" . addslashes($var_insert_id) . "',now())";			
			       // executeQuery($sql,$conn);
				   
				   
				 //Modified On september 30, 2005
				 //delete from tempory table
				  /*$sql="delete from sptbl_temp_tickets where nTpUserId=$var_userid";
				  executeQuery($sql,$conn);
				  */

			 /*  		 
				  //Send mail to the watcher staffs of the department 
				  //modification on SupportPRo Supportdesk3.
				  //added by roshith on 25-11-06
			 */			  
			 	 $sqlWatcher  = "select DISTINCT vMail from sptbl_staffs s inner join sptbl_staffdept sd on s.nStaffId=sd.nStaffId ";
				 $sqlWatcher .= " where ndeptid='$dept_id' and s.nWatcher=1 ";

				 $resultWatcher = executeSelect($sqlWatcher,$conn); 

				 $var_tolist="";
				 while($row = mysql_fetch_array($resultWatcher)) {
				     $var_tolist = $row["vMail"];
					 $var_staff_name = $row["vStaffname"];				 
//					$var_tolist .= "," . $row["vMail"];
//				 }

//				 $var_tolist = substr($var_tolist,1);
					 if($var_tolist != "") {
							$var_mail_body=$var_emailheader."<br>Hi,<br>";
							$var_mail_body .= "<br><br>";
							$var_mail_body .= TEXT_BEGIN_MAIL . "<br>" . TEXT_TICKET_REFERENCE_NUMBER . " : " . $var_refno .".<br><br>".TEXT_MAIL_THANK. "<br>" . htmlentities($var_helpdesktitle) . "<br><br>".$var_emailfooter;
							$var_subject = $var_refno . " - " . TEXT_EMAIL_SUB;
					 
							$var_body = $var_mail_body;
							$Headers="From: $var_fromName <$var_fromMail>\n";
							$Headers.="Reply-To: $var_replyName <$var_replyMail>\n";
							$Headers.="MIME-Version: 1.0\n";
							$Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

							// it is for smtp mail sending
							if($_SESSION["sess_smtpsettings"] == 1){
								$var_smtpserver = $_SESSION["sess_smtpserver"];
								$var_port = $_SESSION["sess_smtpport"];
					
								SMTPMail($var_fromMail,$var_tolist,$var_smtpserver,$var_port,$var_subject,$var_body);
							}
							else					                
								@mail($var_tolist,$var_subject,$var_body,$Headers);
					}
				}
		  //End Send mail			  

	  // applying message rule here
	  			if($var_messagerule == "1")
					applyMessagerule($var_ticketid);
				  
				  /*
				  *	This section is being implemented in the last section where 
				  * email is being sent to the user and all the staff of the department.
				  
				  //Send mail to all staff of the department who has their mail arrival flag set to 1
				  //modification on SupportPRo Supportdesk2.
				  $sql = "Select s.vLogin,s.vMail from sptbl_staffdept sd inner join sptbl_staffs s on
						sd.nStaffId = s.nStaffId where sd.nDeptId='" . $dept_id . "' AND s.nNotifyArrival='1'";
					$result = executeSelect($sql,$conn);
				
					$var_tolist=",";
					while($row = mysql_fetch_array($result)) {
						$var_tolist .= "," . $row["vMail"];
					}
					$var_tolist = substr($var_tolist,1);
					if($var_tolist != "") {
						$var_body = $var_emailheader ."<br>".TEXT_MAIL_START.",<br>&nbsp;<br>";
						 $var_body .= TEXT_TICKET_REPLY_BODY . " ( " . $var_refno . " ) " ;
						 $var_body .= TEXT_TICKET_REPLY_BODY2 . date("m-d-Y H:i");
						 $var_body .= TEXT_MAIL_THANK."<br>" . $var_helpdesktitle . "<br>" . $var_emailfooter;
						 $var_subject = "  [" . $var_refno . "]" . TEXT_TICKET_REPLY_SUBJECT . $var_helpdesktitle;	
						 $Headers="From: " . $var_fromMail . "\n";
						 $Headers.="Reply-To: " . $var_replyMail . "\n";
						 $Headers.="MIME-Version: 1.0\n";
						 $Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
						 @mail($var_tolist,$var_subject,$var_body,$Headers);	
					}
				  //End Send mail
				  */
				  
				  $var_message = MESSAGE_TEXT;
				  $var_message1= TEXT_TICKET_REFERENCE_NUMBER.": ".$var_refno ;
				  }
				  else {// modification on October 3, 2005
				  		//unlink the upload file
									 if($vAttachmentfiles !=""){
												$vAttacharr=explode("|",$vAttachmentfiles);
												foreach($vAttacharr as $key=>$value){
												 
												  $split_name_url=explode("*",$value);
												  @unlink("../attachments/".$split_name_url[0]);
													
												}
						 			}	
					  $var_message = MESSAGE_POST_DEPT_CHANGE;
					  $var_message1= "";
				  }
			}//end if isvalidcredentials()
			else {
				  		//unlink the upload file
									 if($vAttachmentfiles !=""){
												$vAttacharr=explode("|",$vAttachmentfiles);
												foreach($vAttacharr as $key=>$value){
												 
												  $split_name_url=explode("*",$value);
												  @unlink("../attachments/".$split_name_url[0]);
													
												}
						 			}	
					  $var_message = MESSAGE_INVALID_CREDENTIALS;
					  $var_message1= "";
			}		  
  				
	}else{
	  $var_message = MESSAGE_POST_AFTER_DELAY;
	  $var_message1= "";
	}			

  
?>
<div class="content_section">
<form name="frmDownloads" action="<?php echo   $_SERVER['PHP_SELF']?>" method="post">

                
						  <div class="content_section_title">
	<h3><?php echo HEADING_TICKET_POSTED ?></h3>
	</div>
                      <table width="100%"  border="0" cellpadding="0" cellspacing="3" class="whitebasic" >
                                              
                                              <tr><td>&nbsp;</td></tr>
											  <tr><td>&nbsp;</td></tr>
											  <tr><td>&nbsp;</td></tr>
											  <tr><td>&nbsp;</td></tr>
											  <tr><td>&nbsp;</td></tr>
                                              <tr>
											    <td align=center><b><?php echo $var_message; ?></b></td>
											  </tr>
											  <tr>
											    <td align=center><b><?php echo $var_message1; ?></b></td>
											  </tr>
                                              <tr><td>&nbsp;</td></tr>
											  <tr><td>&nbsp;</td></tr>
											  <tr><td>&nbsp;</td></tr>
											  <tr><td>&nbsp;</td></tr>
											  <tr><td>&nbsp;</td></tr>

                                          </table>

                

               
</form>
</div>
<?php
  //send mail
  //email user
      if($var_post_flag==true && $var_continue_exec == true){ 
		         //$var_email=$_SESSION["sess_useremail"];
/*								 
				 $var_mail_body=$var_emailheader."<br>".TEXT_MAIL_START."&nbsp;".addslashes($var_username).",<br>".
                 $var_mail_body .= TEXT_MAIL_BODY ."<br><br>";
                 $var_mail_body .= TEXT_TICKET_REFERENCE_NUMBER . " : " . $var_refno ."<br>".TEXT_EMAIL_THANKS."<br><br>".$var_emailfooter;
				 $var_subject = TEXT_EMAIL_SUB;
		 
				$var_body = $var_mail_body;
				$Headers="From: $var_fromName <$var_fromMail>\n";
				$Headers.="Reply-To: $var_replyName <$var_replyMail>\n";
				$Headers.="MIME-Version: 1.0\n";
				$Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
			    $mailstatus=@mail($var_email,$var_subject,$var_body,$Headers);
*/			
		//get the dept mail address that is used at the footer as well as the from/reply-to address
                            if(isAutoReturnMailNeeded()){
				$sql = "Select nDeptId,vDeptMail from sptbl_depts where nDeptId='" . addslashes($dept_id) . "'";
				$result = executeSelect($sql,$conn);
				if(mysql_num_rows($result) > 0) { 
					$row = mysql_fetch_array($result);
					 $var_body = $var_emailheader ."<br>".TEXT_MAIL_START.",<br>&nbsp;<br>";
					 $var_body .= TEXT_TICKET_CREATION_BODY1 . " ( " . htmlentities($title) . " ) " ;
					 $var_body .= TEXT_TICKET_CREATION_BODY2 . date("m-d-Y H:i");
					 $var_body .= TEXT_TICKET_CREATION_BODY3 . " [" . $var_refno ."].<br><br>";
					 $var_body .= "<a href=\"" . $var_loginurl . "?mt=y&email=" . urlencode($var_email) . "&ref=" . $var_refno . "&\">" . TEXT_CLICK_HERE . "</a> " . TEXT_VIEW_TICKET_STATUS . "<BR><BR>";
					 $var_body .= TEXT_MAIL_THANK."<br>" . htmlentities($var_helpdesktitle) . "<br>" . $var_emailfooter;
					 $var_subject = TEXT_TICKET_CREATION_SUBJECT . $var_helpdesktitle . "  Id#[" . $var_refno . "]";	
					 $Headers="From: " . $row["vDeptMail"] . "\n";
					 $Headers.="Reply-To: " . $row["vDeptMail"] . "\n";
					 $Headers.="MIME-Version: 1.0\n";
					 $Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";


                                         //*************Send mail user on ticket


                                         $useremail = getUserEmail($var_userid);
                                         if (!in_array($var_email,$useremail)) {
                                             $useremail[] = $var_email;
                                         }
                                         if(count($useremail) > 0) {


                                             foreach ($useremail as $key => $value) {
                                                 $var_email = $value;
                                                 // it is for smtp mail sending
                                                 if($_SESSION["sess_smtpsettings"] == 1) {
                                                     $var_smtpserver = $_SESSION["sess_smtpserver"];
                                                     $var_port = $_SESSION["sess_smtpport"];

                                                     SMTPMail($var_fromMail,$var_email,$var_smtpserver,$var_port,$var_subject,$var_body);
                                                 }
                                                 else
                                                     $mailstatus=@mail($var_email,$var_subject,$var_body,$Headers);

                                               
                                                 //*************Send mail user on ticket ends
                                             }//end of loop mail
                                         }//end of if array count




                                }
				}
				if($mailstatus){
				  $var_message = MESSAGE_TEXT;
				  $var_message1= TEXT_TICKET_REFERENCE_NUMBER.":".$var_refno ;
				  $var_email = "";
				  $var_body="";
				  $var_subject="";
			    }
			    else {
				 $var_message = "<font color=red>" . MESSAGE_EMAIL_NOT_SEND . "</font>";
			    }

 /***********************************************************************************
 * Commented on 5th Oct 2012 Version Supportdesk 4.2
 * Original query commenting due to twice mail sent to certain staff ##( Certain Staff in Condition : nWatcher = 1 in sptbl_staffs )
 ************************************************************************************/

                       /*$sql=" select s.nStaffId,s.vStaffname,s.vMail,s.vSMSMail,d.nDeptId from sptbl_staffs s,sptbl_staffdept d where d.nDeptId=$dept_id
                        and s.nStaffId=d.nStaffId and s.nNotifyArrival='1' and s.vDelStatus='0'";*/

                       $sql=" select s.nStaffId,s.vStaffname,s.vMail,s.vSMSMail,d.nDeptId from sptbl_staffs s,sptbl_staffdept d where d.nDeptId=$dept_id 
                            and s.nStaffId=d.nStaffId and s.nNotifyArrival='1' and s.vDelStatus='0' and s.nWatcher !=1 ";
			   
		       $rs= executeSelect($sql,$conn);
			   while($row=mysql_fetch_array($rs)){

				    if($row['vMail'] !=""){
					     $var_email=$row['vMail'];
						 $var_mail_body="";
						 $var_mail_body=$var_emailheader."<br>".TEXT_MAIL_START."&nbsp;".htmlentities($row['vStaffname']).",<br>";
                                                 $var_mail_body .= "<br><br>";
                                                 $var_mail_body .= TEXT_BEGIN_MAIL . "<br>" . TEXT_TICKET_REFERENCE_NUMBER . " : " . $var_refno ."<br>".TEXT_MAIL_THANK. "<br>" . htmlentities($var_helpdesktitle) ."<br><br>".$var_emailfooter;
						 $var_subject = $var_refno . " - " . TEXT_EMAIL_SUB;
				 
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
					}
					 if($row['vSMSMail'] !=""){
					     $var_email=$row['vSMSMail'];
						  $var_mail_body="";
						 $var_mail_body=TEXT_MAIL_START." ".htmlentities($row['vStaffname']).", ".
		                 $var_mail_body .= " ";
		                 $var_mail_body .= TEXT_TICKET_REFERENCE_NUMBER . " : " . $var_refno . "  " . TEXT_SMS_CONT  . " " . TEXT_MAIL_THANK . htmlentities($var_helpdesktitle);
						 $var_subject = $var_refno . " - " . TEXT_EMAIL_SUB;
				 
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
						    $mailstatus=@mail($var_email,"",$var_body,$Headers);
					}
			   }
  }
  
function applyMessagerule($ticket_id) {
	 global $conn;

	 $sqlTickets = "Select t.vTitle,t.tQuestion,r.* from sptbl_tickets t left join sptbl_rules r on t.nDeptId = r.nDeptId where t.nTicketId='".$ticket_id."'";
	 $resultTickets = executeSelect($sqlTickets,$conn);

	 if(mysql_num_rows($resultTickets)>0){
		$array_title = array();
		$array_question = array();
		$array_searchwords = array();
		$staffprobability=array();

		while($row = mysql_fetch_array($resultTickets)){
			$title = $row['vTitle'];
			$question = $row['tQuestion'];
	
			$rulename = $row['vRuleName'];
			$searchwords = $row['vSearchWords'];
			$staffid = $row['nStaffId'];

			$array_title = explode(" ", $title); 
			$array_question = explode(" ", $question); 
			$array_searchwords = explode(",", $searchwords);


					$totalvaluesintitle=count($array_title);
					$totalprobability_selectedrow=0;
			
					foreach($array_searchwords as $searchkey => $searchword) {
						$countof_searchword=0;

						foreach($array_title as $tilekey => $titlevalue) {
								if(strcasecmp($searchword,$titlevalue )==0){
									$countof_searchword++;
								}
						}
						$probabilityindividual=$countof_searchword/$totalvaluesintitle;
					
						$totalprobability_selectedrow=$totalprobability_selectedrow+$probabilityindividual;
					}
					$staffprobability[$staffid]=$totalprobability_selectedrow;
  		} // while loop
			
		$copy_array = $staffprobability;	
		sort($copy_array);
		$arr_count = count($staffprobability);
		$max_value = $copy_array[$arr_count-1];

		foreach($staffprobability as $key=>$value){
			if($max_value == $value && $value != 0){
				$staff_id = $key;
				$point = $value;
			}
		}		

		if($point > 0 && $staff_id != ''){
			$sql = "Update sptbl_tickets set  nOwner='".$staff_id. "' Where nTicketId='" . addslashes($ticket_id) . "' ";
			executeQuery($sql, $conn);
		}
	}
}

?>