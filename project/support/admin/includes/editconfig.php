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
// |                                                                      | 
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
$display_status ="display:none";
$var_staffid = "1";

if (!isset($_POST['txtSMTPPort'])) {
    $var_port = 25;
}

if ($_POST["postback"] == "") {

    $sql = "Select vLookUpName,vLookUpValue from sptbl_lookup ";
    $var_result = executeSelect($sql,$conn);
    if (mysql_num_rows($var_result) > 0) {
        while($var_row = mysql_fetch_array($var_result)) {

            $var_lookupName=$var_row["vLookUpName"];

            switch ($var_lookupName) {

                case "SiteURL":
                    $var_siteURL =$var_row["vLookUpValue"];
                    break;
                case "HelpDeskURL":
                    $var_helpDeskURL =$var_row["vLookUpValue"];
                    break;
                case "LangChoice":
                    $var_langChoice =$var_row["vLookUpValue"];
                    break;
                case "DefaultLang":
                    $var_defaultLang =$var_row["vLookUpValue"];
                    break;
                case "Post2PostGap":
                    $var_post2PostGap =$var_row["vLookUpValue"];
                    break;
                case "AutoLock":
                    $var_autoLock =$var_row["vLookUpValue"];
                    break;
                case "VerifyTemplate":
                    $var_verifyTemplate =$var_row["vLookUpValue"];
                    break;
                case "VerifyKB":
                    $var_verifyKB =$var_row["vLookUpValue"];
                    break;
                case "logactivity":
                    $var_approvelog = $var_row["vLookUpValue"];
                    break;
                case "EmailPiping":
                    $var_emailpiping =  $var_row["vLookUpValue"];
                    break;
                case "UserAuthenticate":
                    $var_userauthentication =  $var_row["vLookUpValue"];
                    break;
                case "MessageRule":
                    $var_messagerule =  $var_row["vLookUpValue"];
                    break;
                case "PostTicketBeforeLogin":
                    $var_postticketbeforeregister =  $var_row["vLookUpValue"];
                    break;
                case "MaxPostsPerPage":
                    $var_maxPost =  $var_row["vLookUpValue"];
                    break;
                case "OldestMessageFirst":
                    $var_messageOrder = $var_row["vLookUpValue"];
                    break;
                case "SMTPSettings":
                    $var_smtp = $var_row["vLookUpValue"];
                    break;
                case "SMTPServer":
                    $var_smtpserver = $var_row["vLookUpValue"];
                    break;
                case "SMTPPort":
                    $var_port = $var_row["vLookUpValue"];
                    break;
                case "SMTPUsername":
                    $var_username = $var_row["vLookUpValue"];
                    break;
                case "SMTPPassword":
                    $var_password = $var_row["vLookUpValue"];
                    break;
                case "SMTPEnableSSL":
                    $var_enableSSL = $var_row["vLookUpValue"];
                    break;
                case "LiveChat":
                    $var_livechat =  $var_row["vLookUpValue"];
                    break;
                case "AD_AUTHENTICATION":
                    $AD_AUTHENTICATION =  $var_row["vLookUpValue"];
                    break;
                case "AD_USER":
                    $AD_USER =  $var_row["vLookUpValue"];
                    break;
                case "AD_PASS":
                    $AD_PASS =  $var_row["vLookUpValue"];
                    break;
                case "AD_DOMAIN":
                    $AD_DOMAIN =  $var_row["vLookUpValue"];
                    break;
                case "AD_HOST":
                    $AD_HOST =  $var_row["vLookUpValue"];
                    break;
                case "AD_USER_DIR":
                    $AD_USER_DIR =  $var_row["vLookUpValue"];
                    break;
                case"NewTicketAutoReturnMail":
                    $var_ticketmail =  $var_row["vLookUpValue"];
                    break;
                case"Theme":
                    $var_theme =  $var_row["vLookUpValue"];
                    break;
                case"HomeFooterContent":
                    $var_homeFooterContent =  $var_row["vLookUpValue"];
                    break;
                case"Version":
                    $var_version =  $var_row["vLookUpValue"];
                    break;
                
            }
        }
    }
    else {
        $var_siteURL="";
        $var_helpDeskURL="";
        $var_defaultLang="";
        $var_post2PostGap="";
        $var_langChoice="";
        $var_autoLock="";
        $var_verifyTemplate="";
        $var_verifyKB="";
        $var_approvelog="";
        $var_maxPost = 30;
        $var_messageOrder = 1;
        $var_messagerule = 0;
        $var_livechat = 0;
        $var_ticketmail=0;
        $var_theme=0;
        $var_smtp = 0;
        $var_smtpserver = "";
        $var_port = "25";
        $var_username = "";
        $var_password = "";
        $var_enableSSL = "";
        $AD_AUTHENTICATION =  "N";
        $AD_USER =  "";
        $AD_PASS =  "";
        $AD_DOMAIN =  "";
        $AD_HOST =  "";
        $AD_USER_DIR =  "";
        $var_homeFooterContent = "";
        $var_version = "";
    }
    mysql_free_result($var_result);
}

