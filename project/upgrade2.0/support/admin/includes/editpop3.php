<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshith<roshith@armia.com>                                  |
// |                                                                      | 
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

	if (!isset($_POST['txtPort'])) {
		$var_port = 110;
	}

	$var_staffid = $_SESSION["sess_staffid"];
	
	if ($_POST["postback"] == "" && $var_id != "") {
		$sql = "select * from sptbl_pop3settings where nPop3Id='".addslashes($var_id)."'";

		$var_result = executeSelect($sql,$conn); 
		if (mysql_num_rows($var_result) > 0) {
			$var_row = mysql_fetch_array($var_result);
			$var_department = $var_row["nDeptId"];
			$var_deptemail  = $var_row["vDeptEmail"];
			$var_pop3_servername = $var_row["vServerName"];
			$var_pop3_username = $var_row["vUserName"];
			$var_pop3_password = $var_row["vPassword"];
			$var_port = $var_row["nPortNo"];
			
			$sqlCompany = "select nCompId from sptbl_depts where nDeptId='".addslashes($var_department)."'";			
			$var_result = executeSelect($sqlCompany,$conn);
			if (mysql_num_rows($var_result) > 0) {
				$var_row = mysql_fetch_array($var_result);
				$var_companyid = $var_row["nCompId"];
			}	
		}
		else {
			$var_message = MESSAGE_RECORD_ERROR ;
                        $flag_msg    = 'class="msg_error"';
		}
	}elseif ($_POST["postback"] == "A") {
            $var_companyid = trim($_POST["cmbCompany"]);
            $var_department = trim($_POST["cmbParentDepartment"]);
            $var_pop3_servername = trim($_POST["txtPop3Server"]);
            $var_pop3_username   = trim($_POST["txtPop3Username"]);
            $var_pop3_password   = trim($_POST["txtPop3Password"]);
            $var_port = trim($_POST["txtPort"]);

			$dup_flag=0;

			$sql = "select * from sptbl_depts where nDeptId='".addslashes($var_department)."'";

			$var_result = executeSelect($sql,$conn); 
			if (mysql_num_rows($var_result) > 0) {
				$var_row = mysql_fetch_array($var_result);
				$var_depEmail = $var_row["vDeptMail"];
			}

			//check duplicate Server Name
			$sqlDuplicate = "SELECT nPop3Id FROM sptbl_pop3settings WHERE nDeptId ='". addslashes($var_department) ."'";
			$rsDuplicate  = executeSelect($sqlDuplicate,$conn);

			if(mysql_num_rows($rsDuplicate)>0){
			  $dup_flag=1;
			}

			if($dup_flag==0){
					//Insert into the pop3settings table
					$sql  = "Insert into sptbl_pop3settings(nPop3Id,nDeptId,vDeptEmail,vServerName,vUserName,vPassword,nPortNo";
					$sql .= ") Values('','$var_department','" . addslashes($var_depEmail). "','" . addslashes($var_pop3_servername). "','" . addslashes($var_pop3_username). "','" . addslashes($var_pop3_password) . "','" . addslashes($var_port) . "')";

					executeQuery($sql,$conn);
					$var_insert_id = mysql_insert_id($conn);
					//Insert the actionlog
					if(logActivity()) {
						$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_ADDITION . "','Pop3','" . addslashes($var_insert_id) . "',now())";
						executeQuery($sql,$conn);
					}

					$var_message = MESSAGE_RECORD_ADDED;
                                        $flag_msg    = 'class="msg_success"';
					$var_companyid = "";
					$var_department = "";
					$var_pop3_servername = "";
					$var_pop3_username   = "";
					$var_pop3_password   = "";
					$var_port = "";
			}else
				$var_message = MESSAGE_RECORD_DUPLICATE_ERROR ;
                                $flag_msg    = 'class="msg_error"';
	}
	elseif ($_POST["postback"] == "D") {
			$sql = "delete from  sptbl_pop3settings where nPop3Id='" . addslashes($var_id) . "'";
			executeQuery($sql,$conn);

			//Insert the actionlog
			if(logActivity()) {
				$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Pop3','" . addslashes($var_id) . "',now())";			
				executeQuery($sql,$conn);
			}
			
			$var_companyid = "";
			$var_department = "";
			$var_pop3_servername = "";
			$var_pop3_username   = "";
			$var_pop3_password   = "";
			$var_port = "";
			$var_message = MESSAGE_RECORD_DELETED;
                        $flag_msg    = 'class="msg_success"';
	}
    elseif ($_POST["postback"] == "U") {

            $var_companyid = trim($_POST["cmbCompany"]);
            $var_department = trim($_POST["cmbParentDepartment"]);
            $var_pop3_servername = trim($_POST["txtPop3Server"]);
            $var_pop3_username   = trim($_POST["txtPop3Username"]);
            $var_pop3_password   = trim($_POST["txtPop3Password"]);
            $var_port = trim($_POST["txtPort"]);

			$sql = "select * from sptbl_depts where nDeptId='".addslashes($var_department)."'";

			$var_result = executeSelect($sql,$conn); 
			if (mysql_num_rows($var_result) > 0) {
				$var_row = mysql_fetch_array($var_result);
				$var_depEmail = $var_row["vDeptMail"];
			}

			$dup_flag=0;

			//check duplicate Server Name
			$sqlDuplicate = "SELECT nPop3Id FROM sptbl_pop3settings WHERE vServerName='". addslashes($var_pop3_servername) ."' and vUserName='". addslashes($var_pop3_username) ."' and nPop3Id !='$var_id'";
			$rsDuplicate  = executeSelect($sqlDuplicate,$conn);

			if(mysql_num_rows($rsDuplicate)>0){
			  $dup_flag=1;
			}
						
			if ($dup_flag==0) {				
					$sql = "Update sptbl_pop3settings set vServerName='" . addslashes($var_pop3_servername) . "',
								vUserName='" . addslashes($var_pop3_username) . "',
								vPassword='" . addslashes($var_pop3_password) . "',
								nPortNo='" . addslashes($var_port). "'
								where nPop3Id='" . addslashes($var_id) . "'";
					executeQuery($sql,$conn);

					//Insert the actionlog
					if(logActivity()) {
						$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Pop3','" . addslashes($var_id) . "',now())";			
						executeQuery($sql,$conn);
					}

					$var_companyid = "";
					$var_department = "";
					$var_pop3_servername = "";
					$var_pop3_username   = "";
					$var_pop3_password   = "";
					$var_port = "";

					$var_message = MESSAGE_RECORD_UPDATED;
                                        $flag_msg    = 'class="msg_success"';
			}
			else {
				$var_message = MESSAGE_RECORD_ERROR ;
                                $flag_msg    = 'class="msg_error"';
			}		
   }elseif ($_POST["postback"] == "CC") {
             $var_companyid = trim($_POST["cmbCompany"]);
             $var_department = trim($_POST["cmbParentDepartment"]);
             $var_pop3_servername = trim($_POST["txtPop3Server"]);
             $var_pop3_username   = trim($_POST["txtPop3Username"]);
             $var_pop3_password   = trim($_POST["txtPop3Password"]);
             $var_port = trim($_POST["txtPort"]);

   }elseif ($_POST["postback"] == "CP") {
             $var_companyid = trim($_POST["cmbCompany"]);
             $var_department = trim($_POST["cmbParentDepartment"]);
             $var_pop3_servername = trim($_POST["txtPop3Server"]);

			 $sql = "select vDeptMail from sptbl_depts where nDeptId='".addslashes($var_department)."'";

			 $var_result = executeSelect($sql,$conn); 
			 if (mysql_num_rows($var_result) > 0) {
				$var_row = mysql_fetch_array($var_result);
				$var_depEmail = $var_row["vDeptMail"];
			 }


//             $var_pop3_username   = trim($_POST["txtPop3Username"]);
			 $var_pop3_username = $var_depEmail;
             $var_pop3_password   = trim($_POST["txtPop3Password"]);
             $var_port = trim($_POST["txtPort"]);
   }

	function make_selectlist($current_dept_id, $count,$cmpid) {
         static $option_results;
		
         if (!isset($current_dept_id)) {
              $current_dept_id =0;
         }
		 if (!isset($cmpid)) {
              $cmpid =0;
         }
         $count = $count+1;
		 
         $sql = "SELECT nDeptId as id, vDeptDesc as name,vDeptMail as email from sptbl_depts where nDeptParent = '$current_dept_id' and nCompId=$cmpid order by name asc ";
  
		 $get_options = mysql_query($sql);
         $num_options = mysql_num_rows($get_options);
		
         if($num_options > 0)
         {
             while (list($dept_id, $dept_name, $email) = mysql_fetch_row($get_options)) {
			    $dept_name = htmlentities($dept_name);
			    $dept_mail = htmlentities($email);
				$option_results[$dept_id] = $dept_name." ($dept_mail) ";
                make_selectlist($dept_id, $count,$cmpid );				   
             }
         }
         return $option_results;
	}
