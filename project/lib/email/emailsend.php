<?php
// This class will help in sending emails
class Emailsend
{
	
    public $msg     = '';
    public $subject = '';
    public $from    = '';
    public $to      = '';
    public $msgType = 0;
    public $msgPid  = 0;
	
    public function email_senderNow($account=array())
    {
        //function for getting the values and passing it for sending
        $this->from     = $account['from'];
        $this->subject  = $account['subject'];
        $this->msg      = $account['message'];

                
//        if($this->isvalidemail($account['to']))
        if(1)
        {
                $this->to = $account['to'];
                $this->send_email();
        }
        else
        {
                //echo "<script>alert('Email incorrect".$account['to']."')</script>";
        }
    }
	
    public function isvalidemail($email='')
    {
         //checking for valid email id  // Function depricated
        /*if (ereg("^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@+([_a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]{2,200}\.[a-zA-Z]{2,6}$",$email) )
           return true;
         else
           return false;
         */

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function headers()
    {
        //function for adding mail headers
	$headers = 'From: '.$this->from.'' . "\r\n" .
        'Reply-To: '.$this->from.'' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
	$headers  .= 'MIME-VERSION 1.0'."\r\n" ;
        $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n" ;
	return $headers; 
    }
    public function body()
    {
        //function for adding contents
        $body = $this->msg;
        $body=str_replace('\n', "<BR>", $body);
        $body=str_replace('\t', "&nbsp;", $body);
        return $body;	
    }
    
    
    public function send_email()
    {
	$to      = $this->from;
        if(mail($this->to,$this->subject, $this->body(), $this->headers()))
             {
               return true; //Email send successfully
             }
    }

    public function escapeData($data = '')
    {
        $data = mysql_real_escape_string($data);
        return $data;
    }
    
}
?>