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
                    <div align="left" ><span id="result_box" lang="fr">Mot de passe oubli&eacute;</span></div>

                </td>
                <td align="right" >
                    <font face="Arial"  size="2" >
                        <a href="registration.htm"><span id="result_box" lang="fr">pr&eacute;c&eacute;dente</span></a>&nbsp;&nbsp;<a href="searchticketwithoutloggingin.htm"><span id="result_box" lang="fr">Suivant</span></a>      </font>    </td>
            </tr></table>
        <hr><div align="left" ><br></div><font color="#010101" ><div align="left" ><br></font></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Si jamais vous oubliez votre mot de passe pour l'SupportDesk, vous pouvez cliquer sur le lien &laquo;Mot de passe" lien affich&eacute; dans le coin inf&eacute;rieur gauche de l'&eacute;cran de connexion. Il vous sera demand&eacute; d'entrer l'adresse email que vous avez fournies au moment de l'inscription. Vous recevrez un lien pour r&eacute;initialiser votre mot de passe dans un courriel &agrave; cette adresse. Il suffit de cliquer sur ce lien pour r&eacute;initialiser votre mot de passe et le faire par courriel &agrave; votre adresse. Vous allez recevoir un email avec votre mot de passe r&eacute;initialis&eacute;. Maintenant vous connecter &agrave; votre compte avec ce mot de passe. Ne pas oublier de changer votre mot de passe imm&eacute;diatement apr&egrave;s votre connexion pour s&eacute;curiser votre compte</span>.</span></font><font color="#010101" ></font></div>

</body></html>
