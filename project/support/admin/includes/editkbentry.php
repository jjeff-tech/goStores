<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: johnson<johnson@armia.com>    		                      |
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

$error = false;
$message = false;

if ($_POST["postback"] == "" && $var_id != "") {//if the page is not posted
    $sqla =  " SELECT k.nKBID, k.nStaffId, k.vKBTitle, k.tKBDesc, k.dDate,k.vStatus,k.vMetaTage_keyword,k.vMetaTage_desc, c.nCompId, d.nDeptId, ca.vCatDesc, ca.nCatId, ca.nParentId ";
    $sqla .= " FROM sptbl_kb k INNER JOIN sptbl_categories ca ON k.nCatId = ca.nCatId  ";
    $sqla .= " INNER JOIN sptbl_depts d ON  ca.nDeptId = d.nDeptId ";
    $sqla .= " INNER JOIN sptbl_companies c ON  d.nCompId = c.nCompId   ";
    $sqla .= " WHERE k.nKBID = '".mysql_real_escape_string($var_id)."'";
    //exit;
    $var_result = executeSelect($sqla,$conn);
    if (mysql_num_rows($var_result) > 0) {
        $var_row = mysql_fetch_array($var_result);
        $var_companyid= $var_row["nCompId"];
        $var_catid = $var_row["nCatId"];
        $var_parentcatid = $var_row["nParentId"];
        $var_departmentid = $var_row["nDeptId"];

        $var_kbid = $var_row["nKBID"];
        $var_kbtitle = $var_row["vKBTitle"];
        $var_kbdesc = $var_row["tKBDesc"];
        $var_status = $var_row["vStatus"];
        $var_kbmetatagkeyword = $var_row["vMetaTage_keyword"];
        $var_kbmetatagdescription = $var_row["vMetaTage_desc"];
        if($var_status == "A") {
            $var_approved = true;
        }else {
            $var_approved = false;
        }
    }
    else {
        $var_id = "";
        $error = true;
    }
}elseif ($_POST["postback"] == "A") {//posted form for adding category
    $var_companyid= trim($_POST["cmbCompany"]);
    $var_departmentid = trim($_POST["cmbDepartment"]);
    $var_catid = trim($_POST["cmbCategory"]);
    $var_kbtitle = trim($_POST["txtKBTitle"]);
    $var_kbdesc = trim($_POST["txtKBDescription"]);
    $var_kbmetatagkeyword = trim($_POST["txtMetaTagkeyword"]);
    $var_kbmetatagdescription = trim($_POST["txtMetaTagdescription"]);
    if($_POST["chkApproved"]) {
        $var_approved = true;
        $var_status = "A";
    }else {
        $var_approved = false;
        $var_status = "I";
    }
    if(!isNotNull($var_companyid)) {
        $error = true;
        $errormessage .= MESSAGE_COMPANY_REQUIRED ."<br>";
    }
    if(!isNotNull($var_departmentid)) {
        $error = true;
        $errormessage .= MESSAGE_DEPARTMENT_REQUIRED."<br>";
    }
    if(!isNotNull($var_catid)) {
        $error = true;
        $errormessage .= MESSAGE_CATEGORY_REQUIRED."<br>";
    }
    if(!isNotNull($var_kbtitle)) {
        $error = true;
        $errormessage .= MESSAGE_TITLE_REQUIRED."<br>";
    }
    if(!isNotNull($var_kbdesc)) {
        $error = true;
        $errormessage .= MESSAGE_DESCRIPTION_REQUIRED."<br>";
    }
    if(isDuplicateKBEntry($var_kbtitle,$var_catid)) {
        $error = true;
        $errormessage .= MESSAGE_DUPLICATE_ENTRY."<br>";
    }
    if (!$error) {
        //Insert into the category table
        $sql  =  "Insert into sptbl_kb(nKBID,nCatId, nStaffId, vKBTitle," ;
        $sql .= " tKBDesc, dDate, vStatus, vMetaTage_keyword,vMetaTage_desc ";
        $sql .= ") Values('','" . mysql_real_escape_string($var_catid) . "','" . mysql_real_escape_string($_SESSION["sess_staffid"]) . "','" . mysql_real_escape_string($var_kbtitle) . "',";
        $sql .= "'". mysql_real_escape_string($var_kbdesc) ."',now(), '".$var_status."', '". mysql_real_escape_string($var_kbmetatagkeyword)."' , '". mysql_real_escape_string($var_kbmetatagdescription)."')";
        executeQuery($sql,$conn);
        $var_insert_id = mysql_insert_id($conn);
        updateCount($var_catid,"+");

        //Insert the actionlog
        if(logActivity()) {
            $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_ADDITION . "','Knowledgebase','" . mysql_real_escape_string($var_insert_id) . "',now())";
            executeQuery($sql,$conn);
        }

        $emailstonotify = getEmailsToNotifyKB($var_departmentid);

        if($emailstonotify!="") {
            if($var_approved) {
                $sql = " Select * from sptbl_lookup where vLookUpName IN('Post2PostGap','MailFromName','MailFromMail',";
                $sql .="'MailReplyName','MailReplyMail','Emailfooter','Emailheader','AutoLock')";
                $result = executeSelect($sql,$conn);
                if(mysql_num_rows($result) > 0) {
                    while($row = mysql_fetch_array($result)) {
                        switch($row["vLookUpName"]) {
                            case "MailFromName":
                                $var_fromName = $row["vLookUpValue"];
                                break;
                            case "MailFromMail":
                                $var_fromMail = $row["vLookUpValue"];
                                break;
                            case "MailReplyName":
                                $var_replyName = $row["vLookUpValue"];
                                break;
                            case "MailReplyMail":
                                $var_replyMail = $row["vLookUpValue"];
                                break;
                            case "Emailfooter":
                                $var_emailfooter = $row["vLookUpValue"];
                                break;
                            case "Emailheader":
                                $var_emailheader = $row["vLookUpValue"];
                                break;
                            case "AutoLock":
                                $var_autoclock = $row["vLookUpValue"];
                                break;
                        }
                    }
                }
                mysql_free_result($result);

                $var_mail_body  = $var_emailheader."<br>".TEXT_MAIL_START.",&nbsp;<br>".
                        $var_mail_body .= TEXT_A_NEW_KB_ENTRY_POSTED ."<br>";
                $var_mail_body .= TEXT_DETAILS_FOLLOW ."<br>";
                $var_mail_body .= "<br>";
                $var_mail_body .= TEXT_DEPARTMENT .": ".htmlentities(getDepartmentName($var_departmentid))."<br>";
                $var_mail_body .= TEXT_ENTRY_TITLE .": ".htmlentities($var_kbtitle)."<br>";
                $var_mail_body .= "<br>";
                $var_mail_body .= "<br>".TEXT_THANKS."<br>".htmlentities($var_helpdesktitle) ."<br>".$var_emailfooter;

                $var_subject = TEXT_A_NEW_KB_ENTRY_POSTED;
                $var_body = $var_mail_body;

                $headers  ="From: $var_fromName <$var_fromMail>\n";
                $headers .="Reply-To: $var_replyName <$var_replyMail>\n";
                $headers .="MIME-Version: 1.0\n";
                $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

                // it is for smtp mail sending
                if($_SESSION["sess_smtpsettings"] == 1) {
                    $var_smtpserver = $_SESSION["sess_smtpserver"];
                    $var_port = $_SESSION["sess_smtpport"];

                    SMTPMail($var_fromMail,$emailstonotify,$var_smtpserver,$var_port,$var_subject,$var_body);
                }
                else
                    $mailstatus=@mail($emailstonotify,$var_subject,$var_body,$headers);
            }

        }


        $message = true;
        $infomessage = MESSAGE_RECORD_ADDED_SUCCESSFULLY;
        $flag_msg     = 'class="msg_success"';
        $var_kbtitle = "";
        $var_kbdesc = "";
        $var_kbmetatagkeyword = "";
        $var_kbmetatagdescription = "";

    }
}elseif ($_POST["postback"] == "D") {

    if(!$error) {
        $catid = getCategoryId($var_id);
        $sql = "DELETE FROM  sptbl_kb  where nKBID='" . mysql_real_escape_string($var_id) . "'";
        executeQuery($sql,$conn);

        updateCount($catid,"-");
        //Insert the actionlog
        if(logActivity()) {
            $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Knowledgebase','" . mysql_real_escape_string($var_id) . "',now())";
            executeQuery($sql,$conn);
        }
        $var_catname="";
        $var_companyid= trim($_POST["cmbCompany"]);
        $var_departmentid = trim($_POST["cmbDepartment"]);
        $var_catid = $_POST["cmbCategory"];
        $message = true;
        $infomessage = MESSAGE_KBENTRY_DELETED ."<br>";
        $flag_msg     = 'class="msg_success"';
        $var_id = "";
    }
}elseif ($_POST["postback"] == "U") {
    $sql = "Select nKBID from sptbl_kb where nKBID='" . mysql_real_escape_string($var_id) . "'";
    if(mysql_num_rows(executeSelect($sql,$conn)) > 0) {
        $var_companyid= trim($_POST["cmbCompany"]);
        $var_departmentid = trim($_POST["cmbDepartment"]);
        $var_catid = trim($_POST["cmbCategory"]);
        $var_kbtitle = trim($_POST["txtKBTitle"]);
        $var_kbdesc = trim($_POST["txtKBDescription"]);
        $var_kbmetatagkeyword = trim($_POST["txtMetaTagkeyword"]);
        $var_kbmetatagdescription = trim($_POST["txtMetaTagdescription"]);
        if($_POST["chkApproved"]) {
            $var_approved = true;
            $var_status = "A";
        }else {
            $var_approved = false;
            $var_status = "I";
        }
        if(!isNotNull($var_companyid)) {
            $error = true;
            $errormessage .= MESSAGE_COMPANY_REQUIRED ."<br>";
        }
        if(!isNotNull($var_departmentid)) {
            $error = true;
            $errormessage .= MESSAGE_DEPARTMENT_REQUIRED."<br>";
        }
        if(!isNotNull($var_catid)) {
            $error = true;
            $errormessage .= MESSAGE_CATEGORY_REQUIRED."<br>";
        }
        if(!isNotNull($var_kbtitle)) {
            $error = true;
            $errormessage .= MESSAGE_TITLE_REQUIRED."<br>";
        }
        if(!isNotNull($var_kbdesc)) {
            $error = true;
            $errormessage .= MESSAGE_DESCRIPTION_REQUIRED."<br>";
        }
        if(!$error) {
            $sql  =  "UPDATE sptbl_kb SET nCatId= '" . mysql_real_escape_string($var_catid) . "', nStaffId = '" . mysql_real_escape_string($_SESSION["sess_staffid"]) . "', vKBTitle='" . mysql_real_escape_string($var_kbtitle) . "', " ;
            $sql .= " tKBDesc = '". mysql_real_escape_string($var_kbdesc) ."', vStatus = '$var_status', vMetaTage_keyword = '". mysql_real_escape_string($var_kbmetatagkeyword) ."' , vMetaTage_desc = '". mysql_real_escape_string($var_kbmetatagdescription) ."'";
            $sql .= "WHERE nKBID = '".mysql_real_escape_string($var_id)."'";

            executeQuery($sql,$conn);

            //updateRoute($var_catid);
            //Insert the actionlog
            if(logActivity()) {
                $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Knowledgebase','" . mysql_real_escape_string($var_id) . "',now())";
                executeQuery($sql,$conn);
            }
            $message = true;
            $infomessage = MESSAGE_RECORD_UPDATED;
            $flag_msg     = 'class="msg_success"';
        }
    }
    else {
        $var_id = "";
        $error = true;
        $errormessage .= MESSAGE_INVALID_KB ."<br>";
    }
}elseif ($_POST["postback"] == "CC") {//change company
    $var_companyid = trim($_POST["cmbCompany"]);
    $sql = "SELECT d.nDeptId FROM sptbl_depts d ";
    $sql .=" WHERE d.nCompId = '".mysql_real_escape_string($var_companyid)."' ";
    $var_result = executeSelect($sql,$conn);
    if (mysql_num_rows($var_result) > 0) {
        $var_row = mysql_fetch_array($var_result);
        $var_departmentid = $var_row["nDeptId"];
    }
    $var_kbdesc = trim($_POST["txtKBDescription"]);
    $var_kbtitle = trim($_POST["txtKBTitle"]);
    $var_kbmetatagkeyword = trim($_POST["txtMetaTagkeyword"]);
    $var_kbmetatagdescription = trim($_POST["txtMetaTagdescription"]);
}elseif ($_POST["postback"] == "CD") {//change department
    $var_companyid= trim($_POST["cmbCompany"]);
    $var_departmentid = trim($_POST["cmbDepartment"]);
    $var_catname = trim($_POST["txtCategoryName"]);
    $var_kbdesc = trim($_POST["txtKBDescription"]);
    $var_kbtitle = trim($_POST["txtKBTitle"]);
    $var_kbmetatagkeyword = trim($_POST["txtMetaTagkeyword"]);
    $var_kbmetatagdescription = trim($_POST["txtMetaTagdescription"]);
}

