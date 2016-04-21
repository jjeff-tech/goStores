<?php
$flag_msg="";
$var_staffid = $_SESSION["sess_staffid"];
if ($_POST["postback"] == "D") {
    if (validateDeletion(mysql_real_escape_string($_POST["id"])) == true) {
        //Delete one company that is clicked on the icon
        $sql = "delete from sptbl_pvtmessages where nPMId = '" . mysql_real_escape_string($_POST["id"]) . "'";
        executeSelect($sql,$conn);

        //Insert the actionlog
        if(logActivity()) {
            $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','PrivateMessages','" . mysql_real_escape_string($_POST["id"]) . "',now())";
            executeQuery($sql,$conn);
        }

        $var_message = MESSAGE_RECORD_DELETED;
        $flag_msg="class='msg_success'";
    }
    else {
        $var_message = MESSAGE_RECORD_ERROR;
        $flag_msg="class='msg_error'";
    }
}
elseif ($_POST["postback"] == "DA") {
    $var_list = "";
    for($i=0;$i<count($_POST["chk"]);$i++) {
        $var_list .= "'" . mysql_real_escape_string($_POST["chk"][$i]) . "',";
    }
    $var_list = substr($var_list,0,-1);

    if (validateDeletion($var_list) == true) {
        //Delete one company that is clicked on the icon
        $sql = "delete from sptbl_pvtmessages where nPMId IN(" . $var_list . ")";
        executeQuery($sql,$conn);

        //Insert the actionlog
        if(logActivity()) {
            for($i=0;$i<count($_POST["chk"]);$i++) {
                $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','PrivateMessages','" . mysql_real_escape_string($_POST["chk"][$i]) . "',now())";
                executeQuery($sql,$conn);
            }
        }
        $var_message = MESSAGE_RECORD_DELETED;
        $flag_msg="class='msg_success'";
    }
    else {
        $var_message = MESSAGE_RECORD_ERROR;
        $flag_msg="class='msg_error'";
    }
}


function validateDeletion($var_list) {
    /*        global $conn;
        $sql = "Select nCompId from sptbl_depts where nCompId IN($var_list)";
        if (mysql_num_rows(executeSelect($sql,$conn)) > 0) {
                return false;
        }
        else {
                return true;
        }
    */
    return true;
}


if($_GET["mt"] == "y") {
    $var_numBegin = $_GET["numBegin"];
    $var_start = $_GET["start"];
    $var_begin = $_GET["begin"];
    $var_num = $_GET["num"];
    $var_styleminus = $_GET["styleminus"];
    $var_stylename = $_GET["stylename"];
    $var_styleplus = $_GET["styleplus"];
}
elseif($_POST["mt"] == "y") {
    $var_numBegin = $_POST["numBegin"];
    $var_start = $_POST["start"];
    $var_begin = $_POST["begin"];
    $var_num = $_POST["num"];
    $var_styleminus = $_POST["styleminus"];
    $var_stylename = $_POST["stylename"];
    $var_styleplus = $_POST["styleplus"];
}

$sql = "Select PM.nPMId,PM.vPMTitle,PM.dDate,PM.vStatus,S.vStaffName as 'FromName',S1.vStaffName as 'ToName'
                 from sptbl_pvtmessages PM inner join sptbl_staffs S on PM.nFrmStaffId = S.nStaffId inner join
                 sptbl_staffs S1 on PM.nToStaffId = S1.nStaffId WHERE PM.nToStaffId = '".$var_staffid."' ";

$qryopt="";
if($_POST["txtSearch"] != "") {
    $var_search = $_POST["txtSearch"];
}else if($_GET["txtSearch"] != "") {
    $var_search = $_GET["txtSearch"];
}

if($_POST["cmbSearch"] != "") {
    $var_cmbSearch = $_POST["cmbSearch"];
}else if($_GET["cmbSearch"] != "") {
    $var_cmbSearch = $_GET["cmbSearch"];
}

if($var_search != "") {
    if($var_cmbSearch == "fromname") {
        $qryopt .= " AND S.vStaffName like '" . mysql_real_escape_string($var_search) . "%'";
    }elseif($var_cmbSearch == "title") {
        $qryopt .= " AND PM.vPMTitle like '" . mysql_real_escape_string($var_search) . "%'";
    }elseif($var_cmbSearch == "toname") {
        $qryopt .= " AND S1.vStaffName like '" . mysql_real_escape_string($var_search) . "%'";
    }
}

//$var_back="companies.php?mt=ybegin=" . $var_begin . "&num=" . $var_num . "&numBegin=" . $var_numBegin . "&start=$var_start&cmbSearch=" . $var_cmbSearch . "&txtSearch=" . urlencode($var_search) . "&";
//$_SESSION["backurl"] = $sess_back;
$sql .= $qryopt . " Order By  PM.vStatus desc,PM.dDate DESC";


