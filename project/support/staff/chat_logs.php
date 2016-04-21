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
		
        include("./languages/".$_SP_language."/chat_logs.php");
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
        function clickDelete() {
                var i=1;
                var flag = false;
                	  for(i=1;i<=10;i++) {
							try{
                                if(eval("document.getElementById('c" + i + "').checked") == true) {
										flag = true;
                                        break;
                                }
			                }catch(e) {}
                        }
                        if(flag == true) {
							if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
								document.frmPersonalNotes.postback.value="DA";
                                document.frmPersonalNotes.method="post";
                                document.frmPersonalNotes.submit();
							}	
                        }
						else {
							alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
						}
        }


        function deleted(id) {
			if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
				document.frmPersonalNotes.id.value=id;
				document.frmPersonalNotes.postback.value="D";
				document.frmPersonalNotes.method="post";
				document.frmPersonalNotes.submit();
			}	
        }
		
		function clickShowLogs() {
			document.frmChatLogs.numBegin.value=0;
			document.frmChatLogs.begin.value=0;
			document.frmChatLogs.start.value=0;
			document.frmChatLogs.num.value=0;
			document.frmChatLogs.method="post";
			document.frmChatLogs.submit();
		}
	  function viewChatLog( cid, flg ) {
   		window.open("chatview.php?cid="+cid+"&flg="+flg,"PrintChat","top=100,left=100,width=600,height=600,resizable=yes,location=no,directories=no,scrollbars=yes");
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
		
		
		<div class="content_column_small">

		<!-- sidelinks -->

                          <?php
                                include("./includes/staffside.php");
                        ?>

                   <!-- End of side links -->


		</div>
		
		
		<div class="content_column_big">
		
		
<div class="content_section">  
					<!-- admin header -->
					<?php
							//include("./includes/staffheader.php");
					?>
					<!--  end admin header -->
                   <!-- Personal notes Section -->
				
                  <?php
                          include("./includes/chat_logs.php");
                  ?> 
                  <!-- End Personal notes Section  -->
         </div>
		
		</div>
		
		
          
      
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
    
</body>
</html>