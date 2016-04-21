<?php
ob_start();
error_reporting(0);
$version = phpversion();
if($version > '5.4'){
include_once('../config/mysql2i.class.php');
}
include_once('../config/config.php');
include_once('cls_serverconfig.php');

//prevent re-installation if already installed
if (INSTALLED) {
    header("location: ../index.php");
    exit;
}

/*
 * get the path dynamically
 * NOTE:- need to add www via htaccess if required
 */
$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) .$s . "://";
define('ROOT_URL', $protocol );
 
//Generating Root URL
 
$current_path = $_SERVER['SCRIPT_FILENAME'];
$current_path=rtrim($current_path,"/");
$current_path=$current_path."/";
$current_path=str_replace("\\","/",$current_path);
$fckeditor_path=str_replace('/install/install.php', '', $current_path);

$DocumentRoot=$_SERVER["DOCUMENT_ROOT"];
$DocumentRoot=rtrim($DocumentRoot,"/");
$DocumentRoot=$DocumentRoot."/";
$DocumentRoot=str_replace("\\","/",$DocumentRoot);



$path=str_replace($DocumentRoot,"",$current_path);
$path=str_replace('/install/install.php', '', $path);
$root_url = ROOT_URL . $_SERVER['SERVER_NAME'] ."/".$path;

////////

//add trailing slashes
if($root_url[strlen($root_url) - 1] <> '/'){
    $root_url .= '/';
}

define('BASE_URL', $root_url);
$secure_root_url = $root_url;
$secure_root_url = str_replace('http:', 'https:', $secure_root_url);


$perm_flag = true;
$perm_msg = '';
$error_message = '';
$error = false;
$installed = false;
$user_data = array();
$post_flag = false;

$configfile     = "../config/config.php";
$settingsfile   = "../config/settings.php";
$useradd		="../api/useradd.php";
$serverclass	="../api/server_class.php";
$schemafile     = "sql/schema.sql";
$fckeditor = "../FCKeditor/editor/filemanager/connectors/php/config.php";

$directories = array(
		"../custom/", 
		"../styles/", 
		"../attachments/", 
		"../backup/", 
		"../downloads/", 
		"../csvfiles/", 
		"../api/useradd.php", 
		"../api/server_class.php", 
		"../config/settings.php", 
		"../config/config.php",
		"../admin/purgedtickets/", 
		"../admin/purgedtickets/attachments/", 
		"../staff/images/", 
		"../fckeditorimages/", 
		"../FCKeditor/editor/filemanager/connectors/php/config.php");



$serverSettings = ServerConfig::checkServerConfiguration();
$serverCurrentSettings = ServerConfig::getServerSettings();




$host_name = parse_url($_SERVER['HTTP_HOST']);

