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
if (strstr($current_path, 'public_html')) {
    $path_part = explode('/public_html', $current_path);
} elseif (strstr($current_path, 'httpdocs')) {
    $path_part = explode('/httpdocs', $current_path);
} else {
    $path_part[1] = '';
}
$path_part[1] .= '/';
$path_part[1] = str_replace('project/upgrade2.4/', '', $path_part[1]);
$root_url = ROOT_URL . $_SERVER['SERVER_NAME'] . $path_part[1];

//add trailing slashes
if ($root_url[strlen($root_url) - 1] <> '/') {
    $root_url .= '/';
}

define('BASE_URL', $root_url);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title>iScripts GoStores Upgrader</title>
    </head>
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script type="text/javascript" src="../js/install.js"></script>
    <link href="css/install.css" rel="stylesheet" type="text/css" />
    <body>
        <div class="header_row">
            <div class="header_container wrapper">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="23%" align="center" ><h2><span class="">iScripts GoStores Upgrader</span></h2></td>
                        <td width="77%"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="75%" align="center">&nbsp;</td>
                                    <td width="25%"></td>
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
                                <font color="#F4700E" size="+1">Congratulations! The Upgrade Process Was Completed Successfully!</font>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" class="maintext" height="20" >&nbsp;</td>
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