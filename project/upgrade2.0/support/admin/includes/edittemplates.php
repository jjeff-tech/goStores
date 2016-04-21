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
		$sql = "Select * from sptbl_templates  ";
        $sql .=" where nTemplateId ='".addslashes($var_id)."'";
		$var_result = executeSelect($sql,$conn); 
		if (mysql_num_rows($var_result) > 0) {
			$var_row = mysql_fetch_array($var_result);  
			$var_title = $var_row["vTemplateTitle"];
			$var_desc= $var_row["tTemplateDesc"];
     	}
		else {
			$var_id="";
			$var_message = MESSAGE_RECORD_ERROR ;
                        $flag_msg  = 'class="msg_error"';
		}
	}
	elseif ($_POST["postback"] == "A") {
	      	$var_title= trim($_POST["txtTitle"]);
			$var_desc = trim($_POST["txtDesc"]);
			$dup_flag=0;
			//check duplicate name template title to block page refrsh
			$sql="select *  from sptbl_templates  WHERE   vTemplateTitle ='".addslashes($var_title) . "'";
			$rs = executeSelect($sql,$conn);
			if(mysql_num_rows($rs)>0){
			  $dup_flag=1;
			}
			
		if (validateAddition() == true and $dup_flag==0) {
		  	$sql = "Insert into sptbl_templates (nTemplateId,vTemplateTitle,dDate,tTemplateDesc,nStaffId,vStatus";
			$sql .= ") Values('','" . addslashes($var_title). "',now(),'" . addslashes($var_desc) . "','$var_staffid','1')" ;
		    executeQuery($sql,$conn);
			$var_insert_id = mysql_insert_id($conn);
			//Insert the actionlog
			if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_ADDITION . "','Templates','" . addslashes($var_insert_id) . "',now())";			
			executeQuery($sql,$conn);
			}
			
			$var_message = MESSAGE_RECORD_ADDED;
                        $flag_msg  = 'class="msg_success"';
			$var_title= "";
			$var_desc = "";
			
		}
		else {
		 	$var_message = MESSAGE_RECORD_ERROR ;
                        $flag_msg  = 'class="msg_error"';
		}
	}
	elseif ($_POST["postback"] == "D") {
	       
			
		if (validateDeletion() == true) {
			$sql = "delete from  sptbl_templates  where nTemplateId ='" . addslashes($var_id) . "'";
			executeQuery($sql,$conn);
			
			//Insert the actionlog
			if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Templates','" . addslashes($var_id) . "',now())";			
			executeQuery($sql,$conn);
			}
			$var_title= "";
			$var_desc = "";
			$var_id="";
			$var_message = MESSAGE_RECORD_DELETED;
                        $flag_msg  = 'class="msg_success"';
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
	   $sql = "Select nTemplateId from sptbl_templates Where nTemplateId='" . addslashes($var_id) . "'";    
		if(mysql_num_rows(executeSelect($sql,$conn)) > 0) {
			$var_title= trim($_POST["txtTitle"]);
			$var_desc = trim($_POST["txtDesc"]);
			$dup_flag=0;
			//check duplicate  template title to block page refrsh
			$sql="select *  from sptbl_templates  WHERE   vTemplateTitle ='".addslashes($var_title) . "'";
			$sql .=" and  nTemplateId   !=$var_id";
			$rs = executeSelect($sql,$conn);
			if(mysql_num_rows($rs)>0){
			  $dup_flag=1;
			}
			
			if (validateUpdation() == true and $dup_flag==0) {
			     $var_validdate =datetimetomysql($var_validdate);
				 $sql = "Update sptbl_templates  set vTemplateTitle ='" . addslashes($var_title) . "',
					     tTemplateDesc ='" . addslashes($var_desc) . "',
					     dDate  =now()
					     where nTemplateId='" . addslashes($var_id) . "'";
						
			executeQuery($sql,$conn);
			//Insert the actionlog
			if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Templates','" . addslashes($var_id) . "',now())";			
			executeQuery($sql,$conn);
			}
			    
				$var_message = MESSAGE_RECORD_UPDATED;
                                $flag_msg  = 'class="msg_success"';
			}
			else {
				$var_message = MESSAGE_RECORD_ERROR ;
                                $flag_msg  = 'class="msg_error"';
			}
		}
		else {
			$var_id="";
			$var_message = MESSAGE_INVALID_TEMPLATE ;
                        $flag_msg  = 'class="msg_error"';
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
<form name="frmTemplate" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>">
<Div class="content_section_title"><h3><?php echo TEXT_EDIT_TEMPLATES ?></h3></Div>
   
    
<Div class="content_section_data">
     
     
     
     
     
    
	       
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
                     <td width="9%" align="left">&nbsp;</td>
                     <td width="20%" align="left" class="toplinks"><?php echo TEXT_TITLE?> <font style="color:#FF0000; font-size:9px">*</font> </td>
					  <td width="71%" align="left">
                        <input name="txtTitle" type="text" class="comm_input input_width1" id="txtTitle" size="64" maxlength="100" value="<?php echo htmlentities($var_title); ?>">
					  </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
					  <tr>
                     <td width="9%" align="left">&nbsp;</td>
                     <td width="20%" align="left" class="toplinks" valign="top"><?php echo TEXT_DESC?> <font style="color:#FF0000; font-size:9px">*</font> </td>
                     <td width="71%" align="left">
                        <textarea name="txtDesc" cols="50" rows="12" id="txtDesc" class="comm_input input_width1" style="width:480px;" ><?php echo htmlentities($var_desc); ?></textarea>
					 </td>
                      </tr>                      
					    <tr><td colspan="3">&nbsp;</td></tr>
								</table>
                      
           
                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center"  class="listingbtnbar">
                                    <td width="22%">&nbsp;</td>
                                    <td width="16%"><input name="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD ?>" onClick="javascript:add();"></td>
                                    <td width="14%"><input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT ?>"  onClick="javascript:edit();"></td>
                                    <td width="16%"><input name="btDelete" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_DELETE ?>" onClick="javascript:deleted();"></td>
                                    <td width="12%"><input name="btCancel" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_CANCEL ?>" onClick="javascript:cancel();"></td>
                                    <td width="20%">
									<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
									<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
									<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">																
									<input type="hidden" name="id" value="<?php echo($var_id); ?>">
									<input type="hidden" name="postback" value="">
									</td>
                                  </tr>
                              </table>
                 
          
<script>
	var setValue = "<?php echo trim($var_country); ?>";

	<?php
		if ($var_id == "") {
			echo("document.frmTemplate.btAdd.disabled=false;");
			echo("document.frmTemplate.btUpdate.disabled=true;");
			echo("document.frmTemplate.btDelete.disabled=true;");
		}
		else {
			echo("document.frmTemplate.btAdd.disabled=true;");
			echo("document.frmTemplate.btUpdate.disabled=false;");
			echo("document.frmTemplate.btDelete.disabled=false;");
		}
	?>
	
</script>
</div>
</form>
</div>