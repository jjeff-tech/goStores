<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: johnson<johnson@armia.com>                                  |
// +----------------------------------------------------------------------+
    require_once("./includes/applicationheader.php");
    include "languages/".$_SP_language."/knowledgebase.php";
    $conn = getConnection();
   
?>
<?php include("./includes/docheader.php"); ?>
<title><?php echo HEADER_KB;?></title>
<?php include("./includes/headsettings.php"); ?>
<?php include("./includes/setkbmetatag.php"); ?>
<!---------------------------------------RATING POP UP STYLE AND SCRIPT------------------------------->


<script language="javascript" src="<?php echo SITE_URL; ?>scripts/jquery.js"></script>
<script language="javascript" src="<?php echo SITE_URL; ?>scripts/jquery-rating.js"></script>
<script language="javascript" src="<?php echo SITE_URL; ?>scripts/rating.js"></script>
<!---------------------------------------RATING POP UP STYLE AND SCRIPT------------------------------->
<script  type="text/javascript">

function goBack() {
        document.frmKB.action = "<?php echo $_SESSION['sess_backurl'];?>";
        document.frmKB.method="post";
        document.frmKB.submit();
}

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
        include("./includes/userside.php");
        ?>
        <!-- End of side links -->
    </div>

    <div class="content_column_big">
        <!-- admin header -->
        <?php
        // include("./includes/userheader.php");
        ?>
        <!--  end admin header -->
        <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Detail Section %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
        <?php

        if(userLoggedIn()) {
            //************************************** User Logged In *************************************************** -->
            include("./includes/viewkbentry.php");
            //************************************** User Logged In ******************************************* -->

        }else {
            ;
        }
        ?>
        <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End Detail Section %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->

    </div>


    <!-- Main footer -->
    <?php
    include("./includes/mainfooter.php");
    ?>
    <!-- End Main footer -->
</td>
</tr>
</table>
</body>
</html>