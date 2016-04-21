<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>                                  |
// +----------------------------------------------------------------------+

    require_once("./includes/applicationheader.php");
	include("./includes/functions/miscfunctions.php");
        include "languages/".$_SP_language."/postticketkb.php";
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADER_POST_TICKET;?></title>
<?php include("./includes/headsettings.php"); ?>
<script language="javascript" type="text/javascript">
   <!--
       function clickContinue(){
              document.frmShowKb.postback.value="CN";
                  document.frmShowKb.method="post";
                  document.frmShowKb.submit();

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

       
                          <!-- sidelinks -->
<div class="content_column_small">
                   <?php
                          include("./includes/adminside.php");
                   ?>
				   </div>

                   <!-- End of side links -->
         
				<!-- admin header -->
				<?php
						//include("./includes/adminheader.php");
				?>
				<div class="content_column_big">
				<!--  end admin header -->

          <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Detail Section %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                 <?php

                  //if(userLoggedIn()){
                  //************************************** User Logged In *************************************************** -->
                          include("./includes/postticketkb.php");
                 //************************************** User Logged In ******************************************* -->

                  //}else{
                  // ;
                  //}
                  ?>
				  </div>
          <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End Detail Section %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
      
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
   
</body>
</html>