<?php
   include("./config/settings.php");
   include("./includes/session.php");
   include("./includes/functions/dbfunctions.php");
   include("./includes/functions/miscfunctions.php");
   include("./includes/functions/impfunctions.php");
   //require_once("./includes/applicationheader.php");
   if ( $_SESSION['sess_userid'] == "" ) echo "##X";
   else {
   include("./languages/".$_SESSION["sess_language"]."/client_chat.php");
   $mod = isset( $_GET['mod']) ? $_GET['mod'] : ''  ;
   $chatid = isset( $_GET['chatid']) ? $_GET['chatid'] : ''  ;
   $department = isset( $_GET['dptid']) ? $_GET['dptid'] : ''  ;
   $comp = isset( $_GET['comp']) ? $_GET['comp'] : ''  ;
   $conn = getConnection();
   // $sql = "Select count(*) as staffcount from sptbl_staffs where vOnline ='1' and nStaffId in ( select nStaffId from sptbl_staffdept where nDeptId ='".$department."')";
    $sql = "select count(s.nStaffId) as staffcount from sptbl_staffs s inner join sptbl_staffdept sd on ( s.nStaffId = sd.nStaffId )  inner join sptbl_depts d on ( sd.nDeptId = d.nDeptId )  where s.vOnline='1' and s.vDelStatus='0' and d.nDeptId='".$department."'";
    $result = executeSelect($sql,$conn);
    $rowcnt_onlinestaffs = mysql_num_rows($result);
    $sql_w = "Select vChatWelcomeMessage from sptbl_companies where nCompId = '".$comp."'";
    $rs_w = executeSelect($sql_w,$conn);
	$row_w = mysql_fetch_array($rs_w);
	$msg = $row_w['vChatWelcomeMessage']; 
	if( $rowcnt_onlinestaffs > 0 ) {
       while ($row = mysql_fetch_array($result)) {
		 $cnt_onlinestaffs=$row["staffcount"] ;
	   }
	   if ($cnt_onlinestaffs > 0 ){
          $sql = "Select c.nStaffId, c.vStatus, c.dTimeStart, c.dTimeEnd, c.vUserName, s.vStaffname, s.vStaffImg, now() as ctm  from sptbl_chat c left join sptbl_staffs s on ( c.nStaffId = s.nStaffId ) inner join sptbl_users u on (c.nUserId = u.nUserId) where  c.nChatId='".$chatid."'";
          $result = executeSelect($sql,$conn);
		  if ( mysql_num_rows($result) > 0 ) {
	         while( $row = mysql_fetch_array($result) ) {
			   $stsAccpt=$row["vStatus"];
			   $tms = $row["dTimeStart"];
			   $tme = $row["dTimeEnd"];
			   $staffname = $row["vStaffname"];
			   $user = $row["vUserName"];
			   $stfimg = ($row["vStaffImg"]) ? ("staff/images/".$row["vStaffImg"]) : "N";
			   $ctm = $row["ctm"];
			 }
			 if ($stsAccpt == 'accepted' ) {
			   $rtStr= "A::".TEXT_CONNECTED_TO."  ".$staffname."::".$msg."::".$tms."::".$stfimg."::".$ctm;
			 } else if ($stsAccpt == 'finished' ) $rtStr = "F::".TEXT_CHAT_FINISHED."::".$msg."::".$tms."::".$tme."::".$stfimg;
		     else $rtStr = TEXT_CALLING; 
		  }
       } else $rtStr = TEXT_STAFFOFFLINE;  
   } else $rtStr = TEXT_STAFFOFFLINE; 
   echo "##S".$rtStr;
 }
?>
