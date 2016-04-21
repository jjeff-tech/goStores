<?php
include("config/settings.php");
include("includes/session.php");
if (!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] == "")) {
    $_SP_language = "en";
} else {
    $_SP_language = $_SESSION["sess_language"];
}
include("includes/functions/dbfunctions.php");
include("includes/functions/miscfunctions.php");
include("includes/functions/impfunctions.php");
include("includes/main_smtp.php");
include("./languages/" . $_SP_language . "/main.php");
include("./languages/".$_SP_language."/viewticket.php");

include("languages/$_SP_language/showticket.php");


$conn = getConnection();

$var_tid       =  $var_ticketid = $_REQUEST['var_tid'];
$var_userid    = $_REQUEST['var_userid'];
$var_type      = $_REQUEST['var_type'];

$sql = "Select t.nTicketId,t.nDeptId,t.vUserName,t.vTitle,t.vRefNo,t.dPostDate,t.tQuestion,t.vPriority,t.vStatus,t.nOwner,
		t.nLockStatus,t.vMachineIP,t.vStaffLogin,d.vDeptDesc,a.nAttachId,vAttachReference,vAttachUrl
		from sptbl_tickets t inner join sptbl_depts d on t.nDeptId = d.nDeptId left outer join sptbl_attachments a
		on t.nTicketId=a.nTicketId Where t.nTicketId='" . addslashes($var_tid) ."' AND t.nUserId='" . addslashes($var_userid) . "' ";

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


    ?>
<div class="content_section">
    <div class="content_section_title"><h3 style="color: #000; padding: 10px 0 10px 20px;"><?php echo TEXT_TICKET_DETAILS; ?></h3></div>
    <div class="content_section_data" style="padding: 20px;">
        <?php if($var_type =='merged'){ ?>
        <div style="color: #666; font-size: 13px; padding: 0 0 10px 0;"><?php echo TEXT_MERGE_TICKET_NOTE;?></div>
        <?php } ?>

        <table width="100%" border="0" cellspacing="0" cellpadding="0" >
            <tr align="left"  class="headinginner2">
                <td colspan="2" style="word-break:break-all; ">
                        <?php echo "&nbsp;&nbsp;".TEXT_USER . " : " . htmlentities($row["vUserName"]); ?>
                </td>
                <td  width="26%" >
                        <?php echo TEXT_DATE . " : " . date("m-d-Y",strtotime($row["dPostDate"])); ?>
                </td>
                <td width="35%">
                        <?php echo TEXT_IP . " : " . $row["vMachineIP"]; ?>
                </td>
                <td width="2%"><br>&nbsp;</td>
            </tr>


            <tr align="left"  class="fieldnames">
                <td colspan="5" style="word-break:break-all; ">
                    <div><br><b><?php echo TEXT_TITLE?> : <?php echo htmlentities($row["vTitle"]); ?></b>&nbsp;
                        <span style="float: right"><b><?php echo TEXT_STATUS?> : <?php echo htmlentities($var_status); ?></b></span></div><br>
                </td>
            </tr>
            <tr>
                <td colspan="5" class="bodycolor" >

                    <table width="100%"  border="0" cellpadding="0" cellspacing="3"  >
                        <tr align="left"  class="fieldnames">
                            <td colspan="4" width="10%" style="word-break:break-all;">
                                    <?php echo nl2br($row["tQuestion"]); ?>
                            </td>
                        </tr>
                        <tr align="left" >
                            <td colspan="4" class="listingmaintext">&nbsp;</td>
                        </tr>

                    </table></td>
            </tr>
                <?php
                if ($row["vAttachUrl"] != "") {
                    ?>
            <tr>
                <td colspan="5">
                    <table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                        <tr align="center">
                            <td colspan="4"><?php
                                        echo(TEXT_ATTACHMENT . " : <a href=\"javascript:var lg=window.open('./attachments/" . addslashes($row["vAttachUrl"]) . "');\"   class=\"listing\">". htmlentities($row["vAttachReference"]) . "</a>");
                                        while($row = mysql_fetch_array($rs)) {
                                            echo("," . "<a href=\"javascript:var lg=window.open('./attachments/" . addslashes($row["vAttachUrl"]) . "');\"   class=\"listing\">". htmlentities($row["vAttachReference"]) . "</a>");
                                        }
                                        ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
                    <?php
                }
                ?>
        </table>

            <?php

        }
        mysql_free_result($rs);
        ?>
        <!-- End Of Ticket Display -->

        <!-- Reply Detail -->

        <?php

