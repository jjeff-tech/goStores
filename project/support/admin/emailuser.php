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
        include("./languages/".$_SP_language."/emailuser.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>


<title><?php echo HEADING_EMAIL_USER?></title>
<?php include("./includes/headsettings.php"); ?>
<script>
<!--
        function sendMail() {
                if(validateForm() == true) {
                        document.frmMail.postback.value="SA";
                        document.frmMail.method = "post";
                        document.frmMail.submit();
                }
                else {
                        alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                }
        }

        function validateForm() {
                var frm = document.frmMail;
                if(frm.txtSubject.value.length == 0) {
                        frm.txtSubject.focus();
                        return false;
                }
                else if(frm.txtBody.value.length == 0) {
                        frm.txtBody.focus();
                        return false;
                }
                return true;
        }

        function cancel() {
                var frm = document.frmMail;
                frm.txtSubject.value="";
                frm.txtBody.value="";

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
                        include("./includes/emailuser.php");
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