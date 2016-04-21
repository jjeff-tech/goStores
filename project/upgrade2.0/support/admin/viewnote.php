<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>        		                  |
// |          									                          |
// +----------------------------------------------------------------------+
        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/viewnote.php");
        $conn = getConnection();
		
?>
<html>
<head>
<title><?php echo HEADING_VIEW_TICKET ?></title>
<?php include("./includes/headsettings.php"); ?>
<script language="javascript" type="text/javascript">
<!--
	function clickSearch() {
		if (validateSearch() == true) {
			
			document.frmNote.method="post";
			document.frmNote.submit();
		}
	}
	function goBack() {
			document.frmNote.action = "<?php echo $_SESSION['sess_backurl'];?>";
			document.frmNote.method="post";
			document.frmNote.submit();
	}
	function validateSearch() {
		var frm = document.frmNote;	
		if (frm.cmbCompany.value == "" && frm.txtDepartment.value == "" && frm.txtStatus.value == "" && frm.txtOwner.value == "" && frm.txtUser.value == "" && frm.txtTicketNo.value == "" && frm.txtTitle.value == "" && frm.txtLabel.value == "" && frm.txtQuestion.value == "" && frm.txtFrom.value == "" && frm.txtTo.value == "") {
			return false;
		}
		else {
			return true;
		}
	}
	function clickUpdate() {
		var frm = document.frmNote;	
		frm.mt.value="u";
		frm.method="post";
		frm.submit();
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
                <!-- Tickets Assigned Section -->
                <?php
                        include("./includes/viewnote.php");
                ?>

                  <!-- End Tickets Assigned  section -->
          </td>
        </tr>

      </table>


      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="1%"><img src="../images/spacerrr.gif" width="10" height="10"></td>
          <td width="35%">&nbsp;</td>
          <td width="6%">&nbsp;
          </td>
          <td width="15%">&nbsp;             </td>
          <td width="43%">&nbsp;</td>
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
</html>