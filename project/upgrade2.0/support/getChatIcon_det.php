<?php
session_start();
if(!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] =="") ){
        $_SP_language = "en";          }else{
            $_SP_language = $_SESSION["sess_language"];          }

            
    
 $comp = $_GET["comp"];
 $page = ($_GET["page"] !='') ? $_GET["page"] : '';
 include("./config/settings.php");
 include("./includes/functions/dbfunctions.php");
 $conn = getConnection();
 $sql_img = "select  vChatIcon from sptbl_companies where nCompId='".$comp."'";
 $res_img = executeSelect($sql_img,$conn);
 if ( mysql_num_rows($res_img) > 0 ) {
   $row = mysql_fetch_array($res_img);
   $img_id=$row["vChatIcon"];
 } else $img_id='2';
 /*Enter visitor details */
 if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
 {
    $client_ip=$_SERVER['HTTP_CLIENT_IP'];
 } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
 {
   $client_ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
 }else {
   $client_ip=$_SERVER['REMOTE_ADDR'];
 }
 $client_page =  $page;
 $sql1 = "select nCompId, (now()-dLastUpdTime) as tme_elps  from sptbl_visitors where nCompId ='".$comp."' and vIpAddr = '".addslashes($client_ip)."' and vPage='".addslashes($client_page)."'";
 $result1 = executeSelect($sql1,$conn);
 if ( mysql_num_rows($result1) > 0 ) {
    $row1 = mysql_fetch_array($result1);
	if ( $row1['tme_elps'] > 10 ) {
    //  $sql2 = "update sptbl_visitors set vPage='".addslashes($client_page)."', dLastUpdTime=now(), vStatus='pending' where nCompId ='".$comp."' and vIpAddr = '".$client_ip."'";
       $sql2 = "Delete from sptbl_visitors where vPage='".addslashes($client_page)."' and nCompId ='".$comp."' and vIpAddr = '".$client_ip."'";
	} else {
      $sql2 = "update sptbl_visitors set  dLastUpdTime=now() where nCompId ='".$comp."' and vIpAddr = '".$client_ip."'and vPage='".addslashes($client_page)."'";
	}
 } else {
    $sql2 = "Insert into sptbl_visitors( nCompId, vIpAddr, vPage, vStatus, dVisitTime, dLastUpdTime ) values('".$comp."', '".$client_ip."', '".addslashes($client_page)."', 'pending', now(), now())";
 }
 $ret = mysql_query($sql2);
 /*ends*/
 $sql = "select s.nStaffId from sptbl_staffs s inner join sptbl_staffdept sd on ( s.nStaffId = sd.nStaffId )  inner join sptbl_depts d on ( sd.nDeptId = d.nDeptId )  where s.vOnline='1' and s.vDelStatus='0' and d.nCompId='".$comp."'";
 $result = executeSelect($sql,$conn);
 if ( mysql_num_rows($result) > 0 ) {
   //$img_src = "images/chat/chat-icon-".$img_id."-online.gif";
   $img_src = "languages/".$_SP_language."/images/chat-icon-".$img_id."-online.gif";
 } else {
   //$img_src = "images/chat/chat-icon-".$img_id."-offline.gif";
   $img_src = "languages/".$_SP_language."/images/chat-icon-".$img_id."-offline.gif";
 }
 
 header("Content-type: image/gif");
 header("Cache-control: private,no-cache,no-store,must-revalidate");
/*Avoided since it requires GD library
// $src =imagecreatefromgif($img_src);
// imagegif($src);
// imagedestroy($src);
*/
 @chmod("languages/".$_SP_language."/images/",0777);
 readfile($img_src);
 @chmod("languages/".$_SP_language."/images/", 0777);
?>