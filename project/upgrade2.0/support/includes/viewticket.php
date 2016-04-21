<?php  
$var_userid = $_SESSION["sess_userid"];

if($_GET["mt"] == "y")
{
    //paging of correspondance

    $var_numBegin        = $_GET["numBegin"];
    $var_start           = $_GET["start"];
    $var_begin           = $_GET["begin"];
    $var_num             = $_GET["num"];

    //paging of correspondance

    $var_ticketid        = $_GET["tk"];
    $var_stylename       = $_GET["stylename"];
    $var_styleminus      = $_GET["styleminus"];
    $var_styleplus       = $_GET["styleplus"];

    if($_GET["cl"] == "y")
    {
        $sql = "Update sptbl_tickets set vStatus='closed' where 
		nTicketId='" . addslashes($var_ticketid) . "' 
		AND nUserId='" . $var_userid . "'";
        
        executeQuery($sql, $conn);

        $message = "<b>Ticket Has Been Closed!</b>";
        $flag_msg="class='msg_success'";
    }
}
else if($_POST["mt"] == "y")
{
    $var_ticketid = $_POST["tk"];
    $var_stylename = $_POST["stylename"];
    $var_styleminus = $_POST["styleminus"];
    $var_styleplus = $_POST["styleplus"];
}

$_SESSION['sess_backurl'] = $_SERVER["REQUEST_URI"];

if (isset($_GET['msg']))
    $message = MESSAGE_TICKET_REPLIED;
    $flag_msg="class='msg_success'";
?>

<div class="content_section_title"><h3><?php echo TEXT_TICKET_DETAIL; ?></h3></div>
<form name="frmDetail" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
    <!--  History Section -->
    <!-- End Of History Section -->

