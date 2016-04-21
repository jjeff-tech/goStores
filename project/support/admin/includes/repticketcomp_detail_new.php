<!--- report detail-->
<?php
$var_msdate= datetimetomysql($var_sdate,"/");
$var_medate =datetimetomysql($var_edate,"/");

$sql = "Select c.vCompName,d.vDeptDesc,d.nCompId,t.nDeptId,t.vStatus,Count(t.vStatus) as 'count' from 
								sptbl_tickets t inner join sptbl_depts  d on t.nDeptId = d.nDeptId
								inner join sptbl_companies c on d.nCompId = c.nCompId";

if($var_companyid >0) {
    $sql .= " Where  d.nCompId='".addslashes($var_companyid)."' ";
    $sql .=" AND t.dPostDate >='".addslashes($var_msdate)."'";
    $sql .=" AND t.dPostDate<='". addslashes($var_medate)."' ";
}
else {
    $sql .=" Where t.dPostDate >='".addslashes($var_msdate)."'";
    $sql .=" AND t.dPostDate<='". addslashes($var_medate)."' ";
}
$sql .= "group by d.nCompId,t.nDeptId,t.vStatus ";
$sql .="  order by c.vCompName asc,d.vDeptDesc,t.vStatus asc ";


$rs = executeSelect($sql,$conn); 
?>
<?php  if(mysql_num_rows($rs)>0) { ?>
<tr>
    <td  colspan="5" align="center"><a href="javascript:printSpecial();"><?php echo   TEXT_PRINT?></a></td>
</tr>
    <?php } ?>
<tr><td width="100%">
        <div id="printReady">
            <table width="100%" border=0 class="column1" cellpadding="2" cellspacing="1">
                <tr>
                    <td  colspan="5" align="center" class="whitebasic"><?php echo TEXT_LBL1."&nbsp;<b>".$var_sdate."</b>&nbsp;".TEXT_LBL2."&nbsp;<b>".$var_edate."</b>";?></td>
                </tr>
                <tr class="listing"><td colspan="3" align=center>&nbsp;</td></tr>

                <?php
                /*
						  $cur_cmp_name="";
						  $cur_dept_name="";
						  $cur_status="";
						  $cnt=0;
						  $statuscnt=0;
						  $cut_status_cnt=0;
                */
                if(mysql_num_rows($rs)>0) {
                    $row = mysql_fetch_array($rs);
                    $var_compid = 0;
                    $var_deptid = 0;
                    do {
                        if($var_compid == $row["nCompId"]) {
                            if($var_deptid == $row["nDeptId"]) {
                                echo("<tr class=\"listing\"><td width=\"50%\">&nbsp;</td><td>" . $row["vStatus"] . "</td><td>&nbsp;&nbsp;&nbsp;" . $row["count"] . "</td></tr>");
                            }
                            else {
                                $var_deptid = $row["nDeptId"];
                                echo("<tr class=\"listing\"><td colspan=3>" . TXT_DEPT_NAME  . "&nbsp;:" . htmlentities($row["vDeptDesc"]). "</td></tr>");
                                echo("<tr class=\"listing\"><td colspan=3>&nbsp;</td></tr>");
                                echo("<tr class=\"listing\"><td width=\"50%\">&nbsp;</td><td>" . $row["vStatus"] . "</td><td>&nbsp;&nbsp;&nbsp;" . $row["count"] . "</td></tr>");
                            }
                        }
                        else {
                            $var_compid = $row["nCompId"];
                            $var_deptid = $row["nDeptId"];
                            echo("<tr class=\"listing\"><td colspan=3>" . TXT_COMPANY_NAME . "&nbsp;:" . htmlentities($row["vCompName"]) . "</td></tr>");
                            echo("<tr class=\"listing\"><td colspan=3>" . TXT_DEPT_NAME  . "&nbsp;:" . htmlentities($row["vDeptDesc"]). "</td></tr>");
                            echo("<tr class=\"listing\"><td width=\"50%\">&nbsp;</td><td>" . $row["vStatus"] . "</td><td>&nbsp;&nbsp;&nbsp;" . $row["count"] . "</td></tr>");
                        }
                    }while($row = mysql_fetch_array($rs));
                } else {
                    echo("<tr class=listing><td>" . TEXT_NO_RECORDS . "</td></tr>");
                } ?>

            </table>
        </div>

        <?php  if(mysql_num_rows($rs)>0) { ?>
<tr>
    <td  colspan="5" align="center"><a href="javascript:printSpecial();"><?php echo   TEXT_PRINT?></a></td>
</tr>
    <?php } ?>