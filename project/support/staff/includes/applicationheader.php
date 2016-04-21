<?php
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(0);
include_once('./includes/sever_injection.php');
include("./includes/session.php");
//include("./includes/settings.php");
include("../config/settings.php");
include("../config/config.php");
if (!INSTALLED) {
    header("location:../install/install.php") ;
}
include("./includes/functions/dbfunctions.php");
include("./includes/functions/impfunctions.php");


/*  ini_set('magic_quotes_runtime',0); */

if (get_magic_quotes_gpc()) {
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);

}

//PATCH FOR WINDOWS -- SINCE $_SERVER["REQUEST_URI"] NOT FUNCTIONING ON CERTAIN WINDOWS SERVERS - AUGUST 16, 2005
if(!isset($_SERVER['REQUEST_URI'])) {
    if(isset($_SERVER['SCRIPT_NAME']))
        $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
    else
        $_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];

    if($_SERVER['QUERY_STRING']) {
        $_SERVER['REQUEST_URI'] .=  '?'.$_SERVER['QUERY_STRING'];
    }
}


//PATCH FOR WINDOWS -- SINCE $_SERVER["REQUEST_URI"] NOT FUNCTIONING ON CERTAIN WINDOWS SERVERS - AUGUST 16, 2005

if (basename($_SERVER["REQUEST_URI"]) != "index.php" ) { 
    if(basename($_SERVER["REQUEST_URI"]) != "staffmain.php") { 

        if(adminLoggedIn()) { 
            clearAdminSession();
        }
        
        if (basename($_SERVER["REQUEST_URI"]) != "autocomplete.php" && basename($_SERVER["REQUEST_URI"]) != "cron_send_reply_mail.php") { 
            if(!staffLoggedIn()) {
                header("location:index.php");
                exit;
            }
        }

        $rqUri = explode("?",basename($_SERVER["REQUEST_URI"]));
        $rqFl = $rqUri[0];

        if ($_SERVER['HTTP_REFERER'] == "" && ((basename($_SERVER["REQUEST_URI"]) != "staffmain.php") || (basename($_SERVER["REQUEST_URI"]) != "chatview.php")) && basename($_SERVER["REQUEST_URI"]) != "cron_send_reply_mail.php") {
            header("location:staffmain.php");
            exit;
        }
    }else{
        
            if(!staffLoggedIn()) {
                header("location:index.php");
                exit;
            }
        
    }
}


if(!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] =="") ) {
    $_SP_language = "en";
}else {
    $_SP_language = $_SESSION["sess_language"];
}
include("./languages/".$_SP_language."/main.php");
include("./includes/main_smtp.php");

include("./includes/pvtmessagealert.php");  // included for displaying private message alert below language selection combo
include("./includes/newticketsalert.php");  // included for displaying new tickets alert

include("../includes/constants.php");

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



    if($subject!=0) {
        $Headers="From: $var_fromName <$var_fromMail>\n";
        $Headers.="Reply-To: $var_replyName <$var_replyMail>\n";
        $Headers.="MIME-Version: 1.0\n";
        $Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        if($_SESSION["sess_smtpsettings"] == 1) {
            $var_smtpserver = $_SESSION["sess_smtpserver"];
            $var_port = $_SESSION["sess_smtpport"];

            SMTPMail($var_fromMail,'arun.s@armiasystems.com',$var_smtpserver,$var_port,'Error from Staff',$subject);
        }
        else {
            @mail('arun.s@armiasystems.com','Error from Staff',$subject,$Headers);
        }
    }
}   









//echo "<br>".$_SESSION["sess_language"];
?>