<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php

class ControllerStreamsend extends BaseController {


 public function streamsendtest() {
        
         $person = array("email" => "akhil.n@armiasystems.com",
                    "fname" => "gostores123455",
                    "lname" => "lgostores",
            );
        $streamsendSettings =Streamsendlogic::getStreamsendSettings();
         
       if(Streamsendlogic::addUserToStreamsend($streamsendSettings, $person)){
           echo 'sucess';
       }else{echo 'fail';}
      //  exit;

        exit;
    }

    public function sendstreamsendmail() {
       echo 'here';exit;
         $arrMailcontent = array("Sitename" => "Gostores",
                    "Adminemail" => "akhil.n@armiasystems.com",
                    "Subject" => "Test Subject2",
                    "Mailcontent" => "Mail Body2",
                    "Scheduledfor" => "2012-11-20T3:00:00Z"
            );
        $streamsendSettings =Streamsendlogic::getStreamsendSettings();
       if(Streamsendlogic::sendStreamsendMails($streamsendSettings, $arrMailcontent)){
           echo 'sucess';
       }else{echo 'fail';}
      //  exit;

        exit;
    }
    public static function getemailtempalte() {
      $emailTemplate =  Streamsendlogic::getemailTemplate("Sample template1");
      $emailList = Streamsendlogic::getemailList();
    //  print_r($emailList);exit;
      foreach ($emailList as $key => $user) {
          
          $subject = "Subject";
          $usermail = $user->vEmail;
          $message = $emailTemplate;
          // Send Mail

        $headers	= "" ;
        $headers 	.= "From: " . $myname." <" . $myemail . ">\r\n";
        $headers 	.= "Reply-To: " . $myname. " <" . $myemail . ">\r\n";
        $headers 	.= "MIME-Version: 1.0\r\n";
        $headers 	.= "Content-type: text/html; charset=iso-8859-1\r\n";

        
        $flagMail	= @mail($usermail, $subject, $message, $headers);

      }
       exit;
        
    }
public function getemailtemplatelist() {


        $emailTemplateList =Streamsendlogic::getemailTemplateList();
      echopre($emailTemplateList);

        exit;
    }


}// end class

?>