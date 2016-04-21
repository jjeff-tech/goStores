<?php
   require_once("./includes/applicationheader.php");
   $uid = isset( $_GET['uid']) ? $_GET['uid'] : ''  ;
   $conn = getConnection();
   $sql = "select count(nUserId) as scnt from sptbl_desktop_share where nUserId='".$uid."' and vStatus='invited'";
   $result = executeSelect($sql,$conn);
   $rowcnt = mysql_num_rows($result);
   if( $rowcnt > 0 ) {
      $row = mysql_fetch_array($result) ;
	  if ($row["scnt"] > 0 )  $rtStr = 'Y';
	  else $rtStr = 'G';
   } else {
     $rtStr = 'N';
   }	
   echo "##H".$rtStr;
?>
