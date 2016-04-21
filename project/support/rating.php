<?php
require_once("./includes/applicationheader.php");
include "languages/".$_SP_language."/main.php";
$conn = getConnection();
?>
<?php include("./includes/docheader.php"); ?>
<html>
    <head>
        <title>Rate Staff</title>
                        <?php include("./includes/headsettings.php"); ?>

        <style>
            .jqRatingPop {
                background:none repeat scroll 0 0 #F9F9F9;
                border:2px solid #4775A1;
                display:none;
                font-family:Verdana,Arial,Helvetica,sans-serif;
                font-size:12px;
                padding:12px;
                position:fixed;
                width:450px;
                z-index:9999;
            }
            /* jQuery.Rating Plugin CSS - http://www.fyneworks.com/jquery/star-rating/ */
            div.rating-cancel,div.star-rating{float:left;width:17px;height:15px;text-indent:-999em;cursor:pointer;display:block;background:transparent;overflow:hidden}
            div.rating-cancel,div.rating-cancel a{background:url(images/delete1.gif) no-repeat 0 -16px}
            div.star-rating,div.star-rating a{background:url(images/star.gif) no-repeat 0 0px}
            div.rating-cancel a,div.star-rating a{display:block;width:16px;height:100%;background-position:0 0px;border:0}
            div.star-rating-on a{background-position:0 -16px!important}
            div.star-rating-hover a{background-position:0 -32px}
            /* Read Only CSS */
            div.star-rating-readonly a{cursor:default !important}
            /* Partial Star CSS */
            div.star-rating{background:transparent!important;overflow:hidden!important}
            /* END jQuery.Rating Plugin CSS */


            /*-------------------Rating Star ------------------*/

            .rating_5{width:71px;
                      height:12px;
                      background-image:url(images/rating_sprite.png);
                      background-position: 0px 0px;
                      float:left;

            }
            .rating_4{width:71px;
                      height:12px;
                      background-image:url(images/rating_sprite.png);
                      background-position: 0px -14px;
                      float:left;


            }
            .rating_3{width:71px;
                      height:12px;
                      background-image:url(images/rating_sprite.png);
                      background-position: 0px -28px;
                      float:left;

            }
            .rating_2{width:71px;
                      height:12px;
                      background-image:url(images/rating_sprite.png);
                      background-position: 0px -43px;
                      float:left;

            }
            .rating_1{width:71px;
                      height:12px;
                      background-image:url(images/rating_sprite.png);
                      background-position: 0px -57px;
                      float:left;

            }
            .rating_0{width:71px;
                      height:12px;
                      background-image:url(images/rating_sprite.png);
                      background-position: 0px -71px;
                      float:left;

            }
            }
        </style>
        <script language="javascript" src="scripts/jquery.js"></script>
        <script language="javascript" src="scripts/jquery-rating.js"></script>
        <script language="javascript" src="scripts/rating.js"></script>
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
					</div>
                    <!-- End of side links -->
                <div class="content_column_big">
					<div class="content_section">
					
                    <!-- admin header -->
                    <?php
                    include("./includes/rating.php");
                    ?>
                    <!--  end admin header -->
              
			</div>
		</div>
        <!-- Main footer -->
        <?php
        include("./includes/mainfooter.php");
        ?>
        <!-- End Main footer -->
    
</body>
</html>