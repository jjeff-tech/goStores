<?php
   require_once("./includes/applicationheader.php");
   $chatid = isset( $_GET['chatid']) ? $_GET['chatid'] : ''  ;
   $conn = getConnection();
//echo $HTTP_RAW_POST_DATA;
//	 $sql = "update sptbl_chat set tMatter='".addslashes($HTTP_RAW_POST_DATA)."' where nChatId='".$chatid."'";
   $sql = "update sptbl_chat set tMatter=concat(tMatter,'".addslashes($HTTP_RAW_POST_DATA)."') where nChatId='".$chatid."'";
   $result = executeQuery($sql,$conn);
?>
