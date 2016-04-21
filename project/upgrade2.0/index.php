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
$path_part[1] = str_replace('project/upgrade2.0/', '', $path_part[1]);

define('BASE_URL', ROOT_URL . $_SERVER['SERVER_NAME'] . $path_part[1]);

$perm_flag = true;
$perm_msg = '';
$error_message = '';
$error = false;
$installed = false;
$user_data = array();
$post_flag = false;

$configfile = "../config/config.php";
$settingsfile = "../config/settings.php";
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

                    $user_install = str_replace('upgrade2.0', '', getcwd());

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
    
    $prefix_parts = explode('demo_', $sqlObj->default['prefix']);
    $sqlPrefix  = $prefix_parts[0];

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
        $configcontent .= "define('VERSION', '2.0'); \n\n";
        $configcontent .= "\n?>";
        fwrite($fp, $configcontent);

        //update vistacart db settings
        $default = '$default';
        $fpp = fopen($vistadbfile, "w+");
        $dbconfigcontent = "<?php\n";
        $dbconfigcontent .= "class DATABASE_CONFIG { \n\n";
        $dbconfigcontent .= "var $default = array( \n\n";
        $dbconfigcontent .= "'driver' => 'mysql', \n\n";
        $dbconfigcontent .= "'persistent' => false,\n\n";
        $dbconfigcontent .= "'host' => '" . $sqlHost . "',\n\n";
        $dbconfigcontent .= "'login' => '" . $sqlUser . "',\n\n";
        $dbconfigcontent .= "'password' => '" . $sqlPass . "',\n\n";
        $dbconfigcontent .= "'database' => '" . $sqlDB . "',\n\n";
        $dbconfigcontent .= "'prefix' => '" . $sqlPrefix . "demo_', \n\n";
        $dbconfigcontent .= "); \n\n";
        $dbconfigcontent .= "}\n\n";
        $dbconfigcontent .= "\n?>";
        fwrite($fpp, $dbconfigcontent);

        //update wp connector
        $handle = fopen($wpconfigfile, "rb");
        $contents = fread($handle, filesize($wpconfigfile));
        fclose($handle);

        $contents = str_replace('CON_DB_NAME', $sqlDB, $contents);
        $contents = str_replace('CON_DB_USER', $sqlUser, $contents);
        $contents = str_replace('CON_DB_PASS', $sqlPass, $contents);
        $contents = str_replace('CON_DB_HOST', $sqlHost, $contents);
        $contents = str_replace('CON_DB_PREFIX', $sqlPrefix . 'demo_wp_', $contents);

        $fp = fopen($wpconfigfile, 'w');
        fwrite($fp, $contents);
        fclose($fp);

        //update supportdesk connector
        $str_write = "<?php \r\n$" . "glb_dbhost=\"" . $sqlHost . "\";\r\n";
        $str_write .= "$" . "glb_dbuser=\"" . $sqlUser . "\";\r\n";
        $str_write .= "$" . "glb_dbpass=\"" . $sqlPass . "\";\r\n";
        $str_write .= "$" . "glb_dbname=\"" . $sqlDB . "\";\r\n $";
        $str_write .= "INSTALLED=\"true\";\r\n";
        $str_write .= "\n?>";

        $fp = fopen($supportconfigfile, "w");
        fputs($fp, $str_write);
        fclose($fp);        

        //------------------------UPDATE THE DB [ VISTACART ]--------------------------------//
        $sqlquery = @fread(@fopen($vista_schemafile, 'r'), @filesize($vista_schemafile));
        $sqlquery = preg_replace('/Vista_/', $sqlPrefix  . 'demo_', $sqlquery);
        $sqlquery = ServerConfig::splitsqlfile($sqlquery, ";");

        for ($i = 0; $i < sizeof($sqlquery); $i++) {
            mysql_query($sqlquery[$i], $connection);
        }

        $dataquery = @fread(@fopen($vista_datafile, 'r'), @filesize($vista_datafile));
        $dataquery = preg_replace('/Vista_/', $sqlPrefix . 'demo_', $dataquery);
        $dataquery = ServerConfig::splitsqlfile($dataquery, ";");

        for ($i = 0; $i < sizeof($dataquery); $i++) {
            mysql_query($dataquery[$i], $connection);
        }

        //-------------------UPDATE VISTACART INITIAL CONFIG VALUES-----------------------//
        $adminblogpword = 'admin_' . time();
        
        $txtSiteBaseFolderfull = dirname($_SERVER['PHP_SELF']);
        $findu = '/project';
        $len = strpos($txtSiteBaseFolderfull, $findu);
        $txtSiteBaseFolder = substr($txtSiteBaseFolderfull, 0, $len);
        
        include_once( "../Demo/app/webroot/blog/wp-includes/class-phpass.php" );
        $wp_hasher = new PasswordHash( 8, TRUE );
        $hashed_wp_password = $wp_hasher->HashPassword( $adminblogpword );
        
        $admin_email = '';
        $sqlsettings1 = mysql_query("SELECT value, fieldname FROM " . $sqlPrefix . "demo_settings WHERE fieldname IN ('admin_email', 'site_name')");
        if($sqlsettings1){
            while($sqlres = mysql_fetch_array($sqlsettings1)){
                if($sqlres['fieldname'] == 'admin_email'){
                    $admin_email = $sqlres['value'];
                }
                else{
                    $site_name = $sqlres['value'];
                }
            }
        }
        
        //update goStores settings file
        $handle = fopen($settingsfile, "rb");
        $contents = fread($handle, filesize($settingsfile));
        fclose($handle);

        $contents = str_replace('DB_NAME', $sqlDB, $contents);
        $contents = str_replace('USER_NAME', $sqlUser, $contents);
        $contents = str_replace('DB_PASSWORD', $sqlPass, $contents);
        $contents = str_replace('HOST_NAME', $sqlHost, $contents);
        $contents = str_replace('DB_PREFIX', $sqlPrefix, $contents);
        $contents = str_replace('ADMIN_CONFIG_EMAIL', $admin_email, $contents);
        $contents = str_replace('SITE_CONFIG_NAME', $site_name, $contents);
        $contents = str_replace('CONFIG_BASE_URL', BASE_URL, $contents);
        $contents = str_replace('SALT_KEY', ServerConfig::rand_string(19).'_', $contents);

        $fp = fopen($settingsfile, 'w');
        fwrite($fp, $contents);
        fclose($fp);
        
        $sqlsettings = "UPDATE " . $sqlPrefix . "demo_settings SET value = '" . addslashes($txtSiteBaseFolder) . "/project/Demo' WHERE fieldname ='sitebasefolder'";
        mysql_query($sqlsettings) or die(mysql_error());
        
        $sqladminsettings = "UPDATE " . $sqlPrefix . "demo_admins SET blog_pword = '" . $adminblogpword . "' WHERE admin_name = 'admin'";
        mysql_query($sqladminsettings) or die(mysql_error());
        
        $sqlsettings1 = "UPDATE " . $sqlPrefix . "demo_wp_users SET user_pass = '" . addslashes($hashed_wp_password) . "', user_email = '" . addslashes($admin_email) . "' WHERE ID = 1";
        mysql_query($sqlsettings1) or die(mysql_error());
        
        $admin_link = BASE_URL . '/project/Demo/admins';
        $blog_link = BASE_URL . '/project/Demo/app/webroot/blog';

        $sqlsettings1 = "UPDATE " . $sqlPrefix . "demo_wp_add_custom_link SET href = '" . addslashes($admin_link) . "' WHERE custom_link_id = 2";
        mysql_query($sqlsettings1) or die(mysql_error());
        
        $sqlsettings1 = "UPDATE " . $sqlPrefix . "demo_wp_options SET option_value = '" . addslashes($blog_link) . "' WHERE option_id IN(3, 39)";
        mysql_query($sqlsettings1) or die(mysql_error());
        
        $sqlsettings1 = mysql_query("SELECT option_value, option_id FROM " . $sqlPrefix . "demo_wp_options WHERE option_id IN(116, 268)");
        if($sqlsettings1){
            while($sqlres = mysql_fetch_array($sqlsettings1)){
                $text_val = $sqlres['option_value'];
                $text_val = str_replace('http://localhost/vistacart', BASE_URL, $text_val);
                
                mysql_query("UPDATE " . $sqlPrefix . "demo_wp_options SET option_value = '" . addslashes($text_val) . "' WHERE option_id = " . $sqlres['option_id']);
            }
        }
        
        $sqlsettings1 = "UPDATE " . $sqlPrefix . "demo_wp_usermeta SET meta_key = '" . $sqlPrefix . "demo_wp_capabilities' WHERE umeta_id IN(10)";
        mysql_query($sqlsettings1) or die(mysql_error());
        
        $sqlsettings1 = "UPDATE " . $sqlPrefix . "demo_wp_usermeta SET meta_key = '" . $sqlPrefix . "demo_wp_user_level' WHERE umeta_id IN(11)";
        mysql_query($sqlsettings1) or die(mysql_error());
        
        $sqlsettings1 = "UPDATE " . $sqlPrefix . "demo_wp_options SET option_name = '" . $sqlPrefix . "demo_wp_user_roles' WHERE option_id IN(99)";
        mysql_query($sqlsettings1) or die(mysql_error());


        //------------------------UPDATE SUPPORTDESK INITIAL CONFIG VALUES-----------------------//
        $sptbl_array = array("actionlog",
                             "attachments",
                             "categories",
                             "companies",
                             "css",
                             "depts",
                             "downloads",
                             "feedback",
                             "fields",
                             "files",
                             "kb",
                             "labels",
                             "lang",
                             "news",
                             "personalnotes",
                             "pop3settings",
                             "priorities",
                             "pvtmessages",
                             "reminders",
                             "replies",
                             "rules",
                             "spam_categories",
                             "spam_references",
                             "spam_tickets",
                             "spam_wordfreqs",
                             "staffdept",
                             "stafffields",
                             "staffratings",
                             "staffs",
                             "support_users",
                             "temp_tickets",
                             "tickets",
                             "xtrastatus");
        
        foreach($sptbl_array as $sptbl){
            $sql = "RENAME TABLE " . $sqlPrefix . $sptbl . " TO sptbl_" . $sptbl . "";
            mysql_query($sql);
        }
        
        $sql = "RENAME TABLE sptbl_support_users TO sptbl_users";
        mysql_query($sql);     
        
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
        
        //-------------IMPORT LOOKUP TABLE-----------------//
        $sql = "INSERT INTO sptbl_lookup SELECT * FROM " . $sqlPrefix . "lookup";
        mysql_query($sql);

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

        $contents = str_replace('FCK_IMAGE_UPLOAD_DIRECTORY', $user_install . 'project/support/fckeditorimages/', $contents);

        $fp = fopen($filename, 'w');
        fwrite($fp, $contents);
        fclose($fp);
        
        //------------------------------REMOVE UNWANTED TABLES--------------------------------//
        $spamtbl_array = array("cart",
                             "combo",
                             "domaincart",
                             "gallery",
                             "gallery_category",
                             "generate_id",
                             "gen_links",
                             "user_mast",
                             "tempsite_pages",
                             "tempsite_mast",
                             "template_category",
                             "template_mast",
                             "templates",
                             "site_mast",
                             "site_pages",
                             "tempdomains",
                             "logo",
                             "payment",
                             "pending_plan",
                             "files",
                             "support_css",
                             "support_categories",
                             "service_types",
                             "support_plans",
                             "support_plan_rate_features");

        foreach($spamtbl_array as $spamtbl){
            $sql = "DROP TABLE " . $sqlPrefix . $spamtbl;
            mysql_query($sql);
        }
        
        $sql = "RENAME TABLE " . $sqlPrefix . "affiliates TO " . $sqlPrefix . "Affiliates";
        mysql_query($sql);
        
        $sql = "RENAME TABLE " . $sqlPrefix . "aff_ref_txns TO " . $sqlPrefix . "AffRefTxns";
        mysql_query($sql);
        
        $sql = "RENAME TABLE " . $sqlPrefix . "server_info TO " . $sqlPrefix . "ServerInfo";
        mysql_query($sql);        
        $sql = "ALTER TABLE " . $sqlPrefix . "server_info ADD `whmip` VARCHAR( 25 ) NOT NULL";
        mysql_query($sql);
        
        $sql = "RENAME TABLE " . $sqlPrefix . "general TO " . $sqlPrefix . "temp_general";
        mysql_query($sql);  
        
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
        
        //need to check if previous gostores versions had any encryption
        $sql = "INSERT INTO " . $sqlPrefix . "general( `nGId` ,`nUserId` ,`vFirstName` ,`vLastName` ,`vNumber` ,
                `vCode` ,`vMonth` ,`vYear` ,`vAddress` ,`vCity` ,`vState` ,`vZipcode` ,`vCountry` ,`vEmail` ,`vUserIp` ) 
                SELECT ngen_id, nuser_id, vfirst_name, vlast_name, vnumber, vcode, vmonth, vyear, vaddress, 
                vcity, vstate, vpostal_code, vcountry, vemail, customer_ip FROM " . $sqlPrefix . "temp_general";
        mysql_query($sql);
        $sql = "DROP TABLE " . $sqlPrefix . "temp_general";
        mysql_query($sql);
        
        $sql = "INSERT INTO " . $sqlPrefix . "ServiceFeatures(tFeatureName, tValue) SELECT sf_name, sf_status FROM " . $sqlPrefix . "support_features";
        mysql_query($sql);  
        $sql = "DROP TABLE " . $sqlPrefix . "support_features";
        mysql_query($sql);
        
        $sql = "INSERT INTO " . $sqlPrefix . "ProductServices(nServiceId, vServiceName, vServiceDescription, nSCatId, nPId, price, nQty, vType, vBillingInterval, nBillingDuration, nStatus) 
                SELECT nrate_id, vrate_desc AS vname, vrate_desc AS vdesc, 1 AS ncatid, 1 AS nPId, nrate_value, 1 AS nqty, 'Paid' AS vtype, CASE WHEN vbilling_interval = 'F' THEN 'L' WHEN vbilling_interval = 'A' THEN 'Y' ELSE 'M' END AS vinterval,
                CASE WHEN vbilling_interval = 'F' THEN 1 WHEN vbilling_interval = 'A' THEN nduration ELSE nduration END AS nduration, IF(nrate_status = 'N', 1, 0) AS nstatus FROM " . $sqlPrefix . "support_plan_rates";
        mysql_query($sql); 
        $sql = "DROP TABLE " . $sqlPrefix . "support_plan_rates";
        mysql_query($sql);
        
        $planFeatureArray = array();
        $planFeatureQry = mysql_query("SELECT * FROM " . $sqlPrefix . "ServiceFeatures");
        if($planFeatureQry){
            while($planFeatureRes = mysql_fetch_array($planFeatureQry)){
                $planFeatureArray[$planFeatureRes['nFeatureId']]['feature'] = $planFeatureRes['nFeatureId'];
                $planFeatureArray[$planFeatureRes['nFeatureId']]['value'] = $planFeatureRes['tValue'];
            }
        }
        
        $serviceQry = mysql_query("SELECT nServiceId FROM " . $sqlPrefix . "support_features");
        if($serviceQry){
            while($serviceRes = mysql_fetch_array($serviceQry)){
                if(!empty($planFeatureArray)){
                    foreach($planFeatureArray as $feature){
                        mysql_query("INSERT INTO " . $sqlPrefix . "ProductServiceFeatures(nProductServiceId, nServiceFeatureId, vFeatureValue) VALUES('" . $serviceRes['nServiceId'] . "', '" . $feature['feature'] . "', '" . $feature['value'] . "')");
                    }
                }
            }
        }
        
        $sql = "INSERT INTO " . $sqlPrefix . "User( `nUId` ,`vUsername` ,`vPassword` ,`vFirstName` ,`vLastName` ,
                `vEmail` ,`vInvoiceEmail` ,`vAddress` ,`vCountry` ,`vState` ,`vCity` ,`vZipcode` ,`vPhoneNumber` ,`vFax` ,`nStatus`, `nAffId`, `vAffType`, `nRefId` ) 
                SELECT nuser_id, vuser_name, vpassword, vname, vlastname, vemail, vemail AS invemail, taddress, vcountry, 
                vstate, vcity, vzip, vphone1, vfax, vdel_status, naff_id, vaff_type, nref_id FROM " . $sqlPrefix . "users";
        mysql_query($sql);
        $sql = "DROP TABLE '" . $sqlPrefix . "users'";
        mysql_query($sql);
        
        $sql = "INSERT INTO " . $sqlPrefix . "BillingSettlement( `nUId` ,`nRequestedAmount` ,`tAdminComments` ,`eStatus` ) 
                SELECT nuser_id, damount, tnote, CASE WHEN vstatus = 1 THEN 'Approved' WHEN vstatus = 0 THEN 'Pending' ELSE 'Rejected' END AS settle_status FROM " . $sqlPrefix . "useraccounts";
        mysql_query($sql);
        $sql = "DROP TABLE '" . $sqlPrefix . "useraccounts'";
        mysql_query($sql);
        
        
        //invoice & domains -import
        $domainQry = "SELECT D.ndomain_id, D.nuser_id, D.vdomain_name, D.vdomain_password, D.vreg_email, D.vcapanel_user, D.dend_date, D.vcapanel_password, D.vserver_location, U.vUsername FROM " . $sqlPrefix . "domains D LEFT JOIN " . $sqlPrefix . "User U ON D.nuser_id = U.nUId";
        if(mysql_query($domainQry)){
            while($domainRes = mysql_fetch_array($domainQry)){
                $domain_info = array('user_name' => $domainRes['vUsername'],
                                     'user_email' => $domainRes['vreg_email'],
                                     'store_name' => $domainRes['vdomain_name'],
                                     'userpassw' => $domainRes['vdomain_password'],
                                     'userPhone' => '',
                                     'userCountry' => '',
                                     'c_user' => $domainRes['vcapanel_user'],
                                     'c_pass' => $domainRes['vcapanel_password'],
                                     'c_host' => $domainRes['vserver_location']);
                
                $domain_info_serialized = serialize($domain_info);
                
                //set status based on current time & db saved time
                $domain_status = time() > strtotime($domainRes['dend_date']) ? '0' : '1';
                
                //check if domain or subdomain
                $isDomainOrSub = mysql_query("SELECT * FROM " . $sqlPrefix . "subdomain WHERE name = '" . $domainRes['vdomain_name'] . "'");
                if(mysql_num_rows($isDomainOrSub) == 0){
                    mysql_query("INSERT INTO " . $sqlPrefix . "ProductLookup(nPLId, nUId, nPId, vDomain, nDomainStatus, nStatus, dPlanExpiryDate, vAccountDetails) 
                             VALUES('" . $domainRes['ndomain_id'] . "', '" . $domainRes['nuser_id'] . "', '1', '" . $domainRes['vdomain_name'] . "', '1', '" . $domain_status . "', '" . $domainRes['dend_date'] . "', '" . $domain_info_serialized . "')");
                }
                else{
                    mysql_query("INSERT INTO " . $sqlPrefix . "ProductLookup(nPLId, nUId, nPId, vSubDomain, nSubDomainStatus, nStatus, dPlanExpiryDate, vAccountDetails) 
                             VALUES('" . $domainRes['ndomain_id'] . "', '" . $domainRes['nuser_id'] . "', '1', '" . $domainRes['vdomain_name'] . "', '1', '" . $domain_status . "', '" . $domainRes['dend_date'] . "', '" . $domain_info_serialized . "')");
                }
            }
        }
        
        $sql = "INSERT INTO " . $sqlPrefix . "Invoice( `nInvId` ,`vInvNo` ,`nUId` ,`nPLId`, `dGeneratedDate`, `dDueDate`, `nAmount`, `nDiscount`, `nTotal`, `vCouponNumber`, `vTerms`, `vNotes`, `dPayment` ) 
                SELECT INV.ninv_id, INV.vinv_no, INV.nuser_id, INP.ndomain_id, INV.dgenerated, INV.ddue, INV.namt, INV.ndiscount, INV.ntotal, INV.vcoupon_id, INV.vterms, INV.vnotes, INV.dpayment FROM " . $sqlPrefix . "invoice INV LEFT JOIN " . $sqlPrefix . "invoice_plan INP ON INV.ninv_id = INP.ninv_id";
        mysql_query($sql);
        
        $sql = "INSERT INTO " . $sqlPrefix . "InvoicePlan( `nIPId` ,`nUId` ,`nInvId` ,`nAmount`, `nAmtNext`, `vBillingInterval`, `nBillingDuration`, `vType`, `nDiscount`, `dDateStart`, `dDateStop` ) 
                SELECT INP.ninv_detail_id, INV.nuser_id, INP.ninv_id, INP.namt_month, INP.namt_month_after_disc, CASE WHEN INP.vbilling_interval = 'F' THEN 'L' WHEN INP.vbilling_interval = 'A' THEN 'Y' ELSE 'M' END AS billinginterval, INP.nduration, CASE WHEN INP.vbilling_interval = 'one time' ELSE 'recurring' END AS billingtype, INP.namt_disc, INP.ddate_start, INP.ddate_stop FROM " . $sqlPrefix . "invoice_plan INP LEFT JOIN " . $sqlPrefix . "invoice INV ON INV.ninv_id = INP.ninv_id";
        mysql_query($sql);
        
        $sql = "DROP TABLE " . $sqlPrefix . "invoice";
        mysql_query($sql);
        $sql = "DROP TABLE " . $sqlPrefix . "invoice_plan";
        mysql_query($sql);
        $sql = "DROP TABLE " . $sqlPrefix . "domains";
        mysql_query($sql);
        $sql = "DROP TABLE " . $sqlPrefix . "subdomain";
        mysql_query($sql);
        
        
        //supportmain - import
        $sql = "INSERT INTO " . $sqlPrefix . "BillingMain( `nBmId` ,`nUId` ,`vInvNo` ,`vDomain`, `nDiscount`, `nAmount`, `vBillingInterval`, `nBillingDuration`, `dDateStart`, `dDateStop`, `vType` ) 
                SELECT SM.nss_id, SM.nuser_id, SM.vinv_no, DN.vdomain_name, SM.ndiscount, SM.namt, CASE WHEN SM.vbilling_interval = 'F' THEN 'L' WHEN SM.vbilling_interval = 'A' THEN 'Y' ELSE 'M' END AS billinginterval, SM.nduration, SM.ddate_start, SM.ddate_stop, 'recurring' AS vtype FROM " . $sqlPrefix . "support_main INV LEFT JOIN " . $sqlPrefix . "domains DN ON DN.ndomain_id = SM.ndomain_id";
        mysql_query($sql);
        $sql = "DROP TABLE " . $sqlPrefix . "support_main";
        mysql_query($sql);
        
        
        //lookup table - port to settings table
        $lookup_sql = "SELECT * FROM " . $sqlPrefix . "lookup WHERE nLookUpId IN (129, 130, 131, 132, 133, 136, 142, 143, 144, 147, 150, 151, 152, 155, 156, 158, 159)";
        if(mysql_query($lookup_sql)){
            while($lookup_res = mysql_fetch_array($lookup_sql)){
                switch($lookup_res['nLookUpId']){
                    case '129' :
                        mysql_query("UPDATE " . $sqlPrefix . "Settings SET value = '" . $lookup_res['vLookUpValue'] . "' WHERE settingfield = 'secureURL'");
                        break;
                    case '130' :
                        mysql_query("UPDATE '" . $sqlPrefix . "Settings' SET value = '" . $lookup_res['vLookUpValue'] . "' WHERE settingfield = 'siteName' OR settingfield = 'siteTitle'");
                        break;
                    case '131' :
                        mysql_query("UPDATE " . $sqlPrefix . "Settings SET value = 'ENOM' WHERE settingfield = 'domain_registrar'");
                        mysql_query("UPDATE " . $sqlPrefix . "Settings SET value = '" . $lookup_res['vLookUpValue'][0] . "' WHERE settingfield = 'enom_testmode'"); //its yes/no in previous version, get the 1st char
                        break;
                    case '132' :
                        mysql_query("UPDATE " . $sqlPrefix . "Settings SET value = '" . $lookup_res['vLookUpValue'] . "' WHERE settingfield = 'enom_user'");
                        break;
                    case '133' :
                        mysql_query("UPDATE " . $sqlPrefix . "Settings SET value = '" . $lookup_res['vLookUpValue'] . "' WHERE settingfield = 'enom_password'");
                        break;
                    case '136' :
                        mysql_query("UPDATE " . $sqlPrefix . "Settings SET value = '" . $lookup_res['vLookUpValue'] . "' WHERE settingfield = 'name_server_4'");
                        break;
                    case '142' :
                        mysql_query("UPDATE " . $sqlPrefix . "Settings SET value = '" . $lookup_res['vLookUpValue'] . "' WHERE settingfield = 'name_server_1'");
                        break;
                    case '143' :
                        mysql_query("UPDATE " . $sqlPrefix . "Settings SET value = '" . $lookup_res['vLookUpValue'] . "' WHERE settingfield = 'name_server_2'");
                        break;
                    case '144' :
                        mysql_query("UPDATE " . $sqlPrefix . "Settings SET value = '" . $lookup_res['vLookUpValue'] . "' WHERE settingfield = 'name_server_3'");
                        break;                    
                    case '150' :
                        mysql_query("UPDATE " . $sqlPrefix . "Settings SET value = '" . $lookup_res['vLookUpValue'][0] . "' WHERE settingfield = 'enablepaypal'");
                        break;
                    case '151' :
                        mysql_query("UPDATE " . $sqlPrefix . "Settings SET value = '" . $lookup_res['vLookUpValue'][0] . "' WHERE settingfield = 'enablepaypalsandbox'");
                        break;
                    case '152' :
                        mysql_query("UPDATE " . $sqlPrefix . "Settings SET value = '" . $lookup_res['vLookUpValue'] . "' WHERE settingfield = 'paypalemail'");
                        break;
                    case '147' :
                        $enable_disable = $lookup_res['vLookUpValue'] == 'AN' ? 'Y' : 'N';
                        mysql_query("UPDATE " . $sqlPrefix . "Settings SET value = '" . $enable_disable . "' WHERE settingfield = 'authorize_enable'");
                        break;
                    case '155' :
                        mysql_query("UPDATE " . $sqlPrefix . "Settings SET value = '" . $lookup_res['vLookUpValue'][0] . "' WHERE settingfield = 'authorize_test_mode'");
                        break;
                    case '156' :
                        mysql_query("UPDATE " . $sqlPrefix . "Settings SET value = '" . $lookup_res['vLookUpValue'] . "' WHERE settingfield = 'authorize_email'");
                        break;
                    case '158' :
                        mysql_query("UPDATE " . $sqlPrefix . "Settings SET value = '" . $lookup_res['vLookUpValue'] . "' WHERE settingfield = 'authorize_transkey'");
                        break;
                    case '159' :
                        mysql_query("UPDATE " . $sqlPrefix . "Settings SET value = '" . $lookup_res['vLookUpValue'] . "' WHERE settingfield = 'authorize_loginid'");
                        break;
                }
            }
        }        
        $sql = "DROP TABLE " . $sqlPrefix . "lookup";
        mysql_query($sql);
        
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
        $theme_query_str = "SELECT * FROM " . $user_data['txtTablePrefix'] . "themes";
        $theme_query = mysql_query($theme_query_str);
        if($theme_query){
            while($theme_res = mysql_fetch_array($theme_query)){
                $img_path = $theme_res['theme_thumbnail'];
                $img_path = str_replace('http://localhost/gostores', BASE_URL, $img_path);
                $tbl_id   = $theme_res['theme_id'];
                
                mysql_query("UPDATE " . $user_data['txtTablePrefix'] . "themes SET theme_thumbnail = '" . mysql_real_escape_string($img_path) . "' WHERE theme_id = $tbl_id");
            }
        }
        
        
        //installation tracker
        $rootserver = BASE_URL;

        $string = "";
        $pro = urlencode("GoStores 2.0");
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
        $mailcontent = $mcont . "Hello , <br>";
        $mailcontent .= "Your Store is successfully installed.<br> <a href='" . BASE_URL . "' target='_blank'>Click Here to Access your Store</a>";
        $mailcontent .= "<br><a href='" . BASE_URL . "cms' target='_blank'>Click Here to Access your Store Administration Control Panel</a> <br>";
        $mailcontent .= "Your Admin Username   :  admin";
        $mailcontent .= "<br>Your Admin Password   :  admin";
        
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
                $mailMsg = str_replace("Header", '', $mailMsg);
                $mailMsg = str_replace("Footer", $footer, $mailMsg);
                $mailMsg = str_replace("{mailtemplate}", $mailcontent, $mailMsg);
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