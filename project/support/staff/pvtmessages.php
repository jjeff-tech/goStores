<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>                          |
// |                           |
// +----------------------------------------------------------------------+

        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
		
        include("./languages/".$_SP_language."/pvtmessages.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_STAFF_PVTMESSAGE ?></title>
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
								document.frmPvtMessage.postback.value="DA";
                                document.frmPvtMessage.method="post";
                                document.frmPvtMessage.submit();
							}	
                        }
						else {
							alert('<?php echo "Select atleast one record to delete."; ?>');
						}
        }


        function deleted(id) {
			if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
				document.frmPvtMessage.id.value=id;
				document.frmPvtMessage.postback.value="D";
				document.frmPvtMessage.method="post";
				document.frmPvtMessage.submit();
			}	
        }
		
		function clickSearch() {
			document.frmPvtMessage.numBegin.value=0;
			document.frmPvtMessage.begin.value=0;
			document.frmPvtMessage.start.value=0;
			document.frmPvtMessage.num.value=0;
			document.frmPvtMessage.method="post";
			document.frmPvtMessage.submit();
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

        <!--  Top links  -->

        <?php
//                 include("./includes/toplinks.php");
         ?>

        <!--  End Top Links -->

        <!-- header  -->
    <?php
                include("./includes/header.php");
        ?>
        <!-- end header -->
          
		  
		  
		  <div class="content_column_small">

			<!-- sidelinks -->

                          <?php
                                include("./includes/staffside.php");
                        ?>

             <!-- End of side links -->


		 </div>




		<div class="content_column_big">


				<!-- admin header -->
				<?php
						// include("./includes/staffheader.php");
				?>
				<!--  end admin header -->

                 <!-- Private Message Section -->

                  <?php
                          include("./includes/pvtmessages.php");
                  ?>

                  <!-- End Private Message section -->
       


		</div>

		  
		  
		  
		  
      
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
   
</body>
</html>