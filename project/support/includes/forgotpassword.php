<?php 
$page = 'forgotpassword';
if(isset($_POST["postback"]) &&  $_POST["postback"]== "Get Password") { //echo '<pre>'; print_r($_POST); echo '</pre>'; exit;
    $error = false;
    $passworderrormessage = "" ;
    if(isNotNull($_POST["txtUserEmail"])) {
        $useremail = trim($_POST["txtUserEmail"]);
    }else {//user email null
        $error = true;
        $passworderrormessage .= MESSAGE_USER_EMAIL_REQUIRED . "<br>";
    }
    if($error) {
        $passworderrormessage = $passworderrormessage;
    }else {//no error so validate and send the
        $sql  = "SELECT nUserId , vUserName , vEmail , vLogin , vPassword FROM sptbl_users  ";
        $sql .= " WHERE vEmail = '".mysql_real_escape_string($useremail)."' ";
        $result = executeSelect($sql,$conn);
        if (mysql_num_rows($result) > 0) {
            $row = mysql_fetch_array($result);
            $userid   = $row["nUserId"];
            $username = $row["vLogin"];
            $useremail = $row["vEmail"];
            $userfullname = $row["vUserName"];

            $code = rand(1, 999999);

            $sql  = "UPDATE sptbl_users  ";
            $sql .= " SET vCodeForPass = '".mysql_real_escape_string($code)."' WHERE nUserId = '".$userid."' ";
            //echo $sql;
            $result = executeSelect($sql,$conn);

            //$path = substr($thisfile,0,)
            $link = getPath()."/resetpass.php?action=resetpass&code=".$code;
            $message=true;


            /*****************************************************************************/
            $sql = " Select * from sptbl_lookup where vLookUpName IN('Post2PostGap','MailFromName','MailFromMail',";
            $sql .="'MailReplyName','MailReplyMail','Emailfooter','Emailheader','AutoLock','HelpdeskTitle')";
            $result = executeSelect($sql,$conn);
            if(mysql_num_rows($result) > 0) {
                while($row = mysql_fetch_array($result)) {
                    switch($row["vLookUpName"]) {
                        case "MailFromName":
                            $var_fromName = $row["vLookUpValue"];
                            break;
                        case "MailFromMail":
                            $var_fromMail = $row["vLookUpValue"];
                            break;
                        case "MailReplyName":
                            $var_replyName = $row["vLookUpValue"];
                            break;
                        case "MailReplyMail":
                            $var_replyMail = $row["vLookUpValue"];
                            break;
                        case "Emailfooter":
                            $var_emailfooter = $row["vLookUpValue"];
                            break;
                        case "Emailheader":
                            $var_emailheader = $row["vLookUpValue"];
                            break;
                        case "AutoLock":
                            $var_autoclock = $row["vLookUpValue"];
                            break;
                        case "HelpdeskTitle":
                            $var_helpdeskname = $row["vLookUpValue"];
                            break;

                    }
                }
            }
            mysql_free_result($result);

            $var_mail_body  = $var_emailheader."<br>".
            $var_mail_body .= MESSAGE_YOU_HAVE_REQUESTED_FOR_PASSWORD_RESET . $var_helpdeskname . "<br>";
            $var_mail_body .= MESSAGE_CLICK_TO_RESET_PASSWORD. "<br><br>";
            $var_mail_body .= "<a href=\"$link\">".$link."</a><br><br>";
            $var_mail_body .= $var_emailfooter;

            $var_body = $var_mail_body;
            $var_subject = MESSAGE_CONFIRM_PASSWORD_RESET_REQUEST ;
            $var_email_to = $useremail;
            $Headers="From: $var_fromName <$var_fromMail>\n";
            $Headers.="Reply-To: $var_replyName <$var_replyMail>\n";
            $Headers.="MIME-Version: 1.0\n";
            $Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

            // it is for smtp mail sending
            if($_SESSION["sess_smtpsettings"] == 1) {
                $var_smtpserver = $_SESSION["sess_smtpserver"];
                $var_port = $_SESSION["sess_smtpport"];

                SMTPMail($var_fromMail,$var_email_to,$var_smtpserver,$var_port,$var_subject,$var_body);
            }
            else
                @mail($var_email_to,$var_subject,$var_body,$Headers);
            /*****************************************************************************/

            $infomessage = MESSAGE_LINK_FOR_PASSWORD_RESET_SENT_TO . "'".$var_email_to."'";
            
        }else {
            $error = true;
            $passworderrormessage = MESSAGE_EMAIL_DONOT_EXIST;
        }
    }
}
?>
<script>
    
    function checkGetPasswordForm(){
        var frm = window.document.frmForgotPassword;
        var errors="";
        if(frm.txtUserEmail.value == ""){
            errors += "<?php echo MESSAGE_USER_EMAIL_REQUIRED; ?>"+ "\n";
        }
        else if(!isValidEmail(frm.txtUserEmail.value)) {
            errors += "<?php echo MESSAGE_INVALID_EMAIL; ?>"+ "\n";
        }
        if(errors !=""){
            errors = errors;
            alert(errors);
            return false;
        }else{
            frm.postback.value = "Get Password";
            frm.submit();
        }
    }
    function isValidEmail(email){
        var str1=email;
        var arr=str1.split('@');
        var eFlag=true;
        if(arr.length != 2)
        {
            eFlag = false;
        }
        else if(arr[0].length <= 0 || arr[0].indexOf(' ') != -1 || arr[0].indexOf("'") != -1 || arr[0].indexOf('"') != -1 || arr[1].indexOf('.') == -1)
        {
            eFlag = false;
        }
        else
        {
            var dot=arr[1].split('.');
            if(dot.length < 2)
            {
                eFlag = false;
            }
            else
            {
                if(dot[0].length <= 0 || dot[0].indexOf(' ') != -1 || dot[0].indexOf('"') != -1 || dot[0].indexOf("'") != -1)
                {
                    eFlag = false;
                }

                for(i=1;i < dot.length;i++)
                {
                    if(dot[i].length <= 0 || dot[i].indexOf(' ') != -1 || dot[i].indexOf('"') != -1 || dot[i].indexOf("'") != -1)
                    {
                        eFlag = false;
                    }
                }
                if(dot[i-1].length > 4)
                    eFlag = false;
            }
        }
        return eFlag;
    }
