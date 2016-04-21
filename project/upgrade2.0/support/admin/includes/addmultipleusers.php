<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshith<roshith@armia.com>    		                      |
// |          									                          |
// +----------------------------------------------------------------------+
	if ($_GET["id"] != "") {
		$var_id = $_GET["id"];
	}
	elseif ($_POST["id"] != "") {
		$var_id = $_POST["id"];
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
	//$var_userid = $_SESSION["sess_staffid"];
	$var_staffid = $_SESSION["sess_staffid"];

	if ($_POST["postback"] == "A") {
		
			$var_message="";
			$invalid_username_flag = 0;
			$nonunique_email_flag  = 0;

			$type=$_FILES['txtUrl']['type'];
                        $size=$_FILES['txtUrl']['size'];
			$var_compId = $_POST["cmbCompanyId"];
			if($size <=0){
			  $var_message .= TEXT_FILE_NOT_CSV_ERROR;
			}else
			/*Modified on 290709*/
			// if($type !="application/octet-stream" && $type != "text/plain" ){
			if($type !="application/octet-stream" && $type != "text/plain"  && $type != "application/vnd.ms-excel" && $type != "text/x-comma-separated-values"   && $type != "text/comma-separated-values" && $type != "text/csv"){
			  $var_message .= TEXT_FILE_TYPE_NOT_SUPPORTED_ERROR;
			}
///////// to prevent executable file uploading
			if ($var_message == ""){
				$filename1	=	$_FILES['txtUrl']['name'];
				$blacklist = array("php", "phtml", "php3", "php4", "js", "shtml", "pl" ,"py", "exe");
				foreach ($blacklist as $file)
				{
					if(preg_match("/\.$file\$/i", "$filename1"))
					{
					   $var_message .= TEXT_FILE_TYPE_NOT_SUPPORTED_ERROR;
					}
				}
			}
////////

                        
                       
			if ($var_message == "") { 

                                $fl = @fopen("../csvfiles/invalidusername$var_compId.txt","w+");
				@fclose($fl);
				$fl = @fopen("../csvfiles/nonuniqueemail$var_compId.txt","w+");
				@fclose($fl);
                                
				$csvfile = $var_compId.".csv";
                                @move_uploaded_file($_FILES['txtUrl']['tmp_name'], "../csvfiles/".$csvfile);
                                $fp = @fopen("../csvfiles/$csvfile","r");
                                $rec_count = 1;
				$line_count = "";
				

					  while($line=fgets($fp,1024)){ 
                                                        $var_invalid_username = 0;
							$var_nonunique_email  = 0;
                                                        if(trim($line)=="") continue;
							$linearray=explode(",",$line);
                                                        if(count($linearray) != 4){
//							  $var_message .= TEXT_MESSAGE_DATA_MISMATCH_ERROR.$rec_count."<br>";
							  $line_count .= $rec_count.",";
							}else{
								$var_userName  =trim($linearray[0]);
								$var_email	   = trim($linearray[1]);
								$var_userLogin = trim($linearray[2]);
								$var_password  = trim($linearray[3]);

								if(!isValidUsername(trim($var_userName))){ 
									$fl = @fopen("../csvfiles/invalidusername$var_compId.txt","a+");
									@fwrite($fl, "$var_userName" .',');
									@fclose($fl);

									$message_user .= "<br>$var_userName";
									$var_invalid_username = 1;
									$invalid_username_flag = 1;
								}
				  //check duplicate email address
								if(!isUniqueEmailUser($var_email,0,$var_compId)) { 
									$fl = @fopen("../csvfiles/nonuniqueemail$var_compId.txt","a+");
									@fwrite($fl, "$var_email".',');
									@fclose($fl);

									$message_email .= "<br>$var_email";
									$var_nonunique_email  = 1;
									$nonunique_email_flag = 1;
								}
								if($var_invalid_username == "0" && $var_nonunique_email == "0"){
								
									$sql = "Insert into sptbl_users(nUserId,nCompId,vUserName,vEmail,vLogin,vPassword,ddate,vOnline,";
									$sql .= "vBanned,vDelStatus) Values('','" . addslashes($var_compId) . "',
											'" . addslashes($var_userName). "','" . addslashes($var_email) . "','" . addslashes($var_userLogin) . "',
											'" . md5($var_password) . "',now(),'0','0','0')";
									executeQuery($sql,$conn);

									$var_insert_id = mysql_insert_id($conn);
		
									//Insert the actionlog
									if(logActivity()) {
										$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_ADDITION . "','Users','$var_insert_id',now())";			
										executeQuery($sql,$conn);
									}
								}
							}
							$rec_count++;
						}	
					 }
					 
					  if($line_count != "")
 							  $var_message .= TEXT_MESSAGE_DATA_MISMATCH_ERROR.substr($line_count,0,-1)."<br>";
					  
					  if($message != "" || $message_user != "" || $message_email !="" || $var_message != ""){
							if($var_invalid_username != 0 && $var_nonunique_email != 0){
							 	$message = TEXT_MESSAGE_INSERT_ERROR;
							}
						}
						else{
						  $message = TEXT_MESSAGE_UPDATE_SUCCESS;
						}

//						if($message_user != "")
//							$message .= TEXT_MESSAGE_INVALIDUSERNAME_ERROR.$message_user;
//						if($message_email != "")
//							$message .= TEXT_MESSAGE_DUPLICATEEMAIL_ERROR.$message_email;
	}
	
	function validateAddition() 
	{
		global $conn;
		if (trim($_POST["txtUrl"]) == "") {
			return false;
		}
		elseif(!isValidUsername(trim($_POST["txtUserLogin"])) || !isValidEmail(trim($_POST["txtEmail"]))){
			return false;	
		}
		else {
			$sql = "Select nCompId from sptbl_companies where vDelStatus='0' AND nCompId='" . addslashes($_POST["cmbCompanyId"]) . "' ";
			if (mysql_num_rows(executeSelect($sql,$conn)) <= 0) {
				return false;
			}
			else {
				$sql = "Select nUserId from sptbl_users where vLogin='" . addslashes(trim($_POST["txtUserLogin"])) . "'";
				if (mysql_num_rows(executeSelect($sql,$conn)) > 0) {
					return false;
				}
				else {
					return true;	
				}
			}
		}
	}
	
	$lst_comp = "";
	//fill the css ids here
	$sql = "Select nCompId,vCompName from sptbl_companies where vDelStatus='0' order by vCompName ";
	$result = executeSelect($sql,$conn);
	while ($row = mysql_fetch_array($result)) {
		$lst_comp .=  "<option value=\"" . $row["nCompId"] . "\"" . (($var_compId == $row["nCompId"])?"Selected":"") . ">" . htmlentities($row["vCompName"]) . "</option>"; 
	}
	mysql_free_result($result);
	
	
