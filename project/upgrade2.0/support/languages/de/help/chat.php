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
        <title>plaudern</title>
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
                    <div align="left" ><font face="Verdana"  size="4" ><span style="font-size:14pt" >plaudern</span></font><font color="#010101" ></font></div>

                </td>
                <td align="right" >
                    <font face="Arial"  size="2" >
                        <a href="settings.htm">Vorherige</a>&nbsp;&nbsp;
                    </font>
                </td>
            </tr></table>
        <hr><div align="left" ><br></div><font color="#010101" ><div align="left" ><br></font></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;Chat-Bereich ist, wo ein Benutzer / Kunde kann mit den Betreibern zu chatten
                    bei der Lösung ihrer questions.You kann das Chat-Fenster durch Klicken auf die <b> Start Chat starten </b> link im User-Bereich. Dann wird ein Fenster öffnet sich, in dem Ihr
                    Benutzernamen und E-Mail-ID wird ordnungsgemäß ausgefüllt werden. Von dort können Sie Ihre
                    Frage. Sie können auch wählen eine bestimmte Abteilung chatten möchten
                    with.If die Mitarbeiter in der ausgewählten Abteilung offline sind, dann werden Sie
                    gefragt, ob die Frage zu, wie ein Ticket gebucht werden. Wenn der Benutzer auf OK klicken, dann
                    die eingegebene Frage wird als ein Ticket gebucht werden.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;WENN Einer der Mitarbeiter in der ausgewählten Abteilung ist online, während
                    Klick auf sterben "Chat jetzt ', dann werden Sie in der Lage sein, mit him.In den Chat
                    Chat-Fenster, wird es eine Statusanzeige, wo Du sehen können
                    aktuellen Status des Chat-Sitzung. Es wird als 'Calling ...' angezeigt, wenn Sie nicht
                    an alle Mitarbeiter noch. Nach dem Anschluss an jeden der Stäbe, Sie
                    können Sie den Status als "Personal Staff Namen Connected". Wenn der Chat hat
                    abgeschlossen ist, wird der Status "Abgeschlossen Chat 'und, wenn das Personal ist offline, wird es
                    werden 'No Staffs verbunden sind ".</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;Wenn Sie eine Personal verbunden sind, können Sie im Chat mit den Mitarbeitern
                    indem Sie Ihre Chat-Text in der Text am unteren Rand der bereitgestellten
                    Chat-Fenster. Nach Eingabe Ihrer Chat-Text, drücken Sie Enter-Taste oder klicken Sie auf den
                    Schaltfläche Senden.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;Es wird eine Reihe von Symbolen wie "Stellen Sie Ihre Desktop ',' Mail Chat werden
                    Transcript ',' Print Transcript ',' Bewerten Support 'und' Exit 'an der Spitze des Chats
                    Fenster zu sehen. Die '<b> Sagen Sie Ihre Desktop </b> "-Taste, um den Computer gemeinsam
                    Desktop mit dem Betreiber. Falls erforderlich, kann der Betreiber den Benutzer auffordern,
                    teile seine / ihre Desktop mit ihm, damit er Ihre Fragen einfach zu lösen. Wenn die
                    Betreiber bitten Sie, Ihren Desktop freigeben, können Sie auf diese Schaltfläche klicken. es wird
                    fragen Sie eine Bestätigung. Wenn der Benutzer auf "Ja", kann diese bitten, eine Datei auszuführen</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >mit JRE. Wenn Sie die Datei mit JRE ausführen, wird der Server in Ihrem gestartet werden
                    Systems. So kann der Bediener den Desktop vom anderen Ende zu sehen.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;Die 'Mail Chat Transcript "-Taste, um das Chat-Protokoll zu jedem E-Mail senden
                    id. Wenn Sie auf diese Schaltfläche, werden Sie gefragt, um die E-Mail-Adresse eingeben
                    die Chat-Protokolle gesendet werden sollen. Dann klicken Sie auf den Senden-Button klicken.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;The &lsquo;Print Chat Transcript "-Taste ist mit dem Chat-Protokolle zu drucken.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;Sie können bewerten die aktuelle Unterstützung der Verwendung dieser Taste. Wenn Sie auf diesen klicken
                    Taste, werden Sie gefragt, zu einer Rate von Bereich von 1-10 wählen. Sie können auch
                    Geben Sie Ihre Kommentare zu dieser Unterstützung in der "Kommentare". nach
                    Eintritt in den Details, dann auf den Button klicken.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp; Die 'Exit' wird verwendet, um die Chat-Sitzung zu beenden.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font color="#010101" ></font></div><div align="left" ><br></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div>

</body></html>
