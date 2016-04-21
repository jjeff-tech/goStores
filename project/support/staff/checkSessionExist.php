<?php
  include("./includes/session.php");
  include("../config/settings.php");
  include("./includes/functions/dbfunctions.php");
  include("./includes/functions/impfunctions.php");
  $conn = getConnection();
  $sql = "Delete from sptbl_visitors where ( TIME_TO_SEC( TIMEDIFF(now(),dLastUpdTime) ) )>10";
  executeQuery($sql,$conn);
  $sql = "Delete from sptbl_chat where ( TIME_TO_SEC( TIMEDIFF(now(),dTimeStart) ) )>86400 and vStatus='pending'";
  executeQuery($sql,$conn);
  if ($_SESSION['sess_staffid'] == "" ) echo "#X";
  else echo "#N";
  
?>