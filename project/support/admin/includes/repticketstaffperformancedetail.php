<!--- report detail-->
<?php
include('../fusionchart/Class/FusionCharts.php');
include('../fusionchart/graph/graph.php');


//$var_sdate = dateFormat($var_sdate,"m/d/Y","m-d-Y");
//$var_edate = dateFormat($var_edate,"m/d/Y","m-d-Y");
$var_msdate = datetimetomysql($var_sdate);
$var_medate = datetimetomysql($var_edate);
$var_sdate_disp 	= dateFormat($var_sdate,"m/d/Y","M, d Y");
$var_edate_disp 	= dateFormat($var_edate,"m/d/Y","M, d Y");
$limitst = 10;
/* $sql = " select s.nStaffId,s.vLogin,count(r.nReplyId) as rpcnt,";
  $sql .=" count(r.nReplyId)-count(r1.tPvtMessage) as cmtcnt,count(p.nStaffId) as pntcnt,";
  $sql .=" sum(r3.vReplyTime)/count(r.nReplyId) as avgtimecnt from sptbl_staffs  s ";
  $sql .=" inner join dummy d on (d.num<4) left join sptbl_replies r on(d.num=0 and";
  $sql .=" s.nStaffId=r.nStaffId and (r.dDate>='$var_msdate' and r.dDate<='$var_medate')) left join sptbl_replies r1 on(d.num=1 and s.nStaffId=r1.nStaffId ";
  $sql .=" and r1.tPvtMessage='' and (r1.dDate>='$var_msdate' and r1.dDate<='$var_medate')) left join sptbl_personalnotes p on(d.num=2 and ";
  $sql .=" s.nStaffId=p.nStaffId and  (p.dDate>='$var_msdate' and p.dDate<='$var_medate') ) left join sptbl_replies r3 on(d.num=3 and s.nStaffId=r3.nStaffId and r3.vReplyTime>0 and (r3.dDate>='$var_msdate' and r3.dDate<='$var_medate'))";
 */
$staff = array();
$arrData =array();
$limitstart = $_GET['start'] == ""?0:$_GET['start'];
$limitend = $_GET['end'] == ""?$limitst:$_GET['end'];
if ($var_staffcmbid <> 0) {
    $sqlUser = mysql_query("SELECT nStaffId,vStaffname,vLogin FROM sptbl_staffs WHERE nStaffId = '" . mysql_real_escape_string($var_staffcmbid) . "' LIMIT ".$limitstart . ", " .$limitend);
} else {
    $sqlUser = mysql_query("SELECT nStaffId,vStaffname,vLogin FROM sptbl_staffs LIMIT ".$limitstart . ", " .$limitend);
}
if (mysql_num_rows($sqlUser) > 0) {
    while($row_stf = mysql_fetch_array($sqlUser)){
    $staff[$row_stf['nStaffId']] = $row_stf['vStaffname'];
    }
}
?>
<br>
<div class="content_section_title"><h3><?php echo TEXT_LBL1 . "&nbsp;" . $var_sdate_disp . "&nbsp;" . TEXT_LBL2 . "&nbsp;" . $var_edate_disp . ""; ?></h3></div>

<table width="100%" border="0"  cellpadding="0" cellspacing="0" class="list_tbl">

<?php

$dateCondition = " AND DATE_FORMAT(REP.dDate,'%Y-%m-%d') >= '$var_msdate' AND DATE_FORMAT(REP.dDate,'%Y-%m-%d') <= '$var_medate'";
if (count($staff) > 0) {

//*************Get staff perfomance by Manual time**************
    $count = 0;
foreach ($staff as $key => $value) {

   $staff_id = $key;
 
                  $replyticketQry = "SELECT COUNT( REP.`nTicketId` ) as reply_count
                                          FROM `sptbl_replies` REP
                                          WHERE REP.`nStaffId` = " . $staff_id . " AND DATE_FORMAT(REP.dDate,'%Y-%m-%d') >= '$var_msdate' AND DATE_FORMAT(REP.dDate,'%Y-%m-%d') <= '$var_medate'
                                          GROUP BY REP.`nTicketId`";

                   $replyTimeQry = "SELECT  SUM( REP.`vReplyTime` ) as reply_time
                                          FROM `sptbl_replies` REP
                                          WHERE REP.`nStaffId` = " . $staff_id . " AND DATE_FORMAT(REP.dDate,'%Y-%m-%d') >= '$var_msdate' AND DATE_FORMAT(REP.dDate,'%Y-%m-%d') <= '$var_medate'
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

                        if($replytimeCountVal > 0) {

                            $stff_time_avg =  number_format($replytimeCountVal / $replyCountVal);
                        }else {
                            $stff_time_avg = 0;
                        }
                    }
                    $arrData[$count][0] = $value;
                    $arrData[$count][1] = $stff_time_avg;
                    $count++;
}
                  ?>


            <tr>
                <td colspan="2">
                    <?php
                   $staffperfomance = TEXT_REPORTS_STAFF_SUMMARY_USER;
                    $graphObj3 = new graph(8,900,350);
                    $graphObj3->setChartParams($staffperfomance, 1, 1, 0);
                    $graphObj3->addChartData($arrData);
                    $graphObj3->renderChart();
                  
                    ?>
                </td>
            </tr>

        <?php
        //*************Get staff perfomance by Manual time ends**************

      //*************Get staff perfomance by Auto time ends**************

            $count = 0;
