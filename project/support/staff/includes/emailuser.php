<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>    		                      |
// |          									                          |
// +----------------------------------------------------------------------+

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
    if ($_POST["postback"] == "CT") {
      $var_tmplate_id=$_POST['cmbTemplate'];

	 //$var_qtrp=$_POST['qtrp'];


	    $var_body = $_POST["txtBody"];
		$var_subject = $_POST["txtSubject"];
		$var_email_to =  $_POST["txtTo"];
		$var_email_cc=$_POST["txtToCC"];

     $sql="select vTemplateTitle,tTemplateDesc from sptbl_templates where vStatus='1' and nTemplateId='".mysql_real_escape_string($var_tmplate_id)."'";
	 $result = executeSelect($sql,$conn);
		if (mysql_num_rows($result) > 0) {
			$var_row = mysql_fetch_array($result);
			$var_templatedesc = $var_row["tTemplateDesc"];
			$var_templatetitle = $var_row["vTemplateTitle"];
			$var_body = "------$var_templatetitle------\n".$var_templatedesc."\n\n\n".$var_replymatter;
		}
  }else	if ($_POST["postback"] == "SA") {
		$var_body = $_POST["txtBody"];
		$var_subject = $_POST["txtSubject"];
		$var_email_to =  $_POST["txtTo"];
		$flag = true;
		//echo  ($var_email_to) ;

		$sql = " Select * from sptbl_lookup where vLookUpName IN('Post2PostGap','MailFromName','MailFromMail',";
		$sql .="'MailReplyName','MailReplyMail','Emailfooter','Emailheader','AutoLock')";
//		echo $sql;
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

		$var_mail_body_withoutheader=$var_body;

		$var_mail_body  = $var_emailheader."<br>".
		$var_mail_body .= htmlentities($var_body) ."<br>";
		$var_mail_body .= "<br>";
		$var_mail_body .= $var_emailfooter;

		$var_body = $var_mail_body;
		$Headers_CC="";
        if($_POST['txtToCC'] !="" ){
         $Headers_CC="CC: ".$_POST['txtToCC']."\n";
		}
		$Headers="From: $var_fromName <$var_fromMail>\n";
		$Headers.="Reply-To: $var_replyName <$var_replyMail>\n";
		$Headers.=$Headers_CC;
		$Headers.="MIME-Version: 1.0\n";
		$Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

		/* create ticket for user*/
        if($_POST['rdCreateTicket']=="YES" && !isUniqueEmail($var_email_to,0,0)){ 
		    $flag = false;
		    $var_message .= MESSAGE_NONUNIQUE_EMAIL;
                     $flag_msg = "class='msg_error'";
		}else {
			// it is for smtp mail sending
			if($_SESSION["sess_smtpsettings"] == 1){
				$var_smtpserver = $_SESSION["sess_smtpserver"];
				$var_port = $_SESSION["sess_smtpport"];
	
				SMTPMail($var_fromMail,$var_email_to,$var_smtpserver,$var_port,$var_subject,$var_body);
			}
			else
				@mail($var_email_to,$var_subject,$var_body,$Headers);

			$var_message .= TEXT_EMAIL_SENT;
                         $flag_msg = "class='msg_success'";
		}
		if($flag == true){
			if($_POST['rdCreateTicket']=="YES"){ 

				$var_deptid=mysql_real_escape_string($_POST['cmbDept']);
				$sql = "select * from sptbl_depts Where nDeptId='$var_deptid'";
				$rs = executeSelect($sql,$conn);
				$row = mysql_fetch_array($rs);
				$company=$row['nCompId'];

				//check useralready exist
				$sql = "Select * from sptbl_users where vEmail='" . mysql_real_escape_string($var_email_to) . "'";
				$exc=mysql_query($sql);
				if(mysql_num_rows($exc) >0){
				   $rowuser=mysql_fetch_array($exc);
				   $var_userid=$rowuser['nUserId'];
				   $deptid=$var_deptid;
				   $var_username=$rowuser['vLogin'];
				   $title=$var_subject;
				   $varclip="";
				   $qstion_user="";
				   //$qstion=" Ticket posted by staff for ".$var_username." \n".$var_mail_body_withoutheader;
				   $qstion=$var_mail_body_withoutheader;
	
				   $sql_insert_ticket ="insert into sptbl_tickets(nTicketId,nDeptId,vRefNo,nUserId,vUserName,vTitle,tQuestion,vPriority,dPostDate,vMachineIP,dLastAttempted)";
				   $sql_insert_ticket .="values('','$deptid','1','$var_userid','".mysql_real_escape_string($var_username)."','".mysql_real_escape_string($title)."','";
				   $sql_insert_ticket .=mysql_real_escape_string($qstion)."','$priority',now(),'$varclip',now())";
				   mysql_query($sql_insert_ticket);
				   $var_insert_id = mysql_insert_id($conn);
				   $var_ticketid = $var_insert_id;
                                   insertStattics($var_ticketid);
	
	//update reference number
	//	modified on 15-11-06 by roshith	for constatnt length ref.no.
	
	// 'zero' added for 2 digit companyid
					if($company<10)
						$company = "0".$company;
	
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
	//			   $var_refno=$company."-".$deptid."-".$var_userid."-".$var_insert_id;
	
					$var_refno=$company.$deptid.$var_userid.$var_insert_id;
	
				   $sql_update_ticket = "update sptbl_tickets set vRefNo='".$var_refno."' where nTicketId='".$var_ticketid."'" ;
				   executeQuery($sql_update_ticket,$conn);
				}else {
						$var_mailbox = $var_email_to;
						$var_mailbox = substr($var_mailbox,0,strpos($var_mailbox, "@"));
						$var_mailbox = preg_replace("/[^a-z0-9]/i","",$var_mailbox);
						$var_mailbox = (strlen($var_mailbox) > 50)?(substr($var_mailbox,0,50)):$var_mailbox;
						$sql = "Select nUserId from sptbl_users where vLogin='" . mysql_real_escape_string($var_mailbox) . "'";

						while(mysql_num_rows(mysql_query($sql)) > 0) {
							$var_mailbox = uniqid($var_mailbox);
							$sql = "Select nUserId from sptbl_users where vLogin='" . mysql_real_escape_string($var_mailbox) . "'";
						}
						$var_userlogin = $var_mailbox;
						$loginname=$var_userlogin;
						$password=uniqid(false);
						$name=$var_mailbox;
						$email=$var_email_to;
						$sql1  = " INSERT INTO sptbl_users(`nUserId`, `nCompId`,`vUserName`,`vEmail`,`vLogin`,`vPassword`,`dDate`, `vBanned`, `vDelStatus`) ";
						$sql1 .= " VALUES('','".mysql_real_escape_string($company)."', '".mysql_real_escape_string($name)."','".mysql_real_escape_string($email)."','".mysql_real_escape_string($loginname)."','".mysql_real_escape_string(md5($password))."',now(),'0','0')";
						$result1 = executeSelect($sql1,$conn);
						$var_userid=mysql_insert_id();
	
						$deptid=$var_deptid;
						$var_username=$loginname;
						$title=$var_subject;
						$varclip="";
						//$qstion=" Ticket posted by staff for ".$var_username." \n".$var_mail_body_withoutheader;
						$qstion=$var_mail_body_withoutheader;
	
					   $sql_insert_ticket ="insert into sptbl_tickets(nTicketId,nDeptId,vRefNo,nUserId,vUserName,vTitle,tQuestion,vPriority,dPostDate,vMachineIP,dLastAttempted)";
					   $sql_insert_ticket .="values('','$deptid','1','$var_userid','".mysql_real_escape_string($var_username)."','".mysql_real_escape_string($title)."','";
					   $sql_insert_ticket .=mysql_real_escape_string($qstion)."','$priority',now(),'$varclip',now())";

					   mysql_query($sql_insert_ticket);

					   $var_insert_id = mysql_insert_id($conn);
					   $var_ticketid = $var_insert_id;
		//update reference number
		//	modified on 15-11-06 by roshith	for constatnt length ref.no.
		
		// 'zero' added for 2 digit companyid
						if($company<10)
							$company = "0".$company;
		
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
						$var_refno=$company.$deptid.$var_userid.$var_insert_id;
					   $sql_update_ticket = "update sptbl_tickets set vRefNo='".$var_refno."' where nTicketId='".$var_ticketid."'" ;
					   executeQuery($sql_update_ticket,$conn);
				}
				$var_body = "";
				$var_subject= "";
				$var_email_to = "";
			}
		}
		/***************************/

                //if($flag == true) header("location:emailuser.php");
	}
