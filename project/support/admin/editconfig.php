<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>                                  |
// |                                                                                                            |
// +----------------------------------------------------------------------+
require_once("./includes/applicationheader.php");
include("./includes/functions/miscfunctions.php");
include("./languages/".$_SP_language."/editconfig.php");
$conn = getConnection();
$page = "editconfig";
?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_EDIT_CONFIG?></title>
<?php
//update css
$sql = "Select vCSSURL from sptbl_css where nCSSId='".addslashes(trim($_POST['ddlCSS']))."'";
$result = executeSelect($sql,$conn);
if (mysql_num_rows($result) > 0) {
    $row = mysql_fetch_array($result);
    unset($_SESSION['sess_cssurl']);
    $_SESSION["sess_cssurl"] = $row["vCSSURL"];
}
//update css
?>
<?php include("./includes/headsettings.php"); ?>
<script src="../scripts/jquery.tooltip.js" type="text/javascript"></script>
<link rel="stylesheet" href="../styles/jquery.tooltip.css" />
<link rel="stylesheet" href="../styles/screen.css" />
<script>
    <!--
    function edit() {
        if (validateForm() == true) {
            document.frmConfig.postback.value="U";
            document.frmConfig.method="post";
            document.frmConfig.submit();
        }
    }

    function validateForm()
    {
        var frm = window.document.frmConfig;
        var flag = false;
        if (frm.txtSiteURL.value.length == 0) {
            alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
            frm.txtSiteURL.focus();
        }
        else if (frm.txtHelpDeskURL.value == ""){
            alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
            frm.txtHelpDeskURL.focus();
        }
        else if ((frm.rdSMTP[0].checked == true) && (frm.txtSMTPServer.value=="")){
            alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
            frm.txtSMTPServer.focus();
        }
        else if ((frm.rdSMTP[0].checked == true) && (frm.txtSMTPPort.value=="")){
            alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
            frm.txtSMTPPort.focus();
        }

        else {
            flag = true;
        }
        if (flag == false) {
            return false;
        }else {
            return true;
        }
    }

    function checkMail(email)
    {
        var str1=email;
        var arr=str1.split('@');
        var eFlag=true;
        if(arr.length != 2)
        {
            eFlag = false;
        }
        else if(arr[0].length <= 0 || arr[0].indexOf(' ') != -1 || arr[0].indexOf("'") != -1 || arr[0].indexOf('"') != -1 || arr[1].indexOf('.') == -1)
        {
            eFlag = false;
        }
        else
        {
            var dot=arr[1].split('.');
            if(dot.length < 2)
            {
                eFlag = false;
            }
            else
            {
                if(dot[0].length <= 0 || dot[0].indexOf(' ') != -1 || dot[0].indexOf('"') != -1 || dot[0].indexOf("'") != -1)
                {
                    eFlag = false;
                }

                for(i=1;i < dot.length;i++)
                {
                    if(dot[i].length <= 0 || dot[i].indexOf(' ') != -1 || dot[i].indexOf('"') != -1 || dot[i].indexOf("'") != -1)
                    {
                        eFlag = false;
                    }
                }
                if(dot[i-1].length > 4)
                    eFlag = false;
            }
        }
        return eFlag;
    }

    function cancel()
    {
        var frm = document.frmConfig;
        frm.txtSiteURL.value = "";
        frm.txtHelpDeskURL.value = "";
        // frm.txtPost2PostGap.value = "";
    }
    -->
</script>
</head>

<body>
    <!--  Top Part  -->
    <?php
    include("./includes/top.php");
    ?>
    <!--  Top Ends  -->
    <!-- header  -->
    <?php
    include("./includes/header.php");
    ?>
    <!-- end header -->


    <div class="content_column_small">

        <!-- sidelinks -->
        <?php
        include("./includes/adminside.php");
        ?>
        <!-- End of side links -->


    </div>


    <div class="content_column_big">


        <!-- admin header -->
        <?php
        //include("./includes/adminheader.php");
        ?>
        <!--  end admin header -->
        <!-- Detail Section -->
        <?php
        include("./includes/editconfig.php");
        ?>
        <!-- End Detail section -->



    </div>




    <!-- Main footer -->
    <?php
    include("./includes/mainfooter.php");
    ?>
    <!-- End Main footer -->

</body>
</html>