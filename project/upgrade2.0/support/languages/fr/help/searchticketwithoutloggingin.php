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
        <title>Search Ticket without logging in</title>
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
                    <div align="left" ><span id="result_box" lang="fr">Recherche billet sans se connecter</span><font color="#010101" ></font></div>

                </td>
                <td align="right" >
                    <font face="Arial"  size="2" >
                        <a href="forgotpassword.htm"><span id="result_box" lang="fr">pr&eacute;c&eacute;dente</span></a>&nbsp;&nbsp;<a href="login.htm"><span id="result_box" lang="fr">Suivant</span></a>      </font>    </td>
            </tr></table>
        <hr><div align="left" ><br></div><font color="#010101" ><div align="left" ><br></font></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Si vous avez besoin pour simplement v&eacute;rifier l'&eacute;tat et les d&eacute;tails d'un billet particulier, sans se connecter &agrave; l'SupportDesk, vous devez aller &agrave; la page d'accueil. Dans la section principale (page centrale), vous pouvez trouver une place o&ugrave; vous pouvez entrer le num&eacute;ro de r&eacute;f&eacute;rence du billet et l'email d'inscription. Vous pouvez noter que envue d'envoyer des r&eacute;ponses ou pour effectuer d'autres op&eacute;rations sur un billet, vous avez besoin de se connecter au syst&egrave;me</span>.</span></font><font color="#010101" ></font></div>

</body></html>
