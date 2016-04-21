<?php

//$var_compid=$_SESSION["sess_usercompid"];
//$var_userid=$_SESSION["sess_userid"];
if ($_GET["stylename"] != "") {
    $var_styleminus = $_GET["styleminus"];
    $var_stylename = $_GET["stylename"];
    $var_styleplus = $_GET["styleplus"];
} else {
    $var_styleminus = $_POST["styleminus"];
    $var_stylename = $_POST["stylename"];
    $var_styleplus = $_POST["styleplus"];
}
$var_userid = $_POST["cmbUser"];
if ($var_userid == "0") {
    $vEmail = $_POST["newUserEmail"];
    $var_email = $vEmail;
    $var_userLogin = $_POST["newUserLogin"];
    $var_password = $_POST["newUserPassword"];
    if (isUniqueEmailUser($vEmail, 0, 1)) {
        //Insert into the company table
        $sql = "INSERT INTO sptbl_users(nCompId,vUserName,vEmail,vLogin,vPassword,dDate,vOnline,";
        $sql .= "vBanned,vDelStatus) VALUES ('1',
						'" . mysql_real_escape_string($var_userLogin) . "',
                                                '" . mysql_real_escape_string($vEmail) . "',
                                                '" . mysql_real_escape_string($var_userLogin) . "',
						'" . md5($var_password) . "',
                                                now(),
                                                '0',
                                                '0',
                                                '0')";
        executeQuery($sql, $conn);
        $var_insert_id = mysql_insert_id($conn);
        $var_userid = $var_insert_id;
        //Insert the actionlog
        if (logActivity()) {
            $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . mysql_real_escape_string(TEXT_ADDITION) . "','Users','$var_insert_id',now())";
            executeQuery($sql, $conn);
        }

        $var_message = MESSAGE_RECORD_ADDED;
        $flag_msg = "class='msg_success'";
        //Send mail with the password to the user here
        $sql = "Select * from sptbl_lookup where vLookUpName IN('MailFromName','MailFromMail','MailReplyName','MailReplyMail','Emailfooter','Emailheader','HelpdeskTitle','LoginURL')";
        $result = executeSelect($sql, $conn);
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_array($result)) {
                switch ($row["vLookUpName"]) {
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
                    case "HelpdeskTitle":
                        $var_helpdesktitle = $row["vLookUpValue"];
                        break;
                    case "LoginURL":
                        $var_loginurl = $row["vLookUpValue"];
                        break;
                }
            }
        }
        mysql_free_result($result);

        $var_mail_body = TEXT_MAIL_WELCOME_HEAD . "<br>";
        $var_mail_body .= TEXT_USER_LOGIN . " : " . $var_userLogin . "<br>";
        $var_mail_body .= TEXT_USER_PASSWORD . " : " . $var_password . "<br><br>";
        $var_mail_body .= "<a href=\"" . $var_loginurl . "\">" . TEXT_CLICK_HERE . "</a>" . TEXT_LOGIN_ACCOUNT . "<br><br>";
        $var_mail_body .= TEXT_MAIL_WELCOME_TAIL . "<br>" . htmlentities($var_helpdesktitle);
        $subject = "Your account has been created";

        $var_body = $var_emailheader . "<br>" . $var_mail_body . "<br>" . $var_emailfooter;
        $Headers = "From: $var_fromName <$var_fromMail>\n";
        $Headers.="Reply-To: $var_replyName <$var_replyMail>\n";
        $Headers.="MIME-Version: 1.0\n";
        $Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

        // it is for smtp mail sending
        if ($_SESSION["sess_smtpsettings"] == 1) {
            $var_smtpserver = $_SESSION["sess_smtpserver"];
            $var_port = $_SESSION["sess_smtpport"];

            SMTPMail($var_fromMail, $var_email, $var_smtpserver, $var_port, $var_subject, $var_body);
        }
        else
            $mailstatus = @mail($var_email, $subject, $var_body, $Headers);

        //End of sending mail
        $var_userName = "";
        $var_userLogin = "";
        $var_password = "";
        $var_online = "";
        $var_email = "";
        $var_banned = "0";
        $var_compId = "";
        $var_date = date("m-d-Y h:i:s");
    }
    else {
        $var_message = MESSAGE_NONUNIQUE_EMAIL;
        $flag_msg = "class='msg_error'";
    }
}
if ($_POST["postback"] == "S") {
    $var_uploadfiles = $_POST['uploadfiles'];
    $var_title = $_POST['tckttitle'];
    $var_deptpid = $_POST['deptid'];
    $var_prty = $_POST['prty'];
    $var_desc = $_POST['tcktdesc'];

    //unlink the upload file
    //Modification on September 29, 2005
    //The following block is being commented
    /* 		 $sql="select vAtt from sptbl_temp_tickets where nTpUserId=$var_userid and vStatus=0";
      $rs = executeSelect($sql,$conn);
      if(mysql_num_rows($rs)>0){
      $row = mysql_fetch_array($rs);
      $vAttachmentfiles=$row['vAtt'];
      if($vAttachmentfiles !=""){
      $vAttacharr=explode("|",$vAttachmentfiles);
      foreach($vAttacharr as $key=>$value){

      $split_name_url=explode("*",$value);
      @unlink("../attachments/".$split_name_url[0]);

      }
      }
      $sql="delete from sptbl_temp_tickets where nTpUserId=$var_userid and vStatus=0";
      executeQuery($sql,$conn);
      }
      //insert into temparary table
      $sql="insert into sptbl_temp_tickets(nTpTicketId,nTpUserId,nTDeptId,vTpTitle,tTpQuestion,vTpPriority,dTpPostDate,vAtt,vStatus)";
      $sql.=" values('','$var_userid','".mysql_real_escape_string($var_deptpid)."',";
      $sql .="'".mysql_real_escape_string($var_title)."',"."'".mysql_real_escape_string($var_desc)."',"."'".mysql_real_escape_string($var_prty)."',";
      $sql .="now(),'".mysql_real_escape_string($var_uploadfiles)."','0')";
      executeQuery($sql,$conn);
     */
}


