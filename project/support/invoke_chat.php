<?php
   if ($_GET["comp"] !='' ) $comp = $_GET["comp"]; 
   if ($_POST["comp"] !='' ) $comp = $_POST["comp"];
   require_once("./includes/applicationheader.php");
   include("./languages/".$_SP_language."/invoke_chat.php");
   $conn = getConnection();
   if (userLoggedIn() && ($_SESSION["sess_clientchatid"] !='')) {
      /*$sql = "select vUserName from sptbl_users where nUserId = '".$_SESSION["sess_userid"]."'";
	  $result = executeSelect($sql,$conn);
	  if(mysql_num_rows($result) > 0) {
	     while($row = mysql_fetch_array($result)) {
			$username = $row["vUserName"];
		 }
	  }*/
	  $username = $_SESSION["sess_userfullname"] ;
      header("location:client_chat.php?username=".$username."&comp=".$comp);
   }
   
   /*Newly added on 190609*/
   $sql = "Select vLookUpName,vLookUpValue from sptbl_lookup WHERE vLookUpName IN('LangChoice',
          'DefaultLang','HelpdeskTitle','Logourl','logactivity','MaxPostsPerPage','OldestMessageFirst', 'PostTicketBeforeLogin','SMTPSettings','SMTPServer','SMTPPort')";
   $rs = executeSelect($sql,$conn);
   if(!isset($_SESSION['sess_cssurl'])){
      $_SESSION['sess_cssurl']="styles/coolgreen.css";
   }
   if (mysql_num_rows($rs) > 0) {
     while($row = mysql_fetch_array($rs)){
          switch($row["vLookUpName"]) {
             case "LangChoice":
                 $_SESSION["sess_langchoice"] = $row["vLookUpValue"];
                 break;
             case "DefaultLang":
                 $_SESSION["sess_defaultlang"] = $row["vLookUpValue"];
                 break;
             case "HelpdeskTitle":
                 $_SESSION["sess_helpdesktitle"] = $row["vLookUpValue"];
                 break;
             case "Logourl":
                 $_SESSION["sess_logourl"] = $row["vLookUpValue"];
                 break;
             case "logactivity":    //this session variable decides to log activities or not
	             $_SESSION["sess_logactivity"] = $row["vLookUpValue"];
				 break;
  			 case "MaxPostsPerPage":
 				 $_SESSION["sess_maxpostperpage"] = $row["vLookUpValue"];
				 break;
			 case "OldestMessageFirst":
			     $_SESSION["sess_messageorder"] = $row["vLookUpValue"];
				 break;
			 case "PostTicketBeforeLogin":
				 $_SESSION["sess_postticket_before_register"] = $row["vLookUpValue"];
				 break;												
			 case "SMTPSettings":
				 $_SESSION["sess_smtpsettings"] = $row["vLookUpValue"];
				 break;
			 case "SMTPServer":
				 $_SESSION["sess_smtpserver"] = $row["vLookUpValue"];
				 break;
			 case "SMTPPort":
				 $_SESSION["sess_smtpport"] = $row["vLookUpValue"];
				 break;												
          }
     }
   }
   mysql_free_result($rs);
   if($_SESSION["sess_userlangchange"] =="1"){
	  ;
   }else{
	  if (($_SESSION["sess_defaultlang"] !=$_SESSION["sess_language"])) {
        $_SESSION["sess_language"] = $_SESSION["sess_defaultlang"];
      }  
   } 
   /*end*/
   if ( $_POST["postback"] == 'S' ) {
     /*
	  $client_ip = $_SERVER['REMOTE_ADDR'];
      $sql = "update sptbl_visitors set vStatus='accepted', dLastUpdTime=now() where vStatus='invited' and  nCompId = '".$comp."' and vIpAddr = '".addslashes($client_ip)."'";
	  executeQuery($sql,$conn);
	 */
	  header("location:client_prechat.php?comp=".$comp); 
   }
?>
<html>
 <head>
  <title></title>
  <script language="javascript" type="text/javascript">
    function gotoLogin() { 
	  frmInvokeChat.postback.value="S";
      frmInvokeChat.submit();   
	}
  </script>
 <link href="<?php echo($_SESSION["sess_cssurl"]);?>" rel="stylesheet" type="text/css">
 </head>
 <body bgcolor="#EDEBEB" >
  <table width="100%" height="100%" border="0" class="div_all">
    <tr height="120px" width="100%" class="header_chat" >
     <td>&nbsp;</td>
    <!--td background="images/chat/title_bg.jpg">&nbsp;</td-->
    </tr>
    <tr height="90%">
    <td ><form id="frmInvokeChat" name="frmInvokeChat" method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>">
	 <table width="100%" height="70%" border="0" class="tab_body">
	   <tr class="column1">
        <td colspan="2"><b><?php echo TEXT_HEADER_INFORMATION; ?></b></td>
       </tr>
	   <tr>
        <td><?php echo TEXT_DESCRIPTION1 ?></td>
		<td><input name="btnStartChat" id="btnStartChat" type="button" class="button" value="<?php echo BUTTON_TEXT_CLICK_HERE ?>" onClick="javascript:gotoLogin();"></td>
       </tr>
	   <tr>
	    <td>
	    <input type="hidden" id="postback" name="postback" value="">
		<input type="hidden" id="comp" name="comp" value="<?php echo $comp ?>">
	    </td>
	   </tr>
      </table></form>
 	 </td>
    </tr>
   <tr height="10%">
    <td colspan="3">
	 <table class="topbar" width="100%" border="0" cellpadding="0" cellspacing="0">
	  <tbody>
	   <tr>
	    <td class="toplinks" width="97%" align="right">
		  <span class="helpdeskname"><?php echo TEXT_POWERED_BY ?></span></td>
  	    <td width="3%" align="right">&nbsp;</td>
	   </tr>
      </tbody>
	 </table>
	</td>
   </tr>
 </table>
 </body>
</html>
