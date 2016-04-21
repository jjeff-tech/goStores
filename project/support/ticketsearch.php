<?php
include("config/settings.php");
include("includes/session.php");
if (!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] == "")) {
    $_SP_language = "en";
} else {
    $_SP_language = $_SESSION["sess_language"];
}
include("includes/functions/dbfunctions.php");
include("includes/functions/miscfunctions.php");
include("includes/functions/impfunctions.php");
include("includes/main_smtp.php");
include("languages/$_SP_language/showticket.php");

$conn = getConnection();

$txtEmail = $_REQUEST['txtEmail'];

$txtTicketRef = $_REQUEST['txtTicketRef'];

$ticketfound = false;
if($txtEmail!='' && $txtTicketRef!='') {
    /*
    $sql  = "SELECT u.nUserId ,u.vEmail ,t.nTicketId, t.vRefNo, t.vTitle,t.merged_from,
                      FROM sptbl_users u INNER JOIN sptbl_tickets t on u.nUserId = t.nUserId   ";
    $sql .= " WHERE u.vEmail = '".addslashes($txtEmail)."' and  t.vRefNo = '".addslashes($txtTicketRef)."' and t.vDelStatus = '0' ";
    */

    $sql  = "SELECT u.nUserId ,u.vEmail ,t.*
             FROM sptbl_users u INNER JOIN sptbl_tickets t on u.nUserId = t.nUserId   ";
    $sql .= " WHERE u.vEmail = '".addslashes($txtEmail)."' and  t.vRefNo = '".addslashes($txtTicketRef)."'";
    $result = executeSelect($sql,$conn);
    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_assoc($result);  //echo '<pre>'; print_r($row['merged_to']); echo '</pre>'; exit;
        $userid   = $row["nUserId"];
        $useremail = $row["vEmail"];
        $title = $row["vTitle"];
        $var_tid = $row["nTicketId"];
        $var_userid =$userid;
        $mergedTo = $row['merged_to'];
        $ticketfound = true;

        if($row['merged_to']!=0 && $row['merged_to']!=""){ 
            echo json_encode(array("response"=>"merged","var_tid"=>$mergedTo,"var_userid"=>$var_userid,"var_type"=>"merged"));
        }else if($row['vDelStatus']!='1'){ 
            // echo "<a href='ticketpop.php' rel='#overlay' style='text-decoration:none'></a>";
            echo json_encode(array("response"=>"success","var_tid"=>$var_tid,"var_userid"=>$var_userid,"var_type"=>"normal"));
        }

    }else {
        echo json_encode(array("response"=>"failure"));
    }

    exit;

}
?>
