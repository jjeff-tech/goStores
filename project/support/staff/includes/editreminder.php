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
$var_alert = $_POST["txtAlert"];

$var_alert = ($var_alert == "")?date("m/d/Y H:i"):$var_alert;

$var_staff = $_SESSION["sess_staffname"];
$arr_alert = explode("/",$var_alert);
$arr_year = explode(" ",$arr_alert[2]);
$arr_tm = explode(":",$arr_year[1]);
$var_time = $arr_year[0] ."-" . $arr_alert[0] . "-" . $arr_alert[1] . " " . $arr_tm[0] . ":" . $arr_tm[1];

if ($_POST["postback"] == "" && $var_id != "") {
    $sql = "Select R.nRemId,R.nStaffId,R.vRemTitle,R.tRemDesc,R.dRemAlert,R.dRemPost,S.vStaffname from sptbl_reminders R
		 		inner join sptbl_staffs S on R.nStaffId = S.nStaffId where nRemId='" . mysql_real_escape_string($var_id) . "' ";
    $result = executeSelect($sql,$conn);
    if (mysql_num_rows($result) > 0) {
        $var_row = mysql_fetch_array($result);
        $var_staffid = $var_row["nStaffId"];
        $var_staff = $var_row["vStaffname"];
        $var_title = $var_row["vRemTitle"];
        $var_desc = $var_row["tRemDesc"];
        $var_alert = date("m/d/Y H:i",strtotime($var_row["dRemAlert"]));
    }
    else {
        $var_message = MESSAGE_RECORD_ERROR;
        $flag_msg = "class='msg_error'";
    }
}
elseif ($_POST["postback"] == "A") {

    if (validateAddition() == true) {
        $sql = "Insert into sptbl_reminders(nRemId,nStaffId,vRemTitle,tRemDesc,dRemAlert,dRemPost)
					Values('','$var_staffid','" . mysql_real_escape_string($var_title) . "',
					'" . mysql_real_escape_string($var_desc) . "','" . $var_time . "',now()) ";
        executeQuery($sql,$conn);

        $var_insert_id = mysql_insert_id($conn);

        if(logActivity()) {
            $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . mysql_real_escape_string(TEXT_ADDITION) . "','Reminders','$var_insert_id',now())";
            executeQuery($sql,$conn);
        }

        $var_message = MESSAGE_RECORD_ADDED;
        $flag_msg = "class='msg_success'";

        $var_title = "";
        $var_desc = "";
        $var_alert = date("m/d/Y H:i",time());
    }
    else {
        $var_message = TEXT_ALREADT_EXIST;
        $flag_msg = "class='msg_error'";
    }
}
elseif ($_POST["postback"] == "D") {

    if (validateDeletion() == true) {

        $sql = "Delete from sptbl_reminders where nRemId='" . mysql_real_escape_string($var_id) . "' ";
        executeQuery($sql,$conn);

        if(logActivity()) {
            $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Reminders','" . mysql_real_escape_string($var_id) . "',now())";
            executeQuery($sql,$conn);
        }

        $var_message = MESSAGE_RECORD_DELETED;
        $flag_msg = "class='msg_success'";
        $var_title = "";
        $var_desc = "";
        $var_alert = date("m/d/Y H:i",time());
        $var_id = "";

    }
    else {
        $var_message = MESSAGE_RECORD_ERROR;
        $flag_msg = "class='msg_error'";
    }
} 
elseif ($_POST["postback"] == "U") {

    if (validateUpdation() == true) {
        $sql = "Update sptbl_reminders set
					vRemTitle='" . mysql_real_escape_string($var_title) . "',
					tRemDesc='" . mysql_real_escape_string($var_desc) . "',
					dRemAlert='$var_time' where nRemId='" . mysql_real_escape_string($var_id) . "'";
        executeQuery($sql,$conn);

        if(logActivity()) {
            $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Reminders','" . mysql_real_escape_string($var_id) . "',now())";
            executeQuery($sql,$conn);
        }

        $var_message = MESSAGE_RECORD_UPDATED;
        $flag_msg = "class='msg_success'";
    }
    else {
        $var_message = TEXT_ALREADT_EXIST;
        $flag_msg = "class='msg_error'";
    }
}

