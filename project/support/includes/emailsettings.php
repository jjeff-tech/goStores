<?php
if($_POST["btnSubmit_Add"]) {
    $error = false;
    $errormessage = "" ;
    $email = $_POST["txtEmail"];
    if(isNotNull($_POST["txtEmail"])) {
        if(!isValidEmail($email)) {
            $error = true;
            $errormessage .= MESSAGE_INVALID_EMAIL . "<br>";
        }else {
            $sql = "Select vEmail from sptbl_useremail where vEmail = '". mysql_real_escape_string($email)."'";
            $rs = executeSelect($sql,$conn);
            if(mysql_num_rows($rs) > 0) {
                $error = true;
                $errormessage .= MESSAGE_NONUNIQUE_EMAIL . "<br>";
            }
        }
    }else {
        $error = true;
        $errormessage .= MESSAGE_EMAIL_REQUIRED . "<br>";
    }
    if($error) {
        $errormessage = MESSAGE_ERRORS_FOUND . "<br>" .$errormessage;
    }else {//no error so validate
        $sql1  = " INSERT INTO  sptbl_useremail (nUserId,vEmail) VALUES ('". mysql_real_escape_string($_SESSION["sess_userid"])."',
                                                                                 '". mysql_real_escape_string($email)."') ";
        $result1 = executeQuery($sql1,$conn);
        $message = true;
        $messagetext = MESSAGE_EMAIL_SAVE_SUCCESSFULLY;
        $email = "";

    }
}else if($_POST["btnSubmit_Edit"]) {
    if(isNotNull($_POST["txtEmail"])) {
        $email = $_POST["txtEmail"];
        if(!isValidEmail($email)) {
            $error = true;
            $errormessage .= MESSAGE_INVALID_EMAIL . "<br>";
            $sql = "Select * from sptbl_useremail u ";
            $sql .=" where u.nUserId !='".mysql_real_escape_string($_SESSION["sess_userid"])."' AND vEmail = '". mysql_real_escape_string($email)."'";
            $result = executeSelect($sql,$conn);
            if (mysql_num_rows($result) > 0) {
                $error = true;
                $errormessage .= MESSAGE_NONUNIQUE_EMAIL . "<br>";
            }

        }
    }else {
        $error = true;
        $errormessage .= MESSAGE_EMAIL_REQUIRED . "<br>";
    }
    if($error) {
        $errormessage = MESSAGE_ERRORS_FOUND . "<br>" .$errormessage;
    }else {//no error so validate

        $id= $_POST['id'];
        $sql1  = " UPDATE  sptbl_useremail SET vEmail = '". mysql_real_escape_string($email)."' where nUseremailId ='".mysql_real_escape_string($id)."' ";
        $result1 = executeQuery($sql1,$conn);
        $message = true;
        $messagetext = MESSAGE_EMAIL_UPDATED_SUCCESSFULLY;
        $email = "";
    }


}else if($_GET['action']== 'delete') {
    if(isNotNull($_GET["id"])) {
        $id= $_GET['id'];
        $sql1  = " DELETE FROM  sptbl_useremail  where nUseremailId ='".mysql_real_escape_string($id)."' ";
        $result1 = executeQuery($sql1,$conn);
        $message = true;
        $messagetext = MESSAGE_EMAIL_DELETED_SUCCESSFULLY;
    }
}else if($_GET['action']== 'edit') {
    if(isNotNull($_GET["id"])) {
        $id= $_GET['id'];
        $sql = "Select * from sptbl_useremail u ";
        $sql .=" where u.nUserId ='".mysql_real_escape_string($_SESSION["sess_userid"])."' AND nUseremailId ='".mysql_real_escape_string($id)."'";
        $result_edit = executeSelect($sql,$conn);
        if (mysql_num_rows($result_edit) > 0) {
            $row_edit = mysql_fetch_array($result_edit);

            $email = $row_edit["vEmail"];
            $varid = $row_edit["nUseremailId"];
        }
    }
}
?>
<script>
    <!--

    function validateEmailForm(){
        var frm = window.document.frmProfile;
        var errors="";
        if(frm.txtEmail.value.length == 0){
            errors += "<?php echo MESSAGE_EMAIL_REQUIRED; ?>"+ "\n";
        }else if(!isValidEmail(frm.txtEmail.value)){
            errors += "<?php echo MESSAGE_INVALID_EMAIL; ?>"+ "\n";
        }
        if(errors !=""){
            errors = "<?php echo MESSAGE_ERRORS_FOUND; ?>"+  "\n" +  "\n" + errors;
            alert(errors);
            return false;
        }else{
            return true;
            //frm.postback.value = "Save Changes";
            //frm.submit();
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
<div class="content_section">
    <form action="emailsettings.php" method="post" name="frmProfile">

        <div class="content_section_title">
            <h3><?php echo HEADER_EMAL?></h3>
            <span style="font-size: 13px; color: #777; padding: 5px 0 0 20px; display: block ">
                <?php echo EMAIL_HELP_TEXT ?> </span>
                <!--span id="set1" style="cursor: pointer;">
                    <a title="If a user has more than one email to which he/she wants to get emails">
                        <img src="images/tooltip.jpg">
                    </a>
                </span-->
            
        </div>

        <div class="content_section_data">


            <?php  if($error) {?>

            <div class="msg_error">
                    <?php echo $errormessage;?>
            </div>

            <?php }
            if($message) { ?>

            <div class="msg_success">
                    <?php echo $messagetext;?>
            </div>


                <?php }?>
            <!--***********List Email **************-->
            
            <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="list_tbl" style="border:1px solid #cfcfcf; margin-bottom:10px; ">
                <tr>
                    <th align="left" width="85%"><?php echo TEXT_EMAIL;?></th>
                    <th align="center" width="15%"><?php echo TEXT_ACTION;?></th>
                </tr>
                <?php
                $sqlUserMainEmailQuery = "SELECT vEmail FROM sptbl_users
                                          WHERE nUserId=".mysql_real_escape_string($_SESSION["sess_userid"])." AND vDelStatus = '0' ";

                $sqlUserMainEmailRes = mysql_query($sqlUserMainEmailQuery) or die(mysql_error());
                $sqlUserMainEmailVal = mysql_fetch_assoc($sqlUserMainEmailRes);
                //echo '<pre>'; print_r($sqlUserMainEmailVal); echo '</pre>';
                ?>
                <tr>
                    <td align="left"><?php echo stripslashes($sqlUserMainEmailVal['vEmail']);?></td>
                    <td align="center" style="font-size: 12px;">Primary Email</td>
                </tr>
                <?php

                $sql = "Select * from sptbl_useremail u ";
                $sql .=" where u.nUserId ='".mysql_real_escape_string($_SESSION["sess_userid"])."' AND u.vStatus = 'Y'";
                $result = executeSelect($sql,$conn);
                if (mysql_num_rows($result) > 0) {
                    while($row = mysql_fetch_array($result)) {
                        ?>
                <tr>
                    <td align="left"><?php echo stripslashes($row['vEmail']);?></td>
                    <td align="center">

                        <a href="<?php echo SITE_URL?>emailsettings.php?action=edit&id=<?php echo $row['nUseremailId'];?>" title="Edit">
                            <img width="13" height="13" border="0" title="Edit Priority" src="./images/edit.gif">
                        </a>

                        <a href="<?php echo SITE_URL?>emailsettings.php?action=delete&id=<?php echo $row['nUseremailId'];?>" title="Delete">
                            <img width="13" height="13" border="0" title="Delete Priority" src="./images/delete.gif">
                        </a>

                    </td>
                </tr>
                        <?php
                    }
                }
                //else {
                    ?>
                <!--tr>
                    <td align="center" ><?php //echo TEXT_EMAIL_NOT_FOUND;?></td>
                </tr-->
                    <?php
                //}

                ?>
            </table>



            <!--****************Add  email************************-->
            <div class="clear"></div>
            <div style="border:1px solid #cfcfcf; ">
                <div class="content_section_title"><h3><?php echo TEXT_EMAL_ADD?></h3></div>
                <div class="clear"></div>

                <table width="100%"  border="0" cellpadding="0" cellspacing="0" border="0" class="comm_tbl btm_brdr" style="border:1px solid #cfcfcf;">
                    <tr>
                        <td align="left" width="10%"><?php echo TEXT_EMAIL?>&nbsp;<span class="required">*</span></td>
                        <td align="left" width="30%">
                            <input name="txtEmail" type="text" size="30" maxlength="100" class="comm_input input_width1"  value="<?php echo htmlentities($email);?>">
                        </td>
                        <td align="left">
                            <?php
                            if($_GET['action']== 'edit') {
                                ?>
                            <input name="btnSubmit_Edit" type="submit" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT?>" onClick="return validateEmailForm();">
                                <?php
                            }else {
                                ?>
                            <input name="btnSubmit_Add" type="submit" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD_NEW?>" onClick="return validateEmailForm();">
                                <?php
                            }
                            ?>
                            <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>" >
                            <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                            <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
                            <input type="hidden" name="id" value="<?php echo $varid; ?>">
                            <input type="hidden" name="postback" value="">

                        </td>
                    </tr>

                </table>







                </form>
            </div></div>
