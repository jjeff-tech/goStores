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
	if ($_GET["id"] != "") {
		$var_id = $_GET["id"];
	}
	elseif ($_POST["id"] != "") {
		$var_id = $_POST["id"];
	} 
	
	$var_staffid = $_SESSION["sess_staffid"];
	
	if ($_POST["postback"] == "" && $var_id != "") {
	    $sql = "Select p.nPNId,p.nStaffId,p.nTicketId,p.vPNTitle,p.tPNDesc,p.dDate,t.vRefNo,s.vLogin from sptbl_personalnotes as p,";
        $sql .=" sptbl_tickets as t ,sptbl_staffs as s";
        $sql .=" where p.nTicketId=t.nTicketId and p.nStaffId=s.nStaffId and p.nPNId='".mysql_real_escape_string($var_id)."'";
	    $var_result = executeSelect($sql,$conn); 
		if (mysql_num_rows($var_result) > 0) {
			$var_row = mysql_fetch_array($var_result);
			$var_pdate=$var_row["dDate"];
			$var_staff= $var_row["vLogin"];
			$var_refno = $var_row["vRefNo"];
			$var_title = $var_row["vPNTitle"];
			$var_notes = $var_row["tPNDesc"];
		}
		else {
			$var_id="";
			$var_message = MESSAGE_RECORD_ERROR ;
                        $flag_msg    = 'class="msg_error"';
		}
	}
	elseif ($_POST["postback"] == "D") {
	        $var_title= trim($_POST["txtPerTitle"]);
			$var_notes = trim($_POST["txtNotes"]);
			$var_refno = trim($_POST["txtRefno"]);
			$var_pdate=trim($_POST["txtDate"]);
			$var_staff= trim($_POST["txtStaff"]);
			
		if (validateDeletion() == true) {
			$sql = "delete from  sptbl_personalnotes   where nPNId ='" . mysql_real_escape_string($var_id) . "'";
			executeQuery($sql,$conn);
			
			//Insert the actionlog
			if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Personal Notes','" . mysql_real_escape_string($var_id) . "',now())";			
			executeQuery($sql,$conn);
			}
			$var_title= "";
			$var_notes = "";
			$var_refno = "";
			$var_pdate="";
			$var_staff= "";
			$var_message = MESSAGE_RECORD_DELETED;
                        $flag_msg    = 'class="msg_success"';
			$var_id="";
		}
		else {
		    $var_message = MESSAGE_RECORD_ERROR ;
                    $flag_msg    = 'class="msg_error"';
		}
	}
	elseif ($_POST["postback"] == "U") {
	   $sql = "SELECT nPNId   FROM sptbl_personalnotes WHERE nPNId ='" . mysql_real_escape_string($var_id) . "'";
	   if(mysql_num_rows(executeSelect($sql,$conn)) > 0) {      
			$var_title= trim($_POST["txtPerTitle"]);
			$var_notes = trim($_POST["txtNotes"]);
			$var_refno = trim($_POST["txtRefno"]);
			$var_pdate=trim($_POST["txtDate"]);
			$var_staff= trim($_POST["txtStaff"]);
			$dup_flag=0;
			//check duplicate name department name
			$sql="SELECT nPNId   FROM sptbl_personalnotes WHERE vPNTitle='".mysql_real_escape_string($var_deptname) . "'";
			$sql .=" and nPNId !=$var_id";
			$rs = executeSelect($sql,$conn);
			if(mysql_num_rows($rs)>0){
			  $dup_flag=1;
			}
			
			if (validateUpdation() == true and $dup_flag==0) {
				$sql = "Update sptbl_personalnotes set 
					    vPNTitle='" . mysql_real_escape_string($var_title) . "',
					    tPNDesc='" . mysql_real_escape_string($var_notes). "',
					    dDate  =now() 
					    where nPNId='" . mysql_real_escape_string($var_id) . "'";
						
				executeQuery($sql,$conn);
				
			//Insert the actionlog
			if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Personal Notes','" . mysql_real_escape_string($var_id) . "',now())";			
			executeQuery($sql,$conn);
			}
			
				$var_message = MESSAGE_RECORD_UPDATED;
                                $flag_msg    = 'class="msg_success"';
			}
			else {
				$var_message = MESSAGE_RECORD_ERROR ;
                                $flag_msg    = 'class="msg_error"';
			}
		}
		else {
			$var_id="";
			$var_message = MESSAGE_INVALID_PN ;
                        $flag_msg    = 'class="msg_error"';
		}		
	}
	
    function validateDeletion() 
	{
		
		return true;
	}
	
	function validateUpdation() 
	{
		if (trim($_POST["txtPerTitle"]) == "" || trim($_POST["txtNotes"]) == "" ) {
			return false;
		}
		else {
		      
			return true;
		}
	}

