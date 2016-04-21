<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>                                 |
// |                                                                                       // |
// +----------------------------------------------------------------------+
  $page = 'adminmain';
if(include('../fusionchart/Class/FusionCharts.php'))
    if(include('../fusionchart/graph/graph.php'))
        if(!isset($_GET['range']) || $_GET['range']=='Month') {
            $first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
            $last_day_this_month = date('Y-m-t');
            $where = "dPostDate>='".$first_day_this_month."' AND dPostDate<='".$last_day_this_month."'";
            $incident_where = " AND dLastAttempted>='".$first_day_this_month."' AND dLastAttempted<='".$last_day_this_month."'  ";
        }elseif(!isset($_GET['range']) || $_GET['range']=='Year')
                 {
        $year = date('Y');

        $first_day_this_month = $year.'-'.'01'.'-'.'01';
        $last_day_this_month =  $year.'-'.'12'.'-'.'31';
                 $where = "dPostDate>='".$first_day_this_month."' AND dPostDate<='".$last_day_this_month."'";
                  $incident_where = " AND dLastAttempted>='".$first_day_this_month."' AND dLastAttempted<='".$last_day_this_month."'  ";
                 }
                 elseif(!isset($_GET['range']) || $_GET['range']=='Week')
                     {
$first_day_this_month = date('Y-m-d', strtotime('Last Monday', time()));
$last_day_this_month = date('Y-m-d', strtotime('Next Sunday', time()));
  $where = "dPostDate>='".$first_day_this_month."' AND dPostDate<='".$last_day_this_month."'";
   $incident_where = " AND dLastAttempted>='".$first_day_this_month."' AND dLastAttempted<='".$last_day_this_month."'  ";
                     } elseif(!isset($_GET['range']) || $_GET['range']=='All')
                         {
                         $where = "1";
                          $incident_where = "";
                         }

$sql_ticket = "SELECT vStatus,count(nTicketId) AS c FROM sptbl_tickets WHERE ".$where." GROUP BY vStatus";

$res = mysql_query($sql_ticket);

$sql_priority = "SELECT vPriorityDesc,count(nTicketId) AS c FROM sptbl_tickets sptl INNER JOIN sptbl_priorities sp  ON sp.nPriorityValue=sptl.vPriority WHERE ".$where." GROUP BY sptl.vPriority";

$res_priority = mysql_query($sql_priority);

$dept_tickets_q = "SELECT count(nTicketId) AS c,vDeptDesc FROM sptbl_tickets sptl INNER JOIN sptbl_depts sd ON sd.nDeptId=sptl.nDeptId WHERE ".$where." GROUP BY sptl.nDeptId";

$dept_tickets = mysql_query($dept_tickets_q);


$res_staff_rating = "SELECT AVG(nMarks) AS mark,vStaffname FROM sptbl_staffratings str INNER JOIN sptbl_staffs ss ON str.nStaffId = ss.nStaffId GROUP BY str.nStaffId ORDER BY mark DESC LIMIT 0,4";
//echo $res_staff_rating;

$staff_rating = mysql_query($res_staff_rating);
$i=1;
if(mysql_num_rows($res)>0){
while($row=mysql_fetch_array($res)) {
    $arrData[$i-1][0] = $row['vStatus'];
    $arrData[$i-1][1] = $row['c'];
    $i++;

}}
$j=1;
if(mysql_num_rows($res_priority)>0){
while($row=mysql_fetch_array($res_priority)) {


    $arrData_priority[$j-1][0] = $row['vPriorityDesc'];
    $arrData_priority[$j-1][1] = $row['c'];
    $j++;

}
}

$k=1;
if(mysql_num_rows($staff_rating)>0){
while($row=mysql_fetch_array($staff_rating)) {


    $arrData_rating[$k-1][0] = $row['vStaffname'];
    $arrData_rating[$k-1][1] = $row['mark'];
    $k++;

}
}




