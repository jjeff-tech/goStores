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
	$var_staffid = $_SESSION["sess_staffid"];
	if ($_GET["id"] != "") {
		$var_id = $_GET["id"];
	}
	elseif ($_POST["id"] != "") {
		$var_id = $_POST["id"];
	}
	if ($_GET["tk"] != "") {
		$var_ticket_id = $_GET["tk"];
	}
	elseif ($_POST["tk"] != "") {
		$var_ticket_id = $_POST["tk"];
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
	

	$var_title = $_POST["txtTitle"];
	$var_desc = $_POST["txtDesc"];
	

	if ($_POST["postback"] == "" && $var_id != "") {
		$sql = "SELECT nPNId,nStaffId,nTicketId,vStaffLogin,
					vPNTitle,tPNDesc
				FROM sptbl_personalnotes
		 		 WHERE nPNId='" . addslashes($var_id) . "' ";
		$result = executeSelect($sql,$conn);
		if (mysql_num_rows($result) > 0) {	
			$var_row = mysql_fetch_array($result);
			$var_staffid = $var_row["nStaffId"];
			$var_title = $var_row["vPNTitle"];
			$var_desc = $var_row["tPNDesc"];
		}
		else {
			$var_message = MESSAGE_RECORD_ERROR;
                         $flag_msg = "class='msg_error'";
		}
	}
	elseif ($_POST["postback"] == "A") {
		if (validateAddition() == true)	{
			$sql = "Insert into sptbl_personalnotes(nPNId,nStaffId,nTicketId,vStaffLogin,
					vPNTitle,tPNDesc,dDate) 
					Values('','$var_staffid','".addslashes($var_ticket_id)."','" . addslashes($_SESSION["sess_staffname"]) . "',
					'" . addslashes($var_title) . "','" . addslashes($var_desc) . "',now()) ";
			
			//echo 	$sql;	
			executeQuery($sql,$conn);
			
			$var_insert_id = mysql_insert_id($conn);
			if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_ADDITION . "','Personal Note','$var_insert_id',now())";
            executeQuery($sql,$conn);
			}
			
			$var_message = MESSAGE_RECORD_ADDED;
                         $flag_msg = "class='msg_success'";
		
			$var_title = "";
			$var_desc = "";
			$url = $_SESSION["sess_backurl"];
			
		}
		else {
			$var_message = MESSAGE_RECORD_ERROR;
                         $flag_msg = "class='msg_error'";
		}
	}
	elseif ($_POST["postback"] == "D") {
		
		if (validateDeletion() == true)	{
		
			$sql = "Delete from sptbl_personalnotes where nPNId='" . addslashes($var_id) . "' ";
			executeQuery($sql,$conn);
			if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Personal Notes','" . addslashes($var_id) . "',now())";			
			executeQuery($sql,$conn);
			}
		
			$var_message = MESSAGE_RECORD_DELETED;
                         $flag_msg = "class='msg_success'";
			$var_title = "";
			$var_desc = "";
			$var_id = "";

		}
		else {
				$var_message = MESSAGE_RECORD_ERROR;
                                 $flag_msg = "class='msg_error'";
		}
	} 
	elseif ($_POST["postback"] == "U") {
		
		if (validateUpdation() == true)	{
			$sql = "UPDATE sptbl_personalnotes SET 
					vPNTitle='" . addslashes($var_title) . "',
					tPNDesc='" . addslashes($var_desc) . "'
					 WHERE nPNId='" . addslashes($var_id) . "'";
			executeQuery($sql,$conn);
			
			if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Personal Notes','" . addslashes($var_id) . "',now())";			
			executeQuery($sql,$conn);
			}
				
				$var_message = MESSAGE_RECORD_UPDATED;
                                 $flag_msg = "class='msg_success";
		}
		else {
				$var_message = MESSAGE_RECORD_ERROR;
                                 $flag_msg = "class='msg_error'";
		}
	}
	
	function validateAddition() {
		global $var_time;
		if (trim($_POST["txtTitle"]) == "" || trim($_POST["txtDesc"]) == "") {
			return false;
		}else {
			return true;
		}
	}
	
	function validateDeletion() {
		return true;
	}
	function validateUpdation() {
		global $var_time;
		if (validateAddition() == false) {
			return false;	
		}
		else {
			return true;
		}
	}
	
	
	