if($error) {
    $errormessage = MESSAGE_ERRORS_FOUND. "<br>".$errormessage;
    $flag_msg     = 'class="msg_error"';
}

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

function updateRoute($catid) {
    global $conn;
    $sql = "SELECT c.vRoute, c.nParentId, parentcat.vRoute as parentroute FROM sptbl_categories c LEFT OUTER JOIN  sptbl_categories parentcat ON c.nParentId = parentcat.nCatId ";
    $sql .=" WHERE c.nCatId = '".mysql_real_escape_string($catid)."' ";
    $result = executeSelect($sql,$conn);
    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);
        $parentroute = $row["parentroute"];
        if($parentroute =="" ) {
            $route = ($row["vRoute"] == "")?"":$row["vRoute"].",";
        }else {
            $route = $parentroute.",";
        }

    }
    $newroute = $route.$catid;
    $sql = "UPDATE sptbl_categories SET vRoute = '".$newroute."' WHERE  nCatId = '".$catid."' ";
    executeQuery($sql,$conn);
}


function validateFields() {
    if (trim($_POST["cmbCompany"]) <= "" || trim($_POST["cmbDepartment"]<=0 ) || trim($_POST["cmbCategory"]<=0 ) ) {
        return false;
    }else {
        return true;
    }
}

