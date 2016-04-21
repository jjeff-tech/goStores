<script language="javascript1.1">
	heavyImage0 = new Image(); 
	heavyImage0.src = "../images/reply1.gif";
	heavyImage1 = new Image(); 
	heavyImage1.src = "../images/personal1.gif";
	heavyImage2 = new Image(); 
	heavyImage2.src = "../images/action1.gif";
	heavyImage3 = new Image(); 
	heavyImage3.src = "../images/attachments1.gif";
	heavyImage4 = new Image(); 
	heavyImage4.src = "../images/knowledge1.gif";
		
	heavyImage5 = new Image(); 
	heavyImage5.src = "../images/reply.gif";
	heavyImage6 = new Image(); 
	heavyImage6.src = "../images/personal.gif";
	heavyImage7 = new Image(); 
	heavyImage7.src = "../images/action.gif";
	heavyImage8 = new Image(); 
	heavyImage8.src = "../images/attachments.gif";
	heavyImage9 = new Image(); 
	heavyImage9.src = "../images/knowledge.gif";
	
	function resetimage(kb){ //alert(kb);
//	alert(document.getElementById("replytab").src);
			document.getElementById("replytab").src='../images/reply.gif';
//	alert(document.getElementById("replytab").src);
			document.getElementById("personaltab").src='../images/personal.gif';
//			document.getElementById("actiontab").src='../images/action.gif';
			document.getElementById("attachtab").src='../images/attachments.gif';
			document.getElementById("knowledgetab").src='../images/knowledge.gif';
			
	}

	function resettd(kb){ 
			document.getElementById("td1").style.display="none";
			document.getElementById("td2").style.display="none";
			document.getElementById("td4").style.display="none";
			document.getElementById("td5").style.display="none";
			document.getElementById("td6").style.display="none";
			document.getElementById("td3").style.display="none";
			
	}

</script>

<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>    		                      |
// |          									                          |
// +----------------------------------------------------------------------+

//  rp,tk,rid
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

include_once("../includes/MIME.class");
	if ($_GET["stylename"] != "") {
		$var_styleminus = $_GET["styleminus"];
		$var_stylename = $_GET["stylename"];
		$var_styleplus = $_GET["styleplus"];
	}
	else {
		$var_styleminus = $_POST["styleminus"];
		$var_stylename = $_POST["stylename"];
		$var_styleplus = $_POST["styleplus"];
	}
	if ($_GET["rp"] != "") {
	    $var_rp = $_GET["rp"];
		$var_tid = $_GET["tk"];
		$var_rid = $_GET["rid"];
	}
	elseif ($_POST["rp"] != "") {
	    $var_rp = $_POST["rp"];
		$var_tid = $_POST["tk"];
		$var_rid = $_POST["rid"];
	}

	if(!isset($_POST['count']))
		$count = 0;
	else
		$count = $_POST['count'];										

	if(!isset($_POST['varrefresh']))
		$var = "";
	else
		$var = $_POST['varrefresh'];
	
//		echo "<br>var:: ".$var;
//		echo "<br>ses_test:: ".$_SESSION['ses_test'];
		

