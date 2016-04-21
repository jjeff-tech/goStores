<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>    		                      |
// |          									                          |
// +----------------------------------------------------------------------+
	$var_staffid = $_SESSION["sess_staffid"];
          $flag_msg = "";
	if ($_GET["id"] != "") {
		$var_id = $_GET["id"];
	}
	elseif ($_POST["id"] != "") {
		$var_id = $_POST["id"];
	}
	if ($_GET["tk"] != "") {
		$var_ticket_id = $_GET["tk"];
	}
	elseif ($_POST["tk"] != "") {
		$var_ticket_id = $_POST["tk"];
	}
	if ($_GET["stylename"] != "") {
		$var_styleminus = $_GET["styleminus"];
		$var_stylename = $_GET["stylename"];
		$var_styleplus = $_GET["styleplus"];
	}
	else {
		$var_styleminus = $_POST["styleminus"];
		$var_stylename = $_POST["stylename"];
		$var_styleplus = $_POST["styleplus"];
	}	
	

	$var_title = $_POST["txtTitle"];
	$var_desc = $_POST["txtDesc"];

	if ($_POST["postback"] == "" && $var_id != "") {
		$sql = "SELECT nFBId,nTicketId,
					vFBTitle,tFBDesc
				FROM sptbl_feedback
		 		 WHERE nFBId='" . mysql_real_escape_string($var_id) . "' ";
		$result = executeSelect($sql,$conn);
		if (mysql_num_rows($result) > 0) {	
			$var_row = mysql_fetch_array($result);
			$var_title = $var_row["vFBTitle"];
			$var_desc = $var_row["tFBDesc"];
		}
		else {
			$var_message = MESSAGE_RECORD_ERROR;
                          $flag_msg = "class='msg_error'";
		}
	}
	
	
?>
<form name="frmDetail" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>">
<table width="100%"  border="0">
  <tr>
    <td width="76%" valign="top">
    <table width="100%"  border="0" cellspacing="10" cellpadding="0">
    <tr>
    <td>
    <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="dotedhoriznline">
     <tr>
     <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
     </tr>

     </table>

     <table width="100%"  border="0" cellspacing="0" cellpadding="0">
     <tr>
     <td width="1" class="vline"><img src="./../images/spacerr.gif" width="1" height="1"></td>
     <td class="pagecolor">
     <table width="100%"  border="0" cellspacing="0" cellpadding="5">
     <tr>
     <td width="93%" class="heading" align="left"><?php echo TEXT_VIEW_FEEDBACK ?></td>
     </tr>
     </table>

     <table width="100%"  border="0" cellspacing="0" cellpadding="5">
     <tr>
     <td>
         <table width="100%"  border="0" cellspacing="0" cellpadding="0">

                 <tr>
         <td width="100%" align="center" colspan=3 >&nbsp;</td>

         </tr>
         <tr>
         <td width="100%" align="center" colspan=3 class="messsagebar">
        <div <?php echo $flag_msg; ?>> <?php echo $var_message ?></div></td>
         </tr>
		<tr><td colspan="3">
			<table BORDER=0 width="100%">
			<tr align="left"  class="listingmainboldtext">
				<td colspan=2>
					<b><?php echo htmlentities(stripslashes($var_title)); ?></b>
				</td>
			</tr>
			<tr align="left"  class="listingmaintext">
	     		<td colspan=2>
					<span>
					<?php  echo nl2br(stripslashes(htmlentities($var_desc)))?>
					</span>
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			</table>
			
			</td></tr>
             </table>
                        </td>
                            </tr>
                        </table></td>
                      <td width="1" class="vline"><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td class="dotedhoriznline"><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table></td>
              </tr>
            </table>
			
            <table width="100%"  border="0" cellspacing="10" cellpadding="0">
              <tr>
                <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" class="dotedhoriznline">
                    <tr>
                      <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr >
                        <td width="1" class="vline"><img src="./../images/spacerr.gif" width="1" height="1"></td>
                        <td ><table width="100%"  border="0" cellspacing="0" cellpadding="5" >
                            <tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center"  class="listingbtnbar">
                                    <td width="100%">
                                   
									<input name="btnBack" type="button" class="button" value="<?php echo BUTTON_TEXT_BACK; ?>" onClick="javascript:goBack();">
									
									</td>
                                  </tr>
                              </table></td>
                            </tr>
                        </table></td>
                        <td width="1" class="vline"><img src="./../images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                    </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td class="dotedhoriznline"><img src="./../images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                  </table></td>
              </tr>
            </table>
            <p class="ashbody">&nbsp;</p></td>
  </tr>
</table>

</form>