<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cls_mailer
 *
 * @author anoop
 */
class Mailer {
    //put your code here
    
    public function sendSmtpMail($emailContents) {
        
        
        //echopre($emailContents); 
       
    	
    	$db                = new Db();
    	
        PageContext::includePath('phpmailer');
    	$mail               	= new PHPMailer();
    	 
        $smtp_username          	= $db->selectRow("Settings","value","settingfield='smtp_username'");
        $smtp_password      	= $db->selectRow("Settings","value","settingfield='smtp_password'");
        $smtp_host         	= $db->selectRow("Settings","value","settingfield='smtp_host'");
        $smtp_port     	= $db->selectRow("Settings","value","settingfield='smtp_port'");
        $smtp_protocol          	= $db->selectRow("Settings","value","settingfield='smtp_protocol'");
         
         // echopre($smtp_username);
		$mailBody   			= $emailContents['message'];
		
	
                
                $mail->IsSMTP();
	    	$mail->Host       = $smtp_host; 		// SMTP server example
			$mail->SMTPDebug  = 1;      // enables SMTP debug information (for testing)
			$mail->SMTPAuth   = true;                  		// enable SMTP authentication
			$mail->Port       = $smtp_port;       // set the SMTP port for the GMAIL server
			$mail->Username   = $smtp_username; 	// SMTP account username example
			$mail->Password   = $smtp_password;   // SMTP account password example
			$mail->SMTPSecure = $smtp_protocol;
                
		
        
       
       
       
      
        $mail->AddReplyTo($emailContents['from'],SITE_NAME);
        $mail->SetFrom($emailContents['from'],SITE_NAME);
       
        
        	$mail->AddAddress($emailContents['to'], $emailContents['to']);
        $mail->Subject              = $emailContents['subject'];
        $mail->AltBody              = ''; // Optional, comment out and test.
    	$mail->MsgHTML($mailBody);
    	
        
        try {
                $mailsent           = $mail->Send();
        }catch(Exception $e) {
  //echo 'Message: ' .$e->getMessage();
}   
                
                
               // echopre1($mailsent);
        return true;
    	 
    	 
    }
    
    
}
