<?php
   include("./config/settings.php");
   include("./includes/functions/dbfunctions.php");
   $conn = getConnection();
   $user = isset( $_GET['uid']) ? $_GET['uid'] : ''  ;
   $sql = "update sptbl_desktop_share set vStatus='pending' where nUserId = '".$user."' and vStatus='invited'";
   executeQuery($sql,$conn);
 ?>
