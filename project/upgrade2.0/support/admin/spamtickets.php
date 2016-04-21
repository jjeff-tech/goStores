<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>                                          |
// |                                                                                                            |
// +----------------------------------------------------------------------+
        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/spamtickets.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_SPAM_TICKETS ?></title>
<?php include("./includes/headsettings.php"); ?>
<?php $var_maxposts = (int)$_SESSION["sess_maxpostperpage"];?>
<script language="javascript" type="text/javascript">
<!--
        function clickSearch() {
                if (validateSearch() == true) {
                        document.frmSearch.method="post";
                        document.frmSearch.submit();
                }
        }

        function validateSearch() {
                var frm = document.frmSearch;
                if (frm.cmbCompany.value == "" && frm.txtDepartment.value == "" && frm.txtStatus.value == "" && frm.txtOwner.value == "" && frm.txtUser.value == "" && frm.txtTicketNo.value == "" && frm.txtTitle.value == "" && frm.txtLabel.value == "" && frm.txtQuestion.value == "" && frm.txtFrom.value == "" && frm.txtTo.value == "") {
                        return false;
                }
                else {
                        return true;
                }
        }
        function changeDepartment() {
                document.frmDetail.numBegin.value="";
                document.frmDetail.num.value="";
                document.frmDetail.start.value="";
                document.frmDetail.begin.value="";
                document.frmDetail.method="post";
                document.frmDetail.submit();
        }
		function deleteTickets(chk) {
			var flag = false;
			if(chk == 0) {
			 document.frmDetail.del.value="DM";
			  for(i=1;i<=<?php echo   $var_maxposts?>;i++) {
				try{
					if(eval("document.getElementById('chkDeleteTickets" + i + "').checked") == true) {
						flag = true;
						break;
					}
				 }catch(e) {}
				}
			}
			if(flag == true) {
			  if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
				document.frmDetail.method="post";
				document.frmDetail.submit();
			  }
			}
			else {
				alert('<?php echo MESSAGE_JS_SELECT_ONE; ?>');
			}
		}
		function spamTickets(chk) {
			var flag = false;
			if(chk == 0) {
			 document.frmDetail.del.value="MS";
			  for(i=1;i<=<?php echo   $var_maxposts?>;i++) {
				try{
					if(eval("document.getElementById('chkDeleteTickets" + i + "').checked") == true) {
						flag = true;
						break;
					}
				 }catch(e) {}
				}
			}
			if(flag == true) {
			  if(confirm('<?php echo MESSAGE_JS_MARKASSPAM_TEXT_1; ?>')) {
				document.frmDetail.method="post";
				document.frmDetail.submit();
			  }
			}
			else {
				alert('<?php echo MESSAGE_JS_MARKSPAM_ONE; ?>');
			}
		}
		function notspamTickets(chk) {
			var flag = false;
			if(chk == 0) {
			 document.frmDetail.del.value="NS";
			  for(i=1;i<=<?php echo   $var_maxposts?>;i++) {
				try{
					if(eval("document.getElementById('chkDeleteTickets" + i + "').checked") == true) {
						flag = true;
						break;
					}
				 }catch(e) {}
				}
			}
			if(flag == true) {
			  if(confirm('<?php echo MESSAGE_JS_MARKASSPAM_TEXT_2; ?>')) {
				document.frmDetail.method="post";
				document.frmDetail.submit();
			  }
			}
			else {
				alert('<?php echo MESSAGE_JS_MARKSPAM_ONE_1; ?>');
			}
		}

		function clickUpdate(chk) {
			var flag = false;
			if(chk == 0) {
			 document.frmDetail.del.value="UP";
			  for(i=1;i<=<?php echo   $var_maxposts?>;i++) {
				try{
					if(eval("document.getElementById('chkDeleteTickets" + i + "').checked") == true) {
						flag = true;
						break;
					}
				 }catch(e) {}
				}
			}
			if(flag == true) {
			  if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT_1; ?>')) {
				document.frmDetail.method="post";
				document.frmDetail.submit();
			  }
			}
			else {
				alert('<?php echo MESSAGE_JS_SELECT_ONE_1; ?>');
			}
		}

		function checkallfn(){
            if(document.frmDetail.checkall.checked){
	             for(i=1;i<=<?php echo   $var_maxposts?>;i++) {
	               try{

                     document.getElementById('chkDeleteTickets' + i ).checked=true;
	               }catch(e) {}
	             }

            }else{
                  for(i=1;i<=<?php echo   $var_maxposts?>;i++) {
	               try{

                     document.getElementById('chkDeleteTickets' + i ).checked=false;
	               }catch(e) {}
	             }



            }




		}
-->
</script>
<link href="./../styles/calendar.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="./../scripts/calendar.js"></script>
    <script type="text/javascript" src="./../scripts/calendar-setup.js"></script>
    <script type="text/javascript" src="./languages/<?php echo $_SP_language; ?>/calendar.js"></script>
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
                <!-- Tickets Assigned Section -->
                <?php
                          include("./includes/spamtickets.php");
                ?>
                <!-- End Tickets Assigned  section -->
                <!-- Advanced Search -->


                 <?php
                          include("./includes/advancedsearch.php");
                  ?>

                  <!-- End Advanced Search -->


          


		</div>

		  
      
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
   
</body>
<script>
	var df = '<?php echo($var_deptid); ?>';
	document.frmDetail.cmbDepartment.value=df;
</script>
</html>