function hasChildren($catid) {
    global $conn;
    $sqlparentcheck="select nCatId from sptbl_categories where nParentId='" . mysql_real_escape_string($catid) . "'";
    $rs = executeSelect($sqlparentcheck,$conn);
    if(mysql_num_rows($rs)>0) {
        return true;
    }else {
        return false;
    }

}

function hasEntries($catid) {
    global $conn;
    $sql="select nKBID from sptbl_kb where nCatId='" . mysql_real_escape_string($catid) . "'";
    $rs = executeSelect($sql,$conn);
    if(mysql_num_rows($rs)>0) {
        return true;
    }else {
        return false;
    }
}

function validateCategoryUpdation() {
    if (trim($_POST["txtCategoryName"]) == "" || trim($_POST["cmbCompany"]) <= "" || trim($_POST["cmbDepartment"]<=0 ) || trim($_POST["cmbDepartment"]<=0 ) ) {
        return false;
    }
    else {
        return true;
    }
}

function isDuplicateCategory($parentcatid, $catname) {
    global $conn;
    //check duplicate category name
    $sql="SELECT vCatDesc FROM sptbl_categories WHERE nParentId = '$parentcatid' and vCatDesc = '".mysql_real_escape_string($catname)."' ";
    $rs = executeSelect($sql,$conn);

    if(mysql_num_rows($rs)>0) {//there are child categories with same name, so return true
        return true;
    }else {//no child categories for the parent category with same name
        return false;
    }
}

