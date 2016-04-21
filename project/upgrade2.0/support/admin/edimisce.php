<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheeshpa@armia.com>                                  |
// |                                                                                                            |
// +----------------------------------------------------------------------+
    ob_start();
        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/edimisce.php");
        include("./languages/".$_SP_language."/main.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_EDIT_MISCCONFIG ?></title>
<?php include("./includes/headsettings.php"); ?>
<script>
<!--


        function edit() {

                        if (validateForm() == true) {

                                document.frmConfig.postback.value="U";
                                document.frmConfig.method="post";
                                document.frmConfig.submit();

                        }
        }


        function validateForm()
        {

                var frm = window.document.frmConfig;
                var flag = false;
                if (frm.txtSiteTitle.value.length == 0) {

                       alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                       frm.txtSiteTitle.focus();

                }
                else if(frm.txtEmailHeader.value == ""){

                     alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                     frm.txtEmailHeader.focus();

                }else if(frm.txtEmailFooter.value==""){
                alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                frm.txtEmailFooter.focus();

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
                var frm = document.frmConfig;
                frm.txtSiteTitle.value = "";
                frm.txtHelpLogoURL.value = "";
                frm.txtEmailHeader.value = "";
                                frm.txtEmailFooter.value = "";

        }
                function limitLength(element, maxLength)
{
        if (element.value.length > maxLength)
        {
                element.value = element.value.substring(0, maxLength);
                return 0;
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
                          include("./includes/edimisce.php");
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
<?php
  ob_end_flush();
?>