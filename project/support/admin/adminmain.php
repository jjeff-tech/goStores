<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshith<roshith@armia.com>                                  |
// |                                                                      |
// +----------------------------------------------------------------------+
require_once("./includes/applicationheader.php");
include("./includes/functions/miscfunctions.php");

if($_POST["postback"] == "CL") {

    $selectedLanguage = $_POST["cmbLan"];

    // Update Language to lookup
    $updateQry = "UPDATE sptbl_lookup SET vLookUpValue='".$selectedLanguage."' WHERE vLookUpName='DefaultLang'";
    $updateRes = executeSelect($updateQry,$conn);

    $_SESSION["sess_language"] = $selectedLanguage;
    $_SESSION["sess_adminlangchange"] ="1";
    header("location:adminmain.php");
    exit;
}

include("./languages/".$_SP_language."/adminmain.php");
$conn = getConnection();
$sql = "Select vLookUpName,vLookUpValue from sptbl_lookup WHERE vLookUpName IN('LangChoice',
                                'DefaultLang','HelpdeskTitle','Logourl','vLicenceKey')";
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
            case "vLicenceKey":
                $_SESSION["sess_licensekey"] = $row["vLookUpValue"];
                break;
        }
    }
}
mysql_free_result($rs);
if($_SESSION["sess_adminlangchange"] =="1") {
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


?>
<?php include("../includes/docheader.php"); ?>
<title><?php echo HEADING_ADMIN_MAIN ?></title>
<?php include("./includes/headsettings.php"); ?>

</head>

<body>
    <!--  Top Part  -->
    <?php  include("./includes/top.php"); ?>
    <!--  Top Ends  -->
    <!-- header  -->
    <?php include("./includes/header.php"); ?>
    <!-- end header -->

    <div class="content_column_small">
        <?php  include("./includes/adminside.php");  ?>
    </div>

    <div class="content_column_big">
        <?php  include("./includes/adminmain.php"); ?>
    </div>


    <!-- Main footer -->
    <?php
    include("./includes/mainfooter.php");
    ?>
    <!-- End Main footer -->

</body>
</html>