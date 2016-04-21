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


	$var_userid=$_SESSION["sess_userid"];
	if($_SESSION["sess_ubackreplyurl"] ==""){

            $_SESSION["sess_ubackreplyurl"] = $_SERVER['HTTP_REFERER'];
	}

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

	if(!isset($_POST['varrefresh']))
		$var = "";
	else
		$var = $_POST['varrefresh'];

	//select ticket details


		//echo "tid====".$var_tid;
 if ($_POST["postback"] == "S") {
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
		  if($_POST['blockrefresh']=="1"){
		    $var_message="Reply already sent";
                    $flag_msg="class='msg_success'";
		    require("./includes/replysent.php");
		    exit;
		  }
		 if($validsave =="1"){
		     //insert into personal notes


				  //update ticket fileds
				       $sql = " Select * from sptbl_lookup where vLookUpName IN('Post2PostGap','MailFromName','MailFromMail',";
							   $sql .="'MailReplyName','MailReplyMail','Emailfooter','Emailheader','AutoLock')";
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
										}
									}
								}
							   mysql_free_result($result);


		       //send mail to user



/*
 * This is commented since the mail is to be sent to the staff assigned rather than the user itself.

 							   $var_email=$_SESSION["sess_useremail"];
							   $var_ulogin=$_SESSION["sess_userfullname"];
							   $var_mail_body=$var_emailheader."<br>".TEXT_MAIL_START."&nbsp;".$var_ulogin.",<br>".
							   $var_mail_body .= TEXT_MAIL_BODY .":". $var_refno ."<br><br>";
							   $var_mail_body .= nl2br($var_replymatter)."<br>".$var_emailfooter;
							   $var_subject = TEXT_EMAIL_SUB;
							   $var_body = $var_mail_body;
							   $Headers="From: $var_fromName <$var_fromMail>\n";
							   $Headers.="Reply-To: $var_replyName <$var_replyMail>\n";
							   if($var_cc !=""){
							       $Headers.="Bcc: $var_cc\r\n";
							   }

						       $Headers.="MIME-Version: 1.0\n";
							   $Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
							   $mailstatus=@mail($var_email,$var_subject,$var_body,$Headers);
*/

		               //insert into reply table
		                $now=date("Y-m-d H:i:s");
					   $sql   ="insert into sptbl_replies(nReplyId,nTicketId,nUserId,";
					   $sql .=" dDate,tReply,vMachineIP) values('','".addslashes($var_tid)."',";
					   $sql .="'".addslashes($_SESSION["sess_userid"])."',";
					   $sql .="'$now','".addslashes($var_replymatter)."',";
			           $sql .="'".addslashes(getClientIP())."')";
		   			  //echo "sql==$sql";
		               executeQuery($sql,$conn);
					   $var_insert_id = mysql_insert_id($conn);
					  //Insert the actionlog
					  if(logActivity()) {
					   $sql = "Insert into sptbl_actionlog(nALId,nUserId,vAction,vArea,nRespId,dDate) Values('','$var_userid','" . TEXT_ADDITION . "','Reply','" . addslashes($var_insert_id) . "',now())";
					   executeQuery($sql,$conn);
					   }
					   //save attachment
					     $sql_insert_attach="insert into sptbl_attachments(nReplyId,vAttachReference,vAttachUrl) values";
						if($var_uploaded_files !=""){
				   			 $vAttacharr=explode("|",$var_uploaded_files);
				    		foreach($vAttacharr as $key=>$value){
				       	       $split_name_url=explode("*",$value);
					           $sql_insert_attach .= "('$var_insert_id','".addslashes($split_name_url[1])."','".addslashes($split_name_url[0])."'),";
				            }
				           $sql_insert_attach = substr($sql_insert_attach,0,-1);
				           executeQuery($sql_insert_attach,$conn);
				     }
					 //update the ticket status open
					 $sql="update sptbl_tickets set vStatus='open',dLastAttempted='$now' where nTicketId='".addslashes($var_tid)."'";
					  executeQuery($sql,$conn);
					  //send mail/sms to all assgned staffs
					  $sql=" select s.nStaffId,s.vStaffname,s.vMail,s.vSMSMail,d.nDeptId from
						   sptbl_staffs s,sptbl_staffdept d where d.nDeptId='" . addslashes($var_deptid) . "' and s.nStaffId=d.nStaffId
						   and s.nNotifyAssign='1' and s.vDelStatus='0'";
						   $rs= executeSelect($sql,$conn);
						   while($row=mysql_fetch_array($rs)){
								if($row['vMail'] !=""){
									 $var_email=$row['vMail'];
									 $var_mail_body="";
									 $var_mail_body=$var_emailheader."<br>".TEXT_MAIL_START."&nbsp;".$row['vStaffname'].",<br>".
									 $var_mail_body .= "<br><br>";
									 $var_mail_body .= TEXT_TICKET_REFERENCE_NUMBER . " : " . $var_refno ."<br>".TEXT_MAIL_THANK. "<br>" . $var_helpdesktitle ."<br><br>".$var_emailfooter;
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
									 $var_mail_body=TEXT_MAIL_START." ".$row['vStaffname'].",";
									 $var_mail_body .= TEXT_TICKET_REFERENCE_NUMBER . " : " . $var_refno ."  ".TEXT_MAIL_THANK." ". htmlentities($var_helpdesktitle);
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
							
										SMTPMail($var_fromMail,$var_email,$var_smtpserver,$var_port,"",$var_body);
								   }
								   else				
										$mailstatus=@mail($var_email,"",$var_body,$Headers);
								}
						   }

					 //clear the fields
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
					 $var_uploaded_files="";
					 $var_rp ="";
		             $var_tid ="";
		             $var_rid ="";

					 $var_message=MESSAGE_SUCCESS;
                                         $flag_msg="class='msg_success'";
					 $var_refresh=1;
