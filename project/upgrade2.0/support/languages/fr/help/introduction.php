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
<html><head>
        <title>Introduction</title>
        <meta name="generator"  content="HelpMaker.net" >
        <meta name="keywords"  content="Topic 1," ><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
include("../../../includes/constants.php");
include("../../../includes/headsettings.php"); ?>
    </head>
    <body bgcolor="FFFCEA" >
        <table width="100%"  border="0"  cellspacing="0"  cellpadding="2"  class="header_row">
            <tr>
                <td align="left" >
                    <div align="left" ><span id="result_box" lang="fr">Pr&eacute;sentation</span></div>

                </td>
                <td align="right" >
                    <font face="Arial"  size="2" >
                        &nbsp;&nbsp;<a href="features.htm"><span id="result_box" lang="fr">Suivant</span></a>      </font>    </td>
            </tr></table>
        <hr><div align="left" ><br></div><font color="#010101" ><div align="left" ><br></font></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Le SupportDesk iScripts est un syst&egrave;me int&eacute;gr&eacute; pour la gestion des demandes des clients, des r&eacute;ponses et des communications r&eacute;sultant de telles requ&ecirc;tes et les probl&egrave;mes li&eacute;s &agrave; l'aide d'un &eacute;ventail d'outils et d'installations regroup&eacute;es autour du syst&egrave;me de gestion des billets comme le syst&egrave;me de base de connaissances, syst&egrave;me de rappel, de diffusion et de syst&egrave;me de messagerie, et le syst&egrave;me de tuyauterie email pour n'en citer que quelques</span>.</span></font><font color="#010101" ></font></div>
        <div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Les utilisateurs potentiels du syst&egrave;me sont des utilisateurs (clients), le personnel (techniciens), et les administrateurs</span>.</span></font><font color="#010101" ></font></div>
    <div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Les utilisateurs / clients communiquent avec le syst&egrave;me pour obtenir leurs probl&egrave;mes et de questions r&eacute;solues. Ils recherchent habituellement dans la section pertinente FAQ pour voir si un probl&egrave;me similaire (et solution) est cot&eacute;e d&eacute;j&agrave; l&agrave;. Si l'utilisateur n'est pas en mesure de trouver un correspondant, il / elle affiche un ticket avec les d&eacute;tails n&eacute;cessaires. Le personnel (techniciens) pour r&eacute;pondre &agrave; des billets avec une r&eacute;ponse pr&eacute;d&eacute;finie ou un nouvellement cr&eacute;&eacute;, selon la situation. La principale fonctionnalit&eacute; du syst&egrave;me est cette communication va et vient entre l'utilisateur et le personnel. Dans le cas d'un personnel est incapable de r&eacute;soudre un probl&egrave;me il / elle peut d&eacute;g&eacute;n&eacute;rer le probl&egrave;me &agrave; l'administrateur</span>.</span></font><font color="#010101" ></font></div>
<div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" ><span id="result_box" lang="fr">Une autre caract&eacute;ristique majeure de soutien iScripts System est LiveChat. Outre le syst&egrave;me de gestion des billets, les clients peuvent discuter avec les op&eacute;rateurs pour toutes les requ&ecirc;tes. Inter chat installation op&eacute;rateur est &eacute;galement disponible dans ce syst&egrave;me. Si n&eacute;cessaire, les clients peuvent partager leurs Ordinateur de bureau avec l'op&eacute;rateur dont ils discutent avec les via la fonction Partage de bureau &agrave; distance. Cela vous aidera &agrave; r&eacute;soudre leurs probl&egrave;mes facilement. Les clients peuvent choisir un d&eacute;partement particulier de l'entreprise et est en mesure de discuter avec le personnel de ce minist&egrave;re particulier</span>. </span></font><font color="#010101" ></font></div>
<div align="left" ><br></div><font color="#010101" ><div align="left" ><br></font></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div>

    </body></html>
