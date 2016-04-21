<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: johnson<johnson@armia.com>                          |
// |                                    |
// +----------------------------------------------------------------------+

require_once("includes/applicationheader.php");
include("includes/functions/miscfunctions.php");
include("languages/" . $_SP_language . "/index.php");
$conn = getConnection();

require_once("../includes/decode.php");

$sql = "Select vLookUpName,vLookUpValue from sptbl_lookup WHERE vLookUpName IN('LangChoice',
                                'DefaultLang','HelpdeskTitle','Logourl','logactivity','MaxPostsPerPage','OldestMessageFirst','SMTPSettings','SMTPServer','SMTPPort','Theme')";
$rs = executeSelect($sql, $conn);

if (mysql_num_rows($rs) > 0) {
    while ($row = mysql_fetch_array($rs)) {
        switch ($row["vLookUpName"]) {
            case "LangChoice":
                $_SESSION["sess_langchoice"] = $row["vLookUpValue"];
                break;
            case "DefaultLang":
                $_SESSION["sess_defaultlang"] = $row["vLookUpValue"];
                break;
            case "HelpdeskTitle":
                $_SESSION["sess_helpdesktitle"] = $row["vLookUpValue"];
                break;
            case "Logourl":
                $_SESSION["sess_logourl"] = $row["vLookUpValue"];
                break;
            case "logactivity":    //this session variable decides to log activities or not
                $_SESSION["sess_logactivity"] = $row["vLookUpValue"];
                break;
            case "MaxPostsPerPage":
                $_SESSION["sess_maxpostperpage"] = $row["vLookUpValue"];
                break;
            case "OldestMessageFirst":
                $_SESSION["sess_messageorder"] = $row["vLookUpValue"];
                break;
            case "SMTPSettings":
                $_SESSION["sess_smtpsettings"] = $row["vLookUpValue"];
                break;
            case "SMTPServer":
                $_SESSION["sess_smtpserver"] = $row["vLookUpValue"];
                break;
            case "SMTPPort":
                $_SESSION["sess_smtpport"] = $row["vLookUpValue"];
                break;
            case "Theme":
                $theme_id = $row["vLookUpValue"];
                $sql = "Select vCSSURL from sptbl_css where nCSSId='" . addslashes($theme_id) . "'";
                $result = executeSelect($sql, $conn);
                if (mysql_num_rows($result) > 0) {
                    $row = mysql_fetch_array($result);
                    //unset($_SESSION['sess_cssurl']);
                    $_SESSION["sess_cssurl"] = $row["vCSSURL"];
                }
                break;
        }
    }
}
mysql_free_result($rs);

//------------------------Force login the admin coming from cms section------------------------//
//   NOTE:-
//   HEX for sadmin = C5EDAC1B8C1D58BAD90A246D8F08F53B
//---------------------------------------------------------------------------------------------//
if (isset($_REQUEST['forcedLogin']) && $_REQUEST['forcedLogin'] == 'C5EDAC1B8C1D58BAD90A246D8F08F53B') {
    $sql = "SELECT s.nStaffId , s.vStaffname , s.vMail , s.vLogin , s.vPassword FROM sptbl_staffs s WHERE s.nStaffId = 1";

    $result = executeSelect($sql, $conn);
    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);
        $adminid = $row["nStaffId"];
        $adminname = $row["vLogin"];
        $adminemail = $row["vMail"];
        $adminfullname = $row["vStaffname"];

        $_SESSION["sess_staffname"] = $adminname;
        $_SESSION["sess_staffid"] = $adminid;
        $_SESSION["sess_staffemail"] = $adminemail;
        $_SESSION["sess_stafffullname"] = $adminfullname;
        $_SESSION["sess_isadmin"] = 1;

        $sql = "Select F.vFieldName,F.vFieldDesc from sptbl_stafffields SF inner join sptbl_fields F
                                                                 ON SF.nFieldId = F.nFieldId  WHERE nStaffId='$adminid' ";
        $rs = executeSelect($sql, $conn);

        if (mysql_num_rows($rs) > 0) {
            $cnt = 0;
            while ($row = mysql_fetch_array($rs)) {
                $fld_arr[$cnt][0] = $row["vFieldName"];
                $fld_arr[$cnt][1] = $row["vFieldDesc"];
                $cnt++;
            }
        }
        $_SESSION["sess_fieldlist"] = $fld_arr;
        mysql_free_result($rs);

        $sql = "Select nPriorityValue,vTicketColor,vPriorityDesc from sptbl_priorities ORDER BY nPriorityValue ";
        $rs = executeSelect($sql, $conn);

        if (mysql_num_rows($rs) > 0) {
            $cnt = 0;
            while ($row = mysql_fetch_array($rs)) {
                $fld_prio[$cnt][0] = $row["nPriorityValue"];
                $fld_prio[$cnt][1] = $row["vTicketColor"];
                $fld_prio[$cnt][2] = $row["vPriorityDesc"];
                $cnt++;
            }
        }
        $_SESSION["sess_priority"] = $fld_prio;
        mysql_free_result($rs);

        header("Location: adminmain.php");
        exit;
    }
}

