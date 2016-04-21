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
// |                                                                                                            |
// +----------------------------------------------------------------------+
        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/chatlogs.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_CHATLOGS ?></title>
<?php include("./includes/headsettings.php"); ?>
<link href="./../styles/calendar.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="./../scripts/calendar.js"></script>
    <script type="text/javascript" src="./../scripts/calendar-setup.js"></script>
    <script type="text/javascript" src="./languages/en/calendar.js"></script>
<script language="javascript" type="text/javascript">

<!--
  var gAutoPrint = true; // Flag for whether or not to automatically call the print function

  document.oncontextmenu=new Function("return false");


function viewChatLog( cid, flg ) {
   var left =100;
   var top=100;
   window.open("chatview.php?cid="+cid+"&flg="+flg,"","top=" + top + ",left=" + left + ",toolbars=no,maximize=yes,resize=no,width=800,height=800,location=no,directories=no,scrollbars=yes,border=thin,caption=no");
}
/* not using
function printSpecial()
{
    var left = 100;
    var top = 100;
	var html = '<HTML>\n<HEAD>\n<link href=\"./../styles/calendar.css\" rel=\"stylesheet\" type=\"text/css\">\n';
	html += '\n</HE' + 'AD>\n<BODY>\n';
	html +="<div id=tpprint><table><tr ><td  colspan=5 align=\"center\"><a class=\"listing\" href=\"javascript:document.getElementById('tpprint').style.display='none';document.getElementById('btprint').style.display='none';window.print();\"><?php echo   TEXT_PRINT?></a></td></tr></table></div>\n";
		
		var printReadyElem = document.getElementById("printReady");
		
		if (printReadyElem != null)
		{
				html += printReadyElem.innerHTML;
				
		}
		else
		{
			alert("<?php echo   TEXT_PR_SECTION_NOT_FOUND?>");
			return;
		}
		html +="<br><div id=btprint><table><tr ><td  colspan=5 align=\"center\"><a class=\"listing\" href=\"javascript:document.getElementById('tpprint').style.display='none';document.getElementById('btprint').style.display='none';window.print();\"><?php echo   TEXT_PRINT?></a></td></tr></table></div>\n";	
		html += '\n</BO' + 'DY>\n</HT' + 'ML>';
		
		var printWin = window.open("","printSpecial","top=" + top + ",left=" + left + ",toolbars=no,maximize=yes,resize=no,width=800,height=800,location=no,directories=no,scrollbars=yes,border=thin,caption=no");
		
		printWin.document.open();
		printWin.document.write(html);
		printWin.document.close();
		//if (gAutoPrint)
			//printWin.print();
}*/
  function clickShowLogs(){
     document.frmChatLogs.postback.value="R";
     document.frmChatLogs.method="post";
     document.frmChatLogs.submit();
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
						//include("./includes/adminheader.php");
				?>
				<!--  end admin header -->
                <!-- Detail Section -->
                <?php
                       include("./includes/chatlogs.php");
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