?>
<script>

function clickradioyes(){
  document.getElementById("trDepartments").style.display="";
}
function clickradiono(){
  document.getElementById("trDepartments").style.display="none";
}
function changetemplate(){
	   document.frmMail.postback.value="CT";
  	   document.frmMail.method="post";
	   document.frmMail.submit();

}
</script>
<form name="frmMail" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>">

<div class="content_section">

 <div class="content_section_title">
	<h3><?php echo HEADING_EMAIL_USER ?></h3>
	</div>
     
     
     
     

     
	 
	 
         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
		<tr>
         <td width="100%" align="center" colspan=3 class="toplinks">
         <?php echo TEXT_FIELDS_MANDATORY ?></td>
         </tr>
         <tr>
         <td width="100%" align="center" colspan=3 class="messsagebar"><div <?php echo $flag_msg; ?>><?php echo $var_message ?></div></td>
         </tr>
         <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="13%" align="left">&nbsp;</td>
         <td width="26%" align="left" class="toplinks"><?php echo TEXT_TO?> <font style="color:#FF0000; font-size:9px">*</font> </td>
         <td width="61%" align="left">
         <input name="txtTo" type="text" class="comm_input input_width1a" id="txtTo" size="72" maxlength="100" value="<?php echo htmlentities($var_email_to); ?>" style="font-size:11px; ">
         </td>
         </tr>
         <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="13%" align="left">&nbsp;</td>
         <td width="26%" align="left" class="toplinks"><?php echo TEXT_EMAIL_CC?>  </td>
         <td width="61%" align="left">
         	<input name="txtToCC" type="text" class="comm_input input_width1a" id="txtToCC" size="72" maxlength="100" value="<?php echo htmlentities($var_email_cc); ?>" style="font-size:11px; ">
         </td>
         </tr>

		<tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="13%" align="left">&nbsp;</td>
         <td width="26%" align="left" class="toplinks"><?php echo TEXT_EMAIL_SUBJECT?> <font style="color:#FF0000; font-size:9px">*</font> </td>
         <td width="61%" align="left">
         <input name="txtSubject" type="text" class="comm_input input_width1a" id="txtSubject" size="72" maxlength="100" value="<?php echo htmlentities($var_subject); ?>" style="font-size:11px; ">
         </td>
          </tr>
          <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="13%" align="left">&nbsp;</td>
         <td width="26%" align="left" class="toplinks"><?php echo TEXT_EMAIL_CREATETICKET?>  </td>
         <td width="61%" align="left">
           <input class=checkbox type=radio name="rdCreateTicket" value="YES" onclick="clickradioyes()" <?php if($_POST['rdCreateTicket']=="YES") echo "checked";?>><?php echo TEXT_EMAIL_CREATETICKET_VALUE_YES?>
           <input class=checkbox type=radio name="rdCreateTicket" value="NO" onclick="clickradiono()" <?php if($_POST['rdCreateTicket']!="YES") echo "checked";?>><?php echo TEXT_EMAIL_CREATETICKET_VALUE_NO?>
         </td>
         </tr>
         <tr><td colspan="3">&nbsp;</td></tr>
        <tr id="trDepartments">
         <td width="13%" align="left">&nbsp;</td>
         <td width="26%" align="left" class="toplinks"><?php echo TEXT_EMAIL_DEPARTMENT?>  </td>
         <td width="61%" align="left">
			<?php
			$leafdeptarr=getLeafDepts();
			if($leafdeptarr !=""){
			$leaflvldeptids=implode(",",$leafdeptarr);
			}else{
			$leaflvldeptids=0;
			}
				?>
 			<select name="cmbDept" size="1" class="comm_input input_width1a" id="cmbDept" style="width:250px;">

			<?php
			$sql = "select d.vDeptMail,d.nDeptId,d.vDeptCode,d.vDeptDesc from sptbl_depts d
			 Where d.nDeptId IN($leaflvldeptids)";
			$rs = executeSelect($sql,$conn);

			while($row = mysql_fetch_array($rs)) {
			$options ="<option value='".$row['nDeptId']."'";
			if ($var_deptid == $row['nDeptId']){

			$options .=" selected=\"selected\"";
			}
			$options .=">[".htmlentities($row['vDeptCode'])."]&nbsp;".htmlentities($row['vDeptDesc'])."</option>\n";
			echo $options;
			}
			mysql_free_result($rs) ;
			?>
         </select>
         </td>
         </tr>
                       <tr><td colspan="3">&nbsp;</td></tr>

                       <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks" valign="top"><?php echo TXT_TEMPLATE ?> </td>
                      <td width="61%" align="left">
                       <?php
														     $sql = "select * from sptbl_templates where vStatus='1' order by vTemplateTitle";
								 							 $rs = executeSelect($sql,$conn);
									?>
	                                <select name="cmbTemplate" size="1" class="comm_input input_width1a" id="cmbTemplate" style="width:200px;" onchange="changetemplate();">
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

                      </tr>

 						<tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks" valign="top"><?php echo TEXT_EMAIL_BODY ?> <font style="color:#FF0000; font-size:9px">*</font></td>
                      <td width="61%" align="left">
                        <textarea name="txtBody" cols="65" rows="12" id="txtBody" class="textarea" style="width:430px;"><?php echo htmlentities($_POST["txtBody"]); ?></textarea></td>
                      </tr>
                      
                              </table>
                      
                  
                  
				  
				
            <table width="100%"  border="0" cellspacing="10" cellpadding="0">
              <tr>
                <td>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td ><table width="100%"  border="0" cellspacing="0" cellpadding="5" >
                            <tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center"  class="whitebasic">
                                    <td width="22%">&nbsp;</td>
                                    <td width="16%">&nbsp;</td>
                                    <td width="14%"><input name="btSend" type="button" class="comm_btn" value="<?php echo TEXT_EMAIL_BUTTON; ?>"  onClick="javascript:sendMail();"></td>
                                    <td width="16%"><input name="btCancel" type="reset" class="comm_btn" value="<?php echo BUTTON_TEXT_CLEAR; ?>" onClick="return clearForm(this.form);"></td>
                                    <td width="12%">&nbsp;</td>
                                    <td width="20%">
									<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
									<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
									<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
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
    </div>
</form>
<script>
<?php if($_POST['rdCreateTicket']=="YES"){ ?>
   document.getElementById("trDepartments").style.display="";
<?php }else{ ?>
 document.getElementById("trDepartments").style.display="none";
 <?php } ?>
</script>