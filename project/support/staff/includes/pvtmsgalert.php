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

	$sql = "Select vPMTitle from sptbl_pvtmessages where nToStaffId='" . mysql_real_escape_string($var_staffid) . "' and vStatus='o'";
	$result = executeSelect($sql,$conn);
	
	$i=1;
	if (mysql_num_rows($result) > 0) {
/*		while($row = mysql_fetch_array($result)){
			$var_title = $var_title.$i.") ".$row["vPMTitle"].'\n';
			$i++;
		}

		$var_title = trim($var_title);
*/
		$total_count = mysql_num_rows($result);

		$msgleft  = MESSAGE_JS_PVTMESSAGE_ALERT1;
		$msgright = MESSAGE_JS_PVTMESSAGE_ALERT2;
		echo("<script>alert('$msgleft$total_count$msgright');</script>");
		mysql_free_result($result);
	}
?>