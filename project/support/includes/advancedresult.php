<?php
$fld_prio = $_SESSION["sess_priority"];
$var_userid = $_SESSION["sess_userid"];

if($_GET["mt"] == "y") {
    $var_numBegin = $_GET["numBegin"];
    $var_start = $_GET["start"];
    $var_begin = $_GET["begin"];
    $var_num = $_GET["num"];
    $var_refno = $_GET["rf"];
    $var_title = trim($_GET["ttl"]);
    $var_priority = trim($_GET["pr"]);
    $var_status = trim($_GET["st"]);
    $var_from = $_GET["frm"];
    $var_to = $_GET["to"];
    $var_stylename = $_GET["stylename"];
    $var_styleminus = $_GET["styleminus"];
    $var_styleplus = $_GET["styleplus"];
}
elseif($_POST["mt"] == "y") {
    $var_numBegin = $_POST["numBegin"];
    $var_start = $_POST["start"];
    $var_begin = $_POST["begin"];
    $var_num = $_POST["num"];
    $var_refno = $_POST["txtRefno"];
    $var_title = trim($_POST["txtTitle"]);
    $var_priority = trim($_POST["cmbPriority"]);
    $var_status = trim($_POST["cmbStatus"]);
    $var_from = $_POST["txtFrom"];
    $var_to = $_POST["txtTo"];
    $var_stylename = $_POST["stylename"];
    $var_styleminus = $_POST["styleminus"];
    $var_styleplus = $_POST["styleplus"];
}

/*
//Block - I (populate the allowed departments for the user)
	$lst_dept = "'',";
	$sql = "Select nDeptId from sptbl_staffdept where nStaffId='$var_staffid'";
	$rs_dept = executeSelect($sql,$conn);
	if (mysql_num_rows($rs_dept) > 0) {
		while($row = mysql_fetch_array($rs_dept)) {
			$lst_dept .= $row["nDeptId"] . ",";
		}
	}
	$lst_dept = substr($lst_dept,0,-1);
	
	mysql_free_result($rs_dept);
//End Of Block - I
*/

$sql = "Select t.nTicketId,t.nUserId,d.vDeptDesc,t.vRefNo,t.vUserName,t.vTitle,t.tQuestion,t.vPriority,
		t.dPostDate,t.vStatus,s.vStaffname as 'vOwner',t.nLockStatus from 
		sptbl_tickets t inner join sptbl_depts d on t.nDeptId = d.nDeptId left outer join 
		sptbl_staffs s on t.nOwner = s.nStaffId  WHERE t.vDelStatus='0' AND t.nUserId='$var_userid' ";

$qryopt="";
if ($var_refno != "") {
    $qryopt .= " AND t.vRefNo Like '%" . mysql_real_escape_string($var_refno) . "%' ";
}
if ($var_title != "") {
    $qryopt .= " AND t.vTitle Like '%" . mysql_real_escape_string($var_title) . "%' ";
}
if ($var_status != "") {
    $qryopt .= " AND t.vStatus ='" . mysql_real_escape_string($var_status) . "' ";
}
if ($var_priority != "") {
    $var_prselect=$var_priority;
    /*	for($j=0;$j < count($fld_prio);$j++) {
		if ($fld_prio[$j][2] == $var_priority) {
				$var_prselect = $fld_prio[$j][0];
			break;
		}
	}
    */
    //$qryopt .= " AND t.vPriority ='" . mysql_real_escape_string($var_prselect) . "' ";
}
if ($var_from != "") {
    $arr_alert = explode("/",$var_from);
    $arr_year = explode(" ",$arr_alert[2]);
    $arr_tm = explode(":",$arr_year[1]);
    $var_time1 = $arr_year[0] ."-" . $arr_alert[0] . "-" . $arr_alert[1] . " " . $arr_tm[0] . ":" . $arr_tm[1];


    $qryopt .= " AND t.dPostDate >='" . mysql_real_escape_string($var_time1) . "' ";
}
if ($var_to != "") {
    $arr_alert = explode("/",$var_to);
    $arr_year = explode(" ",$arr_alert[2]);
    $arr_tm = explode(":",$arr_year[1]);
    $var_time2 = $arr_year[0] ."-" . $arr_alert[0] . "-" . $arr_alert[1] . " " . $arr_tm[0] . ":" . $arr_tm[1];


    $qryopt .= " AND t.dPostDate <='" . mysql_real_escape_string($var_time2) . "' ";
}

