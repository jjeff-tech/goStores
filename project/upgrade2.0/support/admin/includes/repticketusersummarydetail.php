<!--- report detail-->
<?php
include('../fusionchart/Class/FusionCharts.php');
include('../fusionchart/graph/graph.php');

$var_msdate = datetimetomysql($var_sdate);
$var_medate = datetimetomysql($var_edate);
 $var_sdate_disp 	= dateFormat($var_sdate,"m-d-Y","M, d Y");
$var_edate_disp 	= dateFormat($var_edate,"m-d-Y","M, d Y");
/*
  $sql = " select u.nUserId,u.vLogin,count(t.nTicketId) as tkcnt,count(r1.nUserId) as rpcnt,count(sr.nUserId)";
  $sql .=" as rtcnt,count(fd.nTicketId) as fdcnt from sptbl_users u inner join dummy d on (d.num<4) left join sptbl_tickets t";
  $sql .=" on(d.num=0 and u.nUserId=t.nUserId and (t.dPostDate>='$var_msdate' and t.dPostDate<='$var_medate'))";
  $sql .=" left join sptbl_replies r1 on(d.num=1 and u.nUserId=r1.nUserId and (r1.dDate>='$var_msdate' and r1.dDate<='$var_medate'))";
  $sql .=" left join sptbl_staffratings sr on(d.num=2 and u.nUserId=sr.nUserId ) ";
  $sql .=" left join sptbl_tickets t2 on(d.num=3 and u.nUserId=t2.nUserId and (t2.dPostDate>='$var_msdate' and t2.dPostDate<='$var_medate'))";
  $sql .=" left join sptbl_feedback fd on(d.num=3 and t2.nTicketId=fd.nTicketId and (fd.dDate>='$var_msdate' and fd.dDate<='$var_medate'))";
  $sql .=" where sr.vType='T'";
  if ($var_usercmbid > 0) {
  $sql .= " and u.nUserId='" . addslashes($var_usercmbid) . "' ";
  } else {
  $sql .=" and u.nUserID>0 ";
  }
  $sql .=" group by(u.nUserId) ";
 */

$sql = "SELECT COUNT(TCK.nTicketId) ticket_count, vStatus FROM sptbl_tickets TCK WHERE TCK.nUserId = '" . addslashes($var_usercmbid) . "' AND TCK.dPostDate>='$var_msdate' AND TCK.dPostDate<='$var_medate' GROUP BY TCK.vStatus";
/* ends */
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
?>
<tr class="listing">
    <td align="left" class="listingmaintext">
        <div class="content_section_title"><h3>
<?php echo TEXT_LBL1 . "&nbsp;" . $var_sdate_disp . "&nbsp;" . TEXT_LBL2 . "&nbsp" . $var_edate_disp . ""; ?></h3></div>
    </td>
</tr>
<?php
if (mysql_num_rows($rs) > 0) {
    ?>

<tr class="attachband">
    <td align="center">
        <?php echo TEXT_USER; ?>&nbsp;:&nbsp;<?php echo stripslashes($_POST['txtUsername']); ?>
    </td>
</tr>  
<?php

    while ($res = mysql_fetch_array($rs)) {
        if (isset($dataArray[$res['vStatus']])) {
            $dataArray[$res['vStatus']] = $res['ticket_count'];
        }
    }
    ?>
    <tr>
        <td>
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
    <tr  class="listing"><td><h4>No records</h4></td></tr>
<?php } ?>
