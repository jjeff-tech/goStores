<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>                                          |
// |                                                                                                            |
// +----------------------------------------------------------------------+
        require_once("./includes/applicationheader.php");
        //include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/search.php");
        $conn = getConnection();

?>
<?php include("./includes/docheader.php"); ?>
<title><?php echo HEADING_SEARCH ?></title>
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
				
				<!--<div class="content_tab_container">
				<ul>
				<li><a href="tickets.php?mt=y&tp=o&stylename=VIEWTICKETS&styleplus=oneplus&styleminus=oneminus&"  ><?php echo TEXT_OPEN_TICKETS?></a></li>
				<li><a href="tickets.php?mt=y&tp=c&stylename=VIEWTICKETS&styleplus=oneplus&styleminus=oneminus&"  ><?php echo TEXT_CLOSED_TICKETS?></a></li>
				<?php if($var_statusRow>0) { ?>
                                <?php
                                 // Include  Additional Ticket Status Links Modified By Asha On 26-09-2012
                                if($var_statusRow>0){

                                 while($tRow =mysql_fetch_array($rsExtraStat)) {
                                     $status= $tRow['vLookUpValue'];
                                 ?>
                                <li><a id="<?php echo $status;?>" href="tickets.php?mt=y&tp=<?php echo $status;?>&stylename=VIEWTICKETS&styleplus=oneplus&styleminus=oneminus&" ><?php echo $status;?></a></li>
                                <?php
                                    }
                                 }
                                // End Include Extra Links for Ticket Status
                            ?>
                         <?php } ?>
                                <li><a href="tickets.php?mt=y&tp=a&stylename=VIEWTICKETS&styleplus=oneplus&styleminus=oneminus&"  ><?php echo TEXT_VIEW_ALL_TICKETS?></a></li>
				<li><a class="selected" href="search.php?mt=y&<?php echo ("stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus&"); ?>" ><?php echo TEXT_SEARCH_BY_TICKET_ID?></a></li>
				</ul>
				</div>
	-->
	
      
                                        <!-- end of top links -->
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
</html>