//					 require("./includes/replysent.php");

					 require("./includes/replied.php");  // modified  on 1-11-06 by roshith
					 $replysent=1;
					 //exit;

		 }else{
		   $var_message=$validsave;
                    $flag_msg="class='msg_error'";
		 }

  }else if ($_POST["postback"] == "AT") {
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

			$sql ="select * from sptbl_attachments where vAttachReference='".addslashes($_POST['txtRef'])."'";
			$var_result = executeSelect($sql,$conn);
			if(mysql_num_rows($var_result)>0 or $pos > 0 or $not_allowed_pos_star>0 or $not_allowed_pos_pipe>0){
			   $var_message=MESSAGE_REFNAME_ERROR;
                            $flag_msg="class='msg_error'";
			   mysql_free_result($var_result);
			}else{

				    if($_SESSION['ses_test']==$var or $var==""){
						    $var_maxfilesize="1000000000000";


							$uploadstatus=upload("txtUrl","./attachments/","","all",$var_maxfilesize);
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
                                                          $flag_msg="class='msg_error'";
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
					     $file_name=$_FILES['txtUrl']['name'];
					     if($var_uploaded_files==""){
							   $var_uploaded_file_name=$_POST['txtRef'];
							   $var_uploaded_files=$file_name."*".$_POST['txtRef'];
					 	 }else{
							   $var_uploaded_files .="|".$file_name."*".$_POST['txtRef'];
				   		 }
					}
			}
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
            $sql="select nDeptid,vTitle,nUserId,tQuestion,vRefNo from sptbl_tickets where nTicketId='".addslashes($var_tid)."' and nUserId='".addslashes($_SESSION["sess_userid"])."'";
		    $result = executeSelect($sql,$conn);
		    if (mysql_num_rows($result) > 0) {
				$var_row = mysql_fetch_array($result);
				$var_deptid= $var_row["nDeptid"];
				$var_tickettitle= $var_row["vTitle"];
				$var_tqusetion=$var_row["tQuestion"];
				$var_refno= $var_row["vRefNo"];
				$var_userid= $var_row["nUserId"];
				$var_replymatter=$var_row["tQuestion"];
				$var_replymatter=$var_signature."\n\n\n\n\n\n\n\n\n\n######################################\n".replacestr($var_replymatter);
			    $var_ereplymatter=$var_signature."\n\n\n\n\n\n\n\n\n\n######################################\n".replacestrforemail($var_replymatter);
			    $var_qtrp=$var_signature."\n\n\n\n\n\n\n\n\n\n######################################\n".$var_replymatter;

			}
			else {
				$var_message = MESSAGE_RECORD_ERROR;
                                $flag_msg="class='msg_error'";
			}


 }else  if($var_rp=="q" and $var_rid !="0"){
             $sql  ="select r.tReply,nDeptid,t.vTitle,t.nUserId,t.tQuestion,t.vRefNo,t.vStatus from sptbl_replies as r, ";
		     $sql .="sptbl_tickets as t where r.nTicketId=t.nTicketId and r.nReplyId='".addslashes($var_rid)."' and t.nUserId='".addslashes($_SESSION["sess_userid"])."'";
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
				$var_replymatter=$var_signature."\n\n\n\n\n\n\n\n\n\n######################################\n".replacestr($var_replymatter);
		       $var_ereplymatter=$var_signature."\n\n\n\n\n\n\n\n\n\n######################################\n".replacestrforemail($var_replymatter);
		        $var_qtrp=$var_signature."\n\n\n\n\n\n\n\n\n\n######################################\n".$var_replymatter;

			}
			else {
				$var_message = MESSAGE_RECORD_ERROR;
                                $flag_msg="class='msg_error'";

			}

}else  if($var_rp=="r"){

		    $sql="select nDeptid,vTitle,nUserId,tQuestion,vRefNo,vStatus from sptbl_tickets where nTicketId='".addslashes($var_tid)."' and nUserId='".addslashes($_SESSION["sess_userid"])."'";
		    $result = executeSelect($sql,$conn);
		    if (mysql_num_rows($result) > 0) {
				$var_row = mysql_fetch_array($result);
				$var_deptid= $var_row["nDeptid"];
				$var_tickettitle= $var_row["vTitle"];
				$var_refno= $var_row["vRefNo"];
				$var_tqusetion=$var_row["tQuestion"];
				$var_userid= $var_row["nUserId"];
				$var_status=$var_row["vStatus"];
				//$var_replymatter=$var_row["tQuestion"];
				$var_replymatter=$var_signature."\n\n";
			$var_ereplymatter=$var_replymatter;

			}
			else {
				$var_message = MESSAGE_RECORD_ERROR;
                                $flag_msg="class='msg_error'";
			}


}