$sql .= $qryopt . " Order By t.dPostDate DESC";


?>

<div class="content_section">

    <form name="frmDetail" action="<?php echo($_SERVER["REQUEST_URI"]); ?>" method="post">
        <div class="content_section_title"><h4><?php echo HEADING_ADVANCED_RESULT ?></h4></div>


        <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="list_tbl" >
            <tr >
                <th width="25%" align="left">
                    <?php echo (TEXT_REFNO); ?>
                </th>
                <th width="35%" align="left">
                    <?php echo (TEXT_TITLE); ?>
                </th>
                <th width="15%" align="left">
                    <?php echo (TEXT_PRIORITY); ?>
                </th>
                <th width="10%" align="left">
                    <?php echo (TEXT_STATUS); ?>
                </th>
                <th width="15%" align="left">
                    <?php echo (TEXT_DATE); ?>
                </th>
            </tr>
            <?php

//$totalrows = mysql_num_rows(mysql_query($sql,$conn));
            $totalrows = mysql_num_rows(executeSelect($sql,$conn));
            settype($totalrows,integer);
            settype($var_begin,integer);
            settype($var_num,integer);
            settype($var_numBegin,integer);
            settype($var_start,integer);

            $var_calc_begin = ($var_begin == 0)?$var_start:$var_begin;
            if(($totalrows <= $var_calc_begin)) {
                $var_nor = 10;
                $var_nol = 10;
                if($var_num > $var_numBegin) {
                    $var_num = $var_num - 1;
                    $var_numBegin = $var_numBegin;
                    $var_begin = $var_begin - $var_nor;
                }
                elseif($var_num == $var_numBegin) {
                    $var_num = $var_num - 1;
                    $var_numBegin = $var_numBegin - $var_nol;
                    $var_begin = $var_calc_begin - $var_nor;
                    $var_start="";
                }
            }

