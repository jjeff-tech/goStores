<?php
if($_POST["postback"] == "Save Changes") {
    $error = false;
    $errormessage = "" ;
    if(isNotNull($_POST["txtEmail"])) {
        $email = trim($_POST["txtEmail"]);
        $sql="select u.nUserId ,u.vEmail,t.vRefNo,t.vDelStatus,
                date_format(t.dPostDate,'%m/%d/%Y') as dPostDate,t.vTitle
                from sptbl_users u left outer join  sptbl_tickets t
                on t.nUserId = u.nUserId WHERE u.vEmail='$email'
                and (t.vDelStatus='0' OR ISNULL(t.vDelStatus))";


        $result=mysql_query($sql,$conn);
        //$rw=mysql_fetch_array($result);

        //if(mysql_num_rows($result) > 0 && $rw["vRefNo"]!=''){
        if(mysql_num_rows($result) > 0) {
//                   $row=mysql_fetch_array($result);
//	                  if($row["vRefNo"]!="")
            $refNos="<table width=100% border=1>";
            while($row=mysql_fetch_array($result)) {
                if($row["vRefNo"]!="")
                    $refNos.="<tr><td>".$row["vRefNo"]." &nbsp; </td><td>&nbsp; ".$row["vTitle"]." &nbsp;</td><td>&nbsp; ".$row["dPostDate"]."</td></tr>";
            }
            $refNos.="</table>";
            mysql_free_result($result);

            //----------------------------------mail to ref nos--------------

            $sql = "Select * from sptbl_lookup where vLookUpName
                      IN('MailFromName','MailFromMail'
                      ,'MailReplyName','MailReplyMail','Emailfooter','Emailheader')";
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
                            $var_emailFooter = $row["vLookUpValue"];
                            break;
                        case "Emailheader":
                            $var_emailHeader = $row["vLookUpValue"];
                            break;
                    }
                }
            }
            mysql_free_result($result);

            $var_email=$email;
            $var_mail_body = "$var_emailHeader <br><br>";
            $var_mail_body .= "$refNos <br><br>";
            $var_mail_body .= "$var_emailFooter";

            $var_subject=TEXT_MAIL_SUBJECT;
            $var_body = $var_mail_body;
            $Headers="From: $var_fromName <$var_fromMail>\n";
            $Headers.="Reply-To: $var_replyName <$var_replyMail>\n";
            $Headers.="MIME-Version: 1.0\n";
            $Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

            // it is for smtp mail sending
            if($_SESSION["sess_smtpsettings"] == 1) {
                $var_smtpserver = $_SESSION["sess_smtpserver"];
                $var_port = $_SESSION["sess_smtpport"];

                SMTPMail($var_fromMail,$var_email,$var_smtpserver,$var_port,$var_subject,$var_body);
            }
            else
                $mailstatus=@mail($var_email,$var_subject,$var_body,$Headers);

            $message=TEXT_MAIL_SEND;
            $flag_msg="class='msg_success'";
            //-----------------------------------/mail------------------------
        }else {
            $message=TEXT_INVALID_TICKET_COUNT_ERROR;
            $flag_msg="class='msg_error'";
        }
    }else {
        $message=TEXT_INVALID_MAIL_ERROR;
        $flag_msg="class='msg_error'";
    }
}
?>
<script>
    <!--

    function validateRefNo(){
        var frm = window.document.frmRefNo;
        if(frm.txtEmail.value.length == 0){
            alert("<?php echo MESSAGE_JS_EMPTY_EMAIL; ?>");
            frm.txtEmail.focus();
            return false;
        }else if(!isValidEmail(frm.txtEmail.value)){
            alert("<?php echo MESSAGE_JS_EMAIL_ERROR; ?>");
            frm.txtEmail.focus();
            return false;
        }else{
            frm.postback.value = "Save Changes";
            frm.submit();
            return true;
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

    function refNoPress()
    {
        if(window.event.keyCode=="13"){
            if(!validateRefNo()) {
                return false;
            }
        }
    }

    -->
</script>
<div class="content_section">
    <form action="" method="post" name="frmRefNo">

        <div class="content_section_title">
            <h3><?php echo TEXT_REFERENCE_NUMBER?></h3>
        </div>

        <div class="content_section_data">

            <div class="note2">
                <?php echo TEXT_GET_REFNO?>
            </div>
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="comm_tbl">
               
                <tr>
                    <td align="center" colspan="3" class="errormessage">
                        <div <?php echo $flag_msg; ?>>  <?php echo $message ?> </div></td>
                </tr>
                <tr>
                    <td align="left" width=""><?php echo TEXT_EMAIL?>&nbsp;<!--span class="required">*</span--></td>
                    <td align="left" width="600">
                        <input name="txtEmail" type="text"  maxlength="100" class="comm_input input_widthnw10" value="<?php echo $email;?>" onKeyPress="return refNoPress();">
                    </td>
                    <td>
					 <!--input name="btnSubmit" type="button" class="comm_btn" value="<?php //echo BUTTON_TEXT_BACK?>" onClick="window.location.href='index.php'">&nbsp;&nbsp;&nbsp;&nbsp;-->
                    <input name="btnSubmit" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_SUBMIT?>" onClick="javascript:validateRefNo();">
                    <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>" >
                    <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                    <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
                    <input type="hidden" name="id" value="<?php echo($var_id); ?>">
                    <input type="hidden" name="postback" value="">
					</td>
                </tr>
                <tr>
            </table>
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="comm_tbl">
                <td>&nbsp;</td>
                <td align="left" width="89%">
                   
                </td>
                </tr>
            </table>

    </form>
</div>
</div> 