function getdepartmentlink($compid,$deptparentid) {
    global $conn;
    $link=array();
    $cnt=0;
    if($compid<=0) {
        $link[0]="";
    }else if($deptparentid==0) {
        $sql="SELECT vCompName FROM sptbl_companies WHERE nCompId=$compid ";
        $rs = executeSelect($sql,$conn);
        $rowcompanyname=mysql_fetch_array($rs);
        $link[0]=$rowcompanyname['vCompName'];

    }else {
        /* to change later */

        while(1) {
            $sql="SELECT nDeptId,vDeptDesc,nDeptParent FROM sptbl_depts WHERE nDeptId=$deptparentid";
            $rs = executeSelect($sql,$conn);
            $rw=mysql_fetch_array($rs);
            $link[$cnt]=$rw['vDeptDesc'];
            if($rw['nDeptParent']=="0") {
                $cnt++;
                $sql="SELECT vCompName FROM sptbl_companies WHERE nCompId=$compid ";
                $rs = executeSelect($sql,$conn);
                $rowcompanyname=mysql_fetch_array($rs);
                $link[$cnt]=$rowcompanyname['vCompName'];
                break;
            }

            $deptparentid=$rw['nDeptParent'];
            $cnt++;
        }
    }
    return $link;
}	
function make_selectlist($current_dept_id, $count,$cmpid) {
    static $option_results;
    if (!isset($current_dept_id)) {
        $current_dept_id =0;
    }
    $count = $count+1;

    $sql = "SELECT nDeptId as id, vDeptDesc as name from sptbl_depts where nDeptParent = '$current_dept_id' and nCompId=$cmpid order by name asc";
    $get_options = mysql_query($sql);
    $num_options = mysql_num_rows($get_options);
    if ($num_options > 0) {
        while (list($dept_id, $dept_name) = mysql_fetch_row($get_options)) {
            if ($current_dept_id!=0) {
                $indent_flag = "&nbsp;&nbsp;";
                for ($x=2; $x<=$count; $x++) {
                    $indent_flag .= "--&gt;&nbsp;";
                }
            }
            $dept_name = $indent_flag.htmlentities($dept_name);
            $option_results[$dept_id] = $dept_name;
            make_selectlist($dept_id, $count,$cmpid );
        }
    }
    return $option_results;
}