elseif ($_POST["postback"] == "U") {

    $var_siteURL = trim($_POST["txtSiteURL"]);
    $var_helpDeskURL = trim($_POST["txtHelpDeskURL"]);
    $var_defaultLang = trim($_POST["cmbDefaultLang"]);
    $var_post2PostGap = trim($_POST["cmbPost2PostGap"]);
    $var_langChoice = trim($_POST["rdLangChoice"]);
    $var_autoLock = trim($_POST["rdAutoLock"]);
    $var_verifyTemplate = trim($_POST["rdVerifyTemplate"]);
    $var_verifyKB = trim($_POST["rdVerifyKB"]);
    $var_approvelog = trim($_POST["rdApproveLog"]);
    $var_emailpiping = trim($_POST["rdEmailPiping"]);
    $var_homeFooterContent = trim($_POST["txtHomeFooterContent"]);

    $var_postticketbeforeregister = trim($_POST["rdPostTicketBeforeRegister"]);

    $var_userauthentication = trim($_POST["rdUserAuthentication"]);
    $var_messagerule = trim($_POST["rdMessageRule"]);
    $var_livechat = trim($_POST["rdLiveChat"]);
    $var_ticketmail=trim($_POST["rdTicketMail"]);
    $var_theme=trim($_POST['ddlCSS']);

    $var_smtp = trim($_POST["rdSMTP"]);
    $var_smtpserver = trim($_POST["txtSMTPServer"]);
    $var_port = trim($_POST["txtSMTPPort"]);
    $var_username = trim($_POST["txtSMTPUsername"]);
    $var_password = trim($_POST["txtSMTPPassword"]);
    $var_enableSSL = trim($_POST["enableSSL"]);

    $AD_AUTHENTICATION = trim($_POST["AD_AUTHENTICATION"]);
    $AD_USER = trim($_POST["AD_USER"]);
    $AD_PASS = trim($_POST["AD_PASS"]);
    $AD_DOMAIN = trim($_POST["AD_DOMAIN"]);
    $AD_HOST = trim($_POST["AD_HOST"]);
    $AD_USER_DIR = trim($_POST["AD_USER_DIR"]);
    if($var_smtp==0) {
        $var_smtpserver = "";
        $var_port = "";
        $var_username = "";
        $var_password = "";
        
    }
    if($var_postticketbeforeregister=='0')
        $var_userauthentication = 0;

    $var_maxPost = (int)trim($_POST["txtMaxPost"]);
    $var_messageOrder = trim($_POST["rdMessageOrder"]);

    $var_maxPost = ($var_maxPost <= 0)?30:$var_maxPost;

    if($var_port !="" && !is_numeric($var_port)) {
        $var_message = MESSAGE_NON_NUMERIC;
        $display_status ="display:block";
        $flag_msg   = 'class="msg_error"';
    }
    else {
        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($AD_USER_DIR) .
                "'  where vLookUpName = 'AD_USER_DIR'";
        executeQuery($sql,$conn);
        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($AD_HOST) .
                "'  where vLookUpName = 'AD_HOST'";
        executeQuery($sql,$conn);
        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($AD_DOMAIN) .
                "'  where vLookUpName = 'AD_DOMAIN'";
        executeQuery($sql,$conn);
        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($AD_AUTHENTICATION) .
                "'  where vLookUpName = 'AD_AUTHENTICATION'";
        executeQuery($sql,$conn);
        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($AD_USER) .
                "'  where vLookUpName = 'AD_USER'";
        executeQuery($sql,$conn);
        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($AD_PASS) .
                "'  where vLookUpName = 'AD_PASS'";
        executeQuery($sql,$conn);


        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($var_siteURL) .
                "'  where vLookUpName = 'SiteURL'";
        executeQuery($sql,$conn);

        $sql = "Update sptbl_lookup set
				 vLookUpValue='" . mysql_real_escape_string($var_helpDeskURL) . "'
				 where vLookUpName = 'HelpDeskURL'";
        executeQuery($sql,$conn);

        $sql = "Update sptbl_lookup set
				 vLookUpValue='" . mysql_real_escape_string($var_defaultLang) . "'
				 where vLookUpName='DefaultLang'";

        executeQuery($sql,$conn);
        $_SESSION["sess_defaultlang"]=mysql_real_escape_string($var_defaultLang);

        $sql = "Update sptbl_lookup set
				 vLookUpValue='" . mysql_real_escape_string($var_post2PostGap) . "'
				 where vLookUpName='Post2PostGap'";
        executeQuery($sql,$conn);

        $sql = "Update sptbl_lookup set
				 vLookUpValue='" . mysql_real_escape_string($var_langChoice) . "'
				 where vLookUpName='LangChoice'";
        executeQuery($sql,$conn);

        $sql = "Update sptbl_lookup set
				 vLookUpValue='" . mysql_real_escape_string($var_autoLock) . "'
				 where vLookUpName='AutoLock'";
        executeQuery($sql,$conn);

        $sql = "Update sptbl_lookup set
				 vLookUpValue='" . mysql_real_escape_string($var_verifyTemplate) . "'
				 where vLookUpName='VerifyTemplate'";
        executeQuery($sql,$conn);

        $sql = "Update sptbl_lookup set
				 vLookUpValue='" . mysql_real_escape_string($var_verifyKB) . "'
				 where vLookUpName='VerifyKB'";
        executeQuery($sql,$conn);

        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($var_approvelog) .
                "'  where vLookUpName = 'logactivity'";
        executeQuery($sql,$conn);

        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($var_emailpiping) .
                "'  where vLookUpName = 'EmailPiping'";
        executeQuery($sql,$conn);

        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($var_postticketbeforeregister) .
                "'  where vLookUpName = 'PostTicketBeforeLogin'";
        executeQuery($sql,$conn);

        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($var_userauthentication) .
                "'  where vLookUpName = 'UserAuthenticate'";
        executeQuery($sql,$conn);

        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($var_messagerule) .
                "'  where vLookUpName = 'MessageRule'";
        executeQuery($sql,$conn);

        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($var_maxPost) .
                "'  where vLookUpName = 'MaxPostsPerPage'";
        executeQuery($sql,$conn);

        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($var_messageOrder) .
                "'  where vLookUpName = 'OldestMessageFirst'";
        executeQuery($sql,$conn);

        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($var_smtp) .
                "'  where vLookUpName = 'SMTPSettings'";
        executeQuery($sql,$conn);

        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($var_smtpserver) .
                "'  where vLookUpName = 'SMTPServer'";
        executeQuery($sql,$conn);

        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($var_port) .
                "'  where vLookUpName = 'SMTPPort'";
        executeQuery($sql,$conn);

        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($var_username) .
                "'  where vLookUpName = 'SMTPUsername'";
        executeQuery($sql,$conn);

        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($var_password) .
                "'  where vLookUpName = 'SMTPPassword'";
        executeQuery($sql,$conn);
        
        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($var_enableSSL) .
                "'  where vLookUpName = 'SMTPEnableSSL'";
        executeQuery($sql,$conn);

        
        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($var_livechat) .
                "'  where vLookUpName = 'LiveChat'";
        executeQuery($sql,$conn);
        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($var_ticketmail) .
                "'  where vLookUpName = 'NewTicketAutoReturnMail'";
        executeQuery($sql,$conn);
        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($var_theme) .
                "'  where vLookUpName = 'Theme'";
        executeQuery($sql,$conn);

        $sql = "Update sptbl_lookup set vLookUpValue='" . mysql_real_escape_string($var_homeFooterContent) .
                "'  where vLookUpName = 'HomeFooterContent'";
        executeQuery($sql,$conn);

        
        $search = "index.php";
        $pos = strpos($var_helpDeskURL, $search);
        $emailurl = substr($var_helpDeskURL,0,$pos);

        if($emailurl == "")
            $emailurl = $var_helpDeskURL;

        $sql = "Update sptbl_lookup set
				 vLookUpValue='" . mysql_real_escape_string($emailurl) . "'
				 where vLookUpName = 'EmailURL'";
        executeQuery($sql,$conn);

        //update css
        $sql = "Select vCSSURL from sptbl_css where nCSSId='".mysql_real_escape_string($var_theme)."'";
        $result = executeSelect($sql,$conn);
        if (mysql_num_rows($result) > 0) {
            $row = mysql_fetch_array($result);
            unset($_SESSION['sess_cssurl']);
            $_SESSION["sess_cssurl"] = $row["vCSSURL"];
        }
        //update css
        $_SESSION["sess_maxpostperpage"] = $var_maxPost;
        $_SESSION["sess_messageorder"] = $var_messageOrder;
        $_SESSION["sess_logactivity"] = $var_approvelog;

        $_SESSION["sess_smtpsettings"] = $var_smtp;
        $_SESSION["sess_smtpserver"] = $var_smtpserver;
        $_SESSION["sess_smtpport"] = $var_port;
        $_SESSION["sess_smtpusername"] = $var_username;
        $_SESSION["sess_smtppassword"] = $var_password;
        $_SESSION["sess_smtpenableSSL"] = $var_enableSSL;
        

        //Insert the actionlog
        if(logActivity()) {
            $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Config','0',now())";
            executeQuery($sql,$conn);
        }
        $var_message = MESSAGE_RECORD_UPDATED;
        $display_status='display_block';
        $flag_msg   = 'class="msg_success"';
    }
}
else {
    $var_message = MESSAGE_RECORD_ERROR;
    $display_status='display_block';
    $flag_msg   = 'class="msg_error"';
}
?>
<script language="javascript" type="text/javascript" src="../scripts/jipjax.js"></script>
<script language="javascript">
    function clickyes()
    {
        document . getElementById("auth1") . style . display = "";
    }

    function clickno()
    {
        document . getElementById("auth1") . style . display = "none";
    }

    function clicksmtpyes()
    {
        document . getElementById("smtp1") . style . display = "";
        document . getElementById("smtp2") . style . display = "";
        document . getElementById("smtp3") . style . display = "";
        document . getElementById("smtp4") . style . display = "";
        document . getElementById("smtp5") . style . display = "";
        document . getElementById("smtp6") . style . display = "";
        document . getElementById("smtp7") . style . display = "";
        document . getElementById("smtp8") . style . display = "";
        document . getElementById("smtp9") . style . display = "";
        document . getElementById("smtp10") . style . display = "";
    }

    function clicksmtpno()
    {
        document . getElementById("smtp1") . style . display = "none";
        document . getElementById("smtp2") . style . display = "none";
        document . getElementById("smtp3") . style . display = "none";
        document . getElementById("smtp4") . style . display = "none";
        document . getElementById("smtp5") . style . display = "none";
        document . getElementById("smtp6") . style . display = "none";
        document . getElementById("smtp7") . style . display = "none";
        document . getElementById("smtp8") . style . display = "none";
        document . getElementById("smtp9") . style . display = "none";
        document . getElementById("smtp10") . style . display = "none";
    }
    /*function clickadyes()
    {
        document . getElementById("ad1") . style . display = "";
        document . getElementById("ad2") . style . display = "";
        document . getElementById("ad3") . style . display = "";
        document . getElementById("ad4") . style . display = "";
        document . getElementById("ad5") . style . display = "";
        document . getElementById("ad6") . style . display = "";
        document . getElementById("ad7") . style . display = "";
        document . getElementById("ad8") . style . display = "";
        document . getElementById("ad9") . style . display = "";
        document . getElementById("ad10") . style . display = "";
        document . getElementById("ad11") . style . display = "";
    }
    function clickadno()
    {
        document . getElementById("ad1") . style . display = "none";
        document . getElementById("ad2") . style . display = "none";
        document . getElementById("ad3") . style . display = "none";
        document . getElementById("ad4") . style . display = "none";
        document . getElementById("ad5") . style . display = "none";
        document . getElementById("ad6") . style . display = "none";
        document . getElementById("ad7") . style . display = "none";
        document . getElementById("ad8") . style . display = "none";
        document . getElementById("ad9") . style . display = "none";
        document . getElementById("ad10") . style . display = "none";
        document . getElementById("ad11") . style . display = "none";

    }
    function chkconnect(){
        var req = newXMLHttpRequest();
        req.onreadystatechange = getReadyStateHandler(req, serverResponse);
        var str;
        str="&user="+document.frmConfig.AD_USER.value;
        str=str+"&password="+document.frmConfig.AD_PASS.value;
        str=str+"&domain="+document.frmConfig.AD_DOMAIN.value;
        str=str+"&host="+document.frmConfig.AD_HOST.value;
        req.open("POST", "testconnajax.php");
        req.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
        req.send(str);
    }*/
    function serverResponse(_var) {

        document.getElementById('divconn').innerHTML =_var;
    }
