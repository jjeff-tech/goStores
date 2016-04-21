<?php
$fld_prio = $_SESSION["sess_priority"];
$var_userid = $_SESSION["sess_userid"];

if ($_GET["mt"] == "y") {
    $var_numBegin = $_GET["numBegin"];
    $var_start = $_GET["start"];
    $var_begin = $_GET["begin"];
    $var_num = $_GET["num"];
    $var_type = $_GET["tp"];
    $var_cmbsearch = $_GET["cmbSearch"];
    $var_txtsearch = trim($_GET["txtSearch"]);

    $var_stylename = $_GET["stylename"];
    $var_styleminus = $_GET["styleminus"];
    $var_styleplus = $_GET["styleplus"];
} elseif ($_POST["mt"] == "y") {
    $var_numBegin = $_POST["numBegin"];
    $var_start = $_POST["start"];
    $var_begin = $_POST["begin"];
    $var_num = $_POST["num"];
    $var_type = $_POST["tp"];
    $var_cmbsearch = $_POST["cmbSearch"];
    $var_txtsearch = trim($_POST["txtSearch"]);

    $var_stylename = $_POST["stylename"];
    $var_styleminus = $_POST["styleminus"];
    $var_styleplus = $_POST["styleplus"];
}
$var_cmbsearch = ($var_cmbsearch == "") ? "refno" : $var_cmbsearch;

$sql = "Select * from sptbl_tickets where vDelStatus='0' AND nUserId='$var_userid' ";
if ($var_type == "o") {
    $sql .= " AND vStatus='open' ";
} elseif ($var_type == "c") {
    $sql .= " AND vStatus='closed' ";
} elseif ($var_type == "e") {
    $sql .= " AND vStatus='escalated' ";
} elseif ($var_type == "a") {
    $sql .= " ";
} else {
    $sql .= " AND vStatus='" . $var_type . "' ";
}

$qryopt = "";
if (trim($var_txtsearch) != "") {
    switch ($var_cmbsearch) {
        case "refno":
            $qryopt = " AND vRefNo Like '" . addslashes($var_txtsearch) . "%'";
            break;
        case "title":
            $qryopt = " AND vTitle Like '" . addslashes($var_txtsearch) . "%'";
            break;
        case "prio":
            $checkprioflag = false;
            for ($j = 0; $j < count($fld_prio); $j++) {
                //echo($fld_prio[$j][0] . " and " . $row[($fld_arr[$i][0])] . " and " . $fld_prio[$j][2]);
                if (strcasecmp($fld_prio[$j][2], $var_txtsearch) == 0) {
                    $checkprioflag = true;
                    $qryopt = " AND vPriority Like '" . $fld_prio[$j][0] . "'";
                    break;
                }
            }
            if ($checkprioflag == false) {
                $qryopt = " AND vPriority Like ''";
            }
            break;
        case "stat":
            $qryopt = " AND vStatus Like '" . addslashes($var_txtsearch) . "%'";
            break;
        case "dt":
            //$arr_date = explode("-",$var_txtsearch);
            //$var_date = $arr_date[2] . "-" . $arr_date[0] . "-" . $arr_date[1];
            $qryopt = " AND date_format(dPostDate,'%m-%d-%Y %H:%i:%s') Like '" . addslashes($var_txtsearch) . "%'";
            break;
    }
}
/*
 * sort fuction
 */

if($_GET["sorttype"]=="ASC"){
    $sort_type      = "ASC";
    $img_path       = "<img src='images/s_asc.png'>";
    $var_sorttype   = "DESC";
}else if($_GET["sorttype"]=="DESC"){
    $sort_type      = "DESC";
    $img_path       = "<img src='images/s_desc.png'>";
    $var_sorttype   = "ASC";
}
else {
    $sort_type  = $defaultSortOrder;
    if($defaultSortOrder == "ASC"){
        $var_sorttype   = "DESC";
        $var_filename   = "s_desc.png";
    }else {
        $var_sorttype   = "ASC";
        $var_filename   = "s_asc.png";
    }
    
}