if ($_POST["postback"] == "Login") {
    $error = false;
    $errormessage = "";
    if (isNotNull($_POST["txtUserID"])) {
        $loginname = trim($_POST["txtUserID"]);
    } else {//user name null
        $error = true;
        $errormessage .= MESSAGE_USER_ID_REQUIRED . "<br>";
    }
    if (isNotNull($_POST["txtPassword"])) {
        $password = $_POST["txtPassword"];
    } else {//user name null
        $error = true;
        $errormessage .= MESSAGE_PASSWORD_REQUIRED . "<br>";
    }
    if ($error) {
        $errormessage = MESSAGE_ERRORS_FOUND . "<br>" . $errormessage;
    } else {//no error so validate
        $sql = "SELECT s.nStaffId , s.vStaffname , s.vMail , s.vLogin , s.vPassword FROM sptbl_staffs s  ";
        $sql .= " WHERE vLogin = '" . addslashes($loginname) . "' and  vPassword ='" . addslashes(md5($password)) . "'  and vDelStatus='0' and vType='A' ";
        $result = executeSelect($sql, $conn);
        if (mysql_num_rows($result) > 0) {
            $row = mysql_fetch_array($result);
            $adminid = $row["nStaffId"];
            $adminname = $row["vLogin"];
            $adminemail = $row["vMail"];
            $adminfullname = $row["vStaffname"];

            $_SESSION["sess_staffname"] = $adminname;
            $_SESSION["sess_staffid"] = $adminid;
            $_SESSION["sess_staffemail"] = $adminemail;
            $_SESSION["sess_stafffullname"] = $adminfullname;
            $_SESSION["sess_isadmin"] = 1;

            $sql = "Select F.vFieldName,F.vFieldDesc from sptbl_stafffields SF inner join sptbl_fields F
                                                                 ON SF.nFieldId = F.nFieldId  WHERE nStaffId='$adminid' ";
            $rs = executeSelect($sql, $conn);

            if (mysql_num_rows($rs) > 0) {
                $cnt = 0;
                while ($row = mysql_fetch_array($rs)) {
                    $fld_arr[$cnt][0] = $row["vFieldName"];
                    $fld_arr[$cnt][1] = $row["vFieldDesc"];
                    $cnt++;
                }
            }
            $_SESSION["sess_fieldlist"] = $fld_arr;
            mysql_free_result($rs);

            $sql = "Select nPriorityValue,vTicketColor,vPriorityDesc from sptbl_priorities ORDER BY nPriorityValue ";
            $rs = executeSelect($sql, $conn);

            if (mysql_num_rows($rs) > 0) {
                $cnt = 0;
                while ($row = mysql_fetch_array($rs)) {
                    $fld_prio[$cnt][0] = $row["nPriorityValue"];
                    $fld_prio[$cnt][1] = $row["vTicketColor"];
                    $fld_prio[$cnt][2] = $row["vPriorityDesc"];
                    $cnt++;
                }
            }
            $_SESSION["sess_priority"] = $fld_prio;
            mysql_free_result($rs);

            header("Location: adminmain.php");
            exit;
        } else {
            $error = true;
            $errormessage = MESSAGE_INVALID_LOGIN;
        }
    }
}

/*
$gostores_banner = mysql_query("SELECT cms_set_value FROM " . MYSQL_TABLE_PREFIX . "cms_settings WHERE cms_set_name = 'admin_logo'");
$banner_res = mysql_fetch_array($gostores_banner);
$_SESSION["banner_img"] = BASE_URL . $banner_res['cms_set_value'];
*/

$gostores_banner = mysql_query("SELECT s.value, f.file_path FROM ".MYSQL_TABLE_PREFIX."Settings s LEFT JOIN ".MYSQL_TABLE_PREFIX."files f ON f.file_id = s.value WHERE s.settingfield='siteLogo'");
$banner_res = mysql_fetch_array($gostores_banner);

$logiFile = PARENT_ROOT.'/files/'.$banner_res['file_path'];
$logoImage = PARENT_IMAGE_ROOT.'gostores_logo.jpg';

    if(is_file($logiFile)){
        $logoImage = BASE_URL.'project/files/siteLogo_'.$banner_res['file_path'];
    }

$_SESSION["banner_img"] =$logoImage;


$_SESSION["sess_licensetype"] = $glob_licence_type;
$_SESSION["sess_domainname"] = $glob_domain_name;
//end warning
?>
<?php include("../includes/docheader.php"); ?>
<title><?php echo HEADING_LOGIN ?></title>
<?php include("./includes/headsettings.php"); ?>
<script language="javascript" type="text/javascript">
    <!--
    function checkLoginForm(){
        var frm = window.document.frmLogin;
        var errors="";
        if(frm.txtUserID.value == ""){
            //errors += "<?php echo MESSAGE_USER_ID_REQUIRED; ?>"+ "\n";
            errors += "- Please enter the user ID."+ "\n";
        }
        if(frm.txtPassword.value == ""){
            //errors += "<?php echo MESSAGE_PASSWORD_REQUIRED; ?>" + "\n";
            errors += "- Please enter the password."+ "\n";
        }
        if(errors !=""){
            errors = "<?php echo MESSAGE_ERRORS_FOUND; ?>"+  "\n" +  "\n" + errors;
            alert(errors);
            return false;
        }else{
            frm.postback.value = "Login";
            frm.submit();
        }
    }

    function passPress()
    {
        if(window.event.keyCode=="13"){
            checkLoginForm();
        }
    }

    -->
</script>
</head>


<body>
    <form name="frmLogin" method="post" >

<?php include "includes/indextop.php" ?>
        <?php include "includes/indexcenter.php"; ?>
        <?php include "includes/indexbottom.php" ?>
    </form>
</body>
<script language="JavaScript">
    <!--
    if (document.frmLogin) {
        document.frmLogin.txtUserID.focus();
    }
    // -->
</script>
</html>