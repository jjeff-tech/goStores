<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshith<roshith@armia.com>    		                      |
// |          									                          |
// +----------------------------------------------------------------------+

        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/editrules.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_EDIT_RULE ?></title>
<?php include("./includes/headsettings.php"); ?>
<script>
<!--
        function add() {
			if (validateForm() == true) {
					document.frmRules.postback.value="A";
					document.frmRules.method="post";
					document.frmRules.submit();
			}
        }

		function edit() {
			if (validateForm() == true) {
					document.frmRules.postback.value="U";
					document.frmRules.method="post";
					document.frmRules.submit();
			}
        }

        function deleted() {
			if (document.frmRules.id.value.length > 0) {
					if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
							document.frmRules.postback.value="D";
							document.frmRules.method="post";
							document.frmRules.submit();
					}
			}
			else {
					alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
			}
        }

		function validateForm()
		{ 
			var frm = window.document.frmRules;
			var flag = false;

			if (frm.txtRuleName.value == "") {
			    alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
				frm.txtRuleName.focus();
			}else if (frm.txtKeywords.value == "") {
			    alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
				frm.txtKeywords.focus();
/*			}else if ((frm.chkSearchTitle.checked == false) && (frm.chkSearchBody.checked == false)) {
			    alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
				frm.chkSearchTitle.focus();
*/			}else if (frm.cmbCompany.value <= 0) {
			    alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
				frm.cmbCompany.focus();
			}else if (frm.cmbParentDepartment.value <= 0) {
			    alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
				frm.cmbParentDepartment.focus();
			}else if(frm.cmbStaff.value <= 0 ){
				alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
				frm.cmbStaff.focus();
			}else {
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
			  var frm = document.frmRules;
			  frm.txtRuleName.value = "";
			  document.frmRules.id.value="";
			  frm.txtKeywords.value = "";
//			  frm.chkSearchTitle.checked=false;
//			  frm.chkSearchBody.checked=false;
			  frm.cmbCompany.value=0;
			  frm.cmbParentDepartment.value=0;
			  frm.cmbStaff.value=0;
			  frm.btAdd.disabled = false;
			  frm.btDelete.disabled = true;
			  frm.btUpdate.disabled = true;
        }
		function changecompany(){
			  document.frmRules.postback.value="CC";
			  document.frmRules.method="post";
			  document.frmRules.cmbParentDepartment.selectedIndex=0;
			  document.frmRules.submit();
		}
		function changedept(){
			  document.frmRules.postback.value="CP";
			  document.frmRules.method="post";
			  document.frmRules.submit();
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
                          include("./includes/editrules.php");
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