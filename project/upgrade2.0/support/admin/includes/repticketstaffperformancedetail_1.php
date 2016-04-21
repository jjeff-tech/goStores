<!--- report detail-->
<?php
include('../fusionchart/Class/FusionCharts.php');
include('../fusionchart/graph/graph.php');

$var_msdate = datetimetomysql($var_sdate);
$var_medate = datetimetomysql($var_edate);

/* $sql = " select s.nStaffId,s.vLogin,count(r.nReplyId) as rpcnt,";
  $sql .=" count(r.nReplyId)-count(r1.tPvtMessage) as cmtcnt,count(p.nStaffId) as pntcnt,";
  $sql .=" sum(r3.vReplyTime)/count(r.nReplyId) as avgtimecnt from sptbl_staffs  s ";
  $sql .=" inner join dummy d on (d.num<4) left join sptbl_replies r on(d.num=0 and";
  $sql .=" s.nStaffId=r.nStaffId and (r.dDate>='$var_msdate' and r.dDate<='$var_medate')) left join sptbl_replies r1 on(d.num=1 and s.nStaffId=r1.nStaffId ";
  $sql .=" and r1.tPvtMessage='' and (r1.dDate>='$var_msdate' and r1.dDate<='$var_medate')) left join sptbl_personalnotes p on(d.num=2 and ";
  $sql .=" s.nStaffId=p.nStaffId and  (p.dDate>='$var_msdate' and p.dDate<='$var_medate') ) left join sptbl_replies r3 on(d.num=3 and s.nStaffId=r3.nStaffId and r3.vReplyTime>0 and (r3.dDate>='$var_msdate' and r3.dDate<='$var_medate'))";
 */

if ($var_staffcmbid <> 0) {
    $sqlUser = mysql_query("SELECT * FROM sptbl_staffs WHERE nStaffId = '" . addslashes($var_staffcmbid) . "'");
} else {
    $sqlUser = mysql_query("SELECT * FROM sptbl_staffs");
}
?>

<!--<div class="content_section_title"><h3><?php echo TEXT_LBL1 . "&nbsp;" . $var_sdate . "&nbsp;" . TEXT_LBL2 . "&nbsp;" . $var_edate . ""; ?></h3></div>-->

<table width="100%" border="0"  cellpadding="0" cellspacing="0" class="list_tbl">

