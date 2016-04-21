<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>                                  |
// |                                                                      |                // |                                                                      |
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
        $var_userid = $_SESSION["sess_staffid"];

        if ($_POST["postback"] == "") {

                $sql = "Select vLookUpValue from sptbl_lookup where vLookUpName='MaxfileSize'";
                $var_result = executeSelect($sql,$conn);
                if (mysql_num_rows($var_result) > 0) {

                        $row=mysql_fetch_array($var_result);
                        $var_txtMaxFileSize=$row["vLookUpValue"];

                }
                else {
                        $var_txtMaxFileSize="";
                }
                mysql_free_result($var_result);
        }

        elseif ($_POST["postback"] == "U") {

             $var_txtMaxFileSize = trim($_POST["txtMaxFileSize"]);

             $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($var_txtMaxFileSize) .
             "'  where vLookUpName = 'MaxFileSize'";
             executeQuery($sql,$conn);

            //Insert the actionlog
			if(logActivity()) {
            $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Lookup/MaxFileSize','0',now())";
            executeQuery($sql,$conn);
			}

                                $var_message = MESSAGE_RECORD_UPDATED;
                                $flag_msg    = 'class="msg_success"';
             }
             else {
                                $var_message = MESSAGE_RECORD_ERROR ;
                                $flag_msg    = 'class="msg_error"';

             }




?>
<form name="frmMaxFileSize" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>">
	<div class="content_section">
			<div class="content_section_title">
				<h3><?php echo TEXT_MAX_FILE_SIZE_DETAILS ?></h3>
			</div>
			<table width="100%"  border="0">
			  <tr>
				<td width="76%" valign="top">
				<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
					
					 <tr>
					 <td align="center" colspan=3 >
                                             <?php
                                             if ($var_message != ""){?>
					 	<div <?php echo $flag_msg; ?>> <?php echo $var_message ?></div>
                                             <?php
                                             }?>
					</td>
					 </tr>
					 <tr>
					 <td>&nbsp;</td>
					 <td align="left" colspan=2 class="toplinks">
					 <?php echo TEXT_FIELDS_MANDATORY ?></td>
					 </tr>
					 <tr><td colspan="3">&nbsp;</td></tr>
					 <tr>
					 <td width="2%" align="left">&nbsp;</td>
					 <td width="42%" align="left" class="toplinks">
					 <?php echo TEXT_MAX_FILE_SIZE?>
					 <font style="color:#FF0000; font-size:9px">*</font> </td>
					 <td width="56%" align="left">
					 <input name="txtMaxFileSize"
					 type="text" class="comm_input input_width1a" id="txtMaxFileSize"
					 size="30" maxlength="100" value="<?php echo htmlentities($var_txtMaxFileSize); ?>">
					 </td>
					 </tr>
					<tr><td colspan="3">&nbsp;</td></tr>
					</table>
				
				<table width="100%"  border="0" cellspacing="0" cellpadding="0">
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
	
								  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
									  <tr align="center"  class="listingbtnbar">
										<td width="20%">&nbsp;</td>
										<td width="10%"></td>
										<td width="20%" align=right><input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT ?>"  onClick="javascript:edit();"></td>
										<td width="20%"><input name="btCancel" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_CANCEL ?>" onClick="javascript:cancel();"></td>
										<td width="10%"></td>
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
						<p class="ashbody">&nbsp;</p></td>
			
			  </tr>
			</table>
		</div>
</form>