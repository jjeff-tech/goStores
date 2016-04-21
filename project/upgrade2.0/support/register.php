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
        include "languages/".$_SP_language."/register.php";
        $conn = getConnection();
         $page = 'register';
		$sql = "Select vLookUpName,vLookUpValue from sptbl_lookup WHERE vLookUpName IN('LangChoice',
                                'DefaultLang','HelpdeskTitle','Logourl','logactivity','UserAuthenticate')";
                $rs = executeSelect($sql,$conn);

				if(!isset($_SESSION['sess_cssurl'])){
        			$_SESSION['sess_cssurl']="styles/coolgreen.css";
				}
                if (mysql_num_rows($rs) > 0) {
                        while($row = mysql_fetch_array($rs)){
							switch($row["vLookUpName"]) {
									case "LangChoice":
													$_SESSION["sess_langchoice"] = $row["vLookUpValue"];
													break;
									case "DefaultLang":
													$_SESSION["sess_defaultlang"] = $row["vLookUpValue"];
													break;
									case "HelpdeskTitle":
													$_SESSION["sess_helpdesktitle"] = $row["vLookUpValue"];
													break;
									case "Logourl":
													$_SESSION["sess_logourl"] = $row["vLookUpValue"];
													break;
									case "logactivity":    //this session variable decides to log activities or not
													$_SESSION["sess_logactivity"] = $row["vLookUpValue"];
													break;						
									case "UserAuthenticate":    //this variable used to check user authentication
													$auth_Status = $row["vLookUpValue"];
													break;						

							}
					}
			}
			
			mysql_free_result($rs);
?>
<?php include("./includes/docheader.php"); ?>

<title><?php echo HEADER_REGISTER;?></title>
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
	
	<!-- end header -->
<div class="content_column_small">

<!-- sidelinks -->
			    <?php
					 // include("./includes/userside.php");
		   		?>
			  <!-- End of side links -->


</div>


<div class="content_column_big2">


				<!-- admin header -->
				<?php 
						// include("./includes/userheader.php");
				?>
				<!--  end admin header -->
	          <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Detail Section %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                 <?php
                          include("./includes/register.php");
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