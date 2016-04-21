<?php
 $comp = $_GET["comp"];
 include("./config/settings.php");
 include("./includes/functions/dbfunctions.php");
 $conn = getConnection();
 $sql_img = "select  vChatIcon from sptbl_companies where nCompId='".$comp."'";
 $res_img = executeSelect($sql_img,$conn);
 if ( mysql_num_rows($res_img) > 0 ) {
   $row = mysql_fetch_array($res_img);
   $img_id=$row["vChatIcon"];
 } else $img_id='2';
 $sql = "select s.nStaffId from sptbl_staffs s inner join sptbl_staffdept sd on ( s.nStaffId = sd.nStaffId )  inner join sptbl_depts d on ( sd.nDeptId = d.nDeptId )  where s.vOnline='1' and d.nCompId='".$comp."'";
 $result = executeSelect($sql,$conn);
 if ( mysql_num_rows($result) > 0 ) {
   $img_src = "images/chat/chat-icon-".$img_id."-online.gif";
 } else {
   $img_src = "images/chat/chat-icon-".$img_id."-offline.gif";
 }
 
 header("Content-type: image/gif");
/*Avoided since itrequires GD library
// $src =imagecreatefromgif($img_src);
// imagegif($src);
// imagedestroy($src);
*/
 @chmod("./images/chat",0777);
 readfile($img_src);
 @chmod("./images/chat", 0777);
 // exit(0);
// header("location:getChatIcon_det.php?comp=2");
?>