//echo("$totalrows,2,2,\"&ddlSearchType=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&id=$var_batchid&\",$var_numBegin,$var_start,$var_begin,$var_num");
            $navigate = pageBrowser($totalrows,10,10,"&mt=y&rf=" . urlencode($var_refno) . "&ttl=". urlencode($var_title) . "&st=" . urlencode($var_status) . "&pr=" . urlencode($var_priority) .  "&frm=" . urlencode($var_from) . "&to=" . urlencode($var_to) . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus&",$var_numBegin,$var_start,$var_begin,$var_num);

//execute the new query with the appended SQL bit returned by the function
            $sql = $sql.$navigate[0];
//echo "sql==$sql";
//echo $sql;
//echo "<br>".time();
//$rs = mysql_query($sql,$conn);
            $rs = executeSelect($sql,$conn);
            while($row = mysql_fetch_array($rs)) {
                ?>
            <tr align="left"  class="whitebasic">
                <td>
                        <?php echo("<a href=\"viewticket.php?mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=" . $var_stylename . "&styleminus=" . $var_styleminus . "&styleplus=" . $var_styleplus . "&\" class=\"listing\">" . $row["vRefNo"] . "</a>"); ?>
                </td>
                <td width="35%" style="word-break:break-all;" >
                        <?php echo("<a href=\"viewticket.php?mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=" . $var_stylename . "&styleminus=" . $var_styleminus . "&styleplus=" . $var_styleplus . "&\" class=\"listing\">" . htmlentities($row["vTitle"]) . "</a>");
                        $count=getTicketCount($row["nTicketId"]);
                        if($count>1)
                            echo "&nbsp;($count)";
                        ?>
                </td>
                <td class="subtbl">
                        <?php
                        $sql = "Select * from sptbl_priorities where nPriorityValue='" . mysql_real_escape_string($row["vPriority"]) . "'";
                        $res_prior_feacher = executeSelect($sql, $conn);
                        if (mysql_num_rows($res_prior_feacher) > 0) {
                            $row_prior_feacher = mysql_fetch_array($res_prior_feacher);
                            $ticketColor = $row_prior_feacher["vTicketColor"];
                            $prioritieIcon = $row_prior_feacher["vPrioritie_icon"];
                        }

                        if ($prioritieIcon != "") {

                            $filePath = "./ticketPriorLogo/" . $prioritieIcon;
                        } else {
                            $filePath = "./ticketPriorLogo/noicon.jpg";
                        }
                        for($j=0;$j < count($fld_prio);$j++) {
                            //echo($fld_prio[$j][0] . " and " . $row[($fld_arr[$i][0])] . " and " . $fld_prio[$j][2]);
                            if ($fld_prio[$j][0] == $row["vPriority"]) {
                                echo ("<table width=\"100%\" cellpadding=\"0\" border=\"0\" class='innertable1' style='background-color:$ticketColor;'><tr><td class='user_priority_icon'  align='left'><img src='$filePath'></td><td bgcolor=" . $fld_prio[$j][1] . " align='center'><a href=\"viewticket.php?mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=" . $var_stylename . "&styleminus=" . $var_styleminus . "&styleplus=" . $var_styleplus . "&\" class=\"\">" . $fld_prio[$j][2] . "</a></td></tr></table>");
                            }
                        }
                        ?>
                </td>
                <td >
                        <?php echo("<a href=\"viewticket.php?mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=" . $var_stylename . "&styleminus=" . $var_styleminus . "&styleplus=" . $var_styleplus . "&\" class=\"listing\">" . htmlentities($row["vStatus"]) . "</a>"); ?>
                </td>
                <td >
                        <?php echo("<a href=\"viewticket.php?mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=" . $var_stylename . "&styleminus=" . $var_styleminus . "&styleplus=" . $var_styleplus . "&\" class=\"listing\">" . date("m/d/Y",strtotime($row["dPostDate"])) . "</a>"); ?>
                </td>
            </tr>
                <?php

            }
            mysql_free_result($rs);
            ?>
            <tr align="left"  class="whitebasic">
                <td colspan="6">

                    <div class="pagination_container">
                        <div class="pagination_info">

                            <?php echo($navigate[1] ."&nbsp;".TEXT_OF."&nbsp;".$totalrows."&nbsp;" .TEXT_RESULTS ); ?>
                        </div>
                        <div class="pagination_links">
                            <?php
                                if($totalrows > 0){
                                echo($navigate[2]);
                                }
                            ?>
                            <input type="hidden" name="numBegin" value="<?php echo   $var_numBegin?>">
                            <input type="hidden" name="start" value="<?php echo   $var_start?>">
                            <input type="hidden" name="begin" value="<?php echo   $var_begin?>">
                            <input type="hidden" name="num" value="<?php echo   $var_num?>">
                            <input type="hidden" name="mt" value="y">
                            <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
                            <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                            <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
                            <input type="hidden" name="postback" value="">
                        </div>
                        <div class="clear"></div>
                    </div>
                </td>
            </tr>
        </table>


    </form>
</div>

<script>
    <!--
    var refno = '<?php echo mysql_real_escape_string($var_refno); ?>';
    var ttl = '<?php echo  mysql_real_escape_string($var_title); ?>';
    var status = '<?php echo mysql_real_escape_string($var_status); ?>';
    var prio = '<?php echo mysql_real_escape_string($var_priority); ?>';
    var from = '<?php echo($var_from); ?>';
    var to = '<?php echo($var_to); ?>';
    -->
</script>