if ($_POST["postback"] == "CT") {
      $var_userid=$_POST['userid'];
     $var_tmplate_id=$_POST['cmbTemplate'];
	 $var_tickettitle=$_POST['tickettitle'];
	  $var_tqusetion=$_POST["tquestion"];
	 $var_refno=$_POST['refno'];
	 $var_replymatter=$_POST['txtRpMatter'];

	 $var_ereplymatter=$_POST['txtRpMatterE'];
	 $var_pntitle =$_POST['txtPnTitle'];
	 $var_pnmatter=$_POST['txtPnMatter'];
	 $var_addtokb=$_POST['chkaddtokb'];
	 $var_category=$_POST['cmbCategory'];
	 $var_deptid=$_POST['txtDeptId'];
	 $var_status=$_POST['cmbStatus'];
	 $var_tkowner=$_POST['chktkowner'];
	 $var_ntuser=$_POST['chkntuser'];
	 $var_timespent=$_POST['txtTimeSpent'];
	 $var_cc=$_POST['txtCC'];
	 $var_pvtmessage=$_POST['txtRpPvtMesssage'];
	 $var_lock=$_POST['chklock'];
	 $var_uploaded_files=$_POST['uploadedfiles'];
//	 echo "<br>varuploadfiles(1)=".$var_uploaded_files;

     $sql="select vTemplateTitle,tTemplateDesc from sptbl_templates where vStatus='1' and nTemplateId='".mysql_real_escape_string($var_tmplate_id)."'";
	 $result = executeSelect($sql,$conn);
		if (mysql_num_rows($result) > 0) {
			$var_row = mysql_fetch_array($result);
			$var_templatedesc = $var_row["tTemplateDesc"];
			$var_templatetitle = $var_row["vTemplateTitle"];
			$var_replymatter = "------$var_templatetitle------\n".$var_templatedesc."\n\n\n".$var_replymatter;
		}
  }else if ($_POST["postback"] == "S") {

      //Reply And Next Action settings 
      if(isset($_POST['txtNext']) && $_POST['txtNext']=='1' )
                                     {
                                                                                  
                                                    $sess_next_sql = $_SESSION['next_sql'];

                                                    $var_next_limitvalue = $_POST['txtLimit'];

                                                   $sqlnext = $sess_next_sql." LIMIT ".$var_next_limitvalue." ,1";
                            //		  	  echo $sqlnext;
                                                      $rsnext = executeSelect($sqlnext,$conn);
                                                   if(mysql_num_rows($rsnext)>0) {

                                                      while($row = mysql_fetch_array($rsnext)) {
                                                             $nextticketid =  $row['nTicketId'];
                                                             $nextuserid   =  $row['nUserId'];
                                                      }
                                                        $_SESSION['sess_backurl_reply_success'] ='';
                                                        $_SESSION['sess_backurl_reply_success']="viewticket.php?limitval=$var_next_limitvalue&mt=y&tk=$nextticketid&us=$nextuserid&val=$var_orderby&sorttype=$var_sorttype&pagenum=yes&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1'";
                                                   }


                                        
                                     }
    global $curr_status,$mail_refno;
         $var_userid=$_POST['userid'];
         $var_tickettitle=$_POST['tickettitle'];
		  $var_tqusetion=$_POST["tquestion"];
		 $var_refno=$_POST['refno'];
		 $var_tmplate_id=$_POST['cmbTemplate'];
		 $var_replymatter=$_POST['txtRpMatter'];

		 $var_ereplymatter=$_POST['txtRpMatterE'];
		 $var_pntitle =$_POST['txtPnTitle'];
		 $var_pnmatter=$_POST['txtPnMatter'];
		 $var_addtokb=$_POST['chkaddtokb'];
		 $var_category=$_POST['cmbCategory'];
		 $var_deptid=$_POST['txtDeptId'];
		 $var_status=$_POST['cmbStatus'];
		 $var_tkowner=$_POST['chktkowner'];
		 $var_ntuser=$_POST['chkntuser'];
		 $var_timespent=$_POST['txtTimeSpent'];
		 $var_pvtmessage=$_POST['txtRpPvtMesssage'];
		 $var_lock=$_POST['chklock'];
		 $var_cc=$_POST['txtCC'];
		 $var_uploaded_files=$_POST['uploadedfiles'];

		 $validsave=validateSave($var_tid);
/*
//		 echo "validsave".$validsave;
//		 exit;
echo $_POST['blockrefresh'];
exit;
		  if($_POST['blockrefresh']=="1"){
		    $var_message="Reply already sent";
		    require("./includes/replied.php");
		    exit;
		  }
*/		 if($validsave =="1"){

		     //insert into personal notes
				  if(trim($var_pntitle)!="" and trim($var_pnmatter)!=""){
				   $sql ="insert into sptbl_personalnotes(nPNId,nStaffId,nTicketId,vStaffLogin,";
				   $sql .="vPNTitle,dDate,tPNDesc) values('','".mysql_real_escape_string($_SESSION["sess_staffid"])."',";
				   $sql .="'".mysql_real_escape_string($var_tid)."','".mysql_real_escape_string($_SESSION["sess_staffname"])."',";
				   $sql .="'".mysql_real_escape_string($var_pntitle )."',now(),'".mysql_real_escape_string($var_pnmatter)."')";
				   executeQuery($sql,$conn);
				   $var_insert_id = mysql_insert_id($conn);
				   if(logActivity()) {
					   $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','" . $_SESSION["sess_staffid"] . "','" . TEXT_ADDITION . "','Personal Notes','" . mysql_real_escape_string($var_insert_id) . "',now())";
					   executeQuery($sql,$conn);
				   }
				 }
				  //add to knowledge base

				 if($var_addtokb=="atokb" and $var_category >0){
				    if(isKBApprovalNeeded()){
					  $kb_flag="I";
					}else{
					   $kb_flag="A";
					}
					$var_replymatter_new_quest=$var_tqusetion."\n\n".$var_replymatter;
					//$var_replymatter= $var_tqusetion
				    $sql  =  "Insert into sptbl_kb(nKBID,nCatId, nStaffId, vKBTitle," ;
					$sql .= " tKBDesc, dDate, vStatus";
					$sql .= ") Values('','" . mysql_real_escape_string($var_category) . "','" . mysql_real_escape_string($_SESSION["sess_staffid"]) . "','" . mysql_real_escape_string($var_tickettitle) . "',";
					$sql .= "'". mysql_real_escape_string($var_replymatter_new_quest) ."',now(), '$kb_flag')";

					executeQuery($sql,$conn);
					$var_insert_id = mysql_insert_id($conn);
					updateCount($var_catid,"+");

					//Insert the actionlog
					if(logActivity()) {
						$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','" . $_SESSION["sess_staffid"] . "','" . TEXT_ADDITION . "','Knowledgebase','" . mysql_real_escape_string($var_insert_id) . "',now())";
						executeQuery($sql,$conn);
					}
				 }
				  //update ticket fileds
				               $sql = " Select * from sptbl_lookup where vLookUpName IN('Post2PostGap','MailFromName','MailFromMail',";
							   $sql .="'MailReplyName','MailReplyMail','Emailfooter','Emailheader','AutoLock','MailEscalation','HelpdeskTitle')";
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
											case "Emailfooter":
															$var_emailfooter = $row["vLookUpValue"];
															break;
											case "Emailheader":
															$var_emailheader = $row["vLookUpValue"];
															break;
											case "AutoLock":
															$var_autoclock = $row["vLookUpValue"];
															break;
											case "MailEscalation":
															$var_emailescalation = $row["vLookUpValue"];
															break;
											case "HelpdeskTitle":
															$var_helpdesktitle = $row["vLookUpValue"];
															break;
										}
									}
								}
							   mysql_free_result($result);

				      if($curr_status !="escalated" and $var_status=="escalated"){
							 $var_body = $var_emailheader."<br>".TEXT_MAIL_START."&nbsp; Admin,<br>";
							 $var_body .= TEXT_ESCALATED_BODY ." ". $mail_refno. TEXT_MAIL_BY . htmlentities($_SESSION['sess_staffname']) ."<br><br>";
							 $var_body .= TEXT_MAIL_THANK . "<br>" . htmlentities($var_helpdesktitle)  ."<br>".$var_emailfooter;
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
					  }

                                           if($curr_status !="closed" and $var_status=="closed"){
                                               sendMailUserTicketClose($mail_refno,$var_helpdesktitle);
                                           }

					  $now=date("Y-m-d H:i:s");
					 $sql="update sptbl_tickets set vStaffLogin='".mysql_real_escape_string($_SESSION["sess_staffname"])."',
					 vStatus='".mysql_real_escape_string($var_status)."',
					 dLastAttempted=now()";
					 $qry_options="";
					 if($var_autoclock=="1"){
					   $qry_options= ",nLockStatus='1',nOwner='".mysql_real_escape_string($_SESSION["sess_staffid"])."'";
					 }else if($var_lock=="lock"){
					 	  $qry_options= ",nLockStatus='1',nOwner='".mysql_real_escape_string($_SESSION["sess_staffid"])."'";
					 }else if($var_tkowner=="tkowner"){
					    $qry_options= ",nOwner='".mysql_real_escape_string($_SESSION["sess_staffid"])."'";
					 }

					 $sql .=$qry_options." where nTicketId='".mysql_real_escape_string($var_tid)."'";

					 executeQuery($sql,$conn);
					   if(logActivity()) {
						   $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','" . $_SESSION["sess_staffid"] . "','" . TEXT_UPDATION . "','Ticket','" . mysql_real_escape_string($var_tid) . "',now())";
						   executeQuery($sql,$conn);
					   }

		       //send mail to user
					 if($var_ntuser =="ntuser"){

		 			 }
				     if($var_cc !="" or  $var_ntuser =="ntuser"){
					 		//Get department details for the ticket id here
					 			$sql = "Select t.vRefNo,t.vTitle,d.vDeptMail,u.vLogin,u.vEmail from sptbl_tickets t inner join
										sptbl_depts d on t.nDeptId=d.nDeptId inner join sptbl_users u on t.nUserId=u.nUserId
										  where  t.nTicketId='" . mysql_real_escape_string($var_tid) . "'";

					 		//End Get department details for the ticket id here

					           //$sql="select vLogin,vEmail from sptbl_users where nUserId='$var_userid'";
							   $result = executeSelect($sql,$conn);
							   $row = mysql_fetch_array($result);
							   $var_email=$row['vEmail'];
							   $var_ulogin=$row['vLogin'];
							   $var_mail_body=$var_emailheader."<br>".TEXT_MAIL_START."&nbsp;".htmlentities($var_ulogin).",<br>".
							   $var_mail_body .= TEXT_MAIL_BODY .":". $var_refno ."<br><br>";
							   $var_mail_body .= nl2br(htmlentities($var_replymatter))."<br>".$var_emailfooter;
							   //$var_subject = TEXT_EMAIL_SUB;
							   $var_subject = "Re:" . $row["vTitle"] . "  Id#[" . $row["vRefNo"] . "]";
							   $var_body = $var_mail_body;
							   //$Headers="From: $var_fromName <$var_fromMail>\n";
							   //$Headers.="Reply-To: $var_replyName <$var_replyMail>\n";
							   $arr_header = array("Reply-To: " . $row["vDeptMail"]);
							   if($var_cc !=""){
							      // $Headers.="Bcc: $var_cc\r\n";
								  $arr_header[] = "Bcc: $var_cc";
							   }

						       //$Headers.="MIME-Version: 1.0\n";
							   //$Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
							   //$mailstatus=@mail($var_email,$var_subject,$var_body,$Headers);
							    $mime = new MIME_mail($row["vDeptMail"], $var_email, $var_subject, $var_body, $arr_header);
							   //$mime->fattach($fname, "Resume of $name", $type);
//							   echo "<br>varuploadfiles(3)==".$var_uploaded_files;
							   if($var_uploaded_files !=""){
					   			 $vAttacharr=explode("|",$var_uploaded_files);
								   	foreach($vAttacharr as $key=>$value){
									   $split_name_url=explode("*",$value);
									   $mime->fattach("../attachments/" . $split_name_url[0], "Attached here is " . $split_name_url[1]);
									}
								}
							   $mime->send_mail();
		             }
		               //insert into reply table
					   $sql   ="insert into sptbl_replies(nReplyId,nTicketId,nStaffId,vStaffLogin,";
					   $sql .=" dDate,tReply,tPvtMessage,vReplyTime,vMachineIP) values('','".mysql_real_escape_string($var_tid)."',";
					   $sql .="'".mysql_real_escape_string($_SESSION["sess_staffid"])."',";
					   $sql .="'".mysql_real_escape_string($_SESSION["sess_staffname"])."',now(),'".mysql_real_escape_string($var_replymatter)."','".mysql_real_escape_string($var_pvtmessage)."',";
			           $sql .="'".mysql_real_escape_string($var_timespent)."','".mysql_real_escape_string(getClientIP())."')";

					   executeQuery($sql,$conn);
					   $var_insert_id = mysql_insert_id($conn);
					  //Insert the actionlog
					  if(logActivity()) {
						   $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','" . $_SESSION["sess_staffid"] . "','" . TEXT_ADDITION . "','Reply','" . mysql_real_escape_string($var_insert_id) . "',now())";
						   executeQuery($sql,$conn);
					   }
					   //save attachment
					     $sql_insert_attach="insert into sptbl_attachments(nReplyId,vAttachReference,vAttachUrl) values";
