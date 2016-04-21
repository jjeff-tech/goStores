<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | File name : index.php                                                |
// | PHP version >= 5.2                                                   |
// +----------------------------------------------------------------------+
// | Author: BINU CHANDRAN.E<binu.chandran@armiasystems.com>              |
// +----------------------------------------------------------------------+
// | Modified: ARUN SADASIVAN (01/07/2012)								  |
// |----------------------------------------------------------------------+
// | Copyrights Armia Systems � 2010                                      |
// | All rights reserved                                                  |
// +----------------------------------------------------------------------+
// | This script may not be distributed, sold, given away for free to     |
// | third party, or used as a part of any internet services such as      |
// | webdesign etc.                                                       |
// +----------------------------------------------------------------------+
error_reporting(0);
ob_start();
include_once('../config/config.php');

/*
 * get the path dynamically
 * NOTE:- need to add www via htaccess if required
 */
$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s . "://";
define('ROOT_URL', $protocol);

$current_path = getcwd();
$current_path=rtrim($current_path,"/");
$current_path=$current_path."/";
$current_path=str_replace("\\","/",$current_path);

$DocumentRoot=$_SERVER["DOCUMENT_ROOT"];
$DocumentRoot=rtrim($DocumentRoot,"/");
$DocumentRoot=$DocumentRoot."/";
$DocumentRoot=str_replace("\\","/",$DocumentRoot);

$newRoot = explode($DocumentRoot,$current_path);

$path=str_replace($DocumentRoot,"",$current_path);
$path=str_replace('project/install/', '', end($newRoot));

$root_url = ROOT_URL . $_SERVER['SERVER_NAME'] ."/".$path;
//add trailing slashes
if($root_url[strlen($root_url) - 1] <> '/'){
    $root_url .= '/';
}

define('BASE_URL', $root_url);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title>goStores Installer</title>        
    </head>
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script type="text/javascript" src="../js/install.js"></script>
    <link href="css/install.css" rel="stylesheet" type="text/css" />
    <body>
        <div class="header_row">
            <div class="header_container wrapper">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="23%" align="center" ><img src="<?php echo BASE_URL; ?>project/install/css/sitelogo.jpg" alt="Logo"></td>
                        <td width="77%"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="23%" align="center">&nbsp;</td>
                                     <td width="77%" align="right">
					<div align="center" id="items_top_area">
                                    &nbsp;&nbsp;
                                    <a title="OnlineInstallationManual" href="#" onClick="window.open('<?php echo BASE_URL; ?>project/Docs/Installation_Manual.pdf','OnlineInstallationManual','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd');"><strong>Installation manual</strong></a>
                                    | <a title="Readme" href="#" onClick="window.open('<?php echo BASE_URL; ?>project/Docs/Readme.txt','Readme','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd');"><strong>Readme</strong></a> | 
                                    <a title="If you have any difficulty, submit a ticket to the support department" href="#" onClick="window.open('http://www.iscripts.com/support/postticketbeforeregister.php','','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd,resizable=yes');">														
                                        <strong>Get Support</strong></a>
                                    </td>
                                </div>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table></div>
        </div>

        <table width="80%" border="0" align="center">
            <tr>
                <td>
                    <table width=85% border=0 cellpadding="2" cellspacing="2" class=maintext align="center">
                        <br>
                        <tr>
                            <td align="center" class="maintext" >
                                <font color="#F4700E" size="+1">Congratulations! The Installation Process Was Completed Successfully!</font>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" class="maintext" height="20" >&nbsp;</td>
                        </tr>
                        <br>
                        <br>
                        <br>
                        <tr>
                            <td align="center"><span class="required">*</span><b>
                                    All Payment Gateways are disabled by default,Please enable at least one payment gateway !!</b>
                            </td>
                        </tr>
                       					
                        <tr>
                            <td align="center">
                                <br>
                                <fieldset>
                                    <legend class="block_class">Site Login Details</legend>
                                    <table cellpadding="0" cellspacing="0" width="95%" class="maintext" align="center">
                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                        </tr>
                                        
                                        <tr>
                                            <td width="26%"><b><font size="-1">Admin URL&nbsp;:</font></b></td>
                                            <td width="74%"><a style="cursor:pointer" href="<?php echo BASE_URL . "cms" ?>"><img src="css/admin_login_install.jpg" border="0" height="25" align="absmiddle">&nbsp; &nbsp; <?php echo BASE_URL . "cms"; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td  valign="top"><b><font size="-1">Admin Credentials&nbsp;:</font></b></td>
                                            <td  valign="top">
                                                <div class="adm_cred">
                                                    <font size="-1">Username&nbsp;:&nbsp;admin</font><br/><br/>
                                                    <font size="-1">Password&nbsp;:&nbsp;admin</font>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td ><b><font size="-1">Home URL&nbsp;:</font></b></td>
                                            <td ><a style="cursor:pointer" href="<?php echo BASE_URL; ?>"><img src="css/home_page.jpg" border="0" height="25" align="absmiddle">&nbsp; &nbsp;<?php echo BASE_URL; ?></a></td>
                                        </tr>
                                    </table>
                                </fieldset>	
                            </td>
                        </tr>
                        <tr>
                            <td align="center" class="maintext" height="20" >&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="10">
                        <tr>
                            <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="3">
                                                <tr>
                                                    <td height="43" class="bigfont1" align="center"><div align="center" class="copyright"><!-- footr links comes here --></div></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table></td>
                        </tr>
                    </table>      
                </td>
            </tr>
        </table>
    </body>
</html>
                            