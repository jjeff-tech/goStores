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
	$var_userid = $_SESSION["sess_userid"];
	if ($_GET["id"] != "") {
		$var_id = $_GET["id"];
	}elseif ($_POST["id"] != "") {
		$var_id = $_POST["id"];
	}
	
	if ($_GET["tk"] != "") {
		$var_ticket_id = $_GET["tk"];
	}elseif ($_POST["tk"] != "") {
		$var_ticket_id = $_POST["tk"];
	}
	
	if ($_GET["stylename"] != "") {
		$var_styleminus = $_GET["styleminus"];
		$var_stylename = $_GET["stylename"];
		$var_styleplus = $_GET["styleplus"];
	}else {
		$var_styleminus = $_POST["styleminus"];
		$var_stylename = $_POST["stylename"];
		$var_styleplus = $_POST["styleplus"];
	}	
	

	$var_title = $_POST["txtTitle"];
	$var_desc = $_POST["txtDesc"];
	

	if ($_POST["postback"] == "" && $var_id != "") {
		$sql = "SELECT f.nFBId,f.nTicketId,f.vFBTitle,f.tFBDesc
				FROM sptbl_feedback f INNER JOIN sptbl_tickets t ON f.nTicketId = t.nTicketId
		 		WHERE f.nFBId='" . addslashes($var_id) . "' AND t.nUserId = '".addslashes($_SESSION["sess_userid"])."' ";
		//echo $sql;
		$result = executeSelect($sql,$conn);
		if (mysql_num_rows($result) > 0) {	
			$var_row = mysql_fetch_array($result);
			$var_title = $var_row["vFBTitle"];
			$var_desc = $var_row["tFBDesc"];
		}
		else {
			$var_id = "";
			$var_message = MESSAGE_RECORD_ERROR;
                         $flag_msg="class='msg_error'";
		}
	}elseif ($_POST["postback"] == "A") {
		if (validateAddition() == true)	{
			$sql = "INSERT INTO sptbl_feedback(nFBId,nTicketId,
					vFBTitle,tFBDesc,dDate) 
					VALUES('','".addslashes($var_ticket_id)."',
					'" . addslashes($var_title) . "','" . addslashes($var_desc) . "',now()) ";
			
			//echo 	$sql;	
			executeQuery($sql,$conn);
			
			$var_insert_id = mysql_insert_id($conn);
			
			if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nUserId,vAction,vArea,nRespId,dDate) Values('','$var_userid','" . TEXT_ADDITION . "','User Feedback','$var_insert_id',now())";
            executeQuery($sql,$conn);
			}
			
			$var_message = MESSAGE_RECORD_ADDED;
                         $flag_msg="class='msg_success'";
		
			$var_title = "";
			$var_desc = "";
			$url = $_SESSION["sess_backurl"];
		}
		else {
			$var_message = MESSAGE_RECORD_ERROR;
                         $flag_msg="class='msg_error'";
		}
	}
	elseif ($_POST["postback"] == "D"){
		if (validateDeletion() == true)	{
		
			$sql = "Delete from sptbl_feedback where nFBId='" . addslashes($var_id) . "' ";
			executeQuery($sql,$conn);
			
			if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nUserId,vAction,vArea,nRespId,dDate) Values('','$var_userid','" . TEXT_DELETION . "','User Feedback','" . addslashes($var_id) . "',now())";			
			executeQuery($sql,$conn);
			}
		
			$var_message = MESSAGE_RECORD_DELETED;
                         $flag_msg="class='msg_success'";
			$var_title = "";
			$var_desc = "";
			$var_id = "";

		}
		else {
				$var_message = MESSAGE_RECORD_ERROR;
                                 $flag_msg="class='msg_error'";
		}
	} 
	elseif ($_POST["postback"] == "U") {
		if (validateUpdation() == true)	{
			$sql = "UPDATE sptbl_feedback SET 
					vFBTitle='" . addslashes($var_title) . "',
					tFBDesc='" . addslashes($var_desc) . "'
					 WHERE nFBId='" . addslashes($var_id) . "'";
			executeQuery($sql,$conn);
			//echo $sql;
			if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nUserId,vAction,vArea,nRespId,dDate) Values('','$var_userid','" . TEXT_UPDATION . "','User Feedback','" . addslashes($var_id) . "',now())";			
			executeQuery($sql,$conn);
			}
				
			$var_message = MESSAGE_RECORD_UPDATED;
                         $flag_msg="class='msg_success'";
		}
		else {
				$var_message = MESSAGE_RECORD_ERROR;
                                 $flag_msg="class='msg_error'";
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

   
    
    
         <div class="content_section_title"><h3><?php echo TEXT_ADD_FEEDBACK?></h3></div>
    
     <table width="100%"  border="0" cellspacing="1" cellpadding="0" >
     <tr>
     <td>
         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
             <tr>
		         <td align="center" colspan=3 >&nbsp;</td>
    	     </tr>
			<tr>
    	     <td align="center" colspan=3 class="listing">
        	 <?php echo TEXT_FIELDS_MANDATORY ?></td>
	        </tr>
			 <tr>
			 <td align="center" colspan=3 class="errormessage">
			 <div <?php echo $flag_msg; ?>><?php echo $var_message ?></div></td>
			 </tr>  

			<tr><td colspan="3">&nbsp;</td></tr>
       <tr>
         <td width="9%" align="left">&nbsp;</td>
         <td width="11%" align="left" class="listing"><?php echo TEXT_REMINDER_TITLE?> <font style="color:#FF0000; font-size:9px">*</font> </td>
         <td width="71%" align="left">
         <input name="txtTitle" type="text" class="comm_input input_width1a" id="txtTitle" size="60" maxlength="100" value="<?php echo htmlentities(stripslashes($var_title)); ?>" style="font-size:11px;width:476px;  ">
         </td>
                      </tr>
                       <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="listing" valign="top"><?php echo TEXT_REMINDER_DESCRIPTION ?> <font style="color:#FF0000; font-size:9px">*</font></td>
                      <td width="71%" align="left">
                        <textarea name="txtDesc" cols="70" rows="12" id="txtDesc" class="textarea" style="width:480px;"><?php echo htmlentities(stripslashes($var_desc)); ?></textarea></td>
                      </tr> 
                      <tr><td colspan="3">&nbsp;</td></tr>
                              </table>
                        </td>
                            </tr>
                        </table>
                  
           
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr >
                        <td width="1" ><img src="images/spacerr.gif" width="1" height="1"></td>
                        <td ><table width="100%"  border="0" cellspacing="0" cellpadding="5" >
                            <tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="left"  class="listingbtnbar">
                                 <td width="13%">&nbsp;</td>
                                    <td width="5%"><input name="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD; ?>" onClick="javascript:add();"></td>
                                    <!--<td width="14%"><input name="btUpdate" type="button" class="button" value="<?php echo BUTTON_TEXT_EDIT; ?>"  onClick="javascript:edit();"></td>
                                    <td width="16%"><input name="btDelete" type="button" class="button" value="<?php echo BUTTON_TEXT_DELETE; ?>" onClick="javascript:deleted();"></td>-->
                                    <td width="5%"><input name="btCancel" type="reset" class="comm_btn" value="<?php echo BUTTON_TEXT_CLEAR; ?>"></td>
                                    <td width="5%">
                                        <input name="btnBack" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_BACK; ?>" onClick="javascript:goBack();">
                                        <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
                                        <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                                        <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
                                        <input type="hidden" name="id" value="<?php echo($var_id); ?>">
                                        <input type="hidden" name="tk" value="<?php echo($var_ticket_id); ?>">
                                        <input type="hidden" name="uname" value="<?php echo htmlentities($_SESSION["sess_username"]); ?>">
                                        <input type="hidden" name="postback" value="">
                                    </td>
									<td width="25%">							</td>
                                  </tr>
                              </table></td>
                            </tr>
                        </table></td>
                        <td width="1" ><img src="images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                    </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td ><img src="images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                  </table>
           

<script>
<?php
		
		if ($var_id == "") {
			echo("document.frmNote.btAdd.disabled=false;");
			//echo("document.frmNote.btUpdate.disabled=true;");
			//echo("document.frmNote.btDelete.disabled=true;");
		}
		elseif ($var_userid == $_SESSION["sess_userid"]) {
			echo("document.frmNote.btAdd.disabled=true;");
			//echo("document.frmNote.btUpdate.disabled=false;");
			//echo("document.frmNote.btDelete.disabled=false;");
	    }
		else {
			echo("document.frmNote.btAdd.disabled=true;");
			//echo("document.frmNote.btUpdate.disabled=true;");
			//echo("document.frmNote.btDelete.disabled=true;");
		}
	?>
</script>
</form>
</div>