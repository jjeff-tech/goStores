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
	//reset message status as its viewed
	$sql = "Update sptbl_pvtmessages set vStatus='c' where nPMId='" . addslashes($var_id) . "'";
    executeQuery($sql,$conn);
	
	
	if($_POST["postback"] == "D") {
	   	$sql = "Delete from sptbl_pvtmessages where nPMId='" . addslashes($var_id) . "'";
		executeQuery($sql,$conn);
		header("location:pvtmessages.php?mt=y&stylename=STYLEPRIVATEMESSAGES&styleminus=minus2&styleplus=plus2&");
		/*echo("<script>window.location.href=\"pvtmessages.php?mt=y&stylename=STYLEPRIVATEMESSAGES&styleminus=minus2&styleplus=plus2&\"</script>");	
		*/
		exit;
	}
	
	$sql = "Select PM.nPMId,PM.vPMTitle,PM.tPMDesc,PM.dDate,PM.vStatus,S.vStaffName as 'FromName',S1.vStaffName as 'ToName'
	 		from sptbl_pvtmessages PM inner join sptbl_staffs S on PM.nFrmStaffId = S.nStaffId inner join
	 		sptbl_staffs S1 on PM.nToStaffId = S1.nStaffId where PM.nPMId='". addslashes($var_id) . "' ";
		;
	$result = executeSelect($sql,$conn);
	if (mysql_num_rows($result) > 0) {
		$row = mysql_fetch_array($result);
		$var_title = $row["vPMTitle"];
		$var_desc = $row["tPMDesc"];
		$var_from = $row["FromName"];
		$var_to = $row["ToName"];
		$var_date = $row["dDate"];
		$var_status = $row["vStatus"];
	}
	else {
		header("location:pvtmessages.php?mt=y&stylename=STYLEPRIVATEMESSAGES&styleminus=minus2&styleplus=plus2&");
		exit;	
	}	
	mysql_free_result($result);
?>
<form name="frmPvtMessage" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>">

<div class="content_section">
 <div class="content_section_title">
	<h3><?php echo TEXT_VW_PVT_MESSAGE ?></h3>
	</div>
    
    

     
     


   
         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">

                 <tr>
         <td width="100%" align="center" colspan=3 >&nbsp;</td>

         </tr>

         <tr>
         <td width="100%" align="center" colspan=3 class="messsagebar">
        <?php
												
												if ($var_message != ""){
												?>
													<div class="msg_error">
												<?php echo($var_message); ?>
												</div>
												<?php
												}
												?>

         </tr>

			<tr><td colspan="3">&nbsp;</td></tr>
		<tr>
         <td width="13%" align="left">&nbsp;</td>
         <td width="26%" align="left" class="toplinks"><?php echo TEXT_MESSAGE_DATE?> </td>
         <td width="61%" align="left"  class="toplinks"><?php echo date("m-d-Y",strtotime($var_date)); ?>
         
         </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
		<tr>
         <td width="13%" align="left">&nbsp;</td>
         <td width="26%" align="left" class="toplinks"><?php echo TEXT_MESSAGE_STATUS?> </td>
         <td width="61%" align="left"  class="toplinks"><?php echo (($var_status == "o")?"New":"Viewed"); ?>
         
         </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
		<tr>
         <td width="13%" align="left">&nbsp;</td>
         <td width="26%" align="left" class="toplinks"><?php echo TEXT_MESSAGE_FROM?> </td>
         <td width="61%" align="left">
         <input name="txtFrom" type="text"  readonly="true" class="comm_input input_width1" id="txtFrom" size="72" maxlength="100" value="<?php echo htmlentities($var_from); ?>" style="font-size:11px; ">
         </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>	
			<tr>
         <td width="13%" align="left">&nbsp;</td>
         <td width="26%" align="left" class="toplinks"><?php echo TEXT_MESSAGE_TO?> </td>
         <td width="61%" align="left">
         <input name="txtTo" type="text"  readonly="true"  class="comm_input input_width1" id="txtTo" size="72" maxlength="100" value="<?php echo htmlentities($var_to); ?>" style="font-size:11px; ">
         </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>		  		  			  
			
         <tr>
         <td width="13%" align="left">&nbsp;</td>
         <td width="26%" align="left" class="toplinks"><?php echo TEXT_MESSAGE_TITLE?> </td>
         <td width="61%" align="left">
         <input name="txtTitle" type="text"   readonly="true" class="comm_input input_width1" id="txtTitle" size="72" maxlength="100" value="<?php echo htmlentities($var_title); ?>" style="font-size:11px; ">
         </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks" valign="top"><?php echo TEXT_MESSAGE_DESC ?></td>
                      <td width="61%" align="left">
                        <textarea name="txtDesc" cols="70"  readonly="true"  rows="12" id="txtDesc" class="comm_input input_width1" style="width:430px;"><?php echo htmlentities($var_desc); ?></textarea></td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
                              </table>
                       
                  
            <table width="100%"  border="0" cellspacing="10" cellpadding="0">
              <tr>
                <td>
                   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center"  class="whitebasic">
                                    <td width="22%">&nbsp;</td>
                                    <td width="16%">&nbsp;</td>
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
                    </td>
              </tr>
            </table>
  
</form>