<?php
   include("./config/settings.php");
   include("./includes/session.php");
   include("./includes/functions/dbfunctions.php");
   include("./includes/functions/miscfunctions.php");
   include("./includes/functions/impfunctions.php");
//   require_once("./includes/applicationheader.php");
   if ( $_SESSION['sess_userid'] == "" ) echo "##X";
   else {
   $mod = isset( $_GET['mod']) ? $_GET['mod'] : ''  ;
   $chatid = isset( $_GET['chatid']) ? $_GET['chatid'] : ''  ;
   $conn = getConnection();
     $sql = "Select tMatter from sptbl_chat where nChatId='".$chatid."'";
	 $result = executeSelect($sql,$conn);
     $rowcnt_chatUpd = mysql_num_rows($result);
	 if( $rowcnt_chatUpd > 0 ) {
       while ($row = mysql_fetch_array($result)) {
		 $chattext=$row["tMatter"] ;
	   }
	   $rtStr = $chattext; 
     } else $rtStr = "";
	 
	echo "##D".$rtStr;
  }
 ?>