$incident_query="SELECT MAX( REP.`dDate` ) as rep_date , TCK.dPostDate, REP.`nStaffId`, TCK.nDeptId, TCK.`nTicketId`, STF.vLogin 
FROM sptbl_tickets TCK, `sptbl_replies` REP LEFT JOIN sptbl_staffs STF ON STF.nStaffId = REP.nStaffId 
WHERE TCK.`nTicketId` = REP.`nTicketId`
AND REP.`nUserId` IS NULL
AND TCK.vStatus LIKE 'closed' ".$incident_where."
GROUP BY REP.`nTicketId`";
//echo $incident_query;
$ticketQry = mysql_query($incident_query);

$slaQry = mysql_query("SELECT nResponseTime, nDeptId FROM `sptbl_depts`");
if(mysql_num_rows($slaQry)) {
    while($slaRes = mysql_fetch_array($slaQry)) {
        $dept[$slaRes['nDeptId']] = $slaRes['nResponseTime'];
    }
}

if($ticketQry) {
    while($ticketRes = mysql_fetch_array($ticketQry)) {
        $diff = (strtotime($ticketRes['rep_date']) - strtotime($ticketRes['dPostDate'])) / 60;
        if($diff > $dept[$ticketRes['nDeptId']]) {
            $staff[$ticketRes['vLogin']]['delayed'][] = $ticketRes['nTicketId'];
        }
        else {
            $staff[$ticketRes['vLogin']]['on_time'][] = $ticketRes['nTicketId'];
        }
    }
    if(count($staff)>0)
    {
    foreach($staff as $key => $val) {
        $staff_total[$key] = count($staff[$key]['delayed']) + count($staff[$key]['on_time']);
    }

    arsort($staff_total);
    }
    $counter = 1;
    if(count($staff_total)>0)
    {
    foreach($staff_total as $key => $val) {
       // if($counter <= 5) {
            if(!isset($staff[$key]['delayed'])) {
                $final_staff[$key]['delayed'] = 0;
            }
            else {
                $final_staff[$key]['delayed'] = count($staff[$key]['delayed']);
            }
            if(!isset($staff[$key]['on_time'])) {
                $final_staff[$key]['on_time'] = 0;
            }
            else {
                $final_staff[$key]['on_time'] = count($staff[$key]['on_time']);
            }
       // }
        $counter++;
    }
    }

   // print_r($final_staff);
}














?>


<script language='javascript' src='../fusionchart/JSClass/FusionCharts.js'></script>
<div class="drop_down_container">
  <form name="graphfilter" method="get" action="">
    <div align="right">
        
        <select class="comm_input" onchange="document.graphfilter.submit();" name="range">
            <option value="Month" <?php if($_GET['range']=='Month'){echo "selected";} ?>><?php echo TEXT_MONTH; ?></option>
            <option value="Week" <?php if($_GET['range']=='Week'){echo "selected";} ?>><?php echo TEXT_WEEK; ?></option>
            <option value="Year" <?php if($_GET['range']=='Year'){echo "selected";} ?>><?php echo TEXT_YEAR; ?></option>
            <option value="All" <?php if($_GET['range']=='All'){echo "selected";} ?>><?php echo TEXT_ALL; ?></option>
	    
  </select>
    </div>
  </form>
</div>

<div class="DB_container_1">
    <div class="DB_container_contentbox1">
       <h2><?php echo TICKETS_BY_STATUS ?></h2>
        <?php
        /*$graphObj3 = new graph(8,300,200);
        $graphObj3->setChartParams('','','',0,'');*/
         $graphObj3 = new graph(3,300,200);
         $graphObj3->setChartParams('','','',0);
        $graphObj3->addChartData($arrData);

        $graphObj3->renderChart();?>
    </div>
    <div class="DB_container_contentbox1"><h2><?php echo TICKETS_BY_PRIORITY ?></h2>
        <div class="DB_container_content">
    <?php
    $graphObj3 = new graph(3,300,200);
            $graphObj3->setChartParams('','','',0);
            $graphObj3->addChartData($arrData_priority);

            $graphObj3->renderChart();?></div>
    </div>

    <div class="DB_container_contentbox2"><h2><?php echo TICKETS_BY_DEPT ?></h2>
        <?php


        $i=1;
        $chartColours = array ("1" => "AFD8F8",
                "2" => "F6BD0F",
                "3" => "8BBA00",
                "4" => "FF8E46",
                "5" => "008E8E",
                "6" => "008ED6",
                "7" => "9D080D",
                "8" => "A186BE");

