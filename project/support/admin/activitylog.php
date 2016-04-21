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
// |                                                                                                            |
// +----------------------------------------------------------------------+
        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/activitylog.php");
        $conn = getConnection();

?>

<?php include("../includes/docheader.php"); ?>


<title><?php echo HEADING_ACTIVITY_LOG ?></title>
<?php include("./includes/headsettings.php"); ?>
<script language="javascript" type="text/javascript">
<!--
        function clickDelete() {
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


        function deleted(id) {
                        if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
                                document.frmDetail.id.value=id;
                                document.frmDetail.postback.value="D";
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
                function clickCancel() {
                        document.frmDetail.cmbStaff.value='';
                        document.frmDetail.txtSearch.value='';
                        clickSearch();
                }
                function submitForm()
                {
                    document.frmDetail.submit();
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
		
				  <!-- admin header -->
					<?php
							//include("./includes/adminheader.php");
					?>
				  <!--  end admin header -->
                  <!-- Detail Section -->
                  <?php
                          include("./includes/activitylog.php");
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