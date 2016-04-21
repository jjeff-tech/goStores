<?php
error_reporting(E_ERROR);
$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) .$s . "://";
define('ROOT_URL', $protocol );
include("./config/settings.php");

include("../../lib/pagecontext.php");
include("./config/config.php");


if (!INSTALLED)
    header("location:./install/install.php");

include("./includes/session.php");

include("./includes/functions/dbfunctions.php");
include("./includes/functions/miscfunctions.php");
include("./includes/functions/impfunctions.php");
include("./includes/main_smtp.php");


$conn = getConnection();
//ini_set('magic_quotes_runtime', 0);
if (get_magic_quotes_gpc()) {
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}
if (!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] == "")) {
    $_SP_language = "en";
} else {
    $_SP_language = $_SESSION["sess_language"];
}
include("./languages/" . $_SP_language . "/main.php");
if (!isset($_SERVER['REQUEST_URI'])) {
    if (isset($_SERVER['SCRIPT_NAME']))
        $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
    else
        $_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];
    if ($_SERVER['QUERY_STRING']) {
        $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
    }
}


$valid_pages = array("index.php", "resetpass.php", "register.php","forgotpassword.php", "getrefinfo.php", "activated.php", "postticketbeforeregister.php", "postticketbeforeregistersave.php", "client_prechat.php", "invoke_chat.php", "client_chat.php", "rate_support.php","rating.php","review-ajax.php","userkbsearchresult.php","viewuserkbsearchresult.php","knowledgebase.php","viewkbentry.php","categories.php","kblisting_by_category.php");


if (!in_array(basename($_SERVER["SCRIPT_NAME"]), $valid_pages)) {
    if (!userLoggedIn()) {
        header("location:index.php");
        exit;
    }
    //  if ($_SERVER['HTTP_REFERER'] == "" ) {      header("location:index.php");      exit;     }
} $conn = getConnection();
// SITE LOGO
$root = getcwd();
$rootArr = explode('/support',$root);
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
// SITE LOGO END
include("./includes/constants.php");

register_shutdown_function('shutdownFunction');
function shutdownFunction() { 
 //  echo '<script type="text/javascript">alert("hello!");</script>'; 
   $sql = " Select * from sptbl_lookup where vLookUpName IN('Post2PostGap','MailFromName','MailFromMail',";
        $sql .="'MailReplyName','MailReplyMail','Emailfooter','Emailheader','AutoLock','HelpdeskTitle','SMTPSettings','SMTPServer','SMTPPort')";
        $conn = getConnection();
        $result = executeSelect($sql,$conn);
        if(mysql_num_rows($result) > 0) {
            while($row = mysql_fetch_array($result)) {
                switch($row["vLookUpName"]) {
                    case "MailFromName":
                        $var_fromName = $row["vLookUpValue"];
                        break;
                    case "MailFromMail":
                        $var_fromMail = $row["vLookUpValue"];
                        break;
                    case "MailReplyName":
                        $var_replyName = $row["vLookUpValue"];
                        break;
                    case "MailReplyMail":
                        $var_replyMail = $row["vLookUpValue"];
                        break;
                    case "Emailfooter":
                        $var_emailfooter = $row["vLookUpValue"];
                        break;
                    case "Emailheader":
                        $var_emailheader = $row["vLookUpValue"];
                        break;
                    case "AutoLock":
                        $var_autoclock = $row["vLookUpValue"];
                        break;
                    case "HelpdeskTitle":
                        $var_helpdeskname = $row["vLookUpValue"];
                        break;
         
                }
            }
        }
        mysql_free_result($result);
           
	$subject = handleError();
        
               
        if($subject!=0){
        $Headers="From: $var_fromName <$var_fromMail>\n";
        $Headers.="Reply-To: $var_replyName <$var_replyMail>\n";
        $Headers.="MIME-Version: 1.0\n";
        $Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
         if($_SESSION["sess_smtpsettings"] == 1) {
            $var_smtpserver = $_SESSION["sess_smtpserver"];
            $var_port = $_SESSION["sess_smtpport"];

            SMTPMail($var_fromMail,'arun.s@armiasystems.com',$var_smtpserver,$var_port,'Error from User',$subject);
        }
        else{
            @mail('arun.s@armiasystems.com','Error from User',$subject,$Headers);
            }
        }
}   
?>