?><div class="content_section">
<form name="frmPersonalNotes" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>">
    <div class="content_section_title">
            <h3><?php echo TEXT_EDIT_PERSONAL_NOTES ?></h3>
    </div>
<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
  <tr>
    <td width="76%" valign="top">
    <table width="100%"  border="0" cellspacing="10" cellpadding="0">
    <tr>
    <td>
    <table width="100%"  border="0" cellpadding="0" cellspacing="0" >
     <tr>
     <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
     </tr>

     </table>

     <table width="100%"  border="0" cellspacing="0" cellpadding="0">
     <tr>
     <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
     <td class="pagecolor">



     <table width="100%"  border="0" cellspacing="1" cellpadding="0" >
	      
     <tr>
     <td>
	       
         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">

                 <tr>
         <td width="100%" align="center" colspan=3 >&nbsp;</td>

         </tr>
    	<tr>
         <td width="100%" align="center" colspan=3 <?php echo $flag_msg; ?>>
         <?php echo $var_message ?></td>

         </tr>
		<tr>
         <td width="100%" align="center" colspan=3 class="toplinks">
         <?php echo TEXT_FIELDS_MANDATORY ?></td>

         </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
					  
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_STAFF ?></td>
                      <td width="61%" align="left" class="toplinks">
					    <input type=hidden name=txtStaff value="<?php echo htmlentities($var_staff); ?>">
                        <?php echo htmlentities($var_staff); ?>
					  </td>
                      </tr>
                      <tr><td colspan="3" >&nbsp;</td></tr>
					  <tr>
                        <td width="13%" align="left">&nbsp;</td>
                        <td width="26%" align="left" class="toplinks"><?php echo TEXT_REFNO ?>  </td>
                         <td width="61%" align="left" class="toplinks">
						   <input type=hidden name=txtRefno value="<?php echo htmlentities($var_refno); ?>">
					 	   <?php echo htmlentities($var_refno); ?>
                         </td>
                      </tr>


			          <tr><td colspan="3">&nbsp;</td></tr>
					  <tr>
                        <td width="13%" align="left">&nbsp;</td>
                        <td width="26%" align="left" class="toplinks"><?php echo TEXT_DATE ?></td>
                         <td width="61%" align="left" class="toplinks">
					 	   <?php echo datetimefrommysql(htmlentities($var_pdate)); ?>
						   <input type=hidden name=txtDate value="<?php echo htmlentities($var_pdate); ?>">
                         </td>
                      </tr>


			          <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                     <td width="13%" align="left">&nbsp;</td>
                     <td width="26%" align="left" class="toplinks"><?php echo TEXT_TILE ?> <font style="color:#FF0000; font-size:9px">*</font> </td>
					 <td width="61%" align="left">
                        <input name="txtPerTitle" type="text" class="comm_input input_width1a ac_input" id="txtPerTitle" size="64" maxlength="100" value="<?php echo htmlentities($var_title); ?>">
					  </td>
                      </tr>
                       <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                     <td width="13%" align="left">&nbsp;</td>
                     <td width="26%" align="left" class="toplinks"><?php echo TEXT_NOTES ?> <font style="color:#FF0000; font-size:9px">*</font> </td>
					 <td width="61%" align="left">
                        <textarea name="txtNotes" cols="50" rows="12" id="txtNotes" class="textarea"  style="width:330px;"><?php echo htmlentities($var_notes); ?></textarea>
					 </td>
                      </tr>
                      
                      <tr><td colspan="3">&nbsp;</td></tr>
                      		</table>
                        </td>
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
                                    <td width="27%">&nbsp;</td>
                                    
                                    <td width="10%"><input name="btUpdate" type="button" class="button" value="<?php echo BUTTON_TEXT_EDIT; ?>"  onClick="javascript:edit();"></td>
                                    <td width="10%"><input name="btDelete" type="button" class="button" value="<?php echo BUTTON_TEXT_DELETE; ?>" onClick="javascript:deleted();"></td>
                                    <td width="10%"><input name="btCancel" type="button" class="button" value="<?php echo BUTTON_TEXT_CANCEL; ?>" onClick="javascript:cancel();"></td>
                                    
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
<script>
	var setValue = "<?php echo trim($var_country); ?>";

	<?php
		if ($var_id == "") {
			
			echo("document.frmPersonalNotes.btUpdate.disabled=true;");
			echo("document.frmPersonalNotes.btDelete.disabled=true;");
		}
		else {
			
			echo("document.frmPersonalNotes.btUpdate.disabled=false;");
			echo("document.frmPersonalNotes.btDelete.disabled=false;");
		}
	?>
</script>
</form></div>