?>
<div class="content_section">
    <form name="frmPvtMessage" action="<?php echo($_SERVER["REQUEST_URI"]); ?>" method="post">

        <div class="content_section_title">
            <h3><?php echo HEADING_STAFF_PVTMESSAGE ?></h3>
        </div>


        <div class="content_search_container">
            <div class="left rightmargin topmargin">
                <?php echo(TEXT_SEARCH); ?>
            </div>

            <div class="left rightmargin">
                <select name="cmbSearch" class="comm_input input_width1">
                    <option value="fromname" <?php echo(($var_cmbSearch == "fromname" || $var_cmbSearch == "")?"Selected":""); ?>><?php echo TEXT_FROM ?></option>
                    <option value="title" <?php echo(($var_cmbSearch == "title")?"Selected":""); ?>><?php echo TEXT_TITLE ?></option>
                </select>
            </div>
            <div class="left">
                <input type="text" name="txtSearch" value="<?php echo(htmlentities($var_search)); ?>" class="comm_input11btxt" onKeyPress="if(window.event.keyCode == '13'){ return false; }" >
                </div>

            <div class="left">
                <a href="javascript:clickSearch();"><img src="./../languages/<?php echo $_SESSION['sess_language'];?>/images/go.gif" border="0"></a>
            </div>



            <div class="clear"></div>
        </div>





        <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="list_tbl">
            <tr align="left">
                <th align="left" valign="top" width="5%">&nbsp;</th>
                <th align="left" valign="top" width="31%"><?php echo TEXT_FROM; ?></th>
                <th align="left" valign="top" width="28%"><?php echo TEXT_TITLE; ?></th>
                <th align="left" valign="top" width="14%"><?php echo TEXT_DATE; ?></th>
                <th align="left" valign="top" width="9%"><?php echo TEXT_STATUS; ?></th>
                <th align="left" valign="top" colspan="2"><?php echo TEXT_ACTION; ?></th>
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
            $navigate = pageBrowser($totalrows,10,10,"&mt=y&cmbSearch=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&mt=y&stylename=STYLEPRIVATEMESSAGES&styleminus=minus2&styleplus=plus2&",$var_numBegin,$var_start,$var_begin,$var_num);

//execute the new query with the appended SQL bit returned by the function
            $sql = $sql.$navigate[0];
//echo "sql==$sql";
//echo $sql;
//echo "<br>".time();
//$rs = mysql_query($sql,$conn);
            $rs = executeSelect($sql,$conn);
            $cnt = 1;
            while($row = mysql_fetch_array($rs)) {

                ?>

            <tr align="left"  <?php echo (($row["vStatus"] == "o")?"class=\"whitebasic\"":"class=\"whitebasic\"") ?>>
                <td width="5%" align="center"><input type="checkbox" name="chk[]" id="c<?php echo($cnt); ?>" value="<?php echo($row["nPMId"]); ?>" class="checkbox">&nbsp;
                </td>
                <td><?PHP echo htmlentities($row["FromName"]); ?></td>
                <td width="28%"><a href="vwpvtmessage.php?id=<?php echo $row["nPMId"]; ?>&mt=y&stylename=STYLEPRIVATEMESSAGES&styleminus=minus2&styleplus=plus2&" class="listing"><?php echo htmlentities($row["vPMTitle"]); ?></a></td>
                <td width="14%"><?php echo date("m-d-Y",strtotime($row["dDate"])); ?></td>
                <td width="9%"><?php echo (($row["vStatus"] == "o")?"New":"Viewed") ?></td>
                <td width="6%"><a href="vwpvtmessage.php?id=<?php echo $row["nPMId"]; ?>&mt=y&stylename=STYLEPRIVATEMESSAGES&styleminus=minus2&styleplus=plus2&"><img src="././../images/view.gif" border="0" title="<?php echo(TITLE_VIEW_PVT_MESSAGE); ?>"></a></td>
                <td width="7%"><a href="javascript:deleted('<?php echo $row["nPMId"]; ?>');"><img src="././../images/delete.gif" width="13" height="13" border="0" title="<?php echo(TITLE_DELETE_PVT_MESSAGE); ?>"></a></td>
            </tr>
                <?php
                $cnt++;
            }
            mysql_free_result($rs);
            ?>
            <tr align="left"  class="whitebasic">
                <td colspan="7">

                    <div class="pagination_container">

                        <div class="pagination_info">
                        <?php
                        if($totalrows > 0){
                            echo($navigate[1] ."&nbsp;".TEXT_OF."&nbsp;".$totalrows."&nbsp;" .TEXT_RESULTS );
                        }
                        ?>
                        </div>
                        <div class="pagination_links">
                            <?php echo($navigate[2]); ?>
                            <input type="hidden" name="numBegin" value="<?php echo   $var_numBegin?>">
                            <input type="hidden" name="start" value="<?php echo   $var_start?>">
                            <input type="hidden" name="begin" value="<?php echo   $var_begin?>">
                            <input type="hidden" name="num" value="<?php echo   $var_num?>">
                            <input type="hidden" name="mt" value="y">
                            <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
                            <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                            <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
                            <input type="hidden" name="postback" value="">
                            <input type="hidden" name="id" value="">
                        </div>
                        <div class="clear"></div>
                    </div>








                </td>
            </tr>

            <tr>
                <td colspan="7" align="center">
                    <input name="btnDelete" type="button" class="comm_btn_greyad" value="<?php echo BUTTON_TEXT_DELETE; ?>" onClick="javascript:clickDelete();">
                </td>
            </tr>


        </table>



    </form>
</div>