<?php
if($_POST["postback"] == "Save Changes"){
        $error = false;
        $errormessage = "" ;
        if(isNotNull($_POST["ddlCSS"])){
                $newid = $_POST["ddlCSS"];
                $ddlCSS = $_POST["ddlCSS"];
                $selectedid = $ddlCSS;
        }else{//user name null
                $error = true;
                $errormessage .= MESSAGE_STYLE_REQUIRED . "<br>";
        }
        if($error){
                $errormessage = MESSAGE_ERRORS_FOUND . "<br>" .$errormessage;
        }else{//no error so validate
                $sql1  = " UPDATE sptbl_users  ";
                $sql1 .= " SET nCSSId = '".mysql_real_escape_string($newid)."' WHERE nUserId = '".$_SESSION["sess_userid"]."' ";
                $result1 = executeQuery($sql1,$conn);
                $message = true;


                //update css

              $sql = "Select vCSSURL from sptbl_css where nCSSId='".mysql_real_escape_string($newid)."'";
              $result = executeSelect($sql,$conn);
              if (mysql_num_rows($result) > 0) {
                    $row = mysql_fetch_array($result);
                    $_SESSION["sess_cssurl"] = $row["vCSSURL"];
					//$_SESSION["sess_cssurl"] = "./styles/AquaBlue/style.css";
					
					
               }
                //update css

                $messagetext = MESSAGE_STYLE_UPDATED_SUCCESSFULLY;

               // echo "<script>location.href='selectstyle.php?stylename=SETTINGS&styleminus=fourminus&styleplus=fourplus&';</script>";
        }
}

$sql = "Select u.nCSSId from sptbl_users as u ";
$sql .=" where u.nUserId='".mysql_real_escape_string($_SESSION["sess_userid"])."'";
$result = executeSelect($sql,$conn);
if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);
        $selectedid = $row["nCSSId"];

}
?>
<script>
<!--

function validateStyleForm(){
        var frm = window.document.frmStyle;
        var errormessage="";
        var error =false;

        if (frm.ddlCSS.selectedIndex == 0) {
                   error = true;
                   errormessage += "<?php echo MESSAGE_STYLE_REQUIRED ?>" + "\n";
        }
        if(error){
                errormessage = "<?php echo MESSAGE_ERRORS_FOUND; ?>"+   "\n" + errormessage;
                alert(errormessage);
                return false;
        }else{
                frm.postback.value = "Save Changes";
                frm.submit();
        }
}
-->
</script>
<div class="content_section">
    <form action="" method="post" name="frmStyle">
        <div class="content_section_title">
            <h3><?php echo TEXT_SELECT_STYLE?></h3>
        </div>

        <!-- %%%%%%%%%%%%%%%%%%%%% Errors or Messages %%%%%%%%%%%%%%%%%% -->

            <?php
            if($error) {?>

        <div class="content_section_data">
            <div class="msg_error">
                        <?php echo $errormessage;?>
            </div>
        </div>

                <?php }if
    ($message) { ?>

        <div class="content_section_data">
            <div class="msg_success">
        <?php echo $messagetext;?>
            </div>
        </div>

        <?php }?>
        <!-- %%%%%%%%%%%%%%%%%%%%% Errors or Messages %%%%%%%%%%%%%%%%%% -->

        <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" class="comm_tbl">

            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td align="right" width="40%">
                    Choose your theme
                </td>
                <td width="60%" align="left"><?php echo makeDropDownList("ddlCSS",getCSSList(),$selectedid, "comm_input input_width1", $properties, "onchange='validateStyleForm()'", true ); ?></td>
            </tr>
        </table>




        <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="maintext">
            <tr align="center" class="pagecolor">
                <td width="34%">&nbsp;</td>
                <td width="16%"><!-- <input name="btnSubmit" type="button" class="button" value="<?php echo BUTTON_TEXT_SUBMIT?>" onClick="javascript:validateStyleForm();">--></td>
                <td width="16%"><!-- <input name="btnCancel" type="button" class="button" value="<?php echo BUTTON_TEXT_CANCEL?>"> --></td>
                <td width="34%">&nbsp;</td>

            <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>" >
            <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
            <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
            <input type="hidden" name="id" value="<?php echo($var_id); ?>">
            <input type="hidden" name="postback" value="">

            </tr>
        </table>

    </form>
</div>