<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>                                  |
// |                                                                      |                // |                                                                      |
// +----------------------------------------------------------------------+

//we start output buffering on for displaying the status of the purging process as it happens

session_start();
include("../config/settings.php");
include("./includes/functions/dbfunctions.php");
include("./includes/functions/miscfunctions.php");
include("./languages/en/main.php");
include("./languages/en/processpurg.php");
ob_start();
echo str_pad('',1024);	//Minimum start for Safari
echo("<html><head><title></title><body><table><tr><td bgcolor=\"cccccc\">" . TEXT_START_PURGE . "</td></tr></table><br>\n");
ob_flush();
flush();
$conn = getConnection();

//Input parameters
if (!is_writable("./purgedtickets")) {
    echo("<table><tr><td>admin/purgedtickets " . TEXT_NEED_WRITE . "</td></tr></table>");
    ob_flush();
    flush();
    exit;
} elseif(!is_readable("./purgedtickets")) {
    echo("<table><tr><td>admin/purgedtickets " . TEXT_NEED_READ . "</td></tr></table>");
    ob_flush();
    flush();
    exit;
} elseif(!is_executable("./purgedtickets")) {
    echo("<table><tr><td>admin/purgedtickets " . TEXT_NEED_EXECUTE . "</td></tr></table>");
    ob_flush();
    flush();
    exit;
}
if (!is_writable("./purgedtickets/attachments")) {
    echo("<table><tr><td>admin/purgedtickets/attachments " . TEXT_NEED_WRITE . "</td></tr></table>");
    ob_flush();
    flush();
    exit;
} elseif(!is_readable("./purgedtickets/attachments")) {
    echo("<table><tr><td>admin/purgedtickets/attachments " . TEXT_NEED_READ . "</td></tr></table>");
    ob_flush();
    flush();
    exit;
} elseif(!is_executable("./purgedtickets/attachments")) {
    echo("<table><tr><td>admin/purgedtickets/attachments " . TEXT_NEED_EXECUTE . "</td></tr></table>");
    ob_flush();
    flush();
    exit;
}

$var_comp = $_GET["cmp"];
$var_dept = trim($_GET["dpt"]);
$var_status = trim($_GET["st"]);
$var_owner = trim($_GET["own"]);
$var_user = trim($_GET["usr"]);
//$var_from = $_GET["frm"];
//$var_to = $_GET["to"];
$var_from = datetimetomysql($_GET["frm"],"/");
$var_to = datetimetomysql($_GET["to"],"/");

$var_compop = $_GET["cop"];

$var_deptop = $_GET["dop"];
$var_deptlp = $_GET["dlp"];

$var_statusop = $_GET["sop"];
$var_statuslp = $_GET["slp"];

$var_ownerop = $_GET["oop"];
$var_ownerlp = $_GET["olp"];

$var_userop = $_GET["uop"];
$var_userlp = $_GET["ulp"];

$flag_where = false;
$flag_sub = false;

