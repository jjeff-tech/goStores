<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: 			 */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2008 Armia Systems, Inc                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasySnaps                     |
// +----------------------------------------------------------------------+
// | Authors: simi<simi@armia.com>             		                      |
// +----------------------------------------------------------------------+
session_start();

function getFilePermission($file) {
    $perm = fileperms($file);
    if ($perm === false) {
        return "0000";
    } else {
        return substr(sprintf('%o', $perm), -4);
    }
}

function stripslashes_deep($value) {
    $value = is_array($value) ?
            array_map('stripslashes_deep', $value) :
            stripslashes($value);
    return $value;
}

function file_writable($file) {
    $permission = substr(sprintf('%o', fileperms($file)), -4);
    if ($permission == '0777' || $permission == '0666') {
        return true;
    } else {
        return false;
    }
}

if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}

$fullurl = $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
if ($_SERVER['HTTPS'] == 'on') {
    $http = "https://";
} else {
    $http = "http://";
}
$pos = strrpos($fullurl, "/");
if ($pos === false) { // note: three equal signs
    // not found...
} else {
    $fullurl = substr($fullurl, 0, $pos);
}

$txtSiteSecureURL = "https://" . $fullurl;
$fullurl = $http . $fullurl;
$txtSiteURL = $fullurl;

//Section - A - include the settings  file here and assign the database connection 
//and open a live connecton here to the database
include_once("../config/settings.php");
$var_host = $glb_dbhost;
$var_user = $glb_dbuser;
$var_password = $glb_dbpass;
$var_database = $glb_dbname;
$flag = false;
$num = 0;
if ($conn = mysql_connect($var_host, $var_user, $var_password)) {
    if (mysql_select_db($var_database, $conn)) {
        $flag = true;
    } else {
        echo("Cannot select the db from the given connection.  Pleasecheck your configuration settings.");
        exit;
    }
} else {
    echo("Cannot select the db from the given connection.  Pleasecheck your configuration settings.");
    exit;
}


//End Section - A
//file n folder permission check  start here
$directories = array("../custom/", "../styles/", "../attachments/", "../backup/", "../downloads/", "../csvfiles/", "../api/useradd.php", "../api/server_class.php", "../config/settings.php", "../admin/purgedtickets/", "../admin/purgedtickets/attachments/", "../staff/images/", "../fckeditorimages/", "../FCKeditor/editor/filemanager/connectors/php/config.php");

/* --------------------Check server PHP configuration--------------------------- */
$sapi_type = php_sapi_name();
$chmodstatus = '000';
if (substr($sapi_type, 0, 3) == 'cgi') {
    $chmodstatus = '755';
    $write = 'WRITABLE';
} else {
    if (substr(@php_uname(s), 0, 7) == "Windows") {
        $chmodstatus = '000';
    } else {
        $chmodstatus = '777';
    }
    $write = 'UNWRITABLE';
}

$perm_msg = '';
$perm_flag = true;
$host_name = parse_url($_SERVER['HTTP_HOST']);

