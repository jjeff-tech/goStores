<?php
   require_once("./includes/applicationheader.php");
   include("./languages/".$_SP_language."/client_chat.php");
   $user_id=$_SESSION["sess_userid"];
   $chatid=$_SESSION["sess_clientchatid"];
   $department=$_SESSION["sess_clientchatdepid"];
   if (isset($_GET["username"]) &&  ($_GET["username"] != "") ) $user_name=$_GET["username"];
   else $user_name = $_SESSION["sess_userfullname"];
   if ($_GET["comp"] != "") $comp=$_GET["comp"];
   else $comp=$_SESSION["sess_usercompid"];
  
   $conn = getConnection();
  // $sql = "Select count(*) as staffcount from sptbl_staffs where vOnline ='1' and nStaffId in ( select nStaffId from sptbl_staffdept where nDeptId ='".$department."' )";
   $sql = "select count(s.nStaffId) as staffcount from sptbl_staffs s inner join sptbl_staffdept sd on ( s.nStaffId = sd.nStaffId )  inner join sptbl_depts d on ( sd.nDeptId = d.nDeptId )  where s.vOnline='1' and s.vDelStatus='0' and d.nDeptId='".$department."'";
   $result = executeSelect($sql,$conn);
   $rowcnt_onlinestaffs = mysql_num_rows($result);
   $msg ="";
   if( $rowcnt_onlinestaffs > 0 ) {
       while ($row = mysql_fetch_array($result)) {
		 $cnt_onlinestaffs=$row["staffcount"] ;
	   }
	   if ($cnt_onlinestaffs > 0 ){
         $sql = "Select c.nStaffId, c.vStatus, c.dTimeStart, c.dTimeEnd, c.vUserName, s.vStaffname, s.vStaffImg from sptbl_chat c left join sptbl_staffs s on ( c.nStaffId = s.nStaffId ) inner join sptbl_users u on (c.nUserId = u.nUserId) where nChatId='".$chatid."'";
         $result = executeSelect($sql,$conn);
		 if ( mysql_num_rows($result) > 0) {
		   while ($row = mysql_fetch_array($result)) {
		     $stsAccpt=$row["vStatus"] ;
			 $tms = $row["dTimeStart"];
			 $tme = $row["dTimeEnd"];
			 $staffname = $row["vStaffname"];
			 $user = $row["vUserName"];
			 $stfimg = ($row["vStaffImg"]) ? ("staff/images/".$row["vStaffImg"]) : "N";
	       }
		   $sql_w = "Select vChatWelcomeMessage from sptbl_companies where nCompId ='".$comp."'";
           $rs_w = executeSelect($sql_w,$conn);
		   $row_w = mysql_fetch_array($rs_w);
		   $msg = $row_w['vChatWelcomeMessage']; 
		   if ($stsAccpt == 'accepted' ) {
		      $msgConnect = 'cd';
   		   }
		   else if ($stsAccpt == 'finished' ) $msgConnect = 'fh';
		   else $msgConnect = 'cg'; 
		 }
       } else $msgConnect = 'ol';  
   } else {
       $msgConnect = 'ol';
	   
   }	

   $matter = '';
   $sql = "Select tMatter from sptbl_chat where nChatId='".$chatid."'";
   $result = executeSelect($sql,$conn);
   if ( mysql_num_rows($result)> 0 ) {
      while($row = mysql_fetch_array($result)) {
		 $matter=$row["tMatter"] ;
	  }
   }
   $sql = "Select vChatOperatorRating from sptbl_companies where nCompId='".$comp."'";
   $result = executeSelect($sql,$conn);
   if ( mysql_num_rows($result)> 0 ) {
      while($row = mysql_fetch_array($result)) {
		 $rtg=$row["vChatOperatorRating"] ;
	  }
   }
