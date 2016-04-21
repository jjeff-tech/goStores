<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshith<roshith@armia.com>		                          |
// |          									                          |
// +----------------------------------------------------------------------+
		//include("./includes/settings.php");
		include("./config/settings.php");
        include("./includes/session.php");
//        include("./includes/functions/dbfunctions.php");
//        include("./includes/functions/miscfunctions.php");
//        include("./includes/functions/impfunctions.php");
    require_once("./includes/applicationheader.php");
		
		/*/*ini_set('magic_quotes_runtime',0);*/*/
       if (get_magic_quotes_gpc()) {
            $_POST = array_map('stripslashes_deep', $_POST);
            $_GET = array_map('stripslashes_deep', $_GET);
            $_COOKIE = array_map('stripslashes_deep', $_COOKIE);

        }

        if(!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] =="") ){
                $_SP_language = "en";
        }else{
                $_SP_language = $_SESSION["sess_language"];
        }
        include("./languages/".$_SP_language."/main.php");
        include "languages/".$_SP_language."/resetpass.php";
        $conn = getConnection();

        if((isset($_GET["code"]) and $_GET["code"] != "")){

                $sql  = "SELECT nUserId , vUserName , vEmail , vLogin , vPassword FROM sptbl_users  ";
                $sql .= " WHERE vCodeForPass = '".addslashes($_GET["code"])."' ";
                $result = executeSelect($sql,$conn);
                if (mysql_num_rows($result) > 0) {
                        $row = mysql_fetch_array($result);
                        $userid   = $row["nUserId"];
                        $username = $row["vLogin"];
                        $useremail = $row["vEmail"];
                        $userfullname = $row["vUserName"];

                        $newpass = rand(1, 999999);

                        $sql  = "UPDATE sptbl_users  ";
                        $sql .= " SET vCodeForPass = NULL, vPassword='".addslashes(md5($newpass))."' WHERE nUserId = '".$userid."' ";
                        $result = executeSelect($sql,$conn);

                        

                        //mailing
						$sql = " Select * from sptbl_lookup where vLookUpName IN('Post2PostGap','MailFromName','MailFromMail',";
						$sql .="'MailReplyName','MailReplyMail','Emailfooter','Emailheader','AutoLock','HelpdeskTitle','SMTPSettings','SMTPServer','SMTPPort')";
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
						$var_mail_body .= TEXT_YOUR_PASSWORD_RESET ."<br>";
						$var_mail_body .= MESSAGE_DETAILS_FOLLOW. "<br><br>";
						$var_mail_body .= TEXT_USER_ID . ": $username<br>";
						$var_mail_body .= TEXT_PASSWORD . ": $newpass<br><br>";
						$var_mail_body .= $var_emailfooter;
						
						$var_body = $var_mail_body;
						$var_subject = MESSAGE_PASSWORD_RESET ;
						$var_email_to = $useremail;
						$Headers="From: $var_fromName <$var_fromMail>\n";
						$Headers.="Reply-To: $var_replyName <$var_replyMail>\n";
						$Headers.="MIME-Version: 1.0\n";
						$Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

						 // it is for smtp mail sending
						 if($_SESSION["sess_smtpsettings"] == 1){
								$var_smtpserver = $_SESSION["sess_smtpserver"];
								$var_port = $_SESSION["sess_smtpport"];
					
								SMTPMail($var_fromMail,$var_email_to,$var_smtpserver,$var_port,$var_subject,$var_body);
						 }
						 else				
								@mail($var_email_to,$var_subject,$var_body,$Headers);						
						/*
						$from = $glb_siteemail;
                        $to = $useremail;
                        $subject = MESSAGE_PASSWORD_RESET;

                        $headers  = "MIME-Version: 1.0\r\n";
                        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

                        @mail($to, $subject, $emailcontent, $headers);*/
                		//echo $emailcontent;
                		//header("Location: index.php");
                		//echo "<script>window.location.href='index.php';</ script>";
                		//exit;
                        $message = MESSAGE_PASSWORD_RESET_AND_MAILED_TO . "'".$useremail."'";
                }else{//no match
                        $error = true;
                        $errormessage = MESSAGE_ERROR_OCCURED;
                }
        }


?>
<html>
<head>
<title><?php echo HEADER_WELCOME;?></title>
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

    </td>
    <td width="1" rowspan="2"><img src="images/spacerr.gif" width="1" height="1"></td>
  </tr>
  <tr>
    <td align="left">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr class="column1">
          <td width="21%" valign="top">
			   <!-- sidelinks -->
			   <?php
					  include("./includes/userside.php");
			   ?>
			   <!-- End of side links -->
          </td>
          <td width="79%" valign="top" class="whitebasic">
			<!-- admin header -->
			<?php
					include("./includes/userheader.php");
			?>
			<!--  end admin header -->
            <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Detail Section %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
                  <?php
                  if($message != ""){ ?>

                  <table width="400"  border="0" cellspacing="10" cellpadding="0" align="center">
              <tr>
                <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td><img src="images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="1" ><img src="images/spacerr.gif" width="1" height="1"></td>
                        <td class="pagecolor"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td align="left" class="listing"><p><?php echo $message;?></p></td>
                                  </tr>
                              </table></td>
                            </tr>
                        </table></td>
                        <td width="1" ><img src="images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                    </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td ><img src="images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                  </table></td>
              </tr>
            </table>
                  <?php }
                  if ($error) {?>
                  <table width="400"  border="0" cellspacing="10" cellpadding="0" align="center">
              <tr>
                <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td><img src="images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="1" ><img src="images/spacerr.gif" width="1" height="1"></td>
                        <td class="pagecolor"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                            <tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td align="left" class="errormessage"><p><?php echo $errormessage;?></p></td>
                                  </tr>
                              </table></td>
                            </tr>
                        </table></td>
                        <td width="1" ><img src="images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                    </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td ><img src="images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                  </table></td>
              </tr>
            </table>

                  <?php

                  }
                 ?>

          <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% End Detail Section %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
          </td>
        </tr>
      </table>
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
    </td>
  </tr>
</table>
</body>
</html>