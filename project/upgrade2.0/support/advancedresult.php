<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>        		                  |
// |          									                          |
// +----------------------------------------------------------------------+
        require_once("./includes/applicationheader.php");
        include("./languages/".$_SP_language."/advancedresult.php");
        $conn = getConnection();

?>

<?php include("./includes/docheader.php"); ?>
<title><?php echo HEADING_ADVANCED_RESULT ?></title>
<?php include("./includes/headsettings.php"); ?>
<script language="javascript" type="text/javascript" src="scripts/jquery.js"></script>
<script language="javascript" type="text/javascript">
<!--
	function clickSearch() {
		if (validateSearch() == true) {
			document.frmSearch.method="post";
			document.frmSearch.submit();
		}
	}
	
	function validateSearch() {
		var frm = document.frmSearch;	
		if ($.trim(frm.txtRefno.value) == "" && $.trim(frm.txtTitle.value) == "" && frm.cmbStatus.value == "" && frm.cmbPriority.value == "" && frm.txtFrom.value == "" && frm.txtTo.value == "") {
			return false;
		}
		else {
			return true;
		}
	}
-->
</script>
<link href="./styles/calendar.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="./scripts/calendar.js"></script>
    <script type="text/javascript" src="./scripts/calendar-setup.js"></script>
    <script type="text/javascript" src="./languages/<?php echo $_SP_language; ?>/calendar.js"></script>
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
                <!-- Tickets Assigned Section -->	
                <?php
                          include("./includes/advancedresult.php");
                ?>
                <!-- End Tickets Assigned  section -->
                <!-- Advanced Search -->
                <?php
                          include("./includes/advancedsearch.php");
                ?>
                <!-- End Advanced Search -->

</div>
    
      
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
   
</body>
<script>
<!-- 
	var frm = document.frmSearch;
	frm.txtRefno.value=refno;
	frm.txtTitle.value=ttl;
	frm.cmbStatus.value=status;
	frm.cmbPriority.value=prio;
	frm.txtFrom.value=from;
	frm.txtTo.value=to;
-->	
</script>
</html>