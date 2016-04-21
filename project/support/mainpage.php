<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>        		                  |
// |          									                          |
// +----------------------------------------------------------------------+

//echo '<pre>'; print_r($_REQUEST); echo '</pre>'; exit;
require_once("./includes/applicationheader.php");
include("./languages/".$_SP_language."/tickets.php");
$conn = getConnection();
$page = 'userhome';

?>
<?php include("./includes/docheader.php"); ?>
<title><?php echo HEADING_TICKETS ?></title>
<?php include("./includes/headsettings.php"); ?>
<link href="./styles/calendar.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="./scripts/jquery.js"></script>
<script type="text/javascript" src="./scripts/calendar.js"></script>
<script type="text/javascript" src="./scripts/calendar-setup.js"></script>
<script type="text/javascript" src="./languages/<?php echo $_SP_language; ?>/calendar.js"></script>
<script type="text/javascript">
    $(function(){
<?php 
if($_POST["cmbSearch"] == "dt") {
    ?>
                        $("input#txtSearch").attr('readonly', true);
                        Calendar.setup({
                            inputField    : "txtSearch",
                            button        : "txtSearch",
                            ifFormat      : "%m-%d-%Y",
                            cache         : true
                        });
    <?php
}
?>
            });

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

            function clickSearch(){
                //if($.trim(document.frmDetail.txtSearch.value) != ''){
                document.frmDetail.numBegin.value=0;
                document.frmDetail.begin.value=0;
                document.frmDetail.num.value=0;
                document.frmDetail.start.value=0;
                document.frmDetail.method="post";
                document.frmDetail.submit();
                //}
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
        <?php

        include("./includes/mainpage.php");
        ?>
        <!-- admin header -->
        <?php
        // include("./includes/userheader.php");
        ?>
        <!--  end admin header -->
        <!-- Tickets Assigned Section -->
        <?php
        //   include("./includes/tickets.php");
        ?>

        <!-- End Tickets Assigned  section -->
        <!-- Advanced Search -->


        <?php
        // include("./includes/advancedsearch.php");
        ?>

        <!-- End Advanced Search -->


    </div>



    <!-- Main footer -->
    <?php
    include("./includes/mainfooter.php");
    ?>
    <!-- End Main footer -->

</body>
</html>