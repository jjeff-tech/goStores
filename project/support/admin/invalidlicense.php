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

    include("./includes/session.php");
	include("../config/settings.php");
	include("./includes/functions/dbfunctions.php");
	include("./includes/functions/impfunctions.php");
	
	//set_magic_quotes_runtime(0);
        // Check if magic_quotes_runtime is active
      /*  if(get_magic_quotes_runtime())
        {
            // Deactivate
            set_magic_quotes_runtime(false);
        }*/
	if (get_magic_quotes_gpc()) {
		$_POST = array_map('stripslashes_deep', $_POST);
		$_GET = array_map('stripslashes_deep', $_GET);
		$_COOKIE = array_map('stripslashes_deep', $_COOKIE);

	}
	if(!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] =="") ){
			$_SP_language = "en";
	}else{
			$_SP_language = $_SESSION["sess_language"];
	}
	include("./languages/".$_SP_language."/main.php");
    include("languages/".$_SP_language."/index.php");
    $conn = getConnection();
include("../includes/constants.php");
$message ="";
if ($_POST["btnGo"] == "Submit") {
	$txtAdminPass  = trim($_POST['txtAdminPass']);
	$txtLicenseKey  = trim($_POST['txtLicenseKey']);
	if ($txtLicenseKey != "" && $txtAdminPass != "") {
		$sqlSelect	= "SELECT * FROM sptbl_staffs WHERE vPassword='".md5($txtAdminPass)."' AND vLogin ='admin'";
		$res =	mysql_query($sqlSelect);
		if(mysql_num_rows($res) > 0){	
			if (strlen($txtLicenseKey) == '30') {
				$sql = "UPDATE sptbl_lookup SET vLookUpValue='" . addslashes($txtLicenseKey) . "' where vLookUpName = 'vLicenceKey'";
				mysql_query($sql);
				header("Location:index.php");
				exit;
			}else
				$message = "Invalid key. Please enter a valid key";
		}else
			$message = "Invalid admin password. Please enter a valid admin password";
	}else
		$message = "Please enter new key";
}
?>
<html>
<head>
<style type="text/css">
body{
	background:url('<?php echo SITE_URL;?>/custom/license_bg.jpg') repeat-x;
}
.license_hd{
	font:14px Arial, Helvetica, sans-serif;
	line-height:22px;
	color:#333333;
}
.license_p{
	font:13px Arial, Helvetica, sans-serif;
	line-height:20px;
	color:#000;
}
.tab_style{
	border:1px solid #fefefe;
}
.submit{
	width:75px;
	background:#ed6514;
	border:0;
	outline:none;
	height:25px;
	cursor:pointer;
	color:#FFFFFF;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;

}
</style>
<title><?php echo HEADING_LOGIN ?></title>
<?php include("./includes/headsettings.php"); ?>
<script language="javascript" type="text/javascript">
<!--
function checkLoginForm(){
        var frm = window.document.frmLogin;
        var errors="";
        if(frm.txtUserID.value == ""){
                errors += "<?php echo MESSAGE_USER_ID_REQUIRED; ?>"+ "\n";
        }
        if(frm.txtPassword.value == ""){
                errors += "<?php echo MESSAGE_PASSWORD_REQUIRED; ?>" + "\n";
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
-->
</script>
<script language="javascript1.1" type="text/javascript">
// Finction to show license key entry textbox...
function enterNewKey(){
	document.getElementById('adminpass').style.display = '';
	document.getElementById('licensekey').style.display = '';
	document.getElementById('licensekeysubmit').style.display = '';
}
</script>
<script language="javascript1.1" type="text/javascript">
function emptyCheck()
{
	if(document.frmLicense.txtAdminPass.value == ""){
		alert('Please enter administrator password');
		document.frmLicense.txtAdminPass.focus();
		return false;	
	}else if(document.frmLicense.txtLicenseKey.value == ""){
		alert('Please enter valid license key');
		document.frmLicense.txtLicenseKey.focus();
		return false;	
	}	
}
</script>

</head>
<body>
<div style=" margin:0px auto; ">

<form name=frmLicense method=post action="<?php echo   $_SERVER["PHP_SELF"];?>" onSubmit="return emptyCheck();">
<?php include "includes/indextop.php" ?>
  <tr>
    <td align="left">
                <table width="100%"  border="0" cellspacing="0" cellpadding="0" align="center">
                <tr class="bodycolor">
                  <td width="24%" valign="top"><?php include "includes/invalidlicense.php"; ?></td>
                </tr>
              </table>
<?php include "includes/indexbottom.php" ?>
</form>
</div>
</body>
<script language="JavaScript">
<!--
if (document.frmLogin) {
document.frmLogin.txtUserID.focus();
}
// -->
</script>
</html>