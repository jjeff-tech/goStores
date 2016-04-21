<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>                                  |
// |                                                                                                            |
// +----------------------------------------------------------------------+
require_once("./includes/applicationheader.php");
include("./includes/functions/miscfunctions.php");
include("./languages/".$_SP_language."/cron_staffreportmail.php");
$conn = getConnection();

// Get mail settings
$sql = " Select * from sptbl_lookup where vLookUpName IN('MailFromName','MailFromMail',";
$sql .= "'MailReplyName','MailReplyMail','Emailfooter','Emailheader','MailAdmin','HelpdeskTitle')";
$result = executeSelect($sql, $conn);
if (mysql_num_rows($result) > 0) {
    while ($row2 = mysql_fetch_array($result)) {
        switch ($row2["vLookUpName"]) {
            case "MailFromName":
                $var_fromName = $row2["vLookUpValue"];
                break;
            case "MailFromMail":
                $var_fromMail = $row2["vLookUpValue"];
                break;
            case "MailReplyName":
                $var_replyName = $row2["vLookUpValue"];
                break;
            case "MailReplyMail":
                $var_replyMail = $row2["vLookUpValue"];
                break;
            case "Emailfooter":
                $var_emailfooter = $row2["vLookUpValue"];
                break;
            case "Emailheader":
                $var_emailheader = $row2["vLookUpValue"];
                break;
            case "MailAdmin":
                $var_emailadmin = $row2["vLookUpValue"];
                break;
            case "HelpdeskTitle":
                $var_helpdesktitle = $row2["vLookUpValue"];
                break;
        }
    }
}

$lastTime = strtotime("-1 week");
$lastWeek = date("Y-m-d", $lastTime); //Week 
$today = date("Y-m-d");

$datecondition = " AND DATE_FORMAT(t.dPostDate,'%Y-%m-%d') >='" . addslashes($lastWeek) . "' AND DATE_FORMAT(t.dPostDate,'%Y-%m-%d') <= '" . addslashes($today) . "'";
// Get Ticket status
$sql_tick_status = "SELECT DISTINCT t.vStatus  FROM sptbl_tickets t WHERE  t.vDelStatus = '0'
                        ORDER BY vStatus ASC LIMIT 0, 15";

$rs_tick_status = mysql_query($sql_tick_status) or die(mysql_error());//Selct ticket status
$status_count = mysql_num_rows($rs_tick_status);
$ticket_status_array = array();
if($status_count > 0) { //Ticket Status Count  if
    $mail_report= " <table width='100%'  border='0' cellpadding='0' cellspacing='0'>


                                                <tr align='left'>
                                                        <th width='15%'>". TEXT_SIDE_STAFF ."</th>";
    $st_per =  85/$status_count;
    while($row_tick_status = mysql_fetch_array($rs_tick_status)) {
        $ticket_status_array[] = $row_tick_status['vStatus'];//Ticket status Assign to array
        $mail_report.=   "<th width='".$st_per."%'>". $row_tick_status['vStatus'] ."</th>";
    }

    $mail_report.=   " </tr>";

    //Select Staff
    $sql_staff = "SELECT DISTINCT s.vStaffname,t.vStaffLogin  FROM sptbl_tickets t
                        INNER JOIN sptbl_staffs s ON t.vStaffLogin = s.vLogin
                        WHERE  t.vDelStatus = '0'
                        ORDER BY s.vStaffname ASC";
    $rs_staff = mysql_query($sql_staff) or die(mysql_error());

    if(mysql_num_rows($rs_staff) > 0) { //satff  if

     while($row_staff = mysql_fetch_array($rs_staff)){// get Staff
//Get staff ticket status counts
       $sql_ticketcount = "SELECT DISTINCT count(t.nTicketId) as ticketcount , t.vStatus
                    FROM sptbl_tickets t
                    WHERE  t.vDelStatus = '0' AND t.vStaffLogin = '" . $row_staff['vStaffLogin'] ."' ". $datecondition  ." GROUP BY vStatus";
        $rs_ticketcount = mysql_query($sql_ticketcount) or die(mysql_error());
        $staff_ticketcount =array();
        if(mysql_num_rows($rs_ticketcount) > 0) {
            while($row_ticket_count = mysql_fetch_array($rs_ticketcount)) {
                $staff_ticketcount[$row_ticket_count['vStatus']] = $row_ticket_count['ticketcount'];
            }
        }

        $mail_report.=   "<tr align='left'>
<td width='15%'>". stripslashes($row_staff['vStaffname']) ."</td>";
        foreach ($ticket_status_array as $key => $value) {
            $tick_count_temp = $staff_ticketcount[$value] == ""? "0" :$staff_ticketcount[$value];
            $mail_report.=   "<td width='".$st_per."%'>". $tick_count_temp ."</td>";
        }
     }
    }//satff  if ends

    $mail_report.=   " </table>";

}//Ticket Status Count End if


$var_body = $var_emailheader . "<br>" . HI . "&nbsp; Admin,<br>";
$var_body.= TEXT_REPORTS_STAFF_SUMMARY ."<br>".$mail_report."<br>";
 $var_body .= TEXT_MAIL_THANK . "<br>" . htmlentities($var_helpdesktitle) . "<br>" . $var_emailfooter;
//echo $var_body;
$var_subject = TEXT_REPORTS_STAFF_SUMMARY;
$Headers = "From: $var_fromName <$var_fromMail>\n";
$Headers .= "Reply-To: $var_replyName <$var_replyMail>\n";
$Headers .= "MIME-Version: 1.0\n";
$Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

// it is for smtp mail sending
if($_SESSION["sess_smtpsettings"] == 1) {
    $var_smtpserver = $_SESSION["sess_smtpserver"];
    $var_port = $_SESSION["sess_smtpport"];

    SMTPMail($var_fromMail,$var_emailadmin,$var_smtpserver,$var_port,$var_subject,$var_body);
}
else
    $mailstatus = @mail($var_emailadmin, $var_subject, $var_body, $Headers);

?>
