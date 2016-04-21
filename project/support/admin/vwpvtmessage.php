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
// |                                                                      |
// +----------------------------------------------------------------------+


	ob_start();
	require_once("./includes/applicationheader.php");
	include("./includes/functions/miscfunctions.php");
	include("./languages/".$_SP_language."/vwpvtmessage.php");
    $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>
<title><?php echo HEADING_VW_PVT_MESSAGE?></title>
<?php include("./includes/headsettings.php"); ?>
<script>
<!-- 

                        
	
	function deleteMessage() {
		var frm = document.frmMail;
		if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
		   frm.postback.value="D";
		   frm.method="post";
		   frm.submit();
		} 
	}	
	
	function cancel() {
		var frm = document.frmMail;
		frm.txtTitle.value="";
		frm.txtDesc.value="";
	
	}
	
-->	
</script>
</head>

<body bgcolor="#EDEBEB">
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
		
		
				<?php
						//include("./includes/adminheader.php");
				?>
				<!--  end admin header -->
                <!-- Detail Section -->
                <?php
                        include("./includes/vwpvtmessage.php");
                ?>
		
		
		</div>
		 <?php
                  include("./includes/mainfooter.php");
          ?>




          
</body>
</html>
<?php
	ob_end_flush();
?>