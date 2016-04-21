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
include_once('cls_serverconfig.php');

/*
 * get the path dynamically
 * NOTE:- need to add www via htaccess if required
 */
$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) .$s . "://";
define('ROOT_URL', $protocol );
        
$current_path = getcwd();
if (strstr($current_path, 'public_html')) {
    $path_part = explode('/public_html', $current_path);
} elseif (strstr($current_path, 'httpdocs')) {
    $path_part = explode('/httpdocs', $current_path);
} else {
    $path_part[1] = '';
}
$path_part[1] .= '/';
$path_part[1] = str_replace('project/upgrade2.2/', '', $path_part[1]);

define('BASE_URL', ROOT_URL . $_SERVER['SERVER_NAME'] . $path_part[1]);
define('IMAGE_URL', BASE_URL . 'project/styles/images/');
define('IMAGE_FILE_URL', BASE_URL . 'project/files/');
define('FILE_UPLOAD_DIR', "../files/");


$perm_flag = true;
$perm_msg = '';
$error_message = '';
$error = false;
$installed = false;
$user_data = array();
$post_flag = false;

$configfile = "../config/config.php";
$settingsfile = "../config/settings.php";
$routesfile = "../config/routes.php";
$vistadbfile = "../Demo/app/config/database.php";
$wpconfigfile = "../Demo/app/webroot/blog/wp-config.php";
$supportconfigfile = "../support/config/settings.php";

$vista_schemafile = "sql/vista_schema.sql";
$vista_datafile = "sql/vista_data.sql";
$support_schemafile = "sql/supportdesk_schema.sql";
$support_datafile = "sql/supportdesk_data.sql";
$schemafile = "sql/gostores_schema.sql";
$datafile = "sql/gostores_data.sql";

$directories = array("Demo/app/webroot/img/products/",
    "Demo/app/webroot/img/csv/",
    "Demo/app/webroot/Fax/",
    "Demo/app/webroot/files/",
    "Demo/app/webroot/files/File/",
    "Demo/app/webroot/files/Flash/",
    "Demo/app/webroot/files/Image/",
    "Demo/app/webroot/files/Media/",
    "Demo/app/webroot/files/Graph/",
    "Demo/app/webroot/img/SiteLogo_disp.gif",
    "Demo/app/webroot/img/SiteLogo.jpg",
    "Demo/app/tmp/cache/",
    "Demo/app/tmp/cache/models/",
    "Demo/app/tmp/cache/views/",
    "Demo/app/tmp/cache/persistent/",
    "Demo/app/tmp/",
    "Demo/app/tmp/logs/",
    "Demo/app/tmp/sessions/",
    "Demo/app/webroot/img/",
    "Demo/app/webroot/css/",
    "Demo/app/webroot/Fedex/shipping_label/",
    "Demo/app/webroot/Fedex/",
    "Demo/app/webroot/blog/wp-content/",
    "Demo/app/webroot/blog/wp-config.php",
    "Demo/app/controllers/components/pple.xml",
    "Demo/app/config/database.php",
    "Demo/app/webroot/config.php",
    "support/custom/",
    "support/styles/",
    "support/attachments/",
    "support/backup/",
    "support/downloads/",
    "support/csvfiles/",
    "support/api/useradd.php",
    "support/api/server_class.php",
    "support/config/settings.php",
    "support/admin/purgedtickets/",
    "support/admin/purgedtickets/attachments/",
    "support/staff/images/",
    "support/fckeditorimages/",
    "support/FCKeditor/editor/filemanager/connectors/php/config.php",
    "config/config.php",
    "config/settings.php");

$serverOS = ServerConfig::getServerOS();
$serverSettings = ServerConfig::checkServerConfiguration();
$serverCurrentSettings = ServerConfig::getServerSettings();

$host_name = parse_url($_SERVER['HTTP_HOST']);