<?php if($message)
      { ?>
            <div <?php echo $flag_msg; ?>>  <?php echo $message ; ?></div>
<?php } ?>
    <!-- TWO PART Section -->
    <!-- End Of TWO PART Section -->



                <!-- Ticket Display -->
                <?php
                $var_maxposts = (int) $_SESSION["sess_maxpostperpage"];
                $var_maxposts = ($var_maxposts < 1) ? 1 : $var_maxposts;
                $sql = "Select * from sptbl_tickets where nTicketId='" . addslashes($var_ticketid) . "' AND nUserId = '" . addslashes($var_userid) . "'";
                $rs = executeSelect($sql, $conn);
                if(mysql_num_rows($rs) > 0)
                { //if main
                    $row = mysql_fetch_array($rs);
                    $var_username = $_SESSION['sess_username'];
                    $showflag = true;

                    if($row["vStatus"] != "closed")
                    {
                        $var_close_url = "./viewticket.php?mt=y&cl=y&tk=" . $var_ticketid . "&stylename=" . $var_stylename . "&styleminus=" . $var_styleminus . "&styleplus=" . $var_styleplus . "&\" class=\"linktext\">" . TEXT_CLOSE_TICKET;
                    }

                    $sql = "Select t.nTicketId,t.vTitle,t.tQuestion,t.dPostDate,t.vMachineIP,
			r.nReplyId,r.nStaffId,r.nUserId,r.vStaffLogin,r.dDate,r.tReply,r.tPvtMessage,r.vMachineIp as 'ReplyIp',t.vStatus 
			 from dummy d 
			Left join sptbl_tickets t on (d.num=0 AND t.nTicketId='" . addslashes($var_ticketid) . "'
			 AND t.nUserId='" . addslashes($var_userid) . "') 
			Left JOIN sptbl_replies r on (d.num=1 AND r.nTicketId='" . addslashes($var_ticketid) . "' AND r.nHold=0)
			where d.num < 2  AND (t.nTicketId IS NOT NULL OR r.nReplyId IS NOT NULL) order by r.dDate ";

                    if($_SESSION["sess_messageorder"]=="1")
                    {
                        $sql .= " ASC";
                    }
                    else
                    {
                        $sql .= " DESC";
                    }
                     
                    $totalrows = mysql_num_rows(executeSelect($sql, $conn));
                    settype($totalrows, integer);
                    settype($var_begin, integer);
                    settype($var_num, integer);
                    settype($var_numBegin, integer);
                    settype($var_start, integer);

                    $var_calc_begin = ($var_begin == 0) ? $var_start : $var_begin;

                    if(($totalrows <= $var_calc_begin))
                    {
                        $var_nor = $var_maxposts;   //presently assuming nor is number of rows
                        $var_nol = 10;   //presently assuming nol is number of links

                        if($var_num > $var_numBegin)
                        {
                            $var_num = $var_num - 1;
                            $var_numBegin = $var_numBegin;
                            $var_begin = $var_begin - $var_nor;
                        } 
                        elseif ($var_num == $var_numBegin)
                        {
                            $var_num = $var_num - 1;
                            $var_numBegin = $var_numBegin - $var_nol;
                            $var_begin = $var_calc_begin - $var_nor;
                            $var_start = "";
                        }
                    }

                    //echo("$totalrows,2,2,\"&ddlSearchType=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&id=$var_batchid&\",$var_numBegin,$var_start,$var_begin,$var_num");
                    $navigate = pageBrowser($totalrows, 10, $var_maxposts, "&mt=y&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus&tk=" . $var_ticketid . "&", $var_numBegin, $var_start, $var_begin, $var_num);

                    //execute the new query with the appended SQL bit returned by the function
                    $sql = $sql . $navigate[0];


                    $rs = executeSelect($sql, $conn);

                    $var_reply_idlist = "";
                    while($row = mysql_fetch_array($rs))
                    {
                        if($row["nReplyId"] != "")
                        {
                            $var_reply_idlist .= "," . $row["nReplyId"];
                        }
                    }
                    if(mysql_num_rows($rs) > 0)
                    {
                        mysql_data_seek($rs, 0);

                        if($var_reply_idlist != "")
                        {
                            $var_subquery = " OR  nReplyId IN(" . substr($var_reply_idlist, 1) . ")";
                        }

                        $sql_attach = "Select * from sptbl_attachments where nTicketId='" . addslashes($var_ticketid) . "' 
					" . $var_subquery . " ORDER BY nTicketId DESC,nReplyId DESC";
                        $rs_attach = executeSelect($sql_attach, $conn);

                        while ($row = mysql_fetch_array($rs))
                        {
                            if($row["nTicketId"] != "")
                            {  //Ticket section
                                $ticketStatus = $row["vStatus"];

                             ?>

                                <div class="ticket_conv_user">
                                    <div class="content_section_data">
                                        <div class="clear btm_brdr">
                                            <div class="left ticket_user_info">
                                                <table cellpadding="0" cellspacing="0" border="0" class="comm_tbl2" width="100%">
                                                    <tr align="left">
                                                        <td  width="16%" style="word-break:break-all; " align="left"><b><?php echo TEXT_USER; ?></b></td>
                                                        <td align="left"><?php echo ":&nbsp;". stripslashes($var_username); ?> </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left"><b><?php echo TEXT_DATE ?></b></td>
                                                        <td align="left"><?php echo ":&nbsp;" . date("m-d-Y H:i", strtotime($row["dPostDate"])) . ""; ?> </td>
                                                    </tr>
                                                    <?php  if ($row["vStaffLogin"] == "") {        ?>
                                                    <tr>
                                                        <td align="left"><b><?php echo TEXT_IP ?></b></td>
                                                        <td align="left"><?php echo " :&nbsp;" . $row["vMachineIP"] . ""; ?>

                                                        </td>

                                                    </tr>
                                                    <?php } ?>
                                                     <tr>
                                                     <td  width="25%" align="left"><b><?php echo TEXT_TITLE; ?></b></td>
                                                <td align="left">: <?php echo htmlentities(stripslashes($row["vTitle"])); ?></td>
                                                     </tr>
                                                      <tr>
                                                          <td valign="top"><b> <?php echo TICKET_DESCRIPTION;?></b></td>
                                                          <td align="left" valign="top"><?php echo " :&nbsp;" . nl2br(stripslashes($row["tQuestion"])); ?></td>
                                            </tr>
                                                </table>

                                            </div>  </div>
                                </div>
                                            <div class="right">
                <?php
                if ($showflag == true) {
                    ?>
                                                    <a href="replies.php?rp=q&tk=<?php echo($var_ticketid); ?>&rid=0&&stylename=<?php echo($var_stylename); ?>&styleminus=<?php echo($var_styleminus); ?>&styleplus=<?php echo($var_styleplus); ?>&" class="comm_link1"><?php echo(TEXT_QUOTE_REPLY); ?></a>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="clear"></div>
                                        </div>



                                        <!--<table width="100%" border="0" cellspacing="0" cellpadding="0">

                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">

                                            <tr align="left" >
                                                <td  width="15%" align="left"><?php echo TEXT_TITLE; ?></td>
                                                <td align="left">: <b><?php echo htmlentities(stripslashes($row["vTitle"])); ?></b></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td align="left"><?php echo nl2br(stripslashes($row["tQuestion"])); ?></td>
                                            </tr>
                                        </table>-->

                                        </td>
                                        </tr>


                                        <tr>
                                            <td colspan="4">
                                                <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="attachband">
                                                    <tr align="center">
                                                        <td colspan="4"><?php
                                echo(getAttachment($var_ticketid, ""));
                                ?></td>
                                                    </tr>
                                                </table>
                                          
						

                <?php
            } //end if ticket section			
            else { //else correspondance section
                if ($row["nStaffId"] != "") {
                    $var_style = "ticket_conv_staff";
                    $var_styletitle = "comm_tbl2";
                } elseif ($row["nUserId"] != "") {
                    $var_style = "ticket_conv_user";
                    $var_styletitle = "comm_tbl2";
                }
                ?>
                                <div class="<?php echo $var_style; ?>">
                                    <div class="content_section_data">

                                        <div class="clear btm_brdr">
                                            <div class="left ticket_user_info">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="comm_tbl2">
                                <?php
                                if ($row["nStaffId"] != "") {
                                    $var_style = "replyband";
                                    ?>
                                                        <tr>
                                                            <td  width="16%" style="word-break:break-all; "><b><?php echo TEXT_STAFF; ?></b></td>
                                                            <td><?php echo " : " . htmlentities($row["vStaffLogin"]) . ""; ?></td>
                                                        </tr>
                                                        <?php
                                                    } elseif ($row["nUserId"] != "") {

                                                        $var_style = "ticketband";
                                                        ?>
                                                        <tr>
                                                            <td width="16%" align="left" style="word-break:break-all; "><b><?php echo TEXT_USER; ?></b></td>
                                                            <td><?php echo " : " . htmlentities($var_username) . ""; ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td width="16%" align="left" style="word-break:break-all; "><b><?php echo TEXT_DATE; ?></b></td>
                                                        <td><?php echo " : " . date("m-d-Y H:i", strtotime($row["dDate"])) . ""; ?></td>
                                                    </tr>
                                                    <?php
if ($row["nStaffId"] == "") {
?>

                                                    <tr>
                                                        <td style="word-break:break-all; "><b><?php echo TEXT_IP . ""; ?></b></td>
                                                        <td><?php echo " : " . $row["ReplyIp"]; ?></td>
                                                    </tr>
                                                    <?php } ?>
                                                </table>

                                            </div>
                                            <div class="right">
                <?php
                if ($showflag == true) {
                    ?>
                                                    <a href="replies.php?rp=r&tk=<?php echo($var_ticketid); ?>&rid=<?php echo($row["nReplyId"]); ?>&&stylename=<?php echo($var_stylename); ?>&styleminus=<?php echo($var_styleminus); ?>&styleplus=<?php echo($var_styleplus); ?>&" class="comm_link1" ><?php echo(TEXT_REPLY); ?></a>
                                                    <a href="replies.php?rp=q&tk=<?php echo($var_ticketid); ?>&rid=<?php echo($row["nReplyId"]); ?>&&stylename=<?php echo($var_stylename); ?>&styleminus=<?php echo($var_styleminus); ?>&styleplus=<?php echo($var_styleplus); ?>&" class="comm_link1"><?php echo(TEXT_QUOTE_REPLY); ?></a>
                    <?php
                }
                ?>

                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">

                                            <tr>
                                                <td  class="bodycolor" >

                <?php echo stripslashes($row["tReply"]); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td >
                <?php
                echo(getAttachment("", $row["nReplyId"]));
                ?>
                                                </td>
                                            </tr>


                                        </table>
                                    </div>
                                </div>

                                                    <?php
                                                } //end else correspondance section
                                            }
                                        }
                                    }//end if main
//link display
                                    //echo($navigate[2]);

                                    function getAttachment($var_ticketid = "", $var_replyid = "") {
                                        global $rs_attach;
                                        $var_return = "";
                                        $flag = false;
                                        if (mysql_num_rows($rs_attach) > 0) {
                                            mysql_data_seek($rs_attach, 0);
                                            if ($var_ticketid != "") {
                                                while ($row = mysql_fetch_array($rs_attach)) {
                                                    if ($row["nTicketId"] != "") {
                                                        if ($row["nTicketId"] == $var_ticketid) {
                                                            $var_return .= "," . " <a href=\"javascript:var lg=window.open('attachments/" . addslashes($row["vAttachUrl"]) . "');\"  class=\"attachband\">" . htmlentities($row["vAttachReference"]) . "</a>";
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
                                                        $var_return .= "," . " <a href=\"javascript:var lg=window.open('attachments/" . addslashes($row["vAttachUrl"]) . "');\"  class=\"attachband\">" . htmlentities($row["vAttachReference"]) . "</a>";
                                                        $flag = true;
                                                    } elseif ($flag == true) {
                                                        break;
                                                    }
                                                }
                                            }
                                        } else {
                                            return "";
                                        }
                                        return (($var_return != "") ? TEXT_ATTACHMENT . " : " . substr($var_return, 1) : "");
                                    }
                                    ?>
                <!-- End Of Reply Detail -->
            </td>
        </tr>
    </table>



    <table width="100%"  border="0" cellspacing="10" cellpadding="0">
        <tr>
            <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                        <td><img src="./images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                </table>
                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="1"  ><img src="./images/spacerr.gif" width="1" height="1"></td>
                        <td class="pagecolor" align="center"><br>
                          <input type="button" class="comm_btn" name="btFeedback" id="btFeedback" value="<?php echo(TEXT_FEEDBACK1); ?>" onClick="javascript:clickFeedback();">
<?php
if ($ticketStatus <> 'closed') {
    ?>
                                &nbsp;&nbsp;&nbsp;
                                <input type="button" class="comm_btn_green" name="btClose" id="btClose" value="<?php echo(TEXT_CLOSE_TICKET1); ?>" onClick="javascript:closeTickets();">
    <?php
}
?>
                            &nbsp;&nbsp;&nbsp;
                            <input type="button" class="comm_btn" name="btReply" id="btReply" value="<?php echo(TEXT_REPLY); ?>" onClick="javascript:clickReply();"><br><br>
                        </td>
                        <td width="1" ><img src="./images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                </table>

                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td ><img src="./images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                </table></td>
        </tr>
    </table>

<?php
// added on 1-11-06 by roshith for ticket reply re-directing
if ($_GET["tk"] != "")
    $var_tid = $_GET["tk"];

$backurl = "viewticket.php?mt=y&tk=" . $var_tid . "&us=" . $var_userid . "&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&msg=replied";
$_SESSION["sess_backurl_reply_success"] = $backurl;
?>

</form>

<script language="javascript">
    // added on 3-11-06 by roshith
    function clickReply() // function called when clicking 'reply' button
    {
        document.frmReply.submit();
        //	window.location.href='<?php echo "replies.php?rp=r&tk=$var_ticketid&rid=0&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus"; ?>';
    }

    function closeTickets() // function called when clicking 'close ticket' button
    {
        document.frmClose.submit();
        //	window.location.href='<?php echo "$var_close_url"; ?>';
    }

    function clickFeedback() // function called when clicking 'feedback' button
    {
        document.frmFeedback.submit();
        //	window.location.href='<?php echo "editfeedback.php?tk=$var_ticketid"; ?>';
    }

</script>

<form name=frmReply  action=<?php echo "replies.php?rp=r&tk=$var_ticketid&rid=0&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus"; ?> method=post >
</form>

<form name=frmClose action='<?php echo "$var_close_url"; ?>' method=post >
</form>

<form name=frmFeedback action=<?php echo "editfeedback.php?tk=$var_ticketid"; ?> method=post >
</form>

