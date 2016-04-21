<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshith<roshith@armia.com>    		                      |
// |          									                          |
// +----------------------------------------------------------------------+

    include("./languages/".$_SP_language."/staffmain.php");
    $conn = getConnection();
	$var_staffid = $_SESSION["sess_staffid"];

	$sql = "Select * from sptbl_pvtmessages where nToStaffId='" . addslashes($var_staffid) . "' and vStatus='o'";
	$result = executeSelect($sql,$conn);

	if (mysql_num_rows($result) > 0)
		$_SESSION['pvt_msg_alert'] = 1;
	else
		$_SESSION['pvt_msg_alert'] = "";
		
	mysql_free_result($result);
?>