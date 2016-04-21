<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>                          |
// |                           |
// +----------------------------------------------------------------------+
        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
		
        include("./languages/".$_SP_language."/actionlog.php");
        $conn = getConnection();

?>

<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_ACTIVITY_LOG ?></title>
<?php include("./includes/headsettings.php"); ?>
<script language="javascript" type="text/javascript">
<!-- 
	
	function clickSearch() {
			document.frmActivity.numBegin.value=0;
			document.frmActivity.begin.value=0;
			document.frmActivity.start.value=0;
			document.frmActivity.num.value=0;
			document.frmActivity.method="post";
			document.frmActivity.submit();
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

        <!--  Top links  -->

        <?php
//                 include("./includes/toplinks.php");
         ?>

        <!--  End Top Links -->

        <!-- header  -->
    <?php
                include("./includes/header.php");
        ?>
        <!-- end header -->

          <!-- end header -->

<div class="content_column_small">

					<!-- sidelinks -->

                          <?php
                                include("./includes/staffside.php");
                        ?>

                   <!-- End of side links -->


</div>


<div class="content_column_big">




				<!-- admin header -->
				<?php
						///include("./includes/staffheader.php");
				?>
				<!--  end admin header -->

                  <!-- Activity log -->
                  <?php
                          include("./includes/actionlog.php");
                  ?>
                  <!-- End Activity log  -->
         



</div>

	  
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
    
</body>
</html>