?>
<form name="frmPOP3" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>">

<div class="content_section">
			<div class="content_section_title">
				<h3><?php echo TEXT_EDIT_POP3_CONFIG ?></h3>
			</div>
<table width="100%"  border="0">
  <tr>
    <td width="76%" valign="top">
    <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
          <tr>
	         <td align="center" colspan=3 >&nbsp;</td>
         </tr>
         <tr>
         	<td align="center" colspan=3>
							 <?php
				
				if ($var_message != ""){
				?>
					<div <?php echo $flag_msg; ?>>
				<b><?php echo($var_message); ?></b>
				</div>
				<?php
				}
				?>			
			 </td>
         </tr>
         <tr>
		 	<td>&nbsp;</td>
	        <td align="left" colspan=2 class="toplinks">
    	    <?php echo TEXT_FIELDS_MANDATORY ?></td>
         </tr>
         <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
	        <td align="center" colspan=3 class="toplinks">
			<div class="msg_common" style="text-align:center; ">
    	    <?php echo MESSAGE_MAIL_DELETE_WARNING ?>
			</div>
			</td>
         </tr>
         <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="2%" align="left">&nbsp;</td>
         <td width="39%" align="left" class="toplinks">
		 <?php echo TEXT_CON_COMPANY?> <font style="color:#FF0000; font-size:9px">*</font> </td>
         <td width="59%" align="left">
			   <?php
				 $sql = "SELECT nCompId,vCompName   FROM `sptbl_companies` where vDelStatus=0 order by vCompName";			
				 $rs = executeSelect($sql,$conn);
				 $cnt = 1;
				?>
				<input type=hidden name="cmbCompanyhidden" value="<?php echo   $var_companyid?>">
			   <select name="cmbCompany" size="1" class="comm_input input_width1a" id="cmbCompany" onchange="changecompany();" style="width:215px" >
				 <?php
						   $options ="<option value='0'";
						   $options .=">Select Company</option>\n"; 
							echo $options;
							while($row = mysql_fetch_array($rs)) {
								  $options ="<option value='".$row['nCompId']."'";
								  if ($var_companyid == $row['nCompId']){
									   $options .=" selected=\"selected\"";
								  }
								  $options .=">".htmlentities($row['vCompName'])."</option>\n";
								  echo $options;
							}                                            
				 ?>
				</select>
         </td>
         </tr>
         <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="2%" align="left">&nbsp;</td>
         <td width="39%" align="left" class="toplinks"><?php echo TEXT_CON_DEPARTMENT?> <font style="color:#FF0000; font-size:9px">*</font> </td>
         <td width="59%" align="left">
				<input type=hidden name="cmbParentDepartmenthidden" value="<?php echo   $var_department?>">
			   <select name="cmbParentDepartment" size="1" class="comm_input input_width1a" id="cmbParentDepartment" onChange="changedept()"  style="width:215px">
				 <?php
						$options="";
						$get_options = make_selectlist(0,0,$var_companyid);

						if (count($get_options) > 0)
						{
							  $departments = $_POST['dept_id'];
							  $options ="<option value='0'";
							  $options .=">" . TEXT_DEPT_SELECT . "</option>\n";
							  foreach ($get_options  as $key => $value) {
								  $options .= "<option value=\"$key\"";
								  if ($var_department == "$key")
								  {
									   $options .=" selected=\"selected\"";
								  }
								  $options .=">" . $value . "</option>\n";
							 }
						}else{
						   $options ="<option value='0'";
						   $options .=">Parent level</option>\n";
						}
						echo $options;
				 ?>
			   </select>
         </td>
         </tr>
		 <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="2%" align="left">&nbsp;</td>
         <td width="39%" align="left" class="toplinks"><?php echo TEXT_CON_SERVER_NAME?> <font style="color:#FF0000; font-size:9px">*</font> </td>
         <td width="59%" align="left">
         <input name="txtPop3Server" type="text" class="comm_input input_width1" id="txtPop3Server" size="30" maxlength="30" value="<?php echo htmlentities($var_pop3_servername); ?>">
         </td>
         </tr>
         <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="2%" align="left">&nbsp;</td>
         <td width="39%" align="left" class="toplinks"><?php echo TEXT_CON_USERNAME?> <font style="color:#FF0000; font-size:9px">*</font> </td>
         <td width="59%" align="left" class="toplinks">
		 <input name="txtPop3Username" type="text" class="comm_input input_width1" id="txtPop3Username" size="30" maxlength="30" value="<?php echo htmlentities($var_pop3_username); ?>" readonly>
         </td>
         </tr>
         <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="2%" align="left">&nbsp;</td>
         <td width="39%" align="left" class="toplinks"><?php echo TEXT_CON_PASSWORD?> <font style="color:#FF0000; font-size:9px">*</font> </td>
         <td width="59%" align="left">
	        <input name="txtPop3Password" type="password" class="comm_input input_width1" id="txtPop3Password" size="30" maxlength="30" value="<?php echo htmlentities($var_pop3_password); ?>">
         </td>
         </tr>
         <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="2%" align="left">&nbsp;</td>
         <td width="39%" align="left" class="toplinks"><?php echo TEXT_CON_PORT?> <font style="color:#FF0000; font-size:9px">*</font> </td>
         <td width="59%" align="left"  class="toplinks">
			<input name="txtPort" type="text" class="comm_input input_width1" id="txtPort" size="4" maxlength="5" value="<?php echo htmlentities($var_port); ?>" style="width:200px">
         </td>
         </tr>
		 <tr><td colspan="3" class="btm_brdr">&nbsp;</td></tr>
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
                              <td>
                              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center"  class="listingbtnbar">
                                    <td colspan='7' align="center">
									<?php if(isset($_GET["id"])){?>
                                    <input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT ?>"  onClick="javascript:edit();">&nbsp;&nbsp;
                                    <input name="btDelete" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_DELETE ?>" onClick="javascript:deleted();">&nbsp;&nbsp;
									<?php }else{?>
                                    <input name="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD ?>" onClick="javascript:add();">&nbsp;&nbsp;
									<?php }?>
                                    <input name="btCancel" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_CANCEL ?>" onClick="javascript:cancel();">
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
</form>