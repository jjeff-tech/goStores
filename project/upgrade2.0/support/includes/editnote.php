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
			$var_message = "<font color=red>" . MESSAGE_RECORD_ERROR . "</font>";
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
		
			$var_title = "";
			$var_desc = "";
			$url = $_SESSION["sess_backurl"];
		}
		else {
			$var_message = "<font color=red>" . MESSAGE_RECORD_ERROR . "</font>";
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
			$var_title = "";
			$var_desc = "";
			$var_id = "";

		}
		else {
				$var_message = "<font color=red>" . MESSAGE_RECORD_ERROR . "</font>";
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
		}
		else {
				$var_message = "<font color=red>" . MESSAGE_RECORD_ERROR . "</font>";
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
	
	
	
?>
<form name="frmNote" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>">
             
						  <div class="content_section_title">
	<h3><?php echo TEXT_ADD_NOTE ?></h3>
	</div>
 <table width="100%"  border="0" cellspacing="0" cellpadding="5">
     <tr>
     <td>
         <table width="100%"  border="0" cellspacing="0" cellpadding="0">

                 <tr>
         <td width="100%" align="center" colspan=3 >&nbsp;</td>

         </tr>

		<tr>
         <td width="100%" align="center" colspan=3 class="listing">
         <?php echo TEXT_FIELDS_MANDATORY ?></td>

         </tr>

         <tr>
         <td width="100%" align="center" colspan=3 class="errormessage">
         <?php echo $var_message ?></td>

         </tr>     

			<tr><td colspan="3">&nbsp;</td></tr>
       <tr>
         <td width="13%" align="left">&nbsp;</td>
         <td width="26%" align="left" class="listing"><?php echo TEXT_REMINDER_TITLE?> <font style="color:#FF0000; font-size:9px">*</font> </td>
         <td width="61%" align="left">
         <input name="txtTitle" type="text" class="textbox" id="txtTitle" size="60" maxlength="100" value="<?php echo htmlentities(stripslashes($var_title)); ?>" style="font-size:11px; ">
         </td>
                      </tr>
                       <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="listing" valign="top"><?php echo TEXT_REMINDER_DESCRIPTION ?> <font style="color:#FF0000; font-size:9px">*</font></td>
                      <td width="61%" align="left" class="textbox">
                        <textarea name="txtDesc" cols="70" rows="12" id="txtDesc" class="textarea" style="width:480px;"><?php echo htmlentities(stripslashes($var_desc)); ?></textarea></td>
                      </tr> 
                      <tr><td colspan="3">&nbsp;</td></tr>
                              </table>
                        </td>
                            </tr>
                        </table>
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td ><img src="images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
			
			
            <table width="100%"  border="0" cellspacing="10" cellpadding="0">
              <tr>
                <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td><img src="images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr >
                        <td width="1" ><img src="images/spacerr.gif" width="1" height="1"></td>
                        <td ><table width="100%"  border="0" cellspacing="0" cellpadding="5" >
                            <tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center"  class="listingbtnbar">
                                    <td width="22%">&nbsp;</td>
                                    <td width="16%"><input name="btAdd" type="button" class="button" value="<?php echo BUTTON_TEXT_ADD; ?>" onClick="javascript:add();"></td>
                                    <td width="14%"><input name="btUpdate" type="button" class="button" value="<?php echo BUTTON_TEXT_EDIT; ?>"  onClick="javascript:edit();"></td>
                                    <td width="16%"><input name="btDelete" type="button" class="button" value="<?php echo BUTTON_TEXT_DELETE; ?>" onClick="javascript:deleted();"></td>
                                    <td width="12%"><input name="btCancel" type="reset" class="button" value="<?php echo BUTTON_TEXT_CANCEL; ?>"></td>
                                    <td width="20%">
									<?php echo "<a href=\"" . $_SESSION['sess_backurl'] . "\" class='linkmaintext'>" . BUTTON_TEXT_BACK . "</a>" ?>
									<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
									<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
									<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">																
									<input type="hidden" name="id" value="<?php echo($var_id); ?>">
									<input type="hidden" name="tk" value="<?php echo($var_ticket_id); ?>">
									<input type="hidden" name="uname" value="<?php echo htmlentities($_SESSION["sess_username"]); ?>">
									<input type="hidden" name="postback" value="">
									</td>
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
		elseif ($var_userid == $_SESSION["sess_userid"]) {
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