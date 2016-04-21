<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshith<roshtih@armia.com>		                          |
// |          									                          |
// +----------------------------------------------------------------------+
    require_once("./includes/applicationheader.php");
    include("./includes/functions/miscfunctions.php");
    include("./languages/".$_SP_language."/viewkbentry.php");
    $conn = getConnection();

?>
<html>
<head>
<title><?php echo HEADING_STAFF_MAIN ?></title>
<?php include("./includes/headsettings.php"); ?>
<script language="javascript" type="text/javascript">
<!--
		
function clickSearch() {
	document.frmKB.numBegin.value=0;
	document.frmKB.begin.value=0;
	document.frmKB.start.value=0;
	document.frmKB.num.value=0;
	document.frmKB.method="post";
	document.frmKB.postback.value="S";
	document.frmKB.submit();
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
          </td>
  </tr>
  <tr>
    <td align="left">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr class="column1">
          <td width="20%" valign="top"  >
                     <!-- sidelinks -->

                          <?php
                                include("./includes/staffside.php");
                        ?>

                   <!-- End of side links -->
                  </td>
          <td width="79%" valign="top" align=center class="whitebasic">
				<!-- admin header -->
				<?php
						include("./includes/staffheader.php");
				?>
				<!--  end admin header -->

                  <!-- Tickets Assigned Section -->

                  <?php
                          include("./includes/viewkbentry.php");
                  ?>

                  <!-- End Tickets Assigned  section -->

                  
				  


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