<?php
if ($_GET["comp"] !='' ) $comp = $_GET["comp"]; 
if ($_POST["comp"] !='' ) $comp = $_POST["comp"];
// header("location:client_chat.php?username=".$username); 
require_once("./includes/applicationheader.php");
include_once("./parser/functions.php");
include("./languages/".$_SP_language."/client_prechat.php");
$conn = getConnection();
if ($_POST["post_back"] == "CL") {
    $_SESSION["sess_language"] = $_POST["cmbLan"];
    $_SESSION["sess_userlangchange"] = "1";
    header("location:client_prechat.php");
}
if (userLoggedIn() && ($_SESSION["sess_clientchatid"] !='')) {
    /* $sql = "select vUserName from sptbl_users where nUserId = '".$_SESSION["sess_userid"]."'";
	  $result = executeSelect($sql,$conn);
	  if(mysql_num_rows($result) > 0) {
	     while($row = mysql_fetch_array($result)) {
			$username = $row["vUserName"];
		 }
	  }
    */
    $username = $_SESSION["sess_userfullname"] ;
    header("location:client_chat.php?username=".$username."&comp=".$comp);
}

/*Newly added on 190609*/
$sql = "Select vLookUpName,vLookUpValue from sptbl_lookup WHERE vLookUpName IN('LangChoice',
          'DefaultLang','HelpdeskTitle','Logourl','logactivity','MaxPostsPerPage','OldestMessageFirst', 'PostTicketBeforeLogin','SMTPSettings','SMTPServer','SMTPPort')";
