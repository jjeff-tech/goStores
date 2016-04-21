<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshith<roshith@armia.com>                                  |
// +----------------------------------------------------------------------+

require_once("./includes/applicationheader.php");
include "languages/" . $_SP_language . "/postticketbeforeregistersave.php";
$conn = getConnection();

if (isset($_POST['notlogin']) == "NOTLOGIN") {
    $loginstatus = $_POST['notlogin'];
    $name = mysql_real_escape_string($_POST['txtname']);
    $email = $_POST['txtemail'];
    $var_deptid = $_POST["deptid"];

    $errormessage = "";
    $error = false;
    $var_check = 0;

    $sqlCompany = "SELECT nCompId FROM sptbl_depts WHERE nDeptId =" . $var_deptid;
    $rsCompany = executeSelect($sqlCompany, $conn);

    if (mysql_num_rows($rsCompany) > 0) {
        $row = mysql_fetch_array($rsCompany);
        $company = $row['nCompId'];
    }

    $code = rand(1, 999999);   // for password generation

    if (!isValidEmail($email)) {
        $error = true;
        $errormessage .= MESSAGE_INVALID_EMAIL . "<br>";
    }

    // if email exist and different companyid
    $sqlUser = "SELECT nUserid,nCompId,vUserName FROM sptbl_users WHERE vEmail ='" . $email . "'";
    $rsUser = executeSelect($sqlUser, $conn);
    if (mysql_num_rows($rsUser) > 0) {
        $error = true;
        $errormessage .= MESSAGE_EMAIL_EXIST_SAME_COMPANY . "<br>";
    }

    $sqlUser = "SELECT nUserid,nCompId,vUserName FROM sptbl_users WHERE vUserName = '" . $name . "'";
    $rsUser = executeSelect($sqlUser, $conn);
    if (mysql_num_rows($rsUser) > 0) {
        $error = true;
        $errormessage .= MESSAGE_LOGIN_NAME_EXISTS . "<br>";
    }


    $sqlUser = "SELECT nUserid,nCompId,vUserName FROM sptbl_users WHERE vEmail ='" . $email . "' AND nCompId='" . $company . "'";

    $rsUser = executeSelect($sqlUser, $conn);

    if (mysql_num_rows($rsUser) > 0) {
        $row = mysql_fetch_array($rsUser);
        $id = $row['nUserid'];
        $company = $row['nCompId'];
        $name = $row['vUserName'];
    }

    if (userNameExists($name))
        $loginname = strtolower($name) . '1';  // for unique loginname attach '1' for the existing name
    else
        $loginname = strtolower($name);

    if ($error == 'true') {    // if any error exist redirect it to ticket posting form
        header("location:postticketbeforeregister.php?var_message=$errormessage");
        exit;
    } else {      // if email does not exist in the given companyid
        $sql1 = " INSERT INTO sptbl_users(`nUserId`, `nCompId`,`vUserName`,`vEmail`,`vLogin`,`vPassword`,`dDate`, `vBanned`, `vDelStatus`) ";
        $sql1 .= " VALUES('','" . addslashes($company) . "', '" . addslashes($name) . "','" . addslashes($email) . "','" . addslashes($loginname) . "','" . addslashes(md5($code)) . "',now(),'0','0')";
        $result1 = executeSelect($sql1, $conn);
        $id = mysql_insert_id();

        if (result1) {
            $sql = " Select * from sptbl_lookup where vLookUpName IN('Post2PostGap','MailFromName','MailFromMail',";
            $sql .="'MailReplyName','MailReplyMail','Emailfooter','Emailheader','AutoLock','HelpdeskTitle','HelpDeskURL','EmailURL','SMTPSettings','SMTPServer','SMTPPort')";
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

            //log user into the site
            $sql = "SELECT u.nUserId ,u.nCompId, u.vUserName , u.vEmail , u.vLogin , u.vBanned, u.vPassword,u.nCSSId,c.vCSSURL FROM sptbl_users u left outer join sptbl_css c on u.nCSSId = c.nCSSID   ";
            $sql .= " WHERE vLogin = '" . addslashes($loginname) . "' and  vPassword ='" . addslashes(md5($code)) . "' and vDelStatus='0' ";
            $result = executeSelect($sql, $conn);
            if (mysql_num_rows($result) > 0) {
                $row = mysql_fetch_array($result);
                if ($row["vBanned"] == "0") {
                    $userid = $row["nUserId"];
                    $username = $row["vLogin"];
                    $useremail = $row["vEmail"];
                    $userfullname = $row["vUserName"];
                    $compid = $row["nCompId"];
                    $cssurl = $row["vCSSURL"];
                    //$_SESSION["sess_cssurl"] = $cssurl;
                    $_SESSION["sess_username"] = $username;
                    $_SESSION["sess_userid"] = $userid;
                    $_SESSION["sess_useremail"] = $useremail;
                    $_SESSION["sess_userfullname"] = $userfullname;
                    $_SESSION["sess_usercompid"] = $compid;

                    $sql1 = "UPDATE sptbl_users  ";
                    $sql1 .= " SET vOnline = '1' WHERE nUserId = '" . $userid . "' ";
                    $result1 = executeSelect($sql1, $conn);
                    $sql2 = "Select nPriorityValue,vTicketColor,vPriorityDesc from sptbl_priorities ORDER BY nPriorityValue ";
                    $rs2 = executeSelect($sql2, $conn);

                    if (mysql_num_rows($rs2) > 0) {
                        $cnt = 0;
                        while ($row2 = mysql_fetch_array($rs2)) {
                            $fld_prio[$cnt][0] = $row2["nPriorityValue"];
                            $fld_prio[$cnt][1] = $row2["vTicketColor"];
                            $fld_prio[$cnt][2] = $row2["vPriorityDesc"];
                            $cnt++;
                        }
                    }
                    $_SESSION["sess_priority"] = $fld_prio;
                    mysql_free_result($rs2);
                } 
            } 

            $var_mail_body = $var_emailheader . "<br>" .
                    $var_mail_body .= MESSAGE_YOU_ARE_REGISTERED_WITH . $var_helpdeskname . "<br>";
            $var_mail_body .= MESSAGE_DETAILS_FOLLOW . "<br><br>";
            $var_mail_body .= TEXT_LOGIN_NAME . ": $loginname<br>";
            $var_mail_body .= TEXT_PASSWORD . ": $code<br><br>";
            /*
              if($auth_Status =='1'){  // if user authentication is on
              $var_mail_body .= MESSAGE_REGISTRATION_LINK . ": <a href='$var_emailurl"."activate.php?id=$id'>".$var_emailurl."activate.php?id=$id</a><br><br>";
              $var_mail_body .= TEXT_THANK_YOU_FOR_REGISTERING ."<br><br>";
              }
             */

            $var_mail_body .= $var_emailfooter;

            $var_body = $var_mail_body;
            $var_subject = MESSAGE_REGISTRATION_DETAILS;
            $var_email_to = $email;
            $Headers = "From: $var_fromName <$var_fromMail>\n";
            $Headers.="Reply-To: $var_replyName <$var_replyMail>\n";
            $Headers.="MIME-Version: 1.0\n";
            $Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

            
//            echo "<pre>";print($var_body);print($var_email_to);echo $var_fromMail;exit;
            // it is for smtp mail sending
            if ($_SESSION["sess_smtpsettings"] == 1) {
                $var_smtpserver = $_SESSION["sess_smtpserver"];
                $var_port = $_SESSION["sess_smtpport"];

                SMTPMail($var_fromMail, $var_email_to, $var_smtpserver, $var_port, $var_subject, $var_body);
            }
            else
                @mail($var_email_to, $var_subject, $var_body, $Headers);
        }
    }
   
    $_SESSION["sess_temp_username"] = $name;  //this is only for ticket posting. ie, it is not used for login purpose
    $_SESSION["sess_temp_usercompid"] = $company;
    $_SESSION["sess_temp_userid"] = $id;
    $_SESSION["sess_useremail"] = $var_email_to;
}
?>
<?php include("./includes/docheader.php"); ?>
        <title><?php echo HEADER_POST_TICKET; ?></title>
        <?php include("./includes/headsettings.php"); ?>
    </head>

    <body>
        <!--  Top Part  -->
        <?php
        include("./includes/top.php");
        ?>
        <!--  Top Ends  -->
        <!-- header  -->
        <?php
        include("./includes/header.php");
        ?>
        <!-- end header -->
		
		<div class="content_column_small">
		 <!-- sidelinks -->
                    <?php
                    include("./includes/userside.php");
                    ?>
                    <!-- End of side links -->
		</div>


<div class="content_column_big">
<!-- admin header -->
                    <?php
                    //include("./includes/userheader.php");
                    ?>
                    <!--  end admin header -->
                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Detail Section %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                    <?php
                    include("./includes/postticketbeforeregistersave.php");
                    ?>
                    <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End Detail Section %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
</div>
		
    
        
        <!-- Main footer -->
        <?php
        include("./includes/mainfooter.php");
        ?>
        <!-- End Main footer -->
   
</body>
</html>