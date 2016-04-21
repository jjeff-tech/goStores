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
        //include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/editrating.php");
        $conn = getConnection();
?>
<html>
<head>
<title><?php echo HEADING_EDIT_RATINGS ?></title>
<?php include("./includes/headsettings.php"); ?>
<script language="javascript" type="text/javascript">
<!--

        function add() {

                if (document.frmRateStaff.id.value.length <= 0) {
				        if(document.frmRateStaff.cmbMark.value=="0"){
						  alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
						  document.frmRateStaff.cmbMark.focus();
						}else if(document.frmRateStaff.txtComments.value==""){
						   alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
						  document.frmRateStaff.txtComments.focus();
						
						}else{
                                document.frmRateStaff.postback.value="A";
                                document.frmRateStaff.method="post";
                                document.frmRateStaff.submit();
						}      
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

    </td>
    <td width="1" rowspan="2"><img src="./images/spacerr.gif" width="1" height="1"></td>
  </tr>
  <tr>
    <td align="left">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr class="column1">
          <td width="21%" valign="top"  >
             <!-- sidelinks -->
                <?php
                           include("./includes/userside.php");
                ?>
             <!-- End of side links -->
          </td>
          <td width="79%" valign="top" class="whitebasic">
				<!-- admin header -->
				<?php
						include("./includes/userheader.php");
				?>
				<!--  end admin header -->
                <!-- Template Section -->
                  <?php
                          include("./includes/editrating.php");
                  ?>
                <!-- End Template  Section  -->
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
</html>