<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: 			 */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2008 Armia Systems, Inc                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts SupportDesk                    |
// +----------------------------------------------------------------------+
// | Authors: simi<simi@armia.com>             		                      |
// |          										                      |
// +----------------------------------------------------------------------+
session_start();

function ResizeImageTogivenWitdhAndHeight($file, $img_height, $img_width, $tosavefileas = null) {
    if (!isset($tosavefileas)) {
        $tosavefileas = $file;
    }

    $img_temp = NewimageCreatefromtype($file);
    $black = @imagecolorallocate($img_temp, 0, 0, 0);
    $white = @imagecolorallocate($img_temp, 255, 255, 255);
    $font = 2;
    $cuurentimagewidth = @imagesx($img_temp);
    $cuurentimageheight = @imagesy($img_temp);

    list($originalwidth, $originalheight, $originaltype) = getimagesize($file);
    if ($originaltype == "1") { // gif
        $newwidth = $img_width;
        $newheight = $img_height;
        $tpcolor = imagecolorat($img_temp, 0, 0);
        // in the real world, you'd better test all four corners, not just one!
        $img_thumb = imagecreate($newwidth, $newheight);
        // $dest automatically has a black fill...
        imagepalettecopy($img_thumb, $img_temp);
        imagecopyresized($img_thumb, $img_temp, 0, 0, 0, 0, $newwidth, $newheight, @imagesx($img_temp), @imagesy($img_temp));
        $pixel_over_black = imagecolorat($img_thumb, 0, 0);
        // ...but now make the fill white...
        $bg = imagecolorallocate($img_thumb, 255, 255, 255);
        imagefilledrectangle($img_thumb, 0, 0, $newwidth, $newheight, $bg);
        imagecopyresized($img_thumb, $img_temp, 0, 0, 0, 0, $newwidth, $newheight, @imagesx($img_temp), @imagesy($img_temp));
        $pixel_over_white = imagecolorat($img_thumb, 0, 0);
        // ...to test if transparency causes the fill color to show through:
        if ($pixel_over_black != $pixel_over_white) {
            // Background IS transparent
            imagefilledrectangle($img_thumb, 0, 0, $newwidth, $newheight, $tpcolor);
            imagecopyresized($img_thumb, $img_temp, 0, 0, 0, 0, $newwidth, $newheight, @imagesx($img_temp), @imagesy($img_temp));
            imagecolortransparent($img_thumb, $tpcolor);
            imagegif($img_thumb, $tosavefileas);
        } else // Background (most probably) NOT transparent
            imagegif($img_thumb, $tosavefileas);
    } else {
        $img_thumb = @imagecreatetruecolor($img_width, $img_height);
        @imagecopyresampled($img_thumb, $img_temp, 0, 0, 0, 0, $img_width, $img_height, @imagesx($img_temp), @imagesy($img_temp));
        ReturnImagetype($img_thumb, $tosavefileas, $file);
    }
}

function ReturnImagetype($newImage, $newfile, $editimagefile) {
    list($width, $height, $type, $attr) = @getimagesize($editimagefile);
    $jpgCompression = "90";
    if ($type == "1") { // gif
        $returnimage = @imagegif($newImage, $newfile);
    } else if ($type == "2") { // jpeg
        $returnimage = @imagejpeg($newImage, $newfile, $jpgCompression);
        ;
    } else if ($type == "3") { // png
        $returnimage = @imagepng($newImage, $newfile);
    } else {
        $returnimage = "Not Supported";
    }
    return $returnimage;
}

function NewimageCreatefromtype($image) {
    list($width, $height, $type, $attr) = @getimagesize($image);
    if ($type == "1") { // gif
        $returnimage = @imagecreatefromgif($image);
    } else if ($type == "2") { // jpeg
        $returnimage = @imagecreatefromjpeg($image);
    } else if ($type == "3") { // png
        $returnimage = @imagecreatefrompng($image);
    } else {
        $returnimage = "Not Supported";
    }
    return $returnimage;
}

function gdVersion($user_ver = 0) {
    if (!extension_loaded('gd')) {
        return;
    }
    static $gd_ver = 0;
    // Just accept the specified setting if it's 1.
    if ($user_ver == 1) {
        $gd_ver = 1;
        return 1;
    }
    // Use the static variable if function was called previously.
    if ($user_ver != 2 && $gd_ver > 0) {
        return $gd_ver;
    }
    // Use the gd_info() function if possible.
    if (function_exists('gd_info')) {
        $ver_info = gd_info();
        preg_match('/\d/', $ver_info['GD Version'], $match);
        $gd_ver = $match[0];
        return $match[0];
    }
    // If phpinfo() is disabled use a specified / fail-safe choice...
    if (preg_match('/phpinfo/', ini_get('disable_functions'))) {
        if ($user_ver == 2) {
            $gd_ver = 2;
            return 2;
        } else {
            $gd_ver = 1;
            return 1;
        }
    }
    // ...otherwise use phpinfo().
    ob_start();
    phpinfo(8);
    $info = ob_get_contents();
    ob_end_clean();
    $info = stristr($info, 'gd version');
    preg_match('/\d/', $info, $match);
    $gd_ver = $match[0];
    return $match[0];
}

