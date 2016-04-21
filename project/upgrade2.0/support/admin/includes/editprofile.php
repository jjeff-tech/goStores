<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>                                          |
// |                                                                                                            |
// +----------------------------------------------------------------------+
        $var_id = $_SESSION["sess_staffid"];

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
        $var_country = "UnitedStates";
        //$var_userid = $_SESSION["sess_staffid"];
        $var_staffid = "1";
        $var_refreshRate = 60;
        $var_notifyAssign = 0;
        $var_notifyPvtMsg = 0;
        $var_notifyKB = 0;
        $var_cssId = 1;


        if ($_POST["postback"] == "" && $var_id != "") {

                $sql = "Select nStaffId,vStaffname,vLogin,vPassword,vOnline,vMail,vYIM,vSMSMail,vMobileNo,nCSSId,nRefreshRate,nNotifyAssign,";
                $sql .= "nNotifyPvtMsg,nNotifyKB,vType,tSignature from sptbl_staffs where nStaffId = '" . addslashes($var_id) . "' AND vDelStatus='0' ";
                $var_result = executeSelect($sql,$conn);
                if (mysql_num_rows($var_result) > 0) {
                        $var_row = mysql_fetch_array($var_result);

                        $var_staffName = $var_row["vStaffname"];
                        $var_staffLogin = $var_row["vLogin"];
                        $var_password = "";
                        $var_online = $var_row["vOnline"];
                        $var_email = $var_row["vMail"];
                        $var_yim = $var_row["vYIM"];
                        $var_smsMail = $var_row["vSMSMail"];
                        $var_mobile = $var_row["vMobileNo"];
                        $var_cssId = $var_row["nCSSId"];
                        $var_refreshRate = $var_row["nRefreshRate"];
                        $var_notifyAssign = $var_row["nNotifyAssign"];
                        $var_notifyPvtMsg = $var_row["nNotifyPvtMsg"];
                        $var_notifyKB = $var_row["nNotifyKB"];
                        $var_type = $var_row["vType"];
						$var_signature = $var_row["tSignature"];

                }
                else {
                        $var_message = MESSAGE_RECORD_ERROR ;
                        $flag_msg    = 'class="msg_error"';
                }
                mysql_free_result($var_result);
        }
        elseif ($_POST["postback"] == "U") {
                        $var_staffName = $_POST["txtStaffName"];
                        $var_staffLogin = $_POST["txtStaffLogin"];
                        $var_password = $_POST["txtPassword"];
                        $var_email = $_POST["txtEmail"];
                        $var_yim = $_POST["txtYim"];
                        $var_smsMail = $_POST["txtSmsMail"];
                        $var_mobile = $_POST["txtMobile"];
                        $var_cssId = $_POST["cmbCssId"];
                        $var_refreshRate = (int)$_POST["cmbRefresh"];
                        $var_refreshRate = is_int($var_refreshRate)?$var_refreshRate:60;
                        $var_notifyAssign = ($_POST["rdNotifyAssign"] == "1")?$_POST["rdNotifyAssign"]:"0";
                        $var_notifyPvtMsg = ($_POST["rdNotifyPvtMsg"] == "1")?$_POST["rdNotifyPvtMsg"]:"0";
                        $var_notifyKB = ($_POST["rdNotifyKB"] == "1")?$_POST["rdNotifyKB"]:"0";
                        $var_signature = $_POST["txtSignature"];
						if (validateUpdation() == true) {
							if(!isUniqueEmail1($var_email,$var_id,"s")) {
								$var_message = MESSAGE_NONUNIQUE_EMAIL ;
                                                                $flag_msg    = 'class="msg_error"';
							}
							else{
                                $sql = "Update sptbl_staffs set vStaffname='" . addslashes($var_staffName) . "',
                                                " . (($var_password != "")?("vPassword='" . md5($var_password) .  "',"):"") .
                                                "vMail='" . addslashes($var_email) . "',
                                                vYIM='" . addslashes($var_yim) . "',
                                                vSMSMail='" . addslashes($var_smsMail) . "',
                                                vMobileNo='" . addslashes($var_mobile) . "',
                                                nCSSId='" . addslashes($var_cssId) . "',
                                                nRefreshRate='" . addslashes($var_refreshRate) . "',
                                                nNotifyAssign='" . $var_notifyAssign . "',
                                                nNotifyPvtMsg='" . $var_notifyPvtMsg . "',
                                                nNotifyKB='" . $var_notifyKB . "',
												tSignature='" . addslashes($var_signature)  . "'  where nStaffId='" . addslashes($var_id) . "'";
								executeQuery($sql,$conn);

                        //Insert the actionlog
						if(logActivity()) {
                        $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Staff','" . addslashes($var_id) . "',now())";
                        executeQuery($sql,$conn);
						}


                        //update css

                     $sql = "Select vCSSURL from sptbl_css where nCSSId='$var_cssId'";

                     $result = executeSelect($sql,$conn);
                     if (mysql_num_rows($result) > 0) {
                         $row = mysql_fetch_array($result);
                         $_SESSION["sess_cssurl"] = $row["vCSSURL"];
                      }
                        //update css

                    $var_message = MESSAGE_RECORD_UPDATED;
                    $flag_msg    = 'class="msg_success"';
					//header("location:editprofile.php?stylename=STYLEPREFERANCES&styleminus=minus17&styleplus=plus17&");
					//exit;
                    /* echo "<script>location.href='editprofile.php?stylename=STYLEPREFERANCES&styleminus=minus17&styleplus=plus17&';</script>";
					*/


/*                                 if($var_password != "") {
                                                //mail the user the changed password
                                                        $var_mail_body = TEXT_MAIL_MODIFY_HEAD . "<br>";
                                                        $var_mail_body .= TEXT_PROFILE_LOGIN . " : " . $var_staffLogin . "<br>";
                                                        $var_mail_body .= TEXT_PROFILE_PASSWORD . " : " . $var_password . "<br>";
                                                        $var_mail_body .= TEXT_MAIL_WELCOME_TAIL;
                                                        $subject = "Your account has been modified";

                                                         //sendMail($var_id,$var_email,$var_mail_body,$subject,$conn);
                                }
*/
                                $var_password="";
								}
                        }
                        else {
                                $var_message = MESSAGE_RECORD_ERROR ;
                                $flag_msg    = 'class="msg_error"';
                        }
        }



        function validateUpdation()
        {
                /*global $conn,$var_id;
                //implement logic here
                $sql = "Select nCompId from sptbl_companies where nCompId='" . addslashes($var_id) .  "' AND vDelStatus='0'";
                if (mysql_num_rows(executeSelect($sql,$conn)) > 0) {
                        if (validateAddition() == false) {
                                return false;
                        }
                }
                else {
                        return false;
                }
                return true;*/
                return true;
        }


        $lst_css = "";
        //fill the css ids here
        $sql = "Select nCSSId,vCSSName from sptbl_css order by nCSSId";
        $result = executeSelect($sql,$conn);
        while ($row = mysql_fetch_array($result)) {
                $lst_css .=  "<option value=\"" . $row["nCSSId"] . "\"" . (($var_cssId == $row["nCSSId"])?"Selected":"") . ">" . htmlentities($row["vCSSName"]) . "</option>";
        }
        mysql_free_result($result);
        //end of fill the css ids here

