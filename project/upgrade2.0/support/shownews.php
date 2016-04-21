<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: johnson<johnson@armia.com>                                  |
// +----------------------------------------------------------------------+
		//include("./includes/settings.php");
		if ($_SERVER['HTTP_REFERER'] == "" ) {
			header("location:./index.php");
			exit;
		}
		include("./config/settings.php");
        include("./includes/session.php");
        include("./includes/functions/dbfunctions.php");
        include("./includes/functions/miscfunctions.php");
        include("./includes/functions/impfunctions.php");
		///*set_magic_quotes_runtime(0);*/
        // Check if magic_quotes_runtime is active
        if(get_magic_quotes_runtime())
        {
            // Deactivate
            /*set_magic_quotes_runtime(false);*/
        }
       if (get_magic_quotes_gpc()) {
            $_POST = array_map('stripslashes_deep', $_POST);
            $_GET = array_map('stripslashes_deep', $_GET);
            $_COOKIE = array_map('stripslashes_deep', $_COOKIE);

        }

        if(!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] =="") ){
                $_SP_language = "en";
        }else{
                $_SP_language = $_SESSION["sess_language"];
        }
        include("./languages/".$_SP_language."/main.php");
        include("./languages/".$_SP_language."/shownews.php");
        $conn = getConnection();

?>
<html>
<head>
<title><?php echo HEADING_VIEW_NEWS ?></title>
<?php include("./includes/headsettings.php"); ?>
</head>

<body >
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
          <td width="21%" valign="top" >
		   <!-- sidelinks -->
				<?php
						include("./includes/userside.php");
				?>
		   <!-- End of side links -->
          </td>
          <td width="79%" valign="top"  class="whitebasic">
				<!-- admin header -->
				<?php
					   include("./includes/userheader.php");
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