//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

?>
<script src= "<?php echo SITE_URL ?>ckeditor/ckeditor.js"></script>
<script src="<?php echo SITE_URL ?>scripts/editor.js"></script>
<script type="text/javascript" >
$(document).ready(function(){
    loadCKEditor('txtKBDescription', 600);
})
</script>
<form name="frmKBEntry" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>">
    <div class="content_section">
        <div class="content_section_title">
            <h3><?php echo TEXT_EDIT_KB_ENTRY ?></h3>
        </div>
        <table width="100%"  border="0">
            <tr>
                <td width="76%" valign="top">
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">

                        <tr>
                            <td align="center" colspan=3 >&nbsp;</td>

                        </tr>
                        <tr>
                            <td align="left" colspan=3 >
                                <!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->
                                <!--
                                <?php
                                if($error) {?>

                          <table width="100%"  border="0" cellspacing="10" cellpadding="0" align="center">
                      <tr>
                        <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                            <tr>
                              <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
                            </tr>
                          </table>
                            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                                <td class="pagecolor"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                                    <tr>
                                      <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td align="left" class="errormessage"><p><?php echo $errormessage;?></p></td>
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
                                    <?php }
                                if($message) { ?>
                          <table width="100%"  border="0" cellspacing="10" cellpadding="0" align="center">
                      <tr>
                        <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                            <tr>
                              <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
                            </tr>
                          </table>
                            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                                <td class="pagecolor"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                                    <tr>
                                      <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td align="center" class="message"><p><?php echo $infomessage;?></p></td>
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
                                    <?php }?>
			-->
                                <!-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ -->
                            </td>

                        </tr>
                        <tr>
                            <td align="center" colspan=3 <?php echo $flag_msg; ?>>
                                <?php echo $errormessage;
                                echo $infomessage; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td align="left" colspan=2 class="toplinks">
                                <?php echo TEXT_FIELDS_MANDATORY ?></td>

                        </tr>


                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td width="2%" align="left">&nbsp;</td>
                            <td width="37%" align="left" class="toplinks"><?php echo TEXT_COMPANY?> <font style="color:#FF0000; font-size:9px">*</font> </td>
                            <td width="61%" align="left">
                                <?php
                                $sql = "SELECT nCompId,vCompName   FROM `sptbl_companies` where vDelStatus=0 order by vCompName";
                                $rs = executeSelect($sql,$conn);
                                $cnt = 1;
                                ?>
                                <select name="cmbCompany" size="1" class="comm_input input_width1a" id="cmbCompany" onchange="changecompany();">
                                    <?php
                                    $options ="<option value='0'";
                                    $options .=">Select</option>\n";
                                    echo $options;
                                    while($row = mysql_fetch_array($rs)) {
                                        $options ="<option value='".$row['nCompId']."'";
                                        if ($var_companyid == $row['nCompId']) {

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
                            <td width="37%" align="left" class="toplinks"><?php echo TEXT_DEPARTMENT?> <font style="color:#FF0000; font-size:9px">*</font> </td>
                            <td width="61%" align="left" class="listingmaintext">
                                <select name="cmbDepartment" size="1" class="comm_input input_width1a" id="cmbDepartment" onchange="changedepartment();">
                                    <?php
                                    $options="";
                                    $get_options =    make_selectlist(0,0,$var_companyid);
                                    $options ="<option value='0'";
                                    $options .=">Select</option>\n";
                                    if (count($get_options) > 0) {
                                        //$departments = $_POST['dept_id'];


                                        foreach ($get_options  as $key => $value) {
                                            $options .="<option value=\"$key\"";
                                            if ($var_departmentid == "$key") {
                                                $options .=" selected=\"selected\"";
                                            }
                                            $options .=">" . $value . "</option>\n";
                                        }
                                    }
                                    echo $options;
                                    ?>
                                </select>


                            </td>
                        </tr>

                        <!--
                         <tr><td colspan="3">&nbsp;<?php echo 'make_selectlist(0,0,'.$var_companyid.')';?></td></tr>
                        <tr><td colspan="3">Company: <?php echo   $var_companyid?></td></tr>
   <tr><td colspan="3">Dept: <?php echo   $var_departmentid?></td></tr>
					   -->
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td width="2%" align="left">&nbsp;</td>
                            <td width="37%" align="left" class="toplinks"><?php echo TEXT_CATEGORY?><span class="required">*</span> </td>
                            <td width="61%" align="left" class="listingmaintext">
                                <select name="cmbCategory" size="1" class="comm_input input_width1a" id="cmbCategory" >
                                    <?php
                                    $categories =    makeCategoryList(0,0,$var_departmentid);
//print_r($categories);
                                    $catoptions ="<option value='0'";
                                    $catoptions .=">Select</option>\n";
                                    if (count($categories) > 0) {
                                        foreach ($categories  as $key => $value) {
                                            $catoptions .="<option value=\"$key\"";
                                            if ($var_catid == "$key") {
                                                $catoptions .=" selected=\"selected\"";
                                            }
                                            $catoptions .=">" . $value . "</option>\n";
                                        }
                                    }
                                    echo $catoptions;
                                    ?>
                                </select>


                            </td>
                        </tr>
                        <tr><td colspan="3">&nbsp;<?php //echo 'makeCategoryList(0,0,'.$var_departmentid.')';?></td></tr>
                        <!--Meta tage add-->
                        <tr>
                            <td align="left">&nbsp;</td>
                            <td align="left" class="toplinks"><?php echo TEXT_METAKEYWORD;?> <!--<font style="color:#FF0000; font-size:9px">*</font>--></td>
                            <td width="61%" align="left">
                                <textarea name="txtMetaTagkeyword" class="comm_input input_width1"  id="txtMetaTagkeyword" cols="25" rows="5"><?php echo stripslashes($var_kbmetatagkeyword); ?></textarea>
                            </td>
                        </tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td align="left">&nbsp;</td>
                            <td align="left" class="toplinks"><?php echo TEXT_METADESCRIPTION;?> <!--<font style="color:#FF0000; font-size:9px">*</font>--></td>
                            <td width="61%" align="left">
                                <textarea name="txtMetaTagdescription" class="comm_input input_width1"  id="txtMetaTagdescription" cols="25" rows="5"><?php echo stripslashes($var_kbmetatagdescription); ?></textarea>
                            </td>
                        </tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <!--Meta tage end-->
                        <tr>
                            <td align="left">&nbsp;</td>
                            <td align="left" class="toplinks"><?php echo TEXT_ENTRY_TITLE ?> <font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="61%" align="left">
                                <input name="txtKBTitle" type="text" class="comm_input input_width1" id="txtKBTitle" size="30" maxlength="100" value="<?php echo htmlentities(stripslashes($var_kbtitle)); ?>">
                            </td>
                        </tr>
                        <tr><td colspan="3">&nbsp;<?php //echo 'makeCategoryList(0,0,'.$var_departmentid.')';?></td></tr>
                        <tr>
                            <td align="left">&nbsp;</td>
                            <td align="left" class="toplinks" valign="top"><?php echo TEXT_ENTRY_DESC ?> <span class="required">*</span></td>
                            <td width="61%" align="left">
                                 <textarea name="txtKBDescription" cols="62" rows="12" id="txtKBDescription" class="comm_input input_width1a" style="width:380px;"><?php echo htmlentities($var_kbdesc); ?></textarea>
                                <?php
/*
                                $sBasePath                      = "../FCKeditor/";
                                $oFCKeditor 					= new FCKeditor('txtKBDescription') ;
                                $oFCKeditor->BasePath			= $sBasePath ;
                                $oFCKeditor->Value				= stripslashes($var_kbdesc);
                                $oFCKeditor->Width  = '530' ;
                                $oFCKeditor->Height = '350' ;
                                $oFCKeditor->ToolbarSet = 'Basic' ;
                                $oFCKeditor->Create() ;
*/
                                ?>
                            </td>
                        </tr>

                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td align="left">&nbsp;</td>
                            <td align="left" class="toplinks"><?php echo TEXT_APPROVED ?></td>
                            <td width="61%" align="left">

                                <input name="chkApproved" type="checkbox" class="checkbox" id="chkApproved"  <?php if($var_approved) echo "CHECKED"; ?>>&nbsp;
                            </td>
                        </tr>

                    </table>
                    <table width="100%"  border="0" cellspacing="10" cellpadding="0">
                        <tr>
                            <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                                    <tr>
                                        <td class="btm_brdr"><img src="./../images/spacerr.gif" width="1" height="1"></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
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
                                                                <td width="16%"><input name="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD_NEW; ?>" onClick="javascript:add();"></td>
                                                                <td width="14%"><input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_SAVE_CHANGES; ?>"  onClick="javascript:edit();"></td>
                                                                <td width="16%"><input name="btDelete" type="button" class="comm_btn_greyad" value="<?php echo BUTTON_TEXT_DELETE; ?>" onClick="javascript:deleted();"></td>
                                                                <td width="12%"><input name="btCancel" type="reset" class="comm_btn_greyad" value="<?php echo BUTTON_TEXT_CANCEL; ?>"   onClick="window.location='kbentries.php?stylename=STYLEKNOWLEDGEBASE&styleminus=minus10&styleplus=plus10&'"></td>
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
                </td>

            </tr>
        </table>
    </div>
    <script>
        var setValue = "<?php echo trim($var_country); ?>";

<?php
if ($var_id == "") {
    echo("document.frmKBEntry.btAdd.disabled=false;");
    echo("document.frmKBEntry.btUpdate.disabled=true;");
    echo("document.frmKBEntry.btDelete.disabled=true;");
}
else {
    echo("document.frmKBEntry.btAdd.disabled=true;");
    echo("document.frmKBEntry.btUpdate.disabled=false;");
    echo("document.frmKBEntry.btDelete.disabled=false;");
}
?>
    </script>
</form>