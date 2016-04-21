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

    require_once("./includes/applicationheader.php");
        include "languages/".$_SP_language."/emailsettings.php";
        $conn = getConnection();
        $page = 'editprofile';
?>
<?php include("./includes/docheader.php"); ?>

<title><?php echo HEADER_EMAL;?></title>
<?php include("./includes/headsettings.php"); ?>
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
					  include("./includes/userside.php");
			   ?>
			   <!-- End of side links -->

	</div>


	<div class="content_column_big">

			<!-- admin header -->
				<?php
						// include("./includes/userheader.php");
				?>
		    <!--  end admin header -->
            <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Detail Section %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                 <?php

                  if(userLoggedIn()){
                  //************************************** User Logged In *************************************************** -->
                          include("./includes/emailsettings.php");
                 //************************************** User Logged In ******************************************* -->

                  }else{
                   ;
                  }
                  ?>
          <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End Detail Section %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
         
	</div>
	
	
      
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
   
</body>
</html>