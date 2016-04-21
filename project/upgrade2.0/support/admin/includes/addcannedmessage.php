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
	$var_message="";
	if ($_POST["postback"] == "" && $var_id != "") {
		$sql = "Select * from sptbl_cannedmessages  ";
        $sql .=" where nMsgId ='".addslashes($var_id)."'";
		$var_result = executeSelect($sql,$conn); 
		if (mysql_num_rows($var_result) > 0) {
			$var_row = mysql_fetch_array($var_result);  
			$var_title = $var_row["vTitle"];
			$var_desc= $var_row["vDescription"];
			$var_status= $var_row["vStatus"];
     	}
		else {
			$var_id="";
			$var_message = MESSAGE_RECORD_ERROR;
                        $flag_msg    = 'class="msg_error"';
		}
	}
	elseif ($_POST["postback"] == "A") {
	     	$var_title = $_POST["txtTitle"];
			$var_desc = $_POST["txtDesc"];
			$var_status = $_POST["rdSts"];
			$var_date =  date("Y-m-d");
			
		if (validateAddition() == true) {
				//Insert into the company table
				$sql = "Insert into sptbl_cannedmessages(nMsgId,vTitle,vDescription,vStatus,nStaffId,dDate)";
				$sql .= " Values('','" . addslashes($var_title) . "','" . addslashes($var_desc). "','" . addslashes($var_status) . "','" . addslashes($var_staffid) . "',
						'" . addslashes($var_date) . "')";
				executeQuery($sql,$conn);
				$var_insert_id = mysql_insert_id($conn);
				
				//Insert the actionlog
				if(logActivity()) {
				$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_ADDITION . "','Canned Message','$var_insert_id',now())";			
				executeQuery($sql,$conn);
				}
				
				$var_message = MESSAGE_RECORD_ADDED;
                                $flag_msg    = 'class="msg_success"';
				$var_title ="";
			    $var_desc = "";
			    $var_status ='1' ;
				//Send mail with the password to the user here
		}
		else {
			$var_message = MESSAGE_RECORD_ERROR ;
                        $flag_msg    = 'class="msg_error"';
		}
	} elseif ($_POST["postback"] == "U") {
	   $sql = "Select nMsgId from sptbl_cannedmessages Where nMsgId='" . addslashes($var_id) . "'";    
		if(mysql_num_rows(executeSelect($sql,$conn)) > 0) {
			$var_title = trim($_POST["txtTitle"]);
			$var_desc= trim($_POST["txtDesc"]);
			$var_status= trim($_POST["rdSts"]);
			if (validateUpdation() == true ) {
			   //  $var_validdate =datetimetomysql($var_validdate);
				 $sql = "Update sptbl_cannedmessages  set vTitle ='" . addslashes($var_title) . "',
					     vDescription ='" . addslashes($var_desc) . "',
					     vStatus  ='".addslashes($var_status)."' 
					     where nMsgId='" . addslashes($var_id) . "'";
			executeQuery($sql,$conn);
			//Insert the actionlog
			if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','CannedMessages','" . addslashes($var_id) . "',now())";			
			executeQuery($sql,$conn);
			}
			    
				$var_message = MESSAGE_RECORD_UPDATED;
                                $flag_msg    = 'class="msg_success"';
			}
			else {
				$var_message =  MESSAGE_RECORD_ERROR ;
                                $flag_msg    = 'class="msg_error"';
			}
		}
		else {
			$var_id="";
			$var_message = MESSAGE_INVALID_TEMPLATE;
                        $flag_msg    = 'class="msg_error"';
		}	
	}
	
	function validateAddition() 
	{
	
		
 	    if (trim($_POST["txtTitle"]) == "" || trim($_POST["txtDesc"]) == "") {
		    return false;
		}else {
		    return true;
		}
	}
	
	function validateDeletion() 
	{
	
		return true;
	}
	
	function validateUpdation() 
	{
	    if (trim($_POST["txtTitle"]) == "" || trim($_POST["txtDesc"]) == "") {
		    return false;
		}else {
		    return true;
		}
	}

?>
<div class="content_section">

<form name="frmCannedmessage" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>">
<div class="content_section_title">
	<h3>
	<?php if ($var_id == "") echo TEXT_ADD_CANNEDMESSAGE ; else echo TEXT_EDIT_CANNEDMESSAGE; ?>
	</h3>
</div>

   
    
    
     
  
 <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="comm_tbl">

      <tr>
         <td align="center" colspan=3>&nbsp;</td>

         </tr>
    	<tr>
         <td align="center" colspan=3 class="errormessage">
         <?php echo $var_message ?></td>

         </tr>
		<tr>
         <td align="center" colspan=3 class="toplinks">
         <?php echo TEXT_FIELDS_MANDATORY ?></td>
         </tr>
		 
		   <tr>
                     <td width="9%" align="left">&nbsp;</td>
                     <td width="20%" align="left" class="toplinks"><?php echo TEXT_TITLE?> <font style="color:#FF0000; font-size:9px">*</font> </td>
					  <td width="71%" align="left">
                        <input name="txtTitle" type="text" class="comm_input" id="txtTitle" size="64" maxlength="100" value="<?php echo htmlentities($var_title); ?>">
					  </td>
                      </tr>                     
					  <tr>
                     <td width="9%" align="left">&nbsp;</td>
                     <td width="20%" align="left" class="toplinks" valign="top"><?php echo TEXT_DESCRIPTION?> <font style="color:#FF0000; font-size:9px">*</font> </td>
                     <td width="71%" align="left">
                        <textarea name="txtDesc" cols="50" rows="12" id="txtDesc" class="comm_input input_width1" style="width:480px;" ><?php echo htmlentities($var_desc); ?></textarea>
					 </td>
                      </tr>                      
					    
						
						
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_STATUS?></td>
                      <td width="61%" align="left">
                      <input name="rdSts" type="radio" value="1" <?php if($var_status == 1) echo "checked"; ?>>
						Active 
						<input name="rdSts" type="radio" value="0" <?php if($var_status == 0) echo "checked"; ?> >
						Not Active
                      </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
					  
								</table>
                       
			
			
          
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center"  class="listingbtnbar">
                                    <td colspan="5" align="center">
                                    <?php if ($var_id == "") {?><input name="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD ?>" onClick="javascript:add();">&nbsp;<?php }?>
                                    <?php if ($var_id != "") {?><input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT ?>"  onClick="javascript:edit();">&nbsp;<?php }?>
                                    <input name="btCancel" type="reset" class="comm_btn" value="<?php echo BUTTON_TEXT_CANCEL ?>">
									<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
									<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
									<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">																
									<input type="hidden" name="id" value="<?php echo($var_id); ?>">
									<input type="hidden" name="postback" value="">
									</td>
                                  </tr>
                              </table>
                    
            <p class="ashbody">&nbsp;</p>

</div>

<script>
	var setValue = "<?php echo trim($var_country); ?>";
</script>
</form>