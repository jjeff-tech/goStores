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
// |                                                                      |
// +----------------------------------------------------------------------+
        

        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/viewfeedback.php");
        $conn = getConnection();

?>

    <?php include("../includes/docheader.php"); ?>
<title><?php echo HEADING_VIEW_FEEDBACK ?></title>

<?php include("./includes/headsettings.php"); ?>


<script>
<!--
        function clickSearch() {
                if (validateSearch() == true) {
                        document.frmDetail.method="post";
                        document.frmDetail.submit();
                }
        }
		function goBack() {
			document.frmDetail.action = "<?php echo $_SESSION['sess_backurl'];?>";
			document.frmDetail.method="post";
			document.frmDetail.submit();
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

      
                          <!-- sidelinks -->
<div class="content_column_small">
                          <?php
                                include("./includes/adminside.php");
                        ?>
</div>
                   <!-- End of side links -->
         
				<!-- admin header -->
				<?php
						//include("./includes/adminheader.php");
				?>
				<!--  end admin header -->
             
			    <!-- Detail Section -->
				<div class="content_column_big">                                    
                <?php
                          include("./includes/viewfeedback.php");
                ?>                                        
				</div>
		        <!-- End Detail section -->
          
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
   
</body>
</html>