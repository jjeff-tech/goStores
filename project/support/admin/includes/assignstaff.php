<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>                                          |
// |                                                                                                            |
// +----------------------------------------------------------------------+

if ($_GET["stylename"] != "") {
    $var_styleminus = $_GET["styleminus"];
    $var_stylename = $_GET["stylename"];
    $var_styleplus = $_GET["styleplus"];
} else {
    $var_styleminus = $_POST["styleminus"];
    $var_stylename = $_POST["stylename"];
    $var_styleplus = $_POST["styleplus"];
}

$var_staffid = $_SESSION["sess_staffid"];




if ($_POST["postback"] == "A") {
    if (validateSave() == true) {
        $var_flag = true;
        $var_staffassign_id = trim($_POST["cmbstaff"]);
        $var_tosave = trim(mysql_real_escape_string($_POST['tosave']));
        if ($var_tosave != "") {
            $sql = "Select nDeptId from sptbl_depts where nDeptParent IN($var_tosave)";
            if (mysql_num_rows(executeSelect($sql, $conn)) > 0) {
                $var_flag = false;
            }
        }
        if ($var_flag == true) {
            $sql = "delete from  sptbl_staffdept where nStaffId='" . mysql_real_escape_string($var_staffassign_id) . "'";
            executeQuery($sql, $conn);
            if (logActivity()) {
                $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Staff Department','$var_staffassign_id',now())";
                executeQuery($sql, $conn);
            }
            if (trim($var_tosave) != "") {
                $tosaveids = explode(",", $var_tosave);
                if (count($tosaveids) > 0) {
                    foreach ($tosaveids as $key => $value) {
                        $sql = "insert into sptbl_staffdept values('$var_staffassign_id','$value')";
                        executeQuery($sql, $conn);
                        if (logActivity()) {
                            $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_ADDITION . "','Staff Department','$var_staffassign_id,$value',now())";
                            executeQuery($sql, $conn);
                        }
                    }
                }
            }
            $var_message = MESSAGE_RECORD_SAVED;
            $flag_msg    = 'class="msg_success"';
        } else {
            $var_message =  MESSAGE_LEAF_ERROR;
            $flag_msg    = 'class="msg_error"';
        }
    } else {

        $var_message =  MESSAGE_RECORD_ERROR;
        $flag_msg    = 'class="msg_error"';
    }
} elseif ($_POST["postback"] == "C") {
    $var_staffassign_id = trim($_POST["cmbstaff"]);
}

