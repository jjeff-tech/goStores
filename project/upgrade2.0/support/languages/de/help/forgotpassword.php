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
$conn = getConnection();    ?>
<html><head>
        <title>Passwort vergessen</title>
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
                    <div align="left" ><font face="Verdana"  color="#010101"  size="4" ><span style="font-size:14pt" >Passwort vergessen</span></font><font color="#010101" ></font></div>

                </td>
                <td align="right" >
                    <font face="Arial"  size="2" >
                        <a href="registration.htm">Vorherige</a>&nbsp;&nbsp;<a href="searchticketwithoutloggingin.htm">nächste</a>
                    </font>
                </td>
            </tr></table>
        <hr><div align="left" ><br></div><font color="#010101" ><div align="left" ><br></font></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >Wenn Sie Ihr Kennwort vergessen haben, die SupportDesk, können Sie auf die Schaltfläche
                    "Passwort vergessen"-Link auf der linken unteren Ecke der Anmeldung angezeigt
                    Bildschirm. Sie werden zur Eingabe aufgefordert werden die E-Mail-Adresse an zur Verfügung gestellt haben
                    zum Zeitpunkt der Anmeldung. Sie werden empfangen einen Link, um Ihr Passwort zurückzusetzen in
                    eine E-Mail an diese Adresse. Einfach auf diesen Link klicken, um Ihr Passwort zurückzusetzen und
                    um es an Ihre Adresse geschickt. Sie werden empfangen eine eMail mit Ihren reset
                    Kennwort ein. Jetzt bei Ihrem Konto anmelden mit diesem Passwort. Vergessen Sie nicht,
                    Ihr Passwort zu ändern, nachdem Sie Ihr Konto secure login.</span></font><font color="#010101" ></font></div>

</body></html>