?>
<html>
 <head>
  <title></title>
  <link href="<?php echo($_SESSION["sess_cssurl"]);?>" rel="stylesheet" type="text/css">
  <script language="javascript" type="text/javascript" src="scripts/ajax_global.js"></script>
  <script type="text/javascript" src="scripts/jquery.js"></script>
  <script language="JavaScript" type="text/javascript">
window.onbeforeunload = function (e) {
var staffid="<?php echo $staffid; ?>";
var serverurl="<?php  echo 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/endChatSession.php';?>";
$.ajax({
url:serverurl,
type:'get',
data:"mod=X&chatid="+cid_g,
success:function(data) {

}
});

};
</script>
  <script language="javascript" type="text/javascript" src="scripts/client_chat.js"></script>
  <script language="javascript" type="text/javascript">
   self.resizeTo(950,600);
   function sendMailLog() {
     var divMail = getChildById('divEmail') ;
     var spanEmail = getChildById('spanEmail',divMail);
     var widEmail = getChildById('txtEmail',spanEmail);
     var email =  widEmail.value;
     if ( !validateEmail(email)) {
	   alert('<?php echo MESSAGE_JS_EMAIL_ERROR; ?>');
	   return false;
	 } else {
	   send_data_one( '',"emailChatlog.php?chatid="+cid_g+"&email="+ email);
       if(xmlHttp1.responseText == 'send') {
	     var spanAlert = getChildById('spanAlert',divMail);
		 spanAlert.innerHTML = '<?php echo MESSAGE_MAIL_SUCCESS; ?>';
	   } else {
	     var spanAlert = getChildById('spanAlert',divMail);
		 spanAlert.innerHTML = '<?php echo MESSAGE_MAIL_ERROR; ?>';
	   }
	 }
   }
   function printChat() {
    now  =  new  Date;
	var dteStr = now.getFullYear() + ':' + zeroFilledValue(now.getMonth()) + ':' + zeroFilledValue(now.getDate()) + ' ' + zeroFilledValue(now.getHours()) + ':' + zeroFilledValue(now.getMinutes())+ ':' + zeroFilledValue(now.getSeconds());
	var left = 100;
    var top = 100;
	var html = "<table>";
	html += "<tr><td><b><?php echo TEXT_PRINT_CHATLOG; ?></b></td></tr>";
	html += "<tr><td ><b><?php echo TEXT_PRINT_DATE; ?>"+":"+dteStr+"</b></td></tr>";
	html += "<tr><td>";
	var printReadyElem = document.getElementById("divChatDisplay");
	if (printReadyElem != null)	{
	  html += printReadyElem.innerHTML;
	} else {
	  alert("Print Section Not found !");
	  return;
	}
	html += "</td></tr><table>";
	try{
	  var oIframe = getChildById('ifrmPrt');
	  var oDoc = (oIframe.contentWindow || oIframe.contentDocument);
	  if (oDoc.document) oDoc = oDoc.document;
	  oDoc.write('<HTML><HEAD></HEAD>');
	  oDoc.write('<BODY onload=\'this.focus(); this.print();\'>');
	  oDoc.write(html + '</BODY></HTML>');
	  oDoc.close();
	} catch(e){
	  var printWin = window.open("","PrintChat","top=" + top + ",left=" + left + ",toolbars=no,maximize=yes,resize=yes,width=600,height=600,location=no,directories=no,scrollbars=yes");
      printWin.document.open();
	  printWin.document.write('<HTML><HEAD></HEAD><BODY>');
	  printWin.document.write(html);
	  printWin.document.write('</BODY></HTML>');
	  printWin.document.close();
	  printWin.focus();
	  printWin.print();
	}
   }
  </script>
 </head>
 <body  bgcolor="#EDEBEB" onload="init_chatpage(<?php echo $chatid;?>,<?php echo $department;?>,<?php echo $comp;?>,<?php echo $user_id ;?>);">
  <!-- Container Div-->
    <div id="divAll" class="div_all">
	  <!-- Connecting Div start-->
      <div id="divCallConnect" class="div_call_connect" >
	    <span id="spnStaffImg" class="span_staff_img" style="display:<?php if ( (($msgConnect == 'cd') || ($msgConnect == 'fh')) && ($stfimg !="N") ) echo ""; else echo "none"; ?>;">
		  <img class="image_staff" border="1" id="imgStaff" src="<?php if ( $stfimg !="N" ) echo $stfimg; ?>" width="100%" height="100%"></img>
		</span>
	  </div>
	  <!-- Connecting Div end-->
	   <!-- Chat Display Div start-->
	  <div id="divStatus" class="div_status" >
		  <a href="#" onclick="calldesktop(<?php echo $chatid;?>);"><img style="position:absolute;right:29%; top:5%; width:30px;height:26px; border:0;" alt="<?php echo BUTTON_TEXT_SHARE_DESKTOP;?>" src="images/chat/desktop.gif" title="<?php echo TOOLTIP_DESKTOP;?>" ></a>
 	  	  <a href="#" onclick="emailChat();"><img alt="<?php echo BUTTON_TEXT_CHAT_EMAIL;?>" src="images/chat/email5.gif" width="30" height="26" style="position:absolute;right:22%; top:5%;  width:30px;height:26px; border:0;" title="<?php echo TOOLTIP_EMAIL;?>"></a>
	  	  <a href="#" onclick="printChat(<?php echo $user_id ;?>);"><img style="position:absolute;right:15%; top:5%;  width:30px;height:26px; border:0;" alt="<?php echo BUTTON_TEXT_CHAT_PRINT;?>" src="images/chat/print3.gif" title="<?php echo TOOLTIP_PRINT;?>"></a>
	      <a href="#" onclick="rateSupport();"><img alt="<?php echo BUTTON_TEXT_CHAT_RATESUPPORT;?>" src="images/chat/rating3.gif" width="30" height="26" style="position:absolute;right:8%; top:5%;  width:30px;height:26px; border:0; display:<?php if( $rtg == '0') echo "none"; else echo ""; ?>" title="<?php echo TOOLTIP_RATESUPPORT;?>"></a>
	      <a href="#" onclick="exitChat(<?php echo $comp;?>);"><img alt="<?php echo BUTTON_TEXT_CHAT_EXIT;?>" src="./languages/<?php echo $_SP_language;?>/images/exit1.gif" width="59px" height="26px" style="position:absolute;right:1%; top:5%;  width:59px;height:26px; border:0;" title="<?php echo TOOLTIP_EXIT;?>"></a>
	  </div>
	  <!-- Chat Display Div end-->
	  <div id="divInfo" class="div_info" >
	  	<span id="spanSts" class="online_status" style="position:absolute;left:1%;top:12%; bottom:12%;">
		   <?php
		      if( $msgConnect == 'cd' ) echo TEXT_CONNECTED_TO." ".$staffname ;
			  else if ( $msgConnect == 'cg' ) echo TEXT_CALLING;
			  else if ( $msgConnect == 'fh' ) echo TEXT_CHAT_FINISHED ;
			  else echo TEXT_STAFFOFFLINE;
		   ?>
		</span>
	    <span id= "spanInfo" class ="info_message" style="position:absolute;right:1%;top:12%; bottom:12%;"><?php if( (($msgConnect == 'cd') || ($msgConnect == 'fh')) && $msg != '' ) echo $msg;?></span>
	  </div>
	  <!-- Chat Display Div start-->
	  <div id="divChatDisplay" class="div_chat_display" >
	      <?php echo $matter;?>
	  </div>
	  <!-- Chat Display Div end-->
      <span id="spanTime" style="position:absolute;right:0%; top:84%; width:10%;height:95%;color:#000000; font-weight:bold;"></span>
	  <!-- Chat Enter Div start-->
	  <div id="divChat"  class="div_chat">
	      <span id="spanChat" style="position:absolute;left:0%;top:0%; width:89%; height:100%">
		   <input type="text"  class="chat_textbox" id="txtMsg" onkeypress="return onChatEnterPress(event,'<?php echo $user_name ?>');"/>
          </span>
		  <span id="spanSend" style="position:absolute;top:0%; right:0%; width:10%; height:100%;">
           <input type="button" value="Send" id="btnSnd" style="width:100%; height:100%;" disabled="true" onClick="sendChat('<?php echo $user_name ?>');"/>
          </span>
	  </div>
	  <div id="divFooter" class="div_footer">
	    <table class="topbar" width="100%" border="0" cellpadding="0" cellspacing="0">
	     <tbody>
	      <tr>
	       <td class="toplinks" width="97%" align="right">
		    <span class="helpdeskname"><?php echo TEXT_POWERED_BY ?></span></td>
  	       <td width="3%" align="right">&nbsp;</td>
	      </tr>
         </tbody>
	   </table>
	  </div>
      <div id="divEmail" class="div_email" style="display:none;">
	    <div class="topbar_popupdiv" ><span class="topbar_popupdiv_title"><?php echo TEXT_MAIL_LOG_TITLE;?></span><span class="topbar_popupdiv_X" onClick="closeMailDiv();">X</span></div>
		<span id="spanAlert" style="position:absolute;left:20%;top:17%;color:#FF3300;"></span>
	    <span style="position:absolute;left:2%;top:35%; width:10%"><?php echo TEXT_EMAIL_ADDRESS;?></span>
		<span id="spanEmail" style="position:absolute;left:20%;top:35%;"><input type="text" class="textbox" id="txtEmail"/></span>
		<span style="position:absolute;left:20%;bottom:15%; width:10%"><input type="button" class="button" value="<?php echo BUTTON_TEXT_SEND_MAIL;?>" id="btnSndMail" onClick="sendMailLog();"/> </span>
		<span style="position:absolute;right:35%;bottom:15%; width:10%"><input type="button" class="button" value="<?php echo BUTTON_TEXT_CLOSE;?>" id="btnClose" onClick="closeMailDiv();"/> </span>
	  </div>
	  <div id="divShareWarn" class="div_share_warn" style="display:none;">
	    <div class="topbar_popupdiv" ><span class="topbar_popupdiv_title"><?php echo TEXT_SHARE_WARN_TITLE;?></span><span class="topbar_popupdiv_X" onClick="closeShareWarn();">X</span></div>
		<div style="position:absolute;left:2%;top:15%; width:98%"><p><?php echo TEXT_SHARE_WARN1."<br>".TEXT_SHARE_WARN2;?></p></div>
		<span style="position:absolute;left:35%;bottom:15%; width:10%"><input type="button" class="button" value="<?php echo BUTTON_TEXT_YES;?>" id="btnYes" onClick="callDesktopShareWindow(<?php echo $chatid ;?>);"/> </span>
  		<span style="position:absolute;right:40%;bottom:15%; width:10%"><input type="button" class="button" value="<?php echo BUTTON_TEXT_NO;?>" id="btnNo" onClick="closeShareWarn();"/> </span>
	  </div>
          
          <div id="divSessionWarn" class="div_share_warn" style="display:none;">
	   <div class="topbar_popupdiv" ><span class="topbar_popupdiv_title"> <?php echo TEXT_SHARE_WARN_TITLE;?></span><span class="topbar_popupdiv_X" onClick="closeSessionWarn();">X</span></div>
            <div style="position:absolute;left:20%;top:15%; width:98%"><p><?php echo "You have one active session";?></p></div>
	   
	  </div>
          
	  <frame src="chatRefresh.php" frameborder="0" width="0%" height="0%" frameborder="0"></frame>
	  <iframe id="ifrmRDS" src="" frameborder="0" width="0px;" height="0px;" frameborder="0"></iframe>
	  <iframe id="ifrmPrt" src="" frameborder="0" width="0px;" height="0px;" frameborder="0"></iframe>
	  <!-- Chat Enter Div end-->
   </div>
  <!-- Container Div end-->
 </body>
</html>  