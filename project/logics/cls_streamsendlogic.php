<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Streamsendlogic{

 public static function  addUserToStreamsend($arrStreamSendSettings = array(), $person  = array()) {

    PageContext::includePath('streamsend');


    $objStreamsend = new StreamsendAPI();
    //print_r($arrStreamSendSettings);exit;

        //*****************Add user to Streamsend ************
        if(isset($arrStreamSendSettings['streamsend_loginid']) && isset($arrStreamSendSettings['streamsend_key']) && isset($arrStreamSendSettings['streamsend_listid']) && $arrStreamSendSettings['streamsend_enable'] == 'on' && count($person) > 0 ) {
           
            
            $objStreamsend->loginID    = $arrStreamSendSettings['streamsend_loginid'];
            $objStreamsend->key        = $arrStreamSendSettings['streamsend_key'];
            $objStreamsend->listID     = $arrStreamSendSettings['streamsend_listid'];

            //add member
           /* $person = array("email" => $arrUserDetails["email"],
                    "fname" => $arrUserDetails["fname"],
                    "lname" => $arrUserDetails["lname"],
            );*/

            $objStreamsend->addstreamsendpeople($objStreamsend->getXMLAddEmail($person));

            $mid = $objStreamsend->getpeople($person['email']);

            $objStreamsend->addmembership($mid);
            return true;
        }
        //***********************************
        return false;
    }

     public static function  getStreamsendSettings($id="") {
         $dbObj = new Db(); 
         $streamsendSettings = array();
         $streamsendSettings['streamsend_enable'] =  $dbObj->selectRow("Settings","value","settingfield='streamsend_enable'");
         $streamsendSettings['streamsend_loginid'] =  $dbObj->selectRow("Settings","value","settingfield='streamsend_loginid'");
         $streamsendSettings['streamsend_key'] =  $dbObj->selectRow("Settings","value","settingfield='streamsend_key'");
         $streamsendSettings['streamsend_listid'] =  $dbObj->selectRow("Settings","value","settingfield='streamsend_listid'");
         return $streamsendSettings;
     }

public static function  sendStreamsendMails($arrStreamSendSettings = array(), $arrMailcontent  = array()) {

    PageContext::includePath('streamsend');


    $objStreamsend = new StreamsendAPI();
    //print_r($arrStreamSendSettings);exit;

        //*****************Add user to Streamsend ************
        if(isset($arrStreamSendSettings['streamsend_loginid']) && isset($arrStreamSendSettings['streamsend_key']) && isset($arrStreamSendSettings['streamsend_listid']) && $arrStreamSendSettings['streamsend_enable'] == 'on' && count($arrMailcontent) > 0 ) {


            $objStreamsend->loginID    = $arrStreamSendSettings['streamsend_loginid'];
            $objStreamsend->key        = $arrStreamSendSettings['streamsend_key'];
            $objStreamsend->listID     = $arrStreamSendSettings['streamsend_listid'];

            // $scheduledfor = "2012-11-20T3:00:00Z";
           
            $newsletter = array(
                    "frname" => $arrMailcontent['Sitename'],
                    "frmail" => $arrMailcontent['Adminemail'],
                    "subject" => $arrMailcontent['Subject'],
                    "mailtemp" => $arrMailcontent['Mailcontent'],
                    "scheduledfor" => $arrMailcontent['Scheduledfor']
            );

            //activate from maile
            //if($objStreamsend->fromemailactivation($arrMailcontent['Adminemail'])){echo 'in';exit;
            //send mail
            $objStreamsend->fromemailactivation($arrMailcontent['Adminemail']);
            $newsletterxml = $objStreamsend->getXMLSendEmail($newsletter);
            $mailStatus =    $objStreamsend->sendstreamsendmail($newsletterxml);
            if($mailStatus == 'sucess')
                    return true;
            else
                return false;

        }
        //***********************************
        return false;
    }


public static function  getemailTemplateByName($templateName){

    $dbObj = new Db();
   // $emailTemplate =  $dbObj->selectRow("EmailTemplates","temailTemplate","vemailTemplateName='$templateName'");
    $emailTemplate =  $dbObj->selectRow("EmailTemplates","temailTemplate","vemailTemplateName='$templateName' AND estatus = 'Active'");
    return $emailTemplate;
}

public static function  getemailTemplateByID($templateID){

    $dbObj = new Db();
   // $emailTemplate =  $dbObj->selectRow("EmailTemplates","temailTemplate","vemailTemplateName='$templateName'");
    $emailTemplate =  $dbObj->selectRow("EmailTemplates","temailTemplate","nETId='$templateID' AND estatus = 'Active'");
    return $emailTemplate;
}


public static function  getemailList(){
    $dbObj = new Db();
    $userData        = array();
    $userData        = $dbObj->selectResult('User',"vUsername,vEmail","nStatus='".ACTIVE_STATUS."'");
    return $userData;
}
public static function  getemailTemplateList(){

    $dbObj = new Db();
   // $emailTemplate =  $dbObj->selectRow("EmailTemplates","temailTemplate","vemailTemplateName='$templateName'");
    $emailTemplate =  $dbObj->selectResult("EmailTemplates","nETID,vemailTemplateName","estatus = 'Active'");
   
    foreach($emailTemplate as $template) {
       $temp =new stdClass();
       $temp->value =$template->nETID;
       $temp->text =$template->vemailTemplateName;
       $result[] =$temp;
   }
    return $result;
}

public static function  setScheduleMail($id){ 
    $dbObj = new Db();
    $siteName =  $dbObj->selectRow("Settings","value","settingfield='siteName'");
    $siteEmail =  $dbObj->selectRow("Settings","value","settingfield='adminEmail'");
    $emailSchedule =  $dbObj->selectResult("EmailTemplatesMails","vMailName, nETID, tScheduleTime, nMailMode","nETMId='".$id. "'");
    $streamsendSettings =Streamsendlogic::getStreamsendSettings(); 
    if(count($streamsendSettings) >= 4 && $streamsendSettings['streamsend_enable'] == 'Y'){
    if(count($emailSchedule) > 0) {
        $mailTemplate = Streamsendlogic::getemailTemplateByID($emailSchedule[0]->nETID);
        $scheduleTime = "2012-11-20T3:00:00Z";
        $tempTime = $emailSchedule[0]->tScheduleTime;
        $arTime =  explode(" ", $tempTime);
        $tempdate = "2012-11-20";
        if(count($arTime) > 0) {
            $tempdate = $arTime[0];
        }
        $scheduleTime = $tempdate . "T3:00:00Z" ;
        $arrMailcontent = array("Sitename" => $siteName,
                "Adminemail" => $siteEmail,
                "Subject" => $emailSchedule[0]->vMailName,
                "Mailcontent" => $mailTemplate,
                "Scheduledfor" => $scheduleTime
        );
        
        if(Streamsendlogic::sendStreamsendMails($streamsendSettings, $arrMailcontent)) {
             $arrUpdate       = array('nMailMode'=>('Streamsend'));
            $dbObj->updateFields("EmailTemplatesMails",$arrUpdate,"nETMId 	= '".$id."'");
            return true;
        }else {
            return false;
        }
    }
     return false;
      //  exit;
}else{
    $arrUpdate       = array('nMailMode'=>('Mail'));
   $dbObj->updateFields("EmailTemplatesMails",$arrUpdate,"nETMId 	= '".$id."'");
    
}
 return false;
}



public static function  addUseraToList($arrUser){

     $person = array("email" => $arrUser['Email'],
                    "fname" => $arrUser['FirstName'],
                    "lname" => $arrUser['LasttName']
            );
        $streamsendSettings =Streamsendlogic::getStreamsendSettings();

       if(Streamsendlogic::addUserToStreamsend($streamsendSettings, $person)){
          return true;// 'sucess';
       }else{
           return false;// 'fail';
           }
}


}//End of class
?>
