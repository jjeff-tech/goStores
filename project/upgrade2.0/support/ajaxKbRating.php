<?php
require_once("./includes/applicationheader.php");
//include "languages/".$_SP_language."/postticketkb.php";
$conn = getConnection();

$user_id         =   $_POST['user_id'];
$kb_id       =   $_POST['kb_id'];
$clickedStarVal  =   $_POST['clickedStarVal'];


$sqlratingexist  =  " SELECT sKBRId FROM  sptbl_kb_rating
                      WHERE nUserId ='".$user_id."' 
                      AND nKBID ='".$kb_id."'";

$resratingexist  =   executeSelect($sqlratingexist,$conn);
if(mysql_num_rows($resratingexist)==0){
    $sqlrating       =  "INSERT INTO  sptbl_kb_rating(
                            nUserId,
                            nKBID,
                            nMarks
                            ) VALUES(
                            '".addslashes($user_id)."',
                            '".addslashes($kb_id)."',
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
}
else{
        $data = array('duplicate' => 1);
        echo json_encode($data);
        exit;
}

?>