if($var_comp != "") {
    $var_compop = ($var_compop == "m")?"=":"!=";
    $sql = "Select distinct t.nTicketId from sptbl_tickets t inner join sptbl_depts d on
		t.nDeptId = d.nDeptId  Where 
		(d.nCompId" . $var_compop . "'" . addslashes($var_comp) . "' ";
    $flag_where = true;
}
else {
    $sql = "Select distinct t.nTicketId from sptbl_tickets t ";
}

//get all and operators first, then the or operators
$arr_operators['var_dept'] = $var_deptlp;
$arr_operators['var_status'] = $var_statuslp;
$arr_operators['var_owner'] = $var_ownerlp;
$arr_operators['var_user'] = $var_userlp;

//asort is used to sort the associative array
asort($arr_operators);

foreach($arr_operators as $key=>$val) {
    $var_tablename="";
    switch($key) {
        case "var_dept":
            $var_tablename = "t.nDeptId";
            break;
        case "var_status":
            $var_tablename = "t.vStatus";
            break;
        case "var_owner":
            $var_tablename = "t.nOwner";
            break;
        case "var_user":
            $var_tablename = "t.vUserName";
            break;
    }
    if(${$key} != "") {
        $sql .= ($flag_where == true)?(((${$key . "lp"} == "and")?" AND ":" OR ") . "  $var_tablename" . ((${$key . "op"} == "m")?"=":"!=")  . "'" . addslashes(${$key}) . "'"):" Where ($var_tablename" . ((${$key . "op"} == "m")?"=":"!=") . "'" . addslashes(${$key}) . "'";
        $flag_where = true;
    }
}

$sql .= ($flag_where == true)?")":"";
if($_GET["frm"] != "") {
    $sql .= ($flag_where == true)?( " AND (t.dLastAttempted >= DATE_FORMAT('" . addslashes($var_from) . "','%Y-%m-%d %H:%i:%s')"):(" Where (t.dLastAttempted >= DATE_FORMAT('" . addslashes($var_from) . "','%Y-%m-%d %H:%i:%s')");
    $flag_where=true;
    $flag_sub = true;
}
if($_GET["to"] != "") {
    $sql .= ($flag_where == true)?( " AND t.dLastAttempted <= DATE_FORMAT('" . addslashes($var_to) . "','%Y-%m-%d %H:%i:%s')"):(" Where (t.dLastAttempted <= DATE_FORMAT('" . addslashes($var_to) . "','%Y-%m-%d %H:%i:%s')");
    $flag_where=true;
    $flag_sub = true;
}
$sql .= ($flag_sub == true)?")":"";


$result = executeSelect($sql,$conn);
if(mysql_num_rows($result) > 0) {
    while($row = mysql_fetch_array($result)) {
        //Delete the tickets for the ticket id
        DeleteTicket($row["nTicketId"]);
    }
}
echo str_pad('',1024);
echo("<table><tr><td>" . TEXT_PURGE_COMPLETE . " </td></tr></table><br>\n</body></html>"); 
ob_flush();
flush();
ob_end_flush();


function DeleteTicket($var_ticketid) {
    global $conn;
    $arr_list_replyid=array();
    $var_buffer = "<html><head></head>
		<link href=\"./magicviolet.css\" rel=\"stylesheet\" type=\"text/css\"><body>
<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                      <td width=\"1\" class=\"vline\" ><img src=\"./spacerr.gif\" width=\"1\" height=\"1\"></td>
                      <td class=\"pagecolor\"><table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"5\">
                          <tr>
                            <td width=\"5%\" align=\"right\"><img src=\"./dot6.gif\" width=\"15\" height=\"15\"></td>
                            <td width=\"2%\"  class=\"listingheadmid\">&nbsp;</td>
                            <td width=\"93%\" class=\"listingheadright\">" . TEXT_TICKET_DETAIL . "</td>
                          </tr>
                        </table>
                          <table width=\"100%\"  border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"listingmaintext\">
                            <tr>
                              <td><table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                                  <tr>
                                    <td align=\"right\">";

//********************TICKET DISPLAY SECTION*********************************************************
    $tkflag = false;	//this is to check whether there is a ticket for the request
    $sql = "Select t.nTicketId,t.nDeptId,t.vUserName,t.vTitle,t.vRefNo,t.dPostDate,t.tQuestion,t.vPriority,t.vStatus,t.nOwner,
		t.nLockStatus,t.vMachineIp,t.vStaffLogin,d.vDeptDesc,a.nAttachId,vAttachReference,vAttachUrl 
		from sptbl_tickets t inner join sptbl_depts d on t.nDeptId = d.nDeptId left outer join sptbl_attachments a
		on t.nTicketId=a.nTicketId Where t.nTicketId='" . $var_ticketid ."'";
    $var_username = "";
    $showflag = false;  // This is to check whether the ticket belong to the department assigned
    $rs = executeSelect($sql,$conn);
    if(mysql_num_rows($rs) > 0) {
        $row = mysql_fetch_array($rs);
        $tkflag = true;
        $var_username = $row["vUserName"];
        $var_deptid = $row["nDeptId"];
        $var_department = $row["vDeptDesc"];
        $var_owner_name = $row["vStaffLogin"];
        $var_owner_id = $row["nOwner"];
        $var_created_on = $row["dPostDate"];
        $var_status = $row["vStatus"];
        $var_lock = $row["nLockStatus"];
        $var_filename=$row["vRefNo"];

//flush the output to the browser saying the ticket ref no presently being purged 	
        echo str_pad('',1024);
        echo("<table><tr><td>" . TEXT_PURGE_TICKET . " " . $var_filename . " </td></tr></table><br>\n");
        ob_flush();
        flush();

        $showflag = true; //added at the time of comment
        $var_buffer .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			<tr align=\"left\"  class=\"headings2\">
				<td colspan=\"2\" style=\"word-break:break-all; \">
					<img src=\"./separator1.gif\" width=\"10\" height=\"12\">" . TEXT_USER . " : " . htmlentities($row["vUserName"]) . " 
				</td>
				<td  width=\"32%\" >" .
                TEXT_DATE . " : " . date("m-d-Y",strtotime($row["dPostDate"])) . "
				</td>
				<td width=\"36%\">".
                TEXT_IP . " : " . $row["vMachineIp"] . "
				</td>
				<td width=\"3%\"><br>&nbsp;</td>
			</tr>
			<tr>
				<td colspan=\"5\">
					<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"ticketband\">
						<tr align=\"center\">"; 
        $var_buffer .= "<td align=\"right\">&nbsp;</td>
						</tr>
				  </table>
				</td>
			</tr>
		   <tr align=\"left\"  class=\"listingmaintext\">
				<td colspan=\"5\" style=\"word-break:break-all; \">
					<br><b>Title : <div style=\"overflow:hidden;\">" . htmlentities($row["vTitle"]) . "</div></b><br>&nbsp;
				</td>
	  </tr>	
			<tr>
			<td colspan=\"5\" class=\"bodycolor\" >

			   <table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"3\"  >
				<tr align=\"left\"  class=\"toplinks\">
					<td colspan=\"4\" width=\"10%\" style=\"word-break:break-all;\">
						<div style=\"overflow:hidden;\">" . nl2br(htmlentities($row["tQuestion"])) . "</div>
					</td>
			  </tr>								  
			  <tr align=\"left\" >
				<td colspan=\"4\" class=\"listingmaintext\">&nbsp;</td>
			  </tr>
			  

			  <tr align=\"left\"  class=\"listingmaintext\">
				<td colspan=\"2\">&nbsp;</td>
				<td colspan=\"2\">&nbsp;</td>
			 </tr>
		  </table></td>
		</tr>";
        if ($row["vAttachUrl"] != "") {

            $var_buffer .= "<tr>
				<td colspan=\"5\">
					<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"attachband\">
						<tr align=\"center\">
						  <td colspan=\"4\">" . TEXT_ATTACHMENTS . " : <a href=\"javascript:var lg=window.open('./attachments/" . addslashes($row["vAttachUrl"]) . "');\"  class=\"attachband\">". htmlentities($row["vAttachReference"]) . "</a>";
            @copy("../attachments/". $row["vAttachUrl"],"./purgedtickets/attachments/".$row["vAttachUrl"]);
            @unlink("../attachments/". $row["vAttachUrl"]);
            while($row = mysql_fetch_array($rs)) {
                $var_buffer .= "," . "<a href=\"javascript:var lg=window.open('./attachments/" . addslashes($row["vAttachUrl"]) . "');\"  class=\"attachband\">". htmlentities($row["vAttachReference"]) . "</a>";
                @copy("../attachments/". $row["vAttachUrl"],"./purgedtickets/attachments/".$row["vAttachUrl"]);
                @unlink("../attachments/". $row["vAttachUrl"]);
            }
            $var_buffer .= "</td>
						</tr>
				  </table>
				</td>
	  </tr>";

        }

        $var_buffer .= "</table>";
    }
    mysql_free_result($rs);



//********************CORRESPONDANCE SECTION*********************************************************
    $sql = "Select r.nReplyId,r.nStaffId,r.vStaffLogin,r.nUserId,r.dDate,r.vMachineIP,tReply,
		r.tPvtMessage,a.nAttachId,vAttachReference,vAttachUrl  from sptbl_replies r left outer join sptbl_attachments a on 
		r.nReplyId = a.nReplyId Where r.nTicketId='" . $var_ticketid ."'  ORDER BY r.dDate ";
    $rs = executeSelect($sql,$conn);
    if($tkflag == true && mysql_num_rows($rs) > 0) {
        if ($row = mysql_fetch_array($rs)) {
            $flag_main = true;
            while($flag_main == true) {
                $flag_main = false;
                $chk_id = $row["nReplyId"];

                $var_buffer .="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
											<tr align=\"left\"  class=\"headings2\">
												<td colspan=\"2\" width=\"40%\" style=\"word-break:break-all; \"><br>";

                if ($row["nStaffId"] != "") {
                    $var_style = "replyband";
                    $var_buffer .= TEXT_STAFF . " : " .  htmlentities($row["vStaffLogin"]);
                    $var_last_replier = $row["vStaffLogin"];
                }
                elseif ($row["nUserId"] != "") {
                    $var_style = "ticketband";
                    $var_buffer .= TEXT_USER . " : " . htmlentities($var_username);
                }
                $var_buffer .= "<br>&nbsp;
												</td>
												<td  width=\"30%\" >";

                $var_last_update = date("m-d-Y",strtotime($row["dDate"]));
                $var_buffer .=  TEXT_DATE . " : " .  $var_last_update;
                $var_buffer .= "</td>
												<td>" . TEXT_IP . " : ". $row["vMachineIP"] . "
												</td>
											</tr>
											<tr>
												<td colspan=\"4\">
													<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"$var_style\">
														<tr align=\"center\"><td width=\"100%\">&nbsp;</td>
														</tr>
												  </table>
												</td>
											</tr>
                                            <tr>
                                            <td colspan=\"4\" class=\"bodycolor\" >

                                               <table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"3\"  >
												<tr align=\"left\"  class=\"toplinks\">
													<td colspan=\"4\" width=\"10%\" style=\"word-break:break-all;\">
														<div style=\"overflow:hidden;\">" . stripslashes($row["tReply"]) . "</div>
													</td>
										      </tr>								  
                                              <tr align=\"left\" >
                                                <td colspan=\"4\" class=\"listingmaintext\">&nbsp;</td>
                                              </tr>"; 
                //$var_staffid == $row["nStaffId"] &&   condition deprecated for admin
                if (trim($row["tPvtMessage"]) != "") {

                    $var_buffer .= "<tr><td colspan=4 class=\"commentband\" align=\"left\" >Comments</td></tr>
											<tr><td colspan=4  align=\"left\"  style=\"word-break:break-all;\"><div style=\"overflow:auto;\">" . nl2br(htmlentities($row["tPvtMessage"])) . "</div></td></tr>";					

                }



                $var_buffer .= "<tr align=\"left\"  class=\"listingmaintext\">
                                                <td colspan=\"2\">&nbsp;</td>
                                                <td colspan=\"2\">&nbsp;</td>
                                             </tr>
                                          </table></td>
                                        </tr>";

                if ($row["vAttachUrl"] != "") {
                    $arr_list_replyid[] = $row["nReplyId"];
                    $var_buffer .= "<tr>
												<td colspan=\"4\">
													<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"attachband\">
														<tr align=\"center\">
														  <td colspan=\"4\">" .  
                            TEXT_ATTACHMENTS . " : <a href=\"javascript:var lg=window.open('./attachments/" . addslashes($row["vAttachUrl"]) . "');\"  class=\"attachband\">". htmlentities($row["vAttachReference"]) . "</a>";
                    @copy("../attachments/". $row["vAttachUrl"],"./purgedtickets/attachments/".$row["vAttachUrl"]);
                    @unlink("../attachments/". $row["vAttachUrl"]);
                    while($row = mysql_fetch_array($rs)) {
                        if ($row["nReplyId"] == $chk_id) {
                            $var_buffer .= "," . "<a href=\"javascript:var lg=window.open('./attachments/" . addslashes($row["vAttachUrl"]) . "');\"  class=\"attachband\">". htmlentities($row["vAttachReference"]) . "</a>";
                            @copy("../attachments/". $row["vAttachUrl"],"./purgedtickets/attachments/".$row["vAttachUrl"]);
                            @unlink("../attachments/". $row["vAttachUrl"]);
                        }
                        else {
                            $arr_list_replyid[] = $row["nReplyId"];
                            $flag_main = true;
                            break;
                        }
                    }
                    $var_buffer .= "</td>
														</tr>
												  </table>
												</td>
										  </tr>";

                }
                elseif ($row = mysql_fetch_array($rs)) {
                    $flag_main = true;
                }


                $var_buffer .= "</table>";
            }
        }
    }
    mysql_free_result($rs);
//Personal Notes Section
    $sql = "Select vStaffLogin,vPNTitle,tPNDesc,dDate from sptbl_personalnotes where nTicketId='" . $var_ticketid  . "'";
    $result = executeSelect($sql,$conn);
    if(mysql_num_rows($result) > 0) {
        $var_buffer .="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
					<tr>
						<td colspan=\"4\">
							<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"$var_style\">
								<tr align=\"center\"><td width=\"100%\">" . TEXT_PERSONAL . "</td>
								</tr>
						  </table>
						</td>
					</tr>";
        while($row = mysql_fetch_array($result)) {
            $var_buffer .= "<tr align=\"left\"  class=\"headings2\">
						<td colspan=\"2\" width=\"60%\" style=\"word-break:break-all; \">" . $row["vStaffLogin"] . "
						</td>
						<td  colspan=\"2\" width=\"40%\" >" . date("m-d-Y H:i",strtotime($row["dDate"])) . "</td></tr>" . "
						<tr>
							<td colspan=\"4\" class=\"bodycolor\" > " . $row["vPNTitle"] . "
							</td>
						</tr>
						<tr>
							<td colspan=\"4\" class=\"bodycolor\" >

							   <table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"3\"  >
								<tr align=\"left\"  class=\"toplinks\">
									<td colspan=\"4\" width=\"10%\" style=\"word-break:break-all;\">
										<div style=\"overflow:hidden;\">" . nl2br(htmlentities($row["tPNDesc"])) . "</div>
									</td>
							  </tr>								  
							  <tr align=\"left\" >
								<td colspan=\"4\" class=\"listingmaintext\">&nbsp;</td>
							  </tr>
							  </table>
							 </td> 
						</tr>";
        }
        $var_buffer .= "</table>";
    }
//End Personal Notes

//Feedback Section
    $sql = "Select vFBTitle,tFBDesc,dDate from sptbl_feedback where nTicketId='" . $var_ticketid  . "'";
    $result = executeSelect($sql,$conn);
    if(mysql_num_rows($result) > 0) {
        $var_buffer .="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
					<tr>
						<td colspan=\"4\">
							<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"$var_style\">
								<tr align=\"center\"><td width=\"100%\">" .HEADING_VIEW_FEEDBACK. "</td>
								</tr>
						  </table>
						</td>
					</tr>";
        while($row = mysql_fetch_array($result)) {
            $var_buffer .= "<tr align=\"left\"  class=\"headings2\">
						<td colspan=\"2\" width=\"60%\" style=\"word-break:break-all; \">" . $var_username . "
						</td>
						<td  colspan=\"2\" width=\"40%\" >" . date("m-d-Y H:i",strtotime($row["dDate"])) . "</td></tr>" . "
						<tr>
							<td colspan=\"4\" class=\"bodycolor\" > " . $row["vFBTitle"] . "
							</td>
						</tr>
						<tr>
							<td colspan=\"4\" class=\"bodycolor\" >

							   <table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"3\"  >
								<tr align=\"left\"  class=\"toplinks\">
									<td colspan=\"4\" width=\"10%\" style=\"word-break:break-all;\">
										<div style=\"overflow:hidden;\">" . nl2br(htmlentities($row["tFBDesc"])) . "</div>
									</td>
							  </tr>								  
							  <tr align=\"left\" >
								<td colspan=\"4\" class=\"listingmaintext\">&nbsp;</td>
							  </tr>
							  </table>
							 </td> 
						</tr>";
        }
        $var_buffer .= "</table>";
    }
//End Feedback section


    $var_buffer .= "</td>
                                  </tr>
                              </table></td>
                            </tr>
                        </table></td>
                      <td width=\"1\" class=\"vline\"><img src=\"./spacerr.gif\" width=\"1\" height=\"1\"></td>
                    </tr>

                  </table>";		

    $var_buffer .= "</body></html>";
    $handle = fopen("./purgedtickets/" . $var_filename . ".htm", 'w');
    fwrite($handle, $var_buffer);
    fclose($handle);
    $sql = "Delete from sptbl_attachments where nTicketId='$var_ticketid'";
    if(count($arr_list_replyid) > 0) {
        $arr_list_replyid = array_unique($arr_list_replyid);
        $var_list = implode(",",$arr_list_replyid);
        $sql .= " OR nReplyId IN($var_list)";
    }
    executeQuery($sql,$conn);
    $sql = "Delete from sptbl_personalnotes where nTicketId='$var_ticketid'";
    executeQuery($sql,$conn);
    $sql = "Delete from sptbl_feedback where nTicketId='$var_ticketid'";
    executeQuery($sql,$conn);
    $sql = "Delete from sptbl_replies where nTicketId='$var_ticketid'";
    executeQuery($sql,$conn);
    $sql = "Delete from sptbl_tickets where nTicketId='$var_ticketid'";
    executeQuery($sql,$conn);
} 	

?>