foreach ($staff as $key => $stf_value) {
     $staffid = $key;
     $avgSatffTime = 0;
   $staffTicket_arr = getTicketID($staffid,$dateCondition );
  /* echo '<pre>';
   print_r($staffTicket_arr);
   echo '</pre>';*/
   if(count($staffTicket_arr) > 0){// Time for one staff
       $ticketTimeTotal = 0;
    foreach ($staffTicket_arr as $key => $tick_value) {// Time for each ticket
        $ticketTimeTotal+= getTicketMinitsOffTicket($tick_value , $staffid);

    }// Time for each ticket ends

   $totalTicketCount =  getStaffTicketCount($staffid , $dateCondition);
   $ticketTimeTotal;
   $totalTicketCount;
   if($totalTicketCount > 0){
        $avgSatffTime = $ticketTimeTotal / $totalTicketCount;
   }

   $avgSatffTime =  floor($avgSatffTime);
 //  echo '<br>stf : '.$staffid. 'avg '. $avgSatffTime;

       
   }//if ends
     $arrDataAuto[$count][0] = $stf_value;
     $arrDataAuto[$count][1] = $avgSatffTime;
      $count++;
}

?>

     <tr>
                <td colspan="2">
                    <?php
                   $staffperfomance_auto = TEXT_REPORTS_STAFF_SUMMARY_AUTO;
                    $graphObj3 = new graph(8,900,350);
                    $graphObj3->setChartParams($staffperfomance_auto, 1, 1, 0);
                    $graphObj3->addChartData($arrDataAuto);
                    $graphObj3->renderChart();
                    ?>
                </td>
            </tr>
            <tr><td colspan="2" align="center">
                    <div class="pagination_links">
                    <?php
                    $st =0;
                    
                    $end = $limitst;
                    $pageCount=0;
                   $totalStaff = getStaffCount();
                    if($totalStaff > 0)
                   {
                 do {
                        $pageCount ++;
                    $link = "repstaffperfomance.php?start=$st&end=$end&startdate=$var_sdate&enddate=$var_edate";
                    if($limitstart == $st){
                        $pageCount_disp = '<b>' .$pageCount .'</b> ';
                    }else{
                        $pageCount_disp = $pageCount;
                    }
                    $st =$end;
                  $end = $end + $limitst;

                     ?>
                    <a class="listing" href="<?php echo $link;?>"><?php echo $pageCount_disp;?></a>
                    <?php
                    } while ($end < $totalStaff+$limitst);
                   
                        }
                        ?>
                    </div>
                </td></tr>
            <?php



        } else { ?>
            <tr><td colspan="2" align="center"><?php echo TEXT_NO_RECORDS ?></td></tr>
            <?php
      
        }
?>

            <?php
            function getTicketID($staffid, $dateCondition){
                $ticket_arr = array();
                $sqlTicket = "SELECT DISTINCT nTicketId FROM  sptbl_replies REP WHERE nStaffId = '" . mysql_real_escape_string($staffid) . "' " .$dateCondition;
                $resTicket =mysql_query($sqlTicket);
                if (mysql_num_rows($resTicket) > 0) {
                    while($row_Ticket = mysql_fetch_array($resTicket)){
                    $ticket_arr[] = $row_Ticket['nTicketId'];
                    }
                }
                return $ticket_arr;
            }//function ends



            function getTicketMinitsOffTicket($ticketID , $staffID){
                $timeMins = 0;
                $start_time = "";
                $resReplay = "";
                $sqlTicket = "SELECT dPostDate 	 FROM  sptbl_tickets  WHERE   nTicketId = '" . mysql_real_escape_string($ticketID) . "' ORDER BY nTicketId";
                $resTicket =mysql_query($sqlTicket);
                    if (mysql_num_rows($resTicket) > 0) {
                        $rowTicket = mysql_fetch_array($resTicket);
                       $start_time = $rowTicket['dPostDate'];
                    }

              $sqlReplay = "SELECT nReplyId , nTicketId , dDate  FROM sptbl_replies REP WHERE REP.nStaffId = '" . mysql_real_escape_string($staffID) . "' AND  nTicketId = '" . mysql_real_escape_string($ticketID) . "' ORDER BY nTicketId";
                $resReplay =mysql_query($sqlReplay);
                    if (mysql_num_rows($resReplay) > 0) {
                        $rowReplay = mysql_fetch_array($resReplay);
                             $endtime = $rowReplay['dDate'];
                            $mins =  doConvertToMin(dateDifference($start_time,$endtime)) ;
                             $timeMins+=$mins;
                        while($rowReplay = mysql_fetch_array($resReplay)){
                               $start_time = $endtime;
                               $endtime = $rowReplay['dDate'];
                               $mins =  doConvertToMin(dateDifference($start_time,$endtime)) ;
                               $timeMins+=$mins;
                            }
                         
                          
                        }
                       // echo '<br>stf: ' .$staffID . ', tic : '.$ticketID.' ,  min :  ' .$timeMins;

                 return $timeMins;

            }//end function
            function getStaffTicketCount($staffID, $dateCondition){
            $sqlReplay = "SELECT DISTINCT nTicketId FROM sptbl_replies REP WHERE REP.nStaffId = '" . mysql_real_escape_string($staffID) . "' ". $dateCondition ." ORDER BY nTicketId";
                $resReplay = mysql_query($sqlReplay);
                  return mysql_num_rows($resReplay);
            }
            function getStaffCount() {
                $sqlUser = mysql_query("SELECT count(nStaffId) as stCount FROM sptbl_staffs");

                if (mysql_num_rows($sqlUser) > 0) {
                    $row_stf = mysql_fetch_array($sqlUser);
                       return $row_stf['stCount'];
               
                }
            }
            ?>
</table>