//$graphXmlSource = "<chart caption='Plans Chosen By Brands' xAxisName='Month' yAxisName='Units' showValues='0' formatNumberScale='0' showBorder='0'>";
        $strXML  = "";
        $strXML .= "<chart caption='' formatNumberScale='0' showPercentValues='0' bgcolor='#ffffff' showBorder='0'>";

        if(mysql_num_rows($dept_tickets)>0){
        while($row=mysql_fetch_array($dept_tickets)) {
           // echo str_replace("'","_",$row["vDeptDesc"]).$row["c"];
           // $arrPie[$i-1][0] = str_replace("'","s",$row["vDeptDesc"]);
            //$arrPie[$i-1][1] = $row["c"];
           

            $strXML .= "<set label='".str_replace("'","_",$row["vDeptDesc"])."' value='".$row["c"]."' isSliced='".$i."'/>";
       
             $i++;
       }}
        $strXML .= "</chart>";

      /*  $graphObj3 = new graph(4,200,200);
//$graphObj3->setChartParams("Ticket Status for period of ".date('d/m/Y',strtotime($first_day_this_month))."-".date('d/m/Y',strtotime($last_day_this_month)),'','',0,'');
 $graphObj3->setChartParams('','','',0,'');
        $graphObj3->addChartData($arrPie);

$graphObj3->renderChart();*/
        //Create the chart - Column 3D Chart with data from strXML variable using dataStr method
        echo renderChart("../fusionchart/FusionCharts/Pie3D.swf", "", $strXML, "myNext", 310, 190);

        ?>

        <div class="DB_container_content"></div>
    </div>
    <div class="DB_container_contentbox4"><h2><?php echo CLOSED_BY_OWNER ?></h2>
        <div class="DB_container_content">
            <?php if(count($final_staff)>0)
               {
 $arrStaffVal[0][0] = 'Delayed';
 $arrStaffVal[0][1] = '';
$arrStaffVal[1][0] = 'On Time';
$arrStaffVal[1][1] = '';
$p=2; $l=0;
                foreach($final_staff as $key =>$val)
                    {
$arrStaffNames[$l] = $key;
$arrStaffVal[0][$p] = $val['delayed'];
$arrStaffVal[1][$p] = $val['on_time'];
          $p++;
          $l++;
                    }

                $graphObj3 = new graph(6,400,200);
//$graphObj3->setChartParams("Ticket Status for period of ".date('d/m/Y',strtotime($first_day_this_month))."-".date('d/m/Y',strtotime($last_day_this_month)),'','',0,'');
        $graphObj3->addChartData($arrStaffVal,$arrStaffNames);

        $graphObj3->renderChart();
                }
?>

        </div>
    </div>
   <!--  <div class="DB_container_contentbox1"><h2>Closed Incidents by Month</h2>
        <div class="DB_container_content"></div>
    </div> -->
   <div class="DB_container_contentbox5"><h2><?php echo STAFF_RATING_CUSTOMER ?></h2>
        <div class="DB_container_content">
            <?php $graphObj3 = new graph(8,400,200);
            $graphObj3->setChartParams('','','',0);
            $graphObj3->addChartData($arrData_rating);

            $graphObj3->renderChart();?></div>
    </div>
</div>

