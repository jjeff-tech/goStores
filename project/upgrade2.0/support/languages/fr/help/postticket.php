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
        <title>Post Ticket</title>
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
                    <div align="left" ><span id="result_box" lang="fr">message de billets</span></div>

                </td>
                <td align="right" >
                    <font face="Arial"  size="2" >
                        <a href="login.htm"><span id="result_box" lang="fr">pr&eacute;c&eacute;dente</span></a>&nbsp;&nbsp;<a href="postticketbeforeregister.htm"><span id="result_box" lang="fr">Suivant</span></a>      </font>    </td>
            </tr></table>
        <hr><div align="left" ><br></div><font color="#010101" ><div align="left" ><br></font></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Pour poster un nouveau billet, cliquez sur le ic&ocirc;ne '+' &agrave; proximit&eacute; imm&eacute;diate de la section &laquo;BILLETS POST 'en haut &agrave; gauche de la page, et cliquez sur le lien&laquo; Nouveau message &raquo;qui apparaissent sur l'expansion du menu</span>.</span></font><font color="#010101" ></font></div>
        <div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Tous les champs marqu&eacute;s d'une &eacute;toile rouge sont obligatoires et doivent &ecirc;tre remplis avant de soumettre le billet. Vous pouvez avoir un certain nombre de minist&egrave;res afin d'envoyer un billet pour. Maintenant, s&eacute;lectionnez le service que vous voulez envoyer le billet pour. Maintenant, s&eacute;lectionnez la priorit&eacute;. Le titre billet est le titre de votre question. Vous pouvez &eacute;laborer votre probl&egrave;me / question dans la section affaire. Vous pouvez ajouter n'importe quel nombre de pi&egrave;ces jointes avec chacun de vos billets. Le champ &laquo;r&eacute;f&eacute;rence&raquo; peut &ecirc;tre utilis&eacute; pour le nom de votre t&eacute;l&eacute;chargement pour r&eacute;f&eacute;rence</span>.</span></font><font color="#010101" ></font></div>
    <div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Le poster un billet, si la base de connaissances SupportDesk contient un probl&egrave;me similaire, vous sera affich&eacute; les solutions possibles pour votre question et vous serez invit&eacute; soit &agrave; continuer &agrave; poster ou &agrave; accepter la r&eacute;ponse affich&eacute;e</span>.</span></font><font color="#010101" ></font></div>

</body></html>
