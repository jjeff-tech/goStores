<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshith<roshith@armia.com>                                  |
// |                                                                      |
// +----------------------------------------------------------------------+

        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/labeltickets.php");
        $conn = getConnection();

?>
<html>
<head>
<title><?php echo HEADING_LABELED_TICKETS ?></title>
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

<!-- to move labels -->
		function clickUpdateLabel(chk) {
			var flag = false;
			if(chk == 0) {
			 document.frmDetail.del.value="LABELUP";
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
<!-- end label update -->

		
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
				if(document.frmDetail.cmbAction.value=='delete')
					document.frmDetail.del.value="DM";
			 	if(document.frmDetail.cmbAction.value=='spam')
					document.frmDetail.del.value="MS";
				if(document.frmDetail.cmbLabel.value>0)
					document.frmDetail.labelup.value="LABELUP";
				if(document.frmDetail.cmbStatus.value !="")
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
<!-- to move labels -->
		function clickUpdateLabel(chk) {
			var flag = false;
			if(chk == 0) {
			 document.frmDetail.del.value="LABELUP";
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
<!-- end label update -->

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
	<script type="text/javascript" src="./includes/functions/ajax.js"></script>	
</head>

<body bgcolor="#EDEBEB">
<!-- Ajax tool tip-->

<div id="tooltipBox" onMouseOver="clearAdInterval();" onMouseOut="hideAd();" style="z-index:5000;position:absolute;cursor:pointer;"></div>
<!--end  Ajax tool tip-->

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

          </td>
    <td width="1" rowspan="2" ><img src="../images/spacerr.gif" width="1" height="1"></td>
  </tr>
  <tr>
    <td align="left">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr class="column1">
          <td width="20%" valign="top"  >
                     <!-- sidelinks -->

                          <?php
                                include("./includes/adminside.php");
                        ?>

                   <!-- End of side links -->
          </td>
          <td width="79%" valign="top" align=center class="whitebasic">
				<!-- admin header -->
				<?php
						include("./includes/adminheader.php");
				?>
				<!--  end admin header -->
                <!-- Tickets labeled Section -->
                <?php
                          include("./includes/labeltickets.php");
                ?>
                <!-- End Tickets labeled section -->
                <!-- Advanced Search -->
                 <?php
                          include("./includes/advancedsearch.php");
                  ?>

                  <!-- End Advanced Search -->
          </td>
        </tr>
      </table>
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
    </td>
  </tr>
</table>
</body>
<script>
	var df = '<?php echo($var_deptid); ?>';
	document.frmDetail.cmbDepartment.value=df;
</script>
</html>