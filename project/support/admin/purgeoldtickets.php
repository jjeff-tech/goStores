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
        include("./languages/".$_SP_language."/purgeoldtickets.php");
        $conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_TICKET_PURGE ?></title>
<?php include("./includes/headsettings.php"); ?>
<link href="./../styles/calendar.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="./../scripts/calendar.js"></script>
    <script type="text/javascript" src="./../scripts/calendar-setup.js"></script>
    <script type="text/javascript" src="./languages/en/calendar.js"></script>
<script language="javascript" type="text/javascript">

<!--
  var gAutoPrint = true; // Flag for whether or not to automatically call the print function

  document.oncontextmenu=new Function("return false");
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
		html +="<div id=tpprint><table><tr ><td  colspan=5 align=\"center\"><a class=\"listing\" href=\"javascript:document.getElementById('tpprint').style.display='none';document.getElementById('btprint').style.display='none';window.print();\">Print</a></td></tr></table></div>\n";
		
			                
			             
		
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
		html +="<br><div id=btprint><table><tr ><td  colspan=5 align=\"center\"><a class=\"listing\" href=\"javascript:document.getElementById('tpprint').style.display='none';document.getElementById('btprint').style.display='none';window.print();\">Print</a></td></tr></table></div>\n";	
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
var width=400;
  function clickPurge(){
			str="processpurg.php?cmp=" + document.frmSearch.cmbCompany.value + "&dpt=" + document.frmSearch.txtDepartment.value + "&st=" + escape(document.frmSearch.txtStatus.value) + "&own=" + escape(document.frmSearch.txtOwner.value) + "&usr=" + escape(document.frmSearch.txtUser.value) + "&frm=" + escape(document.frmSearch.txtFrom.value) + "&to=" + escape(document.frmSearch.txtTo.value) + "&cop=" + document.frmSearch.cmbCompOp.value + "&dop=" + document.frmSearch.cmbDeptOp.value + "&dlp=" + document.frmSearch.cmbDeptLp.value + "&sop=" + document.frmSearch.cmbStatusOp.value + "&slp=" + document.frmSearch.cmbStatusLp.value + "&oop=" + document.frmSearch.cmbOwnerOp.value + "&olp=" + document.frmSearch.cmbOwnerLp.value + "&uop=" + document.frmSearch.cmbUserOp.value + "&ulp=" + document.frmSearch.cmbUserLp.value + "&";
			var left = Math.floor( (screen.width - width) / 2);
   			var top = Math.floor( (screen.height - 300) / 2);
			var loginWindow=window.open(str,"approvalpage","top=" + top +",left="+  left +",toolbars=no,maximize=no,resize=no,width=" + width + ",height=300,location=no,directories=no,scrollbars=yes,border=thin,caption=no");			
			loginWindow.focus();	
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
                        include("./includes/purgeoldtickets.php");
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