if ($chmodstatus == '777') {
    if (isset($_POST["btnContinue"]) && !isset($_POST["auto_set"])) {
        $txtFTPusername = $_POST['FTPusername'];
        $txtFTPpassword = $_POST['FTPpassword'];

        if (trim($txtFTPusername) == '') {
            $perm_msg .= '* Please enter FTP username <br/>';
        }
        if (trim($txtFTPpassword) == '') {
            $perm_msg .= '* Please enter FTP password <br/>';
        } else {
            $conn_id = @ftp_connect($host_name["path"]);
            $login_result = @ftp_login($conn_id, $txtFTPusername, $txtFTPpassword);
            if ($login_result) {
                $mode = 777;
                $np = '0' . $mode;

                $user_install = str_replace('/upgrade-4.2', '', getcwd());

                //get the path staring from public_html
                $path_parts = explode('/public_html', $user_install);
                $user_install = '/public_html' . $path_parts[1];

                foreach ($directories as $directory) {
                    $edited_path = str_replace('..', '', $directory);
                    $directory = $user_install . $edited_path;
                    if ($directory[strlen($directory) - 1] == '/') {
                        $directory = substr($directory, 0, strlen($directory) - 1);
                    }
                    if (!@ftp_chmod($conn_id, eval("return({$np});"), $directory)) {
                        $perm_flag = false;
                    }
                }

                if (!$perm_flag) {
                    $perm_msg .= '* Sorry, an error occurred. Please try again or set the permissions manually <br/>';
                } else {
                    $perm_msg = '<b>* File permissions successfuly set </b><br/>';
                }
            } else {
                $perm_msg .= '* Sorry, could not connect to the server. Please check the credentials <br/>';
            }
        }
    }
} elseif ($chmodstatus == '755') {
    //cgi handler requires 755 so no file permission change needed
} elseif ($chmodstatus == '000') {
    //ftp_chmod wont work on windows 
}

$error = false;

if ($write == 'UNWRITABLE') {
    $custom_dir     = "../custom/";
    $styles_dir     = "../styles/";
    $attach_dir     = "../attachments/";
    $backup_dir     = "../backup/";
    $download_dir   = "../downloads/";
    $csv_dir        = "../csvfiles/";
    $api_file       = "../api/useradd.php";
    $cls_file       = "../api/server_class.php";
    $settings_file  = "../config/settings.php";
    $purge_dir      = "../admin/purgedtickets/";
    $attachsub_dir  = "../admin/purgedtickets/attachments/";
    $staff_dir      = "../staff/images/";
    $image_dir      = "../fckeditorimages/";
    $config_file    = "../FCKeditor/editor/filemanager/connectors/php/config.php";
}//end if
//show alert only for non-cgi servers    
if ($chmodstatus == '777' || $chmodstatus == '000') {

    if (!file_writable($custom_dir)) {
        $error = true;
        $message .= " * Change the permission of 'custom' folder in the root to 777 <br>";
    }//end if

    if (!file_writable($styles_dir)) {
        $error = true;
        $message .= " * Change the permission of 'styles' folder in the root to 777 <br>";
    }//end if

    if (!file_writable($attach_dir)) {
        $error = true;
        $message .= " * Change the permission of 'attachments' folder in the root to 777 <br>";
    }//end if

    if (!file_writable($backup_dir)) {
        $error = true;
        $message .= " * Change the permission of 'backup' folder in the root to 777 <br>";
    }//end if

    if (!file_writable($download_dir)) {
        $error = true;
        $message .= " * Change the permission of 'downloads' folder in the root to 777 <br>";
    }//end if

    if (!file_writable($csv_dir)) {
        $error = true;
        $message .= " * Change the permission of 'csvfiles' folder in the root to 777 <br>";
    }//end if

    if (!file_writable($api_file)) {
        $error = true;
        $message .= " * Change the permission of 'api/useradd.php' folder in the root to 777 <br>";
    }//end if

    if (!file_writable($cls_file)) {
        $error = true;
        $message .= " * Change the permission of 'api/server_class.php' folder in the root to 777 <br>";
    }//end if

    if (!file_writable($settings_file)) {
        $error = true;
        $message .= " * Change the permission of 'config/settings.php' folder in the root to 777 <br>";
    }//end if

    if (!file_writable($purge_dir)) {
        $error = true;
        $message .= " * Change the permission of 'admin/purgedtickets' folder in the root to 777 <br>";
    }//end if

    if (!file_writable($attachsub_dir)) {
        $error = true;
        $message .= " * Change the permission of 'admin/purgedtickets/attachments' folder in the root to 777 <br>";
    }//end if
    
    if (!file_writable($staff_dir)) {
        $error = true;
        $message .= " * Change the permission of 'staff/images' folder in the root to 777 <br>";
    }//end if
    
    if (!file_writable($image_dir)) {
        $error = true;
        $message .= " * Change the permission of 'fckeditorimages' folder in the root to 777 <br>";
    }//end if
    
    if (!file_writable($config_file)) {
        $error = true;
        $message .= " * Change the permission of 'FCKeditor/editor/filemanager/connectors/php/config.php' file to 777 <br>";
    }//end if
}