if ($_GET["mt"] == "y") {
    $var_numBegin = $_GET["numBegin"];
    $var_start = $_GET["start"];
    $var_begin = $_GET["begin"];
    $var_num = $_GET["num"];
    $var_styleminus = $_GET["styleminus"];
    $var_stylename = $_GET["stylename"];
    $var_styleplus = $_GET["styleplus"];
    $var_title = $_GET['tckttitle'];
    $var_deptpid = $_GET['deptid'];
} elseif ($_POST["mt"] == "y") {
    $var_numBegin = $_POST["numBegin"];
    $var_start = $_POST["start"];
    $var_begin = $_POST["begin"];
    $var_num = $_POST["num"];
    $var_styleminus = $_POST["styleminus"];
    $var_stylename = $_POST["stylename"];
    $var_styleplus = $_POST["styleplus"];
    $var_title = $_POST['tckttitle'];
    $var_deptpid = $_POST['deptid'];
}

//This block is being commented for modification for staff
// In case of staff no kb check is being done, directly we are saving the ticket
/*
  if($_POST["postback"] == "CN"){
  require("./includes/saveticket.php");
  }else{
  $sql = "Select * from sptbl_kb,sptbl_categories   where match(vKBTitle,tKBDesc) against ('".mysql_real_escape_string($var_title)."')";
  $sql .=" and sptbl_kb.nCatId=sptbl_categories.nCatId and sptbl_categories.nDeptId=$var_deptpid" ;
  //echo "sql==$sql";
  $totalrows = mysql_num_rows(executeSelect($sql,$conn));
  if($totalrows=="0"){
  require("./includes/saveticket.php");
  }else{
  require("./includes/showkb.php");
  }
  }
 */
require("./includes/saveticket.php");
?>

