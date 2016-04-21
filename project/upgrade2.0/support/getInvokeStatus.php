<?php
  include("./config/settings.php");
  include("./includes/functions/dbfunctions.php");
  $conn = getConnection();
  $comp = isset( $_GET['comp']) ? $_GET['comp'] : ''  ;
  $pg = isset( $_GET['pg']) ? $_GET['pg'] : ''  ;
  $client_ip = $_SERVER['REMOTE_ADDR'];
  $sql = "Select nCompId from sptbl_visitors where nCompId ='".$comp."' and vIpAddr='".$client_ip."' and vPage='".addslashes($pg)."' and vStatus='invited'";
  $result = executeSelect($sql,$conn);
  $rowcnt = mysql_num_rows($result);
  if( $rowcnt > 0 ) {
    echo "Y";
  } else {
    echo "N";
  }
 ?>