//						 echo "<br>varuploadfiles(4)==".$var_uploaded_files;
						if($var_uploaded_files !=""){
				   			 $vAttacharr=explode("|",$var_uploaded_files);
				    		foreach($vAttacharr as $key=>$value){
				       	       $split_name_url=explode("*",$value);
					           $sql_insert_attach .= "('$var_insert_id','".mysql_real_escape_string($split_name_url[1])."','".mysql_real_escape_string($split_name_url[0])."'),";
				            }
				           $sql_insert_attach = substr($sql_insert_attach,0,-1);
//				           echo $sql_insert_attach;
//						   exit;
						   executeQuery($sql_insert_attach,$conn);
				     }

				 /*  		 
					  //Send mail to the watcher staff(s) of the department 
					  //modification on SupportPRo Supportdesk3.
					  //added by roshith on 25-11-06
				 */			  
					 $sqlWatcher  = "select vStaffname,vMail from sptbl_staffs s inner join sptbl_staffdept sd on s.nStaffId=sd.nStaffId ";
					 $sqlWatcher .= " where ndeptid='$var_deptid' and s.nWatcher=1 ";
	
					 $resultWatcher = executeSelect($sqlWatcher,$conn);
	
					 $var_tolist=",";
					 while($row = mysql_fetch_array($resultWatcher)) {
						$var_tolist .= "," . $row["vMail"];
					 }
	
					 $var_tolist = substr($var_tolist,1);
					 if($var_tolist != "") {
							   $var_mail_body=$var_emailheader."<br>Hi,<br>".
							   $var_mail_body .= TEXT_MAIL_BODY .":". $var_refno ."<br><br>";
							   $var_mail_body .= nl2br(htmlentities($var_replymatter))."<br>".$var_emailfooter;

							   $var_subject = "Re:" . $var_tickettitle . "  Id#[" . $var_refno . "]";

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
					  //End Send mail			  

					 //clear the fields
					 $var_userid=$_POST['userid'];
			         $var_tickettitle="";
					 $var_refno="";
					 $var_tmplate_id="";
					 $var_replymatter="";
					 $var_pntitle ="";
					 $var_pnmatter="";
					 $var_addtokb="";
					 $var_category="";
					 $var_status="";
					 $var_tkowner="";
					 $var_ntuser="";
					 $var_timespent="";
					 $var_pvtmessage="";
					 $var_lock="";
					 $var_cc="";
//					 echo "<br>varuploadfiles(5)==".$var_uploaded_files;
					 $var_uploaded_files="";
					 $var_rp ="";
		             $var_tid ="";
		             $var_rid ="";

					 $var_message=MESSAGE_SUCCESS;
					 $var_refresh=1;
					 $replysent=1;
// added on 1-11-06 by roshith for ticket reply re-directing

                                      if($_SESSION["sess_backurl_reply_success"]!='')
                                          {
                                            $_SESSION["sess_backurl_reply_success"] =$_SESSION["sess_backurl_reply_success"]."&msg=success";
                                         }
                                 
					 
					 require("./includes/replied.php");
		 }else{
		   $var_message=$validsave;
		 }
  }else if ($_POST["postback"] == "AT") {   // attach files
             $var_userid=$_POST['userid'];
	         $var_tickettitle=$_POST['tickettitle'];
			 $var_tqusetion=$_POST["tquestion"];
			 $var_refno=$_POST['refno'];
			 $var_tmplate_id=$_POST['cmbTemplate'];
			 $var_replymatter=$_POST['txtRpMatter'];
			 $var_ereplymatter=$_POST['txtRpMatterE'];
			 $var_pntitle =$_POST['txtPnTitle'];
			 $var_pnmatter=$_POST['txtPnMatter'];
			 $var_addtokb=$_POST['chkaddtokb'];
			 $var_category=$_POST['cmbCategory'];
			 $var_deptid=$_POST['txtDeptId'];
			 $var_status=$_POST['cmbStatus'];
			 $var_tkowner=$_POST['chktkowner'];
			 $var_ntuser=$_POST['chkntuser'];
			 $var_timespent=$_POST['txtTimeSpent'];
			 $var_pvtmessage=$_POST['txtRpPvtMesssage'];
			 $var_lock=$_POST['chklock'];
			 $var_cc=$_POST['txtCC'];
            $var_refname=$_POST['txtRef'];
			$var_list = "";
            $var_uploaded_files=$_POST['uploadedfiles'];
//			echo "<br>varuploadfiles(6)==".$var_uploaded_files;

			//check reference name is duplicate
			$pos=0;
			$not_allowed_pos_star=0;
			$not_allowed_pos_pipe=0;
			//check whtether the refernce name contains | or *

			if($var_refname !=""){
			   $pos=strpos($var_uploaded_files,$var_refname);
			   $not_allowed_pos_star=strpos($var_refname,"*");
			   $not_allowed_pos_pipe=strpos($var_refname,"|");
			}else{
			  $pos=1;
			  $not_allowed_pos_star=1;
			  $not_allowed_pos_pipe=1;
			}

			$sql ="select * from sptbl_attachments where vAttachReference='".mysql_real_escape_string($_POST['txtRef'])."'";
			$var_result = executeSelect($sql,$conn);
			if(mysql_num_rows($var_result)>0 or $pos > 0 or $not_allowed_pos_star>0 or $not_allowed_pos_pipe>0){
			   $var_message=MESSAGE_REFNAME_ERROR;
			   mysql_free_result($var_result);
			}else{
			
			    if($_SESSION['ses_test']==$var or $var==""){
						    $var_maxfilesize="1000000000000";

							$uploadstatus=upload("txtUrl","../attachments/","","all",$var_maxfilesize);
							$file_name="";
							switch ($uploadstatus) {
				               case "FNA":
							              $errorcode=MESSAGE_UPLOAD_ERROR_0;
				                          break;
				               case "IS":
							              $errorcode=MESSAGE_UPLOAD_ERROR_3;
								   		  break;
							   case "IT":
							            $errorcode=MESSAGE_UPLOAD_ERROR_2;
								         break;
							   case "NW":
							            $errorcode=MESSAGE_UPLOAD_ERROR_4;
								         break;
							   case "FE":
							            $errorcode=MESSAGE_UPLOAD_ERROR_5;
								         break;
							   case "IF":
										$errorcode=MESSAGE_UPLOAD_ERROR_6;
										 break;
							   default:
								         $file_name=$uploadstatus;
								         break;
				  		    }

							if($file_name==""){
							  $var_message=$errorcode;
							}else{
							           $var_refname="";
							           if($var_uploaded_files==""){
									              $var_uploaded_file_name=$_POST['txtRef'];
							                      $var_uploaded_files=$file_name."*".$_POST['txtRef'];
							           }else{
									              $var_uploaded_files .="|".$file_name."*".$_POST['txtRef'];
									   }
							}
				 }else{
				     $file_name=time().$_FILES['txtUrl']['name'];
				     if($var_uploaded_files==""){
									              $var_uploaded_file_name=$_POST['txtRef'];
							                      $var_uploaded_files=$file_name."*".$_POST['txtRef'];
					 }else{
									              $var_uploaded_files .="|".$file_name."*".$_POST['txtRef'];
				   }
				 
				 }			
							
			}
			$var_refresh = 1;
}else if ($_POST["postback"] == "RA") {
    $var_userid=$_POST['userid'];
    $var_tickettitle=$_POST['tickettitle'];
	 $var_tqusetion=$_POST["tquestion"];
	$var_refno=$_POST['refno'];
	$var_tmplate_id=$_POST['cmbTemplate'];
	$var_replymatter=$_POST['txtRpMatter'];
	$var_ereplymatter=$_POST['txtRpMatterE'];
	$var_pntitle =$_POST['txtPnTitle'];
	$var_pnmatter=$_POST['txtPnMatter'];
	$var_addtokb=$_POST['chkaddtokb'];
	$var_category=$_POST['cmbCategory'];
	$var_deptid=$_POST['txtDeptId'];
	$var_status=$_POST['cmbStatus'];
	$var_tkowner=$_POST['chktkowner'];
	$var_ntuser=$_POST['chkntuser'];
	$var_timespent=$_POST['txtTimeSpent'];
	$var_pvtmessage=$_POST['txtRpPvtMesssage'];
	$var_lock=$_POST['chklock'];
	$var_cc=$_POST['txtCC'];
    $var_refname=$_POST['txtRef'];
     $var_uploaded_files=$_POST['uploadedfiles'];
     $var_list = "";
	 for($i=0;$i<count($_POST["chk"]);$i++) {
		$var_list .=  $_POST["chk"][$i] . "|";
	 }
	 $var_list = substr($var_list,0,-1);

}else if ($_POST["postback"] == "R") {
    $var_userid=$_POST['userid'];
    $var_tickettitle=$_POST['tickettitle'];
	 $var_tqusetion=$_POST["tquestion"];
	$var_refno=$_POST['refno'];
	$var_tmplate_id=$_POST['cmbTemplate'];
	$var_replymatter=$_POST['txtRpMatter'];
	$var_ereplymatter=$_POST['txtRpMatterE'];
	$var_pntitle =$_POST['txtPnTitle'];
	$var_pnmatter=$_POST['txtPnMatter'];
	$var_addtokb=$_POST['chkaddtokb'];
	$var_category=$_POST['cmbCategory'];
	$var_deptid=$_POST['txtDeptId'];
	$var_status=$_POST['cmbStatus'];
	$var_tkowner=$_POST['chktkowner'];
	$var_ntuser=$_POST['chkntuser'];
	$var_timespent=$_POST['txtTimeSpent'];
	$var_pvtmessage=$_POST['txtRpPvtMesssage'];
	$var_lock=$_POST['chklock'];
	$var_cc=$_POST['txtCC'];
    $var_refname=$_POST['txtRef'];
    $var_list = "";
    $var_uploaded_files=$_POST['uploadedfiles'];
    $var_list=$_POST["attrid"];

}else if($var_rp=="q" and $var_rid =="0"){
            $var_staffid = $_SESSION["sess_staffid"];
	        $sql ="select tSignature from sptbl_staffs where nStaffId='".mysql_real_escape_string($var_staffid)."'";
	        $result = executeSelect($sql,$conn);
			$var_row = mysql_fetch_array($result);
			$var_signature= $var_row["tSignature"];
			mysql_free_result($result);
		    $sql="select nDeptid,vTitle,nUserId,tQuestion,vRefNo from sptbl_tickets where nTicketId='".mysql_real_escape_string($var_tid)."'";
		    $result = executeSelect($sql,$conn);
		    if (mysql_num_rows($result) > 0) {
				$var_row = mysql_fetch_array($result);
				$var_deptid= $var_row["nDeptid"];
				$var_tickettitle= $var_row["vTitle"];
				$var_tqusetion=$var_row["tQuestion"];
				$var_refno= $var_row["vRefNo"];
				$var_userid= $var_row["nUserId"];
				$var_replymatter=$var_row["tQuestion"];
			}
			else {
				$var_message = "<font color=red>" . MESSAGE_RECORD_ERROR . "</font>";
			}

		    $var_replymatter=$var_signature."\n######################################\n".replacestr($var_replymatter);
			$var_ereplymatter=$var_signature."\n######################################\n".replacestrforemail($var_replymatter);
			$var_qtrp=$var_signature."\n######################################\n".$var_replymatter;
 }else  if($var_rp=="q" and $var_rid !="0"){
             $var_staffid = $_SESSION["sess_staffid"];
	        $sql ="select tSignature from sptbl_staffs where nStaffId='".mysql_real_escape_string($var_staffid)."'";
	        $result = executeSelect($sql,$conn);
			$var_row = mysql_fetch_array($result);
			$var_signature= $var_row["tSignature"];
			mysql_free_result($result);
		     $sql  ="select r.tReply,nDeptid,t.vTitle,t.nUserId,t.tQuestion,t.vRefNo,t.vStatus from sptbl_replies as r, ";
		     $sql .="sptbl_tickets as t where r.nTicketId=t.nTicketId and r.nReplyId='".mysql_real_escape_string($var_rid)."'";
			 $result = executeSelect($sql,$conn);

			 if (mysql_num_rows($result) > 0) {
				$var_row = mysql_fetch_array($result);
				$var_deptid= $var_row["nDeptid"];
				$var_tickettitle= $var_row["vTitle"];
				$var_tqusetion=$var_row["tQuestion"];
				$var_refno= $var_row["vRefNo"];
				$var_userid= $var_row["nUserId"];
				$var_replymatter=$var_row["tReply"];
				$var_status=$var_row["vStatus"];
			}
			else {
				$var_message = "<font color=red>" . MESSAGE_RECORD_ERROR . "</font>";
			}
		   $var_replymatter=$var_signature."\n######################################\n".replacestr($var_replymatter);
		   $var_ereplymatter=$var_signature."\n######################################\n".replacestrforemail($var_replymatter);
		   $var_qtrp=$var_signature."\n######################################\n".$var_replymatter;
		   
}else  if($var_rp=="r"){
             $var_staffid = $_SESSION["sess_staffid"];
	        $sql ="select tSignature from sptbl_staffs where nStaffId='".mysql_real_escape_string($var_staffid)."'";
	        $result = executeSelect($sql,$conn);
			$var_row = mysql_fetch_array($result);
			$var_signature= $var_row["tSignature"];
			mysql_free_result($result);
		    $sql="select nDeptid,vTitle,nUserId,tQuestion,vRefNo,vStatus from sptbl_tickets where nTicketId='".mysql_real_escape_string($var_tid)."'";
		    $result = executeSelect($sql,$conn);
		    if (mysql_num_rows($result) > 0) {
				$var_row = mysql_fetch_array($result);
				$var_deptid= $var_row["nDeptid"];
				$var_tickettitle= $var_row["vTitle"];
				$var_refno= $var_row["vRefNo"];
				$var_tqusetion=$var_row["tQuestion"];
				$var_userid= $var_row["nUserId"];
				$var_status=$var_row["vStatus"];
			}
			else {
				$var_message = "<font color=red>" . MESSAGE_RECORD_ERROR . "</font>";
			}
			$var_replymatter=$var_signature."\n\n";
			$var_ereplymatter=$var_replymatter;
}

