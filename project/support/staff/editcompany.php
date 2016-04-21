<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshith<roshith@armia.com>        		                  |
// |          									                          |
// +----------------------------------------------------------------------+
        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/editcompany.php");
        $conn = getConnection();



?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo HEADING_EDIT_COMPANY?></title>
<?php include("./includes/headsettings.php"); ?>
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
          </td>
  </tr>
  <tr>
    <td align="left">

        <!-- admin header -->

        <?php
                include("./includes/adminheader.php");
        ?>

        <!--  end admin header -->


    <table width="100%"  border="0" cellspacing="10" cellpadding="0">
        <tr class="whitebasic">
          <td width="21%" valign="top" >
                    <!-- sidelinks -->
                          <?php
                                include("./includes/adminside.php");
                         ?>
                    <!-- End of side links -->
                  </td>
          <td width="79%" valign="top"  class="whitebasic">

                  <!-- Detail Section -->

                  <?php
                          include("./includes/editcompany.php");
                  ?>

          <!-- End Detail section -->
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