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
        <meta name="keywords"  content="Topic 1," >
        <?php
        include("../../../includes/constants.php");
        include("../../../includes/headsettings.php"); ?>
    </head>
    <body bgcolor="FFFCEA" >
        <table width="100%"  border="0"  cellspacing="0"  cellpadding="2" class="header_row" >
            <tr>
                <td align="left" >
                    <div align="left" ><font face="Verdana"  color="#010101"  size="4" ><span style="font-size:14pt" >Introduction</span></font><font color="#010101" ></font></div>

                </td>
                <td align="right" >
                    <font face="Arial"  size="2" >
                        &nbsp;&nbsp;<a href="features.htm">Next</a>
                    </font>
                </td>
            </tr></table>
        <hr><div align="left" ><br></div><font color="#010101" ><div align="left" ><br></font></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >The iScripts SupportDesk is an integrated system for managing the customer
                    inquiries, answers and communications resulting from such queries and
                    related problems with the help of an array of tools and facilities clustered
                    around the ticket management system like the knowledge base system,
                    reminder system, mailing and messaging system, and email piping system
                    to mention a few.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >The potential users of the system are users (customers), staffs (technicians),
                and administrators.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >Users/Customers communicate with the system to get their problems and
            questions solved. They usually look up in the relevant FAQ section to see
            whether a similar problem (and solution) is listed there already.&nbsp; If the user
            is not able to find a matching one, s/he posts a ticket with the necessary
            details. The staffs (technicians) respond to the tickets with a predefined
            reply or a newly created one, according to the situation. The main
            functionality of the system is this to and fro communication between the
            user and staff. In the case a staff is unable to resolve a problem s/he can
            escalate the problem to the administrator.</span></font><font color="#010101" ></font></div><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ><br></div><font color="#010101" ><div align="left"  style="margin-left:13mm; margin-right:0mm; text-indent:0mm; " ></font><font face="Verdana"  color="#010101" ><span style="font-size:10pt" >Another major feature of iScripts Support System is LiveChat. Besides the
        ticket management system, customers can chat with the operators for any
        queries. Inter operator chat facility is also available in this system. If
        needed, customers can share their Computer Desktop with the operator
        whom they are chatting with through Remote Desktop Sharing feature. This
        will help them to solve their issues easily. Customers can select a particular
        department of the company and is able to chat with the staffs of that
        particular department. </span></font><font color="#010101" ></font></div><div align="left" ><br></div><font color="#010101" ><div align="left" ><br></font></div><font color="#010101" ><div align="left" ></font><font color="#010101" ></font></div>

    </body></html>