</script>

<form name="frmConfig" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>">
    <div class="content_section">
        <div class="content_section_title">
            <h3> <?php echo TEXT_EDIT_CONFIG ?></h3>
        </div>

        <table width="100%"  border="0">
            <tr>
                <td width="76%" valign="top">
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td colspan="3" height="60px" valign="top"><?php if($var_message != '') { ?>
                                <div <?php echo $flag_msg; ?> style="width:850px; margin:0 auto; <?php echo $display_status?>"><?php echo $var_message ?></div>
                                    <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td align="left" colspan=2 class="fieldnames">
                                <?php echo TEXT_FIELDS_MANDATORY ?></td>
                        </tr>

                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames">
                                <?php echo TEXT_CON_SITEURL?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font>
                            </td>
                            <td width="55%" align="left">
                                <input name="txtSiteURL" type="text" class="comm_input txt-bx01" id="txtSiteURL" size="30" maxlength="100" value="<?php echo htmlentities($var_siteURL); ?>">
                                <span id="set1">
                                    <a style="cursor: pointer;" title="<?php echo HELP_SITE_URL; ?>">
                                        <img src="../images/tooltip.jpg">
                                    </a>
                                </span>
                            </td>
                        </tr>

                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_CON_HELPDESKURL?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left">
                                <input name="txtHelpDeskURL" type="text" class="comm_input input_width1a txt-bx01" id="txtHelpDeskURL" size="30" maxlength="100" value="<?php echo htmlentities($var_helpDeskURL); ?>">
                            </td>
                        </tr>

                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_CON_MAX_POSTS?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left">
                                <input name="txtMaxPost" type="text" class="comm_input  txt-bx01" id="txtMaxPost" size="10" maxlength="3" value="<?php echo htmlentities($var_maxPost); ?>" >
                            </td>
                        </tr>

                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_CON_POST2POSTGAP?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left" class="listing">
                                <select name="cmbPost2PostGap" class="comm_input input_width1a slct-style">
                                    <option value="0" <?php echo(($var_post2PostGap == "0")?"Selected":"");?>>0 <?php echo TEXT_MINUTE?></option>
                                    <option value="1" <?php echo(($var_post2PostGap == "1")?"Selected":"");?>>1 <?php echo TEXT_MINUTE?></option>
                                    <option value="2" <?php echo(($var_post2PostGap == "2" || $var_post2PostGap == "")?"Selected":"");?>>2 <?php echo TEXT_MINUTES?></option>
                                    <option value="3" <?php echo(($var_post2PostGap == "3")?"Selected":"");?>>3 <?php echo TEXT_MINUTES?></option>
                                    <option value="4" <?php echo(($var_post2PostGap == "4")?"Selected":"");?>>4 <?php echo TEXT_MINUTES?></option>
                                    <option value="5" <?php echo(($var_post2PostGap == "5")?"Selected":"");?>>5 <?php echo TEXT_MINUTES?></option>
                                    <option value="6" <?php echo(($var_post2PostGap == "6")?"Selected":"");?>>6 <?php echo TEXT_MINUTES?></option>
                                    <option value="7" <?php echo(($var_post2PostGap == "7")?"Selected":"");?>>7 <?php echo TEXT_MINUTES?></option>
                                    <option value="8" <?php echo(($var_post2PostGap == "8")?"Selected":"");?>>8 <?php echo TEXT_MINUTES?></option>
                                    <option value="9" <?php echo(($var_post2PostGap == "9")?"Selected":"");?>>9 <?php echo TEXT_MINUTES?></option>
                                    <option value="10" <?php echo(($var_post2PostGap == "10")?"Selected":"");?>>10 <?php echo TEXT_MINUTES?></option>
                                </select>
                            </td>
                        </tr>

                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_CON_DEFAULTLANG?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left">

                                <?php

                                $sql = "SELECT vLangCode,vLangDesc  FROM `sptbl_lang` order by vLangDesc";
                                $rs = executeSelect($sql,$conn);
                                ?>
                                <select name="cmbDefaultLang" size="1" class="comm_input input_width1a slct-style" id="cmbDefaultLang" >
                                    <?php
                                    $options ="<option value='0'";
                                    $options .=">Select</option>\n";
                                    while($row = mysql_fetch_array($rs)) {
                                        $options ="<option value='".$row['vLangCode']."'";
                                        if ($var_defaultLang == $row['vLangCode']) {
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
                        <tr>
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_CON_ENABLELANGCHOICE?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left"  class="fieldnames">
                                <input name="rdLangChoice" type="radio" value="1"
                                       <?php echo(($var_langChoice == 1)?"checked":""); ?>><?php echo(TEXT_YES); ?>
                                <input name="rdLangChoice" type="radio" value="0"
                                       <?php echo(($var_langChoice == 0)?"checked":""); ?>>
                                <?php echo(TEXT_NO); ?></td>
                        </tr>

                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_CON_MESSAGEORDER?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left"  class="fieldnames">
                                <input name="rdMessageOrder" type="radio" value="1"
                                       <?php echo(($var_messageOrder == 1)?"checked":""); ?>><?php echo(TEXT_OLDEST_MESSAGE); ?>
                                <input name="rdMessageOrder" type="radio" value="0"
                                       <?php echo(($var_messageOrder == 0)?"checked":""); ?>>
                                <?php echo(TEXT_NEWEST_MESSAGE); ?></td>
                        </tr>

                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_CON_AUTOMATICLOCKING?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left"  class="fieldnames">
                                <input name="rdAutoLock" type="radio" value="1"
                                       <?php echo(($var_autoLock == 1)?"checked":""); ?>><?php echo(TEXT_YES); ?>
                                <input name="rdAutoLock" type="radio" value="0"
                                       <?php echo(($var_autoLock == 0)?"checked":""); ?>>
                                <?php echo(TEXT_NO); ?></td>
                        </tr>

                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_CON_VARIFYTEMPLATE?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left"  class="fieldnames">
                                <input name="rdVerifyTemplate" type="radio" value="0"
                                       <?php echo(($var_verifyTemplate == 0)?"checked":""); ?>><?php echo(TEXT_YES); ?>
                                <input name="rdVerifyTemplate" type="radio" value="1"
                                       <?php echo(($var_verifyTemplate == 1)?"checked":""); ?>>
                                <?php echo(TEXT_NO); ?></td>
                        </tr>

                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_CON_VARIFYKB?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left"  class="fieldnames">
                                <input name="rdVerifyKB" type="radio" value="1"
                                       <?php echo(($var_verifyKB == 1)?"checked":""); ?>><?php echo(TEXT_YES); ?>
                                <input name="rdVerifyKB" type="radio" value="0"
                                       <?php echo(($var_verifyKB == 0)?"checked":""); ?>>
                                <?php echo(TEXT_NO); ?></td>
                        </tr>

                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_CON_APPROVELOG?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left"  class="fieldnames">
                                <input name="rdApproveLog" type="radio" value="1"
                                       <?php echo(($var_approvelog == 1)?"checked":""); ?>><?php echo(TEXT_YES); ?>
                                <input name="rdApproveLog" type="radio" value="0"
                                       <?php echo(($var_approvelog == 0)?"checked":""); ?>>
                                <?php echo(TEXT_NO); ?></td>
                        </tr>

                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_EMAIL_PIPING?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left"  class="fieldnames">
                                <input name="rdEmailPiping" type="radio" value="1"
                                       <?php echo(($var_emailpiping == 1)?"checked":""); ?>><?php echo(TEXT_ON); ?>
                                <input name="rdEmailPiping" type="radio" value="0"
                                       <?php echo(($var_emailpiping == 0)?"checked":""); ?>>
                                <?php echo(TEXT_OFF); ?></td>
                        </tr>
                        <?php /* modified by roshith on 06-11-2006 */ ?>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_POST_TICKET_BEFORE_REGISTER?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left"  class="fieldnames">
                                <input name="rdPostTicketBeforeRegister" type="radio" value="1" onClick="clickyes();"
                                       <?php echo(($var_postticketbeforeregister == 1)?"checked":""); ?>><?php echo(TEXT_YES); ?>
                                <input name="rdPostTicketBeforeRegister" type="radio" value="0" onClick="clickno();"
                                       <?php echo(($var_postticketbeforeregister == 0)?"checked":""); ?>>
                                <?php echo(TEXT_NO); ?></td>
                        </tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr id="auth1">
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_USER_AUTHENTICATION?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left"  class="fieldnames">
                                <input name="rdUserAuthentication" type="radio" value="1"
                                       <?php echo(($var_userauthentication == 1)?"checked":""); ?>><?php echo(TEXT_YES); ?>
                                <input name="rdUserAuthentication" type="radio" value="0"
                                       <?php echo(($var_userauthentication == 0)?"checked":""); ?>>
                                       <?php echo(TEXT_NO); ?>
                            </td>
                        </tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_MESSAGE_RULE?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left"  class="fieldnames">
                                <input name="rdMessageRule" type="radio" value="1"
                                       <?php echo(($var_messagerule == 1)?"checked":""); ?>><?php echo(TEXT_YES); ?>
                                <input name="rdMessageRule" type="radio" value="0"
                                       <?php echo(($var_messagerule == 0)?"checked":""); ?>>
                                <?php echo(TEXT_NO); ?></td>
                        </tr>
                        <!--Newly Added by Amaldev-->
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_LIVECHAT_ENABLE?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left"  class="fieldnames">
                                <input name="rdLiveChat" type="radio" value="1"
                                       <?php echo(($var_livechat == 1)?"checked":""); ?>><?php echo(TEXT_YES); ?>
                                <input name="rdLiveChat" type="radio" value="0"
                                       <?php echo(($var_livechat == 0)?"checked":""); ?>>
                                       <?php echo(TEXT_NO); ?>
                            </td>
                        </tr>
                        <!--Newly added by Amaldev Ends-->

                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_SMTP_SETTING?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left"  class="fieldnames">
                                <input name="rdSMTP" type="radio" value="1" onClick="clicksmtpyes();"
                                       <?php echo(($var_smtp == 1)?"checked":""); ?>><?php echo(TEXT_YES); ?>
                                <input name="rdSMTP" type="radio" value="0" onClick="clicksmtpno();"
                                       <?php echo(($var_smtp == 0)?"checked":""); ?>><?php echo(TEXT_NO); ?>
                            </td>
                        </tr>
                        
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr id="smtp1">
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_SMTP_SERVER?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left"  class="listing"><input type="text" name="txtSMTPServer" class="comm_input input_width1" value="<?php echo $var_smtpserver; ?>"></td>
                        </tr>
                        <tr id="smtp2">
                            <td colspan="3">&nbsp;</td>
                        </tr>
                        <tr id="smtp3">
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_SMTP_PORT?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left"  class="listing"><input type="text" name="txtSMTPPort" class="comm_input input_width1" style="width:30px" value="<?php echo htmlentities($var_port); ?>" maxlength="4"></td>
                        </tr>
                        <tr id="smtp4"><td colspan="3">&nbsp;</td></tr>
                        <tr id="smtp5">
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_SMTP_USER?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left"  class="listing"><input type="text" name="txtSMTPUsername" class="comm_input input_width1" value="<?php echo $var_username; ?>"></td>
                        </tr>
                        <tr id="smtp6"><td colspan="3">&nbsp;</td></tr>
                        <tr id="smtp7">
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_SMTP_PASSWORD?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left"  class="listing"><input type="password" name="txtSMTPPassword" class="comm_input input_width1" value="<?php echo $var_password; ?>"></td>
                        </tr>
                        <tr id="smtp8"><td colspan="3">&nbsp;</td></tr>
                        <tr id="smtp9">
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_SMTP_ENABLE_SSL?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left"  class="fieldnames">
                                <input name="enableSSL" type="radio" value="1"
                                       <?php echo(($var_enableSSL == 1)?"checked":""); ?>><?php echo(TEXT_YES); ?>
                                <input name="enableSSL" type="radio" value="0" 
                                       <?php echo(($var_enableSSL == 0)?"checked":""); ?>><?php echo(TEXT_NO); ?>
                            </td>
                        </tr>
                        <tr id="smtp10"><td colspan="3">&nbsp;</td></tr>
                        <!-- Section For LDAP -------------------------------------------->

                        <!--Auto return mail------------------>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_TICKET_AUTO_RETURN_MAIL?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left"  class="fieldnames">
                                <input name="rdTicketMail" type="radio" value="1"
                                       <?php echo(($var_ticketmail == 1)?"checked":""); ?>><?php echo(TEXT_YES); ?>
                                <input name="rdTicketMail" type="radio" value="0"
                                       <?php echo(($var_ticketmail == 0)?"checked":""); ?>><?php echo(TEXT_NO); ?>
                            </td>
                        </tr>
                        <!--Auto return mail------------------>
                        
                        <!----- Theme setting----------------------->

                        <!-- <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_SET_THEME?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left"  class="fieldnames">
                                <?php echo makeDropDownList("ddlCSS",getCSSList(),$var_theme, "comm_input input_width1 slct-style", $properties, "", true ); ?>
                            </td>
                        </tr>
                        <tr><td colspan="3">&nbsp;</td></tr>
                        
                        <tr>
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames">
                                <?php echo TEXT_HOME_FOOTER_CONTENT;?>&nbsp;
                            </td>
                            <td width="55%" align="left">
                                <input name="txtHomeFooterContent" type="text" class="comm_input txt-bx01" id="txtHomeFooterContent" size="30" maxlength="100" value="<?php echo htmlentities($var_homeFooterContent); ?>">
                                <span id="set1">
                                    <a style="cursor: pointer;" title="Content that shows in the footer part of the home page">
                                        <img src="../images/tooltip.jpg">
                                    </a>
                                </span>
                            </td>
                        </tr>
                        
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr>
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_AD_SETTING?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
                            <td width="55%" align="left"  class="fieldnames">
                                <input name="AD_AUTHENTICATION" type="radio" value="Y" onClick="clickadyes();"
                                       <?php echo(($AD_AUTHENTICATION == "Y")?"checked":""); ?>><?php echo(TEXT_YES); ?>
                                <input name="AD_AUTHENTICATION" type="radio" value="N" onClick="clickadno();"
                                       <?php echo(($AD_AUTHENTICATION == "N")?"checked":""); ?>><?php echo(TEXT_NO); ?>
                            </td>
                        </tr>
                        
                        <tr><td colspan="3">&nbsp;</td></tr>
                        <tr id="ad1">
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames">&nbsp;</td>
                            <td width="55%" align="left"  class="listing"><font style="color:#FF0000; font-size:9px"><?php echo MESSAGE_AD_LDAP?></font></td>
                        </tr>
                        <tr id="ad2">
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_AD_USER?>&nbsp;</td>
                            <td width="55%" align="left"  class="listing"><input type="text" name="AD_USER" class="textbox" value="<?php echo $AD_USER; ?>"></td>
                        </tr>
                        <tr id="ad3">
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames">&nbsp;</td>
                            <td width="55%" align="left"  class="listing"><font style="color:#FF0000; font-size:9px"><?php echo MESSAGE_AD_USR?></font></td>
                        </tr>
                        <tr id="ad4">
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_AD_PASS?>&nbsp;</td>
                            <td width="55%" align="left"  class="listing"><input type="text" name="AD_PASS" class="textbox" value="<?php echo htmlentities($AD_PASS); ?>"></td>
                        </tr>
                        <tr id="ad5">
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_AD_DOMAIN?>&nbsp;</td>
                            <td width="55%" align="left"  class="listing"><input type="text" name="AD_DOMAIN" class="textbox" value="<?php echo htmlentities($AD_DOMAIN); ?>"></td>
                        </tr>
                        <tr id="ad6">
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames">&nbsp;</td>
                            <td width="55%" align="left"  class="listing"><font style="color:#FF0000; font-size:9px"><?php echo MESSAGE_AD_DOMAIN?></font></td>
                        </tr>
                        <tr id="ad7">
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_AD_HOST?>&nbsp;</td>
                            <td width="55%" align="left"  class="listing"><input type="text" name="AD_HOST" class="textbox" value="<?php echo htmlentities($AD_HOST); ?>"></td>
                        </tr>
                        <tr id="ad8">
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames">&nbsp;</td>
                            <td width="55%" align="left"  class="listing"><font style="color:#FF0000; font-size:9px"><?php echo MESSAGE_AD_HOST?></font></td>
                        </tr>
                        <tr id="ad9">
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_AD_USRDIR?>&nbsp;</td>
                            <td width="55%" align="left"  class="listing"><input type="text" name="AD_USER_DIR" class="textbox" value="<?php echo htmlentities($AD_USER_DIR); ?>"></td>
                        </tr>
                        <tr id="ad10">
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames">&nbsp;</td>
                            <td width="55%" align="left"  class="listing"><font style="color:#FF0000; font-size:9px"><?php echo MESSAGE_AD_USDIR?></font></td>
                        </tr>
                        <tr id="ad14">
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo SITE_LICENSEKEY; ?>&nbsp;</td>
                            <td width="55%" align="left"  class="listing"><?php echo $_SESSION["sess_licensekey"];?></td>
                        </tr>

                        <tr><td colspan="3">&nbsp;</td></tr>

                        <tr id="ad12">
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><?php echo TEXT_INSTALLED_VERSION; ?>&nbsp;</td>
                            <td width="55%" align="left"  class="listing"><?php echo $var_version; ?></td>
                        </tr>

                        <tr><td colspan="3">&nbsp;</td></tr>

                        <tr id="ad11">
                            <td width="6%" align="left">&nbsp;</td>
                            <td width="39%" align="left" class="fieldnames"><input type="button" value="<?php echo BTN_TSTCON?>" class="button" onClick="chkconnect();"></td>
                            <td width="55%" align="left"  class="listing"><div id="divconn"></div></td>
                        </tr>
                        <tr><td colspan="3" class="btm_brdr">&nbsp;</td></tr> -->
                        <!-- Section For LDAP -------------------------------------------->
                    </table>



                    <table width="100%"  border="0" cellspacing="10" cellpadding="0">
                        <tr>
                            <td>
                                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                    <tr >
                                        <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                                        <td ><table width="100%"  border="0" cellspacing="0" cellpadding="5" >
                                                <tr>
                                                    <td>

                                                        <table width="100%"  border="0" cellspacing="0" cellpadding="0" >
                                                            <tr align=""  class="listingbtnbar">
                                                                <td width="20%">&nbsp;</td>
                                                                <td width="10%"></td>
                                                                <td width="22%" align=right><input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT; ?>"  onClick="javascript:edit();"></td>
                                                                <td width="1px">&nbsp;</td>
                                                                <!--td width="20%"><input name="btCancel" type="reset" class="comm_btn" value="<?php //echo BUTTON_TEXT_CANCEL; ?>"></td--> <!-- onClick="javascript:cancel();" -->
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
                            </td>
                        </tr>
                    </table>


            </tr>
        </table>
    </div>
</form>
<?php if($var_postticketbeforeregister=="0") {
    ?>
<script language="javascript">
    document . getElementById("auth1") . style . display = "none";
</script>
    <?php }?>

<?php if($var_smtp=="0") {
    ?>
<script language="javascript">
    document . getElementById("smtp1") . style . display = "none";
    document . getElementById("smtp2") . style . display = "none";
    document . getElementById("smtp3") . style . display = "none";
    document . getElementById("smtp4") . style . display = "none";
    document . getElementById("smtp5") . style . display = "none";
    document . getElementById("smtp6") . style . display = "none";
    document . getElementById("smtp7") . style . display = "none";
    document . getElementById("smtp8") . style . display = "none";
    document . getElementById("smtp9") . style . display = "none";
    document . getElementById("smtp10") . style . display = "none";
</script>
    <?php }?>
<?php if($AD_AUTHENTICATION == "Y") {
    ?>
<script language="javascript">
    //clickadyes();
</script>
    <?php }else {?>
<script language="javascript">
    //clickadno();
</script>
    <?php } ?>