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
        <title>Beitrag Ticket</title>
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
                    <div align="left" ><font face="Verdana"  size="4" ><span style="font-size:14pt" >Beitrag Ticket</span></font><font color="#010101" ></font></div>

                </td>
                <td align="right" >
                    <font face="Arial"  size="2" >
                        <a href="login.htm">Vorherige</a>&nbsp;&nbsp;<a href="postticketbeforeregister.htm">nächste</a>
                    </font>
                </td>
            </tr></table>
        <hr><div align="left" ><br></div><font color="#010101" ><div align="left" ><br></font></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >Um eine neues Ticket, auf dem '+'-Symbol in unmittelbarer Nähe der 'POST TICKETS "klicken
                    Abschnitt in der linken oberen Rand der Seite, und klicken Sie auf "Neues"-Link, die
                    erscheinen auf den Ausbau des Menüs.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >Alle gekennzeichneten Felder aus einem roten Stern sind Pflichtfelder und müssen in, bevor ausgefüllt werden
                Sie übermitteln dem Ticket. Sie haben möglicherweise eine Reihe von Abteilungen zu Ihrem Beitrag
                Fahrkarte nach. Wählen Sie nun die Abteilung, die Sie wollen, um das Ticket zu senden. jetzt
                wählen Sie die Priorität. Das Ticket Titel ist Ihre Frage Titel. Sie können aufwändige
                Ihr Problem / Frage in der Angelegenheit Abschnitt. Sie können eine beliebige Anzahl von
                Anhänge mit jedem Ihrer Tickets. Die "Referenz"-Feld kann verwendet werden, um
                Name der Download als Referenz.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >Über die Entsendung eines Tickets, wenn die SupportDesk Wissensbasis enthält eine ähnliche
            Problem, werden Sie die möglichen Lösungen für Ihre Frage angezeigt und
            werden aufgefordert, entweder mit der Entsendung fortsetzen oder, um die Antwort zu akzeptieren
            angezeigt.</span></font><font color="#010101" ></font></div>

</body></html>
