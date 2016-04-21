<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>                                          |
// |                                                                                                            |
// +----------------------------------------------------------------------+
   // $addOredit = 'Add Display';
     $addOredit = TEXT_ADD_DISPLAY;
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
        if ($_GET["id"] != "") {
                $var_id = $_GET["id"];
				//$addOredit = 'Edit Display';
                                 $addOredit = TEXT_EDIT_DISPLAY;
        }
        elseif ($_POST["id"] != "") {
                $var_id = $_POST["id"];
				//$addOredit = 'Edit Display';
                                 $addOredit = TEXT_EDIT_DISPLAY;
        }

        //$var_userid = $_SESSION["sess_staffid"];
        $var_staffid = $_SESSION["sess_staffid"];

        if ($_POST["postback"] == "" && $var_id != "") {
                $sql = "Select * from sptbl_css  ";
        $sql .=" where nCSSId ='".mysql_real_escape_string($var_id)."'";
                $var_result = executeSelect($sql,$conn);
                if (mysql_num_rows($var_result) > 0) {
                        $var_row = mysql_fetch_array($var_result);
                        $var_desc = $var_row["vCSSName"];
                        $var_url= $var_row["vCSSURL"];

            }
                else {
						$var_id ="";
                        $var_message = "<font color=red>" . MESSAGE_RECORD_ERROR . "</font>";
                }
        }
        elseif ($_POST["postback"] == "A") {
                        $var_desc= trim($_POST["txtDispDesc"]);


                        $uploadstatus=upload("txtUrl","../styles/","","all","10000000000000000");
                        $errorcode="";
                        $dup_flag=0;
                        switch ($uploadstatus) {
                         case "FNA":
                                      $errorcode=MESSAGE_UPLOAD_ERROR_1;
                                      break;
                            case "IS":
                                       $errorcode=MESSAGE_UPLOAD_ERROR_3;
                                                             break;
                           case "IT":
                                    $errorcode=MESSAGE_UPLOAD_ERROR_2;
                                         break;
                           case "NW":
                                    $errorcode=MESSAGE_UPLOAD_ERROR_4;
                                         break;
                           case "FE":
                                    $errorcode=MESSAGE_UPLOAD_ERROR_5;
                                         break;
						   case "IF":
									$errorcode=MESSAGE_UPLOAD_ERROR_6;
									 break;
                           default:
                                         $file_name=$uploadstatus;
                                         break;
                      }
            $sql="SELECT nCSSId FROM sptbl_css  WHERE   vCSSName  ='".mysql_real_escape_string($var_desc) . "'";

                        $rs = executeSelect($sql,$conn);
                        if(mysql_num_rows($rs)>0){
                          if($file_name !=""){
                            unlink("../styles/".$file_name);
                          }
                          $dup_flag=1;
                        }
						if($dup_flag==1){
							$var_message = "<font color=red>Specified theme already exists, please use another name.</font>";
						}
                        elseif (validateAddition() == true and $errorcode=="") {
                          //Insert into the downloads table
                      $file_name ="styles/".$file_name;
                      $sql = "Insert into sptbl_css(nCSSId,vCSSName,vCSSURL,dDate";
                          $sql .= ") Values('','" . mysql_real_escape_string($var_desc). "','" . mysql_real_escape_string($file_name) . "',now())";
                          executeQuery($sql,$conn);
                          $var_insert_id = mysql_insert_id($conn);
                          //Insert the actionlog
						  if(logActivity()) {
                          $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_ADDITION . "','CSS','" . mysql_real_escape_string($var_insert_id) . "',now())";
                          executeQuery($sql,$conn);
						  }
                          $var_desc="";
                          $var_message = MESSAGE_RECORD_ADDED;

                }
                else {
                         $var_message = "<font color=red>" . MESSAGE_RECORD_ERROR .$errorcode ."</font>";
                }
        }
        elseif ($_POST["postback"] == "D") {

                        $var_desc= trim($_POST["txtDispDesc"]);
                        $var_url=$_POST['varurl'];
                if (validateDeletion($var_id) == true and $var_id !="1") {

                         $sql="SELECT vCSSURL     FROM sptbl_css  WHERE   nCSSId =$var_id";
                         $rs_oldurl = executeSelect($sql,$conn);
                                 $rowoldurl=mysql_fetch_array($rs_oldurl);
                                 $oldurl=$rowoldurl['vCSSURL'];
                                 //chmod("../".$oldurl,0777);
                                 unlink("../".$oldurl);

                             $sql = "delete from  sptbl_css  WHERE  nCSSId  = " . intval($var_id);
							 //echo $sql;
							 //exit;
                             executeQuery($sql,$conn);

                        //Insert the actionlog
						if(logActivity()) {
                        $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','CSS','" . mysql_real_escape_string($var_id) . "',now())";
                        executeQuery($sql,$conn);
						}
                        $var_desc= "";
                        $var_url="";
						$var_id= "";
                        $var_message = MESSAGE_RECORD_DELETED;
                }elseif($var_id == "1") {
	                 $var_message = "<font color=red>" . MESSAGE_DISPLAY_ERROR . "</font>";
				}
				else {
                 $var_message = "<font color=red>" . MESSAGE_RECORD_ERROR . "</font>";
            }

        }
        elseif ($_POST["postback"] == "U") {
                $var_desc= trim($_POST["txtDispDesc"]);
                        $var_url=$_POST['varurl'];
                        $uploadstatus=upload("txtUrl","../styles/","","all","10000000000000000");
                        $errorcode="";
                        $dup_flag=0;
                        $file_name="";
                        switch ($uploadstatus) {
			               case "FNA":
                                      $errorcode="";
            			              break;
			               case "IS":
                                      $errorcode=MESSAGE_UPLOAD_ERROR_3;
                                      break;
                           case "IT":
                                    $errorcode=MESSAGE_UPLOAD_ERROR_2;
                                         break;
                           case "NW":
                                    $errorcode=MESSAGE_UPLOAD_ERROR_4;
                                         break;
                           case "FE":
                                    $errorcode=MESSAGE_UPLOAD_ERROR_5;
                                         break;
						   case "IF":
						            $errorcode=MESSAGE_UPLOAD_ERROR_6;
				        				 break;
                           default:
                                         $file_name=$uploadstatus;
                                         break;
                      }


           $sql="SELECT nCSSId  FROM sptbl_css  WHERE  vCSSName ='".mysql_real_escape_string($var_desc) . "'";
                   $sql .="and nCSSId !=$var_id";
                   $rs = executeSelect($sql,$conn);
                   if(mysql_num_rows($rs)>0){
                          if($file_name !=""){
                            unlink("../styles/".$file_name);
                          }
                          $dup_flag=1;
                        }

                        if (validateUpdation() == true and $dup_flag==0 and $errorcode=="") {
                          if($file_name !=""){
                            $file_name ="styles/".$file_name;
                            $seturlfld=" ,vCSSURL ='".mysql_real_escape_string($file_name)."' ";
                                //unlink the old file
                                 $sql="SELECT vCSSURL     FROM sptbl_css WHERE   nCSSId =$var_id";
                         $rs_oldurl = executeSelect($sql,$conn);
                                 $rowoldurl=mysql_fetch_array($rs_oldurl);
                                 $oldurl=$rowoldurl['vCSSURL'];
                                 unlink("../".$oldurl);
                                 $var_url=$file_name;
                          }else{
                             $seturlfld=" ";
                          }

                                 $sql = "Update sptbl_css  set vCSSName   ='" . mysql_real_escape_string($var_desc) . "',
                                             dDate =now()";

                                 $sql .=$seturlfld;
                                 $sql .="where nCSSId='" . mysql_real_escape_string($var_id) . "'";
                                 executeQuery($sql,$conn);
                                //Insert the actionlog
								if(logActivity()) {
									 $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','CSS','" . mysql_real_escape_string($var_id) . "',now())";
									 executeQuery($sql,$conn);
								 }

                                $var_message = MESSAGE_RECORD_UPDATED;
                        }
                        else {
                                $var_message = "<font color=red>" . MESSAGE_RECORD_ERROR .$errorcode. "</font>";
                        }
        }

        function validateAddition()
        {


             if (trim($_POST["txtDispDesc"]) == "" ) {
                    return false;
                }else {
                    return true;
                }
        }

        function validateDeletion($var_list)
        {
            global $conn;

            /*$sql="SELECT nCSSId    FROM sptbl_staffs  WHERE   nCSSId IN ($var_list)";
            $rs= executeSelect($sql,$conn);
            if(mysql_num_rows($rs)>0){
              return false;
            }else{
			  $sql="select  nCSSId  from sptbl_users where nCSSId  IN ($var_list)";
			  $rs= executeSelect($sql,$conn);
                 if(mysql_num_rows($rs)>0){
                 return false;
              }
			}*/

			$sql=" update sptbl_users set nCSSId=1 where nCSSId in($var_list)";


            $rs=  executeQuery($sql,$conn);

			$sql=" update sptbl_staffs set nCSSId=1 where nCSSId in($var_list)";
            $rs=  executeQuery($sql,$conn);
            return true;
        }

        function validateUpdation()
        {
                if (trim($_POST["txtDispDesc"]) == "" ) {
                    return false;
                }else {
                    return true;
                }
        }

