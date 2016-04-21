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
//error_reporting(E_ALL);

require_once("./includes/applicationheader.php"); 

include "languages/".$_SP_language."/index.php";

$backUrlRaw = explode("/",$_REQUEST['backUrl']);


if (in_array('KNOWLEDGEBASE', $backUrlRaw, true)) {
    $backUrl = $_REQUEST['backUrl'];
}else if($_GET['email']!="" && $_GET['ref']!="" ){
    $backUrl = "mainpage.php?mt=y&email=".$_GET['email']."&ref=".$_GET['ref'];
}else if($_REQUEST['ticketId']){
    $backUrl = "rating.php?ticket_id=".$_REQUEST['ticketId'];
}else{
    $backUrl = "";
}

//echo '<pre>'; print_r($backUrlRaw); echo '</pre>';exit;
/*
if($_POST["post_back"] == "CL") {
    $_SESSION["sess_language"] = $_POST["cmbLan"];
    $_SESSION["sess_userlangchange"] = "1";
    header("location:index.php");
    exit;
} */

$conn = getConnection();

$sql = "Select vLookUpName,vLookUpValue from sptbl_lookup WHERE vLookUpName IN('LangChoice',
                                'DefaultLang','HelpdeskTitle','Logourl','logactivity','PostTicketBeforeLogin','SMTPSettings','SMTPServer','SMTPPort','Theme')";
$rs = executeSelect($sql,$conn);

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
            case "Theme":
                $theme_id = $row["vLookUpValue"];
                $sql = "Select vCSSURL from sptbl_css where nCSSId='".addslashes($theme_id)."'";
                $result = executeSelect($sql,$conn);
                if (mysql_num_rows($result) > 0) {
                    $row = mysql_fetch_array($result);
                    //unset($_SESSION['sess_cssurl']);
                    $_SESSION["sess_cssurl"] = $row["vCSSURL"];
                }
                break;
        }
    }
}
if(!isset($_SESSION['sess_cssurl'])) {
    $_SESSION['sess_cssurl']="styles/AquaBlue/style.css";
}


mysql_free_result($rs);
if($_SESSION["sess_userlangchange"] =="1") {
    ;
}else {
    if (($_SESSION["sess_defaultlang"] !=$_SESSION["sess_language"])) {
        $_SESSION["sess_language"] = $_SESSION["sess_defaultlang"];
        echo("<script>window.location.href='index.php'</script>");
        exit();
        
    }
}
if (userLoggedIn()) {
    header("location:support/mainpage.php");
    exit;
}else{
    header("Location:signup");
    exit;
}


?>
<?php include("./includes/docheader.php");  ?>
<title><?php echo $_SESSION["sess_helpdesktitle"];?></title>
<?php include("./includes/headsettings.php");  ?>
<link href='http://fonts.googleapis.com/css?family=Open+Sans|Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
<link href="./styles/calendar.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="./scripts/jquery.js"></script>
<script type="text/javascript" src="./scripts/calendar.js"></script>
<script type="text/javascript" src="./scripts/calendar-setup.js"></script>
<script type="text/javascript" src="./languages/<?php echo $_SP_language; ?>/calendar.js"></script>
<script type="text/javascript" src="./scripts/jquery.alerts.js"></script>
<link href="./styles/jquery.alerts.css" rel="stylesheet" type="text/css">

<script language="javascript" type="text/javascript">

    function clickSearch() {
        document.frmDetail.numBegin.value=0;
        document.frmDetail.begin.value=0;
        document.frmDetail.num.value=0;
        document.frmDetail.start.value=0;
        document.frmDetail.method="post";
        document.frmDetail.submit();
    }

    function clickDelete() {
        var i=1;
        var flag = false;
        try        {
            for(i=1;i<=10;i++) {
                if(eval("document.frmDetail.c" + i + ".checked") == true) {
                    flag = true;
                    break;
                }
            }
            if(flag == true) {
                document.frmDetail.method="post";
                document.frmDetail.submit();
            }
        }catch(e) {}
    }
    function clickEdit() {
        //alert('Under construction');
    }


    function setCal(val){

        $("input#txtSearch").val('');
        if(val == 'dt'){
            $("input#txtSearch").attr('readonly', true);

            Calendar.setup({
                inputField    : "txtSearch",
                button        : "txtSearch",
                ifFormat      : "%m-%d-%Y",
                cache         : true
            });
        }else{
            $("input#txtSearch").attr('readonly', false);
            Calendar.setup({
                inputField    : "txtSearch",
                button        : "txtSearch",
                ifFormat      : "%m-%d-%Y",
                cache         : true,
                Destroy       : true
            });
        }
    }
</script>
<style>
    div.contentWrap{ height: 495px !important; }
</style>
</head>

<body>

    <!-- header  -->
    <?php  include("./includes/home_header.php");  ?>
    <!-- end header -->


    <?php   
    include("./includes/home_userside.php");
    ?>

    <!-- Main footer -->
    <?php
    include("./includes/mainfooter.php");
    ?>
    <!-- End Main footer -->

</body>
</html>