<?php
/*
 * test_smtp.php
 *
 * @(#) $Header: /home/mlemos/cvsroot/smtp/test_smtp.php,v 1.17 2006/06/11 04:31:42 mlemos Exp $
 *
*/

include("../phpmailer/class.phpmailer.php");

/* Uncomment when using SASL authentication mechanisms */
//require("sasl.php");

// SMTPMail('mahesh.s@armia.com','roshith@armia.com','armia.com','25','test subject','test body');

function SMTPMail($from,$to,$host,$port,$subject,$body,$attachments="",$cc="") { 
    
    //echo '<pre>'; print_r($body); echo '</pre>'; exit;
// $from=$from;  /* Change this to your address like "me@mydomain.com"; */ $sender_line=__LINE__;
// $to="";       /* Change this to your test recipient address */ $recipient_line=__LINE__;

    if(strlen($from)==0)
        die("Please set the messages sender address in line ".$sender_line." of the script ".basename(__FILE__)."\n");
    if(strlen($to)==0)
        die("Please set the messages recipient address in line ".$recipient_line." of the script ".basename(__FILE__)."\n");

    $host       = getSettingsValue('SMTPServer');
    $port       = getSettingsValue('SMTPPort');
    $username   = getSettingsValue('SMTPUsername');
    $password   = getSettingsValue('SMTPPassword');
    $sslenabled = getSettingsValue('SMTPEnableSSL');
    $sslstatus  = ($sslenabled=='1')?'ssl':'';

    $mail       = new PHPMailer();

    $mail->IsSMTP(); 		        // telling the class to use SMTP
    $mail->Host       = $host; 	        // SMTP server
    //$mail->SMTPDebug  = 2; // enables SMTP debug information (for testing)

    $mail->SMTPSecure = $sslstatus;
    $mail->SMTPAuth   = true;             // enable SMTP authentication
    $mail->Port       = $port;       // set the SMTP port for the GMAIL server
    $mail->Username   = $username;   // SMTP account username
    $mail->Password   = $password;

    $mail->AddReplyTo($from);
    $mail->SetFrom($from);
    $mail->AddAddress($to);
    $mail->Subject              = $subject;
    $mail->AltBody              = ''; // Optional, comment out and test.
    $mail->MsgHTML($body);        // Send mail as html.

    // Mail attachments
    if($attachments){
        foreach ($attachments as $key => $value) {
            $split_name_url = explode("*", $value);
            $mail->AddAttachment("../attachments/" . $split_name_url[0], "Attached here is " . $split_name_url[1]);
        }
    }

    // CC
    if($cc){
        $mail->AddCC($cc);
    }
    
    $mailsent                   = $mail->Send();


//    $smtp=new smtp_class;
//
//    $smtp->host_name=$host;             /* Change this variable to the address of the SMTP server to relay, like "smtp.myisp.com" */
//    $smtp->host_port=$port;                /* Change this variable to the port of the SMTP server to use, like 465 */
//    $smtp->ssl=0;                       /* Change this variable if the SMTP server requires an secure connection using SSL */
//    $smtp->localhost="localhost";       /* Your computer address */
//    $smtp->direct_delivery=0;           /* Set to 1 to deliver directly to the recepient SMTP server */
//    $smtp->timeout=10;                  /* Set to the number of seconds wait for a successful connection to the SMTP server */
//    $smtp->data_timeout=0;              /* Set to the number seconds wait for sending or retrieving data from the SMTP server.
//	                                       Set to 0 to use the same defined in the timeout variable */
//    $smtp->debug=1;                     /* Set to 1 to output the communication with the SMTP server */
//    $smtp->html_debug=1;                /* Set to 1 to format the debug output as HTML */
//    $smtp->pop3_auth_host="";           /* Set to the POP3 authentication host if your SMTP server requires prior POP3 authentication */
//    $smtp->user="";                     /* Set to the user name if the server requires authetication */
//    $smtp->realm="";                    /* Set to the authetication realm, usually the authentication user e-mail domain */
//    $smtp->password="";                 /* Set to the authetication password */
//    $smtp->workstation="";              /* Workstation name for NTLM authentication */
//    $smtp->authentication_mechanism=""; /* Specify a SASL authentication method like LOGIN, PLAIN, CRAM-MD5, NTLM, etc..
//	                                       Leave it empty to make the class negotiate if necessary */
//
//    /*
//	 * If you need to use the direct delivery mode and this is running under
//	 * Windows or any other platform that does not have enabled the MX
//	 * resolution function GetMXRR() , you need to include code that emulates
//	 * that function so the class knows which SMTP server it should connect
//	 * to deliver the message directly to the recipient SMTP server.
//    */
//    if($smtp->direct_delivery) {
//        if(!function_exists("GetMXRR")) {
//            /*
//			* If possible specify in this array the address of at least on local
//			* DNS that may be queried from your network.
//            */
//            $_NAMESERVERS=array();
//            include("getmxrr.php");
//        }
//        /*
//		* If GetMXRR function is available but it is not functional, to use
//		* the direct delivery mode, you may use a replacement function.
//        */
//        /*
//		else
//		{
//			$_NAMESERVERS=array();
//			if(count($_NAMESERVERS)==0)
//				Unset($_NAMESERVERS);
//			include("rrcompat.php");
//			$smtp->getmxrr="_getmxrr";
//		}
//        */
//    }
//
//    if($smtp->SendMessage(
//    $from,
//    array(
//    $to
//    ),
//    array(
//    "From: $from",
//    "To: $to",
//    "Subject: $subject",
//    "Date: ".strftime("%a, %d %b %Y %H:%M:%S %Z")
//    ),$body."\n\nThank You.\n"));
////		echo "Message sent to $to OK.\n";
//    else ;
////		echo "Cound not send the message to $to.\nError: ".$smtp->error."\n";


    
}
?>