//********************CORRESPONDANCE SECTION*********************************************************
        $sql = "Select r.nReplyId,r.nStaffId,r.vStaffLogin,r.nUserId,r.dDate,r.vMachineIP,tReply,
		r.tPvtMessage,a.nAttachId,vAttachReference,vAttachUrl  from sptbl_replies r left outer join sptbl_attachments a on
		r.nReplyId = a.nReplyId Where r.nTicketId='" . addslashes($var_tid) ."'  ORDER BY r.dDate ";
        $rs = executeSelect($sql,$conn);
        if($tkflag == true && mysql_num_rows($rs) > 0) {
            if ($row = mysql_fetch_array($rs)) {
                $flag_main = true;
                while($flag_main == true) {
                    $flag_main = false;
                    $chk_id = $row["nReplyId"];
                    ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr align="left"  class="headinginner2">
                <td colspan="2" style="word-break:break-all; ">
                                <?php
                                if ($row["nStaffId"] != "") {
                                    $var_style = "replyband";
                                    echo(TEXT_STAFF . " : " .  htmlentities($row["vStaffLogin"]));
                                    $var_last_replier = $row["vStaffLogin"];
                                }
                                elseif ($row["nUserId"] != "") {
                                    $var_style = "ticketband";
                                    echo(TEXT_USER . " : " . htmlentities($var_username));
                                }
                                ?>
                </td>
                <td  width="30%" >
                                <?php
                                $var_last_update = date("m-d-Y",strtotime($row["dDate"]));
                                echo TEXT_DATE . " : " . $var_last_update; ?>
                </td>
                <td width="28%">
                                <?php echo TEXT_IP . " : ". $row["vMachineIP"]; ?>
                </td>
                <td width="2%"><br>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="5">
                    <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="<?php echo($var_style); ?>">
                        <tr align="center">
                            <td width="57%" align="left">&nbsp;</td>
                            <td width="14%" align="center">&nbsp;</td>
                            <td width="18%" align="center">&nbsp;</td>
                            <td width="11%" align="center">&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="5" class="bodycolor" >

                    <table width="100%"  border="0" cellpadding="0" cellspacing="3"  >
                        <tr align="left"  class="fieldnames">
                            <td colspan="4" width="10%" style="word-break:break-all;">
                                            <?php echo stripslashes($row["tReply"]); ?>
                            </td>
                        </tr>
                        <tr align="left" >
                            <td colspan="4" class="listingmaintext">&nbsp;</td>
                        </tr>
                        <tr align="left"  class="listingmaintext">
                            <td colspan="2">&nbsp;</td>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                    </table></td>
            </tr>

                        <?php
                        if ($row["vAttachUrl"] != "") {
                            ?>
            <tr>
                <td colspan="5">
                    <table width="100%"  border="0" cellpadding="0" cellspacing="0">
                        <tr align="center">
                            <td colspan="4"  style="word-break:break-all;" class="fieldnames"><?php
                                                echo(TEXT_ATTACHMENT . " : <a href=\"javascript:var lg=window.open('./attachments/" . addslashes($row["vAttachUrl"]) . "');\"  class=\"listing\">". htmlentities($row["vAttachReference"]) . "</a>");
                                                while($row = mysql_fetch_array($rs)) {
                                                    if ($row["nReplyId"] == $chk_id) {
                                                        echo("," . "<a href=\"javascript:var lg=window.open('./attachments/" . addslashes($row["vAttachUrl"]) . "');\"  class=\"listing\">". htmlentities($row["vAttachReference"]) . "</a>");
                                                    }
                                                    else {
                                                        $flag_main = true;
                                                        break;
                                                    }
                                                }
                                                ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
                            <?php
                        }
                        elseif ($row = mysql_fetch_array($rs)) {
                            $flag_main = true;
                        }
                        ?>
        </table>


                    <?php
                }
            }
        }
        mysql_free_result($rs);
        ?>
        <?php
require("./includes/mergedticketdisplay.php");

function getAttachment($var_ticketid = "", $var_replyid = "") {
            global $rs_attach;
            $var_return = "";
            $flag = false;
            if($rs_attach){
            if (mysql_num_rows($rs_attach) > 0) {
                mysql_data_seek($rs_attach, 0);
                if ($var_ticketid != "") {
                    while ($row = mysql_fetch_array($rs_attach)) {
                        if ($row["nTicketId"] != "") {
                            if ($row["nTicketId"] == $var_ticketid) {
                                $var_return .= "," . " <a href=\"javascript:var lg=window.open('attachments/" . mysql_real_escape_string($row["vAttachUrl"]) . "');\"  class=\"attachband\">" . htmlentities($row["vAttachReference"]) . "</a>";
                                $flag = true;
                            } elseif ($flag == true) {
                                break;
                            }
                        } else {
                            break;
                        }
                    }
                } elseif ($var_replyid != "") {
                    while ($row = mysql_fetch_array($rs_attach)) {
                        if ($row["nReplyId"] == $var_replyid) {
                            $var_return .= "," . " <a href=\"javascript:var lg=window.open('attachments/" . mysql_real_escape_string($row["vAttachUrl"]) . "');\"  class=\"attachband\">" . htmlentities($row["vAttachReference"]) . "</a>";
                            $flag = true;
                        } elseif ($flag == true) {
                            break;
                        }
                    }
                }
            }
            }else {
                return "";
            }
            return (($var_return != "") ? TEXT_ATTACHMENT . " : " . substr($var_return, 1) : "");
        }
?>
    </div>
</div>

<!-- End Of Reply Detail -->