function validateSave() {
    if (trim($_POST["cmbstaff"]) <= 0)
        return false;
    else
        return true;
}
?>
<form name="frmAssignStaff" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>">
    <div class="content_section">
        <div class="content_section_title">
            <h3><?php echo TEXT_ASSIGN_STAFF ?></h3>
        </div>
        <table width="100%"  border="0">
            <tr>
                <td width="76%" valign="top">
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">

                        <tr>
                            <td width="100%" align="center" colspan=3 class="errormessage">
                                <?php if ($var_message != "") { ?>
                                <div <?php echo $flag_msg; ?>>
                                        <?php echo $var_message ?>
                                </div>
                                    <?php }
                                ?>
                            </td>

                        </tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>

                            <td width="26%" align="center" class="toplinks" colspan="3">
                                <div class="content_search_container" style="background-color:#ffffff; ">
                                    <div class="left rightmargin topmargin">
                                        <?php echo TEXT_STAFF ?> <font style="color:#FF0000; font-size:9px">*</font>
                                    </div>
                                    <div class="left rightmargin">
                                        <?php
                                        $sql = "SELECT nStaffId,vLogin   FROM `sptbl_staffs` where vDelStatus=0 order by vStaffName";
                                        $rs = executeSelect($sql, $conn);
                                        ?>
                                        <select name="cmbstaff" size="1" class="selectstyle" id="cmbstaff" onchange="changestaff();">
                                            <?php
                                            $options = "<option value='0'";
                                            $options .=">" . TEXT_SELECT_STAFF . "</option>\n";
                                            echo $options;
                                            while ($row = mysql_fetch_array($rs)) {
                                                $options = "<option value='" . $row['nStaffId'] . "'";
                                                if ($var_staffassign_id == $row['nStaffId']) {

                                                    $options .=" selected=\"selected\"";
                                                }
                                                $options .=">" . $row['vLogin'] . "</option>\n";
                                                echo $options;
                                            }
                                            ?>

                                        </select>

                                    </div>

                                    <div class="clear"></div>
                                </div>



                            </td>

                        </tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr><td colspan=3 align=center>
                                <div class="content_section_data">
                                    <table width="100%" align=center>
                                        <?php
                                        if ($var_staffassign_id == "") {
                                            $var_staffassign_id = 0;
                                        }
                                        $sql = "select nDeptId from sptbl_staffdept where nStaffId=$var_staffassign_id";

                                        $assigned_ids = "0";
                                        $rs = executeSelect($sql, $conn);

                                        while ($row = mysql_fetch_array($rs)) {
                                            $assigned_ids .= $row['nDeptId'] . ",";
                                        }

                                        $assigned_ids = substr($assigned_ids, 0, -1);
                                        if ($assigned_ids == "")
                                            $assigned_ids = "0";

                                        //find leaf level dept
                                        $leafdeptarr = getLeafDepts();
                                        if ($leafdeptarr != "") {
                                            $leaflvldeptids = implode(",", $leafdeptarr);
                                        } else {
                                            $leaflvldeptids = 0;
                                        }
                                        ?>

                                        <tr>
                                            <td width="44%" align="left" class="whitebasic">
                                                <div class="content_section_title">
                                                    <h4><?php echo TEXT_AVAILABLE_DEPT ?></h4>
                                                </div>

                                                <select multiple name="availabledept" style="width:410px; border:1px solid #cfcfcf;" size=20 class="textarea">
                                                    <?php
                                                    $sql = "Select d.nDeptId,d.vDeptCode,d.vDeptDesc,c.vCompName from sptbl_depts as d,sptbl_companies as c ";
                                                    $sql .=" where d.nCompId=c.nCompId and (d.nDeptId not in($assigned_ids) and d.nDeptId  in($leaflvldeptids))";

                                                    $rs = executeSelect($sql, $conn);
                                                    while ($row = mysql_fetch_array($rs)) {
                                                        $options = "<option value='" . $row['nDeptId'] . "'";

                                                        $options .=">" . htmlentities($row['vDeptDesc']) . "</option>\n";
                                                        echo $options;
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td width="24%">
                                                <table align="center">

                                                    <tr>
                                                        <td>
                                                            <input type="button" value=">" style="width:40px;" class="comm_btn" onclick="availbaletoalloted(this.form);" >
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="button" class="comm_btn" style="width:40px;" value="<" onclick="alloted(this.form);">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="button" class="comm_btn" style="width:40px;" value=">>" onclick="makeallottedall(this.form);">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="button" class="comm_btn" style="width:40px;" value="<<" onclick="makeavailableall(this.form);">
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>

                                            <td width="32%" align="left" class="whitebasic">
                                                <div class="content_section_title">
                                                    <h4><?php echo TEXT_ALOTTED_DEPT ?></h4>
                                                </div>


                                                <select name="alotteddept" multiple size=20 style="width:410px; border:1px solid #cfcfcf;" class="textarea">
                                                    <?php
                                                    $sql = "Select d.nDeptId,d.vDeptCode,d.vDeptDesc,c.vCompName from sptbl_depts as d,sptbl_companies as c ";
                                                    $sql .=" where d.nCompId=c.nCompId and d.nDeptId in($assigned_ids)";
                                                    $rs = executeSelect($sql, $conn);
                                                    while ($row = mysql_fetch_array($rs)) {
                                                        $options = "<option value='" . $row['nDeptId'] . "'";

                                                        $options .=">" . htmlentities($row['vDeptDesc']) . "</option>\n";
                                                        echo $options;
                                                    }
                                                    ?>
                                                </select>
                                            </td>


                                        </tr>
                                    </table>
                                </div>
                            </td></tr>
                        <tr><td colspan="3">&nbsp;</td></tr>

                    </table>
                    <table width="100%"  border="0" cellspacing="10" cellpadding="0">
                        <tr>
                            <td><table width="100%"  border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
                                    </tr>
                                </table>
                                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                    <tr >
                                        <td width="1"><img src="./../images/spacerr.gif" width="1" height="1"></td>
                                        <td ><table width="100%"  border="0" cellspacing="0" cellpadding="5" >
                                                <tr>
                                                    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                                            <tr align="center"  class="listingbtnbar">
                                                                <td width="22%">&nbsp;</td>
                                                                <td width="16%" colspan=4 align=center><input name="btSave" type="button" class="comm_btn" value="<?php  echo BUTTON_TEXT_SAVE; ?>" onClick="saveMe(this.form);"></td>

                                                                <td width="20%">
                                                                    <input type=hidden name="tosave">
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
                                        <td width="1"><img src="./../images/spacerr.gif" width="1" height="1"></td>
                                    </tr>
                                </table>
                                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
                                    </tr>
                                </table></td>
                        </tr>
                    </table>
                    <p class="ashbody">&nbsp;</p></td>

            </tr>
        </table>
    </div>

</form>