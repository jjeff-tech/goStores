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
		
        include("./languages/".$_SP_language."/editnote.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_ADD_NOTE ?></title>
<?php include("./includes/headsettings.php"); ?>
<link href="./../styles/calendar.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript">
<!--
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



	function cancel()
	{
		var frm = document.frmNote;
		frm.txtTitle.value = "";
		frm.txtDesc.value = "";
		frm.txtStaff.value = frm.uname.value;
		frm.id.value = "";
		frm.btAdd.disabled = false;
		frm.btDelete.disabled = true;
		frm.btUpdate.disabled = true;
	}
	function goBack() {
			document.frmNote.action = "<?php echo $_SESSION['sess_backurl'];?>";
			document.frmNote.method="post";
			document.frmNote.submit();
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
						include("./includes/staffheader.php");
				?>
			   <!--  end admin header -->
               <!-- Personal notes Section -->

                  <?php
                          include("./includes/editnote.php");
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