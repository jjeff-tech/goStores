<?php
include("../../../includes/session.php");
include("../../../config/settings.php");
include("../../../includes/functions/dbfunctions.php");
include("../../../includes/functions/miscfunctions.php");
include("../../../includes/functions/impfunctions.php");
/*ini_set('magic_quotes_runtime', 0);*/
        if (get_magic_quotes_gpc()) {
            $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}         if
(!isset($_SERVER['REQUEST_URI'])) {
    if (isset($_SERVER['SCRIPT_NAME']))                 $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];             else                 $_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];
    if ($_SERVER['QUERY_STRING']) {
        $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
    }
}
if
(!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] == "")) {
    $_SP_language = "en";
} else {
    $_SP_language = $_SESSION["sess_language"];
}
include("../../../languages/" . $_SP_language . "/main.php");
$conn = getConnection();
?>
<html><head>
        <title>Forgot Password</title>
        <meta name="generator"  content="HelpMaker.net" >
        <meta name="keywords"  content="Topic 1," >
<?php
include("../../../includes/constants.php");
include("../../../includes/headsettings.php");         ?>

    </head>

    <body bgcolor="FFFCEA" >
        <table width="100%"  border="0"  cellspacing="0"  cellpadding="2"  class="header_row" >
            <tr>
                <td align="left" >
                    <div align="left" ><font face="Verdana"  color="#010101"  size="4" ><span style="font-size:14pt" >Forgot Password</span></font><font color="#010101" ></font></div>

                </td>
                <td align="right" >
                    <font face="Arial"  size="2" >
                        <a href="registration.htm">Previous</a>&nbsp;&nbsp;<a href="searchticketwithoutloggingin.htm">Next</a>
                    </font>
                </td>
            </tr></table>
        <hr><div align="left" ><br></div><font color="#010101" ><div align="left" ><br></font></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >If you ever forget your password to the SupportDesk, you can click on the
                    'Forgot password' Link displayed on the bottom-left corner of the login
                    screen. You will be asked to input the email address you have provided at
                    the time of registration. You will be receiving a link to reset your password in
                    an email to this address. Just click on this link to reset your password and
                    get it emailed to your address. You will be receiving an email with your reset
                    password. Now login to your account with this password. Dont forget to
                    change your password immediately after you login to secure your account.</span></font><font color="#010101" ></font></div>

</body></html>
