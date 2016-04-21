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
        include("./languages/".$_SP_language."/editpop3.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_EDIT_POP3_CONFIG?></title>
<?php include("./includes/headsettings.php"); ?>
<script>
<!--
        function add() {
			if (document.frmPOP3.id.value.length > 0) {
				alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
			}else if (validateForm() == true) {
					document.frmPOP3.postback.value="A";
					document.frmPOP3.method="post";
					document.frmPOP3.submit();
			}
        }

        function edit() {
			if (document.frmPOP3.id.value.length == 0) {
				alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
			}else if (validateForm() == true) {
				document.frmPOP3.postback.value="U";
				document.frmPOP3.method="post";
				document.frmPOP3.submit();
			}
        }

        function deleted() {
			if (document.frmPOP3.id.value.length > 0) {
				if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
					document.frmPOP3.postback.value="D";
					document.frmPOP3.method="post";
					document.frmPOP3.submit();
				}
			}
			else {
					alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
			}
        }

        function validateForm()
        {
                var frm = window.document.frmPOP3;
                var flag = false;
				 
                if (frm.cmbCompany.value <= 0) {
                       alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                       frm.cmbCompany.focus();
                }else if (frm.cmbParentDepartment.value <= 0) {
                       alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                       frm.cmbCompany.focus();
				}else if (frm.txtPop3Server.value.length == 0) {
                       alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                       frm.txtPop3Server.focus();
                }else if (frm.txtPop3Username.value == ""){
                     alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                     frm.txtPop3Username.focus();
                }else if (frm.txtPop3Password.value == ""){
                     alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                     frm.txtPop3Password.focus();
                }else if (frm.txtPort.value == ""){
                     alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                     frm.txtPort.focus();
                }
                else {
                     flag = true;
                }
                if (flag == false) {
                    return false;
                }else {
                    return true;
                }
		}

        function cancel()
        {
                var frm = document.frmPOP3;
                frm.txtPop3Server.value = "";
                frm.txtPop3Username.value = "";
                frm.txtPop3Password.value = "";
                frm.txtPort.value = "";
        }
		
		function changecompany(){
			  if(document.frmPOP3.id.value.length >0){
			     document.frmPOP3.cmbCompany.value=document.frmPOP3.cmbCompanyhidden.value;
				 alert('<?php echo MESSAGE_JS_INTER_COMPANY_TRANSFER_ERROR; ?>');
			  }else
			  {
				  document.frmPOP3.postback.value="CC";
				  document.frmPOP3.method="post";
				  document.frmPOP3.cmbParentDepartment.selectedIndex=0;
				  document.frmPOP3.submit();
			  }	  
		}
		function changedept(){
			  if(document.frmPOP3.id.value.length >0){
			     document.frmPOP3.cmbParentDepartment.value=document.frmPOP3.cmbParentDepartmenthidden.value;
				 alert('<?php echo MESSAGE_JS_INTER_DEPARTMENT_TRANSFER_ERROR; ?>');
			  }else
			  {
				  document.frmPOP3.postback.value="CP";
				  document.frmPOP3.method="post";
				  document.frmPOP3.submit();
			  }	  
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
                        include("./includes/editpop3.php");
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