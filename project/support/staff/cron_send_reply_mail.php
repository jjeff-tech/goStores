<?php

$INSTALLED = true;
require_once("./includes/applicationheader.php");
require_once("./includes/functions/miscfunctions.php");
require_once("../includes/MIME.class");


$getReplyMailsSql   = "SELECT * from sptbl_ticket_mail_replies WHERE DATEDIFF(NOW(),sendAt) >= 0";
$replymailsRes      = mysql_query($getReplyMailsSql);

if(mysql_num_rows($replymailsRes) > 0){
    while($mailRow  = mysql_fetch_array($replymailsRes)){
        echo "<pre>";print_r($mailRow);echo "</pre>";
        $from       = $mailRow["fromMail"];
        $to         = $mailRow["toMail"];
        $subject    = stripslashes($mailRow["subject"]);
        $body       = stripslashes($mailRow["mailBody"]);
        $headers    = $mailRow["mailHeader"];
        $replyId    = $mailRow["replyId"];
        
        $replyPId   = $mailRow["replyPId"];
        
        $attachedFile   = $mailRow["attachment"];
        
        /*
        * To change this template, choose Tools | Templates
        * and open the template in the editor.
        */

        $mime = new MIME_mail($from, $to, $subject, $body, $headers);
        if($attachedFile !=""){
              $vAttacharr   = explode("|",$attachedFile);
                     foreach($vAttacharr as $key=>$value){
                        $split_name_url = explode("*",$value);
                        $mime->fattach("./attachments/" . $split_name_url[0], "Attached here is " . $split_name_url[1]);
                     }
             }
        $mime->send_mail();
        
        /*
         * Change action log status
         */
        
        $actionLogId    = $mailRow["actionLogId"];
        $actionLogSql   = "UPDATE sptbl_actionlog SET logStatus = 'Y' WHERE nALId = $actionLogId";
        mysql_query($actionLogSql) or die(mysql_error());
        
        /*
         * delete the reply mail form db
         */
        
        $deleteMailSql  = "DELETE FROM sptbl_ticket_mail_replies WHERE replyId = '".$replyId."'";
        mysql_query($deleteMailSql);
        
        /*
         * update reply mail sent status in ticket replies table
         */
        
        $updateReplySql = "UPDATE sptbl_replies SET eReplySentstatus = 'Y' WHERE nReplyId = '".$replyPId."'";
        mysql_query($updateReplySql) or die(mysql_error());
        
    }
}


?>
