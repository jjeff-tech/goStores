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
	include("./languages/".$_SP_language."/vwreminder.php");
    $conn = getConnection();
?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_VW_REMINDER?></title>
<?php include("./includes/headsettings.php"); ?>
<script type="text/javascript" src="../scripts/jquery.js"></script>
<script>
<!-- 
	function add() {
		if (document.frmReminder.id.value.length <= 0) {	
			if (validateForm(0) == true) {
				document.frmReminder.postback.value="A";
				document.frmReminder.method="post";
				document.frmReminder.submit();
			}
		}
		else {
			alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
		}	
	}
	
	function edit() {
		if (document.frmReminder.id.value.length > 0) {	
			if (validateForm(1) == true) {
				document.frmReminder.postback.value="U";
				document.frmReminder.method="post";
				document.frmReminder.submit();
			}
		}
		else {
			alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
		}	
	}
	
	function deleted() {
		if (document.frmReminder.id.value.length > 0) {
			if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {	
				document.frmReminder.postback.value="D";
				document.frmReminder.method="post";
				document.frmReminder.submit();
			}
		}
		else {
			alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
		}
			
	}
	
	function validateForm(i)
	{
        var frm = window.document.frmReminder;
		var flag = false; 
		if ($.trim(frm.txtTitle.value) == '') {
		    frm.txtTitle.value = '';
			alert('Please enter the title.');
			frm.txtTitle.focus();
		}
		else if ($.trim(frm.txtDesc.value) == ""){
                frm.txtDesc.value = '';
				alert('Please enter the description.');
                frm.txtDesc.focus();
        }
		else {
			flag = true;
		}
		if (flag == false) {
			return false;
		}
		else {
	        return true;
		}
}



	function cancel()
	{
		var frm = document.frmReminder;
		frm.txtTitle.value = "";
		frm.txtDesc.value = "";
		frm.txtStaff.value = frm.uname.value;
		frm.id.value = "";
		frm.btAdd.disabled = false;
		frm.btDelete.disabled = true;
		frm.btUpdate.disabled = true;
	}
-->	
</script>
<link href="./../styles/calendar.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="./../scripts/calendar.js"></script>
    <script type="text/javascript" src="./../scripts/calendar-setup.js"></script>
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
                                include("./includes/adminside.php");
                        ?>

                   <!-- End of side links -->


		</div>
		
		
		<div class="content_column_big">
		
		
				<!-- admin header -->
				<?php
						//include("./includes/adminheader.php");
				?>
				<!--  end admin header -->
                <!-- Detail Section -->
                <?php
                        include("./includes/vwreminder.php");
                ?>
	            <!-- End Detail section -->
		
		
		</div>

		  
      
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
    
</body>
</html>