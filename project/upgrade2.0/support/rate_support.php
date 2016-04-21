<?php

   // header("location:client_chat.php?username=".$username); 
   require_once("./includes/applicationheader.php");
   include("./languages/".$_SP_language."/client_chat_rating.php");
   $conn = getConnection();
    $flag_msg="";
   if ( isset($_POST["chatid"]) && ($_POST["chatid"] !="")) $chatid= $_POST["chatid"];
   if (isset($_GET["chatid"]) && ($_GET["chatid"] !="")) $chatid= $_GET["chatid"] ;
   $sql = "select nUserId,nStaffId,vStatus from sptbl_chat where nChatId='".$chatid."'";
   $result = executeSelect($sql,$conn);
   if ( mysql_num_rows($result) > 0 ) {
       while ($row = mysql_fetch_array($result)) {
		 $userid=($row["nUserId"]) ? $row["nUserId"] : '';
		 $staffid=($row["nStaffId"]) ? $row["nStaffId"] : ''  ;
		 $status=($row["vStatus"]) ? $row["vStatus"] : ''  ;
       }
   }
   if ( $status == 'pending' ) {$messagetext = TEXT_CANT_RATE;  $flag_msg="class='msg_error'";}
   if ( $_POST["postback"] == 'S' ) {
     if ( $status != 'pending' ) {
	  $chatid = $_POST["chatid"];
	  $rate = $_POST["rdRate"];
	  $comment = ($_POST["txtComment"] !='') ? $_POST["txtComment"] : '';
	//  $sql = "update sptbl_chat set vRateMann='".$rateMann."', vRateProf='".$rateProf."', tComment='".addslashes($comment)."' where nChatId ='".$chatid."'";
	//  executeQuery($sql,$conn);
	  $sql = "select nSRId from sptbl_staffratings where vType='C' and nTicketId='".$chatid."'";
	  $result = executeSelect($sql,$conn);
      if ( mysql_num_rows($result) > 0 ) {
	     $sql = "update sptbl_staffratings set tComments = '".$comment."', nMarks='".$rate."' where nTicketId='".$chatid."' and vType='C'";
      } else {
         $sql = "insert into sptbl_staffratings(nSRId,nUserId , nStaffId ,  nTicketId , tComments, nMarks, vType ) values ('', '".$userid."', '".$staffid."', '".$chatid."', '".addslashes($comment)."', '".$rate."', 'C')";
	  }
	  executeQuery($sql,$conn);
	  $messagetext = TEXT_RATE_SUBMITTED;
           $flag_msg="class='msg_success'";
	 }
   }
   $sql = "select nMarks, tComments from sptbl_staffratings where vType='C' and nTicketId='".$chatid."'";
   $result = executeSelect($sql,$conn);
   if ( mysql_num_rows($result) > 0 ) {
       while ($row = mysql_fetch_array($result)) {
		 $rate=($row["nMarks"]) ? $row["nMarks"] : '';
		 $comment=($row["tComments"]) ? $row["tComments"] : ''  ;
       }
   }
   
?>
<html>
 <head>
  <title></title>
  <script language="javascript" type="text/javascript" src="scripts/ajax_global.js"></script>
  <script language="javascript" type="text/javascript">
    function submitRate() {
	   var flg = 0
	   for (var i=0; i < document.frmRate.rdRate.length; i++) {
    	  if (document.frmRate.rdRate[i].checked) flg = 1;
       }
	   if ( flg == 0 || flg == 0 ) {
	    alert('<?php echo MESSAGE_JS_MANDATORY_ERROR_RATEBUTTONS; ?>');
        return false;
	   }
	   frmRate.postback.value="S";
       frmRate.submit();   
   	}
  </script>
  <link href="<?php echo($_SESSION["sess_cssurl"]);?>" rel="stylesheet" type="text/css">
 </head>
 <body bgcolor="#EDEBEB">
  <table width="100%" height="100%" border="0" class="div_all">
   <form id="frmRate" name="frmRate" method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>">
	<tr class="tab_headerrow">
     <td colspan="2" align="right"><?php echo TEXT_RATETITLE; ?>&nbsp;</td>
    </tr>
	<tr>
     <td align="center" ><div <?php echo $flag_msg;?>>&nbsp;<?php echo $messagetext; ?></div></td>
    </tr>
	<tr >
     <td ><b><?php echo TEXT_RATEQUESTION;?></b></td>
    </tr>
	<tr>
     <td>
	  <table width="100%" height="100%" class="tab_body">
	   <tr>
		<td width="15%" align="right" ><br><b><?php echo TEXT_RATE_LOW;?></b></td>
		<td width="3%">1<br><input name="rdRate" type="radio" value="1" <?php if ($rate =='1') echo "checked"; ?>></td>
		<td width="3%">2<br><input name="rdRate" type="radio" value="2" <?php if ($rate =='2') echo "checked"; ?>></td>
		<td width="3%">3<br><input name="rdRate" type="radio" value="3" <?php if ($rate =='3') echo "checked"; ?>></td>
		<td width="3%">4<br><input name="rdRate" type="radio" value="4" <?php if ($rate =='4') echo "checked";?>></td>
		<td width="3%">5<br><input name="rdRate" type="radio" value="5" <?php if ($rate =='5') echo "checked";?>></td>
		<td width="3%">6<br><input name="rdRate" type="radio" value="6" <?php if ($rate =='6') echo "checked"; ?>></td>
		<td width="3%">7<br><input name="rdRate" type="radio" value="7" <?php if ($rate =='7') echo "checked"; ?>></td>
		<td width="3%">8<br><input name="rdRate" type="radio" value="8" <?php if ($rate =='8') echo "checked"; ?>></td>
		<td width="3%">9<br><input name="rdRate" type="radio" value="9" <?php if ($rate =='9') echo "checked";?>></td>
		<td width="3%">10<br><input name="rdRate" type="radio" value="10" <?php if ($rate =='10') echo "checked";?>></td>
		<td width="37%" ><br><b><?php echo TEXT_RATE_HIGH;?></b></td>
	   </tr>
	   <tr>
	    <td colspan=12 align="center" ><b><?php echo TEXT_RATECOMMENTS;?></b><br><textarea name="txtComment" cols="15" rows="4" id="txtComment" class="textarea" style="width:90%;"><?php echo $comment;?></textarea></td>
	   </tr>
	    <td>&nbsp;</td>
	    <td colspan=10 align="center">
	     <input name="btnSubmitRate" id="btnSubmitRate" type="button" class="button" value="<?php echo BUTTON_TEXT_SUBMIT; ?>" onClick="javascript:submitRate();">&nbsp;
		 <input name="btnClose" id="btnClose" type="button" class="button" value="<?php echo BUTTON_TEXT_CLOSE; ?>" onClick="javascript:window.close();">
		</td>
	   </tr>
	   <tr>
	    <td><input type="hidden" id="postback" name="postback" value=""><input type="hidden" id="chatid" name="chatid" value="<?php echo $chatid; ?>"></td>
	   </tr>
	  </table>
	 </td>
    </tr>
   </form> 
  </table>
 </body>
</html>
