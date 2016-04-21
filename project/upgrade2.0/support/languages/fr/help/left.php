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
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php

include("../../../includes/constants.php");
include("../../../includes/headsettings.php"); ?>
    </head>
    <body class="header_row">
        <br>
        <br>
        <!-- Start of TOC -->
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="20" align=right valign=top><img src="icon1.gif" border="0"></span></td><td align=left><font face="Arial" size="2">Bienvenue SupportDesk</font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="introduction.php" target="stuff">Présentation</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="features.php" target="stuff" style="color:#000000;">caractéristiques</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="20" align=right valign=top><img src="icon1.gif" border="0"></span></td><td align=left><font face="Arial" size="2">Mise en route</font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="registration.php" target="stuff" style="color:#000000;">Inscription</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="forgotpassword.php" target="stuff" style="color:#000000;">Mot de passe oublié</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="searchticketwithoutloggingin.php" target="stuff" style="color:#000000;">Recherche billet sans se connecter</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="login.php" target="stuff" style="color:#000000;">Connectez-vous</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="postticket.php" target="stuff" style="color:#000000;"><span id="result_box" lang="fr">message de billets</span></a></font></td>
            </tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="postticketbeforeregister.php" target="stuff" style="color:#000000;">Message billets sans se connecter</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="viewticket.php" target="stuff" style="color:#000000;">Voir billets</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="getticketreferencenumber.php" target="stuff" style="color:#000000;">Obtenez Numéro de référence billets</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="news.php" target="stuff" style="color:#000000;">Nouvelles</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="knowledgebase.php" target="stuff" style="color:#000000;">base de connaissances</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="settings.php" target="stuff" style="color:#000000;">réglages</a></font></td></tr></table>
        <table border="0" cellspacing="2" cellpadding="0"><tr><td width="40" align=right valign=top><img src="icon2.gif" border="0"></span></td><td align=left><font face="Arial" size="2"><a href="chat.php" target="stuff" style="color:#000000;">chat</a></font></td></tr></table>

        <!-- End of TOC -->
        <br>
        <hr>
    </font>
</body>
</html>

