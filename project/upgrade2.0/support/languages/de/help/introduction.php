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
        <title>Einführung</title>
        <meta name="generator"  content="HelpMaker.net" >
        <meta name="keywords"  content="Topic 1," ><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<?php
include("../../../includes/constants.php");
include("../../../includes/headsettings.php"); ?>
    </head>
    <body bgcolor="FFFCEA" >
        <table width="100%"  border="0"  cellspacing="0"  cellpadding="2" class="header_row">
            <tr>
                <td align="left" >
                    <div align="left" ><font face="Verdana"  color="#010101"  size="4" ><span style="font-size:14pt" >Einführung</span></font><font color="#010101" ></font></div>

                </td>
                <td align="right" >
                    <font face="Arial"  size="2" >
                        &nbsp;&nbsp;<a href="features.htm">Next</a>
                    </font>
                </td>
            </tr></table>
        <hr><div align="left" ><br></div><font color="#010101" ><div align="left" ><br></font></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >Die iScripts SupportDesk ist ein integriertes System für die Verwaltung der Kunden
                    Anfragen, Antworten und Kommunikation, die sich aus solchen Abfragen und
                    Probleme mit Hilfe einer Reihe von Werkzeugen und Einrichtungen geclusterten
                    rund um die Ticket-Management-System wie in der Knowledge Base-System,
                    Mahnwesen, Mailing-und Messaging-System, E-Mail Rohrleitungssystem
                    einige zu nennen.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >Die potenziellen Nutzer des Systems sind (Kunden), Mitarbeiter (Techniker),
                und Administratoren.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >Users / Kunden mit dem System kommunizieren, um ihre Probleme zu bekommen und
            Fragen gelöst. Sie sehen in der Regel bis in den entsprechenden FAQ-Bereich zu sehen
            ob ein ähnliches Problem (und Lösung) ist es bereits gelistet. Wenn der Benutzer
            ist nicht in der Lage, ein übereinstimmendes, s / er Beiträge ein Ticket mit der nötigen finden
            Einzelheiten. Die Mitarbeiter (Techniker), um die Tickets zu reagieren mit einem vordefinierten
            Antwort oder eine neu erstellte, je nach Situation. die wichtigsten
            Funktionalität des Systems ist dies hin und her Kommunikation zwischen den
            Benutzer und Mitarbeiter. Bei einem Stab ist ein Problem nicht lösen s / er kann
            Eskalation des Problems an den Administrator.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >Ein weiteres wichtiges Merkmal der iScripts Support System ist LiveChat. Neben der
        Ticket-Management-System können die Kunden mit den Betreibern für jeden Chat
        Abfragen. Inter-Operator Chat-Funktion ist auch in diesem System zur Verfügung. wenn
        Bedarf können die Kunden ihre Computer Desktop mit dem Betreiber Aktien
        denen sie mit über Remote Desktop Sharing-Funktion im Chat. dieser
        wird ihnen helfen, ihre Probleme einfach zu lösen. Kunden können wählen Sie eine besondere
        Abteilung des Unternehmens und ist in der Lage, mit den Stäben, die Chat-
        bestimmten Abteilung. </span></font><font color="#010101" ></font></div><div align="left" ><br></div><font color="#010101" ><div align="left" ><br></font></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div>

    </body></html>
