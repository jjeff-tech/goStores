<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>                                |
// |                                                                      |
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
		$var_notifyArrival=1;
        $var_cssId = 1;


        if ($_POST["postback"] == "" && $var_id != "") {

                $sql = "Select nStaffId,vStaffname,vLogin,vPassword,vOnline,vMail,vYIM,vSMSMail,vMobileNo,nCSSId,nRefreshRate,nNotifyAssign,";
                $sql .= "nNotifyPvtMsg,nNotifyKB,nNotifyArrival,vType,tSignature, vStaffImg from sptbl_staffs where nStaffId = '" . addslashes($var_id) . "' AND vDelStatus='0' ";
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
						$newfile = $var_row["vStaffImg"];
                        $var_cssId = $var_row["nCSSId"];
                        $var_refreshRate = $var_row["nRefreshRate"];
                        $var_notifyAssign = $var_row["nNotifyAssign"];
                        $var_notifyPvtMsg = $var_row["nNotifyPvtMsg"];
                        $var_notifyKB = $var_row["nNotifyKB"];
						$var_notifyArrival = $var_row["nNotifyArrival"];
                        $var_type = $var_row["vType"];
						$var_signature = $var_row["tSignature"];
                }
                else {
                        $var_message = MESSAGE_RECORD_ERROR;
                         $flag_msg = "class='msg_error'";
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
						$var_staffimg = $_POST["txtStaffImg"];
                        $var_cssId = $_POST["cmbCssId"];
                        $var_refreshRate = (int)$_POST["cmbRefresh"];
                        $var_refreshRate = (is_int($var_refreshRate)) ? $var_refreshRate : 620;
                        $var_notifyAssign = ($_POST["rdNotifyAssign"] == "1")?$_POST["rdNotifyAssign"]:"0";
                        $var_notifyPvtMsg = ($_POST["rdNotifyPvtMsg"] == "1")?$_POST["rdNotifyPvtMsg"]:"0";
                        $var_notifyKB = ($_POST["rdNotifyKB"] == "1")?$_POST["rdNotifyKB"]:"0";
                        $var_notifyArrival = ($_POST["rdNotifyArrival"] == "1")?$_POST["rdNotifyArrival"]:"0";
						$var_signature = $_POST["txtSignature"];
						
						$sql1 ="select vStaffImg from sptbl_staffs where nStaffId='".addslashes($var_id)."'";
						$rs1 = executeSelect($sql1,$conn);
                        if (mysql_num_rows($rs1) > 0) {
                           $row1 = mysql_fetch_array($rs1);
						   $oldimg = $row1["vStaffImg"];
                        }
				
						$uploadstatus=upload("txtStaffImg","images/","","all","10000000000000000");
                        $errorcode="";
                        $dup_flag=0;
                        
						switch ($uploadstatus) {
                         case "FNA":
                                      $errorcode=MESSAGE_UPLOAD_ERROR_1;
                          $flag_msg = "class='msg_error'";
                                      break;
                            case "IS":
                                       $errorcode=MESSAGE_UPLOAD_ERROR_3;
                             $flag_msg = "class='msg_error'";
                                                             break;
                           case "IT":
                                    $errorcode=MESSAGE_UPLOAD_ERROR_2;
                            $flag_msg = "class='msg_error'";
                                         break;
                           case "NW":
                                    $errorcode=MESSAGE_UPLOAD_ERROR_4;
                            $flag_msg = "class='msg_error'";
                                         break;
                           case "FE":
                                    $errorcode=MESSAGE_UPLOAD_ERROR_5;
                            $flag_msg = "class='msg_error'";
                                         break;
						   case "IF":
									$errorcode=MESSAGE_UPLOAD_ERROR_6;
                                                    $flag_msg = "class='msg_error'";
									 break;
                           default:
                                         $file_name=$uploadstatus;
                                         break;
                      }
					  if ( $uploadstatus == "FNA" ) $newfile = $oldimg;
					  if ( $uploadstatus == "FE" ) {
					     if ( ($_FILES["txtStaffImg"]['name']) == $oldimg ) {
						   unlink("images/".$oldimg); 
						   $uploadstatus=upload("txtStaffImg","images/","","all","10000000000000000");
						 } else {
						   $path_parts1 = pathinfo($_FILES["txtStaffImg"]['name']);
						   $ext1= $path_parts1['extension'] ;
						   $newfile1 = "staff_".$var_id.".".$ext1;
						    
						   $uploadstatus=upload("txtStaffImg","images/",$newfile1,"all","10000000000000000");
						 }
						$errorcode="";
                        $dup_flag=0;
						switch ($uploadstatus) {
                         case "FNA":
                                      $errorcode=MESSAGE_UPLOAD_ERROR_1;
                           $flag_msg = "class='msg_error'";
                                      break;
                            case "IS":
                                       $errorcode=MESSAGE_UPLOAD_ERROR_3;
                              $flag_msg = "class='msg_error'";
                                                             break;
                           case "IT":
                                    $errorcode=MESSAGE_UPLOAD_ERROR_2;
                             $flag_msg = "class='msg_error'";
                                         break;
                           case "NW":
                                    $errorcode=MESSAGE_UPLOAD_ERROR_4;
                             $flag_msg = "class='msg_error'";
                                         break;
                           case "FE":
                                    $errorcode=MESSAGE_UPLOAD_ERROR_5;
                             $flag_msg = "class='msg_error'";
                                         break;
						   case "IF":
									$errorcode=MESSAGE_UPLOAD_ERROR_6;
                                                     $flag_msg = "class='msg_error'";
									 break;
                           default:
                                         $file_name=$uploadstatus;
                                         break;
                          }
						
					  }
					  if ( $errorcode == "" && $file_name != "" ) {
					     $path_parts = pathinfo($file_name);
						 $ext= $path_parts['extension'] ;
						 $newfile = "staff_".$var_id.".".$ext;
						 if ( $oldimg != "" ) unlink("images/".$oldimg); 
						 rename("images/".$file_name, "images/".$newfile);
					  }
					  if (isUniqueEmail($var_email,$var_id) == true) {
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
												nNotifyArrival='" . $var_notifyArrival . "',
												tSignature='" . addslashes($var_signature)  . "'  where nStaffId='" . addslashes($var_id) . "'";
                                executeQuery($sql,$conn);
								if ( $errorcode =="" && $file_name != "" ) {
								  $sql = "Update sptbl_staffs set vStaffImg='".addslashes($newfile)."' where nStaffId='" . addslashes($var_id)."'";
								  executeQuery($sql,$conn);
								}
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
					  $_SESSION["sess_refresh"] = $var_refreshRate;
                      //echo $_SESSION["sess_cssurl"];
                      //update css

                     //echo "<script>location.href='editprofile.php?mt=y&stylename=STYLEPREFERANCES&styleminus=minus9&styleplus=plus9&';</ script>";
/*	modified by roshith	on 4-12-06
						echo "<script>location.href='staffmain.php?mt=y&stylename=STYLEPREFERANCES&styleminus=minus9&styleplus=plus9&';</script>";
						
						//header("location:editprofile.php?mt=y&stylename=STYLEPREFERANCES&styleminus=minus9&styleplus=plus9&");
						exit;
*/
                        $var_message = MESSAGE_RECORD_UPDATED;
                         $flag_msg = "class='msg_success'";
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
                        else {
                                $var_message = MESSAGE_NONUNIQUE_EMAIL;
                                 $flag_msg = "class='msg_error'";
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
if($errorcode)
    {
     $flag_msg = "class='msg_error'";
    }
?>
<form name="frmStaff" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
<div class="content_section">

   <div class="content_section_title">
	<h3><?php echo TEXT_EDIT_PROFILE ?></h3>
	</div> 
    
     

     <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="column1">
     <tr>
     <td>
         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
             <tr>
		         <td width="100%" align="center" colspan=3 class="toplinks">
        		 <?php echo TEXT_FIELDS_MANDATORY ?></td>
	         </tr>
	         <tr>
    		     <td width="100%" align="center" colspan=3 class="errormessage">
         		<div <?php echo $flag_msg;?>> <?php echo $var_message ."  ". $errorcode ;?> </div></td>
	         </tr>
	         <tr><td colspan="3">&nbsp;</td></tr>
			 <tr>
			 <td width="13%" align="left">&nbsp;</td>
			 <td width="26%" align="left" class="toplinks"><?php echo TEXT_PROFILE_NAME?> <font style="color:#FF0000; font-size:9px">*</font> </td>
			 <td width="61%" align="left">
			 <input name="txtStaffName" type="text" class="comm_input input_width1a" id="txtStaffName" size="30" maxlength="100" value="<?php echo htmlentities($var_staffName); ?>">
			 </td>
			 </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_PROFILE_LOGIN ?> <font style="color:#FF0000; font-size:9px">*</font></td>
                      <td width="61%" align="left">
                        <input name="txtStaffLogin" type="text" class="comm_input input_width1a" id="txtStaffLogin" size="30" maxlength="100" value="<?php echo htmlentities($var_staffLogin); ?>">
</td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>

                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_PROFILE_PASSWORD ?> <span id="star" style=" visibility:visible"><font style="color:#FF0000; font-size:9px">*</font></span></td>
                      <td width="61%" align="left" class="toplinks">
                      <input name="txtPassword" type="text" class="comm_input input_width1a" id="txtPassword" size="30" maxlength="100" value="<?php echo htmlentities($var_password); ?>">

                      </td>
                      </tr>
                                          <tr><td colspan="3"  class="toplinks" align="center"><span id="showError" style="visibility:hidden"><br><font color="red"><?php echo TEXT_PASSWORD_NOTIFICATION; ?></font></span></td></tr>
                                          <tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
                                          <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_PROFILE_EMAIL?> <font style="color:#FF0000; font-size:9px">*</font></td>
                      <td width="61%" align="left">
                      <input name="txtEmail" type="text" class="comm_input input_width1a" id="txtEmail" size="30" maxlength="100" value="<?php echo htmlentities($var_email); ?>">
                      </td>
                      </tr>

                      <tr>
                      <td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_PROFILE_YIM?></td>
                      <td width="61%" align="left">
                      <input name="txtYim" type="text" class="comm_input input_width1a" id="txtYim" size="30" maxlength="100" value="<?php echo htmlentities($var_yim); ?>">
                      </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>

                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_PROFILE_SMSMAIL?></td>
                                  <td width="61%" align="left">
                                    <input name="txtSmsMail" type="text" class="comm_input input_width1a" id="txtSmsMail" size="30" maxlength="100" value="<?php echo htmlentities($var_smsMail); ?>">
</td>
                                </tr>
                                                                <tr><td colspan="3">&nbsp;</td></tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_PROFILE_MOBILE?> </td>
                                  <td width="61%" align="left">
                                      <input name="txtMobile" type="text" class="comm_input input_width1a" id="txtMobile" size="30" maxlength="20" value="<?php echo(htmlentities($var_mobile)); ?>">
                                  </td>
                                </tr>
								<!--Added by Amaldev for staffimage starts-->
								                                 <tr><td colspan="3">&nbsp;</td></tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_STAFF_IMAGE?></td>
                                  <td colspan="1" width="25%" align="left">
									 <input name="txtStaffImg" type="file" id="txtStaffImg" size="30" maxlength="100" value="">&nbsp;&nbsp;<?php if ($newfile !='') { ?><img width="100" height="125" src="images/<?php echo htmlentities($newfile)?>"></img><?php } ?>
                
								  </td>
								
								<!--Added by Amaldev for staffimage ends-->
								
                                                                <tr><td colspan="3">&nbsp;</td></tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_PROFILE_CSSID?></td>
                                  <td width="61%" align="left"><select name="cmbCssId" class="comm_input input_width1a">
                                                                          <?php echo($lst_css); ?>
                                                                  </select>
                                  </td>
                                </tr>
                                                                <tr><td colspan="3">&nbsp;</td></tr>
                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_PROFILE_REFRESH_RATE?> <font style="color:#FF0000; font-size:9px">*</font></td>
                                  <td width="61%" align="left">
								      <select name="cmbRefresh" class="comm_input input_width1a">
									  <option value="0" <?php echo(($var_refreshRate == "0")?"Selected":"");?>>No Refresh</option>
									  <option value="1" <?php echo(($var_refreshRate == "1")?"Selected":"");?>>1 minute</option>
									  <option value="2" <?php echo(($var_refreshRate == "2" || $var_refreshRate == "")?"Selected":"");?>>2 minutes</option>
									  <option value="3" <?php echo(($var_refreshRate == "3")?"Selected":"");?>>3 minutes</option>
									  <option value="4" <?php echo(($var_refreshRate == "4")?"Selected":"");?>>4 minutes</option>
									  <option value="5" <?php echo(($var_refreshRate == "5")?"Selected":"");?>>5 minutes</option>
									  <option value="6" <?php echo(($var_refreshRate == "6")?"Selected":"");?>>6 minutes</option>
									  <option value="7" <?php echo(($var_refreshRate == "7")?"Selected":"");?>>7 minutes</option>
									  <option value="8" <?php echo(($var_refreshRate == "8")?"Selected":"");?>>8 minutes</option>
									  <option value="9" <?php echo(($var_refreshRate == "9")?"Selected":"");?>>9 minutes</option>
									  <option value="10" <?php echo(($var_refreshRate == "10")?"Selected":"");?>>10 minutes</option>
								  </select>
                                     <!-- <input name="txtRefreshRate" type="text" class="textbox" id="txtRefreshRate" size="30" maxlength="4" value="<?php echo($var_refreshRate); ?>"> -->
                                  </td>
                                </tr>
                                                                <tr><td colspan="3">&nbsp;</td></tr>
                                                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_PROFILE_NOTIFY_ASSIGN?> </td>
                                  <td width="61%" align="left" class="toplinks">
                                    <input name="rdNotifyAssign" type="radio" value="1" <?php echo(($var_notifyAssign == 1)?"checked":""); ?>>
                                    <?php echo(TEXT_YES); ?>
                                    <input name="rdNotifyAssign" type="radio" value="0"  <?php echo(($var_notifyAssign == 0)?"checked":""); ?>>
                                    <?php echo(TEXT_NO); ?>
</td>
                                </tr>
								 <tr><td colspan="3">&nbsp;</td></tr>
                                                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_PROFILE_NOTIFY_ARRIVAL?> </td>
                                  <td width="61%" align="left" class="toplinks">
                                    <input name="rdNotifyArrival" type="radio" value="1" <?php echo(($var_notifyArrival == 1)?"checked":""); ?>>
                                    <?php echo(TEXT_YES); ?>
                                    <input name="rdNotifyArrival" type="radio" value="0"  <?php echo(($var_notifyArrival == 0)?"checked":""); ?>>
                                    <?php echo(TEXT_NO); ?>
</td>
                                </tr>
                                                                <tr><td colspan="3">&nbsp;</td></tr>
                                                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_PROFILE_NOTIFY_PVT_MSG?></td>
                                  <td width="61%" align="left" class="toplinks"><input name="rdNotifyPvtMsg" type="radio" value="1"  <?php echo(($var_notifyPvtMsg == 1)?"checked":""); ?>>
 <?php echo(TEXT_YES); ?>
  <input name="rdNotifyPvtMsg" type="radio" value="0"  <?php echo(($var_notifyPvtMsg == 0)?"checked":""); ?>>
<?php echo(TEXT_NO); ?>
                                  </td>
                                </tr>
                                                                <tr><td colspan="3">&nbsp;</td></tr>
                                                                <tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks"><?php echo TEXT_PROFILE_NOTIFY_KB?></td>
                                  <td width="61%" align="left" class="toplinks"><input name="rdNotifyKB" type="radio" value="1"  <?php echo(($var_notifyKB == 1)?"checked":""); ?>>
 <?php echo(TEXT_YES); ?>
  <input name="rdNotifyKB" type="radio" value="0"  <?php echo(($var_notifyKB == 0)?"checked":""); ?>>
<?php echo(TEXT_NO); ?>
                                  </td>
                                </tr>
                                                                <tr><td colspan="3">&nbsp;</td></tr>
																<tr>
                                  <td align="left">&nbsp;</td>
                                  <td align="left" class="toplinks" valign="top"><?php echo TEXT_STAFF_SIGNATURE?></td>
                                  <td width="61%" align="left" class="toplinks">
								    <textarea name="txtSignature" id="txtSignature" cols="40" rows="7" class="textarea" style="width:200px;"><?php echo($var_signature);?></textarea>
                                  </td>
                                </tr>
                                                                <tr><td colspan="3">&nbsp;</td></tr>

                              </table>
                        </td>
                            </tr>
                        </table>
                  
            <table width="100%"  border="0" cellspacing="5" cellpadding="0">
              <tr>
                <td>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr >
                        
                        <td ><table width="100%"  border="0" cellspacing="0" cellpadding="5" >
                            <tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center"  class="whitebasic">
                                    <td width="22%">&nbsp;</td>
                                    <td width="16%">&nbsp;</td>
                                    <td width="14%"><input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT; ?>"  onClick="javascript:edit();"></td>
                                    <td width="16%">&nbsp;</td>
                                    <td width="12%">&nbsp;</td>
                                    <td width="20%">
                                                                        <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
                                                                        <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                                                                        <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
                                                                        <input type="hidden" name="postback" value="">
                                                                        </td>
                                  </tr>
                              </table></td>
                            </tr>
                        </table></td>
                        
                      </tr>
                    </table>
                    </td>
              </tr>
            </table>
	
</div>
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