?>
<div class="content_section">
<form name="frmStaff" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>">
 <Div class="content_section_title"><h3><?php echo TEXT_EDIT_PROFILE ?></h3></Div>  

         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">

                 <tr>
         <td align="center" colspan=3 >&nbsp;</td>

         </tr>

                <tr>
         <td align="center" colspan=3 class="toplinks">
         <?php echo TEXT_FIELDS_MANDATORY ?></td>

         </tr>

         <tr>
         <td align="center" colspan=3 <?php echo $flag_msg; ?>>
         <?php echo $var_message ?></td>
         </tr>

         <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="7%" align="left">&nbsp;</td>
         <td width="38%" align="left" class="toplinks"><?php echo TEXT_PROFILE_NAME?> <font style="color:#FF0000; font-size:9px">*</font> </td>
         <td width="55%" align="left">
         <input name="txtStaffName" type="text" class="comm_input input_width1" id="txtStaffName" size="30" maxlength="100" value="<?php echo htmlentities($var_staffName); ?>">
         </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_PROFILE_LOGIN ?> <font style="color:#FF0000; font-size:9px">*</font></td>
                      <td width="55%" align="left">
                        <input name="txtStaffLogin" type="text" class="comm_input input_width1" id="txtStaffLogin" size="30" maxlength="100" value="<?php echo htmlentities($var_staffLogin); ?>">