function validateSave($var_tid){
	   global $conn;
	   global $curr_status,$mail_refno;
	   $var_message="1";
	   if(trim($_POST['txtRpMatter'])=="" or trim($_POST['txtTimeSpent'])<=0){
	      $var_message = "<font color=red>" . MESSAGE_RECORD_EMPTY_MATTER_ERROR. "</font>";
		  return $var_message;
	   }
	   $sql="select nDeptid,nOwner,nLockStatus,vTitle,nUserId,tQuestion,vStatus,vRefNo from sptbl_tickets where nTicketId='".mysql_real_escape_string($var_tid)."'";
	   $sql .=" and vDelStatus='0'";
	   $result = executeSelect($sql,$conn);

	   if (mysql_num_rows($result) > 0) {
			$var_row = mysql_fetch_array($result);
			$var_deptid= $var_row["nDeptid"];
			$var_tickettitle= $var_row["vTitle"];
			$var_userid= $var_row["nUserId"];
			$var_replymatter=$var_row["tQuestion"];
			$var_owner=$var_row["nOwner"];
			$var_lockstatus=$var_row["nLockStatus"];
			$var_status=$var_row["vStatus"];
			$curr_status=$var_status;
			$mail_refno=$var_row["vRefNo"];
			mysql_free_result($result);
			$sql =" select  nStaffId,nDeptId from sptbl_staffdept where nStaffId='".mysql_real_escape_string($_SESSION["sess_staffid"])."'";
	        $sql .=" and nDeptId='".mysql_real_escape_string($var_deptid)."'";

			$result = executeSelect($sql,$conn);
			//check valid department

			if (mysql_num_rows($result)==0) {
			  $var_message = "<font color=red>" . MESSAGE_RECORD_ERROR_DEPT . "</font>";
			}else{
			     if($var_lockstatus=="1"){
				    if($var_owner=="0"){
					  $var_message = "<font color=red>" . MESSAGE_RECORD_ERROR_NO_OWNER . "</font>";
					}else if($_POST['chktkowner']=="tkowner"  and $_SESSION["sess_staffid"] !=$var_owner){
				      $var_message = "<font color=red>" . MESSAGE_RECORD_ERROR_ALREADY_OWNED_USER . "</font>";
					 }else if($_POST['chklock']=="lock"  and $_SESSION["sess_staffid"] !=$var_owner){
					   $var_message = "<font color=red>" . MESSAGE_RECORD_ERROR_ALREADY_LOCKED_ANOTHER_USER . "</font>";
					 }else if($_SESSION["sess_staffid"] !=$var_owner){
					   $var_message = "<font color=red>" . MESSAGE_RECORD_ERROR_LOCKED_ANOTHER_USER . "</font>";
					 }
				 }else if($var_lockstatus=="0"){
				        if($_POST['chktkowner']=="tkowner"  and $_POST['chklock']=="lock" ){
					      ;
					   }else if($_POST['chklock']=="lock"  and $_SESSION["sess_staffid"] !=$var_owner  and $var_owner !="0"){
					      $var_message = "<font color=red>" . MESSAGE_RECORD_ERROR_DIFFENT_OWNER . "</font>";
					   }
				 }
			}
       }
		else {
			$var_message = "<font color=red>" . MESSAGE_RECORD_ERROR. "</font>";
		}
	    return $var_message;
	}

	if($_SESSION["sess_backreplyurl"] ==""){
	        $backurl="./viewticket.php?mt=y&tk=".$var_tid."&us=".$var_userid."&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&";
            $_SESSION["sess_backreplyurl"] = $backurl;
	}
