<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>                                  |
// |                                                                                                            |
// +----------------------------------------------------------------------+
        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/repstaff.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_REPORTS ?></title>
<?php include("./includes/headsettings.php"); ?>
<link href="./../styles/calendar.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="./../scripts/calendar.js"></script>
    <script type="text/javascript" src="./../scripts/calendar-setup.js"></script>
    <script type="text/javascript" src="./languages/en/calendar.js"></script>
<script language="javascript" type="text/javascript">

<!--
  var gAutoPrint = true; // Flag for whether or not to automatically call the print function

  //document.oncontextmenu=new Function("return false");
function printSpecial()
{
    var left = 100;
        var top = 100;
	if (document.getElementById != null)
	{
		var html = '<HTML>\n<HEAD>\n<link href=\"./../styles/calendar.css\" rel=\"stylesheet\" type=\"text/css\">\n';
            
		if (document.getElementsByTagName != null)
		{
			var headTags = document.getElementsByTagName("head");
			if (headTags.length > 0)
				html += headTags[0].innerHTML;
		}
		
		html += '\n</HE' + 'AD>\n<BODY>\n';
		html +="<table><tr style=\"attachband\"><td  colspan=5 align=\"center\"><a  href=\"javascript:window.print();\" class=listing>Print</a></td></tr></table>\n";
		
			                
			             
		
		var printReadyElem = document.getElementById("printReady");
		
		if (printReadyElem != null)
		{
				html += printReadyElem.innerHTML;
				
		}
		
		
		else
		{
			alert("Could not find the printReady section in the HTML");
			return;
		}
		html +="<table><tr style=\"listingmaintext\"><td  colspan=5 align=\"center\"><a href=\"javascript:window.print();\" class=listing>Print</a></td></tr></table>\n";	
		html += '\n</BO' + 'DY>\n</HT' + 'ML>';
		
		var printWin = window.open("","printSpecial","top=" + top + ",left=" + left + ",toolbars=no,maximize=yes,resize=no,width=800,height=800,location=no,directories=no,scrollbars=yes,border=thin,caption=no");
		printWin.document.open();
		printWin.document.write(html);
		printWin.document.close();
		//if (gAutoPrint)
			//printWin.print();
	}
	else
	{
		alert("Sorry, the print ready feature is only available in modern browsers.");
	}
}
  function clickRunreport(){
     document.frmrepCompTicket.postback.value="R";
     document.frmrepCompTicket.method="post";
     document.frmrepCompTicket.submit();
  }
-->
</script>
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
                                include("./includes/adminside.php");
                        ?>

                   <!-- End of side links -->


		</div>
		
		
		<div class="content_column_big">
				<!-- admin header -->
				<?php
						include("./includes/adminheader.php");
				?>
				<!--  end admin header -->
                <!-- Detail Section -->
                <?php
                        include("./includes/repstaff.php");
                ?>
	            <!-- End Detail section -->
         
		
		</div>


         
      
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
</body>
</html>