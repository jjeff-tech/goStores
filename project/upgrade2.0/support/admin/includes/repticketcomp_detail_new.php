<!--- report detail-->
<?php
include('../fusionchart/Class/FusionCharts.php');
include('../fusionchart/graph/graph.php');

$var_msdate= datetimetomysql($var_sdate);
$var_medate =datetimetomysql($var_edate);
$var_sdate_disp 	= dateFormat($var_sdate,"m-d-Y","M, d Y");
$var_edate_disp 	= dateFormat($var_edate,"m-d-Y","M, d Y");

?>
<div class="content_section_title"><h3><?php echo TEXT_LBL1."&nbsp;".$var_sdate_disp."&nbsp;".TEXT_LBL2."&nbsp;".$var_edate_disp."";?></h3></div>
<table width="100%" border="0"  cellpadding="0" cellspacing="0" class="list_tbl">
   
    <?php
    $sqlCompany ="SELECT nCompId,vCompName FROM sptbl_companies WHERE vDelStatus =0 ";
    if($var_companyid >0) {
        $sqlCompany .=" AND nCompId='".addslashes($var_companyid)."' ";
    }
    $sqlCompany .= " ORDER BY vCompName";

    $resCompany = executeSelect($sqlCompany,$conn);
    if(mysql_num_rows($resCompany) <> 0) {
        while($rowCompany=mysql_fetch_array($resCompany)) {?>
    <tr >
        <td colspan=2><?php echo TXT_COMPANY_NAME ;?>&nbsp;:&nbsp;<?php echo htmlentities($rowCompany['vCompName']);?></td>
    </tr>
            <?php
            $sqlDept="SELECT * FROM sptbl_depts WHERE nCompId='".$rowCompany['nCompId']."'";
            $resDept = executeSelect($sqlDept,$conn);
            if(mysql_num_rows($resDept)>0) {
                while($rowDept=mysql_fetch_array($resDept)) {?>
    <tr class="commentband1">
        <td colspan=2><?php echo TXT_DEPT_NAME ;?>&nbsp;:&nbsp;<b><span><?php echo htmlentities($rowDept['vDeptDesc']);?></span></b></td>
    </tr>

                    <?php
                    $sqlTicket =" SELECT count(vStatus) AS count,vStatus FROM  sptbl_tickets WHERE nDeptId='".$rowDept['nDeptId']."'
                                  AND dPostDate >='".addslashes($var_msdate)."' AND dPostDate<='". addslashes($var_medate)."'
                                  GROUP BY vStatus";
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
                                foreach($dataArray AS $key=>$value) {
                                    $arrData[$count][0] = $key;
                                    $arrData[$count][1] = $value;

                                    $count++;
                                }

                                $graphObj3 = new graph(8);
                                $graphObj3->setChartParams(TEXT_TCKET_STATISTICS, 1, 1, 0);
                                $graphObj3->addChartData($arrData);
                                $graphObj3->renderChart();?>
        </td></tr>
                        <?php
                    }
                    else {?>
    <tr class="listingmaintext"><td colspan="2" align="center"><?php echo NO_TICKETS ;?></td></tr>
                        <?php
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
        }
    }

    ?>
</table>


