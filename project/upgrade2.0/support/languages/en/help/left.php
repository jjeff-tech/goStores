<?php
include("../../../includes/session.php");
include("../../../config/settings.php");
include("../../../includes/functions/dbfunctions.php");
include("../../../includes/functions/miscfunctions.php");
include("../../../includes/functions/impfunctions.php");

/*ini_set('magic_quotes_runtime',0);*/

if (get_magic_quotes_gpc()) {
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}
if(!isset($_SERVER['REQUEST_URI'])) {
    if(isset($_SERVER['SCRIPT_NAME']))
        $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
    else
        $_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];
    if($_SERVER['QUERY_STRING']) {
        $_SERVER['REQUEST_URI'] .=  '?'.$_SERVER['QUERY_STRING'];
    }
}

if(!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] =="") ) {
    $_SP_language = "en";
}else {
    $_SP_language = $_SESSION["sess_language"];
}
include("../../../languages/".$_SP_language."/main.php");
$conn = getConnection();
?>
<html>
    <head>
        <?php
        include("../../../includes/constants.php");
        include("../../../includes/headsettings.php"); ?>
    </head>
    <body class="header_row">
        <br>
        <br>
        <!-- Start of TOC -->
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="20" align=right valign=top><img src="icon1.gif" border="0"></span></td><td align=left><font face="Arial" size="2">Welcome to SupportDesk</font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="introduction.php" target="stuff" style="color:#000000;">Introduction</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="features.php" target="stuff" style="color:#000000;">Features</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="20" align=right valign=top><img src="icon1.gif" border="0"></span></td><td align=left><font face="Arial" size="2">Getting Started</font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="registration.php" target="stuff" style="color:#000000;">Registration</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="forgotpassword.php" target="stuff" style="color:#000000;">Forgot Password</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="searchticketwithoutloggingin.php" target="stuff" style="color:#000000;">Search Ticket without logging in</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="login.php" target="stuff" style="color:#000000;">Login</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="postticket.php" target="stuff" style="color:#000000;">Post Ticket</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="postticketbeforeregister.php" target="stuff" style="color:#000000;">Post Ticket without logging in</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="viewticket.php" target="stuff" style="color:#000000;">View Tickets</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="getticketreferencenumber.php" target="stuff" style="color:#000000;">Get Ticket Reference Number</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="news.php" target="stuff" style="color:#000000;">News</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="knowledgebase.php" target="stuff" style="color:#000000;">Knowledge base</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="settings.php" target="stuff" style="color:#000000;">Settings</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="chat.php" target="stuff" style="color:#000000;">Chat</a></font></td></tr></table>

        <!-- End of TOC -->
        <br>
        <hr>
    </font>
</body>
</html>

