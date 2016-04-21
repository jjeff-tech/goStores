<?php
error_reporting (E_ALL & ~E_NOTICE );
$nStaffId=$_GET[nStaffId];
$online=$_GET[online];
include("../../config/settings.php");
include("../../includes/functions/dbfunctions.php");
$conn = getConnection();
$query="UPDATE sptbl_staffs SET vOnline = ".$online." WHERE `nStaffId` = ".$nStaffId."" ;
executeQuery($query,$conn);
?>