<?php
if ($sqlUser) {

    while ($userRes = mysql_fetch_array($sqlUser)) {
        ?>
        <tr class="attachband">
            <td colspan="2">
                <?php echo TEXT_STAFF; ?>&nbsp;:&nbsp;<?php echo $userRes['vLogin']; ?>
            </td>
        </tr>        
        <?php
        $sql = "SELECT COUNT(TCK.nTicketId) AS ticket_count, TCK.vStatus FROM sptbl_tickets TCK WHERE TCK.nTicketId IN (SELECT DISTINCT(REP.nTicketId) FROM sptbl_replies REP WHERE REP.dDate >= '$var_msdate' AND REP.dDate <= '$var_medate' AND REP.nStaffId = '" . $userRes['nStaffId'] . "' ) GROUP BY TCK.vStatus";
        //echo $sql;

        $rs = executeSelect($sql, $conn);

        $dataArray['closed'] = 0;
        $dataArray['open'] = 0;
        $dataArray['escalated'] = 0;

        $statusQry = mysql_query("SELECT * FROM sptbl_lookup WHERE vLookUpName LIKE 'ExtraStatus'");
        if (mysql_num_rows($statusQry) <> 0) {
            while ($statusRes = mysql_fetch_array($statusQry)) {
                $dataArray[$statusRes['vLookUpValue']] = 0;
            }
        }

        if (mysql_num_rows($rs) > 0) {
            while ($res = mysql_fetch_array($rs)) {
                if (isset($dataArray[$res['vStatus']])) {
                    $dataArray[$res['vStatus']] = $res['ticket_count'];
                }
            }
            ?>
            <tr>
                <td width="40%">
                    <?php echo TXT_AVG_INITIAL_RESPONSE_TIME; ?>
                </td>
                <td>
                    <?php
                    $responseTimeQry = "SELECT TCK.`dPostDate` , REP.dDate, TCK.`nTicketId` , REP.nStaffId, TIMEDIFF( REP.dDate, TCK.`dPostDate` ) response_time
                                                    FROM `sptbl_tickets` TCK, (
                                                        SELECT *
                                                        FROM sptbl_replies REP 
                                                        WHERE REP.dDate >= '$var_msdate' AND REP.dDate <= '$var_medate' AND REP.nStaffId ='" . $userRes['nStaffId'] . "'
                                                        ORDER BY nTicketId, `dDate`
                                                    ) REP
                                                    WHERE TCK.`nTicketId` = REP.`nTicketId`
                                                    GROUP BY REP.`nTicketId` ";

                    $responseTime = mysql_query($responseTimeQry);

                    $responseTimeVal = 0;
                    $responseTimeCount = 0;

                    if ($responseTime) {
                        while ($responseTimeRes = mysql_fetch_array($responseTime)) {
                            $time = explode(':', $responseTimeRes['response_time']);
                            $responseTimeVal = ($time[0] * 3600) + ($time[1] * 60) + $time[2];
                            $responseTimeCount++;
                        }

                        $time_taken = secondsToTime($responseTimeVal / $responseTimeCount);

                        echo $time_taken['h'] . 'hrs : ' . $time_taken['m'] . 'mins : ' . $time_taken['s'] . 'secs';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td width="40%">
                    <?php echo TXT_AVG_INITIAL_RESPONSE_PER_TICKET; ?>
                </td>
                <td>
                    <?php
                    $replyCountQry = "SELECT COUNT( REP.`nTicketId` ) as reply_count
                                          FROM `sptbl_replies` REP
                                          WHERE REP.`nStaffId` = " . $userRes['nStaffId'] . " AND REP.dDate >= '$var_msdate' AND REP.dDate <= '$var_medate'
                                          GROUP BY REP.`nTicketId`";

                    $replyCount = mysql_query($replyCountQry);

                    $replyCountVal = 0;
                    $replyCountNum = 0;

                    if ($replyCount) {
                        while ($replyCountRes = mysql_fetch_array($replyCount)) {
                            $replyCountVal += $replyCountRes['reply_count'];
                            $replyCountNum++;
                        }

                        echo number_format($replyCountVal / $replyCountNum);
                    }
                    ?>
                </td>
            </tr>
              <tr>
                <td width="40%">
                    <?php echo TXT_AVG_RESPONSE_TIME; ?>
                </td>
                <td>
                    <?php
                    $replyticketQry = "SELECT COUNT( REP.`nTicketId` ) as reply_count
                                          FROM `sptbl_replies` REP
                                          WHERE REP.`nStaffId` = " . $userRes['nStaffId'] . " AND REP.dDate >= '$var_msdate' AND REP.dDate <= '$var_medate'
                                          GROUP BY REP.`nTicketId`";

                    $replyTimeQry = "SELECT  SUM( REP.`vReplyTime` ) as reply_time
                                          FROM `sptbl_replies` REP
                                          WHERE REP.`nStaffId` = " . $userRes['nStaffId'] . " AND REP.dDate >= '$var_msdate' AND REP.dDate <= '$var_medate'
                                          GROUP BY REP.`nTicketId`";
//echo $replyticketQry;
                    $replyCount = mysql_query($replyticketQry);
                     $replyTimeSum = mysql_query($replyTimeQry);

                    $replyCountVal = 0;
                    $replyCountNum = 0;
                    $replytimeCountVal =0;

                     if ($replyTimeSum) {
                        while ($replytimeCountRes = mysql_fetch_array($replyTimeSum)) {
                            $replytimeCountVal += $replytimeCountRes['reply_time'];
                           // $replyCountNum++;
                        }
                     }

                    if ($replyCount) {
                       
                            $replyCountVal = mysql_num_rows($replyCount);
                          
                   

                        echo number_format($replytimeCountVal / $replyCountVal);
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php
                    $count = 0;
                    foreach ($dataArray AS $key => $value) {
                        $arrData[$count][0] = $key;
                        $arrData[$count][1] = $value;

                        $count++;
                    }
                    //print_r($arrData);
                    $graphObj3 = new graph(8);
                    $graphObj3->setChartParams("Ticket Statistics", 1, 1, 0);
                    $graphObj3->addChartData($arrData);
                    $graphObj3->renderChart();
                    ?>
                </td>
            </tr>

        <?php } else { ?>
            <tr><td colspan="2" align="center"><?php echo TEXT_NO_RECORDS ?></td></tr>
            <?php
        }
    }
}
?>
</table>