<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: programmer1<programmer1@armia.com>                          |
// |          programmer1<programmer2@armia.com>                          |
// +----------------------------------------------------------------------+
        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SESSION["sess_language"]."/knowledgebase.php");
        $conn = getConnection();
	$page = 'KB';
		/*foreach ($_SESSION as $key => $val ){
			echo "Key: $key \t Value: $val<br>";
		}*/
		
?>

<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_STAFF_MAIN ?></title>
<?php include("./includes/headsettings.php"); ?>
<script language="javascript" type="text/javascript">
<!--
		
function clickSearch() {
	document.frmKB.numBegin.value=0;
	document.frmKB.begin.value=0;
	document.frmKB.start.value=0;
	document.frmKB.num.value=0;
	document.frmKB.method="post";
	document.frmKB.postback.value="S";
	document.frmKB.submit();
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
                  <!-- Tickets Assigned Section -->
                  <?php
                          include("./includes/knowledgebase.php");
                  ?>
                  <!-- End Tickets Assigned  section -->
         
			
			
			</div>
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
    
</body>
</html>