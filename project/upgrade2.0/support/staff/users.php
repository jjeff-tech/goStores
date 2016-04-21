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
		
        include("./languages/".$_SP_language."/users.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>



<title><?php echo HEADING_USER_DETAILS ?></title>
<?php include("./includes/headsettings.php"); ?>
<link href="./../styles/calendar.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript">
<!--
      
	function clickSearch() {
			document.frmDetail.numBegin.value=0;
			document.frmDetail.begin.value=0;
			document.frmDetail.start.value=0;
			document.frmDetail.num.value=0;
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
						//include("./includes/staffheader.php");
				?>
				<!--  end admin header -->

                  <!-- Personal notes Section -->

                  <?php
                          include("./includes/users.php");
                  ?>

                  <!-- End Personal notes Section  -->

                  
				 

       

		</div>
		
		
          
      
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
   
</body>
</html>