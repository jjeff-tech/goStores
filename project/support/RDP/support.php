<?php
/*DB connection*/
 include("../config/settings.php");
 include("../includes/functions/dbfunctions.php");
 $conn = getConnection();

$xpos=$_GET[xpos];
$ypos=$_GET[ypos];
$key=$_GET[key];
$click=$_GET[click];
$chat_id=$_GET[chat_id];
//$im = imagecreatefromstring($data);

$query="SELECT  Screenshot FROM  sptbl_desktop_share WHERE  nChatId =  '".addslashes($chat_id)."' LIMIT 1" ;
$result = executeSelect($query,$conn);
$data=mysql_fetch_array($result);
$data = stripslashes($data[0]);

echo $data;

	
?> 