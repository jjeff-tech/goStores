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
        include("./languages/".$_SP_language."/shownews.php");
    $conn = getConnection();

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo HEADING_VIEW_NEWS ?></title>
<?php include("./includes/headsettings.php"); ?>
<link href="./../styles/
calendar.css" rel="stylesheet" type="text/css">

  
</head>

<body  bgcolor="#EDEBEB">
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
          <td width="20%" valign="top" >
                   <!-- sidelinks -->

                        <?php
                                include("./includes/staffside.php");
                        ?>

                   <!-- End of side links -->
                  </td>
          <td width="79%" valign="top"  class="whitebasic">
				<!-- admin header -->
				<?php
						include("./includes/staffheader.php");
				?>
				<!--  end admin header -->

                  <!-- Detail Section -->

                  <?php
                          include("./includes/shownews.php");
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