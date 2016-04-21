<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>                                  |
// |                                                                                                            |
// +----------------------------------------------------------------------+
        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/edit_chatsettings.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_EDIT_CHATSETTINGS?></title>
<?php include("./includes/headsettings.php"); ?>
<script>
<!--
        function edit() {
			if (validateForm() == true) {
					document.frmChatConfig.postback.value="U";
					document.frmChatConfig.method="post";
					document.frmChatConfig.submit();
			}
        }
		
		function changecompany() {
			document.frmChatConfig.postback.value="C";
			document.frmChatConfig.method="post";
			document.frmChatConfig.submit();
        }

        function validateForm()
        { 
               var frm = window.document.frmChatConfig;
               var flag = false;
			   if(frm.cmbCompany.value == '0' ) {
			     alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');  
				 frm.cmbCompany.focus();
				 return false;
			   }
               if (frm.txtWelcomeMsg.value.length == 0) {
                       alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                       frm.txtWelcomeMsg.focus();
					   return false;
               } else {
                    return true;
               }
        }

-->
</script>

<?php include("../includes/docheader.php"); ?>

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
							// include("./includes/adminheader.php");
					?>
					<!--  end admin header -->
                    <!-- Detail Section -->
                    <?php
                         include("./includes/editchat_settings.php");
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