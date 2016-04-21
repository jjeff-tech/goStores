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
// |                                                                                                            |
// +----------------------------------------------------------------------+
        

        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/addcannedmessage.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_ADD_CANNEDMESSAGE ?></title>
<?php include("./includes/headsettings.php"); ?>


<script>
<!--
        function add() {

                if (document.frmCannedmessage.id.value.length <= 0) {

                        if (validateForm() == true) {
                                  document.frmCannedmessage.postback.value="A";
                                document.frmCannedmessage.method="post";
                                document.frmCannedmessage.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }
        }

function edit() {
                if (document.frmCannedmessage.id.value.length > 0) {
                        if (validateForm() == true) {
                                document.frmCannedmessage.postback.value="U";
                                document.frmCannedmessage.method="post";
                                document.frmCannedmessage.submit();
                        }
                }
                else {
                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                }
        }

function validateForm(){
        var frm = window.document.frmCannedmessage;
                var flag = false;

                if (frm.txtTitle.value.length == 0) {
                   alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                        frm.txtTitle.focus();
//                        return false;
                }else if (frm.txtDesc.value.length == 0) {
                   alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                        frm.txtDesc.focus();
//                        return false;
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
						// include("./includes/adminheader.php");
				?>
				<!--  end admin header -->
                <!-- Detail Section -->
                <?php
                       include("./includes/addcannedmessage.php");
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