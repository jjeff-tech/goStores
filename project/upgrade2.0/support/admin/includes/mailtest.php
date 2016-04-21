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
    //system("mysqldump -u root --password='status' --databases test >>db.txt");
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
	
	$var_staffid = "1";
	if ($_POST["postback"] == "C") {
	    $var_email=addslashes($_POST['txtEmail']);
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
		

		$var_body = $var_emailheader."<br>".addslashes($_POST["txtBody"])."<br>".$var_emailfooter ;
		$var_subject = addslashes($_POST["txtSubject"]);
		/*$Headers="From: $var_fromName <$var_fromMail>\n";
		$Headers.="Reply-To: $var_replyName <$var_replyMail>\n";
		$Headers.="MIME-Version: 1.0\n";
		$Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";*/
                
                //********************HEADERS*************************
                $Headers  = "MIME-Version: 1.0" . "\r\n";
                $Headers .= "Reply-To: ".$var_replyName." <".$var_replyName.">" . "\r\n";
                $Headers .= "Return-Path: ".$var_replyName." <".$var_replyName.">" . "\r\n"; 
                $Headers .= "From: ".$var_fromName." <".$var_fromMail.">" . "\r\n";
                $Headers .= "Organization: ".$var_replyName."\r\n"; 
                $Headers .= "Content-Type: text/html\r\n"; 
                //*********************************************	
                

		// it is for smtp mail sending		
		if($_SESSION["sess_smtpsettings"] == 1){
			$var_smtpserver = $_SESSION["sess_smtpserver"];
			$var_port = $_SESSION["sess_smtpport"];

			SMTPMail($var_fromMail,$var_email,$var_smtpserver,$var_port,$var_subject,$var_body);
			$mailstatus = 1;
		}
		else
			$mailstatus=mail($var_email,$var_subject,$var_body,$Headers);
			
			if($mailstatus){
			  $var_message = MESSAGE_EMAIL_SENT."  $var_email";
                          $flag_msg    = 'class="msg_success"';
			  $var_email = "";
			  $var_body="";
			  $var_subject="";
		    }
		    else {
			 $var_message = MESSAGE_EMAIL_NOT_SENT ;
                         $flag_msg    = 'class="msg_error"';
		    }
	}

?>
<div class="content_section">
<form name="frmEmailtest" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>">
<Div class="content_section_title"><h3><?php echo TEXT_EMAIL_TEST ?></h3></Div>
<Div class="content_section_data">
<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="column1"> 
     <tr>
     <td>
         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">

		<tr>
         <td align="center" colspan=3 class="fieldnames">
         <?php echo TEXT_FIELDS_MANDATORY ?></td>
         </tr>
    	<tr>
         <td align="center" colspan=3 <?php echo $flag_msg; ?>>
         <?php echo $var_message ?></td>

         </tr>
		        <tr><td colspan="3">&nbsp;</td></tr>
				<tr>
					<td>&nbsp;</td>
                        <td class="fieldnames" colspan=3 align=left>
						   *&nbsp;<?php echo TEXT_EMAIL_LINE_1 ?><br>
						   *&nbsp;<?php echo TEXT_EMAIL_LINE_2 ?><br>
						   *&nbsp;<?php echo TEXT_EMAIL_LINE_3 ?><br>
						    *&nbsp;<?php echo TEXT_EMAIL_LINE_4 ?>
						 </td>
				</tr>		
				<tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                        <td align="left">&nbsp;</td>
                       <td align="left" class="fieldnames"><?php echo TEXT_EMAIL ?> <font style="color:#FF0000; font-size:9px">*</font></td>
                      <td width="77%" align="left">
                        <input name="txtEmail" type="text" class="comm_input input_width1" id="txtEmail" size="72" maxlength="100" value="<?php echo htmlentities($var_email); ?>" style="font-size:11px;width:550px">
					  </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
         			  <tr>
				         <td width="9%" align="left">&nbsp;</td>
				         <td width="14%" align="left" class="fieldnames"><?php echo TEXT_EMAIL_SUBJECT?> <font style="color:#FF0000; font-size:9px">*</font> </td>
				         <td width="77%" align="left">
				         <input name="txtSubject" type="text" class="comm_input input_width1" id="txtSubject" size="72" maxlength="100" value="<?php echo TEXT_EMAIL_TEST_SUBJECT ?>" style="font-size:11px;width:550px" readonly>
				         </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="fieldnames" valign="top"><?php echo TEXT_EMAIL_BODY ?> <font style="color:#FF0000; font-size:9px">*</font></td>
                      <td width="77%" align="left">
                        <textarea name="txtBody" cols="71" rows="12" id="txtBody" class="comm_input input_width1" readonly style=" font-size:11px;width:550px"><?php echo TEXT_EMAIL_TEST_BODY ?></textarea></td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>				  
						</table>
                        </td>
                        </tr>
                        </table>
                
           
                   
                        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                 <tr>
		                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
		                                  <tr align="center"  class="listingbtnbar">
		                                    <td width="22%">&nbsp;</td>
		                                    <td width="16%"><input name="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_CHECK ?>" onClick="javascript:check();"></td>
		                                    
		                                    <td width="20%">
											<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
											<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
											<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">																
											<input type="hidden" name="id" value="<?php echo($var_id); ?>">
											<input type="hidden" name="postback" value="">
											</td>
		                                  </tr>
		                              </table></td>
                            </tr>
                              </table>
							
         
</div>
</form>
</div>