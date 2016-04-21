<?php
   require_once("./includes/applicationheader.php");
   include_once("./parser/functions.php");
   include("./languages/".$_SP_language."/client_chat.php");
   $chatid=isset($_GET["chatid"]) ? $_GET["chatid"] : '' ;
   $email=isset($_GET["email"]) ? $_GET["email"] : '' ;
   
   if ( ($chatid != '') && ($email != '')) {
     $conn = getConnection();
     $arr_lookupvalues = getLookupDetails();
     $sql = "select c.tMatter,c.dTimeStart,c.dTimeEnd,c.vUserName,s.vStaffname,vStatus from sptbl_chat c left join sptbl_staffs s on ( c.nStaffId = s.nStaffId ) inner join sptbl_users u on (c.nUserId = u.nUserId) where nChatId='".$chatid."'";
     $result = executeSelect($sql,$conn);
     if ( mysql_num_rows($result) > 0 ) {
        while ($row = mysql_fetch_array($result)) {
		  $matter= $row["tMatter"];
		  $stime= $row["dTimeStart"];		
		  $user= $row["vUserName"];
		  $staff= $row["vStaffname"];
		  $sts= $row["vStatus"]; 
        }
     }
     $var_body = $arr_lookupvalues['var_emailheader'] ."<br>";
	 $var_body .= TEXT_MAIL_START.",<br>&nbsp;<br>";
	 $var_body .= TEXT_MAIL_CHATLOG_BODY;
	 if ( $sts != 'pending' ) $var_body .= " ".TEXT_CHAT_BETWEEN." ". $user . " ".  TEXT_AND . " " . $staff;
	 $var_body .= "<br>&nbsp;<br><table><tr><td>";
     $var_body .= $matter ;
     $var_body .= "</td></tr></table><br>";
	 $var_body .= TEXT_MAIL_THANK."<br>";
	 $var_body .= $arr_lookupvalues['var_emailfooter']."</br>";
     $var_subject = TEXT_MAIL_CHATLOG_SUBJECT1;
	 if ( $sts != 'pending' ) $var_subject .= TEXT_MAIL_CHATLOG_SUBJECT2.htmlentities($stime);
     $Headers="From: " . $arr_lookupvalues['var_fromMail'] . "\n";
     $Headers.="Reply-To: " . $arr_lookupvalues['var_replyMail'] . "\n";
     $Headers.="MIME-Version: 1.0\n";
     $Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	 if($var_smtp_status == 1){ 
		$var_smtpserver = $arr_lookupvalues['SMTPServer'];
		$var_port = $arr_lookupvalues['SMTPPort'];
		SMTPMail($arr_lookupvalues['var_fromMail'],$email,$var_smtpserver,$var_port,$var_subject,$var_body);
	 } else @mail($email,$var_subject,$var_body,$Headers); 
	 echo "send";
  } else {
    echo "error";
  }
  
?>