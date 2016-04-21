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
        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/viewticket.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>
<title><?php echo HEADING_VIEW_TICKET ?></title>
<?php include("./includes/headsettings.php"); ?>
<script language="javascript" type="text/javascript">
   function clickReplyNext() // function called when clicking 'reply' button
{


    var action = $("#frmReply").attr('action')+"&next=1";
    $("#frmReply").attr('action',action);
     document.frmReply.submit();
//	window.location.href='<?php echo "replies.php?next=1&rp=r&tk=$var_ticketid&rid=0&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus";?>';
}
<!--
	function clickSearch() {
		if (validateSearch() == true) {
			document.frmDetail.method="post";
			document.frmDetail.submit();
		}
	}

	function validateSearch() {
		var frm = document.frmDetail;
		if (frm.cmbCompany.value == "" && frm.txtDepartment.value == "" && frm.txtStatus.value == "" && frm.txtOwner.value == "" && frm.txtUser.value == "" && frm.txtTicketNo.value == "" && frm.txtTitle.value == "" && frm.txtLabel.value == "" && frm.txtQuestion.value == "" && frm.txtFrom.value == "" && frm.txtTo.value == "") {
			return false;
		}
		else {
			return true;
		}
	}
	function clickUpdate() {
		var frm = document.frmDetail;
		frm.mt.value="u";
		frm.method="post";
		frm.submit();
	}

	function deleteTickets(chk) {
              var frm = document.frmDetail;
			  if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
			    frm.mt.value="D";
			    frm.delid.value=chk;
				document.frmDetail.method="post";
				document.frmDetail.submit();
			  }

	  }
-->
</script>
<link href="./../styles/calendar.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="./../scripts/calendar.js"></script>
    <script type="text/javascript" src="./../scripts/calendar-setup.js"></script>
    <script type="text/javascript" src="./languages/<?php echo $_SP_language; ?>/calendar.js"></script>
	<script type="text/javascript" src="./includes/functions/ajax.js"></script>	
</head>

<body>
<!-- Ajax tool tip-->
<div id="tooltipBox" onMouseOver="clearAdInterval();" onMouseOut="hideAd();" style="z-index:5000;position:absolute;cursor:pointer;"></div>
<!--end  Ajax tool tip-->

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

          <div class="content_column_small"> <!-- sidelinks -->

                          <?php
                                include("./includes/adminside.php");
                        ?>

                   <!-- End of side links --></div>


<div class="content_column_big"><!-- admin header -->
				<?php
						//include("./includes/adminheader.php");
				?>
				<!--  end admin header -->
                <!-- Tickets Assigned Section -->
                <?php
                      include("./includes/viewticket.php");
                ?>
                <!-- End Tickets Assigned  section --></div>
     
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
    
</body>
<script>
	document.frmDetail.cmbOwner.value=own;
	document.frmDetail.cmbDepartment.value=dept;
	document.frmDetail.txtCreated.value=ctd;
	document.frmDetail.txtUpdate.value=lstupdate;
	document.frmDetail.txtReplier.value=lstreplier;
	document.frmDetail.cmbStatus.value=st;
	document.frmDetail.cmbLock.value=lck;
        document.frmDetail.txtClosed.value=lstclosed;
</script>
</html>