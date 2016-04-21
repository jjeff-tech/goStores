<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>                                  |
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
        //$var_userid = $_SESSION["sess_staffid"];
        $var_staffid = "1";

        if ($_POST["postback"] == "") {

                $sql = "Select vLookUpName,vLookUpValue from sptbl_lookup where vLookUpName='DefaultLang'";

                $var_result = executeSelect($sql,$conn);
                if (mysql_num_rows($var_result) > 0) {

                        $var_lookupName=$var_row["vLookUpName"];


                 }



                mysql_free_result($var_result);
        }

        elseif ($_POST["postback"] == "U") {

             $var_siteURL = trim($_POST["txtSiteURL"]);
             $var_helpDeskURL = trim($_POST["txtHelpDeskURL"]);
             $var_defaultLang = trim($_POST["cmbDefaultLang"]);
             $var_post2PostGap = trim($_POST["txtPost2PostGap"]);
             $var_langChoice = trim($_POST["rdLangChoice"]);


             $sql = "Update sptbl_lookup set
             vLookUpValue='" . mysql_real_escape_string($var_verifyKB) . "'
             where vLookUpName='VerifyKB'";
             executeQuery($sql,$conn);

            //Insert the actionlog
			if(logActivity()) {
				$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Config','0',now())";
				executeQuery($sql,$conn);
			}

                                $var_message = MESSAGE_RECORD_UPDATED;
                                $flag_msg    = 'class="msg_success"';
             }
             else {
                                $var_message = MESSAGE_RECORD_ERROR;
                                $flag_msg    = 'class="msg_error"';

             }




?>
<form name="frmConfig" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>">
<table width="100%"  border="0">
  <tr>
    <td width="76%" valign="top">
    <table width="100%"  border="0" cellspacing="10" cellpadding="0">
    <tr>
    <td>
    <table width="100%"  border="0" cellpadding="0" cellspacing="0" >
     <tr>
     <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
     </tr>

     </table>

     <table width="100%"  border="0" cellspacing="0" cellpadding="0">
     <tr>
     <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
     <td class="pagecolor">
     <table width="100%"  border="0" cellspacing="0" cellpadding="5">
     <tr>
     <td width="93%" class="heading"><?php echo TEXT_EDIT_CONFIG ?></td>
     </tr>
     </table>


     <table width="100%"  border="0" cellspacing="1" cellpadding="0" class="column1">
     <tr>
     <td>
         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">

                 <tr>
         <td width="100%" align="center" colspan=3 >&nbsp;</td>
         </tr>
         <tr>
         <?php if( $var_message != ''){ ?>
         <td width="100%" align="center" colspan=3 <?php echo $flag_msg; ?>>
         <?php echo $var_message ?></td>
         <?php } ?>
         </tr>
         <tr>
         <td width="100%" align="center" colspan=3 class="toplinks">
         <?php echo TEXT_FIELDS_MANDATORY ?></td>
         </tr>
         <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="13%" align="left">&nbsp;</td>
         <td width="26%" align="left" class="toplinks"><?php echo TEXT_CON_DEFAULTLANG?> <font style="color:#FF0000; font-size:9px">*</font> </td>
         <td width="61%" align="left">


         <?php

        $sql = "SELECT vLangCode,vLangDesc  FROM `sptbl_lang` order by vLangDesc";
        $rs = executeSelect($sql,$conn);
        ?>
        <select name="cmbDefaultLang" size="1" class="textbox" id="cmbDefaultLang" >
        <?php
        $options ="<option value='0'";
        $options .=">Select</option>\n";
        while($row = mysql_fetch_array($rs)) {
                                                                                                       $options ="<option value='".$row['vLangCode']."'";
                                                                                                       if ($var_defaultLang == $row['vLangCode']){
                                                                                                                 $options .=" selected=\"selected\"";

            }
            $options .=">".$row['vLangDesc']."</option>\n";
                                                                                                      echo $options;
          }
          ?>

         </select>
         </td>
         </tr>



 

                                                                <tr><td colspan="3">&nbsp;</td></tr>
                              </table>
                        </td>
                            </tr>
                        </table></td>
                      <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table></td>
              </tr>
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
                              <td>

                              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center"  class="listingbtnbar">
                                    <td width="20%">&nbsp;</td>
                                    <td width="10%"></td>
                                    <td width="20%" align=right><input name="btUpdate" type="button" class="button" value="<?php echo BUTTON_TEXT_EDIT; ?>"  onClick="javascript:edit();"></td>
                                    <td width="20%"><input name="btCancel" type="button" class="button" value="<?php echo BUTTON_TEXT_CANCEL; ?>" onClick="javascript:cancel();"></td>
                                    <td width="10%"></td>
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
                        <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                    </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                  </table></td>
              </tr>
            </table>
            <p class="ashbody">&nbsp;</p></td>

  </tr>
</table>

</form>