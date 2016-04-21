<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>                                  |
// |                                                                      |                // |                                                                      |
// +----------------------------------------------------------------------+
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
      $var_staffid = $_SESSION["sess_staffid"];
   

        if ($_POST["postback"] == "") {

                $sql = "Select vLookUpName,vLookUpValue from sptbl_lookup ";
                $var_result = executeSelect($sql,$conn);
                if (mysql_num_rows($var_result) > 0) {
                    while($var_row = mysql_fetch_array($var_result)){

                        $var_lookupName=$var_row["vLookUpName"];

                        switch ($var_lookupName) {

                        case "HelpdeskTitle":
                             $var_sitetitle =$var_row["vLookUpValue"];
                             break;
                        case "Logourl":
                             $var_LogoURL =$var_row["vLookUpValue"];
                            break;
                        case "Emailfooter":
                             $var_emailfooter =$var_row["vLookUpValue"];
                            break;
                        case "Emailheader":
                             $var_emailheader =$var_row["vLookUpValue"];
                            break;
                        }
                    }
                }
                else {
                          $var_sitetitleL="";
                          $var_LogoURL ="";
                          $var_emailfooter="";
                          $var_emailheader="";      
                }
                mysql_free_result($var_result);
        }
        elseif ($_POST["postback"] == "U") {

             $var_sitetitle = trim($_POST["txtSiteTitle"]);
             $var_LogoURL= trim($_POST["txtHelpLogoURLh"]);
             $var_emailfooter = trim($_POST["txtEmailFooter"]);
             $var_emailheader = trim($_POST["txtEmailHeader"]);
           
      $uploadstatus=upload_logo("txtHelpLogoURL","../custom/","","image/jpeg,image/pjpeg,image/gif,image/png,image/xpng,image/x-png","10000000",$var_LogoURL);

            
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
			   case "IF":
			            $errorcode=MESSAGE_UPLOAD_ERROR_6;
				         break;
//			   case "FE":
//			            $errorcode=MESSAGE_UPLOAD_ERROR_5; 
//				         break;
			   default:
				         $file_name=$uploadstatus;
				         break;
  		    }
			
			if($file_name !=""){
			     
//			    unlink("../".$var_LogoURL);  modified roshith on 6-11-06

				$var_LogoURL="custom/".$file_name;
				$sql = "Update sptbl_lookup set
                vLookUpValue='" . mysql_real_escape_string($var_LogoURL) . "'
                where vLookUpName = 'Logourl'";
				
                executeQuery($sql,$conn);
				$_SESSION["sess_logourl"] = "./custom/".$file_name;
				
			}
           if (validateUpdation() == true and $dup_flag==0 and $errorcode=="") {

             $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($var_sitetitle) .
             "'  where vLookUpName = 'HelpdeskTitle'";
             executeQuery($sql,$conn);

             $sql = "Update sptbl_lookup set
             vLookUpValue='" . mysql_real_escape_string($var_emailfooter) . "'
             where vLookUpName='Emailfooter'";
             executeQuery($sql,$conn);

             $sql = "Update sptbl_lookup set
             vLookUpValue='" . mysql_real_escape_string($var_emailheader) . "'
             where vLookUpName='Emailheader'";
             executeQuery($sql,$conn);


             $var_message = MESSAGE_RECORD_UPDATED;
             $flag_msg    = 'class="msg_success"';
           //  header("location:adminmain.php");
            // exit;
             }
             else {
                                $var_message = MESSAGE_RECORD_ERROR .$errorcode;
                                $flag_msg    = 'class="msg_error"';

             }
			 
}

     function validateUpdation() 
	{
		return true;
	}

