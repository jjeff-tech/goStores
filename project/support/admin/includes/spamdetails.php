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

		if(isset($_GET['spamticketid']) != ""){
			$spamticketid=$_GET['spamticketid'];
			$var_message="";
                         $flag_msg = "";
		}else if($_POST['spamticketid'] != ""){
			$spamticketid=$_POST['spamticketid'];
		}

//        if ($_POST["postback"] == "") {
           $sql = "Select vuseremail,nSpamTicketId,nDeptId,vTitle,tQuestion,dPostDate from sptbl_spam_tickets where nSpamTicketId='".mysql_real_escape_string($spamticketid)."' ";

           $rs = executeSelect($sql,$conn);
           $row = mysql_fetch_array($rs);

//        }else {
//             $var_message = "<font color=red>" . MESSAGE_RECORD_ERROR . "</font>";
//        }


?>
<div class="content_section">
<form name="frmSpamEmails" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>">
<Div class="content_section_title"><h3><?php echo TICKET_SPAM_DETAILS ?></h3></Div>

<div class="content_section_data">
    
 		<div style="overflow:auto">
         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
          <tr>
           <td width="100%" align="center" colspan=3 >&nbsp;</td>
         </tr>
         <tr>
         <td width="100%" align="center" colspan=3 class="errormessage">
         <?php echo $var_message ?></td>
         </tr>
         <tr><td colspan="3">&nbsp;</td></tr>
         <tr>

         <td width="61%" align="left" class=maintext>
          User :<?php echo htmlentities(stripslashes($row['vuseremail'])); ?>
         </td>
         </tr>
          <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="61%" align="left" class=maintext>
         <b>Title:<?php echo htmlentities(stripslashes($row['vTitle'])); ?></b>
         </td>
         </tr>
         <tr><td colspan="3" class=maintext>&nbsp;</td></tr>
         <tr>

         <td width="61%" align="left" class=maintext>
          <?php 
          $question = str_replace('<p>','',$row["tQuestion"]);
                                $question = str_replace('</p>','<br>',$question);
                                $question = str_replace('<br><br>','<br>',$question);
                                echo html_entity_decode(htmlspecialchars(stripslashes((nl2br($question))))); 
                                
          //echo htmlentities(stripslashes($row['tQuestion'])); ?>
         </td>
         </tr>
                   <tr><td colspan="3">&nbsp;</td></tr>
                              </table>
		</div>
         </div>              
                  
				  
           
                <table width="100%"  border="0" cellspacing="0" cellpadding="5" >
                            <tr>
                              <td>

                              <table width="50%"  border="0" cellspacing="0" cellpadding="0" align="center">
                                  <tr align="center"  class="listingbtnbar">
                                    <td width="12%">&nbsp;</td>
                                    <td width="15%" align=right><input name="btUpdate" type="button" class="button" value="<?php echo TICKET_SPAM_DELETE;?>"  onClick="javascript:spamdelete();"></td><td width="12%">&nbsp;</td>
                                    <td width="15%"><input name="btCancel" type="button" class="button" value="<?php echo TICKET_SPAM_NOSPAM;?>" onClick="javascript:notspam();"></td><td width="12%">&nbsp;</td>
                                    <td width="15%"><input name="btCancel" type="button" class="button" value="<?php echo TICKET_SPAM_BACK;?>" onClick="javascript:goback();"></td>
                                    <td>
                                                                        <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
                                                                        <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                                                                        <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">

                                                                        <input type="hidden" name="postback" value="">
                                                                        <input type="hidden" name="spamticketid" value="<?php echo $spamticketid;?>">
                                    </td>
                                  </tr>
                              </table></td>
                            </tr>
                        </table>
                  
		

</form>
</div>