if (isset($_POST['installerCheck'])) {
   $post_flag = true;

//For Getting Folder Permission Status and Proper Error messages
	
	foreach ($directories as $dir) {
        $permission = ServerConfig::fileWritable($dir, str_replace("../","", $dir));
        if (!$permission['status'] && $error == false) {
				$error = true; 
				$serverPermission="true";
	    
        }
        
    }

	
    if ($error == true) { //echo '<pre>'; print_r($_POST); echo '</pre>'; exit;
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

                    $user_install = str_replace('install', '', getcwd());

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
						$directory=ltrim($directory,"/");
                        if (!@ftp_chmod($conn_id, eval("return({$np});"), $directory)) {
                            $perm_flag = false;
                        }
                    }
                    

                    if (!$perm_flag) {
                        $perm_msg .= '* Sorry, an error occurred. Please try again or set the permissions manually <br/>';
                    } else {
                        $perm_msg = '<b>* File permissions successfuly set </b><br/>';
						$serverPermission="false";
						$error=false;
                    }
                } else {
                    $perm_msg .= '* Sorry, could not connect to the server. Please check the credentials <br/>';
                }
            }
        }
    } 

    //check the user data
    $user_data['txtDBServerName'] = $_POST["txtDBServerName"];
    $user_data['txtDBName'] = $_POST["txtDBName"];
    $user_data['txtDBUserName'] = $_POST["txtDBUserName"];
    $user_data['txtDBPassword'] = $_POST["txtDBPassword"];
    $user_data['txtSiteName'] = $_POST["txtSiteName"];
    $user_data['txtAdminName'] = $_POST["txtAdminName"];
    $user_data['txtAdminPassword'] = $_POST["txtAdminPassword"];
    $user_data['txtLicenseKey'] = $_POST["txtLicenseKey"];
    $user_data['txtAdminEmail'] = $_POST["txtAdminEmail"];
   

    if (trim($user_data['txtDBServerName']) == '') {
        $message .= " * Database Server Name is empty!" . "<br>";
        $error = true;
    }
    if (trim($user_data['txtDBName']) == '') {
        $message .= " * Database Name is empty!" . "<br>";
        $error = true;
    }
    if (trim($user_data['txtDBUserName']) == '') {
        $message .= " * Database User Name is empty!" . "<br>";
        $error = true;
    }
    if (trim($user_data['txtSiteName']) == '') {
        $message .= " * Site Name is empty!" . "<br>";
        $error = true;
    }
    if (trim($user_data['txtLicenseKey']) == '') {
        $message .= " * License Key is empty!" . "<br>";
        $error = true;
    }
    if (trim($user_data['txtAdminEmail']) == '') {
        $message .= " * Admin Email is empty!" . "<br>";
        $error = true;
    } else {
        if (!ServerConfig::is_valid_email($user_data['txtAdminEmail'])) {
            $message .= " * Invalid Admin Email!" . "<br>";
            $error = true;
        }
    }

    //check the db connection
    $connection = @mysql_connect($user_data['txtDBServerName'], $user_data['txtDBUserName'], $user_data['txtDBPassword']);
    if ($connection === false) {
        $error = true;
        $message .= " * Connection Not Successful! Please verify your database details!<br>";
    } else {
        $dbselected = @mysql_select_db($user_data['txtDBName'], $connection);
        if (!$dbselected) {
            $error = true;
            $message .= " * Database could not be selected! Please verify your database details!<br>";
        }
    }

    //proceed with corresponding action
    $version = '4.3';
    if (!$error) {
        //update SupportDesk config file
        $fp = fopen($configfile, "w+");
        
        
        $configcontent = "<?php\n";
        $configcontent .= "define('INSTALLED', true); \n\n";
        $configcontent .= "define('VERSION', $version); \n\n";
        $configcontent .= "\n?>";
        fwrite($fp, $configcontent);
        fclose($fp);
        
        //update SupportDesk settings file
        $handle = fopen($settingsfile, "rb");
        $contents = fread($handle, filesize($settingsfile));
        fclose($handle);
        $version_ck = phpversion();
        if($version_ck > '5.4'){
        $contents = "<?php include_once('mysql2i.class.php');  \r\n$" . "glb_dbhost=\"" . $user_data['txtDBServerName'] . "\";\r\n";
        $contents .= "$" . "glb_dbuser=\"" . $user_data['txtDBUserName'] . "\";\r\n";
        $contents .= "$" . "glb_dbpass=\"" . $user_data['txtDBPassword'] . "\";\r\n";
        $contents .= "$" . "glb_dbname=\"" . $user_data['txtDBName'] . "\";\r\n";
         } else {
		  $contents = "<?php \r\n$" . "glb_dbhost=\"" . $user_data['txtDBServerName'] . "\";\r\n";
        $contents .= "$" . "glb_dbuser=\"" . $user_data['txtDBUserName'] . "\";\r\n";
        $contents .= "$" . "glb_dbpass=\"" . $user_data['txtDBPassword'] . "\";\r\n";
        $contents .= "$" . "glb_dbname=\"" . $user_data['txtDBName'] . "\";\r\n";	

              } 
        $fp = fopen($settingsfile, 'w');
        fwrite($fp, $contents);
        fclose($fp);
        
        //Reading User Add file
        
        
        $handle = fopen($useradd, "rb");
        $contents_useradd = fread($handle, filesize($useradd));
        fclose($handle);
        
        //Writing to User Add File
        $fp = fopen($useradd, 'w');
        fwrite($fp, $contents.$contents_useradd);
        fclose($fp);
        
        
        
        //Writing to Server Class API
        
        $contents_serverclass = "\r\n" . "function xml_server() {\r\n";
        $contents_serverclass .= "$" . "this->users_tag = \"\";\r\n";
        $contents_serverclass .= "$" . "this->function_tag = \"\";\r\n";
        $contents_serverclass .= "$" . "this->values_tag = \"\";\r\n";
        $contents_serverclass .= "$" . "this->username_tag = \"\";\r\n";
        $contents_serverclass .= "$" . "this->password_tag = \"\";\r\n";
        $contents_serverclass .= "$" . "this->email_tag = \"\";\r\n";
        $contents_serverclass .= "$" . "this->company_tag = \"\";\r\n";
        $contents_serverclass .= "$" . "this->errno = \"0\";\r\n";
        $contents_serverclass .= "$" . "this->conapi = mysql_connect(\"" . $user_data['txtDBServerName'] . "\",\"" . $user_data['txtDBUserName'] . "\",\"" . $user_data['txtDBPassword'] . "\") or die(mysql_error());\r\n";
        $contents_serverclass .= "mysql_select_db(\"" . $user_data['txtDBName'] . "\",$" . "this->conapi) or die(mysql_error());\r\n}\r\n}\r\n?>";
        
        $fp = fopen($serverclass, "a+");
        fwrite($fp, $contents_serverclass);
        fclose($fp);
        
        // FCK editor
        
        
        $handle = fopen($fckeditor, "rb");
        $contents = fread($handle, filesize($fckeditor));
        fclose($handle);      
        $contents = str_replace('FCK_IMAGE_UPLOAD_DIRECTORY', $fckeditor_path.'fckeditorimages/', $contents);
        $fp = fopen($fckeditor, 'w');
        fwrite($fp, $contents);
        fclose($fp);
        
        //$contents = str_replace('ADMIN_CONFIG_EMAIL', $user_data['txtAdminEmail'], $contents);
        //$contents = str_replace('SITE_CONFIG_NAME', $user_data['txtSiteName'], $contents);
        //$contents = str_replace('CONFIG_BASE_URL', BASE_URL, $contents);

        

        //------------------------UPDATE THE DB---------------------------------//
        $sqlquery = @fread(@fopen($schemafile, 'r'), @filesize($schemafile));
        $sqlquery = ServerConfig::splitsqlfile($sqlquery, ";");

        

        for ($i = 0; $i < sizeof($sqlquery); $i++) {
            mysql_query($sqlquery[$i], $connection);
        }

        
        
       // $sqlsettings1 = "UPDATE " . $user_data['txtTablePrefix'] . $tableName." SET ".$valueField." = '" . addslashes($user_data['txtLicenseKey']) . "' WHERE ".$labelField." = 'licenseKey'";
       // mysql_query($sqlsettings1) or die(mysql_error());
        
        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($user_data['txtSiteName']) . "' where vLookUpName = 'HelpdeskTitle'";
        mysql_query($sql);
        
        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($user_data['txtLicenseKey']) . "' where vLookUpName='vLicenceKey'";
        mysql_query($sql);
        
        //an additional / added
        $sql = "Update sptbl_lookup set vLookUpValue='" . BASE_URL."'  where vLookUpName = 'SiteURL'";
        mysql_query($sql);
        
        $sql = "Update sptbl_lookup set vLookUpValue='" . BASE_URL ."' where vLookUpName = 'HelpDeskURL'";
        mysql_query($sql);
        
        $sql = "Update sptbl_lookup set vLookUpValue='" .BASE_URL. "' where vLookUpName = 'LoginURL'";
        mysql_query($sql);
        
        $sql = "Update sptbl_lookup set vLookUpValue='" . BASE_URL . "' where vLookUpName = 'EmailURL'";
        mysql_query($sql);   
        
        $sql = "Delete from sptbl_companies";
        mysql_query($sql);
        
        $sql = "Insert into sptbl_companies(nCompId,vCompName,vCompAddress1,vCompCity,nCompZip,vCompCountry,";
        $sql .= "vCompMail) Values('','" . addslashes(trim($_SERVER['HTTP_HOST'])) . "',
					'" . addslashes(trim($_SERVER['HTTP_HOST'])) . "','" . addslashes("city") . "',
					'11111','UnitedStates','')";
        mysql_query($sql);
        $var_compid = mysql_insert_id();
        
        
        $sql = "Delete from sptbl_staffs where vStaffname='staff'";
        mysql_query($sql);
        
        $sql = "Insert Into sptbl_staffs(nStaffId,vStaffname,vLogin,vPassword,vOnline,vMail,vYIM,
				vSMSMail,vMobileNo,nCSSId,nRefreshRate,nNotifyAssign,nNotifyPvtMsg,nNotifyKB,nNotifyArrival,vType,
				vDelStatus,tSignature)  Values('','staff','staff','" . md5(staff) . "','0',
				'staff@yoursite.com','staffsms@yoursite.com','staffaol@yoursite.com','23453453',1,1,1,1,
				0,1,'S','0','Thank You\r\nStaff\r\nSupportdesk\r\n')";
        mysql_query($sql);
        $var_staffid = mysql_insert_id();
        
        $sql = "Delete from sptbl_stafffields where nStaffId != '1'";
        mysql_query($sql);
        
        $sql = "Insert Into  sptbl_stafffields(nStaffId,nFieldId) Values('$var_staffid','1'),('$var_staffid','2'),('$var_staffid','3'),('$var_staffid','4')";
        mysql_query($sql);
        
        $sql = "Delete from sptbl_depts";
        mysql_query($sql);
        
        $sql = "Insert Into sptbl_depts(nDeptId,nCompId,vDeptDesc,nDeptParent,vDeptMail,vDeptCode) Values('','$var_compid','Support','0','dept@yoursite.com','D01')";
        mysql_query($sql);
        $var_deptid = mysql_insert_id();
        
        $sql = "Delete from sptbl_staffdept";
        mysql_query($sql);
        
        $sql = "Insert Into sptbl_staffdept(nStaffId,nDeptId) Values('$var_staffid','$var_deptid'),('1','$var_deptid')";
        mysql_query($sql);
        
        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($user_data['txtAdminEmail']) . "' where vLookUpName = 'MailAdmin'";
        mysql_query($sql);
        
        //update in staff table ofr admin user
        $sql = "Update sptbl_staffs set vMail='" . addslashes($user_data['txtAdminEmail']) . "' where nStaffId = '1'";
        mysql_query($sql);
        
        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($user_data['txtAdminEmail']) . "' where vLookUpName = 'MailTechnical'";
        mysql_query($sql);
        
        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($user_data['txtAdminEmail']) . "' where vLookUpName='MailEscalation'";
        mysql_query($sql);
        
        $sql = "Update sptbl_lookup set vLookUpValue='Administrator' where vLookUpName='MailFromName'";
        mysql_query($sql);
        
        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($user_data['txtAdminEmail']) . "' where vLookUpName='MailFromMail'";
        mysql_query($sql);
        
        $sql = "Update sptbl_lookup set vLookUpValue='Administrator' where vLookUpName='MailReplyName'";
        mysql_query($sql);
        
        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($user_data['txtAdminEmail']) . "' where vLookUpName='MailReplyMail'";
        mysql_query($sql);
        

        //installation tracker
        $rootserver = BASE_URL;
        $productVersion = 'SupportDesk 4.3';
        $string = "";
        $pro = urlencode($productVersion);
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
        $subject = "Script Installed at " . $user_data['txtSiteName'];
        
        $headers = "From: " . $user_data['txtSiteName'] . "<" . $user_data['txtAdminEmail'] . ">\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        
        $mailcontent = "Hello , <br><br><br>";
        $mailcontent .= "Your Site is successfully installed.<br> <a href='" . BASE_URL . "' target='_blank'>Click Here to Access your Site</a>";
        $mailcontent .= "<br><a href='" . BASE_URL . "admin' target='_blank'>Click Here to Access your Site Administration Control Panel</a> <br><br>";
        $mailcontent .= "Your Admin Username   :  admin";
        $mailcontent .= "<br>Your Admin Password   :admin ";        
        $mailcontent .= "<br><br><br> Thanks and regards,<br> " . $user_data['txtSiteName'] . " Team";

        /* Email Template */
        $email_temp_qry = "SELECT tTemplateDesc FROM sptbl_templates WHERE vStatus=0";
        $email_temp = mysql_query($email_temp_qry);
        $mailMsgArr = mysql_fetch_array($email_temp);
        $mailMsg = NULL;
        $dateVal = date("m/d/Y");
        $copyright = 'copyright '.date('Y').' '.$user_data['txtSiteName'].' All rights reserved';

        if(count($mailMsgArr) > 0) {
            $mailMsg = $mailMsgArr['tTemplateDesc'];
        } // End If
        if(!empty($mailMsg)){
                $mailMsg = str_replace("{SITE_LOGO}", "<img src='".BASE_URL . "images/logo.png'/>", $mailMsg);
                $mailMsg = str_replace("{Date}", $dateVal, $mailMsg);
                $mailMsg = str_replace("{MAIL_CONTENT}", $mailcontent, $mailMsg);
                $mailMsg = str_replace("{SITE_NAME}", $user_data['txtSiteName'], $mailMsg);
                $mailMsg = str_replace("{COPYRIGHT}", $copyright, $mailMsg);
            } else {
                $mailMsg = $mailcontent;
            }

        $mailcontent = $mailMsg;
        @mail(addslashes($user_data['txtAdminEmail']), $subject, $mailcontent, $headers);
    }
}

