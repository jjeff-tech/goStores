<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>    		                      |
// |          									                          |
// +----------------------------------------------------------------------+
	
	if ($_GET["id"] != "") {
		$var_id = $_GET["id"];
	}
	elseif ($_POST["id"] != "") {
		$var_id = $_POST["id"];
	} 
		
	if($_GET["mt"] == "y") {
      $var_styleminus = $_GET["styleminus"];
		$var_stylename = $_GET["stylename"];
		$var_styleplus = $_GET["styleplus"];
    }
    elseif($_POST["mt"] == "y") {
      $var_styleminus = $_POST["styleminus"];
		$var_stylename = $_POST["stylename"];
		$var_styleplus = $_POST["styleplus"];
   }
		
	if($_POST["postback"] == "D") {
	   	$sql = "Delete from sptbl_cannedmessages where nMsgId='" . mysql_real_escape_string($var_id) . "'";
		executeQuery($sql,$conn);
		header("location:cannedmessages.php?mt=y&stylename=STYLECHAT&styleminus=minus12&styleplus=plus12&");
		/*echo("<script>window.location.href=\"pvtmessages.php?mt=y&stylename=STYLEPRIVATEMESSAGES&styleminus=minus2&styleplus=plus2&\"</script>");	
		*/
		exit;
	}

	if($_POST["postback"] == "U") {
	    $var_ttl = $_POST["txtTitle"];
		$var_des = $_POST["txtDesc"];
		$var_status = $_POST["rdSts"];
	   	$sql = "Update sptbl_cannedmessages SET vTitle='".mysql_real_escape_string($var_ttl)."',vDescription='".mysql_real_escape_string($var_des)."',vStatus='".mysql_real_escape_string($var_status)."' where nMsgId='" . mysql_real_escape_string($var_id) . "'";
		executeQuery($sql,$conn);
		header("location:cannedmessages.php?mt=y&stylename=STYLECHAT&styleminus=minus12&styleplus=plus12&");
		/*echo("<script>window.location.href=\"pvtmessages.php?mt=y&stylename=STYLEPRIVATEMESSAGES&styleminus=minus2&styleplus=plus2&\"</script>");	
		*/
		exit;
	}
	
	$sql = "Select CM.nMsgId,CM.vTitle,CM.vDescription,CM.dDate,CM.vStatus from sptbl_cannedmessages CM where CM.nMsgId='". mysql_real_escape_string($var_id) . "' ";
	$result = executeSelect($sql,$conn);
	if (mysql_num_rows($result) > 0) {
		$row = mysql_fetch_array($result);
		$var_title = $row["vTitle"];
		$var_desc = $row["vDescription"];
		$var_date = $row["dDate"];
		$var_status = $row["vStatus"];
	}
	else {
		header("location:cannedmessages.php?mt=y&stylename=STYLECHAT&styleminus=minus12&styleplus=plus12&");
		exit;	
	}	
	mysql_free_result($result);
?>
<form name="frmCannedMessage" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>">
<div class="content_section">


 <div class="content_section_title">
	<h3><?php echo TEXT_VW_CANNED_MESSAGE ?></h3>
	</div>
     <div class="content_section_data">
         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">

                 <tr>
         <td width="100%" align="center" colspan=3 >&nbsp;</td>

         </tr>

		

         <tr>
         <td width="100%" align="center" colspan=3 class="messsagebar">
         <?php echo $var_message ?></td>

         </tr>

			
		<tr>
         <td width="13%" align="left">&nbsp;</td>
         <td width="26%" align="left" class="toplinks"><?php echo TEXT_MESSAGE_DATE?> </td>
         <td width="61%" align="left"  class="toplinks"><?php echo date("m-d-Y",strtotime($var_date)); ?>
         
         </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
		
         <tr>
         <td width="13%" align="left">&nbsp;</td>
         <td width="26%" align="left" class="toplinks"><?php echo TEXT_MESSAGE_TITLE?> </td>
         <td width="61%" align="left">
         <input name="txtTitle" type="text"   class="comm_input input_width1" id="txtTitle" size="72" maxlength="100" value="<?php echo htmlentities($var_title); ?>" style="font-size:11px; ">
         </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks" valign="top"><?php echo TEXT_MESSAGE_DESC ?></td>
                      <td width="61%" align="left">
                        <textarea name="txtDesc" cols="70"   rows="12" id="txtDesc" class="comm_input input_width1" style="width:430px;"><?php echo htmlentities($var_desc); ?></textarea></td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
					  <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks" valign="top"><?php echo TEXT_MESSAGE_STATUS ?></td>
                      <td width="61%" align="left">
                      <input name="rdSts" type="radio" value="1" <?php echo(($var_status == '1')?"checked":""); ?>>
						Active 
						<input name="rdSts" type="radio" value="0"  <?php echo(($var_status == '0')?"checked":""); ?>>
						Not Active
                      </td>
					  
                      </tr>
					  
                              </table>
                                     <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center"  class="whitebasic">
                                    <td width="22%">&nbsp;</td>
                                    <td width="16%">&nbsp;</td>
                                    <td width="14%"><input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT; ?>"  onClick="javascript:updateMessage();"></td>
									<td width="14%"><input name="btDelete" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_DELETE; ?>"  onClick="javascript:deleteMessage();"></td>
                                    <td width="16%"></td>
                                    <td width="12%">&nbsp;</td>
                                    <td width="20%">
									<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
									<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
									<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">																
									<input type="hidden" name="id" value="<?php echo($var_id); ?>">
									<input type="hidden" name="postback" value="">
									</td>
                                  </tr>
                              </table>
                     
  </div>
</div>
</form>