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
	
	$var_staffid = $_SESSION["sess_staffid"];
	
    if ($_POST["postback"] == "SM") {
			$var_tosave = $_POST["tosave"];
			$var_title = mysql_real_escape_string(trim($_POST["txtTitle"]));
			$var_desc = mysql_real_escape_string(trim($_POST["txtDesc"]));
			
			//email notification settings
			$sql = "Select * from sptbl_lookup where vLookUpName IN('MailFromName','MailFromMail','MailReplyName','MailReplyMail','Emailfooter','Emailheader','HelpdeskTitle')";
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
						case "HelpdeskTitle":
										$var_helpdesktitle = $row["vLookUpValue"];
										break;											
					}
				}
			}
			mysql_free_result($result);	
			$var_body  = $var_emailheader."<br>".
			$var_body .= MESSAGE_PVT_MESSAGE_ARRIVAL . htmlentities($_SESSION["sess_staffname"]) . "<br> of the title " . htmlentities($var_title) . "<br>";
			$var_body .= PVT_MESSAGE_SALUTATION  . "<br>" . htmlentities($var_helpdesktitle);
			$var_body .= $var_emailfooter;
			
			$var_subject = MESSAGE_PVT_MESSAGE_SUBJECT . $_SESSION["sess_staffname"];
			$Headers="From: $var_fromName <$var_fromMail>\n";
			$Headers.="Reply-To: $var_replyName <$var_replyMail>\n";
			$Headers.="MIME-Version: 1.0\n";
			$Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
			//end email notification settings
			
			
			//echo($var_cmbStaff . "1<br>" . $var_cmbSelected . "2<br>" . $var_tosave ."3<br>");	
			$arr_userid = explode(",",$var_tosave);
			for($i=0;$i<count($arr_userid);$i++) {
				$sql = "Insert into sptbl_pvtmessages(nPMId,vPMTitle,tPMDesc,nFrmStaffId,nToStaffId,dDate,vStatus) 
						Values('','$var_title','$var_desc','$var_staffid','$arr_userid[$i]',now(),'o')";
				executeQuery($sql,$conn);		
				
				$var_new_id = mysql_insert_id();
				//Modified on December 21, 2005 -- Jimmy
				if(logActivity()) {
					$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_ADDITION . "','PrivateMessages','" . mysql_real_escape_string($var_new_id) . "',now())";			
					executeQuery($sql,$conn);
				}
				//notify on pvt message - added on December 27, 2005 
				$sql = "Select vMail,nNotifyPvtMsg from sptbl_staffs where nStaffId='" . mysql_real_escape_string($arr_userid[$i]) . "'";
				$result = mysql_query($sql) or die(mysql_error());
				if(mysql_num_rows($result) > 0) {
					$row = mysql_fetch_array($result);
					if($row["nNotifyPvtMsg"] == "1" && $row["vMail"] != "") {
						// it is for smtp mail sending
						if($_SESSION["sess_smtpsettings"] == 1){			
							$var_smtpserver = $_SESSION["sess_smtpserver"];
							$var_port = $_SESSION["sess_smtpport"];
				
							SMTPMail($var_fromMail,$row["vMail"],$var_smtpserver,$var_port,$var_subject,$var_body);
						}
						else				
							@mail($row["vMail"],$var_subject,$var_body,$Headers);
					}
				}
			}
			$var_message = MESSAGE_RECORD_SENT ;
			$var_title = stripslashes($var_title);
			$var_desc = stripslashes($var_desc);
	}
	
?>
<form name="frmPvtMessage" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>">
<div class="content_section">
			<div class="content_section_title">
				<h3><?php echo TEXT_ADD_PVT_MESSAGE ?></h3>
			</div>
			