?>
<form name="frmUser" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">

<div class="content_section">
			<div class="content_section_title">
				<h3><?php echo TEXT_EDIT_USER ?></h3>
			</div>
			
			
<table width="100%"  border="0">
  <tr>
    <td width="76%" valign="top">
    <table width="100%"  border="0" cellspacing="10" cellpadding="0">
    <tr>
    <td>
	<div style="overflow:auto">
     <table width="100%"  border="0" cellspacing="0" cellpadding="0">
     <tr>
     <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
     <td class="pagecolor">
     
     <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="column1">
     <tr>
     <td>
         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
          
		   <tr>
		   		<td>&nbsp;</td>
	         <td align="left" colspan=2 class="fieldnames"><?php echo TEXT_FIELDS_MANDATORY ?></td>
		   </tr>
         <tr>
		 	
	         <td align="left" colspan="3">
			 <?php

if ($var_message != ""){
?>
	<div class="msg_error">
<b><?php echo($var_message); ?></b>
</div>
<?php
}
?>			
			 </td>
         </tr>
		 <tr>
		 	<td>&nbsp;</td>
	         <td align="left" colspan="2" <?php if($message != ""){ ?>class="msg_success" <?php } ?>><?php  echo $message ?></td>
         </tr>
		<?php	 if($message_user != ""){
				$message_invaliduser = TEXT_MESSAGE_INVALIDUSERNAME_ERROR.$message_user;
		?> 		<tr>
			 		<td>&nbsp;</td>
		        	<td align="left" colspan="2" class="msg_error"><?php echo $message_invaliduser; ?></td>
        		</tr>
		<?php   } ?>
		<?php  if($message_email != ""){
				$message_nonunique_email = TEXT_MESSAGE_DUPLICATEEMAIL_ERROR.$message_email;
		?>	<tr>
			 		<td>&nbsp;</td>
		        	<td align="left" colspan="2" class="msg_error"><?php echo $message_nonunique_email; ?></td>
        		</tr>
		<?php   } ?>
			
		<?php	 if($invalid_username_flag == "1"){ ?>
				 <tr>
				 	 <td>&nbsp;</td>
                     <td align="left"  colspan="3" class="msg_error"><?php echo "<br>".TEXT_MESSAGE_INVALIDUSERNAME_FILE; ?>&nbsp;&nbsp;<a href="javascript:download('<?php echo "invalidusername".$var_compId; ?>');"><img src="././../images/download.gif" width="13" height="15" border="0" title="<?php echo TEXT_TITLE_DOWNLOAD ?>"></a></td>
		         </tr>
		<?php	}  ?>
		<?php	 if($nonunique_email_flag == "1"){ ?>
				 <tr>
				 	 <td>&nbsp;</td>
                     <td align="left" colspan="3" ><?php echo TEXT_MESSAGE_DUPLICATEEMAIL_FILE; ?>&nbsp;&nbsp;<a href="javascript:download('<?php echo "nonuniqueemail".$var_compId; ?>');"><img src="././../images/download.gif" width="13" height="15" border="0" title="<?php echo TEXT_TITLE_DOWNLOAD ?>"></a></td>
		         </tr>
		<?php	}  ?>
		 <tr>
			 <td width="2%" align="left">&nbsp;</td>
			 <td  colspan="3" align="left" class="fieldnames" valign="top"><?php echo TEXT_CSV_TYPE_TEXT.TEXT_CSV_FORMAT?><br>
                             <a href="../csvfiles/sample.csv"><?php echo TEXT_DOWNLOAD_SAMPLE_CSV;?></a>
                         <table border="0" celpadding="5" cellspacing="0" style="border-top:1px solid #cccccc;border-left:1px solid #cccccc;">
                                <tr>
                                    <td style="border-bottom:1px solid #cccccc;border-right:1px solid #cccccc; padding:5px;">name1</td>
                                    <td style="border-bottom:1px solid #cccccc;border-right:1px solid #cccccc; padding:5px;">email@helpdesk.com</td>
                                    <td style="border-bottom:1px solid #cccccc;border-right:1px solid #cccccc; padding:5px;">login1</td>
                                    <td style="border-bottom:1px solid #cccccc;border-right:1px solid #cccccc; padding:5px;">password </td>
                                </tr>
                                <tr>
                                    <td style="border-bottom:1px solid #cccccc;border-right:1px solid #cccccc; padding:5px;">name2</td>
                                    <td style="border-bottom:1px solid #cccccc;border-right:1px solid #cccccc; padding:5px;">email2@helpdesk.com</td>
                                    <td style="border-bottom:1px solid #cccccc;border-right:1px solid #cccccc; padding:5px;">login2</td>
                                    <td style="border-bottom:1px solid #cccccc;border-right:1px solid #cccccc; padding:5px;">password</td>
                                </tr>
                                </table>
                         </td>
		  </tr>
         <tr><td colspan="3">&nbsp;</td></tr>		
         <tr>
			 <td width="2%" align="left">&nbsp;</td>
			 <td width="39%" align="left" class="toplinks"><?php echo TEXT_USER_COMPANY?></td>
			 <td width="59%" align="left">
			 <select name="cmbCompanyId" class="comm_input input_width1a">
				<?php echo($lst_comp); ?>		 
			 </select>
			 </td>
         </tr>
         <tr><td colspan="3">&nbsp;</td></tr>
		 <tr>
			 <td width="2%" align="left">&nbsp;</td>
			 <td width="39%" align="left" class="fieldnames" valign="top"><?php echo TEXT_UPLOAD_FILE?> <font style="color:#FF0000; font-size:9px">*</font> </td>
			 <td width="59%" align="left" class="fieldnames">
					<input name="txtUrl" type="file" class="comm_input input_width1a" id="txtUrl" size="30" maxlength="100" value="<?php echo htmlentities($var_Url); ?>">
			 </td>
		  </tr>
          <tr><td colspan="3" class="btm_brdr">&nbsp;</td></tr>																																				
       </table>
     </td>
    </tr>
   </table>
  </td>
                      <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
				  </div>
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
                                    <td width="25%">&nbsp;</td>
                                    <td width="21%"><input name="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD ?>" onClick="javascript:add();"></td>
                                    <td width="23%" align="left"><input name="btCancel" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_CANCEL ?>" onClick="javascript:cancel();"></td>
                                    <td width="31%">
									<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
									<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
									<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">																
									<input type="hidden" name="id" value="<?php echo($var_id); ?>">
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
<script>
	var setValue = "<?php echo trim($var_compId); ?>";
//	document.frmUser.cmbCountry.text=setValue;
	try{
 	for(i=0;i<document.frmUser.cmbCompanyId.options.length;i++){
            if(document.frmUser.cmbCompanyId.options[i].value == setValue){
                        document.frmUser.cmbCompanyId.options[i].selected=true;
                        break;
            }
    }
	}catch(e){}
	<?php
		if ($var_id == "") {
			echo("document.frmUser.btAdd.disabled=false;");
//			echo("document.frmUser.btUpdate.disabled=true;");
//			echo("document.frmUser.btDelete.disabled=true;");
			//echo("document.getElementById('showError').style.visibility='hidden';");
			//echo("document.getElementById('star').style.visibility='visible';");
			//echo("document.frmUser.txtUserLogin.readOnly=false;");
		}
		else {
			echo("document.frmUser.btAdd.disabled=true;");
//			echo("document.frmUser.btUpdate.disabled=false;");
//			echo("document.frmUser.btDelete.disabled=false;");
			//echo("document.getElementById('showError').style.visibility='visible';");
			//echo("document.getElementById('star').style.visibility='hidden';");
			//echo("document.frmUser.txtUserLogin.readOnly=true;");
		}
	?>
</script>
</form>