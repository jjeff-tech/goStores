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
	
	if ($_POST["postback"] == "SA") {
		/*
		$sql = "Select * from sptbl_lookup where vLookUpName IN('MailFromName','MailFromMail','MailReplyName','MailReplyMail')";
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
				}
			}
		}
		mysql_free_result($result);

		$var_body = $_POST["txtBody"];
		$var_subject = $_POST["txtSubject"];
		$var_mail_to  = $_POST["txtTo"];
		
		$Headers="From: $var_fromName <$var_fromMail>\n";
		$Headers.="Reply-To: $var_replyName <$var_replyMail>\n";
		$Headers.="MIME-Version: 1.0\n";
		$Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		mail($var_mail_to,$var_subject,$var_body,$Headers);
		
		$var_message = TEXT_EMAIL_SENT;
		$var_body = "";
		$var_subject = "";
		$var_mail_to  = "";
		* 
		* 
		*/
		$var_body = $_POST["txtBody"];
		$var_subject = $_POST["txtSubject"];
		$var_email_to =  $_POST["txtTo"];
		//echo  ($var_email_to) ;
		
		
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
		
		$var_mail_body  = $var_emailheader."<br>".
		$var_mail_body .= nl2br(htmlentities($var_body)) ."<br>";
		$var_mail_body .= "<br>";
		$var_mail_body .= $var_emailfooter;
		
		$var_body = $var_mail_body;
		
		$Headers="From: $var_fromName <$var_fromMail>\n";
		$Headers.="Reply-To: $var_replyName <$var_replyMail>\n";
		$Headers.="MIME-Version: 1.0\n";
		$Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

		// it is for smtp mail sending		
		if($_SESSION["sess_smtpsettings"] == 1){
			$var_smtpserver = $_SESSION["sess_smtpserver"];
			$var_port = $_SESSION["sess_smtpport"];

			SMTPMail($var_fromMail,$var_email_to,$var_smtpserver,$var_port,$var_subject,$var_body);
		}
		else
			@mail($var_email_to,$var_subject,$var_body,$Headers);

		$var_body = "";
		$var_subject= "";
		$var_email_to = "";
		$var_message = TEXT_EMAIL_SENT;
                $flag_msg    = 'class="msg_success"';
	}
?>
<form name="frmMail" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>">
	<div class="content_section">
			<div class="content_section_title">
				<h3><?php echo HEADING_EMAIL_USER ?></h3>
			</div>
<table width="100%"  border="0">
  <tr>
    <td width="76%" valign="top">
    <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="column1">
     <tr>
     <td>
         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">

                 <tr>
         <td align="center" colspan=3 >&nbsp;</td>

         </tr>

		<tr>
			<td>&nbsp;</td>
         <td align="left" colspan=2 class="toplinks">
         <?php echo TEXT_FIELDS_MANDATORY ?></td>

         </tr>

         <tr>
         <td align="center" colspan=3 <?php echo $flag_msg; ?>>
         <?php echo $var_message ?></td>

         </tr>


<tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="2%" align="left">&nbsp;</td>
         <td width="21%" align="left" class="toplinks"><?php echo TEXT_TO?> <font style="color:#FF0000; font-size:9px">*</font> </td>
         <td width="77%" align="left">
         <input name="txtTo" type="text" class="comm_input input_width2" id="txtTo" size="72" maxlength="100" value="<?php echo htmlentities($var_email_to); ?>" style="font-size:11px;width:500px">
         </td>
                      </tr>


			<tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="2%" align="left">&nbsp;</td>
         <td width="21%" align="left" class="toplinks"><?php echo TEXT_EMAIL_SUBJECT?> <font style="color:#FF0000; font-size:9px">*</font> </td>
         <td width="77%" align="left">
         <input name="txtSubject" type="text" class="comm_input input_width2" id="txtSubject" size="72" maxlength="100" value="<?php echo htmlentities($var_subject); ?>" style="font-size:11px;width:500px">
         </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks" valign="top"><?php echo TEXT_EMAIL_BODY ?> <font style="color:#FF0000; font-size:9px">*</font></td>
                      <td width="77%" align="left">
                        <textarea name="txtBody" cols="71" rows="12" id="txtBody" class="comm_input input_width2" style="font-size:11px;width:500px"><?php echo htmlentities($var_body); ?></textarea></td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
                              </table>
                        </td>
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
                                    <td width="22%">&nbsp;</td>
                                    <td width="16%">&nbsp;</td>
                                    <td width="14%"><input name="btSend" type="button" class="comm_btn" value="<?php echo TEXT_EMAIL_BUTTON; ?>"  onClick="javascript:sendMail();"></td>
                                    <td width="16%"><input name="btCancel" type="button" class="comm_btn_greyad" value="<?php echo BUTTON_TEXT_CLEAR; ?>" onClick="javascript:cancel();"></td>
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
          </td>

  </tr>
</table>
</div>
</form>