//For Getting Folder Permission Status and Proper Error messages
	
	foreach ($directories as $dir) {
        $permission = ServerConfig::fileWritable($dir, str_replace("../","", $dir));
        if (!$permission['status'] && $error == false) {
				$error = true; 
				$serverPermission="true";
	    
        }
         if (!$permission['status'] ) {
                $error_message=$error_message.$permission['message'];
        }
    }


if ($installed) {
    header("location: install_success.php");
    exit;
}
$installerTitle = 'iScripts SupportDesk Installer';
$productName = 'SupportDesk';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title><?php echo $installerTitle; ?></title>
    </head>
    <script type="text/javascript" src="../scripts/jquery.min.js"></script>
    <script type="text/javascript" src="../scripts/install.js"></script>
    <link href="css/install.css" rel="stylesheet" type="text/css" />
    <body class="bodyinstaller">
        <div class="header_row" >
            <div class="header_container  sitewidth">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tr>
                        <td width="23%" align="left" ><img src="<?php echo BASE_URL; ?>images/logo.png" alt="Logo"></td>
                        <td width="77%" align="right">
                            <h4><?php echo $installerTitle; ?></h4>
                            <div align="center" id="items_top_area">
                                &nbsp;&nbsp;
                                <a title="OnlineInstallationManual" href="#" onClick="window.open('<?php echo BASE_URL; ?>docs/supportdesk.pdf','OnlineInstallationManual','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd');"><strong>Installation manual</strong></a> |
                                <a title="Readme" href="#" onClick="window.open('<?php echo BASE_URL; ?>Readme.txt','Readme','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd');"><strong>Readme</strong></a> | 
                                <a title="If you have any difficulty, submit a ticket to the support department" href="#" onClick="window.open('http://www.iscripts.com/support/postticketbeforeregister.php','','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd,resizable=yes');">
                                <strong>Get Support</strong></a>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td><img src="css/spacer.gif" width="1" height="5"></td>
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
                                                        <font color="#F4700E" size="+1">Thank you for choosing <?php echo $productName;?>&nbsp;</font> <br><br>
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
                                                        <?php if($error){?><u><b><font color="#FF0000">Please correct the following errors to continue:</font></b></u><p/><?php }?>
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
                                                                        <?php if ($serverPermission==true) { ?>
                                                                            <?php echo $productName;?> requires that some of the folders have write permission. You can provide an FTP login so that this process is done automatically.<br/><br/>
                                                                            For security reasons, it is best to create a separate FTP user account with access to the <?php echo $productName;?> installation only and not the entire web server. Your host can assist you with this.
                                                                            If you have difficulties completing installation without these credentials, please click "I would provide permissions manually" to do it yourself.<br/><br/>
                                                                        <?php } ?>
                                                                    </b>
                                                                </td>
                                                            </tr>
                                                            <?php if ($serverPermission==true) { ?>
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
                                                                <?php if ($serverPermission==true) { ?>
                                                                    <tr>
                                                                        <td colspan="2" align="left">
                                                                            <input type="checkbox" name="auto_set" id="auto_set" <?php echo ($_POST['auto_set'])?'checked':'';?> onclick="divToggle(this)" /> &nbsp; I would provide permissions manually
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
                                                        <?php if ($serverPermission==true) { ?>
                                                            <div id="err_div" style="display:none">
                                                                <fieldset>
                                                                    <legend>Directories/Files List</legend>
                                                                    <?php echo $error_message; ?>
                                                                </fieldset>
                                                            </div>
                                                        <?php } ?>
                                                    </FIELDSET>
                                                    <br>
                                                    <br>

                                                    <FIELDSET>
                                                        <LEGEND class='block_class'>Database Details</LEGEND>
                                                        <table width=85% border=0 cellpadding="2" cellspacing="2" class=maintext>
                                                            <tr>
                                                                <td colspan="2" class=maintext align="left">Database Server</td>
                                                                <td width="61%" align=left>
                                                                    <input type="text" name="txtDBServerName" id="txtDBServerName" value="<?php echo trim($user_data['txtDBServerName']) <> "" ? htmlentities($user_data['txtDBServerName']) : "localhost"; ?>" />
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" class=maintext align="left">Database Name</td>
                                                                <td width="61%" align=left>
                                                                    <input name="txtDBName"  id="txtDBName" type="text"   class="textbox"  maxlength="100" size="50" value="<?php echo htmlentities($user_data['txtDBName']); ?>" >								
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" class=maintext align="left">Database User Name</td>
                                                                <td width="61%" align=left>
                                                                    <input name="txtDBUserName"  id="txtDBUserName" type="text" maxlength="100" size="50" value="<?php echo htmlentities($user_data['txtDBUserName']); ?>">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" class=maintext align="left">Database Password</td>
                                                                <td width="61%" align=left>
                                                                    <input name="txtDBPassword"  id="txtDBPassword" type="text"  maxlength="100" size="50" value="<?php echo htmlentities($user_data['txtDBPassword']); ?>">
                                                                </td>
                                                            </tr>
                                                           

                                                        </table>
                                                    </FIELDSET><br><br>
                                                    <FIELDSET>
                                                        <LEGEND class='block_class'>Site Details</LEGEND>
                                                        <table width=85% border=0 cellpadding="2" cellspacing="2" class=maintext>
                                                            <tr>
                                                                <td colspan="2" class=maintext align="left">Site Name</td>
                                                                <td width="61%" align=left>
                                                                    <input name="txtSiteName"  id="txtSiteName" type="text" maxlength="100" size="50" value="<?php echo htmlentities($user_data['txtSiteName']); ?>">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" class=maintext align="left">License Key</td>
                                                                <td width="61%" align=left>
                                                                    <input name="txtLicenseKey"  id="txtLicenseKey" type="text" maxlength="100" size="50" value="<?php echo htmlentities($user_data['txtLicenseKey']); ?>">
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </FIELDSET>
                                                    <br>
                                                    <br>
                                                    <FIELDSET>
                                                        <LEGEND class="block_class">Administration Details</LEGEND>
                                                        <table width=85% border=0 cellpadding="2" cellspacing="2" class=maintext>							
                                                            <tr>
                                                                <td colspan="2" class=maintext  align="left">Admin Email</td>
                                                                <td width="61%" align=left>
                                                                    <input name="txtAdminName"  id="txtAdminName" type="hidden" maxlength="100" size="50" value="admin">

                                                                    <input name="txtAdminPassword"  id="txtAdminPassword" type="hidden" maxlength="100" size="50" value="admin">

                                                                    <input name="txtAdminEmail"  id="txtAdminEmail" type="text"  maxlength="100" size="50" value="<?php echo htmlentities($user_data['txtAdminEmail']); ?>">
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </FIELDSET>
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
                                                    <input type="hidden" name="installerCheck" id="installerCheck" value="1" />
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
         <div class="installr_footer"></div>
    </body>
</html>
