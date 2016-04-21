<?php

    $getMergeTicketIdQuery = "SELECT merged_from
                              FROM sptbl_tickets
                              WHERE nTicketId='" . mysql_real_escape_string($var_ticketid)."'
                              AND nUserId='" . mysql_real_escape_string($var_userid) . "'";
    $getMergeTicketIdResult = executeSelect($getMergeTicketIdQuery,$conn);
    $getMergeTicketIdData   = mysql_fetch_assoc($getMergeTicketIdResult);
    $getMergeTicketIds      =  $getMergeTicketIdData['merged_from'];

    if($getMergeTicketIds){
        $mergedTicketsQuery = "SELECT * FROM sptbl_tickets
                               WHERE nTicketId IN (" . mysql_real_escape_string($getMergeTicketIds) . ") ORDER BY dPostDate DESC ";
        $mergedTicketsResult = executeSelect($mergedTicketsQuery,$conn);
    }
    if($mergedTicketsResult){
    if(mysql_num_rows($mergedTicketsResult) > 0){ ?>
    <div style="border: solid 1px #999; padding: 10px;">
    <?php
        while($mergedTicketsData   = mysql_fetch_assoc($mergedTicketsResult)){ //echo '<pre>'; print_r($mergedTicketsData); echo '</pre>';

            $ticketId = $mergedTicketsData["nTicketId"];
            $userId = $mergedTicketsData["nUserId"];
            $userName = $mergedTicketsData["vUserName"];

            $sql_attach = "Select * from sptbl_attachments where nTicketId='" . mysql_real_escape_string($ticketId)  . "'
					" . $var_subquery . " ORDER BY nTicketId DESC,nReplyId DESC";
            $rs_attach = executeSelect($sql_attach,$conn);

            ?>
        <div>
            <h3>Merged Tickets</h3>
            <div class="ticket_conv_user">
                <div class="content_section_data" >

                    <div class="clear btm_brdr">
                        <div class="left ticket_user_info">
                            <table cellpadding="0" cellspacing="0" border="0" class="comm_tbl2" width="100%">
                                <tr align="left">
                                    <td  width="16%" style="word-break:break-all; "><?php echo TEXT_USER;?></td>
                                    <td>
                                    <?php echo $mergedTicketsData["eReplySentstatus"];?>
                                    <?php echo " :<a href=\"javascript:userdetails('$userId')\" > <b>" . htmlentities(stripslashes($userName))."</a></b>"; ?> </td>
                                </tr>
                                <tr>
                                    <td><?php echo TEXT_DATE ?></td>
                                    <td><?php echo  " :&nbsp;<b><span>" . date("m-d-Y H:i",strtotime($mergedTicketsData["dPostDate"]))."</b></span>"; ?> </td>
                                </tr>
                                <tr>
                                    <td ><?php  echo TEXT_IP ?></td>
                                    <td><?php echo " :&nbsp;<b><span>" . $mergedTicketsData["vMachineIP"]."</b></span>"; ?>
                                    </td>
                                </tr>
                            </table>

                        </div>
                        <div class="clear"></div>
                    </div>

                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="comm_tbl2">
                        <tr>
                            <td style="word-break:break-all; " width="15%" align="left" valign="top"><?php echo TEXT_TITLE;?></td>
                            <td align="left" valign="top">:&nbsp;<b><?php echo htmlentities(stripslashes($mergedTicketsData["vTitle"])); ?></b></td>
                        </tr>
                        <tr>
                            <td valign="top" ><?php echo TICKET_DESCRIPTION;?></td>
                            <td style="word-break:break-all;"> : &nbsp;
                                    <?php  echo html_entity_decode(htmlspecialchars(stripslashes(($mergedTicketsData["tQuestion"])))); ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <?php echo(getTicketAttachment($ticketId,""));   ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <?php

            $mergedReplyQuery = "SELECT r.*,u.vUserName,r.vMachineIP as 'ReplyIp'
                                 FROM sptbl_replies r
                                 LEFT JOIN sptbl_users u on u.nUserId= r.nUserId
                                 WHERE r.nTicketId = " . mysql_real_escape_string($ticketId) . " ORDER BY r.dDate DESC ";
            $mergedReplyResult = executeSelect($mergedReplyQuery,$conn);
            if(mysql_num_rows($mergedTicketsResult) > 0){
                while($mergedReplyData   = mysql_fetch_assoc($mergedReplyResult)){ //echo '<pre>'; print_r($mergedReplyData); echo '</pre>';

                    if ($mergedReplyData["nStaffId"] != "") {
                        $var_style = "ticket_conv_staff";
                        $var_styletitle = "comm_tbl2";
                    }
                    elseif ($mergedReplyData["nUserId"] != "") {
                        $var_style = "ticket_conv_user";
                        $var_styletitle = "comm_tbl2";
                    }
                ?>

        <div class="<?php echo $var_style;?>">
            <div class="content_section_data">
                <div class="clear btm_brdr">
                    <div class="left ticket_user_info">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="comm_tbl2">
                            <?php
                            if ($mergedReplyData["nStaffId"] != "") {
                                $var_style = "replyband";
                            ?>
                                <tr>
                                    <td  width="16%" style="word-break:break-all; "><?php echo TEXT_STAFF;?></td>
                                    <td><?php echo  " :<b><span> " .  htmlentities($mergedReplyData["vStaffLogin"]) ."</span></b>";?></td>
                                </tr>
                            <?php
                            }
                            elseif ($mergedReplyData["nUserId"] != "") {
                                $var_style = "ticketband";
                            ?>
                                <tr>
                                    <td style="word-break:break-all; "><?php echo TEXT_USER;?></td>
                                    <td><?php echo  " : <b><span>" .  htmlentities($mergedReplyData["vUserName"]) ."</span></b>";?></td>
                                </tr>
                                <?php
                                }
                                ?>
                            <tr>
                                <td style="word-break:break-all; "><?php echo TEXT_DATE ;?></td>
                                <td><?php echo  " :<b><span> " .  date("m-d-Y H:i",strtotime($mergedReplyData["dDate"])) ."</span></b>";?></td>
                            </tr>
                            <tr>
                                <td style="word-break:break-all; "><?php echo TEXT_IP ."</span></b>" ;?></td>
                                <td><?php echo  " : <b><span>" .  $mergedReplyData["ReplyIp"];?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="clear"></div>
                </div>

                <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="comm_tbl2">
                    <tr align="left">
                        <td colspan="4" width="10%" style="word-break:break-all;">
                            <?php
                            $reply = str_replace('<p>','',$mergedReplyData["tReply"]);
                            $reply = str_replace('</p>','<br>',$reply);
                            $reply = str_replace('<br><br>','<br>',$reply);

                            echo html_entity_decode(htmlspecialchars(stripslashes(nl2br($reply))));
                            ?>
                        </td>
                    </tr>

                    <?php
                    if ($var_staffid == $mergedReplyData["nStaffId"] && trim($mergedReplyData["tPvtMessage"]) != "") {
                    ?>
                        <tr>
                            <td colspan=4 class="listing" align="left">Comments</td>
                        </tr>
                        <tr>
                            <td colspan=4 align="left" style="word-break:break-all;" class="listing"><?php echo nl2br(htmlentities(stripslashes($mergedReplyData["tPvtMessage"]))); ?></td>
                        </tr>
                        <?php

                        }
                        ?>
                        <tr align="center">
                            <td colspan="4">
                            <?php
                                echo(getTicketAttachment("",$mergedReplyData["nReplyId"]));
                            ?></td>
                        </tr>

                </table>
            </div>
        </div>

        <?php
            }
        }

    } ?>
    </div>
    <?php
    }
    }


    function getTicketAttachment($var_ticketid="",$var_replyid="") {
            global $rs_attach;
            $var_return = "";
            $flag = false;
            if(mysql_num_rows($rs_attach) > 0) {
                mysql_data_seek($rs_attach,0);
                if($var_ticketid != "") {
                    //$var_return  = "<div class='content_sub_box'>";
                    while($row = mysql_fetch_array($rs_attach)) {
                        if($row["nTicketId"] != "") {
                            if($row["nTicketId"] == $var_ticketid) {
                                $var_return .= "," . " <a href=\"javascript:var lg=window.open('../attachments/" . mysql_real_escape_string($row["vAttachUrl"]) . "');\"  class=\"attachband\">". htmlentities($row["vAttachReference"]) . "</a>";
                                $flag = true;
                            }
                            elseif($flag == true) {
                                break;
                            }
                        }
                        else {
                            break;
                        }
                    }
                }
                elseif($var_replyid != "") {
                    while($row = mysql_fetch_array($rs_attach)) {
                        if($row["nReplyId"] == $var_replyid) {
                            $var_return .= "," . " <a href=\"javascript:var lg=window.open('../attachments/" . mysql_real_escape_string($row["vAttachUrl"]) . "');\"  class=\"attachband\">". htmlentities($row["vAttachReference"]) . "</a>";
                            $flag = true;
                        }
                        elseif($flag == true) {
                            break;
                        }
                    }
                }
                //$var_return .= "</div>" ;
            }
            else {
                return "";
            }
            return (($var_return != "")?TEXT_ATTACHMENTS . " : " . substr($var_return,1):"");
        }
    ?>
