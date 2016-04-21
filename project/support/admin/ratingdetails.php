<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>                            |
// |                                                                                                            |
// +----------------------------------------------------------------------+

        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/ratingdetails.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>
<title><?php echo HEADING_RATING_DETAILS?></title>
<?php include("./includes/headsettings.php"); ?>
</head>

<body >
<style>
    .unreadTK td{
        font-weight: normal !important;
}
</style>
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
		
				  <?php
						//include("./includes/adminheader.php");
				?>
				<!--  end admin header -->
                <!-- Detail Section -->
                <?php
                          include("./includes/ratingdetails.php");
                ?>

		</div>
   
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
   
</body>
</html>