if (isset($_POST['upgraderCheck'])) {

    $post_flag = true;

    //automatically set the folder permissions
    if ($serverOS['chmodstatus'] == '777') {
        if (isset($_POST["btnContinue"]) && !isset($_POST["auto_set"])) {
            $txtFTPusername = $_POST['FTPusername'];
            $txtFTPpassword = $_POST['FTPpassword'];

            $user_data['FTPusername'] = $_POST["FTPusername"];
            $user_data['FTPpassword'] = $_POST["FTPpassword"];

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

                    $user_install = str_replace('upgrade2.2', '', getcwd());

                    //get the path staring from public_html
                    if (strstr($user_install, 'public_html')) {
                        $path_parts = explode('/public_html', $user_install);
                        $user_install = '/public_html' . $path_parts[1];
                    } elseif (strstr($user_install, 'httpdocs')) {
                        $path_parts = explode('/httpdocs', $user_install);
                        $user_install = '/httpdocs' . $path_parts[1];
                    }

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
    } elseif ($serverOS['chmodstatus'] == '755') {
        //cgi handler requires 755 so no file permission change needed
    } elseif ($serverOS['chmodstatus'] == '000') {
        //ftp_chmod wont work on windows 
    }
    
    //-------------------------------UPGRADE VISTACART DEMO-------------------------------//
    include_once('../Demo/app/config/database.php');
    
    $sqlObj     = new DATABASE_CONFIG();
    $sqlUser    = $sqlObj->default['login'];
    $sqlPass    = $sqlObj->default['password'];
    $sqlHost    = $sqlObj->default['host'];
    $sqlDB      = $sqlObj->default['database'];
    
    
    $sqlPrefix  = $sqlObj->default['prefix'];

    $connection = @mysql_connect($sqlHost, $sqlUser, $sqlPass);
    if ($connection === false) {
        $error = true;
        $message .= " * Connection Not Successful! Please verify your database details!<br>";
    } else {
        $dbselected = @mysql_select_db($sqlDB, $connection);
        if (!$dbselected) {
            $error = true;
            $message .= " * Database could not be selected! Please verify your database details!<br>";
        }
    }    


    //check the user data
    $user_data['txtSiteName'] = $_POST["txtSiteName"];
    $user_data['txtAdminName'] = $_POST["txtAdminName"];
    $user_data['txtAdminPassword'] = $_POST["txtAdminPassword"];
    $user_data['txtLicenseKey'] = $_POST["txtLicenseKey"];
    $user_data['txtAdminEmail'] = $_POST["txtAdminEmail"];
    $user_data['txtTablePrefix'] = $_POST["txtTablePrefix"];

    //proceed with corresponding action
    if ($error) {
        $message = "<u><b>Please correct the following errors to continue:</b></u>" . "<br><br>" . $message;
    } else {
        //update goStores config file
        $fp = fopen($configfile, "w+");
        $configcontent = "<?php\n";
        $configcontent .= "define('INSTALLED', true); \n\n";
        $configcontent .= "define('VERSION', '2.2'); \n\n";
        $configcontent .= "\n?>";
        fwrite($fp, $configcontent);


        //------------------------UPDATE THE DB [ VISTACART ]--------------------------------//
        $sqlquery = @fread(@fopen($vista_schemafile, 'r'), @filesize($vista_schemafile));
        $sqlquery = preg_replace('/goStores_Vista_/', $sqlPrefix, $sqlquery);
        $sqlquery = ServerConfig::splitsqlfile($sqlquery, ";");

        for ($i = 0; $i < sizeof($sqlquery); $i++) {
            mysql_query($sqlquery[$i], $connection);
        }

        $dataquery = @fread(@fopen($vista_datafile, 'r'), @filesize($vista_datafile));
        $dataquery = preg_replace('/goStores_Vista_/', $sqlPrefix, $dataquery);
        $dataquery = ServerConfig::splitsqlfile($dataquery, ";");

        for ($i = 0; $i < sizeof($dataquery); $i++) {
            mysql_query($dataquery[$i], $connection);
        }

        //-------------------UPDATE VISTACART INITIAL CONFIG VALUES-----------------------//
        $sqlPrefix  = str_replace("Vista_","",$sqlPrefix); 
        $admin_email = '';
        $sqlsettings1 = mysql_query("SELECT value, settingfield FROM " . $sqlPrefix . "Settings WHERE settingfield IN ('adminEmail', 'siteName','siteLogo')");
        if($sqlsettings1){
            while($sqlres = mysql_fetch_array($sqlsettings1)){
                if($sqlres['settingfield'] == 'adminEmail'){
                    $admin_email = $sqlres['value'];
                }
                else if($sqlres['settingfield'] == 'siteLogo'){
                    $siteLogoFile = $sqlres['value'];
                    
                    $siteLogo = IMAGE_URL.'gostores_logo.jpg';
                    if(!empty($siteLogoFile)){
                        $sqlsettings2 = mysql_query("SELECT file_path FROM " . $sqlPrefix . "files WHERE file_id=".$siteLogoFile);
                        if($sqlsettings2){
                            while($sqlres2 = mysql_fetch_array($sqlsettings2)){
                                $sqlres2["file_path"];
                                if(is_file(FILE_UPLOAD_DIR.$sqlres2["file_path"])){
                                    $siteLogo = IMAGE_FILE_URL."siteLogo_".$sqlres2["file_path"];
                                }
                            }
                        }
                        
                    }
                }
                else{
                    $site_name = $sqlres['value'];
                }
            }
        }
define('SITE_LOGO_FILE',stripslashes($generalSettingArr['siteLogo']));

define('SITE_LOGO_PREFIX','siteLogo_');

//SITE LOGO
$siteLogoFile = SITE_LOGO_FILE;
$siteLogo = IMAGE_URL.'gostores_logo.jpg';
if(!empty($siteLogoFile)){
    $logoArr = $dbObj->selectResult("files", "file_path", "file_id=".$siteLogoFile);
    $logoArr[0]->file_path;
    if(is_file(FILE_UPLOAD_DIR.$logoArr[0]->file_path)){
        $siteLogo = IMAGE_FILE_URL.SITE_LOGO_PREFIX.$logoArr[0]->file_path;
    }
}

define('SITE_LOGO',$siteLogo);
        //------------------------UPDATE SUPPORTDESK INITIAL CONFIG VALUES-----------------------//
       
        //update goStores settings file
        $handle = fopen($routesfile, "rb");
        $contents = fread($handle, filesize($routesfile));
        fclose($handle);

        $contents = str_replace('"cms/developer/", "cms/cms/developer/"', '"cms/developer/", "cms/cms/"', $contents);
        $contents = str_replace('"cms/developer", "cms/cms/developer/"', '"cms/developer", "cms/cms/"', $contents);
        

        $fp = fopen($routesfile, 'w');
        fwrite($fp, $contents);
        fclose($fp);
        //------------------------UPDATE THE DB [ SUPPORTDESK ]--------------------------------//
        $sqlquery = @fread(@fopen($support_schemafile, 'r'), @filesize($support_schemafile));
        $sqlquery = ServerConfig::splitsqlfile($sqlquery, ";");

        for ($i = 0; $i < sizeof($sqlquery); $i++) {
            mysql_query($sqlquery[$i], $connection);
        }

        $dataquery = @fread(@fopen($support_datafile, 'r'), @filesize($support_datafile));
        $dataquery = ServerConfig::splitsqlfile($dataquery, ";");

        for ($i = 0; $i < sizeof($dataquery); $i++) {
            mysql_query($dataquery[$i], $connection);
        }
        
       
        
        
        //--------------------------------IMPORT GOSTORES DB HERE-------------------------//
        $sqlquery = @fread(@fopen($schemafile, 'r'), @filesize($schemafile));
        $sqlquery = preg_replace('/goStores_/', $sqlPrefix , $sqlquery);
        $sqlquery = ServerConfig::splitsqlfile($sqlquery, ";");

        for ($i = 0; $i < sizeof($sqlquery); $i++) {
            mysql_query($sqlquery[$i], $connection);
        }

        $dataquery = @fread(@fopen($datafile, 'r'), @filesize($datafile));
        $dataquery = preg_replace('/goStores_/', $sqlPrefix, $dataquery);
        $dataquery = ServerConfig::splitsqlfile($dataquery, ";");

        for ($i = 0; $i < sizeof($dataquery); $i++) {
            mysql_query($dataquery[$i], $connection);
        }
        
        
        
        //update cms settings
        $cms_query_str = "SELECT id, table_name, section_config FROM cms_sections";
        $cms_query = mysql_query($cms_query_str);
        if($cms_query){
            while($cms_res = mysql_fetch_array($cms_query)){
                $tbl_name       = $cms_res['table_name'];
                $section_config = $cms_res['section_config'];
                $tbl_id         = $cms_res['id'];
                
                //if($tbl_name <> 'tbl_cms_settings'){
                    $tbl_name = str_replace('tbl_', $sqlPrefix, $tbl_name);
                    $section_config = str_replace('tbl_', $sqlPrefix, $section_config);
                    
                    mysql_query("UPDATE cms_sections SET table_name = '" . mysql_real_escape_string($tbl_name) . "', section_config = '" . mysql_real_escape_string($section_config) . "' WHERE id = $tbl_id");
                //}
            }
        }
        
        //update theme path
        $theme_query_str = "SELECT * FROM " . $sqlPrefix . "themes";
        $theme_query = mysql_query($theme_query_str);
        if($theme_query){
            while($theme_res = mysql_fetch_array($theme_query)){
                $img_path = $theme_res['theme_thumbnail'];
                $img_path = str_replace('http://localhost/gostores/', BASE_URL, $img_path);
                $tbl_id   = $theme_res['theme_id'];
                
                mysql_query("UPDATE " . $sqlPrefix . "themes SET theme_thumbnail = '" . mysql_real_escape_string($img_path) . "' WHERE theme_id = $tbl_id");
            }
        }
        
        
        //installation tracker
        $rootserver = BASE_URL;

        $string = "";
        $pro = urlencode("GoStores 2.2");
        $dom = urlencode($rootserver);
        $ipv = urlencode($_SERVER['REMOTE_ADDR']);
        $mai = urlencode($user_data['txtAdminEmail']);
        $string = "pro=$pro&dom=$dom&ipv=$ipv&mai=$mai";
        $contents = "no";
        $file = @fopen("http://www.iscripts.com/installtracker.php?$string", 'r');
        if ($file) {
            $contents = @fread($file, 8192);
        }
        

        $installed = true;


        //send confirmation email to admin
        $subject = "Script Upgraded at " . $site_name;
        
        $headers = "From: " . $site_name . "<" . $admin_email . ">\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        $path = "sitelogo.jpg";
        $mcont = "<table width='90%'  border='0' cellspacing='2' cellpadding='2' align='center'>
<tr><td><a href='" . BASE_URL . "' target='_blank'><img src='" . BASE_URL . "project/install/css/" . $path . "' border='0'></a></td></tr></table>";
        $mailcontent = "Hello , <br><br>";
        $mailcontent .= "Your Store is successfully upgraded.<br><br> <a href='" . BASE_URL . "' target='_blank'>Click Here to Access your Store</a>";
        $mailcontent .= "<br><a href='" . BASE_URL . "cms' target='_blank'>Click Here to Access your Store Administration Control Panel</a> <br>";
        //$mailcontent .= "Your Admin Username   :  admin";
        //$mailcontent .= "<br>Your Admin Password   :  admin";
        
        $footer = "<br> Thanks and regards,<br> " . $site_name . " Team";

        /* Email Template */
        $email_temp_qry = "SELECT cms_desc FROM " . $sqlPrefix . "Cms WHERE cms_name='email_template' ";
        $email_temp = mysql_query($email_temp_qry);
        $mailMsgArr = mysql_fetch_array($email_temp);
        $mailMsg = NULL;

        if(count($mailMsgArr) > 0) {
            $mailMsg = $mailMsgArr['cms_desc'];
        } // End If
        if(!empty($mailMsg)){
                $mailMsg = str_replace("{SITE_LOGO}", $siteLogo, $mailMsg);
                $mailMsg = str_replace("{DATE}", date("m/d/Y"), $mailMsg);
                $mailMsg = str_replace("{MAIL_CONTENT}", $mailcontent, $mailMsg);               
            } else {
                $mailMsg =$mailcontent;
            }

        $mailcontent = $mailMsg;
        @mail(addslashes($admin_email), $subject, $mailcontent, $headers);
    }
}


//check current directory permissions
foreach ($directories as $dir) {
    $permission = ServerConfig::fileWritable($dir, 'project/'.$dir);
    if (!$permission['status']) {
        $error = true;
        $error_message .= $permission['message'];
    }
}

if ($installed) {
    header("location: upgrade_success.php");
    exit;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
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

        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td><img src="../img/spacer.gif" width="1" height="5"></td>
            </tr>
        </table>
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>

                <td width="76%" valign="top" height ="400">
                    <!-- Here's where I want my views to be displayed -->
                    <table width="80%" border="0" cellpadding="0" cellspacing="0" align="center">
                        <tr>
                            <td>
                                <!--------Installer starts------------------------->

                                <!--items display area start -->

                                <div align="center" id="items_top_area">
                                    &nbsp;&nbsp;
                                    <a title="OnlineInstallationManual" href="#" onClick="window.open('<?php echo BASE_URL; ?>project/Docs/Installation_Manual.pdf','OnlineInstallationManual','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd');"><strong>Installation manual</strong></a>
                                    | <a title="Readme" href="#" onClick="window.open('<?php echo BASE_URL; ?>project/Docs/Readme.txt','Readme','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd');"><strong>Readme</strong></a> | 
                                    <a title="If you have any difficulty, submit a ticket to the support department" href="#" onClick="window.open('http://www.iscripts.com/support/postticketbeforeregister.php','','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd,resizable=yes');">														
                                        <strong>Get Support</strong></a>
                                </div>

                                <?php
                                if ($serverSettings == "FAILURE") {
                                    ?>
                                    <table width="100%" border=0 align="center">
                                        <?php
                                        foreach ($serverCurrentSettings as $settings) {
                                            $span_class = $settings['flag'] ? "install_value_ok" : "install_value_fail";
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $settings['feature']; ?> <span class="<?php echo $span_class; ?>"><?php echo $settings['setting']; ?></span>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <tr>
                                            <td>
                                                <span class="install_value_fail">Fatal errors detected.  Please correct the above red items and reload.</span>
                                            </td>
                                        </tr>
                                    </table>

                                    <?php
                                } else if (!$installed) {
                                    ?>
                                    <table width="80%" border="0" align="center">
                                        <tr>
                                            <td align="center" ><b><font size="1">
                                                    <div align="justify" >
                                                        <br>
                                                        <font color="#F4700E" size="+1">Thank you for choosing GoStores&nbsp;</font> <br><br>
                                                        <font color="#000000" size="2">To complete this installation please enter the details below.</font>
                                                    </div>

                                                    </font></b>
                                            </td>
                                        </tr>
                                        <?php if ($post_flag) { ?>
                                            <tr>
                                                <td align=center class="message" >
                                                    <div align="left" class="text_information">
                                                        <br>
                                                        <font color="#FF0000"><?php echo $perm_msg; ?></font><br>
                                                        <font color="#FF0000"><?php echo $error_message . '<br/>' . $message; ?></font><br>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td class=maintext align="left" >
                                                Note: All Fields Are Mandatory.
                                                <br>
                                                <form name="frmInstall" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                                    <br>

                                                    <FIELDSET>
                                                        <LEGEND class='block_class'>File Permissions</LEGEND>
                                                        <table width=85% border="0" cellpadding="2" cellspacing="2" class=maintext>
                                                            <tr>
                                                                <td colspan="2" align="left">
                                                                    <b>
                                                                        <?php if ($serverOS['chmodstatus'] == '777') { ?>
                                                                            GoStores requires that some of the folders have write permission. You can provide an FTP login so that this process is done automatically.<br/><br/>
                                                                            For security reasons, it is best to create a separate FTP user account with access to the GoStores installation only and not the entire web server. Your host can assist you with this.
                                                                            If you have difficulties completing installation without these credentials, please click "I would provide permissions manually" to do it yourself.<br/><br/>
                                                                        <?php } elseif ($serverOS['chmodstatus'] == '000') { ?>
                                                                            GoStores requires that some of the folders have write permission. Please provide write permission for the following files :-<br/><br/>
                                                                        <?php } elseif ($serverOS['chmodstatus'] == '755') { ?>
                                                                            GoStores requires that some of the folders have write permission.<br/><br/>
                                                                        <?php } ?>
                                                                    </b>
                                                                </td>
                                                            </tr>
                                                            <?php if ($serverOS['write'] == 'UNWRITABLE') { ?>
                                                                <tr>
                                                                    <td class=maintext align="left">FTP username</td>
                                                                    <td width="61%" align=left>
                                                                        <input name="FTPusername"  id="FTPusername" type="text" size="50" value="<?php echo htmlentities($user_data['FTPusername']); ?>">								
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class=maintext align="left">FTP password</td>
                                                                    <td width="61%" align=left>
                                                                        <input name="FTPpassword"  id="FTPpassword" type="password" size="50" value="<?php echo htmlentities($user_data['FTPpassword']); ?>">
                                                                    </td>
                                                                </tr>
                                                                <?php if ($serverOS['chmodstatus'] == '777') { ?>
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
                                                        <?php if ($serverOS['write'] == 'UNWRITABLE') { ?>
                                                            <div id="err_div" style="<?php if ($serverOS['chmodstatus'] == '777') { ?>display:none<?php } ?>">
                                                                <fieldset>
                                                                    <legend>Directories/Files List</legend>
                                                                    <?php echo $error_message; ?>
                                                                </fieldset>
                                                            </div>
                                                        <?php } ?>
                                                    </FIELDSET>
                                                    <br>
                                                    <br>
                                                    <table width=85% border=0 cellpadding="2" cellspacing="2" class=maintext>
                                                        <tr><td>&nbsp;</td></tr>
                                                        <tr>
                                                            <td align="center">
                                                                <input type="submit" name="btnContinue" value="Continue" class="buttn_admin">
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <!-- DO NOT REMOVE -->
                                                    <input type="hidden" name="upgraderCheck" id="upgraderCheck" value="1" />
                                                    <!-- ------------- -->
                                                </form>	
                                            </td>
                                        </tr>
                                    </table>
                                    <?php
                                }
                                ?>
                            </td>
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