if ($error) {
    $write = 'UNWRITABLE';
} else {
    $write = 'WRITABLE';
}

/* * *********Server configuration check ends here**************** */


$upgrade = false;
if ($_POST["btnContinue"] == "Upgrade") {
    $message = "";

    if ($error) {
        $message = "Please correct the following errors to continue:" . "<br>" . $message;
        // echo $message;
    }//end if
    else {

        $fp2 = fopen("./includes/config.sql", "r");
        while (!feof($fp2)) {
            $buffer = fgets($fp2, 4096);
            mysql_query($buffer, $conn);
        }
        fclose($fp2);
        
        //set the upload path in the fckeditor config file
        $filename = "../FCKeditor/editor/filemanager/connectors/php/config.php";
        $handle = fopen($filename, "rb");
        $contents = fread($handle, filesize($filename));
        fclose($handle);
        
        $user_install = str_replace('/upgrade-4.2', '', getcwd());
        $path_parts = explode('/public_html', $user_install);
        $user_install = $path_parts[1];
        
        $contents = str_replace('FCK_IMAGE_UPLOAD_DIRECTORY', $user_install.'/fckeditorimages/', $contents);
        
        $fp = fopen($filename, 'w');
        fwrite($fp, $contents);
        fclose($fp);

        $sql = "Update sptbl_lookup set vLookUpValue='" . $txtSiteURL . "/'  where vLookUpName = 'SiteURL'";
        mysql_query($sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . $txtSiteURL . "/' where vLookUpName = 'HelpDeskURL'";
        mysql_query($sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . $txtSiteURL . "' where vLookUpName = 'LoginURL'";
        mysql_query($sql);

        $upgrade = true;
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title>iScripts SupportDesk Upgrade</title>
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <style type="text/css">
            <!--
            .install_option {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9pt; color: #333333}
            .install_value_ok { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9pt; font-weight: bold; color: #009900 !important }
            .install_value_fail { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 7pt; font-weight: bold; color: #CC0000 !important }
            -->
        </style>
        <script type="text/javascript" src="../scripts/jquery.js"></script>
        <script type="text/javascript">
            function divToggle(elem)
            {
                if($(elem).attr('checked')){
                    $('#err_div').slideDown('slow');
                }
                else{
                    $('#err_div').slideUp('slow');
                }
            }
        </script>
    <body topmargin="0">
        <div class="header_row">
            <div class="header_container wrapper">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="23%" align="center" ><h2><span class="">SupportDesk Upgrade</span></h2></td>
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

        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td><img src="style/spacer.gif" width="1" height="5"></td>
            </tr>
        </table>
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>

                <td width="76%" valign="top" height ="400">
                    <!-- Here's where I want my views to be displayed -->
                    <table width="80%" border="0" cellpadding="0" cellspacing="0" align="center">
                        <tr>
                            <td>
                                <?php
                                $pos = strpos($_SERVER['SCRIPT_NAME'], '/webroot');
                                $ck = substr($_SERVER['SCRIPT_NAME'], 0, $pos);

                                $s = null;
                                if ($_SERVER['HTTPS']) {
                                    $s = 's';
                                }
                                $httpHost = $_SERVER['HTTP_HOST'];
                                if (isset($httpHost)) {
                                    $ser = 'http' . $s . '://' . $httpHost;
                                }
                                unset($httpHost, $s);
                                $ft = '';
                                $ft = @file_get_contents($ser . "/" . $ck . "/config/rewtest/link2.html");
                                //print_r($txtSiteURLt);exit;

                                if (php_sapi_name() != 'apache2handler' || $ft == '') {
                                    ?>
                                    <div align="center" id="items_top_area">
                                        <a title="OnlineInstallationManual" href="javascript:void(0)" onClick="window.open('<?php echo htmlentities($txtSiteURL); ?>/docs/supportdesk.pdf','OnlineInstallationManual','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd');">Installation Manual</a>&nbsp;&bull;&nbsp;
                                        <a title="Readme" href="javascript:void(0)" onClick="window.open('<?php echo htmlentities($txtSiteURL); ?>/Readme.txt','Readme','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd');">Readme</a>&nbsp;&bull;&nbsp;
                                        <a title="If you have any difficulty, submit a ticket to the support department" href="http://www.iscripts.com/support/postticketbeforeregister.php" target="_blank">Get Support</a>
                                    </div>
                                <?php } ?>
                                <?php
                                if (!$upgrade) {
                                    ?>
                                    <table width="80%" border="0" align="center">
                                        <tr>
                                            <td align="center" ><b><font size="1">
                                                        <div align="justify" >
                                                            <br>
                                                            <font color="#F4700E" size="+1">Thank you for choosing SupportDesk&nbsp;</font> <br><br>
                                                            <font color="#000000" size="2"><img src="style/dot.jpg">&nbsp;To complete this upgrade please enter the details below.</font>
                                                        </div>

                                                    </font></b>
                                            </td>
                                        </tr>
                                        <?php if ($post_flag) { ?>
                                            <tr>
                                                <td align=center class="message" >
                                                    <div align="left" class="text_information">
                                                        <br>
                                                        <font color="#FF0000"><?php echo $message; ?></font><br>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td class=maintext align="left" >
                                                <br>
                                                <form name="frmInstall" method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" enctype="multipart/form-data">
                                                    <br>
                                                    <FIELDSET>
                                                        <LEGEND class='block_class'>File Permissions</LEGEND>
                                                        <table width=85% border="0" cellpadding="2" cellspacing="2" class=maintext>
                                                            <tr>
                                                                <td colspan="2" align="left">
                                                                    <b>
                                                                        <?php if ($chmodstatus == '777') { ?>
                                                                            SupportDesk requires that some of the folders have write permission. You can provide an FTP login so that this process is done automatically.<br/><br/>
                                                                            For security reasons, it is best to create a separate FTP user account with access to the SupportDesk installation only and not the entire web server. Your host can assist you with this.
                                                                            If you have difficulties completing installation without these credentials, please click "I would provide permissions manually" to do it yourself.<br/><br/>
                                                                        <?php } elseif ($chmodstatus == '000') { ?>
                                                                            SupportDesk requires that some of the folders have write permission. Please provide write permission for the following files :-<br/><br/>
                                                                        <?php } elseif ($chmodstatus == '755') { ?>
                                                                            SupportDesk requires that some of the folders have write permission.<br/><br/>
                                                                        <?php } ?>
                                                                    </b>
                                                                </td>
                                                            </tr>
                                                            <?php if ($write == 'UNWRITABLE') { ?>
                                                                <tr>
                                                                    <td class=maintext align="left">FTP username</td>
                                                                    <td width="61%" align=left>
                                                                        <input name="FTPusername"  id="FTPusername" type="text" size="50" value="<?php echo htmlentities($txtFTPusername); ?>">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class=maintext align="left">FTP password</td>
                                                                    <td width="61%" align=left>
                                                                        <input name="FTPpassword"  id="FTPpassword" type="password" size="50" value="<?php echo htmlentities($txtFTPpassword); ?>">
                                                                    </td>
                                                                </tr>
                                                                <?php if ($chmodstatus == '777') { ?>
                                                                    <tr>
                                                                        <td colspan="2" align="left">
                                                                            <input type="checkbox" name="auto_set" id="auto_set" onclick="divToggle(this)" /> &nbsp; I would provide permissions manually
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            } else {
                                                                ?>
                                                                <tr>
                                                                    <td colspan="2" align="left">
                                                                        <b>File permissions are OK.</b>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </table>
                                                        <?php if ($write == 'UNWRITABLE') { ?>
                                                            <div id="err_div" style="<?php if ($chmodstatus == '777') { ?>display:none<?php } ?>">
                                                                <fieldset>
                                                                    <legend>Directories/Files List</legend>
                                                                    <?php echo $message; ?>
                                                                </fieldset>
                                                            </div>
                                                        <?php } ?>
                                                    </FIELDSET>
                                                    <br>
                                                    <table width=85% border=0 cellpadding="2" cellspacing="2" class=maintext>
                                                        <tr><td>&nbsp;</td></tr>
                                                        <tr>
                                                            <td align="center">
                                                                <input type="submit" name="btnContinue" value="Upgrade" class="buttn_admin">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </form>
                                            </td>
                                        </tr>
                                    </table>
                                    <?php
                                } else {
                                    ?>
                                    <table width="80%" border="0" align="center">
                                        <tr>
                                            <td>
                                                <table width=85% border=0 cellpadding="2" cellspacing="2" class=maintext align="center">
                                                    <br>
                                                    <tr>
                                                        <td align="center" class="maintext" >
                                                            <font color="#F4700E" size="+1">Congratulations! The Upgrade Process was completed successfully!</font>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" class="maintext" height="20" >&nbsp;</td>
                                                    </tr>
                                                    <?php
                                                    $request_uri = explode('/', $_SERVER['REQUEST_URI']);

                                                    $script_filename = explode('/', $_SERVER['SCRIPT_FILENAME']);
                                                    $build_path = "http://" . $_SERVER['HTTP_HOST'] . "/" . $request_uri[1] . "/admin/login.php";
                                                    $build_path_home = "http://" . $_SERVER['HTTP_HOST'] . "/" . $request_uri[1] . "/index.php";
                                                    ?>
                                                    <tr>
                                                        <td align="center">
                                                            <br>
                                                            <fieldset>
                                                                <legend class="block_class">Site Login Details</legend>
                                                                <table cellpadding="0" cellspacing="0" width="80%" class="maintext" align="center">
                                                                    <tr>
                                                                        <td colspan="2">&nbsp;</td>
                                                                    </tr>
                                                                    <?php
                                                                    $pos = strpos($_SERVER['SCRIPT_NAME'], '/webroot');
                                                                    $ck = substr($_SERVER['SCRIPT_NAME'], 0, $pos);

                                                                    $s = null;
                                                                    if ($_SERVER['HTTPS']) {
                                                                        $s = 's';
                                                                    }
                                                                    $httpHost = $_SERVER['HTTP_HOST'];
                                                                    if (isset($httpHost)) {
                                                                        $ser = 'http' . $s . '://' . $httpHost;
                                                                    }
                                                                    unset($httpHost, $s);
                                                                    $ft = '';
                                                                    $ft = @file_get_contents($ser . "/" . $ck . "/config/rewtest/link2.html");
                                                                    if (php_sapi_name() == 'apache2handler') {
                                                                        $apach = true;
                                                                    } else {
                                                                        $apach = false;
                                                                    }
                                                                    ?>
                                                                    <tr>
                                                                        <td width="24%"><b><font size="-1">Admin URL&nbsp;:</font></b></td>
                                                                        <td width="76%"><a style="cursor:pointer" href="<?php echo $txtSiteURL . "/admin" ?>"><?php echo $txtSiteURL . "/admin"; ?>&nbsp;<img src="style/homebut.jpg" border="0" height="25"></a></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">&nbsp;</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td width="24%"><b><font size="-1">Home URL&nbsp;:</font></b></td>
                                                                        <td width="76%"><a style="cursor:pointer" href="<?php echo $txtSiteURL; ?>/"><?php echo $txtSiteURL; ?>/&nbsp;<img src="style/homebut.jpg" border="0" height="25"></a></td>
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
                                    <?php
                                }
                                ?>
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
