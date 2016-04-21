<?php
   require_once("./includes/applicationheader.php");
   include("./languages/".$_SP_language."/client_chat.php");
   $mod = isset( $_GET['mod']) ? $_GET['mod'] : ''  ;
   $chatid = isset( $_GET['chatid']) ? $_GET['chatid'] : ''  ;
   $username = $_SESSION["sess_userfullname"] ;
   $conn = getConnection();
   $txtFinish = "<span><FONT color=\"FF0000\" style=\"font-size:14px;\" FACE=\"Verdana\">".$username." logged out of chat.</FONT></span><br>";
   
   
      $sql1  = "UPDATE sptbl_chat SET tMatter=concat(tMatter,'".addslashes($txtFinish)."'), vStatus = 'finished', dTimeEnd=now() WHERE nChatId='".$chatid."'";
	  executeQuery($sql1,$conn);
	  $sql2  = "UPDATE sptbl_users SET vOnline = '0' WHERE nUserId = '".$_SESSION['sess_userid']."' ";
      executeQuery($sql2,$conn);
	  $_SESSION['sess_userid'] = "";
      $_SESSION['sess_username']= "";
	  $_SESSION["sess_useremail"] ="";
	  $_SESSION["sess_userfullname"] = "";
	  $_SESSION["sess_usercompid"]	= "";
      $_SESSION["sess_cssurl"] = "";
	  $_SESSION['sess_language']= "";
	  $_SESSION["sess_clientchatid"] = "";
	  $_SESSION["sess_clientchatdepid"] = "";
	  /*session_unregister('sess_userid');
          session_unregister('sess_username');
          session_unregister('sess_useremail');
          session_unregister('sess_userfullname');
          session_unregister('sess_usercompid');
	  session_unregister('sess_cssurl');
	  session_unregister('sess_language');
	  session_unregister('sess_clientchatid');
	  session_unregister('sess_clientchatdepid');*/
?>