?>
<form name="frmConfig" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>" enctype="multipart/form-data">
<div class="content_section">
			<div class="content_section_title">
				<h3><?php echo TEXT_EDIT_MISCCONFIG ?></h3>
			</div>
			<table width="100%"  border="0">
			  <tr>
				<td width="76%" valign="top">
									 <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
					
							 <tr>
					 <td align="center" colspan=3 >&nbsp;</td>
					 </tr>
					 <tr>
                                         <?php if( $var_message != ''){ ?>
					 <td align="center" colspan=3 <?php echo $flag_msg; ?>>
					 <?php echo $var_message ?></td>
                                         <?php } ?>
					 </tr>
					
					 <tr>
					 <td>&nbsp;</td>
					 <td align="left" colspan=2 class="toplinks">
					 <?php echo TEXT_FIELDS_MANDATORY ?></td>
					
					 </tr>
					
					
					
					 <tr><td colspan="3">&nbsp;</td></tr>
					 <tr>
					 <td width="2%" align="left">&nbsp;</td>
					 <td width="13%" align="left" class="toplinks"><?php echo TEXT_MISC_SITETITLE?> <font style="color:#FF0000; font-size:9px">*</font> </td>
					 <td width="85%" align="left">
					 <input name="txtSiteTitle" type="text" class="comm_input input_width1a" id="txtSiteTitle" size="30" maxlength="100" value="<?php echo htmlentities($var_sitetitle); ?>">
					 </td>
					 </tr>
					 <tr><td colspan="3">&nbsp;</td></tr>
					 <tr>
					 <td width="2%" align="left">&nbsp;</td>
					 <td width="13%" align="left" class="whitebasic" valign="top"><?php echo TEXT_MISC_LOGOURL?> <font style="color:#FF0000; font-size:9px">*</font> </td>
					 <td width="85%" align="left" class="whitebasic">
					 
					  <input name="txtHelpLogoURL" type="file" class="comm_input" id="txtHelpLogoURL" size="30" maxlength="100" value="<?php echo htmlentities($var_Url); ?>"> * <?php echo IMAGE_SIZE_NOTIFY; ?>
									&nbsp;<br><?php echo $var_LogoURL ?>
					  <input  type=hidden name="txtHelpLogoURLh" size="50" id="txtHelpLogoURLh"	value="<?php echo $var_LogoURL ?>">
					 </td>
					 </tr>
					 <tr><td colspan="3">&nbsp;</td></tr>
					 <tr>
					 <td width="2%" align="left">&nbsp;</td>
					 <td width="13%" align="left" class="toplinks" valign="top"><?php echo TEXT_MISC_EMAIL_HEADER?> <font style="color:#FF0000; font-size:9px">*</font> </td>
					 <td width="85%" align="left" class="toplinks">
						 <textarea name="txtEmailHeader" cols="50" rows="12" id="txtEmailHeader" class="textarea" style="width:780px;" maxlength="400" onKeyDown="limitLength(this.form.txtEmailHeader, 400);" onKeyUp="limitLength(this.form.txtEmailHeader, 400);"><?php echo htmlentities($var_emailheader); ?></textarea>
					 </td>
					 </tr>
					
					 <tr><td colspan="3">&nbsp;</td></tr>
					 <tr>
					 <td width="2%" align="left">&nbsp;</td>
					 <td width="13%" align="left" class="toplinks" valign="top"><?php echo TEXT_MISC_EMAIL_FOOTER?> <font style="color:#FF0000; font-size:9px">*</font> </td>
					 <td width="85%" align="left" class="toplinks">
					 <textarea name="txtEmailFooter" cols="50" rows="12" id="txtEmailFooter" class="textarea" style="width:780px;" onKeyDown="limitLength(this.form.txtEmailFooter, 400);" onKeyUp="limitLength(this.form.txtEmailFooter, 400);"><?php echo htmlentities($var_emailfooter); ?></textarea>      
					 </td>
					 </tr>         
					 
										  </table>



						<table width="100%"  border="0" cellspacing="10" cellpadding="0">
							<tr>
								<td class="btm_brdr">&nbsp;</td>
							</tr>
						  <tr>
							<td>
							<table width="100%"  border="0" cellspacing="0" cellpadding="0">
							  <tr align="center"  class="listingbtnbar">
								<td width="20%">&nbsp;</td>
								<td width="10%"></td>
								<td width="20%" align=right><input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT; ?>"  onClick="javascript:edit();"></td>
								<!--td width="20%"><input name="btCancel" type="reset" class="comm_btn" value="<?php //echo BUTTON_TEXT_CANCEL; ?>" ></td--><!-- onClick="javascript:cancel();" -->
								<td width="10%"></td>
								<td width="20%">
								<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
								<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
								<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
								<input type="hidden" name="postback" value="">
								</td>
							  </tr>
							</table>
							</td>
						  </tr>
						</table>
				  </td>
			  </tr>
			</table>
			</div>
</form>