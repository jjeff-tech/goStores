<style>
    /* use a semi-transparent image for the overlay */
    #overlay {
        background-image:url(images/transparent.png);
        color:#ffffff;
        height:450px;
    }
    /* container for external content. uses vertical scrollbar, if needed */
    div.contentWrap {
        height:441px;
        overflow-y:auto;
    }

    /* the overlayed element */
    .apple_overlay {

        /* initially overlay is hidden */
        display:none;

        /* growing background image */
        background-image:url(images/white.png);

        /*
          width after the growing animation finishes
          height is automatically calculated
        */
        width:640px;

        /* some padding to layout nested elements nicely  */
        padding:35px;

        /* a little styling */
        font-size:11px;
    }

    /* default close button positioned on upper right corner */
    .apple_overlay .close {
        background-image:url(images/close.png);
        position:absolute; right:5px; top:5px;
        cursor:pointer;
        height:35px;
        width:35px;
    }
</style>
<script src="./scripts/jquery.tools.min.js"></script>
<script language="javascript" src="<?php echo SITE_URL; ?>scripts/kb.js"></script>
<script language="javascript" >
    function validateKbKey()
    {
        var base_url = '<?php echo SITE_URL; ?>';
        var txtSearch = $("#txtKbTitleSearch").val();
        frmkbSearch.action =base_url+'kb/search?q='+urlencode(txtSearch);
        return true;
    }

</script>
<script type="text/javascript">
    $(document).ready(function () { 

        $("#jQStatusDisplayButton").live("click",function(){
            $("#jQStatusCheckDiv").show();
            $("#jQStatusContentDiv").hide();
            $("#jQStatusDisplayDiv").hide();
        });


        $('#GetPass').hide();
        //       $("input").keypress(function(event) {
        //       if (event.which == 13) {
        //          checkLoginForm();
        //       }
        //   });
        //alert('asdasd');
        //$('#clsTicket').hide();


    });
</script>
<script type="text/javascript">
    function redr()
    {
        window.location="<?php echo SITE_URL ; ?>getrefinfo.php";

    }
    function registration()
    {
        window.location="<?php echo SITE_URL ; ?>register.php";

    }

</script>
<?php

include "languages/".$_SP_language."/loginbox.php";
include("./languages/$_SP_language/showticket.php");

require_once("includes/decode.php");

include "languages/".$_SP_language."/userkbsearck.php";


/*...........Section for active directoryy variables......................*/
$sql = "Select vLookUpName,vLookUpValue from sptbl_lookup ";
$var_result = executeSelect($sql,$conn);
if (mysql_num_rows($var_result) > 0) {
    while($var_row = mysql_fetch_array($var_result)) {

        $var_lookupName=$var_row["vLookUpName"];

        switch ($var_lookupName) {

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
            case "PostTicketBeforeLogin":
                $PostTicketBeforeLogin =  $var_row["vLookUpValue"];
                break;
            case "HomeFooterContent":
                $HomeFooterContent =  $var_row["vLookUpValue"];
                break;
        }
    }
}
/*...........Section for active directoryy variables......................*/
if(!isValid(0)) {
    echo("<script>window.location.href='invalidkey.php'</script>");
    exit();
}
$sql = "Select vLookUpName,vLookUpValue from sptbl_lookup WHERE vLookUpName IN('LangChoice',
                                'DefaultLang','HelpdeskTitle','Logourl','logactivity','MaxPostsPerPage','OldestMessageFirst')";
$rs = executeSelect($sql,$conn);

if (mysql_num_rows($rs) > 0) {
    while($row = mysql_fetch_array($rs)) {
        switch($row["vLookUpName"]) {
            case "LangChoice":
                $_SESSION["sess_langchoice"] = $row["vLookUpValue"];
                break;
            case "DefaultLang":
                $_SESSION["sess_defaultlang"] = $row["vLookUpValue"];
                break;
            case "HelpdeskTitle":
                $_SESSION["sess_helpdesktitle"] = $row["vLookUpValue"];
                break;
            case "Logourl":
                $_SESSION["sess_logourl"] = $row["vLookUpValue"];
                break;
            case "logactivity":    //this session variable decides to log activities or not
                $_SESSION["sess_logactivity"] = $row["vLookUpValue"];
                break;
            case "MaxPostsPerPage":
                $_SESSION["sess_maxpostperpage"] = $row["vLookUpValue"];
                break;
            case "OldestMessageFirst":
                $_SESSION["sess_messageorder"] = $row["vLookUpValue"];
                break;
        }
    }
}
mysql_free_result($rs);

