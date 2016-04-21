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
// |                                                                                       // |
// +----------------------------------------------------------------------+


        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/editmaxfilesize.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_MAX_FILE_SIZE?></title>
<?php include("./includes/headsettings.php"); ?>
<script>
<!--


        function edit() {

                        if (validateForm() == true) {

                                document.frmMaxFileSize.postback.value="U";
                                document.frmMaxFileSize.method="post";
                                document.frmMaxFileSize.submit();

                        }
        }


        function validateForm()
        {

                var frm = window.document.frmMaxFileSize;
                var flag = false;
                if (frm.txtMaxFileSize.value.length == 0) {

                       alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                       frm.txtMaxFileSize.focus();

                }
                else if (isNaN(frm.txtMaxFileSize.value)){

                     alert('<?php echo MESSAGE_JS_NUMERIC_ERROR; ?>');
                     frm.txtMaxFileSize.focus();
                }

                else if(parseInt(frm.txtMaxFileSize.value) < 1) {
                     alert('<?php echo MESSAGE_JS_POSITIVE_NUMERIC_ERROR; ?>');
                     frm.txtMaxFileSize.focus();
				}
				else{

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
                var frm = document.frmMaxFileSize;
                frm.txtMaxFileSize.value = "";


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
                          include("./includes/editmaxfilesize.php");
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