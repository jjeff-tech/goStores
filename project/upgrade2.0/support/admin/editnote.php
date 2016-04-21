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
// |                                                                        // |
// +----------------------------------------------------------------------+


        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/editnote.php");
        $conn = getConnection();


?>
<html>
<head>
<title><?php echo HEADING_EDIT_NOTE ?></title>
<?php include("./includes/headsettings.php"); ?>
<script>
<!--
		
		function goBack() {
			document.frmNote.action = "<?echo $_SESSION['sess_backurl'];?>";
			document.frmNote.method="post";
			document.frmNote.submit();
		}

		 function add() {
		if (document.frmNote.id.value.length <= 0) {	
			if (validateForm(0) == true) {
				document.frmNote.postback.value="A";
				document.frmNote.method="post";
				document.frmNote.submit();
			}
		}
		else {
			alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
		}	
	}
	
	function edit() {
		if (document.frmNote.id.value.length > 0) {	
			if (validateForm(1) == true) {
				document.frmNote.postback.value="U";
				document.frmNote.method="post";
				document.frmNote.submit();
			}
		}
		else {
			alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
		}	
	}
	
	function deleted() {
		if (document.frmNote.id.value.length > 0) {
			if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {	
				document.frmNote.postback.value="D";
				document.frmNote.method="post";
				document.frmNote.submit();
			}
		}
		else {
			alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
		}
			
	}
	
	function validateForm(i)
	{
        var frm = window.document.frmNote;
		var flag = false; 
		if (frm.txtTitle.value.length == 0) {
		   alert('<?php echo MESSAGE_JS_MANDATORY_TITLE; ?>');
			frm.txtTitle.focus();
		}
		else if (frm.txtDesc.value == ""){
                alert('<?php echo MESSAGE_JS_MANDATORY_DESCRIPTION; ?>');
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
-->
</script>
</head>

<body  bgcolor="#EDEBEB">
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
</div>
                   <!-- End of side links -->
         <div class="content_column_big"> 
				<!-- admin header -->
				<?php
						//include("./includes/adminheader.php");
				?>
				<!--  end admin header -->
                <!-- Detail Section -->
                <?php
                        include("./includes/editnote.php");
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