?>
<form name="frmDisplays" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>" enctype="multipart/form-data">
<div class="content_section">
			<div class="content_section_title">
				<h3><?php echo $addOredit ?></h3>
			</div>
			<table width="100%"  border="0">
			  <tr>
				<td width="76%" valign="top">
				<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
					
					<tr>
					<td align="center" colspan=3 >&nbsp;</td>
					
					</tr>
					<tr>
					<td>&nbsp;</td>
					<td align="center" colspan=2 class="errormessage">
                                            <?php
                                            if ($var_message != ""){?>
						<div class="msg_success">
						<?php echo $var_message ?>
						</div>
                                          <?php
                                            }?>
					</td>
					
					</tr>
					<tr>
					<td>&nbsp;</td>
					<td align="left" colspan=2 class="toplinks">
					<?php echo TEXT_FIELDS_MANDATORY ?></td>
					
					</tr>
					<tr><td colspan="3">&nbsp;</td></tr>
					<tr>
					<td width="2%" align="left">&nbsp;</td>
					<td width="33%" align="left" class="toplinks"><?php echo TEXT_DISPLAY_DESC?> <font style="color:#FF0000; font-size:9px">*</font> </td>
						  <td width="65%" align="left">
					<input name="txtDispDesc" type="text" class="comm_input input_width1a" id="txtDispDesc" size="30" maxlength="100" value="<?php echo htmlentities($var_desc); ?>">
						  </td>
					</tr>
					<tr><td colspan="3">&nbsp;</td></tr>
						  <tr>
					<td width="2%" align="left">&nbsp;</td>
					<td width="33%" align="left" class="toplinks" valign="top"><?php echo TEXT_DISPLAY_URL?> <font style="color:#FF0000; font-size:9px">*</font> </td>
					<td width="65%" align="left" class="toplinks">
							<input name="txtUrl" type="file" class="comm_input input_width1a" id="txtUrl" size="30" maxlength="100" value="<?php echo htmlentities($var_Url); ?>">
					<br>&nbsp;<?php echo $var_url ?>
						 </td>
					</tr>
					<tr><td colspan="3">&nbsp;</td></tr>
					<tr><td colspan="3" align=center class="subsidelink">
						<div class="msg_common ">
						 <?php echo   TEXT_THEME_CAUTION?>
						</div>
					 
					</td></tr>
					</table>

						<table width="100%"  border="0" cellspacing="10" cellpadding="0">
						  <tr>
							<td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
								<tr>
								  <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
								</tr>
							  </table>
								<table width="100%"  border="0" cellspacing="0" cellpadding="0">
								  <tr >
									<td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
									<td ><table width="100%"  border="0" cellspacing="0" cellpadding="5" >
										<tr>
										  <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
											  <tr align="center"  class="listingbtnbar">
												<td width="22%">&nbsp;</td>
												<td width="14%"><input name="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD; ?>" onClick="javascript:add();"></td>
												<td width="14%"><input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT; ?>"  onClick="javascript:edit();"></td>
												<td width="14%"><input name="btDelete" type="button" class="comm_btn_greyad" value="<?php echo BUTTON_TEXT_DELETE; ?>" onClick="javascript:deleted();"></td>
												<td width="14%"><input name="btCancel" type="reset" class="comm_btn_greyad" value="<?php echo BUTTON_TEXT_CLEAR; ?>" ></td>
												<td width="20%">
																					<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
																					<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
																					<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
																					<input type="hidden" name="id" value="<?php echo($var_id); ?>">
																					<input type="hidden" name="varurl" value="<?php echo($var_url); ?>">
																					<input type="hidden" name="postback" value="">
																					</td>
											  </tr>
										  </table></td>
										</tr>
									</table></td>
									<td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
								  </tr>
								</table>
							</td>
						  </tr>
						</table>
					</td>
			  </tr>
			</table>
			</div>
<script>
        var setValue = "<?php echo trim($var_country); ?>";

        <?php
                if ($var_id == "") {
                        echo("document.frmDisplays.btAdd.disabled=false;");
                        echo("document.frmDisplays.btUpdate.disabled=true;");
                        echo("document.frmDisplays.btDelete.disabled=true;");
                }
                else {
                        echo("document.frmDisplays.btAdd.disabled=true;");
                        echo("document.frmDisplays.btUpdate.disabled=false;");
                        echo("document.frmDisplays.btDelete.disabled=false;");
                }
        ?>
		document.frmDisplays.txtDispDesc.focus();
</script>
<style>
input:disabled { cursor: inherit !important; }
.admin_main .comm_btn:hover:disabled { background-color:#00A4EF!important }
.admin_main .comm_btn_greyad:disabled { background-color:#444444!important  }
</style>
</form>