//}
$var_kbentry = "false";
?>
<script>
    
 function getTemplate(){

         var cmbTemplate = $("#cmbTemplate").val();

         var dataString = {"txtTemplate":cmbTemplate};

        $.ajax({

            url			:"autocomplete.php",

            type		:"GET",

            data		:dataString,

            dataType            : "html",

            success		:function(response){
            //alert(response);
            var oEditor = FCKeditorAPI.GetInstance('txtRpMatter');
           // oEditor.InsertHtml(" ");
                     if(response!='')
                    {
                        oEditor.SetHTML(response);
                    }
                    else
                        {
                            oEditor.SetHTML('');
                        }
                    
            }

        });
    }
 </script>
<?php if($replysent != 1) { ?>
<form name="frmReplies" method="GET" action="./replies.php" enctype="multipart/form-data">
    <input type="hidden" name="txtNext" id="txtNext" value="<?php echo $_REQUEST['next'];?>">
    <input type="hidden" name="txtLimit" id="txtLimit" value="<?php echo $_REQUEST['limitval'];?>">
    
<table width="100%"  border="0">
  <tr>
    <td width="76%" valign="top">
    <table width="100%"  border="0" cellspacing="10" cellpadding="0">
    <tr>
    <td>
    <table width="100%"  border="0" cellpadding="0" cellspacing="0" >
     <tr>
     <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
     </tr>
     </table>

     <table width="100%"  border="0" cellspacing="0" cellpadding="0">
     <tr>
     <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
     <td class="pagecolor">
     <table width="100%"  border="0" cellspacing="0" cellpadding="5">
     <tr>
     <td width="93%" class="heading" align="left"><?php echo TEXT_REPLIES ?></td>
     </tr>
     </table>

     <table width="100%"  border="0" cellspacing="1" cellpadding="0" class="column1">
	   <tr>
    	<td>
         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
		   <tr>
				 <td width="100%" align="center" colspan=3 class="whitebasic"><?php echo TEXT_FIELDS_MANDATORY ?></td>
		   </tr>
           <tr>
    	     <td width="100%" align="center" colspan=3 class="errormesssage">
        	 	<?php echo "<font color=red>".$var_message."</font>"; ?></td>
	         </tr>
			   <tr>
				 <td width="100%" align="left" colspan=3 class="whitebasic" valign="bottom">
					 <a href="javascript:void(0)" onclick="clicktab1()"><img id="replytab" src='../images/reply1.gif' border='0'></a>
					 <a href="javascript:void(0)" onclick="clicktab2()"><img id="personaltab" src='../images/personal.gif' border="0"></a>
<!--					 <a href="javascript:void(0)" onclick="clicktab4()"><img id="actiontab" src="../images/action.gif" border="0"></a> -->
					 <a href="javascript:void(0)" onclick="clicktab5()"><img id="attachtab" src="../images/attachments.gif" border="0"></a>
				<?php 
					//$list=makeCategoryList(0,0,$var_deptid);
					//if($var_rid==0 and $var_rp=="r" and count($list)>0){  $var_kbentry = "true"; ?>
						 <a href="javascript:void(0)" onclick="clicktab3()"><img id="knowledgetab" src="../images/knowledge.gif" border="0"></a>
				<?php //} ?>
				 </td>
			   </tr>
               <tr>
						    <td align="center" id="td1">
							  <fieldset style="width:720px;">
		                      <legend class="whitebasic"><b><?php echo TEXT_REPLY?></b></legend>
							  <table border=0 width="100%">
							     <tr><td colspan="3">&nbsp;</td></tr>
							    <tr>
							     <td width="23%" align="left" class="listingmaintext"><?php echo TEXT_TEMPLATE?>&nbsp;</td>
						         <td width="2%">&nbsp;</td>
						          <td width="75%" align="left">
								     <?php
														     $sql = "select * from sptbl_templates where vStatus='1' order by vTemplateTitle";
								 							 $rs = executeSelect($sql,$conn);
									?>
	                                <select name="cmbTemplate" size="1" class="textbox" id="cmbTemplate" style="width:200px;" onchange="getTemplate();">
									   <?php
									                                     $options ="<option value='0'";
													                     $options .=">".TXT_SELECT_TEMPLATE."</option>\n";
																		 echo $options;
								                                         while($row = mysql_fetch_array($rs)) {
																			  $options ="<option value='".$row['nTemplateId']."'";
																			  if ($var_tmplate_id == $row['nTemplateId']){

								                                                           $options .=" selected=\"selected\"";
								                                              }
								                                              $options .=">".htmlentities($row['vTemplateTitle'])."</option>\n";
																			  echo $options;
																			}
																			 mysql_free_result($rs) ;
									   ?>
									 </select>
						           </td>
								 </tr>
								 <tr><td colspan="3">&nbsp;</td></tr>
								 <tr>
										    <td align="left" class="listingmaintext" valign="top"><?php echo TEXT_REPLAY_MATTER?>&nbsp;<span class="required">*</span></td>
										    <td>&nbsp;</td>
										    <td  align="left">

                     							<!--<textarea name="txtRpMatter" cols="50" rows="12" id="txtRpMatter" class="textarea" style="width:560px;"><?php// echo htmlentities($var_replymatter); ?></textarea>-->
                                                                                         <?php                  $sBasePath                      = "../FCKeditor/";
                                                                                                                $oFCKeditor 					= new FCKeditor('txtRpMatter') ;
                                                                                                                $oFCKeditor->BasePath			= $sBasePath ;
                                                                                                                $oFCKeditor->Value		       = stripslashes($var_replymatter);;
                                                                                                                $oFCKeditor->Width  = '530' ;
                                                                                                                $oFCKeditor->Height = '350' ;
                                                                                                                $oFCKeditor->Create() ; ?>


		 									</td>
							    </tr>
							  </table>
							  </fieldset>
                                                       
                                                       
		                     </td>
					   </tr>
		                <tr>
						    <td colspan=3 align="center"  id="td2">
							  <fieldset style="width:720px;">
		                      <legend class="whitebasic"><b><?php echo TEXT_PERSONAL_NOTES?></b><?php echo TEXT_REPLAY_PERSONALNOTES?></legend>
							  <table width="100%">
									  <tr><td colspan="3">&nbsp;</td></tr>
									 <tr>
										<td align="left" class="listingmaintext" width="23%"><?php echo TEXT_PN_TITLE?>&nbsp;</td>
										<td width="2%">&nbsp;</td>
										<td width="75%" align="left"><input name="txtPnTitle" type="text" size="70" maxlength="100" class="textbox" value="<?php echo htmlentities($var_pntitle);?>" style="width:400px"></td>
									  </tr>
									  <tr>
									   <td colspan="3">&nbsp;</td>
									  </tr>
									  <tr>
										<td align="left" class="listingmaintext" valign="top"><?php echo TEXT_PN_MATTER?>&nbsp;</td>
										<td>&nbsp;</td>
										<td  align="left">
												<textarea name="txtPnMatter" cols="50" rows="12" id="txtPnMatter" class="textarea" style="width:560px;"><?php echo htmlentities($var_pnmatter); ?></textarea>
										</td>
									  </tr>
							  </table>
							  </fieldset>
		                     </td>
					   </tr>
					   <tr>
						    <td colspan=3 align="center" id="td3">
                       <?php
					          $list=makeCategoryList(0,0,$var_deptid);
					         if($var_rid==0 and $var_rp=="r" and count($list)>0){            //show kb only for ticket reply   ?>
							  <fieldset style="width:720px;">
		                      <legend class="listingmaintext"><b><?php echo TEXT_KB_INFO?></b></legend>
							  <table border=0 width="100%">
							      <tr><td colspan="5">&nbsp;</td></tr>
							     <tr>
			                     <td width="12%" align="left">&nbsp;</td>
			                     <td width="25%" align="left" class="listingmaintext"><?php echo TEXT_CATEGORY?></td>
			                     <td width="22%" align="left" class="listingmaintext">
								 <?php
									    echo makeDropDownList("cmbCategory",makeCategoryList(0,0,$var_deptid),$var_category,true, "textbox", "","" );
								 ?>
			                      </td>
						            <td width="41%" align="left" class="listingmaintext"> <?php //modified on October 28, 2006 by Roshith ?>
											   <input name="chkaddtokb" type="checkbox"  value="atokb" class="checkbox" onClick="checkaddtokb();" <?php if($var_addtokb=="atokb") echo "checked" ;echo $chk_kb_disabled ;?>><?php echo TEXT_KB_ADD?>
									</td>
								 </tr>
							  </table>
							  </fieldset>
                                                           <?php } ?>

                                                          <!--Show KnowlaGE Base-->
                                                          <fieldset style="width:720px;">
                                                              <legend class="listingmaintext"><b><?php echo TEXT_KB_SEARCH_INFO?></b></legend>
                                                              <table border=0 width="100%">
                                                                  <tr><td colspan="5">&nbsp;</td></tr>
                                                                  <tr>
                                                                      <td><?php echo TEXT_ENTRY_TITLE;?></td>
                                                                      <td>&nbsp;</td>
                                                                      <td>
                                                                          <input name="txtKbTitleSearch" id="txtKbTitleSearch" type="text" size="70"  value="<?php echo htmlentities($var_cc);?>" size="200">
                                                                          <input type="hidden" name="txtKbSearchid" id="txtKbSearchid">
                                                                      </td>
                                                                  </tr>
                                                                   <tr><td colspan="5"><br><b><?php echo TEXT_KB_SERACH_RESULT;?></b></td></tr>
                                                                    <tr><td colspan="5" >
                                                                           
                                                                            <div id="kbSearchResult"></div>
                                                                            <input type="hidden" id="txt_kbSearchResult" name="txt_kbSearchResult" value="">
                                                                            <!--Search result shown here-->
                                                                            <div>
                                                                                <input type="button" name="setkbreplay" value="Set As Reply" id="setkbreplay">
                                                                            </div>
                                                                        </td></tr>

                                                              </table>
                                                          </fieldset>

                                                          <script type="text/javascript" src="../scripts/jquery.autocomplete_kbsearch.js"></script>
                                                        <script type="text/javascript">
                                                        $(document).ready(function(){
                                                            var site_url ='<?php echo SITE_URL?>';

                                                          $("#txtKbTitleSearch").autocomplete(site_url+"staff/searck_kb_result_ajax.php", {
                                                                        selectFirst: true
                                                                       

                                                                });

                                                                // getKbSearchdata();
                                                        });
                                                       
                                                            function getKbSearchdata(){
                                                                
                                                                var txtKbSearchid = $("#txtKbSearchid").val();
                                                                var dataString = {"txtKbSearchid":txtKbSearchid};

                                                                $.ajax({

                                                                    url			:"searck_kb_result_ajax.php",

                                                                    type		:"POST",

                                                                    data		:dataString,

                                                                    dataType            : "html",

                                                                    success		:function(response){
                                                                        
                                                                        if(response!='')
                                                                        {
                                                                            $("#kbSearchResult").html(response);
                                                                            $("#txt_kbSearchResult").val(response);
                                                                        }
                                                                        else
                                                                        {
                                                                           $("#kbSearchResult").html("No Result Found !");
                                                                        }


                                                                    }

                                                                });
                                                            }

                                                        $("#setkbreplay").click(function() {
                                                            
                                                           // var currentRreplay = $("#txtRpMatter").val();
                                                           var oEditor = FCKeditorAPI.GetInstance('txtRpMatter');
                                                            var newReplay =null;
                                                            var oDOM = null;
                                                            var currentRreplay = null;
                                                            var kbreplay = null;
                                                            var newReplay = null;
                                                            if(oEditor)
                                                                oDOM = oEditor.EditorDocument;
                                                            if(oDOM)
                                                                currentRreplay =  oEditor.GetHTML();

                                                            var kbreplay =  $("#txt_kbSearchResult").val();

                                                            newReplay  = kbreplay +  currentRreplay;

                                                            if(newReplay){
                                                                oEditor.SetHTML(newReplay);
                                                                clicktab1();
                                                               // alert("Replay set suessfully .")
                                                            }
                                                             
                                                        });
                                                        </script>
                                                        <!--Show KnowlaGE Base ends-->



						  

                                                      

		                     </td>
					   </tr>
					      <tr>
						    <td colspan=3 align="center"  id="td4">
							  <fieldset style="width:720px;">
		                      <legend class="listingmaintext"><b><?php echo TEXT_ACTION?></b></legend>
							  <table border=0 width="100%">
							   <tr>
							      <td width="20%" align="left" class="listingmaintext" ><?php echo TEXT_STATUS?>&nbsp;</td>
								  <td width="20%">&nbsp;</td>

								  <td align="left" colspan="4">
			                               <select name="cmbStatus" size="1" class="textbox" id="cmbStatus" style="width:120px;">
										      <option value="open" <?php if($var_status=="open") echo "selected"; ?>>Open</option>
											  <option value="closed" <?php if($var_status=="closed") echo "selected"; ?>>Closed</option>
											  <option value="escalated" <?php if($var_status=="escalated") echo "selected"; ?>>Escalated</option>
											  <?php
											   $sql ="select vLookUpValue from sptbl_lookup where vLookUpName='ExtraStatus'";
											   $rs = executeSelect($sql,$conn);
									           while($row = mysql_fetch_array($rs)) {
											           $options ="<option value='".htmlentities($row['vLookUpValue'])."'";
														if ($var_status == $row['vLookUpValue']){
								                                 $options .=" selected=\"selected\"";
		                                                }
								                        $options .=">".htmlentities($row['vLookUpValue'])."</option>\n";
													    echo $options;
												}
												 mysql_free_result($rs) ;
											  ?>
										  </select>
								  </td>
							   </tr>
								   <tr><td colspan="6">&nbsp;</td></tr>
			                      <tr>
								            <td align="right" class="listingmaintext">
<?php //modified on October 28, 2006 by Roshith ?>
											   <input type="checkbox"  name="chkntuser" value="ntuser" class="checkbox" <?php if($var_ntuser=="ntuser") echo "checked" ;?> checked>
											 </td>
											 <td align=left class="listingmaintext">
											   <?php echo TEXT_NT_USR?>
											</td>
								            <td width="5%" align="right" class="listingmaintext"  >
											   <input type="checkbox"  name="chktkowner" value="tkowner" class="checkbox" <?php if($var_tkowner=="tkowner") echo "checked" ;?>>
											 </td>
											 <td width="22%" align=left class="listingmaintext">
											   <?php echo TEXT_TAKE_OWNSHP?>
											</td>
								            <td width="5%" align="right" class="listingmaintext"  >
											   <input type="checkbox"  name="chklock" value="lock" class="checkbox" <?php if($var_lock=="lock") echo "checked" ;?>>
											 </td>
											 <td width="28%" align=left class="listingmaintext">
											   <?php echo TEXT_TAKE_LOCK?>
											</td>
								  </tr>
							  </table>
							  </fieldset>
		                     </td>
					   </tr>
					   <tr>
						    <td colspan=3  align="center"  id="td5">
							  <fieldset style="width:720px;">
		                      <legend class="listingmaintext"><b><?php echo TEXT_OTHER_INFO?></b></legend>
							  <table align="center" width="100%">
							   <tr>
							    <td align="left" class="listingmaintext" width="20%"><?php echo TEXT_CC?>&nbsp;</td>
							    <td width="1%">&nbsp;</td>
							    <td width="79%" align="left">
                                                                <?php
                                                           //     echo $var_tid;
                                                            // echo     $ticketDelayTime = getTicketDelayTime($var_tid);
                                                                $ticketDelayTime = 20;
                                                                ?>
                                                                <input name="txtCC" type="text" size="70" maxlength="100" class="textbox" value="<?php echo htmlentities($var_cc);?>">
                                                                <input type="hidden" name="txtTimeSpent" id="txtTimeSpent" value="<?php echo $ticketDelayTime;?>">
                                                            </td>
							  </tr>
							 <!--   <tr><td colspan="3">&nbsp;</td></tr>
							<tr>
							    <td align="left" class="listingmaintext"><?php echo TEXT_TIME?>&nbsp;<span class="required">*</span></td>
							    <td>&nbsp;</td>
							    <td align="left">
								   <select name="txtTimeSpent" size="1" class="textbox" id="txtTimeSpent" style="width:100px;">
								           <option value="0"><?php echo TEXT_SELECT_TIME_SPENT?></option>
								           <?php  for($i=1;$i<=59;$i++) { ?>
									         <option value="<?php echo $i;?>" <?php if($i==$var_timespent) echo "selected";?>><?php echo $i;?>Minute(s)</option>
									       <?php } ?>
										   <?php  for($i=1;$i<=23;$i++) { ?>
									         <option value="<?php echo $i*60;?>" <?php if($i*60==$var_timespent) echo "selected";?>><?php echo $i;?>Hour(s)</option>
									       <?php } ?>
										   <?php  for($i=1;$i<=10;$i++) { ?>
									         <option value="<?php echo $i*60*24;?>" <?php if($i*60*24==$var_timespent) echo "selected";?>><?php echo $i;?>Day(s)</option>
									       <?php } ?>
										  </select>-->
								  <!--<input name="txtTimeSpent" type="text" size="70" maxlength="100" class="textbox" value="<?php echo htmlentities($var_timespent);?>"></td> -->
							<!--  </tr>-->
							  </table>
							  </fieldset>
		                     </td>
					   </tr>
					   <tr>
						    <td colspan=3  align="center"  id="td6">
 							     <fieldset style="width:720px;">
                                        <legend class="listingmaintext"><b><?php echo TEXT_ATTACHMENTS?></b></legend>
												  <table width="100%">
													  <tr>
														<td colspan="3" width="100%" align="center" class="listing">
															<?php echo TEXT_FIELDS_SEMI_MANDATORY ?>
														</td>
													  </tr>
													<!--  <tr>
													    <td align="left" class="listingmaintext" width="30%"><?php// echo TEXT_ATTACH_REFERENCE?>&nbsp;<span class="semirequired">*</span></td>
													    <td width="10%">&nbsp;</td>
                                                                                                            <td align="left">
                                                                                                               
                                                                                                            </td>
													  </tr>-->
													  <tr>
													   <td colspan="3">&nbsp;</td>
													  </tr>
													  <tr>
													     <td align="left" class="listingmaintext"><?php echo TEXT_ATTACH_URL?>&nbsp;<span class="semirequired">*</span></td>
													     <td>&nbsp;</td>
												        <td width="60%" align="left" class="listingmaintext">
                                                                                                             <?php
                                                                                                                $var_refname = time(). rand(1,90000);
                                                                                                                ?>
                                                                                                                <input name="txtRef" type="hidden" size="60" maxlength="100" class="textbox" value="<?php echo htmlentities($var_refname);?>">
					    										<input name="txtUrl" type="file" class="textbox" id="txtUrl" style="width:300px" size="30">

													<?php 
														if($var==""){ 
															$var=0;
														}else{
															$var=$var+1;
														}
														$_SESSION['ses_test'] = $var ; 
													?>
														<input type=hidden name=varrefresh value="<?php echo   $var?>">														
														<input name="btnSubmit" type="button" class="button" value="<?php echo BUTTON_TEXT_ATTACH?>" onClick="javascript:attach();">
														 </td>
													  </tr>
													  <?php 
													                   $total_uploaded_file=explode("|",$var_uploaded_files);
																	   //remove list not empty
																	   if($var_list !=""){
																		     $remove_array=explode("|",$var_list);
																			 foreach($remove_array as $key=>$value){
																			    $picarry=explode("*",$value);
																			    if(file_exists("../attachments/".$picarry[0]))
																					unlink("../attachments/".$picarry[0]);
																				
																			 }
																			 $var_uploaded_files_arr = array_diff($total_uploaded_file,$remove_array);
																			 $total_uploaded_file =array_diff($total_uploaded_file,$remove_array);
													                         $var_uploaded_files=implode("|",$var_uploaded_files_arr);
																	   }
													     	  if($var_uploaded_files !=""){
													   ?>
													                     <tr><td colspan=3>
													                     <table width='60%' border=1 align="center">
													   <?php
																       foreach($total_uploaded_file as $key=>$value){
																		   $spli_name_file=explode("*",$value);
																		   $disp_name_file=$spli_name_file[1]."(".$spli_name_file[0].")";
													  ?>
													  					   <tr>
																		      <td width="6%" align="center">
																			     <input type="checkbox" name="chk[]" id="u<?php echo($key); ?>" value="<?php echo htmlentities($value) ?>" class="checkbox">
																			  </td>
													    					  <td width="89%" class="whitebasic"><?php  echo htmlentities($disp_name_file); ?></td>
																			  <td width="5%" align="center"><a href="javascript:remove('<?php  echo mysql_real_escape_string(htmlentities($value));?>');"><img src="../images/delete.gif" width="13" height="13" border="0" title="<?php echo TEXT_TITLE_DELETE ?>"></a></td>
													  					   </tr>
													  <?php             }
													   ?>
													                    <tr>
																		  <td colspan=3 align=center>
																		   <input name="btnDelete" type="button" class="button" value="<?php echo BUTTON_TEXT_REMOVE; ?>" onClick="javascript:clickRemove();">
																		  </td>
																		</tr>
													                    </table></td></tr>
													   <?php
													    }
													   ?>
													   
													 <input type="hidden" name="count" value="<?php echo $count; ?>">
													 <input type="hidden" name="attrid" value="<?php echo $var_attrid; ?>">
													 <input type="hidden" name="uploadedfiles" value="<?php echo htmlentities($var_uploaded_files); ?>">
													 <input type="hidden" name="uploadedfile_name" value="<?php echo $var_uploaded_file_name; ?>">
												  </table>
											  </fieldset>
							</td>
					    </tr>
					          <tr><td colspan="3">&nbsp;</td></tr>
                              </table>
                        </td>
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
            <table width="100%"  border="0" cellspacing="10" cellpadding="0">
              <tr>
                <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr >
                        <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                        <td ><table width="100%"  border="0" cellspacing="0" cellpadding="5" >
                            <tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center"  class="whitebasic">
                                    <td width="22%">&nbsp;</td>
                                    <td width="16%">
										<input name="btAdd" type="button" class="button" value="<?php echo BUTTON_TEXT_REPLY ?>" onClick="javascript:save();">
										&nbsp;&nbsp;<input name="btAdd" type="button" class="button" value="<?php echo BUTTON_TEXT_BACK ?>"onClick="javascript:back();">
									</td>
                                    <td width="20%">
									<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
									<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
									<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
									<input type="hidden" name="qtrp" value="<?php echo(htmlentities($var_qtrp)); ?>">
									<!--<input type="hidden" name="txtRpMatterE" value="<?php// echo htmlentities($var_ereplymatter); ?>"> -->

<!--									<input type="hidden" name="tquestion" value="<?php// echo htmlentities($var_tqusetion); ?>"> -->
									
									<input type="hidden" name="blockrefresh" value="<?php echo($var_refresh); ?>">
									<input type="hidden" name="txtDeptId" value="<?php echo($var_deptid); ?>">
									<input type="hidden" name="refno" value="<?php echo($var_refno); ?>">
                                    <input type="hidden" name="userid" value="<?php echo($var_userid); ?>">
									<input type="hidden" name="rp" value="<?php echo($var_rp); ?>">
									<input type="hidden" name="tk" value="<?php echo($var_tid); ?>">
									<input type="hidden" name="rid" value="<?php echo($var_rid); ?>">
<!--									<input type="hidden" name="uname" value="<?php //echo htmlentities($_SESSION["sess_staffname"]); ?>"> -->
									<input type="hidden" name="tickettitle" value="<?php echo htmlentities($var_tickettitle); ?>">
									<input type="hidden" name="postback" value="">
									</td>
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
        </td>
  </tr>
</table>
</form>
		    <table width="100%"  border="0" cellspacing="10" cellpadding="0">
              <tr>
                <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr >
                        <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                        <td >
						<table width="100%" cellpadding="0" cellspacing="0" border="0" style="width:99%;">
							  	<tr>
									<td width="100%">
										<div style="overflow:auto;border:1px solid #666;padding:2px; width:100%">
											<table width="100%" cellpadding="0" cellspacing="0">
												<tr>
													<td width="100%" valign="top">
														<table width="100%"  border="0" cellspacing="0" cellpadding="0" >
															<tr class="headings1"><td width="100%" class="heading" height="30"><?php echo TEXT_ICKET_DETAILS." ( ".$var_refno." )"; ?></td></tr>
															<tr>
															  <td>
																   <?php require("./includes/ticketdisplay.php"); ?>					
															   </td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</div>
									</td>
								</tr>
							  </table>
						</td>
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
<script>
<?php
		if ($var_tid != "") {
			echo("document.frmReplies.btAdd.disabled=false;\n");
			echo("document.frmReplies.btnSubmit.disabled=false;");
		}
		elseif ($var_staffid == $_SESSION["sess_staffid"]) {
			echo("document.frmReplies.btAdd.disabled=true;");
	    }
		else {
			echo("document.frmReplies.btAdd.disabled=true;");
		}
?>
	function manageTables(row){
		if(document.getElementById(row).style.display=="none"){
			document.getElementById(row).style.display="block";
		}else{
			document.getElementById(row).style.display="none";	
		}	
	}

	var mt='<?php echo   $_POST["mt"]?>';
	if(mt=='u'){
		manageTables('propertymatter','propertyimage');
	}else if(mt=='f'){
		manageTables('forewardmatter','forewardimage');
	}
</script>
	<script>
		
		function clicktab1(){  //reply tab
			resetimage(<?php echo   $var_kbentry?>);
			document.getElementById("replytab").src="../images/reply1.gif";
			resettd(<?php echo   $var_kbentry?>);
			document.getElementById("td1").style.display="";
			document.getElementById("td4").style.display="";
			document.getElementById("td5").style.display="";
	 	}
 		function clicktab2(){  //personal tab
			resetimage(<?php echo   $var_kbentry?>);
			document.getElementById("personaltab").src="../images/personal1.gif";
			resettd(<?php echo   $var_kbentry?>);
			document.getElementById("td2").style.display="";
	 	}
 		function clicktab3(){  //knowledge tab
			resetimage(<?php echo   $var_kbentry?>);
			document.getElementById("knowledgetab").src='../images/knowledge1.gif';
			resettd(<?php echo   $var_kbentry?>);
   			document.getElementById("td3").style.display="";
 	}
 		function clicktab4(){  // action tab
			resetimage(<?php echo   $var_kbentry?>);
			document.getElementById("actiontab").src='../images/action1.gif';
			resettd(<?php echo   $var_kbentry?>);
			document.getElementById("td4").style.display="";
	 	}
 		function clicktab5(){  // attachments tab
			resetimage(<?php echo   $var_kbentry?>);
			document.getElementById("attachtab").src='../images/attachments1.gif';
			resettd(<?php echo   $var_kbentry?>);
			document.getElementById("td6").style.display="";
	 	}
	</script>
<script>
document.getElementById("td2").style.display="none";
document.getElementById("td3").style.display="none";
//document.getElementById("td4").style.display="none";
document.getElementById("td6").style.display="none";

</script>
<?php if($_POST["postback"] == "AT") 
	{
		echo("<script>clicktab5();</script>");
	}
?>
<?php } ?>