<div class="DB_container_2" >
    <h2><?php echo TRENDING_INFO ?></h2>
    <div class="DB_container_content">
        <?php


        $m=date('m',strtotime('-365days'));
        $y=date('Y',strtotime('-365days'));



        $strXML1  = "";
        $strXML1.="<chart caption='' yAxisName='' bgColor='#ffffff, #ffffff' numVDivLines='10' divLineAlpha='30'  labelPadding ='10' yAxisValuesPadding ='10' showValues='1' rotateValues='0'  valuePosition='Below' canvaspadding='5'  >";

        $strXML1 .= "<categories>";
        for($i=1;$i<=12;$i++) {
            $date = date('M/Y',strtotime(date($y.'-'.$m.'-'.'01', strtotime($date)) . $i." month"));
          //  $arrCatName[$i]  = $date;
            $strXML1 .= "<category label='".$date."' />";
        }

        $strXML1 .="</categories>";


        $strXML1 .="<dataset seriesName='closed'>";
       // $arrData1[0][0] = TEXT_CLOSED;
        // $arrData1[0][1] = "";
        for($i=1;$i<=12;$i++) {
            $Ym = date('Y',strtotime(date($y.'-'.$m.'-'.'01', strtotime($date)) . $i." month"));
            $mm = date('m',strtotime(date($y.'-'.$m.'-'.'01', strtotime($date)) . $i." month"));
            $st = $Ym.'-'.$mm.'-'.'01';
            $end = $Ym.'-'.$mm.'-'.'31';
            $tick_mont_q = "SELECT nTicketId FROM sptbl_tickets WHERE vStatus='closed' AND dPostDate>='".$st."' AND dPostDate<='".$end."'";
//echo $tick_mont_q;
            $tick_count = mysql_query($tick_mont_q);
            $count = mysql_num_rows($tick_count);
          //  $arrData1[0][$i+1] =$count;
           
            $strXML1 .=" <set value='".$count."' />";

        }
        $strXML1 .=" </dataset>";


        $strXML1 .=" <dataset seriesName='pending' color='F6BD0F'>";
       // $arrData1[1][0] = TEXT_PENDING;
       //  $arrData1[1][1] = "";
        for($i=1;$i<=12;$i++) {
            $Ym = date('Y',strtotime(date($y.'-'.$m.'-'.'01', strtotime($date)) . $i." month"));
            $mm = date('m',strtotime(date($y.'-'.$m.'-'.'01', strtotime($date)) . $i." month"));
            $st = $Ym.'-'.$mm.'-'.'01';
            $end = $Ym.'-'.$mm.'-'.'31';
            $tick_mont_o = "SELECT nTicketId FROM sptbl_tickets WHERE dLastAttempted>='".$st."' AND dLastAttempted<='".$end."'";
            $tick_count = mysql_query($tick_mont_o);
            $count = mysql_num_rows($tick_count);
           // $arrData1[1][$i+1] = $count;
            $strXML1 .=" <set value='".$count."' />";

        }


        $strXML1 .="  </dataset>


</chart>";

        //$graphObj3 = new graph(2,986,170);
//$graphObj3->setChartParams("Ticket Status for period of ".date('d/m/Y',strtotime($first_day_this_month))."-".date('d/m/Y',strtotime($last_day_this_month)),'','',0,'');
        //$graphObj3->addChartData($arrData1,$arrCatName);

        //$graphObj3->renderChart();
        //Create the chart - Column 3D Chart with data from strXML variable using dataStr method
        echo renderChartHTML("../fusionchart/FusionCharts/MSLine.swf", "", $strXML1, "myNext", '987', '250', true, true);

        ?>

<div class="clear"></div>
    </div>
</div>
<div class="comm_spacediv">&nbsp;</div>
<!--<div class="DB_container_3">
  <!--  <h2><?php echo TEXT_LICENCE_INFORMATION; ?></h2>-->
    <!--<div class="DB_container_content">
       
      
	                                            
                                           <!-- <table width="100%"  border="0" align="left" cellpadding="8" cellspacing="0">
                                                <tr align="left" bgcolor="#FFFFFF" class="listingmaintext">
                                                    <td ><B><?php echo SITE_LICENSEKEY; ?></B> </td>
                                                    <td >:</td>
                                                    <td ><?php echo $_SESSION["sess_licensekey"];?></td>
                                                </tr>
                                                <tr align="left" bgcolor="#FFFFFF" class="listingmaintext">
                                                    <td width="40%" ><B><?php echo TEXT_INSTALLED_VERSION; ?></B> </td>
                                                    <td width="8%" >:</td>
                                                    <td width="52%" >4.0</td>
                                                </tr>
                                                <tr align="left" bgcolor="#FFFFFF" class="listingmaintext">
                                                    <td ><B><?php echo TEXT_CURRENT_VERSION; ?></B> </td>
                                                    <td >:</td>
                                                    <td >4.0</td>
                                                </tr>
                                            </table>
                                        
				
		
		
    </div>
</div> -->


