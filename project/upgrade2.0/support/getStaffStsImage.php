<?php
   include("./config/settings.php");
   include("./includes/functions/dbfunctions.php");
   //require_once("./includes/applicationheader.php");
   //include("./languages/".$_SP_language."/client_chat.php");
   $dptid = isset( $_GET['dptid']) ? $_GET['dptid'] : ''  ;
   $conn = getConnection();
   $sql = "select s.nStaffId from sptbl_staffs s inner join sptbl_staffdept d on ( s.nStaffId = d.nStaffId) where s.vOnline='1' and s.vDelStatus='0' and d.nDeptId='".$dptid."'";
   $result = executeSelect($sql,$conn);
   $rowcnt_onlinestaffs = mysql_num_rows($result);
   if( $rowcnt_onlinestaffs > 0 ) {
    /*  $sql = "select vLookUpValue from sptbl_lookup where vLookUpName='ChatStaffOnlineImage'";
   	  $result = executeSelect($sql,$conn);
      if (mysql_num_rows($result) > 0) {
	    while ($row = mysql_fetch_array($result)) {
		  if ($row["vLookUpValue"] != "" ) echo "images/chat/".$row["vLookUpValue"] ;
	    }
	  }
	*/
	  echo "ONL";
   } else {
     /* $sql = "select vLookUpValue from sptbl_lookup where vLookUpName='ChatStaffOfflineImage'";
   	  $result = executeSelect($sql,$conn);
      if (mysql_num_rows($result) > 0) {
	    while ($row = mysql_fetch_array($result)) {
		  if ($row["vLookUpValue"] != "" ) echo "images/chat/".$row["vLookUpValue"] ;
		}
	  }
	 */
	 echo "OFL";
   } 
?>