function validateSave($var_tid){
	   global $conn;

	   $var_message="1";
	   if(trim($_POST['txtRpMatter'])=="" ){
	      $var_message = MESSAGE_RECORD_EMPTY_MATTER_ERROR;
              $flag_msg="class='msg_error'";
		  return $var_message;
	   }
	   $sql="select nDeptid,nOwner,nLockStatus,vTitle,nUserId,tQuestion,vStatus from sptbl_tickets where nTicketId='".addslashes($var_tid)."'";
	   $sql .=" and vDelStatus='0' and nUserId='".addslashes($_SESSION["sess_userid"])."'";

	   $result = executeSelect($sql,$conn);

	   if (mysql_num_rows($result) > 0) {
			;

       }
		else {
			$var_message = MESSAGE_RECORD_ERROR;
                        $flag_msg="class='msg_error'";
		}

	    return $var_message;
	}

        

?>
<?php if($replysent != 1) { ?>
<form name="frmReplies" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
<div class="content_section">
<Div class="content_section_title"><h3><?php echo TEXT_REPLIES ?></h3></Div>
<div class="content_section_data">
   
         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
		<tr>
         <td width="100%" align="center" colspan=3 class="fieldnames">
         <?php echo TEXT_FIELDS_MANDATORY ?></td>
         </tr>
         <tr>
         <td width="100%" align="center" colspan=3 class="errormessage">
         <div <?php echo $flag_msg; ?>><?php echo $var_message;  ?></div></td>
         </tr>
                       <tr>
						    <td colspan=3 align="center">
							 <div class="content_section_subtitle " align="left"><h3><?php echo TEXT_REPLY?></h3></div>
		                      
							  <table border=0 width="100%" align="center">
								 <tr>
										    <td width="15%" align="left" class="fieldnames" valign="top"><?php echo TEXT_REPLAY_MATTER?>&nbsp;<span class="required">*</span></td>
										    <td width="2%">&nbsp;</td>
										    <td width="83%"  align="left">
               										<!--<textarea name="txtRpMatter" cols="50" rows="15" id="txtRpMatter" class="textarea" style="width:550px;"><?php //echo htmlentities($var_replymatter); ?></textarea>-->

                                                                                        <?php                  $sBasePath                      = "FCKeditor/";
                                                                                                                $oFCKeditor 					= new FCKeditor('txtRpMatter') ;
                                                                                                                $oFCKeditor->BasePath			= $sBasePath ;
                                                                                                                $oFCKeditor->Value		       = stripslashes($var_replymatter);;
                                                                                                                $oFCKeditor->Width  = '530' ;
                                                                                                                $oFCKeditor->Height = '350' ;
                                                                                                                $oFCKeditor->ToolbarSet="Basic";
                                                                                                                $oFCKeditor->Create() ; ?>
		 									</td>
							    </tr>
							  </table>
							 
		                     </td>
					   </tr>
					   <tr>
						    <td colspan=3 align="center">

							    <div class="content_section_subtitle" align="left"><h3><?php echo TEXT_ATTACHMENTS?></h3></div>
		                                    </br>

													  <table width="100%">
													  <!--<tr>
													    <td align="left" class="fieldnames" width="16%"><?php// echo TEXT_ATTACH_REFERENCE?>&nbsp;</td>
													    <td width="5%">&nbsp;</td>
													    <td align="left"><input name="txtRef" type="text" size="40" maxlength="100" class="textbox" value="<?php// echo htmlentities($var_refname);?>" style="width:310px"></td>
													    <td width="35%">&nbsp;</td>
													  </tr>
													  <tr>
													   <td colspan="4">&nbsp;</td>
													  </tr>-->
													  <tr>
													     <td align="left" class="fieldnames" width="16%"><?php echo TEXT_ATTACH_URL?></td><td width="35%">&nbsp;<?php
                                                                                                                 $var_refname = time(). rand(1,90000);
                                                                                                                 ?>
                                                                                                                 <input name="txtRef" type="hidden"  value="<?php echo htmlentities($var_refname);?>">
					    										<input name="txtUrl" type="file" class="comm_input input_width1" id="txtUrl"  >
																
																<?php 
																if($var==""){ 
																	$var=0;
																}else{
																	$var=$var+1;
																}
																$_SESSION['ses_test'] = $var ; 
															?>&nbsp;&nbsp;&nbsp;
															<input type=hidden name=varrefresh value="<?php echo   $var?>">														
															<input name="btnSubmit" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ATTACH?>" onClick="javascript:attach();">
																</td>
													    
													    
													    <td colspan=4 align=left>
															
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
																			    unlink("./attachments/".$picarry[0]);
																		 }
																		 $var_uploaded_files_arr = array_diff($total_uploaded_file,$remove_array);
																		 $total_uploaded_file =array_diff($total_uploaded_file,$remove_array);
												                         $var_uploaded_files=implode("|",$var_uploaded_files_arr);
																	   }

													     	  if($var_uploaded_files !=""){
													   ?>
													                     <tr><td colspan=4>
													                     <table width='80%' border=1 align="center">
													   <?php


																       foreach($total_uploaded_file as $key=>$value){
																	   $spli_name_file=explode("*",$value);
																	   $disp_name_file=$spli_name_file[1]."(".$spli_name_file[0].")";



													  ?>
													  					   <tr>
																		      <td width="5%">
																			     <input type="checkbox" name="chk[]" id="u<?php echo($key); ?>" value="<?php echo htmlentities($value) ?>" class="checkbox">

																			  </td>
													    					  <td width="90%" class="fieldnames"><?php  echo htmlentities($disp_name_file); ?></td>
																			  <td width="5%"><a href="javascript:remove('<?php  echo addslashes(htmlentities($value)); ?>');"><img src="./images/delete.gif" width="13" height="13" border="0" title="<?php echo TEXT_TITLE_DELETE ?>"></a></td>
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


													 <input type="hidden" name="attrid" value="<?php echo $var_attrid; ?>">
													  <input type="hidden" name="uploadedfiles" value="<?php echo htmlentities($var_uploaded_files); ?>">
													  <input type="hidden" name="uploadedfile_name" value="<?php echo $var_uploaded_file_name; ?>">
													  </table>

													


							</td>
					  </tr>
					  <tr><td colspan="4">&nbsp;</td></tr>
                              </table>
                        
						
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td ><img src="./../images/spacerr.gif" width="1" height="1"></td>
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
                                  <tr align="center"  class="listingbtnbar">
                                    <td width="16%">&nbsp;</td>
                                    <td width="16%">
                                        <script>
<?php
		if ($var_tid != "") {

			echo("document.frmReplies.btAdd.disabled=false;\n");
			echo("document.frmReplies.btnSubmit.disabled=false;");


		}

		else {
			echo("document.frmReplies.btAdd.disabled=true;");

		}
	?>
        </script>
                                        <input name="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_REPLY; ?>" onClick="javascript:save();"></td>

                                    <td width="20%">
									<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
									<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
									<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
									<input type="hidden" name="qtrp" value="<?php echo(htmlentities($var_qtrp)); ?>">


									<input type="hidden" name="tquestion" value="<?php echo htmlentities($var_tqusetion); ?>">
									<input type="hidden" name="blockrefresh" value="<?php echo($var_refresh); ?>">
									<input type="hidden" name="txtDeptId" value="<?php echo($var_deptid); ?>">
									<input type="hidden" name="refno" value="<?php echo($var_refno); ?>">
                                                                        <input type="hidden" name="userid" value="<?php echo($var_userid); ?>">
									<input type="hidden" name="rp" value="<?php echo($var_rp); ?>">
									<input type="hidden" name="tk" value="<?php echo($var_tid); ?>">
									<input type="hidden" name="rid" value="<?php echo($var_rid); ?>">
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
				</td>
              </tr>
            </table>
			<?php if($var_tickettitle !=""){ ?>

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
                              <td>
							  <table width="100%" cellpadding="0" cellspacing="0" border="0">
							  	<tr>
									<td width="100%" align="center">
										<div  style="width: 100%;overflow: auto;border: 1px solid #666;padding: 2px;">
											<table width="100%" cellpadding="0" cellspacing="0" height="200" style="height:200px;">
												<tr>
													<td width="100%" valign="top">


														<table width="100%"  border="0" cellspacing="0" cellpadding="0">
															<tr><td width="100%" class="listingheadright"></td></tr>
															<tr><td width="100%" class="heading"><?php echo TEXT_TICKET_DETAILS; ?></td></tr>
														<tr>
														  <td>
														   <?php
                                                                                                                 
                                                                                                                   require("./includes/ticketdisplay.php");

                                                                                                                   
                                                                                                                   ?>
                                                                                                                     
														   </td>
														</tr>
														<tr><td>&nbsp;</td></tr>
														  </table>
													</td>
												</tr>
											</table>
										</div>
									</td>
								</tr>
							  </table>
							   </td>
                            </tr>
							<tr><td>&nbsp;</td></tr>
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
			<?php
                        


                        } ?>
		
		</div>
</div>
</form>
<?php } ?>
