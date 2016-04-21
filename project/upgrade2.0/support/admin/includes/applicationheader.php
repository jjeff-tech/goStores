<?php

include("./includes/session.php");
include("../config/settings.php");
$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s . "://";
define('ROOT_URL', $protocol);

include("../../../lib/pagecontext.php");
include("../../config/settings.php");

include("./includes/functions/dbfunctions.php");
include("./includes/functions/impfunctions.php");
/*ini_set('magic_quotes_runtime', 0);*/

if (get_magic_quotes_gpc()) {
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}

/*if (!isset($_SERVER['REQUEST_URI'])) {
    if (isset($_SERVER['SCRIPT_NAME']))
        $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
    else
        $_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];
    if ($_SERVER['QUERY_STRING']) {
        $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
    }
}
if (basename($_SERVER["REQUEST_URI"]) != "index.php") {
    if (staffLoggedIn()) {
        clearStaffSession();
    } if (!adminLoggedIn()) {
        header("location:" . BASE_URL . "support/index.php");
        exit;
    }
}*/

if (!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] == "")) {
    $_SP_language = "en";
} else {
    $_SP_language = $_SESSION["sess_language"];
} include("./languages/" . $_SP_language . "/main.php");
include("./includes/main_smtp.php");
$conn = getConnection();

/*
$gostores_banner = mysql_query("SELECT cms_set_value FROM " . MYSQL_TABLE_PREFIX . "cms_settings WHERE cms_set_name = 'admin_logo'");
$banner_res = mysql_fetch_array($gostores_banner);
$_SESSION["banner_img"] = BASE_URL . $banner_res['cms_set_value'];
*/

$root = getcwd();
$rootArr = explode('/support/admin',$root);
$baseRoot=$rootArr[0];
define('PARENT_ROOT',$baseRoot);
define('PARENT_IMAGE_ROOT',BASE_URL.'project/styles/images/');
$gostores_banner = mysql_query("SELECT s.value, f.file_path FROM ".MYSQL_TABLE_PREFIX."Settings s LEFT JOIN ".MYSQL_TABLE_PREFIX."files f ON f.file_id = s.value WHERE s.settingfield='siteLogo'");
$banner_res = mysql_fetch_array($gostores_banner);

$logiFile = PARENT_ROOT.'/files/'.$banner_res['file_path'];
$logoImage = PARENT_IMAGE_ROOT.'gostores_logo.jpg';

    if(is_file($logiFile)){
        $logoImage = BASE_URL.'project/files/siteLogo_'.$banner_res['file_path'];
    }

$_SESSION["banner_img"] =$logoImage;

include("../includes/constants.php");
?>