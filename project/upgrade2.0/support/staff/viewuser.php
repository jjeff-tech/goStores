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
		
        include("./languages/".$_SP_language."/viewuser.php");
        $conn = getConnection();

?>
<html>
<head>
<title><?php echo HEADING_VIEW_USER ?></title>
<?php include("./includes/headsettings.php"); ?>
<link href="./../styles/calendar.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript">
<!--
	function goBack() {
			document.frmUser.action = "<?php echo $_SESSION['sess_backurl'];?>";
			document.frmUser.method="post";
			document.frmUser.submit();
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
  <div class="content_column_small">
                    <!-- sidelinks -->
                      <?php
                                include("./includes/staffside.php");
                      ?>
					  </div>
                   <!-- End of side links -->
            <div class="content_column_big">
					<!-- admin header -->
					<?php
							//include("./includes/staffheader.php");
					?>
					<!--  end admin header -->

                  <!-- Personal notes Section -->

                  <?php
                          include("./includes/viewuser.php");
                  ?>

                  <!-- End Personal notes Section  -->

                  </div>
				 

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