</td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>

                      <tr style="display:none">
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_PROFILE_PASSWORD ?> <span id="star" style=" visibility:visible"><font style="color:#FF0000; font-size:9px">*</font></span></td>
                      <td width="55%" align="left" class="toplinks">
                      <input name="txtPassword" type="text" class="comm_input input_width1" id="txtPassword" size="30" maxlength="100" value="<?php echo htmlentities($var_password); ?>">

                      </td>
                      </tr>
                                          <tr style="display:none">
                                              <td colspan="2"></td>
                                              <td  class="toplinks" align="left"><span id="showError" style="visibility:hidden"><br><font color="red"><?php echo TEXT_PASSWORD_NOTIFICATION; ?></font></span></td></tr>
                                          <tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
                                          <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_PROFILE_EMAIL?> <font style="color:#FF0000; font-size:9px">*</font></td>
                      <td width="55%" align="left">
                      <input name="txtEmail" type="text" class="comm_input input_width1" id="txtEmail" size="30" maxlength="100" value="<?php echo htmlentities($var_email); ?>">
                      </td>
                      </tr>

                      <tr>
                      <td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_PROFILE_YIM?></td>
                      <td width="55%" align="left">
                      <input name="txtYim" type="text" class="comm_input input_width1" id="txtYim" size="30" maxlength="100" value="<?php echo htmlentities($var_yim); ?>">
                      </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>

                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_PROFILE_SMSMAIL?></td>
                                  <td width="55%" align="left">
                                    <input name="txtSmsMail" type="text" class="comm_input input_width1" id="txtSmsMail" size="30" maxlength="100" value="<?php echo htmlentities($var_smsMail); ?>">
</td>
                                </tr>
                                                                <tr><td colspan="3">&nbsp;</td></tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_PROFILE_MOBILE?> </td>
                                  <td width="55%" align="left">
                                      <input name="txtMobile" type="text" class="comm_input input_width1" id="txtMobile" size="30" maxlength="20" value="<?php echo(htmlentities($var_mobile)); ?>">
                                  </td>
                                </tr>
                                                                <tr><td colspan="3">&nbsp;</td></tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_PROFILE_CSSID?></td>
                                  <td width="55%" align="left"><select name="cmbCssId" class="comm_input input_width1">
                                                                          <?php echo($lst_css); ?>
                                                                  </select>
                                  </td>
                                </tr>
                                                                <tr><td colspan="3">&nbsp;</td></tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_PROFILE_REFRESH_RATE?> <font style="color:#FF0000; font-size:9px">*</font></td>
                                  <td width="55%" align="left">
								     <select name="cmbRefresh" class="comm_input input_width1">
									  <option value="0" <?php echo(($var_refreshRate == "0")?"Selected":"");?>>No Refresh</option>
									  <option value="1" <?php echo(($var_refreshRate == "1")?"Selected":"");?>>1 <?php echo TEXT_MINUTE?></option>
									  <option value="2" <?php echo(($var_refreshRate == "2" || $var_refreshRate == "")?"Selected":"");?>>2 <?php echo TEXT_MINUTES?></option>
									  <option value="3" <?php echo(($var_refreshRate == "3")?"Selected":"");?>>3 <?php echo TEXT_MINUTES?></option>
									  <option value="4" <?php echo(($var_refreshRate == "4")?"Selected":"");?>>4 <?php echo TEXT_MINUTES?></option>
									  <option value="5" <?php echo(($var_refreshRate == "5")?"Selected":"");?>>5 <?php echo TEXT_MINUTES?></option>
									  <option value="6" <?php echo(($var_refreshRate == "6")?"Selected":"");?>>6 <?php echo TEXT_MINUTES?></option>
									  <option value="7" <?php echo(($var_refreshRate == "7")?"Selected":"");?>>7 <?php echo TEXT_MINUTES?></option>
									  <option value="8" <?php echo(($var_refreshRate == "8")?"Selected":"");?>>8 <?php echo TEXT_MINUTES?></option>
									  <option value="9" <?php echo(($var_refreshRate == "9")?"Selected":"");?>>9 <?php echo TEXT_MINUTES?></option>
									  <option value="10" <?php echo(($var_refreshRate == "10")?"Selected":"");?>>10 <?php echo TEXT_MINUTES?></option>
								  </select>

                                  </td>
                                </tr>
                                                                <tr><td colspan="3">&nbsp;</td></tr>
                                                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_PROFILE_NOTIFY_ASSIGN?> </td>
                                  <td width="55%" align="left" class="toplinks">
                                    <input name="rdNotifyAssign" type="radio" value="1" <?php echo(($var_notifyAssign == 1)?"checked":""); ?>>
                                    <?php echo   TEXT_YES?>
                                    <input name="rdNotifyAssign" type="radio" value="0"  <?php echo(($var_notifyAssign == 0)?"checked":""); ?>>
                                    <?php echo   TEXT_NO?>
