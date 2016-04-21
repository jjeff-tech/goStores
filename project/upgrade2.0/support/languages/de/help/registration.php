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
        <title>Anmeldung</title>
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
                    <div align="left" ><font face="Verdana"  color="#010101"  size="4" ><span style="font-size:14pt" >Anmeldung</span></font><font color="#010101" ></font></div>

                </td>
                <td align="right" >
                    <font face="Arial"  size="2" >
                        <a href="features.htm">Vorherige</a>&nbsp;&nbsp;<a href="forgotpassword.htm">nächste</a>
                    </font>
                </td>
            </tr></table>
        <hr><div align="left" ><br></div><font color="#010101" ><div align="left" ><br></font></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >Die Registrierung Abschnitt besteht aus der Erhebung der minimal Benutzerdaten.
                    Bitte geben Sie Ihren 'Login-Namen', 'Password', Name, Email und Company. alle
                    Felder mit einem roten Stern gekennzeichneten Felder sind Pflichtfelder. Ihr Login-Namen sollte
                    enthalten nur Buchstaben (az, AZ) und Ziffern (0-9) und keine Leer-oder Sonderzeichen
                    Zeichen. Bitte notieren Sie Ihren Benutzernamen und Ihr Passwort irgendwo da
                    Diese Informationen werden häufig benötigt werden, um Ihr Konto und post Zugang
                    Ihre Fragen. Die SupportDesk kann als Host für mehrere Unternehmen, und Sie werden
                    benötigen, um ein Unternehmen aus der Liste der Unternehmen in der Registrierung wählen Sie
                    Feld. In der E-Mail-Feld zur Eingabe erinnere mich an eine gültige E-Mail, da diese E-Mail wird
                    werden die, die für alle weiteren Kommunikationen und Operationen verwendet werden.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >Bei erfolgreicher Anmeldung erhalten Sie mit entsprechenden Nachrichten informiert werden
                und eine Benachrichtigung Mail an die Adresse, die Sie in der E-Mail-Bereich zur Verfügung gestellt haben.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >Jetzt ist Ihr Konto aktiviert und Sie können sofort auf die Anmeldeseite
            SupportDesk und Verfügbarkeit der angebotenen Dienstleistungen.</span></font><font color="#010101" ></font></div>

</body></html>
