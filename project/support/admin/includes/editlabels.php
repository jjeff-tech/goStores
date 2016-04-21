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
		$sql = "Select * from sptbl_labels  ";
        $sql .=" where nLabelId ='".mysql_real_escape_string($var_id)."'";
		$var_result = executeSelect($sql,$conn); 
		if (mysql_num_rows($var_result) > 0) {
			$var_row = mysql_fetch_array($var_result);  
			$var_title = $var_row["vLabelname"];
     	}
		else {
			$var_id="";
			$var_message = MESSAGE_RECORD_ERROR ;
                        $flag_msg    = 'class="msg_error"';
		}
	}
	elseif ($_POST["postback"] == "A") {
	      	$var_title= trim($_POST["txtTitle"]);
			$dup_flag=0;
			//check duplicate name label title to block page refrsh
			$sql="select *  from sptbl_labels  WHERE   vLabelname ='".mysql_real_escape_string($var_title) . "'";
			$rs = executeSelect($sql,$conn);
			if(mysql_num_rows($rs)>0){
			  $dup_flag=1;
			}
			
		if (validateAddition() == true and $dup_flag==0) {
		  	$sql = "Insert into sptbl_labels (nLabelId,vLabelname,nStaffId";
			$sql .= ") Values('','" . mysql_real_escape_string($var_title). "','$var_staffid')" ;
		    executeQuery($sql,$conn);
			$var_insert_id = mysql_insert_id($conn);
			//Insert the actionlog
			if(logActivity()) {
				$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_ADDITION . "','Labels','" . mysql_real_escape_string($var_insert_id) . "',now())";			
				executeQuery($sql,$conn);
			}
			
			$var_message = MESSAGE_RECORD_ADDED;
                        $flag_msg    = 'class="msg_success"';
			$var_title= "";
			$var_desc = "";			
		}
		else {
		 	$var_message = MESSAGE_RECORD_ERROR ;
                        $flag_msg    = 'class="msg_error"';
		}
	}
	elseif ($_POST["postback"] == "D") {
		if (validateDeletion() == true) {
			$sql = "delete from  sptbl_labels  where nLabelId ='" . mysql_real_escape_string($var_id) . "'";
			executeQuery($sql,$conn);
			
			//Insert the actionlog
			if(logActivity()) {
				$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Labels','" . mysql_real_escape_string($var_id) . "',now())";			
				executeQuery($sql,$conn);
			}
			$var_title= "";
			$var_desc = "";
			$var_id="";
			$var_message = MESSAGE_RECORD_DELETED;
                        $flag_msg    = 'class="msg_success"';
		}
		else {
		    $var_title= trim($_POST["txtNewsTitle"]);
			$var_news = trim($_POST["txtNews"]);
			$var_validdate = trim($_POST["txtDate"]);
			$var_stype = trim($_POST["chk_staff"]);
			$var_utype = trim($_POST["chk_user"]);
			if($var_stype!="" and $var_utype!=""){
			  $vtype="A";
			}elseif($var_stype!=""){
			  $vtype=$var_stype;
			  
			}else if($var_utype!=""){
			  $vtype=$var_utype;
			}
		}
	}
	elseif ($_POST["postback"] == "U") {
	   $sql = "Select nLabelId from sptbl_labels Where nLabelId='" . mysql_real_escape_string($var_id) . "'";    
		if(mysql_num_rows(executeSelect($sql,$conn)) > 0) {
			$var_title= trim($_POST["txtTitle"]);
			$dup_flag=0;
			//check duplicate  template title to block page refrsh
			$sql="select *  from sptbl_labels  WHERE   vLabelname ='".mysql_real_escape_string($var_title) . "'";
			$sql .=" and  nLabelId   !=$var_id";
			$rs = executeSelect($sql,$conn);
			if(mysql_num_rows($rs)>0){
			  $dup_flag=1;
			}
			
			if (validateUpdation() == true and $dup_flag==0) {
			     $var_validdate =datetimetomysql($var_validdate);
				 $sql = "Update sptbl_labels  set vLabelname ='" . mysql_real_escape_string($var_title) . "'
					     where nLabelId='" . mysql_real_escape_string($var_id) . "'";
						
			executeQuery($sql,$conn);
			//Insert the actionlog
			if(logActivity()) {
				$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Labels','" . mysql_real_escape_string($var_id) . "',now())";
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
			$var_message = MESSAGE_INVALID_LABELS ;
                        $flag_msg    = 'class="msg_error"';
		}	
	}
	
	function validateAddition() 
	{
	    if (trim($_POST["txtTitle"]) == "") {
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
	    if (trim($_POST["txtTitle"]) == "") {
		    return false;
		}else {
		    return true;
		}
	}

?><div class="content_section">
<form name="frmLabel" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>">



    
    
<Div class="content_section_title"><h3><?php if(isset($_REQUEST['id'])) echo TEXT_EDIT_LABELS; else echo 'Add Labels'; ?></h3></Div>
   
	  
         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">

                 <tr>
         <td align="center" colspan=3 >&nbsp;</td>

         </tr>
    	<tr>
         <td align="center" colspan=3 <?php echo $flag_msg; ?>>
         <?php echo $var_message ?></td>

         </tr>
		<tr>
         <td align="center" colspan=3 class="toplinks">
         <?php echo TEXT_FIELDS_MANDATORY ?></td>
         </tr>
			          <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                     <td width="8%" align="left">&nbsp;</td>
                     <td width="37%" align="left" class="toplinks"><?php echo TEXT_TITLE?> <font style="color:#FF0000; font-size:9px">*</font> </td>
					  <td width="55%" align="left">
                        <input name="txtTitle" type="text" class="comm_input input_width1" id="txtTitle" size="34" maxlength="100" value="<?php echo htmlentities($var_title); ?>">
					  </td>
                      </tr>                    
					    <tr><td colspan="3">&nbsp;</td></tr>
								</table>
                       
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td ><img src="./../images/spacerr.gif" width="1" height="1"></td>
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
                                    <td width="22%">&nbsp;</td>
                                    <td width="16%"><input name="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD; ?>" onClick="javascript:add();"></td>
                                    <td width="14%"><input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT; ?>"  onClick="javascript:edit();"></td>
                                    <td width="16%"><input name="btDelete" type="button" class="comm_btn_greyad" value="<?php echo BUTTON_TEXT_DELETE; ?>" onClick="javascript:deleted();"></td>
                                    <td width="12%"><input name="btCancel" type="button" class="comm_btn_greyad" value="<?php echo BUTTON_TEXT_CLEAR; ?>" onClick="javascript:cancel();"></td>
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
        
<script>
	var setValue = "<?php echo trim($var_country); ?>";

	<?php
		if ($var_id == "") {
			echo("document.frmLabel.btAdd.disabled=false;");
			echo("document.frmLabel.btUpdate.disabled=true;");
			echo("document.frmLabel.btDelete.disabled=true;");
		}
		else {
			echo("document.frmLabel.btAdd.disabled=true;");
			echo("document.frmLabel.btUpdate.disabled=false;");
			echo("document.frmLabel.btDelete.disabled=false;");
		}
	?>
</script>
</form>
</div>