<?php

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
