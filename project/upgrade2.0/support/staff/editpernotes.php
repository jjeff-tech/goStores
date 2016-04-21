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
		
        include("./languages/".$_SP_language."/editpernotes.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>


<title><?php echo HEADING_EDIT_PERSONAL_NOTES ?></title>
<?php include("./includes/headsettings.php"); ?>
<script language="javascript" type="text/javascript">
<!-- 
function edit() {
		if (document.frmPersonalNotes.id.value.length > 0) {	
			if (validateForm() == true) {
				document.frmPersonalNotes.postback.value="U";
				document.frmPersonalNotes.method="post";
				document.frmPersonalNotes.submit();
			}
		}
		else {
			alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
		}	
	}
	
	function deleted() {
		if (document.frmPersonalNotes.id.value.length > 0) {
			if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {	
				document.frmPersonalNotes.postback.value="D";
				document.frmPersonalNotes.method="post";
				document.frmPersonalNotes.submit();
			}
		}
		else {
			alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
		}
			
	}
	
function validateForm()
	{
        var frm = window.document.frmPersonalNotes;
		var flag = false; 
		if (frm.txtPerTitle.value.length == 0) {
		   alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
			frm.txtPerTitle.focus();
//			return false;
		}else if (frm.txtNotes.value.length == 0) {
		   alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
			frm.txtNotes.focus();
//			return false;
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
		var frm = document.frmPersonalNotes;
		frm.txtNotes.value = "";
		frm.txtPerTitle.value = "";
		
		frm.btDelete.disabled = true;
		frm.btUpdate.disabled = true;
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
                          include("./includes/editpernotes.php");
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