function validateAddition() {
    global $var_time;
    if (trim($_POST["txtTitle"]) == "" || trim($_POST["txtDesc"]) == "") {
        return false;
    }
    else {
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
<form name="frmReminder" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>">
    <div class="content_section">

        <div class="content_section_title">
            <h3><?php echo TEXT_VW_REMINDER ?></h3>
        </div>

        <div class="content_section_data">

            <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
                <tr>
                    <td width="100%" align="center" colspan=3 class="toplinks">
                        <?php echo TEXT_FIELDS_MANDATORY ?></td>

                </tr>

                <tr>
                    <td width="100%" align="center" colspan=3 class="errormessage">
                        <div <?php echo $flag_msg;?>> <?php echo $var_message ?></div></td>

                </tr>
                <tr>
                    <td width="13%" align="left">&nbsp;</td>
                    <td width="26%" align="left" class="toplinks"><?php echo TEXT_REMINDER_STAFF?> </td>
                    <td width="61%" align="left">
                        <input name="txtStaff" type="text" class="comm_input input_width1a" id="txtStaff" size="60" maxlength="100" value="<?php echo htmlentities($var_staff); ?>" style="font-size:11px; ">
                    </td>
                </tr>

                <tr><td colspan="3">&nbsp;</td></tr>
                <tr>
                    <td width="13%" align="left">&nbsp;</td>
                    <td width="26%" align="left" class="toplinks"><?php echo TEXT_REMINDER_ALERT?> <font style="color:#FF0000; font-size:9px">*</font> </td>
                    <td width="61%" align="left">
                        <input name="txtAlert" type="text" class="comm_input input_width1a" id="txtAlert" size="24" maxlength="20" value="<?php echo htmlentities($var_alert); ?>" style="font-size:11px;width:170px; height:25px!important; float:left; margin-right:5px;" readonly="true">
                        <input name="btAlert"  id="btAlert" type="button" class="secondary_btn" value=" " onClick="">
                        <script type="text/javascript">
                            Calendar.setup({
                                inputField    	: "txtAlert",
                                button        	: "btAlert",
                                ifFormat      	: "%m/%d/%Y %H:%M",       // format of the input field
                                showsTime      	: true,
                                timeFormat     	: "24"
                            });
                        </script>
                    </td>
                </tr>

                <tr><td colspan="3">&nbsp;</td></tr>
                <tr>
                    <td width="13%" align="left">&nbsp;</td>
                    <td width="26%" align="left" class="toplinks"><?php echo TEXT_REMINDER_TITLE?> <font style="color:#FF0000; font-size:9px">*</font> </td>
                    <td width="61%" align="left">
                        <input name="txtTitle" type="text" class="comm_input input_width1a" id="txtTitle" size="60" maxlength="100" value="<?php echo htmlentities($var_title); ?>" style="font-size:11px; ">
                    </td>
                </tr>
                <tr><td colspan="3">&nbsp;</td></tr>
                <tr>
                    <td align="left">&nbsp;</td>
                    <td align="left" class="toplinks" valign="top"><?php echo TEXT_REMINDER_DESCRIPTION ?> <font style="color:#FF0000; font-size:9px">*</font></td>
                    <td width="61%" align="left">
                        <textarea name="txtDesc" cols="70" rows="12" id="txtDesc" class="comm_input input_width1a" style="width:330px;"><?php echo htmlentities($var_desc); ?></textarea></td>
                </tr>
                <tr><td colspan="3">&nbsp;</td></tr>
            </table>
        </div>






        <table width="100%"  border="0" cellspacing="10" cellpadding="0">
            <tr>
                <td>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                        <tr >

                            <td ><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                    <tr align="center"  class="whitebasic">
                                        <td width="22%">&nbsp;</td>
                                        <td width="16%"><input name="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD; ?>" onClick="javascript:add();"></td>
                                        <td width="14%"><input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT; ?>"  onClick="javascript:edit();"></td>
                                        <td width="16%"><input name="btDelete" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_DELETE; ?>" onClick="javascript:deleted();"></td>
                                        <td width="12%"><input name="btCancel" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_CLEAR; ?>" onClick="javascript:cancel();"></td>
                                        <td width="20%">
                                            <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
                                            <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                                            <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
                                            <input type="hidden" name="id" value="<?php echo($var_id); ?>">
                                            <input type="hidden" name="uname" value="<?php echo htmlentities($_SESSION["sess_staffname"]); ?>">
                                            <input type="hidden" name="postback" value="">
                                        </td>
                                    </tr>
                                </table>

                            </td>

                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </div>
    <script>
<?php

if ($var_id == "") {
    echo("document.frmReminder.btAdd.disabled=false;");
    echo("document.frmReminder.btUpdate.disabled=true;");
    echo("document.frmReminder.btDelete.disabled=true;");
}
elseif ($var_staffid == $_SESSION["sess_staffid"]) {
    echo("document.frmReminder.btAdd.disabled=true;");
    echo("document.frmReminder.btUpdate.disabled=false;");
    echo("document.frmReminder.btDelete.disabled=false;");
}
else {
    echo("document.frmReminder.btAdd.disabled=true;");
    echo("document.frmReminder.btUpdate.disabled=true;");
    echo("document.frmReminder.btDelete.disabled=true;");
}
?>
    </script>
</form>