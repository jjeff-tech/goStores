<?php 
$page = 'register';
if($_POST["postback"] == "Save Changes") {
    $error = false;
    $company=0;
    $errormessage = "" ;
    if(isNotNull($_POST["txtLoginName"])) {
        $loginname = trim($_POST["txtLoginName"]);
        if(!isValidUsername($loginname)) {
            $error = true;
            $errormessage .= MESSAGE_INVALID_LOGIN_NAME . "<br>";
        }else if(userNameExists($loginname)) {
            $error = true;
            $errormessage .= MESSAGE_LOGIN_NAME_EXISTS . "<br>";
        }
    }else {//login name null
        $error = true;
        $errormessage .= MESSAGE_LOGIN_NAME_REQUIRED . "<br>";
    }
    if(isNotNull($_POST["txtPassword"])) {
        $password = $_POST["txtPassword"];
        if(strlen($password) < $passwordLength) {
            $error = true;
            $errormessage .= MESSAGE_NEW_PASSWORD_LENGTH . "<br>";
        }
    }else {//user password null
        $error = true;
        $errormessage .= MESSAGE_PASSWORD_REQUIRED . "<br>";
    }
    if(isNotNull($_POST["txtConfirmPassword"])) {
        $confirmpassword = $_POST["txtConfirmPassword"];
    }else {//user confirmpassword null
        $error = true;
        $errormessage .= MESSAGE_CONFIRM_PASSWORD_REQUIRED . "<br>";
    }
    if(isNotNull($_POST["txtConfirmPassword"]) and isNotNull($_POST["txtPassword"])) {
        if($_POST["txtConfirmPassword"] != $_POST["txtPassword"] ) {
            $error = true;
            $errormessage .= MESSAGE_PASSWORDS_SHOULD_MATCH . "<br>";
        }
    }
    if(isNotNull($_POST["txtName"])) {
        $name = $_POST["txtName"];
    }else {//user name null
        $error = true;
        $errormessage .= MESSAGE_NAME_REQUIRED . "<br>";
    }
    if(isNotNull($_POST["ddlCompany"])) {
        $company = $_POST["ddlCompany"];
    }else {//user Company null
        $error = true;
        $errormessage .= MESSAGE_COMPANY_REQUIRED . "<br>";
    }
    if(isNotNull($_POST["txtEmail"])) {
        $email = $_POST["txtEmail"];
        if(!isValidEmail($email)) {
            $error = true;
            $errormessage .= MESSAGE_INVALID_EMAIL . "<br>";
        }
        elseif(!isUniqueEmail($email,0,$company)) {
            $error = true;
            $errormessage .= MESSAGE_NONUNIQUE_EMAIL . "<br>";
        }
    }else {//user Email null
        $error = true;
        $errormessage .= MESSAGE_EMAIL_REQUIRED . "<br>";
    }
    if($error) {
        $errormessage = MESSAGE_ERRORS_FOUND . "<br>" .$errormessage;
        $registered = false;
    }else {//no error so validate

        //if authenticate user is  yes then set vDelStatus=2 in sptbl_users
        if($auth_Status =='1') {
            $delStatus = 2;
            $infomessage = MESSAGE_REGISTRATION_SUCCESSFULL_AUTHETICATION;
        }else {
            $delStatus = 0;
            $infomessage = MESSAGE_REGISTRATION_SUCCESFULL.'<br/><br/><span style="text-align:center; font-size:13px;"><a href="index.php">'.TEXT_LOGIN.'</a><span>';
        }

        $sql1  = " INSERT INTO sptbl_users(`nUserId`, `nCompId`,`vUserName`,`vEmail`,`vLogin`,`vPassword`,`dDate`, `vBanned`, `vDelStatus`) ";
        $sql1 .= " VALUES('','".mysql_real_escape_string($company)."', '".mysql_real_escape_string($name)."','".mysql_real_escape_string($email)."','".mysql_real_escape_string($loginname)."','".mysql_real_escape_string(md5($password))."',now(),'0','".mysql_real_escape_string($delStatus)."')";
        $result1 = executeSelect($sql1,$conn);
        $id = mysql_insert_id();
        $message = true;
        $registered = true;

        $sql = " Select * from sptbl_lookup where vLookUpName IN('Post2PostGap','MailFromName','MailFromMail',";
        $sql .="'MailReplyName','MailReplyMail','Emailfooter','Emailheader','AutoLock','HelpdeskTitle','HelpDeskURL','EmailURL','SMTPSettings','SMTPServer','SMTPPort')";
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
                    case "HelpDeskURL":
                        $var_helpdeskurl = $row["vLookUpValue"];
                        break;
                    case "EmailURL":
                        $var_emailurl = $row["vLookUpValue"];
                        break;
                    case "SMTPSettings":
                        $_SESSION["sess_smtpsettings"] = $row["vLookUpValue"];
                        break;
                    case "SMTPServer":
                        $_SESSION["sess_smtpserver"] = $row["vLookUpValue"];
                        break;
                    case "SMTPPort":
                        $_SESSION["sess_smtpport"] = $row["vLookUpValue"];
                        break;
                }
            }
        }

        mysql_free_result($result);

        $var_mail_body  = $var_emailheader."<br>".
                $var_mail_body .= MESSAGE_YOU_ARE_REGISTERED_WITH .$var_helpdeskname ."<br>";
        $var_mail_body .= MESSAGE_DETAILS_FOLLOW. "<br><br>";
        $var_mail_body .= TEXT_LOGIN_NAME . ": $loginname<br>";
        $var_mail_body .= TEXT_PASSWORD . ": $password<br><br>";

        if($auth_Status =='1') { // if user authentication is on
            $var_mail_body .= MESSAGE_REGISTRATION_LINK . ": <a href='$var_emailurl"."activate.php?id=$id' >".$var_emailurl."activate.php?id=$id</a><br><br>";
            $var_mail_body .= MESSAGE_REGISTRATION_LINK_NOT_WORKING."<br>";
        }

        $var_mail_body .= TEXT_THANK_YOU_FOR_REGISTERING ."<br><br>";
        $var_mail_body .= $var_emailfooter;

        $var_body = $var_mail_body;
        $var_subject = MESSAGE_REGISTRATION_DETAILS ;
        $var_email_to = $email;
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
            mail($var_email_to,$var_subject,$var_body,$Headers);
    }
}
?>
<script>
    <!--

    function validateProfileForm(){
        var frm = window.document.frmProfile;
        var errors="";
        var pwdlength = "<?php echo $passwordLength; ?>";
        if(frm.txtLoginName.value.length == 0){
            errors += "<?php echo MESSAGE_LOGIN_NAME_REQUIRED; ?>"+ "\n";
        }
        if(frm.txtPassword.value.length == 0){
            errors += "<?php echo MESSAGE_PASSWORD_REQUIRED; ?>"+ "\n";
        }
        if(frm.txtPassword.value.length  < pwdlength){
            errors += "<?php echo MESSAGE_NEW_PASSWORD_LENGTH; ?>"+ "\n";
        }
        if(frm.txtConfirmPassword.value.length == 0){
            errors += "<?php echo MESSAGE_CONFIRM_PASSWORD_REQUIRED; ?>"+ "\n";
        }
        if((frm.txtPassword.value.length != 0) && (frm.txtConfirmPassword.value.length != 0)){
            if(frm.txtPassword.value != frm.txtConfirmPassword.value){
                errors += "<?php echo MESSAGE_PASSWORDS_SHOULD_MATCH; ?>"+ "\n";
            }
        }
        if(frm.txtName.value.length == 0){
            errors += "<?php echo MESSAGE_NAME_REQUIRED; ?>"+ "\n";
        }
        if(frm.txtEmail.value.length == 0){
            errors += "<?php echo MESSAGE_EMAIL_REQUIRED; ?>"+ "\n";
        }else if(!isValidEmail(frm.txtEmail.value)){
            errors += "<?php echo MESSAGE_INVALID_EMAIL; ?>"+ "\n";
        }
        //if(frm.ddlCompany.selectedIndex == 0){
        //	errors += "<?php echo MESSAGE_COMPANY_REQUIRED; ?>"+ "\n";
        //}
        if(errors !=""){
            errors = "<?php echo MESSAGE_ERRORS_FOUND; ?>"+  "\n" +  "\n" + errors;
            alert(errors);
            return false;
        }else{
            frm.postback.value = "Save Changes";
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

    function cancel(){
        ;
    }

    -->
</script>

<form action="" method="post" name="frmProfile">

    <div class="content_section">
        <div class="content_section_title" style="margin-bottom: 20px;"><h3><?php echo TEXT_REGISTER?></h3></div>
        <!-- ##########################################- -->

        <table width="80%"  border="0" align="center">
            <tr>
                <td colspan="3">
                    <!-- %%%%%%%%%%%%%%%%%%%%% Errors or Messages %%%%%%%%%%%%%%%%%% -->


                    <?php
                    if($error) {?>

                    <div class="msg_error"><?php echo $errormessage;?></div>
                        <?php }


                    if($message) { ?>
                    <div class="msg_success"> <?php echo $infomessage;?></div>
                        <?php }?>
                    <!-- %%%%%%%%%%%%%%%%%%%%% Errors or Messages %%%%%%%%%%%%%%%%%% -->

                </td>
            </tr>
            <?php
            if(!$registered) {
                ?>
            <tr>
                <td width="30%" align="left" class="listing"><?php echo TEXT_LOGIN_NAME?>&nbsp;<span class="required">*</span></td>
                <td width="2%">&nbsp;</td>
                <td width="68%" align="left"><input name="txtLoginName" type="text" size="30" maxlength="100" class="comm_input" value="<?php echo htmlentities($loginname);?>" ></td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td align="left" class="listing"><?php echo TEXT_PASSWORD?>&nbsp;<span class="required">*</span></td>
                <td>&nbsp;</td>
                <td align="left"><input name="txtPassword" type="password" value="" size="30" maxlength="100" class="comm_input"></td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td align="left" class="listing"><?php echo TEXT_CONFIRM_PASSWORD?>&nbsp;<span class="required">*</span></td>
                <td>&nbsp;</td>
                <td align="left"><input name="txtConfirmPassword" type="password" value="" size="30" maxlength="100" class="comm_input"></td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td align="left" class="listing"><?php echo TEXT_NAME?>&nbsp;<span class="required">*</span></td>
                <td>&nbsp;</td>
                <td align="left"><input name="txtName" type="text" size="30" maxlength="100" class="comm_input" value="<?php echo htmlentities($name);?>"></td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td align="left" class="listing"><?php echo TEXT_EMAIL?>&nbsp;<span class="required">*</span></td>
                <td>&nbsp;</td>
                <td align="left"><input name="txtEmail" type="text" size="30" maxlength="100" class="comm_input" value="<?php echo htmlentities($email);?>"></td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <!--tr>
                <td align="left" class="listing"><?php //echo TEXT_COMPANY?>&nbsp;<span class="required">*</span></td>
                <td>&nbsp;</td>
                <td align="left">
                    <input type="hidden" name="ddlCompany" id="ddlCompany" value="1" />
                        <?php
                        //echo makeDropDownList("ddlCompany",getCompanyList(),$company,"comm_input input_width1","","");
                        ?>
                </td>
            </tr-->
            <!--tr>
                <td colspan="3">&nbsp;</td>
            </tr-->
                <?php }
            ?>
            
        </table>


        <!-- ##########################################- -->

        <?php
        if(!$registered) {
            ?>
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
                                                    <td width="36%">&nbsp;</td>
                                                    <!--td width="10%"><input name="btnCancel" type="button" class="comm_btn" value="<?php //echo BUTTON_TEXT_CANCEL?>" onclick="window.location.href='index.php'"></td-->
                                                    <td width="15%" align="left"><input name="btnSubmit" type="button" class="comm_btn" value="<?php echo HEADER_REGISTER?>" onClick="javascript:validateProfileForm();"></td>
                                                    <td width="34%">&nbsp;</td><td>
                                                        <input type="hidden" name="ddlCompany" id="ddlCompany" value="1" />
                                                        <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>" >
                                                        <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                                                        <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
                                                        <input type="hidden" name="id" value="<?php echo($var_id); ?>">
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

            <?php
        }
        ?>
</form>
</div>
