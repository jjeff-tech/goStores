<?php
/*DB connection*/
 include("../config/settings.php");
 include("../includes/functions/dbfunctions.php");
 $conn = getConnection();
 
$chat_id=$_GET[chat_id];
$active=$_GET[active];
$query="UPDATE  sptbl_desktop_share SET  Status = '".$active."'  WHERE  nChatId ='".addslashes($chat_id)."' LIMIT 1" ;
//$res = executeSelect($query,$conn);
executeQuery($query,$conn);
//mysql_query($query,$conn);
//////////////////////////////////


	
?> 