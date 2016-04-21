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
        <title>Chat</title>
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
                    <div align="left" ><font face="Verdana"  size="4" ><span style="font-size:14pt" >Chat</span></font><font color="#010101" ></font></div>

                </td>
                <td align="right" >
                    <font face="Arial"  size="2" >
                        <a href="settings.htm">précédente</a>&nbsp;&nbsp;
                    </font>
                </td>
            </tr></table>
        <hr><div align="left" ><br></div><font color="#010101" ><div align="left" ><br></font></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;<span id="result_box" lang="fr">Section Chat est l'endroit o&ugrave; un client utilisateur / pouvez discuter avec les op&eacute;rateurs pour r&eacute;soudre leurs questions.You pouvez lancer la fen&ecirc;tre de chat en cliquant sur &#8203;&#8203;le lien chat Lancement de la zone utilisateur. Ensuite, une fen&ecirc;tre pop up dans lequel votre nom d'utilisateur et ID Email seront d&ucirc;ment remplis. De l&agrave;, vous pouvez entrer votre question. Vous pouvez &eacute;galement s&eacute;lectionner un d&eacute;partement particulier, vous voulez discuter with.If le personnel dans le d&eacute;partement s&eacute;lectionn&eacute; sont hors ligne, puis il vous sera demand&eacute; si la question doit &ecirc;tre affich&eacute; comme un billet. Si l'utilisateur clique sur OK, puis la question est entr&eacute; sera affich&eacute; comme un billet</span>.</span></font><font color="#010101" ></font></div>
        <div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;<span id="result_box" lang="fr">Si l'un des &eacute;tats-majors dans le d&eacute;partement s&eacute;lectionn&eacute; est en ligne, en cliquant sur le 'Chat maintenant "bouton, vous pourrez discuter avec d'autres him.In la fen&ecirc;tre de chat, il y aura une section Affichage de l'&eacute;tat o&ugrave; vous pouvez voir le courant statut de la session de chat. Elle s'affiche comme &laquo;Calling ..." si vous n'&ecirc;tes pas connect&eacute; &agrave; tout le personnel encore. Apr&egrave;s la connexion &agrave; n'importe qui des b&acirc;tons, vous pouvez voir le statut de &laquo;Connect&eacute; au nom du personnel du personnel&raquo;. Si le chat est termin&eacute;, le statut sera "Chat Termin&eacute;&raquo; et si le personnel est d&eacute;connect&eacute;, il sera &laquo;Non personnel est hors ligne</span>&rsquo;.</span></font><font color="#010101" ></font></div>
        <div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;<span id="result_box" lang="fr">Si vous &ecirc;tes connect&eacute; &agrave; tout le personnel, vous pouvez commencer &agrave; discuter avec le personnel en entrant votre chat texte dans la zone de texte pr&eacute;vue au bas de la fen&ecirc;tre de chat. Apr&egrave;s avoir entr&eacute; votre texte de chat, appuyez sur la touche Entr&eacute;e ou cliquez sur le bouton Envoyer</span>.</span></font><font color="#010101" ></font></div>
        <div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;<span id="result_box" lang="fr">Il y aura un ensemble d'ic&ocirc;nes comme "Partagez votre bureau&raquo;, &laquo;Chat Transcript mail ',' Transcription Imprimer&raquo;, &laquo;Soutien de taux&raquo; et &laquo;Exit&raquo; en haut de la fen&ecirc;tre de chat. Le &laquo;Partagez votre bureau" bouton est de partager votre bureau d'ordinateur avec l'op&eacute;rateur. Si n&eacute;cessaire, l'op&eacute;rateur peut demander &agrave; l'utilisateur de partager son / sa table avec lui afin qu'il peut r&eacute;soudre vos probl&egrave;mes facilement. Si l'op&eacute;rateur vous demandera de partager votre bureau, vous pouvez cliquer sur ce bouton. Il vous demandera une confirmation. Si l'utilisateur clique sur 'Oui', cela peut vous demander d'ex&eacute;cuter un fichier</span></span></font><font color="#010101" ></font></div>
        <div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><span id="result_box" lang="fr">avec JRE. Si vous ex&eacute;cutez le fichier avec JRE, le serveur sera lanc&eacute; dans votre syst&egrave;me. Ainsi l'op&eacute;rateur peut visualiser votre bureau &agrave; l'autre extr&eacute;mit&eacute;.</span></div>
        <div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;<span id="result_box" lang="fr">Le bouton &laquo;Transcription courrier Chat" est d'envoyer le log de &#8203;&#8203;chat pour toute identification d'email. Si vous cliquez sur ce bouton, il vous demandera d'entrer l'adresse &eacute;lectronique &agrave; laquelle le chat logs doivent &ecirc;tre envoy&eacute;s. Puis cliquez sur le bouton envoyer</span>.</span></font><font color="#010101" ></font></div>
        <div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;<span id="result_box" lang="fr">Le bouton 'Imprimer Transcription Chat "est d'imprimer les journaux de chat</span>.</span></font><font color="#010101" ></font></div>
        <div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;<span id="result_box" lang="fr">Vous pouvez &eacute;valuer le soutien actuel &agrave; l'aide de ce bouton. Si vous cliquez sur ce bouton, il vous demandera de choisir un taux de la gamme 1-10. Vous pouvez &eacute;galement inscrire vos commentaires &agrave; propos de cette aide dans la section 'Commentaires'. Apr&egrave;s &ecirc;tre entr&eacute; dans les d&eacute;tails, cliquez sur le bouton soumettre</span>.</span></font><font color="#010101" ></font></div>
        <div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;<span id="result_box" lang="fr">La &laquo;sortie&raquo; est utilis&eacute; pour mettre fin &agrave; la session de conversation</span>.</span></font><font color="#010101" ></font></div>
        <div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font color="#010101" ></font></div><div align="left" ><br></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div>

</body></html>
