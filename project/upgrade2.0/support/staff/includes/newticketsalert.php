<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshith<roshith@armia.com>    		                      |
// |          									                          |
// +----------------------------------------------------------------------+

    include("./languages/".$_SP_language."/staffmain.php");
    $conn = getConnection();

	$sql = "Select t.*,rp.nStaffId,rp.nUserId as rpuserid  from sptbl_tickets t left join sptbl_replies rp on (rp.dDate=t.dLastAttempted and rp.nTicketId=t.nTicketId) where t.vDelStatus='0' AND t.vStatus='open'  ";
	$rs = executeSelect($sql,$conn);

	if (mysql_num_rows($rs) > 0)
		$_SESSION['newticket_msg_alert'] = 1;
	else
		$_SESSION['newticket_msg_alert'] = "";

	mysql_free_result($rs);
	
?>