if($_GET['val']){
    $sort_field =   $_GET['val'];
    $sql .= $qryopt . " Order By $sort_field $sort_type ";
}else{
   // $sql .= $qryopt . " Order By dPostDate $sort_type ";
    $sql .= $qryopt . " Order By  nTicketId $sort_type ";

}

if($sort_field == "vRefNo"){
     $r_img_path    = $img_path;
}else if($sort_field == "vTitle"){
     $t_img_path    = $img_path;
}else if($sort_field == "vStatus"){
     $s_img_path    = $img_path;
}else if($sort_field == "dPostDate"){
     $d_img_path    = $img_path;
}

//echo $sql ;
?>

<script type="text/javascript">

    $(document).ready(function(){

        var tab = '<?php echo $_GET['tp']; ?>'

        if(tab == 'o'){

            $('#openticket').attr('class','selected');
            $('#closedticket').attr('class','');
            $('#allticket').attr('class','');

        }
        else if (tab == 'c'){

            $('#closedticket').attr('class','selected');
            $('#openticket').attr('class','');
            $('#allticket').attr('class','');

        }
        else if (tab == 'a'){

            $('#allticket').attr('class','selected');
            $('#openticket').attr('class','');
            $('#closedticket').attr('class','');

        }
        else
        {
            $('#'+tab).attr('class','selected');
            $('#openticket').attr('class','');
            $('#closedticket').attr('class','');
            $('#allticket').attr('class','');
        }

    });

</script>

<form name="frmDetail" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">

    <!-- Tab section-->
    <!--<div class="content_tab_container">
            <ul>
                <li><a id="openticket" class="selected" href="tickets.php?mt=y&tp=o&stylename=VIEWTICKETS&styleplus=oneplus&styleminus=oneminus&" ><?php echo TEXT_OPEN_TICKETS ?></a></li>
                    <li><a id="closedticket" href="tickets.php?mt=y&tp=c&stylename=VIEWTICKETS&styleplus=oneplus&styleminus=oneminus&" ><?php echo TEXT_CLOSED_TICKETS ?></a></li>
<?php if ($var_statusRow > 0) { ?>
    <?php
    // Include  Additional Ticket Status Links Modified By Asha On 26-09-2012
    if ($var_statusRow > 0) {

        while ($tRow = mysql_fetch_array($rsExtraStat)) {
            $status = $tRow['vLookUpValue'];
            ?>
                                                <li><a id="<?php echo $status; ?>" href="tickets.php?mt=y&tp=<?php echo $status; ?>&stylename=VIEWTICKETS&styleplus=oneplus&styleminus=oneminus&" ><?php echo $status; ?></a></li>
                <?php
            }
        }
        // End Include Extra Links for Ticket Status
        ?>
    <?php } ?>
                    <li><a id="allticket" href="tickets.php?mt=y&tp=a&stylename=VIEWTICKETS&styleplus=oneplus&styleminus=oneminus&" ><?php echo TEXT_VIEW_ALL_TICKETS ?></a></li>
                    <li><a href="search.php?mt=y&<?php echo ("stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus&"); ?>" class="titleticket"><?php echo TEXT_SEARCH_BY_TICKET_ID ?></a></li>
    
            </ul>
    </div>-->
    <!-- Tab section ends-->

    <!-- end of top links -->
    <div class="content_section">

        <div class="content_section_title">
            <h4><?php
    if ($var_type == "o") {
        echo HEADING_OPEN_TICKETS;
    } elseif ($var_type == "c") {
        echo HEADING_CLOSED_TICKETS;
    } elseif ($var_type == "e") {
        echo HEADING_ESCALATED_TICKETS;
    } elseif ($var_type == "a") {
        echo HEADING_ALL_TICKETS;
    } else {
        echo $var_type . " Tickets";
    }
    ?>
            </h4></div>

        <div class="content_search_container">
            <div class="left rightmargin topmargin">
                <?php echo TEXT_SEARCH ?>
            </div>

            <div class="left rightmargin">
                <select name="cmbSearch" class="selectstyle" onchange='setCal(this.value)'>
                    <option value="refno"><?php echo(TEXT_REFNO); ?></option>
                    <option value="title"><?php echo(TEXT_TITLE); ?></option>
                    <option value="prio"><?php echo(TEXT_PRIORITY); ?></option>
                    <option value="stat"><?php echo(TEXT_STATUS); ?></option>
                    <option value="dt"><?php echo(TEXT_DATE); ?>(mm-dd-yyyy)</option>
                </select>
            </div>
            <div class="left">
                <input type="text" name="txtSearch" id="txtSearch" value="<?php echo htmlentities($var_txtsearch); ?>" class="inputstyle">
            </div>

            <div class="left">
                <a href="javascript:clickSearch();">&nbsp;<img src="./languages/<?php echo $_SESSION['sess_language']; ?>/images/go.gif" border="0"></a>
            </div>

            <div class="clear"></div>
        </div>
        <?php
        //$totalrows = mysql_num_rows(mysql_query($sql,$conn));
                    $totalrows = mysql_num_rows(executeSelect($sql, $conn));
                    settype($totalrows, integer);
                    settype($var_begin, integer);
                    settype($var_num, integer);
                    settype($var_numBegin, integer);
                    settype($var_start, integer);

                    $var_calc_begin = ($var_begin == 0) ? $var_start : $var_begin;
                    if (($totalrows <= $var_calc_begin)) {
                        $var_nor = 10;
                        $var_nol = 10;
                        if ($var_num > $var_numBegin) {
                            $var_num = $var_num - 1;
                            $var_numBegin = $var_numBegin;
                            $var_begin = $var_begin - $var_nor;
                        } elseif ($var_num == $var_numBegin) {
                            $var_num = $var_num - 1;
                            $var_numBegin = $var_numBegin - $var_nol;
                            $var_begin = $var_calc_begin - $var_nor;
                            $var_start = "";
                        }
                    }
                    if ($var_begin < 0)
                        $var_begin = 0;
