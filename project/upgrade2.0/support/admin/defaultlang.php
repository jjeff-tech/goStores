<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>                            |
// |                                                                                                            |
// +----------------------------------------------------------------------+


        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/defaultlang.php");
        $conn = getConnection();

?>
<html>
<head>
<title><?php echo HEADING_DEFAULT_LANGUAGE?></title>
<?php include("./includes/headsettings.php"); ?>

<script>
<!--


        function edit() {



                                document.frmConfig.postback.value="U";
                                document.frmConfig.method="post";
                                document.frmConfig.submit();


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
          <td width="20%" valign="top" >
                          <!-- sidelinks -->

                          <?php
                                include("./includes/adminside.php");
                        ?>

                   <!-- End of side links -->
          </td>
          <td width="79%" valign="top"  class="whitebasic">
					<!-- admin header -->
					<?php
							include("./includes/adminheader.php");
					?>
					<!--  end admin header -->
                  <!-- Detail Section -->
                  <?php
                          include("./includes/defaultlang.php");
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