<?php
 
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: 			*/
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: roshith<roshith@armia.com>             		              |
// |          										                      |
// +----------------------------------------------------------------------+

		include("./includes/session.php");
        include("../config/settings.php");
		include("./includes/functions/dbfunctions.php");
		include("./includes/functions/impfunctions.php");		 
		if(!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] =="") ){
                $_SP_language = "en";
        }else{
                $_SP_language = $_SESSION["sess_language"];
        }		
		
		include("./languages/".$_SP_language."/ajax.php");
		
		
        $conn = getConnection();
		$act=$_GET["act"];
		if ($act="ticketdetails"){
			$sql = "Select t.vTitle,t.tQuestion,r.tReply from sptbl_tickets t Left join  sptbl_replies r
			on t.nTicketId=r.nTicketId where t.nTicketId='".$_GET["id"]."' order by r.dDate desc limit 0,1;";
            $rs = executeSelect($sql,$conn);
			$row=mysql_fetch_array($rs);
			echo "<p align=left><b><u>".TEXT_TITLE."</u></b><br>".htmlentities(strip_tags($row["vTitle"]))."<br><br>
			<b><u>".TEXT_QUESTION."</u></b><br>".stripslashes(strip_tags($row["tQuestion"]))."<br><br><b><u>".TEXT_LAST_REPLY."</u></b><br>".stripslashes(strip_tags($row["tReply"]))."</p>";
		}
?>		