//echo("$totalrows,2,2,\"&ddlSearchType=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&id=$var_batchid&\",$var_numBegin,$var_start,$var_begin,$var_num");
                    $navigate = pageBrowser($totalrows, 10, 10, "&val=$sort_field&sorttype=$sort_type&mt=y&tp=$var_type&cmbDepartment=$var_deptid&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus&cmbSearch=" . urlencode($var_cmbsearch) . "&txtSearch=" . urlencode($var_txtsearch) . "&", $var_numBegin, $var_start, $var_begin, $var_num);

//execute the new query with the appended SQL bit returned by the function
                    $sql = $sql . $navigate[0];
//echo "sql==$sql";
//echo $sql;
//echo "<br>".time();
//$rs = mysql_query($sql,$conn);
                    $rs = executeSelect($sql, $conn);
                    ?>
        <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="list_tbl">

            <tr align="left" >
                <th width="16%">
                    <?php echo "<a href='?val=vRefNo&sorttype=".$var_sorttype."&tp=".$var_type."&numBegin=$var_numBegin&start=$var_begin&begin=$var_begin&num=$var_num&mt=y&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus' >".TEXT_REFNO."</a>&nbsp;&nbsp;".$r_img_path; ?>
                </th>
                <th colspan="2">
                    <?php echo "<a href='?val=vTitle&sorttype=".$var_sorttype."&tp=".$var_type."&numBegin=$var_numBegin&start=$var_begin&begin=$var_begin&num=$var_num&mt=y&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus' >".TEXT_TITLE."</a>&nbsp;&nbsp;".$t_img_path; ?>
<?php //echo (TEXT_TITLE); ?>
                </th>
                <th width="16%">
