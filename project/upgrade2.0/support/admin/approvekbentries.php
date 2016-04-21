<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>                                  |
// |                                                                                                           |
// +----------------------------------------------------------------------+
        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/approvekbentries.php");
        $conn = getConnection();
         $page = 'kbentries';
?>
<?php include("../includes/docheader.php"); ?>



<title><?php echo HEADING_APPROVE_KB_ENTRIES ?></title>
<?php include("./includes/headsettings.php"); ?>
<script language="javascript" type="text/javascript">
<!--
        function clickApproveAll() {
                var i=1;
                var flag = false;
                          for(i=1;i<=10;i++) {
                                                        try{
                                if(eval("document.getElementById('c" + i + "').checked") == true) {
                                                                                flag = true;
                                        break;
                                }
                                        }catch(e) {}
                        }
                        if(flag == true) {
                                                        if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
                                                                document.frmDetail.postback.value="DA";
                                document.frmDetail.method="post";
                                document.frmDetail.submit();
                                                        }
                        }
                                                else {
                                                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                                                }
        }
                function clickChangeStatus(type) {
                var i=1;
                var flag = false;
                          for(i=1;i<=10;i++) {
                                                        try{
                                if(eval("document.getElementById('c" + i + "').checked") == true) {
                                                                                flag = true;
                                        break;
                                }
                                        }catch(e) {}
                        }
                        if(flag == true) {
                                                        if(confirm('<?php echo MESSAGE_CONFIRM_BATCH_CHANGE; ?>')) {
                                                                document.frmDetail.postback.value="CA";
                                                                document.frmDetail.changeto.value=type;
                                document.frmDetail.method="post";
                                document.frmDetail.submit();
                                                        }
                        }
                                                else {
                                                        alert('Please select the records to be updated.');
                                                }
        }

        function deleted(id) {
                        if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
                                document.frmDetail.id.value=id;
                                document.frmDetail.postback.value="D";
                                document.frmDetail.method="post";
                                document.frmDetail.submit();
                        }
        }
                function changestatus(id,newstat) {
                        if(confirm('<?php echo MESSAGE_CONFIRM_STATUS_CHANGE; ?>')) {
                                document.frmDetail.id.value=id;
                                document.frmDetail.newstat.value=newstat;
                                document.frmDetail.postback.value="CS";
                                document.frmDetail.method="post";
                                document.frmDetail.submit();
                        }
        }



                function clickSearch() {
                        document.frmDetail.numBegin.value=0;
                        document.frmDetail.begin.value=0;
                        document.frmDetail.start.value=0;
                        document.frmDetail.num.value=0;
                        document.frmDetail.method="post";
                        document.frmDetail.submit();
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
                        include("./includes/approvekbentries.php");
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