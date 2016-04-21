<?php
include("config/settings.php");
include("includes/session.php");
include("includes/functions/dbfunctions.php");
include("includes/functions/miscfunctions.php");

if (!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] == "")) {
    $_SP_language = "en";
} else {
    $_SP_language = $_SESSION["sess_language"];
}

include "languages/".$_SP_language."/viewkbentry.php";
include("includes/functions/functions.php"); 

$conn = getConnection();

$user_id         =   $_POST['user_id'];
$kb_id           =   $_POST['kb_id']; 
$clickedStarVal  =   $_POST['clickedStarVal'];
$ip              = '';
if($user_id <= 0){
   $ip = getClientIP();
} 
//echo '<pre>'; print_r($user_id); echo '</pre>'; exit;
$sqlratingexist  =  " SELECT sKBRId FROM  sptbl_kb_rating
                      WHERE nKBID ='".$kb_id."'";

if($user_id > 0){
    $sqlratingexist  .= " AND nUserId ='".$user_id."'";
}else{
    $sqlratingexist  .= " AND vIP ='".$ip."'";
}
$resratingexist  =   executeSelect($sqlratingexist,$conn);



if(mysql_num_rows($resratingexist)==0){
    
    $sqlrating       =  "INSERT INTO  sptbl_kb_rating(
                            nUserId,
                            nKBID,
                            vIP,
                            nMarks
                            ) VALUES(
                            '".addslashes($user_id)."',
                            '".addslashes($kb_id)."',
                            '".addslashes($ip)."',
                            '".addslashes($clickedStarVal)."')";

    executeQuery($sqlrating,$conn);
    $insertedId = mysql_insert_id();
    $starRatingContent = getStarRatingContent($kb_id);
    if($insertedId >0){
        $data = array('success' => 1,'ratingContent'=>$starRatingContent);
        echo json_encode($data);
        exit;
    }
    else{
        $data = array('error' => 1,'ratingContent'=>$starRatingContent);
        echo json_encode($data);
        exit;
    }
}
else{
        $data = array('duplicate' => 1,'ratingContent'=>$starRatingContent);
        echo json_encode($data);
        exit;
}

?>