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
        <meta name="keywords"  content="Topic 2," >
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
                        <a href="settings.htm">Previous</a>&nbsp;&nbsp;
                    </font>
                </td>
            </tr></table>
        <hr><div align="left" ><br></div><font color="#010101" ><div align="left" ><br></font></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;Chat section is where a user/customer can chat with the operators to
                    solve their questions.You can launch the chat window by clicking on the <b>Launch Chat</b> link of the user area. Then a window will pop up in which your
                    user name and&nbsp; Email ID will be duly filled.&nbsp; From there you can enter your
                    question . Also you can select any particular department you want to chat
                    with.If the staffs in the selected department are offline, then you will be
                    asked if the question has to be posted as a ticket. If the user click OK, then
                    the entered question will be posted as a ticket.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;If any one of the staffs in the selected department is online,&nbsp; while
                    clicking on the &lsquo;chat now&rsquo; button, you will be able to chat with him.In the
                    chat window, there will be a status display section where you can see the
                    current status of the chat session. It will display as &lsquo;Calling...&rsquo; if you are not
                    connected to&nbsp; any staff yet.&nbsp; After connecting to anyone of the staffs, you
                    can see the status as &lsquo;Connected to staff Staff name&rsquo;. If the chat has
                    finished, the status will be &lsquo;Chat Completed&rsquo; and if the staff is offline, it will
                    be &lsquo;No Staffs are Offline&rsquo;.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;If you are connected to any staff, you can start chatting with the staff
                    by entering your chat text in the text area provided at the bottom of the
                    chat window. After entering your chat text, press enter key or click on the
                    Send button.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;There will be a set of&nbsp; icons like &lsquo;Share your Desktop&rsquo;, &lsquo;Mail Chat
                    Transcript&rsquo;, &lsquo;Print Transcript&rsquo;, &lsquo;Rate&nbsp; Support&rsquo; and &lsquo;Exit&rsquo;&nbsp; at the top of the chat
                    window. The &lsquo;<b>Share your Desktop</b>&rsquo; button is to share your computer
                    Desktop with the operator. If needed, the operator may ask the user to
                    share his/her Desktop with him so that he can solve your issues easily. If the
                    operator ask you to share your Desktop, you can click on this button. It will
                    ask you a confirmation. If the user click &lsquo;Yes&rsquo;, this may ask you to run a file </span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >with JRE. If you run the file with JRE,the server will be started in your
                    system. Thus the operator can view your Desktop from the other end.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;The &lsquo;Mail Chat Transcript&rsquo; button is to send the chat log to any email
                    id. If you click on this button, it will ask you to enter the email address to
                    which the chat logs are to be sent. Then click on the send button.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;The &lsquo;Print Chat Transcript &rsquo; button is to print the chat logs.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp;You can rate the current support using this button. If you click on this
                    button, it will ask you to select a rate from the range 1-10. You can also
                    enter your comments about this support in the &lsquo;Comments&rsquo; section. After
                    entering the details, click on the submit button.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >&nbsp;&nbsp;&nbsp;&nbsp; The &lsquo;Exit&rsquo; is used to end the Chat session.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font color="#010101" ></font></div><div align="left" ><br></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div>

</body></html>
