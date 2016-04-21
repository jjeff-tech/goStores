<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com> ,mahesh.s@armia.com              |
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
	
	$var_staffid = $_SESSION['sess_staffid'];
	if ($_POST["postback"] == "SM") {
	    if(trim($_POST['tosave'])=="" or trim($_POST['txtTitle'])=="" or trim($_POST['txtDesc'])==""){
		  $var_message =  MESSAGE_RECORD_ERROR;
                   $flag_msg = "class='msg_error'";
		}
	    else{
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
			$var_body .= MESSAGE_PVT_MESSAGE_ARRIVAL . stripslashes($_SESSION["sess_staffname"]) . "<br> of the title " . stripslashes($var_title) . "<br>";
			$var_body .= PVT_MESSAGE_SALUTATION . "<br>" . stripslashes($var_helpdesktitle);
			$var_body .= $var_emailfooter;
			
			$var_subject = MESSAGE_PVT_MESSAGE_SUBJECT . htmlentities($_SESSION["sess_staffname"]);
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
				if(logActivity()) {
				$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . mysql_real_escape_string(TEXT_ADDITION) . "','PrivateMessages','" . mysql_real_escape_string($var_new_id) . "',now())";
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
			$var_message = MESSAGE_RECORD_SENT;
                        $flag_msg = "class='msg_success'";
			$var_title = stripslashes($var_title);
			$var_desc = stripslashes($var_desc);
	   }
	}
	
?>
<form name="frmPvtMessage" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>">

<div class="content_section">

	 
	  <div class="content_section_title">
	<h3><?php echo TEXT_ADD_PVT_MESSAGE ?></h3>
	</div>
	  <div class="content_section_data">
     <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="column1">
     <tr>
     <td>   
         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
           <tr>
	         <td align="center" colspan=2 >&nbsp;</td>
	       </tr>
    	<tr>
         <td align="center" colspan=2 class="messsagebar">
       <div <?php echo $flag_msg; ?>>  <?php echo  $var_message; ?></div></td>

         </tr>
					  <tr><td colspan=2 align=center><table width="63%" border=0 align=center>					  
					  <tr>
                      <td align="left" class="listingmaintext"><?php echo TEXT_SELECT_STAFF ?><br>
					                 <select name="cmbStaff" multiple size=20 style="width:200px;height:300px" class="comm_input input_width1" >
									 <?php
						     				$sql = "Select nStaffId,vStaffname from sptbl_staffs where vDelStatus='0' and nStaffId !='$var_staffid' ORDER By vStaffname ";
											$rs = executeSelect($sql,$conn);
						  	 				while($row = mysql_fetch_array($rs)) {
											  $options ="<option value='".$row['nStaffId']."'";
											 
                                              $options .=">".htmlentities($row['vStaffname']) ."</option>\n";
											  echo $options;
											}
                                     ?>    
						</select>
					  </td>
					  <td>
							 <table border=0>
							        
							 	      <tr>
									      <td>
										  <input type="button" value=">"  class="button" onclick="alloted(this.form);" style="width:40px;">
										  </td>
									   </tr>
									   <tr>
									      <td>
										  <input type="button" class="button" value="<" onclick="availbaletoalloted(this.form);"  style="width:40px;">
										  </td>
									   </tr>
									   <tr>
									      <td>
										  <input type="button" class="button"  value=">>" onclick="makeavailableall(this.form);"  style="width:40px;">
										  </td>
									   </tr>
									   <tr>
									      <td>
										  <input type="button" class="button"  value="<<" onclick="makeallottedall(this.form);"  style="width:40px;">
										  </td>
									   </tr>
							    </table>
					  </td>
					 <td width="61%" align="left" class="listingmaintext"><?php echo TEXT_SELECTED_STAFF ?><span class="errormessage">*</span><br>
                         <select multiple name="cmbSelected" style="width:200px;height:300px" size=20  class="comm_input input_width1">
						</select>
					  </td>
                      </tr>
					 </table></td></tr>
                      <tr><td colspan="2">&nbsp;</td></tr>
					  
					  
					  <tr>
         <td width="19%" align="left"><?php echo TEXT_MESSAGE_TITLE?> <span class="listingmaintext"><span class="errormessage">*</span></span></td>
         <td width="81%" align="left">
         <input name="txtTitle" type="text" class="comm_input input_width1" id="txtTitle" size="60" maxlength="100" style="font-size:11px;width:550px;">
         </td>
                      </tr>
                      <tr><td colspan="2">&nbsp;</td></tr>
                      <tr>
                      <td align="left" valign="top"><?php echo TEXT_MESSAGE_DESC ?><span class="listingmaintext"><span class="errormessage">*</span></span></td>
                      <td width="81%" align="left">
                        <textarea name="txtDesc" cols="70" rows="12" id="txtDesc" class="comm_input input_width1" style="width:550px;"></textarea></td>
                      </tr>
                      <tr><td colspan="2">&nbsp;</td></tr>
					  
					  
					  
								</table>
                        </td>
                            </tr>
                        </table>
                  
            <table width="100%"  border="0" cellspacing="10" cellpadding="0">
              <tr>
                <td>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr >
                        
                        <td ><table width="100%"  border="0" cellspacing="0" cellpadding="5" >
                            <tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center"  class="whitebasic">
                                    <td><input name="btSend" type="button" class="comm_btn" value="<?php echo TEXT_SEND_BUTTON; ?>" onClick="javascript:saveMe(this.form);">
									 &nbsp;
									  <input name="btCancel" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_CANCEL; ?>" onClick="javascript:clickCancel(this.form);">
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
                        
                      </tr>
                    </table>
                    </td>
              </tr>
            </table>
			</div>
   
</div>
</form>