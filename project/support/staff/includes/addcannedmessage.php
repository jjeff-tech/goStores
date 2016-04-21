<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>    		                      |
// |          									                          |
// +----------------------------------------------------------------------+
	$flag_msg='';
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
	$var_staffid = $_SESSION["sess_staffid"];
	$var_message="";
	if ($_POST["postback"] == "A") {
			$var_title = $_POST["txtTitle"];
			$var_description = $_POST["txtDescription"];
			$var_status = $_POST["rdSts"];
			$var_date =  date("Y-m-d");

		if (validateAddition() == true) {
				//Insert into the company table
				$sql = "Insert into sptbl_cannedmessages(nMsgId,vTitle,vDescription,vStatus,nStaffId,dDate)";
				$sql .= " Values('','" . mysql_real_escape_string($var_title) . "','" . mysql_real_escape_string($var_description). "','" . mysql_real_escape_string($var_status) . "','" . mysql_real_escape_string($var_staffid) . "',
						'" . mysql_real_escape_string($var_date) . "')";
				executeQuery($sql,$conn);
				$var_insert_id = mysql_insert_id($conn);
				
				//Insert the actionlog
				if(logActivity()) {
				$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . mysql_real_escape_string(TEXT_ADDITION) . "','Canned Message','$var_insert_id',now())";
				executeQuery($sql,$conn);
				}
				
				$var_message = MESSAGE_RECORD_ADDED;
                                $flag_msg="class='msg_success'";
				//Send mail with the password to the user here
		}
		else {
			$var_message = MESSAGE_RECORD_ERROR;
                         $flag_msg="class='msg_error'";
		}
	}

	function validateAddition() 
	{
		if (trim($_POST["txtTitle"]) == "" || trim($_POST["txtDescription"]) == "" ) {
			return false;
		} else {
		  return true;
		}
	}
	
	function validateDeletion($var_list) 
	{
		//implement logic here
		global $conn;
		$sql = "Select nTicketId from sptbl_tickets where vStatus !='closed' AND nUserId IN($var_list) ";
		if (mysql_num_rows(executeSelect($sql,$conn)) > 0) {
			return false;
		}
		else {
			return true;
		}
	}
	
	function validateUpdation() 
	{
		global $conn,$var_id;
		//implement logic here
		if (trim($_POST["txtUserName"]) == "" || trim($_POST["txtUserLogin"]) == ""  || trim($_POST["txtEmail"]) == "") {
			return false;
		}
		elseif(!isValidUsername(trim($_POST["txtUserLogin"])) || !isValidEmail(trim($_POST["txtEmail"]))){
			return false;	
		}
		else {
			$sql = "Select nCompId from sptbl_companies where vDelStatus='0' AND nCompId='" . mysql_real_escape_string($_POST["cmbCompanyId"]) . "' ";
			if (mysql_num_rows(executeSelect($sql,$conn)) <= 0) {
				return false;
			}
			else {
				$sql = "Select nUserId from sptbl_users where vLogin='" . mysql_real_escape_string(trim($_POST["txtUserLogin"])) . "' AND nUserId != '" . mysql_real_escape_string($var_id) . "'";
				if (mysql_num_rows(executeSelect($sql,$conn)) > 0) {
					return false;
				}
			}
		}
		return true;
	}
	
	
		$lst_comp = "";
	//fill the css ids here
	$sql = "Select nCompId,vCompName from sptbl_companies  where (vDelStatus='0') and (nCompId IN (".getStaffCompanies($_SESSION["sess_staffid"]).")) order by vCompName ";
	$result = executeSelect($sql,$conn);
	while ($row = mysql_fetch_array($result)) {
		$lst_comp .=  "<option value=\"" . $row["nCompId"] . "\"" . (($var_compId == $row["nCompId"])?"Selected":"") . ">" . htmlentities($row["vCompName"]) . "</option>"; 
	}
	mysql_free_result($result);
	//end of fill the css ids here

?>
<form name="frmCannedmessage" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>">
<div class="content_section">
 <div class="content_section_title">
	<h3><?php echo TEXT_ADD_CANNEDMESSAGE ?></h3>
	</div>
  
     <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="column1">
     <tr>
     <td>
         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
		<tr>
         <td width="100%" align="center" colspan=3 class="toplinks">
         <?php echo TEXT_FIELDS_MANDATORY ?></td>

         </tr>

         <tr>
         <td width="100%" align="center" colspan=3 class="messsage">
        <div <?php echo $flag_msg; ?>> <?php echo $var_message ?> </div></td>
         </tr>
		<tr><td colspan="3">&nbsp;</td></tr>
        <tr>
         <td width="13%" align="left">&nbsp;</td>
         <td width="26%" align="left" class="toplinks"><?php echo TEXT_TITLE?> <font style="color:#FF0000; font-size:9px">*</font> </td>
         <td width="61%" align="left">
         <input name="txtTitle" type="text" class="comm_input input_width1" id="txtTitle" size="30" maxlength="100" value="">
         </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_DESCRIPTION?> <font style="color:#FF0000; font-size:9px">*</font></td>
                      <td width="61%" align="left">
					    <textarea name="txtDescription" cols="70" rows="12" id="txtDescription" class="textarea" style="width:400px;"></textarea>
					  </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
					  
                     <tr>
                      <td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_STATUS?></td>
                      <td width="61%" align="left">
                      <input name="rdSts" type="radio" value="1" <?php echo "checked"; ?>>
						Active 
						<input name="rdSts" type="radio" value="0" >
						Not Active
                      </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
																																							
                              </table>
                        </td>
                            </tr>
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
                                  <tr align="center"  class="whitebasic">
                                    <td width="22%">&nbsp;</td>
                                    <td width="58%" colspan="3">
									<input name="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD; ?>" onClick="javascript:add();">&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
									<input name="btCancel" type="reset" class="comm_btn" value="<?php echo BUTTON_TEXT_CLEAR; ?>" >&nbsp;&nbsp;
									</td>
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
   
</div>

</form>