</td>
                                </tr>
                                                                <tr><td colspan="3">&nbsp;</td></tr>
                                                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_PROFILE_NOTIFY_PVT_MSG?></td>
                                  <td width="55%" align="left" class="toplinks"><input name="rdNotifyPvtMsg" type="radio" value="1"  <?php echo(($var_notifyPvtMsg == 1)?"checked":""); ?>>
<?php echo   TEXT_YES?>
  <input name="rdNotifyPvtMsg" type="radio" value="0"  <?php echo(($var_notifyPvtMsg == 0)?"checked":""); ?>>
<?php echo   TEXT_NO?>
                                  </td>
                                </tr>
                                                                <tr><td colspan="3">&nbsp;</td></tr>
                                                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_PROFILE_NOTIFY_KB?></td>
                                  <td width="55%" align="left" class="toplinks"><input name="rdNotifyKB" type="radio" value="1"  <?php echo(($var_notifyKB == 1)?"checked":""); ?>>
<?php echo   TEXT_YES?>
  <input name="rdNotifyKB" type="radio" value="0"  <?php echo(($var_notifyKB == 0)?"checked":""); ?>>
<?php echo   TEXT_NO?>
                                  </td>
                                </tr>
                                                                <tr><td colspan="3">&nbsp;</td></tr>
																<tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks" valign="top"><?php echo TEXT_STAFF_SIGNATURE?></td>
                                  <td width="55%" align="left" class="toplinks"><textarea name="txtSignature" id="txtSignature" cols="30" rows="7" class="textarea"><?php echo($var_signature);?></textarea>
                                  </td>
                                </tr>
                                                                <tr><td colspan="3">&nbsp;</td></tr>

                              </table>
                      
						
                  
           
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="comm_tbl">
                                  <tr align="center"  class="listingbtnbar">
                                    <td width="22%">&nbsp;</td>
                                    <td width="16%">&nbsp;</td>
                                    <td width="14%"><input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT ?>"  onClick="javascript:edit();"></td>
                                    <td width="16%">&nbsp;</td>
                                    <td width="12%">&nbsp;</td>
                                    <td width="20%">
                                                                        <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
                                                                        <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                                                                        <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
                                                                        <input type="hidden" name="postback" value="">
                                                                        </td>
                                  </tr>
                              </table>
							
                    
 
<script>
        var setValue = "<?php echo trim($var_cssId); ?>";
//        document.frmStaff.cmbCountry.text=setValue;
        try{
         for(i=0;i<document.frmStaff.cmbCssId.options.length;i++){
            if(document.frmStaff.cmbCssId.options[i].value == setValue){
                        document.frmStaff.cmbCssId.options[i].selected=true;
                        break;
            }
    }
        }catch(e){}
        <?php
                if ($var_id == "") {

                        echo("document.frmStaff.btUpdate.disabled=true;");
                        echo("document.getElementById('showError').style.visibility='hidden';");
                        echo("document.getElementById('star').style.visibility='visible';");
                        echo("document.frmStaff.txtStaffLogin.readOnly=false;");
                }
                else {
                        echo("document.frmStaff.btUpdate.disabled=false;");
                        echo("document.getElementById('showError').style.visibility='visible';");
                        echo("document.getElementById('star').style.visibility='hidden';");
                        echo("document.frmStaff.txtStaffLogin.readOnly=true;");
                }
        ?>
</script>
</form>
</div>