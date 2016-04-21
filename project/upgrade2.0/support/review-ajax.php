<?php
require_once("./includes/applicationheader.php");
include "languages/".$_SP_language."/postticketkb.php";
$conn = getConnection();

$user_id         =   $_POST['user_id'];
$ticket_id       =   $_POST['ticket_id'];
$clickedStarVal  =   $_POST['clickedStarVal'];
$staff_id        =   $_POST['staff_id'];
$comment         =   $_POST['comment'];

$sqlratingexist  =  "SELECT nSRId FROM sptbl_staffratings 
                     WHERE nUserId ='".$user_id."'
                     AND nStaffId ='".$staff_id."'
                     AND nTicketId ='".$ticket_id."'";
$resratingexist  =   executeSelect($sqlratingexist,$conn);
//if(mysql_num_rows($resratingexist)==0){
    $sqlrating       =  "INSERT INTO  sptbl_staffratings(
                            nUserId,
                            nStaffId,
                            nTicketId,
                            tComments,
                            nMarks
                            ) VALUES(
                            '".addslashes($user_id)."',
                            '".addslashes($staff_id)."',
                            '".addslashes($ticket_id)."',
                            '".addslashes($comment)."',
                            '".addslashes($clickedStarVal)."')";
    executeQuery($sqlrating,$conn);
    $insertedId =mysql_insert_id();
    if($insertedId >0){
        $data = array('success' => 1);
        echo json_encode($data);
        exit;
    }
    else{
        $data = array('error' => 1);
        echo json_encode($data);
        exit;
    }
//}
//else{
//        $data = array('duplicate' => 1);
//        echo json_encode($data);
//        exit;
//}

?>