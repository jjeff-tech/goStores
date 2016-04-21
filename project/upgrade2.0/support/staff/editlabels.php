<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshith<roshith@armia.com>                                  |
// |                                                                      |
// +----------------------------------------------------------------------+

        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
		
        include("./languages/".$_SP_language."/editlabels.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_EDIT_LABELS ?></title>
<?php include("./includes/headsettings.php"); ?>
<script language="javascript" type="text/javascript">
<!--

	function add() {	   
		if (document.frmLabel.id.value.length <= 0) {
			if (validateForm() == true) {
			  	document.frmLabel.postback.value="A";
				document.frmLabel.method="post";
				document.frmLabel.submit();
			}
		}
		else {
			alert("<?php echo MESSAGE_JS_OPERATION_ERROR; ?>");
		}	
	}
	
function edit() {
		if (document.frmLabel.id.value.length > 0) {	
			if (validateForm() == true) {
				document.frmLabel.postback.value="U";
				document.frmLabel.method="post";
				document.frmLabel.submit();
			}
		}
		else {
			alert("<?php echo MESSAGE_JS_OPERATION_ERROR; ?>");
		}	
	}
	
	function deleted() {
		if (document.frmLabel.id.value.length > 0) {
			if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {	
				document.frmLabel.postback.value="D";
				document.frmLabel.method="post";
				document.frmLabel.submit();
			}
		}
		else {
			alert("<?php echo MESSAGE_JS_OPERATION_ERROR; ?>");
		}
			
	}
function validateForm(){
        var frm = window.document.frmLabel;
		var flag = false; 
		
		if (frm.txtTitle.value.length == 0) {
		   alert("<?php echo MESSAGE_JS_MANDATORY_LABEL_TITLE; ?>");
			frm.txtTitle.focus();
//			return false;
		}else{
		  flag=true;
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
		var frm = document.frmLabel;
		frm.txtTitle.value = "";
		frm.txtDesc.value = "";
		frm.btAdd.disabled = false;
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
                <!-- Label Section -->
                  <?php
                          include("./includes/editlabels.php");
                  ?>
                  <!-- End Label Section  --> 
        
		
		
		</div>


      
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
   
</body>
</html>