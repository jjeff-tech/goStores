<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshitrh<roshith@armia.com>                                 |
// |                                                                      |
// +----------------------------------------------------------------------+



        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/addmultipleusers.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_EDIT_USER?></title>
<?php include("./includes/headsettings.php"); ?>
<script>
<!--
        function add() {
             if (document.frmUser.id.value.length <= 0) {
					if (validateForm(0) == true) {
							document.frmUser.postback.value="A";
							document.frmUser.method="post";
							document.frmUser.submit();
					}
             }
             else {
					alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
             }
        }
        function validateForm(i)
        {
	        var frm = window.document.frmUser;
            var flag = false;
                if (frm.txtUrl.value.length == 0) {
                   alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                        frm.txtUrl.focus();
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
                var frm = document.frmUser;
                frm.txtUrl.value = "";
                frm.id.value = "";
                frm.btAdd.disabled = false;
        }
        function download(path) {
			location.href="./csvdownload.php?id="+path;			
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
                          include("./includes/addmultipleusers.php");
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