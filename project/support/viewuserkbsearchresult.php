<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: 			*/
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshith<roshith@armia.com>             		              |
// |          										                      |
// +----------------------------------------------------------------------+
require_once("./includes/applicationheader.php");

include "languages/".$_SP_language."/knowledgebase.php";



if($_POST["post_back"] == "CL") {
    $_SESSION["sess_language"] = $_POST["cmbLan"];
    $_SESSION["sess_userlangchange"] = "1";
    header("location:index.php");
    exit;
}

$conn = getConnection();

$sql = "Select vLookUpName,vLookUpValue from sptbl_lookup WHERE vLookUpName IN('LangChoice',
                                'DefaultLang','HelpdeskTitle','Logourl','logactivity','PostTicketBeforeLogin','SMTPSettings','SMTPServer','SMTPPort')";
$rs = executeSelect($sql,$conn);
if(!isset($_SESSION['sess_cssurl'])) {
    $_SESSION['sess_cssurl']="styles/AquaBlue/style.css";
}
if (mysql_num_rows($rs) > 0) {
    while($row = mysql_fetch_array($rs)) {
        switch($row["vLookUpName"]) {
            case "LangChoice":
                $_SESSION["sess_langchoice"] = $row["vLookUpValue"];
                break;
            case "DefaultLang":
                $_SESSION["sess_defaultlang"] = $row["vLookUpValue"];
                break;
            case "HelpdeskTitle":
                $_SESSION["sess_helpdesktitle"] = $row["vLookUpValue"];
                break;
            case "Logourl":
                $_SESSION["sess_logourl"] = $row["vLookUpValue"];
                break;
            case "logactivity":    //this session variable decides to log activities or not
                $_SESSION["sess_logactivity"] = $row["vLookUpValue"];
                break;
            case "PostTicketBeforeLogin":
                $_SESSION["sess_postticket_before_register"] = $row["vLookUpValue"];
                break;
            case "SMTPSettings":
                $_SESSION["sess_smtpsettings"] = $row["vLookUpValue"];
                break;
            case "SMTPServer":
                $_SESSION["sess_smtpserver"] = $row["vLookUpValue"];
                break;
            case "SMTPPort":
                $_SESSION["sess_smtpport"] = $row["vLookUpValue"];
                break;
        }
    }
}

mysql_free_result($rs);
if($_SESSION["sess_userlangchange"] =="1") {
    ;
}else {
    if (($_SESSION["sess_defaultlang"] !=$_SESSION["sess_language"])) {
        $_SESSION["sess_language"] = $_SESSION["sess_defaultlang"];
        echo("<script>window.location.href='index.php'</script>");
        exit();

        //header("location:index.php");
        //exit;
    }
}
if(userLoggedIn()) {
    header("location:tickets.php?mt=y&tp=a&stylename=VIEWTICKETS&styleplus=oneplus&styleminus=oneminus&");
    exit;
}

?>
<?php
include("./includes/docheader.php");
include("./includes/functions/functions.php");

$kbId        = $_REQUEST['id'];
$kbDataQuery = mysql_query("SELECT vKBTitle FROM sptbl_kb WHERE nKBID=".$kbId);
$kbData      = mysql_fetch_assoc($kbDataQuery);
//echo '<pre>';print_r($kbData); echo '</pre>';
$title = ($kbData['vKBTitle'])? $kbData['vKBTitle']:$_SESSION["sess_helpdesktitle"];
?>
<title><?php echo $title;?></title>
<?php include("./includes/headsettings.php"); ?>

<link href="./styles/calendar.css" rel="stylesheet" type="text/css">
<!--<script type="text/javascript" src="./scripts/jquery.js"></script>-->
</head>

<body>

    <!-- header  -->
    <?php  include("./includes/header.php");  ?>
    <!-- end header -->

    <div class="content_column_small">
        <?php
        include("./includes/userside.php");
        ?>

    </div>

    <div class="content_column_big">
        <?php   include("./includes/viewuserkbsearchresult.php");  ?>
    </div>

    <!-- Main footer -->
    <?php
    include("./includes/mainfooter.php");
    ?>
    <!-- End Main footer -->

</body>
</html>