</script>

<form action="" method="post" name="frmForgotPassword">

    <div class="content_section">
        <div class="content_section_title" style="margin-bottom: 20px;"><h3><?php echo HEADER_FORGOTPASSWORD?></h3></div>
        <!-- ##########################################- -->

        <table width="80%"  border="0" align="center">
            <tr>
                <td colspan="3">
                    <!-- %%%%%%%%%%%%%%%%%%%%% Errors or Messages %%%%%%%%%%%%%%%%%% -->


                    <?php
                    if($passworderrormessage!='') {?>
                        <div class="msg_error"><?php echo $passworderrormessage;?></div>
                    <?php }


                    if($message) { ?>
                    <div class="msg_success"> <?php echo $infomessage;?></div>
                    <?php }?>
                    <!-- %%%%%%%%%%%%%%%%%%%%% Errors or Messages %%%%%%%%%%%%%%%%%% -->

                </td>
            </tr>
           
            <tr>
                <td align="left" class="listing"><?php echo TEXT_EMAIL?>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="left"><input name="txtUserEmail" id="txtUserEmail" type="text" size="30" maxlength="100" class="comm_input" value="<?php echo htmlentities($email);?>"></td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            
        </table>


        <!-- ##########################################- -->
        
        <table width="100%"  border="0" cellspacing="10" cellpadding="0" >
            <tr>
                <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                        <tr>
                            <td><img src="images/spacerr.gif" width="1" height="1"></td>
                        </tr>
                    </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="1" ><img src="images/spacerr.gif" width="1" height="1"></td>
                            <td class="pagecolor">

                                <table width="100%"  border="0" cellspacing="0" cellpadding="5">
                                    <tr>
                                        <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" class="maintext">
                                                <tr align="center" class="pagecolor">
                                                    <td width="23%">&nbsp;</td>
                                                    <!--td width="10%"><input name="btnCancel" type="button" class="comm_btn" value="<?php //echo BUTTON_TEXT_CANCEL?>" onclick="window.location.href='index.php'"></td-->
                                                    <td width="15%" align="left"><input name="btnSubmit" type="button" class="comm_btn" value="<?php echo TEXT_GET_PASSWORD?>" onClick="return checkGetPasswordForm();"></td>
                                                    <td width="34%">&nbsp;</td><td>
                                                        <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>" >
                                                        <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                                                        <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
                                                        <input type="hidden" name="postback" value="">

                                                    </td></tr>
                                            </table></td>
                                    </tr>
                                </table>


                            </td>
                            <td width="1" ><img src="images/spacerr.gif" width="1" height="1"></td>
                        </tr>
                    </table>


                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td ><img src="images/spacerr.gif" width="1" height="1"></td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>
           
</form>
</div>