<?php echo (TEXT_PRIORITY); ?>
                </th>
                <th width="16%">
                    <?php //echo (TEXT_STATUS); ?>
                    <?php echo "<a href='?val=vStatus&sorttype=".$var_sorttype."&tp=".$var_type."&numBegin=$var_numBegin&start=$var_begin&begin=$var_begin&num=$var_num&mt=y&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus' >".TEXT_STATUS."</a>&nbsp;&nbsp;".$s_img_path; ?>
                </th>
                <th width="20%">
                    <?php //echo (TEXT_DATE); ?>
                    <?php echo "<a href='?val=dPostDate&sorttype=".$var_sorttype."&tp=".$var_type."&numBegin=$var_numBegin&start=$var_begin&begin=$var_begin&num=$var_num&mt=y&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus' >".TEXT_DATE."</a>&nbsp;&nbsp;".$d_img_path; ?>
                </th>
            </tr>
                    <?php

                    while ($row = mysql_fetch_array($rs)) {
                        ?>

                <tr align="left"  class="whitebasic">
                    <td width="16%" >
                <?php echo("<a href=\"viewticket.php?mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=" . $var_stylename . "&styleminus=" . $var_styleminus . "&styleplus=" . $var_styleplus . "&\" class=\"listing\">" . $row["vRefNo"] . "</a>"); ?>
                    </td>
                    <td style="word-break:break-all;" colspan="2">
                <?php echo("<a href=\"viewticket.php?mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=" . $var_stylename . "&styleminus=" . $var_styleminus . "&styleplus=" . $var_styleplus . "&\" class=\"listing\">" . htmlentities($row["vTitle"]) . "</a>"); ?>
                    <td width="16%" class="subtbl">
                <?php
                //taking priority feachers

                $sql = "Select * from sptbl_priorities where nPriorityValue='" . addslashes($row["vPriority"]) . "'";
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

                ////taking priority feachers ends
                for ($j = 0; $j < count($fld_prio); $j++) {
                    //echo($fld_prio[$j][0] . " and " . $row[($fld_arr[$i][0])] . " and " . $fld_prio[$j][2]);

                    if ($fld_prio[$j][0] == $row["vPriority"]) {
                        echo ("<table width=\"100%\" cellpadding=\"0\" border=\"0\" class='innertable1' style='background-color:$ticketColor;'><tr><td class='user_priority_icon'  align='left'><img src='$filePath'></td><td align='left'><a href=\"viewticket.php?mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=" . $var_stylename . "&styleminus=" . $var_styleminus . "&styleplus=" . $var_styleplus . "&\" >" . $fld_prio[$j][2] . "</a></td></tr></table>");
                    }
                }
                ?>
                    </td>
                    <td width="16%"> 
                        <?php echo("<a href=\"viewticket.php?mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=" . $var_stylename . "&styleminus=" . $var_styleminus . "&styleplus=" . $var_styleplus . "&\" class=\"listing\">" . htmlentities($row["vStatus"]) . "</a>"); ?>
                    </td>
                    <td width="20%">
                        <?php echo("<a href=\"viewticket.php?mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=" . $var_stylename . "&styleminus=" . $var_styleminus . "&styleplus=" . $var_styleplus . "&\" class=\"listing\">" . date("m-d-Y", strtotime($row["dPostDate"])) . "</a>"); ?>
                    </td>                                                                                                  
                </tr>
                        <?php
                    }
                    mysql_free_result($rs);
                    ?>
            <tr align="left">
                <td colspan="6">

                    <div class="pagination_container">
                        <div class="pagination_info">
                    <?php echo($navigate[1] . "&nbsp;" . TEXT_OF . "&nbsp;" . $totalrows . "&nbsp;" . TEXT_RESULTS ); ?>
                        </div>
                        <div class="pagination_links">
            <?php echo($navigate[2]); ?>
                            <input type="hidden" name="numBegin" value="<?php echo $var_numBegin ?>">
                            <input type="hidden" name="start" value="<?php echo $var_start ?>">
                            <input type="hidden" name="begin" value="<?php echo $var_begin ?>">
                            <input type="hidden" name="num" value="<?php echo $var_num ?>">
                            <input type="hidden" name="mt" value="y">
                            <input type="hidden" name="tp" value="<?php echo($var_type); ?>">
                            <input type="hidden" name="postback" value="">
                            <input type="hidden" name="id" value="">
                            <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
                            <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                            <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
                        </div>

                    </div>  
                </td>
            </tr>
        </table>




    </div>

</form>
<script>
    var val = '<?php echo($var_cmbsearch); ?>';
    document.frmDetail.cmbSearch.value=val;
</script>