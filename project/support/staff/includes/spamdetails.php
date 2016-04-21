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
if(isset($_GET['spamticketid']) != "") {
    $spamticketid=$_GET['spamticketid'];
    $var_message="";
}else if($_POST['spamticketid'] != "") {
    $spamticketid=$_POST['spamticketid'];
}
//	if ($_POST["postback"] == "") {
$sql = "Select vuseremail,nSpamTicketId,nDeptId,vTitle,tQuestion,dPostDate from sptbl_spam_tickets where nSpamTicketId='".mysql_real_escape_string($spamticketid)."' ";

$rs = executeSelect($sql,$conn);
$row = mysql_fetch_array($rs);

//   }else {
//            $var_message = "<font color=red>" . MESSAGE_RECORD_ERROR . "</font>";
//   }

?>
<form name="frmSpamEmails" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>">
    <div class="content_section">



        <div class="content_section">
            <div class="content_section_title">
                <h3><?php echo TICKET_SPAM_DETAILS ?></h3>
            </div>
            <div class="content_section_data">


                <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="column1">
                    <tr>
                        <td>
                            <div style="overflow:auto">
                                <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">

                                    <tr>
                                        <td width="100%" align="center" colspan=3 >&nbsp;</td>

                                    </tr>

                                    <tr>
                                        <td width="100%" align="center" colspan=3 class="errormessage"><div <?php echo $flag_msg;?>><?php echo $var_message ?></div></td>
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
                                            $ticket = str_replace('<p>','',$row['tQuestion']);
                                            $ticket = str_replace('</p>','<br>',$ticket);
                                            $ticket = str_replace('<br><br>','<br>',$ticket);

                                            echo html_entity_decode(htmlspecialchars(stripslashes(nl2br($ticket))));
                                            ?>
                                        </td>
                                    </tr>
                                    <tr><td colspan="3">&nbsp;</td></tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>


                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr >

                        <td ><table width="100%"  border="0" cellspacing="0" cellpadding="5" >
                                <tr>
                                    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                            <tr align="center"  class="listingbtnbar">
                                                <td width="2%">&nbsp;</td>
                                                <td width="70%" align=center><input name="btUpdate" type="button" class="comm_btn" value="<?php echo TICKET_SPAM_DELETE;?>"  onClick="javascript:spamdelete();">
                                                    <input name="btCancel" type="button" class="comm_btn" value="<?php echo TICKET_SPAM_NOSPAM;?>" onClick="notspam();">
                                                    <input name="btCancel" type="button" class="comm_btn" value="<?php echo TICKET_SPAM_BACK;?>" onClick="goback1();"></td>

                                                <td width="20%">
                                                    <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
                                                    <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                                                    <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
                                                    <input type="hidden" name="id" value="<?php echo($var_id); ?>">
                                                    <input type="hidden" name="postback" value="">
                                                    <input type="hidden" name="spamticketid" value="<?php echo $spamticketid;?>">
                                                </td>
                                            </tr>
                                        </table></td>
                                </tr>
                            </table></td>

                    </tr>
                </table>


            </div>
        </div>
</form>