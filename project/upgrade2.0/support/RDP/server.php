<?php
/*DB connection*/
 include("../config/settings.php");
 include("../includes/functions/dbfunctions.php");
 $conn = getConnection();
 
$chat_id=$_GET[chat_id];
$img_data = $_POST['imagedata'];
$img_data=(string)$img_data;
$img_data =str_replace(" ","+",$img_data);
$data=addslashes($img_data);
$query="UPDATE  sptbl_desktop_share SET  Screenshot = '".addslashes($data)."'  WHERE  nChatId ='".addslashes($chat_id)."' LIMIT 1" ;
//$res = executeSelect($query,$conn);
executeQuery($query,$conn);
//mysql_query($query,$conn);
//////////////////////////////////




	
?> 