<table width="100%"  border="0">
  <tr>
    <td width="76%" valign="top">
    <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">

                
    	<tr>
         <td align="center" colspan=3 class="errormessage">
         <?php

			if ($var_message != ""){
			?>
				<div class="msg_success">
			<b><?php echo($var_message); ?></b>
			</div>
			<?php
			}
			?>			
		 </td>

         </tr>
		        
                      
                    
					  <tr><td colspan=3 align=center>
					  <div class="content_section_data">
					  <table width="100%" align=center>
					  

					  <tr>
                      <td width="40%" align="left" class="whitebasic">
					  	<div class="content_section_title">
							<h4> <?php echo TEXT_SELECT_STAFF ?></h4>
						</div>
					
					                 <select name="cmbStaff" multiple size=20 style=" border: 1px solid #CFCFCF; width: 410px;" class="textarea">
									 <?php
						     				$sql = "Select nStaffId,vStaffname from sptbl_staffs where vDelStatus='0' ORDER By vStaffname ";
											$rs = executeSelect($sql,$conn);
						  	 				while($row = mysql_fetch_array($rs)) {
											  $options ="<option value='".$row['nStaffId']."'";
											 
                                              $options .=">".htmlentities($row['vStaffname']) ."</option>\n";
											  echo $options;
											}
                                     ?>    
						</select>
					  </td>
					  <td width="13%">
							 <table align="center">
							        
							 	      <tr>
									      <td>
										  <input type="button" value=">" style="width:40px;" class="comm_btn" onclick="alloted(this.form);" >
										  </td>
									   </tr>
									   <tr>
									      <td>
										  <input type="button" class="comm_btn" style="width:40px;" value="<" onclick="availbaletoalloted(this.form);">
										  </td>
									   </tr>
									   <tr>
									      <td>
										  <input type="button" class="comm_btn" style="width:40px;" value=">>" onclick="makeavailableall(this.form);">
										  </td>
									   </tr>
									   <tr>
									      <td>
										  <input type="button" class="comm_btn" style="width:40px;" value="<<" onclick="makeallottedall(this.form);">
										  </td>
									   </tr>
							    </table>
					  </td>
					 <td width="47%" align="left" class="whitebasic">
					 <div class="content_section_title">
							<h4> <?php echo TEXT_SELECTED_STAFF ?></h4>
						</div>
					
                         <select multiple name="cmbSelected" style="border: 1px solid #CFCFCF; width: 410px;" size=20 class="textarea">
						</select>
					  </td>
                      </tr>
					 </table>
					  
					  
					  
					  </div>
					  
					  </td></tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
					  
					  
					  <tr>
         <td width="6%" align="left">&nbsp;</td>
         <td width="10%" align="left" class="toplinks"><?php echo TEXT_MESSAGE_TITLE?> </td>
         <td width="84%" align="left">
         <input name="txtTitle" type="text" class="comm_input" id="txtTitle" size="72" maxlength="100" value="<?php echo htmlentities($var_title); ?>" style="font-size:11px;width:767px">
         </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks" valign="top"><?php echo TEXT_MESSAGE_DESC ?></td>
                      <td width="84%" align="left">
                        <textarea name="txtDesc" cols="71" rows="12" id="txtDesc" class="textarea" style="font-size:11px;width:765px"><?php echo htmlentities($var_desc); ?></textarea></td>
                      </tr>
                      <tr><td colspan="3" class="btm_brdr">&nbsp;</td></tr>
					  
					  
					  
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
                                    <td><input name="btSend" type="button" class="comm_btn" value="<?php echo TEXT_SEND_BUTTON; ?>" onClick="javascript:saveMe(this.form);">
									  &nbsp;
									  <input name="btCancel" type="button" class="comm_btn_greyad" value="<?php echo BUTTON_TEXT_CLEAR; ?>" onClick="javascript:clickCancel(this.form);">
									  <input type=hidden name="tosave">
									  <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
									  <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
									  <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">																
									  <input type="hidden" name="id" value="<?php echo($var_id); ?>">
									  <input type="hidden" name="postback" value="">									</td>
                                  </tr>
                              </table></td>
                            </tr>
                        </table></td>
                        <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                    </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                  </table></td>
              </tr>
            </table>
      </td>
  </tr>
</table>

</form>