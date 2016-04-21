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
        <title>Registration</title>
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
                    <div align="left" ><span id="result_box" lang="fr">Inscription</span></div>

                </td>
                <td align="right" >
                    <font face="Arial"  size="2" >
                        <a href="features.htm"><span id="result_box" lang="fr">pr&eacute;c&eacute;dente</span></a>&nbsp;&nbsp;<a href="forgotpassword.htm"><span id="result_box" lang="fr">Suivant</span></a>      </font>    </td>
            </tr></table>
        <hr><div align="left" ><br></div><font color="#010101" ><div align="left" ><br></font></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">La section d'inscription consiste &agrave; collecter les informations utilisateur minimale. S'il vous pla&icirc;t entrer votre "Login Name", "Mot de passe ', nom, email et Soci&eacute;t&eacute;. Tous les champs marqu&eacute;s d'une &eacute;toile rouge sont obligatoires. Votre nom de connexion doit contenir que des lettres (az, AZ) et aux chiffres (0-9) et sans espaces blancs ou des caract&egrave;res sp&eacute;ciaux. S'il vous pla&icirc;t enregistrer votre nom et mot de passe quelque part, car ces informations seront fr&eacute;quemment n&eacute;cessaires pour acc&eacute;der &agrave; votre compte et poster vos questions. SupportDesk peut accueillir des entreprises multiples et vous aurez besoin pour choisir une entreprise de la liste des soci&eacute;t&eacute;s dans le domaine de l'enregistrement. Dans le champ email n'oubliez pas de saisir un email valide car ce courriel sera celui qui sera utilis&eacute; pour toutes les autres communications et des op&eacute;rations</span>.</span></font><font color="#010101" ></font></div>
        <div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Sur inscription r&eacute;ussie, vous serez averti par des messages correspondant et un mail de notification &agrave; l'adresse que vous avez fournies dans le champ email</span>.</span></font><font color="#010101" ></font></div>
    <div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Maintenant, votre compte est activ&eacute; et vous pouvez imm&eacute;diatement vous connecter au SupportDesk et la disponibilit&eacute; des services offerts</span>.</span></font><font color="#010101" ></font></div>

</body></html>