if($_SESSION["sess_userlangchange"] =="1") {
    ;
}else {

    if (($_SESSION["sess_defaultlang"] !=$_SESSION["sess_language"])) {
        $_SESSION["sess_language"] = $_SESSION["sess_defaultlang"];
        echo("<script>window.location.href='index.php'</script>");
        exit();

        //header("location:index.php");
        //exit;
    }
}




if(isset($_POST["postback"]) && $_POST["postback"]=='Login') {

    if($AD_AUTHENTICATION == "Y") {
        $error = false;
        $errormessage = "" ;
        if(isNotNull($_POST["txtUserID"])) {
            $loginname = trim($_POST["txtUserID"]);
            $username = $AD_USER;
            $password = $AD_PASS;
            $domain = $AD_DOMAIN;
            $domain_username  = $username . "@" . $domain;
            $bdn	=	explode(".",$AD_DOMAIN);
            $bdr	=	"";
            for($i=0;$i<sizeof($bdn);$i++) {
                $bdr	.=	"DC=".$bdn[$i];
                if($i != (sizeof($bdn)-1)) {
                    $bdr	.= ",";
                }
            }
            $user_dir = $AD_USER_DIR.",".$bdr;
            $ldap_server = $AD_HOST;
            $ldap_conn = ldap_connect($ldap_server);
            ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3) or die ("Could not set LDAP Protocol version");
            if($ldapbind = ldap_bind($ldap_conn, $domain_username, $password) == true) {
                $attributes = array ("mail" , "name");
                $filter = "(&(objectclass=user) (objectcategory=Person)(sAMAccountName=".$loginname."))";
                $search_result = ldap_search( $ldap_conn, $user_dir, $filter, $attributes ) or die ("LDAP search has failed");
                $info = ldap_get_entries( $ldap_conn, $search_result );
                if($info["count"] > 0) {
                    @ldap_unbind($ldap_conn);
                    @ldap_close($ldap_conn);
                }else {
                    $error = true;
                    $errormessage = MESSAGE_INVALID_LOGIN;
                }
            }
            @ldap_unbind($ldap_conn);
            @ldap_close($ldap_conn);
            if($error != true) {
                $sql  = "SELECT u.nUserId ,u.nCompId, u.vUserName , u.vEmail , u.vLogin , u.vBanned, u.vPassword,u.nCSSId, u.vDelStatus FROM sptbl_users u  ";
                $sql .= " WHERE vLogin = '".mysql_real_escape_string($loginname)."' ";
                // $sql .= " WHERE vLogin = '".mysql_real_escape_string($loginname)."' and  vPassword ='".mysql_real_escape_string(md5($password))."' and vDelStatus='0' ";

                $result = executeSelect($sql,$conn);
                if (mysql_num_rows($result) > 0) {
                    $row = mysql_fetch_array($result);
                    if ($row["vBanned"] == "0" && $row["vDelStatus"] == "0") {
                        $userid   = $row["nUserId"];
                        $username = $row["vLogin"];
                        $useremail = $row["vEmail"];
                        $userfullname = $row["vUserName"];
                        $compid = $row["nCompId"];
                        //$cssurl = $row["vCSSURL"];
                        echo $_SESSION["sess_cssurl"] 		= getCurrentThemeUrl();
                        $_SESSION["sess_username"] 		= $username;
                        $_SESSION["sess_userid"] 		= $userid;
                        $_SESSION["sess_useremail"] 	= $useremail;
                        $_SESSION["sess_userfullname"] 	= $userfullname;
                        $_SESSION["sess_usercompid"]	= $compid;
                        $sql1  = "UPDATE sptbl_users  ";
                        $sql1 .= " SET vOnline = '1' WHERE nUserId = '".$userid."' ";
                        $result1 = executeSelect($sql1,$conn);
                        $sql2  = "Select nPriorityValue,vTicketColor,vPriorityDesc from sptbl_priorities ORDER BY nPriorityValue ";
                        $rs2 = executeSelect($sql2,$conn);
                        if (mysql_num_rows($rs2) > 0) {
                            $cnt = 0;
                            while($row2 = mysql_fetch_array($rs2)) {
                                $fld_prio[$cnt][0] = $row2["nPriorityValue"];
                                $fld_prio[$cnt][1] = $row2["vTicketColor"];
                                $fld_prio[$cnt][2] = $row2["vPriorityDesc"];
                                $cnt++;
                            }
                        }
                        $_SESSION["sess_priority"] = $fld_prio;
                        mysql_free_result($rs2);
                        echo "<script>window.location.href='index.php';</script>";
                        exit;
                    }else {
                        if ($row["vBanned"] != "0") {
                            $error = true;
                            $errormessage = MESSAGE_USER_BANNED;
                        }
                        if ($row["vDelStatus"] == "1") {
                            $error = true;
                            $errormessage = MESSAGE_USER_DEACTIVE;
                        }
                        if ($row["vDelStatus"] == "2") {
                            $error = true;
                            $errormessage = MESSAGE_USER_DELETED;
                        }
                    }


                }else {
                    $var_userName = $info[0]["name"][0];
                    $var_userLogin = $loginname;
                    $var_password = $password;
                    $var_online = "";
                    if(isset($info[0]["mail"][0]) && $info[0]["mail"][0] <>" ") {
                        $var_email  = $info[0]["mail"][0];
                    }else {
                        $var_email  = "user@activedirectory.com";
                    }
                    $var_banned = "0";
                    $sql	=	"SELECT * FROM sptbl_companies WHERE vDelStatus = '0' LIMIT 0 , 1";
                    $result = executeSelect($sql,$conn);
                    if (mysql_num_rows($result) > 0) {
                        $row = mysql_fetch_array($result);
                        $var_compId = $row["nCompId"];
                    }else {
                        $var_compId = 1;
                    }
                    $var_date   = date("m-d-Y h:i:s");
                    $var_active = "0";
                    $sql = "INSERT INTO sptbl_users set vUserName='" . mysql_real_escape_string($var_userName) . "',
						" . (($var_password != "")?("vPassword='" . md5($var_password) .  "',"):"") .
                            "vEmail='" . mysql_real_escape_string($var_email) . "',
						vLogin='".mysql_real_escape_string($var_userLogin)."',
						nCompId='" . mysql_real_escape_string($var_compId) . "',
						vBanned='" . mysql_real_escape_string($var_banned) . "',
						vDelStatus='" . mysql_real_escape_string($var_active) . "'";
                    executeQuery($sql,$conn);
                    $sql  = "SELECT u.nUserId ,u.nCompId, u.vUserName , u.vEmail , u.vLogin , u.vBanned, u.vPassword,u.nCSSId,c.vCSSURL FROM sptbl_users u left outer join sptbl_css c on u.nCSSId = c.nCSSID   ";
                    $sql .= " WHERE vLogin = '".mysql_real_escape_string($loginname)."' and vDelStatus='0' ";
                    // $sql .= " WHERE vLogin = '".mysql_real_escape_string($loginname)."' and  vPassword ='".mysql_real_escape_string(md5($password))."' and vDelStatus='0' ";
                    $result = executeSelect($sql,$conn);
                    if (mysql_num_rows($result) > 0) {
                        $row = mysql_fetch_array($result);
                        if ($row["vBanned"] == "0") {
                            $userid   = $row["nUserId"];
                            $username = $row["vLogin"];
                            $useremail = $row["vEmail"];
                            $userfullname = $row["vUserName"];
                            $compid = $row["nCompId"];
                            $cssurl = $row["vCSSURL"];
                            $_SESSION["sess_cssurl"] 		= getCurrentThemeUrl();
                            $_SESSION["sess_username"] 		= $username;
                            $_SESSION["sess_userid"] 		= $userid;
                            $_SESSION["sess_useremail"] 	= $useremail;
                            $_SESSION["sess_userfullname"] 	= $userfullname;
                            $_SESSION["sess_usercompid"]	= $compid;

                            $sql1  = "UPDATE sptbl_users  ";
                            $sql1 .= " SET vOnline = '1' WHERE nUserId = '".$userid."' ";
                            $result1 = executeSelect($sql1,$conn);

                            $sql2  = "Select nPriorityValue,vTicketColor,vPriorityDesc from sptbl_priorities ORDER BY nPriorityValue ";
                            $rs2 = executeSelect($sql2,$conn);

                            if (mysql_num_rows($rs2) > 0) {
                                $cnt = 0;
                                while($row2 = mysql_fetch_array($rs2)) {
                                    $fld_prio[$cnt][0] = $row2["nPriorityValue"];
                                    $fld_prio[$cnt][1] = $row2["vTicketColor"];
                                    $fld_prio[$cnt][2] = $row2["vPriorityDesc"];
                                    $cnt++;
                                }
                            }
                            $_SESSION["sess_priority"] = $fld_prio;
                            mysql_free_result($rs2);

                            //header("Location: index.php");
                            $location = ($backUrl)?$backUrl:"mainpage.php";
                            echo "<script>window.location.href='$location';</script>";
                            exit;

                        }
                    }
                }
            }
        }else {//user name null
            $error = true;
            $errormessage .= MESSAGE_USER_ID_REQUIRED . "<br>";
        }
    }else { // Active directory not require...
        $error = false;
        $errormessage = "" ;
        if(isNotNull($_POST["txtUserID"])) {
            $loginname = trim($_POST["txtUserID"]);
        }else {//user name null
            $error = true;
            $errormessage .= MESSAGE_USER_ID_REQUIRED . "<br>";
        }
        if(isNotNull($_POST["txtPassword"])) {
            $password = $_POST["txtPassword"];
        }else {//user name null
            $error = true;
            $errormessage .= MESSAGE_PASSWORD_REQUIRED . "<br>";
        }

        if($error) {
            $errormessage = MESSAGE_ERRORS_FOUND . "<br>" .$errormessage;
        }else {//no error so validate


            $sql  = "SELECT u.nUserId ,u.nCompId, u.vUserName , u.vEmail , u.vLogin , u.vBanned, u.vPassword,u.nCSSId, u.vDelStatus FROM sptbl_users u   ";
            $sql .= " WHERE vLogin = '".mysql_real_escape_string($loginname)."' and  vPassword ='".mysql_real_escape_string(md5($password))."'";
            $result = executeSelect($sql,$conn);
            if (mysql_num_rows($result) > 0) {
                $row = mysql_fetch_array($result);
                if ($row["vBanned"] == "0" && $row["vDelStatus"] == "0") {
                    $userid   = $row["nUserId"];
                    $username = $row["vLogin"];
                    $useremail = $row["vEmail"];
                    $userfullname = $row["vUserName"];
                    $compid = $row["nCompId"];
                    //$cssurl = $row["vCSSURL"];
                    $_SESSION["sess_cssurl"] 		= getCurrentThemeUrl();
                    $_SESSION["sess_username"] 		= $username;
                    $_SESSION["sess_userid"] 		= $userid;
                    $_SESSION["sess_useremail"] 	= $useremail;
                    $_SESSION["sess_userfullname"] 	= $userfullname;
                    $_SESSION["sess_usercompid"]	= $compid;

                    $sql1  = "UPDATE sptbl_users  ";
                    $sql1 .= " SET vOnline = '1' WHERE nUserId = '".$userid."' ";
                    $result1 = executeSelect($sql1,$conn);

                    $sql2  = "Select nPriorityValue,vTicketColor,vPriorityDesc from sptbl_priorities ORDER BY nPriorityValue ";
                    $rs2 = executeSelect($sql2,$conn);

                    if (mysql_num_rows($rs2) > 0) {
                        $cnt = 0;
                        while($row2 = mysql_fetch_array($rs2)) {
                            $fld_prio[$cnt][0] = $row2["nPriorityValue"];
                            $fld_prio[$cnt][1] = $row2["vTicketColor"];
                            $fld_prio[$cnt][2] = $row2["vPriorityDesc"];
                            $cnt++;
                        }
                    }
                    $_SESSION["sess_priority"] = $fld_prio;
                    mysql_free_result($rs2);
                    $location = ($backUrl)?$backUrl:"mainpage.php";

                    //header("Location: index.php");
                    echo "<script>window.location.href='$location';</script>";
                    exit;
                }
                else {
                    if ($row["vBanned"] != "0") {
                        $error = true;
                        $errormessage = MESSAGE_USER_BANNED;
                    }
                    if ($row["vDelStatus"] == "1") {
                        $error = true;
                        $errormessage = MESSAGE_USER_DEACTIVE;
                    }
                    if ($row["vDelStatus"] == "2") {
                        $error = true;
                        $errormessage = MESSAGE_USER_DELETED;
                    }
                }

            }else {
                $error = true;
                $errormessage = MESSAGE_INVALID_LOGIN;
            }
        }
    }

}else if(isset($_POST["postback"]) &&  $_POST["postback"]== "Get Password") {
    $error = false;
    $passworderrormessage = "" ;
    if(isNotNull($_POST["txtUserEmail"])) {
        $useremail = trim($_POST["txtUserEmail"]);
    }else {//user email null
        $error = true;
        $passworderrormessage .= MESSAGE_USER_EMAIL_REQUIRED . "<br>";
    }
    if($error) {
        $passworderrormessage = MESSAGE_ERRORS_FOUND . "<br>" .$passworderrormessage;
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
//								echo $sql;
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

// Setting Default Values for Text Filed // Done By Asha

if($_POST["txtUserID"]!='') {
    $txtUserId = $_POST["txtUserID"];
}
else {
    $txtUserId = TEXT_USER_ID;
}

if($_POST["txtPassword"]!='') {
    $txtPassword =$_POST["txtPassword"];
}else {
    $txtPassword =  TEXT_PASSWORD;
}



if($passworderrormessage!='') {

    $txtforgot = '';
}


else {
    $txtforgot ='none';
}


if($_POST['txtEmail']!='') {

    $txtEmail = $_POST['txtEmail'];
}
else {
    $txtEmail =TEXT_EMAIL;
}

if($_POST['txtTicketRef']!='') {

    $txtTicketRef   = $_POST['txtTicketRef'];
}
else {
    $txtTicketRef   = TEXT_REF_NO;
}


// End Setting Default Values


// Ticket Search Functionality

?>
<script type="text/javascript">
    $(document).ready(function () {

        // To change text color to black for login box
        $(".jQcheckcontent").keyup(function () {
            if($(this).val() != '') $(this).addClass("datacolor");
            else $(this).removeClass("datacolor");
        });

        // To



        var txtUseremail  = $("#txtUserEmail").val();

        if(txtUseremail==''){
            $('#GetPass').hide();
        }else
        {
            $('#GetPass').show();
        }

        $("#txtUserID").focusout(function () {

            var username = $("#txtUserID").val();

            var defaltname = '<?php echo TEXT_USER_ID;?>';

            if(username=='')
            {
                $("#txtUserID").val(defaltname);
            }

        });

        $("#txtPassword").focusout(function () {

            var userpass = $("#txtPassword").val();

            var defaltpass = '<?php echo TEXT_PASSWORD;?>';

            if(userpass=='')
            {
                $("#txtPassword").val(defaltpass);
            }

        });

        $("#txtEmail").focusout(function () {

            var email = $("#txtEmail").val();

            var defaultemail = '<?php echo TEXT_EMAIL;?>';

            if(email=='')
            {
                $("#txtEmail").val(defaultemail);
            }

        });


        $("#txtTicketRef").focusout(function () {

            var txtTicketRef = $("#txtTicketRef").val();

            var defaulttxtTicketRef = '<?php echo TEXT_REF_NO;?>';

            if(txtTicketRef=='')
            {
                $("#txtTicketRef").val(defaulttxtTicketRef);
            }

        });
        //alert('asdasd');
        //$('#clsTicket').hide();
    });
</script>
<script language="Javascript">

    function clearText(thefield,id){
        if (thefield.defaultValue==thefield.value)
            thefield.value = ""

    }
    function checkLoginForm(){
        var frm = window.document.frmLogin;
        var errors="";
        var defaultname=$('#hiduserid').val();
        var defaultpswd=$('#hidpassword').val();

        if(frm.txtUserID.value == "" || frm.txtUserID.value==defaultname){
            errors += "<?php echo MESSAGE_USER_ID_REQUIRED; ?>"+ "\n";
        }
        if(frm.txtPassword.value == "" || frm.txtPassword.value==defaultpswd){
            errors += "<?php echo MESSAGE_PASSWORD_REQUIRED; ?>" + "\n";
        }
        if(errors !=""){
            errors = "<?php echo MESSAGE_ERRORS_FOUND; ?>"+  "\n" +  "\n" + errors;
            alert(errors);
            return false;
        }else{
            frm.postback.value = "Login";
            frm.submit();
            return true;
        }
    }


    function toggleVisibility()
    {
        var attr=$("#GetPass").css('display');
        if(attr=='block') {

            $("#GetPass").attr("style", "display:none");
            //$('#GetPass').slideToggle();
            $("#txtUserEmail").val('');
            $("#diverr").html('');

        }
        else
        {
            $("#GetPass").attr("style", "display:block");
        }

        $("#success_msg").hide();

        $("#error_msg").hide();




    }

    function checkGetPasswordForm(){
        var frm = window.document.frmLogin;
        var errors="";
        if(frm.txtUserEmail.value == ""){
            errors += "<?php echo MESSAGE_USER_EMAIL_REQUIRED; ?>"+ "\n";
        }
        else if(!isValidEmail(frm.txtUserEmail.value)) {
            errors += "<?php echo MESSAGE_INVALID_EMAIL; ?>"+ "\n";
        }
        if(errors !=""){
            errors = "<?php echo MESSAGE_ERRORS_FOUND; ?>"+  "\n" +  "\n" + errors;
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

    function searchTicket(){
       	//var frm = window.document.frmShowTicket;
        var errors="";
        /*var defaultemail=$('#hidemail').val();
        var defaultrefno=$('#hidrefno').val();*/

        var txtEmail = $("#txtEmail").val();
        var txtTicketRef = $("#txtTicketRef").val();

        if(txtEmail==''){
            errors += "<?php echo MESSAGE_EMAIL_REQUIRED; ?>"+ "\n";
        }else if(!isValidEmail(txtEmail)){
            errors += "<?php echo MESSAGE_INVALID_EMAIL; ?>"+ "\n";
        }
        if(txtTicketRef==''){
            errors += "<?php echo MESSAGE_TICKET_REF_REQUIRED; ?>" + "\n";
        }
        if(errors !=""){
            errors = "<?php echo MESSAGE_ERRORS_FOUND; ?>"+  "\n" +  "\n" + errors;
            alert(errors);
            return false;
        }else{
            //frm.postback.value = "Search Ticket";
            //frm.submit();

            getSearchdata();

        }

    }

    function getSearchdata(email,ref){  

        var txtEmail = $("#txtEmail").val(); 

        if(txtEmail== undefined || txtEmail=="" ){ 
            txtEmail = email;
        }

        var txtTicketRef = $("#txtTicketRef").val(); 
        if(txtTicketRef=="" || txtTicketRef==undefined){ 
            txtTicketRef = ref;
        }

        var dataString = {"txtEmail":txtEmail,"txtTicketRef":txtTicketRef }; 

        $.ajax({

            url		:"ticketsearch.php",

            type		:"GET",

            data		:dataString,

            dataType            : "json",

            success		:function(data){ //alert(data);

                if(data.response=='success' || data.response=='merged' ){

                    var var_userid = data.var_userid;

                    var var_tid = data.var_tid;
                    var var_type = data.var_type;

                    var thehref='ticketpop.php?var_tid='+var_tid+'&var_userid='+var_userid+'&var_type='+var_type;
                    
                    $("#clsclick").attr("href", thehref)

                    $("#clsclick").trigger("click");
                    //$('#clsclick').fireEvent('click');
                }
                
                else
                {
                    alert("No Ticket Details Found");
                    return false;
                }


            }

        });
    }

</script>

<?php
// Ticket Status Check From Email
if(isset($_GET['email']) && $_GET['ref'] && $_GET['email']!="" && $_GET['ref']!="") {
    $txtEmail       = $_GET['email'];
    $txtTicketRef   = $_GET['ref'];
    echo("<script>getSearchdata('".$txtEmail."','".$txtTicketRef."');</script>");
}
?>

<div class="content_row_home">
    <div class="content_area_home">
        <form name="frmLogin" id="frmLogin" method="post" action="<?php echo($_SERVER["REQUEST_URI"]); ?>"  >
            <div class="loginbox_new">
                <div class="loginbox_new_header"> <?php  echo HEADING_LOGIN_DESC; ?>  </div>
                <div class="logincontent_wrapper">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="home_login">
                        <tr>
                            <td align="left" valign="top" colspan="3" class="home_text_style1"><?php //echo TEXT_LOGIN_AREA;?>
			  to access the entire ticketing system including your dashboard. 
                            </td>
                        </tr>
                        <?php
                        if($errormessage!='') {?>
                        <tr id="error_msg">
                            <td align="left" valign="top" colspan="3" ><div class="msg_error" ><?php echo $errormessage; ?></div></td>
                        </tr>
                            <?php }

                        if($message) { ?>
                        <tr id="success_msg">
                            <td align="left" valign="top" colspan="3" ><div class="msg_common msg_success" style="color: #2F6B03"><?php echo $infomessage; ?></div></td>
                        </tr>
                            <?php } ?>
                        <tr>
                            <td align="left" valign="top" colspan="3"><input name="txtUserID" type="text" class="jQcheckcontent"  id="txtUserID" value="<?php echo $txtUserId;?>"  onfocus="clearText(this,'txtUserID');" >
                            </td>
                        </tr>
                        <tr>
                            <td align="left" valign="top" colspan="3"><input name="txtPassword" class="jQcheckcontent"  id="txtPassword" type="password"   value="<?php echo $txtPassword;?>" onfocus="clearText(this,'txtPassword');">
                            </td>
                        </tr>
                        <tr>

                            <td align="center" valign="top" width="10%" colspan="3" class="rightpadding">
                                <input name="btnSubmit" type="submit" class="btn_orange" value="<?php echo TEXT_LOGIN;?>"  onClick="return checkLoginForm();" >
                                <input type="hidden" name="postback" value="">
                                <input name="hiduserid"  id="hiduserid" type="hidden" value="<?php echo TEXT_USER_ID?>">
                                <input name="hidpassword" id="hidpassword" type="hidden" value="<?php echo TEXT_PASSWORD?>">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                        </tr>
                        <tr>
                            <td align="left" valign="top">
                                <!--a class="" href="#"  onClick="toggleVisibility();" ><?php //echo TEXT_FORGOT_PASSWORD;?></a-->
                                <a class="" href="<?php echo SITE_URL ; ?>forgotpassword.php" ><?php echo TEXT_FORGOT_PASSWORD;?></a>
                            </td>
                            <td></td>
                            <td align="left" valign="top" width="51%">
                                <a href="<?php echo SITE_URL ; ?>register.php" onClick="registration();" ><?php echo CLICK_HERE_REGISTER;?></a>
                            </td>
                        </tr>
                    </table>
                    <div id="GetPass" class="home_login_get_pass"  style="display:<?php echo $txtforgot;?>">
                        <?php if($passworderrormessage!='') { ?>
                        <div class="errormessage" id="diverr"> <?php echo $passworderrormessage;?></div>
                            <?php } ?>
                        <div class="clear">
                            <table width="100%"  border="0" cellspacing="5" cellpadding="0">
                                <tr>
                                    <td colspan="2"><a href="#" class="get_pass_close" onclick="toggleVisibility();">X</a> </td>
                                </tr>
                                <tr>
                                    <td valign="" align="left"><div class="left"> <?php echo TEXT_EMAIL ?> </div></td>
                                    <td valign="top" align="left"><input name="txtUserEmail" id="txtUserEmail" type="text" class="comm_input" style="width:138px;"  value="<?php echo $_POST['txtUserEmail'];?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right"><input name="btnGetPassword" type="button" class="comm_btn btn" style="width:120px;" value="<?php echo TEXT_GET_PASSWORD;?>" onClick="return checkGetPasswordForm();" >
                                    </td>
                                </tr>
                            </table>

                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="clear"></div>
            </div>
        </form>
        <div class="Home_content">
            <script type="text/javascript" src="./scripts/jquery.autocomplete_kbsearch.js"></script>
            <script type="text/javascript">
                $(document).ready(function(){
                    var site_url ='<?php echo SITE_URL?>';

                    $("#txtKbTitleSearch").autocomplete("kb_search_home.php", {
                        selectFirst: true


                    });

                    // getKbSearchdata();
                });

                function getKbSearchdata(){


                }


            </script>
            <div class="homecontent_box_td">
                <h2><?php echo CREATE_NEW_TCKT;?></h2>
                <div class="homecontent_box_top">
                    <div class="homecontent_topsec1">
                        <p> <?php echo VIEW_TICKETS_HOME_DEF; ?> </p>
                    </div>
                </div>
                <div class="home_contentftr_btn">
                    <input type="button" class="newhome_btn" value="<?php echo CREATE ?>" <?php if($PostTicketBeforeLogin == 1) { ?> onclick="javascript:jAlert('<?php echo MESSAGE_ERROR_LOGIN; ?>','<?php echo HEADING_LOGIN; ?>');" <?php }else { ?> onClick="window.location.href='<?php echo SITE_URL; ?>postticketbeforeregister.php'" <?php } ?>>
                </div>
            </div>
            <div class="homecontent_box_td">
                <h2><?php echo HOME_TICKET_STAUS;?></h2>

                <div class="chkstatus_content1">
                    <div class="homecontent_box_top">
                        <div class="homecontent_topsec2" id="jQStatusContentDiv">
                            <p> <?php echo TEXT_TICKET_STAUS;?> </p>
                        </div>
                    </div>

                    <div class="home_contentftr_btn2" id="jQStatusDisplayDiv">
                        <input id="jQStatusDisplayButton" type="button" value="<?php echo TEXT_CHECK_TICKET_STATUS; ?>" class="newhome_btn">
                        <a href="<?php echo SITE_URL; ?>getrefinfo.php" onClick="redr();" ><?php echo TEXT_GET_REF_NO ?></a>
                        <span id="set1" style="cursor: pointer;">
                            <a title=" To get ticket reference numbers of all submitted tickets "><span style="color:#ccc;font-size: 12px; font-family: open Sans ,arial;">[?]</span>
                                <!--img src="images/tooltip.jpg"-->
                            </a>
                        </span>
                    </div>


                    <div class="clear"></div>
                </div>





                <table width="100%" cellpadding="0" cellspacing="0" border="0" class="home_login" style="display: none;" id="jQStatusCheckDiv">
                    <?php if($ticketerrormessage!='') { ?>
                    <tr>
                        <td align="left" valign="top" colspan="2" ><div class="msg_error"><?php echo $ticketerrormessage; ?></div></td>
                    </tr>
                        <?php } ?>
                    <?php if($ticketmessage!='') { ?>
                    <tr >
                        <td align="left" valign="top" colspan="2" ><div class="message"><?php echo $infomessage; ?></div></td>
                    </tr>
                        <?php } ?>

                    <tr>
                        <td align="left" valign="top" colspan="2"><input class="widthnew jQcheckcontent" name="txtEmail" type="text"  id="txtEmail" value="<?php echo htmlentities($txtEmail); ?>" onfocus="clearText(this,'txtEmail');">
                        </td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" colspan="2"><input name="txtTicketRef" class="widthnew jQcheckcontent" type="text" id="txtTicketRef" value="<?php echo htmlentities($txtTicketRef); ?>" onfocus="clearText(this,'txtTicketRefy');">
                        </td>
                    </tr>
                    <tr>
                        <td height="27">
                            <!--div class="homecontent_box_top" style="height:auto;">
                                <p style="padding:0; margin:0;">Enter your email address and ticket referance number to get your ticket status</p>
                            </div-->
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top" width="10%" colspan="3">
                            <div class="home_contentftr_btn2">
                                <input name="btnSearch" id="btnSearch" type="submit" class="newhome_btn" value="<?php echo TEXT_CONTINUE ?>" onClick="javascript:searchTicket();">
                                <input type="hidden" name="postback" value="">
                                <input name="hidemail"  id="hidemail" type="hidden" value="<?php echo TEXT_EMAIL?>">
                                <input name="hidrefno" id="hidrefno" type="hidden" value="<?php echo TEXT_REF_NO?>">
                                <a href="<?php echo SITE_URL; ?>getrefinfo.php" onClick="redr();" ><?php echo TEXT_GET_REF_NO ?></a>
                                <span id="set1" style="cursor: pointer;">
                                    <a title=" To get ticket reference numbers of all submitted tickets "> <span style="color:#ccc;font-size: 12px; font-family: open Sans ,arial;">[?]</span>
                                        <!--img src="images/tooltip.jpg"-->
                                    </a>
                                </span>
                            </div></td>
                    </tr>

                </table>

            </div>
            <div class="homecontent_box_td">
                <h2><a style="color:#00A4EF;" href="<?php echo SITE_URL; ?>kb/"><?php echo HEADER_KB;?></a></h2>
                <form name="frmkbSearch" action="kb/" method="post" onsubmit="validateKbKey()">
                    <div class="homecontent_box_top">
                        <div class="homecontent_topsec3">
                            <p> <?php echo TEXT_HOMEKNOWLEDGE;?></p>
                        </div>
                    </div>

                    <div class="home_btncontainer">
                        <div class="home_contentftr_btn">
                            <input name="txtKbTitleSearch" id="txtKbTitleSearch" type="text" class="searchfield_knowledgebase"  value="<?php echo htmlentities($var_cc);?>" >
                            <input type="hidden" name="txtKbSearchid" id="txtKbSearchid">
                            <input type="submit" name="btnKbSearch" id="btnKbSearch" value="<?php echo TEXT_SEARCH;?>" class="searchkb">
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </form>
            </div>
            <div class="clear"></div>
        </div>
        <!--div class="home_column2 right">
          <div class=" homeboxheight">
        <?php //include "includes/newsbox.php"; ?>
          </div>
        </div-->


        <div class="clear"></div>
    </div>
    <div class="bottom_pannel">
        <div class="bottom_pannel_content"><p><?php echo $HomeFooterContent; ?></p>
            <?php
            if(strcmp($PostTicketBeforeLogin,"0")==0) {
                ?>

            <a href="<?php echo SITE_URL;?>postticketbeforeregister.php"><?php echo TEXT_POSTTICKET;?></a>
    <?php
            }
            ?>
            <div class="clear"></div>
        </div>
    </div>
</div>
<a href=" " rel="#overlay" style="text-decoration:none" id="clsclick"> </a>
<!-- overlayed element -->
<div class="apple_overlay" id="overlay">
    <!-- the external content is loaded inside this tag -->
    <div class="contentWrap"></div>
</div>
<script>

    $(function() {

        // if the function argument is given to overlay,
        // it is assumed to be the onBeforeLoad event listener
        $("a[rel]").overlay({

            mask: 'white',
            effect: 'apple',

            onBeforeLoad: function() {

                // grab wrapper element inside content
                var wrap = this.getOverlay().find(".contentWrap");

                // load the page specified in the trigger
                wrap.load(this.getTrigger().attr("href"));
            }

        });
    });
</script>
