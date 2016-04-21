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
error_reporting(E_ERROR);
ob_start();
include_once('../config/config.php');
include_once('cls_serverconfig.php');

//prevent re-installation if already installed
if (INSTALLED) {
    header("location: ../../index");
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
$path=str_replace('project/install/', '',end($newRoot));

$root_url = ROOT_URL . $_SERVER['SERVER_NAME'] ."/".$path;
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

$configfile = "../config/config.php";
$settingsfile = "../config/settings.php";
$vistadbfile = "../Demo/app/Config/database.php";
$vistaconfigfile = "../Demo/app/webroot/config.php";
$wpconfigfile = "../Demo/app/webroot/blog/wp-config.php";
$supportconfigfile = "../support/config/settings.php";

$schemafile = "sql/schema.sql";
$datafile = "sql/data.sql";

$txtTablePrefix = 'goStores_';
$txtSiteNameArr = explode('.', $_SERVER['SERVER_NAME']);
$txtSiteName = $txtSiteNameArr[0];
$user_data['txtSiteName'] = $txtSiteName;



$directories = array("Demo/app/webroot/img/products/",
    "Demo/app/webroot/img/csv/",
    "Demo/app/webroot/img/SiteLogo_disp.gif",
    "Demo/app/webroot/img/SiteLogo.jpg",
    "Demo/app/webroot/img/products/SiteLogo.png",
    "Demo/app/webroot/templates/atzshop/img/ads/",
    "Demo/app/webroot/templates/atzshop/img/banners/",
    "Demo/app/webroot/templates/linea/img/ads/",
    "Demo/app/webroot/templates/linea/img/banners/",
    "Demo/app/webroot/templates/rapidshop/img/ads/",
    "Demo/app/webroot/templates/rapidshop/img/banners/",
    "Demo/app/webroot/templates/autima/img/ads/",
    "Demo/app/webroot/templates/autima/img/banners/",
    "Demo/app/webroot/templates/ruger/img/ads/",
    "Demo/app/webroot/templates/ruger/img/banners/",
    "Demo/app/webroot/templates/vistacart/img/ads/",
    "Demo/app/webroot/templates/vistacart/img/banners/",
    "Demo/app/tmp/cache/",
    "Demo/app/tmp/cache/models/",
    "Demo/app/tmp/cache/persistent/",
    "Demo/app/tmp/",
    "Demo/app/tmp/logs/",
    "Demo/app/tmp/sessions/",
    "Demo/app/webroot/img/",
    "Demo/app/webroot/css/",
    "Demo/app/Controller/Component/pple.xml",
    "Demo/app/Config/database.php",
    "Demo/app/webroot/config.php",
    "files/",
    "config/config.php",
    "config/settings.php");


$serverSettings = ServerConfig::checkServerConfiguration();




$serverCurrentSettings = ServerConfig::getServerSettings();




$host_name = parse_url($_SERVER['HTTP_HOST']);






if (isset($_POST['installerCheck'])) {

    $post_flag = true;

//For Getting Folder Permission Status and Proper Error messages
	
	foreach ($directories as $dir) {
        $permission = ServerConfig::fileWritable($dir, 'project/'.$dir);
      
        
        //echo 'project/'.$dir.'=='.print_r($permission).'<br>';

        if (!$permission['status'] && $error == false) {
				$error = true; 
				$serverPermission="true";
	    
        }
        
    }

	
    if ($error == true) { 
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
    $user_data['txtTablePrefix'] = $txtTablePrefix;

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
     $connection = @mysqli_connect($user_data['txtDBServerName'],$user_data['txtDBUserName'], $user_data['txtDBPassword'],$user_data['txtDBName']);


    if (mysqli_connect_errno())
  {
        $error = true;
        $message .= " * Connection Not Successful! Please verify your database details!<br>".mysqli_connect_error();
  }else{

            @mysqli_query($connection, " SET @@global.sql_mode='' ");
            @mysqli_query($connection, " SET SESSION sql_mode='' ");
            @mysqli_query($connection,"SET NAMES 'utf8' COLLATE 'utf8_unicode_ci'"); 
  }

    //proceed with corresponding action
    if (!$error) {
        //update goStores config file
        $fp = fopen($configfile, "w+");
        $configcontent = "<?php\n";
        $configcontent .= "define('INSTALLED', true); \n\n";
        $configcontent .= "define('VERSION', '3.0'); \n\n";
        $configcontent .= "\n?>";
        fwrite($fp, $configcontent);

        //update vistacart db settings
        $default = '$default';
        $fpp = fopen($vistadbfile, "w+"); 
        $thisDefault = '$this->default';
                
                $defaultVar = '$default';
                
		$dbconfigcontent = "<?php\n";
		$dbconfigcontent .= "class DATABASE_CONFIG { \n\n";

		$dbconfigcontent .= "public $defaultVar = array();\n\n";
  
  		$dbconfigcontent .= "function __construct() {\n\n";
		$dbconfigcontent .= "$thisDefault = array( \n\n";
		$dbconfigcontent .= "'datasource' => 'Database/Mysql', \n\n";
		$dbconfigcontent .= "'persistent' => false,\n\n";
		$dbconfigcontent .= "'host' => '". $user_data['txtDBServerName'] ."',\n\n";
		$dbconfigcontent .= "'login' => '". $user_data['txtDBUserName'] ."',\n\n";
		$dbconfigcontent .= "'password' => '". $user_data['txtDBPassword'] ."',\n\n";
		$dbconfigcontent .= "'database' => '". $user_data['txtDBName'] ."',\n\n";
		$dbconfigcontent .= "'prefix' => '". $user_data['txtTablePrefix'].'Vista_'."' \n\n";
		$dbconfigcontent .= "); \n\n";
		$dbconfigcontent .= "}\n\n";
		$dbconfigcontent .= "}\n\n";
		$dbconfigcontent .= "\n?>";
        
        
        
        fwrite($fpp, $dbconfigcontent);
        
        
        
        
        

        //update wp connector

         $handle = fopen($wpconfigfile, "rb");
        $contents = fread($handle, filesize($wpconfigfile));
        fclose($handle);
        
        
        
         $configcontent1 = "<?php\n";
                $configcontent1 .= "define('INSTALLED', true); \n\n";
                $configcontent1 .= "define('VERSION', '3.0'); \n\n";
                $configcontent1 .= "\n?>";
        
         $fpp1 = fopen($vistaconfigfile, "w+");
          fwrite($fpp1, $configcontent1);
        
        $contents = str_replace('CON_DB_NAME', $user_data['txtDBName'], $contents);
        $contents = str_replace('CON_DB_USER', $user_data['txtDBUserName'], $contents);
        $contents = str_replace('CON_DB_PASS', $user_data['txtDBPassword'], $contents);
        $contents = str_replace('CON_DB_HOST', $user_data['txtDBServerName'], $contents);
        $contents = str_replace('CON_DB_PREFIX', $user_data['txtTablePrefix'] . 'Vista_wp_', $contents);
        
        $fp = fopen($wpconfigfile, 'w');
        fwrite($fp, $contents);
        fclose($fp);
      

        //update supportdesk connector

        $version_ck = phpversion();

        if($version_ck > '5.4'){
        $str_write = "<?php include_once('mysql2i.class.php');  \r\n$" . "glb_dbhost=\"" . $user_data['txtDBServerName'] . "\";\r\n";
        $str_write .= "$" . "glb_dbuser=\"" . $user_data['txtDBUserName'] . "\";\r\n";
        $str_write .= "$" . "glb_dbpass=\"" . $user_data['txtDBPassword']. "\";\r\n";
        $str_write .= "$" . "glb_dbname=\"" . $user_data['txtDBName'] . "\";\r\n $";
        $str_write .= "INSTALLED=\"true\";\r\n";
        $str_write .= "?>";
         } else {
          $str_write = "<?php \r\n$" . "glb_dbhost=\"" . $user_data['txtDBServerName'] . "\";\r\n";
        $str_write .= "$" . "glb_dbuser=\"" . $user_data['txtDBUserName'] . "\";\r\n";
        $str_write .= "$" . "glb_dbpass=\"" . $user_data['txtDBPassword'] . "\";\r\n";
        $str_write .= "$" . "glb_dbname=\"" . $user_data['txtDBName'] . "\";\r\n $";
        $str_write .= "INSTALLED=\"true\";\r\n";
        $str_write .= "?>";  
              } 

        $fp = fopen($supportconfigfile, "w");
        fputs($fp, $str_write);
        fclose($fp);

        //update goStores settings file
        $handle = fopen($settingsfile, "rb");
        $contents = fread($handle, filesize($settingsfile));
        fclose($handle);

        $contents = str_replace('DB_NAME', $user_data['txtDBName'], $contents);
        $contents = str_replace('USER_NAME', $user_data['txtDBUserName'], $contents);
        $contents = str_replace('DB_PASSWORD', $user_data['txtDBPassword'], $contents);
        $contents = str_replace('HOST_NAME', $user_data['txtDBServerName'], $contents);
        $contents = str_replace('DB_PREFIX', $user_data['txtTablePrefix'], $contents);
        $contents = str_replace('ADMIN_CONFIG_EMAIL', $user_data['txtAdminEmail'], $contents);
        $contents = str_replace('SITE_CONFIG_NAME', $user_data['txtSiteName'], $contents);
        $contents = str_replace('CONFIG_BASE_URL', $_SERVER['SERVER_NAME']."/".$path, $contents);
        $contents = str_replace('SALT_KEY', ServerConfig::rand_string(19).'_', $contents);

        $fp = fopen($settingsfile, 'w');
        fwrite($fp, $contents);
        fclose($fp);


        //------------------------UPDATE THE DB---------------------------------//
        $sqlquery = @fread(@fopen($schemafile, 'r'), @filesize($schemafile));
        $sqlquery = preg_replace('/goStores_/', $user_data['txtTablePrefix'], $sqlquery);
        $sqlquery = ServerConfig::splitsqlfile($sqlquery, ";");

        for ($i = 0; $i < sizeof($sqlquery); $i++) {
            mysqli_query($connection,$sqlquery[$i]);            
        }

        $dataquery = @fread(@fopen($datafile, 'r'), @filesize($datafile));
        $dataquery = preg_replace('/goStores_/', $user_data['txtTablePrefix'], $dataquery);
        $dataquery = ServerConfig::splitsqlfile($dataquery, ";");

        for ($i = 0; $i < sizeof($dataquery); $i++) {
            mysqli_query($connection,$dataquery[$i]);
        }        

        $sqlsettings1 = "UPDATE " . $user_data['txtTablePrefix'] . "Settings SET value = '" . addslashes($user_data['txtLicenseKey']) . "' WHERE settingfield = 'licenseKey'";
        mysqli_query($connection,$sqlsettings1) or die(mysqli_error($connection));
        
        $sqlsettings1 = "UPDATE " . $user_data['txtTablePrefix'] . "Settings SET value = '" . addslashes($user_data['txtSiteName']) . "' WHERE settingfield = 'siteName'";
       mysqli_query($connection,$sqlsettings1) or die(mysqli_error($connection));
        
        $sqlsettings1 = "UPDATE " . $user_data['txtTablePrefix'] . "Settings SET value = '" . addslashes($user_data['txtSiteName']) . "' WHERE settingfield = 'siteTitle'";
        mysqli_query($connection,$sqlsettings1) or die(mysqli_error($connection));
        
        $sqlsettings1 = "UPDATE " . $user_data['txtTablePrefix'] . "Settings SET value = '" . addslashes($user_data['txtAdminEmail']) . "' WHERE settingfield = 'adminEmail'";
       mysqli_query($connection,$sqlsettings1) or die(mysqli_error($connection));
        
        $sqlsettings1 = "UPDATE " . $user_data['txtTablePrefix'] . "Settings SET value = '" . addslashes($secure_root_url) . "' WHERE settingfield = 'secureURL'";
        mysqli_query($connection,$sqlsettings1) or die(mysqli_error($connection));

        //-------------------UPDATE VISTACART INITIAL CONFIG VALUES-----------------------//
        $adminusername = addslashes($user_data['txtAdminName']);
        $adminpassword = md5($user_data['txtAdminPassword']);
        $adminmailpword = $user_data['txtAdminPassword'];
        //$adminblogpword = $user_data['txtAdminPassword'] . '_' . time();

         $adminblogpword = 'admin';

        $txtSiteBaseFolderfull = dirname($_SERVER['PHP_SELF']);
        $findu = '/project';
        $len = strpos($txtSiteBaseFolderfull, $findu);
        $txtSiteBaseFolder = substr($txtSiteBaseFolderfull, 0, $len);

        include_once( "../Demo/app/webroot/blog/wp-includes/class-phpass.php" );
        $wp_hasher = new PasswordHash(8, TRUE);
        $hashed_wp_password = $wp_hasher->HashPassword($adminblogpword);

        $sqladminsettings = "UPDATE " . $user_data['txtTablePrefix'] . "Vista_admins SET admin_name = '" . $adminusername . "',admin_pword ='" . $adminpassword . "', blog_pword = '" . $adminblogpword . "', email = '" . addslashes($txtAdminEmail) . "' WHERE admin_name = 'admin'";
        mysqli_query($connection,$sqladminsettings) or die(mysqli_error($connection));

        $sqlsettings = "UPDATE " . $user_data['txtTablePrefix'] . "Vista_settings SET value = '" . addslashes($user_data['txtSiteName']) . "' WHERE fieldname ='site_name'";
        mysqli_query($connection,$sqlsettings) or die(mysqli_error($connection));

        $sqlsettings = "UPDATE " . $user_data['txtTablePrefix'] . "Vista_settings SET value = '" . addslashes($txtSiteBaseFolder) . "/project/Demo' WHERE fieldname ='sitebasefolder'";
        mysqli_query($connection,$sqlsettings) or die(mysqli_error($connection));

        $sqlsettings1 = "UPDATE " . $user_data['txtTablePrefix'] . "Vista_settings SET value = '" . addslashes($user_data['txtAdminEmail']) . "' WHERE fieldname ='admin_email'";
       mysqli_query($connection,$sqlsettings1) or die(mysqli_error($connection));

       // $sqlsettings1 = "UPDATE " . $user_data['txtTablePrefix'] . "Vista_wp_users SET user_pass = '" . addslashes($hashed_wp_password) . "', user_email = '" . addslashes($user_data['txtAdminEmail']) . "' WHERE ID = 1";
      //  mysqli_query($connection,$sqlsettings1) or die(mysqli_error($connection));

        $admin_link = BASE_URL . 'project/Demo/admins';
        $blog_link = BASE_URL . 'project/Demo/app/webroot/blog';


       //  $sqlsettings1 = "UPDATE " . $user_data['txtTablePrefix']  . "Vista_wp_add_custom_link SET href = '" . mysqli_real_escape_string($connection,$admin_link) . "' WHERE custom_link_id = 2";
       // @mysqli_query($connection,$sqlsettings1) or die(mysqli_error($connection));
        

     //   $sqlsettings1 = "UPDATE " . $user_data['txtTablePrefix']  . "Vista_wp_options SET option_value = '" . mysqli_real_escape_string($connection,$blog_link) . "' WHERE option_id IN(3, 39)";
     //   @mysqli_query($connection,$sqlsettings1) or die(mysqli_error($connection));
/*
    $sqlsettings1 = mysqli_query($connection,"SELECT option_value, option_id FROM " . $user_data['txtTablePrefix']  . "Vista_wp_options WHERE option_id IN(116, 268)");
        if($sqlsettings1){
            while($sqlres = mysqli_fetch_array($sqlsettings1,MYSQLI_BOTH)){
                $text_val = $sqlres['option_value'];
                $text_val = str_replace('http://localhost/vistacart', $txtSiteURL, $text_val);
                
                mysqli_query($connection,"UPDATE " . $user_data['txtTablePrefix']  . "Vista_wp_options SET option_value = '" . mysqli_real_escape_string($connection,$text_val) . "' WHERE option_id = " . $sqlres['option_id']);
            }
        }        
        */
       /* $sqlsettings1 = "UPDATE " . $user_data['txtTablePrefix']  . "Vista_wp_usermeta SET meta_key = '" . $txtTablePrefix . "wp_capabilities' WHERE umeta_id IN(10)";
        mysqli_query($connection,$sqlsettings1) or die(mysqli_error($connection));
        
        $sqlsettings1 = "UPDATE " . $user_data['txtTablePrefix']  . "Vista_wp_usermeta SET meta_key = '" . $txtTablePrefix . "wp_user_level' WHERE umeta_id IN(11)";
        mysqli_query($connection,$sqlsettings1) or die(mysqli_error($connection));
        
        $sqlsettings1 = "UPDATE " . $user_data['txtTablePrefix']  . "Vista_wp_options SET option_name = '" . $txtTablePrefix . "wp_user_roles' WHERE option_id IN(99)";
        mysqli_query($connection,$sqlsettings1) or die(mysqli_error($connection));*/


    /*    $wp_admin_menu_links = array('enabled' => 1, 'title' => 'Back To Main Store', 'title_link' => $admin_link, 'links' => array(), 'disabled_menus' => '');
        $sqlsettings1 = "UPDATE " . $user_data['txtTablePrefix']  . "Vista_wp_options SET option_value = '" . serialize($wp_admin_menu_links) . "' WHERE option_id IN(159)";
        mysqli_query($connection,$sqlsettings1) or die(mysqli_error($connection));
*/
        /*$sqlsettings2 = mysqli_query($connection,"SELECT ID, guid FROM " . $user_data['txtTablePrefix']  . "Vista_wp_posts");
        if($sqlsettings2){
            while($sqlres = mysqli_fetch_array($sqlsettings2)){
                $text_val = $sqlres['guid'];
                $text_val = str_replace('http://localhost/vistacart', BASE_URL, $text_val);

                mysqli_query($connection,"UPDATE " . $user_data['txtTablePrefix'] . "Vista_wp_posts SET guid = '" . addslashes($text_val) . "' WHERE ID = " . $sqlres['ID']);
            }
        }

        $sqlsettings1 = "UPDATE " . $user_data['txtTablePrefix'] . "Vista_wp_usermeta SET meta_key = '" . $user_data['txtTablePrefix'] . "Vista_wp_capabilities' WHERE umeta_id IN(10)";
        mysqli_query($connection,$sqlsettings1) or die(mysqli_error($connection));

        $sqlsettings1 = "UPDATE " . $user_data['txtTablePrefix'] . "Vista_wp_usermeta SET meta_key = '" . $user_data['txtTablePrefix'] . "Vista_wp_user_level' WHERE umeta_id IN(11)";
        mysqli_query($connection,$sqlsettings1) or die(mysqli_error($connection));

        $sqlsettings1 = "UPDATE " . $user_data['txtTablePrefix'] . "Vista_wp_usermeta SET meta_key = '" . $user_data['txtTablePrefix'] . "Vista_wp_dashboard_quick_press_last_post_id' WHERE umeta_id IN(14)";
        mysqli_query($connection,$sqlsettings1) or die(mysqli_error($connection));

        $sqlsettings1 = "UPDATE " . $user_data['txtTablePrefix'] . "Vista_wp_options SET option_name = '" . $user_data['txtTablePrefix'] . "Vista_wp_user_roles' WHERE option_id IN(92)";
        mysqli_query($connection,$sqlsettings1) or die(mysqli_error($connection));*/

        //-------------------UPDATE SUPPORTDESK INITIAL CONFIG VALUES-----------------------//
        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($user_data['txtSiteName']) . "' where vLookUpName = 'HelpdeskTitle'";
        mysqli_query($connection,$sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes(BASE_URL) . "support/'  where vLookUpName = 'SiteURL'";
        mysqli_query($connection,$sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes(BASE_URL) . "support/' where vLookUpName = 'HelpDeskURL'";
        mysqli_query($connection,$sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes(BASE_URL) . "support/' where vLookUpName = 'LoginURL'";
        mysqli_query($connection,$sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes(BASE_URL) . "support/' where vLookUpName = 'EmailURL'";
        mysqli_query($connection,$sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($txtAdminEmail) . "' where vLookUpName = 'MailAdmin'";
        mysqli_query($connection,$sql);

        //update in staff table ofr admin user
        $sql = "Update sptbl_staffs set vMail='" . addslashes($user_data['txtAdminEmail']) . "' where nStaffId = '1'";
        mysqli_query($connection,$sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($user_data['txtAdminEmail']) . "' where vLookUpName = 'MailTechnical'";
        mysqli_query($connection,$sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($user_data['txtAdminEmail']) . "' where vLookUpName='MailEscalation'";
        mysqli_query($connection,$sql);

        $sql = "Update sptbl_lookup set vLookUpValue='Administrator' where vLookUpName='MailFromName'";
        mysqli_query($connection,$sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($user_data['txtAdminEmail']) . "' where vLookUpName='MailFromMail'";
        mysqli_query($connection,$sql);

        $sql = "Update sptbl_lookup set vLookUpValue='Administrator' where vLookUpName='MailReplyName'";
        mysqli_query($connection,$sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($user_data['txtAdminEmail']) . "' where vLookUpName='MailReplyMail'";
        mysqli_query($connection,$sql);

        $sql = "Update sptbl_companies set vCompName = '" . addslashes($user_data['txtSiteName']) . "' WHERE nCompId = '1'";
        mysqli_query($connection,$sql);


        //set the upload path in the fckeditor config file
        $filename = "../support/FCKeditor/editor/filemanager/connectors/php/config.php";
        $handle = fopen($filename, "rb");
        $contents = fread($handle, filesize($filename));
        fclose($handle);

        unset($path_parts);
        $user_install = getcwd();
        if (strstr($user_install, 'public_html')) {
            $path_parts = explode('/public_html', $user_install);
            $user_install = '/public_html' . $path_parts[1];
        } elseif (strstr($user_install, 'httpdocs')) {
            $path_parts = explode('/httpdocs', $user_install);
            $user_install = '/httpdocs' . $path_parts[1];
        }

        $contents = str_replace('FCK_IMAGE_UPLOAD_DIRECTORY', "'/".$path."project/support/fckeditorimages/'", $contents);

        $fp = fopen($filename, 'w');
        fwrite($fp, $contents);
        fclose($fp);

        //update cms settings
        $cms_query_str = "SELECT id, table_name, section_config FROM cms_sections";
        $cms_query = mysqli_query($connection,$cms_query_str);
        if($cms_query){
            while($cms_res = mysqli_fetch_array($cms_query)){
                $tbl_name       = $cms_res['table_name'];
                $section_config = $cms_res['section_config'];
                $tbl_id         = $cms_res['id'];
                
                //if($tbl_name <> 'tbl_cms_settings'){
                    $tbl_name = str_replace('tbl_', $user_data['txtTablePrefix'], $tbl_name);
                    $section_config = str_replace('tbl_', $user_data['txtTablePrefix'], $section_config);
                    
                    mysqli_query($connection,"UPDATE cms_sections SET table_name = '" . mysqli_real_escape_string($connection,$tbl_name) . "', section_config = '" . mysqli_real_escape_string($connection,$section_config) . "' WHERE id = $tbl_id");
                //}
            }
        }
        
        //update theme path
        $theme_query_str = "SELECT * FROM " . $user_data['txtTablePrefix'] . "themes";
        $theme_query = mysqli_query($connection,$theme_query_str);
        if($theme_query){
            while($theme_res = mysqli_fetch_array($theme_query)){
                $img_path = $theme_res['theme_thumbnail'];
                $img_path = str_replace('http://localhost/gostores/', BASE_URL, $img_path);
                $tbl_id   = $theme_res['theme_id'];
                
                mysqli_query($connection,"UPDATE " . $user_data['txtTablePrefix'] . "themes SET theme_thumbnail = '" . mysqli_real_escape_string($connection,$img_path) . "' WHERE theme_id = $tbl_id");
            }
        }

        //installation tracker
        $rootserver = BASE_URL;

        $string = "";
        $pro = urlencode("GoStores 3.0");
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

        $mailcontent = "Hello , <br>";
        $mailcontent .= "Your Store is successfully installed.<br> <a href='" . BASE_URL . "' target='_blank'>Click Here to Access your Store</a>";
        $mailcontent .= "<br><a href='" . BASE_URL . "cms' target='_blank'>Click Here to Access your Store Administration Control Panel</a> <br>";
        $mailcontent .= "Your Admin Username   :  " . $user_data['txtAdminName'];
        $mailcontent .= "<br>Your Admin Password   :  " . $user_data['txtAdminPassword'];
        $mailcontent .= "<br> Thanks and regards,<br> " . $user_data['txtSiteName'] . " Team";

        /* Email Template */
        $email_temp_qry = "SELECT cms_desc FROM ".$user_data['txtTablePrefix']."Cms WHERE cms_name='email_template' ";
        $email_temp = mysqli_query($connection,$email_temp_qry);
        $mailMsgArr = mysqli_fetch_array($email_temp);
        $mailMsg = NULL;
        $date = date("Y/m/d");

        if(count($mailMsgArr) > 0) {
            $mailMsg = $mailMsgArr['cms_desc'];
        } // End If
        if(!empty($mailMsg)){
                $mailMsg = str_replace("{SITE_LOGO}", BASE_URL . "project/install/css/sitelogo.jpg", $mailMsg);
                $mailMsg = str_replace("{DATE}", $date, $mailMsg);
                $mailMsg = str_replace("{MAIL_CONTENT}", $mailcontent, $mailMsg);
            } else {
                $mailMsg =$mailcontent;
            }

        $mailcontent = $mailMsg;
        @mail(addslashes($user_data['txtAdminEmail']), $subject, $mailcontent, $headers);
    }
}
else{
    foreach ($directories as $dir) {
        $permission = ServerConfig::fileWritable($dir, 'project/'.$dir);
        if (!$permission['status'] && $error == false) {
				$error = true; 
				$serverPermission="true";
	    
        }
         if (!$permission['status'] ) {
                $error_message=$error_message.$permission['message'];
        }
        }
    }


if ($installed && !$error) {
    header("location: install_success.php");
    exit;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title>iScripts GoStores Installer</title>        
    </head>
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script type="text/javascript" src="../js/install.js"></script>
    <link href="css/install.css" rel="stylesheet" type="text/css" />
    <body class="bodyinstaller">
	<div class="header_row" >
            <div class="header_container sitewidth">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tr>
                        <td width="23%" align="left" ><img src="<?php echo BASE_URL; ?>project/install/css/sitelogo.jpg" alt="Logo"></td>
                        <td width="77%" align="right">
						<h4>iScripts GoStores Installer</h4>
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
						
                        </td>
                    </tr>
                </table>
				</div>
        </div>
    

        
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>

                <td width="76%" valign="top" height ="400">
                    <!-- Here's where I want my views to be displayed -->
                    <table width="80%" border="0" cellpadding="0" cellspacing="0" align="center">
                        <tr>
                            <td>
                                <!--------Installer starts------------------------->

                                <!--items display area start -->

                                
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
                                                        <?php if($error){?><u><b>Please correct the following errors to continue:</b></u><p/><?php }?>
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
                                                                            GoStores requires that some of the folders have write permission. You can provide an FTP login so that this process is done automatically.<br/><br/>
                                                                            For security reasons, it is best to create a separate FTP user account with access to the GoStores installation only and not the entire web server. Your host can assist you with this.
                                                                            If you have difficulties completing installation without these credentials, please click "I would provide permissions manually" to do it yourself.<br/><br/>
                                                                       <?php } ?>
                                                                    </b>
                                                                </td>
                                                            </tr>
                                                           <?php if ($serverPermission==true) { ?>
                                                                <tr>
                                                                    <td class=maintext align="left">FTP username</td>
                                                                    <td width="70%" align=left>
                                                                        <input name="FTPusername"  id="FTPusername" type="text" size="50" value="<?php echo htmlentities($user_data['FTPusername']); ?>"> <img src="css/Help.png" width="20" height="20" title="FTP Username of Root folder for changing appropriate file permission">								
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class=maintext align="left">FTP password</td>
                                                                    <td width="70%" align=left>
                                                                        <input name="FTPpassword"  id="FTPpassword" type="password" size="50" value="<?php echo htmlentities($user_data['FTPpassword']); ?>"> <img src="css/Help.png" width="20" height="20" title="FTP password of above FTP user">
                                                                    </td>
                                                                </tr>
                                                                <?php if ($serverPermission==true) { ?>
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
                                                                <td width="70%" align=left>
                                                                    <input type="text" name="txtDBServerName" id="txtDBServerName" value="<?php echo trim($user_data['txtDBServerName']) <> "" ? htmlentities($user_data['txtDBServerName']) : "localhost"; ?>" /> <img src="css/Help.png" width="20" height="20" title="Database server name,eg:localhost">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" class=maintext align="left">Database Name</td>
                                                                <td width="70%" align=left>
                                                                    <input name="txtDBName"  id="txtDBName" type="text"   class="textbox"  maxlength="100" size="50" value="<?php echo htmlentities($user_data['txtDBName']); ?>" > <img src="css/Help.png" width="20" height="20" title="Name of your Mysql Database">								
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" class=maintext align="left">Database User Name</td>
                                                                <td width="70%" align=left>
                                                                    <input name="txtDBUserName"  id="txtDBUserName" type="text" maxlength="100" size="50" value="<?php echo htmlentities($user_data['txtDBUserName']); ?>"> <img src="css/Help.png" width="20" height="20" title="Mysql Username">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" class=maintext align="left">Database Password</td>
                                                                <td width="70%" align=left>
                                                                    <input name="txtDBPassword"  id="txtDBPassword" type="text"  maxlength="100" size="50" value="<?php echo htmlentities($user_data['txtDBPassword']); ?>"> <img src="css/Help.png" width="20" height="20" title="Mysql Password">
                                                                </td>
                                                            </tr>
                                                            <!--
                                                            <tr>
                                                                <td colspan="2" class=maintext align="left">Table Prefix</td>
                                                                <td width="70%" align=left>
                                                                    <input name="txtTablePrefix"  id="txtTablePrefix" type="text"  maxlength="100" size="50" value="<?php echo trim($user_data['txtTablePrefix']) <> "" ? htmlentities($user_data['txtTablePrefix']) : "goStores_"; ?>">
                                                                </td>
                                                            </tr>
								-->
                                                        </table>
                                                    </FIELDSET><br><br>
                                                    <FIELDSET>
                                                        <LEGEND class='block_class'>Site Details</LEGEND>
                                                        <table width=85% border=0 cellpadding="2" cellspacing="2" class=maintext>
                                                            <tr>
                                                                <td colspan="2" class=maintext align="left">Site Name</td>
                                                                <td width="70%" align=left>
                                                                    <input name="txtSiteName"  id="txtSiteName" type="text" maxlength="100" size="50" value="<?php echo htmlentities($user_data['txtSiteName']); ?>"> <img src="css/Help.png" width="20" height="20" title="Official Sitename">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" class=maintext align="left">License Key</td>
                                                                <td width="70%" align=left>
                                                                    <input name="txtLicenseKey"  id="txtLicenseKey" type="text" maxlength="100" size="50" value="<?php echo htmlentities($user_data['txtLicenseKey']); ?>"> <img src="css/Help.png" width="20" height="20" title="License key provided  by iScripts.com">
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
                                                                <td width="70%" align=left>
                                                                    <input name="txtAdminName"  id="txtAdminName" type="hidden" maxlength="100" size="50" value="admin">

                                                                    <input name="txtAdminPassword"  id="txtAdminPassword" type="hidden" maxlength="100" size="50" value="admin">

                                                                    <input name="txtAdminEmail"  id="txtAdminEmail" type="text"  maxlength="100" size="50" value="<?php echo htmlentities($user_data['txtAdminEmail']); ?>"> <img src="css/Help.png" width="20" height="20" title="Email of Site Admin">
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </FIELDSET>
                                                    <br>
                                                    <table width=85% border=0 cellpadding="2" cellspacing="2" class=maintext>
                                                        
                                                        <tr>
                                                            <td align="center">
                                                                <input type="submit" name="btnContinue" value="Continue" class="buttn_admin">
                                                            </td>
                                                        </tr>
														<tr><td>&nbsp;</td></tr>
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
		 <div class="installr_footer">
		 	<div align="center" class="copyright"></div>
		 </div>
        
    </body>
</html>
                            
                            