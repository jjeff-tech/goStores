<!--- report detail-->
<?php
include('../fusionchart/Class/FusionCharts.php');
include('../fusionchart/graph/graph.php');
//$var_sdate = dateFormat($var_sdate,"m/d/Y","m-d-Y");
//$var_edate = dateFormat($var_edate,"m/d/Y","m-d-Y");
$var_msdate= datetimetomysql($var_sdate);
$var_medate =datetimetomysql($var_edate);
$var_sdate_disp 	= dateFormat($var_sdate,"m-d-Y","M, d Y");
$var_edate_disp 	= dateFormat($var_edate,"m-d-Y","M, d Y");
?>
<div class="content_section_title"><h3><?php echo TEXT_LBL1."&nbsp;".$var_sdate_disp."&nbsp;".TEXT_LBL2."&nbsp;".$var_edate_disp."";?></h3></div>
<table width="100%" border="0"  cellpadding="0" cellspacing="0" class="list_tbl">
   
    <?php
    
       ?>
            <?php
//            $getDepartmentList  = make_departmentlistlist(0,0,$rowCompany['nCompId']);
//            print_r($getDepartmentList);
//            die;
            $sqlDept="SELECT * FROM sptbl_depts $deptString";
            $resDept = executeSelect($sqlDept,$conn);
            if(mysql_num_rows($resDept)>0) {
                while($rowDept=mysql_fetch_array($resDept)) {
                    
                    $sqlChildDept="SELECT * FROM sptbl_depts WHERE nDeptParent='".$rowDept['nDeptId']."'";
                    $resChildDept = mysql_query($sqlChildDept);
                    if(mysql_num_rows($resChildDept)==0) {
                    ?>
    <tr class="commentband1">
        <td colspan=2><?php echo TXT_DEPT_NAME ;?>&nbsp;:&nbsp;<b><span><?php echo htmlentities($rowDept['vDeptDesc']);?></span></b></td>
    </tr>

                    <?php
                    $sqlTicket =" SELECT count(t.vStatus) AS count,t.vStatus FROM  sptbl_tickets t $userTabStr WHERE t.nDeptId='".$rowDept['nDeptId']."'
                                  AND dPostDate >='".mysql_real_escape_string($var_msdate)."' AND dPostDate<='". mysql_real_escape_string($var_medate)."'
                                  $excludeEmail
                                  GROUP BY t.vStatus$uniqueEmail";
                    $resTicket = executeSelect($sqlTicket,$conn);

                    $dataArray['closed']   = 0;
                    $dataArray['open']     = 0;
                    $dataArray['escalated']= 0;

                    $statusQry = mysql_query("SELECT * FROM sptbl_lookup WHERE vLookUpName LIKE 'ExtraStatus'");
                    if(mysql_num_rows($statusQry) <> 0) {
                        while($statusRes = mysql_fetch_array($statusQry)) {
                            $dataArray[$statusRes['vLookUpValue']] = 0;
                        }
                    }

                    if(mysql_num_rows($resTicket)>0) {
                        while($rowTicket=mysql_fetch_array($resTicket)) {

                            if(isset($dataArray[$rowTicket['vStatus']])) {
                                $dataArray[$rowTicket['vStatus']] = $rowTicket['count'];
                            }
                        }

                        ?>
    <tr class="listingmaintext">
        <td colspan="2" align="center">
                                <?php
                                $count = 0;
                                $ticketTotal =  0;
                                foreach($dataArray AS $key=>$value) {
                                    $arrData[$count][0] = $key;
                                    $arrData[$count][1] = $value;
                                    $ticketTotal        = $ticketTotal+$value;
                                    $count++;
                                }

                                $graphObj3 = new graph(8);
                                $graphObj3->setChartParams(TEXT_TCKET_STATISTICS, 1, 1, 0);
                                $graphObj3->addChartData($arrData);
                                $graphObj3->renderChart();?>
        </td></tr>
        <tr class="listingmaintext"><td colspan=2><?php echo NO_TICKETS ;?>&nbsp;:&nbsp;<b><span><?php echo $ticketTotal;?></span></b></td></tr>
                        <?php
                    }
                    else {?>
    <tr class="listingmaintext"><td colspan="2" align="center"><?php echo NO_TICKETS ;?></td></tr>
                        <?php
                    }
                    
                }//New if loop
                else
                { //------------------
                    $partentCounter     = 0;
                    $ticketTotal        = 0;
                    $parentDept         = $rowDept['vDeptDesc'];
                    ?><tr><td colspan=2><table  width="100%" border="0"  cellpadding="0" cellspacing="0" class="list_tbl" style="border: 1px solid #CFCFCF;"> 
    <?php $sqlCDept="SELECT * FROM sptbl_depts WHERE (`nDeptId`='".$rowDept['nDeptId']."' OR nDeptParent='".$rowDept['nDeptId']."')";
                      $resCDept = executeSelect($sqlCDept,$conn);
                    while($rowCDept=mysql_fetch_array($resCDept)) {
                   
?>
                                <tr class="commentband1"  style="padding:1;">
        <td colspan=2 style="padding:2;"><?php if($partentCounter==0) echo "<div class='content_section_title'><h3>";  ?><?php echo TXT_DEPT_NAME ;?>&nbsp;:&nbsp;<b><span><?php echo htmlentities($rowCDept['vDeptDesc']);?></span></b><?php if($partentCounter==0) echo "</h3></div>"; $partentCounter++; ?></td>
    </tr>

                    <?php
//                    $sqlTicket =" SELECT count(vStatus) AS count,vStatus FROM  sptbl_tickets WHERE nDeptId='".$rowCDept['nDeptId']."'
//                                  AND dPostDate >='".mysql_real_escape_string($var_msdate)."' AND dPostDate<='". mysql_real_escape_string($var_medate)."'
//                                  GROUP BY vStatus";
                    $sqlTicket =" SELECT count(t.vStatus) AS count,t.vStatus FROM  sptbl_tickets t $userTabStr WHERE t.nDeptId='".$rowCDept['nDeptId']."'
                                  AND dPostDate >='".mysql_real_escape_string($var_msdate)."' AND dPostDate<='". mysql_real_escape_string($var_medate)."'
                                  $excludeEmail
                                  GROUP BY t.vStatus$uniqueEmail";
                    $resTicket = executeSelect($sqlTicket,$conn);

                    $dataArray['closed']   = 0;
                    $dataArray['open']     = 0;
                    $dataArray['escalated']= 0;

                    $statusQry = mysql_query("SELECT * FROM sptbl_lookup WHERE vLookUpName LIKE 'ExtraStatus'");
                    if(mysql_num_rows($statusQry) <> 0) {
                        while($statusRes = mysql_fetch_array($statusQry)) {
                            $dataArray[$statusRes['vLookUpValue']] = 0;
                        }
                    }

                    if(mysql_num_rows($resTicket)>0) {
                        while($rowTicket=mysql_fetch_array($resTicket)) {

                            if(isset($dataArray[$rowTicket['vStatus']])) {
                                $dataArray[$rowTicket['vStatus']] = $rowTicket['count'];
                            }
                        }

                        ?>
    <tr class="listingmaintext">
        <td colspan="2" align="center" style="padding:2;">
                                <?php
                                $count = 0;
                                $subDeptval =   0;
                                foreach($dataArray AS $key=>$value) {
                                    $arrData[$count][0] = $key;
                                    $arrData[$count][1] = $value;
                                    $ticketTotal        = $ticketTotal+$value;
                                    $subDeptval         = $$subDeptval+$value;
                                    $count++;
                                }

                                $graphObj3 = new graph(8);
                                $graphObj3->setChartParams(TEXT_TCKET_STATISTICS, 1, 1, 0);
                                $graphObj3->addChartData($arrData);
                                $graphObj3->renderChart();?>
        </td></tr>
    <tr class="listingmaintext"><td colspan=2><?php echo 'Total number of tickets under -'.'<b>'.$rowCDept['vDeptDesc'].'</b>' ;?>&nbsp;:&nbsp;<b><span><?php echo $subDeptval;?></span></b></td></tr>
                        <?php
                    }
                    else {?>
    <tr class="listingmaintext"><td colspan="2" align="center"><?php echo NO_TICKETS ;?></td></tr>
                        <?php
                    }
 }
 ?>
    
    <tr class="listingmaintext"><td colspan=2><?php echo 'Total number of tickets under '.'<b>'.$parentDept.'</b>' ;?>&nbsp;:&nbsp;<b><span><?php echo $ticketTotal;?></span></b></td></tr>
    <?php
 
             ?>
</table>
</td></tr>
   <?php        
                    //------------------
                }
             }
            }
            else {
                ?>
    <tr>
        <td colspan="2" align="center">
                        <?php echo NO_DEPT ;?>
        </td>
    </tr>
                <?php
            }

    ?>
</table>