$rs = executeSelect($sql,$conn);
if(!isset($_SESSION['sess_cssurl'])) {
    $_SESSION['sess_cssurl']="styles/coolgreen.css";
}
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
            case "PostTicketBeforeLogin":
                $_SESSION["sess_postticket_before_register"] = $row["vLookUpValue"];
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
mysql_free_result($rs);
if($_SESSION["sess_userlangchange"] =="1") {
    ;
}else {
    if (($_SESSION["sess_defaultlang"] !=$_SESSION["sess_language"])) {
        $_SESSION["sess_language"] = $_SESSION["sess_defaultlang"];
        //commented by amal
        //echo("<script>window.location.href='index.php'<--/script-->");
        //exit();
    }
} 
/*end*/
if ( ( $_POST["postback"] == 'S' ) || ( $_POST["postback"] == 'T' ) ) { //echo '<pre>'; print_r($_POST); echo '</pre>'; exit;
    $username = $_POST["txtName"];
    $email = $_POST["txtEmail"];
    $question = $_POST["txtQst"];
    $department = $_POST["cmbDpt"];
    $sql = "select nCompId from sptbl_depts where  nDeptId ='" . addslashes($department) . "'";
    $result = executeSelect($sql,$conn);
    if(mysql_num_rows($result) > 0) {
        while($row = mysql_fetch_array($result)) {
            $companyid = $row["nCompId"];
        }
    }
    // echo $username.":".$email.":".$question.":".$department.":".$companyid."<br>";
    $sql = "select nUserId,vLogin,vUserName,vEmail from sptbl_users where vEmail='" . addslashes($email) . "' and nCompId='".addslashes($companyid)."'";
    // echo $sql;
    $result = executeSelect($sql,$conn);
    if(mysql_num_rows($result) > 0) {
        while($row = mysql_fetch_array($result)) {
            $userid = $row["nUserId"];
            //$username = $row["vUserName"];
            $userlogin = $row["vLogin"];
            $email = $row["vEmail"];
        }
        $sql = "update sptbl_users set vOnline='1' where nUserId='".$userid."'";
        executeQuery($sql,$conn);
    } else {
        $userlogin = preg_replace("/[^a-z0-9]/i","",$username);
        $userlogin = (strlen($userlogin) > 50)?(substr($userlogin,0,50)):$userlogin;
        $sql = "Select nUserId from sptbl_users where vLogin='" . addslashes($userlogin) . "'";
        //echo "<br>".$sql."-".$userlogin;
        $result = mysql_query($sql);
        if ( mysql_num_rows($result) > 0 ) {
            $userlogin = uniqid($userlogin);
        }
        //echo "<br>"."-".$userlogin;
        $userpassword = uniqid($userlogin);
        $sql = "Insert into sptbl_users(nUserId,nCompId,vUserName,vEmail,vLogin,vPassword,dDate,vOnline,nCSSId)
				Values('','" . addslashes($companyid) . "','" . addslashes($username) . "','" . addslashes($email) . "',
				'" . addslashes($userlogin) . "','" . md5($userpassword) . "',now(),'1','1')";
        //echo "<br>".$sql;
        executeQuery($sql,$conn);
        $userid = mysql_insert_id();
        /* send username and password  to user's mail id */
        $arr_lookupvalues = getLookupDetails();
        $var_body = $arr_lookupvalues['var_emailheader'] ."<br>";
        $var_body .= TEXT_MAIL_START." ".$username.",<br>&nbsp;<br>";
        $var_body .= TEXT_MAIL_CHAT_REGUSER_BODY.htmlentities($email)."<br>&nbsp;<br>";
        $var_body .= TEXT_MAIL_CHAT_LOGIN.":".$userlogin."<br>&nbsp;<br>";
        $var_body .= TEXT_MAIL_CHAT_PWD.":".$userpassword."<br>&nbsp;<br>";
        $var_body .= "<br>&nbsp;<br>";
        $var_body .= TEXT_MAIL_THANK."<br>";
        $var_body .= $arr_lookupvalues['var_emailfooter']."</br>";
        $var_subject = TEXT_MAIL_CHAT_REG_SUBJECT;
        $Headers="From: " . $arr_lookupvalues['var_fromMail'] . "\n";
        $Headers.="Reply-To: " . $arr_lookupvalues['var_replyMail'] . "\n";
        $Headers.="MIME-Version: 1.0\n";
        $Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        if($var_smtp_status == 1) {
            $var_smtpserver = $arr_lookupvalues['SMTPServer'];
            $var_port = $arr_lookupvalues['SMTPPort'];
            SMTPMail($arr_lookupvalues['var_fromMail'],$email,$var_smtpserver,$var_port,$var_subject,$var_body);
        } else @mail($email,$var_subject,$var_body,$Headers);
        /* ends */
    }
    
}
if ( $_POST["postback"] == 'S' ) {
    $sql = "select vCSSURL from sptbl_css where nCSSId = (select nCSSId from sptbl_users where nUserId='".$userid."')";
    $result = executeSelect($sql,$conn);
    if (mysql_num_rows($result) > 0) {
        while($row = mysql_fetch_array($result)) {
            $cssurl = $row["vCSSURL"];
        }
    }
    $_SESSION["sess_userid"] = $userid;
    $_SESSION["sess_username"] = $userlogin;
    $_SESSION["sess_cssurl"] = $cssurl;
    $_SESSION["sess_useremail"] = $useremail;
    $_SESSION["sess_userfullname"]	= $username;
    $_SESSION["sess_usercompid"]	= $compid;

    if ( $question != '' ) $matter = "<span><FONT color=\"#0000FF\" style=\"font-size:14px;\" FACE=\"Verdana\">".$username." : "."</FONT></span><span><FONT style=\"font-size:14px;\" FACE=\"Verdana\">".$question."</FONT></span><br>";
    else $matter =' ';
    $sql = "Insert into sptbl_chat(nChatId,nUserId,vUserName,dTimeStart,tMatter,vStatus,vNewMsg,nDeptId)
				Values('','" . addslashes($userid) . "','".addslashes($username)."',now(),'" . addslashes($matter) . "','pending', '1','".$department."')";
    executeQuery($sql,$conn);
    $chatid = mysql_insert_id();
    $_SESSION["sess_clientchatid"] = $chatid;
    $_SESSION["sess_clientchatdepid"] = $department;
    header("location:client_chat.php?username=".$username."&comp=".$comp);
}
if ( $_POST["postback"] == 'T' ) {
    //ticket posting to temperory table
    $sql="delete from sptbl_temp_tickets where nTpUserId=$userid and vStatus=0";
    executeQuery($sql,$conn);
    if ( $question != '') {
        $sql="insert into sptbl_temp_tickets(nTpTicketId,nTpUserId,nTDeptId,vTpTitle,tTpQuestion,vTpPriority,dTpPostDate,vAtt,vStatus)";
        $sql.=" values('','$userid','".addslashes($department)."',";
        $sql .="'".addslashes($question)."',"."'".addslashes($question)."',0,";
        $sql .="now(),'','0')";
        executeQuery($sql,$conn);
    }
    $sql="select * from sptbl_temp_tickets where nTpUserId=$userid and vStatus=0";
    $rs = executeSelect($sql,$conn);
    if( mysql_num_rows($rs) > 0 ) {
        $row = mysql_fetch_array($rs);
        $deptid=$row['nTDeptId'];
        $title=$row['vTpTitle'];
        $qstion=$row['tTpQuestion'];
        $vAttachmentfiles=$row['vAtt'];
        $tempticketid=$row['nTpTicketId'];
        $priority=$row['vTpPriority'];
        mysql_free_result($rs);
        /*
		$sql ="select date_add(dLastAttempted,interval $var_posttopostgap MINUTE) < now() as ptop from sptbl_tickets ";
	    $sql .=" where nUserId=$user_id order by dPostDate desc limit 0,1";
	    $result = executeSelect($sql,$conn);
	    if(mysql_num_rows($result)>0){
	      $row=mysql_fetch_array($result);
	      if($row['ptop']=="1") $var_post_flag=true;
		  else $var_post_flag=false;    
	    }*/
        $var_final_flag = false;
        $var_continue_exec=true;
        $var_post_flag=true;
        if ($var_post_flag==true) {
            $varclip=getClientIP();
            $sql_insert_ticket ="insert into sptbl_tickets(nTicketId,nDeptId,vRefNo,nUserId,vUserName,vTitle,tQuestion,vPriority,dPostDate,vMachineIP,dLastAttempted)";
            $sql_insert_ticket .="values('','".$deptid."','1','".$userid."','".$username."','".addslashes($title)."','";
            $sql_insert_ticket .=addslashes($qstion)."','$priority',now(),'".$varclip."',now())";
            executeQuery($sql_insert_ticket,$conn);
            $var_insert_id = mysql_insert_id($conn);
            $var_comp_id_1 = $compid ;
            insertStattics($var_insert_id);
            
            if($var_comp_id_1<10) $var_comp_id_1 = "0".$var_comp_id_1;
            $dept_id = $deptid; // to send mail
// 'zero' added for 2 digit departmentid
            if($dept_id<10) $dept_id = "0".$dept_id;
// 'zeros' added for 4 digit userid
            $user_id = $userid;
            if($user_id<10) $user_id = "000".$user_id;  // 9   0009
            else if($user_id<100)	$user_id = "00".$user_id;  // 99   0099
            else if($user_id<1000) $user_id = "0".$user_id;  // 999   0999
// 'zeros' added for 5 digit ticket no
            if($var_insert_id<10) $var_insert_id = "0000".$var_insert_id;       // 9   00009
            else if($var_insert_id<100) $var_insert_id = "000".$var_insert_id;  // 99   00099
            else if($var_insert_id<1000) $var_insert_id = "00".$var_insert_id;   // 999   00999
            else if($var_insert_id<10000) $var_insert_id = "0".$var_insert_id;   // 9999   09999
            $var_refno=$var_comp_id_1.$dept_id.$user_id.$var_insert_id;
            $sql_update_ticket="update sptbl_tickets set vRefNo='".$var_refno."' where nTicketId='".$var_insert_id."'";
            executeQuery($sql_update_ticket,$conn);
            $messagetext = TEXT_TICKET_POST_INFO." ".$var_refno;
            $sql="delete from sptbl_temp_tickets where nTpUserId='".$userid."'";
            executeQuery($sql,$conn);
        }
    }
    //ticket posting ends
}
if (userLoggedIn()) {
    $username = $_SESSION["sess_userfullname"];
    $email = $_SESSION["sess_useremail"];
}
//$sql_dpt = "SELECT d.nDeptId, d.vDeptDesc FROM sptbl_depts d INNER JOIN sptbl_companies c
//ON ( d.nCompId = c.nCompId ) WHERE d.nCompId = '".$comp."' ORDER BY d.vDeptDesc";
$sql_dpt = "SELECT d.nDeptId, d.vDeptDesc FROM sptbl_depts d WHERE nDeptVisibility='1' ORDER BY d.vDeptDesc DESC";
$res_dpt = executeSelect($sql_dpt,$conn);
?>
<html>
    <head>
        <title></title>
        <script language="javascript" type="text/javascript" src="scripts/ajax_global.js"></script>
        <script language="javascript" type="text/javascript" src="scripts/jquery.js"></script>
        <script language="javascript" type="text/javascript">
            self.resizeTo(475,680);
            function getStaffOnlineStatus() {
                var dptId = frmPreChat.cmbDpt.value;
                var spnImg = document.getElementById("spanOnlineImg");
                if (dptId == 'Select'){
                    spnImg.style.display="none";
                    return false;
                }
                send_data_one( '',"getStaffStsImage.php?dptid="+dptId);
                if ( xmlHttp1.responseText == 'OFL' ) {
                    var ans = confirm("<?php echo MESSAGE_JS_TICKE_POST_CONFIRM ;?>");
                    if (ans){
                        frmPreChat.postback.value="T";
                        if (validateForm()){
                            frmPreChat.submit();
                        } else return false;
                    } else {
                        return false;
                    }
                }
                /*if (xmlHttp1.responseText !='') var img_path = xmlHttp1.responseText;
                getChildById("imgOnline", spnImg).src = img_path;
                spnImg.style.display="";
                 */
            }
            function startChat() {
                if (validateForm()){
                    var dptId = frmPreChat.cmbDpt.value;
                    var spnImg = document.getElementById("spanOnlineImg");
                    if (dptId == 'Select'){
                        spnImg.style.display="none";
                        return false;
                    }
                    send_data_one( '',"getStaffStsImage.php?dptid="+dptId);
                    if ( xmlHttp1.responseText == 'OFL' ) {
                        var ans = confirm("<?php echo MESSAGE_JS_TICKE_POST_CONFIRM ;?>");
                        if (ans){
                            frmPreChat.postback.value="T";
                            if (validateForm()){
                                frmPreChat.submit();
                            } else return false;
                        } else return false;
                    } else if (xmlHttp1.responseText == 'ONL') {
                        frmPreChat.postback.value="S";
                        frmPreChat.submit();
                    } else  return false;
                } else return false;
            }
            function validateForm() {
                if ($.trim(frmPreChat.txtName.value) == '') {
                    alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                    frmPreChat.txtName.value = '';
                    frmPreChat.txtName.focus();
                    return false;
                } else if (frmPreChat.txtEmail.value == ""){
                    alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                    frmPreChat.txtEmail.focus();
                    return false;
                } else if(!checkMail(frmPreChat.txtEmail.value)){
                    alert('<?php echo MESSAGE_JS_EMAIL_ERROR; ?>');
                    frmPreChat.txtEmail.select();
                    frmPreChat.txtEmail.focus();
                    return false;
                } else if ($.trim(frmPreChat.txtQst.value) == ""){
                    alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                    frmPreChat.txtQst.value = "";
                    frmPreChat.txtQst.focus();
                    return false;
                } else if (frmPreChat.cmbDpt.value == "Select"){
                    alert('<?php echo MESSAGE_JS_MANDATORY_ERROR; ?>');
                    frmPreChat.cmbDpt.focus();
                    return false;
                } else return true;
            }
            function checkMail(email)
            {
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
        <link href="<?php echo($_SESSION["sess_cssurl"]);?>" rel="stylesheet" type="text/css">
    </head>
    <body bgcolor="#EDEBEB" >
        <table width="100%" height="100%" border="0" class="div_all">

            <tr height="2%" width="100%">
                <td align="left" >
                    <?php
                    if ($_SESSION["sess_langchoice"] == "1") {
                        $sql="Select vLangCode,vLangDesc from sptbl_lang order by vLangDesc ";
                        $result=mysql_query($sql,$conn);
                        ?>
                    <form name="frmLanguage" action="client_prechat.php" method="post">
                        <font ><?php echo(TEXT_SELECT_LANGUAGE); ?></font>&nbsp;
                        <select name="cmbLan" class="comm_input input_width1" onChange="javascript:changeLanguage();">
                                <?php
                                if (mysql_num_rows($result) > 0) {
                                    while($row = mysql_fetch_array($result)) {
                                        echo("<option value=\"" . htmlentities($row["vLangCode"]) . "\">" . $row["vLangDesc"] . "</option>");
                                    }
                                }
                                ?>
                        </select>&nbsp;
                        <script>
                            var lc = '<?php echo($_SESSION["sess_language"]); ?>';
                            document.frmLanguage.cmbLan.value=lc;
                            function changeLanguage(){
                                document.frmLanguage.method="post";
                                document.frmLanguage.post_back.value ="CL";
                                document.frmLanguage.submit();
                            }
                        </script>
                        <input type="hidden" name="post_back" value="">
                        <input type="hidden" id="comp" name="comp" value="<?php echo $comp ?>">
                    </form>
                        <?php
                    }
                    ?>
                </td>
            </tr>

            <tr>
                <td>
                    <form id="frmPreChat" name="frmPreChat" method="post" action="<?php echo($_SERVER['PHP_SELF']); ?>">
                        <table width="100%"  border="0" cellpadding="0" cellspacing="10">
                            <tr >
                                <td colspan="2"><b><?php echo TEXT_HEADER_INFORMATION; ?></b></td>
                            </tr>
                            <tr>
                                <td>&nbsp;<font style="color:green; font-weight: bold; font-size:13px"><?php if ( $messagetext != '' ) echo $messagetext;?></font></td>
                            </tr>
                            <tr>
                                <td><?php echo TEXT_NAME ?><font style="color:#FF0000; font-size:12px">*</font></td>
                            </tr>
                            <tr>
                                <td><input name="txtName" type="text" id="txtName" class="comm_input"  style="width:250px;" width="100px" size="60" maxlength="300" value="<?php echo $username;?>"></td>
                            </tr>
                            <tr>
                                <td><?php echo TEXT_EMAIL ?><font style="color:#FF0000; font-size:12px">*</font></td>
                            </tr>
                            <tr>
                                <td><input name="txtEmail" type="text" id="txtEmail" class="comm_input"  style="width:250px;" size="60" maxlength="200" value="<?php echo $email;?>"></td>
                            </tr>
                            <tr>
                                <td><?php echo TEXT_QUESTION ?><font style="color:#FF0000; font-size:12px">*</font></td>
                            </tr>
                            <tr>
                                <td><textarea name="txtQst" cols="15" rows="4" id="txtQst" class="comm_input" style="width:253px;"></textarea></td>
                            </tr>
                            <tr>
                                <td><?php echo TEXT_DEPARTMENT ?><font style="color:#FF0000; font-size:12px">*</font></td>
                            </tr>
                            <tr>
                          <!--   <td><input name="rdDpt" type="radio" value="cs" checked><?php //echo TEXT_CUSTOMERSUPPORT ?><input name="rdDpt" type="radio" value="ts" ><?php //echo TEXT_TECHSUPPORT ?></td>-->
                                <td>
                                    <select id="cmbDpt" name="cmbDpt" class="comm_input" style="width:250px;" onChange="getStaffOnlineStatus();">
                                        <option value="Select"><?php echo TEXT_DEPT; ?></option>
                                        <?php
                                        if(mysql_num_rows($res_dpt) > 0) {
                                            while($row = mysql_fetch_array($res_dpt)) {
                                                $options ="<option value='".$row['nDeptId']."'";
                                                if ($department == $row['nDeptId']) {
                                                    $options .=" selected=\"selected\"";
                                                }
                                                $options .=">".htmlentities($row['vDeptDesc'])."</option>\n";
                                                echo $options;
                                            }
                                        }
                                        ?>
                                    </select>&nbsp;<span id="spanOnlineImg" name="spanOnlineImg" style="display:none"><img id="imgOnline" width="35" height="27" src=""></img></span>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan=""><font style="color:#FF0000; font-size:12px"><?php echo TEXT_MANDATORY?></font>&nbsp;<input name="btnStartChat" id="btnStartChat" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_START_CHAT ?>" onClick="javascript:startChat();"></td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="hidden" id="postback" name="postback" value="">
                                    <input type="hidden" id="comp" name="comp" value="<?php echo $comp ?>">
                                </td>
                            </tr>
                        </table></form>
                </td>
            </tr>
            <tr >
                <td colspan="3">

                    <div class="footer_row">
                        <div class="footer_cnt sitewidth">
                            <div class="footer_left" style="font-size:12px;"><?php echo TEXT_POWERED_BY ?></div>
                            <div class="footer_right">&nbsp;</div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </body>
</html>