// End gdVersion()

function isValidUsername($str) {
    if (trim($str) != "") {
        if (preg_match("[^0-9a-zA-Z+_]", $str)) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}

function stripslashes_deep($value) {
    $value = is_array($value) ?
            array_map('stripslashes_deep', $value) :
            stripslashes($value);
    return $value;
}

function isValidEmail($email) {
    $email = trim($email);
    if ($email == "")
        return false;
    if (!preg_match("/^" . "[a-z0-9]+([_\\.-][a-z0-9]+)*" . "@" . "([a-z0-9]+([\.-][a-z0-9]+)*)+" . "\\.[a-z]{2,}" . "$/", $email, $regs)
    ) {
        return false;
    } else {
        return true;
    }
}

function isNotNull($value) {
    if (is_array($value)) {
        if (sizeof($value) > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
            return true;
        } else {
            return false;
        }
    }
}

function isValidWebImageType($mimetype, $filename, $tempname) {
    $blacklist = array("php", "phtml", "php3", "php4", "js", "shtml", "pl", "py", "exe");
    foreach ($blacklist as $file) {
        if (preg_match("/\.$file\$/i", "$filename")) {
            return false;
        }
    }
    //check if its image file
    if (!getimagesize($tempname)) {
        return false;
    }

    if (($mimetype == "image/pjpeg") || ($mimetype == "image/jpeg") || ($mimetype == "image/x-png") || ($mimetype == "image/png") || ($mimetype == "image/gif") ||
            ($mimetype == "image/x-windows-bmp") || ($mimetype == "image/bmp")) {
        return true;
    } else {
        return false;
    }
}

function getFilePermission($file) {
    $perm = fileperms($file);
    if ($perm === false) {
        return "0000";
    } else {
        return substr(sprintf('%o', $perm), -4);
    }
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


$configfile = "../config/settings.php";

$configcontents = @fread(@fopen($configfile, 'r'), @filesize($configfile));
$pos = strpos($configcontents, "INSTALLED");
if ($pos === false) {
    ;
} else {
    header("location:../index.php");
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
/* * ********************************check server configuration **************************************************** */
$gdv = gdVersion();
$val1 = true;
$val3 = ini_get("file_uploads");
$val4 = ini_get("open_basedir");

$gdrequiredarray = array();

array_push($gdrequiredarray, "FreeType Support");
array_push($gdrequiredarray, "GIF Read Support");
array_push($gdrequiredarray, "GIF Create Support");
array_push($gdrequiredarray, "JPG Support");
array_push($gdrequiredarray, "PNG Support");
array_push($gdrequiredarray, "FreeType Linkage");


$gdvsupport = true;
$errmess_gdsupport = "";
if ($gdv) {
    $gdvsupportarray = gd_info();
    $z = 0;

    foreach ($gdvsupportarray as $key => $value) {
        if (in_array($key, $gdrequiredarray)) {
            if ($gdvsupportarray[$key]) {
                ;
            }//end if
            else {
                $gdvsupport = false;
                $errmess_gdsupport .= $key . " required,";
            }//end else
        }//end if
    }//end foreach
}//end if

if ((!empty($val1) || $val1 == 1) or (empty($val3) || $val3 != 1) or !$gdv or !$gdvsupport) {
    $serverconfiguration = "FAILURE";
}//end if
else {
    $serverconfiguration = "OK";
}//end else

/* * *********Server configuration check ends here**************** */

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

                $user_install = str_replace('/install', '', getcwd());

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

/* ----------------------------------------------------------------------------- */
umask(0);

$passed['files'] = true;

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
        $message .= " * Change the permission of 'api/useradd.php' file to 777 <br>";
    }//end if

    if (!file_writable($cls_file)) {
        $error = true;
        $message .= " * Change the permission of 'api/server_class.php' file to 777 <br>";
    }//end if

    if (!file_writable($settings_file)) {
        $error = true;
        $message .= " * Change the permission of 'config/settings.php' file to 777 <br>";
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

$error_message = '';
$installed = false;
if ($_POST["btnContinue"] == "Continue") {

    $txtLicenseKey = $_POST["txtLicenseKey"];

    $txtDBServerName = $_POST["txtDBServerName"];
    $txtDBName = $_POST["txtDBName"];
    $txtDBUserName = $_POST["txtDBUserName"];
    $txtDBPassword = $_POST["txtDBPassword"];

    $txtSiteName = $_POST["txtSiteName"];
    $txtAdminEmail = $_POST["txtAdminEmail"];

    //if not able to give permission automatically, give permission manually

    $logofile = $_FILES['userfile'];
    $logofilename = $_FILES['userfile']['name'];
    $logofiletype = $_FILES['userfile']['type'];
    $logotempname = $_FILES['userfile']['tmp_name'];
    
    //safeguard against rogue filenames
    if($logofilename){
        $logo_parts = explode('.', $logofilename);
        $logoimagedest = '../custom/logo_' .time() . '.' . end($logo_parts);
    }

    if (!function_exists('gd_info')) {
        $error_message .= " * Your PHP version does not support GD! Please recompile PHP with GD support to continue!" . "<br>";
        $error = true;
    }//end if
    if (!isNotNull($txtLicenseKey)) {
        $error_message .= " * License key is empty!" . "<br>";
        $error = true;
    }//end if

    if (!isNotNull($txtDBServerName)) {
        $error_message .= " * Database Server Name is empty!" . "<br>";
        $error = true;
    }//end if

    if (!isNotNull($txtDBName)) {
        $error_message .= " * Database Name is empty!" . "<br>";
        $error = true;
    }//end if

    if (!isNotNull($txtDBUserName)) {
        $error_message .= " * Database User Name is empty!" . "<br>";
        $error = true;
    }//end if

    if (!isNotNull($txtSiteName)) {
        $error_message .= " * Site Name is empty!" . "<br>";
        $error = true;
    }//end if

    if (!isNotNull($txtSiteURL)) {
        $error_message .= " * Site URL is empty!" . "<br>";
        $error = true;
    }//end if

    if ($logofiletype != "") {
        if (!isValidWebImageType($logofiletype, $logofilename, $logotempname)) {
            $error_message .= " * Invalid Logo file ! Upload an image (jpg/gif/bmp/png)" . "<br>";
            $error = true;
        }//end if
        else {
            if (file_exists($logoimagedest)) {
                @rename('../custom' . $logofilename, '../custom' . 'old_' . $logofilename);
            }//end if
        }//end else
    }//end if  

    if (!isNotNull($txtAdminEmail)) {
        $error_message .= " * Admin Email is empty!" . "<br>";
        $error = true;
    }//end if
    else {
        if (!isValidEmail($txtAdminEmail)) {
            $error_message .= " * Invalid Admin Email!" . "<br>";
            $error = true;
        }//end if
    }//end else

    $connection = @mysql_connect($txtDBServerName, $txtDBUserName, $txtDBPassword);
    if ($connection === false) {
        $error = true;
        $error_message .= " * Connection Not Successful! Please verify your database details!<br>";
    }//end if
    else {
        $dbselected = @mysql_select_db($txtDBName, $connection);
        if (!$dbselected) {
            $error = true;
            $error_message .= " * Database could not be selected! Please verify your database details!<br>";
        }//end if
    }//end else

//    if (ini_get('safe_mode')) {//safe_mode is on
//        $error = true;
//        $error_message .= " * The script requires PHP with safe mode Off to work properly. Installation cannot continue! <br>";
//    }//end if

    if ($error) {
        $error_message = "<b>Please correct the following errors to continue:</b>" . "<br/>" . $error_message . '<br/>' . $message;
        // echo $message;
    }//end if
    else {

        if ($conn = @mysql_connect($txtDBServerName, $txtDBUserName, $txtDBPassword)) {
            if (mysql_select_db($txtDBName, $conn)) {
                $fp2 = fopen("./includes/config.sql", "r");
                while (!feof($fp2)) {
                    $buffer = fgets($fp2, 4096);
                    mysql_query($buffer, $conn);
                }
                fclose($fp2);
                $page_flag = 1;
            }
        }

        //uploading logo, watermark files
        if ($logofilename == "") {
            @copy("../images/logoo.gif", "../custom/logoo.gif");
            $logoimagedest = "../custom/logoo.gif";
        }//end if
        else {
            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $logoimagedest)) {
                chmod($logoimagedest, 0777);
                list($originalwidth, $originalheight, $originaltype) = getimagesize($logoimagedest);
                if ($originalwidth <= 411 and $originalheight <= 100) {
                    ;
                }//end if
                else {
                    $resizedimage = $logoimagedest;
                    if ($originalwidth >= 411) {
                        $imagewidth = 411;
                    }//end if
                    else {
                        $imagewidth = $originalwidth;
                    }//end else
                    if ($originalheight >= 100) {
                        $imageheight = 100;
                    }//end if
                    else {
                        $imageheight = $originalheight;
                    }//end else
                    ResizeImageTogivenWitdhAndHeight($logoimagedest, $imageheight, $imagewidth, $resizedimage);
                }//end else
            }//end if
        }//end else

        $file_name = explode('../', $logoimagedest);
        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($file_name[1]) . "' where vLookUpName = 'Logourl'";
        mysql_query($sql);

        $str_write = "<?php \r\n$" . "glb_dbhost=\"" . $txtDBServerName . "\";\r\n";
        $str_write .= "$" . "glb_dbuser=\"" . $txtDBUserName . "\";\r\n";
        $str_write .= "$" . "glb_dbpass=\"" . $txtDBPassword . "\";\r\n";
        $str_write .= "$" . "glb_dbname=\"" . $txtDBName . "\";\r\n";

        $fp = fopen("../config/settings.php", "w");
        fputs($fp, "$str_write");
        fclose($fp);

        $filename = "../api/useradd.php";
        $handle = fopen($filename, "rb");
        $contents = fread($handle, filesize($filename));
        fclose($handle);

        $str_write = "<?php \r\n$" . "glb_dbhost_1=\"" . $txtDBServerName . "\";\r\n";
        $str_write .= "$" . "glb_dbuser_1=\"" . $txtDBUserName . "\";\r\n";
        $str_write .= "$" . "glb_dbpass_1=\"" . $txtDBPassword . "\";\r\n";
        $str_write .= "$" . "glb_dbname_1=\"" . $txtDBName . "\";\r\n ";
        $str_write .= $contents;

        $fp = fopen("../api/useradd.php", "w");
        fputs($fp, "$str_write");
        fclose($fp);

        $str_write = "\r\n" . "function xml_server() {\r\n";
        $str_write .= "$" . "this->users_tag = \"\";\r\n";
        $str_write .= "$" . "this->function_tag = \"\";\r\n";
        $str_write .= "$" . "this->values_tag = \"\";\r\n";
        $str_write .= "$" . "this->username_tag = \"\";\r\n";
        $str_write .= "$" . "this->password_tag = \"\";\r\n";
        $str_write .= "$" . "this->email_tag = \"\";\r\n";
        $str_write .= "$" . "this->company_tag = \"\";\r\n";
        $str_write .= "$" . "this->errno = \"0\";\r\n";
        $str_write .= "$" . "this->conapi = mysql_connect(\"" . $txtDBServerName . "\",\"" . $txtDBUserName . "\",\"" . $txtDBPassword . "\") or die(mysql_error());\r\n";
        $str_write .= "mysql_select_db(\"" . $txtDBName . "\",$" . "this->conapi) or die(mysql_error());\r\n}\r\n}\r\n?>";

        $fp = fopen("../api/server_class.php", "a+");
        fputs($fp, "$str_write");
        fclose($fp);
        
        //set the upload path in the fckeditor config file
        $filename = "../FCKeditor/editor/filemanager/connectors/php/config.php";
        $handle = fopen($filename, "rb");
        $contents = fread($handle, filesize($filename));
        fclose($handle);
        
        $user_install = str_replace('/install', '', getcwd());
        $path_parts = explode('/public_html', $user_install);
        $user_install = $path_parts[1];
        
        $contents = str_replace('FCK_IMAGE_UPLOAD_DIRECTORY', $user_install.'/fckeditorimages/', $contents);
        
        $fp = fopen($filename, 'w');
        fwrite($fp, $contents);
        fclose($fp);

        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($txtSiteName) . "' where vLookUpName = 'HelpdeskTitle'";
        mysql_query($sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($txtLicenseKey) . "' where vLookUpName='vLicenceKey'";
        mysql_query($sql, $conn);

        //an additional / added
        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($_POST["txtSiteURL"]) . "/'  where vLookUpName = 'SiteURL'";
        mysql_query($sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($_POST["txtSiteURL"]) . "/' where vLookUpName = 'HelpDeskURL'";
        mysql_query($sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($_POST["txtSiteURL"]) . "/' where vLookUpName = 'LoginURL'";
        mysql_query($sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($_POST["cmbLang"]) . "' where vLookUpName='DefaultLang'";
        mysql_query($sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . (($_POST["rdChoice"] == "1") ? "1" : "0") . "' where vLookUpName='LangChoice'";
        mysql_query($sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($_POST["rdLock"]) . "' where vLookUpName='AutoLock'";
        mysql_query($sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($_POST["rdTemplate"]) . "' where vLookUpName='VerifyTemplate'";
        mysql_query($sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($_POST["rdKb"]) . "' where vLookUpName='VerifyKB'";
        mysql_query($sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($_POST["txtSiteURL"]) . "/' where vLookUpName = 'EmailURL'";
        mysql_query($sql);

        $sql = "Delete from sptbl_companies";
        mysql_query($sql);

        $sql = "Insert into sptbl_companies(nCompId,vCompName,vCompAddress1,vCompCity,nCompZip,vCompCountry,";
        $sql .= "vCompMail) Values('','" . addslashes(trim($_SERVER['HTTP_HOST'])) . "',
					'" . addslashes(trim($_SERVER['HTTP_HOST'])) . "','" . addslashes("city") . "',
					'11111','UnitedStates','company1@yoursite.com')";
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

        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($txtAdminEmail) . "' where vLookUpName = 'MailAdmin'";
        mysql_query($sql);

        //update in staff table ofr admin user
        $sql = "Update sptbl_staffs set vMail='" . addslashes($txtAdminEmail) . "' where nStaffId = '1'";
        mysql_query($sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($txtAdminEmail) . "' where vLookUpName = 'MailTechnical'";
        mysql_query($sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($txtAdminEmail) . "' where vLookUpName='MailEscalation'";
        mysql_query($sql);

        $sql = "Update sptbl_lookup set vLookUpValue='Administrator' where vLookUpName='MailFromName'";
        mysql_query($sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($txtAdminEmail) . "' where vLookUpName='MailFromMail'";
        mysql_query($sql);

        $sql = "Update sptbl_lookup set vLookUpValue='Administrator' where vLookUpName='MailReplyName'";
        mysql_query($sql);

        $sql = "Update sptbl_lookup set vLookUpValue='" . addslashes($txtAdminEmail) . "' where vLookUpName='MailReplyMail'";
        mysql_query($sql);

        $settingcontent = "";
        $fp = fopen("../config/settings.php", "r");
        while ($strcontent = fgets($fp)) {
            $settingcontent .=$strcontent;
        }
        fclose($fp);
        $settingcontent = str_replace("<?php", "", $settingcontent);
        $settingcontent = str_replace("?>", "", $settingcontent);

        $str_write1 = "<?php\r\n " . $settingcontent . "\r\n $" . "INSTALLED=\"true\";\r\n ?>";
        $fp = fopen("../config/settings.php", "w");
        fputs($fp, "$str_write1");
        fclose($fp);

        /* -------------- /
          New code for install tracker
          /------------------ */
        $documentroot = $_SERVER['DOCUMENT_ROOT'];
        $realpath = realpath("../");
        $replacedpath = str_replace($documentroot, "", $realpath);
        $rootserver = "http://" . $_SERVER['SERVER_NAME'] . $replacedpath;

        $string = "";
        $pro = urlencode("SupportDesk 4.2");
        $dom = urlencode($rootserver);
        $ipv = urlencode($_SERVER['REMOTE_ADDR']);
        $mai = urlencode($txtAdminEmail);
        $string = "pro=$pro&dom=$dom&ipv=$ipv&mai=$mai";
        $contents = "no";
        $file = @fopen("http://www.iscripts.com/installtracker.php?$string", 'r');
        if ($file) {
            $contents = @fread($file, 8192);
        }
        /* -------------- /
          New code for install tracker
          /------------------ */


        $installed = true;
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title>iScripts SupportDesk Installation</title>
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
            function validate(){
                if(document.frmLogin.txtUserName.value=="" || document.frmLogin.txtPassword.value==""){
                    alert("Username and password are required!");
                }else{
                    frmLogin.submit();
                }
            }

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
    </head>
    <body>
        <div class="headerContainer">
            <div class="headerContent siteWidth">
                <div class="headerLeft"><img alt="Logo" src="style/installer_logo.png" ></div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="installer_menu_row">
            <div class="installer_menu siteWidth">
                <a title="OnlineInstallationManual" href="javascript:void(0)" onClick="window.open('<?php echo htmlentities($txtSiteURL); ?>/docs/supportdesk.pdf','OnlineInstallationManual','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd');">Installation Manual</a>&nbsp;&bull;&nbsp;
                <a title="Readme" href="javascript:void(0)" onClick="window.open('<?php echo htmlentities($txtSiteURL); ?>/Readme.txt','Readme','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd');">Readme</a>&nbsp;&bull;&nbsp;
                <a title="If you have any difficulty, submit a ticket to the support department" href="http://www.iscripts.com/support/postticketbeforeregister.php" target="_blank">Get Support</a>
            </div>
        </div>

        <div class="mainContentContainer">
            <div class="mainContentarea siteWidth">
                <div class="install_head" align="center">
                    Welcome to iScripts SupportDesk Installation
                </div>

                <!-- Loops this section for each item -->
                <div class="installer_section">
                    <?php
                    if (!$installed) {
                        ?>
                        <div class="comm_div">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="left" valign="top" width="220">
                                        <img alt="Installer_logo" src="style/installer_pack.png" >
                                    </td>
                                    <td align="left" valign="top" class="installer_intro_text">
                                        <h4>Thank you for choosing iScripts SupportDesk.
                                            <br>
                                            In order to complete this installation, please fill out the details requested below.
                                        </h4>
                                        <p>
                                            <b>Please note the following points before you continue:</b>
                                            <br/><br/>
                                            <?php
                                            echo "After the install process delete the 'install' folder and its contents.</b>";
                                            ?>
                                        </p>
                                        <p>
                                            <?php
                                            echo $error_message;
                                            echo $perm_msg;
                                            ?>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-------------------------- Preinstallation check------------------------------------------>
                        <div class="install_section_head">
                            Pre-Installation Check
                        </div>
                        <div class="comm_div">
                            <div class="installer_section_desc">
                                If any of these items is not supported (marked in <b style="color:red">Red</b>) then please take actions to correct them.
                                Failure to do so could lead to your SupportDesk installation not functioning correctly.
                            </div>
                            <div class="installer_section_inputs">
                                <table width="100%" border="0" cellpadding="5" cellspacing="1">
                                    <?php
                                    $ivo = "<span class='install_value_ok'>";
                                    $ivf = "<span class='install_value_fail'>";
                                    $sc = "</span>";
                                    echo "<tr><td class=maintext><b>iScripts SupportDesk Installer</b><br><br></td></tr>";


                                    echo "<tr><td class=maintext>Checking PHP Version... " . $ivo . PHP_VERSION . $sc . " ";
                                    if (version_compare(PHP_VERSION, "4.3.0") >= 0)
                                        echo $ivo . "(ok)" . $sc; else {
                                        echo $ivf . "(4.2.0 or higher required)" . $sc;
                                        $fatal = true;
                                    }
                                    echo "</td></tr>";

                                    echo "<tr><td class=maintext>Checking System Information... " . $ivo . PHP_OS . $sc . "</td></tr>";

                                    echo "<tr><td class=maintext>Checking PHP Server API... " . $ivo . php_sapi_name() . $sc . "</td></tr>";

                                    echo "<tr><td class=maintext>Checking Path to 'php.ini'... " . $ivo . PHP_CONFIG_FILE_PATH . $sc . "</td></tr>";

                                    echo "<tr><td class=maintext>Checking PHP GD extension... ";
                                    $gdv = gdVersion();
                                    echo $ivo . (( $gdv) ? "On" : " $ivf This program requires PHP GD extension. Please recompile your PHP with GD Support.") . $sc;
                                    echo "</td></tr>";
                                    if ($errmess_gdsupport != "") {
                                        echo "<tr><td class=maintext align=left>Checking currently installed GD library ... ";
                                        echo $ivo . (($gdvsupport) ? "On" : " $ivf $errmess_gdsupport.") . $sc;
                                        echo "</td></tr>";
                                    }

//                                    echo "<tr><td class=maintext>Checking safe_mode... ";
//                                    $val1 = ini_get("safe_mode");
//                                    echo ((!empty($val1) || $val1 == 1) ? $ivf . "On-Please turn off safe_mode in the php.ini" : $ivo . "Off") . $sc;
//                                    echo "</td></tr>";

                                    echo "<tr><td class=maintext>Checking file_uploads...";
                                    $val3 = ini_get("file_uploads");
                                    echo ((!empty($val3) || $val3 == 1) ? $ivo . "On" : $ivf . "Off - Please turn on file_uplaods in the php.ini file") . $sc;
                                    echo "</td></tr>";

                                    if ((!empty($val1) || $val1 == 1) or (empty($val3) || $val3 != 1) or !$gdv) {
                                        echo "<tr><td class=maintext>" . $ivf . "Fatal errors detected.  Please correct the above red items and reload.</td></tr>";
                                    }
                                    ?>
                                </table>

                            </div>
                            <div class="clear"></div>
                        </div>

                        <!---------------------------------------------------------------------------------------------------------->

                        <form name="frmInstall" method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" enctype="multipart/form-data">

                            <div class="install_section_head">
                                License Key
                            </div>

                            <div class="comm_div">
                                <div class="installer_section_desc">
                                    <p>The script would function only for the domain it is licensed. If you cannot recall the license, its also included in the email you received with subject: iScripts.com software download link. You can also get the license key from your user panel at <a href="http://www.iscripts.com" target="_blank">www.iscripts.com</a>.</p>
                                </div>
                                <div class="installer_section_inputs">
                                    <table border="0" class='installer_table'>
                                        <tr>
                                            <td align="left" valign="top" width="25%">License Key</td>
                                            <td align="left" valign="top">
                                                <input name="txtLicenseKey"  id="txtLicenseKey" type="text" class="textbox" size="50" maxlength="40" value="<?php echo htmlentities($txtLicenseKey); ?>" />
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="clear"></div>
                            </div>

                            <!---------------- get FTP details from user ----------------------->
                            <div class="install_section_head">
                                File Permissions
                            </div>

                            <div class="comm_div">
                                <div class="installer_section_desc">
                                    <?php if ($chmodstatus == '777') { ?>
                                        <p>SupportDesk requires that some of the folders have write permission. You can provide an FTP login so that this process is done automatically.<br/><br/>
                                            For security reasons, it is best to create a separate FTP user account with access to the SupportDesk installation only and not the entire web server. Your host can assist you with this.
                                            If you have difficulties completing installation without these credentials, please click "I would provide permissions manually" to do it yourself.
                                        </p>
                                    <?php } elseif ($chmodstatus == '000') { ?>
                                        <p>SupportDesk requires that some of the folders have write permission. Please provide write permission for the following files :-</p>
                                    <?php } elseif ($chmodstatus == '755') { ?>
                                        <p>SupportDesk requires that some of the folders have write permission.</p>
                                    <?php } ?>
                                </div>
                                <div class="installer_section_inputs">
                                    <?php if ($write == 'UNWRITABLE') { ?>
                                        <table border="0" class='installer_table'>
                                            <tr>
                                                <td width="25%" align="left">FTP username :</td>
                                                <td align="left"><input name="FTPusername"  type="text" class="textbox" size="50" value="<?php echo htmlentities($txtFTPusername); ?>"/></td>
                                            </tr>
                                            <tr>
                                                <td width="25%" align="left">FTP password :</td>
                                                <td align="left"><input name="FTPpassword"  type="password" class="textbox" size="50" value="<?php echo htmlentities($txtFTPpassword); ?>"/></td>
                                            </tr>
                                            <?php if ($chmodstatus == '777') { ?>
                                                <tr>
                                                    <td colspan="2" align="left">
                                                        <input type="checkbox" name="auto_set" id="auto_set" onclick="divToggle(this)" /> &nbsp; I would provide permissions manually
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                        <div id="err_div" <?php if ($chmodstatus == '777') { ?>style="display:none"<?php } ?>>
                                            <fieldset>
                                                <legend>Directories/Files List</legend>
                                                <p><?php echo $message; ?></p>
                                            </fieldset>
                                        </div>
                                    <?php } else { ?>
                                        <p><b>File permissions are OK.</b></p>
                                        <p>&nbsp</p>
                                        <p>&nbsp</p>
                                        <p>&nbsp</p>
                                        <p>&nbsp</p>
                                    <?php } ?>
                                </div>
                                <div class="clear"></div>
                            </div>

                            <div class="install_section_head">
                                Database Details
                            </div>

                            <div class="comm_div">
                                <div class="installer_section_desc">
                                    <p>SupportDesk stores all of its data in a database. This screen gives the installation program the information needed to connect to this database.The database you install into should already exist.</p>
                                </div>
                                <div class="installer_section_inputs">
                                    <table border="0" class='installer_table'>
                                        <tr>
                                            <td colspan="2" class=maintext width="25%">
                                                Database Server Hostname
                                            </td>
                                            <td align=left>
                                                <input name="txtDBServerName"  id="txtDBServerName" type="text"   class="textbox" size="50" maxlength="100" value="<?php echo htmlentities($txtDBServerName); ?>">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" class=maintext >
                                                Database Name
                                            </td>
                                            <td align=left>
                                                <input name="txtDBName"  id="txtDBName" type="text"   class="textbox" size="50" maxlength="30" value="<?php echo htmlentities($txtDBName); ?>" >
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class=maintext >
                                                Database User Name
                                            </td>
                                            <td align=left>
                                                <input name="txtDBUserName"  id="txtDBUserName" type="text"   class="textbox" size="50" maxlength="30" value="<?php echo htmlentities($txtDBUserName); ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class=maintext>
                                                Database Password
                                            </td>
                                            <td align=left>
                                                <input name="txtDBPassword"  id="txtDBPassword" type="text"   class="textbox" size="50" maxlength="30" value="<?php echo htmlentities($txtDBPassword); ?>">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="clear"></div>
                            </div>

                            <div class="install_section_head">
                                Site Details
                            </div>

                            <div class="comm_div">
                                <div class="installer_section_desc">
                                    <p>Details for basic operation of the site.</p>
                                </div>
                                <div class="installer_section_inputs">
                                    <table border="0" class='installer_table'>
                                        <tr>
                                            <td class=maintext width="25%">
                                                Site Name
                                            </td>
                                            <td align=left colspan="2">
                                                <input name="txtSiteName"  id="txtSiteName" type="text"   class="textbox" size="50" maxlength="100" value="<?php echo htmlentities($txtSiteName); ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class=maintext>
                                                Site URL
                                            </td>
                                            <td align=left colspan="2">
                                                <input name="txtSiteURL"  id="txtSiteURL" type="text"   class="textbox" size="50" maxlength="100" value="<?php echo htmlentities($txtSiteURL); ?>" readonly ><!-- &nbsp;&nbsp;<font color="RED">**</font>No Trailing slash (Ex: http://www.iscriptsimagegallery.com) -->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="top" width="25%">Site Logo</td>
                                            <td align="left" valign="top" width="45%">
                                                <input type="file" name="userfile"  class="textbox">
                                                <br/>
                                                <label style="color:red; font-size: 10px">NOTE :- Optimal Size 275x50</label>
                                            </td>
                                            <td align="left" valign="top">
                                                <img src="../images/logoo.gif" width="210" height="33" name="image">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class=maintext >
                                                Admin Email
                                            </td>
                                            <td align=left colspan="2">
                                                <input name="txtAdminEmail"  id="txtAdminEmail" type="text"   class="textbox" size="50" maxlength="100" value="<?php echo htmlentities($txtAdminEmail); ?>"><input name="cmbLang"  id="cmbLang" type="hidden" value="en">
                                                <input name="rdChoice"  id="rdChoice" type="hidden" value="1">
                                                <input name="rdLock"  id="rdLock" type="hidden" value="1">
                                                <input name="rdTemplate"  id="rdTemplate" type="hidden" value="1">
                                                <input name="rdKb"  id="rdKb" type="hidden" value="1">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="clear"></div>
                            </div>


                            <input type="hidden" name="btnContinue" value="Continue" />
                            <div class="installer_btn_section">
                                <img alt="Submit_button" src="style/installer_button.png" border="0" onclick="document.frmInstall.submit();" >
                            </div>

                        </form>
                        <?php
                    } else {
                        ?>
                        <div class="comm_div">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="left" valign="top" width="220">
                                        <img alt="Installer_logo" src="style/installer_pack.png" >
                                    </td>
                                    <td align="left" valign="top" class="installer_intro_text">
                                        <h4><b>Congratulations! The Installation Process is completed successfully!</b>
                                            <br>
                                        </h4>
                                        <p>
                                            <b>Please note the following points before you continue:</b><br>    
                                            <br>
                                            To ensure complete security, now <br>
                                            1)You should remove the 'install' directory.<br>
                                            2)Change the permission of config/settings.php to 644    
                                        </p>
                                        <p>
                                            <strong>Admin URL </strong>
                                            <br/>
                                            <a href="<?php echo $txtSiteURL . '/admin/index.php'; ?>" target="_blank">Login</a> to the admin panel and change the settings to suit yours. Your username is <b>admin</b> and password is <b>admin</b>.
                                            <br/>
                                            <a href="<?php echo $txtSiteURL . '/admin/index.php'; ?>" target="_blank"><img src="style/homebut.jpg" border="0" height="25"></a>
                                            <br/>
                                            <a href="<?php echo $txtSiteURL . '/admin/index.php'; ?>" target="_blank"><?php echo $txtSiteURL . '/admin/index.php'; ?></a>
                                        </p>
                                        <p>
                                            <strong>Home URL </strong>
                                            <br/>
                                            <a href="<?php echo $txtSiteURL . '/index.php'; ?>" target="_blank"><img src="style/homebut.jpg" border="0" height="25"></a>
                                            <br/>
                                            <a href="<?php echo $txtSiteURL . '/index.php'; ?>" target="_blank"><?php echo $txtSiteURL . '/index.php'; ?></a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <!-- loop ends -->

                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>

        <div class="mainFooterContainer">
            <div class="mainFooterarea siteWidth">

                <div class="footerRight">
                    Powered by <a rel="nofollow" href="http://www.iscripts.com/supportdesk/" target="_blank">iScripts SupportDesk</a> . A premium product from <a rel="nofollow" href="http://www.iscripts.com" target="_blank">iScripts.com</a>
                </div>

                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>


    </body>
</html>