?><div class="content_section">
<form name="frmNote" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>">

   
    
<div class="content_section_title">
	<h3><?php echo TEXT_ADD_NOTE ?></h3>
</div>
    
     


         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
           <tr>
	         <td width="100%" align="center" colspan=3 >&nbsp;</td>
	       </tr>
		   <tr>
         <td width="100%" align="center" colspan=3 class="toplinks">
         <?php echo TEXT_FIELDS_MANDATORY ?></td>
         </tr>
         <tr>
         <td width="100%" align="center" colspan=3 class="messsagebar">
      <div <?php echo  $flag_msg; ?>>   <?php echo $var_message; ?> </div></td>

         </tr>
			<tr><td colspan="3">&nbsp;</td></tr>
       <tr>
         <td width="13%" align="left">&nbsp;</td>
         <td width="26%" align="left" class="toplinks"><?php echo TEXT_REMINDER_TITLE?> <font style="color:#FF0000; font-size:9px">*</font> </td>
         <td width="61%" align="left">
         <input name="txtTitle" type="text" class="comm_input input_width1 ac_input" id="txtTitle" size="60" maxlength="100" value="<?php echo htmlentities($var_title); ?>" style="font-size:11px; margin: 0;width: 329px;">
         </td>
                      </tr>
                       <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks" valign="top"><?php echo TEXT_REMINDER_DESCRIPTION ?> <font style="color:#FF0000; font-size:9px">*</font></td>
                      <td width="61%" align="left">
                        <textarea name="txtDesc" cols="70" rows="12" id="txtDesc" class="textarea" style="width:330px;"><?php echo htmlentities($var_desc); ?></textarea></td>
                      </tr> 
                      <tr><td colspan="3">&nbsp;</td></tr>
                              </table>
                      
                  
           
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr >
                        <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                        <td ><table width="100%"  border="0" cellspacing="0" cellpadding="5" >
                            <tr>
                              <td><table width="80%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center"  class="whitebasic">
                                    <td width="30%">&nbsp;</td>
                                    <td width="13%"><input name="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD; ?>" onClick="javascript:add();"></td>
                                    <td width="13%"><input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT; ?>"  onClick="javascript:edit();"></td>
                                    <td width="13%"><input name="btDelete" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_DELETE; ?>" onClick="javascript:deleted();"></td>
                                    <td width="15%"><input name="btCancel" type="reset" class="comm_btn" value="<?php echo BUTTON_TEXT_CANCEL; ?>"></td>
                                    <td width="23%">
									<input name="btnBack" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_BACK; ?>" onClick="javascript:goBack();">
									<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
									<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
									<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">																
									<input type="hidden" name="id" value="<?php echo($var_id); ?>">
									<input type="hidden" name="tk" value="<?php echo($var_ticket_id); ?>">
									<input type="hidden" name="uname" value="<?php echo htmlentities($_SESSION["sess_staffname"]); ?>">
									<input type="hidden" name="postback" value="">
									</td>
                                  </tr>
                              </table></td>
                            </tr>
                        </table></td>
                        
                      </tr>
                    </table>
                  
  

<script>
<?php
		
		if ($var_id == "") {
			echo("document.frmNote.btAdd.disabled=false;");
			echo("document.frmNote.btUpdate.disabled=true;");
			echo("document.frmNote.btDelete.disabled=true;");
		}
		elseif ($var_staffid == $_SESSION["sess_staffid"]) {
			echo("document.frmNote.btAdd.disabled=true;");
			echo("document.frmNote.btUpdate.disabled=false;");
			echo("document.frmNote.btDelete.disabled=false;");
	    }
		else {
			echo("document.frmNote.btAdd.disabled=true;");
			echo("document.frmNote.btUpdate.disabled=true;");
			echo("document.frmNote.btDelete.disabled=true;");
		}
	?>
</script>
</form>
</div>